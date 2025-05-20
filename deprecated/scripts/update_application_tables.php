<?php
/**
 * Database Update Script
 * Adds loan duration and bank account fields to loan_applications and household_applications tables
 */

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<html><body><pre>";
echo "Database Update Script - Adding Bank Account fields\n";
echo "==================================================\n\n";

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n\n";
    
    // Check if loan_applications table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'loan_applications'");
    $loanTableExists = $stmt->rowCount() > 0;
    
    if ($loanTableExists) {
        echo "Updating loan_applications table...\n";
        
        // Check if fields already exist in loan_applications table
        $stmt = $pdo->query("DESCRIBE loan_applications");
        $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Add loan_duration field if it doesn't exist
        if (!in_array('loan_duration', $fields)) {
            $pdo->exec("ALTER TABLE loan_applications ADD COLUMN loan_duration INT UNSIGNED DEFAULT 12 AFTER ip_figure");
            echo "✓ Added loan_duration field to loan_applications table\n";
        } else {
            echo "- loan_duration field already exists in loan_applications table\n";
        }
        
        // Add bank_name field if it doesn't exist
        if (!in_array('bank_name', $fields)) {
            $pdo->exec("ALTER TABLE loan_applications ADD COLUMN bank_name VARCHAR(100) AFTER additional_info");
            echo "✓ Added bank_name field to loan_applications table\n";
        } else {
            echo "- bank_name field already exists in loan_applications table\n";
        }
        
        // Add account_number field if it doesn't exist
        if (!in_array('account_number', $fields)) {
            $pdo->exec("ALTER TABLE loan_applications ADD COLUMN account_number VARCHAR(20) AFTER bank_name");
            echo "✓ Added account_number field to loan_applications table\n";
        } else {
            echo "- account_number field already exists in loan_applications table\n";
        }
        
        // Add account_name field if it doesn't exist
        if (!in_array('account_name', $fields)) {
            $pdo->exec("ALTER TABLE loan_applications ADD COLUMN account_name VARCHAR(100) AFTER account_number");
            echo "✓ Added account_name field to loan_applications table\n";
        } else {
            echo "- account_name field already exists in loan_applications table\n";
        }
        
        // Add account_type field if it doesn't exist
        if (!in_array('account_type', $fields)) {
            $pdo->exec("ALTER TABLE loan_applications ADD COLUMN account_type ENUM('Savings', 'Current') AFTER account_name");
            echo "✓ Added account_type field to loan_applications table\n";
        } else {
            echo "- account_type field already exists in loan_applications table\n";
        }
    } else {
        echo "Error: loan_applications table does not exist!\n";
    }
    
    echo "\n";
    
    // Check if household_applications table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'household_applications'");
    $householdTableExists = $stmt->rowCount() > 0;
    
    if ($householdTableExists) {
        echo "Updating household_applications table...\n";
        
        // Check if fields already exist in household_applications table
        $stmt = $pdo->query("DESCRIBE household_applications");
        $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Add purchase_duration field if it doesn't exist
        if (!in_array('purchase_duration', $fields)) {
            $pdo->exec("ALTER TABLE household_applications ADD COLUMN purchase_duration INT UNSIGNED DEFAULT 12 AFTER ip_figure");
            echo "✓ Added purchase_duration field to household_applications table\n";
        } else {
            echo "- purchase_duration field already exists in household_applications table\n";
        }
        
        // Add bank_name field if it doesn't exist
        if (!in_array('bank_name', $fields)) {
            $pdo->exec("ALTER TABLE household_applications ADD COLUMN bank_name VARCHAR(100) AFTER vendor_details");
            echo "✓ Added bank_name field to household_applications table\n";
        } else {
            echo "- bank_name field already exists in household_applications table\n";
        }
        
        // Add account_number field if it doesn't exist
        if (!in_array('account_number', $fields)) {
            $pdo->exec("ALTER TABLE household_applications ADD COLUMN account_number VARCHAR(20) AFTER bank_name");
            echo "✓ Added account_number field to household_applications table\n";
        } else {
            echo "- account_number field already exists in household_applications table\n";
        }
        
        // Add account_name field if it doesn't exist
        if (!in_array('account_name', $fields)) {
            $pdo->exec("ALTER TABLE household_applications ADD COLUMN account_name VARCHAR(100) AFTER account_number");
            echo "✓ Added account_name field to household_applications table\n";
        } else {
            echo "- account_name field already exists in household_applications table\n";
        }
        
        // Add account_type field if it doesn't exist
        if (!in_array('account_type', $fields)) {
            $pdo->exec("ALTER TABLE household_applications ADD COLUMN account_type ENUM('Savings', 'Current') AFTER account_name");
            echo "✓ Added account_type field to household_applications table\n";
        } else {
            echo "- account_type field already exists in household_applications table\n";
        }
    } else {
        echo "Error: household_applications table does not exist!\n";
    }
    
    echo "\nScript completed successfully.\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<a href='/Coops_Bichi/public/' class='button'>Return to Home</a>";
echo "</body></html>";
?> 