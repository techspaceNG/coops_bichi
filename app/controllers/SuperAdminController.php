<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\View;
use App\Helpers\Auth;
use App\Models\Admin;

/**
 * SuperAdminController
 * Base controller for superadmin functionality - delegates to specialized controllers
 */
final class SuperAdminController extends Controller
{
    // Class properties
    protected $db;

    /**
     * Constructor
     * Initialize common resources
     */
    public function __construct()
    {
        try {
            // No need to call parent constructor as it doesn't exist
            // Simply initialize what we need
            $this->requireSuperAdmin();
            $this->db = Database::getConnection();
        } catch (\Exception $e) {
            // Log the error
            error_log('Error in SuperAdminController constructor: ' . $e->getMessage());
            // Continue execution to allow redirection to work
        }
    }

    /**
     * Dashboard - redirects to DashboardController
     */
    public function dashboard(): void
    {
        $this->redirect('/superadmin/dashboard');
    }

    /**
     * Profile methods - redirects to ProfileController
     */
    public function profile(): void
    {
        $this->redirect('/superadmin/profile');
    }

    public function updateProfile(): void
    {
        $this->redirect('/superadmin/profile/update');
    }

    public function uploadProfileImage(): void
    {
        $this->redirect('/superadmin/profile/upload-image');
    }

    public function settings(): void
    {
        $this->redirect('/superadmin/settings');
    }

    public function updateOwnPassword(): void
    {
        $this->redirect('/superadmin/profile/update-password');
    }

    /**
     * Admin management methods - redirects to AdminController
     */
    public function manageAdmins(): void
    {
        $this->redirect('/superadmin/admin');
    }

    public function createAdmin(): void
    {
        $this->redirect('/superadmin/admin/create');
    }

    public function storeAdmin(): void
    {
        $this->redirect('/superadmin/admin/store');
    }

    public function editAdmin(string $id): void
    {
        $this->redirect('/superadmin/admin/edit/' . $id);
    }

    public function updateAdmin(string $id): void
    {
        $this->redirect('/superadmin/admin/update/' . $id);
    }

    public function deleteAdmin(string $id): void
    {
        $this->redirect('/superadmin/admin/delete/' . $id);
    }

    public function toggleLock(string $id): void
    {
        $this->redirect('/superadmin/admin/toggle-lock/' . $id);
    }

    public function resetPassword(string $id): void
    {
        $this->redirect('/superadmin/admin/reset-password/' . $id);
    }

    public function updatePassword(string $id): void
    {
        $this->redirect('/superadmin/admin/update-password/' . $id);
    }

    /**
     * System settings methods - redirects to SettingsController
     */
    public function systemSettings(): void
    {
        $this->redirect('/superadmin/settings/system');
    }

    public function updateSettings(): void
    {
        $this->redirect('/superadmin/settings/update');
    }

    public function updateSystemSettings(): void
    {
        $this->redirect('/superadmin/settings/update-system');
    }

    /**
     * System logs methods - redirects to LogsController
     */
    public function systemLogs(): void
    {
        $this->redirect('/superadmin/logs');
    }

    /**
     * Database backup methods - redirects to BackupController
     */
    public function databaseBackup(): void
    {
        $this->redirect('/superadmin/backup');
    }

    public function performBackup(): void
    {
        $this->redirect('/superadmin/backup/perform');
    }

    public function downloadBackup(string $filename): void
    {
        $this->redirect('/superadmin/backup/download/' . $filename);
    }

    public function restoreBackup(): void
    {
        $this->redirect('/superadmin/backup/restore');
    }

    public function deleteBackup(): void
    {
        $this->redirect('/superadmin/backup/delete');
    }

    public function uploadBackup(): void
    {
        $this->redirect('/superadmin/backup/upload');
    }

    /**
     * Member management methods - redirects to MemberController
     */
    public function members(): void
    {
        $this->redirect('/superadmin/members');
    }

    public function exportMembers(): void
    {
        $this->redirect('/superadmin/members/export');
    }

    public function addMember(): void
    {
        $this->redirect('/superadmin/members/create');
    }

    public function uploadMembers(): void
    {
        $this->redirect('/superadmin/members/upload');
    }

    public function viewMember(string $id): void
    {
        $this->redirect('/superadmin/members/view/' . $id);
    }

    public function editMember(string $id): void
    {
        $this->redirect('/superadmin/members/edit/' . $id);
    }

    public function toggleMemberStatus(string $id, string $status): void
    {
        $this->redirect('/superadmin/members/toggle-status/' . $id . '/' . $status);
    }

    /**
     * Loans management methods - redirects to LoanController
     */
    public function loans(): void
    {
        $this->redirect('/superadmin/loans');
    }

