<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Controller;
use App\Core\Database;

/**
 * AbstractController for Superadmin
 * Base controller for all superadmin controllers
 */
abstract class AbstractController extends Controller
{
    protected $db;

    public function __construct()
    {
        // Don't call parent constructor directly since it doesn't exist
        // Instead, set up what we need directly
        $this->requireSuperAdmin();
        $this->db = Database::getConnection();
    }

    /**
     * Update a single setting in the database
     */
    protected function updateSetting(string $key, string $value): bool
    {
        // Sanitize the key and value
        $key = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        // Update the setting
        return (bool) Database::query(
            "INSERT INTO system_settings (setting_key, value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE value = ?",
            [$key, $value, $value]
        );
    }

    /**
     * Clean input data
     */
    protected function sanitizeInput(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInput($value);
            } else {
                $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        
        return $sanitized;
    }
} 