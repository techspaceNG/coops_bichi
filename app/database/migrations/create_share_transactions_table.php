<?php
declare(strict_types=1);

use App\Core\Database;

class CreateShareTransactionsTable
{
    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS share_transactions (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                share_id INT UNSIGNED NOT NULL,
                transaction_type ENUM('purchase', 'sale', 'transfer') NOT NULL,
                quantity INT UNSIGNED NOT NULL,
                unit_price DECIMAL(10,2) NOT NULL,
                total_amount DECIMAL(10,2) NOT NULL,
                transaction_date DATE NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (share_id) REFERENCES shares(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        Database::execute($sql);
    }

    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS share_transactions;";
        Database::execute($sql);
    }
} 