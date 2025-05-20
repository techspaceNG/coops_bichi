<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Notification Model
 */
class Notification
{
    public int $id;
    public int $member_id;
    public string $title;
    public string $message;
    public string $type; // 'info', 'success', 'warning', 'error'
    public bool $is_read;
    public ?string $action_url;
    public string $created_at;
    public string $updated_at;
    
    private static ?PDO $db = null;
    
    /**
     * Get database connection
     */
    private static function getDb(): PDO
    {
        if (self::$db === null) {
            self::$db = Database::getConnection();
            
            // Set fetch mode to assoc by default
            self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$db;
    }
    
    /**
     * Get notifications for a member
     * 
     * @param int $member_id Member ID
     * @param int $limit Number of notifications to fetch
     * @param bool $include_read Whether to include read notifications
     * @param bool $isRetry Whether this is a retry call to prevent infinite recursion
     * @return array Array of notifications
     */
    public static function getForMember(int $member_id, int $limit = 5, bool $include_read = false, bool $isRetry = false): array
    {
        try {
            $db = self::getDb();
            $query = "SELECT id, user_id as member_id, title, message, type, is_read, link as action_url, created_at, updated_at 
                    FROM notifications 
                    WHERE user_id = :member_id AND user_type = 'member' " .
                    ($include_read ? "" : "AND is_read = 0 ") .
                    "ORDER BY created_at DESC LIMIT :limit";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            // Fetch results incrementally instead of all at once
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = $row;
            }
            
            return $results;
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            if (!$isRetry) {
                $tableExists = self::createTableIfNotExists();
                
                if ($tableExists) {
                    // Retry only once if table was created successfully
                    return self::getForMember($member_id, $limit, $include_read, true);
                }
            }
            
            // Log the error
            error_log('Error fetching notifications: ' . $e->getMessage());
            
            // Return empty array instead of sample notifications
            return [];
        }
    }
    
    /**
     * Get unread notification count for a member
     * 
     * @param int $member_id Member ID
     * @return int Number of unread notifications
     */
    public static function getUnreadCount(int $member_id): int
    {
        try {
            $db = self::getDb();
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = :member_id AND user_type = 'member' AND is_read = 0");
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        } catch (\PDOException $e) {
            // Log the error
            error_log('Error getting unread notification count: ' . $e->getMessage());
            // Return 0 if there was an error
            return 0;
        }
    }
    
