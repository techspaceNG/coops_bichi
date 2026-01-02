<?php
declare(strict_types=1);

namespace App\Core;

// Include URL helper functions
require_once BASE_DIR . '/app/helpers/url_helper.php';

use App\Helpers\Auth;
use App\Helpers\Utility;
use App\Helpers\Validator;

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
        if (!empty($url) && $url[0] === '/' && strpos($url, '//') !== 0) {
            $baseUrl = \App\Core\Config::getPublicUrl();
            
            // Don't add baseUrl if it's already included in the URL
            if (!empty($baseUrl) && strpos($url, $baseUrl) !== 0) {
                $url = $baseUrl . $url;
            }
        }
        
        Utility::redirect($url);
    }
    
    /**
     * Set flash message
     *
     * @param string $type Message type (success, error, info, warning)
     * @param string $message Message content
     * @param string $page Optional page identifier to limit where message appears
     * @return void
     */
    protected function setFlash(string $type, string $message, string $page = 'global'): void
    {
        Utility::setFlashMessage($type, $message, $page);
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
            
            $publicUrl = \App\Core\Config::getPublicUrl();
            header('Location: ' . $publicUrl . '/admin/login');
            exit;
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
            
            $publicUrl = \App\Core\Config::getPublicUrl();
            header('Location: ' . $publicUrl . '/login');
            exit;
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
     * Render an admin view with data
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
            $adminId = \App\Helpers\Auth::getAdminId();
            if ($adminId) {
                $data['admin'] = \App\Helpers\Database::fetchOne(
                    "SELECT * FROM admin_users WHERE id = ?",
                    [$adminId]
                );
            }
        }
        
        // Extract data to make variables available in view
        extract($data);
        
        // Include layout header
        include BASE_DIR . '/app/views/layouts/admin_header.php';
        
        // Include the view file
        include BASE_DIR . '/app/views/' . $view . '.php';
        
        // Include layout footer
        include BASE_DIR . '/app/views/layouts/admin_footer.php';
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
            $adminId = \App\Helpers\Auth::getAdminId();
            if ($adminId) {
                $data['admin'] = \App\Helpers\Database::fetchOne(
                    "SELECT * FROM admin_users WHERE id = ?",
                    [$adminId]
                );
            }
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
} 