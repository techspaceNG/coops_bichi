<?php
declare(strict_types=1);

use App\Core\Database;

class CreateSharesTable
{
    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS shares (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                member_id INT UNSIGNED NOT NULL,
                share_type ENUM('ordinary', 'preferred') DEFAULT 'ordinary',
                quantity INT UNSIGNED NOT NULL,
                unit_price DECIMAL(10,2) NOT NULL,
                total_value DECIMAL(10,2) NOT NULL,
                purchase_date DATE NOT NULL,
                status ENUM('active', 'sold', 'forfeited') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        Database::execute($sql);
    }

    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS shares;";
        Database::execute($sql);
    }
} 