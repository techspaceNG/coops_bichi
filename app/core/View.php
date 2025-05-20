<?php
declare(strict_types=1);

namespace App\Core;

/**
 * View Helper Class
 * Provides static methods to render views
 */
class View
{
    /**
     * Render a view with data
     *
     * @param string $view View path relative to app/views
     * @param array $data Data to pass to the view
     * @return void
     */
    public static function render(string $view, array $data = []): void
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
    public static function renderPartial(string $view, array $data = []): void
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
    public static function renderJson(mixed $data, int $statusCode = 200): void
    {
        // Set content type and status code
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        // Encode and output JSON data
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Render a view with data using superadmin layout
     *
     * @param string $view View path relative to app/views
     * @param array $data Data to pass to the view
     * @return void
     */
    public static function renderSuperAdmin(string $view, array $data = []): void
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