<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Utility;
use App\Helpers\Validator;
use App\Models\Member;
use App\Config\Database;
use App\Helpers\Session;

/**
 * Household Controller
 * Handles household purchase functionality
 */
final class HouseholdController extends Controller
{
    /**
     * Display household purchases dashboard
     *
     * @return void
     */
    public function index(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/Coops_Bichi/public/logout');
        }
        
        // Get household balance
        $financialSummary = Member::getFinancialSummary($memberId);
        $household_balance = $financialSummary['household_balance'] ?? 0.0;
        
        // Get active purchases
        try {
            // First check what columns exist in the household_purchases table
            $columnsResult = Database::fetchAll(
                "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'household_purchases'"
            );
            
            $columns = array_column($columnsResult, 'COLUMN_NAME');
            error_log('Available columns in household_purchases: ' . implode(', ', $columns));
            
            // Determine the right field names based on the schema
            $descriptionField = in_array('description', $columns) ? 'description' : 'item_name';
            $amountField = in_array('amount', $columns) ? 'amount' : 'household_amount';
            
            // Query based on the exact schema we have in database/coops_bichi.sql
            $activePurchases = Database::fetchAll(
                "SELECT 
                    id,
                    member_id,
                    {$descriptionField} as item_description,
                    {$amountField} as item_cost,
                    ip_figure,
                    total_repayment,
                    balance as remaining_balance,
                    interest_rate as admin_charge_rate,
                    status,
                    approval_date,
                    approved_by,
                    repayment_period,
                    start_date,
                    end_date,
                    created_at,
                    updated_at
                FROM household_purchases 
                WHERE member_id = ? AND status != 'declined'
                ORDER BY created_at DESC",
                [$memberId]
            );
            
            // If no purchases found, check the household_applications table
            if (empty($activePurchases)) {
                error_log('No purchases found in household_purchases. Checking household_applications...');
                
                try {
                    $applicationsResult = Database::fetchAll(
                        "SELECT 
                            id,
                            member_id,
                            item_name as item_description,
                            household_amount as item_cost,
                            ip_figure,
                            household_amount * 1.05 as total_repayment,
                            household_amount as remaining_balance,
                            5.0 as admin_charge_rate,
                            status,
                            approval_date,
                            approved_by,
                            purchase_duration as repayment_period,
                            created_at,
                            updated_at
                        FROM household_applications 
                        WHERE member_id = ? AND status = 'approved'
                        ORDER BY created_at DESC",
                        [$memberId]
                    );
                    
                    if (!empty($applicationsResult)) {
                        $activePurchases = $applicationsResult;
                        error_log('Found approved applications in household_applications table: ' . count($activePurchases));
                    }
                } catch (\PDOException $e) {
                    error_log('Error checking household_applications: ' . $e->getMessage());
                }
            }
            
            // Get recent applications
            $purchaseApplications = Database::fetchAll(
                "SELECT 
                    id,
                    member_id,
                    item_name as item_description,
                    household_amount as item_cost,
                    ip_figure,
                    status,
                    comment,
                    created_at,
                    updated_at
                FROM household_applications 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT 5",
                [$memberId]
            );
            
            // If no applications found in household_applications, try household_purchases
            if (empty($purchaseApplications)) {
                $purchaseApplications = Database::fetchAll(
                    "SELECT 
                        id,
                        member_id,
                        {$descriptionField} as item_description,
                        {$amountField} as item_cost,
                        ip_figure,
                        status,
                        created_at,
                        updated_at
                    FROM household_purchases 
                    WHERE member_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT 5",
                    [$memberId]
                );
            }
            
            // Log the purchase applications to debug
            error_log('Purchase applications found: ' . count($purchaseApplications));
            if (!empty($purchaseApplications)) {
                foreach ($purchaseApplications as $index => $app) {
                    error_log("App #{$index}: ID={$app['id']}, Description=" . 
                              ($app['item_description'] ?? $app['item_name'] ?? $app['description'] ?? 'N/A') . 
                              ", Status={$app['status']}, Amount=" . 
                              ($app['item_cost'] ?? $app['household_amount'] ?? $app['amount'] ?? 'N/A'));
                }
            }
            
            // Calculate total purchase amount and ensure all purchases have remaining_balance
            $total_purchase_amount = 0;
            foreach ($activePurchases as &$purchase) {
                // Get the item cost - directly from the mapped field
                $itemCost = (float)($purchase['item_cost'] ?? 0);
                
                // Calculate admin charge based on interest_rate field or default 5%
                $adminChargeRate = (float)($purchase['admin_charge_rate'] ?? 5.0);
                $adminCharge = $itemCost * ($adminChargeRate / 100);
                $purchase['admin_charge'] = $adminCharge;
                
                // Use total_repayment if available, otherwise calculate
                if (isset($purchase['total_repayment']) && !empty($purchase['total_repayment'])) {
                    $itemTotalCost = (float)$purchase['total_repayment'];
                } else {
                    $itemTotalCost = $itemCost + $adminCharge;
                    $purchase['total_repayment'] = $itemTotalCost;
                }
                
                $total_purchase_amount += $itemTotalCost;
                
                // Use remaining_balance from the query if available
                if (!isset($purchase['remaining_balance']) || $purchase['remaining_balance'] === null) {
                    // Use balance field if available, otherwise calculate
                    if (isset($purchase['balance']) && !empty($purchase['balance'])) {
                        $purchase['remaining_balance'] = (float)$purchase['balance'];
                    } else {
                        // Include admin charges in the remaining balance to pay
                        $purchase['remaining_balance'] = $itemTotalCost; // Default to full amount with admin charge
                    }
                } else {
                    // Make sure remaining balance includes admin charge
                    if ($purchase['remaining_balance'] < $itemTotalCost && $purchase['remaining_balance'] == $itemCost) {
                        // If the remaining balance equals the base cost without admin charge, update it
                        $purchase['remaining_balance'] = $itemTotalCost;
                        
                        // Update the balance in the database to include admin charge
                        try {
                            Database::update(
                                'household_purchases',
                                ['balance' => $itemTotalCost],
                                ['id' => $purchase['id']]
                            );
                            error_log("Updated household purchase ID {$purchase['id']} balance to include admin charge: {$itemTotalCost}");
                        } catch (\PDOException $e) {
                            error_log("Failed to update household purchase balance: " . $e->getMessage());
                        }
                    }
                }
                
                // Ensure we have a monthly payment amount
                if (!isset($purchase['monthly_payment']) || empty($purchase['monthly_payment'])) {
                    $purchase['monthly_payment'] = (float)($purchase['ip_figure'] ?? ($itemTotalCost / 12));
                }
                
                // Ensure we have an item category
                if (!isset($purchase['item_category']) || empty($purchase['item_category'])) {
                    $purchase['item_category'] = 'General';
                }
                
                // Log purchase debug info
                error_log("Purchase processed: ID: {$purchase['id']}, Description: {$purchase['item_description']}, Cost: {$itemCost}, Total: {$itemTotalCost}, Remaining: {$purchase['remaining_balance']}");
            }
            unset($purchase); // Release the reference
            
            // Log data for debugging
            error_log('Active purchases found: ' . count($activePurchases));
            if (empty($activePurchases)) {
                error_log('No active purchases found for member ID: ' . $memberId);
            }
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            $activePurchases = [];
            $purchaseApplications = [];
            $total_purchase_amount = 0;
        }
        
