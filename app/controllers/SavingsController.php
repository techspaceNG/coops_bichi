<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Utility;
use App\Models\Member;
use App\Config\Database;

/**
 * Savings Controller
 * Handles savings functionality for members
 */
final class SavingsController extends Controller
{
    /**
     * Display member savings dashboard
     *
     * @return void
     */
    public function index(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get financial summary
        $financialSummary = Member::getFinancialSummary($memberId);
        $savings_balance = $financialSummary['savings_balance'] ?? 0.0;
        
        // Get monthly contribution
        try {
            $savingsInfo = Database::fetchOne(
                "SELECT monthly_deduction FROM savings 
                WHERE member_id = ?",
                [$memberId]
            );
            
            $monthlyContribution = $savingsInfo ? (float)$savingsInfo['monthly_deduction'] : 0.0;
            
            // Get savings transactions
            $transactions = Database::fetchAll(
                "SELECT * FROM savings_transactions 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT 10",
                [$memberId]
            );
            
            // Get withdrawal requests
            $withdrawalRequests = Database::fetchAll(
                "SELECT * FROM withdrawal_requests 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT 5",
                [$memberId]
            );
        } catch (\PDOException $e) {
            // If tables don't exist or database error
            $monthlyContribution = 0.0;
            $transactions = [];
            $withdrawalRequests = [];
        }
        
        $this->render('member/savings/index', [
            'title' => 'My Savings - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'savings_balance' => $savings_balance,
            'monthly_contribution' => $monthlyContribution,
            'transactions' => $transactions,
            'withdrawal_requests' => $withdrawalRequests
        ]);
    }
    
    /**
     * Display savings statement
     *
     * @return void
     */
    public function statement(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get query parameters
        $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-3 months'));
        $end_date = $_GET['end_date'] ?? date('Y-m-d');
        $transaction_type = $_GET['transaction_type'] ?? 'all';
        
        // Get page number from query string
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        try {
            // Build query
            $query = "SELECT * FROM savings_transactions WHERE member_id = ?";
            $params = [$memberId];
            
            if ($transaction_type !== 'all') {
                $query .= " AND transaction_type = ?";
                $params[] = $transaction_type;
            }
            
            $query .= " AND created_at BETWEEN ? AND ?";
            $params[] = $start_date . ' 00:00:00';
            $params[] = $end_date . ' 23:59:59';
            
            // Count total records
            $countQuery = str_replace("SELECT *", "SELECT COUNT(*) as count", $query);
            $countResult = Database::fetchOne($countQuery, $params);
            $totalRecords = $countResult ? (int)$countResult['count'] : 0;
            $totalPages = ceil($totalRecords / $perPage);
            
            // Get paginated results
            $query .= " ORDER BY created_at DESC LIMIT ?, ?";
            $params[] = $offset;
            $params[] = $perPage;
            
            $transactions = Database::fetchAll($query, $params);
            
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'offset' => $offset,
                'total_records' => $totalRecords
            ];
        } catch (\PDOException $e) {
            // If table doesn't exist or database error
            $transactions = [];
            $pagination = [
                'current_page' => 1,
                'total_pages' => 1,
                'per_page' => $perPage,
                'offset' => 0,
                'total_records' => 0
            ];
        }
        
