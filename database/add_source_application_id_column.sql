-- Add source_application_id column to household_purchases table
ALTER TABLE household_purchases 
ADD COLUMN source_application_id INT NULL AFTER member_id,
ADD INDEX idx_source_application_id (source_application_id);

-- Update data to link existing purchases to applications where possible
UPDATE household_purchases hp
JOIN household_applications ha ON 
    hp.member_id = ha.member_id AND 
    hp.amount = ha.household_amount AND
    ABS(TIMESTAMPDIFF(MINUTE, hp.created_at, ha.approval_date)) < 60
SET hp.source_application_id = ha.id
WHERE ha.status = 'approved'; 