    /**
     * Mark notification as read
     * 
     * @param int $notification_id Notification ID
     * @param int $member_id Member ID for security check
     * @return bool Success or failure
     */
    public static function markAsRead(int $notification_id, int $member_id): bool
    {
        try {
            $db = self::getDb();
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1, read_at = NOW(), updated_at = NOW() 
                WHERE id = :id AND user_id = :member_id AND user_type = 'member'");
            $stmt->bindParam(':id', $notification_id, PDO::PARAM_INT);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Error marking notification as read: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark all notifications as read for a member
     * 
     * @param int $member_id Member ID
     * @return bool Success or failure
     */
    public static function markAllAsRead(int $member_id): bool
    {
        try {
            $db = self::getDb();
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1, read_at = NOW(), updated_at = NOW() 
                WHERE user_id = :member_id AND user_type = 'member' AND is_read = 0");
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Error marking all notifications as read: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create a new notification
     * 
     * @param int $member_id Member ID
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string $type Notification type (info, success, warning, error)
     * @param string|null $action_url URL for action button (optional)
     * @param bool $isRetry Whether this is a retry call to prevent infinite recursion
     * @return bool Success or failure
     */
    public static function create(int $member_id, string $title, string $message, string $type = 'info', ?string $action_url = null, bool $isRetry = false): bool
    {
        try {
            $db = self::getDb();
            $stmt = $db->prepare("
                INSERT INTO notifications (user_id, user_type, title, message, type, link, is_read, created_at, updated_at)
                VALUES (:member_id, 'member', :title, :message, :type, :action_url, 0, NOW(), NOW())
            ");
            
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':action_url', $action_url);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            if (!$isRetry) {
                $tableExists = self::createTableIfNotExists();
                
                if ($tableExists) {
                    // Retry only once if table was created successfully
                    return self::create($member_id, $title, $message, $type, $action_url, true);
                }
            }
            
            // Log the error
            error_log('Error creating notification: ' . $e->getMessage());
            
            return false;
        }
    }
    
    /**
     * Create notifications table if it doesn't exist
     * 
     * @return bool Success or failure
     */
    private static function createTableIfNotExists(): bool
    {
        try {
            $db = self::getDb();
            $db->exec("
                CREATE TABLE IF NOT EXISTS `notifications` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `user_type` enum('member','admin') NOT NULL DEFAULT 'member',
                    `title` varchar(255) NOT NULL,
                    `message` text NOT NULL,
                    `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
                    `is_read` tinyint(1) NOT NULL DEFAULT 0,
                    `link` varchar(255) DEFAULT NULL,
                    `read_at` datetime DEFAULT NULL,
                    `created_at` datetime NOT NULL,
                    `updated_at` datetime NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `user_id` (`user_id`),
                    KEY `user_type` (`user_type`),
                    KEY `is_read` (`is_read`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
            return true;
        } catch (\PDOException $e) {
            error_log('Error creating notifications table: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get sample notifications for demonstration purposes
     * 
     * @param int $member_id Member ID
     * @param int $limit Maximum number of sample notifications to return
     * @return array Sample notifications
     */
    private static function getSampleNotifications(int $member_id, int $limit = 3): array
    {
        $samples = [
            [
                'id' => 1,
                'member_id' => $member_id,
                'user_id' => $member_id,
                'title' => 'Loan Application Approved',
                'message' => 'Your loan application has been approved. The funds will be disbursed shortly.',
                'type' => 'success',
                'is_read' => 0,
                'action_url' => '/Coops_Bichi/public/member/loans',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'id' => 2,
                'member_id' => $member_id,
                'user_id' => $member_id,
                'title' => 'Monthly Savings Contribution Received',
                'message' => 'Your monthly savings contribution has been received.',
                'type' => 'info',
                'is_read' => 0,
                'action_url' => '/Coops_Bichi/public/member/savings',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 day'))
            ],
            [
                'id' => 3,
                'member_id' => $member_id,
                'user_id' => $member_id,
                'title' => 'Password Changed',
                'message' => 'Your account password was recently changed. If you did not make this change, please contact administrator immediately.',
                'type' => 'warning',
                'is_read' => 0,
                'action_url' => '/Coops_Bichi/public/member/profile',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 day'))
            ]
        ];
        
        return array_slice($samples, 0, $limit);
    }

    /**
     * Get notifications for a member with pagination
     * 
     * @param int $member_id Member ID
     * @param int $limit Number of notifications per page
     * @param bool $include_read Whether to include read notifications
     * @param int $offset Pagination offset
     * @param bool $isRetry Whether this is a retry call to prevent infinite recursion
     * @return array Array of notifications
     */
    public static function getForMemberPaginated(int $member_id, int $limit = 10, bool $include_read = false, int $offset = 0, bool $isRetry = false): array
    {
        try {
            $db = self::getDb();
            $query = "SELECT id, user_id as member_id, title, message, type, is_read, link as action_url, created_at, updated_at 
                    FROM notifications 
                    WHERE user_id = :member_id AND user_type = 'member' " .
                    ($include_read ? "" : "AND is_read = 0 ") .
                    "ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            // Fetch results incrementally instead of all at once
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = $row;
            }
            
            return $results;
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            if (!$isRetry) {
                $tableExists = self::createTableIfNotExists();
                
                if ($tableExists) {
                    // Retry only once if table was created successfully
                    return self::getForMemberPaginated($member_id, $limit, $include_read, $offset, true);
                }
            }
            
            // Log error and return empty array
            error_log('Error fetching paginated notifications: ' . $e->getMessage());
            
            // Return empty array instead of sample data
            return [];
        }
    }

    /**
     * Get total notification count for a member
     * 
     * @param int $member_id Member ID
     * @param bool $include_read Whether to include read notifications
     * @param bool $isRetry Whether this is a retry call to prevent infinite recursion
     * @return int Total number of notifications
     */
    public static function getTotalCount(int $member_id, bool $include_read = true, bool $isRetry = false): int
    {
        try {
            $db = self::getDb();
            $query = "SELECT COUNT(*) as total FROM notifications 
                    WHERE user_id = :member_id AND user_type = 'member' " .
                    ($include_read ? "" : "AND is_read = 0");
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            if (!$isRetry) {
                $tableExists = self::createTableIfNotExists();
                
                if ($tableExists) {
                    // Retry only once if table was created successfully
                    return self::getTotalCount($member_id, $include_read, true);
                }
            }
            
            // Log error and return 0
            error_log('Error getting total notification count: ' . $e->getMessage());
            
            // Return 0 for notification count on error
            return 0;
        }
    }
} 