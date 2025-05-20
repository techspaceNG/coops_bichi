<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;
use App\Helpers\Session;

/**
 * LoanController for Superadmin
 * Handles loan management functionalities
 */
final class LoanController extends AbstractController
{
    /**
     * Display list of loans
     */
    public function index(): void
    {
        // Get filter parameters
        $status = $_GET['status'] ?? '';
        $memberId = $_GET['member_id'] ?? '';
        $dateRange = $_GET['date_range'] ?? '';
        $search = $_GET['search'] ?? '';
        
        // Build query conditions
        $conditions = [];
        $params = [];
        
        if (!empty($status)) {
            $conditions[] = "l.status = ?";
            $params[] = $status;
        }
        
        if (!empty($memberId)) {
            $conditions[] = "l.member_id = ?";
            $params[] = $memberId;
        }
        
        if (!empty($search)) {
            $conditions[] = "(m.name LIKE ? OR m.coop_no LIKE ? OR l.id LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($dateRange)) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) == 2) {
                $conditions[] = "l.created_at BETWEEN ? AND ?";
                $params[] = $dates[0] . ' 00:00:00';
                $params[] = $dates[1] . ' 23:59:59';
            }
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        // Count total records
        $countQuery = "
            SELECT COUNT(*) as total 
            FROM (
                SELECT 
                    id, 
                    member_id
                FROM loans
                UNION ALL
                SELECT 
                    id, 
                    member_id
                FROM loan_applications 
                WHERE status = 'pending'
            ) as combined_loans
            LEFT JOIN members m ON combined_loans.member_id = m.id
            {$whereClause}
        ";
        $countResult = Database::fetchOne($countQuery, $params);
        $total = $countResult['total'] ?? 0;
        
        // Check for members with initial loan balance but no loan records
        if (empty($whereClause) || (strpos($whereClause, 'l.member_id') === false && strpos($whereClause, 'l.status') === false)) {
            $membersWithBalanceQuery = "
                SELECT COUNT(*) as count 
                FROM members m
                LEFT JOIN loans l ON m.id = l.member_id
                WHERE m.loan_balance > 0 AND l.id IS NULL
            ";
            $membersWithBalanceResult = Database::fetchOne($membersWithBalanceQuery);
            $membersWithBalance = $membersWithBalanceResult['count'] ?? 0;
            
            // Add count of members with balance to total
            $total += $membersWithBalance;
        }
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        if ($page < 1) $page = 1;
        if ($totalPages > 0 && $page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;
        
        // Get loans
        $query = "
            SELECT 
                combined_loans.id as loan_id,
                CASE 
                    WHEN combined_loans.source = 'loans' THEN CONCAT('L', combined_loans.id)
                    ELSE CONCAT('LA', combined_loans.id)
                END as display_id,
                combined_loans.loan_amount,
                combined_loans.ip_figure,
                combined_loans.loan_duration,
                combined_loans.status,
                combined_loans.created_at as application_date,
                m.name as member_name,
                m.coop_no,
                m.loan_balance as current_balance
            FROM 
                (
                    SELECT 
                        id, 
                        member_id, 
                        loan_amount, 
                        ip_figure, 
                        loan_duration, 
                        status, 
                        created_at,
                        'loans' as source
                    FROM loans
                    UNION ALL
                    SELECT 
                        id, 
                        member_id, 
                        loan_amount, 
                        ip_figure, 
                        loan_duration, 
                        status, 
                        created_at,
                        'applications' as source
                    FROM loan_applications 
                    WHERE status = 'pending'
                ) as combined_loans
            LEFT JOIN members m ON combined_loans.member_id = m.id
            {$whereClause}
            ORDER BY application_date DESC 
            LIMIT {$offset}, {$perPage}
        ";
        
        $loans = Database::fetchAll($query, $params);
        
        // If there's room for showing members with balance but no loans
        if (count($loans) < $perPage && (empty($whereClause) || (strpos($whereClause, 'l.member_id') === false && strpos($whereClause, 'l.status') === false))) {
            $remainingSlots = $perPage - count($loans);
            
            // Get members with initial loan balance but no loan records
            $membersWithBalanceQuery = "
                SELECT 
                    m.id as member_id,
                    m.name as member_name,
                    m.coop_no as member_coop_no,
                    m.loan_balance as current_balance
                FROM 
                    members m
                LEFT JOIN 
                    loans l ON m.id = l.member_id
                WHERE 
                    m.loan_balance > 0 AND l.id IS NULL
                LIMIT 
                    {$remainingSlots}
            ";
            
            $membersWithBalance = Database::fetchAll($membersWithBalanceQuery);
            
            // Add virtual loan records for members with initial balances
            foreach ($membersWithBalance as $member) {
                $virtualLoan = [
                    'loan_id' => 'IB' . $member['member_id'],
                    'display_id' => 'IB' . $member['member_id'],
                    'member_id' => $member['member_id'],
                    'loan_amount' => $member['current_balance'],
                    'ip_figure' => 0,
                    'loan_duration' => 0,
                    'application_date' => null,
                    'status' => 'initial_balance',
                    'coop_no' => $member['member_coop_no'],
                    'member_name' => $member['member_name'],
                    'current_balance' => $member['current_balance']
                ];
                
                $loans[] = $virtualLoan;
            }
        }
        
        // Get members for filter dropdown
        $members = Database::fetchAll("
            SELECT id, coop_no, name 
            FROM members 
            WHERE is_active = 1 
            ORDER BY name ASC
        ");
        
        // Get loan statistics
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'completed' => 0,
            'total_amount' => 0,
            'pending_amount' => 0,
            'approved_amount' => 0,
            'rejected_amount' => 0
        ];
        
        // Total loans count
        $statsQuery = Database::fetchOne("
            SELECT COUNT(*) as count FROM loans
        ");
        $stats['total'] = $statsQuery['count'] ?? 0;
        
        // Add members with initial balance but no loan records to stats
        $membersWithBalanceCountQuery = Database::fetchOne("
            SELECT COUNT(*) as count 
            FROM members m
            LEFT JOIN loans l ON m.id = l.member_id
            WHERE m.loan_balance > 0 AND l.id IS NULL
        ");
        $stats['total'] += $membersWithBalanceCountQuery['count'] ?? 0;
        
        // Pending loans
        $pendingQuery = Database::fetchOne("
            SELECT COUNT(*) as count, SUM(loan_amount) as amount FROM loans WHERE status = 'pending'
        ");
        $stats['pending'] = $pendingQuery['count'] ?? 0;
        $stats['pending_amount'] = $pendingQuery['amount'] ?? 0;
        
        // Approved loans
        $approvedQuery = Database::fetchOne("
            SELECT COUNT(*) as count, SUM(loan_amount) as amount FROM loans WHERE status = 'approved'
        ");
        $stats['approved'] = $approvedQuery['count'] ?? 0;
        $stats['approved_amount'] = $approvedQuery['amount'] ?? 0;
        
        // Rejected loans
        $rejectedQuery = Database::fetchOne("
            SELECT COUNT(*) as count, SUM(loan_amount) as amount FROM loans WHERE status = 'rejected'
        ");
        $stats['rejected'] = $rejectedQuery['count'] ?? 0;
        $stats['rejected_amount'] = $rejectedQuery['amount'] ?? 0;
        
        // Completed loans
        $completedQuery = Database::fetchOne("
            SELECT COUNT(*) as count FROM loans WHERE status = 'completed'
        ");
        $stats['completed'] = $completedQuery['count'] ?? 0;
        
        // Total loan amount
        $totalAmountQuery = Database::fetchOne("
            SELECT SUM(loan_amount) as total FROM loans
        ");
        $stats['total_amount'] = $totalAmountQuery['total'] ?? 0;
        
        // Add members with initial balance but no loan records to total amount
        $initialBalanceQuery = Database::fetchOne("
            SELECT SUM(loan_balance) as total 
            FROM members m
            LEFT JOIN loans l ON m.id = l.member_id
            WHERE m.loan_balance > 0 AND l.id IS NULL
        ");
        $stats['total_amount'] += $initialBalanceQuery['total'] ?? 0;
        
        // Check for members with initial loan balance but no loan records
        $initialBalancesQuery = "
            SELECT 
                COUNT(*) as count,
                SUM(m.loan_balance) as total
            FROM 
                members m
            LEFT JOIN 
                loans l ON m.id = l.member_id
            WHERE 
                m.loan_balance > 0 AND l.id IS NULL
        ";
        $initialBalancesResult = Database::fetchOne($initialBalancesQuery);

        // Add stats about initial balances that need processing
        $stats['initial_balance_count'] = $initialBalancesResult['count'] ?? 0;
        $stats['initial_balance_total'] = $initialBalancesResult['total'] ?? 0;
        $stats['needs_processing'] = $stats['initial_balance_count'] > 0;
        
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
        
        $this->renderSuperAdmin('superadmin/modules/loans', [
            'loans' => $loans,
            'members' => $members,
            'stats' => $stats,
            'pagination' => $pagination,
            'current_page' => 'loans',
            'pageTitle' => 'Loans Management'
        ]);
    }
    
    /**
     * View loan details
     * 
     * @param string|int $id The loan ID
     */
    public function view($id): void
    {
        try {
            // Debug logging
            error_log("LoanController::view - Accessed with loan ID: {$id}");
            
            // Cast the ID to integer
            $loanId = (int)$id;
            
            if ($loanId <= 0) {
                error_log("Invalid loan ID provided: {$id}");
                Session::setFlash('error', 'Invalid loan ID provided');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // First check if this is a loan in the loans table
            $loan = null;
            
            try {
                // First, check if the loan exists at all
                $loanExists = Database::fetchOne("SELECT id FROM loans WHERE id = ?", [$loanId]);
                if (!$loanExists) {
                    error_log("Loan ID {$loanId} not found in the loans table");
                }
                
                // Get member ID for this loan to see if member exists
                $loanMember = Database::fetchOne("SELECT member_id FROM loans WHERE id = ?", [$loanId]);
                if ($loanMember) {
                    $memberExists = Database::fetchOne("SELECT id FROM members WHERE id = ?", [$loanMember['member_id']]);
                    if (!$memberExists) {
                        error_log("Member ID {$loanMember['member_id']} for loan {$loanId} not found in members table");
                    }
                }
                
                // Modified query with LEFT JOINs instead of JOIN to ensure we get loan data even if related data is missing
                $loan = Database::fetchOne(
                    "SELECT 
                        l.*,
                        CONCAT('L', l.id) as display_id,
                        m.name as member_name,
                        m.coop_no,
                        m.email,
                        m.phone,
                        d.name as department_name,
                        m.bank_name,
                        m.account_number,
                        m.account_name,
                        a.name as approved_by_name
                    FROM 
                        loans l
                    LEFT JOIN 
                        members m ON l.member_id = m.id
                    LEFT JOIN 
                        departments d ON m.department_id = d.id
                    LEFT JOIN 
                        admin_users a ON l.approved_by = a.id
                    WHERE 
                        l.id = ?",
                    [$loanId]
                );
                
                if ($loan) {
                    error_log("Loan found in loans table: " . json_encode($loan));
                    
                    // Fix for missing or zero total_repayment and ip_figure (admin charge)
                    if (empty($loan['total_repayment']) || $loan['total_repayment'] == 0 || empty($loan['ip_figure']) || $loan['ip_figure'] == 0) {
                        // Calculate total repayment if it's missing
                        $interestRate = isset($loan['interest_rate']) && $loan['interest_rate'] > 0 ? 
                            $loan['interest_rate'] : 5.0; // Default to 5% if not set
                            
                        $adminCharges = $loan['loan_amount'] * ($interestRate / 100);
                        $loan['ip_figure'] = $adminCharges; // Fix admin charge amount
                        $loan['total_repayment'] = $loan['loan_amount'] + $adminCharges; // Fix total repayment
                        
                        // Update the loan record in the database
                        Database::update('loans', [
                            'ip_figure' => $adminCharges,
                            'total_repayment' => $loan['total_repayment']
                        ], ['id' => $loanId]);
                        
                        error_log("Fixed loan values: Admin Charge: {$adminCharges}, Total Repayment: {$loan['total_repayment']}");
                    }
                    
                    // Get loan repayments
                    $repayments = Database::fetchAll(
                        "SELECT 
                            lr.*,
                            au.name as processed_by_name
                        FROM 
                            loan_repayments lr
                        LEFT JOIN 
                            admin_users au ON lr.processed_by = au.id
                        WHERE 
                            lr.loan_id = ?
                        ORDER BY 
                            lr.payment_date DESC",
                        [$loanId]
                    );
                    
                    // Calculate totals
                    $totalPaid = !empty($repayments) ? array_sum(array_column($repayments, 'amount')) : 0;
                    $remainingBalance = $loan['total_repayment'] - $totalPaid;
                    
                    // If the balance in the database is different from our calculation, update it
                    if (isset($loan['balance']) && abs($loan['balance'] - $remainingBalance) > 0.01) {
                        Database::update('loans', [
                            'balance' => $remainingBalance
                        ], ['id' => $loanId]);
                        
                        error_log("Updated loan balance from {$loan['balance']} to {$remainingBalance}");
                        $loan['balance'] = $remainingBalance;
                    }
                    
                    // Get audit logs for this loan
                    $auditLogs = [];
                    try {
                        $auditLogs = Database::fetchAll(
                            "SELECT 
                                al.*,
                                CASE 
                                    WHEN al.user_type = 'admin' THEN (SELECT name FROM admin_users WHERE id = al.user_id)
                                    WHEN al.user_type = 'member' THEN (SELECT name FROM members WHERE id = al.user_id)
                                    ELSE 'System'
                                END as user_name
                            FROM audit_logs al
                            WHERE 
                                (al.action_type = 'loan' AND al.details LIKE ?) OR
                                (al.action_type = 'loan_repayment' AND al.details LIKE ?)
                            ORDER BY al.timestamp DESC
                            LIMIT 50",
                            ["%\"loan_id\":{$loanId}%", "%\"loan_id\":{$loanId}%"]
                        );
                    } catch (\Exception $e) {
                        error_log("Error fetching audit logs: " . $e->getMessage());
                        // Continue without audit logs
                    }
                    
                    // Render the view
                    $this->renderSuperAdmin('superadmin/view-loan', [
                        'loan' => $loan,
                        'repayments' => $repayments,
                        'total_paid' => $totalPaid,
                        'remaining_balance' => $remainingBalance,
                        'audit_logs' => $auditLogs,
                        'is_application' => false,
                        'current_page' => 'loans',
                        'pageTitle' => 'View Loan Details'
                    ]);
                    return;
                } else {
                    error_log("Loan not found in loans table after query, checking loan_applications");
                }
            } catch (\Exception $e) {
                error_log("Error while fetching loan details: " . $e->getMessage());
                error_log("Error trace: " . $e->getTraceAsString());
                // Continue to try application check
            }
            
            // If not found in loans, check if it's a loan application
            try {
                // First check if the application exists at all
                $applicationExists = Database::fetchOne("SELECT id FROM loan_applications WHERE id = ?", [$loanId]);
                if (!$applicationExists) {
                    error_log("Application ID {$loanId} not found in the loan_applications table");
                }
                
                // Modified query with LEFT JOINs
                $application = Database::fetchOne(
                    "SELECT 
                        la.*,
                        m.name as member_name,
                        m.coop_no,
                        m.email,
                        m.phone,
                        d.name as department_name,
                        COALESCE(la.bank_name, m.bank_name) as bank_name,
                        COALESCE(la.account_number, m.account_number) as account_number,
                        COALESCE(la.account_name, m.account_name) as account_name,
                        la.account_type
                    FROM 
                        loan_applications la
                    LEFT JOIN 
                        members m ON la.member_id = m.id
                    LEFT JOIN 
                        departments d ON m.department_id = d.id
                    WHERE 
                        la.id = ?",
                    [$loanId]
                );
                
                if ($application) {
                    error_log("Found application in loan_applications table: " . json_encode($application));
                    
                    // Format application data to match loan structure expected by the view
                    $loan = $application;
                    $loan['display_id'] = 'A' . $application['id'];
                    $loan['loan_amount'] = $application['loan_amount'] ?? 0;
                    $loan['ip_figure'] = $application['ip_figure'] ?? 0;
                    $loan['repayment_period'] = $application['loan_duration'] ?? 12;
                    
                    // Render the view
                    $this->renderSuperAdmin('superadmin/view-loan', [
                        'loan' => $loan,
                        'repayments' => [],
                        'total_paid' => 0,
                        'remaining_balance' => 0,
                        'audit_logs' => [],
                        'is_application' => true,
                        'current_page' => 'loans',
                        'pageTitle' => 'View Loan Application'
                    ]);
                    return;
                }
            } catch (\Exception $e) {
                error_log("Error while fetching loan application: " . $e->getMessage());
                error_log("Error trace: " . $e->getTraceAsString());
                // Continue to general error handling
            }
            
            // If we get here, the loan was not found in either table
            error_log("No loan or application found with ID: {$loanId}");
            
            // Verify if loans table exists and has correct structure
            try {
                $tablesResult = Database::fetchAll("SHOW TABLES LIKE 'loans'");
                if (empty($tablesResult)) {
                    error_log("The 'loans' table does not exist in the database");
                } else {
                    // Check table structure
                    $columns = Database::fetchAll("SHOW COLUMNS FROM loans");
                    error_log("Loans table structure: " . json_encode($columns));
                    
                    // Check if there are any records in the loans table
                    $loanCount = Database::fetchOne("SELECT COUNT(*) as count FROM loans");
                    error_log("Total loans in database: " . ($loanCount['count'] ?? 'unknown'));
                }
            } catch (\Exception $e) {
                error_log("Error checking database structure: " . $e->getMessage());
            }
            
            Session::setFlash('error', 'Loan not found');
            $this->redirect('/superadmin/loans');
            
        } catch (\Exception $e) {
            // Log error for debugging
            error_log("Error in LoanController::view - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            if ($e instanceof \PDOException) {
                error_log("SQL Error: " . $e->getCode() . " - " . $e->getMessage());
            }
            
            Session::setFlash('error', 'An error occurred while retrieving loan details');
            $this->redirect('/superadmin/loans');
        }
    }
    
    /**
     * Show form to approve/reject a pending loan
     */
    public function review(string $id): void
    {
        $id = (int)$id;
        
        // Get loan details
        $loan = Database::fetchOne("
            SELECT 
                l.*,
                m.name as member_name,
                m.coop_no as member_coop_no,
                m.email as member_email
            FROM 
                loans l
            LEFT JOIN 
                members m ON l.member_id = m.id
            WHERE 
                l.id = ?
        ", [$id]);
        
        if (!$loan) {
            $this->setFlash('error', 'Loan not found.');
            $this->redirect('/superadmin/loans');
            return;
        }
        
        // Check if loan is pending
        if ($loan['status'] !== 'pending') {
            $this->setFlash('error', 'This loan is already ' . $loan['status'] . '.');
            $this->redirect('/superadmin/view-loan/' . $id);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $notes = trim($_POST['notes'] ?? '');
            $interestRate = (float)($_POST['interest_rate'] ?? $loan['interest_rate']);
            $interestAmount = (float)($_POST['ip_figure'] ?? $loan['ip_figure']);
            
            if (!in_array($action, ['approve', 'reject'])) {
                $this->setFlash('error', 'Invalid action.');
                $this->redirect('/superadmin/review-loan/' . $id);
                return;
            }
            
            if ($action === 'approve') {
                // Update loan
                $updateData = [
                    'status' => 'active',
                    'approved_at' => date('Y-m-d H:i:s'),
                    'approved_by' => Auth::getAdminId(),
                    'admin_notes' => $notes,
                    'interest_rate' => $interestRate,
                    'ip_figure' => $interestAmount
                ];
                
                // Create payment schedule
                $updateData['payment_schedule'] = $this->generatePaymentSchedule($loan);
                
                $updated = Database::update('loans', $updateData, ['id' => $id]);
                
                if ($updated) {
                    // Log the action
                    Auth::logAction(
                        'admin',
                        Auth::getAdminId(),
                        "Approved loan #{$loan['loan_number']} for {$loan['member_name']} ({$loan['member_coop_no']})",
                        ['type' => 'loan', 'loan_id' => $id]
                    );
                    
                    // Create notification for member
                    \App\Models\Notification::create(
                        (int)$loan['member_id'],
                        'Loan Application Approved',
                        "Your loan application for ₦" . number_format($loan['loan_amount'], 2) . " has been approved. Monthly payment: ₦" . number_format($interestAmount, 2),
                        'success',
                        '/Coops_Bichi/public/member/loans'
                    );
                    
                    $this->setFlash('success', 'Loan has been approved successfully.');
                } else {
                    $this->setFlash('error', 'Failed to approve loan.');
                }
            } else {
                // Reject the loan
                $updated = Database::update('loans', [
                    'status' => 'rejected',
                    'admin_notes' => $notes
                ], ['id' => $id]);
                
                if ($updated) {
                    // Log the action
                    Auth::logAction(
                        'admin',
                        Auth::getAdminId(),
                        "Rejected loan #{$loan['loan_number']} for {$loan['member_name']} ({$loan['member_coop_no']})",
                        ['type' => 'loan', 'loan_id' => $id]
                    );
                    
                    // Create notification for member
                    \App\Models\Notification::create(
                        (int)$loan['member_id'],
                        'Loan Application Rejected',
                        "Your loan application for ₦" . number_format($loan['loan_amount'], 2) . " has been rejected. Reason: " . $notes,
                        'error',
                        '/Coops_Bichi/public/member/loans'
                    );
                    
                    $this->setFlash('success', 'Loan has been rejected.');
                } else {
                    $this->setFlash('error', 'Failed to reject loan.');
                }
            }
            
            $this->redirect('/superadmin/view-loan/' . $id);
            return;
        }
        
        $this->renderSuperAdmin('superadmin/review-loan', [
            'loan' => $loan,
            'current_page' => 'loans',
            'pageTitle' => 'Review Loan: ' . $loan['loan_number']
        ]);
    }
    
    /**
     * Record a payment for a loan
     */
    public function recordPayment(string $id): void
    {
        $id = (int)$id;
        
        // Get loan details
        $loan = Database::fetchOne("
            SELECT 
                l.*,
                m.name as member_name,
                (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as total_paid
            FROM 
                loans l
            LEFT JOIN 
                members m ON l.member_id = m.id
            WHERE 
                l.id = ?
        ", [$id]);
        
        if (!$loan) {
            $this->setFlash('error', 'Loan not found.');
            $this->redirect('/superadmin/loans');
            return;
        }
        
        if ($loan['status'] !== 'active') {
            $this->setFlash('error', 'Cannot record payment for a non-active loan.');
            $this->redirect('/superadmin/view-loan/' . $id);
            return;
        }
        
        // Calculate remaining balance
        $totalPaid = (float)($loan['total_paid'] ?? 0);
        $totalDue = (float)$loan['loan_amount'] + (float)$loan['ip_figure'];
        $remainingBalance = $totalDue - $totalPaid;
        
        if ($remainingBalance <= 0) {
            $this->setFlash('error', 'This loan has already been fully paid.');
            $this->redirect('/superadmin/view-loan/' . $id);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = (float)$loan['loan_amount'];
            $paymentDate = $_POST['payment_date'] ?? date('Y-m-d');
            $notes = trim($_POST['notes'] ?? '');
            $receiptNumber = trim($_POST['receipt_number'] ?? '');
            
            // Validate input
            if ($amount <= 0) {
                $this->setFlash('error', 'Payment amount must be greater than zero.');
                $this->redirect('/superadmin/record-payment/' . $id);
                return;
            }
            
            if ($amount > $remainingBalance) {
                $this->setFlash('error', 'Payment amount cannot exceed the remaining balance of ' . number_format($remainingBalance, 2));
                $this->redirect('/superadmin/record-payment/' . $id);
                return;
            }
            
            // Insert payment record
            $paymentId = Database::insert('loan_repayments', [
                'loan_id' => $id,
                'amount' => $amount,
                'payment_date' => $paymentDate,
                'notes' => $notes,
                'admin_id' => Auth::getAdminId(),
                'receipt_number' => $receiptNumber,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($paymentId) {
                // Check if loan is fully paid
                $newTotalPaid = $totalPaid + $amount;
                if ($newTotalPaid >= $totalDue) {
                    // Update loan status to completed
                    Database::update('loans', [
                        'status' => 'completed',
                        'completed_at' => date('Y-m-d H:i:s')
                    ], ['id' => $id]);
                }
                
                // Log the action
                Auth::logAction(
                    'admin',
                    Auth::getAdminId(),
                    "Recorded payment of " . number_format($amount, 2) . " for loan #{$loan['loan_number']}",
                    ['type' => 'loan_payment', 'loan_id' => $id, 'payment_id' => $paymentId]
                );
                
                $this->setFlash('success', 'Payment recorded successfully.');
            } else {
                $this->setFlash('error', 'Failed to record payment.');
            }
            
            $this->redirect('/superadmin/view-loan/' . $id);
            return;
        }
        
        $this->renderSuperAdmin('superadmin/record-payment', [
            'loan' => $loan,
            'remainingBalance' => $remainingBalance,
            'current_page' => 'loans',
            'pageTitle' => 'Record Payment: ' . $loan['loan_number']
        ]);
    }
    
    /**
     * Generate payment schedule for a loan
     */
    private function generatePaymentSchedule(array $loan): string
    {
        $amount = (float)$loan['loan_amount'];
        $interestAmount = (float)$loan['ip_figure'];
        $totalAmount = $amount + $interestAmount;
        $term = (int)$loan['repayment_period'];
        $startDate = date('Y-m-d', strtotime($loan['approved_at'] ?? date('Y-m-d')));
        
        $schedule = [];
        $paymentAmount = $totalAmount / $term;
        
        for ($i = 1; $i <= $term; $i++) {
            $dueDate = date('Y-m-d', strtotime($startDate . " +{$i} months"));
            
            $schedule[] = [
                'installment' => $i,
                'due_date' => $dueDate,
                'amount' => round($paymentAmount, 2),
                'status' => 'pending'
            ];
        }
        
        return json_encode($schedule);
    }
    
    /**
     * Export loans data to CSV
     */
    public function export(): void
    {
        // Get filter parameters
        $status = $_GET['status'] ?? '';
        $memberId = $_GET['member_id'] ?? '';
        $dateRange = $_GET['date_range'] ?? '';
        $search = $_GET['search'] ?? '';
        $department = $_GET['department'] ?? '';
        
        try {
            // Special handling for completed status
            if ($status === 'completed') {
                $query = "
                    SELECT 
                        l.id,
                        m.coop_no as member_coop_no,
                        m.name as member_name,
                        m.ti_number,
                        l.loan_amount,
                        l.total_repayment as regular_limit,
                        l.ip_figure as regular_ip_figure,
                        l.repayment_period as regular_ip_number,
                        l.status,
                        l.purpose,
                        l.created_at as application_date,
                        l.approval_date,
                        au.name as approved_by_name,
                        (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as regular_total_rpmt
                    FROM 
                        loans l
                    LEFT JOIN 
                        members m ON l.member_id = m.id
                    LEFT JOIN 
                        admin_users au ON l.approved_by = au.id
                    WHERE 
                        l.status = 'completed'
                    ORDER BY 
                        l.created_at DESC
                ";
                $loans = Database::fetchAll($query);
            } else {
                // Regular export for other statuses
                // Build query conditions for filtering
                $conditions = [];
                $params = [];
                
                if (!empty($status)) {
                    // Handle both 'declined' and 'rejected' statuses
                    if ($status === 'rejected' || $status === 'declined') {
                        $conditions[] = "(l.status = 'rejected' OR l.status = 'declined')";
                    } else {
                        $conditions[] = "l.status = ?";
                        $params[] = $status;
                    }
                }
                
                if (!empty($memberId)) {
                    $conditions[] = "l.member_id = ?";
                    $params[] = $memberId;
                }
                
                if (!empty($department)) {
                    $conditions[] = "m.department_id = ?";
                    $params[] = $department;
                }
                
                if (!empty($search)) {
                    $conditions[] = "(m.name LIKE ? OR m.coop_no LIKE ?)";
                    $params[] = "%{$search}%";
                    $params[] = "%{$search}%";
                }
                
                if (!empty($dateRange)) {
                    $dates = explode(' to ', $dateRange);
                    if (count($dates) == 2) {
                        $conditions[] = "l.created_at BETWEEN ? AND ?";
                        $params[] = $dates[0] . ' 00:00:00';
                        $params[] = $dates[1] . ' 23:59:59';
                    }
                }
                
                // Build WHERE clause
                $whereClause = '';
                if (!empty($conditions)) {
                    $whereClause = "WHERE " . implode(" AND ", $conditions);
                }
                
                // Get loans data
                $query = "
                    SELECT 
                        l.id,
                        m.coop_no as member_coop_no,
                        m.name as member_name,
                        m.ti_number,
                        l.loan_amount,
                        l.total_repayment as regular_limit,
                        l.ip_figure as regular_ip_figure,
                        l.repayment_period as regular_ip_number,
                        l.status,
                        l.purpose,
                        l.created_at as application_date,
                        l.approval_date,
                        au.name as approved_by_name,
                        (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as regular_total_rpmt
                    FROM 
                        loans l
                    LEFT JOIN 
                        members m ON l.member_id = m.id
                    LEFT JOIN 
                        admin_users au ON l.approved_by = au.id
                    {$whereClause}
                    ORDER BY 
                        l.created_at DESC
                ";
                
                $loans = Database::fetchAll($query, $params);
            }
            
            // Check if we have loans to export
            if (empty($loans)) {
                $this->setFlash('warning', 'No loans found to export.');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Set headers for Excel download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="loans_export_' . date('Y-m-d') . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add headers to CSV
            fputcsv($output, [
                'Loan ID',
                'Member Name',
                'COOPS Number',
                'TI Number',
                'Regular Limit',
                'Regular IP Number',
                'Regular IP Figure',
                'Regular Total RPMT',
                'Regular Balance',
                'Status',
                'Purpose',
                'Application Date',
                'Approval Date',
                'Approved By'
            ]);
            
            // Add data to CSV
            foreach ($loans as $loan) {
                $regularLimit = (float)($loan['regular_limit'] ?? 0);
                $regularTotalRpmt = (float)($loan['regular_total_rpmt'] ?? 0);
                $regularBalance = $regularLimit - $regularTotalRpmt;
                
                // Format loan data for CSV
                fputcsv($output, [
                    'L' . $loan['id'],
                    $loan['member_name'] ?? 'N/A',
                    $loan['member_coop_no'] ?? 'N/A',
                    $loan['ti_number'] ?? 'N/A',
                    number_format($regularLimit, 2),
                    $loan['regular_ip_number'] ?? 'N/A',
                    number_format((float)($loan['regular_ip_figure'] ?? 0), 2),
                    number_format($regularTotalRpmt, 2),
                    number_format($regularBalance, 2),
                    ucfirst($loan['status'] ?? 'unknown'),
                    $loan['purpose'] ?? 'N/A',
                    date('Y-m-d', strtotime($loan['application_date'] ?? 'now')),
                    $loan['approval_date'] ? date('Y-m-d', strtotime($loan['approval_date'])) : 'N/A',
                    $loan['approved_by_name'] ?? 'N/A'
                ]);
            }
            
            // Close stream and exit
            fclose($output);
            exit;
        } catch (\Exception $e) {
            // Log error for debugging
            error_log("Error in LoanController::export - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Set flash message and redirect
            $this->setFlash('error', 'An error occurred during export: ' . $e->getMessage());
            $this->redirect('/superadmin/loans');
        }
    }
    
    /**
     * Display form for adding loan deduction
     * 
     * @return void
     */
    public function addDeduction(): void
    {
        $this->requireSuperAdmin();
        
        // Get active tab from query parameter, default to 'individual'
        $activeTab = $_GET['tab'] ?? 'individual';
        
        // Get bulk results from session if available
        $bulkResults = null;
        if (isset($_SESSION['bulk_results'])) {
            $bulkResults = $_SESSION['bulk_results'];
            // Clear from session to prevent showing on refresh
            unset($_SESSION['bulk_results']);
        }
        
        $this->renderSuperAdmin('superadmin/add-loan-deduction', [
            'activeTab' => $activeTab,
            'success' => false,
            'errors' => [],
            'bulk_results' => $bulkResults,
            'current_page' => 'loans',
            'pageTitle' => 'Add Loan Deduction'
        ]);
    }
    
    /**
     * Process loan deduction submission
     * 
     * @return void
     */
    public function saveDeduction(): void
    {
        $this->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/add-loan-deduction');
            return;
        }
        
        // Sanitize and validate input
        $input = $this->sanitizeInput($_POST);
        
        $loanId = (int)($input['loan_id'] ?? 0);
        $amount = (float)($input['amount'] ?? 0);
        $paymentDate = $input['payment_date'] ?? date('Y-m-d');
        $notes = $input['notes'] ?? '';
        
        // Validate required fields
        if ($loanId <= 0) {
            $this->setFlash('error', 'Invalid loan selected');
            $this->redirect('/superadmin/add-loan-deduction');
            return;
        }
        
        if ($amount <= 0) {
            $this->setFlash('error', 'Amount must be greater than zero');
            $this->redirect('/superadmin/add-loan-deduction');
            return;
        }
        
        // Check if loan exists and get details
        $loan = Database::fetchOne("
            SELECT 
                l.*,
                m.name as member_name,
                (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as total_paid
            FROM loans l
            LEFT JOIN members m ON l.member_id = m.id
            WHERE l.id = ?
        ", [$loanId]);
        
        if (!$loan) {
            $this->setFlash('error', 'Loan not found');
            $this->redirect('/superadmin/add-loan-deduction');
            return;
        }
        
        // Calculate remaining balance
        $totalPaid = (float)($loan['total_paid'] ?? 0);
        // Only use loan_amount for balance calculation (not including ip_figure)
        $remainingBalance = (float)$loan['loan_amount'] - $totalPaid;
        
        // Ensure amount is not greater than remaining balance
        if ($amount > $remainingBalance) {
            $this->setFlash('error', 'Deduction amount cannot exceed the remaining balance');
            $this->redirect('/superadmin/add-loan-deduction');
            return;
        }
        
        // Get admin info
        $adminId = Auth::getAdminId();
        
        // Create the deduction record
        $deductionData = [
            'loan_id' => $loanId,
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'processed_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Add notes if provided - but first check if the column exists
        if (!empty($notes)) {
            // Check if 'notes' column exists in loan_repayments table
            $columnExists = false;
            try {
                $stmt = Database::getConnection()->query("SHOW COLUMNS FROM loan_repayments LIKE 'notes'");
                $columnExists = ($stmt && $stmt->rowCount() > 0);
            } catch (\Exception $e) {
                error_log("Error checking for notes column: " . $e->getMessage());
            }
            
            if ($columnExists) {
                $deductionData['notes'] = $notes;
            } else {
                error_log("Column 'notes' does not exist in loan_repayments table - skipping this field");
            }
        }
        
        try {
            // Start transaction
            Database::getConnection()->beginTransaction();
            
            // Insert deduction record
            $paymentId = Database::insert('loan_repayments', $deductionData);
            
            // No need to update the loan status or balance directly
            // The database trigger 'after_loan_repayment_insert' will handle this
            
            // Record in transaction history if needed
            Database::insert('transaction_history', [
                'member_id' => $loan['member_id'],
                'transaction_type' => 'loan',
                'amount' => $amount,
                'description' => 'Loan repayment - Manual deduction',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Commit transaction
            Database::getConnection()->commit();
            
            $this->setFlash('success', 'Loan deduction successfully recorded');
            $this->redirect('/superadmin/view-loan/' . $loanId);
            
        } catch (\Exception $e) {
            // Rollback transaction on error
            Database::getConnection()->rollBack();
            
            $this->setFlash('error', 'Failed to record loan deduction: ' . $e->getMessage());
            $this->redirect('/superadmin/add-loan-deduction');
        }
    }
    
    /**
     * Show bulk loan deductions form
     * 
     * @return void
     */
    public function bulkDeductions(): void
    {
        $this->requireSuperAdmin();
        
        $this->renderSuperAdmin('superadmin/bulk-loan-deductions', [
            'current_page' => 'loans',
            'pageTitle' => 'Bulk Loan Deductions'
        ]);
    }
    
    /**
     * Process bulk loan deductions from CSV
     * 
     * @return void
     */
    public function processBulkDeductions(): void
    {
        $this->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['deduction_file'])) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/superadmin/add-loan-deduction?tab=bulk');
            return;
        }
        
        $file = $_FILES['deduction_file'];
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'File upload failed: ' . $this->getFileUploadError($file['error']));
            $this->redirect('/superadmin/add-loan-deduction?tab=bulk');
            return;
        }
        
        // Validate file type
        $mimeType = mime_content_type($file['tmp_name']);
        if ($mimeType !== 'text/plain' && $mimeType !== 'text/csv' && $mimeType !== 'application/csv') {
            $this->setFlash('error', 'Invalid file type. Please upload a CSV file.');
            $this->redirect('/superadmin/add-loan-deduction?tab=bulk');
            return;
        }
        
        // Process CSV file
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            $this->setFlash('error', 'Failed to open the uploaded file');
            $this->redirect('/superadmin/add-loan-deduction?tab=bulk');
            return;
        }
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $adminId = Auth::getAdminId();
        
        try {
            // Skip header row if exists
            $firstRow = fgetcsv($handle);
            if (is_array($firstRow) && count($firstRow) >= 3) {
                // More robust header detection - check if first cell contains "Loan ID" or similar text
                if (
                    !is_numeric($firstRow[0]) && 
                    (
                        stripos($firstRow[0], 'loan') !== false || 
                        stripos($firstRow[0], 'id') !== false || 
                        stripos($firstRow[0], 'coops') !== false ||
                        !is_numeric($firstRow[1]) && stripos($firstRow[1], 'amount') !== false
                    )
                ) {
                    // It's definitely a header, continue to next row
                    error_log("CSV Header detected and skipped: " . implode(',', $firstRow));
                } else {
                    // Not a header, reset pointer and process from beginning
                    error_log("First row doesn't look like a header, processing as data: " . implode(',', $firstRow));
                    rewind($handle);
                }
            } else {
                error_log("First row format invalid or empty, continuing with file processing");
                rewind($handle);
            }
            
            // Process each row
            while (($row = fgetcsv($handle)) !== false) {
                // Need at least 3 columns: loan identifier, amount, date
                if (count($row) < 3) {
                    $errorCount++;
                    $rowData = implode(',', $row);
                    $errors[] = "Row format invalid (need at least 3 columns): $rowData";
                    error_log("Invalid row format: $rowData");
                    continue;
                }
                
                $identifier = trim($row[0]);
                $amount = (float)trim($row[1]);
                $paymentDate = isset($row[2]) ? trim($row[2]) : '';
                $notes = isset($row[3]) ? trim($row[3]) : '';
                
                // Skip empty rows
                if (empty($identifier) && empty($amount) && empty($paymentDate)) {
                    error_log("Skipping empty row in CSV file");
                    continue;
                }
                
                // Check for empty identifier
                if (empty($identifier)) {
                    $errorCount++;
                    $errors[] = "Empty loan identifier found. Please provide a valid Loan ID or COOPS Number";
                    error_log("Empty loan identifier in CSV row: " . implode(',', $row));
                    continue;
                }
                
                // Skip rows that look like headers
                if (!is_numeric($identifier) && !is_numeric($amount) && 
                    (stripos($identifier, 'loan') !== false || stripos($identifier, 'coops') !== false)) {
                    error_log("Skipping row that looks like a header: $identifier, $amount, $paymentDate");
                    continue;
                }
                
                // Validate payment date
                if (empty($paymentDate)) {
                    // If date is empty, use current date
                    $paymentDate = date('Y-m-d');
                    error_log("Empty payment date for identifier: $identifier. Using today's date: $paymentDate");
                } else if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $paymentDate)) {
                    // Try to convert common date formats
                    $timestamp = strtotime($paymentDate);
                    if ($timestamp === false) {
                        $errorCount++;
                        $errors[] = "Invalid date format for identifier: $identifier. Use YYYY-MM-DD format.";
                        error_log("Invalid date format: $paymentDate for identifier: $identifier");
                        continue;
                    }
                    $paymentDate = date('Y-m-d', $timestamp);
                    error_log("Converted payment date from '$paymentDate' to standard format: $paymentDate");
                }
                
                // Find loan by ID or member COOPS number
                $loan = null;
                if (is_numeric($identifier)) {
                    $loan = Database::fetchOne("
                        SELECT 
                            l.*,
                            (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as total_paid
                        FROM loans l
                        WHERE l.id = ?
                    ", [(int)$identifier]);
                } else {
                    // Try to find by COOPS number
                    $loan = Database::fetchOne("
                        SELECT 
                            l.*,
                            (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as total_paid
                        FROM loans l
                        JOIN members m ON l.member_id = m.id
                        WHERE m.coop_no = ?
                        ORDER BY l.created_at DESC
                        LIMIT 1
                    ", [$identifier]);
                }
                
                if (!$loan) {
                    $errorCount++;
                    $errors[] = "Loan not found for identifier: $identifier";
                    continue;
                }
                
                if ($amount <= 0) {
                    $errorCount++;
                    $errors[] = "Invalid amount ($amount) for loan ID: {$loan['id']}";
                    continue;
                }
                
                // Calculate remaining balance
                $totalPaid = (float)($loan['total_paid'] ?? 0);
                // Only use loan_amount for balance calculation (not including ip_figure)
                $remainingBalance = (float)$loan['loan_amount'] - $totalPaid;
                
                if ($amount > $remainingBalance) {
                    $errorCount++;
                    $errors[] = "Amount ($amount) exceeds remaining balance ($remainingBalance) for loan ID: {$loan['id']}";
                    continue;
                }
                
                // Create the deduction record
                $deductionData = [
                    'loan_id' => $loan['id'],
                    'amount' => $amount,
                    'payment_date' => $paymentDate,
                    'processed_by' => $adminId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                // Add notes if provided - but first check if the column exists
                if (!empty($notes)) {
                    // Check if 'notes' column exists in loan_repayments table
                    $columnExists = false;
                    try {
                        $stmt = Database::getConnection()->query("SHOW COLUMNS FROM loan_repayments LIKE 'notes'");
                        $columnExists = ($stmt && $stmt->rowCount() > 0);
                    } catch (\Exception $e) {
                        error_log("Error checking for notes column: " . $e->getMessage());
                    }
                    
                    if ($columnExists) {
                        $deductionData['notes'] = $notes;
                    } else {
                        error_log("Column 'notes' does not exist in loan_repayments table - skipping this field");
                    }
                }
                
                // Start transaction for this record
                Database::getConnection()->beginTransaction();
                
                try {
                    // Validate admin ID exists
                    $adminExists = Database::fetchOne("SELECT id FROM admin_users WHERE id = ?", [$adminId]);
                    if (!$adminExists) {
                        // If admin ID doesn't exist, use NULL for processed_by (if allowed by schema)
                        $deductionData['processed_by'] = null;
                        error_log("Warning: Admin ID {$adminId} does not exist. Using NULL for processed_by.");
                    }
                    
                    // Log the exact data being inserted for debugging
                    error_log("Attempting to insert loan repayment: " . json_encode($deductionData));
                    
                    // Try direct SQL insert to handle potential issues
                    $fields = implode(', ', array_keys($deductionData));
                    $placeholders = implode(', ', array_fill(0, count($deductionData), '?'));
                    
                    $stmt = Database::getConnection()->prepare("INSERT INTO loan_repayments ({$fields}) VALUES ({$placeholders})");
                    $result = $stmt->execute(array_values($deductionData));
                    
                    if (!$result) {
                        throw new \Exception("Database insert failed: " . implode(' ', $stmt->errorInfo()));
                    }
                    
                    $paymentId = Database::getConnection()->lastInsertId();
                    
                    if (!$paymentId) {
                        throw new \Exception("Insert succeeded but no ID was returned");
                    }
                    
                    // Verify the loan balance was updated by the trigger
                    $updatedLoan = Database::fetchOne("SELECT balance FROM loans WHERE id = ?", [$loan['id']]);
                    
                    if (!$updatedLoan) {
                        throw new \Exception("Could not verify loan balance update");
                    }
                    
                    // Verify the balance was actually reduced
                    $expectedBalance = $remainingBalance - $amount;
                    $actualBalance = (float)$updatedLoan['balance'];
                    
                    // Allow slightly larger margin for calculation differences due to SUM in trigger
                    if (abs($actualBalance - $expectedBalance) > 0.1) { // Allow for small floating point differences
                        throw new \Exception("Loan balance not updated correctly. Expected: {$expectedBalance}, Actual: {$actualBalance}. This might be due to other concurrent updates.");
                    }
                    
                    // Record in transaction history
                    $transactionData = [
                        'member_id' => $loan['member_id'],
                        'transaction_type' => 'loan',
                        'amount' => $amount,
                        'description' => 'Loan repayment - Bulk deduction',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    error_log("Inserting transaction history: " . json_encode($transactionData));
                    
                    $fields = implode(', ', array_keys($transactionData));
                    $placeholders = implode(', ', array_fill(0, count($transactionData), '?'));
                    
                    $stmt = Database::getConnection()->prepare("INSERT INTO transaction_history ({$fields}) VALUES ({$placeholders})");
                    $result = $stmt->execute(array_values($transactionData));
                    
                    if (!$result) {
                        throw new \Exception("Transaction history insert failed: " . implode(' ', $stmt->errorInfo()));
                    }
                    
                    $transactionId = Database::getConnection()->lastInsertId();
                    
                    if (!$transactionId) {
                        throw new \Exception("Transaction history insert succeeded but no ID was returned");
                    }
                    
                    // Commit transaction
                    Database::getConnection()->commit();
                    $successCount++;
                    error_log("Successfully processed loan deduction for loan ID {$loan['id']}");
                    
                } catch (\Exception $e) {
                    // Rollback transaction on error
                    Database::getConnection()->rollBack();
                    $errorCount++;
                    $errorMessage = $e->getMessage();
                    $errors[] = "Error processing loan ID {$loan['id']}: " . $errorMessage;
                    
                    // Log detailed error information
                    error_log("Bulk loan deduction error - Loan ID: {$loan['id']}, Amount: {$amount}, Date: {$paymentDate}");
                    error_log("Error details: " . $errorMessage);
                    error_log("Error trace: " . $e->getTraceAsString());
                    
                    // Try to get specific database error
                    if ($e instanceof \PDOException) {
                        error_log("PDO Error code: " . $e->getCode());
                    }
                }
            }
            
            fclose($handle);
            
            // Set flash message based on results
            if ($errorCount === 0 && $successCount > 0) {
                $this->setFlash('success', "Successfully processed $successCount loan deductions");
                // Store bulk upload results in session for display
                $_SESSION['bulk_results'] = [
                    'total' => $successCount + $errorCount,
                    'success' => $successCount,
                    'failed' => $errorCount,
                    'errors' => []
                ];
            } else if ($successCount > 0 && $errorCount > 0) {
                $this->setFlash('warning', "Processed $successCount deductions with $errorCount errors");
                // Store bulk upload results in session for display
                $_SESSION['bulk_results'] = [
                    'total' => $successCount + $errorCount,
                    'success' => $successCount,
                    'failed' => $errorCount,
                    'errors' => $errors
                ];
            } else if ($successCount == 0 && $errorCount > 0) {
                $this->setFlash('error', "Failed to process all $errorCount deductions");
                // Store bulk upload results in session for display
                $_SESSION['bulk_results'] = [
                    'total' => $successCount + $errorCount,
                    'success' => $successCount,
                    'failed' => $errorCount,
                    'errors' => $errors
                ];
            } else {
                $this->setFlash('warning', "No loan deductions were processed");
                // Store bulk upload results in session for display
                $_SESSION['bulk_results'] = [
                    'total' => 0,
                    'success' => 0,
                    'failed' => 0,
                    'errors' => ['No valid records found in the uploaded file']
                ];
            }
            
            // Redirect back to the bulk deductions page to show results
            $this->redirect('/superadmin/add-loan-deduction?tab=bulk');
            
        } catch (\Exception $e) {
            if (is_resource($handle)) {
                fclose($handle);
            }
            
            $this->setFlash('error', 'Failed to process bulk deductions: ' . $e->getMessage());
            $this->redirect('/superadmin/add-loan-deduction?tab=bulk');
        }
    }
    
    /**
     * Download template for bulk loan deductions
     * 
     * @return void
     */
    public function downloadDeductionTemplate(): void
    {
        $this->requireSuperAdmin();
        
        // Create CSV content
        $csvContent = "Loan ID or COOPS Number,Deduction Amount,Payment Date (YYYY-MM-DD),Notes (Optional)\n";
        $csvContent .= "1,5000,2023-11-01,Monthly payment\n";
        $csvContent .= "COOPS123,2500,2023-11-01,\n";
        
        // Output CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="loan_deductions_template.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo $csvContent;
        exit;
    }
    
    /**
     * Helper function to get file upload error message
     *
     * @param int $errorCode Error code from $_FILES array
     * @return string Human-readable error message
     */
    private function getFileUploadError(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Print loan details
     * 
     * @param string|int $id The loan ID (with or without prefix)
     */
    public function print($id): void
    {
        try {
            // Debug logging
            error_log("LoanController::print - Accessed with ID: {$id}");
            
            // Check if the ID has a prefix
            $isApplication = false;
            $isInitialBalance = false;
            $cleanId = $id;
            
            // If ID starts with a letter (prefix), determine the type
            if (is_string($id) && preg_match('/^([LAI][B]?)(\d+)$/i', $id, $matches)) {
                $prefix = strtoupper($matches[1]);
                $cleanId = $matches[2];
                
                if ($prefix === 'A') {
                    $isApplication = true;
                } else if ($prefix === 'IB') {
                    $isInitialBalance = true;
                    // In this case, the ID matches the member ID for initial balance
                }
                
                error_log("Detected ID with prefix: {$prefix}, clean ID: {$cleanId}");
            }
            
            // Validate the clean ID is numeric
            if (!is_numeric($cleanId)) {
                error_log("Invalid ID format: {$id}, clean ID: {$cleanId}");
                $this->setFlash('error', 'Invalid ID format. Expected a number or ID with L/A/IB prefix.');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Cast the ID to integer
            $loanId = (int)$cleanId;
            
            if ($loanId <= 0) {
                error_log("Invalid ID provided (zero or negative): {$id}, clean ID: {$cleanId}");
                $this->setFlash('error', 'Invalid ID provided');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Get status from query parameters if available
            $status = $_GET['status'] ?? null;
            
            $loan = null;
            
            // Handle initial balance case
            if ($isInitialBalance) {
                error_log("Processing as an initial balance with member ID: {$loanId}");
                
                // Get the member's initial balance info
                $member = Database::fetchOne("
                    SELECT 
                        m.id as member_id,
                        m.name as member_name,
                        m.coop_no,
                        m.email,
                        m.phone,
                        m.loan_balance as loan_amount,
                        d.name as department_name,
                        m.bank_name,
                        m.account_number,
                        m.account_name
                    FROM members m
                    LEFT JOIN departments d ON m.department_id = d.id
                    WHERE m.id = ? AND m.loan_balance > 0
                ", [$loanId]);
                
                if ($member) {
                    error_log("Found member with initial balance: " . json_encode($member));
                    
                    // Format as a loan structure expected by the view
                    $loan = [
                        'id' => 'IB' . $member['member_id'],
                        'display_id' => 'IB' . $member['member_id'],
                        'member_id' => $member['member_id'],
                        'member_name' => $member['member_name'],
                        'coop_no' => $member['coop_no'],
                        'email' => $member['email'],
                        'phone' => $member['phone'],
                        'department_name' => $member['department_name'],
                        'loan_amount' => $member['loan_amount'],
                        'ip_figure' => 0,
                        'interest_rate' => 0,
                        'total_repayment' => $member['loan_amount'],
                        'loan_duration' => 0,
                        'status' => 'initial_balance',
                        'bank_name' => $member['bank_name'],
                        'account_number' => $member['account_number'],
                        'account_name' => $member['account_name'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'type' => 'initial_balance'
                    ];
                } else {
                    // Member with initial balance not found
                    $this->setFlash('error', "Member ID {$loanId} not found or has no initial loan balance. Please verify the ID.");
                    $this->redirect('/superadmin/loans');
                    return;
                }
            }
            // If we know it's an application (from prefix), check application table first
            else if ($isApplication) {
                error_log("Processing as an application with ID: {$loanId}");
                
                $application = $this->getApplicationForPrinting($loanId);
                if ($application) {
                    error_log("Found application in loan_applications table: " . json_encode($application));
                    $loan = $this->formatApplicationAsLoan($application);
                } else {
                    // Application not found - show specific error
                    $this->setFlash('error', "Loan application A{$loanId} not found. Please verify the application ID.");
                    $this->redirect('/superadmin/loans');
                    return;
                }
            } else {
                // First check if this loan exists in the loans table
                $loanExists = Database::fetchOne("SELECT id FROM loans WHERE id = ?", [$loanId]);
                if ($loanExists) {
                    // Check the loans table first
                    $loan = $this->getLoanForPrinting($loanId);
                    if ($loan) {
                        error_log("Found loan in loans table: " . json_encode($loan));
                    } else {
                        error_log("Loan ID {$loanId} exists but detailed query failed");
                    }
                } else {
                    error_log("Loan ID {$loanId} not found in loans table, checking applications");
                    
                    // Try in loan_applications
                    $application = $this->getApplicationForPrinting($loanId);
                    if ($application) {
                        error_log("Found application in loan_applications table: " . json_encode($application));
                        $loan = $this->formatApplicationAsLoan($application);
                        $isApplication = true;
                    } else {
                        // Neither loan nor application found - show suggestions
                        $this->showLoanNotFoundMessage($loanId);
                        return;
                    }
                }
            }
            
            if (!$loan) {
                error_log("No loan or application found with ID {$loanId} after queries");
                $this->showLoanNotFoundMessage($loanId);
                return;
            }
            
            // Debug output to error log
            error_log("Printing ID: {$loanId}, data: " . json_encode($loan));
            
            // Fix for missing or zero total_repayment and ip_figure (admin charge)
            if (!$isApplication && !$isInitialBalance && (empty($loan['total_repayment']) || $loan['total_repayment'] == 0 || empty($loan['ip_figure']) || $loan['ip_figure'] == 0)) {
                // Calculate total repayment if it's missing
                $interestRate = isset($loan['interest_rate']) && $loan['interest_rate'] > 0 ? 
                    $loan['interest_rate'] : 5.0; // Default to 5% if not set
                    
                $adminCharges = $loan['loan_amount'] * ($interestRate / 100);
                $loan['ip_figure'] = $adminCharges; // Fix admin charge amount
                $loan['total_repayment'] = $loan['loan_amount'] + $adminCharges; // Fix total repayment
                
                // Update the loan record in the database
                Database::update('loans', [
                    'ip_figure' => $adminCharges,
                    'total_repayment' => $loan['total_repayment']
                ], ['id' => $loanId]);
                
                error_log("Fixed loan values for print: Admin Charge: {$adminCharges}, Total Repayment: {$loan['total_repayment']}");
            }
            
            // Get loan repayments if it's a regular loan
            $repayments = [];
            $totalPaid = 0;
            $remainingBalance = 0;
            
            if (!$isApplication && !$isInitialBalance) {
                $repayments = Database::fetchAll(
                    "SELECT 
                        lr.*,
                        au.name as processed_by_name
                    FROM loan_repayments lr
                    LEFT JOIN admin_users au ON lr.processed_by = au.id
                    WHERE lr.loan_id = ?
                    ORDER BY lr.payment_date DESC",
                    [$loanId]
                );
                
                // Calculate totals
                $totalPaid = array_sum(array_column($repayments, 'amount'));
                $remainingBalance = $loan['total_repayment'] - $totalPaid;
                
                // Get admin who approved the loan
                if (!empty($loan['approved_by'])) {
                    $approver = Database::fetchOne(
                        "SELECT name FROM admin_users WHERE id = ?",
                        [$loan['approved_by']]
                    );
                    $loan['approved_by_name'] = $approver['name'] ?? 'Unknown';
                }
            } else if ($isInitialBalance) {
                // For initial balance, the remaining balance is the full amount
                $remainingBalance = $loan['loan_amount'];
            }
            
            // Render the print view
            $this->renderSuperAdmin('superadmin/print-loan', [
                'loan' => $loan,
                'repayments' => $repayments,
                'total_paid' => $totalPaid,
                'remaining_balance' => $remainingBalance,
                'is_application' => $isApplication,
                'is_initial_balance' => $isInitialBalance,
                'current_page' => 'loans',
                'pageTitle' => $isApplication ? 'Print Loan Application' : ($isInitialBalance ? 'Print Initial Balance' : 'Print Loan Details')
            ]);
            
        } catch (\Exception $e) {
            // Log error for debugging
            error_log("Error in LoanController::print - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Log PDO errors in detail
            if ($e instanceof \PDOException) {
                error_log("PDO Error Code: " . $e->getCode());
                error_log("SQL State: " . $e->errorInfo[0] ?? 'unknown');
                error_log("Driver Error Code: " . $e->errorInfo[1] ?? 'unknown');
                error_log("Driver Error Message: " . $e->errorInfo[2] ?? 'unknown');
            }
            
            $this->setFlash('error', 'An error occurred while trying to print loan details: ' . $e->getMessage());
            $this->redirect('/superadmin/loans');
        }
    }
    
    /**
     * Helper method to show a detailed "loan not found" message with suggestions
     * 
     * @param int $loanId The ID that wasn't found
     * @return void
     */
    private function showLoanNotFoundMessage(int $loanId): void
    {
        try {
            $loanCount = Database::fetchOne("SELECT COUNT(*) as count FROM loans");
            $appCount = Database::fetchOne("SELECT COUNT(*) as count FROM loan_applications");
            error_log("Database check: Found {$loanCount['count']} loans and {$appCount['count']} applications");
            
            // Get the highest IDs to guide the user
            $maxLoanId = Database::fetchOne("SELECT MAX(id) as max_id FROM loans");
            $maxAppId = Database::fetchOne("SELECT MAX(id) as max_id FROM loan_applications");
            
            // Find nearby valid loan IDs to suggest alternatives
            $validLoanIds = Database::fetchAll("SELECT id FROM loans ORDER BY ABS(id - ?) ASC LIMIT 5", [$loanId]);
            $validAppIds = Database::fetchAll("SELECT id FROM loan_applications ORDER BY ABS(id - ?) ASC LIMIT 5", [$loanId]);
            
            $errorMsg = "ID {$loanId} not found. ";
            
            // Suggest valid loan IDs
            if (!empty($validLoanIds)) {
                $nearbyIds = array_column($validLoanIds, 'id');
                $errorMsg .= "Available loan IDs: " . implode(', ', $nearbyIds) . " (use with L prefix, e.g., L" . $nearbyIds[0] . "). ";
            }
            
            if (!empty($validAppIds)) {
                $nearbyAppIds = array_column($validAppIds, 'id');
                $errorMsg .= "Available application IDs: " . implode(', ', $nearbyAppIds) . " (use with A prefix, e.g., A" . $nearbyAppIds[0] . "). ";
            }
            
            // Still provide the range info
            if (isset($maxLoanId['max_id']) && isset($maxAppId['max_id'])) {
                if ($loanCount['count'] > 0) {
                    $errorMsg .= "Valid loan ID range: 1-{$maxLoanId['max_id']}. ";
                }
                if (isset($maxAppId['max_id']) && $maxAppId['max_id'] > 0) {
                    $errorMsg .= "Valid application ID range: 1-{$maxAppId['max_id']}.";
                }
            }
            
            $this->setFlash('error', $errorMsg);
        } catch (\Exception $e) {
            error_log("Error generating loan not found message: " . $e->getMessage());
            $this->setFlash('error', 'Loan or application not found');
        }
        
        $this->redirect('/superadmin/loans');
    }
    
    /**
     * Helper method to get loan data for printing
     * 
     * @param int $loanId The loan ID
     * @return array|null The loan data or null if not found
     */
    private function getLoanForPrinting(int $loanId): ?array
    {
        try {
            // Debug logging
            error_log("getLoanForPrinting - Attempting to get loan ID: {$loanId}");
            
            // First check if the loan exists with a simple query
            $loanExists = Database::fetchOne("SELECT id FROM loans WHERE id = ?", [$loanId]);
            if (!$loanExists) {
                error_log("getLoanForPrinting - Loan ID {$loanId} not found in the loans table");
                return null;
            }
            
            // If the loan exists, get full details
            $loan = Database::fetchOne(
                "SELECT 
                    l.*,
                    CONCAT('L', l.id) as display_id,
                    m.name as member_name,
                    m.coop_no,
                    m.email,
                    m.phone,
                    d.name as department_name,
                    m.bank_name,
                    m.account_number,
                    m.account_name
                FROM loans l
                LEFT JOIN members m ON l.member_id = m.id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE l.id = ?",
                [$loanId]
            );
            
            if ($loan) {
                error_log("getLoanForPrinting - Successfully retrieved loan data: " . json_encode($loan));
            } else {
                error_log("getLoanForPrinting - Failed to retrieve full loan data despite loan ID existing");
            }
            
            return $loan;
        } catch (\Exception $e) {
            error_log("Error in getLoanForPrinting - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Helper method to get loan application data for printing
     * 
     * @param int $applicationId The application ID
     * @return array|null The application data or null if not found
     */
    private function getApplicationForPrinting(int $applicationId): ?array
    {
        try {
            // Debug logging
            error_log("getApplicationForPrinting - Attempting to get application ID: {$applicationId}");
            
            // First check if the application exists with a simple query
            $applicationExists = Database::fetchOne("SELECT id FROM loan_applications WHERE id = ?", [$applicationId]);
            if (!$applicationExists) {
                error_log("getApplicationForPrinting - Application ID {$applicationId} not found in the loan_applications table");
                return null;
            }
            
            // If the application exists, get full details
            $application = Database::fetchOne(
                "SELECT 
                    la.*,
                    m.name as member_name,
                    m.coop_no,
                    m.email,
                    m.phone,
                    d.name as department_name,
                    COALESCE(la.bank_name, m.bank_name) as bank_name,
                    COALESCE(la.account_number, m.account_number) as account_number,
                    COALESCE(la.account_name, m.account_name) as account_name,
                    la.account_type
                FROM loan_applications la
                LEFT JOIN members m ON la.member_id = m.id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE la.id = ?",
                [$applicationId]
            );
            
            if ($application) {
                error_log("getApplicationForPrinting - Successfully retrieved application data: " . json_encode($application));
            } else {
                error_log("getApplicationForPrinting - Failed to retrieve full application data despite application ID existing");
            }
            
            return $application;
        } catch (\Exception $e) {
            error_log("Error in getApplicationForPrinting - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Helper method to format an application as a loan
     * 
     * @param array $application The application data
     * @return array The formatted loan data
     */
    private function formatApplicationAsLoan(array $application): array
    {
        $loan = $application;
        $loan['display_id'] = 'A' . $application['id'];
        $loan['loan_amount'] = $application['loan_amount'] ?? 0;
        $loan['ip_figure'] = $application['ip_figure'] ?? 0;
        $loan['repayment_period'] = $application['loan_duration'] ?? 12;
        return $loan;
    }
    
    /**
     * Approve a loan application
     * 
     * @param string $id The loan application ID
     * @return void
     */
    public function approveApplication(string $id): void
    {
        $this->requireSuperAdmin();
        
        try {
            // Get the application details
            $application = Database::fetchOne("
                SELECT * FROM loan_applications WHERE id = ?
            ", [(int)$id]);
            
            if (!$application) {
                $this->setFlash('error', 'Loan application not found.');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Check if application is pending
            if ($application['status'] !== 'pending') {
                $this->setFlash('error', 'This application is already ' . $application['status'] . '.');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Update the application status
            Database::update('loan_applications', [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s'),
                'comment' => 'Approved by admin'
            ], ['id' => (int)$id]);
            
            // Create an actual loan record
            $loanNumber = 'L' . date('Y') . str_pad((string)mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $adminId = Auth::getAdminId();
            
            // Fixed interest rate of 5%
            $interestRate = 5.00;
            
            // Calculate admin charges as 5% of loan amount
            $loanAmount = (float)$application['loan_amount'];
            $adminCharges = $loanAmount * ($interestRate / 100);
            
            // Calculate total repayment - loan amount plus admin charges
            $totalRepayment = $loanAmount + $adminCharges;
            
            // Calculate monthly payment amount (IP figure from application or calculated amount)
            $repaymentPeriod = (int)($application['loan_duration'] ?? 12);
            $monthlyPayment = $totalRepayment / $repaymentPeriod;
            
            // Debug logging
            error_log("Loan approval calculation: Loan Amount: {$loanAmount}, Admin Charges: {$adminCharges}, Total Repayment: {$totalRepayment}");
            
            $loanData = [
                'member_id' => (int)$application['member_id'],
                'loan_amount' => $loanAmount,
                'ip_figure' => $adminCharges,  // This is the admin charge (5%)
                'total_repayment' => $totalRepayment,
                'balance' => $totalRepayment,
                'interest_rate' => $interestRate,
                'repayment_period' => $repaymentPeriod,
                'purpose' => $application['purpose'] ?? '',
                'status' => 'approved',
                'approved_by' => $adminId,
                'approval_date' => date('Y-m-d H:i:s'),
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+' . $repaymentPeriod . ' months')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $loanId = Database::insert('loans', $loanData);
            
            if (!$loanId) {
                throw new \Exception("Failed to create loan record.");
            }
            
            // Log the approval action
            $logDetails = json_encode([
                'action' => 'approve_application',
                'application_id' => $id,
                'loan_id' => $loanId,
                'member_id' => $application['member_id'],
                'loan_amount' => $loanAmount,
                'admin_charges' => $adminCharges,
                'total_repayment' => $totalRepayment
            ]);
            
            Database::insert('audit_logs', [
                'user_id' => $adminId,
                'user_type' => 'admin',
                'action_type' => 'loan',
                'action' => 'approve_application',
                'details' => $logDetails,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
            // Create notification for member
            Database::insert('notifications', [
                'user_id' => $application['member_id'],
                'user_type' => 'member',
                'type' => 'loan_approved',
                'title' => 'Loan Application Approved',
                'message' => 'Your loan application for ₦' . number_format($loanAmount, 2) . ' has been approved. Monthly payment: ₦' . number_format($monthlyPayment, 2) . ' for ' . $repaymentPeriod . ' months.',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->setFlash('success', 'Loan application approved and loan created successfully.');
            $this->redirect('/superadmin/view-loan/' . $loanId);
            
        } catch (\Exception $e) {
            error_log('Error in LoanController::approveApplication: ' . $e->getMessage());
            
            // Log more detailed information about the error
            if ($e instanceof \PDOException) {
                error_log('Database error code: ' . $e->getCode());
                error_log('SQL State: ' . $e->errorInfo[0] ?? 'unknown');
                error_log('Driver error code: ' . $e->errorInfo[1] ?? 'unknown');
                error_log('Driver error message: ' . $e->errorInfo[2] ?? 'unknown');
            }
            
            // Log the data we were trying to insert
            if (isset($loanData)) {
                error_log('Loan data that failed to insert: ' . json_encode($loanData));
            }
            
            $this->setFlash('error', 'An error occurred while processing the loan application: ' . $e->getMessage());
            $this->redirect('/superadmin/loans');
        }
    }
    
    /**
     * Decline a loan application
     * 
     * @param string $id The loan application ID
     * @return void
     */
    public function declineApplication(string $id): void
    {
        $this->requireSuperAdmin();
        
        try {
            // Get the application details
            $application = Database::fetchOne("
                SELECT * FROM loan_applications WHERE id = ?
            ", [(int)$id]);
            
            if (!$application) {
                $this->setFlash('error', 'Loan application not found.');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Check if application is pending
            if ($application['status'] !== 'pending') {
                $this->setFlash('error', 'This application is already ' . $application['status'] . '.');
                $this->redirect('/superadmin/loans');
                return;
            }
            
            // Get reason for declining
            $reason = $_POST['reason'] ?? '';
            if (empty($reason) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $reason = 'Declined by admin';
            }
            
            // If this is a GET request, show the decline form
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->renderSuperAdmin('superadmin/decline-loan-application', [
                    'application' => $application,
                    'pageTitle' => 'Decline Loan Application',
                    'current_page' => 'loans'
                ]);
                return;
            }
            
            // Update the application status
            Database::update('loan_applications', [
                'status' => 'rejected',
                'updated_at' => date('Y-m-d H:i:s'),
                'comment' => $reason
            ], ['id' => (int)$id]);
            
            // Log the decline action
            $adminId = Auth::getAdminId();
            $logDetails = json_encode([
                'action' => 'decline_application',
                'application_id' => $id,
                'member_id' => $application['member_id'],
                'loan_amount' => $application['loan_amount'],
                'reason' => $reason
            ]);
            
            Database::insert('audit_logs', [
                'user_id' => $adminId,
                'user_type' => 'admin',
                'action_type' => 'loan',
                'action' => 'decline_application',
                'details' => $logDetails,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
            // Create notification for member
            Database::insert('notifications', [
                'user_id' => $application['member_id'],
                'user_type' => 'member',
                'type' => 'loan_declined',
                'title' => 'Loan Application Declined',
                'message' => 'Your loan application for ₦' . number_format((float)$application['loan_amount'], 2) . ' has been declined. Reason: ' . $reason,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            $this->setFlash('success', 'Loan application has been declined.');
            $this->redirect('/superadmin/loans');
            
        } catch (\Exception $e) {
            error_log('Error in LoanController::declineApplication: ' . $e->getMessage());
            $this->setFlash('error', 'An error occurred while processing the loan application: ' . $e->getMessage());
            $this->redirect('/superadmin/loans');
        }
    }
    
    /**
     * Check if an ID exists in loan_applications but not in loans
     * 
     * @param int $id The ID to check
     * @return bool|array False if not found, or the application data if found
     */
    private function checkIdInApplicationsOnly(int $id): bool|array
    {
        try {
            // First check if it exists in applications but not in loans
            $applicationExists = Database::fetchOne("
                SELECT la.id, la.status, la.loan_amount, m.name as member_name 
                FROM loan_applications la
                LEFT JOIN members m ON la.member_id = m.id
                WHERE la.id = ?
            ", [$id]);
            
            if (!$applicationExists) {
                return false;
            }
            
            // Check if a loan exists with the same ID
            $loanExists = Database::fetchOne("SELECT id FROM loans WHERE id = ?", [$id]);
            if ($loanExists) {
                return false; // It exists in both tables, so not exclusively in applications
            }
            
            return $applicationExists;
        } catch (\Exception $e) {
            error_log("Error in checkIdInApplicationsOnly: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Process loan balances to ensure all member loans are reflected properly
     * This method ensures that even members with initial balances have proper loan records
     */
    public function processLoanBalance(): void
    {
        // Start transaction
        $db = Database::getConnection();
        $db->beginTransaction();
        
        try {
            // Get all members with loan_balance but no corresponding loan records
            $members = Database::fetchAll("
                SELECT m.id, m.name, m.coop_no, m.loan_balance
                FROM members m
                LEFT JOIN loans l ON m.id = l.member_id
                WHERE m.loan_balance > 0 AND l.id IS NULL
            ");
            
            $processed = 0;
            
            foreach ($members as $member) {
                $memberId = $member['id'];
                $loanBalance = $member['loan_balance'];
                
                // Only create records if balance is significant
                if ($loanBalance > 0) {
                    // Get loan settings
                    $settings = Database::fetchOne("SELECT * FROM loan_settings WHERE is_active = 1");
                    $interestRate = $settings ? ($settings['interest_rate'] ?? 5) : 5;
                    $tenureMonths = 12; // Default tenure
                    
                    // Create loan record
                    $loanId = Database::insert('loans', [
                        'member_id' => $memberId,
                        'loan_amount' => $loanBalance,
                        'interest_rate' => $interestRate,
                        'tenure_months' => $tenureMonths,
                        'total_payable' => $loanBalance * (1 + ($interestRate/100)),
                        'monthly_repayment' => ($loanBalance * (1 + ($interestRate/100))) / $tenureMonths,
                        'status' => 'approved',
                        'purpose' => 'Initial loan balance',
                        'approval_date' => date('Y-m-d H:i:s'),
                        'approved_by' => Auth::getAdminId(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    if ($loanId) {
                        // Create loan repayment plan
                        $this->createInitialRepaymentPlan($loanId, $loanBalance, $interestRate, $tenureMonths);
                        
                        // Record initial transaction
                        Database::insert('loan_transactions', [
                            'loan_id' => $loanId,
                            'member_id' => $memberId,
                            'amount' => $loanBalance,
                            'transaction_type' => 'disbursement',
                            'processed_by' => Auth::getAdminId(),
                            'notes' => 'Initial loan balance conversion',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        $processed++;
                    }
                }
            }
            
            // Commit transaction
            $db->commit();
            
            $this->setFlash('success', "Processed {$processed} member loan balances successfully.");
        } catch (\Exception $e) {
            // Rollback transaction
            $db->rollBack();
            $this->setFlash('error', 'Error processing loan balances: ' . $e->getMessage());
        }
        
        $this->redirect('/superadmin/loans');
    }

    /**
     * Helper method to create repayment plan for an initial loan
     */
    private function createInitialRepaymentPlan(int $loanId, float $loanAmount, float $interestRate, int $tenureMonths): void
    {
        $totalInterest = $loanAmount * ($interestRate / 100);
        $totalPayable = $loanAmount + $totalInterest;
        $monthlyRepayment = $totalPayable / $tenureMonths;
        
        // Create repayment schedule
        $startDate = new \DateTime();
        
        for ($i = 1; $i <= $tenureMonths; $i++) {
            $dueDate = clone $startDate;
            $dueDate->modify("+{$i} months");
            
            Database::insert('loan_repayments', [
                'loan_id' => $loanId,
                'installment_number' => $i,
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $monthlyRepayment,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
} 