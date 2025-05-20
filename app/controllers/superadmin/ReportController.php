<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * ReportController for Superadmin
 * Handles generating various reports
 */
final class ReportController extends AbstractController
{
    /**
     * Display reports dashboard with options for different report types
     */
    public function index(): void
    {
        // Get departments for filter dropdowns
        $departments = Database::fetchAll("SELECT id, name FROM departments ORDER BY name");
        
        View::renderSuperAdmin('superadmin/reports/index', [
            'departments' => $departments,
            'title' => 'Reports Dashboard',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
                ['label' => 'Reports', 'url' => '']
            ]
        ]);
    }
    
    /**
     * Generate member report based on filters
     */
    public function memberReport(): void
    {
        // Get and sanitize filters
        $departmentId = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
        $status = htmlspecialchars((string)($_GET['status'] ?? ''), ENT_QUOTES, 'UTF-8');
        $joinDateFrom = htmlspecialchars((string)($_GET['join_date_from'] ?? ''), ENT_QUOTES, 'UTF-8');
        $joinDateTo = htmlspecialchars((string)($_GET['join_date_to'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Build query
        $query = "SELECT 
                    m.id, 
                    m.coop_no, 
                    m.name, 
                    m.email, 
                    m.phone, 
                    m.created_at as join_date,
                    m.is_active,
                    d.name as department_name,
                    (SELECT 
                        (SUM(CASE WHEN transaction_type = 'deposit' THEN amount ELSE 0 END) - 
                         SUM(CASE WHEN transaction_type = 'withdrawal' THEN amount ELSE 0 END)) 
                     FROM savings_transactions 
                     WHERE member_id = m.id) as savings_balance,
                    (SELECT COUNT(*) FROM loans WHERE member_id = m.id) as loans_count,
                    (SELECT COUNT(*) FROM household_purchases WHERE member_id = m.id) as household_count
                FROM members m
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        $conditions = [];
        
        // Add conditions based on filters
        if ($departmentId) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($status !== null && $status !== '') {
            $isActive = ($status === 'active') ? 1 : 0;
            $conditions[] = "m.is_active = ?";
            $params[] = $isActive;
        }
        
        if ($joinDateFrom) {
            $conditions[] = "m.created_at >= ?";
            $params[] = $joinDateFrom;
        }
        
        if ($joinDateTo) {
            $conditions[] = "m.created_at <= ?";
            $params[] = $joinDateTo;
        }
        
        // Add conditions to query
        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }
        
        $query .= " ORDER BY m.name";
        
        // Fetch members
        $members = Database::fetchAll($query, $params);
        
        // Check if PhpSpreadsheet is available, display error if not
        $this->checkPhpSpreadsheetAvailability();
        
        // Generate Excel file
        $this->generateMemberExcelReport($members);
    }
    
    /**
     * Generate savings report based on filters
     */
    public function savingsReport(): void
    {
        // Get and sanitize filters
        $departmentId = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
        $dateFrom = htmlspecialchars((string)($_GET['date_from'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateTo = htmlspecialchars((string)($_GET['date_to'] ?? ''), ENT_QUOTES, 'UTF-8');
        $transactionType = htmlspecialchars((string)($_GET['transaction_type'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Build query
        $query = "SELECT 
                    st.id,
                    st.transaction_date,
                    st.transaction_type,
                    st.amount,
                    st.description,
                    m.coop_no,
                    m.name as member_name,
                    d.name as department_name
                FROM savings_transactions st
                JOIN members m ON st.member_id = m.id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        $conditions = [];
        
        // Add conditions based on filters
        if ($departmentId) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($dateFrom) {
            $conditions[] = "st.transaction_date >= ?";
            $params[] = $dateFrom;
        }
        
        if ($dateTo) {
            $conditions[] = "st.transaction_date <= ?";
            $params[] = $dateTo;
        }
        
        if ($transactionType && in_array($transactionType, ['deposit', 'withdrawal'])) {
            $conditions[] = "st.transaction_type = ?";
            $params[] = $transactionType;
        }
        
        // Add conditions to query
        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }
        
        $query .= " ORDER BY st.transaction_date DESC";
        
        // Fetch savings transactions
        $transactions = Database::fetchAll($query, $params);
        
        // Check if PhpSpreadsheet is available, display error if not
        $this->checkPhpSpreadsheetAvailability();
        
        // Generate Excel file
        $this->generateSavingsExcelReport($transactions);
    }
    
    /**
     * Generate loan report based on filters
     */
    public function loanReport(): void
    {
        // Get and sanitize filters
        $departmentId = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
        $status = htmlspecialchars((string)($_GET['status'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateFrom = htmlspecialchars((string)($_GET['date_from'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateTo = htmlspecialchars((string)($_GET['date_to'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Build query
        $query = "SELECT 
                    l.id,
                    l.created_at,
                    l.amount,
                    l.interest_rate,
                    l.term_months,
                    l.status,
                    l.approval_date,
                    l.description,
                    m.coop_no,
                    m.name as member_name,
                    d.name as department_name,
                    (SELECT SUM(amount) FROM loan_repayments WHERE loan_id = l.id) as paid_amount
                FROM loans l
                JOIN members m ON l.member_id = m.id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        $conditions = [];
        
        // Add conditions based on filters
        if ($departmentId) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($status && in_array($status, ['pending', 'approved', 'active', 'completed', 'rejected'])) {
            $conditions[] = "l.status = ?";
            $params[] = $status;
        }
        
        if ($dateFrom) {
            $conditions[] = "l.created_at >= ?";
            $params[] = $dateFrom;
        }
        
        if ($dateTo) {
            $conditions[] = "l.created_at <= ?";
            $params[] = $dateTo;
        }
        
        // Add conditions to query
        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }
        
        $query .= " ORDER BY l.created_at DESC";
        
        // Fetch loans
        $loans = Database::fetchAll($query, $params);
        
        // Calculate remaining balance
        foreach ($loans as &$loan) {
            $loan['paid_amount'] = $loan['paid_amount'] ?? 0;
            $loan['remaining_balance'] = $loan['amount'] - $loan['paid_amount'];
        }
        
        // Check if PhpSpreadsheet is available, display error if not
        $this->checkPhpSpreadsheetAvailability();
        
        // Generate Excel file
        $this->generateLoanExcelReport($loans);
    }
    
    /**
     * Generate household purchases report based on filters
     */
    public function householdReport(): void
    {
        // Get and sanitize filters
        $departmentId = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
        $status = htmlspecialchars((string)($_GET['status'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateFrom = htmlspecialchars((string)($_GET['date_from'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateTo = htmlspecialchars((string)($_GET['date_to'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Build query
        $query = "SELECT 
                    h.id,
                    h.purchase_date,
                    h.total_amount,
                    h.down_payment,
                    h.status,
                    h.description,
                    m.coop_no,
                    m.name as member_name,
                    d.name as department_name,
                    (SELECT SUM(amount) FROM household_repayments WHERE household_id = h.id) as paid_amount
                FROM household_purchases h
                JOIN members m ON h.member_id = m.id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        $conditions = [];
        
        // Add conditions based on filters
        if ($departmentId) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($status && in_array($status, ['pending', 'approved', 'active', 'completed', 'rejected'])) {
            $conditions[] = "h.status = ?";
            $params[] = $status;
        }
        
        if ($dateFrom) {
            $conditions[] = "h.purchase_date >= ?";
            $params[] = $dateFrom;
        }
        
        if ($dateTo) {
            $conditions[] = "h.purchase_date <= ?";
            $params[] = $dateTo;
        }
        
        // Add conditions to query
        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }
        
        $query .= " ORDER BY h.purchase_date DESC";
        
        // Fetch household purchases
        $purchases = Database::fetchAll($query, $params);
        
        // Calculate remaining balance
        foreach ($purchases as &$purchase) {
            $purchase['paid_amount'] = $purchase['paid_amount'] ?? 0;
            $purchase['remaining_balance'] = $purchase['amount'] - $purchase['paid_amount'];
        }
        
        // Check if PhpSpreadsheet is available, display error if not
        $this->checkPhpSpreadsheetAvailability();
        
        // Generate Excel file
        $this->generateHouseholdExcelReport($purchases);
    }
    
    /**
     * Generate transaction history report based on filters
     */
    public function transactionReport(): void
    {
        // Get and sanitize filters
        $departmentId = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
        $transactionType = htmlspecialchars((string)($_GET['transaction_type'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateFrom = htmlspecialchars((string)($_GET['date_from'] ?? ''), ENT_QUOTES, 'UTF-8');
        $dateTo = htmlspecialchars((string)($_GET['date_to'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Build query
        $query = "SELECT 
                    t.id,
                    t.transaction_date,
                    t.transaction_type,
                    t.amount,
                    t.reference_id,
                    t.description,
                    m.coop_no,
                    m.name as member_name,
                    d.name as department_name
                FROM transaction_history t
                JOIN members m ON t.member_id = m.id
                LEFT JOIN departments d ON m.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        $conditions = [];
        
        // Add conditions based on filters
        if ($departmentId) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($dateFrom) {
            $conditions[] = "t.transaction_date >= ?";
            $params[] = $dateFrom;
        }
        
        if ($dateTo) {
            $conditions[] = "t.transaction_date <= ?";
            $params[] = $dateTo;
        }
        
        if ($transactionType && in_array($transactionType, ['deposit', 'withdrawal', 'loan', 'loan_payment', 'household', 'household_payment'])) {
            $conditions[] = "t.transaction_type = ?";
            $params[] = $transactionType;
        }
        
        // Add conditions to query
        if (!empty($conditions)) {
            $query .= " AND " . implode(" AND ", $conditions);
        }
        
        $query .= " ORDER BY t.transaction_date DESC";
        
        // Fetch transactions
        $transactions = Database::fetchAll($query, $params);
        
        // Check if PhpSpreadsheet is available, display error if not
        $this->checkPhpSpreadsheetAvailability();
        
        // Generate Excel file
        $this->generateTransactionExcelReport($transactions);
    }
    
    /**
     * Generate Excel file for member report
     */
    private function generateMemberExcelReport(array $data): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Cooperative System')
            ->setLastModifiedBy('Cooperative System')
            ->setTitle('Member Report')
            ->setSubject('Member Report')
            ->setDescription('Member report generated from Cooperative System');
        
        // Set column headers
        $sheet->setCellValue('A1', 'Member ID');
        $sheet->setCellValue('B1', 'Cooperative No');
        $sheet->setCellValue('C1', 'Name');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Phone');
        $sheet->setCellValue('F1', 'Department');
        $sheet->setCellValue('G1', 'Join Date');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Savings Balance');
        $sheet->setCellValue('J1', 'Loans Count');
        $sheet->setCellValue('K1', 'Household Purchases Count');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['coop_no']);
            $sheet->setCellValue('C' . $row, $item['name']);
            $sheet->setCellValue('D' . $row, $item['email']);
            $sheet->setCellValue('E' . $row, $item['phone']);
            $sheet->setCellValue('F' . $row, $item['department_name']);
            $sheet->setCellValue('G' . $row, $item['join_date']);
            $sheet->setCellValue('H' . $row, $item['is_active'] ? 'Active' : 'Inactive');
            $sheet->setCellValue('I' . $row, $item['savings_balance'] ?? 0);
            $sheet->setCellValue('J' . $row, $item['loans_count']);
            $sheet->setCellValue('K' . $row, $item['household_count']);
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply borders to all data
        $sheet->getStyle('A1:K' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set money format for savings balance
        $sheet->getStyle('I2:I' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        
        $this->outputExcelFile($spreadsheet, 'member_report');
    }
    
    /**
     * Generate Excel file for savings report
     */
    private function generateSavingsExcelReport(array $data): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Cooperative System')
            ->setLastModifiedBy('Cooperative System')
            ->setTitle('Savings Transactions Report')
            ->setSubject('Savings Transactions Report')
            ->setDescription('Savings transactions report generated from Cooperative System');
        
        // Set column headers
        $sheet->setCellValue('A1', 'Transaction ID');
        $sheet->setCellValue('B1', 'Transaction Date');
        $sheet->setCellValue('C1', 'Member No');
        $sheet->setCellValue('D1', 'Member Name');
        $sheet->setCellValue('E1', 'Department');
        $sheet->setCellValue('F1', 'Transaction Type');
        $sheet->setCellValue('G1', 'Amount');
        $sheet->setCellValue('H1', 'Description');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['transaction_date']);
            $sheet->setCellValue('C' . $row, $item['coop_no']);
            $sheet->setCellValue('D' . $row, $item['member_name']);
            $sheet->setCellValue('E' . $row, $item['department_name']);
            $sheet->setCellValue('F' . $row, ucfirst($item['transaction_type']));
            $sheet->setCellValue('G' . $row, $item['amount']);
            $sheet->setCellValue('H' . $row, $item['description']);
            
            // Set color for transaction type
            if ($item['transaction_type'] === 'deposit') {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('008000'); // Green
            } else {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
            }
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply borders to all data
        $sheet->getStyle('A1:H' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set money format for amount
        $sheet->getStyle('G2:G' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        
        $this->outputExcelFile($spreadsheet, 'savings_transactions_report');
    }
    
    /**
     * Generate Excel file for loan report
     */
    private function generateLoanExcelReport(array $data): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Cooperative System')
            ->setLastModifiedBy('Cooperative System')
            ->setTitle('Loan Report')
            ->setSubject('Loan Report')
            ->setDescription('Loan report generated from Cooperative System');
        
        // Set column headers
        $sheet->setCellValue('A1', 'Loan ID');
        $sheet->setCellValue('B1', 'Created Date');
        $sheet->setCellValue('C1', 'Member No');
        $sheet->setCellValue('D1', 'Member Name');
        $sheet->setCellValue('E1', 'Department');
        $sheet->setCellValue('F1', 'Loan Amount');
        $sheet->setCellValue('G1', 'Interest Rate (%)');
        $sheet->setCellValue('H1', 'Term (Months)');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Approval Date');
        $sheet->setCellValue('K1', 'Paid Amount');
        $sheet->setCellValue('L1', 'Remaining Balance');
        $sheet->setCellValue('M1', 'Description');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle('A1:M1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['created_at']);
            $sheet->setCellValue('C' . $row, $item['coop_no']);
            $sheet->setCellValue('D' . $row, $item['member_name']);
            $sheet->setCellValue('E' . $row, $item['department_name']);
            $sheet->setCellValue('F' . $row, $item['amount']);
            $sheet->setCellValue('G' . $row, $item['interest_rate']);
            $sheet->setCellValue('H' . $row, $item['term_months']);
            $sheet->setCellValue('I' . $row, ucfirst($item['status']));
            $sheet->setCellValue('J' . $row, $item['approval_date']);
            $sheet->setCellValue('K' . $row, $item['paid_amount']);
            $sheet->setCellValue('L' . $row, $item['remaining_balance']);
            $sheet->setCellValue('M' . $row, $item['description']);
            
            // Set color for status
            switch ($item['status']) {
                case 'approved':
                case 'active':
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'completed':
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'rejected':
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                default:
                    $sheet->getStyle('I' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange for pending
            }
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply borders to all data
        $sheet->getStyle('A1:M' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set money format for amount columns
        $sheet->getStyle('F2:F' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('K2:L' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        
        $this->outputExcelFile($spreadsheet, 'loan_report');
    }
    
    /**
     * Generate Excel file for household purchases report
     */
    private function generateHouseholdExcelReport(array $data): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Cooperative System')
            ->setLastModifiedBy('Cooperative System')
            ->setTitle('Household Purchases Report')
            ->setSubject('Household Purchases Report')
            ->setDescription('Household purchases report generated from Cooperative System');
        
        // Set column headers
        $sheet->setCellValue('A1', 'Purchase ID');
        $sheet->setCellValue('B1', 'Purchase Date');
        $sheet->setCellValue('C1', 'Member No');
        $sheet->setCellValue('D1', 'Member Name');
        $sheet->setCellValue('E1', 'Department');
        $sheet->setCellValue('F1', 'Item Name');
        $sheet->setCellValue('G1', 'Amount');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Approval Date');
        $sheet->setCellValue('J1', 'Paid Amount');
        $sheet->setCellValue('K1', 'Remaining Balance');
        $sheet->setCellValue('L1', 'Description');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['purchase_date']);
            $sheet->setCellValue('C' . $row, $item['coop_no']);
            $sheet->setCellValue('D' . $row, $item['member_name']);
            $sheet->setCellValue('E' . $row, $item['department_name']);
            $sheet->setCellValue('F' . $row, $item['item_name']);
            $sheet->setCellValue('G' . $row, $item['amount']);
            $sheet->setCellValue('H' . $row, ucfirst($item['status']));
            $sheet->setCellValue('I' . $row, $item['approval_date']);
            $sheet->setCellValue('J' . $row, $item['paid_amount']);
            $sheet->setCellValue('K' . $row, $item['remaining_balance']);
            $sheet->setCellValue('L' . $row, $item['description']);
            
            // Set color for status
            switch ($item['status']) {
                case 'approved':
                case 'active':
                    $sheet->getStyle('H' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'completed':
                    $sheet->getStyle('H' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'rejected':
                    $sheet->getStyle('H' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                default:
                    $sheet->getStyle('H' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange for pending
            }
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply borders to all data
        $sheet->getStyle('A1:L' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set money format for amount columns
        $sheet->getStyle('G2:G' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('J2:K' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        
        $this->outputExcelFile($spreadsheet, 'household_purchases_report');
    }
    
    /**
     * Generate Excel file for transaction history report
     */
    private function generateTransactionExcelReport(array $data): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Cooperative System')
            ->setLastModifiedBy('Cooperative System')
            ->setTitle('Transaction History Report')
            ->setSubject('Transaction History Report')
            ->setDescription('Transaction history report generated from Cooperative System');
        
        // Set column headers
        $sheet->setCellValue('A1', 'Transaction ID');
        $sheet->setCellValue('B1', 'Transaction Date');
        $sheet->setCellValue('C1', 'Member No');
        $sheet->setCellValue('D1', 'Member Name');
        $sheet->setCellValue('E1', 'Department');
        $sheet->setCellValue('F1', 'Transaction Type');
        $sheet->setCellValue('G1', 'Amount');
        $sheet->setCellValue('H1', 'Related Table');
        $sheet->setCellValue('I1', 'Related ID');
        $sheet->setCellValue('J1', 'Description');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['transaction_date']);
            $sheet->setCellValue('C' . $row, $item['coop_no']);
            $sheet->setCellValue('D' . $row, $item['member_name']);
            $sheet->setCellValue('E' . $row, $item['department_name']);
            $sheet->setCellValue('F' . $row, ucfirst(str_replace('_', ' ', $item['transaction_type'])));
            $sheet->setCellValue('G' . $row, $item['amount']);
            $sheet->setCellValue('H' . $row, ucfirst(str_replace('_', ' ', $item['related_table'])));
            $sheet->setCellValue('I' . $row, $item['related_id']);
            $sheet->setCellValue('J' . $row, $item['description']);
            
            // Set color for transaction type
            switch ($item['transaction_type']) {
                case 'deposit':
                    $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('008000'); // Green
                    break;
                case 'withdrawal':
                    $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('FF0000'); // Red
                    break;
                case 'loan':
                    $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('0000FF'); // Blue
                    break;
                case 'loan_payment':
                    $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('800080'); // Purple
                    break;
                case 'household':
                    $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('FFA500'); // Orange
                    break;
                case 'household_payment':
                    $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('8B4513'); // Brown
                    break;
            }
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Apply borders to all data
        $sheet->getStyle('A1:J' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Set money format for amount column
        $sheet->getStyle('G2:G' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        
        $this->outputExcelFile($spreadsheet, 'transaction_history_report');
    }
    
    /**
     * Output the Excel file for download
     */
    private function outputExcelFile(Spreadsheet $spreadsheet, string $filename): void
    {
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Create Excel file writer
        $writer = new Xlsx($spreadsheet);
        
        // Save to PHP output stream
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Check if PhpSpreadsheet is available, display error if not
     */
    private function checkPhpSpreadsheetAvailability(): void
    {
        if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
            // Display error message
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Missing Required Extension</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        margin: 30px;
                        color: #333;
                    }
                    .error-container {
                        background-color: #f8d7da;
                        color: #721c24;
                        padding: 20px;
                        border-radius: 5px;
                        border: 1px solid #f5c6cb;
                        margin-bottom: 20px;
                    }
                    h2 {
                        margin-top: 0;
                        color: #721c24;
                    }
                    .steps {
                        background-color: #f8f9fa;
                        padding: 20px;
                        border-radius: 5px;
                        border: 1px solid #ddd;
                    }
                    code {
                        background-color: #f1f1f1;
                        padding: 2px 5px;
                        border-radius: 3px;
                        font-family: Consolas, monospace;
                    }
                    .back-link {
                        margin-top: 20px;
                        display: inline-block;
                        padding: 10px 15px;
                        background-color: #007bff;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    .back-link:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>
            <body>
                <div class="error-container">
                    <h2>Required PHP Extension Missing</h2>
                    <p>The PhpSpreadsheet library is not available. This is required to generate Excel reports.</p>
                </div>
                
                <div class="steps">
                    <h3>Follow these steps to fix the issue:</h3>
                    <ol>
                        <li>Enable the GD extension in your PHP configuration:
                            <ul>
                                <li>Open <code>php.ini</code> file (usually located at <code>C:\\xampp\\php\\php.ini</code>)</li>
                                <li>Find the line <code>;extension=gd</code> and remove the semicolon to make it <code>extension=gd</code></li>
                                <li>Save the file and restart your Apache server</li>
                            </ul>
                        </li>
                        <li>Run Composer to install the required packages:
                            <ul>
                                <li>Open a command prompt in your project root directory</li>
                                <li>Run: <code>composer install</code></li>
                            </ul>
                        </li>
                    </ol>
                </div>
                
                <a href="/Coops_Bichi/public/superadmin/reports" class="back-link">Back to Reports</a>
            </body>
            </html>';
            exit;
        }
    }
}