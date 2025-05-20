<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Helpers\Utility;
use App\Models\Member;
use Exception;

/**
 * HouseholdPurchase Model
 */
final class HouseholdPurchase
{
    /**
     * Get household purchase details by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM household_purchases WHERE id = ?", [$id]);
    }
    
    /**
     * Get household purchase details by member ID
     *
     * @param int $memberId
     * @return array|null
     */
    public static function getByMemberId(int $memberId): ?array
    {
        return Database::fetchOne("SELECT * FROM household_purchases WHERE member_id = ?", [$memberId]);
    }
    
    /**
     * Get all household purchase details
     *
     * @param int $page
     * @param int $perPage
     * @param string|null $status Filter by status (approved, pending, etc.)
     * @return array
     */
    public static function getAll(int $page = 1, int $perPage = 10, ?string $status = null): array
    {
        $whereClause = "";
        $params = [];
        
        if ($status !== null) {
            $whereClause = "WHERE hp.status = ?";
            $params[] = $status;
        }
        
        $totalQuery = "SELECT COUNT(*) as count FROM household_purchases";
        if ($status !== null) {
            $totalQuery .= " WHERE status = ?";
        }
        
        $totalResult = Database::fetchOne($totalQuery, $params);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $queryParams = array_merge($params, [$pagination['per_page'], $pagination['offset']]);
        
        $purchases = Database::fetchAll(
            "SELECT hp.*, m.coop_no, m.name 
            FROM household_purchases hp
            JOIN members m ON hp.member_id = m.id
            $whereClause
            ORDER BY hp.created_at DESC LIMIT ? OFFSET ?",
            $queryParams
        );
        
        return [
            'data' => $purchases,
            'total' => $total,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Create or update household purchase details
     *
     * @param array $data Household purchase data
     * @return int|null Purchase ID on success, null on failure
     */
    public static function createOrUpdate(array $data): ?int
    {
        // Check if member already has a household purchase
        $memberId = $data['member_id'];
        $existingPurchase = self::getByMemberId($memberId);
        
        if ($existingPurchase) {
            // Update existing purchase
            $updated = Database::update('household_purchases', $data, ['id' => $existingPurchase['id']]);
            return $updated ? $existingPurchase['id'] : null;
        } else {
            // Insert new purchase
            return Database::insert('household_purchases', $data);
        }
    }
    
    /**
     * Update household purchase balance
     *
     * @param int $memberId
     * @param float $newBalance
     * @return bool
     */
    public static function updateBalance(int $memberId, float $newBalance): bool
    {
        $existingPurchase = self::getByMemberId($memberId);
        
        if (!$existingPurchase) {
            return false;
        }
        
        return Database::update('household_purchases', ['balance' => $newBalance], ['id' => $existingPurchase['id']]) > 0;
    }
    
    /**
     * Delete household purchase details
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        return Database::delete('household_purchases', ['id' => $id]) > 0;
    }
    
    /**
     * Get household purchase application by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function getApplicationById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM household_applications WHERE id = ?", [$id]);
    }
    
    /**
     * Get household purchase applications by member ID
     *
     * @param int $memberId
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getApplicationsByMemberId(int $memberId, int $page = 1, int $perPage = 10): array
    {
        $totalQuery = "SELECT COUNT(*) as count FROM household_applications WHERE member_id = ?";
        $totalResult = Database::fetchOne($totalQuery, [$memberId]);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $applications = Database::fetchAll(
            "SELECT * FROM household_applications WHERE member_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$memberId, $pagination['per_page'], $pagination['offset']]
        );
        
        return [
            'data' => $applications,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Get all household purchase applications
     *
     * @param string|null $status
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getAllApplications(?string $status = null, int $page = 1, int $perPage = 10): array
    {
        $whereClause = "";
        $params = [];
        
        if ($status !== null) {
            $whereClause = "WHERE status = ?";
            $params[] = $status;
        }
        
        $totalQuery = "SELECT COUNT(*) as count FROM household_applications $whereClause";
        $totalResult = Database::fetchOne($totalQuery, $params);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $queryParams = array_merge($params, [$pagination['per_page'], $pagination['offset']]);
        
        $applications = Database::fetchAll(
            "SELECT ha.*, m.name as member_name 
            FROM household_applications ha
            JOIN members m ON ha.member_id = m.id
            $whereClause
            ORDER BY ha.created_at DESC LIMIT ? OFFSET ?",
            $queryParams
        );
        
        return [
            'data' => $applications,
            'pagination' => $pagination,
            'total' => $total
        ];
    }
    
    /**
     * Create a household purchase application
     *
     * @param int $memberId
     * @param string $fullname
     * @param string $coopNo
     * @param string $itemName
     * @param float $householdAmount
     * @param float $ipFigure
     * @param int $purchaseDuration
     * @param string|null $vendorDetails
     * @param string|null $bankName
     * @param string|null $accountNumber
     * @param string|null $accountName
     * @param string|null $accountType
     * @return int|null
     */
    public static function createApplication(
        int $memberId, 
        string $fullname, 
        string $coopNo, 
        string $itemName, 
        float $householdAmount, 
        float $ipFigure,
        int $purchaseDuration = 12,
        ?string $vendorDetails = null,
        ?string $bankName = null,
        ?string $accountNumber = null,
        ?string $accountName = null,
        ?string $accountType = null
    ): ?int
    {
        // Calculate total including admin charge (5%)
        $totalAmount = $householdAmount * 1.05; 
        
        $data = [
            'member_id' => $memberId,
            'fullname' => $fullname,
            'coop_no' => $coopNo,
            'item_name' => $itemName,
            'household_amount' => $householdAmount,
            'ip_figure' => $ipFigure,
            'purchase_duration' => $purchaseDuration,
            'status' => 'pending'
        ];
        
        if ($vendorDetails) {
            $data['vendor_details'] = $vendorDetails;
        }
        
        if ($bankName !== null) {
            $data['bank_name'] = $bankName;
        }
        
        if ($accountNumber !== null) {
            $data['account_number'] = $accountNumber;
        }
        
        if ($accountName !== null) {
            $data['account_name'] = $accountName;
        }
        
        if ($accountType !== null) {
            $data['account_type'] = $accountType;
        }
        
        return Database::insert('household_applications', $data);
    }
    
    /**
     * Update household purchase application status
     *
     * @param int $id
     * @param string $status
     * @param string|null $comment
     * @return bool
     */
    public static function updateApplicationStatus(int $id, string $status, ?string $comment = null): bool
    {
        $data = ['status' => $status];
        
        if ($comment !== null) {
            $data['comment'] = $comment;
        }
        
        return Database::update('household_applications', $data, ['id' => $id]) > 0;
    }
    
    /**
     * Approve household application
     *
     * @param int $applicationId
     * @param string|null $comment
     * @param int|null $adminId
     * @return bool
     */
    public static function approveApplication(int $applicationId, ?string $comment = null, ?int $adminId = null): bool
    {
        $application = self::getApplicationById($applicationId);
        
        if (!$application || $application['status'] !== 'pending') {
            return false;
        }
        
        // Start transaction
        Database::getConnection()->beginTransaction();
        
        try {
            // Update application status and approval details
            $data = [
                'status' => 'approved',
                'approval_date' => date('Y-m-d H:i:s')
            ];
            
            if ($comment !== null) {
                $data['comment'] = $comment;
            }
            
            if ($adminId !== null) {
                $data['approved_by'] = $adminId;
            }
            
            $statusUpdated = Database::update('household_applications', $data, ['id' => $applicationId]) > 0;
            
            if (!$statusUpdated) {
                throw new Exception('Failed to update application status');
            }
            
            // Calculate household purchase repayment details
            $repaymentPeriod = ceil((float)$application['household_amount'] / (float)$application['ip_figure']);
            $totalRepayment = (float)$application['household_amount'] * 1.05; // 5% interest
            
            // Create or update household purchase entry in the main table
            $data = [
                'member_id' => $application['member_id'],
                'description' => $application['item_name'],
                'amount' => $application['household_amount'],
                'ip_figure' => $application['ip_figure'],
                'total_repayment' => $totalRepayment,
                'balance' => $application['household_amount'],
                'interest_rate' => 5.0, // Default admin charges
                'status' => 'approved',
                'approval_date' => date('Y-m-d H:i:s'),
                'repayment_period' => $repaymentPeriod,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime("+{$repaymentPeriod} months"))
            ];
            
            $purchaseId = Database::insert('household_purchases', $data);
            
            if (!$purchaseId) {
                throw new Exception('Failed to create household purchase');
            }
            
            // Add transaction record
            $transactionCreated = Database::insert('transaction_history', [
                'member_id' => $application['member_id'],
                'transaction_type' => 'household',
                'amount' => $application['household_amount'],
                'description' => "Household Purchase Application #{$applicationId} Approved"
            ]);
            
            if (!$transactionCreated) {
                throw new Exception('Failed to create transaction record');
            }
            
            // Update member's household balance
            $member = Member::findById($application['member_id']);
            if ($member) {
                $member->household_balance = (float)$application['household_amount'];
                $member->save();
            }
            
            // Commit transaction
            Database::getConnection()->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            Database::getConnection()->rollBack();
            error_log("Household purchase approval error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reject household application
     *
     * @param int $applicationId
     * @param string|null $comment
     * @return bool
     */
    public static function rejectApplication(int $applicationId, ?string $comment = null): bool
    {
        $application = self::getApplicationById($applicationId);
        
        if (!$application || $application['status'] !== 'pending') {
            return false;
        }
        
        return self::updateApplicationStatus($applicationId, 'rejected', $comment);
    }
    
    /**
     * Process household purchase repayment
     *
     * @param int $memberId
     * @param float $amount
     * @return bool
     */
    public static function processRepayment(int $memberId, float $amount): bool
    {
        $purchase = self::getByMemberId($memberId);
        
        if (!$purchase) {
            return false;
        }
        
        // Start transaction
        Database::getConnection()->beginTransaction();
        
        try {
            // Calculate new balance
            $newBalance = max(0, (float)$purchase['balance'] - $amount);
            
            // Update purchase balance
            $balanceUpdated = self::updateBalance($memberId, $newBalance);
            
            if (!$balanceUpdated) {
                throw new Exception('Failed to update household purchase balance');
            }
            
            // Add transaction record
            $transactionCreated = Database::insert('transaction_history', [
                'member_id' => $memberId,
                'transaction_type' => 'household_purchase',
                'amount' => $amount,
                'description' => "Household Purchase Repayment"
            ]);
            
            if (!$transactionCreated) {
                throw new Exception('Failed to create transaction record');
            }
            
            // Commit transaction
            Database::getConnection()->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            Database::getConnection()->rollBack();
            error_log("Household purchase repayment error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process bulk household purchase repayments
     *
     * @param array $repayments Array of [member_id => amount] pairs
     * @param int $adminId
     * @param string $filename
     * @return array
     */
    public static function processBulkRepayments(array $repayments, int $adminId, string $filename): array
    {
        // Create bulk upload log
        $bulkUploadId = Database::insert('bulk_upload_logs', [
            'admin_id' => $adminId,
            'filename' => $filename,
            'upload_type' => 'household_purchase',
            'status' => 'pending',
            'records_processed' => 0,
            'records_failed' => 0,
            'details' => '{}'
        ]);
        
        if (!$bulkUploadId) {
            return [
                'success' => false,
                'message' => 'Failed to create bulk upload log'
            ];
        }
        
        $processed = 0;
        $failed = 0;
        $failures = [];
        
        // Process each repayment
        foreach ($repayments as $memberId => $amount) {
            $success = self::processRepayment((int)$memberId, (float)$amount);
            
            if ($success) {
                $processed++;
            } else {
                $failed++;
                $failures[] = "Member ID: $memberId, Amount: $amount";
            }
        }
        
        // Update bulk upload log
        Database::update('bulk_upload_logs', [
            'status' => 'completed',
            'records_processed' => $processed,
            'records_failed' => $failed,
            'details' => json_encode(['failures' => $failures])
        ], ['id' => $bulkUploadId]);
        
        return [
            'success' => true,
            'processed' => $processed,
            'failed' => $failed,
            'failures' => $failures
        ];
    }
    
    /**
     * Get household purchase statistics for dashboard
     * 
     * @return array
     */
    public static function getStats(): array
    {
        $stats = [
            'total_active' => 0,
            'active_value' => 0,
            'pending_applications' => 0,
            'outstanding_balance' => 0,
            'outstanding_count' => 0,
            'monthly_purchases' => 0,
            'monthly_count' => 0
        ];
        
        try {
            // Get total active purchases and their value
            $activeResult = Database::fetchOne("
                SELECT 
                    COUNT(*) as total_count, 
                    SUM(amount) as total_amount 
                FROM household_purchases 
                WHERE status = 'approved' AND balance > 0
            ");
            
            if ($activeResult) {
                $stats['total_active'] = (int)$activeResult['total_count'];
                $stats['active_value'] = (float)$activeResult['total_amount'];
            }
            
            // Get pending applications count
            $pendingResult = Database::fetchOne("
                SELECT COUNT(*) as count 
                FROM household_applications 
                WHERE status = 'pending'
            ");
            
            if ($pendingResult) {
                $stats['pending_applications'] = (int)$pendingResult['count'];
            }
            
            // Get outstanding balance and count
            $outstandingResult = Database::fetchOne("
                SELECT 
                    COUNT(*) as count, 
                    SUM(balance) as total_balance 
                FROM household_purchases 
                WHERE balance > 0
            ");
            
            if ($outstandingResult) {
                $stats['outstanding_count'] = (int)$outstandingResult['count'];
                $stats['outstanding_balance'] = (float)$outstandingResult['total_balance'];
            }
            
            // Get purchases made this month
            $currentMonth = date('Y-m-01');
            $nextMonth = date('Y-m-01', strtotime('+1 month'));
            
            $monthlyResult = Database::fetchOne("
                SELECT 
                    COUNT(*) as count, 
                    SUM(amount) as total_amount 
                FROM household_purchases 
                WHERE created_at >= ? AND created_at < ?
            ", [$currentMonth, $nextMonth]);
            
            if ($monthlyResult) {
                $stats['monthly_count'] = (int)$monthlyResult['count'];
                $stats['monthly_purchases'] = (float)$monthlyResult['total_amount'];
            }
            
            return $stats;
        } catch (Exception $e) {
            error_log("Error calculating household stats: " . $e->getMessage());
            return $stats; // Return default empty stats
        }
    }
} 