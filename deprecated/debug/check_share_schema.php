<?php
// Bootstrap the application to get access to database connection
require_once 'app/core/Database.php';

use App\Core\Database;

try {
    $db = Database::getConnection();
    
    // Check if shares table exists
    $result = $db->query("SHOW TABLES LIKE 'shares'");
    if ($result->rowCount() === 0) {
        echo "Shares table does not exist.\n";
        exit(1);
    }
    
    // Get table structure
    echo "Shares table structure:\n";
    $columns = $db->query("DESCRIBE shares");
    
    echo str_pad("Field", 20) . str_pad("Type", 20) . str_pad("Null", 6) . "Default\n";
    echo str_repeat("-", 60) . "\n";
    
    foreach ($columns as $column) {
        echo str_pad($column['Field'], 20) . 
             str_pad($column['Type'], 20) . 
             str_pad($column['Null'], 6) . 
             $column['Default'] . "\n";
    }
    
    // Check for generated columns
    echo "\nGenerated columns:\n";
    $generatedColumns = $db->query("
        SELECT COLUMN_NAME, GENERATION_EXPRESSION, EXTRA
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'shares'
        AND EXTRA LIKE '%GENERATED%'
    ");
    
    if ($generatedColumns->rowCount() === 0) {
        echo "No generated columns found.\n";
    } else {
        foreach ($generatedColumns as $column) {
            echo "Column: " . $column['COLUMN_NAME'] . ", Expression: " . $column['GENERATION_EXPRESSION'] . ", Extra: " . $column['EXTRA'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 