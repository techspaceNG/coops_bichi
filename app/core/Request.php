<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Request class
 * Handles HTTP request data
 */
class Request
{
    /**
     * Check if the current request is an AJAX request
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Validate CSRF token
     *
     * @return bool
     */
    public static function validateCsrfToken(): bool
    {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        $storedToken = $_SESSION['csrf_token'] ?? '';
        
        return !empty($token) && !empty($storedToken) && hash_equals($storedToken, $token);
    }
    
    /**
     * Generate a CSRF token
     *
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
    
    /**
     * Get CSRF token
     *
     * @return string
     */
    public static function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            return self::generateCsrfToken();
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Get request method
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
    
    /**
     * Get request URI
     *
     * @return string
     */
    public static function getUri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }
    
    /**
     * Get request IP address
     *
     * @return string
     */
    public static function getIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
} 