-- Drop existing tables if they exist
DROP TABLE IF EXISTS `share_transactions`;
DROP TABLE IF EXISTS `shares`;

-- Create shares table with proper defaults and computed columns
CREATE TABLE `shares` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(10) UNSIGNED NOT NULL,
  `share_type` enum('ordinary','preferred') DEFAULT 'ordinary',
  `units` int(10) UNSIGNED NOT NULL,
  `unit_value` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED GENERATED ALWAYS AS (units) STORED,
  `unit_price` decimal(10,2) GENERATED ALWAYS AS (unit_value) STORED,
  `total_value` decimal(10,2) GENERATED ALWAYS AS (units * unit_value) STORED,
  `purchase_date` date DEFAULT (CURRENT_DATE),
  `status` enum('active','sold','forfeited') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sale_date` date DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create share_transactions table with proper defaults
CREATE TABLE `share_transactions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `share_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_type` enum('purchase','sale','transfer') NOT NULL,
  `units` int(10) UNSIGNED NOT NULL,
  `unit_value` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED GENERATED ALWAYS AS (units) STORED,
  `unit_price` decimal(10,2) GENERATED ALWAYS AS (unit_value) STORED,
  `total_amount` decimal(10,2) NOT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `transaction_date` date DEFAULT (CURRENT_DATE),
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `share_id` (`share_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recreate the share transaction trigger
-- Note: This must be executed separately through MySQL client
/*
DELIMITER //
CREATE TRIGGER `after_share_transaction_insert`
AFTER INSERT ON `share_transactions`
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
END //
DELIMITER ;
*/ 