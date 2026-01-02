<?php
/**
 * URL Helper Functions
 * Contains functions for URL generation and management
 */

if (!function_exists('url')) {
    /**
     * Generate a URL with the correct base path for the application
     * 
     * @param string $path The relative path
     * @return string The complete URL with base path
     */
    function url($path) {
        $baseUrl = \App\Core\Config::getPublicUrl();
        
        // Make sure path starts with a slash
        if (!empty($path) && $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        // Prevent double slashes in the URL
        if (!empty($baseUrl) && substr($baseUrl, -1) === '/' && !empty($path) && $path[0] === '/') {
            $path = substr($path, 1);
        }
        
        return $baseUrl . $path;
    }
} 