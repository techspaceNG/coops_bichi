<?php
declare(strict_types=1);

// Include database configuration
require_once __DIR__ . '/app/config/Database.php';

echo "Starting household purchase balance update...\n";

try {
    // Check if the connection is established
    $conn = \App\Config\Database::getConnection();
    
    // First, identify records where balance equals amount (without admin charge)
    $query = "SELECT id, amount, balance FROM household_purchases 
              WHERE ABS(balance - amount) < 0.01 AND balance > 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    echo "Found " . count($records) . " records where balance doesn't include admin charge.\n";
    
    // Update balances to include 5% admin charge
    $updateCount = 0;
    if (!empty($records)) {
        $conn->beginTransaction();
        
        foreach ($records as $record) {
            $newBalance = (float)$record['amount'] * 1.05;
            
            $updateQuery = "UPDATE household_purchases 
                           SET balance = :balance,
                               total_repayment = :total_repayment
                           WHERE id = :id";
            
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindValue(':balance', $newBalance, \PDO::PARAM_STR);
            $updateStmt->bindValue(':total_repayment', $newBalance, \PDO::PARAM_STR);
            $updateStmt->bindValue(':id', $record['id'], \PDO::PARAM_INT);
            
            if ($updateStmt->execute()) {
                $updateCount++;
                echo "Updated ID {$record['id']}: Amount = {$record['amount']}, New Balance = {$newBalance}\n";
            }
        }
        
        $conn->commit();
    }
    
    echo "Successfully updated {$updateCount} records.\n";
    
    // Also check if we need to add an admin_charge_rate column if it doesn't exist
    $columnsQuery = "SHOW COLUMNS FROM household_purchases LIKE 'admin_charge_rate'";
    $columnsStmt = $conn->prepare($columnsQuery);
    $columnsStmt->execute();
    $columnExists = $columnsStmt->rowCount() > 0;
    
    if (!$columnExists) {
        echo "Adding admin_charge_rate column to household_purchases table...\n";
        
        $alterQuery = "ALTER TABLE household_purchases 
                       ADD COLUMN admin_charge_rate DECIMAL(5,2) DEFAULT 5.00 AFTER balance";
        
        $alterStmt = $conn->prepare($alterQuery);
        if ($alterStmt->execute()) {
            echo "Successfully added admin_charge_rate column.\n";
        } else {
            echo "Failed to add admin_charge_rate column.\n";
        }
    } else {
        echo "admin_charge_rate column already exists.\n";
    }
    
    // Make sure we have a trigger to automatically update the balance when amount is updated
    $triggerQuery = "
    CREATE TRIGGER IF NOT EXISTS update_household_balance_after_update
    BEFORE UPDATE ON household_purchases
    FOR EACH ROW
    BEGIN
        IF NEW.amount != OLD.amount THEN
            SET NEW.total_repayment = NEW.amount * (1 + (IFNULL(NEW.admin_charge_rate, 5) / 100));
            IF NEW.balance = OLD.amount THEN
                SET NEW.balance = NEW.total_repayment;
            END IF;
        END IF;
    END;
    ";
    
    echo "Creating/updating database trigger for automatic balance calculations...\n";
    $conn->exec($triggerQuery);
    echo "Trigger setup complete.\n";
    
    echo "All database updates completed successfully.\n";
    
} catch (\PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
        echo "Transaction rolled back.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 