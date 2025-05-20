<?php
declare(strict_types=1);

// Define base directory
define('BASE_DIR', __DIR__);

// Include the Database class
require_once BASE_DIR . '/app/core/Database.php';

use App\Core\Database;

// Get household purchase with ID 4
$purchase = Database::fetchOne("
    SELECT 
        h.*,
        m.name as member_name,
        m.coop_no as member_coop_no
    FROM 
        household_purchases h
    LEFT JOIN 
        members m ON h.member_id = m.id
    WHERE h.id = 4
");

// Get payment records
$payments = Database::fetchAll("
    SELECT * FROM household_repayments WHERE purchase_id = 4 ORDER BY payment_date ASC, id ASC
");

// Calculate totals
$totalPaid = 0;
foreach ($payments as $payment) {
    $totalPaid += (float)$payment['amount'];
}

// Get the total due amount
$totalDue = 0;
if (isset($purchase['total_repayment'])) {
    $totalDue = (float)$purchase['total_repayment'];
} elseif (isset($purchase['amount'])) {
    $totalDue = (float)$purchase['amount'] * 1.05; // Add 5% admin charges
}

$remainingBalance = $totalDue - $totalPaid;
$savedBalance = (float)$purchase['balance'];

echo "===== HOUSEHOLD PURCHASE ID 4 =====\n";
echo "Member: " . $purchase['member_name'] . " (" . $purchase['member_coop_no'] . ")\n";
echo "Description: " . $purchase['description'] . "\n";
echo "Amount: ₦" . number_format((float)$purchase['amount'], 2) . "\n";
echo "IP Figure: ₦" . number_format((float)$purchase['ip_figure'], 2) . "\n";
echo "Total Repayment: ₦" . number_format((float)$purchase['total_repayment'], 2) . "\n";
echo "Balance in DB: ₦" . number_format($savedBalance, 2) . "\n";
echo "Status: " . $purchase['status'] . "\n\n";

echo "===== CALCULATIONS =====\n";
echo "Total Paid (from payments): ₦" . number_format($totalPaid, 2) . "\n";
echo "Total Due: ₦" . number_format($totalDue, 2) . "\n";
echo "Calculated Remaining Balance: ₦" . number_format($remainingBalance, 2) . "\n";
echo "Difference (DB Balance - Calculated): ₦" . number_format($savedBalance - $remainingBalance, 2) . "\n\n";

echo "===== PAYMENT HISTORY =====\n";
$runningBalance = $totalDue;
foreach ($payments as $index => $payment) {
    $runningBalance -= (float)$payment['amount'];
    echo ($index + 1) . ". Date: " . $payment['payment_date'] . 
         " | Amount: ₦" . number_format((float)$payment['amount'], 2) .
         " | Notes: " . ($payment['notes'] ?: 'N/A') . 
         " | Running Balance: ₦" . number_format($runningBalance, 2) . "\n";
}

// Now check the balance in the members table
$member = Database::fetchOne("
    SELECT household_balance FROM members WHERE id = ?
", [$purchase['member_id']]);

echo "\n===== MEMBER RECORD =====\n";
echo "Member Household Balance: ₦" . number_format((float)($member['household_balance'] ?? 0), 2) . "\n\n";

// Check if there's a discrepancy between the view-household calculation and the database
echo "===== CONTROLLER VS. DATABASE =====\n";
echo "Controller calculation (total_due - total_paid): ₦" . number_format($remainingBalance, 2) . "\n";
echo "Database 'balance' field: ₦" . number_format($savedBalance, 2) . "\n";
echo "Issue: The view-household page calculates the remaining balance as totalDue - totalPaid,\n";
echo "       but it uses \$purchase['total_paid'] which doesn't exist in the database.\n";
echo "       Instead, it should sum up the payment amounts from household_repayments.\n\n";

// Count repayments
echo "Total number of repayments: " . count($payments) . "\n";

// Look for the total_paid field
echo "\nAvailable fields in the purchase record:\n";
foreach (array_keys($purchase) as $field) {
    echo "- " . $field . "\n";
}

echo "\n===== TRIGGERS =====\n";
$triggers = Database::fetchAll("
    SHOW TRIGGERS LIKE 'household_repayments'
");
var_dump($triggers); 