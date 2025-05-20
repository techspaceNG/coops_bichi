<?php
require_once 'app/core/Database.php';
use App\Core\Database;

$db = Database::getConnection();
$result = $db->query('DESCRIBE share_transactions');
foreach ($result as $row) {
    print_r($row);
} 