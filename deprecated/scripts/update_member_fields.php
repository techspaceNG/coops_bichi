<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>Member Table Update Script</h1>";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<p>Successfully connected to the database.</p>";
    
    // Check if the members table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'members'");
    if ($stmt->rowCount() === 0) {
        throw new Exception("The members table does not exist.");
    }
    
    echo "<p>Members table exists.</p>";
    
    // Get the current columns in the members table
    $stmt = $pdo->query("SHOW COLUMNS FROM members");
    $existingColumns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
    }
    
    echo "<p>Current columns in members table: " . implode(", ", $existingColumns) . "</p>";
    
    // Define the columns to add
    $columnsToAdd = [
        // Account information fields
        'account_number' => "VARCHAR(50) DEFAULT NULL COMMENT 'Bank account number'",
        'bank_name' => "VARCHAR(100) DEFAULT NULL COMMENT 'Bank name'",
        'account_name' => "VARCHAR(100) DEFAULT NULL COMMENT 'Bank account name'",
        'bank_branch' => "VARCHAR(100) DEFAULT NULL COMMENT 'Bank branch'",
        'bvn' => "VARCHAR(50) DEFAULT NULL COMMENT 'Bank Verification Number'",
        
        // Financial balance fields
        'savings_balance' => "DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Current savings balance'",
        'loan_balance' => "DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Current loan balance'",
        'household_balance' => "DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Current household purchases balance'",
        'shares_balance' => "DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Current shares value'"
    ];
    
    // Prepare the ALTER TABLE statements
    $alterStatements = [];
    $addedColumns = [];
    
    foreach ($columnsToAdd as $column => $definition) {
        if (!in_array($column, $existingColumns)) {
            $alterStatements[] = "ADD COLUMN `$column` $definition";
            $addedColumns[] = $column;
        } else {
            echo "<p>Column '$column' already exists in the members table.</p>";
        }
    }
    
    // Execute the ALTER TABLE statement if there are columns to add
    if (!empty($alterStatements)) {
        $sql = "ALTER TABLE members " . implode(", ", $alterStatements);
        echo "<p>Executing SQL: $sql</p>";
        
        $pdo->exec($sql);
        echo "<p>Successfully added the following columns to members table: " . implode(", ", $addedColumns) . "</p>";
    } else {
        echo "<p>No new columns need to be added to the members table.</p>";
    }
    
    // Verify the columns were added
    $stmt = $pdo->query("SHOW COLUMNS FROM members");
    $updatedColumns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $updatedColumns[] = $row['Field'];
    }
    
    echo "<p>Updated columns in members table: " . implode(", ", $updatedColumns) . "</p>";
    
    echo "<h2>Script completed successfully!</h2>";
    
} catch (PDOException $e) {
    die("<p>Database error: " . $e->getMessage() . "</p>");
} catch (Exception $e) {
    die("<p>Error: " . $e->getMessage() . "</p>");
} 