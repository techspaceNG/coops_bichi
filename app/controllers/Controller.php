<?php
declare(strict_types=1);

// Include URL helper functions
require_once BASE_DIR . '/app/helpers/url_helper.php';

/**
 * Base Controller
 */
class Controller
{
    /**
     * Render a view with data
     *
     * @param string $view View path relative to app/views
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        // Extract data to make variables available in view
        extract($data);
        
        // Include layout header
        include BASE_DIR . '/app/views/layouts/header.php';
        
        // Include the view file
        include BASE_DIR . '/app/views/' . $view . '.php';
        
        // Include layout footer
        include BASE_DIR . '/app/views/layouts/footer.php';
    }
    
    /**
     * Render a view with data (without layout)
     *
     * @param string $view View path relative to app/views
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function renderPartial(string $view, array $data = []): void
    {
        // Extract data to make variables available in view
        extract($data);
        
        // Include the view file
        include BASE_DIR . '/app/views/' . $view . '.php';
    }
    
    /**
     * Render JSON response
     *
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    protected function renderJson(mixed $data, int $statusCode = 200): void
    {
        // Set content type and status code
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        // Encode and output JSON data
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Redirect to URL
     *
     * @param string $url URL to redirect to
     * @return void
     */
    protected function redirect(string $url): void
    {
        Utility::redirect($url);
    }
    
    /**
     * Set flash message
     *
     * @param string $type Message type (success, error, info, warning)
     * @param string $message Message content
     * @return void
     */
    protected function setFlash(string $type, string $message): void
    {
        Utility::setFlashMessage($type, $message);
    }
    
    /**
     * Check if user is logged in as admin
     * If not, redirect to admin login page
     *
     * @return void
     */
    protected function requireAdmin(): void
    {
        if (!Auth::isAdminLoggedIn()) {
            $this->setFlash('error', 'Please log in to access the admin area');
            $this->redirect('/admin/login');
        }
    }
    
    /**
     * Check if user is logged in as a super admin
     * If not, redirect to 403 forbidden page
     *
     * @return void
     */
    protected function requireSuperAdmin(): void
    {
        $this->requireAdmin();
        
        if (!Auth::isSuperAdmin()) {
            $this->redirect('/error/403');
        }
    }
    
    /**
     * Check if user is logged in as member
     * If not, redirect to member login page
     *
     * @return void
     */
    protected function requireMember(): void
    {
        if (!Auth::isMemberLoggedIn()) {
            $this->setFlash('error', 'Please log in to access the member area');
            $this->redirect('/login');
        }
    }
    
    /**
     * Clean input data
     *
     * @param array $data Input data to sanitize
     * @return array Sanitized data
     */
    protected function sanitizeInput(array $data): array
    {
        return Validator::sanitize($data);
    }
    
    /**
     * Render a view with data using superadmin layout
     *
     * @param string $view View path relative to app/views
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function renderSuperAdmin(string $view, array $data = []): void
    {
        // Set current page for sidebar active state
        if (!isset($data['current_page'])) {
            // Extract current page from view path if not provided
            $parts = explode('/', $view);
            $data['current_page'] = end($parts);
        }
        
        // Retrieve admin user data if not already provided
        if (!isset($data['admin'])) {
            $adminId = Auth::getAdminId();
            if ($adminId) {
                $data['admin'] = Database::fetchOne(
                    "SELECT * FROM admin_users WHERE id = ?",
                    [$adminId]
                );
            }
        }
        
        // Retrieve unread notifications for admin
        $adminId = Auth::getAdminId();
        if ($adminId) {
            // Get unread notifications count
            $unreadCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND user_type = 'admin' AND is_read = 0",
                [$adminId]
            );
            $data['unread_notifications_count'] = $unreadCount ? (int)$unreadCount['count'] : 0;
            
            // Get recent notifications
            $recentNotifications = Database::fetchAll(
                "SELECT * FROM notifications WHERE user_id = ? AND user_type = 'admin' ORDER BY created_at DESC LIMIT 5",
                [$adminId]
            );
            
            // Debug: Log the count of notifications found
            error_log("Found " . count($recentNotifications) . " notifications for admin ID: " . $adminId);
            
            $data['recent_notifications'] = $recentNotifications ?: [];
        }
        
        // Extract data to make variables available in view
        extract($data);
        
        // Include superadmin header
        include BASE_DIR . '/app/views/layouts/superadmin_header.php';
        
        // Include the view file
        include BASE_DIR . '/app/views/' . $view . '.php';
        
        // Include superadmin footer
        include BASE_DIR . '/app/views/layouts/superadmin_footer.php';
    }
    
    /**
     * Render a view with data using admin layout
     *
     * @param string $view View path relative to app/views
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function renderAdmin(string $view, array $data = []): void
    {
        // Set current page for sidebar active state
        if (!isset($data['current_page'])) {
            // Extract current page from view path if not provided
            $parts = explode('/', $view);
            $data['current_page'] = end($parts);
        }
        
        // Retrieve admin user data if not already provided
        if (!isset($data['admin'])) {
            $adminId = Auth::getAdminId();
            if ($adminId) {
                $data['admin'] = Database::fetchOne(
                    "SELECT * FROM admin_users WHERE id = ?",
                    [$adminId]
                );
            }
        }
        
        // Retrieve unread notifications for admin
        $adminId = Auth::getAdminId();
        if ($adminId) {
            // Get unread notifications count
            $unreadCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND user_type = 'admin' AND is_read = 0",
                [$adminId]
            );
            $data['unread_notifications_count'] = $unreadCount ? (int)$unreadCount['count'] : 0;
            
            // Get recent notifications
            $recentNotifications = Database::fetchAll(
                "SELECT * FROM notifications WHERE user_id = ? AND user_type = 'admin' ORDER BY created_at DESC LIMIT 5",
                [$adminId]
            );
            
            $data['recent_notifications'] = $recentNotifications ?: [];
        }
        
        // Extract data to make variables available in view
        extract($data);
        
        // Include admin header
        include BASE_DIR . '/app/views/layouts/admin_header.php';
        
        // Include the view file
        include BASE_DIR . '/app/views/' . $view . '.php';
        
        // Include admin footer
        include BASE_DIR . '/app/views/layouts/admin_footer.php';
    }
} 