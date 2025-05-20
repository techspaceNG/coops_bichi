<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define constants needed by the application
define('BASE_DIR', dirname(__DIR__));
define('APP_ROOT', BASE_DIR . '/app');

// Include autoloader manually for testing
require_once 'index.php';

echo "<h1>Database Test Page</h1>";

try {
    // Test App\Helpers\Database
    echo "<h2>Testing App\\Helpers\\Database</h2>";
    $helperDb = \App\Helpers\Database::getConnection();
    echo "<p style='color:green'>✓ Successfully connected to database using App\\Helpers\\Database</p>";
    
    // Test DB query
    $stmt = $helperDb->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables in database:</p><ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Test App\Config\Database if it exists
    echo "<h2>Testing App\\Config\\Database</h2>";
    if (class_exists('\\App\\Config\\Database')) {
        try {
            $configDb = \App\Config\Database::getConnection();
            echo "<p style='color:green'>✓ Successfully connected to database using App\\Config\\Database</p>";
        } catch (Exception $e) {
            echo "<p style='color:red'>✗ Failed to connect using App\\Config\\Database: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:red'>✗ Class App\\Config\\Database does not exist</p>";
    }
    
    // Test Announcement model
    echo "<h2>Testing Announcement Model</h2>";
    try {
        $announcements = \App\Models\Announcement::getPublished(3);
        echo "<p style='color:green'>✓ Successfully retrieved " . count($announcements) . " announcements</p>";
        
        if (count($announcements) > 0) {
            echo "<ul>";
            foreach ($announcements as $announcement) {
                echo "<li>" . htmlspecialchars($announcement->title) . "</li>";
            }
            echo "</ul>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red'>✗ Failed to retrieve announcements: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Database test failed: " . $e->getMessage() . "</p>";
} 