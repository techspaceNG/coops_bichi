<?php
declare(strict_types=1);

/**
 * Add missing fields to loan_applications table
 * 
 * This script adds the purpose and additional_info fields to the loan_applications table
 * which are required for the loan application functionality to work properly
 */

// Define base directory
define('BASE_DIR', __DIR__);

// Include necessary files
require_once BASE_DIR . '/app/config/constants.php';
require_once BASE_DIR . '/app/core/Database.php';

use App\Core\Database;

// Connect to database
try {
    $db = Database::getConnection();
    echo "Connected to database successfully.\n";
    
    // Check if loan_applications table exists
    $tableExists = $db->query("SHOW TABLES LIKE 'loan_applications'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "Error: loan_applications table does not exist. Please create the table first.\n";
        exit(1);
    }
    
    // Check if fields already exist in loan_applications table
    $stmt = $db->query("DESCRIBE loan_applications");
    $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Add purpose field if it doesn't exist
    if (!in_array('purpose', $fields)) {
        $db->exec("ALTER TABLE loan_applications ADD COLUMN purpose VARCHAR(100) AFTER loan_duration");
        echo "✓ Added purpose field to loan_applications table\n";
    } else {
        echo "- purpose field already exists in loan_applications table\n";
    }
    
    // Add additional_info field if it doesn't exist
    if (!in_array('additional_info', $fields)) {
        $db->exec("ALTER TABLE loan_applications ADD COLUMN additional_info TEXT AFTER purpose");
        echo "✓ Added additional_info field to loan_applications table\n";
    } else {
        echo "- additional_info field already exists in loan_applications table\n";
    }
    
    echo "\nDatabase update completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
} 