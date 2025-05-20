<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;
use App\Models\Admin;

/**
 * AdminController for Superadmin
 * Handles admin management functionality
 */
final class AdminController extends AbstractController
{
    /**
     * Display list of admin accounts
     */
    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        
        $admins = Admin::getAll($page, $perPage);
        
        $this->renderSuperAdmin('superadmin/manage-admins', [
            'admins' => $admins['data'],
            'pagination' => $admins['pagination'],
            'current_page' => 'manage_admins',
            'pageTitle' => 'Manage Administrators'
        ]);
    }
    
    /**
     * Show form to create a new admin
     */
    public function create(): void
    {
        $this->renderSuperAdmin('superadmin/create-admin', [
            'current_page' => 'create_admin',
            'pageTitle' => 'Create Administrator Account'
        ]);
    }
    
    /**
     * Process admin creation
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        // Validate input
        Validator::resetErrors();
        Validator::required($_POST, ['username', 'name', 'email', 'password', 'role']);
        Validator::email($_POST, 'email');
        Validator::minLength($_POST, 'password', 8);
        
        if (Validator::hasErrors()) {
            $this->setFlash('errors', Validator::getErrors());
            $this->redirect('/superadmin/create-admin');
            return;
        }
        
        // Sanitize input
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);
        
        // Validate role
        if (!Admin::isValidRole($role)) {
            $this->setFlash('error', 'Invalid role specified');
            $this->redirect('/superadmin/create-admin');
            return;
        }
        
        // Create admin
        $adminId = Admin::create($username, $name, $email, $password, $role);
        
        if ($adminId) {
            Auth::logAction('admin', Auth::getAdminId(), "Created new {$role} account for {$username}", ['type' => 'admin']);
            $this->setFlash('success', "The {$role} account has been created successfully");
            $this->redirect('/superadmin/manage-admins');
        } else {
            $this->setFlash('error', 'Failed to create administrator account. Username or email may already be in use.');
            $this->redirect('/superadmin/create-admin');
        }
    }
    
    /**
     * Show form to edit admin
     */
    public function edit(string $id): void
    {
        $admin = Admin::getById((int)$id);
        
        if (!$admin) {
            $this->setFlash('error', 'Administrator not found');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        $this->renderSuperAdmin('superadmin/edit-admin', [
            'admin' => $admin,
            'current_page' => 'manage_admins',
            'pageTitle' => 'Edit Administrator'
        ]);
    }
    
    /**
     * Process admin update
     */
    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        $admin = Admin::getById((int)$id);
        
        if (!$admin) {
            $this->setFlash('error', 'Administrator not found');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        // Validate input
        Validator::resetErrors();
        Validator::required($_POST, ['name', 'email']);
        Validator::email($_POST, 'email');
        
        if (Validator::hasErrors()) {
            $this->setFlash('errors', Validator::getErrors());
            $this->redirect("/superadmin/edit-admin/{$id}");
            return;
        }
        
        // Sanitize input
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $role = filter_var($_POST['role'] ?? $admin['role'], FILTER_SANITIZE_STRING);
        
        // Validate role
        if (!Admin::isValidRole($role)) {
            $this->setFlash('error', 'Invalid role specified');
            $this->redirect("/superadmin/edit-admin/{$id}");
            return;
        }
        
        // Prevent demoting the last superadmin
        if ($admin['role'] === Admin::ROLE_SUPERADMIN && $role !== Admin::ROLE_SUPERADMIN) {
            $superadminCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM admin_users WHERE role = ?",
                [Admin::ROLE_SUPERADMIN]
            );
            
            if ($superadminCount && (int)$superadminCount['count'] <= 1) {
                $this->setFlash('error', 'Cannot demote the last superadmin');
                $this->redirect("/superadmin/edit-admin/{$id}");
                return;
            }
        }
        
        // Update admin
        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if (Admin::update((int)$id, $data)) {
            Auth::logAction('admin', Auth::getAdminId(), "Updated administrator account for {$admin['username']}", ['type' => 'admin']);
            $this->setFlash('success', 'Administrator account has been updated successfully');
            $this->redirect('/superadmin/manage-admins');
        } else {
            $this->setFlash('error', 'Failed to update administrator account');
            $this->redirect("/superadmin/edit-admin/{$id}");
        }
    }
    
    /**
     * Delete admin account
     */
    public function delete(string $id): void
    {
        $admin = Admin::getById((int)$id);
        
        if (!$admin) {
            $this->setFlash('error', 'Administrator not found');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        // Prevent deleting self
        if ((int)$id === Auth::getAdminId()) {
            $this->setFlash('error', 'You cannot delete your own account');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        if (Admin::delete((int)$id)) {
            Auth::logAction('admin', Auth::getAdminId(), "Deleted administrator account for {$admin['username']}", ['type' => 'security']);
            $this->setFlash('success', 'Administrator account has been deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete administrator account. Cannot delete the last superadmin.');
        }
        
        $this->redirect('/superadmin/manage-admins');
    }
    
    /**
     * Toggle admin lock status
     */
    public function toggleLock(string $id): void
    {
        $admin = Admin::getById((int)$id);
        
        if (!$admin) {
            $this->setFlash('error', 'Administrator not found');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        // Prevent locking the last superadmin
        if ($admin['role'] === Admin::ROLE_SUPERADMIN && !$admin['is_locked']) {
            $activeSuperadminCount = Database::fetchOne(
                "SELECT COUNT(*) as count FROM admin_users WHERE role = ? AND is_locked = 0",
                [Admin::ROLE_SUPERADMIN]
            );
            
            if ($activeSuperadminCount && (int)$activeSuperadminCount['count'] <= 1) {
                $this->setFlash('error', 'Cannot lock the last active superadmin');
                $this->redirect('/superadmin/manage-admins');
                return;
            }
        }
        
        // Toggle lock status
        $newStatus = !$admin['is_locked'];
        
        if (Admin::setLockStatus((int)$id, $newStatus)) {
            $action = $newStatus ? 'locked' : 'unlocked';
            Auth::logAction('admin', Auth::getAdminId(), "{$action} administrator account for {$admin['username']}", ['type' => 'security']);
            $this->setFlash('success', "Administrator account has been {$action} successfully");
        } else {
            $this->setFlash('error', 'Failed to change account lock status');
        }
        
        $this->redirect('/superadmin/manage-admins');
    }
    
    /**
     * Show form to reset admin password
     */
    public function resetPassword(string $id): void
    {
        $admin = Admin::getById((int)$id);
        
        if (!$admin) {
            $this->setFlash('error', 'Administrator not found');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        $this->renderSuperAdmin('superadmin/reset-password', [
            'admin' => $admin,
            'current_page' => 'manage_admins',
            'pageTitle' => 'Reset Administrator Password'
        ]);
    }
    
    /**
     * Process password reset
     */
    public function updatePassword(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        $admin = Admin::getById((int)$id);
        
        if (!$admin) {
            $this->setFlash('error', 'Administrator not found');
            $this->redirect('/superadmin/manage-admins');
            return;
        }
        
        // Validate input
        Validator::resetErrors();
        Validator::required($_POST, ['password', 'confirm_password']);
        Validator::minLength($_POST, 'password', 8);
        
        // Check if passwords match
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $errors = Validator::getErrors();
            $errors['confirm_password'] = 'Passwords do not match';
            $this->setFlash('errors', $errors);
            $this->redirect("/superadmin/reset-password/{$id}");
            return;
        }
        
        if (Validator::hasErrors()) {
            $this->setFlash('errors', Validator::getErrors());
            $this->redirect("/superadmin/reset-password/{$id}");
            return;
        }
        
        $password = $_POST['password'];
        
        if (Admin::updatePassword((int)$id, $password)) {
            // Reset failed login attempts
            Admin::resetFailedAttempts((int)$id);
            
            Auth::logAction('admin', Auth::getAdminId(), "Reset password for administrator {$admin['username']}", ['type' => 'security']);
            $this->setFlash('success', 'Password has been reset successfully');
            $this->redirect('/superadmin/manage-admins');
        } else {
            $this->setFlash('error', 'Failed to reset administrator password');
            $this->redirect("/superadmin/reset-password/{$id}");
        }
    }
} 