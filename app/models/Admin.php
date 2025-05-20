<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Helpers\Utility;

/**
 * Admin Model
 */
final class Admin
{
    /**
     * Role constants
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPERADMIN = 'superadmin';
    
    /**
     * Check if a role is valid
     *
     * @param string $role
     * @return bool
     */
    public static function isValidRole(string $role): bool
    {
        return in_array($role, [self::ROLE_ADMIN, self::ROLE_SUPERADMIN]);
    }
    
    /**
     * Get admin by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM admin_users WHERE id = ?", [$id]);
    }
    
    /**
     * Get admin by username
     *
     * @param string $username
     * @return array|null
     */
    public static function getByUsername(string $username): ?array
    {
        return Database::fetchOne("SELECT * FROM admin_users WHERE username = ?", [$username]);
    }
    
    /**
     * Get admin by email
     *
     * @param string $email
     * @return array|null
     */
    public static function getByEmail(string $email): ?array
    {
        return Database::fetchOne("SELECT * FROM admin_users WHERE email = ?", [$email]);
    }
    
    /**
     * Get all admins
     *
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getAll(int $page = 1, int $perPage = 10): array
    {
        $totalQuery = "SELECT COUNT(*) as count FROM admin_users";
        $totalResult = Database::fetchOne($totalQuery);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $admins = Database::fetchAll(
            "SELECT id, username, name, email, role, is_locked, created_at, updated_at 
            FROM admin_users ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$pagination['per_page'], $pagination['offset']]
        );
        
        return [
            'data' => $admins,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Create a new admin
     *
     * @param string $username
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     * @return int|null
     */
    public static function create(string $username, string $name, string $email, string $password, string $role = 'admin'): ?int
    {
        // Check if username or email already exists
        if (self::getByUsername($username) || self::getByEmail($email)) {
            return null;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $data = [
            'username' => $username,
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ];
        
        return Database::insert('admin_users', $data);
    }
    
    /**
     * Update admin details
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        // Don't update password here - use updatePassword method
        if (isset($data['password'])) {
            unset($data['password']);
        }
        
        return Database::update('admin_users', $data, ['id' => $id]) > 0;
    }
    
    /**
     * Update admin password
     *
     * @param int $id
     * @param string $password
     * @return bool
     */
    public static function updatePassword(int $id, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        return Database::update('admin_users', ['password' => $hashedPassword], ['id' => $id]) > 0;
    }
    
    /**
     * Delete an admin
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        // Check if this is the last superadmin
        $admin = self::getById($id);
        
        if ($admin && $admin['role'] === 'superadmin') {
            $superadminCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM admin_users WHERE role = 'superadmin'"
            );
            
            if ($superadminCount && (int)$superadminCount['count'] <= 1) {
                // Cannot delete the last superadmin
                return false;
            }
        }
        
        return Database::delete('admin_users', ['id' => $id]) > 0;
    }
    
    /**
     * Lock or unlock an admin account
     *
     * @param int $id
     * @param bool $locked
     * @return bool
     */
    public static function setLockStatus(int $id, bool $locked): bool
    {
        // Check if this is the last superadmin
        $admin = self::getById($id);
        
        if ($admin && $admin['role'] === 'superadmin' && $locked) {
            $activeSuperadminCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM admin_users WHERE role = 'superadmin' AND is_locked = 0"
            );
            
            if ($activeSuperadminCount && (int)$activeSuperadminCount['count'] <= 1) {
                // Cannot lock the last active superadmin
                return false;
            }
        }
        
        return Database::update('admin_users', ['is_locked' => $locked], ['id' => $id]) > 0;
    }
    
    /**
     * Change admin role
     *
     * @param int $id
     * @param string $role
     * @return bool
     */
    public static function changeRole(int $id, string $role): bool
    {
        // Check if demoting the last superadmin
        if ($role !== 'superadmin') {
            $admin = self::getById($id);
            
            if ($admin && $admin['role'] === 'superadmin') {
                $superadminCount = Database::fetchOne(
                    "SELECT COUNT(*) as count FROM admin_users WHERE role = 'superadmin'"
                );
                
                if ($superadminCount && (int)$superadminCount['count'] <= 1) {
                    // Cannot demote the last superadmin
                    return false;
                }
            }
        }
        
        return Database::update('admin_users', ['role' => $role], ['id' => $id]) > 0;
    }
    
