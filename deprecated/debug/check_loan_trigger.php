<?php
declare(strict_types=1);

// Display all errors for debugging
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Database connection details
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>Loan Repayment Trigger Check</h1>";

try {
    // Create PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Check if trigger exists
    $stmt = $pdo->query("SHOW TRIGGERS LIKE 'loan_repayments'");
    $triggers = $stmt->fetchAll();
    
    $triggerExists = false;
    $triggerInfo = null;
    
    foreach ($triggers as $trigger) {
        if ($trigger['Trigger'] === 'after_loan_repayment_insert') {
            $triggerExists = true;
            $triggerInfo = $trigger;
            break;
        }
    }
    
    if ($triggerExists) {
        echo "<p style='color:green'>✓ The 'after_loan_repayment_insert' trigger exists.</p>";
        echo "<pre>" . print_r($triggerInfo, true) . "</pre>";
    } else {
        echo "<p style='color:red'>✗ The 'after_loan_repayment_insert' trigger does NOT exist!</p>";
        
        // Create the trigger
        echo "<p>Attempting to create the trigger...</p>";
        
        // First drop the trigger if it exists
        $pdo->exec("DROP TRIGGER IF EXISTS `after_loan_repayment_insert`");
        
        // Then create the new trigger
        $triggerCreate = "
        CREATE TRIGGER `after_loan_repayment_insert` AFTER INSERT ON `loan_repayments` FOR EACH ROW 
        BEGIN
            -- Update the loan balance
            UPDATE loans 
            SET balance = balance - NEW.amount,
                updated_at = NOW()
            WHERE id = NEW.loan_id;
            
            -- If this makes the balance zero or negative, mark as completed
            UPDATE loans 
            SET status = 'completed',
                updated_at = NOW()
            WHERE id = NEW.loan_id AND balance <= 0;
        END
        ";
        
        $pdo->exec($triggerCreate);
        echo "<p style='color:green'>✅ Trigger 'after_loan_repayment_insert' created successfully.</p>";
    }
    
    // Test the trigger with a small temporary transaction
    echo "<h2>Testing the trigger...</h2>";
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Find a loan with balance > 1
        $stmt = $pdo->query("SELECT id, balance FROM loans WHERE balance > 1 AND status = 'approved' LIMIT 1");
        $loan = $stmt->fetch();
        
        if (!$loan) {
            echo "<p style='color:orange'>⚠️ No suitable loan found for testing.</p>";
        } else {
            echo "<p>Found loan ID: {$loan['id']} with balance: {$loan['balance']}</p>";
            
            // Record the balance before
            $balanceBefore = $loan['balance'];
            $testAmount = 1.00; // Small test amount
            
            // Create a test repayment
            $stmt = $pdo->prepare("
                INSERT INTO loan_repayments 
                (loan_id, amount, payment_date, processed_by, created_at)
                VALUES (?, ?, ?, 1, NOW())
            ");
            $stmt->execute([$loan['id'], $testAmount, date('Y-m-d')]);
            
            // Check the balance after
            $stmt = $pdo->prepare("SELECT balance FROM loans WHERE id = ?");
            $stmt->execute([$loan['id']]);
            $updatedLoan = $stmt->fetch();
            $balanceAfter = $updatedLoan['balance'];
            
            echo "<p>Balance before: {$balanceBefore}, Amount deducted: {$testAmount}, Balance after: {$balanceAfter}</p>";
            
            if (abs(($balanceBefore - $testAmount) - $balanceAfter) < 0.01) {
                echo "<p style='color:green'>✅ Trigger is working correctly! The balance was updated.</p>";
            } else {
                echo "<p style='color:red'>✗ Trigger is NOT working correctly! The balance was not updated as expected.</p>";
                echo "<p>Expected balance: " . ($balanceBefore - $testAmount) . ", Actual balance: {$balanceAfter}</p>";
            }
        }
        
        // Always rollback the test transaction
        $pdo->rollBack();
        echo "<p>Test transaction rolled back.</p>";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red'>Error during testing: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>Testing Conclusion</h2>";
    echo "<p>If the trigger test was successful, your bulk loan deductions should now work correctly.</p>";
    echo "<p>If the test failed, you may need to check your database permissions or contact your database administrator.</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?> 