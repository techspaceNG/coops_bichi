<?php

declare(strict_types=1);

try {
    echo "Starting household balance update script...\n";
    
    // Database connection settings
    $host = 'localhost';
    $dbname = 'coops_bichi';
    $username = 'root';
    $password = '';
    
    // Connect to database
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->beginTransaction();

    // 1. Update household_purchases table to include 5% admin charge in balance
    $updatePurchasesQuery = "
        UPDATE household_purchases 
        SET 
            total_repayment = amount * 1.05,
            balance = (
                CASE 
                    WHEN status = 'completed' THEN 0
                    ELSE amount * 1.05 - COALESCE(
                        (SELECT SUM(amount) 
                         FROM household_repayments 
                         WHERE purchase_id = household_purchases.id), 
                        0
                    )
                END
            )
        WHERE status IN ('approved', 'pending')";

    $stmt = $db->prepare($updatePurchasesQuery);
    $stmt->execute();
    
    $updatedPurchases = $stmt->rowCount();
    echo "Updated {$updatedPurchases} household purchases with admin charges.\n";

    // 2. Update members' household balances
    $updateMembersQuery = "
        UPDATE members m
        SET household_balance = (
            SELECT COALESCE(SUM(balance), 0)
            FROM household_purchases hp
            WHERE hp.member_id = m.id
            AND hp.status IN ('approved', 'pending')
        )";

    $stmt = $db->prepare($updateMembersQuery);
    $stmt->execute();
    
    $updatedMembers = $stmt->rowCount();
    echo "Updated {$updatedMembers} member household balances.\n";

    // Commit the transaction
    $db->commit();
    echo "Database update completed successfully!\n";

} catch (PDOException $e) {
    // Roll back the transaction on error
    if (isset($db)) {
        $db->rollBack();
    }
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    // Roll back the transaction on error
    if (isset($db)) {
        $db->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 