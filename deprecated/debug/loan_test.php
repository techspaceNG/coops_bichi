<?php
// Database connection details
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>CSV File Processing Check</h1>";

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Check if the after_loan_repayment_insert trigger exists
    $stmt = $pdo->query("SHOW TRIGGERS LIKE 'loan_repayments'");
    $triggers = $stmt->fetchAll();
    $triggerFound = false;
    
    echo "<h2>Trigger Check</h2>";
    foreach ($triggers as $trigger) {
        if ($trigger['Trigger'] === 'after_loan_repayment_insert') {
            echo "<p style='color:green'>✓ The 'after_loan_repayment_insert' trigger exists!</p>";
            $triggerFound = true;
            break;
        }
    }
    
    if (!$triggerFound) {
        echo "<p style='color:red'>✗ The 'after_loan_repayment_insert' trigger is MISSING!</p>";
        echo "<p>This is why your loan deductions aren't working properly.</p>";
        
        // Create the trigger
        echo "<p>Attempting to create the trigger...</p>";
        
        $pdo->exec("DROP TRIGGER IF EXISTS `after_loan_repayment_insert`");
        
        $triggerSQL = "
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
        
        $pdo->exec($triggerSQL);
        echo "<p style='color:green'>✓ Trigger created successfully!</p>";
    }
    
    // Check the CSV file format
    echo "<h2>CSV File Format Guide</h2>";
    echo "<p>Your CSV file should have the following format:</p>";
    echo "<pre>
Loan ID or COOPS Number,Deduction Amount,Payment Date (YYYY-MM-DD),Notes (Optional)
1,5000,2023-11-01,Monthly payment
COOPS123,2500,2023-11-01,
</pre>";
    
    echo "<p>Common issues:</p>";
    echo "<ul>";
    echo "<li>Make sure your header row exactly matches the format above</li>";
    echo "<li>Ensure dates are in YYYY-MM-DD format (e.g., 2023-11-01)</li>";
    echo "<li>Check that loan IDs or COOPS numbers exist in your database</li>";
    echo "<li>Verify amounts are not larger than remaining loan balances</li>";
    echo "</ul>";
    
    echo "<h2>Restart Instructions</h2>";
    echo "<p>After fixing your CSV file, try again by:</p>";
    echo "<ol>";
    echo "<li>Go to <a href='/Coops_Bichi/public/superadmin/add-loan-deduction?tab=bulk'>Bulk Loan Deduction</a> page</li>";
    echo "<li>Upload your corrected CSV file</li>";
    echo "<li>If you still get errors, check which specific loans or COOPS numbers are failing</li>";
    echo "</ol>";
    
    echo "<h2>Manual Test Option</h2>";
    echo "<p>If you'd like to test a single loan deduction to confirm the system is working:</p>";
    echo "<form method='post' action=''>";
    echo "<div style='margin-bottom: 10px;'>";
    echo "<label for='loan_id'>Enter Loan ID: </label>";
    echo "<input type='number' name='loan_id' id='loan_id' required>";
    echo "</div>";
    echo "<div style='margin-bottom: 10px;'>";
    echo "<label for='amount'>Amount: </label>";
    echo "<input type='number' name='amount' id='amount' step='0.01' min='1' value='1.00' required>";
    echo " (small amount for testing)";
    echo "</div>";
    echo "<button type='submit' name='test_deduction'>Test Deduction</button>";
    echo "</form>";
    
    // Handle test deduction
    if (isset($_POST['test_deduction'])) {
        $loanId = (int)$_POST['loan_id'];
        $amount = (float)$_POST['amount'];
        
        // Get loan details before
        $stmtBefore = $pdo->prepare("SELECT id, balance, status FROM loans WHERE id = ?");
        $stmtBefore->execute([$loanId]);
        $loanBefore = $stmtBefore->fetch();
        
        if (!$loanBefore) {
            echo "<p style='color:red'>✗ Loan ID {$loanId} not found!</p>";
        } else {
            echo "<h3>Test Results:</h3>";
            echo "<p>Loan ID: {$loanBefore['id']}, Initial Balance: {$loanBefore['balance']}, Status: {$loanBefore['status']}</p>";
            
            // Start transaction
            $pdo->beginTransaction();
            
            try {
                // Insert test deduction
                $stmt = $pdo->prepare("
                    INSERT INTO loan_repayments (loan_id, amount, payment_date, processed_by, created_at)
                    VALUES (?, ?, ?, 1, NOW())
                ");
                $stmt->execute([$loanId, $amount, date('Y-m-d')]);
                
                // Get loan details after
                $stmtAfter = $pdo->prepare("SELECT id, balance, status FROM loans WHERE id = ?");
                $stmtAfter->execute([$loanId]);
                $loanAfter = $stmtAfter->fetch();
                
                $expectedBalance = $loanBefore['balance'] - $amount;
                
                echo "<p>Expected New Balance: {$expectedBalance}</p>";
                echo "<p>Actual New Balance: {$loanAfter['balance']}, New Status: {$loanAfter['status']}</p>";
                
                if (abs($expectedBalance - $loanAfter['balance']) < 0.01) {
                    echo "<p style='color:green'>✓ TRIGGER IS WORKING! The balance was updated correctly.</p>";
                } else {
                    echo "<p style='color:red'>✗ TRIGGER IS NOT WORKING! The balance was not updated correctly.</p>";
                }
                
                // Rollback the test transaction
                $pdo->rollBack();
                echo "<p>Test transaction has been rolled back - no actual changes were made.</p>";
                
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<p style='color:red'>Error during test: " . $e->getMessage() . "</p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Database Error: " . $e->getMessage() . "</p>";
}
?> 