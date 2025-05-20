<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Auth;
use App\Traits\ActivityLogger;

/**
 * Admin Savings Controller
 * Handles savings management in the admin area
 */
final class AdminSavingsController extends Controller
{
    use ActivityLogger;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Require admin authentication
        $this->requireAdmin();
    }
    
    /**
     * Display savings listing
     */
    public function index(): void
    {
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Start building the query
        $query = "SELECT m.*, s.monthly_deduction, s.cumulative_amount, s.last_deduction_date, d.name as department_name 
                 FROM members m
                 LEFT JOIN savings s ON m.id = s.member_id
                 LEFT JOIN departments d ON m.department_id = d.id";
        
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
        
        // Apply department filter
        if (isset($_GET['department']) && !empty($_GET['department'])) {
            $whereConditions[] = "m.department_id = ?";
            $params[] = $_GET['department'];
        }
        
        // Apply WHERE conditions if any
        if (!empty($whereConditions)) {
            $query .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        // Apply balance sorting
        if (isset($_GET['balance']) && !empty($_GET['balance'])) {
            if ($_GET['balance'] === 'high') {
                $query .= " ORDER BY s.cumulative_amount DESC, m.name ASC";
            } elseif ($_GET['balance'] === 'low') {
                $query .= " ORDER BY s.cumulative_amount ASC, m.name ASC";
            }
        } else {
            $query .= " ORDER BY m.name ASC";
        }
        
        // Get all members with savings information
        $members = Database::fetchAll($query, $params);
        
        // Get total savings amount
        $totalSavings = Database::fetchOne(
            "SELECT SUM(cumulative_amount) as total FROM savings"
        );
        
        $this->renderAdmin('admin/savings/index', [
            'members' => $members,
            'totalSavings' => $totalSavings['total'] ?? 0,
            'pageTitle' => 'Savings Management',
            'current_page' => 'savings',
            'admin' => $admin
        ]);
    }
    
    /**
     * Display savings contributions list
     */
    public function contributions(): void
    {
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Get all savings transactions
        $transactions = Database::fetchAll(
            "SELECT st.*, m.name as member_name, m.coop_no 
            FROM savings_transactions st
            JOIN members m ON st.member_id = m.id
            WHERE st.transaction_type = 'deposit'
            ORDER BY st.deduction_date DESC
            LIMIT 100"
        );
        
        $this->renderAdmin('admin/savings/contributions', [
            'transactions' => $transactions,
            'pageTitle' => 'Savings Contributions',
            'current_page' => 'savings_contributions',
            'admin' => $admin
        ]);
    }
    
    /**
     * Display savings withdrawals list
     */
    public function withdrawals(): void
    {
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Get withdrawal transactions from savings_transactions
        $withdrawals = Database::fetchAll(
            "SELECT st.id, st.member_id, st.amount, st.deduction_date, st.description, st.created_at,
                    m.name as member_name, m.coop_no, m.email, m.phone
            FROM savings_transactions st
            JOIN members m ON st.member_id = m.id
            WHERE st.transaction_type = 'withdrawal'
            ORDER BY st.created_at DESC"
        );
        
        $this->renderAdmin('admin/savings/withdrawals', [
            'withdrawals' => $withdrawals,
            'pageTitle' => 'Savings Withdrawal Requests',
            'current_page' => 'savings_withdrawals',
            'admin' => $admin
        ]);
    }
} 