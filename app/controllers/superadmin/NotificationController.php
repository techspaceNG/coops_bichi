<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;

/**
 * NotificationController for Superadmin
 * Handles notification management functionality
 */
final class NotificationController extends AbstractController
{
    /**
     * Display notifications list
     */
    public function index(): void
    {
        // Get notifications with pagination
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        
        // Count total records
        $countQuery = "SELECT COUNT(*) as total FROM notifications";
        $countResult = Database::fetchOne($countQuery);
        $total = (int)($countResult['total'] ?? 0);
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        if ($page < 1) $page = 1;
        if ($totalPages > 0 && $page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;
        
        // Get notifications
        $query = "
            SELECT 
                n.*,
                CASE 
                    WHEN n.user_type = 'admin' THEN a.name
                    WHEN n.user_type = 'member' THEN m.name
                    ELSE 'All Users'
                END as recipient_name
            FROM 
                notifications n
            LEFT JOIN 
                admin_users a ON n.user_id = a.id AND n.user_type = 'admin'
            LEFT JOIN 
                members m ON n.user_id = m.id AND n.user_type = 'member'
            ORDER BY 
                n.created_at DESC
            LIMIT 
                {$offset}, {$perPage}
        ";
        
        $notifications = Database::fetchAll($query);
        
        $this->renderSuperAdmin('superadmin/notifications', [
            'notifications' => $notifications,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total' => $total
            ],
            'current_page' => 'notifications',
            'pageTitle' => 'Notification Management'
        ]);
    }
    
    /**
     * Mark a notification as read
     */
    public function markRead(string $id): void
    {
        $id = (int)$id;
        
        // Update notification
        $updated = Database::update('notifications', [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if ($updated) {
            $this->setFlash('success', 'Notification marked as read');
        } else {
            $this->setFlash('error', 'Failed to mark notification as read');
        }
        
        $this->redirect('/superadmin/notifications');
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllRead(): void
    {
        // Update all notifications
        $query = "
            UPDATE notifications 
            SET is_read = 1, read_at = ? 
            WHERE (user_type = 'admin' AND user_id = ?) OR user_id IS NULL
        ";
        
        $updated = Database::execute($query, [
            date('Y-m-d H:i:s'),
            Auth::getAdminId()
        ]);
        
        if ($updated) {
            $this->setFlash('success', 'All notifications marked as read');
        } else {
            $this->setFlash('error', 'Failed to mark notifications as read');
        }
        
        $this->redirect('/superadmin/notifications');
    }
    
    /**
     * Create sample notifications for testing
     */
    public function createSample(): void
    {
        $notifications = [
            [
                'title' => 'System Update',
                'message' => 'The system will be updated tonight at 2 AM. Please save your work.',
                'link' => '/superadmin/system-settings',
                'user_type' => null,
                'user_id' => null
            ],
            [
                'title' => 'New Loan Application',
                'message' => 'A new loan application has been submitted and requires your review.',
                'link' => '/superadmin/loans',
                'user_type' => 'admin',
                'user_id' => Auth::getAdminId()
            ],
            [
                'title' => 'Security Alert',
                'message' => 'Multiple failed login attempts detected from IP 192.168.1.1',
                'link' => '/superadmin/system-logs',
                'user_type' => 'admin',
                'user_id' => Auth::getAdminId()
            ]
        ];
        
        $insertedCount = 0;
        
        foreach ($notifications as $notification) {
            $notification['created_at'] = date('Y-m-d H:i:s');
            
            $result = Database::insert('notifications', $notification);
            if ($result) {
                $insertedCount++;
            }
        }
        
        if ($insertedCount > 0) {
            $this->setFlash('success', "Successfully created {$insertedCount} sample notifications.");
        } else {
            $this->setFlash('error', 'Failed to create sample notifications.');
        }
        
        $this->redirect('/superadmin/notifications');
    }
    
    /**
     * Show form to create a new notification
     */
    public function create(): void
    {
        $this->renderSuperAdmin('superadmin/create-notification', [
            'current_page' => 'notifications',
            'pageTitle' => 'Create New Notification'
        ]);
    }
    
    /**
     * Save a new notification
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/notifications');
            return;
        }

        try {
            // Process form submission
            $title = trim($_POST['title'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $link = trim($_POST['link'] ?? '');
            $notificationType = $_POST['notification_type'] ?? 'global';
            
            // Validate required fields
            if (empty($title) || empty($message)) {
                throw new \Exception('Title and message are required');
            }
            
            $insertedCount = 0;
            $failedCount = 0;
            
            if ($notificationType === 'global') {
                // Create global notification (for all users)
                $notificationData = [
                    'title' => $title,
                    'message' => $message,
                    'link' => $link,
                    'user_type' => null,
                    'user_id' => null,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $result = Database::insert('notifications', $notificationData);
                if ($result) {
                    $insertedCount++;
                } else {
                    $failedCount++;
                }
            } elseif ($notificationType === 'admins') {
                // Create notification for all admins
                $admins = Database::fetchAll("SELECT id FROM admin_users WHERE is_locked = 0");
                
                foreach ($admins as $admin) {
                    $notificationData = [
                        'title' => $title,
                        'message' => $message,
                        'link' => $link,
                        'user_type' => 'admin',
                        'user_id' => $admin['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $result = Database::insert('notifications', $notificationData);
                    if ($result) {
                        $insertedCount++;
                    } else {
                        $failedCount++;
                    }
                }
            } elseif ($notificationType === 'members') {
                // Create notification for all members
                $members = Database::fetchAll("SELECT id FROM members WHERE is_active = 1");
                
                foreach ($members as $member) {
                    $notificationData = [
                        'title' => $title,
                        'message' => $message,
                        'link' => $link,
                        'user_type' => 'member',
                        'user_id' => $member['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $result = Database::insert('notifications', $notificationData);
                    if ($result) {
                        $insertedCount++;
                    } else {
                        $failedCount++;
                    }
                }
            } elseif ($notificationType === 'specific' && !empty($_POST['member_ids'])) {
                // Create notification for specific members
                $memberIds = json_decode($_POST['member_ids'], true);
                
                if (!is_array($memberIds)) {
                    throw new \Exception('Invalid member selection');
                }
                
                foreach ($memberIds as $memberId) {
                    $notificationData = [
                        'title' => $title,
                        'message' => $message,
                        'link' => $link,
                        'user_type' => 'member',
                        'user_id' => $memberId,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $result = Database::insert('notifications', $notificationData);
                    if ($result) {
                        $insertedCount++;
                    } else {
                        $failedCount++;
                    }
                }
            }
            
            if ($failedCount > 0) {
                $this->setFlash('warning', "Created $insertedCount notifications, but $failedCount failed to be created.");
            } else {
                $this->setFlash('success', "Successfully created $insertedCount notifications.");
            }
            
            // Store the form data in the session in case we need to redirect back with errors
            $_SESSION['form_data'] = $_POST;
            
            $this->redirect('/superadmin/notifications');
            
        } catch (\Exception $e) {
            // Log the detailed error
            error_log("Error creating notifications: " . $e->getMessage());
            
            // Store the form data in the session so it can be repopulated
            $_SESSION['form_data'] = $_POST;
            
            $this->setFlash('error', "Error creating notifications: " . $e->getMessage());
            $this->redirect('/superadmin/create-notification');
        }
    }
} 