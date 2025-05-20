<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Utility;
use App\Models\Member;
use App\Models\Savings;
use App\Config\Database;

/**
 * SavingsController for superadmin area
 * Manages savings functionality for the cooperative
 */
final class SavingsController extends AbstractController
{
    /**
     * Display savings management dashboard
     *
     * @return void
     */
    public function index(): void
    {
        $this->requireSuperAdmin();
        
        // Process filters
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 20;
        
        $filterMemberId = isset($_GET['member_id']) ? trim($_GET['member_id']) : '';
        $filterDateFrom = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
        $filterDateTo = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Default date range (current month) if not specified
        if (empty($filterDateFrom)) {
            $filterDateFrom = date('Y-m-01'); // First day of current month
        }
        
        if (empty($filterDateTo)) {
            $filterDateTo = date('Y-m-t'); // Last day of current month
        }
        
        // Count total records
        $countQuery = "SELECT COUNT(*) as count 
                       FROM members m
                       LEFT JOIN savings s ON m.id = s.member_id
                       LEFT JOIN departments d ON m.department_id = d.id
                       WHERE (s.id IS NOT NULL OR m.savings_balance > 0)";
        $params = [];
        
        if (!empty($filterMemberId)) {
            $countQuery .= " AND m.id = ?";
            $params[] = $filterMemberId;
        }
        
        if (!empty($searchTerm)) {
            $countQuery .= " AND (m.name LIKE ? OR m.coop_no LIKE ? OR d.name LIKE ?)";
            $searchParam = "%$searchTerm%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $totalResult = Database::fetchOne($countQuery, $params);
        $totalRecords = $totalResult ? (int)$totalResult['count'] : 0;
        
        // Calculate pagination values
        $offset = ($page - 1) * $perPage;
        $totalPages = ceil($totalRecords / $perPage);
        
        // Get savings data with pagination
        $query = "SELECT 
                    s.id, 
                    COALESCE(s.member_id, m.id) as member_id,
                    s.monthly_deduction, 
                    s.cumulative_amount, 
                    s.last_deduction_date, 
                    m.name, 
                    m.coop_no, 
                    d.name as department, 
                    m.created_at as member_since,
                    m.is_active as member_status,
                    COALESCE(s.cumulative_amount, m.savings_balance) as total_savings
                 FROM members m
                 LEFT JOIN savings s ON m.id = s.member_id
                 LEFT JOIN departments d ON m.department_id = d.id
                 WHERE (s.id IS NOT NULL OR m.savings_balance > 0)";
        
        if (!empty($filterMemberId)) {
            $query .= " AND m.id = ?";
        }
        
        if (!empty($searchTerm)) {
            $query .= " AND (m.name LIKE ? OR m.coop_no LIKE ? OR d.name LIKE ?)";
        }
        
        $query .= " ORDER BY COALESCE(s.updated_at, m.updated_at) DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $savingsData = Database::fetchAll($query, $params);
        
        // For members that only have a savings_balance but no savings record,
        // create a standardized structure for the view
        foreach ($savingsData as $key => $saving) {
            if (empty($saving['id'])) {
                $savingsData[$key]['id'] = null;
                $savingsData[$key]['monthly_deduction'] = 0;
                $savingsData[$key]['cumulative_amount'] = $saving['total_savings'];
                $savingsData[$key]['last_deduction_date'] = null;
            }
        }
        
        // Get savings statistics
        $statsQuery = "SELECT 
                        COUNT(*) as total_members,
                        SUM(COALESCE(s.monthly_deduction, 0)) as total_monthly_contributions,
                        SUM(COALESCE(s.cumulative_amount, m.savings_balance)) as total_savings_balance,
                        COUNT(DISTINCT m.id) as active_savings_members,
                        SUM(CASE WHEN COALESCE(s.monthly_deduction, 0) > 0 THEN 1 ELSE 0 END) as members_with_deductions
                       FROM members m
                       LEFT JOIN savings s ON m.id = s.member_id
                       WHERE m.is_active = 1
                       AND (s.id IS NOT NULL OR m.savings_balance > 0)";
        
        $statistics = Database::fetchOne($statsQuery) ?: [
            'total_members' => 0,
            'total_monthly_contributions' => 0,
            'total_savings_balance' => 0,
            'active_savings_members' => 0,
            'members_with_deductions' => 0
        ];
        
        // Get savings transactions stats for the filtered period
        $transactionStatsQuery = "SELECT 
                                  COUNT(*) as total_transactions,
                                  SUM(amount) as total_amount,
                                  COUNT(DISTINCT member_id) as members_with_transactions
                                 FROM savings_transactions
                                 WHERE transaction_type = 'deposit'
                                 AND created_at BETWEEN ? AND ?";
        
        $transactionStats = Database::fetchOne(
            $transactionStatsQuery, 
            ["$filterDateFrom 00:00:00", "$filterDateTo 23:59:59"]
        ) ?: [
            'total_transactions' => 0,
            'total_amount' => 0,
            'members_with_transactions' => 0
        ];
        
        // Get withdrawal stats for the filtered period
        $withdrawalStatsQuery = "SELECT 
                                COUNT(*) as total_withdrawals,
                                SUM(amount) as total_amount,
                                COUNT(DISTINCT member_id) as members_with_withdrawals
                               FROM savings_transactions
                               WHERE transaction_type = 'withdrawal'
                               AND created_at BETWEEN ? AND ?";
        
        $withdrawalStats = Database::fetchOne(
            $withdrawalStatsQuery, 
            ["$filterDateFrom 00:00:00", "$filterDateTo 23:59:59"]
        ) ?: [
            'total_withdrawals' => 0,
            'total_amount' => 0,
            'members_with_withdrawals' => 0
        ];
        
        $this->renderSuperAdmin('superadmin/modules/savings', [
            'title' => 'Savings Management',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                ['label' => 'Savings Management', 'url' => '']
            ],
            'savingsData' => $savingsData,
            'statistics' => $statistics,
            'transactionStats' => $transactionStats,
            'withdrawalStats' => $withdrawalStats,
            'stats' => [
                'total_savings' => $statistics['total_savings_balance'] ?? 0,
                'total_monthly_deductions' => $statistics['total_monthly_contributions'] ?? 0,
                'average_savings' => $statistics['total_members'] > 0 
                    ? ($statistics['total_savings_balance'] / $statistics['total_members']) 
                    : 0,
                'total_members' => $statistics['total_members'] ?? 0,
                'monthly_deductions' => $statistics['total_monthly_contributions'] ?? 0
            ],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_records' => $totalRecords
            ],
            'filters' => [
                'member_id' => $filterMemberId,
                'date_from' => $filterDateFrom,
                'date_to' => $filterDateTo,
                'search' => $searchTerm
            ]
        ]);
    }
    
    /**
     * Export savings data to CSV
     *
     * @return void
     */
    public function export(): void
    {
        $this->requireSuperAdmin();
        
        // Process filters
        $filterMemberId = isset($_GET['member_id']) ? trim($_GET['member_id']) : '';
        $filterDateFrom = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
        $filterDateTo = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Default date range (current month) if not specified
        if (empty($filterDateFrom)) {
            $filterDateFrom = date('Y-m-01'); // First day of current month
        }
        
        if (empty($filterDateTo)) {
            $filterDateTo = date('Y-m-t'); // Last day of current month
        }
        
        // Build query with filters
        $query = "SELECT 
                   COALESCE(s.id, null) as id,
                   m.coop_no,
                   m.name,
                   d.name as department, 
                   COALESCE(s.monthly_deduction, 0) as monthly_deduction,
                   COALESCE(s.cumulative_amount, m.savings_balance) as cumulative_amount, 
                   s.last_deduction_date,
                   COALESCE(s.created_at, m.created_at) as created_at,
                   COALESCE(s.updated_at, m.updated_at) as updated_at
                FROM members m
                LEFT JOIN savings s ON m.id = s.member_id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE (s.id IS NOT NULL OR m.savings_balance > 0)";
        $params = [];
        
        if (!empty($filterMemberId)) {
            $query .= " AND m.id = ?";
            $params[] = $filterMemberId;
        }
        
        if (!empty($searchTerm)) {
            $query .= " AND (m.name LIKE ? OR m.coop_no LIKE ? OR d.name LIKE ?)";
            $searchParam = "%$searchTerm%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $query .= " ORDER BY COALESCE(s.updated_at, m.updated_at) DESC";
        
        $savingsData = Database::fetchAll($query, $params);
        
        // Generate CSV data
        $filename = "savings_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            'ID', 'Coop Number', 'Member Name', 'Department', 
            'Monthly Contribution', 'Cumulative Amount', 
            'Last Deduction Date', 'Created At', 'Updated At'
        ];
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add headers to CSV
        fputcsv($output, $headers);
        
        // Add data rows to CSV
        foreach ($savingsData as $row) {
            $csvRow = [
                $row['id'],
                $row['coop_no'],
                $row['name'],
                $row['department'],
                $row['monthly_deduction'],
                $row['cumulative_amount'],
                $row['last_deduction_date'],
                $row['created_at'],
                $row['updated_at']
            ];
            fputcsv($output, $csvRow);
        }
        
        // Close output stream
        fclose($output);
        exit;
    }
    
    /**
     * Render form for uploading savings deductions
     *
     * @return void
     */
    public function uploadDeductions(): void
    {
        $this->requireSuperAdmin();
        
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process uploaded file
            if (!isset($_FILES['deduction_file']) || $_FILES['deduction_file']['error'] !== UPLOAD_ERR_OK) {
                $errors['file'] = 'Please select a valid CSV file to upload';
                error_log('CSV file upload error: ' . ($_FILES['deduction_file']['error'] ?? 'No file submitted'));
            } else {
                $file = $_FILES['deduction_file'];
                
                // Validate file type
                $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if ($fileType !== 'csv') {
                    $errors['file'] = 'Only CSV files are allowed';
                    error_log('Invalid file type: ' . $fileType);
                } else {
                    // Process CSV file
                    $tempFilePath = $file['tmp_name'];
                    
                    try {
                        // Open the CSV file
                        $handle = fopen($tempFilePath, 'r');
                        
                        if ($handle === false) {
                            throw new \Exception('Failed to open the uploaded file');
                        }
                        
                        // Read and validate header row
                        $headerRow = fgetcsv($handle);
                        $expectedHeaders = ['coop_no', 'amount'];
                        
                        if ($headerRow === false || count(array_intersect($headerRow, $expectedHeaders)) !== count($expectedHeaders)) {
                            $errors['file'] = 'Invalid CSV format. The file must contain coop_no and amount columns';
                            error_log('Invalid CSV headers: ' . json_encode($headerRow));
                        } else {
                            // Process records
                            $deductions = [];
                            $lineNumber = 1;
                            $validRecords = 0;
                            $invalidRecords = [];
                            
                            while (($row = fgetcsv($handle)) !== false) {
                                $lineNumber++;
                                
                                // Map columns to expected fields
                                $record = array_combine($headerRow, $row);
                                
                                // Validate record
                                if (empty($record['coop_no']) || !is_numeric($record['amount']) || (float)$record['amount'] <= 0) {
                                    $invalidRecords[] = "Line $lineNumber: Invalid coop_no or amount";
                                    error_log("Invalid record at line $lineNumber: " . json_encode($record));
                                    continue;
                                }
                                
                                // Check if member exists
                                $member = Database::fetchOne(
                                    "SELECT id FROM members WHERE coop_no = ?",
                                    [$record['coop_no']]
                                );
                                
                                if (!$member) {
                                    $invalidRecords[] = "Line $lineNumber: Member with Coop No. {$record['coop_no']} not found";
                                    error_log("Member not found for coop_no: {$record['coop_no']}");
                                    continue;
                                }
                                
                                // Add to valid records
                                $deductions[$member['id']] = (float)$record['amount'];
                                $validRecords++;
                            }
                            
                            fclose($handle);
                            
                            // Check if we have valid records
                            if ($validRecords === 0) {
                                $errors['file'] = 'No valid deduction records found in the CSV file';
                                error_log('No valid records found in CSV file');
                            } else {
                                // Process deductions
                                $adminId = Auth::getAdminId();
                                error_log("Processing bulk deductions: " . json_encode([
                                    'admin_id' => $adminId,
                                    'record_count' => count($deductions),
                                    'filename' => $file['name']
                                ]));
                                
                                $result = \App\Models\Savings::processBulkDeductions($deductions, $adminId, $file['name']);
                                
                                error_log("Bulk deduction result: " . json_encode($result));
                                
                                if ($result['success']) {
                                    $success = true;
                                    $this->setFlash(
                                        'success', 
                                        "Successfully processed {$result['processed']} deductions. Failed: {$result['failed']}"
                                    );
                                    
                                    if ($result['failed'] > 0) {
                                        error_log("Failures encountered during bulk deduction: " . json_encode($result['failures']));
                                    }
                                    
                                    $this->redirect('/superadmin/savings');
                                    return;
                                } else {
                                    $errors['processing'] = 'Failed to process deductions: ' . ($result['message'] ?? 'Unknown error');
                                    error_log("Bulk deduction processing failed: " . ($result['message'] ?? 'Unknown error'));
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $errors['file'] = 'Error processing file: ' . $e->getMessage();
                        error_log("Exception in uploadDeductions: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                    }
                }
            }
        }
        
        $this->renderSuperAdmin('superadmin/modules/savings/upload', [
            'title' => 'Upload Savings Deductions',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                ['label' => 'Savings Management', 'url' => '/superadmin/savings'],
                ['label' => 'Upload Deductions', 'url' => '']
            ],
            'errors' => $errors,
            'success' => $success
        ]);
    }
    
    /**
     * Render form for adding a manual deduction
     *
     * @return void
     */
    public function addDeduction(): void
    {
        $this->requireSuperAdmin();
        
        $errors = [];
        $success = false;
        
        // Get list of members for dropdown
        $members = Database::fetchAll(
            "SELECT id, name, coop_no FROM members WHERE is_active = 1 ORDER BY name ASC"
        );
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitizeInput($_POST);
            
            // Validate form data
            if (empty($input['member_id'])) {
                $errors['member_id'] = 'Member is required';
            }
            
            if (empty($input['amount'])) {
                $errors['amount'] = 'Amount is required';
            } elseif (!is_numeric($input['amount']) || (float)$input['amount'] <= 0) {
                $errors['amount'] = 'Amount must be a positive number';
            }
            
            if (empty($input['description'])) {
                $errors['description'] = 'Description is required';
            }
            
            if (empty($errors)) {
                $memberId = (int)$input['member_id'];
                $amount = (float)$input['amount'];
                $description = $input['description'];
                
                // Process the deduction
                try {
                    Database::getConnection()->beginTransaction();
                    
                    // No need to update the savings table directly 
                    // The database trigger 'after_savings_transaction_insert' will handle it
                    
                    // Record the transaction
                    Database::insert('savings_transactions', [
                        'member_id' => $memberId,
                        'transaction_type' => 'deposit',
                        'amount' => $amount,
                        'description' => $description,
                        'processed_by' => Auth::getAdminId(),
                        'deduction_date' => date('Y-m-d', strtotime($input['deduction_date'] ?? 'now')),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    // Log the activity
                    $memberInfo = Database::fetchOne(
                        "SELECT name, coop_no FROM members WHERE id = ?",
                        [$memberId]
                    );
                    
                    $logMessage = "Added savings deduction of ₦" . number_format($amount, 2) . 
                                 " for member " . $memberInfo['name'] . " (" . $memberInfo['coop_no'] . ")";
                    
                    Auth::logAction(
                        'admin',
                        Auth::getAdminId(),
                        $logMessage,
                        ['type' => 'savings', 'member_id' => $memberId]
                    );
                    
                    Database::getConnection()->commit();
                    
                    $success = true;
                    $this->setFlash('success', 'Savings deduction added successfully');
                    $this->redirect('/superadmin/savings');
                    return;
                } catch (\Exception $e) {
                    Database::getConnection()->rollBack();
                    $errors['general'] = 'An error occurred: ' . $e->getMessage();
                }
            }
        }
        
        $this->renderSuperAdmin('superadmin/modules/savings/add_deduction', [
            'title' => 'Add Savings Deduction',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                ['label' => 'Savings Management', 'url' => '/superadmin/savings'],
                ['label' => 'Add Deduction', 'url' => '']
            ],
            'members' => $members,
            'errors' => $errors,
            'success' => $success,
            'input' => $_POST ?? []
        ]);
    }
    
    /**
     * Download CSV template for savings deductions
     *
     * @return void
     */
    public function downloadTemplate(): void
    {
        $this->requireSuperAdmin();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="savings_deductions_template.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['coop_no', 'amount']);
        
        // Add sample data
        fputcsv($output, ['COOP001', '5000']);
        fputcsv($output, ['COOP002', '7500']);
        
        // Close output stream
        fclose($output);
        exit;
    }
    
    /**
     * View savings details for a member
     *
     * @param string $memberId Member ID
     * @return void
     */
    public function view(string $memberId): void
    {
        try {
            // Get member details
            $memberQuery = "SELECT m.*, d.name as department_name 
                          FROM members m 
                          LEFT JOIN departments d ON m.department_id = d.id 
                          WHERE m.id = ?";
            $member = Database::fetchOne($memberQuery, [$memberId]);
            
            if (!$member) {
                throw new \Exception('Member not found');
            }
            
            // Get savings details
            $savingsQuery = "SELECT * FROM savings WHERE member_id = ?";
            $savings = Database::fetchOne($savingsQuery, [$memberId]);
            
            // Get savings transactions
            $transactionsQuery = "SELECT st.*, au.name as admin_name 
                                FROM savings_transactions st 
                                LEFT JOIN admin_users au ON st.processed_by = au.id 
                                WHERE st.member_id = ? 
                                ORDER BY st.created_at DESC";
            $transactions = Database::fetchAll($transactionsQuery, [$memberId]);
            
            // Get monthly deductions
            $deductionsQuery = "SELECT * FROM savings_transactions 
                              WHERE member_id = ? 
                              AND transaction_type = 'deposit' 
                              ORDER BY created_at DESC";
            $deductions = Database::fetchAll($deductionsQuery, [$memberId]);
            
            // Get withdrawals
            $withdrawalsQuery = "SELECT * FROM savings_transactions 
                               WHERE member_id = ? 
                               AND transaction_type = 'withdrawal' 
                               ORDER BY created_at DESC";
            $withdrawals = Database::fetchAll($withdrawalsQuery, [$memberId]);
            
            // Calculate statistics
            $totalDeposits = array_sum(array_column($deductions, 'amount'));
            $totalWithdrawals = array_sum(array_column($withdrawals, 'amount'));
            $currentBalance = $totalDeposits - $totalWithdrawals;
            
            $this->renderSuperAdmin('superadmin/modules/savings/view', [
                'title' => 'Member Savings Details',
                'breadcrumb' => [
                    ['label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                    ['label' => 'Savings Management', 'url' => '/superadmin/savings'],
                    ['label' => 'View Details', 'url' => '']
                ],
                'member' => $member,
                'savings' => $savings,
                'transactions' => $transactions,
                'deductions' => $deductions,
                'withdrawals' => $withdrawals,
                'statistics' => [
                    'total_deposits' => $totalDeposits,
                    'total_withdrawals' => $totalWithdrawals,
                    'current_balance' => $currentBalance,
                    'total_transactions' => count($transactions),
                    'total_deductions' => count($deductions),
                    'total_withdrawals_count' => count($withdrawals)
                ]
            ]);
            
        } catch (\Exception $e) {
            error_log('Error in SavingsController::view: ' . $e->getMessage());
            $this->redirect('/superadmin/savings', [
                'error' => 'Failed to load member savings details: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Edit savings settings for a member
     *
     * @param string $memberId Member ID
     * @return void
     */
    public function edit(string $memberId): void
    {
        $this->requireSuperAdmin();
        
        $errors = [];
        $success = false;
        
        // Get member details
        $memberQuery = "SELECT m.*, d.name as department_name 
                       FROM members m 
                       LEFT JOIN departments d ON m.department_id = d.id 
                       WHERE m.id = ?";
        $member = Database::fetchOne($memberQuery, [$memberId]);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/superadmin/savings');
            return;
        }
        
        // Get savings details
        $savingsQuery = "SELECT * FROM savings WHERE member_id = ?";
        $savings = Database::fetchOne($savingsQuery, [$memberId]);
        
        if (!$savings) {
            $savings = [
                'id' => null,
                'monthly_deduction' => 0,
                'cumulative_amount' => 0,
                'last_deduction_date' => null
            ];
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitizeInput($_POST);
            
            // Validate form data
            if (!isset($input['monthly_deduction']) || $input['monthly_deduction'] === '') {
                $errors['monthly_deduction'] = 'Monthly deduction amount is required';
            } elseif (!is_numeric($input['monthly_deduction']) || (float)$input['monthly_deduction'] < 0) {
                $errors['monthly_deduction'] = 'Monthly deduction must be a non-negative number';
            }
            
            if (empty($errors)) {
                $monthlyDeduction = (float)$input['monthly_deduction'];
                
                try {
                    if ($savings['id']) {
                        // Update existing record
                        $updated = Database::update('savings', [
                            'monthly_deduction' => $monthlyDeduction,
                            'updated_at' => date('Y-m-d H:i:s')
                        ], ['id' => $savings['id']]);
                    } else {
                        // Create new record
                        $updated = Database::insert('savings', [
                            'member_id' => $memberId,
                            'monthly_deduction' => $monthlyDeduction,
                            'cumulative_amount' => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]) > 0;
                    }
                    
                    if ($updated) {
                        // Log the activity
                        Auth::logAction(
                            'admin',
                            Auth::getAdminId(),
                            "Updated monthly savings deduction for {$member['name']} ({$member['coop_no']}) to ₦" . 
                            number_format($monthlyDeduction, 2),
                            ['type' => 'savings', 'member_id' => $memberId]
                        );
                        
                        $success = true;
                        $this->setFlash('success', 'Savings settings updated successfully');
                        $this->redirect("/superadmin/savings/view/{$memberId}");
                        return;
                    } else {
                        $errors['general'] = 'Failed to update savings settings';
                    }
                } catch (\Exception $e) {
                    $errors['general'] = 'An error occurred: ' . $e->getMessage();
                }
            }
        }
        
        $this->renderSuperAdmin('superadmin/modules/savings/edit', [
            'title' => 'Edit Savings Settings',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                ['label' => 'Savings Management', 'url' => '/superadmin/savings'],
                ['label' => 'View Savings', 'url' => "/superadmin/savings/view/{$memberId}"],
                ['label' => 'Edit Settings', 'url' => '']
            ],
            'member' => $member,
            'savings' => $savings,
            'errors' => $errors,
            'success' => $success,
            'input' => $_POST ?? []
        ]);
    }
    
    /**
     * Get deduction history for a member (AJAX)
     *
     * @param string $id Member ID
     * @return void
     */
    public function getHistory(string $id): void
    {
        $this->requireSuperAdmin();
        
        $memberId = (int)$id;
        
        // Validate request
        if (!$this->isAjaxRequest()) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        
        try {
            // Get date range if provided
            $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : date('Y-m-01');
            $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : date('Y-m-t');
            
            // Get transaction type filter
            $transactionType = isset($_GET['type']) ? trim($_GET['type']) : 'all';
            $validTypes = ['all', 'deposit', 'withdrawal'];
            if (!in_array($transactionType, $validTypes)) {
                $transactionType = 'all';
            }
            
            // Build query
            $query = "SELECT st.*, a.name as admin_name
                     FROM savings_transactions st
                     LEFT JOIN admin_users a ON st.processed_by = a.id
                     WHERE st.member_id = ?";
            $params = [$memberId];
            
            if ($transactionType !== 'all') {
                $query .= " AND st.transaction_type = ?";
                $params[] = $transactionType;
            }
            
            if (!empty($startDate) && !empty($endDate)) {
                $query .= " AND st.created_at BETWEEN ? AND ?";
                $params[] = "$startDate 00:00:00";
                $params[] = "$endDate 23:59:59";
            }
            
            $query .= " ORDER BY st.created_at DESC";
            
            $transactions = Database::fetchAll($query, $params);
            
            // Calculate summary
            $deposits = array_sum(array_map(function($t) {
                return $t['transaction_type'] === 'deposit' ? (float)$t['amount'] : 0;
            }, $transactions));
            
            $withdrawals = array_sum(array_map(function($t) {
                return $t['transaction_type'] === 'withdrawal' ? (float)$t['amount'] : 0;
            }, $transactions));
            
            // Format response
            $response = [
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'summary' => [
                        'deposits' => $deposits,
                        'withdrawals' => $withdrawals,
                        'net' => $deposits - $withdrawals
                    ]
                ]
            ];
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            exit;
        }
    }
    
    /**
     * Log an activity
     *
     * @param string $action The action being performed
     * @param string $description Description of the activity
     * @param array $data Additional data to log
     * @return void
     */
    protected function logActivity(string $action, string $description, array $data = []): void
    {
        Auth::logAction(
            'admin',
            Auth::getAdminId(),
            $description,
            array_merge(['type' => 'savings'], $data)
        );
    }
} 