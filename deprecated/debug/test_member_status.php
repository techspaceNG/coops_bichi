<?php
// Turn on error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'app/core/Database.php';
require_once 'app/models/Member.php';

// Check if we have members in the database
try {
    $db = App\Core\Database::getConnection();
    
    echo "=== TESTING MEMBER STATUS DISPLAY ===\n";
    
    // Get a few members
    $result = $db->query('SELECT id FROM members LIMIT 3');
    $memberIds = $result->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($memberIds)) {
        echo "No members found in the database.\n";
        exit;
    }
    
    foreach ($memberIds as $memberId) {
        // Get member using model
        $member = App\Models\Member::findById($memberId);
        
        if ($member) {
            echo "Member ID: {$member->id}\n";
            echo "  Name: {$member->first_name} {$member->last_name}\n";
            echo "  Is Active: " . ($member->is_active ? 'Yes' : 'No') . "\n";
            echo "  Status: {$member->status}\n\n";
            
            // Verify that status matches is_active
            $expectedStatus = $member->is_active ? 'active' : 'pending';
            if ($member->status !== $expectedStatus) {
                echo "  ERROR: Status '{$member->status}' does not match expected '{$expectedStatus}' based on is_active={$member->is_active}\n\n";
            }
        } else {
            echo "Failed to load member with ID: $memberId\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 