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
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $baseUrl = '';
        
        // If we're in a subdirectory, extract it properly
        if ($scriptDir !== '/' && $scriptDir !== '\\') {
            $baseUrl = $scriptDir;
            
            // Make sure we're consistent with case sensitivity
            if (strtolower($baseUrl) === '/coops_bichi/public') {
                $baseUrl = '/Coops_Bichi/public';
            } 
            // Make sure the baseUrl always includes /public for proper routing
            else if (strtolower($baseUrl) === '/coops_bichi') {
                $baseUrl = '/Coops_Bichi/public';
            }
            
            // Handle XAMPP-specific case
            if (strpos(strtolower($baseUrl), '/public') !== false) {
                // Keep /public in the URL since we want to point to the publicly accessible directory
                if (strtolower($baseUrl) !== '/coops_bichi/public' && strtolower($baseUrl) !== '/coops_bichi/public/') {
                    $publicPos = strpos(strtolower($baseUrl), '/public');
                    $baseUrl = substr($baseUrl, 0, $publicPos);
                    
                    // Force consistent case
                    if (strtolower($baseUrl) === '/coops_bichi') {
                        $baseUrl = '/Coops_Bichi';
                    }
                    
                    $baseUrl .= '/public';
                }
            }
        } else {
            // Default case for root installs
            if (isset($_SERVER['HTTP_HOST']) && strpos(strtolower($_SERVER['HTTP_HOST']), 'localhost') !== false) {
                $baseUrl = '/Coops_Bichi/public';
            }
        }
        
        // Additional fallback for XAMPP installations
        if (empty($baseUrl)) {
            $requestUrl = $_SERVER['REQUEST_URI'];
            if (strpos(strtolower($requestUrl), '/coops_bichi') === 0) {
                $baseUrl = '/Coops_Bichi/public';
            }
        }
        
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