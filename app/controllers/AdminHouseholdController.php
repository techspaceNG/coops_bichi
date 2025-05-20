<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Config\Database;
use App\Helpers\Validator;
use App\Models\HouseholdPurchase;
use App\Models\Member;
use App\Models\Notification;
use App\Core\Controller;

/**
 * Controller for handling household purchase management by administrators
 */
final class AdminHouseholdController extends Controller
{
    /**
     * Constructor to check admin authentication
     */
    public function __construct()
    {
        // Only allow authenticated admins
        if (!Auth::isAdminLoggedIn()) {
            $this->redirect('/Coops_Bichi/public/login');
        }
    }
    
    /**
     * Display household purchases dashboard
     *
     * @return void
     */
    public function index(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        
        // Get approved household purchases
        $householdPurchases = HouseholdPurchase::getAll($page, $perPage, 'approved');
        
        // Get household statistics
        $householdStats = HouseholdPurchase::getStats();
        
        $this->renderAdmin('admin/household/index', [
            'title' => 'Household Purchases - Admin Dashboard',
            'household_purchases' => $householdPurchases['data'],
            'household_stats' => $householdStats,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $householdPurchases['total'],
                'last_page' => ceil($householdPurchases['total'] / $perPage)
            ]
        ]);
    }
    
    /**
     * Display household purchase applications
     *
     * @return void
     */
    public function applications(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $status = isset($_GET['status']) ? $_GET['status'] : 'pending';
        
        // Validate status
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            $status = 'pending';
        }
        
        // Get household purchase applications
        $applications = HouseholdPurchase::getAllApplications($status, $page, $perPage);
        
        // Get application statistics for dashboard
        $appStats = $this->getApplicationStats();
        
        $this->renderAdmin('admin/household/applications', [
            'title' => 'Household Purchase Applications - Admin Dashboard',
            'applications' => $applications['data'],
            'status' => $status,
            'app_stats' => $appStats,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $applications['total'],
                'last_page' => ceil($applications['total'] / $perPage)
            ]
        ]);
    }
    
    /**
     * Get household purchase application statistics
     *
     * @return array
     */
    private function getApplicationStats(): array
    {
        $stats = [
            'pending' => 0,
            'pending_value' => 0,
            'approved' => 0,
            'approved_value' => 0,
            'rejected' => 0,
            'rejected_month' => 0
        ];
        
        try {
            $db = Database::getConnection();
            
            // Pending applications count and value
            $pendingQuery = "SELECT COUNT(*) as count, SUM(household_amount) as total_amount FROM household_applications WHERE status = 'pending'";
            $pendingStmt = $db->prepare($pendingQuery);
            $pendingStmt->execute();
            $pendingResult = $pendingStmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($pendingResult) {
                $stats['pending'] = (int)$pendingResult['count'];
                $stats['pending_value'] = (float)($pendingResult['total_amount'] ?? 0);
            }
            
            // Approved applications count and value
            $approvedQuery = "SELECT COUNT(*) as count, SUM(household_amount) as total_amount FROM household_applications WHERE status = 'approved'";
            $approvedStmt = $db->prepare($approvedQuery);
            $approvedStmt->execute();
            $approvedResult = $approvedStmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($approvedResult) {
                $stats['approved'] = (int)$approvedResult['count'];
                $stats['approved_value'] = (float)($approvedResult['total_amount'] ?? 0);
            }
            
            // Rejected applications count
            $rejectedQuery = "SELECT COUNT(*) as count FROM household_applications WHERE status = 'rejected'";
            $rejectedStmt = $db->prepare($rejectedQuery);
            $rejectedStmt->execute();
            $rejectedResult = $rejectedStmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($rejectedResult) {
                $stats['rejected'] = (int)$rejectedResult['count'];
            }
            
            // Rejected in last 30 days
            $monthlyRejectedQuery = "SELECT COUNT(*) as count FROM household_applications WHERE status = 'rejected' AND updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $monthlyRejectedStmt = $db->prepare($monthlyRejectedQuery);
            $monthlyRejectedStmt->execute();
            $monthlyRejectedResult = $monthlyRejectedStmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($monthlyRejectedResult) {
                $stats['rejected_month'] = (int)$monthlyRejectedResult['count'];
            }
            
            return $stats;
        } catch (\Exception $e) {
            error_log("Error calculating application stats: " . $e->getMessage());
            return $stats;
        }
    }
    
    /**
     * View details of a household purchase application
     *
     * @param array $params The route parameters
     * @return void
     */
    public function view(array $params): void
    {
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            $this->setFlash('error', 'Invalid household purchase ID');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        }
        
        $id = (int)$params['id'];
        
        // Get application details
        $application = HouseholdPurchase::getApplicationById($id);
        
        if (!$application) {
            $this->setFlash('error', 'Household purchase application not found');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        }
        
        // Get member details
        $member = Member::findById((int)$application['member_id']);
        
        $this->renderAdmin('admin/household/view', [
            'title' => 'View Household Purchase Application - Admin Dashboard',
            'application' => $application,
            'member' => $member
        ]);
    }
    
    /**
     * Approve a household purchase application
     *
     * @param array $params The route parameters
     * @return void
     */
    public function approve(array $params): void
    {
        // Restrict access for admin users (view-only)
        $this->setFlash('error', 'You do not have permission to approve household purchase applications. This is a view-only role.');
        $this->redirect('/Coops_Bichi/public/admin/household/applications');
        return;
        
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            $this->setFlash('error', 'Invalid household purchase application ID');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        }
        
        $id = (int)$params['id'];
        
        // Get application details
        $application = HouseholdPurchase::getApplicationById($id);
        
        if (!$application) {
            $this->setFlash('error', 'Household purchase application not found');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        }
        
        if ($application['status'] !== 'pending') {
            $this->setFlash('error', 'Only pending applications can be approved');
            $this->redirect('/Coops_Bichi/public/admin/household/view/' . $id);
        }
        
        // Process the approval in a transaction
        $success = Database::transaction(function() use ($application, $id) {
            // Get admin ID
            $adminId = Auth::getAdminId();
            
            // Update application status
            $approved = HouseholdPurchase::approveApplication($id, null, $adminId);
            
            if (!$approved) {
                return false;
            }
            
            // Create household purchase entry
            $purchaseId = HouseholdPurchase::createOrUpdate([
                'member_id' => $application['member_id'],
                'fullname' => $application['fullname'],
                'coop_no' => $application['coop_no'],
                'item_name' => $application['item_name'],
                'amount' => $application['household_amount'],
                'ip_figure' => $application['ip_figure'],
                'remaining_balance' => $application['household_amount'],
                'status' => 'approved'
            ]);
            
            if (!$purchaseId) {
                return false;
            }
            
            // Create notification for member
            $notification = [
                'recipient_id' => $application['member_id'],
                'recipient_type' => 'member',
                'sender_id' => $adminId,
                'sender_type' => 'admin',
                'type' => 'household_approved',
                'title' => 'Household Purchase Approved',
                'message' => 'Your application for ' . htmlspecialchars($application['item_name']) . ' has been approved.',
                'link' => '/member/household',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            return Notification::create($notification) !== null;
        });
        
        if ($success) {
            $this->setFlash('success', 'Household purchase application approved successfully');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        } else {
            $this->setFlash('error', 'Failed to approve household purchase application');
            $this->redirect('/Coops_Bichi/public/admin/household/view/' . $id);
        }
    }
    
    /**
     * Reject a household purchase application
     *
     * @param array $params The route parameters
     * @return void
     */
    public function reject(array $params): void
    {
        // Restrict access for admin users (view-only)
        $this->setFlash('error', 'You do not have permission to reject household purchase applications. This is a view-only role.');
        $this->redirect('/Coops_Bichi/public/admin/household/applications');
        return;
        
        if (!isset($params['id']) || !is_numeric($params['id'])) {
            $this->setFlash('error', 'Invalid household purchase application ID');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        }
        
        $id = (int)$params['id'];
        
        // Get application details
        $application = HouseholdPurchase::getApplicationById($id);
        
        if (!$application) {
            $this->setFlash('error', 'Household purchase application not found');
            $this->redirect('/Coops_Bichi/public/admin/household/applications');
        }
        
        if ($application['status'] !== 'pending') {
            $this->setFlash('error', 'Only pending applications can be rejected');
            $this->redirect('/Coops_Bichi/public/admin/household/view/' . $id);
        }
        
        // Process form submission for rejection reason
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
            
            if (empty($reason)) {
                $this->setFlash('error', 'Please provide a rejection reason');
                $this->redirect('/Coops_Bichi/public/admin/household/view/' . $id);
            }
            
            // Process the rejection in a transaction
            $success = Database::transaction(function() use ($application, $id, $reason) {
                // Update application status
                $rejected = HouseholdPurchase::rejectApplication($id, $reason);
                
                if (!$rejected) {
                    return false;
                }
                
                // Create notification for member
                $adminId = Auth::getAdminId();
                $notification = [
                    'recipient_id' => $application['member_id'],
                    'recipient_type' => 'member',
                    'sender_id' => $adminId,
                    'sender_type' => 'admin',
                    'type' => 'household_rejected',
                    'title' => 'Household Purchase Rejected',
                    'message' => 'Your application for ' . htmlspecialchars($application['item_name']) . ' has been rejected. Reason: ' . htmlspecialchars($reason),
                    'link' => '/member/household',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                return Notification::create($notification) !== null;
            });
            
            if ($success) {
                $this->setFlash('success', 'Household purchase application rejected successfully');
                $this->redirect('/Coops_Bichi/public/admin/household/applications');
            } else {
                $this->setFlash('error', 'Failed to reject household purchase application');
                $this->redirect('/Coops_Bichi/public/admin/household/view/' . $id);
            }
        } else {
            $this->setFlash('error', 'Invalid request method');
            $this->redirect('/Coops_Bichi/public/admin/household/view/' . $id);
        }
    }
    
    /**
     * Upload household purchase payment data
     *
     * @return void
     */
    public function upload(): void
    {
        // Restrict access for admin users (view-only)
        $this->setFlash('error', 'You do not have permission to upload household purchase payment data. This is a view-only role.');
        $this->redirect('/Coops_Bichi/public/admin/household');
        return;
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process file upload and store data
            // Implementation will depend on the specific requirements
            
            $this->setFlash('info', 'File upload processing not implemented yet');
            $this->redirect('/Coops_Bichi/public/admin/household');
        }
        
        $this->renderAdmin('admin/household/upload', [
            'title' => 'Upload Household Purchase Payments - Admin Dashboard'
        ]);
    }
} 