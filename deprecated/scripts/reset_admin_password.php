<?php
// Simple script to reset the superadmin password

// Database connection parameters
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Check if superadmin user exists
    $checkStmt = $pdo->prepare("SELECT id, username FROM admin_users WHERE username = ?");
    $checkStmt->execute(['superadmin']);
    $admin = $checkStmt->fetch();
    
    if ($admin) {
        // Create new password hash
        $newPassword = 'superadmin123';
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        // Update superadmin password
        $updateStmt = $pdo->prepare("UPDATE admin_users SET 
            password = ?, 
            failed_attempts = 0, 
            is_locked = 0
            WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $admin['id']]);
        
        echo "Password for superadmin user '{$admin['username']}' has been reset to 'superadmin123'.\n";
        echo "Failed login attempts have been reset.\n";
        echo "Account has been unlocked.\n";
    } else {
        // Superadmin doesn't exist, create it
        $name = 'Super Administrator';
        $email = 'superadmin@fcetbichi.edu.ng';
        $role = 'superadmin';
        $hashedPassword = password_hash('superadmin123', PASSWORD_BCRYPT);
        
        $createStmt = $pdo->prepare("INSERT INTO admin_users (username, name, email, password, role, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())");
        $createStmt->execute(['superadmin', $name, $email, $hashedPassword, $role]);
        
        echo "New superadmin user 'superadmin' has been created with password 'superadmin123'.\n";
    }
    
    echo "\nPlease use these credentials to log in:\n";
    echo "Username: superadmin\n";
    echo "Password: superadmin123\n";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} 