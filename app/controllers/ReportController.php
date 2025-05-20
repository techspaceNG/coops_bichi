<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Config\Database;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Report Controller
 * Handles report generation for members
 */
final class ReportController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Require member authentication for member reports
        if (strpos($_SERVER['REQUEST_URI'], '/member/') === 0) {
            $this->requireMember();
        }
        
        // Require admin authentication for admin reports
        if (strpos($_SERVER['REQUEST_URI'], '/admin/') === 0) {
            $this->requireAdmin();
        }
    }
    
    /**
     * Generate savings report for member
     */
    public function memberSavingsReport(): void
    {
        // Get member ID
        $memberId = Auth::getMemberId();
        
        // Get member data
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE id = ?",
            [$memberId]
        );
        
        // Get savings transactions
        $transactions = Database::fetchAll(
            "SELECT * FROM savings_transactions 
            WHERE member_id = ? 
            ORDER BY transaction_date DESC",
            [$memberId]
        );
        
        // Generate Excel report
        $this->generateSavingsExcel($member, $transactions);
    }
    
    /**
     * Generate loan report for member
     */
    public function memberLoanReport(): void
    {
        // Get member ID
        $memberId = Auth::getMemberId();
        
        // Get member data
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE id = ?",
            [$memberId]
        );
        
        // Get loan data
        $loans = Database::fetchAll(
            "SELECT * FROM loans 
            WHERE member_id = ? 
            ORDER BY created_at DESC",
            [$memberId]
        );
        
        // Get loan payments
        $payments = Database::fetchAll(
            "SELECT * FROM loan_payments 
            WHERE member_id = ? 
            ORDER BY payment_date DESC",
            [$memberId]
        );
        
        // Generate Excel report
        $this->generateLoanExcel($member, $loans, $payments);
    }
    
    /**
     * Generate household purchases report for member
     */
    public function memberHouseholdReport(): void
    {
        // Get member ID
        $memberId = Auth::getMemberId();
        
        // Get member data
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE id = ?",
            [$memberId]
        );
        
        // Get household purchase data
        $purchases = Database::fetchAll(
            "SELECT * FROM household_purchases 
            WHERE member_id = ? 
            ORDER BY created_at DESC",
            [$memberId]
        );
        
        // Get purchase payments
        $payments = Database::fetchAll(
            "SELECT * FROM household_payments 
            WHERE member_id = ? 
            ORDER BY payment_date DESC",
            [$memberId]
        );
        
        // Generate Excel report
        $this->generateHouseholdExcel($member, $purchases, $payments);
    }
    
    /**
     * Generate transaction history report for member
     */
    public function memberTransactionReport(): void
    {
        // Get member ID
        $memberId = Auth::getMemberId();
        
        // Get member data
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE id = ?",
            [$memberId]
        );
        
        // Get all transactions (combined from different tables)
        $transactions = $this->getAllMemberTransactions($memberId);
        
        // Generate Excel report
        $this->generateTransactionExcel($member, $transactions);
    }
    
    /**
     * Generate savings report for admin
     */
    public function savings(): void
    {
        // Check if department filter is applied
        $departmentFilter = $_GET['department'] ?? null;
        
        // Prepare query
        $query = "SELECT m.*, SUM(st.amount) as total_savings 
                 FROM members m 
                 LEFT JOIN savings_transactions st ON m.id = st.member_id";
        
        $params = [];
        
        if ($departmentFilter) {
            $query .= " WHERE m.department_id = ?";
            $params[] = $departmentFilter;
        }
        
        $query .= " GROUP BY m.id ORDER BY m.department_id, m.name";
        
        // Get all members with savings data
        $membersData = Database::fetchAll($query, $params);
        
        // Generate Excel report
        $this->generateAdminSavingsExcel($membersData, $departmentFilter);
    }
    
    /**
     * Generate loan report for admin
     */
    public function loans(): void
    {
        // Check if status filter is applied
        $statusFilter = $_GET['status'] ?? null;
        
        // Prepare query
        $query = "SELECT l.*, m.name as member_name, 
                 m.coop_no, m.department_id
                 FROM loans l
                 JOIN members m ON l.member_id = m.id";
        
        $params = [];
        
        if ($statusFilter) {
            $query .= " WHERE l.status = ?";
            $params[] = $statusFilter;
        }
        
        $query .= " ORDER BY l.created_at DESC";
        
        // Get all loans
        $loans = Database::fetchAll($query, $params);
        
        // Generate Excel report
        $this->generateAdminLoanExcel($loans, $statusFilter);
    }
    
    /**
     * Generate household purchases report for admin
     */
    public function household(): void
    {
        // Check if status filter is applied
        $statusFilter = $_GET['status'] ?? null;
        
        // Prepare query
        $query = "SELECT h.*, m.name as member_name, 
                 m.coop_no, m.department_id
                 FROM household_purchases h
                 JOIN members m ON h.member_id = m.id";
        
        $params = [];
        
        if ($statusFilter) {
            $query .= " WHERE h.status = ?";
            $params[] = $statusFilter;
        }
        
        $query .= " ORDER BY h.created_at DESC";
        
        // Get all household purchases
        $purchases = Database::fetchAll($query, $params);
        
        // Generate Excel report
        $this->generateAdminHouseholdExcel($purchases, $statusFilter);
    }
    
    /**
     * Generate Excel for savings report
     * 
     * @param array $member Member data
     * @param array $transactions Savings transactions
     */
    private function generateSavingsExcel(array $member, array $transactions): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Savings Report')
            ->setSubject('Member Savings Report')
            ->setDescription('Savings report for ' . $member['first_name'] . ' ' . $member['last_name']);
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'SAVINGS REPORT');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add member information
        $sheet->setCellValue('A4', 'Member Name:');
        $sheet->setCellValue('B4', $member['first_name'] . ' ' . $member['last_name']);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        $sheet->setCellValue('A5', 'Coop Number:');
        $sheet->setCellValue('B5', $member['coop_no']);
        $sheet->getStyle('A5')->getFont()->setBold(true);
        
        $sheet->setCellValue('A6', 'Department:');
        $sheet->setCellValue('B6', $this->getDepartmentName($member['department_id']));
        $sheet->getStyle('A6')->getFont()->setBold(true);
        
        $sheet->setCellValue('D4', 'Current Balance:');
        $sheet->setCellValue('E4', '₦' . number_format($member['savings_balance'], 2));
        $sheet->getStyle('D4')->getFont()->setBold(true);
        
        $sheet->setCellValue('D5', 'Report Date:');
        $sheet->setCellValue('E5', date('Y-m-d'));
        $sheet->getStyle('D5')->getFont()->setBold(true);
        
        // Add table header
        $headerRow = 8;
        $sheet->setCellValue('A' . $headerRow, 'Date');
        $sheet->setCellValue('B' . $headerRow, 'Transaction Type');
        $sheet->setCellValue('C' . $headerRow, 'Description');
        $sheet->setCellValue('D' . $headerRow, 'Amount (₦)');
        $sheet->setCellValue('E' . $headerRow, 'Reference');
        $sheet->setCellValue('F' . $headerRow, 'Balance After (₦)');
        
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add transaction data
        $row = $headerRow + 1;
        $runningBalance = $member['savings_balance'];
        
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, date('Y-m-d', strtotime($transaction['transaction_date'])));
            $sheet->setCellValue('B' . $row, $transaction['transaction_type']);
            $sheet->setCellValue('C' . $row, $transaction['description']);
            $sheet->setCellValue('D' . $row, number_format($transaction['amount'], 2));
            $sheet->setCellValue('E' . $row, $transaction['reference']);
            
            // Calculate running balance (going backward from current balance)
            if ($transaction['transaction_type'] == 'deposit') {
                $runningBalance -= $transaction['amount'];
            } else {
                $runningBalance += $transaction['amount'];
            }
            
            $sheet->setCellValue('F' . $row, number_format($runningBalance, 2));
            
            $row++;
        }
        
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':F' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Savings_Report_' . $member['coop_no'] . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel for loan report
     * 
     * @param array $member Member data
     * @param array $loans Loan data
     * @param array $payments Loan payments
     */
    private function generateLoanExcel(array $member, array $loans, array $payments): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Loan Report')
            ->setSubject('Member Loan Report')
            ->setDescription('Loan report for ' . $member['first_name'] . ' ' . $member['last_name']);
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'LOAN REPORT');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add member information
        $sheet->setCellValue('A4', 'Member Name:');
        $sheet->setCellValue('B4', $member['first_name'] . ' ' . $member['last_name']);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        $sheet->setCellValue('A5', 'Coop Number:');
        $sheet->setCellValue('B5', $member['coop_no']);
        $sheet->getStyle('A5')->getFont()->setBold(true);
        
        $sheet->setCellValue('A6', 'Department:');
        $sheet->setCellValue('B6', $this->getDepartmentName($member['department_id']));
        $sheet->getStyle('A6')->getFont()->setBold(true);
        
        $sheet->setCellValue('E4', 'Report Date:');
        $sheet->setCellValue('F4', date('Y-m-d'));
        $sheet->getStyle('E4')->getFont()->setBold(true);
        
        // Add loan summary section
        $sheet->setCellValue('A8', 'LOAN SUMMARY');
        $sheet->mergeCells('A8:G8');
        $sheet->getStyle('A8')->getFont()->setBold(true);
        $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add loan summary table header
        $summaryHeaderRow = 10;
        $sheet->setCellValue('A' . $summaryHeaderRow, 'Loan ID');
        $sheet->setCellValue('B' . $summaryHeaderRow, 'Application Date');
        $sheet->setCellValue('C' . $summaryHeaderRow, 'Amount (₦)');
        $sheet->setCellValue('D' . $summaryHeaderRow, 'Interest (%)');
        $sheet->setCellValue('E' . $summaryHeaderRow, 'Status');
        $sheet->setCellValue('F' . $summaryHeaderRow, 'Balance (₦)');
        $sheet->setCellValue('G' . $summaryHeaderRow, 'Term (Months)');
        
        $sheet->getStyle('A' . $summaryHeaderRow . ':G' . $summaryHeaderRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $summaryHeaderRow . ':G' . $summaryHeaderRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add loan data
        $row = $summaryHeaderRow + 1;
        foreach ($loans as $loan) {
            $sheet->setCellValue('A' . $row, $loan['id']);
            $sheet->setCellValue('B' . $row, date('Y-m-d', strtotime($loan['created_at'])));
            $sheet->setCellValue('C' . $row, number_format((float)$loan['loan_amount'], 2));
            $sheet->setCellValue('D' . $row, $loan['interest_rate']);
            $sheet->setCellValue('E' . $row, ucfirst($loan['status']));
            $sheet->setCellValue('F' . $row, number_format((float)$loan['balance'], 2));
            $sheet->setCellValue('G' . $row, $loan['repayment_period']);
            
            // Set color for status
            switch ($loan['status']) {
                case 'approved':
                    $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'completed':
                    $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'declined':
                    $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                default:
                    $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange for pending
            }
            
            $row++;
        }
        
        // Apply borders to loan summary
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $summaryHeaderRow . ':G' . ($row - 1))->applyFromArray($styleArray);
        
        // Add payment history section
        $paymentHeaderRow = $row + 2;
        $sheet->setCellValue('A' . ($paymentHeaderRow - 1), 'PAYMENT HISTORY');
        $sheet->mergeCells('A' . ($paymentHeaderRow - 1) . ':F' . ($paymentHeaderRow - 1));
        $sheet->getStyle('A' . ($paymentHeaderRow - 1))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($paymentHeaderRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add payment history table header
        $sheet->setCellValue('A' . $paymentHeaderRow, 'Date');
        $sheet->setCellValue('B' . $paymentHeaderRow, 'Loan ID');
        $sheet->setCellValue('C' . $paymentHeaderRow, 'Amount (₦)');
        $sheet->setCellValue('D' . $paymentHeaderRow, 'Reference');
        $sheet->setCellValue('E' . $paymentHeaderRow, 'Method');
        $sheet->setCellValue('F' . $paymentHeaderRow, 'Notes');
        
        $sheet->getStyle('A' . $paymentHeaderRow . ':F' . $paymentHeaderRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $paymentHeaderRow . ':F' . $paymentHeaderRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add payment data
        $row = $paymentHeaderRow + 1;
        foreach ($payments as $payment) {
            $sheet->setCellValue('A' . $row, date('Y-m-d', strtotime($payment['payment_date'])));
            $sheet->setCellValue('B' . $row, $payment['loan_id']);
            $sheet->setCellValue('C' . $row, number_format((float)$payment['amount'], 2));
            $sheet->setCellValue('D' . $row, $payment['reference']);
            $sheet->setCellValue('E' . $row, $payment['payment_method'] ?? 'N/A');
            $sheet->setCellValue('F' . $row, $payment['notes'] ?? '');
            
            $row++;
        }
        
        // Apply borders to payment history
        $sheet->getStyle('A' . $paymentHeaderRow . ':F' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Loan_Report_' . $member['coop_no'] . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel for household purchase report
     * 
     * @param array $member Member data
     * @param array $purchases Household purchases
     * @param array $payments Purchase payments
     */
    private function generateHouseholdExcel(array $member, array $purchases, array $payments): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Household Purchases Report')
            ->setSubject('Member Household Purchases Report')
            ->setDescription('Household purchases report for ' . $member['first_name'] . ' ' . $member['last_name']);
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'HOUSEHOLD PURCHASES REPORT');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add member information
        $sheet->setCellValue('A4', 'Member Name:');
        $sheet->setCellValue('B4', $member['first_name'] . ' ' . $member['last_name']);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        $sheet->setCellValue('A5', 'Coop Number:');
        $sheet->setCellValue('B5', $member['coop_no']);
        $sheet->getStyle('A5')->getFont()->setBold(true);
        
        $sheet->setCellValue('A6', 'Department:');
        $sheet->setCellValue('B6', $this->getDepartmentName($member['department_id']));
        $sheet->getStyle('A6')->getFont()->setBold(true);
        
        $sheet->setCellValue('E4', 'Report Date:');
        $sheet->setCellValue('F4', date('Y-m-d'));
        $sheet->getStyle('E4')->getFont()->setBold(true);
        
        // Add purchases summary section
        $sheet->setCellValue('A8', 'HOUSEHOLD PURCHASES SUMMARY');
        $sheet->mergeCells('A8:H8');
        $sheet->getStyle('A8')->getFont()->setBold(true);
        $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add purchases summary table header
        $summaryHeaderRow = 10;
        $sheet->setCellValue('A' . $summaryHeaderRow, 'Purchase ID');
        $sheet->setCellValue('B' . $summaryHeaderRow, 'Date');
        $sheet->setCellValue('C' . $summaryHeaderRow, 'Item Description');
        $sheet->setCellValue('D' . $summaryHeaderRow, 'Quantity');
        $sheet->setCellValue('E' . $summaryHeaderRow, 'Unit Price (₦)');
        $sheet->setCellValue('F' . $summaryHeaderRow, 'Total Amount (₦)');
        $sheet->setCellValue('G' . $summaryHeaderRow, 'Status');
        $sheet->setCellValue('H' . $summaryHeaderRow, 'Balance (₦)');
        
        $sheet->getStyle('A' . $summaryHeaderRow . ':H' . $summaryHeaderRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $summaryHeaderRow . ':H' . $summaryHeaderRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add purchases data
        $row = $summaryHeaderRow + 1;
        foreach ($purchases as $purchase) {
            $sheet->setCellValue('A' . $row, $purchase['id']);
            $sheet->setCellValue('B' . $row, date('Y-m-d', strtotime($purchase['created_at'])));
            $sheet->setCellValue('C' . $row, $purchase['item_description']);
            $sheet->setCellValue('D' . $row, $purchase['quantity']);
            $sheet->setCellValue('E' . $row, number_format((float)$purchase['unit_price'], 2));
            $sheet->setCellValue('F' . $row, number_format((float)$purchase['total_amount'], 2));
            $sheet->setCellValue('G' . $row, ucfirst($purchase['status']));
            $sheet->setCellValue('H' . $row, number_format((float)$purchase['balance'], 2));
            
            // Set color for status
            switch ($purchase['status']) {
                case 'approved':
                    $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'completed':
                    $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'declined':
                    $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                default:
                    $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange for pending
            }
            
            $row++;
        }
        
        // Apply borders to purchases summary
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $summaryHeaderRow . ':H' . ($row - 1))->applyFromArray($styleArray);
        
        // Add payment history section
        $paymentHeaderRow = $row + 2;
        $sheet->setCellValue('A' . ($paymentHeaderRow - 1), 'PAYMENT HISTORY');
        $sheet->mergeCells('A' . ($paymentHeaderRow - 1) . ':F' . ($paymentHeaderRow - 1));
        $sheet->getStyle('A' . ($paymentHeaderRow - 1))->getFont()->setBold(true);
        $sheet->getStyle('A' . ($paymentHeaderRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add payment history table header
        $sheet->setCellValue('A' . $paymentHeaderRow, 'Date');
        $sheet->setCellValue('B' . $paymentHeaderRow, 'Purchase ID');
        $sheet->setCellValue('C' . $paymentHeaderRow, 'Amount (₦)');
        $sheet->setCellValue('D' . $paymentHeaderRow, 'Reference');
        $sheet->setCellValue('E' . $paymentHeaderRow, 'Method');
        $sheet->setCellValue('F' . $paymentHeaderRow, 'Notes');
        
        $sheet->getStyle('A' . $paymentHeaderRow . ':F' . $paymentHeaderRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $paymentHeaderRow . ':F' . $paymentHeaderRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add payment data
        $row = $paymentHeaderRow + 1;
        foreach ($payments as $payment) {
            $sheet->setCellValue('A' . $row, date('Y-m-d', strtotime($payment['payment_date'])));
            $sheet->setCellValue('B' . $row, $payment['purchase_id']);
            $sheet->setCellValue('C' . $row, number_format((float)$payment['amount'], 2));
            $sheet->setCellValue('D' . $row, $payment['reference']);
            $sheet->setCellValue('E' . $row, $payment['payment_method'] ?? 'N/A');
            $sheet->setCellValue('F' . $row, $payment['notes'] ?? '');
            
            $row++;
        }
        
        // Apply borders to payment history
        $sheet->getStyle('A' . $paymentHeaderRow . ':F' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Household_Report_' . $member['coop_no'] . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel for transaction history report
     * 
     * @param array $member Member data
     * @param array $transactions All transactions
     */
    private function generateTransactionExcel(array $member, array $transactions): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Transaction History Report')
            ->setSubject('Member Transaction History')
            ->setDescription('Transaction history report for ' . $member['first_name'] . ' ' . $member['last_name']);
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'TRANSACTION HISTORY REPORT');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add member information
        $sheet->setCellValue('A4', 'Member Name:');
        $sheet->setCellValue('B4', $member['first_name'] . ' ' . $member['last_name']);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        $sheet->setCellValue('A5', 'Coop Number:');
        $sheet->setCellValue('B5', $member['coop_no']);
        $sheet->getStyle('A5')->getFont()->setBold(true);
        
        $sheet->setCellValue('A6', 'Department:');
        $sheet->setCellValue('B6', $this->getDepartmentName($member['department_id']));
        $sheet->getStyle('A6')->getFont()->setBold(true);
        
        $sheet->setCellValue('E4', 'Current Savings:');
        $sheet->setCellValue('F4', '₦' . number_format((float)$member['savings_balance'], 2));
        $sheet->getStyle('E4')->getFont()->setBold(true);
        
        $sheet->setCellValue('E5', 'Report Date:');
        $sheet->setCellValue('F5', date('Y-m-d'));
        $sheet->getStyle('E5')->getFont()->setBold(true);
        
        // Add table header
        $headerRow = 8;
        $sheet->setCellValue('A' . $headerRow, 'Date');
        $sheet->setCellValue('B' . $headerRow, 'Category');
        $sheet->setCellValue('C' . $headerRow, 'Type');
        $sheet->setCellValue('D' . $headerRow, 'Description');
        $sheet->setCellValue('E' . $headerRow, 'Amount (₦)');
        $sheet->setCellValue('F' . $headerRow, 'Reference');
        
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add transaction data
        $row = $headerRow + 1;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, date('Y-m-d', strtotime($transaction['date'])));
            $sheet->setCellValue('B' . $row, $transaction['category']);
            $sheet->setCellValue('C' . $row, ucfirst($transaction['type']));
            $sheet->setCellValue('D' . $row, $transaction['description']);
            $sheet->setCellValue('E' . $row, number_format((float)$transaction['amount'], 2));
            $sheet->setCellValue('F' . $row, $transaction['reference']);
            
            // Color-code different categories
            switch ($transaction['category']) {
                case 'Savings':
                    $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'Loan':
                    $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'Household':
                    $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('800080'); // Purple
                    break;
                default:
                    $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('000000'); // Black
            }
            
            $row++;
        }
        
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':F' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Transaction_History_' . $member['coop_no'] . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel for admin savings report
     * 
     * @param array $membersData Members with savings data
     * @param string|null $departmentFilter Department filter applied
     */
    private function generateAdminSavingsExcel(array $membersData, ?string $departmentFilter): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Savings Report')
            ->setSubject('Members Savings Report')
            ->setDescription('Savings report for all members');
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'MEMBERS SAVINGS REPORT');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add filter information if applied
        if ($departmentFilter) {
            $sheet->setCellValue('A3', 'Department Filter: ' . $departmentFilter);
            $sheet->mergeCells('A3:H3');
            $sheet->getStyle('A3')->getFont()->setBold(true);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        
        // Add table header
        $headerRow = $departmentFilter ? 5 : 4;
        $sheet->setCellValue('A' . $headerRow, 'Member ID');
        $sheet->setCellValue('B' . $headerRow, 'Coop Number');
        $sheet->setCellValue('C' . $headerRow, 'Name');
        $sheet->setCellValue('D' . $headerRow, 'Email');
        $sheet->setCellValue('E' . $headerRow, 'Phone');
        $sheet->setCellValue('F' . $headerRow, 'Department');
        $sheet->setCellValue('G' . $headerRow, 'Join Date');
        $sheet->setCellValue('H' . $headerRow, 'Savings Balance');
        
        $sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add data rows
        $row = $headerRow + 1;
        foreach ($membersData as $member) {
            $sheet->setCellValue('A' . $row, $member['id']);
            $sheet->setCellValue('B' . $row, $member['coop_no']);
            $sheet->setCellValue('C' . $row, $member['name']);
            $sheet->setCellValue('D' . $row, $member['email']);
            $sheet->setCellValue('E' . $row, $member['phone']);
            $sheet->setCellValue('F' . $row, $this->getDepartmentName($member['department_id']));
            $sheet->setCellValue('G' . $row, date('Y-m-d', strtotime($member['created_at'])));
            $sheet->setCellValue('H' . $row, '₦' . number_format((float)$member['total_savings'], 2));
            
            $row++;
        }
        
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':H' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        $filename = 'Savings_Report_All_Members';
        if ($departmentFilter) {
            $filename = 'Savings_Report_' . str_replace(' ', '_', $departmentFilter);
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel for admin loan report
     * 
     * @param array $loans All loans
     * @param string|null $statusFilter Status filter applied
     */
    private function generateAdminLoanExcel(array $loans, ?string $statusFilter): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Loan Report')
            ->setSubject('Loan Report')
            ->setDescription('Loan report for all members');
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'LOANS REPORT');
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add filter information if applied
        if ($statusFilter) {
            $sheet->setCellValue('A3', 'Status Filter: ' . ucfirst($statusFilter));
            $sheet->mergeCells('A3:M3');
            $sheet->getStyle('A3')->getFont()->setBold(true);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        
        // Add table header
        $headerRow = $statusFilter ? 5 : 4;
        $sheet->setCellValue('A' . $headerRow, 'Loan ID');
        $sheet->setCellValue('B' . $headerRow, 'Created Date');
        $sheet->setCellValue('C' . $headerRow, 'Member No');
        $sheet->setCellValue('D' . $headerRow, 'Member Name');
        $sheet->setCellValue('E' . $headerRow, 'Department');
        $sheet->setCellValue('F' . $headerRow, 'Loan Amount');
        $sheet->setCellValue('G' . $headerRow, 'Interest Rate (%)');
        $sheet->setCellValue('H' . $headerRow, 'Term (Months)');
        $sheet->setCellValue('I' . $headerRow, 'Status');
        $sheet->setCellValue('J' . $headerRow, 'Approval Date');
        $sheet->setCellValue('K' . $headerRow, 'Purpose');
        $sheet->setCellValue('L' . $headerRow, 'Start Date');
        $sheet->setCellValue('M' . $headerRow, 'Balance');
        
        $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add data rows
        $row = $headerRow + 1;
        foreach ($loans as $loan) {
            $sheet->setCellValue('A' . $row, $loan['id']);
            $sheet->setCellValue('B' . $row, date('Y-m-d', strtotime($loan['created_at'])));
            $sheet->setCellValue('C' . $row, $loan['coop_no']);
            $sheet->setCellValue('D' . $row, $loan['member_name']);
            $sheet->setCellValue('E' . $row, $this->getDepartmentName($loan['department_id']));
            $sheet->setCellValue('F' . $row, '₦' . number_format((float)$loan['loan_amount'], 2));
            $sheet->setCellValue('G' . $row, $loan['interest_rate']);
            $sheet->setCellValue('H' . $row, $loan['repayment_period']);
            $sheet->setCellValue('I' . $row, ucfirst($loan['status']));
            $sheet->setCellValue('J' . $row, $loan['approval_date'] ? date('Y-m-d', strtotime($loan['approval_date'])) : 'N/A');
            $sheet->setCellValue('K' . $row, $loan['purpose'] ?? 'N/A');
            $sheet->setCellValue('L' . $row, $loan['start_date'] ? date('Y-m-d', strtotime($loan['start_date'])) : 'N/A');
            $sheet->setCellValue('M' . $row, '₦' . number_format((float)$loan['balance'], 2));
            
            // Set color for status
            switch ($loan['status']) {
                case 'approved':
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'completed':
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'declined':
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                default:
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange for pending
            }
            
            $row++;
        }
        
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':M' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        $filename = 'Loan_Report_All';
        if ($statusFilter) {
            $filename = 'Loan_Report_' . ucfirst($statusFilter);
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate Excel for admin household purchase report
     * 
     * @param array $purchases All purchases
     * @param string|null $statusFilter Status filter applied
     */
    private function generateAdminHouseholdExcel(array $purchases, ?string $statusFilter): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Household Purchases Report')
            ->setSubject('Household Purchases Report')
            ->setDescription('Household purchases report for all members');
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'HOUSEHOLD PURCHASES REPORT');
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add filter information if applied
        if ($statusFilter) {
            $sheet->setCellValue('A3', 'Status Filter: ' . ucfirst($statusFilter));
            $sheet->mergeCells('A3:M3');
            $sheet->getStyle('A3')->getFont()->setBold(true);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        
        // Add table header
        $headerRow = $statusFilter ? 5 : 4;
        $sheet->setCellValue('A' . $headerRow, 'Purchase ID');
        $sheet->setCellValue('B' . $headerRow, 'Created Date');
        $sheet->setCellValue('C' . $headerRow, 'Member No');
        $sheet->setCellValue('D' . $headerRow, 'Member Name');
        $sheet->setCellValue('E' . $headerRow, 'Department');
        $sheet->setCellValue('F' . $headerRow, 'Item Description');
        $sheet->setCellValue('G' . $headerRow, 'Quantity');
        $sheet->setCellValue('H' . $headerRow, 'Unit Price');
        $sheet->setCellValue('I' . $headerRow, 'Total Amount');
        $sheet->setCellValue('J' . $headerRow, 'Status');
        $sheet->setCellValue('K' . $headerRow, 'Approval Date');
        $sheet->setCellValue('L' . $headerRow, 'Amount Paid');
        $sheet->setCellValue('M' . $headerRow, 'Balance');
        
        $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $headerRow . ':M' . $headerRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Add data rows
        $row = $headerRow + 1;
        foreach ($purchases as $purchase) {
            $sheet->setCellValue('A' . $row, $purchase['id']);
            $sheet->setCellValue('B' . $row, date('Y-m-d', strtotime($purchase['created_at'])));
            $sheet->setCellValue('C' . $row, $purchase['coop_no']);
            $sheet->setCellValue('D' . $row, $purchase['member_name']);
            $sheet->setCellValue('E' . $row, $this->getDepartmentName($purchase['department_id']));
            $sheet->setCellValue('F' . $row, $purchase['item_description']);
            $sheet->setCellValue('G' . $row, $purchase['quantity']);
            $sheet->setCellValue('H' . $row, '₦' . number_format((float)$purchase['unit_price'], 2));
            $sheet->setCellValue('I' . $row, '₦' . number_format((float)$purchase['total_amount'], 2));
            $sheet->setCellValue('J' . $row, ucfirst($purchase['status']));
            $sheet->setCellValue('K' . $row, $purchase['approval_date'] ? date('Y-m-d', strtotime($purchase['approval_date'])) : 'N/A');
            $sheet->setCellValue('L' . $row, '₦' . number_format((float)$purchase['amount_paid'], 2));
            $sheet->setCellValue('M' . $row, '₦' . number_format((float)$purchase['balance'], 2));
            
            // Set color for status
            switch ($purchase['status']) {
                case 'approved':
                    $sheet->getStyle('J' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'completed':
                    $sheet->getStyle('J' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'declined':
                    $sheet->getStyle('J' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                default:
                    $sheet->getStyle('J' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange for pending
            }
            
            $row++;
        }
        
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':M' . ($row - 1))->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        $filename = 'Household_Report_All';
        if ($statusFilter) {
            $filename = 'Household_Report_' . ucfirst($statusFilter);
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Get all transactions for a member from different sources
     * 
     * @param int $memberId Member ID
     * @return array Combined transaction history
     */
    private function getAllMemberTransactions(int $memberId): array
    {
        // Get savings transactions
        $savingsTransactions = Database::fetchAll(
            "SELECT 
                transaction_date as date, 
                'Savings' as category,
                transaction_type as type,
                description,
                amount,
                reference
            FROM savings_transactions 
            WHERE member_id = ?",
            [$memberId]
        );
        
        // Get loan payments
        $loanPayments = Database::fetchAll(
            "SELECT 
                payment_date as date, 
                'Loan' as category,
                'payment' as type,
                CONCAT('Payment for loan #', loan_id) as description,
                amount,
                reference
            FROM loan_payments 
            WHERE member_id = ?",
            [$memberId]
        );
        
        // Get household purchase payments
        $householdPayments = Database::fetchAll(
            "SELECT 
                payment_date as date, 
                'Household' as category,
                'payment' as type,
                CONCAT('Payment for purchase #', purchase_id) as description,
                amount,
                reference
            FROM household_payments 
            WHERE member_id = ?",
            [$memberId]
        );
        
        // Combine all transactions
        $allTransactions = array_merge($savingsTransactions, $loanPayments, $householdPayments);
        
        // Sort by date (descending)
        usort($allTransactions, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $allTransactions;
    }
    
    /**
     * Display reports dashboard for admin
     */
    public function index(): void
    {
        // Require admin authentication
        $this->requireAdmin();
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = \App\Core\Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Get some basic stats for the reports dashboard
        $stats = [
            'total_members' => \App\Core\Database::fetchOne("SELECT COUNT(*) as count FROM members")['count'] ?? 0,
            'total_loans' => \App\Core\Database::fetchOne("SELECT COUNT(*) as count FROM loans")['count'] ?? 0,
            'total_savings' => \App\Core\Database::fetchOne("SELECT SUM(cumulative_amount) as total FROM savings")['total'] ?? 0,
            'total_household' => \App\Core\Database::fetchOne("SELECT COUNT(*) as count FROM household_purchases")['count'] ?? 0
        ];
        
        // Render the reports dashboard view
        $this->renderAdmin('admin/reports/index', [
            'pageTitle' => 'Reports Dashboard',
            'current_page' => 'reports',
            'admin' => $admin,
            'stats' => $stats
        ]);
    }
    
    /**
     * Get department name from ID
     * 
     * @param int|null $departmentId Department ID
     * @return string Department name or 'N/A' if not found
     */
    private function getDepartmentName(?int $departmentId): string 
    {
        if (!$departmentId) {
            return 'N/A';
        }
        
        $department = \App\Core\Database::fetchOne(
            "SELECT name FROM departments WHERE id = ?",
            [$departmentId]
        );
        
        return $department ? $department['name'] : 'N/A';
    }
    
    /**
     * Generate financial report for admin
     */
    public function financial(): void
    {
        // Check if time period filter is applied
        $periodFilter = $_GET['period'] ?? 'monthly'; // Options: monthly, quarterly, yearly
        $yearFilter = $_GET['year'] ?? date('Y');
        
        // Get financial data based on period
        $savingsData = $this->getFinancialData('savings_transactions', $periodFilter, $yearFilter);
        $loansData = $this->getFinancialData('loan_repayments', $periodFilter, $yearFilter);
        $householdData = $this->getFinancialData('household_repayments', $periodFilter, $yearFilter);
        
        // Combine data for report
        $financialData = [
            'period' => $periodFilter,
            'year' => $yearFilter,
            'savings' => $savingsData,
            'loans' => $loansData,
            'household' => $householdData
        ];
        
        // Generate Excel report
        $this->generateFinancialExcel($financialData);
    }
    
    /**
     * Get financial data aggregated by period
     * 
     * @param string $table Table to query
     * @param string $period Period type (monthly, quarterly, yearly)
     * @param string $year Year to filter
     * @return array Aggregated financial data
     */
    private function getFinancialData(string $table, string $period, string $year): array
    {
        // Determine the correct date field based on the table
        $dateField = '';
        switch ($table) {
            case 'savings_transactions':
                $dateField = 'deduction_date';
                break;
            case 'loan_payments':
                $table = 'loan_repayments'; // Correct the table name
                $dateField = 'payment_date';
                break;
            case 'household_payments':
                $table = 'household_repayments'; // Correct the table name
                $dateField = 'payment_date';
                break;
            default:
                $dateField = 'created_at';
        }
        
        $baseQuery = "SELECT ";
        
        if ($period === 'monthly') {
            $baseQuery .= "MONTH($dateField) as period, ";
        } elseif ($period === 'quarterly') {
            $baseQuery .= "QUARTER($dateField) as period, ";
        } else {
            $baseQuery .= "YEAR($dateField) as period, ";
        }
        
        $baseQuery .= "SUM(amount) as total FROM $table WHERE YEAR($dateField) = ? ";
        
        if ($period === 'monthly') {
            $baseQuery .= "GROUP BY MONTH($dateField) ORDER BY MONTH($dateField)";
        } elseif ($period === 'quarterly') {
            $baseQuery .= "GROUP BY QUARTER($dateField) ORDER BY QUARTER($dateField)";
        } else {
            $baseQuery .= "GROUP BY YEAR($dateField) ORDER BY YEAR($dateField)";
        }
        
        return Database::fetchAll($baseQuery, [$year]);
    }
    
    /**
     * Generate Excel for financial report
     * 
     * @param array $financialData Combined financial data
     */
    private function generateFinancialExcel(array $financialData): void
    {
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('FCET Bichi COOPS')
            ->setLastModifiedBy('FCET Bichi COOPS')
            ->setTitle('Financial Report')
            ->setSubject('Cooperative Financial Report')
            ->setDescription('Financial report for ' . $financialData['period'] . ' ' . $financialData['year']);
        
        // Add header
        $sheet->setCellValue('A1', 'FCET BICHI STAFF MULTIPURPOSE COOPERATIVE SOCIETY');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'FINANCIAL REPORT - ' . strtoupper($financialData['period']) . ' ' . $financialData['year']);
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add table headers
        $sheet->setCellValue('A4', 'Period');
        $sheet->setCellValue('B4', 'Savings (₦)');
        $sheet->setCellValue('C4', 'Loan Payments (₦)');
        $sheet->setCellValue('D4', 'Household Payments (₦)');
        $sheet->setCellValue('E4', 'Total (₦)');
        
        $sheet->getStyle('A4:E4')->getFont()->setBold(true);
        $sheet->getStyle('A4:E4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');
        
        // Format period names
        $periodNames = [];
        if ($financialData['period'] === 'monthly') {
            $periodNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        } elseif ($financialData['period'] === 'quarterly') {
            $periodNames = ['Q1', 'Q2', 'Q3', 'Q4'];
        } else {
            // Just use the year
            $periodNames = [$financialData['year']];
        }
        
        // Process data for each period
        $row = 5;
        $totalSavings = 0;
        $totalLoans = 0;
        $totalHousehold = 0;
        
        foreach ($periodNames as $index => $periodName) {
            $periodNum = $index + 1;
            
            // Get amounts for this period
            $savingsAmount = 0;
            foreach ($financialData['savings'] as $savingsItem) {
                if ((int)$savingsItem['period'] === $periodNum) {
                    $savingsAmount = (float)$savingsItem['total'];
                    break;
                }
            }
            
            $loansAmount = 0;
            foreach ($financialData['loans'] as $loansItem) {
                if ((int)$loansItem['period'] === $periodNum) {
                    $loansAmount = (float)$loansItem['total'];
                    break;
                }
            }
            
            $householdAmount = 0;
            foreach ($financialData['household'] as $householdItem) {
                if ((int)$householdItem['period'] === $periodNum) {
                    $householdAmount = (float)$householdItem['total'];
                    break;
                }
            }
            
            // Calculate row total
            $rowTotal = $savingsAmount + $loansAmount + $householdAmount;
            
            // Add to running totals
            $totalSavings += $savingsAmount;
            $totalLoans += $loansAmount;
            $totalHousehold += $householdAmount;
            
            // Add data row
            $sheet->setCellValue('A' . $row, $periodName);
            $sheet->setCellValue('B' . $row, number_format($savingsAmount, 2));
            $sheet->setCellValue('C' . $row, number_format($loansAmount, 2));
            $sheet->setCellValue('D' . $row, number_format($householdAmount, 2));
            $sheet->setCellValue('E' . $row, number_format($rowTotal, 2));
            
            $row++;
        }
        
        // Add totals row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, number_format((float)$totalSavings, 2));
        $sheet->setCellValue('C' . $row, number_format((float)$totalLoans, 2));
        $sheet->setCellValue('D' . $row, number_format((float)$totalHousehold, 2));
        $sheet->setCellValue('E' . $row, number_format((float)($totalSavings + $totalLoans + $totalHousehold), 2));
        
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A4:E' . $row)->applyFromArray($styleArray);
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set the header for download
        $filename = 'Financial_Report_' . ucfirst($financialData['period']) . '_' . $financialData['year'];
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
} 