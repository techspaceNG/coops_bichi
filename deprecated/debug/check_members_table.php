<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'app/core/Database.php';

// Check table structure
try {
    $db = App\Core\Database::getConnection();
    
    echo "=== MEMBERS TABLE STRUCTURE ===\n";
    $result = $db->query('DESCRIBE members');
    $fields = [];
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' (' . $row['Type'] . ")\n";
        $fields[] = $row['Field'];
    }
    
    // Check if status field exists
    $hasStatusField = in_array('status', $fields);
    echo "\nStatus field exists: " . ($hasStatusField ? 'Yes' : 'No') . "\n";
    echo "Is_active field exists: " . (in_array('is_active', $fields) ? 'Yes' : 'No') . "\n\n";
    
    // Check sample member data
    echo "=== SAMPLE MEMBER DATA ===\n";
    $result = $db->query('SELECT id, coop_no, name, email, is_active FROM members LIMIT 3');
    
    $members = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach ($members as $member) {
        echo "ID: " . $member['id'] . "\n";
        echo "  Coop No: " . $member['coop_no'] . "\n";
        echo "  Name: " . $member['name'] . "\n";
        echo "  Email: " . $member['email'] . "\n";
        echo "  Is_active: " . ($member['is_active'] ? 'Yes' : 'No') . "\n";
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 