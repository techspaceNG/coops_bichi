<?php
require_once 'app/bootstrap.php';

use App\Core\Database;

try {
    // Connect to the database
    $db = Database::getConnection();
    
    // Get admin user from database
    $query = "SELECT * FROM admin_users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->execute(['username' => 'admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Show admin details (excluding sensitive data)
    echo "Admin User Details:\n";
    if ($admin) {
        echo "ID: " . $admin['id'] . "\n";
        echo "Username: " . $admin['username'] . "\n";
        echo "Name: " . $admin['name'] . "\n";
        echo "Password Hash (first 20 chars): " . substr($admin['password'], 0, 20) . "...\n";
        echo "Is Locked: " . ($admin['is_locked'] ? 'Yes' : 'No') . "\n";
        echo "Failed Attempts: " . $admin['failed_attempts'] . "\n";
        
        // Test the password 'admin'
        $testPassword = 'admin';
        $isValidPassword = password_verify($testPassword, $admin['password']);
        echo "\nTesting password 'admin': " . ($isValidPassword ? 'Valid' : 'Invalid') . "\n";
    } else {
        echo "No admin user found with username 'admin'\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 