<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'coops_bichi';

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL to describe the members table
    $sql = "DESCRIBE members";
    
    // Execute query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Get all columns
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Members table structure:\n\n";
    
    // Display the structure
    foreach ($columns as $column) {
        echo "Field: " . $column['Field'] . "\n";
        echo "Type: " . $column['Type'] . "\n";
        echo "Null: " . $column['Null'] . "\n";
        echo "Default: " . ($column['Default'] ?? "NULL") . "\n";
        echo "---------------------\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn = null;
?> 