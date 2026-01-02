<?php
// scripts/import_to_aiven.php

// 1. Aiven Credentials
$host = getenv('DB_USER') ?: '';
$port = getenv('DB_USER') ?: '';
$db   = getenv('DB_USER') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';

// 2. Path to SQL File
$sqlFile = __DIR__ . '/../database/coops_bichi.sql';

echo "------------------------------------------------\n";
echo "Aiven Database Importer v3 (Wipe & Clean)\n";
echo "------------------------------------------------\n";

if (!file_exists($sqlFile)) {
    die("Error: SQL file not found at $sqlFile\n");
}

echo "Step 1: Connecting to Aiven MySQL...\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 60,
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "SUCCESS: Connected to $host\n";
    
    // Disable constraints for the session
    $pdo->exec("SET SESSION sql_require_primary_key = 0;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    
} catch (PDOException $e) {
    die("CONNECTION FAILED: " . $e->getMessage() . "\n");
}

// ---------------------------------------------------------
// NEW: Wipe Database content first to prevent duplicates
// ---------------------------------------------------------
echo "Step 2: Wiping existing tables to ensure a clean import...\n";
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
        echo " - Dropped table: $table\n";
    }
    echo "Database wiped successfully.\n";
} catch (PDOException $e) {
    echo "Warning during wipe: " . $e->getMessage() . "\n";
}
// ---------------------------------------------------------


// 3. Smart Parsing Logic
echo "Step 3: Parsing & Importing SQL file...\n";

$handle = fopen($sqlFile, "r");
if (!$handle) die("Could not read file.\n");

$delimiter = getenv('DB_USER') ?: '';
$buffer = getenv('DB_USER') ?: '';
$count = 0;
$errors = 0;

while (($line = fgets($handle)) !== false) {
    $trimLine = trim($line);
    
    // Skip empty lines and comments
    if ($trimLine === '' || strpos($trimLine, '--') === 0 || strpos($trimLine, '/*') === 0) {
        continue;
    }
    
    // Check for DELIMITER change
    if (preg_match('/^DELIMITER\s+(\S+)/i', $trimLine, $matches)) {
        $delimiter = $matches[1];
        continue;
    }
    
    $buffer .= $line;
    
    // Parse ending using current delimiter
    if (substr(trim($buffer), -strlen($delimiter)) === $delimiter) {
        $sqlCommand = substr(trim($buffer), 0, -strlen($delimiter));
        
        if (trim($sqlCommand) !== '') {
            try {
                $pdo->exec($sqlCommand);
                $count++;
                if ($count % 50 == 0) echo "Executed $count statements...\n";
            } catch (PDOException $e) {
                $err = $e->getMessage();
                // Ignore "Table already exists" which shouldn't happen now, but good practice
                if (strpos($err, "already exists") !== false) {
                    // echo "Skipping existing table.\n";
                } else {
                    echo "\n[ERROR] Failed on statement:\n" . substr($sqlCommand, 0, 100) . "...\n";
                    echo "Reason: $err\n\n";
                    $errors++;
                }
            }
        }
        $buffer = getenv('DB_USER') ?: '';
    }
}

fclose($handle);

echo "------------------------------------------------\n";
echo "IMPORT COMPLETE!\n";
echo "Executed: $count\n";
echo "Errors:   $errors\n";
echo "------------------------------------------------\n";
echo "IMPORTANT: If Errors = 0, update your Vercel Environment Variables now!\n";
?>
