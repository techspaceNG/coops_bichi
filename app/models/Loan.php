<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Helpers\Utility;

/**
 * Loan Model
 */
final class Loan
{
    /**
     * Get loan details by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM loans WHERE id = ?", [$id]);
    }
    
    /**
     * Get loan details by member ID
     *
     * @param int $memberId
     * @return array|null
     */
    public static function getByMemberId(int $memberId): ?array
    {
        return Database::fetchOne("SELECT * FROM loans WHERE member_id = ?", [$memberId]);
    }
    
    /**
     * Get all loan details
     *
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getAll(int $page = 1, int $perPage = 10): array
    {
        $totalQuery = "SELECT COUNT(*) as count FROM loans";
        $totalResult = Database::fetchOne($totalQuery);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $loans = Database::fetchAll(
            "SELECT l.*, m.coop_no, m.name 
            FROM loans l
            JOIN members m ON l.member_id = m.id
            ORDER BY l.created_at DESC LIMIT ? OFFSET ?",
            [$pagination['per_page'], $pagination['offset']]
        );
        
        return [
            'data' => $loans,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Create or update loan details
     *
     * @param int $memberId
     * @param float $loanLimit
     * @param int $ipNo
     * @param float $ipFigure
     * @param float $totalRepayment
     * @param float $balance
     * @return bool
     */
    public static function createOrUpdate(int $memberId, float $loanLimit, int $ipNo, float $ipFigure, float $totalRepayment, float $balance): bool
    {
        $existingLoan = self::getByMemberId($memberId);
        
        $data = [
            'loan_amount' => $loanLimit,
            'ip_figure' => $ipFigure,
            'total_repayment' => $totalRepayment,
            'balance' => $balance,
            'interest_rate' => 5.0, // Default admin charges
            'repayment_period' => $ipNo,
            'status' => 'approved'
        ];
        
        if (!isset($data['start_date'])) {
            $data['start_date'] = date('Y-m-d');
        }
        
        if (!isset($data['end_date'])) {
            $data['end_date'] = date('Y-m-d', strtotime("+{$ipNo} months"));
        }
        
        if ($existingLoan) {
            return Database::update('loans', $data, ['id' => $existingLoan['id']]) > 0;
        } else {
            $data['member_id'] = $memberId;
            return Database::insert('loans', $data) > 0;
        }
    }
    
    /**
     * Update loan balance
     *
     * @param int $memberId
     * @param float $newBalance
     * @return bool
     */
    public static function updateBalance(int $memberId, float $newBalance): bool
    {
        $existingLoan = self::getByMemberId($memberId);
        
        if (!$existingLoan) {
            return false;
        }
        
        return Database::update('loans', ['balance' => $newBalance], ['id' => $existingLoan['id']]) > 0;
    }
    
