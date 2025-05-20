<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;

/**
 * Admin Controller
 * Handles admin functionality
 */
final class AdminController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Require admin authentication
        $this->requireAdmin();
    }
    
    /**
     * Display admin dashboard
     */
    public function dashboard(): void
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get chart data
        $charts = $this->getChartData();
        
        // Get recent activity
        $recentActivity = Database::fetchAll(
            "SELECT al.*, au.name as user_name, 
            CASE 
                WHEN al.user_type = 'admin' THEN 'Admin' 
                WHEN al.user_type = 'member' THEN 'Member' 
                ELSE al.user_type 
            END as user_type
            FROM audit_logs al 
            LEFT JOIN admin_users au ON al.user_id = au.id AND al.user_type = 'admin'
            ORDER BY al.timestamp DESC LIMIT 10"
        );
        
        // Get pending loan applications
        $pendingLoans = Database::fetchAll(
            "SELECT l.*, m.name as member_name
            FROM loans l
            JOIN members m ON l.member_id = m.id
            WHERE l.status = 'pending'
            ORDER BY l.created_at DESC"
        );
        
        // Get pending household purchase applications
        $pendingPurchases = Database::fetchAll(
            "SELECT h.*, m.name as member_name
            FROM household_purchases h
            JOIN members m ON h.member_id = m.id
            WHERE h.status = 'pending'
            ORDER BY h.created_at DESC"
        );
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/dashboard', [
            'stats' => $stats,
            'charts' => $charts,
            'recentActivity' => $recentActivity,
            'pendingLoans' => $pendingLoans,
            'pendingPurchases' => $pendingPurchases,
            'pageTitle' => 'Admin Dashboard',
            'admin' => $admin
        ]);
    }
    
    /**
     * Get dashboard statistics
     * 
     * @return array Dashboard statistics
     */
    private function getDashboardStats(): array
    {
        // Total members
        $totalMembers = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members"
        );
        
        // Get new members in the last 30 days
        $newMembers = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );
        
        // Calculate member growth rate
        $prevMonthMembers = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members 
            WHERE created_at < DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
        );
        
        $memberGrowth = 0;
        if ($prevMonthMembers && $prevMonthMembers['count'] > 0) {
            $memberGrowth = ($newMembers['count'] / $prevMonthMembers['count']) * 100;
        }
        
        // Total savings
        $totalSavings = Database::fetchOne(
            "SELECT SUM(cumulative_amount) as total FROM savings"
        );
        
        // Calculate savings growth
        $currentMonthSavings = Database::fetchOne(
            "SELECT SUM(amount) as total FROM savings_transactions 
            WHERE deduction_date >= DATE_FORMAT(CURDATE() ,'%Y-%m-01')"
        );
        
        $prevMonthSavings = Database::fetchOne(
            "SELECT SUM(amount) as total FROM savings_transactions 
            WHERE deduction_date >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ,'%Y-%m-01')
            AND deduction_date < DATE_FORMAT(CURDATE() ,'%Y-%m-01')"
        );
        
        $savingsGrowth = 0;
        if ($prevMonthSavings && $prevMonthSavings['total'] > 0) {
            $savingsGrowth = (($currentMonthSavings['total'] - $prevMonthSavings['total']) / $prevMonthSavings['total']) * 100;
        }
        
        // Total shares
        $totalShares = Database::fetchOne(
            "SELECT 
                (SELECT COALESCE(SUM(total_value), 0) FROM shares) +
                (SELECT COALESCE(SUM(shares_balance), 0) FROM members 
                 WHERE id NOT IN (SELECT DISTINCT member_id FROM shares)) as total"
        );
        
        // Calculate shares growth
        $currentMonthShares = Database::fetchOne(
            "SELECT SUM(total_amount) as total FROM share_transactions 
            WHERE transaction_date >= DATE_FORMAT(CURDATE() ,'%Y-%m-01')"
        );
        
        $prevMonthShares = Database::fetchOne(
            "SELECT SUM(total_amount) as total FROM share_transactions 
            WHERE transaction_date >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ,'%Y-%m-01')
            AND transaction_date < DATE_FORMAT(CURDATE() ,'%Y-%m-01')"
        );
        
        $sharesGrowth = 0;
        if ($prevMonthShares && $prevMonthShares['total'] > 0) {
            $sharesGrowth = (($currentMonthShares['total'] - $prevMonthShares['total']) / $prevMonthShares['total']) * 100;
        }
        
        // Active loans
        $activeLoans = Database::fetchOne(
            "SELECT COUNT(*) as count FROM loans WHERE status = 'approved' AND balance > 0"
        );
        
        // Total loan balance
        $totalLoanBalance = Database::fetchOne(
            "SELECT SUM(balance) as total FROM loans WHERE status = 'approved'"
        );
        
        // Active household purchases
        $activePurchases = Database::fetchOne(
            "SELECT COUNT(*) as count FROM household_purchases WHERE status = 'approved' AND balance > 0"
        );
        
        // Total household purchase balance
        $totalPurchaseBalance = Database::fetchOne(
            "SELECT SUM(balance) as total FROM household_purchases WHERE status = 'approved'"
        );
        
        return [
            'total_members' => $totalMembers['count'] ?? 0,
            'member_growth' => $memberGrowth,
            'total_savings' => $totalSavings['total'] ?? 0,
            'savings_growth' => $savingsGrowth,
            'total_shares' => $totalShares['total'] ?? 0,
            'shares_growth' => $sharesGrowth,
            'active_loans' => $activeLoans['count'] ?? 0,
            'total_loan_balance' => $totalLoanBalance['total'] ?? 0,
            'active_purchases' => $activePurchases['count'] ?? 0,
            'total_purchase_balance' => $totalPurchaseBalance['total'] ?? 0
        ];
    }
    
    /**
     * Get chart data for dashboard
     * 
     * @return array Chart data
     */
    private function getChartData(): array
    {
        // Monthly savings trend for last 12 months
        $savingsData = [];
        $savingsLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            
            $monthlySavings = Database::fetchOne(
                "SELECT SUM(amount) as total FROM savings_transactions 
                WHERE deduction_date LIKE '$monthDate%'"
            );
            
            $savingsLabels[] = $monthName;
            $savingsData[] = $monthlySavings['total'] ?? 0;
        }
        
        // Loan vs Savings vs Shares data by department
        $departments = Database::fetchAll(
            "SELECT d.id, d.name as department_name 
            FROM departments d
            JOIN members m ON m.department_id = d.id
            GROUP BY d.id, d.name
            ORDER BY d.name ASC"
        );
        
        $departmentLabels = [];
        $departmentSavings = [];
        $departmentLoans = [];
        $departmentPurchases = [];
        $departmentShares = [];
        
        foreach ($departments as $dept) {
            $departmentLabels[] = $dept['department_name'];
            
            // Get department savings
            $savings = Database::fetchOne(
                "SELECT SUM(s.cumulative_amount) as total FROM savings s
                JOIN members m ON s.member_id = m.id
                WHERE m.department_id = ?",
                [$dept['id']]
            );
            $departmentSavings[] = $savings['total'] ?? 0;
            
            // Get department loans
            $loans = Database::fetchOne(
                "SELECT SUM(l.balance) as total FROM loans l
                JOIN members m ON l.member_id = m.id
                WHERE m.department_id = ? AND l.status = 'approved'",
                [$dept['id']]
            );
            $departmentLoans[] = $loans['total'] ?? 0;
            
            // Get department household purchases
            $purchases = Database::fetchOne(
                "SELECT SUM(h.balance) as total FROM household_purchases h
                JOIN members m ON h.member_id = m.id
                WHERE m.department_id = ? AND h.status = 'approved'",
                [$dept['id']]
            );
            $departmentPurchases[] = $purchases['total'] ?? 0;
            
            // Get department shares
            $shares = Database::fetchOne(
                "SELECT 
                    (
                        SELECT COALESCE(SUM(sh.total_value), 0) 
                        FROM shares sh 
                        JOIN members m2 ON sh.member_id = m2.id 
                        WHERE m2.department_id = ?
                    ) + 
                    (
                        SELECT COALESCE(SUM(m3.shares_balance), 0) 
                        FROM members m3 
                        WHERE m3.department_id = ? 
                        AND m3.id NOT IN (SELECT DISTINCT member_id FROM shares)
                    ) as total",
                [$dept['id'], $dept['id']]
            );
            $departmentShares[] = $shares['total'] ?? 0;
        }
        
        return [
            'savings' => [
                'labels' => $savingsLabels,
                'data' => $savingsData
            ],
            'loanVsSavings' => [
                'labels' => $departmentLabels,
                'savings' => $departmentSavings,
                'loans' => $departmentLoans,
                'purchases' => $departmentPurchases,
                'shares' => $departmentShares
            ]
        ];
    }
    
    /**
     * Display admin profile
     */
    public function profile(): void
    {
        // Get admin info
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/profile', [
            'admin' => $admin,
            'pageTitle' => 'Admin Profile'
        ]);
    }
    
    /**
     * Display admin settings
     */
    public function settings(): void
    {
        // Get admin info
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/settings', [
            'admin' => $admin,
            'pageTitle' => 'Admin Settings'
        ]);
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/profile');
            return;
        }
        
        // Get admin ID
        $adminId = Auth::getAdminId();
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        Validator::resetErrors();
        Validator::required($input, ['name', 'email']);
        Validator::email($input, 'email');
        
        if (Validator::hasErrors()) {
            $this->setFlash('error', reset(Validator::getErrors()));
            $this->redirect('/admin/profile');
            return;
        }
        
        // Update profile
        $result = Database::update('admin_users', [
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? ''
        ], ['id' => $adminId]);
        
        if ($result) {
            // Log the action
            Auth::logAction('admin', $adminId, 'Profile updated', []);
            
            $this->setFlash('success', 'Profile updated successfully');
        } else {
            $this->setFlash('error', 'Failed to update profile');
        }
        
        $this->redirect('/admin/profile');
    }
    
    /**
     * Display change password form
     */
    public function changePassword(): void
    {
        $this->renderAdmin('admin/change-password', [
            'pageTitle' => 'Change Password'
        ]);
    }
    
    /**
     * Process password change
     */
    public function updatePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/change-password');
            return;
        }
        
        // Get admin ID
        $adminId = Auth::getAdminId();
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        Validator::resetErrors();
        Validator::required($input, ['current_password', 'new_password', 'confirm_password']);
        Validator::minLength($input, 'new_password', 8);
        Validator::passwordConfirmation($input, 'new_password', 'confirm_password');
        
        if (Validator::hasErrors()) {
            $this->setFlash('error', reset(Validator::getErrors()));
            $this->redirect('/admin/change-password');
            return;
        }
        
        // Get current admin data
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Verify current password
        if (!password_verify($input['current_password'], $admin['password'])) {
            $this->setFlash('error', 'Current password is incorrect');
            $this->redirect('/admin/change-password');
            return;
        }
        
        // Hash new password
        $hashedPassword = password_hash($input['new_password'], PASSWORD_BCRYPT);
        
        // Update password
        $result = Database::update('admin_users', [
            'password' => $hashedPassword
        ], ['id' => $adminId]);
        
        if ($result) {
            // Log the action
            Auth::logAction('admin', $adminId, 'Password changed', []);
            
            $this->setFlash('success', 'Password updated successfully');
        } else {
            $this->setFlash('error', 'Failed to update password');
        }
        
        $this->redirect('/admin/profile');
    }
} 