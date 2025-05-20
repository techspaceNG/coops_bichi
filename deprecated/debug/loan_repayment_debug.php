<?php
// Database connection details
$host = 'localhost';
$dbname = 'coops_bichi';
$username = 'root';
$password = '';

echo "<h1>Loan Repayment Debug Tool</h1>";

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Check loan_repayments table structure
    echo "<h2>Table Structure Check</h2>";
    $stmt = $pdo->query("DESCRIBE loan_repayments");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Check if loan ID 2 exists
    echo "<h2>Loan Check</h2>";
    $stmt = $pdo->prepare("SELECT * FROM loans WHERE id = 2");
    $stmt->execute();
    $loan = $stmt->fetch();
    
    if (!$loan) {
        echo "<p style='color:red'>Loan ID 2 does not exist in the database!</p>";
    } else {
        echo "<p style='color:green'>Loan ID 2 exists in the database.</p>";
        echo "<pre>" . print_r($loan, true) . "</pre>";
        
        // Test insertion with transaction (will be rolled back)
        $pdo->beginTransaction();
        
        try {
            echo "<h2>Test Insert</h2>";
            
            $data = [
                'loan_id' => 2,
                'amount' => 1.00,
                'payment_date' => date('Y-m-d'),
                'processed_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Check all required fields
            echo "<p>Checking required fields:</p>";
            echo "<ul>";
            foreach ($columns as $column) {
                if ($column['Null'] === 'NO' && $column['Default'] === null && $column['Extra'] !== 'auto_increment') {
                    if (array_key_exists($column['Field'], $data)) {
                        echo "<li style='color:green'>{$column['Field']} ✓ (provided)</li>";
                    } else {
                        echo "<li style='color:red'>{$column['Field']} ✗ (missing)</li>";
                    }
                }
            }
            echo "</ul>";
            
            // Try direct SQL insert to see exact error
            echo "<p>Attempting direct SQL insert:</p>";
            
            $insertSQL = "INSERT INTO loan_repayments (loan_id, amount, payment_date, processed_by, created_at) 
                          VALUES (2, 1.00, '" . date('Y-m-d') . "', 1, '" . date('Y-m-d H:i:s') . "')";
            
            echo "<p>SQL: <code>{$insertSQL}</code></p>";
            
            try {
                $pdo->exec($insertSQL);
                echo "<p style='color:green'>✓ Direct SQL insert succeeded!</p>";
            } catch (Exception $e) {
                echo "<p style='color:red'>✗ Direct SQL insert failed: " . $e->getMessage() . "</p>";
            }
            
            // Check for foreign key constraints
            echo "<h3>Foreign Key Constraints</h3>";
            $stmt = $pdo->query("
                SELECT *
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_NAME = 'loan_repayments'
                   OR TABLE_NAME = 'loan_repayments' AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            $constraints = $stmt->fetchAll();
            
            if (empty($constraints)) {
                echo "<p>No foreign key constraints found.</p>";
            } else {
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><th>Column</th><th>References</th><th>Referenced Column</th></tr>";
                
                foreach ($constraints as $constraint) {
                    echo "<tr>";
                    echo "<td>{$constraint['COLUMN_NAME']}</td>";
                    echo "<td>{$constraint['REFERENCED_TABLE_NAME']}</td>";
                    echo "<td>{$constraint['REFERENCED_COLUMN_NAME']}</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
                
                // Check if foreign key references exist
                foreach ($constraints as $constraint) {
                    if ($constraint['TABLE_NAME'] === 'loan_repayments') {
                        $columnName = $constraint['COLUMN_NAME'];
                        $refTable = $constraint['REFERENCED_TABLE_NAME'];
                        $refColumn = $constraint['REFERENCED_COLUMN_NAME'];
                        
                        if (isset($data[$columnName])) {
                            $value = $data[$columnName];
                            $stmt = $pdo->prepare("SELECT * FROM {$refTable} WHERE {$refColumn} = ?");
                            $stmt->execute([$value]);
                            $ref = $stmt->fetch();
                            
                            if ($ref) {
                                echo "<p style='color:green'>Foreign key check for {$columnName} = {$value} in {$refTable}.{$refColumn}: ✓ (exists)</p>";
                            } else {
                                echo "<p style='color:red'>Foreign key check for {$columnName} = {$value} in {$refTable}.{$refColumn}: ✗ (does not exist)</p>";
                            }
                        }
                    }
                }
            }
            
            // Always rollback the test transaction
            $pdo->rollBack();
            echo "<p>Test transaction rolled back - no actual changes were made.</p>";
            
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<p style='color:red'>Error during test insert: " . $e->getMessage() . "</p>";
        }
    }
    
    // Check for database trigger
    echo "<h2>Trigger Status</h2>";
    $stmt = $pdo->query("SHOW TRIGGERS LIKE 'loan_repayments'");
    $triggers = $stmt->fetchAll();
    
    if (empty($triggers)) {
        echo "<p style='color:red'>No triggers defined for loan_repayments table!</p>";
        
        echo "<p>Creating the trigger:</p>";
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
    } else {
        echo "<p style='color:green'>Triggers defined for loan_repayments table:</p>";
        echo "<pre>" . print_r($triggers, true) . "</pre>";
    }
    
    // Final recommendations
    echo "<h2>Recommendations</h2>";
    echo "<ol>";
    echo "<li>Make sure the 'processed_by' field references a valid admin ID in the admin_users table</li>";
    echo "<li>Verify that your loan_id exists in the loans table</li>";
    echo "<li>Ensure your CSV file does not contain any hidden characters or incorrect formatting</li>";
    echo "<li>Try downloading the <a href='/Coops_Bichi/public/superadmin/download-loan-deduction-template'>template</a> and creating a fresh CSV with only one loan record to test</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Database Error: " . $e->getMessage() . "</p>";
}
?> 