    public function exportLoans(): void
    {
        $this->redirect('/superadmin/loans/export');
    }

    public function viewLoan(string $id): void
    {
        $this->redirect('/superadmin/loans/view/' . $id);
    }

    public function approveLoan(string $id): void
    {
        $this->redirect('/superadmin/loans/approve/' . $id);
    }

    public function declineLoan(string $id): void
    {
        $this->redirect('/superadmin/loans/decline/' . $id);
    }

    public function printLoan(string $id): void
    {
        $this->redirect('/superadmin/loans/print/' . $id);
    }

    public function addLoanDeduction(): void
    {
        $this->redirect('/superadmin/loans/add-deduction');
    }

    public function saveLoanDeduction(): void
    {
        $this->redirect('/superadmin/loans/save-deduction');
    }

    public function bulkLoanDeductions(): void
    {
        $this->redirect('/superadmin/loans/bulk-deductions');
    }

    public function processBulkLoanDeductions(): void
    {
        $this->redirect('/superadmin/loans/process-bulk-deductions');
    }

    public function downloadLoanDeductionTemplate(): void
    {
        $this->redirect('/superadmin/loans/download-template');
    }

    /**
     * Household management methods - redirects to HouseholdController
     */
    public function household(): void
    {
        $this->redirect('/superadmin/household');
    }

    public function exportHousehold(): void
    {
        $this->redirect('/superadmin/household/export');
    }

    public function viewHousehold(string $id): void
    {
        $this->redirect('/superadmin/household/view/' . $id);
    }

    public function approveHousehold(string $id): void
    {
        $this->redirect('/superadmin/household/approve/' . $id);
    }

    public function declineHousehold(string $id): void
    {
        $this->redirect('/superadmin/household/decline/' . $id);
    }

    public function printHousehold(string $id): void
    {
        $this->redirect('/superadmin/household/print/' . $id);
    }

    public function addHouseholdDeduction(): void
    {
        $this->redirect('/superadmin/household/add-deduction');
    }

    public function saveHouseholdDeduction(): void
    {
        $this->redirect('/superadmin/household/save-deduction');
    }

    public function bulkHouseholdDeductions(): void
    {
        $this->redirect('/superadmin/household/bulk-deductions');
    }

    public function processBulkHouseholdDeductions(): void
    {
        $this->redirect('/superadmin/household/process-bulk-deductions');
    }

    public function downloadHouseholdDeductionTemplate(): void
    {
        $this->redirect('/superadmin/household/download-template');
    }

    /**
     * Savings management methods - redirects to SavingsController
     */
    public function savings(): void
    {
        $this->redirect('/superadmin/savings');
    }

    public function exportSavings(): void
    {
        $this->redirect('/superadmin/savings/export');
    }

    public function uploadDeductions(): void
    {
        $this->redirect('/superadmin/savings/upload');
    }

    public function addDeduction(): void
    {
        $this->redirect('/superadmin/savings/add');
    }

    public function downloadDeductionTemplate(): void
    {
        $this->redirect('/superadmin/savings/download-template');
    }

    public function viewSavings(string $id): void
    {
        $this->redirect('/superadmin/savings/view/' . $id);
    }

    public function editSavings(string $id): void
    {
        $this->redirect('/superadmin/savings/edit/' . $id);
    }

    public function getDeductionHistory(string $id): void
    {
        $this->redirect('/superadmin/savings/history/' . $id);
    }

    /**
     * Reports management methods - redirects to ReportController
     */
    public function reports(): void
    {
        $this->redirect('/superadmin/reports');
    }

    /**
     * Notifications methods - redirects to NotificationController
     */
    public function notifications(): void
    {
        $this->redirect('/superadmin/notifications');
    }

    public function markNotificationRead(string $id): void
    {
        $this->redirect('/superadmin/notifications/mark-read/' . $id);
    }

    public function markAllNotificationsRead(): void
    {
        $this->redirect('/superadmin/notifications/mark-all-read');
    }

    public function createSampleNotifications(): void
    {
        $this->redirect('/superadmin/notifications/create-samples');
    }

    public function createNotification(): void
    {
        $this->redirect('/superadmin/notifications/create');
    }

    public function saveNotification(): void
    {
        $this->redirect('/superadmin/notifications/save');
    }

    /**
     * API methods - redirects to ApiController
     */
    public function searchMembersApi(): void
    {
        $this->redirect('/superadmin/api/search-members');
    }

    public function searchLoansApi(): void
    {
        $this->redirect('/superadmin/api/search-loans');
    }

    public function searchHouseholdPurchasesApi(): void
    {
        $this->redirect('/superadmin/api/search-household');
    }
} 