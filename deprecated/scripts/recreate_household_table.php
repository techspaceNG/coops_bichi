<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=coops_bichi', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Dropping existing table...\n";
    $pdo->exec("DROP TABLE IF EXISTS `household_applications`");
    
    echo "Creating household_applications table...\n";
    $sql = "CREATE TABLE `household_applications` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `member_id` int(11) NOT NULL,
      `fullname` varchar(100) NOT NULL,
      `coop_no` varchar(20) NOT NULL,
      `item_name` varchar(255) NOT NULL,
      `household_amount` decimal(12,2) NOT NULL,
      `ip_figure` decimal(12,2) NOT NULL,
      `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
      `comment` text DEFAULT NULL,
      `vendor_details` text DEFAULT NULL,
      `approval_date` datetime DEFAULT NULL,
      `approved_by` int(11) DEFAULT NULL,
      `created_at` datetime DEFAULT current_timestamp(),
      `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `member_id` (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    
    echo "Table created successfully.\n";
    
    // Verify structure
    $query = $pdo->query("DESCRIBE household_applications");
    $columns = $query->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Table structure for household_applications:\n";
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} 