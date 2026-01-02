<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;

/**
 * ShareController for Superadmin
 * Handles shares management
 */
final class ShareController extends AbstractController
{
    /**
     * Display list of shares
     */
    public function index(): void
    {
        // Get filter parameters
        $memberId = $_GET['member_id'] ?? '';
        $dateRange = $_GET['date_range'] ?? '';
        $search = $_GET['search'] ?? '';
        
        // Build query conditions for filtering
        $conditions = [];
        $params = [];
        
        if (!empty($memberId)) {
            $conditions[] = "m.id = ?";
            $params[] = $memberId;
        }
        
        if (!empty($search)) {
            $conditions[] = "(m.name LIKE ? OR m.coop_no LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($dateRange)) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) == 2) {
                $conditions[] = "(s.created_at BETWEEN ? AND ? OR m.created_at BETWEEN ? AND ?)";
                $params[] = $dates[0] . ' 00:00:00';
                $params[] = $dates[1] . ' 23:59:59';
                $params[] = $dates[0] . ' 00:00:00';
                $params[] = $dates[1] . ' 23:59:59';
            }
        }
        
        // Always include members with share balances
        $conditions[] = "(s.id IS NOT NULL OR m.shares_balance > 0)";
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        // Pagination
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        
        // Count total records
        $countQuery = "
            SELECT COUNT(DISTINCT m.id) as total 
            FROM members m
            LEFT JOIN shares s ON m.id = s.member_id
            {$whereClause}
        ";
        $countResult = Database::fetchOne($countQuery, $params);
        $total = (int)($countResult['total'] ?? 0);
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        if ($page < 1) $page = 1;
        if ($totalPages > 0 && $page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;
        
        // Get shares with member details
        $query = "
            SELECT 
                m.id as member_id,
                m.name as member_name,
                m.coop_no as member_coop_no,
                m.shares_balance,
                MAX(s.id) as id,
                SUM(s.units) as units,
                MAX(s.unit_value) as unit_value,
                MAX(s.status) as status,
                MAX(s.created_at) as created_at,
                MAX(s.updated_at) as updated_at
            FROM 
                members m
            LEFT JOIN 
                shares s ON m.id = s.member_id
            {$whereClause}
            GROUP BY 
                m.id
            ORDER BY 
                m.name ASC
            LIMIT 
                {$offset}, {$perPage}
        ";
        
        $shares = Database::fetchAll($query, $params);
        
        // Process shares to ensure proper display
        foreach ($shares as &$share) {
            // If no share record exists but member has shares_balance
            if (is_null($share['id']) && $share['shares_balance'] > 0) {
                // Create a virtual share record
                $share['id'] = null;
                $share['status'] = 'active';
                $defaultUnitValue = 1000; // Default unit value
                $share['unit_value'] = $defaultUnitValue;
                $share['units'] = floor($share['shares_balance'] / $defaultUnitValue);
                $share['total_value'] = $share['shares_balance'];
                
                // Add a note that this is an initial balance without a proper record
                $share['needs_processing'] = true;
            } else {
                // Calculate total value for existing share records
                $share['total_value'] = ($share['units'] ?? 0) * ($share['unit_value'] ?? 0);
                
                // Check if the calculated total doesn't match the member's balance
                if (abs($share['total_value'] - $share['shares_balance']) > 0.01) {
                    // Flag records that need syncing
                    $share['needs_syncing'] = true;
                    $share['balance_difference'] = $share['shares_balance'] - $share['total_value'];
                }
            }
        }
        
        // Get member list for filter dropdown
        $members = Database::fetchAll("
            SELECT id, coop_no, name FROM members WHERE is_active = 1 ORDER BY name ASC
        ");
        
        // Get share statistics
        $stats = [
            'total_shares' => 0,
            'total_value' => 0,
            'total_members' => 0
        ];
        
        // Calculate total from members table
        $totalStatsQuery = Database::fetchOne("
            SELECT 
                SUM(shares_balance) as total_value,
                COUNT(DISTINCT id) as total_members
            FROM members 
            WHERE shares_balance > 0
        ");
        
        $stats['total_shares'] = Database::fetchOne("SELECT SUM(units) as total FROM shares WHERE status = 'active'")['total'] ?? 0;
        $stats['total_value'] = $totalStatsQuery['total_value'] ?? 0;
        $stats['total_members'] = $totalStatsQuery['total_members'] ?? 0;
        
        // Prepare pagination query string
        $queryString = '';
        foreach ($_GET as $key => $value) {
            if ($key !== 'page') {
                $queryString .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }
        
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'query_string' => $queryString
        ];
        
        $this->renderSuperAdmin('superadmin/modules/shares', [
            'shares' => $shares,
            'members' => $members,
            'stats' => $stats,
            'pagination' => $pagination,
            'current_page' => 'shares',
            'pageTitle' => 'Shares Management'
        ]);
    }
    
    /**
     * Show form to add a new share deduction
     */
    public function addDeduction(): void
    {
        // Get members for dropdown
        $members = Database::fetchAll("SELECT id, coop_no, name FROM members WHERE is_active = 1 ORDER BY name ASC");
        
        $this->renderSuperAdmin('superadmin/add-share-deduction', [
            'members' => $members,
            'current_page' => 'shares',
            'pageTitle' => 'Add Share Deduction'
        ]);
    }
    
    /**
     * Process adding a new share deduction
     */
    public function saveDeduction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/shares');
            return;
        }
        
        // Validate input
        $memberId = (int)($_POST['member_id'] ?? 0);
        $units = (int)($_POST['units'] ?? 0);
        $unitValue = (float)($_POST['unit_value'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');
        
        if ($memberId <= 0) {
            $this->setFlash('error', 'Please select a valid member.');
            $this->redirect('/superadmin/add-share-deduction');
            return;
        }
        
        if ($units <= 0) {
            $this->setFlash('error', 'Number of units must be greater than zero.');
            $this->redirect('/superadmin/add-share-deduction');
            return;
        }
        
        if ($unitValue <= 0) {
            $this->setFlash('error', 'Unit value must be greater than zero.');
            $this->redirect('/superadmin/add-share-deduction');
            return;
        }
        
        // Get member details
        $member = Database::fetchOne("
            SELECT * FROM members WHERE id = ?
        ", [$memberId]);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found.');
            $this->redirect('/superadmin/add-share-deduction');
            return;
        }
        
        // Check if member already has shares record
        $share = Database::fetchOne("
            SELECT * FROM shares WHERE member_id = ?
        ", [$memberId]);
        
        $totalAmount = $units * $unitValue;
        
        // Start transaction
        $db = Database::getConnection();
        $db->beginTransaction();
        
        try {
            if ($share) {
                // Update existing shares record
                $updated = Database::update('shares', [
                    'units' => $share['units'] + $units,
                    'unit_value' => $unitValue,
                    'updated_at' => date('Y-m-d H:i:s')
                ], ['id' => $share['id']]);
                
                if (!$updated) {
                    throw new \Exception('Failed to update shares record.');
                }
                
                $shareId = $share['id'];
            } else {
                // Create new shares record
                $shareId = Database::insert('shares', [
                    'member_id' => $memberId,
                    'units' => $units,
                    'unit_value' => $unitValue,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                if (!$shareId) {
                    throw new \Exception('Failed to create shares record.');
                }
            }
            
            // Record transaction
            $transactionId = Database::insert('share_transactions', [
                'share_id' => $shareId,
                'member_id' => $memberId,
                'units' => $units,
                'unit_value' => $unitValue,
                'total_amount' => $totalAmount,
                'transaction_type' => 'purchase',
                'processed_by' => Auth::getAdminId(),
                'notes' => $notes ?: 'Share deduction by admin',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$transactionId) {
                throw new \Exception('Failed to record share transaction.');
            }
            
            // Update member's shares balance
            $updated = Database::update('members', [
                'shares_balance' => ($member['shares_balance'] ?? 0) + $totalAmount,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $memberId]);
            
            if (!$updated) {
                throw new \Exception('Failed to update member shares balance.');
            }
            
            // Commit transaction
            $db->commit();
            
            // Create notification for member
            \App\Models\Notification::create(
                $memberId,
                'Share Deduction Recorded',
                "A share deduction of {$units} units (₦" . number_format($totalAmount, 2) . ") has been recorded in your account.",
                'info',
                '/member/shares'
            );
            
            // Log action
            Auth::logAction(
                'admin',
                Auth::getAdminId(),
                "Recorded share deduction of {$units} units for member {$member['name']} ({$member['coop_no']})",
                ['type' => 'share_deduction', 'member_id' => $memberId, 'units' => $units, 'amount' => $totalAmount]
            );
            
            $this->setFlash('success', 'Share deduction recorded successfully.');
            $this->redirect('/superadmin/shares');
        } catch (\Exception $e) {
            // Rollback transaction
            $db->rollBack();
            
            $this->setFlash('error', 'Error: ' . $e->getMessage());
            $this->redirect('/superadmin/add-share-deduction');
        }
    }
    
    /**
     * Show form to upload bulk share deductions
     */
    public function bulkDeductions(): void
    {
        $this->renderSuperAdmin('superadmin/bulk-share-deductions', [
            'current_page' => 'shares',
            'pageTitle' => 'Bulk Share Deductions'
        ]);
    }
    
    /**
     * Process uploaded bulk share deductions
     */
    public function processBulkDeductions(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/shares');
            return;
        }
        
        // Handle file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'Please upload a valid CSV file.');
            $this->redirect('/superadmin/add-share-deduction?tab=bulk');
            return;
        }
        
        $file = $_FILES['file'];
        $filePath = $file['tmp_name'];
        $fileName = $file['name'];
        
        // Check if file is CSV
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($fileExt !== 'csv') {
            $this->setFlash('error', 'Please upload a CSV file.');
            $this->redirect('/superadmin/add-share-deduction?tab=bulk');
            return;
        }
        
        // Open the file
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->setFlash('error', 'Could not open the file.');
            $this->redirect('/superadmin/add-share-deduction?tab=bulk');
            return;
        }
        
        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->setFlash('error', 'Could not read the file headers.');
            $this->redirect('/superadmin/add-share-deduction?tab=bulk');
            return;
        }
        
        // Expected headers
        $expectedHeaders = ['coops_number', 'units', 'unit_value', 'notes'];
        
        // Map CSV headers to expected headers (case-insensitive)
        $headerMap = [];
        foreach ($headers as $index => $header) {
            $lowerHeader = strtolower(trim($header));
            foreach ($expectedHeaders as $expectedHeader) {
                if ($lowerHeader === strtolower($expectedHeader)) {
                    $headerMap[$expectedHeader] = $index;
                    break;
                }
            }
        }
        
        // Validate required headers
        if (!isset($headerMap['coops_number']) || !isset($headerMap['units']) || !isset($headerMap['unit_value'])) {
            fclose($handle);
            $this->setFlash('error', 'CSV file does not have all required headers: coops_number, units, unit_value');
            $this->redirect('/superadmin/add-share-deduction?tab=bulk');
            return;
        }
        
        // Process each row
        $processed = 0;
        $success = 0;
        $failed = 0;
        $errors = [];
        
        // Start transaction
        $db = Database::getConnection();
        $db->beginTransaction();
        
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $processed++;
                
                // Extract data using header map
                $coopsNumber = isset($headerMap['coops_number']) && isset($row[$headerMap['coops_number']]) ? 
                    trim($row[$headerMap['coops_number']]) : '';
                $units = isset($headerMap['units']) && isset($row[$headerMap['units']]) ? 
                    (int)$row[$headerMap['units']] : 0;
                $unitValue = isset($headerMap['unit_value']) && isset($row[$headerMap['unit_value']]) ? 
                    (float)$row[$headerMap['unit_value']] : 0;
                $notes = isset($headerMap['notes']) && isset($row[$headerMap['notes']]) ? 
                    trim($row[$headerMap['notes']]) : '';
                
                // Validate required fields
                if (empty($coopsNumber) || $units <= 0 || $unitValue <= 0) {
                    $failed++;
                    $errors[] = "Row {$processed}: Missing required fields (COOPS number, valid units, or valid unit value).";
                    continue;
                }
                
                // Find the member by COOPS number
                $member = Database::fetchOne(
                    "SELECT id, name, coop_no, shares_balance FROM members WHERE coop_no = ?",
                    [$coopsNumber]
                );
                
                if (!$member) {
                    $failed++;
                    $errors[] = "Row {$processed}: Member with COOPS number {$coopsNumber} not found.";
                    continue;
                }
                
                // Calculate total amount
                $totalAmount = $units * $unitValue;
                
                // Check if member already has shares record
                $share = Database::fetchOne("
                    SELECT * FROM shares WHERE member_id = ?
                ", [$member['id']]);
                
                if ($share) {
                    // Update existing shares record
                    $updated = Database::update('shares', [
                        'units' => $share['units'] + $units,
                        'unit_value' => $unitValue,
                        'updated_at' => date('Y-m-d H:i:s')
                    ], ['id' => $share['id']]);
                    
                    if (!$updated) {
                        $failed++;
                        $errors[] = "Row {$processed}: Failed to update shares record for member {$coopsNumber}.";
                        continue;
                    }
                    
                    $shareId = $share['id'];
                } else {
                    // Create new shares record
                    $shareId = Database::insert('shares', [
                        'member_id' => $member['id'],
                        'units' => $units,
                        'unit_value' => $unitValue,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    if (!$shareId) {
                        $failed++;
                        $errors[] = "Row {$processed}: Failed to create shares record for member {$coopsNumber}.";
                        continue;
                    }
                }
                
                // Record transaction
                $transactionId = Database::insert('share_transactions', [
                    'share_id' => $shareId,
                    'member_id' => $member['id'],
                    'units' => $units,
                    'unit_value' => $unitValue,
                    'total_amount' => $totalAmount,
                    'transaction_type' => 'purchase',
                    'processed_by' => Auth::getAdminId(),
                    'notes' => $notes ?: 'Bulk share deduction by admin',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                if (!$transactionId) {
                    $failed++;
                    $errors[] = "Row {$processed}: Failed to record share transaction for member {$coopsNumber}.";
                    continue;
                }
                
                // Update member's shares balance
                $updated = Database::update('members', [
                    'shares_balance' => ($member['shares_balance'] ?? 0) + $totalAmount,
                    'updated_at' => date('Y-m-d H:i:s')
                ], ['id' => $member['id']]);
                
                if (!$updated) {
                    $failed++;
                    $errors[] = "Row {$processed}: Failed to update member shares balance for member {$coopsNumber}.";
                    continue;
                }
                
                // Create notification for member
                \App\Models\Notification::create(
                    $member['id'],
                    'Share Deduction Recorded',
                    "A share deduction of {$units} units (₦" . number_format($totalAmount, 2) . ") has been recorded in your account.",
                    'info',
                    '/member/shares'
                );
                
                // Log action
                Auth::logAction(
                    'admin',
                    Auth::getAdminId(),
                    "Recorded bulk share deduction of {$units} units for member {$member['name']} ({$member['coop_no']})",
                    ['type' => 'share_deduction', 'member_id' => $member['id'], 'units' => $units, 'amount' => $totalAmount]
                );
                
                $success++;
            }
            
            // Commit transaction if at least one record was successful
            if ($success > 0) {
                $db->commit();
            } else {
                $db->rollBack();
            }
        } catch (\Exception $e) {
            // Rollback transaction
            $db->rollBack();
            
            $failed++;
            $errors[] = "Error: " . $e->getMessage();
        }
        
        // Close file
        fclose($handle);
        
        // Store results for display in the view
        $bulk_results = [
            'total' => $processed,
            'success' => $success,
            'failed' => $failed,
            'errors' => $errors
        ];
        
        $this->renderSuperAdmin('superadmin/add-share-deduction', [
            'current_page' => 'shares',
            'pageTitle' => 'Add Share Deduction',
            'activeTab' => 'bulk',
            'bulk_results' => $bulk_results
        ]);
    }
    
    /**
     * Download bulk share deduction template
     */
    public function downloadDeductionTemplate(): void
    {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="share_deduction_template.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add headers to CSV
        fputcsv($output, [
            'coops_number',
            'units',
            'unit_value',
            'notes'
        ]);
        
        // Add sample data
        fputcsv($output, [
            'COOPS/04/002',
            '10',
            '1000.00',
            'Monthly share deduction'
        ]);
        
        // Close stream and exit
        fclose($output);
        exit;
    }
    
    /**
     * Process shares balance to ensure all shares are reflected properly
     * This method ensures that even members with initial balances have proper share records
     */
    public function processShareBalance(): void
    {
        // Start transaction
        $db = Database::getConnection();
        $db->beginTransaction();
        
        try {
            // Get all members with shares_balance but no corresponding shares records
            $members = Database::fetchAll("
                SELECT m.id, m.name, m.coop_no, m.shares_balance
                FROM members m
                LEFT JOIN shares s ON m.id = s.member_id
                WHERE m.shares_balance > 0 AND s.id IS NULL
            ");
            
            $processed = 0;
            
            foreach ($members as $member) {
                $memberId = $member['id'];
                $sharesBalance = $member['shares_balance'];
                $defaultUnitValue = 1000; // Standard unit value
                $units = floor($sharesBalance / $defaultUnitValue);
                
                // Only create records if balance is significant
                if ($units > 0) {
                    // Create share record
                    $shareId = Database::insert('shares', [
                        'member_id' => $memberId,
                        'units' => $units,
                        'unit_value' => $defaultUnitValue,
                        'status' => 'active',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    if ($shareId) {
                        // Record initial transaction
                        Database::insert('share_transactions', [
                            'share_id' => $shareId,
                            'member_id' => $memberId,
                            'units' => $units,
                            'unit_value' => $defaultUnitValue,
                            'total_amount' => $sharesBalance,
                            'transaction_type' => 'initial',
                            'processed_by' => Auth::getAdminId(),
                            'notes' => 'Initial share balance conversion',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        $processed++;
                    }
                }
            }
            
            // Commit transaction
            $db->commit();
            
            $this->setFlash('success', "Processed {$processed} member share balances successfully.");
        } catch (\Exception $e) {
            // Rollback transaction
            $db->rollBack();
            $this->setFlash('error', 'Error processing share balances: ' . $e->getMessage());
        }
        
        $this->redirect('/superadmin/shares');
    }
} 