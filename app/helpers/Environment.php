<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Environment Helper
 * Loads and manages environment variables from .env file
 */
final class Environment
{
    private static bool $loaded = false;
    private static array $variables = [];

    
    /**
     * Load environment variables from .env file
     *
     * @return void
     */
    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }
        
        $envFile = dirname(__DIR__, 2) . '/.env';
        
        if (!file_exists($envFile)) {
            error_log('Warning: .env file not found');
            self::$loaded = true;
            return;
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            
            $parts = explode('=', $line, 2);
            
            if (count($parts) !== 2) {
                continue;
            }
            
            $name = trim($parts[0]);
            $value = trim($parts[1]);
            
            // Remove quotes if present
            if (strlen($value) > 1 && 
                (($value[0] === '"' && $value[strlen($value) - 1] === '"') || 
                 ($value[0] === "'" && $value[strlen($value) - 1] === "'"))) {
                $value = substr($value, 1, -1);
            }
            
            self::$variables[$name] = $value;
        }
        
        self::$loaded = true;
    }
    
    /**
     * Get environment variable
     *
     * @param string $key Variable key
     * @param mixed $default Default value if variable not found
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (!self::$loaded) {
            self::load();
        }
        
        // Check loaded .env variables
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }

        // Check system environment variables (for Vercel/Cloud)
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        // Check $_ENV superglobal
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        
        return $default;
    }
    
    /**
     * Check if environment variable exists
     *
     * @param string $key Variable key
     * @return bool
     */
    public static function has(string $key): bool
    {
        if (!self::$loaded) {
            self::load();
        }
        
        return isset(self::$variables[$key]);
    }
    
    /**
     * Get all environment variables
     *
     * @return array
     */
    public static function all(): array
    {
        if (!self::$loaded) {
            self::load();
        }
        
        return self::$variables;
    }
} 