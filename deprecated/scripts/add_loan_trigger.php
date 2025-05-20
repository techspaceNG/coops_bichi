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

// SQL for creating the trigger
$triggerSQL = "
DROP TRIGGER IF EXISTS `after_loan_repayment_insert`;
DELIMITER $$
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
END$$
DELIMITER ;
";

try {
    // Create PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // MySQL DELIMITER doesn't work directly in PDO
    // We need to split the SQL statements
    
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
    
    echo "✅ Trigger 'after_loan_repayment_insert' created successfully.";
    
    // Add record to audit_logs table if it exists
    try {
        $stmt = $pdo->prepare("SHOW TABLES LIKE 'audit_logs'");
        $stmt->execute();
        $tableExists = (bool)$stmt->fetch();
        
        if ($tableExists) {
            $auditSql = "INSERT INTO audit_logs 
                (user_id, user_type, action, action_type, details, ip_address) 
                VALUES 
                (1, 'admin', 'Created database trigger', 'database', :details, :ip)";
                
            $stmt = $pdo->prepare($auditSql);
            $details = json_encode([
                'trigger' => 'after_loan_repayment_insert',
                'type' => 'loan_repayment'
            ]);
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            
            $stmt->bindParam(':details', $details);
            $stmt->bindParam(':ip', $ip);
            $stmt->execute();
            
            echo "\n✅ Audit log entry created.";
        }
    } catch (PDOException $e) {
        echo "\n⚠️ Could not create audit log: " . $e->getMessage();
    }
    
} catch (PDOException $e) {
    echo "❌ Error creating trigger: " . $e->getMessage();
    error_log("Failed to create trigger: " . $e->getMessage());
} 