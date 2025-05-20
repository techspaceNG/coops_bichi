<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Response class
 * Handles HTTP responses
 */
class Response
{
    /**
     * Send a JSON response
     *
     * @param mixed $data The data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    public static function json($data, int $statusCode = 200): void
    {
        // Clear any output buffering to prevent contamination
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Set headers
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        // Encode data as JSON
        $json = json_encode($data);
        
        // Check for JSON encoding errors
        if ($json === false) {
            // Log the error
            error_log('JSON encoding error: ' . json_last_error_msg());
            
            // Send error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Could not encode response data as JSON']);
            exit;
        }
        
        // Output JSON
        echo $json;
        exit;
    }
    
    /**
     * Redirect to a URL
     *
     * @param string $url The URL to redirect to
     * @param int $statusCode HTTP status code for the redirect
     * @return void
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
} 