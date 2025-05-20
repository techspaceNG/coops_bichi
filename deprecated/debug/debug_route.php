<?php
// Set error reporting
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo '<h1>URL Debugging Tool</h1>';

// Get server info
echo '<h2>Server Information</h2>';
echo '<pre>';
echo 'REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . "\n";
echo 'SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'] . "\n";
echo 'PHP_SELF: ' . $_SERVER['PHP_SELF'] . "\n";
echo 'DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo 'SERVER_NAME: ' . $_SERVER['SERVER_NAME'] . "\n";
echo 'HTTP_HOST: ' . $_SERVER['HTTP_HOST'] . "\n";
echo '</pre>';

// Current URL parsing
echo '<h2>Current URL Analysis</h2>';
$requestUrl = $_SERVER['REQUEST_URI'];
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);

echo '<pre>';
echo "Original Request URL: $requestUrl\n";
echo "Script Directory: $scriptDir\n";

// Remove query string if present
$position = strpos($requestUrl, '?');
if ($position !== false) {
    $requestUrl = substr($requestUrl, 0, $position);
    echo "URL without query string: $requestUrl\n";
}

// Determine base path
$baseUrl = '';
if ($scriptDir !== '/' && $scriptDir !== '\\' && strpos($requestUrl, $scriptDir) === 0) {
    $baseUrl = $scriptDir;
    $requestUrl = substr($requestUrl, strlen($scriptDir));
    echo "Base URL from script dir: $baseUrl\n";
    echo "Relative URL after base extraction: $requestUrl\n";
} else {
    // XAMPP fallback
    $parts = explode('/', trim($requestUrl, '/'));
    if (count($parts) > 0) {
        $firstPart = strtolower($parts[0]);
        if ($firstPart === 'coops_bichi') {
            $baseUrl = '/Coops_Bichi'; // Use consistent uppercase C
            $requestUrl = '/' . implode('/', array_slice($parts, 1));
            echo "Base URL from XAMPP fallback: $baseUrl\n";
            echo "Relative URL after XAMPP extraction: $requestUrl\n";
        }
    }
}

// Normalize URL
$normalizedUrl = '/' . trim($requestUrl, '/');
echo "Normalized URL: $normalizedUrl\n";
echo '</pre>';

// Test Admin Dashboard URL construction
echo '<h2>Admin Dashboard URL Test</h2>';
echo '<pre>';
$adminDashboardUrl = $baseUrl . '/public/admin/dashboard';
echo "Admin Dashboard URL would be: $adminDashboardUrl\n";

$adminDashboardUrl2 = $baseUrl . '/admin/dashboard';
echo "Alternative Admin Dashboard URL: $adminDashboardUrl2\n";
echo '</pre>';

// Test file locations
echo '<h2>File System Testing</h2>';
echo '<pre>';
$controllerPath = __DIR__ . '/app/controllers/AdminController.php';
echo "AdminController path: $controllerPath\n";
echo "File exists: " . (file_exists($controllerPath) ? 'Yes' : 'No') . "\n";

$baseDir = dirname(__DIR__);
$controllerPath2 = $baseDir . '/app/controllers/AdminController.php';
echo "Alternative AdminController path: $controllerPath2\n";
echo "File exists: " . (file_exists($controllerPath2) ? 'Yes' : 'No') . "\n";
echo '</pre>';

// Route debugging
echo '<h2>Route Testing</h2>';
echo '<p>Testing if routes are correctly defined and recognized</p>';
echo '<pre>';
// Include route configuration (assuming it's accessible)
$routes = [];
$routeFile = __DIR__ . '/app/config/routes.php';
if (file_exists($routeFile)) {
    include $routeFile;
    echo "Number of routes defined: " . count($routes) . "\n";
    
    // Test if admin dashboard route exists
    echo "Admin dashboard route exists: " . (isset($routes['/admin/dashboard']) ? 'Yes' : 'No') . "\n";
    if (isset($routes['/admin/dashboard'])) {
        echo "Handler: " . $routes['/admin/dashboard'] . "\n";
    }
} else {
    echo "Route configuration file not found at: $routeFile\n";
}
echo '</pre>'; 