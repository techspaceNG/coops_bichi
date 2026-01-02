<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Member;
use App\Helpers\Session;
use App\Helpers\Validator;
use App\Core\Database;

/**
 * Member Controller
 * Handles member dashboard and profile management
 */
final class MemberController
{
    /**
     * Display member dashboard
     *
     * @return void
     */
    public function dashboard(): void
    {
        // Check if user is logged in and is a member
        if (!Session::isMember()) {
            Session::setFlash('error', 'You must be logged in as a member to access the dashboard');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member_id = Session::userId();
        
        // Check if member_id is null
        if ($member_id === null) {
            Session::setFlash('error', 'Session is invalid. Please log in again.');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member = Member::findById($member_id);
        
        if (!$member) {
            Session::setFlash('error', 'Member not found');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        // Get member financial summary
        $financialSummary = Member::getFinancialSummary($member_id);
        
        // Update member object with current balances from database
        $member->savings_balance = $financialSummary['savings_balance'] ?? 0.0;
        $member->loan_balance = $financialSummary['loan_balance'] ?? 0.0;
        $member->household_balance = $financialSummary['household_balance'] ?? 0.0;
        $member->shares_balance = $financialSummary['shares_balance'] ?? 0.0;
        
        // Get recent transactions - limit to 5 most recent
        $recent_transactions = $this->getRecentTransactions($member_id, 5);
        
        // Get notifications for dashboard
        try {
            // Direct SQL query to fetch notifications
            $db = Database::getConnection();
            
            // Get notification count
            $stmtCount = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND user_type = 'member' AND is_read = 0");
            $stmtCount->execute([$member_id]);
            $notification_count = (int)$stmtCount->fetchColumn();
            
            // Get recent notifications (limit to 2 for dashboard)
            $stmtNotifications = $db->prepare("
                SELECT * FROM notifications 
                WHERE user_id = ? AND user_type = 'member' 
                ORDER BY created_at DESC LIMIT 2
            ");
            $stmtNotifications->execute([$member_id]);
            $notifications = $stmtNotifications->fetchAll(\PDO::FETCH_ASSOC);
            
            // Log notification details to help debug link issues
            if (!empty($notifications)) {
                foreach ($notifications as $n) {
                    error_log("Notification #{$n['id']} has link: " . ($n['link'] ?? 'none'));
                }
            }
            
            error_log("Dashboard fetched " . count($notifications) . " notifications, unread count: $notification_count");
        } catch (\Exception $e) {
            // Log the error and provide empty data if notifications fail
            error_log('Error loading notifications on dashboard: ' . $e->getMessage());
            $notifications = [];
            $notification_count = 0;
        }
        
        try {
            // Get loan application status - limit to 2 most recent
            $loanApplications = Database::fetchAll(
                "SELECT id, loan_type, amount, status, created_at 
                FROM loans 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT 2",
                [$member_id]
            );
            
            // Get household purchase application status - limit to 2 most recent
            $householdApplications = Database::fetchAll(
                "SELECT id, item_name, item_cost, status, created_at 
                FROM household_purchases 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT 2",
                [$member_id]
            );
        } catch (\PDOException $e) {
            // Log the error
            error_log('Database error: ' . $e->getMessage());
            
            // If tables don't exist, set empty arrays
            $loanApplications = [];
            $householdApplications = [];
        }
        
        require_once APP_ROOT . '/views/members/dashboard.php';
    }
    
    /**
     * Display member profile
     *
     * @return void
     */
    public function profile(): void
    {
        // Check if user is logged in and is a member
        if (!Session::isMember()) {
            Session::setFlash('error', 'You must be logged in as a member to access your profile');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member_id = Session::userId();
        
        // Check if member_id is null
        if ($member_id === null) {
            Session::setFlash('error', 'Session is invalid. Please log in again.');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member = Member::findById($member_id);
        
        if (!$member) {
            Session::setFlash('error', 'Member not found');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        // Ensure department name is loaded
        if ($member->department_id && (empty($member->department) || $member->department === null)) {
            try {
                $departmentData = \App\Models\Department::getById($member->department_id);
                if ($departmentData) {
                    $member->department = $departmentData['name'];
                }
            } catch (\Exception $e) {
                error_log('Error fetching department: ' . $e->getMessage());
            }
        }
        
        $errors = Session::getFlash('errors', []);
        
        require_once APP_ROOT . '/views/members/profile.php';
    }
    
    /**
     * Update member profile
     */
    public function updateProfile(): void
    {
        // Check if user is logged in and is a member
        if (!Session::isMember()) {
            Session::setFlash('error', 'You must be logged in as a member to update your profile');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member_id = Session::userId();
        
        // Check if member_id is null
        if ($member_id === null) {
            Session::setFlash('error', 'Session is invalid. Please log in again.');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member = Member::findById($member_id);
        
        if (!$member) {
            Session::setFlash('error', 'Member not found');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $errors = [];
        
        // Validate input
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $ti_number = trim($_POST['ti_number'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate name
        if (empty($first_name)) {
            $errors['first_name'] = 'First name is required';
        }
        
        if (empty($last_name)) {
            $errors['last_name'] = 'Last name is required';
        }
        
        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!Validator::isValidEmail($email)) {
            $errors['email'] = 'Please enter a valid email address';
        } elseif ($email !== $member->email) {
            $existing_member = Member::findByEmail($email);
            if ($existing_member && $existing_member->id !== $member->id) {
                $errors['email'] = 'This email is already registered to another member';
            }
        }
        
        // Validate phone
        if (empty($phone)) {
            $errors['phone'] = 'Phone number is required';
        } elseif (!Validator::isValidPhone($phone)) {
            $errors['phone'] = 'Please enter a valid phone number';
        } elseif ($phone !== $member->phone) {
            $existing_member = Member::findByPhone($phone);
            if ($existing_member && $existing_member->id !== $member->id) {
                $errors['phone'] = 'This phone number is already registered to another member';
            }
        }
        
        // Validate department
        if (empty($department)) {
            $errors['department'] = 'Department is required';
        }
        
        // Password validation (only if user is trying to change password)
        if (!empty($new_password)) {
            // Verify current password
            if (empty($current_password)) {
                $errors['current_password'] = 'Current password is required to change password';
            } elseif (!password_verify($current_password, $member->password)) {
                $errors['current_password'] = 'Current password is incorrect';
            }
            
            // Validate new password
            if (strlen($new_password) < 8) {
                $errors['new_password'] = 'Password must be at least 8 characters long';
            } elseif (!Validator::isStrongPassword($new_password)) {
                $errors['new_password'] = 'Password must include uppercase, lowercase, and numbers';
            }
            
            // Validate confirm password
            if ($new_password !== $confirm_password) {
                $errors['confirm_password'] = 'Passwords do not match';
            }
        }
        
        // If no validation errors, update member
        if (empty($errors)) {
            $member->first_name = $first_name;
            $member->last_name = $last_name;
            $member->email = $email;
            $member->phone = $phone;
            $member->department = $department;
            $member->ti_number = $ti_number;
            
            // Get and set department_id
            try {
                $department_id = \App\Models\Department::getIdByName($department);
                if ($department_id) {
                    $member->department_id = $department_id;
                }
            } catch (\Exception $e) {
                error_log('Error getting department ID: ' . $e->getMessage());
            }
            
            // Update password if provided
            if (!empty($new_password)) {
                $member->password = password_hash($new_password, PASSWORD_DEFAULT);
            }
            
            if ($member->save()) {
                // Update session name
                Session::set('name', $member->first_name . ' ' . $member->last_name);
                
                Session::setFlash('success', 'Profile updated successfully');
                header('Location: /Coops_Bichi/public/member/profile');
                exit;
            } else {
                $errors['general'] = 'Failed to update profile';
            }
        }
        
        // If we get here, there were errors
        Session::setFlash('errors', $errors);
        header('Location: /Coops_Bichi/public/member/profile');
        exit;
    }
    
    /**
     * Change member password
     */
    public function changePassword(): void
    {
        // Check if user is logged in and is a member
        if (!Session::isMember()) {
            Session::setFlash('error', 'You must be logged in as a member to change your password');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member_id = Session::userId();
        
        // Check if member_id is null
        if ($member_id === null) {
            Session::setFlash('error', 'Session is invalid. Please log in again.');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member = Member::findById($member_id);
        
        if (!$member) {
            Session::setFlash('error', 'Member not found');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $errors = [];
        
        // Validate input
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Verify current password
        if (empty($current_password)) {
            $errors['current_password'] = 'Current password is required';
        } elseif (!password_verify($current_password, $member->password)) {
            $errors['current_password'] = 'Current password is incorrect';
        }
        
        // Validate new password
        if (empty($new_password)) {
            $errors['new_password'] = 'New password is required';
        } elseif (strlen($new_password) < 8) {
            $errors['new_password'] = 'Password must be at least 8 characters long';
        } elseif (!Validator::isStrongPassword($new_password)) {
            $errors['new_password'] = 'Password must include uppercase, lowercase, and numbers';
        }
        
        // Validate confirm password
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        // If no validation errors, update password
        if (empty($errors)) {
            $member->password = password_hash($new_password, PASSWORD_DEFAULT);
            
            if ($member->save()) {
                Session::setFlash('success', 'Password changed successfully');
                header('Location: /Coops_Bichi/public/member/profile');
                exit;
            } else {
                $errors['general'] = 'Failed to change password';
            }
        }
        
        // If we get here, there were errors
        Session::setFlash('errors', $errors);
        header('Location: /Coops_Bichi/public/member/profile');
        exit;
    }
    
    /**
     * Update bank information
     */
    public function updateBankInfo(): void
    {
        // Check if user is logged in and is a member
        if (!Session::isMember()) {
            Session::setFlash('error', 'You must be logged in as a member to update your bank information');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member_id = Session::userId();
        
        // Check if member_id is null
        if ($member_id === null) {
            Session::setFlash('error', 'Session is invalid. Please log in again.');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $member = Member::findById($member_id);
        
        if (!$member) {
            Session::setFlash('error', 'Member not found');
            header('Location: /Coops_Bichi/public/login');
            exit;
        }
        
        $errors = [];
        
        // Validate input
        $bank_name = trim($_POST['bank_name'] ?? '');
        $account_number = trim($_POST['account_number'] ?? '');
        $account_name = trim($_POST['account_name'] ?? '');
        $bvn = trim($_POST['bvn'] ?? '');
        
        // Validate bank name
        if (empty($bank_name)) {
            $errors['bank_name'] = 'Bank name is required';
        }
        
        // Validate account number
        if (empty($account_number)) {
            $errors['account_number'] = 'Account number is required';
        } elseif (!preg_match('/^\d{10}$/', $account_number)) {
            $errors['account_number'] = 'Account number must be 10 digits';
        }
        
        // Validate account name
        if (empty($account_name)) {
            $errors['account_name'] = 'Account name is required';
        }
        
        // Validate BVN if provided
        if (!empty($bvn) && !preg_match('/^\d{11}$/', $bvn)) {
            $errors['bvn'] = 'BVN must be 11 digits';
        }
        
        if (empty($errors)) {
            $member->bank_name = $bank_name;
            $member->account_number = $account_number;
            $member->account_name = $account_name;
            $member->bvn = $bvn;
            
            if ($member->save()) {
                Session::setFlash('success', 'Bank information updated successfully');
                header('Location: /Coops_Bichi/public/member/profile');
                exit;
            } else {
                $errors['general'] = 'Failed to update bank information';
            }
        }
        
        // If we get here, there were errors
        Session::setFlash('errors', $errors);
        header('Location: /Coops_Bichi/public/member/profile');
        exit;
    }
    
    /**
     * Get member's recent transactions
     * 
     * @param int $member_id Member ID
     * @param int $limit Limit number of transactions
     * @return array Recent transactions
     */
    private function getRecentTransactions(int $member_id, int $limit = 5): array
    {
        try {
            // Try to fetch transactions from the database
            
            // Get savings transactions
            $savingsTransactions = Database::fetchAll(
                "SELECT 
                    'savings' as type, 
                    created_at as date, 
                    amount, 
                    transaction_type as status,
                    description
                FROM savings_transactions 
                WHERE member_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?",
                [$member_id, $limit]
            );
            
            // If database query successful, return the results
            if ($savingsTransactions !== false) {
                return $savingsTransactions;
            }
            
            // If we reached here, there was a database error, so fall back to sample data
            return $this->getSampleTransactions();
        } catch (\Exception $e) {
            // If there's an error, log it and return sample data
            error_log('Error fetching transactions: ' . $e->getMessage());
            return $this->getSampleTransactions();
        }
    }
    
    /**
     * Get sample transactions data for demonstration
     * 
     * @return array Sample transactions
     */
    private function getSampleTransactions(): array
    {
        return [
            [
                'id' => 1,
                'date' => date('Y-m-d', strtotime('-2 days')),
                'type' => 'savings',
                'description' => 'Monthly savings contribution',
                'amount' => 5000.00,
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'date' => date('Y-m-d', strtotime('-5 days')),
                'type' => 'loan',
                'description' => 'Loan repayment installment',
                'amount' => -2500.00,
                'status' => 'completed'
            ],
            [
                'id' => 3,
                'date' => date('Y-m-d', strtotime('-10 days')),
                'type' => 'household',
                'description' => 'Purchase of household items',
                'amount' => -15000.00,
                'status' => 'completed'
            ],
            [
                'id' => 4,
                'date' => date('Y-m-d', strtotime('-15 days')),
                'type' => 'savings',
                'description' => 'Withdrawal request',
                'amount' => -10000.00,
                'status' => 'pending'
            ],
            [
                'id' => 5,
                'date' => date('Y-m-d', strtotime('-20 days')),
                'type' => 'loan',
                'description' => 'Loan application',
                'amount' => 50000.00,
                'status' => 'completed'
            ]
        ];
    }

    /**
     * Display all notifications for the logged-in member
     */
    public function notifications()
    {
        // Check if user is logged in
        if (!Session::isMember()) {
            header('Location: /Coops_Bichi/public/login');
            exit;
        }

        $member_id = Session::userId();
        $member = \App\Models\Member::findById($member_id);

        if (!$member) {
            header('Location: /Coops_Bichi/public/login');
            exit;
        }

        // Debug: Log the member ID to error log
        error_log("Loading notifications for member ID: $member_id");

        // Implement pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10; // Number of notifications per page
        $offset = ($page - 1) * $perPage;
        
        try {
            $db = Database::getConnection();
            
            // Get total count for pagination
            $stmtCount = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND user_type = 'member'");
            $stmtCount->execute([$member_id]);
            $totalCount = (int)$stmtCount->fetchColumn();
            $totalPages = ceil($totalCount / $perPage);
            
            // Get unread count
            $stmtUnread = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND user_type = 'member' AND is_read = 0");
            $stmtUnread->execute([$member_id]);
            $unread_count = (int)$stmtUnread->fetchColumn();
            
            // Get paginated results
            $stmtNotifications = $db->prepare("
                SELECT * FROM notifications 
                WHERE user_id = ? AND user_type = 'member' 
                ORDER BY created_at DESC LIMIT ? OFFSET ?
            ");
            $stmtNotifications->bindValue(1, $member_id, \PDO::PARAM_INT);
            $stmtNotifications->bindValue(2, $perPage, \PDO::PARAM_INT);
            $stmtNotifications->bindValue(3, $offset, \PDO::PARAM_INT);
            $stmtNotifications->execute();
            $notifications = $stmtNotifications->fetchAll(\PDO::FETCH_ASSOC);
            
            error_log("Found {$totalCount} total notifications, {$unread_count} unread, fetched " . count($notifications) . " for current page");
            
            // If no notifications and it's the first page, create a welcome notification
            if (count($notifications) === 0 && $page === 1 && $totalCount === 0) {
                error_log("No notifications found, creating welcome notification for member $member_id");
                
                try {
                    $insertStmt = $db->prepare("
                        INSERT INTO notifications (user_id, user_type, title, message, type, link, is_read, created_at, updated_at)
                        VALUES (?, 'member', 'Welcome to Cooperative Portal', 'Thank you for using the portal. This is your notification area.', 'info', '/Coops_Bichi/public/member/dashboard', 0, NOW(), NOW())
                    ");
                    $insertStmt->execute([$member_id]);
                    
                    // Fetch the newly created notification
                    $newId = $db->lastInsertId();
                    $stmtNew = $db->prepare("SELECT * FROM notifications WHERE id = ?");
                    $stmtNew->execute([$newId]);
                    $notifications = $stmtNew->fetchAll(\PDO::FETCH_ASSOC);
                    
                    // Update counts
                    $totalCount = 1;
                    $totalPages = 1;
                    $unread_count = 1;
                    
                    error_log("Created welcome notification with ID: $newId");
                } catch (\Exception $e) {
                    error_log("Error creating welcome notification: " . $e->getMessage());
                }
            }
            
            // Set title and other variables
            $title = 'My Notifications';
            $current_page = $page;
            
            // Render the view directly
            require_once APP_ROOT . '/views/members/notifications.php';
        } catch (\Exception $e) {
            // Log error and display empty notifications
            error_log('Error loading notifications: ' . $e->getMessage());
            
            // Set fallback variables
            $notifications = [];
            $notification_count = 0;
            $title = 'My Notifications';
            $current_page = 1;
            $total_pages = 1;
            $error_message = 'Unable to load notifications. Please try again later.';
            
            // Render the view with error message
            require_once APP_ROOT . '/views/members/notifications.php';
        }
    }

    /**
     * Mark a specific notification as read
     * 
     * @param int $id Notification ID
     */
    public function markNotificationRead($id)
    {
        // Check if user is logged in
        if (!Session::isMember()) {
            header('Location: /Coops_Bichi/public/login');
            exit;
        }

        $member_id = Session::userId();
        
        // Log request method
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
        error_log("markNotificationRead called with method: $requestMethod for notification ID $id and member $member_id");
        
        try {
            // First verify the notification exists
            $db = Database::getConnection();
            $checkStmt = $db->prepare("
                SELECT id, is_read FROM notifications 
                WHERE id = ? AND user_id = ? AND user_type = 'member'
            ");
            $checkStmt->execute([(int)$id, $member_id]);
            $notification = $checkStmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$notification) {
                throw new \Exception("Notification ID $id does not exist for member $member_id");
            }
            
            error_log("Found notification ID $id, current read status: " . ($notification['is_read'] ? 'read' : 'unread'));
            
            // Only update if not already read
            if (!$notification['is_read']) {
                // Use a simpler direct query for testing
                $sql = "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE id = " . (int)$id . " AND user_id = " . $member_id . " AND user_type = 'member'";
                error_log("SQL Query: $sql");
                
                $rowCount = $db->exec($sql);
                error_log("Direct SQL execution affected rows: $rowCount");
                
                if ($rowCount === 0) {
                    error_log("WARNING: Update query did not affect any rows. SQL might have failed silently.");
                }
            } else {
                error_log("Notification already marked as read, skipping update");
                $rowCount = 0;
            }
            
            // Verify the update was successful
            $verifyStmt = $db->prepare("
                SELECT is_read FROM notifications 
                WHERE id = ? AND user_id = ? AND user_type = 'member'
            ");
            $verifyStmt->execute([(int)$id, $member_id]);
            $updatedNotification = $verifyStmt->fetch(\PDO::FETCH_ASSOC);
            
            error_log("After update, notification read status: " . ($updatedNotification['is_read'] ? 'read' : 'unread'));
            
            // Check if this is an AJAX request (multiple check methods for compatibility)
            $isAjax = (
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
                (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            );
            
            // Also consider it AJAX if the request method is GET
            $isProbablyAjax = $isAjax || ($requestMethod === 'GET');
            
            error_log("Request type for mark read: " . ($isProbablyAjax ? "AJAX/GET" : "POST Form"));
                      
            if ($isProbablyAjax) {
                // Return JSON response for AJAX requests
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Notification marked as read',
                    'notification_id' => $id,
                    'affected_rows' => $rowCount,
                    'is_read' => $updatedNotification['is_read'] ? true : false
                ]);
                exit;
            } else {
                // Set flash message for success
                Session::setFlash('success', 'Notification marked as read');
                
                // Redirect back to notifications or the action URL if available
                $referer = $_SERVER['HTTP_REFERER'] ?? null;
                if ($referer) {
                    header('Location: ' . $referer);
                } else {
                    header('Location: /Coops_Bichi/public/member/notifications');
                }
                exit;
            }
        } catch (\Exception $e) {
            error_log('Failed to mark notification as read: ' . $e->getMessage());
            if (isset($db)) {
                error_log('SQL Error Info: ' . print_r($db->errorInfo(), true));
            }
            
            // Check if this is an AJAX request
            $isAjax = (
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
                (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            );
            
            // Also consider it AJAX if the request method is GET
            $isProbablyAjax = $isAjax || ($requestMethod === 'GET');
                      
            if ($isProbablyAjax) {
                // Return JSON error response for AJAX requests
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Failed to mark notification as read',
                    'error' => $e->getMessage()
                ]);
                exit;
            } else {
                Session::setFlash('error', 'Failed to mark notification as read: ' . $e->getMessage());
                header('Location: /Coops_Bichi/public/member/notifications');
                exit;
            }
        }
    }

    /**
     * Mark all notifications as read for the logged-in member
     */
    public function markAllNotificationsRead()
    {
        // Check if user is logged in
        if (!Session::isMember()) {
            header('Location: /Coops_Bichi/public/login');
            exit;
        }

        $member_id = Session::userId();
        
        // Log request method
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
        error_log("markAllNotificationsRead called with method: $requestMethod for member $member_id");
        
        try {
            // First check how many unread notifications exist
            $db = Database::getConnection();
            $checkStmt = $db->prepare("
                SELECT COUNT(*) as unread_count FROM notifications 
                WHERE user_id = ? AND user_type = 'member' AND is_read = 0
            ");
            $checkStmt->execute([$member_id]);
            $result = $checkStmt->fetch(\PDO::FETCH_ASSOC);
            $unreadCount = (int)$result['unread_count'];
            
            error_log("Found $unreadCount unread notifications for member $member_id");
            
            if ($unreadCount > 0) {
                // Use a simpler direct query for testing
                $sql = "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE user_id = " . $member_id . " AND user_type = 'member' AND is_read = 0";
                error_log("SQL Query: $sql");
                
                $rowCount = $db->exec($sql);
                error_log("Direct SQL execution affected rows: $rowCount");
                
                if ($rowCount === 0) {
                    error_log("WARNING: Update query did not affect any rows despite finding unread notifications.");
                } else if ($rowCount !== $unreadCount) {
                    error_log("WARNING: Update affected $rowCount rows but found $unreadCount unread notifications.");
                }
            } else {
                error_log("No unread notifications found, skipping update");
                $rowCount = 0;
            }
            
            // Verify the update was successful
            $verifyStmt = $db->prepare("
                SELECT COUNT(*) as unread_count FROM notifications 
                WHERE user_id = ? AND user_type = 'member' AND is_read = 0
            ");
            $verifyStmt->execute([$member_id]);
            $verifyResult = $verifyStmt->fetch(\PDO::FETCH_ASSOC);
            $remainingUnread = (int)$verifyResult['unread_count'];
            
            error_log("After update, remaining unread notifications: $remainingUnread");
            
            // Check if this is an AJAX request (multiple check methods for compatibility)
            $isAjax = (
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
                (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            );
            
            // Also consider it AJAX if the request method is GET
            $isProbablyAjax = $isAjax || ($requestMethod === 'GET');
            
            error_log("Request type for mark all read: " . ($isProbablyAjax ? "AJAX/GET" : "POST Form"));
                      
            if ($isProbablyAjax) {
                // Return JSON response for AJAX requests
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => "$rowCount notifications marked as read",
                    'affected_rows' => $rowCount,
                    'remaining_unread' => $remainingUnread
                ]);
                exit;
            } else {
                // Set flash message for success
                Session::setFlash('success', "$rowCount notifications marked as read");
                
                // Redirect back to notifications
                header('Location: /Coops_Bichi/public/member/notifications');
                exit;
            }
        } catch (\Exception $e) {
            error_log('Failed to mark all notifications as read: ' . $e->getMessage());
            if (isset($db)) {
                error_log('SQL Error Info: ' . print_r($db->errorInfo(), true));
            }
            
            // Check if this is an AJAX request
            $isAjax = (
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
                (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            );
            
            // Also consider it AJAX if the request method is GET
            $isProbablyAjax = $isAjax || ($requestMethod === 'GET');
                      
            if ($isProbablyAjax) {
                // Return JSON error response for AJAX requests
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Failed to mark all notifications as read',
                    'error' => $e->getMessage()
                ]);
                exit;
            } else {
                Session::setFlash('error', 'Failed to mark all notifications as read: ' . $e->getMessage());
                header('Location: /Coops_Bichi/public/member/notifications');
                exit;
            }
        }
    }
} 