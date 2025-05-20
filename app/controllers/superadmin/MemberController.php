<?php
declare(strict_types=1);

namespace App\Controllers\Superadmin;

use App\Core\Database;
use App\Helpers\Auth;
use App\Helpers\Validator;

/**
 * MemberController for Superadmin
 * Handles member management functionality
 */
final class MemberController extends AbstractController
{
    /**
     * Display list of members
     */
    public function index(): void
    {
        // Get departments for filtering
        $departments = Database::fetchAll("SELECT id, name FROM departments ORDER BY name ASC");
        
        // Get filter parameters
        $isActive = $_GET['is_active'] ?? '';
        $departmentId = $_GET['department'] ?? '';
        $search = $_GET['search'] ?? '';
        $joinDate = $_GET['join_date'] ?? '';
        
        // Build query conditions for filtering
        $conditions = [];
        $params = [];
        
        if ($isActive !== '') {
            $conditions[] = "m.is_active = ?";
            $params[] = (int)$isActive;
        }
        
        if (!empty($departmentId)) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if (!empty($search)) {
            $conditions[] = "(m.name LIKE ? OR m.coop_no LIKE ? OR m.email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($joinDate)) {
            $dates = explode(' to ', $joinDate);
            if (count($dates) == 2) {
                $conditions[] = "m.created_at BETWEEN ? AND ?";
                $params[] = $dates[0] . ' 00:00:00';
                $params[] = $dates[1] . ' 23:59:59';
            }
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        // Pagination
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        
        // Count total records
        $countQuery = "
            SELECT COUNT(*) as total 
            FROM members m
            LEFT JOIN departments d ON m.department_id = d.id
            {$whereClause}
        ";
        $countResult = Database::fetchOne($countQuery, $params);
        $total = (int)($countResult['total'] ?? 0);
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        if ($page < 1) $page = 1;
        if ($totalPages > 0 && $page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;
        
        // Get members
        $query = "
            SELECT 
                m.id,
                m.coop_no,
                m.name,
                m.email,
                m.phone,
                d.name as department_name,
                m.is_active,
                m.created_at
            FROM 
                members m
            LEFT JOIN 
                departments d ON m.department_id = d.id
            {$whereClause}
            ORDER BY 
                m.created_at DESC
            LIMIT 
                {$offset}, {$perPage}
        ";
        
        $members = Database::fetchAll($query, $params);
        
        // Calculate member statistics
        $stats = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
            'pending' => 0,
            'departments' => 0,
            'new_this_month' => 0
        ];
        
        // Total members
        $totalQuery = Database::fetchOne("SELECT COUNT(*) as count FROM members");
        $stats['total'] = $totalQuery['count'] ?? 0;
        
        // Active members
        $activeQuery = Database::fetchOne("SELECT COUNT(*) as count FROM members WHERE is_active = 1");
        $stats['active'] = $activeQuery['count'] ?? 0;
        
        // Inactive members
        $inactiveQuery = Database::fetchOne("SELECT COUNT(*) as count FROM members WHERE is_active = 0");
        $stats['inactive'] = $inactiveQuery['count'] ?? 0;
        
        // Pending members (just use is_active = 0 as there is no status column)
        $pendingQuery = Database::fetchOne("SELECT COUNT(*) as count FROM members WHERE is_active = 0");
        $stats['pending'] = $pendingQuery['count'] ?? 0;
        
        // Total departments
        $departmentsQuery = Database::fetchOne("SELECT COUNT(*) as count FROM departments");
        $stats['departments'] = $departmentsQuery['count'] ?? 0;
        
        // New members this month
        $currentMonth = date('Y-m-01 00:00:00');
        $newMembersQuery = Database::fetchOne(
            "SELECT COUNT(*) as count FROM members WHERE created_at >= ?",
            [$currentMonth]
        );
        $stats['new_this_month'] = $newMembersQuery['count'] ?? 0;
        
        // Prepare pagination query string
        $queryString = '';
        foreach ($_GET as $key => $value) {
            if ($key !== 'page') {
                $queryString .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }
        
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'query_string' => $queryString
        ];
        
        $this->renderSuperAdmin('superadmin/modules/members', [
            'members' => $members,
            'departments' => $departments,
            'stats' => $stats,
            'pagination' => $pagination,
            'current_page' => 'members',
            'pageTitle' => 'Members Management'
        ]);
    }
    
    /**
     * Export members to CSV
     */
    public function export(): void
    {
        // Get filter parameters (if any)
        $isActive = isset($_GET['is_active']) ? $_GET['is_active'] : null;
        $departmentId = isset($_GET['department']) ? $_GET['department'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $joinDate = isset($_GET['join_date']) ? $_GET['join_date'] : null;
        
        // Build query conditions based on filters
        $conditions = [];
        $params = [];
        
        if ($isActive !== null && $isActive !== '') {
            $conditions[] = "m.is_active = ?";
            $params[] = $isActive;
        }
        
        if ($departmentId) {
            $conditions[] = "m.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($search) {
            $conditions[] = "(m.name LIKE ? OR m.coop_no LIKE ? OR m.email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if ($joinDate) {
            $dates = explode(' to ', $joinDate);
            if (count($dates) == 2) {
                $conditions[] = "m.created_at BETWEEN ? AND ?";
                $params[] = $dates[0] . ' 00:00:00';
                $params[] = $dates[1] . ' 23:59:59';
            }
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        // Get members based on filters
        $query = "
            SELECT 
                m.id,
                m.coop_no,
                m.name,
                m.email,
                m.phone,
                d.name as department_name,
                m.is_active,
                m.created_at
            FROM 
                members m
            LEFT JOIN 
                departments d ON m.department_id = d.id
            {$whereClause}
            ORDER BY 
                m.name ASC
        ";
        
        $members = Database::fetchAll($query, $params);
        
        // Set headers for Excel download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="members_export_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add headers to CSV
        fputcsv($output, [
            'COOPS No.',
            'Name',
            'Email',
            'Phone',
            'Department',
            'Status',
            'Join Date'
        ]);
        
        // Add data to CSV
        foreach ($members as $member) {
            fputcsv($output, [
                $member['coop_no'],
                $member['name'],
                $member['email'],
                $member['phone'] ?? 'N/A',
                $member['department_name'] ?? 'Not Assigned',
                $member['is_active'] ? 'Active' : 'Inactive',
                date('Y-m-d', strtotime($member['created_at']))
            ]);
        }
        
        // Close stream and exit
        fclose($output);
        exit;
    }
    
    /**
     * Show form to add a new member and handle form submission
     */
    public function create(): void
    {
        // Get departments for dropdown
        $departments = Database::fetchAll("SELECT id, name FROM departments ORDER BY name ASC");
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            $errors = [];
            
            $coop_no = trim($_POST['coop_no'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            
            if (empty($coop_no)) {
                $errors[] = 'COOPS No. is required';
            }
            
            if (empty($name)) {
                $errors[] = 'Name is required';
            }
            
            if (empty($email)) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email format is invalid';
            }
            
            // Check if member already exists
            $existingMember = Database::fetchOne(
                "SELECT id FROM members WHERE coop_no = ? OR email = ?",
                [$coop_no, $email]
            );
            
            if ($existingMember) {
                $errors[] = 'A member with this COOPS No. or email already exists';
            }
            
            // If no errors, insert the new member
            if (empty($errors)) {
                // Generate a default password (can be changed later)
                $defaultPassword = substr(md5(uniqid()), 0, 8); // 8-character password
                $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);
                
                // Convert monetary values to proper format
                $savings_balance = floatval($_POST['savings_balance'] ?? 0);
                $loan_balance = floatval($_POST['loan_balance'] ?? 0);
                $household_balance = floatval($_POST['household_balance'] ?? 0);
                $shares_balance = floatval($_POST['shares_balance'] ?? 0);
                
                // Prepare member data
                $memberData = [
                    'coop_no' => $coop_no,
                    'ti_number' => !empty($_POST['ti_number']) ? trim($_POST['ti_number']) : null,
                    'name' => $name,
                    'email' => $email,
                    'phone' => !empty($_POST['phone']) ? trim($_POST['phone']) : null,
                    'address' => !empty($_POST['address']) ? trim($_POST['address']) : null,
                    'department_id' => !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null,
                    'password' => $hashedPassword,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0,
                    'savings_balance' => $savings_balance,
                    'loan_balance' => $loan_balance,
                    'household_balance' => $household_balance,
                    'shares_balance' => $shares_balance,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    
                    // Bank details
                    'account_number' => !empty($_POST['account_number']) ? trim($_POST['account_number']) : null,
                    'bank_name' => !empty($_POST['bank_name']) ? trim($_POST['bank_name']) : null,
                    'account_name' => !empty($_POST['account_name']) ? trim($_POST['account_name']) : null,
                    'bvn' => !empty($_POST['bvn']) ? trim($_POST['bvn']) : null
                ];
                
                // Insert new member
                $memberId = Database::insert('members', $memberData);
                
                if ($memberId) {
                    // Log the action
                    Auth::logAction(
                        'admin', 
                        Auth::getAdminId(), 
                        "Added new member: {$name} ({$coop_no})",
                        ['type' => 'member']
                    );
                    
                    $this->setFlash('success', "Member '{$name}' added successfully. The temporary password is: {$defaultPassword}");
                    $this->redirect('/superadmin/members');
                    return;
                } else {
                    $errors[] = 'Failed to add member. Please try again.';
                }
            }
            
            // If there are errors, display them and the form again
            $this->setFlash('error', implode('<br>', $errors));
        }
        
        // Display the form (for both GET and failed POST)
        $this->renderSuperAdmin('superadmin/add-member', [
            'departments' => $departments,
            'current_page' => 'members',
            'pageTitle' => 'Add New Member'
        ]);
    }
    
    /**
     * Show form to upload members in bulk
     */
    public function upload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $filePath = $file['tmp_name'];
                $fileName = $file['name'];
                
                // Check if file is CSV
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($fileExt !== 'csv') {
                    $this->setFlash('error', 'Please upload a CSV file.');
                    $this->redirect('/superadmin/upload-members');
                    return;
                }
                
                // Process the CSV file
                $this->processMemberCsvFile($filePath);
                return;
            } else {
                $this->setFlash('error', 'Please select a file to upload.');
                $this->redirect('/superadmin/upload-members');
                return;
            }
        }
        