        $this->render('member/savings/statement', [
            'title' => 'Savings Statement - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'transactions' => $transactions,
            'pagination' => $pagination,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'transaction_type' => $transaction_type
        ]);
    }
    
    /**
     * Display and process withdrawal request
     *
     * @return void
     */
    public function withdraw(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get financial summary
        $financialSummary = Member::getFinancialSummary($memberId);
        $savings_balance = $financialSummary['savings_balance'] ?? 0.0;
        
        $errors = [];
        $request_success = false;
        
        // Check if member already has a pending withdrawal request
        try {
            $pendingRequest = Database::fetchOne(
                "SELECT * FROM withdrawal_requests 
                WHERE member_id = ? AND status = 'pending'",
                [$memberId]
            );
            
            $hasPendingRequest = !empty($pendingRequest);
        } catch (\PDOException $e) {
            // If table doesn't exist or database error
            $pendingRequest = null;
            $hasPendingRequest = false;
        }
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitizeInput($_POST);
            
            // Validate form data
            if (empty($input['amount'])) {
                $errors['amount'] = 'Withdrawal amount is required';
            } elseif (!is_numeric($input['amount'])) {
                $errors['amount'] = 'Withdrawal amount must be a number';
            } elseif ((float)$input['amount'] <= 0) {
                $errors['amount'] = 'Withdrawal amount must be greater than zero';
            } elseif ((float)$input['amount'] > $savings_balance) {
                $errors['amount'] = 'Withdrawal amount cannot exceed your available balance';
            }
            
            if (empty($input['purpose'])) {
                $errors['purpose'] = 'Withdrawal purpose is required';
            }
            
            if (empty($errors)) {
                try {
                    // Check if the table exists, create it if not
                    try {
                        Database::execute("SELECT 1 FROM withdrawal_requests LIMIT 1");
                    } catch (\PDOException $e) {
                        // Create the table if it doesn't exist
                        Database::execute("
                            CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `member_id` int(11) NOT NULL,
                                `amount` decimal(10,2) NOT NULL,
                                `purpose` text NOT NULL,
                                `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
                                `admin_remarks` text,
                                `processed_by` int(11) DEFAULT NULL,
                                `processed_at` datetime DEFAULT NULL,
                                `created_at` datetime NOT NULL,
                                `updated_at` datetime NOT NULL,
                                PRIMARY KEY (`id`),
                                KEY `member_id` (`member_id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                        ");
                    }
                    
                    // Create withdrawal request
                    $requestData = [
                        'member_id' => $memberId,
                        'amount' => (float)$input['amount'],
                        'purpose' => $input['purpose'],
                        'status' => 'pending',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $requestId = Database::insert('withdrawal_requests', $requestData);
                    
                    if ($requestId) {
                        $request_success = true;
                        $this->setFlash('success', 'Withdrawal request submitted successfully. Your request is pending approval.');
                        $this->redirect('/member/savings/withdrawals');
                    } else {
                        $errors['request'] = 'Failed to submit withdrawal request. Please try again.';
                    }
                } catch (\PDOException $e) {
                    error_log('Withdrawal request error: ' . $e->getMessage());
                    $errors['request'] = 'Database error occurred. Please try again later.';
                }
            }
        }
        
        $this->render('member/savings/withdraw', [
            'title' => 'Request Withdrawal - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'savings_balance' => $savings_balance,
            'currentBalance' => $savings_balance,
            'maxWithdrawalAmount' => round($savings_balance * 0.8, 2), // Setting max withdrawal to 80% of balance
            'minimumBalance' => 1000.00, // Minimum balance to maintain
            'hasPendingRequest' => $hasPendingRequest,
            'pendingRequest' => $pendingRequest,
            'errors' => $errors,
            'request_success' => $request_success
        ]);
    }
    
    /**
     * Display withdrawal requests
     *
     * @return void
     */
    public function withdrawals(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get page number from query string
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        try {
            // Get total count
            $totalRecords = Database::fetchOne(
                "SELECT COUNT(*) as count FROM withdrawal_requests WHERE member_id = ?",
                [$memberId]
            );
            
            $totalRecords = $totalRecords ? (int)$totalRecords['count'] : 0;
            $totalPages = ceil($totalRecords / $perPage);
            
            // Get paginated requests
            $withdrawalRequests = Database::fetchAll(
                "SELECT * FROM withdrawal_requests 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?, ?",
                [$memberId, $offset, $perPage]
            );
            
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'offset' => $offset,
                'total_records' => $totalRecords
            ];
        } catch (\PDOException $e) {
            // If table doesn't exist or database error
            $withdrawalRequests = [];
            $pagination = [
                'current_page' => 1,
                'total_pages' => 1,
                'per_page' => $perPage,
                'offset' => 0,
                'total_records' => 0
            ];
        }
        
        $this->render('member/savings/withdrawals', [
            'title' => 'Withdrawal Requests - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'withdrawalRequests' => $withdrawalRequests,
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Display withdrawal request details
     *
     * @param int $id Withdrawal request ID
     * @return void
     */
    public function withdrawalDetails(int $id): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        try {
            // Get withdrawal request details
            $withdrawalRequest = Database::fetchOne(
                "SELECT * FROM withdrawal_requests WHERE id = ? AND member_id = ?",
                [$id, $memberId]
            );
            
            if (!$withdrawalRequest) {
                $this->setFlash('error', 'Withdrawal request not found');
                $this->redirect('/member/savings/withdrawals');
            }
            
            // Get admin name if processed
            $adminName = null;
            if ($withdrawalRequest['processed_by']) {
                $admin = Database::fetchOne(
                    "SELECT name FROM admin_users WHERE id = ?",
                    [$withdrawalRequest['processed_by']]
                );
                
                $adminName = $admin ? $admin['name'] : 'Unknown Admin';
            }
        } catch (\PDOException $e) {
            // If table doesn't exist or database error
            $this->setFlash('error', 'Could not retrieve withdrawal request details');
            $this->redirect('/member/savings/withdrawals');
        }
        
        $this->render('member/savings/withdrawal_detail', [
            'title' => 'Withdrawal Request Details - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'withdrawalRequest' => $withdrawalRequest,
            'adminName' => $adminName
        ]);
    }
    
    /**
     * Display and process update savings contribution
     *
     * @return void
     */
    public function updateContribution(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        // Get current contribution
        try {
            $savingsInfo = Database::fetchOne(
                "SELECT monthly_deduction FROM savings 
                WHERE member_id = ?",
                [$memberId]
            );
            
            $currentContribution = $savingsInfo ? (float)$savingsInfo['monthly_deduction'] : 0.0;
        } catch (\PDOException $e) {
            // If table doesn't exist or database error
            $currentContribution = 0.0;
        }
        
        $errors = [];
        $update_success = false;
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitizeInput($_POST);
            
            // Validate form data
            if (empty($input['monthly_contribution'])) {
                $errors['monthly_contribution'] = 'Monthly contribution amount is required';
            } elseif (!is_numeric($input['monthly_contribution'])) {
                $errors['monthly_contribution'] = 'Monthly contribution must be a number';
            } elseif ((float)$input['monthly_contribution'] < 1000) {
                $errors['monthly_contribution'] = 'Monthly contribution must be at least ₦1,000';
            }
            
            if (empty($errors)) {
                try {
                    // Check if savings record exists
                    $savingsExists = Database::fetchOne(
                        "SELECT id FROM savings WHERE member_id = ?",
                        [$memberId]
                    );
                    
                    if ($savingsExists) {
                        // Update existing record
                        $updated = Database::update(
                            'savings',
                            ['monthly_deduction' => (float)$input['monthly_contribution']],
                            ['member_id' => $memberId]
                        );
                    } else {
                        // Create new record
                        $created = Database::insert('savings', [
                            'member_id' => $memberId,
                            'monthly_deduction' => (float)$input['monthly_contribution'],
                            'cumulative_amount' => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        $updated = $created > 0;
                    }
                    
                    if ($updated) {
                        $update_success = true;
                        $this->setFlash('success', 'Monthly contribution updated successfully');
                        $this->redirect('/member/savings');
                    } else {
                        $errors['update'] = 'Failed to update monthly contribution. Please try again.';
                    }
                } catch (\PDOException $e) {
                    error_log('Update contribution error: ' . $e->getMessage());
                    $errors['update'] = 'Database error occurred. Please try again later.';
                }
            }
        }
        
        $this->render('member/savings/update', [
            'title' => 'Update Monthly Contribution - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'currentContribution' => $currentContribution,
            'minimumContribution' => 1000.00, // Add minimum contribution amount
            'errors' => $errors,
            'update_success' => $update_success
        ]);
    }
    
    /**
     * Display savings calculator
     *
     * @return void
     */
    public function calculator(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/logout');
        }
        
        $this->render('member/savings/calculator', [
            'title' => 'Savings Calculator - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member
        ]);
    }
} 