        $this->render('member/household/index', [
            'title' => 'My Household Purchases - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'household_balance' => $household_balance,
            'activePurchases' => $activePurchases,
            'purchaseApplications' => $purchaseApplications,
            'total_purchase_amount' => $total_purchase_amount
        ]);
    }
    
    /**
     * Display and process household purchase application
     *
     * @return void
     */
    public function order(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/Coops_Bichi/public/logout');
        }
        
        // Check if member already has a pending household purchase application
        try {
            // First check if the household_applications table exists
            $tableExists = Database::fetchOne(
                "SELECT COUNT(*) as count FROM information_schema.tables 
                WHERE table_schema = DATABASE() 
                AND table_name = 'household_applications'"
            );
            
            $pendingApplication = null;
            if ($tableExists && $tableExists['count'] > 0) {
                $pendingApplication = Database::fetchOne(
                    "SELECT * FROM household_applications 
                    WHERE member_id = ? AND status = 'pending'",
                    [$memberId]
                );
            } else {
                // Check legacy table as fallback
                $pendingApplication = Database::fetchOne(
                    "SELECT * FROM household_purchases 
                    WHERE member_id = ? AND status = 'pending'",
                    [$memberId]
                );
            }
            
            $hasActiveApplication = !empty($pendingApplication);
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            $pendingApplication = null;
            $hasActiveApplication = false;
        }
        
        $errors = [];
        $application_success = false;
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitizeInput($_POST);
            
            // Validate form data
            Validator::resetErrors();
            Validator::required($input, ['item_name', 'household_amount', 'ip_figure', 'purchase_duration', 'bank_name', 'account_number', 'account_name', 'account_type']);
            Validator::numeric($input, 'household_amount');
            Validator::numeric($input, 'ip_figure');
            Validator::numeric($input, 'purchase_duration');
            Validator::min($input, 'household_amount', 1000);
            Validator::min($input, 'ip_figure', 500);
            
            if ($input['ip_figure'] > $input['household_amount']) {
                Validator::addError('ip_figure', 'Monthly payment cannot be greater than the item cost');
            }
            
            // Validate account number (must be 10 digits)
            if (isset($input['account_number']) && !preg_match('/^\d{10}$/', $input['account_number'])) {
                Validator::addError('account_number', 'Account number must be 10 digits');
            }
            
            if (Validator::hasErrors()) {
                $errors = Validator::getErrors();
            } else {
                try {
                    // Import HouseholdPurchase model if not already available
                    if (!class_exists('\App\Models\HouseholdPurchase') && !class_exists('HouseholdPurchase')) {
                        require_once APP_ROOT . '/models/HouseholdPurchase.php';
                    }
                    
                    // Use the model method to create application
                    // Check which namespace the class exists in and use accordingly
                    if (class_exists('\App\Models\HouseholdPurchase')) {
                        $applicationId = \App\Models\HouseholdPurchase::createApplication(
                            $memberId,
                            $member->getFullName(),
                            $member->coop_no,
                            $input['item_name'],
                            (float)$input['household_amount'],
                            (float)$input['ip_figure'],
                            (int)$input['purchase_duration'],
                            $input['vendor_details'] ?? null,
                            $input['bank_name'] ?? null,
                            $input['account_number'] ?? null,
                            $input['account_name'] ?? null,
                            $input['account_type'] ?? null
                        );
                    } else {
                        $applicationId = \HouseholdPurchase::createApplication(
                            $memberId,
                            $member->getFullName(),
                            $member->coop_no,
                            $input['item_name'],
                            (float)$input['household_amount'],
                            (float)$input['ip_figure'],
                            (int)$input['purchase_duration'],
                            $input['vendor_details'] ?? null,
                            $input['bank_name'] ?? null,
                            $input['account_number'] ?? null,
                            $input['account_name'] ?? null,
                            $input['account_type'] ?? null
                        );
                    }
                    
                    if ($applicationId) {
                        // Save bank details to member profile if they're not already set
                        $member = Member::findById($memberId);
                        if ($member) {
                            $shouldUpdate = false;
                            
                            if (empty($member->bank_name) && !empty($input['bank_name'])) {
                                $member->bank_name = $input['bank_name'];
                                $shouldUpdate = true;
                            }
                            
                            if (empty($member->account_number) && !empty($input['account_number'])) {
                                $member->account_number = $input['account_number'];
                                $shouldUpdate = true;
                            }
                            
                            if ($shouldUpdate) {
                                $member->save();
                            }
                        }
                        
                        Session::setFlash('success', 'Household purchase application submitted successfully.');
                        header('Location: /Coops_Bichi/public/member/household/applications');
                        exit;
                    } else {
                        $errors['application'] = 'Failed to submit application. Please try again.';
                    }
                } catch (\PDOException $e) {
                    error_log('Household application error: ' . $e->getMessage());
                    $errors['application'] = 'Database error occurred. Please try again later.';
                }
            }
        }
        
        $this->render('member/household/order', [
            'title' => 'Apply for Household Purchase - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'hasActiveApplication' => $hasActiveApplication,
            'pendingApplication' => $pendingApplication,
            'errors' => $errors,
            'application_success' => $application_success
        ]);
    }
    
    /**
     * Display household purchase details
     *
     * @param int $id Household purchase ID
     * @return void
     */
    public function details(int $id): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/Coops_Bichi/public/logout');
        }
        
        try {
            // Get purchase details
            $purchase = Database::fetchOne(
                "SELECT * FROM household_purchases WHERE id = ? AND member_id = ?",
                [$id, $memberId]
            );
            
            if (!$purchase) {
                $this->setFlash('error', 'Household purchase not found', 'member_household');
                $this->redirect('/Coops_Bichi/public/member/household');
            }
            
            // Get purchase payment history
            $paymentHistory = Database::fetchAll(
                "SELECT * FROM transaction_history 
                WHERE member_id = ? AND transaction_type = 'household' AND related_id = ? 
                ORDER BY created_at DESC",
                [$memberId, $id]
            );
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            $this->setFlash('error', 'Could not retrieve household purchase details', 'member_household');
            $this->redirect('/Coops_Bichi/public/member/household');
        }
        
        $this->render('member/household/details', [
            'title' => 'Household Purchase Details - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'purchase' => $purchase,
            'paymentHistory' => $paymentHistory ?? []
        ]);
    }
    
    /**
     * Display household purchase applications
     *
     * @return void
     */
    public function applications(): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/Coops_Bichi/public/logout');
        }
        
        // Get page number from query string
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        try {
            // Check if household_applications table exists
            $applicationsTableExists = Database::fetchOne(
                "SELECT COUNT(*) as count FROM information_schema.tables 
                WHERE table_schema = DATABASE() 
                AND table_name = 'household_applications'"
            );
            
            $totalRecords = 0;
            $applications = [];
            
            // First try the household_applications table
            if ($applicationsTableExists && $applicationsTableExists['count'] > 0) {
                // Get total count of applications
                $applicationsCount = Database::fetchOne(
                    "SELECT COUNT(*) as count FROM household_applications WHERE member_id = ?",
                    [$memberId]
                );
                
                $totalRecords += $applicationsCount ? (int)$applicationsCount['count'] : 0;
                
                // Check if item_category field exists
                $columnsResult = Database::fetchAll(
                    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'household_applications'"
                );
                
                $columns = array_column($columnsResult, 'COLUMN_NAME');
                $hasCategoryField = in_array('item_category', $columns);
                
                // Get applications from household_applications
                $applicationsResults = Database::fetchAll(
                    "SELECT 
                        id, 
                        member_id,
                        item_name as item_description,
                        " . ($hasCategoryField ? "item_category," : "'General' as item_category,") . "
                        household_amount as item_cost,
                        ip_figure,
                        status,
                        created_at,
                        updated_at,
                        'household_applications' as source_table
                    FROM household_applications 
                    WHERE member_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?",
                    [$memberId, $perPage, $offset]
                );
                
                if (!empty($applicationsResults)) {
                    $applications = $applicationsResults;
                }
            }
            
            // If we need more results or found none, check household_purchases table
            if (count($applications) < $perPage) {
                // Get total count from household_purchases
                $purchasesCount = Database::fetchOne(
                    "SELECT COUNT(*) as count FROM household_purchases WHERE member_id = ?",
                    [$memberId]
                );
                
                $totalRecords += $purchasesCount ? (int)$purchasesCount['count'] : 0;
                
                // Determine remaining items to fetch
                $remainingItems = $perPage - count($applications);
                $remainingOffset = max(0, $offset - ($applicationsCount['count'] ?? 0));
                
                if ($remainingItems > 0 && $remainingOffset >= 0) {
                    // First check what columns exist in the household_purchases table
                    $columnsResult = Database::fetchAll(
                        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'household_purchases'"
                    );
                    
                    $columns = array_column($columnsResult, 'COLUMN_NAME');
                    
                    // Determine the right field names based on the schema
                    $descriptionField = in_array('description', $columns) ? 'description' : 'item_name';
                    $amountField = in_array('amount', $columns) ? 'amount' : 'household_amount';
                    $categoryField = in_array('item_category', $columns) ? 'item_category' : "'General' as item_category";
                    
                    // Get applications from household_purchases
                    $purchasesResults = Database::fetchAll(
                        "SELECT 
                            id, 
                            member_id,
                            {$descriptionField} as item_description,
                            {$categoryField},
                            {$amountField} as item_cost,
                            ip_figure,
                            status,
                            created_at,
                            updated_at,
                            'household_purchases' as source_table
                        FROM household_purchases 
                        WHERE member_id = ? 
                        ORDER BY created_at DESC 
                        LIMIT ? OFFSET ?",
                        [$memberId, $remainingItems, $remainingOffset]
                    );
                    
                    if (!empty($purchasesResults)) {
                        $applications = array_merge($applications, $purchasesResults);
                    }
                }
            }
            
            // Sort the combined results by created_at
            usort($applications, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            
            $totalPages = ceil($totalRecords / $perPage);
            
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'offset' => $offset,
                'total_records' => $totalRecords
            ];
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            error_log('Error fetching applications: ' . $e->getMessage());
            $applications = [];
            $pagination = [
                'current_page' => 1,
                'total_pages' => 1,
                'per_page' => $perPage,
                'offset' => 0,
                'total_records' => 0
            ];
        }
        
        $result = [
            'data' => $applications,
            'pagination' => $pagination
        ];
        
        $this->render('member/household/applications', [
            'title' => 'Household Purchase Applications - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'applications' => $result
        ]);
    }
    
    /**
     * Display household purchase application details
     *
     * @param int $id Household purchase application ID
     * @return void
     */
    public function applicationDetails(int $id): void
    {
        $this->requireMember();
        
        $memberId = Auth::getMemberId();
        $member = Member::findById($memberId);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found');
            $this->redirect('/Coops_Bichi/public/logout');
        }
        
        try {
            // First try to get application details from household_applications table
            $application = Database::fetchOne(
                "SELECT * FROM household_applications WHERE id = ? AND member_id = ?",
                [$id, $memberId]
            );
            
            // If not found, try household_purchases table
            if (!$application) {
                $application = Database::fetchOne(
                    "SELECT * FROM household_purchases WHERE id = ? AND member_id = ?",
                    [$id, $memberId]
                );
            }
            
            if (!$application) {
                $this->setFlash('error', 'Application not found', 'member_household');
                $this->redirect('/Coops_Bichi/public/member/household/applications');
            }
            
            // Standardize field names for consistent view rendering
            if (isset($application['description']) && !isset($application['item_description'])) {
                $application['item_description'] = $application['description'];
            }
            
            if (isset($application['item_name']) && !isset($application['item_description'])) {
                $application['item_description'] = $application['item_name'];
            }
            
            if (isset($application['amount']) && !isset($application['item_cost'])) {
                $application['item_cost'] = $application['amount'];
            }
            
            if (isset($application['household_amount']) && !isset($application['item_cost'])) {
                $application['item_cost'] = $application['household_amount'];
            }
        } catch (\PDOException $e) {
            // If the table doesn't exist or some other database error
            $this->setFlash('error', 'Could not retrieve application details: ' . $e->getMessage(), 'member_household');
            $this->redirect('/Coops_Bichi/public/member/household/applications');
        }
        
        $this->render('member/household/application_details', [
            'title' => 'Application Details - FCET Bichi Staff Multipurpose Cooperative Society',
            'member' => $member,
            'application' => $application
        ]);
    }
    
    /**
     * Helper method to sanitize input data
     *
     * @param array $data Input data to sanitize
     * @return array Sanitized data
     */
    protected function sanitizeInput(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
} 