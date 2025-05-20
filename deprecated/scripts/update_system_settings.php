<?php
// Set error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "Connected to database successfully.\n";
    
    // First, check if the description column exists in system_settings table
    $stmt = $pdo->prepare("SHOW COLUMNS FROM system_settings LIKE 'description'");
    $stmt->execute();
    $hasDescriptionColumn = (bool)$stmt->fetch();
    
    if (!$hasDescriptionColumn) {
        echo "Adding description column to system_settings table...\n";
        $pdo->exec("ALTER TABLE system_settings ADD COLUMN description TEXT NULL");
        echo "Added description column successfully.\n";
    }
    
    // Now check if the entry exists
    $check = $pdo->prepare("SELECT COUNT(*) as count FROM system_settings WHERE setting_key = ?");
    $check->execute(['db_version']);
    $result = $check->fetch();
    
    if ($result['count'] > 0) {
        // Update existing entry
        $update = $pdo->prepare("UPDATE system_settings SET value = ?, updated_at = NOW() WHERE setting_key = ?");
        $updated = $update->execute(['1.2', 'db_version']);
        
        if ($updated) {
            echo "Updated system_settings table with db_version = 1.2\n";
        } else {
            echo "Failed to update system_settings table.\n";
        }
    } else {
        // Check if the updated_at column exists
        $stmt = $pdo->prepare("SHOW COLUMNS FROM system_settings LIKE 'updated_at'");
        $stmt->execute();
        $hasUpdatedAtColumn = (bool)$stmt->fetch();
        
        if ($hasUpdatedAtColumn) {
            // Insert with updated_at column
            $insert = $pdo->prepare("INSERT INTO system_settings (setting_key, value, description, updated_at) VALUES (?, ?, ?, NOW())");
            $inserted = $insert->execute(['db_version', '1.2', 'Database schema version']);
        } else {
            // Insert without updated_at column
            $insert = $pdo->prepare("INSERT INTO system_settings (setting_key, value, description) VALUES (?, ?, ?)");
            $inserted = $insert->execute(['db_version', '1.2', 'Database schema version']);
        }
        
        if ($inserted) {
            echo "Inserted new record in system_settings table with db_version = 1.2\n";
        } else {
            echo "Failed to insert into system_settings table.\n";
        }
    }
    
    // Also add the missing columns to household tables
    echo "\nAdding missing columns to household_purchases table...\n";
    
    // For MySQL versions that don't support IF NOT EXISTS in ADD COLUMN
    try {
        // First, check if the column exists
        $stmt = $pdo->prepare("SHOW COLUMNS FROM household_purchases LIKE 'reference_number'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `household_purchases` ADD COLUMN `reference_number` VARCHAR(50) NULL AFTER `id`");
            echo "Added reference_number column.\n";
        }
        
        $stmt = $pdo->prepare("SHOW COLUMNS FROM household_purchases LIKE 'payment_schedule'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `household_purchases` ADD COLUMN `payment_schedule` TEXT NULL AFTER `balance`");
            echo "Added payment_schedule column.\n";
        }
        
        $stmt = $pdo->prepare("SHOW COLUMNS FROM household_purchases LIKE 'completed_at'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `household_purchases` ADD COLUMN `completed_at` DATETIME NULL AFTER `end_date`");
            echo "Added completed_at column.\n";
        }
        
        $stmt = $pdo->prepare("SHOW COLUMNS FROM household_purchases LIKE 'term'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `household_purchases` ADD COLUMN `term` INT NULL DEFAULT 3 AFTER `repayment_period`");
            echo "Added term column.\n";
        }
    } catch (PDOException $e) {
        echo "Error adding columns to household_purchases: " . $e->getMessage() . "\n";
    }
    
    echo "Finished altering household_purchases table.\n";
    
    echo "\nAdding missing columns to household_repayments table...\n";
    
    try {
        // Check if notes column exists
        $stmt = $pdo->prepare("SHOW COLUMNS FROM household_repayments LIKE 'notes'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `household_repayments` ADD COLUMN `notes` TEXT NULL AFTER `processed_by`");
            echo "Added notes column.\n";
        }
        
        // Check if receipt_number column exists
        $stmt = $pdo->prepare("SHOW COLUMNS FROM household_repayments LIKE 'receipt_number'");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `household_repayments` ADD COLUMN `receipt_number` VARCHAR(50) NULL AFTER `notes`");
            echo "Added receipt_number column.\n";
        }
    } catch (PDOException $e) {
        echo "Error adding columns to household_repayments: " . $e->getMessage() . "\n";
    }
    
    echo "Finished altering household_repayments table.\n";
    
    // Generate reference numbers for any purchases that don't have them
    echo "\nGenerating reference numbers for household purchases...\n";
    
    $updateReferenceNumbers = $pdo->prepare("
        UPDATE `household_purchases` 
        SET `reference_number` = CONCAT('HP', LPAD(id, 6, '0')) 
        WHERE `reference_number` IS NULL OR `reference_number` = ''
    ");
    
    $updateReferenceNumbers->execute();
    echo "Updated reference numbers successfully.\n";
    
    // Drop and recreate the trigger
    echo "\nCreating trigger for auto-generating reference numbers...\n";
    
    try {
        $pdo->exec("DROP TRIGGER IF EXISTS `before_household_insert`");
        
        $createTrigger = "
            CREATE TRIGGER `before_household_insert` BEFORE INSERT ON `household_purchases` 
            FOR EACH ROW 
            BEGIN
                IF NEW.reference_number IS NULL OR NEW.reference_number = '' THEN
                    SET NEW.reference_number = CONCAT('HP', LPAD((SELECT IFNULL(MAX(id), 0) + 1 FROM household_purchases), 6, '0'));
                END IF;
            END
        ";
        
        $pdo->exec($createTrigger);
        echo "Created trigger successfully.\n";
    } catch (PDOException $e) {
        echo "Error creating trigger: " . $e->getMessage() . "\n";
    }
    
    echo "\nAll database updates completed successfully!\n";
    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
} 