    /**
     * Reset failed login attempts
     *
     * @param int $id
     * @return bool
     */
    public static function resetFailedAttempts(int $id): bool
    {
        return Database::update('admin_users', ['failed_attempts' => 0], ['id' => $id]) > 0;
    }
    
    /**
     * Search admins
     *
     * @param string $keyword
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function search(string $keyword, int $page = 1, int $perPage = 10): array
    {
        $searchTerm = "%$keyword%";
        
        $totalQuery = "SELECT COUNT(*) as count FROM admin_users 
                      WHERE username LIKE ? OR name LIKE ? OR email LIKE ?";
        $totalResult = Database::fetchOne($totalQuery, [$searchTerm, $searchTerm, $searchTerm]);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $admins = Database::fetchAll(
            "SELECT id, username, name, email, role, is_locked, created_at, updated_at 
            FROM admin_users 
            WHERE username LIKE ? OR name LIKE ? OR email LIKE ? 
            ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$searchTerm, $searchTerm, $searchTerm, $pagination['per_page'], $pagination['offset']]
        );
        
        return [
            'data' => $admins,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Get system statistics for admin dashboard
     *
     * @return array
     */
    public static function getSystemStats(): array
    {
        $memberCount = Database::fetchOne("SELECT COUNT(*) as count FROM members");
        $registeredMemberCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members WHERE is_registered = 1"
        );
        $loanApplicationsCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM loan_applications WHERE status = 'pending'"
        );
        $householdApplicationsCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM household_applications WHERE status = 'pending'"
        );
        
        return [
            'total_members' => $memberCount ? (int)$memberCount['count'] : 0,
            'registered_members' => $registeredMemberCount ? (int)$registeredMemberCount['count'] : 0,
            'pending_loan_applications' => $loanApplicationsCount ? (int)$loanApplicationsCount['count'] : 0,
            'pending_household_applications' => $householdApplicationsCount ? (int)$householdApplicationsCount['count'] : 0
        ];
    }
    
    /**
     * Get dashboard statistics for admin
     *
     * @return array
     */
    public static function getDashboardStats(): array
    {
        // Get total members
        $membersCount = Database::fetchOne("SELECT COUNT(*) as count FROM members WHERE status = 'active'");
        
        // Get total loans
        $loansCount = Database::fetchOne("SELECT COUNT(*) as count FROM loans");
        
        // Get total household purchases
        $purchasesCount = Database::fetchOne("SELECT COUNT(*) as count FROM household_purchases");
        
        // Get total shares and savings
        $totalShares = Database::fetchOne("SELECT SUM(amount) as total FROM shares");
        $totalSavings = Database::fetchOne("SELECT SUM(amount) as total FROM savings");
        
        // Get pending applications
        $loanApplicationsCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM loan_applications WHERE status = 'pending'"
        );
        
        $householdApplicationsCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM household_applications WHERE status = 'pending'"
        );
        
        // Get today's transactions
        $today = date('Y-m-d');
        $todayTransactions = Database::fetchOne(
            "SELECT COUNT(*) as count FROM transactions WHERE DATE(created_at) = ?",
            [$today]
        );
        
        return [
            'total_members' => $membersCount ? (int)$membersCount['count'] : 0,
            'total_loans' => $loansCount ? (int)$loansCount['count'] : 0,
            'total_household_purchases' => $purchasesCount ? (int)$purchasesCount['count'] : 0,
            'total_shares' => $totalShares ? (float)$totalShares['total'] : 0,
            'total_savings' => $totalSavings ? (float)$totalSavings['total'] : 0,
            'pending_loan_applications' => $loanApplicationsCount ? (int)$loanApplicationsCount['count'] : 0,
            'pending_household_applications' => $householdApplicationsCount ? (int)$householdApplicationsCount['count'] : 0,
            'today_transactions' => $todayTransactions ? (int)$todayTransactions['count'] : 0
        ];
    }
} 