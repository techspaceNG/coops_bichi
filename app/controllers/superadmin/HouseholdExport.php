<?php
declare(strict_types=1);

/**
 * Modified Household Export function
 * This file should be included in the HouseholdController.php file
 * Replace the current export() method with this one
 */

/**
 * Export household purchases to CSV with all requested fields
 */
public function export(): void
{
    // Get filter parameters
    $status = $_GET['status'] ?? '';
    $memberId = $_GET['member_id'] ?? '';
    $dateRange = $_GET['date_range'] ?? '';
    $search = $_GET['search'] ?? '';
    
    // Build query conditions for filtering
    $conditions = [];
    $params = [];
    
    if (!empty($status)) {
        $conditions[] = "h.status = ?";
        $params[] = $status;
    }
    
    if (!empty($memberId)) {
        $conditions[] = "h.member_id = ?";
        $params[] = $memberId;
    }
    
    if (!empty($search)) {
        $conditions[] = "(m.name LIKE ? OR m.coop_no LIKE ? OR h.reference_number LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }
    
    if (!empty($dateRange)) {
        $dates = explode(' to ', $dateRange);
        if (count($dates) == 2) {
            $conditions[] = "h.created_at BETWEEN ? AND ?";
            $params[] = $dates[0] . ' 00:00:00';
            $params[] = $dates[1] . ' 23:59:59';
        }
    }
    
    // Build WHERE clause
    $whereClause = '';
    if (!empty($conditions)) {
        $whereClause = "WHERE " . implode(" AND ", $conditions);
    }
    
    // Modified query to include all requested fields:
    // - household limit (total_repayment)
    // - household ip number (purchase_duration/repayment_period)
    // - household ip figure (monthly deduction)
    // - household total RPMT (total amount paid)
    // - household balance (amount to be paid)
    $query = "
        SELECT 
            h.reference_number,
            m.coop_no as member_coop_no,
            m.name as member_name,
            h.amount as principal_amount,
            h.total_repayment as household_limit,
            h.repayment_period as purchase_duration,
            h.ip_figure as monthly_deduction,
            h.description,
            h.status,
            h.created_at as purchase_date,
            (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid,
            h.balance as remaining_balance
        FROM 
            household_purchases h
        LEFT JOIN 
            members m ON h.member_id = m.id
        {$whereClause}
        ORDER BY 
            h.created_at DESC
    ";
    
    $purchases = Database::fetchAll($query, $params);
    
    // Set headers for Excel download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="household_purchases_export_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add headers to CSV with all required fields
    fputcsv($output, [
        'Reference Number',
        'Member COOPS No.',
        'Member Name',
        'Purchase Description',
        'Principal Amount',
        'Household Limit (Total Repayment)',
        'Purchase Duration (Months)',
        'Monthly Deduction (IP Figure)',
        'Total Amount Paid',
        'Remaining Balance',
        'Status',
        'Purchase Date'
    ]);
    
    // Add data to CSV
    foreach ($purchases as $purchase) {
        $totalPaid = (float)($purchase['total_paid'] ?? 0);
        $principalAmount = (float)($purchase['principal_amount'] ?? 0);
        $householdLimit = (float)($purchase['household_limit'] ?? 0);
        $remainingBalance = (float)($purchase['remaining_balance'] ?? 0);
        $monthlyDeduction = (float)($purchase['monthly_deduction'] ?? 0);
        
        fputcsv($output, [
            $purchase['reference_number'],
            $purchase['member_coop_no'],
            $purchase['member_name'],
            $purchase['description'],
            number_format($principalAmount, 2),
            number_format($householdLimit, 2),
            $purchase['purchase_duration'],
            number_format($monthlyDeduction, 2),
            number_format($totalPaid, 2),
            number_format($remainingBalance, 2),
            ucfirst($purchase['status']),
            date('Y-m-d', strtotime($purchase['purchase_date']))
        ]);
    }
    
    // Close stream and exit
    fclose($output);
    exit;
} 