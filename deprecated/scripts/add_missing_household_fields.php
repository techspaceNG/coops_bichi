<?php
declare(strict_types=1);

/**
 * Add missing fields to household_applications table
 * 
 * This script adds the item_category field to the household_applications table
 * which is required for the household application display functionality to work properly
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
    
    // Check if household_applications table exists
    $tableExists = $db->query("SHOW TABLES LIKE 'household_applications'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "Error: household_applications table does not exist. Please create the table first.\n";
        exit(1);
    }
    
    // Check if fields already exist in household_applications table
    $stmt = $db->query("DESCRIBE household_applications");
    $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Add item_category field if it doesn't exist
    if (!in_array('item_category', $fields)) {
        $db->exec("ALTER TABLE household_applications ADD COLUMN item_category VARCHAR(50) DEFAULT 'General' AFTER item_name");
        echo "✓ Added item_category field to household_applications table\n";
        
        // Update existing records to set a default value
        $db->exec("UPDATE household_applications SET item_category = 'General' WHERE item_category IS NULL");
        echo "✓ Updated existing records with default item_category\n";
    } else {
        echo "- item_category field already exists in household_applications table\n";
    }
    
    echo "\nDatabase update completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}