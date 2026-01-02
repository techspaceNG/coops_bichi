<?php $publicUrl = \App\Core\Config::getPublicUrl(); ?>
<div class="bg-white w-64 min-h-screen border-r border-gray-200 hidden md:block">
    <div class="flex flex-col h-full">
        <!-- Logo and Brand -->
        <div class="px-6 pt-6 pb-4 flex items-center border-b border-gray-200">
            <img src="<?= $publicUrl ?>/assets/images/logo.png" alt="FCET Bichi Cooperative" class="h-8 w-auto">
            <span class="ml-2 text-lg font-semibold text-gray-800">Admin Portal</span>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="<?= $publicUrl ?>/admin/dashboard" class="<?= $current_page === 'dashboard' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-tachometer-alt mr-3 <?= $current_page === 'dashboard' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                Dashboard
            </a>
            
            <!-- Members Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Members
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/members" class="<?= $current_page === 'members' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-users mr-3 <?= $current_page === 'members' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    All Members
                </a>
                
                <a href="<?= $publicUrl ?>/admin/members/pending" class="<?= $current_page === 'pending_members' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-user-clock mr-3 <?= $current_page === 'pending_members' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Pending Approvals
                    <?php if (isset($counts['pending_members']) && $counts['pending_members'] > 0): ?>
                        <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-red-100 text-red-800">
                            <?= $counts['pending_members'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                
                <a href="<?= $publicUrl ?>/admin/members/add" class="<?= $current_page === 'add_member' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-user-plus mr-3 <?= $current_page === 'add_member' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Add New Member
                </a>
            </div>
            
            <!-- Savings Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Savings
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/savings" class="<?= $current_page === 'savings' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-piggy-bank mr-3 <?= $current_page === 'savings' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Savings Overview
                </a>
                
                <a href="<?= $publicUrl ?>/admin/savings/contributions" class="<?= $current_page === 'contributions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-money-bill-wave mr-3 <?= $current_page === 'contributions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Monthly Contributions
                </a>
                
                <a href="<?= $publicUrl ?>/admin/savings/withdrawals" class="<?= $current_page === 'withdrawals' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-hand-holding-usd mr-3 <?= $current_page === 'withdrawals' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Withdrawal Requests
                    <?php if (isset($counts['pending_withdrawals']) && $counts['pending_withdrawals'] > 0): ?>
                        <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-yellow-100 text-yellow-800">
                            <?= $counts['pending_withdrawals'] ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
            
            <!-- Shares Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Shares
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/shares" class="<?= $current_page === 'shares' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-chart-pie mr-3 <?= $current_page === 'shares' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Shares Overview
                </a>
                
                <a href="<?= $publicUrl ?>/admin/shares/transactions" class="<?= $current_page === 'share_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-exchange-alt mr-3 <?= $current_page === 'share_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Share Transactions
                </a>
                
                <a href="<?= $publicUrl ?>/admin/shares/upload" class="<?= $current_page === 'shares_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-upload mr-3 <?= $current_page === 'shares_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Upload Contributions
                </a>
            </div>
            
            <!-- Loans Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Loans
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/loans" class="<?= $current_page === 'loans' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-file-invoice-dollar mr-3 <?= $current_page === 'loans' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Loan Management
                </a>
                
                <a href="<?= $publicUrl ?>/admin/loans/applications" class="<?= $current_page === 'loan_applications' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-clipboard-list mr-3 <?= $current_page === 'loan_applications' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Loan Applications
                    <?php if (isset($counts['pending_loans']) && $counts['pending_loans'] > 0): ?>
                        <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-blue-100 text-blue-800">
                            <?= $counts['pending_loans'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                
                <a href="<?= $publicUrl ?>/admin/loans/repayments" class="<?= $current_page === 'repayments' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-money-check-alt mr-3 <?= $current_page === 'repayments' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Loan Repayments
                </a>
            </div>
            
            <!-- Household Products Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Household Products
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/household" class="<?= $current_page === 'household' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-shopping-basket mr-3 <?= $current_page === 'household' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Household Overview
                </a>
                
                <a href="<?= $publicUrl ?>/admin/household/applications" class="<?= $current_page === 'household_applications' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-clipboard-list mr-3 <?= $current_page === 'household_applications' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Purchase Applications
                    <?php if (isset($counts['pending_household']) && $counts['pending_household'] > 0): ?>
                        <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-purple-100 text-purple-800">
                            <?= $counts['pending_household'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                
                <a href="<?= $publicUrl ?>/admin/products" class="<?= $current_page === 'products' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-boxes mr-3 <?= $current_page === 'products' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Product Catalog
                </a>
                
                <a href="<?= $publicUrl ?>/admin/orders" class="<?= $current_page === 'orders' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-shopping-cart mr-3 <?= $current_page === 'orders' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Orders
                    <?php if (isset($counts['pending_orders']) && $counts['pending_orders'] > 0): ?>
                        <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-green-100 text-green-800">
                            <?= $counts['pending_orders'] ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
            
            <!-- Bulk Uploads Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Bulk Uploads
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/uploads" class="<?= $current_page === 'uploads' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-upload mr-3 <?= $current_page === 'uploads' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Upload Logs
                </a>
                
                <a href="<?= $publicUrl ?>/admin/uploads/savings" class="<?= $current_page === 'savings_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-file-upload mr-3 <?= $current_page === 'savings_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Savings Upload
                </a>
                
                <a href="<?= $publicUrl ?>/admin/uploads/loans" class="<?= $current_page === 'loans_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-file-upload mr-3 <?= $current_page === 'loans_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Loan Deductions
                </a>
                
                <a href="<?= $publicUrl ?>/admin/uploads/household" class="<?= $current_page === 'household_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-file-upload mr-3 <?= $current_page === 'household_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Household Deductions
                </a>
            </div>
            
            <!-- Transactions Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Transactions
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/transactions" class="<?= $current_page === 'transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-exchange-alt mr-3 <?= $current_page === 'transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    All Transactions
                </a>
                
                <a href="<?= $publicUrl ?>/admin/transactions/savings" class="<?= $current_page === 'savings_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-piggy-bank mr-3 <?= $current_page === 'savings_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Savings Transactions
                </a>
                
                <a href="<?= $publicUrl ?>/admin/transactions/loans" class="<?= $current_page === 'loan_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-hand-holding-usd mr-3 <?= $current_page === 'loan_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Loan Transactions
                </a>
                
                <a href="<?= $publicUrl ?>/admin/transactions/household" class="<?= $current_page === 'household_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-shopping-cart mr-3 <?= $current_page === 'household_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Household Transactions
                </a>
            </div>
            
            <!-- Reports Section -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Reports
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/reports/savings" class="<?= $current_page === 'savings_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-chart-pie mr-3 <?= $current_page === 'savings_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Savings Reports
                </a>
                
                <a href="<?= $publicUrl ?>/admin/reports/loans" class="<?= $current_page === 'loans_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-chart-bar mr-3 <?= $current_page === 'loans_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Loan Reports
                </a>
                
                <a href="<?= $publicUrl ?>/admin/reports/household" class="<?= $current_page === 'household_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-chart-area mr-3 <?= $current_page === 'household_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Household Reports
                </a>
                
                <a href="<?= $publicUrl ?>/admin/reports/financial" class="<?= $current_page === 'financial_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-chart-line mr-3 <?= $current_page === 'financial_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Financial Reports
                </a>
                
                <a href="<?= $publicUrl ?>/admin/audit-logs" class="<?= $current_page === 'audit_logs' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-history mr-3 <?= $current_page === 'audit_logs' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Audit Logs
                </a>
            </div>
            
            <!-- Administration -->
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Administration
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/settings" class="<?= $current_page === 'settings' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-cog mr-3 <?= $current_page === 'settings' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    System Settings
                </a>
                
                <a href="<?= $publicUrl ?>/admin/backups" class="<?= $current_page === 'backups' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-database mr-3 <?= $current_page === 'backups' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Database Backups
                </a>
                
                <a href="<?= $publicUrl ?>/admin/logs" class="<?= $current_page === 'system_logs' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-file-alt mr-3 <?= $current_page === 'system_logs' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    System Logs
                </a>
            </div>

            <!-- SuperAdmin Section - Only visible to superadmins -->
            <?php if (isset($admin) && isset($admin['role']) && $admin['role'] === 'superadmin'): ?>
            <div class="space-y-1 mt-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider bg-yellow-50 text-yellow-700 py-1 rounded">
                    SUPERADMIN
                </h3>
                
                <a href="<?= $publicUrl ?>/admin/users" class="<?= $current_page === 'users' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-users-cog mr-3 <?= $current_page === 'users' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Admin Users Management
                </a>
                
                <a href="<?= $publicUrl ?>/admin/users/add" class="<?= $current_page === 'add_user' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-user-plus mr-3 <?= $current_page === 'add_user' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Add New Admin
                </a>
                
                <a href="<?= $publicUrl ?>/admin/system/maintenance" class="<?= $current_page === 'maintenance' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-tools mr-3 <?= $current_page === 'maintenance' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    System Maintenance
                </a>
                
                <a href="<?= $publicUrl ?>/admin/announcements" class="<?= $current_page === 'announcements' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-bullhorn mr-3 <?= $current_page === 'announcements' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Manage Announcements
                </a>
                
                <a href="<?= $publicUrl ?>/admin/roles" class="<?= $current_page === 'roles' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-user-tag mr-3 <?= $current_page === 'roles' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Role Permissions
                </a>
            </div>
            <?php endif; ?>
        </nav>
        
        <!-- Profile -->
        <div class="border-t border-gray-200 py-4 px-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 h-9 w-9">
                    <img class="h-9 w-9 rounded-full" src="<?= isset($admin) && !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : '/assets/images/default-avatar.png' ?>" alt="Profile">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        <?= isset($admin) && isset($admin['first_name']) && isset($admin['last_name']) ? htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) : 'Admin User' ?>
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        <?= isset($admin) && isset($admin['role']) ? htmlspecialchars($admin['role']) : 'admin' ?>
                    </p>
                </div>
                <div>
                    <div class="relative">
                        <button id="profileMenuButton" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="profileMenu" class="absolute right-0 bottom-10 w-48 bg-white rounded-md shadow-lg py-1 hidden">
                            <a href="<?= $publicUrl ?>/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2 text-gray-500"></i> Your Profile
                            </a>
                            <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile menu button -->
<div class="md:hidden fixed top-4 left-4 z-50">
    <button id="mobileSidebarToggle" class="bg-white rounded-md p-2 shadow-md text-gray-500 hover:bg-gray-100 focus:outline-none">
        <i class="fas fa-bars"></i>
    </button>
</div>

<!-- Mobile sidebar -->
<div id="mobileSidebar" class="fixed inset-0 z-40 hidden md:hidden">
    <div class="absolute inset-0 bg-gray-600 opacity-75" id="sidebarOverlay"></div>
    
    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button id="closeMobileSidebar" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <i class="fas fa-times text-white text-xl"></i>
            </button>
        </div>
        
        <!-- Mobile sidebar content (same as desktop but in mobile view) -->
        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <div class="flex-shrink-0 flex items-center px-4 mb-4">
                <img src="/assets/images/logo.png" alt="FCET Bichi Cooperative" class="h-8 w-auto">
                <span class="ml-2 text-lg font-semibold text-gray-800">Admin Portal</span>
            </div>
            <!-- Copy the desktop nav items here but without the mobile toggle controls -->
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                <!-- Same navigation items as desktop version -->
                <!-- Dashboard -->
                <a href="<?= $publicUrl ?>/admin/dashboard" class="<?= $current_page === 'dashboard' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-tachometer-alt mr-3 <?= $current_page === 'dashboard' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Dashboard
                </a>
                
                <!-- Members Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Members
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/members" class="<?= $current_page === 'members' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-users mr-3 <?= $current_page === 'members' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        All Members
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/members/pending" class="<?= $current_page === 'pending_members' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-user-clock mr-3 <?= $current_page === 'pending_members' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Pending Approvals
                        <?php if (isset($counts['pending_members']) && $counts['pending_members'] > 0): ?>
                            <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-red-100 text-red-800">
                                <?= $counts['pending_members'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/members/add" class="<?= $current_page === 'add_member' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-user-plus mr-3 <?= $current_page === 'add_member' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Add New Member
                    </a>
                </div>
                
                <!-- Savings Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Savings
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/savings" class="<?= $current_page === 'savings' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-piggy-bank mr-3 <?= $current_page === 'savings' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Savings Overview
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/savings/contributions" class="<?= $current_page === 'contributions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-money-bill-wave mr-3 <?= $current_page === 'contributions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Monthly Contributions
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/savings/withdrawals" class="<?= $current_page === 'withdrawals' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-hand-holding-usd mr-3 <?= $current_page === 'withdrawals' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Withdrawal Requests
                        <?php if (isset($counts['pending_withdrawals']) && $counts['pending_withdrawals'] > 0): ?>
                            <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                <?= $counts['pending_withdrawals'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Shares Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Shares
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/shares" class="<?= $current_page === 'shares' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-pie mr-3 <?= $current_page === 'shares' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Shares Overview
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/shares/transactions" class="<?= $current_page === 'share_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-exchange-alt mr-3 <?= $current_page === 'share_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Share Transactions
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/shares/upload" class="<?= $current_page === 'shares_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-upload mr-3 <?= $current_page === 'shares_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Upload Contributions
                    </a>
                </div>
                
                <!-- Loans Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Loans
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/loans" class="<?= $current_page === 'loans' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-file-invoice-dollar mr-3 <?= $current_page === 'loans' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Loan Management
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/loans/applications" class="<?= $current_page === 'loan_applications' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-clipboard-list mr-3 <?= $current_page === 'loan_applications' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Loan Applications
                        <?php if (isset($counts['pending_loans']) && $counts['pending_loans'] > 0): ?>
                            <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-blue-100 text-blue-800">
                                <?= $counts['pending_loans'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/loans/repayments" class="<?= $current_page === 'repayments' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-money-check-alt mr-3 <?= $current_page === 'repayments' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Loan Repayments
                    </a>
                </div>
                
                <!-- Household Products Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Household Products
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/household" class="<?= $current_page === 'household' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shopping-basket mr-3 <?= $current_page === 'household' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Household Overview
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/household/applications" class="<?= $current_page === 'household_applications' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-clipboard-list mr-3 <?= $current_page === 'household_applications' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Purchase Applications
                        <?php if (isset($counts['pending_household']) && $counts['pending_household'] > 0): ?>
                            <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-purple-100 text-purple-800">
                                <?= $counts['pending_household'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/products" class="<?= $current_page === 'products' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-boxes mr-3 <?= $current_page === 'products' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Product Catalog
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/orders" class="<?= $current_page === 'orders' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shopping-cart mr-3 <?= $current_page === 'orders' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Orders
                        <?php if (isset($counts['pending_orders']) && $counts['pending_orders'] > 0): ?>
                            <span class="ml-auto inline-block py-0.5 px-2 text-xs rounded-full bg-green-100 text-green-800">
                                <?= $counts['pending_orders'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Bulk Uploads Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Bulk Uploads
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/uploads" class="<?= $current_page === 'uploads' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-upload mr-3 <?= $current_page === 'uploads' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Upload Logs
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/uploads/savings" class="<?= $current_page === 'savings_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-file-upload mr-3 <?= $current_page === 'savings_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Savings Upload
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/uploads/loans" class="<?= $current_page === 'loans_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-file-upload mr-3 <?= $current_page === 'loans_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Loan Deductions
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/uploads/household" class="<?= $current_page === 'household_upload' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-file-upload mr-3 <?= $current_page === 'household_upload' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Household Deductions
                    </a>
                </div>
                
                <!-- Transactions Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Transactions
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/transactions" class="<?= $current_page === 'transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-exchange-alt mr-3 <?= $current_page === 'transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        All Transactions
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/transactions/savings" class="<?= $current_page === 'savings_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-piggy-bank mr-3 <?= $current_page === 'savings_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Savings Transactions
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/transactions/loans" class="<?= $current_page === 'loan_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-hand-holding-usd mr-3 <?= $current_page === 'loan_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Loan Transactions
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/transactions/household" class="<?= $current_page === 'household_transactions' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shopping-cart mr-3 <?= $current_page === 'household_transactions' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Household Transactions
                    </a>
                </div>
                
                <!-- Reports Section -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Reports
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/reports/savings" class="<?= $current_page === 'savings_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-pie mr-3 <?= $current_page === 'savings_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Savings Reports
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/reports/loans" class="<?= $current_page === 'loans_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-bar mr-3 <?= $current_page === 'loans_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Loan Reports
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/reports/household" class="<?= $current_page === 'household_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-area mr-3 <?= $current_page === 'household_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Household Reports
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/reports/financial" class="<?= $current_page === 'financial_reports' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-line mr-3 <?= $current_page === 'financial_reports' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Financial Reports
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/audit-logs" class="<?= $current_page === 'audit_logs' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-history mr-3 <?= $current_page === 'audit_logs' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Audit Logs
                    </a>
                </div>
                
                <!-- Administration -->
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Administration
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/settings" class="<?= $current_page === 'settings' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-cog mr-3 <?= $current_page === 'settings' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        System Settings
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/backups" class="<?= $current_page === 'backups' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-database mr-3 <?= $current_page === 'backups' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Database Backups
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/logs" class="<?= $current_page === 'system_logs' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-file-alt mr-3 <?= $current_page === 'system_logs' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        System Logs
                    </a>
                </div>

                <!-- SuperAdmin Section - Only visible to superadmins -->
                <?php if (isset($admin) && isset($admin['role']) && $admin['role'] === 'superadmin'): ?>
                <div class="space-y-1 mt-6">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider bg-yellow-50 text-yellow-700 py-1 rounded">
                        SUPERADMIN
                    </h3>
                    
                    <a href="<?= $publicUrl ?>/admin/users" class="<?= $current_page === 'users' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-users-cog mr-3 <?= $current_page === 'users' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Admin Users Management
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/users/add" class="<?= $current_page === 'add_user' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-user-plus mr-3 <?= $current_page === 'add_user' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Add New Admin
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/system/maintenance" class="<?= $current_page === 'maintenance' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-tools mr-3 <?= $current_page === 'maintenance' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        System Maintenance
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/announcements" class="<?= $current_page === 'announcements' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-bullhorn mr-3 <?= $current_page === 'announcements' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Manage Announcements
                    </a>
                    
                    <a href="<?= $publicUrl ?>/admin/roles" class="<?= $current_page === 'roles' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-user-tag mr-3 <?= $current_page === 'roles' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                        Role Permissions
                    </a>
                </div>
                <?php endif; ?>
            </nav>
        </div>
        
        <!-- Copy SuperAdmin section for mobile -->
        <?php if (isset($admin) && isset($admin['role']) && $admin['role'] === 'superadmin'): ?>
        <div class="px-2 py-4">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider bg-yellow-50 text-yellow-700 py-1 rounded">
                SUPERADMIN
            </h3>
            <div class="mt-2 space-y-1">
                <a href="<?= $publicUrl ?>/admin/users" class="<?= $current_page === 'users' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-users-cog mr-3 <?= $current_page === 'users' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Admin Users Management
                </a>
                
                <a href="<?= $publicUrl ?>/admin/users/add" class="<?= $current_page === 'add_user' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-user-plus mr-3 <?= $current_page === 'add_user' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Add New Admin
                </a>
                
                <a href="<?= $publicUrl ?>/admin/system/maintenance" class="<?= $current_page === 'maintenance' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-tools mr-3 <?= $current_page === 'maintenance' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    System Maintenance
                </a>
                
                <a href="<?= $publicUrl ?>/admin/announcements" class="<?= $current_page === 'announcements' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-bullhorn mr-3 <?= $current_page === 'announcements' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Manage Announcements
                </a>
                
                <a href="<?= $publicUrl ?>/admin/roles" class="<?= $current_page === 'roles' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-user-tag mr-3 <?= $current_page === 'roles' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' ?>"></i>
                    Role Permissions
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Profile section for mobile -->
        <div class="border-t border-gray-200 py-4 px-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 h-9 w-9">
                    <img class="h-9 w-9 rounded-full" src="<?= isset($admin) && !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : '/assets/images/default-avatar.png' ?>" alt="Profile">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        <?= isset($admin) && isset($admin['first_name']) && isset($admin['last_name']) ? htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) : 'Admin User' ?>
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        <?= isset($admin) && isset($admin['role']) ? htmlspecialchars($admin['role']) : 'admin' ?>
                    </p>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="<?= $publicUrl ?>/admin/profile" class="block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-md">
                    <i class="fas fa-user mr-2 text-gray-500"></i> Your Profile
                </a>
                <a href="<?= $publicUrl ?>/logout" class="block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-md">
                    <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i> Logout
                </a>
            </div>
        </div>
    </div>
    
    <div class="flex-shrink-0 w-14" aria-hidden="true">
        <!-- Dummy element to force sidebar to shrink to fit close icon -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile menu toggle
    const profileMenuButton = document.getElementById('profileMenuButton');
    const profileMenu = document.getElementById('profileMenu');
    
    if (profileMenuButton && profileMenu) {
        profileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        
        // Close the menu when clicking elsewhere
        document.addEventListener('click', function() {
            if (!profileMenu.classList.contains('hidden')) {
                profileMenu.classList.add('hidden');
            }
        });
    }
    
    // Mobile sidebar toggle
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const closeMobileSidebar = document.getElementById('closeMobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (mobileSidebarToggle && mobileSidebar && closeMobileSidebar) {
        mobileSidebarToggle.addEventListener('click', function() {
            mobileSidebar.classList.remove('hidden');
        });
        
        // Close sidebar when clicking the close button or overlay
        function closeSidebar() {
            mobileSidebar.classList.add('hidden');
        }
        
        if (closeMobileSidebar) {
            closeMobileSidebar.addEventListener('click', closeSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
    }
});
</script> 