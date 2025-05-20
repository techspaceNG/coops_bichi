<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>Household Purchase Consistency Fix Script</h1>";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<p>Successfully connected to the database.</p>";
    
    // Part 1: Fix the issue with approved household applications not showing in household_purchases
    echo "<h2>Checking for approved applications that need to be migrated to household_purchases table...</h2>";
    
    // Get all approved household applications that don't have corresponding purchase entries
    $stmt = $pdo->query("
        SELECT ha.*
        FROM household_applications ha
        LEFT JOIN household_purchases hp ON ha.member_id = hp.member_id AND ha.item_name = hp.description
        WHERE ha.status = 'approved' AND hp.id IS NULL
    ");
    
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $migratedCount = 0;
    
    foreach ($applications as $application) {
        echo "<p>Found approved application #{$application['id']} for member #{$application['member_id']} that needs migration.</p>";
        
        // Start transaction
        $pdo->beginTransaction();
        
        try {
            // Calculate household purchase repayment details
            $repaymentPeriod = ceil((float)$application['household_amount'] / (float)$application['ip_figure']);
            $totalRepayment = (float)$application['household_amount'] * 1.05; // 5% interest
            
            // Create household purchase entry
            $stmt = $pdo->prepare("
                INSERT INTO household_purchases (
                    member_id, description, amount, ip_figure, total_repayment,
                    balance, interest_rate, status, approval_date, approved_by,
                    repayment_period, start_date, end_date, created_at, updated_at
                ) VALUES (
                    :member_id, :description, :amount, :ip_figure, :total_repayment,
                    :balance, :interest_rate, :status, :approval_date, :approved_by,
                    :repayment_period, :start_date, :end_date, :created_at, :updated_at
                )
            ");
            
            $currentDate = date('Y-m-d H:i:s');
            $endDate = date('Y-m-d', strtotime("+{$repaymentPeriod} months"));
            
            $stmt->execute([
                ':member_id' => $application['member_id'],
                ':description' => $application['item_name'],
                ':amount' => $application['household_amount'],
                ':ip_figure' => $application['ip_figure'],
                ':total_repayment' => $totalRepayment,
                ':balance' => $application['household_amount'],
                ':interest_rate' => 5.0, // Default admin charges
                ':status' => 'approved',
                ':approval_date' => $application['approval_date'] ?? $currentDate,
                ':approved_by' => $application['approved_by'] ?? null,
                ':repayment_period' => $repaymentPeriod,
                ':start_date' => date('Y-m-d'),
                ':end_date' => $endDate,
                ':created_at' => $application['created_at'] ?? $currentDate,
                ':updated_at' => $currentDate
            ]);
            
            $purchaseId = $pdo->lastInsertId();
            
            // Add transaction record if it doesn't exist
            $transactionStmt = $pdo->prepare("
                SELECT COUNT(*) FROM transaction_history 
                WHERE member_id = :member_id 
                AND transaction_type = 'household' 
                AND description LIKE :description
            ");
            
            $transactionStmt->execute([
                ':member_id' => $application['member_id'],
                ':description' => "Household Purchase Application #{$application['id']} Approved"
            ]);
            
            if ($transactionStmt->fetchColumn() == 0) {
                $transactionStmt = $pdo->prepare("
                    INSERT INTO transaction_history (
                        member_id, transaction_type, amount, description, created_at
                    ) VALUES (
                        :member_id, 'household', :amount, :description, :created_at
                    )
                ");
                
                $transactionStmt->execute([
                    ':member_id' => $application['member_id'],
                    ':amount' => $application['household_amount'],
                    ':description' => "Household Purchase Application #{$application['id']} Approved",
                    ':created_at' => $currentDate
                ]);
            }
            
            // Update member's household balance
            $memberStmt = $pdo->prepare("
                UPDATE members
                SET household_balance = (
                    SELECT COALESCE(SUM(balance), 0)
                    FROM household_purchases
                    WHERE member_id = :member_id
                    AND status IN ('approved', 'pending')
                ),
                updated_at = :updated_at
                WHERE id = :member_id
            ");
            
            $memberStmt->execute([
                ':member_id' => $application['member_id'],
                ':updated_at' => $currentDate
            ]);
            
            $pdo->commit();
            $migratedCount++;
            
            echo "<p>Successfully migrated application #{$application['id']} to household_purchases with ID #{$purchaseId}.</p>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<p style='color: red;'>Error processing application #{$application['id']}: " . $e->getMessage() . "</p>";
        }
    }
    
    if ($migratedCount === 0) {
        echo "<p>No approved household applications found that needed migration.</p>";
    } else {
        echo "<p>Successfully migrated {$migratedCount} approved household applications to the household_purchases table.</p>";
    }
    
    // Part 2: Fix the createOrUpdate method in the AdminHouseholdController
    echo "<h2>Creating database trigger to ensure automatic migration of approved applications...</h2>";
    
    // Drop the trigger if it exists
    $pdo->exec("DROP TRIGGER IF EXISTS after_household_application_approval");
    
    // Create a trigger to automatically migrate approved applications
    $pdo->exec("
        CREATE TRIGGER after_household_application_approval
        AFTER UPDATE ON household_applications
        FOR EACH ROW
        BEGIN
            DECLARE repayment_period INT;
            DECLARE total_repayment DECIMAL(12,2);
            
            IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
                -- Calculate repayment period and total repayment
                SET repayment_period = CEILING(NEW.household_amount / NEW.ip_figure);
                SET total_repayment = NEW.household_amount * 1.05;
                
                -- Create household purchase entry
                INSERT INTO household_purchases (
                    member_id, description, amount, ip_figure, total_repayment,
                    balance, interest_rate, status, approval_date, approved_by,
                    repayment_period, start_date, end_date, created_at, updated_at
                ) VALUES (
                    NEW.member_id, NEW.item_name, NEW.household_amount, NEW.ip_figure, total_repayment,
                    NEW.household_amount, 5.0, 'approved', NOW(), NEW.approved_by,
                    repayment_period, CURDATE(), DATE_ADD(CURDATE(), INTERVAL repayment_period MONTH),
                    NEW.created_at, NOW()
                );
                
                -- Update member's household balance
                UPDATE members
                SET household_balance = (
                    SELECT COALESCE(SUM(balance), 0)
                    FROM household_purchases
                    WHERE member_id = NEW.member_id
                    AND status IN ('approved', 'pending')
                ),
                updated_at = NOW()
                WHERE id = NEW.member_id;
                
                -- Add transaction record
                INSERT INTO transaction_history (
                    member_id, transaction_type, amount, description, created_at
                ) VALUES (
                    NEW.member_id, 'household', NEW.household_amount, 
                    CONCAT('Household Purchase Application #', NEW.id, ' Approved'),
                    NOW()
                );
            END IF;
        END
    ");
    
    echo "<p>Successfully created database trigger 'after_household_application_approval'.</p>";
    
    // Part 3: Verify the consistency
    echo "<h2>Verifying household purchase data consistency...</h2>";
    
    // Check for any remaining inconsistencies
    $stmt = $pdo->query("
        SELECT COUNT(*) as mismatch_count
        FROM household_applications ha
        LEFT JOIN household_purchases hp ON ha.member_id = hp.member_id AND ha.item_name = hp.description
        WHERE ha.status = 'approved' AND hp.id IS NULL
    ");
    
    $mismatchCount = $stmt->fetchColumn();
    echo "<p>Household purchase mismatches: {$mismatchCount}</p>";
    
    if ($mismatchCount == 0) {
        echo "<h3 style='color: green;'>All approved household purchase applications are now properly migrated to the household_purchases table!</h3>";
    } else {
        echo "<h3 style='color: red;'>Some inconsistencies still exist. Please review the database manually.</h3>";
    }
    
    echo "<h2>Script completed successfully!</h2>";
    
} catch (PDOException $e) {
    die("<p>Database error: " . $e->getMessage() . "</p>");
} catch (Exception $e) {
    die("<p>Error: " . $e->getMessage() . "</p>");
} 