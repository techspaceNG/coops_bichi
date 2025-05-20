<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Session management helper
 */
final class Session
{
    /**
     * Initialize the session
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure session parameters
            ini_set('session.use_only_cookies', '1');
            ini_set('session.use_strict_mode', '1');
            
            // Set cookie parameters
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            
            session_start();
        }
    }
    
    /**
     * Set a session variable
     *
     * @param string $key The key
     * @param mixed $value The value
     */
    public static function set(string $key, mixed $value): void
    {
        self::init();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get a session variable
     *
     * @param string $key The key
     * @param mixed $default Default value if key does not exist
     * @return mixed The session value or default
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::init();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if a session variable exists
     *
     * @param string $key The key
     * @return bool True if exists, false otherwise
     */
    public static function has(string $key): bool
    {
        self::init();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove a session variable
     *
     * @param string $key The key
     */
    public static function remove(string $key): void
    {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Set a flash message
     * Flash messages only appear once and are automatically cleared after being retrieved
     *
     * @param string $key The key
     * @param mixed $value The value
     */
    public static function setFlash(string $key, mixed $value): void
    {
        self::init();
        $_SESSION['flash'][$key] = $value;
    }
    
    /**
     * Get a flash message and clear it
     *
     * @param string $key The key
     * @param mixed $default Default value if key does not exist
     * @return mixed The flash value or default
     */
    public static function getFlash(string $key, mixed $default = null): mixed
    {
        self::init();
        $value = $_SESSION['flash'][$key] ?? $default;
        
        if (isset($_SESSION['flash'][$key])) {
            unset($_SESSION['flash'][$key]);
        }
        
        return $value;
    }
    
    /**
     * Check if a flash message exists
     *
     * @param string $key The key
     * @return bool True if exists, false otherwise
     */
    public static function hasFlash(string $key): bool
    {
        self::init();
        return isset($_SESSION['flash'][$key]);
    }
    
    /**
     * Clear all flash messages
     */
    public static function clearAllFlash(): void
    {
        self::init();
        $_SESSION['flash'] = [];
    }
    
    /**
     * Check if user is logged in
     *
     * @return bool True if logged in, false otherwise
     */
    public static function isLoggedIn(): bool
    {
        self::init();
        return isset($_SESSION['user_id']) || isset($_SESSION['member_id']);
    }
    
    /**
     * Check if logged in user is an admin
     *
     * @return bool True if admin, false otherwise
     */
    public static function isAdmin(): bool
    {
        self::init();
        return self::isLoggedIn() && $_SESSION['user_type'] === 'admin';
    }
    
    /**
     * Check if logged in user is a member
     *
     * @return bool True if member, false otherwise
     */
    public static function isMember(): bool
    {
        self::init();
        return self::isLoggedIn() && 
               (($_SESSION['user_type'] ?? '') === 'member' || 
                isset($_SESSION['member_logged_in']) && $_SESSION['member_logged_in'] === true);
    }
    
    /**
     * Get the current user ID
     *
     * @return int|null User ID or null if not logged in
     */
    public static function userId(): ?int
    {
        self::init();
        if (isset($_SESSION['user_id'])) {
            return (int)$_SESSION['user_id'];
        }
        if (isset($_SESSION['member_id'])) {
            return (int)$_SESSION['member_id'];
        }
        return null;
    }
    
    /**
     * Regenerate the session ID
     *
     * @param bool $deleteOldSession Whether to delete the old session data
     */
    public static function regenerate(bool $deleteOldSession = true): void
    {
        self::init();
        session_regenerate_id($deleteOldSession);
    }
    
    /**
     * Destroy the session
     */
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Clear session data
            $_SESSION = [];
            
            // Delete the session cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
            
            // Destroy the session
            session_destroy();
        }
    }
} 