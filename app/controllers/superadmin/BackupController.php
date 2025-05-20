<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;

/**
 * BackupController for Superadmin
 * Handles database backup and restore functionality
 */
final class BackupController extends AbstractController
{
    /**
     * Display database backup page
     */
    public function index(): void
    {
        // Get list of available backups
        $backupDir = BASE_DIR . '/backups';
        $backups = [];
        
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                $backups[] = [
                        'filename' => $file,
                        'size' => filesize($backupDir . '/' . $file),
                        'created_at' => filemtime($backupDir . '/' . $file) // Store as timestamp instead of formatted date
                    ];
                }
            }
            
            // Sort by created date descending
            usort($backups, function($a, $b) {
                return $b['created_at'] - $a['created_at'];
            });
        }
        
        // Get database statistics
        $dbStats = $this->getDatabaseStats();
        
        // Get backup settings
        $settings = [];
        $settingsData = Database::fetchAll("SELECT * FROM system_settings WHERE setting_key LIKE 'auto_backup%' OR setting_key LIKE 'backup_%'");
        foreach ($settingsData as $setting) {
            $settings[$setting['setting_key']] = $setting['value'];
        }
        
        $this->renderSuperAdmin('superadmin/database-backup', [
            'backups' => $backups,
            'dbStats' => $dbStats,
            'settings' => $settings,
            'pageTitle' => 'Database Backup & Restore'
        ]);
    }
    
    /**
     * Get database statistics for the backup page
     */
    private function getDatabaseStats(): array
    {
        $stats = [
            'size_mb' => 0,
            'tables' => 0,
            'records' => 0
        ];
        
        // Get database size
        $sizeQuery = "SELECT 
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
            FROM information_schema.TABLES 
            WHERE table_schema = DATABASE()";
        $sizeResult = Database::fetchOne($sizeQuery);
        if ($sizeResult) {
            $stats['size_mb'] = $sizeResult['size_mb'] ?? 0;
        }
        
        // Get table count
        $tablesQuery = "SELECT COUNT(*) as count FROM information_schema.TABLES WHERE table_schema = DATABASE()";
        $tablesResult = Database::fetchOne($tablesQuery);
        if ($tablesResult) {
            $stats['tables'] = $tablesResult['count'] ?? 0;
        }
        
        // Get approximate record count (sum of all tables)
        $recordsQuery = "SELECT SUM(table_rows) as count FROM information_schema.TABLES WHERE table_schema = DATABASE()";
        $recordsResult = Database::fetchOne($recordsQuery);
        if ($recordsResult) {
            $stats['records'] = $recordsResult['count'] ?? 0;
        }
        
        return $stats;
    }
    
    /**
     * Perform database backup
     */
    public function perform(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Create backups directory if it doesn't exist
        $backupDir = BASE_DIR . '/backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        // Generate backup filename with timestamp
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.sql";
        $filePath = $backupDir . '/' . $filename;
        
        // Get database configuration
        $dbConfig = $this->getDatabaseConfig();
        
        // Build mysqldump command
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['dbname']),
            escapeshellarg($filePath)
        );
        
        try {
            // Execute the mysqldump command
            $output = [];
            $returnValue = 0;
            exec($command, $output, $returnValue);
            
            if ($returnValue === 0 && file_exists($filePath)) {
                // Update last backup date in settings
                $this->updateSetting('last_backup_date', date('Y-m-d H:i:s'));
                
                // Log the action
                Auth::logAction('admin', Auth::getAdminId(), "Created database backup: {$filename}", ['type' => 'system']);
                
                $this->setFlash('success', 'Database backup created successfully.');
            } else {
                $this->setFlash('error', 'Failed to create database backup. Command returned error code: ' . $returnValue);
            }
        } catch (\Exception $e) {
            $this->setFlash('error', 'An error occurred while creating the backup: ' . $e->getMessage());
        }
        
        $this->redirect('/superadmin/database-backup');
    }
    
    /**
     * Download a backup file
     */
    public function download(string $filename): void
    {
        // Sanitize filename and build the file path
        $filename = basename($filename);
        $filePath = BASE_DIR . '/backups/' . $filename;
        
        // Check if the file exists
        if (!file_exists($filePath)) {
            $this->setFlash('error', 'Backup file not found.');
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Log the action
        Auth::logAction('admin', Auth::getAdminId(), "Downloaded database backup: {$filename}", ['type' => 'system']);
        
        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        // Clear output buffer
        ob_clean();
        flush();
        
        // Output file
        readfile($filePath);
        exit;
    }
    
    /**
     * Restore database from backup
     */
    public function restore(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['filename'])) {
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Sanitize filename and build the file path
        $filename = basename($_POST['filename']);
        $filePath = BASE_DIR . '/backups/' . $filename;
        
        // Check if the file exists
        if (!file_exists($filePath)) {
            $this->setFlash('error', 'Backup file not found.');
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Get database configuration
        $dbConfig = $this->getDatabaseConfig();
        
        // Build MySQL command to restore
        $command = sprintf(
            'mysql --host=%s --user=%s --password=%s %s < %s',
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['dbname']),
            escapeshellarg($filePath)
        );
        
        try {
            // Execute the mysql restore command
            $output = [];
            $returnValue = 0;
            exec($command, $output, $returnValue);
            
            if ($returnValue === 0) {
                // Log the action
                Auth::logAction('admin', Auth::getAdminId(), "Restored database from backup: {$filename}", ['type' => 'system']);
                
                $this->setFlash('success', 'Database has been restored successfully from backup.');
            } else {
                $this->setFlash('error', 'Failed to restore database. Command returned error code: ' . $returnValue);
            }
        } catch (\Exception $e) {
            $this->setFlash('error', 'An error occurred while restoring the database: ' . $e->getMessage());
        }
        
        $this->redirect('/superadmin/database-backup');
    }
    
    /**
     * Delete a backup file
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['filename'])) {
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Sanitize filename and build the file path
        $filename = basename($_POST['filename']);
        $filePath = BASE_DIR . '/backups/' . $filename;
        
        // Check if the file exists
        if (!file_exists($filePath)) {
            $this->setFlash('error', 'Backup file not found.');
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Attempt to delete the file
        if (unlink($filePath)) {
            // Log the action
            Auth::logAction('admin', Auth::getAdminId(), "Deleted database backup: {$filename}", ['type' => 'system']);
            
            $this->setFlash('success', 'Backup file has been deleted successfully.');
        } else {
            $this->setFlash('error', 'Failed to delete backup file. Please check file permissions.');
        }
        
        $this->redirect('/superadmin/database-backup');
    }
    
    /**
     * Upload and restore from backup file
     */
    public function upload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Check if file was uploaded successfully
        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'Error uploading backup file. Please try again.');
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Check file extension
        $filename = basename($_FILES['backup_file']['name']);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if ($extension !== 'sql') {
            $this->setFlash('error', 'Only SQL backup files are allowed.');
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Create backups directory if it doesn't exist
        $backupDir = BASE_DIR . '/backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        // Generate unique filename with timestamp
        $timestamp = date('Y-m-d_H-i-s');
        $newFilename = "uploaded_{$timestamp}.sql";
        $uploadPath = $backupDir . '/' . $newFilename;
        
        // Move uploaded file to backups directory
        if (!move_uploaded_file($_FILES['backup_file']['tmp_name'], $uploadPath)) {
            $this->setFlash('error', 'Failed to save uploaded backup file.');
            $this->redirect('/superadmin/database-backup');
            return;
        }
        
        // Get database configuration
        $dbConfig = $this->getDatabaseConfig();
        
        // Build MySQL command to restore
        $command = sprintf(
            'mysql --host=%s --user=%s --password=%s %s < %s',
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['dbname']),
            escapeshellarg($uploadPath)
        );
        
        try {
            // Execute the mysql restore command
            $output = [];
            $returnValue = 0;
            exec($command, $output, $returnValue);
            
            if ($returnValue === 0) {
                // Log the action
                Auth::logAction('admin', Auth::getAdminId(), "Restored database from uploaded backup: {$filename}", ['type' => 'system']);
                
                // Update last backup date in settings
                $this->updateSetting('last_backup_date', date('Y-m-d H:i:s'));
                
                $this->setFlash('success', 'Database has been restored successfully from the uploaded backup file.');
            } else {
                $this->setFlash('error', 'Failed to restore database. Command returned error code: ' . $returnValue);
            }
        } catch (\Exception $e) {
            $this->setFlash('error', 'An error occurred while restoring the database: ' . $e->getMessage());
        }
        
        $this->redirect('/superadmin/database-backup');
    }
    
    /**
     * Get database configuration
     */
    private function getDatabaseConfig(): array
    {
        // Hard-coded configuration (in real Laravel environment, this would be from config)
        return [
            'host'     => 'localhost',
            'dbname'   => 'coops_bichi',
            'username' => 'root',
            'password' => ''
        ];
    }
} 