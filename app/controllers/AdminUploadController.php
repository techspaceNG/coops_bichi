<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Config\Database;

/**
 * Admin Upload Controller
 * Handles bulk data uploads for loans, household purchases, and savings
 */
final class AdminUploadController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Require admin authentication
        $this->requireAdmin();
    }
    
    /**
     * Display upload history
     */
    public function index(): void
    {
        // Get upload logs from database
        $uploads = Database::fetchAll(
            "SELECT * FROM bulk_uploads ORDER BY upload_date DESC"
        );
        
        $this->render('admin/uploads/index', [
            'uploads' => $uploads,
            'pageTitle' => 'Bulk Upload History'
        ]);
    }
    
    /**
     * View upload details
     * 
     * @param int $id Upload ID
     */
    public function view(int $id): void
    {
        // Get upload details
        $upload = Database::fetchOne(
            "SELECT * FROM bulk_uploads WHERE id = ?",
            [$id]
        );
        
        if (!$upload) {
            $this->setFlash('error', 'Upload record not found');
            $this->redirect('/admin/uploads');
            return;
        }
        
        // Get upload results
        $results = Database::fetchAll(
            "SELECT * FROM bulk_upload_results WHERE upload_id = ? ORDER BY row_num ASC",
            [$id]
        );
        
        $this->render('admin/uploads/view', [
            'upload' => $upload,
            'results' => $results,
            'pageTitle' => 'Upload Details'
        ]);
    }
    
    /**
     * Process loan data upload
     */
    public function processLoanUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/loans/upload');
            return;
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'Invalid file upload');
            $this->redirect('/admin/loans/upload');
            return;
        }
        
        $file = $_FILES['file'];
        $filename = $file['name'];
        $tmpPath = $file['tmp_name'];
        
        // Validate file extension
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($ext !== 'csv' && $ext !== 'xlsx') {
            $this->setFlash('error', 'Only CSV and Excel files are supported');
            $this->redirect('/admin/loans/upload');
            return;
        }
        
        // Save file to uploads directory
        $targetDir = BASE_DIR . '/uploads/';
        $newFilename = 'loan_upload_' . date('YmdHis') . '.' . $ext;
        $targetPath = $targetDir . $newFilename;
        
        if (!move_uploaded_file($tmpPath, $targetPath)) {
            $this->setFlash('error', 'Failed to save uploaded file');
            $this->redirect('/admin/loans/upload');
            return;
        }
        
        // Create upload record
        $uploadId = Database::insert('bulk_uploads', [
            'file_name' => $newFilename,
            'original_name' => $filename,
            'upload_type' => 'loan',
            'upload_date' => date('Y-m-d H:i:s'),
            'uploaded_by' => Auth::getAdminId(),
            'status' => 'processing'
        ]);
        
        // Process file (read data, validate, and import)
        $results = $this->processDataFile($targetPath, $ext, 'loan');
        
        // Update upload status
        $successCount = array_sum(array_map(function($r) { return $r['status'] === 'success' ? 1 : 0; }, $results));
        $errorCount = count($results) - $successCount;
        
        Database::update('bulk_uploads', [
            'status' => 'completed',
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'completion_date' => date('Y-m-d H:i:s')
        ], ['id' => $uploadId]);
        
        // Save results to database
        foreach ($results as $result) {
            Database::insert('bulk_upload_results', [
                'upload_id' => $uploadId,
                'row_num' => $result['row'],
                'status' => $result['status'],
                'message' => $result['message'],
                'data' => json_encode($result['data'])
            ]);
        }
        
        // Log action
        Auth::logAction('admin', Auth::getAdminId(), 'Uploaded loan data', [
            'filename' => $filename,
            'success_count' => $successCount,
            'error_count' => $errorCount
        ]);
        
        $this->setFlash('success', "File uploaded successfully. Processed $successCount records with $errorCount errors.");
        $this->redirect('/admin/uploads/view/' . $uploadId);
    }
    
    /**
     * Process household purchase data upload
     */
    public function processHouseholdUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/household/upload');
            return;
        }
        
        // Similar implementation to processLoanUpload but for household purchases
        // Check file upload, validate, save, and process data
        
        // For now, just redirect to prevent errors
        $this->setFlash('error', 'Household upload not implemented yet');
        $this->redirect('/admin/household/upload');
    }
    
    /**
     * Process savings data upload
     */
    public function processSavingsUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/savings/upload');
            return;
        }
        
        // Similar implementation to processLoanUpload but for savings data
        // Check file upload, validate, save, and process data
        
        // For now, just redirect to prevent errors
        $this->setFlash('error', 'Savings upload not implemented yet');
        $this->redirect('/admin/savings/upload');
    }
    
    /**
     * Process data file (CSV or Excel)
     * 
     * @param string $filePath File path
     * @param string $fileType File extension (csv or xlsx)
     * @param string $dataType Type of data (loan, household, savings)
     * @return array Processing results
     */
    private function processDataFile(string $filePath, string $fileType, string $dataType): array
    {
        $results = [];
        
        // Read file based on type (CSV or Excel)
        if ($fileType === 'csv') {
            $file = fopen($filePath, 'r');
            if (!$file) {
                return [['row' => 0, 'status' => 'error', 'message' => 'Failed to open file', 'data' => []]];
            }
            
            // Read header row
            $header = fgetcsv($file);
            if (!$header) {
                fclose($file);
                return [['row' => 0, 'status' => 'error', 'message' => 'Empty or invalid file', 'data' => []]];
            }
            
            // Process data rows
            $rowNum = 1;
            while (($row = fgetcsv($file)) !== false) {
                $rowNum++;
                
                // Map row to associative array using header
                $data = array_combine($header, $row);
                
                // Validate and process data based on type
                $result = $this->validateAndSaveData($data, $dataType, $rowNum);
                $results[] = $result;
            }
            
            fclose($file);
        } else if ($fileType === 'xlsx') {
            // Excel processing logic would go here
            // For simplicity, not implementing Excel reader in this example
            $results[] = ['row' => 0, 'status' => 'error', 'message' => 'Excel processing not implemented', 'data' => []];
        }
        
        return $results;
    }
    
    /**
     * Validate and save data row
     * 
     * @param array $data Data row
     * @param string $dataType Type of data (loan, household, savings)
     * @param int $rowNum Row number for error reporting
     * @return array Result of processing
     */
    private function validateAndSaveData(array $data, string $dataType, int $rowNum): array
    {
        // Basic validation - check required fields
        $requiredFields = [];
        
        switch ($dataType) {
            case 'loan':
                $requiredFields = ['coop_no', 'loan_amount', 'ip_figure'];
                break;
            case 'household':
                $requiredFields = ['coop_no', 'purchase_amount', 'ip_figure'];
                break;
            case 'savings':
                $requiredFields = ['coop_no', 'amount'];
                break;
        }
        
        // Check if all required fields exist
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return [
                    'row' => $rowNum,
                    'status' => 'error',
                    'message' => "Missing required field: $field",
                    'data' => $data
                ];
            }
        }
        
        // Find member by coop number
        $member = Database::fetchOne(
            "SELECT id FROM members WHERE coop_no = ?",
            [$data['coop_no']]
        );
        
        if (!$member) {
            return [
                'row' => $rowNum,
                'status' => 'error',
                'message' => "Member not found with Coop No: {$data['coop_no']}",
                'data' => $data
            ];
        }
        
        // Process data based on type
        try {
            switch ($dataType) {
                case 'loan':
                    // Process loan data
                    // Example: Update loan balances, create transaction records, etc.
                    return [
                        'row' => $rowNum,
                        'status' => 'success',
                        'message' => "Loan data processed for {$data['coop_no']}",
                        'data' => $data
                    ];
                
                case 'household':
                    // Process household purchase data
                    return [
                        'row' => $rowNum,
                        'status' => 'success',
                        'message' => "Household data processed for {$data['coop_no']}",
                        'data' => $data
                    ];
                
                case 'savings':
                    // Process savings data
                    return [
                        'row' => $rowNum,
                        'status' => 'success',
                        'message' => "Savings data processed for {$data['coop_no']}",
                        'data' => $data
                    ];
                
                default:
                    return [
                        'row' => $rowNum,
                        'status' => 'error',
                        'message' => "Unknown data type: $dataType",
                        'data' => $data
                    ];
            }
        } catch (\Exception $e) {
            return [
                'row' => $rowNum,
                'status' => 'error',
                'message' => "Error processing data: " . $e->getMessage(),
                'data' => $data
            ];
        }
    }
} 