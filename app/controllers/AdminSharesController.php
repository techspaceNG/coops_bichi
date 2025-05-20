<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Auth;
use App\Traits\ActivityLogger;

/**
 * Admin Shares Controller
 * Handles shares management in the admin area
 */
final class AdminSharesController extends Controller
{
    use ActivityLogger;

    /**
     * Flag to determine if shares module is in view-only mode
     * @var bool
     */
    private bool $view_only_mode = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Require admin authentication
        $this->requireAdmin();
    }
    
    /**
     * Check if shares module is in view-only mode
     * @return bool
     */
    private function isViewOnlyMode(): bool
    {
        return $this->view_only_mode;
    }
    
    /**
     * Display shares listing
     */
    public function index(): void
    {
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Prepare for displaying shares
        
        // Start building the query
        $query = "SELECT m.*, s.units, s.unit_value, s.total_value, s.purchase_date, d.name as department_name 
                 FROM members m
                 LEFT JOIN departments d ON m.department_id = d.id
                 LEFT JOIN shares s ON m.id = s.member_id";
        
        $params = [];
        $whereConditions = [];
        
        // Apply search filter
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = trim($_GET['search']);
            $whereConditions[] = "(m.name LIKE ? OR m.email LIKE ? OR m.coop_no LIKE ? OR m.phone LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Apply department filter
        if (isset($_GET['department']) && !empty($_GET['department'])) {
            $whereConditions[] = "m.department_id = ?";
            $params[] = $_GET['department'];
        }
        
        // Apply WHERE conditions if any
        if (!empty($whereConditions)) {
            $query .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        // Apply balance sorting
        if (isset($_GET['balance']) && !empty($_GET['balance'])) {
            if ($_GET['balance'] === 'high') {
                $query .= " ORDER BY COALESCE(s.total_value, m.shares_balance) DESC, m.name ASC";
            } elseif ($_GET['balance'] === 'low') {
                $query .= " ORDER BY COALESCE(s.total_value, m.shares_balance) ASC, m.name ASC";
            }
        } else {
            $query .= " ORDER BY m.name ASC";
        }
        
        // Get all members with shares information
        $members = Database::fetchAll($query, $params);
        
        // Get total shares value - update to include both tables
        $totalShares = Database::fetchOne(
            "SELECT 
                (SELECT COALESCE(SUM(total_value), 0) FROM shares) +
                (SELECT COALESCE(SUM(shares_balance), 0) FROM members 
                 WHERE id NOT IN (SELECT DISTINCT member_id FROM shares)) as total"
        );
        
        $this->renderAdmin('admin/shares/index', [
            'members' => $members,
            'totalShares' => $totalShares['total'] ?? 0,
            'pageTitle' => 'Shares Management',
            'current_page' => 'shares',
            'admin' => $admin
        ]);
    }
    
    /**
     * Display shares upload form
     */
    public function upload(): void
    {
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/shares/upload', [
            'pageTitle' => 'Upload Shares Contributions',
            'current_page' => 'shares_upload',
            'admin' => $admin
        ]);
    }
    
    /**
     * Display share transactions
     */
    public function transactions(): void
    {
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Build the base query
        $query = "SELECT st.*, m.name as member_name, m.coop_no 
                 FROM share_transactions st
                 JOIN members m ON st.member_id = m.id";
        
        $params = [];
        $whereConditions = ["st.transaction_type = 'purchase'"]; // Default filter
        
        // Apply search filter
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = trim($_GET['search']);
            $whereConditions[] = "(m.name LIKE ? OR m.coop_no LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Apply date range filter
        if (isset($_GET['date_range']) && !empty($_GET['date_range'])) {
            $dateRange = trim($_GET['date_range']);
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = date('Y-m-d', strtotime($dates[0]));
                $endDate = date('Y-m-d', strtotime($dates[1]));
                $whereConditions[] = "(st.transaction_date BETWEEN ? AND ?)";
                $params[] = $startDate;
                $params[] = $endDate . ' 23:59:59';
            }
        }
        
        // Apply transaction type filter
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            // Remove the default filter
            $whereConditions = array_filter($whereConditions, function($condition) {
                return strpos($condition, "st.transaction_type") === false;
            });
            
            $whereConditions[] = "st.transaction_type = ?";
            $params[] = $_GET['type'];
        }
        
        // Combine all WHERE conditions
        if (!empty($whereConditions)) {
            $query .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        // Add order by
        $query .= " ORDER BY st.transaction_date DESC LIMIT 100";
        
        // Get all share transactions
        $transactions = Database::fetchAll($query, $params);
        
        $this->renderAdmin('admin/shares/transactions', [
            'transactions' => $transactions,
            'pageTitle' => 'Share Transactions',
            'current_page' => 'share_transactions',
            'admin' => $admin
        ]);
    }
    
    /**
     * Add a new share purchase
     */
    public function addPurchase(): void
    {
        
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/shares');
            return;
        }
        
        // Validate required fields
        $memberId = isset($_POST['member_id']) ? (int)$_POST['member_id'] : 0;
        $units = isset($_POST['units']) ? (int)$_POST['units'] : 0;
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
        
        if ($memberId <= 0 || $units <= 0 || $amount <= 0) {
            $this->setFlash('error', 'Invalid input: Member ID, units, and amount are required and must be positive values.');
            $this->redirect('/admin/shares');
            return;
        }
        
        // Get admin ID for logging
        $adminId = Auth::getAdminId();
        
        try {
            // Start transaction
            Database::beginTransaction();
            
            // Check if member exists
            $member = Database::fetchOne("SELECT id, name FROM members WHERE id = ?", [$memberId]);
            if (!$member) {
                throw new \Exception('Member not found.');
            }
            
            // Check if member already has shares
            $shareRecord = Database::fetchOne("SELECT * FROM shares WHERE member_id = ?", [$memberId]);
            
            if ($shareRecord) {
                // Update existing share record
                $totalAmount = $amount * $units;
                $newUnits = $shareRecord['units'] + $units;
                $newCumulativeAmount = $shareRecord['total_value'] + $totalAmount;
                
                Database::execute(
                    "UPDATE shares SET 
                    units = ?, 
                    unit_value = ?, 
                    total_value = ?, 
                    purchase_date = ? 
                    WHERE member_id = ?",
                    [$newUnits, $amount, $newCumulativeAmount, $date, $memberId]
                );
            } else {
                // Create new share record
                $totalAmount = $amount * $units;
                
                Database::execute(
                    "INSERT INTO shares (member_id, units, unit_value, total_value, purchase_date) 
                    VALUES (?, ?, ?, ?, ?)",
                    [$memberId, $units, $amount, $totalAmount, $date]
                );
            }
            
            // Add transaction record
            Database::execute(
                "INSERT INTO share_transactions (member_id, transaction_type, units, unit_value, total_amount, transaction_date, notes, processed_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [$memberId, 'purchase', $units, $amount, $totalAmount, $date, $notes, $adminId]
            );
            
            // Log the activity
            $this->logActivity(
                'share_purchase',
                $memberId,
                'purchase',
                ['units' => $units, 'amount' => $amount, 'member_name' => $member['name']]
            );
            
            // Commit transaction
            Database::commit();
            
            $this->setFlash('success', "Successfully added {$units} shares for {$member['name']}.");
        } catch (\Exception $e) {
            // Rollback on error
            if (Database::inTransaction()) {
                Database::rollback();
            }
            $this->setFlash('error', 'Failed to add share purchase: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/shares');
    }
    
    /**
     * Process uploaded shares file
     */
    public function processUpload(): void
    {
        
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/shares/upload');
            return;
        }
        
        // Validate required fields
        $contributionDate = isset($_POST['contribution_date']) ? $_POST['contribution_date'] : date('Y-m-d');
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
        
        // Check if file was uploaded
        if (!isset($_FILES['upload_file']) || $_FILES['upload_file']['error'] != 0) {
            $this->setFlash('error', 'No file uploaded or upload error occurred.');
            $this->redirect('/admin/shares/upload');
            return;
        }
        
        // Get admin ID for logging
        $adminId = Auth::getAdminId();
        
        // Get file info
        $file = $_FILES['upload_file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        
        // Validate file size (5MB max)
        if ($fileSize > 5 * 1024 * 1024) {
            $this->setFlash('error', 'File size exceeds maximum limit (5MB).');
            $this->redirect('/admin/shares/upload');
            return;
        }
        
        // Validate file extension
        $allowedExtensions = ['csv', 'xls', 'xlsx'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            $this->setFlash('error', 'Invalid file format. Only CSV, XLS, and XLSX files are allowed.');
            $this->redirect('/admin/shares/upload');
            return;
        }
        
        // Process file based on extension
        try {
            // In a real implementation, you would use a library like PhpSpreadsheet
            // to parse Excel files and CSV files. For simplicity, we'll assume CSV here.
            
            // Open the CSV file
            $handle = fopen($fileTmpName, 'r');
            if (!$handle) {
                throw new \Exception('Could not open file for reading.');
            }
            
            // Read header row
            $header = fgetcsv($handle);
            if (!$header) {
                throw new \Exception('Could not read header row from file.');
            }
            
            // Validate headers
            $expectedHeaders = ['Coop Number', 'Member Name', 'Units', 'Unit Value', 'Notes'];
            $headerCount = count(array_intersect($header, $expectedHeaders));
            if ($headerCount < 4) { // At least the first 4 headers should match
                throw new \Exception('Invalid file format. Header row does not match expected format.');
            }
            
            // Process rows
            $successCount = 0;
            $errorCount = 0;
            $rowNumber = 1; // Start from row 1 (after header)
            
            // Start transaction
            Database::beginTransaction();
            
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty($row[0]) || count($row) < 4) {
                    continue;
                }
                
                // Extract data
                $coopNo = trim($row[0]);
                $memberName = trim($row[1]); // Not used, but for validation
                $units = (int)trim($row[2]);
                $unitValue = (float)trim($row[3]);
                $rowNotes = count($row) > 4 ? trim($row[4]) : '';
                
                // Validate data
                if (empty($coopNo) || $units <= 0 || $unitValue <= 0) {
                    $errorCount++;
                    continue;
                }
                
                // Find member by Coop Number
                $member = Database::fetchOne(
                    "SELECT id, name FROM members WHERE coop_no = ?",
                    [$coopNo]
                );
                
                if (!$member) {
                    $errorCount++;
                    continue;
                }
                
                $memberId = $member['id'];
                
                // Calculate total amount
                $totalAmount = $units * $unitValue;
                
                // Check if member already has shares
                $shareRecord = Database::fetchOne(
                    "SELECT * FROM shares WHERE member_id = ?",
                    [$memberId]
                );
                
                if ($shareRecord) {
                    // Update existing share record
                    $newUnits = $shareRecord['units'] + $units;
                    $newCumulativeAmount = $shareRecord['total_value'] + $totalAmount;
                    
                    Database::execute(
                        "UPDATE shares SET 
                        units = ?, 
                        unit_value = ?, 
                        total_value = ?, 
                        purchase_date = ? 
                        WHERE member_id = ?",
                        [$newUnits, $unitValue, $newCumulativeAmount, $contributionDate, $memberId]
                    );
                } else {
                    // Create new share record
                    Database::execute(
                        "INSERT INTO shares (member_id, units, unit_value, total_value, purchase_date) 
                        VALUES (?, ?, ?, ?, ?)",
                        [$memberId, $units, $unitValue, $totalAmount, $contributionDate]
                    );
                }
                
                // Add transaction record
                $transactionNotes = !empty($rowNotes) ? $rowNotes : $notes;
                
                Database::execute(
                    "INSERT INTO share_transactions (member_id, transaction_type, units, unit_value, total_amount, transaction_date, notes, processed_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [$memberId, 'purchase', $units, $unitValue, $totalAmount, $contributionDate, $transactionNotes, $adminId]
                );
                
                $successCount++;
            }
            
            // Close file
            fclose($handle);
            
            // Log the activity
            $this->logActivity(
                'share_upload',
                null,
                'upload',
                ['filename' => $fileName, 'success_count' => $successCount, 'error_count' => $errorCount]
            );
            
            // Commit transaction
            Database::commit();
            
            $message = "Successfully processed file. Added {$successCount} share records.";
            if ($errorCount > 0) {
                $message .= " Encountered {$errorCount} errors.";
            }
            
            $this->setFlash('success', $message);
        } catch (\Exception $e) {
            // Rollback on error
            if (Database::inTransaction()) {
                Database::rollback();
            }
            
            $this->setFlash('error', 'Failed to process upload: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/shares/upload');
    }
    
    /**
     * Download a template file for share uploads
     */
    public function downloadTemplate(): void
    {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="share_upload_template.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV header
        fputcsv($output, ['Coop Number', 'Member Name', 'Units', 'Unit Value', 'Notes']);
        
        // Add sample rows
        fputcsv($output, ['C001', 'John Doe', '5', '1000.00', 'New purchase']);
        fputcsv($output, ['C002', 'Jane Smith', '3', '1000.00', 'Additional shares']);
        fputcsv($output, ['C003', 'Robert Johnson', '10', '1000.00', 'Monthly contribution']);
        
        // Close output stream
        fclose($output);
        exit();
    }
    
    /**
     * Export transactions to CSV
     */
    public function exportTransactions(): void
    {
        // Build query with any filters from GET request
        $query = "SELECT st.id, m.coop_no, m.name as member_name, 
                 st.transaction_type, st.units, st.unit_value, 
                 st.total_amount,
                 st.transaction_date, st.notes, st.created_at
                 FROM share_transactions st
                 JOIN members m ON st.member_id = m.id";
        
        $params = [];
        $whereConditions = [];
        
        // Apply search filter
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = trim($_GET['search']);
            $whereConditions[] = "(m.name LIKE ? OR m.coop_no LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Apply date range filter
        if (isset($_GET['date_range']) && !empty($_GET['date_range'])) {
            $dateRange = trim($_GET['date_range']);
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = date('Y-m-d', strtotime($dates[0]));
                $endDate = date('Y-m-d', strtotime($dates[1]));
                $whereConditions[] = "(st.transaction_date BETWEEN ? AND ?)";
                $params[] = $startDate;
                $params[] = $endDate . ' 23:59:59';
            }
        }
        
        // Apply transaction type filter
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $whereConditions[] = "st.transaction_type = ?";
            $params[] = $_GET['type'];
        }
        
        // Combine all WHERE conditions
        if (!empty($whereConditions)) {
            $query .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        // Add order by
        $query .= " ORDER BY st.transaction_date DESC";
        
        // Get transactions
        $transactions = Database::fetchAll($query, $params);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="share_transactions_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV header
        fputcsv($output, [
            'Transaction ID', 
            'Coop Number', 
            'Member Name', 
            'Transaction Type', 
            'Units', 
            'Unit Value', 
            'Total Amount', 
            'Transaction Date', 
            'Notes', 
            'Created At'
        ]);
        
        // Add rows
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction['id'],
                $transaction['coop_no'],
                $transaction['member_name'],
                ucfirst($transaction['transaction_type']),
                $transaction['units'],
                number_format($transaction['unit_value'], 2),
                number_format($transaction['total_amount'], 2),
                date('Y-m-d', strtotime($transaction['transaction_date'])),
                $transaction['notes'],
                $transaction['created_at']
            ]);
        }
        
        // Close output stream
        fclose($output);
        exit();
    }
} 