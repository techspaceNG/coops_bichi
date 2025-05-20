<?php
declare(strict_types=1);

/**
 * Route Configuration
 * Format: 'route_pattern' => 'ControllerName@methodName'
 */

$routes = [
    // Public routes
    '/home' => 'HomeController@index',
    '/about' => 'HomeController@about',
    '/contact' => 'HomeController@contact',
    '/faq' => 'HomeController@faq',
    
    // Authentication routes
    '/login' => 'AuthController@login',
    '/login/process' => 'AuthController@processLogin',
    '/admin/login' => 'AuthController@adminLogin',
    '/admin/login/process' => 'AuthController@processAdminLogin',
    '/register' => 'AuthController@register',
    '/register/process' => 'AuthController@processRegister',
    '/logout' => 'AuthController@logout',
    '/forgot-password' => 'AuthController@forgotPassword',
    
    // Member routes
    '/member/dashboard' => 'MemberController@dashboard',
    '/member/profile' => 'MemberController@profile',
    '/member/profile/update' => 'MemberController@updateProfile',
    '/member/profile/update-bank' => 'MemberController@updateBankInfo',
    '/member/change-password' => 'MemberController@changePassword',
    
    // Member notification routes
    '/member/notifications' => 'MemberController@notifications',
    '/member/notifications/mark-read/{id}' => 'MemberController@markNotificationRead',
    '/member/notifications/mark-all-read' => 'MemberController@markAllNotificationsRead',
    
    // Loan routes for members
    '/member/loans' => 'LoanController@index',
    '/member/loans/apply' => 'LoanController@apply',
    '/member/loans/applications' => 'LoanController@applications',
    '/member/loans/calculator' => 'LoanController@calculator',
    
    // Household purchase routes for members
    '/member/household' => 'HouseholdController@index',
    '/member/household/order' => 'HouseholdController@order',
    '/member/household/apply' => 'HouseholdController@order',
    '/member/household/applications' => 'HouseholdController@applications',
    '/member/household/applications/{id}' => 'HouseholdController@applicationDetails',
    '/member/household/details/{id}' => 'HouseholdController@details',
    
    // Savings routes for members
    '/member/savings' => 'SavingsController@index',
    '/member/savings/statement' => 'SavingsController@statement',
    '/member/savings/withdraw' => 'SavingsController@withdraw',
    '/member/savings/withdrawals' => 'SavingsController@withdrawals',
    '/member/savings/withdrawals/{id}' => 'SavingsController@withdrawalDetails',
    '/member/savings/update' => 'SavingsController@updateContribution',
    '/member/savings/calculator' => 'SavingsController@calculator',
    
    // Share routes for members
    '/member/shares' => 'App\Controllers\Member\SharesController@index',
    '/member/shares/purchase' => 'App\Controllers\Member\SharesController@purchase',
    '/member/shares/view/{id}' => 'App\Controllers\Member\SharesController@view',
    '/member/shares/sell/{id}' => 'App\Controllers\Member\SharesController@sell',
    
    // Transaction history routes for members
    '/member/transactions' => 'TransactionController@index',
    '/member/transactions/download' => 'TransactionController@download',
    
    // Admin routes
    '/admin/dashboard' => 'AdminController@dashboard',
    '/admin/profile' => 'AdminController@profile',
    '/admin/settings' => 'AdminController@settings',
    '/admin/change-password' => 'AdminController@changePassword',
    
    // SuperAdmin routes
    '/superadmin/dashboard' => 'App\Controllers\Superadmin\DashboardController@index',
    '/superadmin/profile' => 'App\Controllers\Superadmin\ProfileController@index',
    '/superadmin/profile/update' => 'App\Controllers\Superadmin\ProfileController@update',
    '/superadmin/profile/upload-image' => 'App\Controllers\Superadmin\ProfileController@uploadImage',
    '/superadmin/settings' => 'App\Controllers\Superadmin\ProfileController@settings',
    '/superadmin/settings/update-password' => 'App\Controllers\Superadmin\ProfileController@updatePassword',
    '/superadmin/manage-admins' => 'App\Controllers\Superadmin\AdminController@index',
    '/superadmin/create-admin' => 'App\Controllers\Superadmin\AdminController@create',
    '/superadmin/store-admin' => 'App\Controllers\Superadmin\AdminController@store',
    '/superadmin/edit-admin/{id}' => 'App\Controllers\Superadmin\AdminController@edit',
    '/superadmin/update-admin/{id}' => 'App\Controllers\Superadmin\AdminController@update',
    '/superadmin/delete-admin/{id}' => 'App\Controllers\Superadmin\AdminController@delete',
    '/superadmin/toggle-lock/{id}' => 'App\Controllers\Superadmin\AdminController@toggleLock',
    '/superadmin/reset-password/{id}' => 'App\Controllers\Superadmin\AdminController@resetPassword',
    '/superadmin/update-password/{id}' => 'App\Controllers\Superadmin\AdminController@updatePassword',
    '/superadmin/system-settings' => 'App\Controllers\Superadmin\SettingsController@index',
    '/superadmin/update-system-settings' => 'App\Controllers\Superadmin\SettingsController@update',
    '/superadmin/system-logs' => 'App\Controllers\Superadmin\LogsController@index',
    
    // Member management routes for admin
    '/admin/members' => 'AdminMemberController@index',
    '/admin/members/add' => 'AdminMemberController@add',
    '/admin/members/create' => 'AdminMemberController@create',
    '/admin/members/edit/{id}' => 'AdminMemberController@edit',
    '/admin/members/update/{id}' => 'AdminMemberController@update',
    '/admin/members/delete/{id}' => 'AdminMemberController@delete',
    '/admin/members/view/{id}' => 'AdminMemberController@view',
    '/admin/members/lock/{id}' => 'AdminMemberController@lock',
    '/admin/members/unlock/{id}' => 'AdminMemberController@unlock',
    '/admin/members/pending' => 'AdminMemberController@pending',
    '/admin/members/approve/{id}' => 'AdminMemberController@approve',
    '/admin/members/reject/{id}' => 'AdminMemberController@reject',
    
    // Loan management routes for admin - view only
    '/admin/loans' => 'AdminLoanController@index',
    '/admin/loans/applications' => 'AdminLoanController@applications',
    '/admin/loans/view/{id}' => 'AdminLoanController@view',
    '/admin/loans/repayments' => 'AdminLoanController@repayments',
    
    // Household purchase management routes for admin
    '/admin/household' => 'AdminHouseholdController@index',
    '/admin/household/applications' => 'AdminHouseholdController@applications',
    '/admin/household/approve/{id}' => 'AdminHouseholdController@approve',
    '/admin/household/reject/{id}' => 'AdminHouseholdController@reject',
    '/admin/household/view/{id}' => 'AdminHouseholdController@view',
    '/admin/household/upload' => 'AdminHouseholdController@upload',
    
    // Savings management routes for admin
    '/admin/savings' => 'AdminSavingsController@index',
    '/admin/savings/upload' => 'AdminSavingsController@upload',
    '/admin/savings/contributions' => 'AdminSavingsController@contributions',
    '/admin/savings/withdrawals' => 'AdminSavingsController@withdrawals',
    
    // Shares management routes for admin
    '/admin/shares' => 'AdminSharesController@index',
    '/admin/shares/upload' => 'AdminSharesController@upload',
    '/admin/shares/transactions' => 'AdminSharesController@transactions',
    '/admin/shares/add-purchase' => 'AdminSharesController@addPurchase',
    '/admin/shares/process-upload' => 'AdminSharesController@processUpload',
    '/admin/shares/download-template' => 'AdminSharesController@downloadTemplate',
    '/admin/shares/export-transactions' => 'AdminSharesController@exportTransactions',
    
    // Products management routes for admin
    '/admin/products' => 'AdminProductController@index',
    '/admin/products/add' => 'AdminProductController@add',
    '/admin/products/edit/{id}' => 'AdminProductController@edit',
    '/admin/products/delete/{id}' => 'AdminProductController@delete',
    
    // Orders management routes for admin
    '/admin/orders' => 'AdminOrderController@index',
    '/admin/orders/view/{id}' => 'AdminOrderController@view',
    '/admin/orders/update/{id}' => 'AdminOrderController@update',
    
    // Transaction routes for admin
    '/admin/transactions' => 'AdminTransactionController@index',
    '/admin/transactions/savings' => 'AdminTransactionController@savings',
    '/admin/transactions/loans' => 'AdminTransactionController@loans',
    '/admin/transactions/household' => 'AdminTransactionController@household',
    
    // Bulk upload routes for admin
    '/admin/uploads' => 'AdminUploadController@index',
    '/admin/uploads/view/{id}' => 'AdminUploadController@view',
    '/admin/uploads/savings' => 'AdminUploadController@savings',
    '/admin/uploads/loans' => 'AdminUploadController@loans',
    '/admin/uploads/household' => 'AdminUploadController@household',
    
    // Reports routes for admin
    '/admin/reports' => 'ReportController@index',
    '/admin/reports/savings' => 'ReportController@savings',
    '/admin/reports/loans' => 'ReportController@loans',
    '/admin/reports/household' => 'ReportController@household',
    '/admin/reports/financial' => 'ReportController@financial',
    
    // Audit log routes for admin
    '/admin/audit-logs' => 'AdminAuditController@index',
    
    // System management routes for admin
    '/admin/backups' => 'AdminSystemController@backups',
    '/admin/logs' => 'AdminSystemController@logs',
    
    // User management routes for superadmin
    '/admin/users' => 'SuperAdminController@users',
    '/admin/users/add' => 'SuperAdminController@addUser',
    '/admin/users/edit/{id}' => 'SuperAdminController@editUser',
    '/admin/users/delete/{id}' => 'SuperAdminController@deleteUser',
    '/admin/users/lock/{id}' => 'SuperAdminController@lockUser',
    '/admin/users/unlock/{id}' => 'SuperAdminController@unlockUser',
    
    // Additional SuperAdmin routes
    '/admin/system/maintenance' => 'SuperAdminController@maintenance',
    '/admin/announcements' => 'SuperAdminController@announcements',
    '/admin/announcements/add' => 'SuperAdminController@addAnnouncement',
    '/admin/announcements/edit/{id}' => 'SuperAdminController@editAnnouncement',
    '/admin/announcements/delete/{id}' => 'SuperAdminController@deleteAnnouncement',
    '/admin/roles' => 'SuperAdminController@roles',
    '/admin/roles/add' => 'SuperAdminController@addRole',
    '/admin/roles/edit/{id}' => 'SuperAdminController@editRole',
    '/admin/roles/permissions/{id}' => 'SuperAdminController@setPermissions',
    '/admin/system/updates' => 'SuperAdminController@systemUpdates',
    
    // SuperAdmin module routes
    '/superadmin/members' => 'App\Controllers\Superadmin\MemberController@index',
    '/superadmin/export-members' => 'App\Controllers\Superadmin\MemberController@export',
    '/superadmin/add-member' => 'App\Controllers\Superadmin\MemberController@create',
    '/superadmin/upload-members' => 'App\Controllers\Superadmin\MemberController@upload',
    '/superadmin/view-member/{id}' => 'App\Controllers\Superadmin\MemberController@view',
    '/superadmin/edit-member/{id}' => 'App\Controllers\Superadmin\MemberController@edit',
    '/superadmin/toggle-member-status/{id}/{status}' => 'App\Controllers\Superadmin\MemberController@toggleStatus',
    
    '/superadmin/savings' => 'App\Controllers\Superadmin\SavingsController@index',
    '/superadmin/savings/export' => 'App\Controllers\Superadmin\SavingsController@export',
    '/superadmin/savings/upload' => 'App\Controllers\Superadmin\SavingsController@uploadDeductions',
    '/superadmin/savings/add' => 'App\Controllers\Superadmin\SavingsController@addDeduction',
    '/superadmin/savings/download-template' => 'App\Controllers\Superadmin\SavingsController@downloadTemplate',
    '/superadmin/savings/view/{memberId}' => 'App\Controllers\Superadmin\SavingsController@view',
    '/superadmin/savings/edit/{memberId}' => 'App\Controllers\Superadmin\SavingsController@edit',
    '/superadmin/savings/history/{memberId}' => 'App\Controllers\Superadmin\SavingsController@getDeductionHistory',
    
    '/superadmin/loans' => 'App\Controllers\Superadmin\LoanController@index',
    '/superadmin/export-loans' => 'App\Controllers\Superadmin\LoanController@export',
    '/superadmin/view-loan/{id}' => 'App\Controllers\Superadmin\LoanController@view',
    '/superadmin/review-loan/{id}' => 'App\Controllers\Superadmin\LoanController@review',
    '/superadmin/approve-loan/{id}' => 'App\Controllers\Superadmin\LoanController@approve',
    '/superadmin/decline-loan/{id}' => 'App\Controllers\Superadmin\LoanController@decline',
    '/superadmin/approve-loan-application/{id}' => 'App\Controllers\Superadmin\LoanController@approveApplication',
    '/superadmin/decline-loan-application/{id}' => 'App\Controllers\Superadmin\LoanController@declineApplication',
    '/superadmin/print-loan/{id}' => 'App\Controllers\Superadmin\LoanController@print',
    '/superadmin/add-loan-deduction' => 'App\Controllers\Superadmin\LoanController@addDeduction',
    '/superadmin/save-loan-deduction' => 'App\Controllers\Superadmin\LoanController@saveDeduction',
    '/superadmin/bulk-loan-deductions' => 'App\Controllers\Superadmin\LoanController@bulkDeductions',
    '/superadmin/process-bulk-loan-deductions' => 'App\Controllers\Superadmin\LoanController@processBulkDeductions',
    '/superadmin/download-loan-deduction-template' => 'App\Controllers\Superadmin\LoanController@downloadDeductionTemplate',
    '/superadmin/process-loan-balances' => 'App\Controllers\Superadmin\LoanController@processLoanBalance',
    
    '/superadmin/household' => 'App\Controllers\Superadmin\HouseholdController@index',
    '/superadmin/export-household' => 'App\Controllers\Superadmin\HouseholdController@export',
    '/superadmin/view-household/{id}' => 'App\Controllers\Superadmin\HouseholdController@view',
    '/superadmin/view-household-application/{id}' => 'App\Controllers\Superadmin\HouseholdController@viewApplication',
    '/superadmin/approve-household-application/{id}' => 'App\Controllers\Superadmin\HouseholdController@approveApplication',
    '/superadmin/decline-household-application/{id}' => 'App\Controllers\Superadmin\HouseholdController@declineApplication',
    '/superadmin/approve-household/{id}' => 'App\Controllers\Superadmin\HouseholdController@approve',
    '/superadmin/decline-household/{id}' => 'App\Controllers\Superadmin\HouseholdController@decline',
    '/superadmin/print-household/{id}' => 'App\Controllers\Superadmin\HouseholdController@print',
    '/superadmin/add-household-deduction' => 'App\Controllers\Superadmin\HouseholdController@addDeduction',
    '/superadmin/save-household-deduction' => 'App\Controllers\Superadmin\HouseholdController@saveDeduction',
    '/superadmin/bulk-household-deductions' => 'App\Controllers\Superadmin\HouseholdController@bulkDeductions',
    '/superadmin/process-bulk-household-deductions' => 'App\Controllers\Superadmin\HouseholdController@processBulkDeductions',
    '/superadmin/download-household-deduction-template' => 'App\Controllers\Superadmin\HouseholdController@downloadDeductionTemplate',
    
    // Shares Management Routes
    '/superadmin/shares' => 'App\Controllers\Superadmin\ShareController@index',
    '/superadmin/shares/export' => 'App\Controllers\Superadmin\ShareController@export',
    '/superadmin/add-share-deduction' => 'App\Controllers\Superadmin\ShareController@addDeduction',
    '/superadmin/save-share-deduction' => 'App\Controllers\Superadmin\ShareController@saveDeduction',
    '/superadmin/bulk-share-deductions' => 'App\Controllers\Superadmin\ShareController@bulkDeductions',
    '/superadmin/process-bulk-share-deductions' => 'App\Controllers\Superadmin\ShareController@processBulkDeductions',
    '/superadmin/download-share-deduction-template' => 'App\Controllers\Superadmin\ShareController@downloadDeductionTemplate',
    '/superadmin/share-transactions/{id}' => 'App\Controllers\Superadmin\ShareController@transactions',
    '/superadmin/process-share-balances' => 'App\Controllers\Superadmin\ShareController@processShareBalance',
    
    '/superadmin/reports' => 'App\Controllers\Superadmin\ReportController@index',
    '/superadmin/reports/member' => 'App\Controllers\Superadmin\ReportController@memberReport',
    '/superadmin/reports/savings' => 'App\Controllers\Superadmin\ReportController@savingsReport',
    '/superadmin/reports/loan' => 'App\Controllers\Superadmin\ReportController@loanReport',
    '/superadmin/reports/household' => 'App\Controllers\Superadmin\ReportController@householdReport',
    '/superadmin/reports/transaction' => 'App\Controllers\Superadmin\ReportController@transactionReport',
    
    // SuperAdmin notification routes
    '/superadmin/notifications' => 'App\Controllers\Superadmin\NotificationController@index',
    '/superadmin/mark-notification-read/{id}' => 'App\Controllers\Superadmin\NotificationController@markRead',
    '/superadmin/mark-all-notifications-read' => 'App\Controllers\Superadmin\NotificationController@markAllRead',
    '/superadmin/create-sample-notifications' => 'App\Controllers\Superadmin\NotificationController@createSamples',
    '/superadmin/create-notification' => 'App\Controllers\Superadmin\NotificationController@create',
    '/superadmin/save-notification' => 'App\Controllers\Superadmin\NotificationController@save',
    
    // SuperAdmin API routes
    '/superadmin/api/search-members' => 'App\Controllers\Superadmin\ApiController@searchMembers',
    '/superadmin/api/search-loans' => 'App\Controllers\Superadmin\ApiController@searchLoans',
    '/superadmin/api/search-household-purchases' => 'App\Controllers\Superadmin\ApiController@searchHousehold',
    
    // Backup routes
    '/superadmin/database-backup' => 'App\Controllers\Superadmin\BackupController@index',
    '/superadmin/perform-backup' => 'App\Controllers\Superadmin\BackupController@perform',
    '/superadmin/download-backup/{filename}' => 'App\Controllers\Superadmin\BackupController@download',
    '/superadmin/restore-backup' => 'App\Controllers\Superadmin\BackupController@restore',
    '/superadmin/delete-backup' => 'App\Controllers\Superadmin\BackupController@delete',
    '/superadmin/upload-backup' => 'App\Controllers\Superadmin\BackupController@upload',
    
    // API routes
    '/api/loan/calculate' => 'ApiController@calculateLoan',
    
    // Error routes
    '/error/403' => 'ErrorController@forbidden',
    '/error/404' => 'ErrorController@notFound',
    '/error/500' => 'ErrorController@serverError',
]; 