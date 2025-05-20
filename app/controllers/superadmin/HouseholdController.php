<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;

/**
 * HouseholdController for Superadmin
 * Handles household purchases management
 */
final class HouseholdController extends AbstractController
{
    /**
     * Display list of household purchases
     */
    public function index(): void
    {
        // Get filter parameters
        $status = $_GET['status'] ?? '';
        $memberId = $_GET['member_id'] ?? '';
        $dateRange = $_GET['date_range'] ?? '';
        $search = $_GET['search'] ?? '';
        
        // Build query conditions for filtering household purchases
        $purchaseConditions = [];
        $purchaseParams = [];
        
        if (!empty($status)) {
            $purchaseConditions[] = "h.status = ?";
            $purchaseParams[] = $status;
        }
        
        if (!empty($memberId)) {
            $purchaseConditions[] = "h.member_id = ?";
            $purchaseParams[] = $memberId;
        }
        
        if (!empty($search)) {
            $purchaseConditions[] = "(m.name LIKE ? OR m.coop_no LIKE ? OR h.reference_number LIKE ?)";
            $purchaseParams[] = "%{$search}%";
            $purchaseParams[] = "%{$search}%";
            $purchaseParams[] = "%{$search}%";
        }
        
        if (!empty($dateRange)) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) == 2) {
                $purchaseConditions[] = "h.created_at BETWEEN ? AND ?";
                $purchaseParams[] = $dates[0] . ' 00:00:00';
                $purchaseParams[] = $dates[1] . ' 23:59:59';
            }
        }
        
        // Build WHERE clause for household purchases
        $purchaseWhereClause = '';
        if (!empty($purchaseConditions)) {
            $purchaseWhereClause = "WHERE " . implode(" AND ", $purchaseConditions);
        }
        
        // Build similar conditions for household applications
        $appConditions = [];
        $appParams = [];
        
        if (!empty($status)) {
            if ($status === 'pending') {
                $appConditions[] = "ha.status = 'pending'";
            } elseif ($status === 'approved') {
                $appConditions[] = "ha.status = 'approved'";
            } elseif ($status === 'declined' || $status === 'rejected') {
                $appConditions[] = "ha.status = 'rejected'";
            }
        } else {
            // Only include pending applications by default
            $appConditions[] = "ha.status = 'pending'";
        }
        
        if (!empty($memberId)) {
            $appConditions[] = "ha.member_id = ?";
            $appParams[] = $memberId;
        }
        
        if (!empty($search)) {
            $appConditions[] = "(ha.fullname LIKE ? OR ha.coop_no LIKE ?)";
            $appParams[] = "%{$search}%";
            $appParams[] = "%{$search}%";
        }
        
        if (!empty($dateRange)) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) == 2) {
                $appConditions[] = "ha.created_at BETWEEN ? AND ?";
                $appParams[] = $dates[0] . ' 00:00:00';
                $appParams[] = $dates[1] . ' 23:59:59';
            }
        }
        
        // Build WHERE clause for applications
        $appWhereClause = '';
        if (!empty($appConditions)) {
            $appWhereClause = "WHERE " . implode(" AND ", $appConditions);
        }
        
        // Pagination
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        
        // Count total records from both tables
        $countPurchasesQuery = "
            SELECT COUNT(*) as total 
            FROM household_purchases h
            LEFT JOIN members m ON h.member_id = m.id
            {$purchaseWhereClause}
        ";
        $countPurchasesResult = Database::fetchOne($countPurchasesQuery, $purchaseParams);
        $totalPurchases = (int)($countPurchasesResult['total'] ?? 0);
        
        $countAppsQuery = "
            SELECT COUNT(*) as total 
            FROM household_applications ha
            {$appWhereClause}
        ";
        $countAppsResult = Database::fetchOne($countAppsQuery, $appParams);
        $totalApps = (int)($countAppsResult['total'] ?? 0);
        
        $total = $totalPurchases + $totalApps;
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        if ($page < 1) $page = 1;
        if ($totalPages > 0 && $page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;
        
        // Get household purchases with member details
        $query = "
            SELECT 
                h.*,
                m.name as member_name,
                m.coop_no as member_coop_no,
                (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid,
                'purchase' as source_table
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            {$purchaseWhereClause}
            ORDER BY 
                h.created_at DESC
            LIMIT 
                {$offset}, {$perPage}
        ";
        
        $purchases = Database::fetchAll($query, $purchaseParams);
        
        // Also get applications from household_applications table based on filters
        $appQuery = "
            SELECT 
                ha.id,
                ha.member_id,
                ha.fullname as member_name,
                ha.coop_no as member_coop_no,
                ha.item_name as description,
                ha.household_amount as amount,
                ha.ip_figure,
                ha.purchase_duration,
                ha.status,
                ha.created_at,
                0 as total_paid,
                (ha.household_amount * 1.05) as total_repayment,
                'application' as source_table
            FROM 
                household_applications ha
            {$appWhereClause}
            ORDER BY 
                ha.created_at DESC
        ";
        
        $applications = Database::fetchAll($appQuery, $appParams);
        
        // Merge the results
        $household = array_merge($purchases, $applications);
        
        // Sort by created_at descending
        usort($household, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Get member list for filter dropdown
        $members = Database::fetchAll("
            SELECT id, coop_no, name FROM members WHERE is_active = 1 ORDER BY name ASC
        ");
        
        // Get department list for filter dropdown
        $departments = Database::fetchAll("
            SELECT id, name FROM departments ORDER BY name ASC
        ");
        
        // Get household statistics
        $stats = [
            'total_purchases' => 0,
            'active_purchases' => 0,
            'pending_purchases' => 0,
            'total_amount' => 0,
            'total' => 0,
            'pending' => 0,
            'pending_amount' => 0,
            'approved' => 0,
            'approved_amount' => 0,
            'declined' => 0,
            'declined_amount' => 0
        ];
        
        // Total purchases
        $totalPurchasesQuery = Database::fetchOne("SELECT COUNT(*) as count FROM household_purchases");
        $totalPendingAppsQuery = Database::fetchOne("SELECT COUNT(*) as count FROM household_applications WHERE status = 'pending'");
        
        $stats['total_purchases'] = $totalPurchasesQuery['count'] ?? 0;
        
        // Only count pending applications, as approved ones are already counted in purchases
        $stats['total'] = ($totalPurchasesQuery['count'] ?? 0) + ($totalPendingAppsQuery['count'] ?? 0);
        
        // Active/Approved purchases
        $approvedPurchasesQuery = Database::fetchOne("
            SELECT 
                COUNT(*) as count,
                SUM(amount) as total_amount
            FROM household_purchases 
            WHERE status = 'active' OR status = 'completed' OR status = 'approved'
        ");
        $stats['active_purchases'] = $approvedPurchasesQuery['count'] ?? 0;
        $stats['approved'] = $stats['active_purchases'];
        $stats['approved_amount'] = $approvedPurchasesQuery['total_amount'] ?? 0;
        
        // Pending purchases and applications
        $pendingPurchasesQuery = Database::fetchOne("
            SELECT 
                COUNT(*) as count,
                SUM(amount) as total_amount
            FROM household_purchases 
            WHERE status = 'pending'
        ");
        
        $pendingAppsQuery = Database::fetchOne("
            SELECT 
                COUNT(*) as count,
                SUM(household_amount) as total_amount
            FROM household_applications 
            WHERE status = 'pending'
        ");
        
        $stats['pending_purchases'] = ($pendingPurchasesQuery['count'] ?? 0) + ($pendingAppsQuery['count'] ?? 0);
        $stats['pending'] = $stats['pending_purchases'];
        $stats['pending_amount'] = ($pendingPurchasesQuery['total_amount'] ?? 0) + ($pendingAppsQuery['total_amount'] ?? 0);
        
        // Declined purchases
        $declinedPurchasesQuery = Database::fetchOne("
            SELECT 
                COUNT(*) as count,
                SUM(amount) as total_amount
            FROM household_purchases 
            WHERE status = 'declined'
        ");
        
        $declinedAppsQuery = Database::fetchOne("
            SELECT 
                COUNT(*) as count,
                SUM(household_amount) as total_amount
            FROM household_applications 
            WHERE status = 'rejected'
        ");
        
        $stats['declined'] = ($declinedPurchasesQuery['count'] ?? 0) + ($declinedAppsQuery['count'] ?? 0);
        $stats['declined_amount'] = ($declinedPurchasesQuery['total_amount'] ?? 0) + ($declinedAppsQuery['total_amount'] ?? 0);
        
        // Total purchase amount
        $purchaseAmountQuery = Database::fetchOne("
            SELECT 
                SUM(total_repayment) as total_amount 
            FROM household_purchases 
            WHERE status != 'declined'
        ");
        
        $appAmountQuery = Database::fetchOne("
            SELECT 
                SUM(household_amount * 1.05) as total_amount 
            FROM household_applications 
            WHERE status = 'pending'
        ");
        
        $stats['total_amount'] = ($purchaseAmountQuery['total_amount'] ?? 0) + ($appAmountQuery['total_amount'] ?? 0);
        
        // Prepare pagination query string
        $queryString = '';
        foreach ($_GET as $key => $value) {
            if ($key !== 'page') {
                $queryString .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }
        
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'query_string' => $queryString
        ];
        
        $this->renderSuperAdmin('superadmin/modules/household', [
            'household' => $household,
            'members' => $members,
            'departments' => $departments,
            'stats' => $stats,
            'pagination' => $pagination,
            'current_page' => 'household',
            'pageTitle' => 'Household Purchases Management'
        ]);
    }
    
    /**
     * View household purchase details
     */
    public function view(string $id): void
    {
        $id = (int)$id;
        
        // Get purchase details
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
                h.id = ?
        ", [$id]);
        
        if (!$purchase) {
            $this->setFlash('error', 'Household purchase not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Get payments made for this purchase
        $payments = Database::fetchAll("
            SELECT * FROM household_repayments 
            WHERE purchase_id = ? 
            ORDER BY payment_date DESC
        ", [$id]);
        
        // Calculate total paid amount from all payments
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += (float)$payment['amount'];
        }
        
        // Get the total due amount, using the right field from the database
        $totalDue = 0;
        if (isset($purchase['total_repayment'])) {
            $totalDue = (float)$purchase['total_repayment'];
        } elseif (isset($purchase['amount'])) {
            $totalDue = (float)$purchase['amount'] * 1.05; // Add 5% admin charges
        } elseif (isset($purchase['total_amount'])) {
            $totalDue = (float)$purchase['total_amount'] * 1.05; // Add 5% admin charges
        }
        
        // Calculate remaining balance
        $remainingBalance = $totalDue - $totalPaid;
        
        // Payment schedule
        $schedule = [];
        if (isset($purchase['payment_schedule']) && $purchase['payment_schedule']) {
            $schedule = json_decode($purchase['payment_schedule'], true) ?? [];
        }
        
        $this->renderSuperAdmin('superadmin/view-household', [
            'purchase' => $purchase,
            'payments' => $payments,
            'remainingBalance' => $remainingBalance,
            'totalPaid' => $totalPaid,
            'schedule' => $schedule,
            'current_page' => 'household',
            'pageTitle' => 'View Purchase: ' . $purchase['reference_number']
        ]);
    }
    
    /**
     * Approve a household purchase
     */
    public function approve(string $id): void
    {
        $id = (int)$id;
        
        // Get purchase details
        $purchase = Database::fetchOne("
            SELECT 
                h.*,
                m.name as member_name,
                m.coop_no as member_coop_no
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            WHERE 
                h.id = ?
        ", [$id]);
        
        if (!$purchase) {
            $this->setFlash('error', 'Household purchase not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Check if purchase is pending
        if ($purchase['status'] !== 'pending') {
            $this->setFlash('error', 'This purchase is already ' . $purchase['status'] . '.');
            $this->redirect('/superadmin/view-household/' . $id);
            return;
        }
        
        // Create payment schedule
        $paymentSchedule = $this->generatePaymentSchedule($purchase);
        
        // Update purchase status and add payment schedule
        $updated = Database::update('household_purchases', [
            'status' => 'active',
            'payment_schedule' => $paymentSchedule
        ], ['id' => $id]);
        
        if ($updated) {
            // Log the action
            Auth::logAction(
                'admin',
                Auth::getAdminId(),
                "Approved household purchase #{$purchase['reference_number']} for {$purchase['member_name']} ({$purchase['member_coop_no']})",
                ['type' => 'household', 'purchase_id' => $id]
            );
            
            // Create notification for member
            \App\Models\Notification::create(
                (int)$purchase['member_id'],
                'Household Purchase Approved',
                "Your household purchase application for ₦" . number_format($purchase['amount'], 2) . " has been approved.",
                'success',
                '/Coops_Bichi/public/member/household'
            );
            
            $this->setFlash('success', 'Household purchase has been approved successfully.');
        } else {
            $this->setFlash('error', 'Failed to approve household purchase.');
        }
        
        $this->redirect('/superadmin/view-household/' . $id);
    }
    
    /**
     * Decline a household purchase
     */
    public function decline(string $id): void
    {
        $id = (int)$id;
        
        // Get purchase details
        $purchase = Database::fetchOne("
            SELECT 
                h.*,
                m.name as member_name,
                m.coop_no as member_coop_no
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            WHERE 
                h.id = ?
        ", [$id]);
        
        if (!$purchase) {
            $this->setFlash('error', 'Household purchase not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Check if purchase is pending
        if ($purchase['status'] !== 'pending') {
            $this->setFlash('error', 'This purchase is already ' . $purchase['status'] . '.');
            $this->redirect('/superadmin/view-household/' . $id);
            return;
        }
        
        // Update purchase status
        $updated = Database::update('household_purchases', [
            'status' => 'declined',
            'admin_notes' => 'Purchase declined by admin',
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if ($updated) {
            // Log the action
            Auth::logAction(
                'admin',
                Auth::getAdminId(),
                "Declined household purchase #{$purchase['reference_number']} for {$purchase['member_name']} ({$purchase['member_coop_no']})",
                ['type' => 'household', 'purchase_id' => $id]
            );
            
            // Create notification for member
            \App\Models\Notification::create(
                (int)$purchase['member_id'],
                'Household Purchase Declined',
                "Your household purchase application has been declined.",
                'error',
                '/Coops_Bichi/public/member/household'
            );
            
            $this->setFlash('success', 'Household purchase has been declined.');
        } else {
            $this->setFlash('error', 'Failed to decline household purchase.');
        }
        
        $this->redirect('/superadmin/household');
    }
    
    /**
     * Print household purchase details
     */
    public function print(string $id): void
    {
        $id = (int)$id;
        
        // Get purchase details
        $purchase = Database::fetchOne("
            SELECT 
                h.*,
                m.name as member_name,
                m.coop_no as member_coop_no,
                m.email as member_email,
                m.phone as member_phone,
                m.department_id,
                d.name as department_name,
                (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            LEFT JOIN 
                departments d ON m.department_id = d.id
            WHERE 
                h.id = ?
        ", [$id]);
        
        if (!$purchase) {
            $this->setFlash('error', 'Household purchase not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Get payment history
        $payments = Database::fetchAll("
            SELECT 
                hp.*,
                a.name as admin_name
            FROM 
                household_repayments hp
            LEFT JOIN 
                admin_users a ON hp.processed_by = a.id
            WHERE 
                hp.purchase_id = ?
            ORDER BY 
                hp.payment_date DESC
        ", [$id]);
        
        // Calculate remaining balance
        $totalPaid = (float)($purchase['total_paid'] ?? 0);
        
        // Get the total due amount, using the right field from the database
        $totalDue = 0;
        if (isset($purchase['amount'])) {
            $totalDue = (float)$purchase['amount'];
        } elseif (isset($purchase['total_amount'])) {
            $totalDue = (float)$purchase['total_amount'];
        } elseif (isset($purchase['total_repayment'])) {
            $totalDue = (float)$purchase['total_repayment'];
        }
        
        $remainingBalance = $totalDue - $totalPaid;
        
        // Get system settings
        $settings = [];
        $settingsData = Database::fetchAll("SELECT * FROM system_settings WHERE setting_key IN ('site_name', 'site_short_name', 'contact_email', 'contact_phone', 'physical_address')");
        foreach ($settingsData as $setting) {
            $settings[$setting['setting_key']] = $setting['value'];
        }
        
        // Render print view
        $this->renderSuperAdmin('superadmin/print-household', [
            'purchase' => $purchase,
            'payments' => $payments,
            'remainingBalance' => $remainingBalance,
            'settings' => $settings,
            'pageTitle' => 'Print Purchase: ' . $purchase['reference_number']
        ], true); // true for print layout without header/footer
    }
    
    /**
     * Show form to add a new deduction for a household purchase
     */
    public function addDeduction(): void
    {
        // Get members for dropdown
        $members = Database::fetchAll("SELECT id, coop_no, name FROM members WHERE is_active = 1 ORDER BY name ASC");
        
        // Get active household purchases
        $purchases = Database::fetchAll("
            SELECT 
                h.id,
                h.reference_number,
                m.name as member_name,
                h.amount as amount,
                h.total_repayment as total_repayment,
                (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            WHERE 
                h.status = 'active' OR h.status = 'approved'
            ORDER BY 
                h.created_at DESC
        ");
        
        // Calculate remaining balance for each purchase
        foreach ($purchases as &$purchase) {
            $totalPaid = $purchase['total_paid'] ?? 0;
            
            // Get the total due amount, using the right field from the database
            $totalDue = 0;
            if (isset($purchase['total_amount'])) {
                $totalDue = (float)$purchase['total_amount'];
            } elseif (isset($purchase['amount'])) {
                $totalDue = (float)$purchase['amount'];
            } elseif (isset($purchase['total_repayment'])) {
                $totalDue = (float)$purchase['total_repayment'];
            }
            
            $purchase['remaining_balance'] = $totalDue - $totalPaid;
        }
        
        $this->renderSuperAdmin('superadmin/add-household-deduction', [
            'members' => $members,
            'purchases' => $purchases,
            'current_page' => 'household',
            'pageTitle' => 'Add Household Purchase Deduction'
        ]);
    }
    
    /**
     * Process adding a new deduction
     */
    public function saveDeduction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Validate input
        $purchaseId = (int)($_POST['purchase_id'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);
        $paymentDate = $_POST['payment_date'] ?? date('Y-m-d');
        $notes = trim($_POST['notes'] ?? '');
        $receiptNumber = trim($_POST['receipt_number'] ?? '');
        
        if ($purchaseId <= 0) {
            $this->setFlash('error', 'Invalid purchase selected.');
            $this->redirect('/superadmin/add-household-deduction');
            return;
        }
        
        if ($amount <= 0) {
            $this->setFlash('error', 'Payment amount must be greater than zero.');
            $this->redirect('/superadmin/add-household-deduction');
            return;
        }
        
        // Get purchase details
        $purchase = Database::fetchOne("
            SELECT 
                h.id,
                h.member_id,
                h.reference_number,
                h.amount as amount,
                h.total_repayment as total_repayment,
                h.status,
                m.name as member_name,
                (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            WHERE 
                h.id = ?
        ", [$purchaseId]);
        
        if (!$purchase) {
            $this->setFlash('error', 'Household purchase not found.');
            $this->redirect('/superadmin/add-household-deduction');
            return;
        }
        
        if ($purchase['status'] !== 'active' && $purchase['status'] !== 'approved') {
            $this->setFlash('error', 'Cannot record payment for a purchase that is not active or approved.');
            $this->redirect('/superadmin/add-household-deduction');
            return;
        }
        
        // Calculate remaining balance
        $totalPaid = (float)($purchase['total_paid'] ?? 0);
        
        // Get the total due amount, using the right field from the database
        $totalDue = 0;
        if (isset($purchase['amount'])) {
            $totalDue = (float)$purchase['amount'];
        } elseif (isset($purchase['total_amount'])) {
            $totalDue = (float)$purchase['total_amount'];
        } elseif (isset($purchase['total_repayment'])) {
            $totalDue = (float)$purchase['total_repayment'];
        }
        
        $remainingBalance = $totalDue - $totalPaid;
        
        if ($remainingBalance <= 0) {
            $this->setFlash('error', 'This purchase has already been fully paid.');
            $this->redirect('/superadmin/add-household-deduction');
            return;
        }
        
        if ($amount > $remainingBalance) {
            $this->setFlash('error', 'Payment amount cannot exceed the remaining balance of ' . number_format($remainingBalance, 2));
            $this->redirect('/superadmin/add-household-deduction');
            return;
        }
        
        // Insert payment record
        $paymentId = Database::insert('household_repayments', [
            'purchase_id' => $purchaseId,
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'processed_by' => Auth::getAdminId(),
            'notes' => $notes,
            'receipt_number' => $receiptNumber,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($paymentId) {
            // Check if purchase is fully paid
            $newTotalPaid = $totalPaid + $amount;
            if ($newTotalPaid >= $totalDue) {
                // Update purchase status to completed
                Database::update('household_purchases', [
                    'status' => 'completed'
                ], ['id' => $purchaseId]);
                
                // Create notification for completed purchase
                \App\Models\Notification::create(
                    (int)$purchase['member_id'],
                    'Household Purchase Completed',
                    "Your household purchase for ₦" . number_format($totalDue, 2) . " has been fully paid.",
                    'success',
                    '/Coops_Bichi/public/member/household'
                );
            } else {
                // Create notification for payment
                \App\Models\Notification::create(
                    (int)$purchase['member_id'],
                    'Household Purchase Payment Recorded',
                    "A payment of ₦" . number_format($amount, 2) . " has been recorded for your household purchase. Remaining balance: ₦" . number_format($totalDue - $newTotalPaid, 2),
                    'info',
                    '/Coops_Bichi/public/member/household'
                );
            }
            
            // Log the action
            Auth::logAction(
                'admin',
                Auth::getAdminId(),
                "Recorded payment of " . number_format($amount, 2) . " for household purchase #{$purchase['reference_number']}",
                ['type' => 'household_payment', 'purchase_id' => $purchaseId, 'payment_id' => $paymentId]
            );
            
            $this->setFlash('success', 'Payment recorded successfully.');
        } else {
            $this->setFlash('error', 'Failed to record payment.');
        }
        
        $this->redirect('/superadmin/view-household/' . $purchaseId);
    }
    
    /**
     * Show form to upload bulk deductions
     */
    public function bulkDeductions(): void
    {
        $this->renderSuperAdmin('superadmin/bulk-household-deductions', [
            'current_page' => 'household',
            'pageTitle' => 'Bulk Household Deductions'
        ]);
    }
    
    /**
     * Process uploaded bulk deductions
     */
    public function processBulkDeductions(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Handle file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'Please upload a valid CSV file.');
            $this->redirect('/superadmin/add-household-deduction?tab=bulk');
            return;
        }
        
        $file = $_FILES['file'];
        $filePath = $file['tmp_name'];
        $fileName = $file['name'];
        
        // Check if file is CSV
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($fileExt !== 'csv') {
            $this->setFlash('error', 'Please upload a CSV file.');
            $this->redirect('/superadmin/add-household-deduction?tab=bulk');
            return;
        }
        
        // Open the file
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->setFlash('error', 'Could not open the file.');
            $this->redirect('/superadmin/add-household-deduction?tab=bulk');
            return;
        }
        
        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->setFlash('error', 'Could not read the file headers.');
            $this->redirect('/superadmin/add-household-deduction?tab=bulk');
            return;
        }
        
        // Expected headers
        $expectedHeaders = ['coops_number', 'amount', 'payment_date', 'notes'];
        
        // Map CSV headers to expected headers (case-insensitive)
        $headerMap = [];
        foreach ($headers as $index => $header) {
            $lowerHeader = strtolower(trim($header));
            foreach ($expectedHeaders as $expectedHeader) {
                if ($lowerHeader === strtolower($expectedHeader)) {
                    $headerMap[$expectedHeader] = $index;
                    break;
                }
            }
        }
        
        // Validate required headers
        if (!isset($headerMap['coops_number']) || !isset($headerMap['amount']) || !isset($headerMap['payment_date'])) {
            fclose($handle);
            $this->setFlash('error', 'CSV file does not have all required headers: coops_number, amount, payment_date');
            $this->redirect('/superadmin/add-household-deduction?tab=bulk');
            return;
        }
        
        // Process each row
        $processed = 0;
        $success = 0;
        $failed = 0;
        $errors = [];
        
        while (($row = fgetcsv($handle)) !== false) {
            $processed++;
            
            // Extract data using header map
            $coopsNumber = isset($headerMap['coops_number']) && isset($row[$headerMap['coops_number']]) ? 
                trim($row[$headerMap['coops_number']]) : '';
            $amount = isset($headerMap['amount']) && isset($row[$headerMap['amount']]) ? 
                (float)$row[$headerMap['amount']] : 0;
            $paymentDate = isset($headerMap['payment_date']) && isset($row[$headerMap['payment_date']]) ? 
                trim($row[$headerMap['payment_date']]) : date('Y-m-d');
            $notes = isset($headerMap['notes']) && isset($row[$headerMap['notes']]) ? 
                trim($row[$headerMap['notes']]) : '';
            
            // Validate required fields
            if (empty($coopsNumber) || $amount <= 0) {
                $failed++;
                $errors[] = "Row {$processed}: Missing required fields (COOPS number or valid amount).";
                continue;
            }
            
            // Find the member by COOPS number
            $member = Database::fetchOne(
                "SELECT id, name FROM members WHERE coop_no = ?",
                [$coopsNumber]
            );
            
            if (!$member) {
                $failed++;
                $errors[] = "Row {$processed}: Member with COOPS number {$coopsNumber} not found.";
                continue;
            }
            
            // Find active household purchases for this member
            $purchases = Database::fetchAll(
                "SELECT 
                    h.*,
                    (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid
                FROM 
                    household_purchases h
                WHERE 
                    h.member_id = ? AND (h.status = 'active' OR h.status = 'approved')
                ORDER BY
                    h.created_at ASC",
                [$member['id']]
            );
            
            if (empty($purchases)) {
                $failed++;
                $errors[] = "Row {$processed}: No active or approved household purchases found for member {$coopsNumber}.";
                continue;
            }
            
            // Find the first purchase with remaining balance
            $purchase = null;
            foreach ($purchases as $p) {
                $totalPaid = (float)($p['total_paid'] ?? 0);
                
                // Get the total due amount, using the right field from the database
                $totalDue = 0;
                if (isset($p['amount'])) {
                    $totalDue = (float)$p['amount'];
                } elseif (isset($p['total_amount'])) {
                    $totalDue = (float)$p['total_amount'];
                } elseif (isset($p['total_repayment'])) {
                    $totalDue = (float)$p['total_repayment'];
                }
                
                $remainingBalance = $totalDue - $totalPaid;
                
                if ($remainingBalance > 0) {
                    $purchase = $p;
                    break;
                }
            }
            
            if (!$purchase) {
                $failed++;
                $errors[] = "Row {$processed}: No household purchases with remaining balance found for member {$coopsNumber}.";
                continue;
            }
            
            // Calculate remaining balance
            $totalPaid = (float)($purchase['total_paid'] ?? 0);
            
            // Get the total due amount, using the right field from the database
            $totalDue = 0;
            if (isset($purchase['amount'])) {
                $totalDue = (float)$purchase['amount'];
            } elseif (isset($purchase['total_amount'])) {
                $totalDue = (float)$purchase['total_amount'];
            } elseif (isset($purchase['total_repayment'])) {
                $totalDue = (float)$purchase['total_repayment'];
            }
            
            $remainingBalance = $totalDue - $totalPaid;
            
            if ($amount > $remainingBalance) {
                $failed++;
                $errors[] = "Row {$processed}: Payment amount exceeds the remaining balance of " . number_format($remainingBalance, 2) . " for member {$coopsNumber}.";
                continue;
            }
            
            // Validate payment date
            if (!empty($paymentDate) && !strtotime($paymentDate)) {
                $paymentDate = date('Y-m-d'); // Default to current date if invalid
            }
            
            // Insert payment record
            $paymentId = Database::insert('household_repayments', [
                'purchase_id' => $purchase['id'],
                'amount' => $amount,
                'payment_date' => $paymentDate,
                'processed_by' => Auth::getAdminId(),
                'notes' => $notes,
                'receipt_number' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($paymentId) {
                $success++;
                
                // Check if purchase is fully paid
                $newTotalPaid = $totalPaid + $amount;
                if ($newTotalPaid >= $totalDue) {
                    // Update purchase status to completed
                    Database::update('household_purchases', [
                        'status' => 'completed'
                    ], ['id' => $purchase['id']]);
                    
                    // Create notification for completed purchase
                    \App\Models\Notification::create(
                        (int)$purchase['member_id'],
                        'Household Purchase Completed',
                        "Your household purchase for ₦" . number_format($totalDue, 2) . " has been fully paid.",
                        'success',
                        '/Coops_Bichi/public/member/household'
                    );
                } else {
                    // Create notification for payment
                    \App\Models\Notification::create(
                        (int)$purchase['member_id'],
                        'Household Purchase Payment Recorded',
                        "A payment of ₦" . number_format($amount, 2) . " has been recorded for your household purchase. Remaining balance: ₦" . number_format($totalDue - $newTotalPaid, 2),
                        'info',
                        '/Coops_Bichi/public/member/household'
                    );
                }
                
                // Log the action
                Auth::logAction(
                    'admin',
                    Auth::getAdminId(),
                    "Recorded bulk payment of " . number_format($amount, 2) . " for member {$coopsNumber} (purchase #{$purchase['reference_number']})",
                    ['type' => 'household_payment', 'purchase_id' => $purchase['id'], 'payment_id' => $paymentId]
                );
            } else {
                $failed++;
                $errors[] = "Row {$processed}: Failed to record payment for member {$coopsNumber}.";
            }
        }
        
        // Close file
        fclose($handle);
        
        // Store results for display in the view
        $bulk_results = [
            'total' => $processed,
            'success' => $success,
            'failed' => $failed,
            'errors' => $errors
        ];
        
        $this->renderSuperAdmin('superadmin/add-household-deduction', [
            'current_page' => 'household',
            'pageTitle' => 'Add Household Purchase Deduction',
            'activeTab' => 'bulk',
            'bulk_results' => $bulk_results
        ]);
    }
    
    /**
     * Download bulk deduction template
     */
    public function downloadDeductionTemplate(): void
    {
        // Set headers for Excel download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="household_deduction_template.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add headers to CSV
        fputcsv($output, [
            'coops_number',
            'amount',
            'payment_date',
            'notes'
        ]);
        
        // Add sample data
        fputcsv($output, [
            'COOPS/04/002',
            '5000.00',
            date('Y-m-d'),
            'Monthly deduction'
        ]);
        
        // Close stream and exit
        fclose($output);
        exit;
    }
    
    /**
     * API search method for household purchases
     */
    public function searchApi(): void
    {
        // Get search parameters
        $query = $_GET['q'] ?? '';
        $memberId = $_GET['member_id'] ?? '';
        $status = $_GET['status'] ?? '';
        
        if (empty($query) && empty($memberId) && empty($status)) {
            echo json_encode(['results' => []]);
            return;
        }
        
        // Build query conditions
        $conditions = [];
        $params = [];
        
        if (!empty($query)) {
            $conditions[] = "(h.reference_number LIKE ? OR m.name LIKE ? OR m.coop_no LIKE ?)";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
        }
        
        if (!empty($memberId)) {
            $conditions[] = "h.member_id = ?";
            $params[] = $memberId;
        }
        
        if (!empty($status)) {
            $conditions[] = "h.status = ?";
            $params[] = $status;
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        // Get household purchases
        $query = "
            SELECT 
                h.id,
                h.reference_number,
                m.name as member_name,
                m.coop_no as member_coop_no,
                h.amount as total_amount,
                h.status,
                h.created_at,
                (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid
            FROM 
                household_purchases h
            LEFT JOIN 
                members m ON h.member_id = m.id
            {$whereClause}
            ORDER BY 
                h.created_at DESC
            LIMIT 
                10
        ";
        
        $purchases = Database::fetchAll($query, $params);
        
        // Format results
        $results = [];
        foreach ($purchases as $purchase) {
            $totalPaid = (float)($purchase['total_paid'] ?? 0);
            
            // Get the total due amount, using the right field from the database
            $totalDue = 0;
            if (isset($purchase['amount'])) {
                $totalDue = (float)$purchase['amount'];
            } elseif (isset($purchase['total_amount'])) {
                $totalDue = (float)$purchase['total_amount'];
            } elseif (isset($purchase['total_repayment'])) {
                $totalDue = (float)$purchase['total_repayment'];
            }
            
            $remainingBalance = $totalDue - $totalPaid;
            
            $results[] = [
                'id' => $purchase['id'],
                'reference_number' => $purchase['reference_number'],
                'member_name' => $purchase['member_name'],
                'member_coop_no' => $purchase['member_coop_no'],
                'total_amount' => number_format($totalDue, 2),
                'total_paid' => number_format($totalPaid, 2),
                'remaining_balance' => number_format($remainingBalance, 2),
                'status' => $purchase['status'],
                'created_at' => date('Y-m-d', strtotime($purchase['created_at']))
            ];
        }
        
        echo json_encode(['results' => $results]);
    }
    
    /**
     * Export household purchases to CSV
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
        
        // Updated query to include all the requested fields
        $query = "
            SELECT 
                h.reference_number,
                m.coop_no as member_coop_no,
                m.name as member_name,
                h.description,
                h.amount,
                h.total_repayment,
                h.repayment_period as purchase_duration,
                h.ip_figure,
                h.status,
                h.created_at as purchase_date,
                (SELECT SUM(amount) FROM household_repayments WHERE purchase_id = h.id) as total_paid,
                h.balance
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
        
        // Add headers to CSV
        fputcsv($output, [
            'Reference Number',
            'Member COOPS No.',
            'Member Name',
            'Item Description',
            'Purchase Amount',
            'Household Limit (Total Repayment)',
            'IP Number (Purchase Duration)',
            'IP Figure (Monthly Deduction)',
            'Total RPMT (Amount Paid)',
            'Balance (Amount to be Paid)',
            'Status',
            'Purchase Date'
        ]);
        
        // Add data to CSV
        foreach ($purchases as $purchase) {
            $totalPaid = (float)($purchase['total_paid'] ?? 0);
            $balance = (float)($purchase['balance'] ?? 0);
            
            // Use the actual balance from the database if available
            if ($balance <= 0 && $totalPaid > 0) {
                $balance = (float)$purchase['total_repayment'] - $totalPaid;
            }
            
            fputcsv($output, [
                $purchase['reference_number'],
                $purchase['member_coop_no'],
                $purchase['member_name'],
                $purchase['description'],
                number_format((float)$purchase['amount'], 2),
                number_format((float)$purchase['total_repayment'], 2),
                $purchase['purchase_duration'],
                number_format((float)$purchase['ip_figure'], 2),
                number_format($totalPaid, 2),
                number_format($balance, 2),
                ucfirst($purchase['status']),
                date('Y-m-d', strtotime($purchase['purchase_date']))
            ]);
        }
        
        // Close stream and exit
        fclose($output);
        exit;
    }
    
    /**
     * Generate payment schedule for a household purchase
     */
    private function generatePaymentSchedule(array $purchase): string
    {
        $amount = (float)$purchase['amount'];
        $term = (int)($purchase['term'] ?? 3); // Default to 3 months if not specified
        $startDate = date('Y-m-d'); // Use current date as start date
        
        $schedule = [];
        $paymentAmount = $amount / $term;
        
        for ($i = 1; $i <= $term; $i++) {
            $dueDate = date('Y-m-d', strtotime($startDate . " +{$i} months"));
            
            $schedule[] = [
                'installment' => $i,
                'due_date' => $dueDate,
                'amount' => round($paymentAmount, 2),
                'status' => 'pending'
            ];
        }
        
        return json_encode($schedule);
    }
    
    /**
     * View household application details (from household_applications table)
     */
    public function viewApplication(string $id): void
    {
        $id = (int)$id;
        
        // Get application details
        $application = Database::fetchOne("
            SELECT 
                ha.*
            FROM 
                household_applications ha
            WHERE 
                ha.id = ?
        ", [$id]);
        
        if (!$application) {
            $this->setFlash('error', 'Household application not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Get member details
        $member = Database::fetchOne("
            SELECT * FROM members WHERE id = ?
        ", [$application['member_id']]);
        
        $this->renderSuperAdmin('superadmin/view-household-application', [
            'application' => $application,
            'member' => $member,
            'current_page' => 'household',
            'pageTitle' => 'View Application: ' . $application['id']
        ]);
    }
    
    /**
     * Approve a household application from the household_applications table
     */
    public function approveApplication(string $id): void
    {
        $id = (int)$id;
        
        // Get application details
        $application = Database::fetchOne("
            SELECT * FROM household_applications WHERE id = ?
        ", [$id]);
        
        if (!$application) {
            $this->setFlash('error', 'Household application not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Check if application is pending
        if ($application['status'] !== 'pending') {
            $this->setFlash('error', 'This application is already ' . $application['status'] . '.');
            $this->redirect('/superadmin/view-household-application/' . $id);
            return;
        }
        
        // Get comment from post data
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        
        // Update application status
        $updated = Database::update('household_applications', [
            'status' => 'approved',
            'comment' => $comment,
            'approval_date' => date('Y-m-d H:i:s'),
            'approved_by' => Auth::getAdminId(),
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if ($updated) {
            // The DB trigger will create the purchase entry and update balances
            
            // Log the action
            Auth::logAction(
                'admin',
                Auth::getAdminId(),
                "Approved household application #{$id} for " . $application['fullname'],
                ['type' => 'household', 'application_id' => $id]
            );
            
            // Create notification for member
            \App\Models\Notification::create(
                (int)$application['member_id'],
                'Household Purchase Application Approved',
                "Your household purchase application for ₦" . number_format((float)$application['household_amount'], 2) . " has been approved.",
                'success',
                '/member/household'
            );
            
            $this->setFlash('success', 'Household application has been approved successfully.');
        } else {
            $this->setFlash('error', 'Failed to approve household application.');
        }
        
        $this->redirect('/superadmin/household');
    }
    
    /**
     * Decline a household application from the household_applications table
     */
    public function declineApplication(string $id): void
    {
        $id = (int)$id;
        
        // Get application details
        $application = Database::fetchOne("
            SELECT * FROM household_applications WHERE id = ?
        ", [$id]);
        
        if (!$application) {
            $this->setFlash('error', 'Household application not found.');
            $this->redirect('/superadmin/household');
            return;
        }
        
        // Check if application is pending or approved
        if ($application['status'] !== 'pending' && $application['status'] !== 'approved') {
            $this->setFlash('error', 'This application is already ' . $application['status'] . '.');
            $this->redirect('/superadmin/view-household-application/' . $id);
            return;
        }
        
        // Get comment from post data (required)
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        if (empty($comment)) {
            $this->setFlash('error', 'Please provide a reason for declining the application.');
            $this->redirect('/superadmin/view-household-application/' . $id);
            return;
        }
        
        // Update application status
        $updated = Database::update('household_applications', [
            'status' => 'rejected',
            'comment' => $comment,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        // If application was previously approved, also update any corresponding household_purchases record
        if ($application['status'] === 'approved') {
            // Find and update the corresponding household purchase based on matching criteria
            $purchase = Database::fetchOne("
                SELECT * FROM household_purchases 
                WHERE member_id = ? AND amount = ? AND 
                      created_at >= ? AND created_at <= ?
            ", [
                $application['member_id'],
                $application['household_amount'],
                $application['approval_date'] ?? $application['created_at'],
                date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($application['approval_date'] ?? $application['created_at'])))
            ]);
            
            if ($purchase) {
                Database::update('household_purchases', [
                    'status' => 'declined',
                    'admin_notes' => 'Purchase rejected: ' . $comment,
                    'updated_at' => date('Y-m-d H:i:s')
                ], ['id' => $purchase['id']]);
            }
        }
        
        if ($updated) {
            // Log the action
            Auth::logAction(
                'admin',
                Auth::getAdminId(),
                "Declined household application #{$id} for " . $application['fullname'],
                ['type' => 'household', 'application_id' => $id]
            );
            
            // Create notification for member
            \App\Models\Notification::create(
                (int)$application['member_id'],
                'Household Purchase Application Rejected',
                "Your household purchase application for ₦" . number_format((float)$application['household_amount'], 2) . " has been rejected. Reason: " . $comment,
                'danger',
                '/member/household'
            );
            
            $this->setFlash('success', 'Household application has been rejected successfully.');
        } else {
            $this->setFlash('error', 'Failed to reject household application.');
        }
        
        $this->redirect('/superadmin/household');
    }
}