<?php
declare(strict_types=1);

namespace App\Traits;

use App\Core\Database;
use App\Helpers\Auth;

/**
 * Activity Logger Trait
 * Provides methods for logging user activities
 */
trait ActivityLogger
{
    /**
     * Log an activity
     *
     * @param string $action The action being performed
     * @param int|null $targetId The ID of the target entity (optional)
     * @param string $actionType The type of action (default: 'general')
     * @param array $details Additional details to log (optional)
     * @return bool Whether the log was created successfully
     */
    protected function logActivity(
        string $action,
        ?int $targetId = null,
        string $actionType = 'general',
        array $details = []
    ): bool {
        try {
            // Get the current user's ID and type
            $userId = Auth::isAdminLoggedIn() ? Auth::getAdminId() : Auth::getMemberId();
            $userType = Auth::isAdminLoggedIn() ? 'admin' : 'member';
            
            // Prepare log data
            $logData = [
                'user_id' => $userId,
                'user_type' => $userType,
                'action' => $action,
                'action_type' => $actionType,
                'details' => json_encode($details),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Insert log entry
            return Database::insert('audit_logs', $logData) !== null;
        } catch (\Exception $e) {
            // Log error but don't throw it (logging should not break main functionality)
            error_log('Activity logging error: ' . $e->getMessage());
            return false;
        }
    }
} 