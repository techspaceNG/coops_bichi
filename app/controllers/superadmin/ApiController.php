<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;

/**
 * ApiController for Superadmin
 * Handles various API endpoints for superadmin dashboard
 */
final class ApiController extends AbstractController
{
    /**
     * Search members API endpoint
     * Returns JSON response with matched members
     */
    public function searchMembers(): void
    {
        try {
            // Disable error output to prevent HTML in JSON response
            ini_set('display_errors', 0);
            error_reporting(0);
            
            // Set proper headers
            header('Content-Type: application/json');
            
            // Check if request is AJAX
            if (!Request::isAjax()) {
                echo json_encode(['error' => 'Invalid request']);
                exit;
            }
            
            // Get search term from either 'q' or 'term' parameter
            $searchTerm = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
            if (empty($searchTerm)) {
                $searchTerm = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
            }
            
            if (empty($searchTerm) || strlen($searchTerm) < 2) {
                echo json_encode(['members' => []]);
                exit;
            }
            
            // Prepare query
            $query = "SELECT 
                        id, 
                        coop_no, 
                        name,
                        email,
                        phone,
                        department_id,
                        (SELECT name FROM departments WHERE id = members.department_id) as department_name
                    FROM members
                    WHERE 
                        coop_no LIKE ? OR
                        name LIKE ? OR
                        email LIKE ? OR
                        phone LIKE ?
                    LIMIT 10";
            
            $searchParam = "%{$searchTerm}%";
            $params = [$searchParam, $searchParam, $searchParam, $searchParam];
            
            $members = Database::fetchAll($query, $params);
            
            echo json_encode(['members' => $members]);
            exit;
        } catch (\Exception $e) {
            // Log the error
            error_log("Error in searchMembers: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            
            // Return a friendly error message
            echo json_encode(['error' => 'An error occurred while searching for members.']);
            exit;
        }
    }
    
    /**
     * Get member details API endpoint
     * Returns JSON response with member details
     */
    public function getMemberDetails(): void
    {
        try {
            // Disable error output to prevent HTML in JSON response
            ini_set('display_errors', 0);
            error_reporting(0);
            
            // Set proper headers
            header('Content-Type: application/json');
            
            // Check if request is AJAX
            if (!Request::isAjax()) {
                echo json_encode(['error' => 'Invalid request']);
                exit;
            }
            
            // Get member ID
            $memberId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            
            if (!$memberId) {
                echo json_encode(['error' => 'Invalid member ID']);
                exit;
            }
            
            // Get member details
            $member = Database::fetchOne(
                "SELECT 
                    m.*,
                    d.name as department_name
                FROM members m
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE m.id = ?",
                [$memberId]
            );
            
            if (!$member) {
                echo json_encode(['error' => 'Member not found']);
                exit;
            }
            
            // Get member savings info
            $savingsInfo = Database::fetchOne(
                "SELECT 
                    s.*,
                    COALESCE(
                        (SELECT SUM(amount) FROM savings_transactions 
                         WHERE member_id = ? AND transaction_type = 'deposit'), 
                        0
                    ) as total_deposits,
                    COALESCE(
                        (SELECT SUM(amount) FROM savings_transactions 
                         WHERE member_id = ? AND transaction_type = 'withdrawal'), 
                        0
                    ) as total_withdrawals
                FROM savings s
                WHERE s.member_id = ?",
                [$memberId, $memberId, $memberId]
            );
            
            $member['savings'] = $savingsInfo ?: [
                'monthly_deduction' => 0,
                'cumulative_amount' => 0,
                'total_deposits' => 0,
                'total_withdrawals' => 0
            ];
            
            echo json_encode(['member' => $member]);
            exit;
            
        } catch (\Exception $e) {
            // Log the error
            error_log("Error in getMemberDetails: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            
            // Return a friendly error message
            echo json_encode(['error' => 'An error occurred while retrieving member details.']);
            exit;
        }
    }
    
    /**
     * Get department members API endpoint
     * Returns JSON response with members in a department
     */
    public function getDepartmentMembers(): void
    {
        // Check if request is AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Get department ID
        $departmentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$departmentId) {
            Response::json(['error' => 'Invalid department ID'], 400);
            return;
        }
        
        // Get members
        $members = Database::fetchAll(
            "SELECT 
                id, 
                coop_no, 
                name,
                email,
                phone,
                is_active
            FROM members
            WHERE department_id = ?
            ORDER BY name",
            [$departmentId]
        );
        
        Response::json(['members' => $members], 200);
    }
    
    /**
     * Get dashboard stats API endpoint
     * Returns JSON response with dashboard statistics
     */
    public function getDashboardStats(): void
    {
        // Check if request is AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Get total members
        $totalMembers = Database::fetchOne("SELECT COUNT(*) as count FROM members")['count'];
        
        // Get active members
        $activeMembers = Database::fetchOne("SELECT COUNT(*) as count FROM members WHERE is_active = 1")['count'];
        
        // Get total savings
        $totalSavings = Database::fetchOne(
            "SELECT 
                (SUM(CASE WHEN transaction_type = 'deposit' THEN amount ELSE 0 END) - 
                 SUM(CASE WHEN transaction_type = 'withdrawal' THEN amount ELSE 0 END)) as balance
             FROM savings_transactions"
        )['balance'] ?? 0;
        
        // Get total loans
        $totalLoans = Database::fetchOne(
            "SELECT SUM(loan_amount) as total FROM loans WHERE status IN ('approved', 'active')"
        )['total'] ?? 0;
        
        // Get total loan repayments
        $totalLoanPayments = Database::fetchOne(
            "SELECT SUM(amount) as total FROM loan_repayments"
        )['total'] ?? 0;
        
        // Get total household purchases
        $totalHouseholdPurchases = Database::fetchOne(
            "SELECT SUM(total_cost) as total FROM household_purchases WHERE status IN ('approved', 'active')"
        )['total'] ?? 0;
        
        // Get total household repayments
        $totalHouseholdPayments = Database::fetchOne(
            "SELECT SUM(amount) as total FROM household_repayments"
        )['total'] ?? 0;
        
        // Get monthly deposits for the last 6 months
        $monthlyDeposits = Database::fetchAll(
            "SELECT 
                DATE_FORMAT(transaction_date, '%Y-%m') as month,
                SUM(amount) as total
             FROM savings_transactions
             WHERE 
                transaction_type = 'deposit' AND
                transaction_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
             ORDER BY month"
        );
        
        // Get monthly loan repayments for the last 6 months
        $monthlyLoanPayments = Database::fetchAll(
            "SELECT 
                DATE_FORMAT(payment_date, '%Y-%m') as month,
                SUM(amount) as total
             FROM loan_repayments
             WHERE payment_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
             ORDER BY month"
        );
        
        $stats = [
            'total_members' => $totalMembers,
            'active_members' => $activeMembers,
            'total_savings' => $totalSavings,
            'total_loans' => $totalLoans,
            'total_loan_repayments' => $totalLoanPayments,
            'total_household_purchases' => $totalHouseholdPurchases,
            'total_household_payments' => $totalHouseholdPayments,
            'monthly_deposits' => $monthlyDeposits,
            'monthly_loan_repayments' => $monthlyLoanPayments
        ];
        
        Response::json(['stats' => $stats], 200);
    }
    
    /**
     * Toggle member active status API endpoint
     * Returns JSON response with updated status
     */
    public function toggleMemberStatus(): void
    {
        // Check if request is AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Check CSRF token
        if (!Request::validateCsrfToken()) {
            Response::json(['error' => 'Invalid security token'], 400);
            return;
        }
        
        // Get member ID
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_VALIDATE_INT);
        
        if (!$memberId) {
            Response::json(['error' => 'Invalid member ID'], 400);
            return;
        }
        
        // Get current status
        $member = Database::fetchOne("SELECT is_active FROM members WHERE id = ?", [$memberId]);
        
        if (!$member) {
            Response::json(['error' => 'Member not found'], 404);
            return;
        }
        
        // Toggle status
        $newStatus = $member['is_active'] ? 0 : 1;
        $statusText = $newStatus ? 'active' : 'inactive';
        
        // Update status
        $updated = Database::execute(
            "UPDATE members SET is_active = ?, updated_at = NOW() WHERE id = ?",
            [$newStatus, $memberId]
        );
        
        if (!$updated) {
            Response::json(['error' => 'Failed to update member status'], 500);
            return;
        }
        
        // Log action
        $this->logAction("Set member #$memberId status to $statusText", 'member');
        
        Response::json([
            'success' => true,
            'message' => 'Member status updated successfully',
            'is_active' => $newStatus,
            'status_text' => $statusText
        ], 200);
    }
    
