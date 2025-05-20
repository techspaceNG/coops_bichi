<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Models\Admin;
use App\Config\Database;

/**
 * Authentication Helper
 */
class Auth
{
    /**
     * Start session if not already started
     */
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Log in an admin user
     *
     * @param array $user
     * @return void
     */
    public static function loginAdmin(array $user): void
    {
        self::startSession();
        
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        $_SESSION['admin_logged_in'] = true;
        
        // Reset failed attempts
        Database::update('admin_users', ['failed_attempts' => 0], ['id' => $user['id']]);
        
        // Log the login action
        self::logAction('admin', $user['id'], 'Admin login', ['username' => $user['username']]);
    }
    
    /**
     * Log in a member
     *
     * @param array $member
     * @return void
     */
    public static function loginMember(array $member): void
    {
        self::startSession();
        
        // Set all required session variables
        $_SESSION['member_id'] = $member['id'];
        $_SESSION['member_coop_no'] = $member['coop_no'];
        $_SESSION['member_logged_in'] = true;
        
        // Set common session variables for Session helper checks
        $_SESSION['user_id'] = $member['id'];
        $_SESSION['user_type'] = 'member';
        
        // Set user profile information
        $_SESSION['name'] = $member['first_name'] . ' ' . $member['last_name'];
        $_SESSION['email'] = $member['email'];
        
        // Reset failed attempts
        Database::update('members', ['failed_attempts' => 0], ['id' => $member['id']]);
        
        // Log the login action
        self::logAction('member', $member['id'], 'Member login', ['coop_no' => $member['coop_no']]);
    }
    
    /**
     * Log out the current user
     *
     * @return void
     */
    public static function logout(): void
    {
        self::startSession();
        
        // Log the logout action
        if (isset($_SESSION['admin_id'])) {
            self::logAction('admin', $_SESSION['admin_id'], 'Admin logout', []);
        } elseif (isset($_SESSION['member_id'])) {
            self::logAction('member', $_SESSION['member_id'], 'Member logout', []);
        }
        
        // Destroy the session
        session_unset();
        session_destroy();
        
        // Regenerate session ID for security
        session_start();
        session_regenerate_id(true);
    }
    
    /**
     * Check if an admin is logged in
     *
     * @return bool
     */
    public static function isAdminLoggedIn(): bool
    {
        self::startSession();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Check if a member is logged in
     *
     * @return bool
     */
    public static function isMemberLoggedIn(): bool
    {
        self::startSession();
        return isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true;
    }
    
    /**
     * Check if current admin is a superadmin
     *
     * @return bool
     */
    public static function isSuperAdmin(): bool
    {
        if (!self::isAdminLoggedIn()) {
            return false;
        }
        
        // Get admin from database to check role
        $adminId = self::getAdminId();
        $admin = Database::fetchOne(
            "SELECT role FROM admin_users WHERE id = ?", 
            [$adminId]
        );
        
        return $admin && $admin['role'] === Admin::ROLE_SUPERADMIN;
    }
    
    /**
     * Get current logged in admin ID
     *
     * @return int|null
     */
    public static function getAdminId(): ?int
    {
        self::startSession();
        return isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : null;
    }
    
    /**
     * Get current logged in member ID
     *
     * @return int|null
     */
    public static function getMemberId(): ?int
    {
        self::startSession();
        return isset($_SESSION['member_id']) ? (int)$_SESSION['member_id'] : null;
    }
    
    /**
     * Validate admin login credentials
     *
     * @param string $username
     * @param string $password
     * @return array|null User data if valid, null otherwise
     */
    public static function validateAdminLogin(string $username, string $password): ?array
    {
        $user = Database::fetchOne(
            "SELECT * FROM admin_users WHERE username = ?",
            [$username]
        );
        
        if (!$user) {
            return null;
        }
        
        // Check if account is locked
        if ((bool)$user['is_locked']) {
            return null;
        }
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        
        // Increment failed attempts
        $failedAttempts = (int)$user['failed_attempts'] + 1;
        $data = ['failed_attempts' => $failedAttempts];
        
        // Lock account after 5 failed attempts
        if ($failedAttempts >= 5) {
            $data['is_locked'] = true;
        }
        
        Database::update('admin_users', $data, ['id' => $user['id']]);
        
        return null;
    }
    
    /**
     * Validate member login credentials
     *
     * @param string $coopNo
     * @param string $password
     * @return array|null Member data if valid, null otherwise
     */
    public static function validateMemberLogin(string $coopNo, string $password): ?array
    {
        $db = Database::getConnection();
        
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE coop_no = ?",
            [$coopNo]
        );
        
        if (!$member || !$member['password']) {
            return null;
        }
        
        // Check if account is locked or inactive
        if ((bool)$member['is_locked'] || !(bool)$member['is_active']) {
            return null;
        }
        
        // Verify password
        if (password_verify($password, $member['password'])) {
            // Update last login time
            Database::update('members', ['last_login' => date('Y-m-d H:i:s')], ['id' => $member['id']]);
            return $member;
        }
        
        // Increment failed attempts
        $failedAttempts = (int)$member['failed_attempts'] + 1;
        $data = ['failed_attempts' => $failedAttempts];
        
        // Lock account after 5 failed attempts
        if ($failedAttempts >= 5) {
            $data['is_locked'] = true;
        }
        
        Database::update('members', $data, ['id' => $member['id']]);
        
        return null;
    }
    
    /**
     * Register a new member
     *
     * @param string $coopNo
     * @param string $name
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function registerMember(string $coopNo, string $name, string $email, string $password): bool
    {
        // Check if member exists and is not registered
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE coop_no = ? AND is_registered = 0",
            [$coopNo]
        );
        
        if (!$member) {
            return false;
        }
        
        // Update member data
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'is_registered' => true
        ];
        
        $updated = Database::update('members', $data, ['id' => $member['id']]);
        
        if ($updated) {
            self::logAction('member', $member['id'], 'Member registration', ['coop_no' => $coopNo]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Log an action to the audit log
     *
     * @param string $userType
     * @param int $userId
     * @param string $action
     * @param array $details
     * @return void
     */
    public static function logAction(string $userType, int $userId, string $action, array $details = []): void
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        Database::insert('audit_logs', [
            'user_id' => $userId,
            'user_type' => $userType,
            'action' => $action,
            'details' => json_encode($details),
            'ip_address' => $ipAddress
        ]);
    }
} 