<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;

/**
 * ProfileController for Superadmin
 * Handles profile management functionality
 */
final class ProfileController extends AbstractController
{
    /**
     * Display profile page
     */
    public function index(): void
    {
        // Get admin info
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderSuperAdmin('superadmin/profile', [
            'admin' => $admin,
            'current_page' => 'profile',
            'page_title' => 'SuperAdmin Profile'
        ]);
    }
    
    /**
     * Update profile information
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/profile');
            return;
        }
        
        // Get admin ID
        $adminId = Auth::getAdminId();
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        Validator::resetErrors();
        Validator::required($input, ['name', 'email']);
        Validator::email($input, 'email');
        
        if (Validator::hasErrors()) {
            $errors = Validator::getErrors();
            $firstError = reset($errors);
            $this->setFlash('error', $firstError);
            $this->redirect('/superadmin/profile');
            return;
        }
        
        // Update profile
        $result = Database::update('admin_users', [
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? ''
        ], ['id' => $adminId]);
        
        if ($result) {
            // Log the action
            Auth::logAction('admin', $adminId, 'Profile updated', []);
            
            $this->setFlash('success', 'Profile updated successfully');
        } else {
            $this->setFlash('error', 'Failed to update profile');
        }
        
        $this->redirect('/superadmin/profile');
    }
    
    /**
     * Upload profile image
     */
    public function uploadImage(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/profile');
            return;
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'No image file uploaded or upload error occurred');
            $this->redirect('/superadmin/profile');
            return;
        }
        
        $file = $_FILES['profile_image'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->setFlash('error', 'Only JPG and PNG images are allowed');
            $this->redirect('/superadmin/profile');
            return;
        }
        
        // Validate file size (max 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if ($file['size'] > $maxSize) {
            $this->setFlash('error', 'Image size exceeds 2MB limit');
            $this->redirect('/superadmin/profile');
            return;
        }
        
        // Create upload directory if it doesn't exist
        $uploadDir = BASE_DIR . '/public/uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'admin_' . Auth::getAdminId() . '_' . time() . '.' . $fileExt;
        $targetPath = $uploadDir . $fileName;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Update profile image path in database
            $adminId = Auth::getAdminId();
            $imagePath = '/uploads/profiles/' . $fileName;
            
            $result = Database::update('admin_users', [
                'profile_image' => $imagePath
            ], ['id' => $adminId]);
            
            if ($result) {
                $this->setFlash('success', 'Profile image updated successfully');
            } else {
                $this->setFlash('error', 'Failed to update profile image in database');
            }
        } else {
            $this->setFlash('error', 'Failed to upload image');
        }
        
        $this->redirect('/superadmin/profile');
    }
    
    /**
     * Display settings page
     */
    public function settings(): void
    {
        // Get admin info
        $adminId = Auth::getAdminId();
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        $this->renderSuperAdmin('superadmin/settings', [
            'admin' => $admin,
            'current_page' => 'settings',
            'page_title' => 'Account Settings'
        ]);
    }
    
    /**
     * Update superadmin password
     */
    public function updatePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/settings');
            return;
        }
        
        // Get admin ID
        $adminId = Auth::getAdminId();
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        Validator::resetErrors();
        Validator::required($input, ['current_password', 'new_password', 'confirm_password']);
        Validator::minLength($input, 'new_password', 8);
        
        // Check if passwords match
        if ($input['new_password'] !== $input['confirm_password']) {
            $errors = Validator::getErrors();
            $errors['confirm_password'] = 'Password confirmation does not match';
            $this->setFlash('errors', $errors);
            $this->redirect('/superadmin/settings');
            return;
        }
        
        if (Validator::hasErrors()) {
            $errors = Validator::getErrors();
            $firstError = reset($errors);
            $this->setFlash('error', $firstError);
            $this->redirect('/superadmin/settings');
            return;
        }
        
        // Get admin details
        $admin = Database::fetchOne(
            "SELECT * FROM admin_users WHERE id = ?",
            [$adminId]
        );
        
        // Verify current password
        if (!password_verify($input['current_password'], $admin['password'])) {
            $this->setFlash('error', 'Current password is incorrect');
            $this->redirect('/superadmin/settings');
            return;
        }
        
        // Update password
        $hashedPassword = password_hash($input['new_password'], PASSWORD_DEFAULT);
        $result = Database::update('admin_users', [
            'password' => $hashedPassword,
            'password_reset_at' => date('Y-m-d H:i:s')
        ], ['id' => $adminId]);
        
        if ($result) {
            // Reset failed login attempts
            Database::update('admin_users', [
                'failed_login_attempts' => 0
            ], ['id' => $adminId]);
            
            // Log the action
            Auth::logAction('admin', $adminId, 'Password updated', ['type' => 'security']);
            
            $this->setFlash('success', 'Password updated successfully');
        } else {
            $this->setFlash('error', 'Failed to update password');
        }
        
        $this->redirect('/superadmin/settings');
    }
} 