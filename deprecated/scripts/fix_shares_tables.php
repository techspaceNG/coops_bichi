<?php
/**
 * Fix Shares Tables Script
 * This script fixes the shares and share_transactions tables by dropping them and recreating
 * with the correct structure that matches what the controller expects.
 */

// Bootstrap the application to get access to database connection
require_once 'app/core/Database.php';

use App\Core\Database;

echo "Starting shares tables fix...\n";

try {
    $db = Database::getConnection();
    
    echo "Reading SQL file...\n";
    $sql = file_get_contents('app/sql/fixed_shares_schema.sql');
    
    // Split SQL statements (respecting DELIMITER statements)
    $statements = [];
    $current = '';
    $delimiter = ';';
    $inDelimiter = false;
    
    foreach (explode("\n", $sql) as $line) {
        $line = trim($line);
        
        if (empty($line) || strpos($line, '--') === 0) {
            continue; // Skip empty lines and comments
        }
        
        if (strpos($line, 'DELIMITER') === 0) {
            if ($inDelimiter) {
                $inDelimiter = false;
                $delimiter = ';';
            } else {
                $inDelimiter = true;
                $delimiter = trim(substr($line, 10)); // Get new delimiter
            }
            continue;
        }
        
        $current .= $line . " ";
        
        if (substr($line, -strlen($delimiter)) === $delimiter) {
            $statements[] = $current;
            $current = '';
        }
    }
    
    echo "Found " . count($statements) . " SQL statements to execute.\n";
    
    // Execute each statement separately, no transaction
    foreach ($statements as $i => $statement) {
        try {
            echo "Executing statement " . ($i + 1) . "... ";
            $db->exec($statement);
            echo "Done\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            // Continue with the next statement instead of exiting
        }
    }
    
    echo "Shares tables fix completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 