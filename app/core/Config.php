<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Application Configuration
 */
final class Config
{
    /**
     * Get the base URL of the application
     * 
     * @return string
     */
    public static function getBaseUrl(): string
    {
        // Check if we are in a serverless environment (Vercel)
        if (defined('SERVERLESS_ENVIRONMENT') && SERVERLESS_ENVIRONMENT) {
            return '';
        }

        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Handle XAMPP/Localhost subdirectories
        if ($scriptDir !== '/' && $scriptDir !== '\\') {
            $baseUrl = $scriptDir;
            
            // Special handling for Coops_Bichi case sensitivity
            if (strpos(strtolower($baseUrl), '/coops_bichi') !== false) {
                // If the URL already contains /public, return the part before it + /public
                if (strpos(strtolower($baseUrl), '/public') !== false) {
                    $publicPos = strpos(strtolower($baseUrl), '/public');
                    return '/Coops_Bichi/public';
                }
                return '/Coops_Bichi';
            }
            return $baseUrl;
        }

        return '';
    }

    /**
     * Get the public URL of the application (usually includes /public)
     * 
     * @return string
     */
    public static function getPublicUrl(): string
    {
        $baseUrl = self::getBaseUrl();
        
        // If baseUrl already ends with /public, just return it
        if (substr(strtolower($baseUrl), -7) === '/public') {
            return $baseUrl;
        }
        
        // If baseUrl is empty, it depends on the directory structure.
        // In this project, if we're at root, we usually want to point to the current root.
        return $baseUrl !== '' ? $baseUrl . '/public' : '';
    }

    /**
     * Get database configuration from environment variables
     * 
     * @return array
     */
    public static function getDatabaseConfig(): array
    {
        return [
            'host'     => getenv('DB_HOST') ?: 'localhost',
            'dbname'   => getenv('DB_NAME') ?: 'coops_bichi',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASS') ?: '',
            'charset'  => getenv('DB_CHARSET') ?: 'utf8mb4',
        ];
    }
}
