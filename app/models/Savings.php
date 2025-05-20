<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

/**
 * Savings Model
 */
final class Savings
{
    public ?int $id = null;
    public int $member_id;
    public float $monthly_deduction = 0.00;
    public float $cumulative_amount = 0.00;
    public ?string $last_deduction_date = null;
    public string $created_at = '';
    public string $updated_at = '';
    
    /**
     * Database connection
     */
    private ?PDO $db = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    /**
     * Save savings to database (insert or update)
     *
     * @return bool True on success, false on failure
     */
    public function save(): bool
    {
        try {
            if ($this->id) {
                // Update existing savings
                $sql = "UPDATE savings SET 
                    member_id = :member_id,
                    monthly_deduction = :monthly_deduction,
                    cumulative_amount = :cumulative_amount,
                    last_deduction_date = :last_deduction_date,
                    updated_at = NOW()
                WHERE id = :id";
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            } else {
                // Insert new savings
                $sql = "INSERT INTO savings (
                    member_id, monthly_deduction, cumulative_amount, 
                    last_deduction_date, created_at, updated_at
                ) VALUES (
                    :member_id, :monthly_deduction, :cumulative_amount, 
                    :last_deduction_date, NOW(), NOW()
                )";
                
                $stmt = $this->db->prepare($sql);
            }
            
            // Bind parameters
            $stmt->bindValue(':member_id', $this->member_id, PDO::PARAM_INT);
            $stmt->bindValue(':monthly_deduction', $this->monthly_deduction, PDO::PARAM_STR);
            $stmt->bindValue(':cumulative_amount', $this->cumulative_amount, PDO::PARAM_STR);
            $stmt->bindValue(':last_deduction_date', $this->last_deduction_date, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            
            if (!$this->id && $result) {
                $this->id = (int)$this->db->lastInsertId();
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log('Error saving savings: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get savings details by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM savings WHERE id = ?", [$id]);
    }
    
    /**
     * Get savings details by member ID
     *
     * @param int $memberId
     * @return array|null
     */
    public static function getByMemberId(int $memberId): ?array
    {
        return Database::fetchOne("SELECT * FROM savings WHERE member_id = ?", [$memberId]);
    }
    
    /**
     * Get all savings details
     *
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getAll(int $page = 1, int $perPage = 10): array
    {
        $totalQuery = "SELECT COUNT(*) as count FROM savings";
        $totalResult = Database::fetchOne($totalQuery);
        $total = $totalResult ? (int)$totalResult['count'] : 0;
        
        $pagination = Utility::getPagination($total, $page, $perPage);
        
        $savings = Database::fetchAll(
            "SELECT s.*, m.coop_no, m.name 
            FROM savings s
            JOIN members m ON s.member_id = m.id
            ORDER BY s.created_at DESC LIMIT ? OFFSET ?",
            [$pagination['per_page'], $pagination['offset']]
        );
        
        return [
            'data' => $savings,
            'pagination' => $pagination
        ];
    }
    
    /**
     * Create or update savings details
     *
     * @param int $memberId
     * @param float $monthlyDeduction
     * @param float $cumulativeAmountSaved
     * @param float $grandTotalDeduction
     * @return bool
     */
    public static function createOrUpdate(int $memberId, float $monthlyDeduction, float $cumulativeAmountSaved, float $grandTotalDeduction): bool
    {
        $existingSavings = self::getByMemberId($memberId);
        
        $data = [
            'monthly_deduction' => $monthlyDeduction,
            'cumulative_amount_saved' => $cumulativeAmountSaved,
            'grand_total_deduction' => $grandTotalDeduction
        ];
        
        if ($existingSavings) {
            return Database::update('savings', $data, ['id' => $existingSavings['id']]) > 0;
        } else {
            $data['member_id'] = $memberId;
            return Database::insert('savings', $data) > 0;
        }
    }
    
    /**
     * Update monthly deduction
     *
     * @param int $memberId
     * @param float $monthlyDeduction
     * @return bool
     */
    public static function updateMonthlyDeduction(int $memberId, float $monthlyDeduction): bool
    {
        $existingSavings = self::getByMemberId($memberId);
        
        if (!$existingSavings) {
            // Create new savings record if it doesn't exist
            return self::createOrUpdate($memberId, $monthlyDeduction, 0, 0);
        }
        
        return Database::update('savings', ['monthly_deduction' => $monthlyDeduction], ['id' => $existingSavings['id']]) > 0;
    }
    
    /**
     * Process savings deduction
     *
     * @param int $memberId
     * @param float $amount
     * @param int|null $adminId Optional admin ID for tracking
     * @return bool
     */
    public static function processDeduction(int $memberId, float $amount, ?int $adminId = null): bool
    {
        // Start transaction
        Database::getConnection()->beginTransaction();
        
        try {
            // We don't need to update the savings table directly anymore
            // The database trigger 'after_savings_transaction_insert' will handle it
            
            // Add savings transaction record
            $transactionData = [
                'member_id' => $memberId,
                'transaction_type' => 'deposit',
                'amount' => $amount,
                'deduction_date' => date('Y-m-d'),
                'description' => "Savings Deduction",
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Add admin ID if provided
            if ($adminId !== null) {
                $transactionData['processed_by'] = $adminId;
            }
            
            $transactionCreated = Database::insert('savings_transactions', $transactionData);
            
            if (!$transactionCreated) {
                throw new \Exception('Failed to create savings transaction record');
            }
            
            // Add to transaction history
            $historyCreated = Database::insert('transaction_history', [
                'member_id' => $memberId,
                'transaction_type' => 'savings',
                'amount' => $amount,
                'description' => "Savings Deduction" . ($adminId ? " (by admin)" : ""),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$historyCreated) {
                throw new \Exception('Failed to create transaction history record');
            }
            
            // Commit transaction
            Database::getConnection()->commit();
            
            return true;
        } catch (\Exception $e) {
            // Rollback transaction on error
            Database::getConnection()->rollBack();
            error_log("Savings deduction error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process bulk savings deductions
     *
     * @param array $deductions Array of [member_id => amount] pairs
     * @param int $adminId
     * @param string $filename
     * @return array
     */
    public static function processBulkDeductions(array $deductions, int $adminId, string $filename): array
    {
        try {
        // Create bulk upload log
        $bulkUploadId = Database::insert('bulk_upload_logs', [
            'admin_id' => $adminId,
            'filename' => $filename,
            'upload_type' => 'savings',
            'status' => 'pending',
            'records_processed' => 0,
            'records_failed' => 0,
                'details' => json_encode(['started' => date('Y-m-d H:i:s')]),
                'created_at' => date('Y-m-d H:i:s')
        ]);
        
        if (!$bulkUploadId) {
                error_log('Failed to create bulk upload log');
            return [
                'success' => false,
                    'message' => 'Failed to create bulk upload log',
                    'processed' => 0,
                    'failed' => count($deductions)
            ];
        }
            
            error_log("Created bulk upload log with ID: $bulkUploadId");
        
        $processed = 0;
        $failed = 0;
        $failures = [];
        
        // Process each deduction
        foreach ($deductions as $memberId => $amount) {
                try {
                    // Check if the member exists
                    $member = Database::fetchOne("SELECT id, name, coop_no FROM members WHERE id = ?", [$memberId]);
                    
                    if (!$member) {
                        error_log("Member with ID $memberId not found");
                        $failed++;
                        $failures[] = "Member ID: $memberId, Amount: $amount, Error: Member not found";
                        continue;
                    }
                    
                    error_log("Processing deduction for member: " . json_encode($member));
                    
                    // Begin transaction for this member's deduction
                    Database::getConnection()->beginTransaction();
                    
                    try {
                        // Create savings transaction record
                        // The database trigger will handle updating the savings table
                        $transactionId = Database::insert('savings_transactions', [
                            'member_id' => $memberId,
                            'amount' => $amount,
                            'transaction_type' => 'deposit',
                            'deduction_date' => date('Y-m-d'),
                            'description' => 'Bulk Savings Deduction',
                            'processed_by' => $adminId,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        if (!$transactionId) {
                            throw new \Exception("Failed to create savings transaction for member $memberId");
                        }
                        
                        // Add to transaction history
                        $historyId = Database::insert('transaction_history', [
                            'member_id' => $memberId,
                            'transaction_type' => 'savings',
                            'amount' => $amount,
                            'description' => 'Savings Deduction - Bulk Upload',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        if (!$historyId) {
                            throw new \Exception("Failed to create transaction history for member $memberId");
                        }
                        
                        // Commit transaction
                        Database::getConnection()->commit();
                $processed++;
                        error_log("Successfully processed deduction for member $memberId");
                        
                    } catch (\Exception $innerEx) {
                        // Rollback transaction
                        Database::getConnection()->rollBack();
                        $failed++;
                        $failures[] = "Member ID: $memberId, Amount: $amount, Error: " . $innerEx->getMessage();
                        error_log("Error processing deduction for member $memberId: " . $innerEx->getMessage());
                    }
                    
                } catch (\Exception $ex) {
                $failed++;
                    $failures[] = "Member ID: $memberId, Amount: $amount, Error: " . $ex->getMessage();
                    error_log("Exception in deduction processing for member $memberId: " . $ex->getMessage());
                }
        }
        
        // Update bulk upload log
            $updated = Database::update('bulk_upload_logs', [
            'status' => 'completed',
            'records_processed' => $processed,
            'records_failed' => $failed,
                'details' => json_encode([
                    'failures' => $failures,
                    'completed_at' => date('Y-m-d H:i:s')
                ]),
                'completed_at' => date('Y-m-d H:i:s')
        ], ['id' => $bulkUploadId]);
            
            if (!$updated) {
                error_log("Failed to update bulk upload log with ID: $bulkUploadId");
            }
        
        return [
            'success' => true,
            'processed' => $processed,
            'failed' => $failed,
            'failures' => $failures
        ];
            
        } catch (\Exception $e) {
            error_log("Fatal error in processBulkDeductions: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => "Error: " . $e->getMessage(),
                'processed' => 0,
                'failed' => count($deductions)
            ];
        }
    }
    
    /**
     * Delete savings details
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        return Database::delete('savings', ['id' => $id]) > 0;
    }
    
    /**
     * Get total savings for a specific month and year
     *
     * @param int $month
     * @param int $year
     * @return float
     */
    public static function getTotalSavingsForMonth(int $month, int $year): float
    {
        $startDate = sprintf("%04d-%02d-01", $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $result = Database::fetchOne(
            "SELECT SUM(amount) as total FROM transaction_history 
            WHERE transaction_type = 'savings' 
            AND created_at BETWEEN ? AND ?",
            [$startDate, $endDate]
        );
        
        return $result && isset($result['total']) ? (float)$result['total'] : 0;
    }
    
    /**
     * Get monthly savings statistics for the current year
     *
     * @return array
     */
    public static function getMonthlySavingsStats(): array
    {
        $currentYear = (int)date('Y');
        $stats = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $total = self::getTotalSavingsForMonth($month, $currentYear);
            $stats[] = [
                'month' => date('F', mktime(0, 0, 0, $month, 1)),
                'total' => $total
            ];
        }
        
        return $stats;
    }
    
    /**
     * Get total savings for all members
     *
     * @return float
     */
    public static function getTotalSavings(): float
    {
        $result = Database::fetchOne("SELECT SUM(grand_total_deduction) as total FROM savings");
        return $result && isset($result['total']) ? (float)$result['total'] : 0;
    }
} 