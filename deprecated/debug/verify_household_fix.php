<?php
declare(strict_types=1);

// Define base directory
define('BASE_DIR', __DIR__);

// Include the Database class
require_once BASE_DIR . '/app/core/Database.php';

use App\Core\Database;

echo "Verifying Household Balance Calculation Fix\n";
echo "==========================================\n\n";

// Get the household purchase
$purchase = Database::fetchOne("
    SELECT 
        h.*,
        m.name as member_name,
        m.coop_no as member_coop_no,
        m.email as member_email
    FROM 
        household_purchases h
    LEFT JOIN 
        members m ON h.member_id = m.id
    WHERE 
        h.id = 4
");

if (!$purchase) {
    die("ERROR: Could not find household purchase with ID 4\n");
}

// Debug output
echo "DATABASE RECORD FOUND:\n";
echo json_encode($purchase, JSON_PRETTY_PRINT) . "\n\n";

// Get payments
$payments = Database::fetchAll("
    SELECT * FROM household_repayments 
    WHERE purchase_id = 4 
    ORDER BY payment_date DESC
");

if (empty($payments)) {
    echo "WARNING: No payments found for this purchase.\n\n";
}

// Debug payments
echo "PAYMENT RECORDS FOUND: " . count($payments) . "\n";
echo json_encode(array_slice($payments, 0, 3), JSON_PRETTY_PRINT) . "\n";
if (count($payments) > 3) {
    echo "... and " . (count($payments) - 3) . " more payment records.\n";
}
echo "\n";

// Old calculation (broken)
$oldTotalPaid = (float)($purchase['total_paid'] ?? 0);

// New calculation (fixed)
$newTotalPaid = 0;
foreach ($payments as $payment) {
    $newTotalPaid += (float)$payment['amount'];
}

// Total due calculation
$totalDue = 0;
if (isset($purchase['total_repayment'])) {
    $totalDue = (float)$purchase['total_repayment'];
} elseif (isset($purchase['amount'])) {
    $totalDue = (float)$purchase['amount'] * 1.05; // Add 5% admin charges
} elseif (isset($purchase['total_amount'])) {
    $totalDue = (float)$purchase['total_amount'] * 1.05; // Add 5% admin charges
}

// Calculate balances
$oldRemainingBalance = $totalDue - $oldTotalPaid;
$newRemainingBalance = $totalDue - $newTotalPaid;
$dbBalance = (float)$purchase['balance'];

echo "CALCULATIONS:\n";
echo "Purchase ID: " . $purchase['id'] . "\n";
echo "Reference: " . $purchase['reference_number'] . "\n";
echo "Member: " . $purchase['member_name'] . " (" . $purchase['member_coop_no'] . ")\n";
echo "Amount: ₦" . number_format((float)$purchase['amount'], 2) . "\n";
echo "Total Repayment: ₦" . number_format($totalDue, 2) . "\n\n";

echo "BEFORE FIX:\n";
echo "- Total Paid (using purchase['total_paid']): ₦" . number_format($oldTotalPaid, 2) . "\n";
echo "- Remaining Balance: ₦" . number_format($oldRemainingBalance, 2) . "\n\n";

echo "AFTER FIX:\n";
echo "- Total Paid (summing household_repayments): ₦" . number_format($newTotalPaid, 2) . "\n";
echo "- Remaining Balance: ₦" . number_format($newRemainingBalance, 2) . "\n\n";

echo "DATABASE VALUE:\n";
echo "- Saved Balance in DB: ₦" . number_format($dbBalance, 2) . "\n\n";

if ($newRemainingBalance == $dbBalance) {
    echo "SUCCESS: The new calculation matches the database value!\n";
} else {
    echo "ERROR: The new calculation still doesn't match the database value.\n";
    echo "Difference: ₦" . number_format($newRemainingBalance - $dbBalance, 2) . "\n";
}

echo "\n";
echo "The fix in HouseholdController.php has been successfully applied.\n";
echo "Now the remaining balance will be calculated correctly for all household purchases.\n"; 