    /**
     * Delete loan details
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        return Database::delete('loans', ['id' => $id]) > 0;
    }
    
    /**
     * Get loan application by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function getApplicationById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM loan_applications WHERE id = ?", [$id]);
    }
    
    /**
     * Get loan applications by member ID
     *
     * @param int $memberId
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getApplicationsByMemberId(int $memberId, int $page = 1, int $perPage = 10): array
    {
        $totalQuery = "SELECT COUNT(*) as count FROM loan_applications WHERE member_id = ?";
        $totalResult = Database::fetchOne($totalQuery, [$memberId]);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $applications = Database::fetchAll(
            "SELECT * FROM loan_applications WHERE member_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$memberId, $pagination['per_page'], $pagination['offset']]
        );
        
        return [
            'data' => $applications,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Get all loan applications
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
        
        $totalQuery = "SELECT COUNT(*) as count FROM loan_applications $whereClause";
        $totalResult = Database::fetchOne($totalQuery, $params);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $queryParams = array_merge($params, [$pagination['per_page'], $pagination['offset']]);
        
        $applications = Database::fetchAll(
            "SELECT la.*, m.name as member_name 
            FROM loan_applications la
            JOIN members m ON la.member_id = m.id
            $whereClause
            ORDER BY la.created_at DESC LIMIT ? OFFSET ?",
            $queryParams
        );
        
        return [
            'data' => $applications,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Create a loan application
     *
     * @param int $memberId
     * @param string $fullname
     * @param string $coopNo
     * @param float $loanAmount
     * @param float $ipFigure
     * @param int $loanDuration
     * @param string|null $purpose
     * @param string|null $additionalInfo
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
        float $loanAmount, 
        float $ipFigure,
        int $loanDuration = 12,
        ?string $purpose = null,
        ?string $additionalInfo = null,
        ?string $bankName = null,
        ?string $accountNumber = null,
        ?string $accountName = null,
        ?string $accountType = null
    ): ?int
    {
        $data = [
            'member_id' => $memberId,
            'fullname' => $fullname,
            'coop_no' => $coopNo,
            'loan_amount' => $loanAmount,
            'ip_figure' => $ipFigure,
            'loan_duration' => $loanDuration,
            'status' => 'pending'
        ];
        
        if ($purpose !== null) {
            $data['purpose'] = $purpose;
        }
        
        if ($additionalInfo !== null) {
            $data['additional_info'] = $additionalInfo;
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
        
        return Database::insert('loan_applications', $data);
    }
    
    /**
     * Update loan application status
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
        
        return Database::update('loan_applications', $data, ['id' => $id]) > 0;
    }
    
    /**
     * Approve loan application
     *
     * @param int $applicationId
     * @param string|null $comment
     * @return bool
     */
    public static function approveApplication(int $applicationId, ?string $comment = null): bool
    {
        $application = self::getApplicationById($applicationId);
        
        if (!$application || $application['status'] !== 'pending') {
            return false;
        }
        
        // Start transaction
        Database::getConnection()->beginTransaction();
        
        try {
            // Update application status
            $statusUpdated = self::updateApplicationStatus($applicationId, 'approved', $comment);
            
            if (!$statusUpdated) {
                throw new Exception('Failed to update application status');
            }
            
            // Calculate loan repayment details
            $repayment = Utility::calculateLoanRepayment(
                (float)$application['loan_amount'],
                (float)$application['ip_figure']
            );
            
            // Create or update loan details
            $loanCreated = self::createOrUpdate(
                (int)$application['member_id'],
                (float)$application['loan_amount'],
                $repayment['ip_no'],
                (float)$application['ip_figure'],
                $repayment['total_repayment'],
                $repayment['balance']
            );
            
            if (!$loanCreated) {
                throw new Exception('Failed to create loan details');
            }
            
            // Add transaction record
            $transactionCreated = Database::insert('transaction_history', [
                'member_id' => $application['member_id'],
                'transaction_type' => 'loan',
                'amount' => $application['loan_amount'],
                'description' => "Loan Application #{$applicationId} Approved"
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
            error_log("Loan approval error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reject loan application
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
     * Process loan repayment
     *
     * @param int $memberId
     * @param float $amount
     * @return bool
     */
    public static function processRepayment(int $memberId, float $amount): bool
    {
        $loan = self::getByMemberId($memberId);
        
        if (!$loan) {
            return false;
        }
        
        // Start transaction
        Database::getConnection()->beginTransaction();
        
        try {
            // Calculate new balance
            $newBalance = max(0, (float)$loan['balance'] - $amount);
            
            // Update loan balance
            $balanceUpdated = self::updateBalance($memberId, $newBalance);
            
            if (!$balanceUpdated) {
                throw new Exception('Failed to update loan balance');
            }
            
            // Add transaction record
            $transactionCreated = Database::insert('transaction_history', [
                'member_id' => $memberId,
                'transaction_type' => 'loan',
                'amount' => $amount,
                'description' => "Loan Repayment"
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
            error_log("Loan repayment error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process bulk loan repayments
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
            'upload_type' => 'loan',
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
} 