<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;

/**
 * SettingsController for Superadmin
 * Handles system settings management
 */
final class SettingsController extends AbstractController
{
    /**
     * Display system settings
     */
    public function index(): void
    {
        $settings = Database::fetchAll("SELECT * FROM system_settings ORDER BY setting_key ASC");
        
        $settingsMap = [];
        foreach ($settings as $setting) {
            $settingsMap[$setting['setting_key']] = $setting['value'];
        }
        
        $this->renderSuperAdmin('superadmin/system-settings', [
            'settings' => $settingsMap,
            'pageTitle' => 'System Settings'
        ]);
    }
    
    /**
     * Update system settings
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/system-settings');
            return;
        }
        
        $settings = $_POST['settings'] ?? [];
        $updated = 0;
        
        foreach ($settings as $key => $value) {
            // Sanitize the key and value
            $key = filter_var($key, FILTER_SANITIZE_STRING);
            $value = filter_var($value, FILTER_SANITIZE_STRING);
            
            // Update the setting
            $result = Database::execute(
                "INSERT INTO system_settings (setting_key, value) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE value = ?",
                [$key, $value, $value]
            );
            
            if ($result) {
                $updated++;
            }
        }
        
        if ($updated > 0) {
            Auth::logAction('admin', Auth::getAdminId(), "Updated {$updated} system settings", ['type' => 'settings']);
            $this->setFlash('success', 'System settings have been updated successfully');
        } else {
            $this->setFlash('warning', 'No settings were changed');
        }
        
        $this->redirect('/superadmin/system-settings');
    }
    
    /**
     * Update system settings from the settings page
     */
    public function updateSystem(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/system-settings');
            return;
        }
        
        $settingsType = $_POST['settings_type'] ?? 'general';
        $updated = 0;
        
        // Process settings based on type
        switch($settingsType) {
            case 'general':
                // General settings
                $generalSettings = [
                    'site_name' => $_POST['site_name'] ?? null,
                    'site_short_name' => $_POST['site_short_name'] ?? null,
                    'contact_email' => $_POST['contact_email'] ?? null,
                    'contact_phone' => $_POST['contact_phone'] ?? null,
                    'physical_address' => $_POST['physical_address'] ?? null,
                    'currency_symbol' => $_POST['currency_symbol'] ?? null,
                    'fiscal_year_start' => $_POST['fiscal_year_start'] ?? null
                ];
                
                foreach ($generalSettings as $key => $value) {
                    if ($value !== null) {
                        $this->updateSetting($key, $value);
                        $updated++;
                    }
                }
                break;
                
            case 'email':
                // Email settings
                $emailSettings = [
                    'mail_driver' => $_POST['mail_driver'] ?? null,
                    'mail_host' => $_POST['mail_host'] ?? null,
                    'mail_port' => $_POST['mail_port'] ?? null,
                    'mail_username' => $_POST['mail_username'] ?? null,
                    'mail_encryption' => $_POST['mail_encryption'] ?? null,
                    'mail_from_address' => $_POST['mail_from_address'] ?? null,
                    'mail_from_name' => $_POST['mail_from_name'] ?? null
                ];
                
                // Handle password separately - only update if a new one is provided
                if (!empty($_POST['mail_password']) && $_POST['mail_password'] !== '********') {
                    $emailSettings['mail_password'] = $_POST['mail_password'];
                }
                
                foreach ($emailSettings as $key => $value) {
                    if ($value !== null) {
                        $this->updateSetting($key, $value);
                        $updated++;
                    }
                }
                break;
                
            case 'security':
                // Security settings
                $securitySettings = [
                    'password_policy_min_length' => $_POST['password_policy_min_length'] ?? null,
                    'password_policy_require_uppercase' => isset($_POST['password_policy_require_uppercase']) ? '1' : '0',
                    'password_policy_require_numbers' => isset($_POST['password_policy_require_numbers']) ? '1' : '0',
                    'password_policy_require_symbols' => isset($_POST['password_policy_require_symbols']) ? '1' : '0',
                    'password_expiry_days' => $_POST['password_expiry_days'] ?? null,
                    'login_attempts_before_lockout' => $_POST['login_attempts_before_lockout'] ?? null,
                    'session_timeout_minutes' => $_POST['session_timeout_minutes'] ?? null,
                    'require_2fa_for_admins' => isset($_POST['require_2fa_for_admins']) ? '1' : '0',
                    'allow_2fa_for_members' => isset($_POST['allow_2fa_for_members']) ? '1' : '0'
                ];
                
                foreach ($securitySettings as $key => $value) {
                    if ($value !== null) {
                        $this->updateSetting($key, $value);
                        $updated++;
                    }
                }
                break;
                
            case 'backup':
                // Backup settings
                $backupSettings = [
                    'auto_backup_enabled' => isset($_POST['auto_backup_enabled']) ? '1' : '0',
                    'backup_frequency' => $_POST['backup_frequency'] ?? null,
                    'backup_retention_days' => $_POST['backup_retention_days'] ?? null,
                    'backup_storage_location' => $_POST['backup_storage_location'] ?? null
                ];
                
                foreach ($backupSettings as $key => $value) {
                    if ($value !== null) {
                        $this->updateSetting($key, $value);
                        $updated++;
                    }
                }
                break;
        }
        
        if ($updated > 0) {
            Auth::logAction('admin', Auth::getAdminId(), "Updated {$updated} {$settingsType} settings", ['type' => 'settings']);
            $this->setFlash('success', ucfirst($settingsType) . ' settings have been updated successfully');
        } else {
            $this->setFlash('warning', 'No settings were changed');
        }
        
        $this->redirect('/superadmin/system-settings');
    }
} 