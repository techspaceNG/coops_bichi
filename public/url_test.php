<?php
// Simple test file to diagnose URL handling

echo "<h1>URL Path Diagnosis</h1>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "dirname(SCRIPT_NAME): " . dirname($_SERVER['SCRIPT_NAME']) . "\n";

// Extract the base URL
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = '';

// If we're in a subdirectory, extract it properly
if ($scriptDir !== '/' && $scriptDir !== '\\') {
    $baseUrl = $scriptDir;
    
    // Handle XAMPP-specific case
    if (strpos($baseUrl, '/public') !== false) {
        $baseUrl = substr($baseUrl, 0, strpos($baseUrl, '/public'));
    }
}

// Additional fallback
if (empty($baseUrl)) {
    $requestUrl = $_SERVER['REQUEST_URI'];
    $lowerRequestUrl = strtolower($requestUrl);
    if (strpos($lowerRequestUrl, '/coops_bichi/') === 0) {
        $baseUrl = '/Coops_Bichi';
    }
}

echo "Calculated Base URL: " . $baseUrl . "\n";

// Show the admin login URL that would be generated
echo "Admin Login URL: " . $baseUrl . "/admin/login\n";
echo "Admin Login Process URL: " . $baseUrl . "/admin/login/process\n";
echo "</pre>";

// Show some links to test
echo "<p><a href='" . $baseUrl . "/admin/login'>Go to Admin Login</a></p>";
echo "<p><a href='" . $baseUrl . "/home'>Go to Home Page</a></p>";
?> 