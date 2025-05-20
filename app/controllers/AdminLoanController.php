<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;

/**
 * Admin Loan Controller
 * Handles loan view functionality in the admin area
 */
final class AdminLoanController extends Controller
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
     * Display loans listing
     */
    public function index(): void
    {
        // Set up pagination
        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $per_page = 10;
        
        // Start building the query
        $base_query = "FROM loans l
                      LEFT JOIN members m ON l.member_id = m.id";
        
        $params = [];
        $whereConditions = [];
        
        // Apply search filter
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = trim($_GET['search']);
            $whereConditions[] = "(m.name LIKE ? OR m.email LIKE ? OR m.coop_no LIKE ? OR m.phone LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Apply status filter
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            if ($_GET['status'] === 'overdue') {
                $whereConditions[] = "(l.status = 'approved' AND l.end_date < CURDATE())";
            } elseif ($_GET['status'] === 'active') {
                $whereConditions[] = "l.status = 'approved'";
            } else {
                $whereConditions[] = "l.status = ?";
                $params[] = $_GET['status'];
            }
        }
        
        // Apply loan type filter
        if (isset($_GET['loan_type']) && !empty($_GET['loan_type'])) {
            $whereConditions[] = "l.loan_type = ?";
            $params[] = $_GET['loan_type'];
        }
        
        // Apply date range filters
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $whereConditions[] = "l.created_at >= ?";
            $params[] = $_GET['date_from'] . " 00:00:00";
        }
        
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $whereConditions[] = "l.created_at <= ?";
            $params[] = $_GET['date_to'] . " 23:59:59";
        }
        
        // Apply amount range filters
        if (isset($_GET['amount_min']) && !empty($_GET['amount_min'])) {
            $whereConditions[] = "l.loan_amount >= ?";
            $params[] = floatval($_GET['amount_min']);
        }
        
        if (isset($_GET['amount_max']) && !empty($_GET['amount_max'])) {
            $whereConditions[] = "l.loan_amount <= ?";
            $params[] = floatval($_GET['amount_max']);
        }
        
        // Apply WHERE conditions if any
        $where_clause = "";
        if (!empty($whereConditions)) {
            $where_clause = "WHERE " . implode(" AND ", $whereConditions);
        }
        
        // Count total records for pagination
        $count_query = "SELECT COUNT(*) as count " . $base_query . " " . $where_clause;
        $total_records = Database::fetchOne($count_query, $params)['count'] ?? 0;
        
        $total_pages = ceil($total_records / $per_page);
        $offset = ($current_page - 1) * $per_page;
        
        // Build query string for pagination links
        $query_string = '';
        foreach ($_GET as $key => $value) {
            if ($key !== 'page') {
                $query_string .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }
        
        // Build the final query with ORDER BY and LIMIT
        $order_by = "ORDER BY l.created_at DESC";
        
        // Get all loans with pagination
        $loans_query = "SELECT l.*, m.name as member_name, m.coop_no " . 
                      $base_query . " " . 
                      $where_clause . " " .
                      $order_by . " " .
                      "LIMIT ? OFFSET ?";
        
        // Add pagination parameters
        $params[] = $per_page;
        $params[] = $offset;
        
        $loans = Database::fetchAll($loans_query, $params);
        
        // Get loan statistics
        $loan_stats = [
            'total_active' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loans WHERE status = 'approved'"
            )['count'] ?? 0,
            'active_value' => Database::fetchOne(
                "SELECT SUM(loan_amount) as sum FROM loans WHERE status = 'approved'"
            )['sum'] ?? 0,
            'pending_applications' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loan_applications WHERE status = 'pending'"
            )['count'] ?? 0,
            'overdue_count' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loans 
                WHERE status = 'approved' 
                AND end_date < CURDATE()"
            )['count'] ?? 0,
            'overdue_amount' => Database::fetchOne(
                "SELECT SUM(ip_figure) as sum FROM loans 
                WHERE status = 'approved' 
                AND end_date < CURDATE()"
            )['sum'] ?? 0,
            'monthly_disbursed' => Database::fetchOne(
                "SELECT SUM(loan_amount) as sum FROM loans 
                WHERE status = 'approved' 
                AND MONTH(created_at) = MONTH(CURDATE()) 
                AND YEAR(created_at) = YEAR(CURDATE())"
            )['sum'] ?? 0,
            'monthly_count' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loans 
                WHERE status = 'approved' 
                AND MONTH(created_at) = MONTH(CURDATE()) 
                AND YEAR(created_at) = YEAR(CURDATE())"
            )['count'] ?? 0
        ];
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/loans/index', [
            'loans' => $loans,
            'loan_stats' => $loan_stats,
            'pageTitle' => 'Loans Management',
            'current_page' => 'loans',
            'admin' => $admin,
            // Add pagination data
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'query_string' => $query_string
        ]);
    }
    
    /**
     * Display loan applications
     */
    public function applications(): void
    {
        // Get loan applications
        $applications = Database::fetchAll(
            "SELECT la.*, m.name as member_name, m.coop_no 
            FROM loan_applications la
            LEFT JOIN members m ON la.member_id = m.id
            ORDER BY la.created_at DESC"
        );
        
        // Get loan application statistics
        $stats = [
            'pending' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loan_applications WHERE status = 'pending'"
            )['count'] ?? 0,
            'pending_value' => Database::fetchOne(
                "SELECT SUM(loan_amount) as sum FROM loan_applications WHERE status = 'pending'"
            )['sum'] ?? 0,
            'today' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loan_applications 
                WHERE DATE(created_at) = CURDATE()"
            )['count'] ?? 0,
            'today_change' => 0, // Default value
            'approved_week' => Database::fetchOne(
                "SELECT COUNT(*) as count FROM loan_applications 
                WHERE status = 'approved' 
                AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)"
            )['count'] ?? 0,
            'approved_value' => Database::fetchOne(
                "SELECT SUM(loan_amount) as sum FROM loan_applications 
                WHERE status = 'approved' 
                AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)"
            )['sum'] ?? 0
        ];
        
        // Calculate today's change percentage compared to yesterday
        $yesterday_count = Database::fetchOne(
            "SELECT COUNT(*) as count FROM loan_applications 
            WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)"
        )['count'] ?? 0;
        
        if ($yesterday_count > 0) {
            $stats['today_change'] = round((($stats['today'] - $yesterday_count) / $yesterday_count) * 100, 2);
        }
        
        // Initialize loan_types as empty array since the table doesn't exist
        $loan_types = [];
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/loans/applications', [
            'applications' => $applications,
            'loan_types' => $loan_types,
            'stats' => $stats,
            'pageTitle' => 'Loan Applications',
            'current_page' => 'loan_applications',
            'admin' => $admin
        ]);
    }
    
    /**
     * View loan details
     * 
     * @param array $params The route parameters
     */
    public function view(array $params): void
    {
        $id = $params['id'] ?? 0;
        
        // Get loan details
        $loan = Database::fetchOne(
            "SELECT l.*, m.name as member_name, m.coop_no, m.email, m.phone 
            FROM loans l
            LEFT JOIN members m ON l.member_id = m.id
            WHERE l.id = ?",
            [$id]
        );
        
        if (!$loan) {
            $this->setFlash('error', 'Loan not found.');
            $this->redirect('/admin/loans');
            return;
        }
        
        // Get loan repayments
        $repayments = Database::fetchAll(
            "SELECT * FROM loan_repayments WHERE loan_id = ? ORDER BY created_at DESC",
            [$id]
        );
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/loans/view', [
            'loan' => $loan,
            'repayments' => $repayments,
            'pageTitle' => 'Loan Details',
            'current_page' => 'loans',
            'admin' => $admin
        ]);
    }
    
    /**
     * Display loan repayments
     */
    public function repayments(): void
    {
        // Get all repayments
        $repayments = Database::fetchAll(
            "SELECT lr.*, l.member_id, m.name as member_name, m.coop_no 
            FROM loan_repayments lr
            LEFT JOIN loans l ON lr.loan_id = l.id
            LEFT JOIN members m ON l.member_id = m.id
            ORDER BY lr.repayment_date DESC"
        );
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/loans/repayments', [
            'repayments' => $repayments,
            'pageTitle' => 'Loan Repayments',
            'current_page' => 'loan_repayments',
            'admin' => $admin
        ]);
    }
} 