        // Show upload form
        $this->renderSuperAdmin('superadmin/upload-members', [
            'current_page' => 'members',
            'pageTitle' => 'Bulk Upload Members'
        ]);
    }
    
    /**
     * Process the uploaded CSV file for member import
     */
    private function processMemberCsvFile(string $filePath): void
    {
        // Open the file
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->setFlash('error', 'Could not open the file.');
            $this->redirect('/superadmin/upload-members');
            return;
        }
        
        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->setFlash('error', 'Could not read the file headers.');
            $this->redirect('/superadmin/upload-members');
            return;
        }
        
        // Expected headers (required fields)
        $expectedHeaders = ['coop_no', 'name', 'email', 'phone', 'department', 'address'];
        
        // Optional headers that will be processed if present
        $optionalHeaders = [
            'ti_number', 'account_number', 'bank_name', 'account_name', 'bvn',
            'savings_balance', 'loan_balance', 'household_balance', 'shares_balance', 'is_active'
        ];
        
        // Validate headers
        $headersValid = true;
        foreach ($expectedHeaders as $header) {
            if (!in_array($header, $headers)) {
                $headersValid = false;
                break;
            }
        }
        
        if (!$headersValid) {
            fclose($handle);
            $this->setFlash('error', 'CSV file does not have the required headers: ' . implode(', ', $expectedHeaders));
            $this->redirect('/superadmin/upload-members');
            return;
        }
        
        // Get department mapping
        $departments = Database::fetchAll("SELECT id, name FROM departments");
        $departmentMap = [];
        foreach ($departments as $dept) {
            $departmentMap[strtolower($dept['name'])] = $dept['id'];
        }
        
        // Process each row
        $processed = 0;
        $success = 0;
        $failed = 0;
        $errors = [];
        $passwords = [];
        
        while (($row = fgetcsv($handle)) !== false) {
            $processed++;
            
            // Create associative array from row
            $data = array_combine($headers, $row);
            
            // Validate required fields
            if (empty($data['coop_no']) || empty($data['name']) || empty($data['email'])) {
                $failed++;
                $errors[] = "Row {$processed}: Missing required fields (coop_no, name, or email).";
                continue;
            }
            
            // Check if member already exists
            $existingMember = Database::fetchOne(
                "SELECT id FROM members WHERE coop_no = ? OR email = ?",
                [$data['coop_no'], $data['email']]
            );
            
            if ($existingMember) {
                $failed++;
                $errors[] = "Row {$processed}: Member with COOPS No. {$data['coop_no']} or email {$data['email']} already exists.";
                continue;
            }
            
            // Map department name to ID
            $departmentId = null;
            if (!empty($data['department'])) {
                $deptKey = strtolower(trim($data['department']));
                if (isset($departmentMap[$deptKey])) {
                    $departmentId = $departmentMap[$deptKey];
                } else {
                    // Create new department if it doesn't exist
                    $insertedId = Database::insert('departments', [
                        'name' => trim($data['department']),
                        'description' => 'Created from bulk import'
                    ]);
                    
                    if ($insertedId) {
                        $departmentMap[$deptKey] = $insertedId;
                        $departmentId = $insertedId;
                    }
                }
            }
            
            // Generate a default password (can be changed later)
            $defaultPassword = substr(md5(uniqid()), 0, 8); // 8-character password
            $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);
            
            // Prepare member data with required fields
            $memberData = [
                'coop_no' => $data['coop_no'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'department_id' => $departmentId,
                'address' => $data['address'] ?? null,
                'password' => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Add optional fields if they exist in the CSV
            $memberData['ti_number'] = $data['ti_number'] ?? null;
            $memberData['account_number'] = $data['account_number'] ?? null;
            $memberData['bank_name'] = $data['bank_name'] ?? null;
            $memberData['account_name'] = $data['account_name'] ?? null;
            $memberData['bvn'] = $data['bvn'] ?? null;
            
            // Process numeric values
            $memberData['savings_balance'] = isset($data['savings_balance']) ? floatval($data['savings_balance']) : 0;
            $memberData['loan_balance'] = isset($data['loan_balance']) ? floatval($data['loan_balance']) : 0;
            $memberData['household_balance'] = isset($data['household_balance']) ? floatval($data['household_balance']) : 0;
            $memberData['shares_balance'] = isset($data['shares_balance']) ? floatval($data['shares_balance']) : 0;
            
            // Set is_active (default to 1/active if not specified)
            $memberData['is_active'] = isset($data['is_active']) ? (int)$data['is_active'] : 1;
            
            // Insert new member
            $memberId = Database::insert('members', $memberData);
            
            if ($memberId) {
                $success++;
                $passwords[] = $defaultPassword;
            } else {
                $failed++;
                $errors[] = "Row {$processed}: Failed to insert member {$data['name']}.";
            }
        }
        
        // Close file
        fclose($handle);
        
        // Set flash message with results
        $message = "Import completed. Processed: {$processed}, Success: {$success}, Failed: {$failed}";
        
        if (!empty($errors)) {
            $message .= ". First few errors: " . implode("; ", array_slice($errors, 0, 3));
            if (count($errors) > 3) {
                $message .= "... and " . (count($errors) - 3) . " more.";
            }
        }
        
        // Store passwords in session for display
        if (!empty($passwords) && $success > 0) {
            // Only show the first 5 passwords to avoid cluttering the UI
            $passwordMessage = "<br><br>Generated passwords for the first " . min(5, count($passwords)) . " members:<br>";
            $count = 0;
            foreach ($passwords as $i => $pwd) {
                if ($count >= 5) break;
                $name = $data['name'] ?? "Member " . ($i + 1);
                $passwordMessage .= "- {$name}: <code>{$pwd}</code><br>";
                $count++;
            }
            
            if (count($passwords) > 5) {
                $passwordMessage .= "... and " . (count($passwords) - 5) . " more.";
            }
            
            $message .= $passwordMessage;
        }
        
        $this->setFlash($failed > 0 ? 'warning' : 'success', $message);
        $this->redirect('/superadmin/members');
    }
    
    /**
     * View member details
     */
    public function view(string $id): void
    {
        $id = (int)$id; // Cast to integer for database operations
        
        // Get member details
        $member = Database::fetchOne("
            SELECT 
                m.*,
                d.name as department_name
            FROM 
                members m
            LEFT JOIN 
                departments d ON m.department_id = d.id
            WHERE 
                m.id = ?
        ", [$id]);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found.');
            $this->redirect('/superadmin/members');
            return;
        }
        
        // Get savings information
        $savings = Database::fetchOne("
            SELECT * FROM savings WHERE member_id = ?
        ", [$id]);
        
        // Check if member has savings_balance but no savings record
        if (!$savings && $member['savings_balance'] > 0) {
            // Create a new savings record for the member
            $savingsData = [
                'member_id' => $id,
                'monthly_deduction' => 0, // Default value, can be updated later
                'cumulative_amount' => $member['savings_balance'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $inserted = Database::insert('savings', $savingsData);
            
            if ($inserted) {
                // Fetch the newly created record
                $savings = Database::fetchOne("
                    SELECT * FROM savings WHERE member_id = ?
                ", [$id]);
                
                // Log the action
                Auth::logAction(
                    'admin',
                    Auth::getAdminId(),
                    "Created missing savings record for member #$id with balance of ₦" . number_format((float)$member['savings_balance'], 2),
                    ['type' => 'savings']
                );
            }
        }
        
        // Get loan information
        $loans = Database::fetchAll("
            SELECT * FROM loans WHERE member_id = ? ORDER BY created_at DESC
        ", [$id]);
        
        // Get household purchases
        $household = Database::fetchAll("
            SELECT * FROM household_purchases WHERE member_id = ? ORDER BY created_at DESC
        ", [$id]);
        
        $this->renderSuperAdmin('superadmin/view-member', [
            'member' => $member,
            'savings' => $savings,
            'loans' => $loans,
            'household' => $household,
            'current_page' => 'members',
            'pageTitle' => 'View Member: ' . $member['name']
        ]);
    }
    
    /**
     * Show form to edit a member
     */
    public function edit(string $id): void
    {
        $id = (int)$id; // Cast to integer for database operations
        
        // Get member details
        $member = Database::fetchOne("SELECT * FROM members WHERE id = ?", [$id]);
        
        if (!$member) {
            $this->setFlash('error', 'Member not found.');
            $this->redirect('/superadmin/members');
            return;
        }
        
        // Get departments for dropdown
        $departments = Database::fetchAll("SELECT id, name FROM departments ORDER BY name ASC");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form submission
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $ti_number = trim($_POST['ti_number'] ?? '');
            $departmentId = isset($_POST['department_id']) && $_POST['department_id'] !== '' ? (int)$_POST['department_id'] : null;
            $address = trim($_POST['address'] ?? '');
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            
            // Validate required fields
            if (empty($name) || empty($email)) {
                $this->setFlash('error', 'Name and email are required.');
                $this->redirect('/superadmin/edit-member/' . $id);
                return;
            }
            
            // Check if email is already used by another member
            $existingMember = Database::fetchOne(
                "SELECT id FROM members WHERE email = ? AND id != ?",
                [$email, $id]
            );
            
            if ($existingMember) {
                $this->setFlash('error', 'Email is already in use by another member.');
                $this->redirect('/superadmin/edit-member/' . $id);
                return;
            }
            
            // Update member
            $updated = Database::update('members', [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'ti_number' => $ti_number,
                'department_id' => $departmentId,
                'address' => $address,
                'is_active' => $isActive,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $id]);
            
            if ($updated) {
                $this->setFlash('success', 'Member details updated successfully');
            } else {
                $this->setFlash('error', 'Failed to update member details');
            }
            
            $this->redirect('/superadmin/view-member/' . $id);
        }
        
        $this->renderSuperAdmin('superadmin/edit-member', [
            'member' => $member,
            'departments' => $departments,
            'current_page' => 'members',
            'pageTitle' => 'Edit Member: ' . $member['name']
        ]);
    }
    
    /**
     * Toggle member active status
     */
    public function toggleStatus(string $id, string $status): void
    {
        // Cast parameters to integers
        $id = (int)$id;
        $status = (int)$status;
        
        // Validate status (should be 0 or 1)
        if ($status !== 0 && $status !== 1) {
            $this->setFlash('error', 'Invalid status value');
            $this->redirect('/superadmin/members');
            return;
        }
        
        // Update member status
        $result = Database::update('members', ['is_active' => $status], ['id' => $id]);
        
        if ($result) {
            // Get member details for the log
            $member = Database::fetchOne(
                "SELECT coop_no, name FROM members WHERE id = ?",
                [$id]
            );
            
            $action = $status ? 'activated' : 'deactivated';
            $statusText = $status ? 'Active' : 'Inactive';
            
            // Log the action
            Auth::logAction(
                'admin', 
                Auth::getAdminId(), 
                "Member {$member['name']} ({$member['coop_no']}) {$action}",
                ['type' => 'member']
            );
            
            $this->setFlash('success', "Member has been set to {$statusText} successfully");
        } else {
            $this->setFlash('error', 'Failed to update member status');
        }
        
        $this->redirect('/superadmin/members');
    }
} 