    /**
     * Get loan details API endpoint
     * Returns JSON response with loan details
     */
    public function getLoanDetails(): void
    {
        // Check if request is AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Get loan ID
        $loanId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$loanId) {
            Response::json(['error' => 'Invalid loan ID'], 400);
            return;
        }
        
        // Get loan details
        $loan = Database::fetchOne(
            "SELECT 
                l.*,
                m.name as member_name,
                m.coop_no
            FROM loans l
            JOIN members m ON l.member_id = m.id
            WHERE l.id = ?",
            [$loanId]
        );
        
        if (!$loan) {
            Response::json(['error' => 'Loan not found'], 404);
            return;
        }
        
        // Get loan repayments
        $repayments = Database::fetchAll(
            "SELECT * FROM loan_repayments WHERE loan_id = ? ORDER BY payment_date DESC",
            [$loanId]
        );
        
        $loan['repayments'] = $repayments;
        
        // Calculate totals
        $totalPaid = array_sum(array_column($repayments, 'amount'));
        $loan['total_paid'] = $totalPaid;
        $loan['remaining_balance'] = $loan['loan_amount'] - $totalPaid;
        
        Response::json(['loan' => $loan], 200);
    }
    
    /**
     * Get household purchase details API endpoint
     * Returns JSON response with household purchase details
     */
    public function getHouseholdDetails(): void
    {
        // Check if request is AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Get purchase ID
        $purchaseId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$purchaseId) {
            Response::json(['error' => 'Invalid purchase ID'], 400);
            return;
        }
        
        // Get purchase details
        $purchase = Database::fetchOne(
            "SELECT 
                h.*,
                m.name as member_name,
                m.coop_no
            FROM household_purchases h
            JOIN members m ON h.member_id = m.id
            WHERE h.id = ?",
            [$purchaseId]
        );
        
        if (!$purchase) {
            Response::json(['error' => 'Purchase not found'], 404);
            return;
        }
        
        // Get payments
        $payments = Database::fetchAll(
            "SELECT * FROM household_repayments WHERE purchase_id = ? ORDER BY payment_date DESC",
            [$purchaseId]
        );
        
        $purchase['payments'] = $payments;
        
        // Calculate totals
        $totalPaid = array_sum(array_column($payments, 'amount'));
        $purchase['total_paid'] = $totalPaid;
        $purchase['remaining_balance'] = $purchase['total_cost'] - $totalPaid;
        
        Response::json(['purchase' => $purchase], 200);
    }
    
    /**
     * Get department statistics API endpoint
     * Returns JSON response with statistics per department
     */
    public function getDepartmentStats(): void
    {
        // Check if request is AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Invalid request'], 400);
            return;
        }
        
        // Get departments
        $departments = Database::fetchAll("SELECT id, name FROM departments ORDER BY name");
        
        $stats = [];
        
        foreach ($departments as $department) {
            // Get members count
            $membersCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM members WHERE department_id = ?",
                [$department['id']]
            )['count'];
            
            // Get active members count
            $activeMembersCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM members WHERE department_id = ? AND is_active = 1",
                [$department['id']]
            )['count'];
            
            // Get total savings
            $totalSavings = Database::fetchOne(
                "SELECT 
                    (SUM(CASE WHEN st.transaction_type = 'deposit' THEN st.amount ELSE 0 END) - 
                     SUM(CASE WHEN st.transaction_type = 'withdrawal' THEN st.amount ELSE 0 END)) as balance
                 FROM savings_transactions st
                 JOIN members m ON st.member_id = m.id
                 WHERE m.department_id = ?",
                [$department['id']]
            )['balance'] ?? 0;
            
            // Get total loans
            $totalLoans = Database::fetchOne(
                "SELECT SUM(l.loan_amount) as total 
                 FROM loans l
                 JOIN members m ON l.member_id = m.id
                 WHERE m.department_id = ? AND l.status IN ('approved', 'active')",
                [$department['id']]
            )['total'] ?? 0;
            
            $stats[] = [
                'id' => $department['id'],
                'name' => $department['name'],
                'members_count' => $membersCount,
                'active_members_count' => $activeMembersCount,
                'total_savings' => $totalSavings,
                'total_loans' => $totalLoans
            ];
        }
        
        Response::json(['departments' => $stats], 200);
    }
    
    /**
     * Search household purchases API endpoint
     * Returns JSON response with matched household purchases
     */
    public function searchHousehold(): void
    {
        try {
            // Disable error output to prevent HTML in JSON response
            ini_set('display_errors', 0);
            
            // Check if request is AJAX
            if (!Request::isAjax()) {
                Response::json(['error' => 'Invalid request'], 400);
                return;
            }
            
            // Get search term
            $searchTerm = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
            
            if (empty($searchTerm) || strlen($searchTerm) < 3) {
                Response::json(['purchases' => []], 200);
                return;
            }
            
            // First search for member by COOPS number, name, or email
            $memberQuery = "SELECT 
                    id, 
                    coop_no, 
                    name,
                    email
                FROM members
                WHERE 
                    coop_no LIKE ? OR
                    name LIKE ? OR
                    email LIKE ?
                LIMIT 10";
            
            $searchParam = "%{$searchTerm}%";
            $memberParams = [$searchParam, $searchParam, $searchParam];
            
            $members = Database::fetchAll($memberQuery, $memberParams);
            
            // If no members found, return empty result
            if (empty($members)) {
                Response::json(['purchases' => []], 200);
                return;
            }
            
            $memberIds = array_column($members, 'id');
            
            // If no member IDs extracted, return empty result
            if (empty($memberIds)) {
                Response::json(['purchases' => []], 200);
                return;
            }
            
            // Search for active household purchases for these members
            $placeholders = str_repeat('?,', count($memberIds) - 1) . '?';
            
            $purchaseQuery = "SELECT 
                    hp.id,
                    hp.member_id,
                    m.name as member_name,
                    m.coop_no as coop_no,
                    hp.amount,
                    (hp.amount * 1.05) as total_amount_with_admin,
                    hp.description,
                    hp.status,
                    (SELECT COALESCE(SUM(amount), 0) FROM household_repayments WHERE purchase_id = hp.id) as paid_amount
                FROM household_purchases hp
                JOIN members m ON hp.member_id = m.id
                WHERE 
                    hp.member_id IN ({$placeholders}) AND
                    hp.status IN ('approved', 'active')
                ORDER BY hp.created_at DESC
                LIMIT 10";
            
            $purchases = Database::fetchAll($purchaseQuery, $memberIds);
            
            // Transform and return the result
            $formattedPurchases = [];
            foreach ($purchases as $purchase) {
                $paidAmount = (float)($purchase['paid_amount'] ?? 0);
                $rawAmount = (float)$purchase['amount'];
                $totalAmount = (float)$purchase['total_amount_with_admin'];
                $remainingAmount = $totalAmount - $paidAmount;
                
                // Only include purchases that still have remaining balance
                if ($remainingAmount > 0) {
                    $formattedPurchases[] = [
                        'id' => $purchase['id'],
                        'member_id' => $purchase['member_id'],
                        'member_name' => $purchase['member_name'],
                        'coop_no' => $purchase['coop_no'],
                        'raw_amount' => $rawAmount,
                        'amount' => $totalAmount,
                        'paid_amount' => $paidAmount,
                        'remaining_amount' => $remainingAmount,
                        'description' => $purchase['description'],
                        'status' => $purchase['status']
                    ];
                }
            }
            
            // Set the correct content type header before sending the response
            header('Content-Type: application/json');
            echo json_encode(['purchases' => $formattedPurchases]);
            exit;
        } catch (\Exception $e) {
            // Log error for debugging
            error_log("Error in searchHousehold: " . $e->getMessage());
            
            // Return a friendly error message with proper JSON headers
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred while searching for household purchases.']);
            exit;
        }
    }
    
    /**
     * Search loans API endpoint
     * Returns JSON response with matched loans
     */
    public function searchLoans(): void
    {
        try {
            // Disable error output to prevent HTML in JSON response
            ini_set('display_errors', 0);
            error_reporting(0);
            
            // Set headers early to prevent other headers from interfering
            header('Content-Type: application/json');
            
            // Get search term
            $searchTerm = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
            
            // First search for member by COOPS number, name, or email
            $memberQuery = "SELECT 
                    id, 
                    coop_no, 
                    name,
                    email
                FROM members
                WHERE 
                    coop_no LIKE ? OR
                    name LIKE ? OR
                    email LIKE ?";
            
            $searchParam = "%{$searchTerm}%";
            $memberParams = [$searchParam, $searchParam, $searchParam];
            
            $members = Database::fetchAll($memberQuery, $memberParams);
            
            // If no members found, try searching by loan ID
            if (empty($members) && is_numeric($searchTerm)) {
                // Search for the specific loan
                $loanQuery = "SELECT 
                        l.id,
                        l.member_id,
                        l.loan_amount,
                        l.total_repayment,
                        l.balance,
                        l.interest_rate,
                        l.status,
                        m.name as member_name,
                        m.coop_no
                    FROM loans l
                    JOIN members m ON l.member_id = m.id
                    WHERE l.id = ? AND l.status IN ('approved', 'active', 'pending')";
                
                $loans = Database::fetchAll($loanQuery, [(int)$searchTerm]);
            } else {
                // Get member IDs
                $memberIds = array_column($members, 'id');
                
                if (!empty($memberIds)) {
                    // Create placeholders for the IN clause
                    $placeholders = str_repeat('?,', count($memberIds) - 1) . '?';
                    
                    // Search for active loans for these members
                    $loanQuery = "SELECT 
                            l.id,
                            l.member_id,
                            l.loan_amount,
                            l.total_repayment,
                            l.balance,
                            l.interest_rate,
                            l.status,
                            m.name as member_name,
                            m.coop_no
                        FROM loans l
                        JOIN members m ON l.member_id = m.id
                        WHERE 
                            l.member_id IN ({$placeholders}) AND
                            l.status IN ('approved', 'active', 'pending')
                        ORDER BY l.created_at DESC";
                    
                    $loans = Database::fetchAll($loanQuery, $memberIds);
                } else {
                    $loans = [];
                }
            }
            
            // Format loans for response
            $formattedLoans = [];
            foreach ($loans as $loan) {
                // Only include loans that have a balance
                if ((float)($loan['balance'] ?? 0) > 0) {
                    $formattedLoans[] = [
                        'id' => $loan['id'],
                        'member_id' => $loan['member_id'],
                        'member_name' => $loan['member_name'] ?? 'Unknown',
                        'coop_no' => $loan['coop_no'] ?? 'N/A',
                        'loan_amount' => (float)$loan['loan_amount'],
                        'total_repayment' => (float)$loan['total_repayment'],
                        'balance' => (float)$loan['balance'],
                        'interest_rate' => (float)$loan['interest_rate'],
                        'status' => $loan['status']
                    ];
                }
            }
            
            echo json_encode(['loans' => $formattedLoans]);
            exit;
            
        } catch (\Exception $e) {
            // Log detailed error for debugging
            error_log("Error in searchLoans: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            
            // Return a friendly error message
            echo json_encode(['error' => 'An error occurred while searching for loans.']);
            exit;
        }
    }
} 