<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;
use App\Models\Member;
use App\Traits\ActivityLogger;

/**
 * Admin Member Controller
 * Handles member management in the admin area
 */
final class AdminMemberController extends Controller
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
     * Display members listing
     */
    public function index(): void
    {
        // Get filter parameters
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        $departmentId = $_GET['department'] ?? '';

        // Build query conditions
        $conditions = [];
        $params = [];
        $query = "SELECT m.*, d.name as department_name 
                 FROM members m 
                 LEFT JOIN departments d ON m.department_id = d.id";

        if (!empty($search)) {
            $conditions[] = "(m.name LIKE ? OR m.email LIKE ? OR m.coop_no LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($status)) {
            switch ($status) {
                case 'active':
                    $conditions[] = "m.is_active = 1 AND m.is_locked = 0";
                    break;
                case 'pending':
                    $conditions[] = "m.is_active = 0 AND m.is_locked = 0";
                    break;
                case 'locked':
                    $conditions[] = "m.is_locked = 1";
                    break;
            }
        }

        if (!empty($departmentId)) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }

        // Add WHERE clause if conditions exist
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add ordering
        $query .= " ORDER BY m.created_at DESC";

        // Get filtered members
        $members = Database::fetchAll($query, $params);
        
        // Process members data
        foreach ($members as &$member) {
            // Map is_active and is_locked to a status field
            if (isset($member['is_locked']) && $member['is_locked'] == 1) {
                $member['status'] = 'locked';
            } elseif (isset($member['is_active']) && $member['is_active'] == 1) {
                $member['status'] = 'active';
            } else {
                $member['status'] = 'pending';
            }
            
            // Set employee_id from coop_no
            $member['employee_id'] = $member['coop_no'] ?? '';
        }
        unset($member); // Remove reference
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/members/index', [
            'members' => $members,
            'pageTitle' => 'Members Management',
            'current_page' => 'members',
            'admin' => $admin
        ]);
    }
    
    /**
     * Display add member form
     */
    public function add(): void
    {
        // Restrict access to admin users
        $this->setFlash('error', 'Adding new members is not permitted for admin users. Please contact a super administrator.');
        $this->redirect('/Coops_Bichi/public/admin/members');
        return;
        
        // The code below won't execute due to the redirect above
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Get departments for dropdown
        $departments = Database::fetchAll("SELECT * FROM departments ORDER BY name");
        
        $this->renderAdmin('admin/members/add', [
            'pageTitle' => 'Add New Member',
            'current_page' => 'add_member',
            'departments' => $departments,
            'admin' => $admin
        ]);
    }
    
    /**
     * Create new member
     */
    public function create(): void
    {
        // Restrict access to admin users
        $this->setFlash('error', 'Adding new members is not permitted for admin users. Please contact a super administrator.');
        $this->redirect('/Coops_Bichi/public/admin/members');
        return;
        
        // The code below won't execute due to the redirect above
        // Validate form input
        $validator = new Validator($_POST);
        $validator->required(['name', 'email', 'phone', 'department_id', 'employee_id']);
        $validator->email('email');
        $validator->unique('email', 'members', 'email');
        $validator->unique('employee_id', 'members', 'coop_no');
        
        if (!$validator->passes()) {
            $this->setFlash('error', 'Please correct the errors in the form.');
            $this->setFormErrors($validator->errors());
            $this->setFormData($_POST);
            $this->redirect('/admin/members/add');
            return;
        }
        
        // Create member
        $memberId = Database::insert('members', [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'department_id' => $_POST['department_id'],
            'coop_no' => $_POST['employee_id'],
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if (!$memberId) {
            $this->setFlash('error', 'Failed to create member. Please try again.');
            $this->setFormData($_POST);
            $this->redirect('/admin/members/add');
            return;
        }
        
        // Create member credentials
        $password = $this->generateRandomPassword();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        Database::insert('member_credentials', [
            'member_id' => $memberId,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Log activity
        $this->logActivity('member_created', $memberId, 'member');
        
        $this->setFlash('success', 'Member created successfully. Temporary password: ' . $password);
        $this->redirect('/admin/members');
    }
    
    /**
     * Display member editing form
     * 
     * @param array $params The route parameters
     */
    public function edit(array $params): void
    {
        $id = $params['id'] ?? 0;
        
        // Get member details
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE id = ?",
            [$id]
        );
        
        if (!$member) {
            $this->setFlash('error', 'Member not found.');
            $this->redirect('/admin/members');
            return;
        }
        
        // Map is_active and is_locked to a status field
        if (isset($member['is_locked']) && $member['is_locked'] == 1) {
            $member['status'] = 'locked';
        } elseif (isset($member['is_active']) && $member['is_active'] == 1) {
            $member['status'] = 'active';
        } else {
            $member['status'] = 'pending';
        }
        
        // Map coop_no to employee_id for the view
        $member['employee_id'] = $member['coop_no'] ?? '';
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Get departments for dropdown
        $departments = Database::fetchAll("SELECT * FROM departments ORDER BY name");
        
        $this->renderAdmin('admin/members/edit', [
            'member' => $member,
            'pageTitle' => 'Edit Member',
            'current_page' => 'members',
            'departments' => $departments,
            'admin' => $admin
        ]);
    }
    
    /**
     * Update member
     * 
     * @param array $params The route parameters
     */
    public function update(array $params): void
    {
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        
        // Validate form input
        Validator::resetErrors();
        $required_fields = ['name', 'email', 'phone', 'department_id', 'employee_id', 'status'];
        Validator::required($_POST, $required_fields);
        Validator::email($_POST, 'email');
        
        // Check unique email and employee_id
        if (isset($_POST['email'])) {
            Validator::unique('members', 'email', $_POST['email'], $id);
        }
        if (isset($_POST['employee_id'])) {
            Validator::unique('members', 'coop_no', $_POST['employee_id'], $id);
        }
        
        if (Validator::hasErrors()) {
            $this->setFlash('error', 'Please correct the errors in the form.');
            $this->setFormErrors(Validator::getErrors());
            $this->setFormData($_POST);
            $this->redirect('/admin/members/edit/' . $id);
            return;
        }
        
        // Convert status to is_active and is_locked fields
        $is_active = 0;
        $is_locked = 0;
        
        if ($_POST['status'] === 'active') {
            $is_active = 1;
        } elseif ($_POST['status'] === 'locked') {
            $is_locked = 1;
        }
        
        // Update member
        $updated = Database::update('members', [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'department_id' => $_POST['department_id'],
            'coop_no' => $_POST['employee_id'], // Map employee_id to coop_no in database
            'is_active' => $is_active,
            'is_locked' => $is_locked,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if (!$updated) {
            $this->setFlash('error', 'Failed to update member. Please try again.');
            $this->setFormData($_POST);
            $this->redirect('/admin/members/edit/' . $id);
            return;
        }
        
        // Log activity
        $this->logActivity('member_updated', $id, 'member');
        
        $this->setFlash('success', 'Member updated successfully.');
        $this->redirect('/admin/members');
    }
    
    /**
     * View member details
     * 
     * @param array $params The route parameters
     */
    public function view(array $params): void
    {
        $id = $params['id'] ?? 0;
        
        // Get member details
        $member = Database::fetchOne(
            "SELECT m.*, d.name as department_name 
            FROM members m
            LEFT JOIN departments d ON m.department_id = d.id
            WHERE m.id = ?",
            [$id]
        );
        
        if (!$member) {
            $this->setFlash('error', 'Member not found.');
            $this->redirect('/admin/members');
            return;
        }
        
        // Map is_active and is_locked to status field
        if (isset($member['is_locked']) && $member['is_locked'] == 1) {
            $member['status'] = 'locked';
        } elseif (isset($member['is_active']) && $member['is_active'] == 1) {
            $member['status'] = 'active';
        } else {
            $member['status'] = 'pending';
        }
        
        // Get member savings
        $savings = Database::fetchOne(
            "SELECT * FROM savings WHERE member_id = ?",
            [$id]
        );
        
        // Get savings transactions for calculations
        if ($savings) {
            // Calculate total deposits
            $totalDeposits = Database::fetchOne(
                "SELECT SUM(amount) as total FROM savings_transactions 
                WHERE member_id = ? AND transaction_type = 'deposit'",
                [$id]
            );
            
            // Calculate total withdrawals
            $totalWithdrawals = Database::fetchOne(
                "SELECT SUM(amount) as total FROM savings_transactions 
                WHERE member_id = ? AND transaction_type = 'withdrawal'",
                [$id]
            );
            
            // Calculate interest earned
            $interestEarned = Database::fetchOne(
                "SELECT SUM(amount) as total FROM savings_transactions 
                WHERE member_id = ? AND transaction_type = 'interest'",
                [$id]
            );
            
            // Add calculated values to savings data
            $savings['total_deposits'] = $totalDeposits['total'] ?? 0;
            $savings['total_withdrawals'] = $totalWithdrawals['total'] ?? 0;
            $savings['interest_amount'] = $interestEarned['total'] ?? 0;
        }
        
        // Get member loans
        $loans = Database::fetchAll(
            "SELECT * FROM loans WHERE member_id = ? ORDER BY created_at DESC",
            [$id]
        );
        
        // Get member household purchases
        $purchases = Database::fetchAll(
            "SELECT * FROM household_purchases WHERE member_id = ? ORDER BY created_at DESC",
            [$id]
        );
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/members/view', [
            'member' => $member,
            'savings' => $savings,
            'loans' => $loans,
            'purchases' => $purchases,
            'pageTitle' => 'Member Details',
            'current_page' => 'members',
            'admin' => $admin
        ]);
    }
    
    /**
     * Lock member account
     * 
     * @param array $params The route parameters
     */
    public function lock(array $params): void
    {
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        
        // Update member status
        $updated = Database::update('members', [
            'is_active' => 0,
            'is_locked' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if (!$updated) {
            $this->setFlash('error', 'Failed to lock member account. Please try again.');
            $this->redirect('/admin/members');
            return;
        }
        
        // Log activity
        $this->logActivity('member_locked', $id, 'member');
        
        $this->setFlash('success', 'Member account locked successfully.');
        $this->redirect('/admin/members');
    }
    
    /**
     * Unlock member account
     * 
     * @param array $params The route parameters
     */
    public function unlock(array $params): void
    {
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        
        // Update member status
        $updated = Database::update('members', [
            'is_active' => 1,
            'is_locked' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if (!$updated) {
            $this->setFlash('error', 'Failed to unlock member account. Please try again.');
            $this->redirect('/admin/members');
            return;
        }
        
        // Log activity
        $this->logActivity('member_unlocked', $id, 'member');
        
        $this->setFlash('success', 'Member account unlocked successfully.');
        $this->redirect('/admin/members');
    }
    
    /**
     * Delete member
     * 
     * @param array $params The route parameters
     */
    public function delete(array $params): void
    {
        // Restrict access to admin users
        $this->setFlash('error', 'Deleting members is not permitted for admin users. Please contact a super administrator.');
        $this->redirect('/Coops_Bichi/public/admin/members');
        return;
        
        // The code below won't execute due to the redirect above
        $id = $params['id'] ?? 0;
        
        // Check if member has any active loans or purchases
        $hasActiveLoans = Database::fetchOne(
            "SELECT COUNT(*) as count FROM loans WHERE member_id = ? AND status = 'approved' AND balance > 0",
            [$id]
        );
        
        $hasActivePurchases = Database::fetchOne(
            "SELECT COUNT(*) as count FROM household_purchases WHERE member_id = ? AND status = 'approved' AND balance > 0",
            [$id]
        );
        
        if (($hasActiveLoans['count'] ?? 0) > 0 || ($hasActivePurchases['count'] ?? 0) > 0) {
            $this->setFlash('error', 'Cannot delete member with active loans or household purchases.');
            $this->redirect('/admin/members');
            return;
        }
        
        // Delete member
        $deleted = Database::delete('members', 'id = ?', [$id]);
        
        if (!$deleted) {
            $this->setFlash('error', 'Failed to delete member. Please try again.');
            $this->redirect('/admin/members');
            return;
        }
        
        // Log activity
        $this->logActivity('member_deleted', $id, 'member');
        
        $this->setFlash('success', 'Member deleted successfully.');
        $this->redirect('/admin/members');
    }
    
    /**
     * Show pending members for approval
     */
    public function pending(): void
    {
        // Get pending members
        $members = Database::fetchAll(
            "SELECT m.*, d.name as department_name 
            FROM members m
            LEFT JOIN departments d ON m.department_id = d.id
            WHERE m.status = 'pending'
            ORDER BY m.created_at DESC"
        );
        
        // Get admin user data
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderAdmin('admin/members/pending', [
            'members' => $members,
            'pageTitle' => 'Pending Members',
            'current_page' => 'pending_members',
            'admin' => $admin
        ]);
    }
    
    /**
     * Approve pending member
     * 
     * @param array $params The route parameters
     */
    public function approve(array $params): void
    {
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        
        // Update member status
        $updated = Database::update('members', [
            'is_active' => 1,
            'is_locked' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        
        if (!$updated) {
            $this->setFlash('error', 'Failed to approve member. Please try again.');
            $this->redirect('/admin/members/pending');
            return;
        }
        
        // Log activity
        $this->logActivity('member_approved', $id, 'member');
        
        $this->setFlash('success', 'Member approved successfully.');
        $this->redirect('/admin/members/pending');
    }
    
    /**
     * Reject pending member
     * 
     * @param array $params The route parameters
     */
    public function reject(array $params): void
    {
        $id = $params['id'] ?? 0;
        
        // Delete member
        $deleted = Database::delete('members', 'id = ?', [$id]);
        
        if (!$deleted) {
            $this->setFlash('error', 'Failed to reject member. Please try again.');
            $this->redirect('/admin/members/pending');
            return;
        }
        
        // Log activity
        $this->logActivity('member_rejected', $id, 'member');
        
        $this->setFlash('success', 'Member rejected successfully.');
        $this->redirect('/admin/members/pending');
    }
    
    /**
     * Generate a random password
     * 
     * @return string Random password
     */
    private function generateRandomPassword(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        
        for ($i = 0; $i < 8; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }
        
        return $password;
    }
} 