<?php
declare(strict_types=1);

// Start session
session_start();

// Error reporting configuration
ini_set('display_errors', '1'); // Temporarily enable error display for debugging
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Define base directory
define('BASE_DIR', dirname(__DIR__));
define('APP_ROOT', BASE_DIR . '/app');

// Load application constants
require_once BASE_DIR . '/app/config/constants.php';

// Load Composer's autoloader if available
if (file_exists(BASE_DIR . '/vendor/autoload.php')) {
    require_once BASE_DIR . '/vendor/autoload.php';
}

// Autoload classes
spl_autoload_register(function($className) {
    // Convert namespace to file path
    $className = str_replace('\\', '/', $className);
    
    // Remove leading App/ if it exists
    if (strpos($className, 'App/') === 0) {
        $className = substr($className, 4);
    }
    
    // Try the direct path first
    $file = BASE_DIR . '/app/' . $className . '.php';
    
    // If direct path doesn't exist, try case-insensitive alternatives
    if (!file_exists($file)) {
        // For Config/Database.php, try alternative cases
        if (stripos($className, 'config/database') !== false) {
            $alternatives = [
                BASE_DIR . '/app/config/Database.php',
                BASE_DIR . '/app/Config/Database.php',
                BASE_DIR . '/app/Config/database.php',
                BASE_DIR . '/app/config/database.php',
            ];
            
            foreach ($alternatives as $altFile) {
                if (file_exists($altFile)) {
                    $file = $altFile;
                    break;
                }
            }
        }
    }
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Log failed autoload attempts
    error_log("Failed to autoload class: $className, tried file: $file");
    return false;
});

// Include route configuration
require_once BASE_DIR . '/app/config/routes.php';

// Get the requested URL
$requestUrl = $_SERVER['REQUEST_URI'];

// Debug info - remove in production
if ($_SERVER['QUERY_STRING'] === 'debug') {
    echo '<pre>';
    echo 'REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . "\n";
    echo 'SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'] . "\n";
    echo 'Base Path: ' . dirname($_SERVER['SCRIPT_NAME']) . "\n";
    echo '</pre>';
}

// Remove query string
$position = strpos($requestUrl, '?');
if ($position !== false) {
    $requestUrl = substr($requestUrl, 0, $position);
}

// Determine base path differently - more reliable
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = '';

// Check if we are in a serverless environment (Vercel)
if (defined('SERVERLESS_ENVIRONMENT') && SERVERLESS_ENVIRONMENT) {
    $baseUrl = '';
    // On Vercel, we might need to strip /api/index.php if it's there, 
    // but usually REQUEST_URI is what we want.
} 
// If we're in a subdirectory, extract it properly
else if ($scriptDir !== '/' && $scriptDir !== '\\') {
    // Special handling for Coops_Bichi in XAMPP
    if (stripos($requestUrl, '/coops_bichi/public') === 0) {
        $baseUrl = '/Coops_Bichi/public';
        $requestUrl = substr($requestUrl, strlen($baseUrl)) ?: '/';
    } else if (stripos($requestUrl, '/coops_bichi') === 0) {
        // For cases where /public is missing
        $baseUrl = '/Coops_Bichi';
        // Check if this is just the base URL without the /public part
        if (stripos($requestUrl, '/public') === false) {
            header('Location: ' . $baseUrl . '/public');
            exit;
        }
    } else if (strpos($requestUrl, $scriptDir) === 0) {
        // Standard case - request contains the script directory
        $baseUrl = $scriptDir;
        $requestUrl = substr($requestUrl, strlen($baseUrl));
    }
} else {
    // For XAMPP or similar installations, try a different approach
    $parts = explode('/', trim($requestUrl, '/'));
    if (count($parts) > 0) {
        $firstPart = strtolower($parts[0]);
        if ($firstPart === 'coops_bichi') {
            // This is a fallback for XAMPP-like installations
            $baseUrl = '/Coops_Bichi'; // Use consistent uppercase C
            $requestUrl = '/' . implode('/', array_slice($parts, 1));
            
            // Check if the next part is 'public'
            if (count($parts) > 1 && strtolower($parts[1]) === 'public') {
                $baseUrl = '/Coops_Bichi/public';
                $requestUrl = '/' . implode('/', array_slice($parts, 2));
            } else {
                // Redirect to include public
                header('Location: ' . $baseUrl . '/public');
                exit;
            }
        }
    }
}

// Normalize URL
$requestUrl = '/' . trim($requestUrl, '/');

// Default route
if ($requestUrl === '/') {
    $requestUrl = '/home';
}

// Debug point - remove in production
if ($_SERVER['QUERY_STRING'] === 'debug') {
    echo '<pre>';
    echo 'Processed URL: ' . $requestUrl . "\n";
    echo 'Base URL: ' . $baseUrl . "\n";
    echo '</pre>';
    exit;
}

// Route the request
$routeFound = false;

foreach ($routes as $route => $handler) {
    // Convert route pattern to regex
    $pattern = preg_replace('/{([^\/]+)}/', '(?P<$1>[^/]+)', $route);
    $pattern = '#^' . $pattern . '$#';
    
    if (preg_match($pattern, $requestUrl, $matches)) {
        $routeFound = true;
        
        // Extract parameters
        $params = array_filter($matches, function($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
        
        // Call the handler
        list($controllerName, $methodName) = explode('@', $handler);
        
        // Add namespace if not provided
        if (strpos($controllerName, '\\') === false) {
            $controllerName = 'App\\Controllers\\' . $controllerName;
        }
        
        // Create controller instance and call method
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                // Get method parameters to check what type it expects
                $reflection = new \ReflectionMethod($controller, $methodName);
                $parameters = $reflection->getParameters();
                
                // Check if the method expects an array as its first parameter
                if (!empty($parameters) && count($parameters) === 1 && 
                    ($parameters[0]->getType() && $parameters[0]->getType()->getName() === 'array')) {
                    // Pass the entire params array when the method expects an array
                    call_user_func([$controller, $methodName], $params);
                } else {
                    // Otherwise, pass individual parameters
                    if (!empty($params)) {
                        // Extract individual parameters to pass to the method
                        $methodParams = array_values($params);
                        call_user_func_array([$controller, $methodName], $methodParams);
                    } else {
                        // No parameters to pass
                        call_user_func([$controller, $methodName]);
                    }
                }
                break;
            } else {
                echo "Method $methodName not found in controller $controllerName";
                exit;
            }
        } else {
            echo "Controller class $controllerName not found";
            exit;
        }
    }
}

// Handle 404 Not Found
if (!$routeFound) {
    header("HTTP/1.0 404 Not Found");
    include BASE_DIR . '/app/views/errors/404.php';
} 