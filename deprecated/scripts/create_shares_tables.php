<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

// SQL statements to create the tables
$sharesSql = "
CREATE TABLE IF NOT EXISTS shares (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNSIGNED NOT NULL,
    share_type ENUM('ordinary', 'preferred') DEFAULT 'ordinary',
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_value DECIMAL(10,2) NOT NULL,
    purchase_date DATE NOT NULL,
    status ENUM('active', 'sold', 'forfeited') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

$transactionsSql = "
CREATE TABLE IF NOT EXISTS share_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    share_id INT UNSIGNED NOT NULL,
    transaction_type ENUM('purchase', 'sale', 'transfer') NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    transaction_date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// Additional fields for shares table
$sharesUpdateSql = "
ALTER TABLE shares 
ADD COLUMN sale_date DATE DEFAULT NULL,
ADD COLUMN sale_price DECIMAL(10,2) DEFAULT NULL;
";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Create the shares table
    $pdo->exec($sharesSql);
    echo "Shares table created successfully!\n";
    
    // Create the share_transactions table
    $pdo->exec($transactionsSql);
    echo "Share transactions table created successfully!\n";
    
    // Add additional fields
    $pdo->exec($sharesUpdateSql);
    echo "Added additional fields to shares table!\n";
    
    echo "All tables created successfully!\n";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage() . "\n";
} 