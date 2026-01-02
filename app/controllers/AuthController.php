<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Member;
use App\Helpers\Session;
use App\Helpers\Validator;
use App\Helpers\Auth;
use App\Models\Admin;
use App\Core\Controller;
use App\Helpers\Database;

/**
 * Handles authentication-related actions including login, registration, and logout
 */
final class AuthController extends Controller
{
    /**
     * Display the login form
     */
    public function showLogin(): void
    {
        // Check if user is already logged in
        if (Session::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        
        $errors = Session::getFlash('errors', []);
        require_once APP_ROOT . '/views/auth/login.php';
    }
    
    /**
     * Process login form submission
     */
    public function login(): void
    {
        $publicUrl = \App\Core\Config::getPublicUrl();
        
        // Check if already logged in
        if (Auth::isMemberLoggedIn()) {
            header('Location: ' . $publicUrl . '/member/dashboard');
                exit;
        }
        
        $this->render('auth/login');
    }
    
    /**
     * Display the registration form
     */
    public function register(): void
    {
        $publicUrl = \App\Core\Config::getPublicUrl();
        
        // Check if already logged in
        if (Auth::isMemberLoggedIn()) {
            header('Location: ' . $publicUrl . '/member/dashboard');
            exit;
        }
        
        // Fetch departments from database
        $departments = \App\Models\Department::getAll();
        
        $this->render('auth/register', [
            'departments' => $departments
        ]);
    }
    
    /**
     * Process registration form submission
     */
    public function processRegister(): void
    {
        $publicUrl = \App\Core\Config::getPublicUrl();
        
        // Check if already logged in
        if (Auth::isMemberLoggedIn()) {
            header('Location: ' . $publicUrl . '/member/dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $publicUrl . '/register');
            exit;
        }
        
        // Store form data in session for repopulating the form if validation fails
        $_SESSION['old_input'] = $_POST;
        
        // Initialize validation errors array
        $validationErrors = [];
        
        // Sanitize and validate inputs
        $coop_no = trim($_POST['coop_no'] ?? '');
        $ti_number = trim($_POST['ti_number'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirmation = $_POST['password_confirmation'] ?? '';
        $terms = isset($_POST['terms']);
        
        // Validate coop_no
        if (empty($coop_no)) {
            $validationErrors['coop_no'] = 'Cooperative number is required';
        } elseif (Member::findByCoopNo($coop_no)) {
            $validationErrors['coop_no'] = 'This Cooperative number is already registered';
        }
        
        // Validate TI Number
        if (empty($ti_number)) {
            $validationErrors['ti_number'] = 'Treasury Integrated Number is required';
        } elseif (Member::findByTiNumber($ti_number)) {
            $validationErrors['ti_number'] = 'This TI Number is already registered';
        }
        
        // Validate name
        if (empty($name)) {
            $validationErrors['name'] = 'Full name is required';
        }
        
        // Validate email
        if (empty($email)) {
            $validationErrors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validationErrors['email'] = 'Please enter a valid email address';
        } elseif (Member::findByEmail($email)) {
            $validationErrors['email'] = 'This email is already registered';
        }
        
        // Validate phone
        if (empty($phone)) {
            $validationErrors['phone'] = 'Phone number is required';
        } elseif (!preg_match('/^[0-9]{11}$/', preg_replace('/[^0-9]/', '', $phone))) {
            $validationErrors['phone'] = 'Please enter a valid phone number';
        } elseif (Member::findByPhone($phone)) {
            $validationErrors['phone'] = 'This phone number is already registered';
        }
        
        // Validate department
        if (empty($department)) {
            $validationErrors['department'] = 'Department is required';
        }
        
        // Validate password
        if (empty($password)) {
            $validationErrors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $validationErrors['password'] = 'Password must be at least 8 characters long';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/', $password)) {
            $validationErrors['password'] = 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character';
        }
        
        // Validate password confirmation
        if ($password !== $password_confirmation) {
            $validationErrors['password_confirmation'] = 'Passwords do not match';
        }
        
        // Validate terms
        if (!$terms) {
            $validationErrors['terms'] = 'You must agree to the terms and conditions';
        }
        
        // If there are validation errors, redirect back to the form
        if (!empty($validationErrors)) {
            $_SESSION['validation_errors'] = $validationErrors;
            header('Location: ' . $publicUrl . '/register');
            exit;
        }
        
        // All validation passed, register the user
        try {
            // Create new member
            $member = new Member();
            $member->coop_no = $coop_no;
            $member->ti_number = $ti_number;
            
            // Parse name into first and last name
            $nameParts = explode(' ', $name);
            $member->first_name = $nameParts[0];
            $member->last_name = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
            
            $member->email = $email;
            $member->phone = $phone;
            $member->department_id = \App\Models\Department::getIdByName($department);
            $member->password = password_hash($password, PASSWORD_DEFAULT);
            $member->is_active = false; // Set as inactive by default, requires admin approval
            $member->status = 'pending'; // Set status to pending
            
            if ($member->save()) {
                // Registration successful
                unset($_SESSION['old_input'], $_SESSION['validation_errors']);
                
                // Create initial savings record
                $savings = new \App\Models\Savings();
                $savings->member_id = $member->id;
                $savings->monthly_deduction = 0; // Default value
                $savings->cumulative_amount = 0; // Initial value
                $savings->save();
                
                // Set flash message
                $this->setFlash('success', 'Your account has been registered successfully. An administrator will review and activate your account.', 'register_success');
                
                // Redirect to login page
                header('Location: ' . $publicUrl . '/login');
                exit;
            } else {
                throw new \Exception('Failed to create your account. Please try again.');
            }
        } catch (\Exception $e) {
            // Set error message
            $this->setFlash('error', $e->getMessage(), 'register_error');
            
            // Redirect back to registration form
            header('Location: ' . $publicUrl . '/register');
            exit;
        }
    }
    
    /**
     * Process user logout
     */
    public function logout(): void
    {
        $publicUrl = \App\Core\Config::getPublicUrl();
        
        // Clear remember cookie if exists
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            
            // Find the member with this token and clear it
            $member = Member::findByRememberToken($token);
            if ($member) {
                $member->remember_token = null;
                $member->token_expiry = null;
                $member->save();
            }
            
            // Delete the cookie
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        // Clear session
        Session::destroy();
        
        // Redirect to login page
        Session::setFlash('success', 'You have been logged out successfully');
        header('Location: ' . $publicUrl . '/login');
        exit;
    }
    
    /**
     * Check if a user has a valid remember-me token and log them in automatically
     */
    public function checkRememberToken(): void
    {
        // Only check if not already logged in
        if (!Session::isLoggedIn() && isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            
            // Find member with this token
            $member = Member::findByRememberToken($token);
            
            if ($member && strtotime($member->token_expiry) > time()) {
                // Valid token, log the user in
                Session::set('user_id', $member->id);
                Session::set('user_type', 'member');
                Session::set('coop_no', $member->coop_no);
                Session::set('name', $member->first_name . ' ' . $member->last_name);
                
                // Refresh token
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                $member->token_expiry = date('Y-m-d H:i:s', $expiry);
                $member->save();
                
                // Set new cookie
                setcookie('remember_token', $token, $expiry, '/', '', true, true);
            }
        }
    }
    
    /**
     * Display the admin login form
     */
    public function adminLogin(): void
    {
        // For debugging - show the URL and paths
        if (isset($_GET['debug'])) {
            echo "<pre>";
            echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
            echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
            echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
            echo "dirname(SCRIPT_NAME): " . dirname($_SERVER['SCRIPT_NAME']) . "\n";
            echo "</pre>";
            exit;
        }
        
        // Check if admin is already logged in
        if (Auth::isAdminLoggedIn()) {
            $this->redirect('/admin/dashboard');
            return;
        }
        
        // Get base URL for redirects
        $publicUrl = \App\Core\Config::getPublicUrl();
        // Remove /public from the end if it exists, as we want the base URL for the view
        $baseUrl = rtrim($publicUrl, '/public');
         // If publicUrl is empty (Vercel), baseUrl is empty
        if ($publicUrl === '') {
            $baseUrl = '';
        } elseif (substr($publicUrl, -7) === '/public') {
             $baseUrl = substr($publicUrl, 0, -7);
        } else {
             $baseUrl = $publicUrl;
        }
        
        // Display admin login form
        $this->render('auth/admin_login', [
            'pageTitle' => 'Admin Login',
            'baseUrl' => $baseUrl,
            'publicUrl' => $publicUrl
        ]);
    }
    
    /**
     * Process admin login form submission
     */
    public function processAdminLogin(): void
    {
        $publicUrl = \App\Core\Config::getPublicUrl();
        
        // Check if already logged in
        if (Auth::isAdminLoggedIn()) {
            if (Auth::isSuperAdmin()) {
                header('Location: ' . $publicUrl . '/superadmin/dashboard');
            } else {
                header('Location: ' . $publicUrl . '/admin/dashboard');
            }
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $publicUrl . '/admin/login');
            exit;
        }
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        $isValid = Validator::required($input, ['username', 'password']);
        
        if (!$isValid) {
            $errors = Validator::getErrors();
            $this->setFlash('error', reset($errors), 'admin_login'); // Use admin_login page identifier
            header('Location: ' . $publicUrl . '/admin/login');
            exit;
        }
        
        // Validate login credentials
        $admin = Auth::validateAdminLogin($input['username'], $input['password']);
        
        if ($admin) {
            // Login successful
            Auth::loginAdmin($admin);
            
            // Redirect based on role
            if ($admin['role'] === Admin::ROLE_SUPERADMIN) {
                header('Location: ' . $publicUrl . '/superadmin/dashboard');
            } else {
                header('Location: ' . $publicUrl . '/admin/dashboard');
            }
            
            exit;
        }
        
        // Login failed
        $this->setFlash('error', 'Invalid username or password', 'admin_login'); // Use admin_login page identifier
        header('Location: ' . $publicUrl . '/admin/login');
        exit;
    }
    
    /**
     * Process member login form submission
     */
    public function processLogin(): void
    {
        $publicUrl = \App\Core\Config::getPublicUrl();
        
        // Check if already logged in
        if (Auth::isMemberLoggedIn() || Session::isMember()) {
            header('Location: ' . $publicUrl . '/member/dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $publicUrl . '/login');
            exit;
        }
        
        // Sanitize input
        $input = $this->sanitizeInput($_POST);
        
        // Validate required fields
        $isValid = Validator::required($input, ['username', 'password']);
        
        if (!$isValid) {
            $errors = Validator::getErrors();
            $this->setFlash('error', reset($errors), 'member_login'); // Use member_login page identifier
            header('Location: ' . $publicUrl . '/login');
            exit;
        }
        
        // Validate login credentials
        $member = self::validateMemberLogin($input['username'], $input['password']);
        
        if ($member) {
            // Login successful
            Auth::loginMember($member);
            
            // Set additional session variables directly
            Session::set('user_id', $member['id']);
            Session::set('member_id', $member['id']);
            Session::set('user_type', 'member');
            
            // Redirect to dashboard
            header('Location: ' . $publicUrl . '/member/dashboard');
            exit;
        }
        
        // Login failed
        $this->setFlash('error', 'Invalid username or password', 'member_login'); // Use member_login page identifier
        header('Location: ' . $publicUrl . '/login');
        exit;
    }

    /**
     * Validate member login credentials
     *
     * @param string $coopNo
     * @param string $password
     * @return array|null Member data if valid, null otherwise
     */
    public static function validateMemberLogin(string $coopNo, string $password): ?array
    {
        $member = Database::fetchOne(
            "SELECT * FROM members WHERE coop_no = ?",
            [$coopNo]
        );
        
        if (!$member || !$member['password']) {
            return null;
        }
        
        // Check if account is locked or inactive
        if ((bool)$member['is_locked'] || !(bool)$member['is_active']) {
            return null;
        }
        
        // Verify password
        if (password_verify($password, $member['password'])) {
            // Update last login time
            Database::update('members', ['last_login' => date('Y-m-d H:i:s')], ['id' => $member['id']]);
            
            // If we have a name field but no first_name/last_name, split it
            if (isset($member['name']) && !isset($member['first_name'])) {
                $nameParts = explode(' ', $member['name'], 2);
                $member['first_name'] = $nameParts[0];
                $member['last_name'] = isset($nameParts[1]) ? $nameParts[1] : '';
            }
            
            return $member;
        }
        
        // Increment failed attempts
        $failedAttempts = (int)$member['failed_attempts'] + 1;
        $data = ['failed_attempts' => $failedAttempts];
        
        // Lock account after 5 failed attempts
        if ($failedAttempts >= 5) {
            $data['is_locked'] = true;
        }
        
        Database::update('members', $data, ['id' => $member['id']]);
        
        return null;
    }
} 