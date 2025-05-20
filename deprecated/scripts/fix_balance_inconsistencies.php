<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>Balance Consistency Fix Script</h1>";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<p>Successfully connected to the database.</p>";
    
    // Part 1: Fix inconsistencies in existing data
    echo "<h2>Fixing existing data inconsistencies...</h2>";
    
    // Step 1: Update loan balances in the members table based on loans table
    $pdo->beginTransaction();
    
    try {
        // Get all active loans and members
        $stmt = $pdo->query("
            SELECT m.id AS member_id, m.loan_balance AS current_balance, 
                   COALESCE(l.balance, 0) AS actual_balance
            FROM members m
            LEFT JOIN (
                SELECT member_id, SUM(balance) AS balance 
                FROM loans 
                WHERE status IN ('approved', 'pending')
                GROUP BY member_id
            ) l ON m.id = l.member_id
        ");
        
        $loanUpdates = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $memberId = $row['member_id'];
            $currentBalance = (float)$row['current_balance'];
            $actualBalance = (float)$row['actual_balance'];
            
            if ($currentBalance != $actualBalance) {
                $updateStmt = $pdo->prepare("
                    UPDATE members 
                    SET loan_balance = :balance,
                        updated_at = NOW()
                    WHERE id = :member_id
                ");
                
                $updateStmt->execute([
                    ':balance' => $actualBalance,
                    ':member_id' => $memberId
                ]);
                
                $loanUpdates++;
                echo "<p>Updated loan balance for member ID {$memberId} from {$currentBalance} to {$actualBalance}</p>";
            }
        }
        
        if ($loanUpdates == 0) {
            echo "<p>No loan balance inconsistencies found.</p>";
        } else {
            echo "<p>Fixed {$loanUpdates} loan balance inconsistencies.</p>";
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
    // Step 2: Update savings balances in the members table based on savings table
    $pdo->beginTransaction();
    
    try {
        // Get all savings accounts and members
        $stmt = $pdo->query("
            SELECT m.id AS member_id, m.savings_balance AS current_balance, 
                   COALESCE(s.cumulative_amount, 0) AS actual_balance
            FROM members m
            LEFT JOIN savings s ON m.id = s.member_id
        ");
        
        $savingsUpdates = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $memberId = $row['member_id'];
            $currentBalance = (float)$row['current_balance'];
            $actualBalance = (float)$row['actual_balance'];
            
            if ($currentBalance != $actualBalance) {
                $updateStmt = $pdo->prepare("
                    UPDATE members 
                    SET savings_balance = :balance,
                        updated_at = NOW()
                    WHERE id = :member_id
                ");
                
                $updateStmt->execute([
                    ':balance' => $actualBalance,
                    ':member_id' => $memberId
                ]);
                
                $savingsUpdates++;
                echo "<p>Updated savings balance for member ID {$memberId} from {$currentBalance} to {$actualBalance}</p>";
            }
        }
        
        if ($savingsUpdates == 0) {
            echo "<p>No savings balance inconsistencies found.</p>";
        } else {
            echo "<p>Fixed {$savingsUpdates} savings balance inconsistencies.</p>";
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
    // Step 3: Update household balances in the members table based on household_purchases table
    $pdo->beginTransaction();
    
    try {
        // Get all household purchases and members
        $stmt = $pdo->query("
            SELECT m.id AS member_id, m.household_balance AS current_balance, 
                   COALESCE(h.total_balance, 0) AS actual_balance
            FROM members m
            LEFT JOIN (
                SELECT member_id, SUM(balance) AS total_balance 
                FROM household_purchases 
                WHERE status IN ('approved', 'pending')
                GROUP BY member_id
            ) h ON m.id = h.member_id
        ");
        
        $householdUpdates = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $memberId = $row['member_id'];
            $currentBalance = (float)$row['current_balance'];
            $actualBalance = (float)$row['actual_balance'];
            
            if ($currentBalance != $actualBalance) {
                $updateStmt = $pdo->prepare("
                    UPDATE members 
                    SET household_balance = :balance,
                        updated_at = NOW()
                    WHERE id = :member_id
                ");
                
                $updateStmt->execute([
                    ':balance' => $actualBalance,
                    ':member_id' => $memberId
                ]);
                
                $householdUpdates++;
                echo "<p>Updated household balance for member ID {$memberId} from {$currentBalance} to {$actualBalance}</p>";
            }
        }
        
        if ($householdUpdates == 0) {
            echo "<p>No household balance inconsistencies found.</p>";
        } else {
            echo "<p>Fixed {$householdUpdates} household balance inconsistencies.</p>";
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
    // Part 2: Create triggers to maintain consistency going forward
    echo "<h2>Creating database triggers to maintain consistency...</h2>";
    
    // Drop existing triggers if they exist
    $pdo->exec("DROP TRIGGER IF EXISTS after_loan_update");
    $pdo->exec("DROP TRIGGER IF EXISTS after_loan_insert");
    $pdo->exec("DROP TRIGGER IF EXISTS after_loan_repayment_insert");
    $pdo->exec("DROP TRIGGER IF EXISTS after_savings_update");
    $pdo->exec("DROP TRIGGER IF EXISTS after_savings_transaction_insert");
    $pdo->exec("DROP TRIGGER IF EXISTS after_household_update");
    $pdo->exec("DROP TRIGGER IF EXISTS after_household_repayment_insert");
    $pdo->exec("DROP TRIGGER IF EXISTS after_share_transaction_insert");
    
    // Create trigger for loan updates
    $pdo->exec("
        CREATE TRIGGER after_loan_update 
        AFTER UPDATE ON loans
        FOR EACH ROW
        BEGIN
            -- Update the loan balance in the members table
            UPDATE members 
            SET loan_balance = (
                SELECT COALESCE(SUM(balance), 0)
                FROM loans 
                WHERE member_id = NEW.member_id 
                AND status IN ('approved', 'pending')
            ),
            updated_at = NOW()
            WHERE id = NEW.member_id;
        END
    ");
    
    // Create trigger for new loans
    $pdo->exec("
        CREATE TRIGGER after_loan_insert 
        AFTER INSERT ON loans
        FOR EACH ROW
        BEGIN
            -- Update the loan balance in the members table
            UPDATE members 
            SET loan_balance = (
                SELECT COALESCE(SUM(balance), 0)
                FROM loans 
                WHERE member_id = NEW.member_id 
                AND status IN ('approved', 'pending')
            ),
            updated_at = NOW()
            WHERE id = NEW.member_id;
        END
    ");
    
    // Create trigger for loan repayments
    $pdo->exec("
        CREATE TRIGGER after_loan_repayment_insert 
        AFTER INSERT ON loan_repayments
        FOR EACH ROW
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
    ");
    
    // Create trigger for savings updates
    $pdo->exec("
        CREATE TRIGGER after_savings_update 
        AFTER UPDATE ON savings
        FOR EACH ROW
        BEGIN
            -- Update the savings balance in the members table
            UPDATE members 
            SET savings_balance = NEW.cumulative_amount,
                updated_at = NOW()
            WHERE id = NEW.member_id;
        END
    ");
    
    // Create trigger for savings transactions
    $pdo->exec("
        CREATE TRIGGER after_savings_transaction_insert 
        AFTER INSERT ON savings_transactions
        FOR EACH ROW
        BEGIN
            DECLARE current_amount DECIMAL(12,2);
            
            -- Get current cumulative amount
            SELECT COALESCE(cumulative_amount, 0) INTO current_amount
            FROM savings WHERE member_id = NEW.member_id;
            
            -- If no savings record exists, create one
            IF current_amount IS NULL THEN
                INSERT INTO savings (member_id, monthly_deduction, cumulative_amount, last_deduction_date, created_at, updated_at)
                VALUES (NEW.member_id, 0, 0, NOW(), NOW(), NOW());
                
                SET current_amount = 0;
            END IF;
            
            -- Update the savings amount based on transaction type
            IF NEW.transaction_type = 'deposit' OR NEW.transaction_type = 'interest' THEN
                UPDATE savings 
                SET cumulative_amount = cumulative_amount + NEW.amount,
                    last_deduction_date = NEW.deduction_date,
                    updated_at = NOW()
                WHERE member_id = NEW.member_id;
            ELSEIF NEW.transaction_type = 'withdrawal' THEN
                UPDATE savings 
                SET cumulative_amount = GREATEST(0, cumulative_amount - NEW.amount),
                    updated_at = NOW()
                WHERE member_id = NEW.member_id;
            ELSEIF NEW.transaction_type = 'adjustment' THEN
                UPDATE savings 
                SET cumulative_amount = GREATEST(0, cumulative_amount + NEW.amount),
                    updated_at = NOW()
                WHERE member_id = NEW.member_id;
            END IF;
        END
    ");
    
    // Create trigger for household purchase updates
    $pdo->exec("
        CREATE TRIGGER after_household_update 
        AFTER UPDATE ON household_purchases
        FOR EACH ROW
        BEGIN
            -- Update the household balance in the members table
            UPDATE members 
            SET household_balance = (
                SELECT COALESCE(SUM(balance), 0)
                FROM household_purchases 
                WHERE member_id = NEW.member_id 
                AND status IN ('approved', 'pending')
            ),
            updated_at = NOW()
            WHERE id = NEW.member_id;
        END
    ");
    
    // Create trigger for household repayments
    $pdo->exec("
        CREATE TRIGGER after_household_repayment_insert 
        AFTER INSERT ON household_repayments
        FOR EACH ROW
        BEGIN
            -- Update the household balance
            UPDATE household_purchases 
            SET balance = balance - NEW.amount,
                updated_at = NOW()
            WHERE id = NEW.purchase_id;
            
            -- If this makes the balance zero or negative, mark as completed
            UPDATE household_purchases 
            SET status = 'completed',
                updated_at = NOW()
            WHERE id = NEW.purchase_id AND balance <= 0;
        END
    ");
    
    // Create trigger for shares updates to update shares_balance in members table
    $pdo->exec("
        CREATE TRIGGER after_share_transaction_insert 
        AFTER INSERT ON share_transactions
        FOR EACH ROW
        BEGIN
            DECLARE member_id INT;
            
            -- Get the member ID for this share
            SELECT s.member_id INTO member_id
            FROM shares s WHERE s.id = NEW.share_id;
            
            -- Update the member's shares balance
            UPDATE members 
            SET shares_balance = (
                SELECT COALESCE(SUM(s.total_value), 0)
                FROM shares s 
                WHERE s.member_id = member_id AND s.status = 'active'
            ),
            updated_at = NOW()
            WHERE id = member_id;
        END
    ");
    
    echo "<p>Successfully created all database triggers to maintain balance consistency.</p>";
    
    // Verify the financial data consistency
    echo "<h2>Verifying financial data consistency...</h2>";
    
    // Check loan balances
    $stmt = $pdo->query("
        SELECT COUNT(*) AS mismatch_count
        FROM members m
        LEFT JOIN (
            SELECT member_id, SUM(balance) AS balance 
            FROM loans 
            WHERE status IN ('approved', 'pending')
            GROUP BY member_id
        ) l ON m.id = l.member_id
        WHERE m.loan_balance != COALESCE(l.balance, 0)
    ");
    
    $loanMismatchCount = $stmt->fetchColumn();
    echo "<p>Loan balance mismatches: {$loanMismatchCount}</p>";
    
    // Check savings balances
    $stmt = $pdo->query("
        SELECT COUNT(*) AS mismatch_count
        FROM members m
        LEFT JOIN savings s ON m.id = s.member_id
        WHERE m.savings_balance != COALESCE(s.cumulative_amount, 0)
    ");
    
    $savingsMismatchCount = $stmt->fetchColumn();
    echo "<p>Savings balance mismatches: {$savingsMismatchCount}</p>";
    
    // Check household balances
    $stmt = $pdo->query("
        SELECT COUNT(*) AS mismatch_count
        FROM members m
        LEFT JOIN (
            SELECT member_id, SUM(balance) AS total_balance 
            FROM household_purchases 
            WHERE status IN ('approved', 'pending')
            GROUP BY member_id
        ) h ON m.id = h.member_id
        WHERE m.household_balance != COALESCE(h.total_balance, 0)
    ");
    
    $householdMismatchCount = $stmt->fetchColumn();
    echo "<p>Household balance mismatches: {$householdMismatchCount}</p>";
    
    if ($loanMismatchCount == 0 && $savingsMismatchCount == 0 && $householdMismatchCount == 0) {
        echo "<h3 style='color: green;'>All financial data is now consistent across all tables!</h3>";
    } else {
        echo "<h3 style='color: red;'>Some inconsistencies still exist. Please review the database manually.</h3>";
    }
    
    echo "<h2>Script completed successfully!</h2>";
    
} catch (PDOException $e) {
    die("<p>Database error: " . $e->getMessage() . "</p>");
} catch (Exception $e) {
    die("<p>Error: " . $e->getMessage() . "</p>");
} 