<?php
declare(strict_types=1);

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
    
    // Read SQL file
    $sqlFile = file_get_contents(__DIR__ . '/database/update_application_fields.sql');
    
    if ($sqlFile === false) {
        throw new Exception('Could not read SQL file');
    }
    
    // Split SQL file into individual queries
    $queries = explode(';', $sqlFile);
    
    // Execute each query
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $pdo->exec($query);
            echo "Executed query: " . substr($query, 0, 80) . "...\n";
        }
    }
    
    echo "Database updated successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 