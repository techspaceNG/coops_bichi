<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Models\Admin;

/**
 * DashboardController for Superadmin
 * Handles dashboard functionality
 */
final class DashboardController extends AbstractController
{
    /**
     * Display the dashboard
     */
    public function index(): void
    {
        // Get additional superadmin statistics
        $adminStats = $this->getAdminStats();
        $systemStats = $this->getSystemStats();
        $securityLogs = $this->getSecurityLogs();
        $memberStats = $this->getMemberStats();
        $financialStats = $this->getFinancialStats();
        $systemNotices = $this->getSystemNotices();
        
        $this->renderSuperAdmin('superadmin/dashboard', [
            'adminStats' => $adminStats,
            'systemStats' => $systemStats,
            'securityLogs' => $securityLogs,
            'memberStats' => $memberStats,
            'financialStats' => $financialStats,
            'systemNotices' => $systemNotices,
            'pageTitle' => 'Superadmin Dashboard'
        ]);
    }
    
    /**
     * Get admin statistics
     */
    private function getAdminStats(): array
    {
        $totalAdmins = Database::fetchOne(
            "SELECT COUNT(*) as count FROM admin_users WHERE role = ?",
            [Admin::ROLE_ADMIN]
        );
        
        $totalSuperadmins = Database::fetchOne(
            "SELECT COUNT(*) as count FROM admin_users WHERE role = ?",
            [Admin::ROLE_SUPERADMIN]
        );
        
        $activeAdmins = Database::fetchOne(
            "SELECT COUNT(*) as count FROM admin_users WHERE role = ? AND is_locked = 0",
            [Admin::ROLE_ADMIN]
        );
        
        $lockedAdmins = Database::fetchOne(
            "SELECT COUNT(*) as count FROM admin_users WHERE is_locked = 1"
        );
        
        return [
            'total_admins' => $totalAdmins['count'] ?? 0,
            'total_superadmins' => $totalSuperadmins['count'] ?? 0,
            'active_admins' => $activeAdmins['count'] ?? 0,
            'locked_admins' => $lockedAdmins['count'] ?? 0
        ];
    }
    
    /**
     * Get system statistics
     */
    private function getSystemStats(): array
    {
        // Get database size
        $dbStats = Database::fetchOne("SELECT 
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
            FROM information_schema.TABLES 
            WHERE table_schema = DATABASE()
        ");
        
        // Get audit log count
        $auditLogs = Database::fetchOne(
            "SELECT COUNT(*) as count FROM audit_logs"
        );
        
        // Get last backup date (if available)
        $lastBackup = Database::fetchOne(
            "SELECT value FROM system_settings WHERE setting_key = 'last_backup_date'"
        );
        
        return [
            'db_size_mb' => $dbStats['size_mb'] ?? 0,
            'audit_log_count' => $auditLogs['count'] ?? 0,
            'last_backup' => $lastBackup['value'] ?? 'Never'
        ];
    }
    
    /**
     * Get security logs
     */
    private function getSecurityLogs(): array
    {
        return Database::fetchAll(
            "SELECT * FROM audit_logs 
            WHERE action_type = 'security' 
            ORDER BY timestamp DESC 
            LIMIT 10"
        );
    }
    
    /**
     * Get member statistics
     */
    private function getMemberStats(): array
    {
        // Get total members
        $totalMembers = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members"
        );
        
        // Get active members
        $activeMembers = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members WHERE is_active = 1"
        );
        
        // Calculate active percentage
        $activePercentage = 0;
        if (($totalMembers['count'] ?? 0) > 0) {
            $activePercentage = round(($activeMembers['count'] / $totalMembers['count']) * 100);
        }
        
        // Get new members this month
        $currentMonth = date('Y-m-01');
        $newMembers = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members WHERE DATE(created_at) >= ?",
            [$currentMonth]
        );
        
        // Get members with active loans
        $membersWithLoans = Database::fetchOne(
            "SELECT COUNT(DISTINCT m.id) as count 
             FROM members m
             JOIN loans l ON m.id = l.member_id
             WHERE (l.balance > 0 OR l.status = 'active')"
        );
        
        // Calculate with loans percentage
        $withLoansPercentage = 0;
        if (($totalMembers['count'] ?? 0) > 0 && isset($membersWithLoans['count'])) {
            $withLoansPercentage = round(($membersWithLoans['count'] / $totalMembers['count']) * 100);
        }
        
        return [
            'total_members' => $totalMembers['count'] ?? 0,
            'active_members' => $activeMembers['count'] ?? 0,
            'active_percentage' => $activePercentage,
            'new_this_month' => $newMembers['count'] ?? 0,
            'with_loans' => $membersWithLoans['count'] ?? 0,
            'with_loans_percentage' => $withLoansPercentage,
            'retention_rate' => 95 // Default placeholder value
        ];
    }
    
    /**
     * Get financial statistics
     */
    private function getFinancialStats(): array
    {
        // Get total savings - use NULL coalescing to handle NULL results from SUM
        $totalSavings = Database::fetchOne(
            "SELECT SUM(savings_balance) as total FROM members"
        );
        
        // Get total loans
        $totalLoans = Database::fetchOne(
            "SELECT SUM(loan_balance) as total FROM members"
        );
        
        // Get total household purchases
        $totalHousehold = Database::fetchOne(
            "SELECT SUM(household_balance) as total FROM members"
        );
        
        // Get total shares
        $totalShares = Database::fetchOne(
            "SELECT SUM(shares_balance) as total FROM members"
        );
        
        // Get active loans count - try both balance and status fields
        $activeLoans = Database::fetchOne(
            "SELECT COUNT(*) as count FROM loans WHERE balance > 0 OR status = 'active'"
        );
        
        // Calculate monthly changes by comparing with previous month
        // For this example, we'll use placeholder values, but in a real implementation
        // you would retrieve previous month data and calculate the percentage change
        
        return [
            'total_savings' => $totalSavings['total'] ?? 0,
            'total_loans' => $totalLoans['total'] ?? 0,
            'total_household' => $totalHousehold['total'] ?? 0,
            'total_shares' => $totalShares['total'] ?? 0,
            'savings_change' => 5.2, // Placeholder
            'loans_change' => 3.8,   // Placeholder
            'household_change' => 2.5, // Placeholder
            'shares_change' => 1.8,   // Placeholder
            'active_loans' => $activeLoans['count'] ?? 0
        ];
    }
    
    /**
     * Get system notices
     */
    private function getSystemNotices(): array
    {
        // This would typically come from a database table
        // For now, we'll use a placeholder
        return [
            [
                'title' => 'System Update',
                'message' => 'A system update is scheduled for this weekend.',
                'date' => date('Y-m-d', strtotime('+2 days')),
                'type' => 'info',
                'link' => '/superadmin/notices/view/1'
            ],
            [
                'title' => 'Database Backup Reminder',
                'message' => 'Please remember to perform a database backup before the end of the month.',
                'date' => date('Y-m-d'),
                'type' => 'warning',
                'link' => '/superadmin/database-backup'
            ]
        ];
    }
} 