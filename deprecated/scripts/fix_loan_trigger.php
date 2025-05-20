<?php
// Database connection details
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>Loan Balance Trigger Fix</h1>";

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Check the specific loan that's having issues
    echo "<h2>Checking Loan ID 2</h2>";
    $loan = $pdo->query("SELECT * FROM loans WHERE id = 2")->fetch();
    
    if (!$loan) {
        echo "<p style='color:red'>Loan ID 2 doesn't exist!</p>";
    } else {
        echo "<p>Loan Details:</p>";
        echo "<ul>";
        echo "<li>Loan Amount: " . number_format($loan['loan_amount'], 2) . "</li>";
        echo "<li>IP Figure: " . number_format($loan['ip_figure'], 2) . "</li>";
        echo "<li>Current Balance: " . number_format($loan['balance'], 2) . "</li>";
        echo "<li>Status: " . $loan['status'] . "</li>";
        echo "</ul>";
        
        // Check repayments
        $repayments = $pdo->query("SELECT SUM(amount) as total FROM loan_repayments WHERE loan_id = 2")->fetch();
        $totalPaid = $repayments['total'] ?? 0;
        
        echo "<p>Total repayments made: " . number_format($totalPaid, 2) . "</p>";
        
        // Calculate expected balance
        $expectedBalance = $loan['loan_amount'] - $totalPaid;
        echo "<p>Expected balance (loan_amount - total_paid): " . number_format($expectedBalance, 2) . "</p>";
        
        // Check if there's a difference
        if (abs($expectedBalance - $loan['balance']) > 0.01) {
            echo "<p style='color:red'>Balance discrepancy detected!</p>";
            echo "<p>Difference: " . number_format($loan['balance'] - $expectedBalance, 2) . "</p>";
        }
    }
    
    // Check and fix the trigger
    echo "<h2>Checking and Fixing Trigger</h2>";
    
    // Drop the existing trigger
    $pdo->exec("DROP TRIGGER IF EXISTS after_loan_repayment_insert");
    echo "<p>Removed existing trigger.</p>";
    
    // Create the updated trigger with fixed balance calculation
    $newTrigger = "
    CREATE TRIGGER after_loan_repayment_insert 
    AFTER INSERT ON loan_repayments
    FOR EACH ROW
    BEGIN
        -- Update the loan balance (using only loan_amount, not including IP figure)
        UPDATE loans 
        SET balance = (loan_amount - (SELECT COALESCE(SUM(amount), 0) FROM loan_repayments WHERE loan_id = NEW.loan_id)),
            updated_at = NOW()
        WHERE id = NEW.loan_id;
        
        -- If this makes the balance zero or negative, mark as completed
        UPDATE loans 
        SET status = 'completed',
            updated_at = NOW()
        WHERE id = NEW.loan_id AND balance <= 0;
    END
    ";
    
    $pdo->exec($newTrigger);
    echo "<p style='color:green'>Created new trigger with fixed balance calculation.</p>";
    
    // Fix the current balance for loan ID 2
    if ($loan) {
        $updatedBalance = $loan['loan_amount'] - $totalPaid;
        $pdo->exec("UPDATE loans SET balance = {$updatedBalance} WHERE id = 2");
        echo "<p style='color:green'>Updated balance for loan ID 2 to: " . number_format($updatedBalance, 2) . "</p>";
        
        // Verify the update
        $updatedLoan = $pdo->query("SELECT balance FROM loans WHERE id = 2")->fetch();
        echo "<p>New balance: " . number_format($updatedLoan['balance'], 2) . "</p>";
    }
    
    echo "<h2>Instructions for Processing CSV File</h2>";
    echo "<p>Now that the trigger is fixed, please:</p>";
    echo "<ol>";
    echo "<li>Make sure your CSV file doesn't contain invalid loan identifiers like '@coops_bichi.sql'</li>";
    echo "<li>Use the corrected_loan_deduction.csv file we created earlier</li>";
    echo "<li>Try the bulk upload again</li>";
    echo "</ol>";
    
    echo "<p>For convenience, here's a valid CSV format to use:</p>";
    echo "<pre>Loan ID or COOPS Number,Deduction Amount,Payment Date (YYYY-MM-DD)\n2,1000.00,2023-12-01</pre>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?> 