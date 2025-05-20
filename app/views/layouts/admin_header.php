<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>FCET Bichi Cooperative Admin</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/Coops_Bichi/public/assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/Coops_Bichi/public/assets/css/admin.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Global Admin Styles -->
    <style>
        /* Custom Primary Colors - Can be overridden in theme settings */
        :root {
            --color-primary-50: #eef2ff;
            --color-primary-100: #e0e7ff;
            --color-primary-200: #c7d2fe;
            --color-primary-300: #a5b4fc;
            --color-primary-400: #818cf8;
            --color-primary-500: #6366f1;
            --color-primary-600: #4f46e5;
            --color-primary-700: #4338ca;
            --color-primary-800: #3730a3;
            --color-primary-900: #312e81;
        }
        
        /* Remove all underlines from links */
        a {
            text-decoration: none !important;
        }
        
        a:hover, a:focus, a:active {
            text-decoration: none !important;
        }
        
        /* Utility classes for primary colors */
        .bg-primary-50 { background-color: var(--color-primary-50); }
        .bg-primary-100 { background-color: var(--color-primary-100); }
        .bg-primary-200 { background-color: var(--color-primary-200); }
        .bg-primary-300 { background-color: var(--color-primary-300); }
        .bg-primary-400 { background-color: var(--color-primary-400); }
        .bg-primary-500 { background-color: var(--color-primary-500); }
        .bg-primary-600 { background-color: var(--color-primary-600); }
        .bg-primary-700 { background-color: var(--color-primary-700); }
        .bg-primary-800 { background-color: var(--color-primary-800); }
        .bg-primary-900 { background-color: var(--color-primary-900); }
        
        .text-primary-50 { color: var(--color-primary-50); }
        .text-primary-100 { color: var(--color-primary-100); }
        .text-primary-200 { color: var(--color-primary-200); }
        .text-primary-300 { color: var(--color-primary-300); }
        .text-primary-400 { color: var(--color-primary-400); }
        .text-primary-500 { color: var(--color-primary-500); }
        .text-primary-600 { color: var(--color-primary-600); }
        .text-primary-700 { color: var(--color-primary-700); }
        .text-primary-800 { color: var(--color-primary-800); }
        .text-primary-900 { color: var(--color-primary-900); }
        
        .border-primary-50 { border-color: var(--color-primary-50); }
        .border-primary-100 { border-color: var(--color-primary-100); }
        .border-primary-200 { border-color: var(--color-primary-200); }
        .border-primary-300 { border-color: var(--color-primary-300); }
        .border-primary-400 { border-color: var(--color-primary-400); }
        .border-primary-500 { border-color: var(--color-primary-500); }
        .border-primary-600 { border-color: var(--color-primary-600); }
        .border-primary-700 { border-color: var(--color-primary-700); }
        .border-primary-800 { border-color: var(--color-primary-800); }
        .border-primary-900 { border-color: var(--color-primary-900); }
        
        .hover\:bg-primary-50:hover { background-color: var(--color-primary-50); }
        .hover\:bg-primary-100:hover { background-color: var(--color-primary-100); }
        .hover\:bg-primary-200:hover { background-color: var(--color-primary-200); }
        .hover\:bg-primary-300:hover { background-color: var(--color-primary-300); }
        .hover\:bg-primary-400:hover { background-color: var(--color-primary-400); }
        .hover\:bg-primary-500:hover { background-color: var(--color-primary-500); }
        .hover\:bg-primary-600:hover { background-color: var(--color-primary-600); }
        .hover\:bg-primary-700:hover { background-color: var(--color-primary-700); }
        .hover\:bg-primary-800:hover { background-color: var(--color-primary-800); }
        .hover\:bg-primary-900:hover { background-color: var(--color-primary-900); }
        
        .hover\:text-primary-50:hover { color: var(--color-primary-50); }
        .hover\:text-primary-100:hover { color: var(--color-primary-100); }
        .hover\:text-primary-200:hover { color: var(--color-primary-200); }
        .hover\:text-primary-300:hover { color: var(--color-primary-300); }
        .hover\:text-primary-400:hover { color: var(--color-primary-400); }
        .hover\:text-primary-500:hover { color: var(--color-primary-500); }
        .hover\:text-primary-600:hover { color: var(--color-primary-600); }
        .hover\:text-primary-700:hover { color: var(--color-primary-700); }
        .hover\:text-primary-800:hover { color: var(--color-primary-800); }
        .hover\:text-primary-900:hover { color: var(--color-primary-900); }
        
        .focus\:ring-primary-50:focus { --tw-ring-color: var(--color-primary-50); }
        .focus\:ring-primary-100:focus { --tw-ring-color: var(--color-primary-100); }
        .focus\:ring-primary-200:focus { --tw-ring-color: var(--color-primary-200); }
        .focus\:ring-primary-300:focus { --tw-ring-color: var(--color-primary-300); }
        .focus\:ring-primary-400:focus { --tw-ring-color: var(--color-primary-400); }
        .focus\:ring-primary-500:focus { --tw-ring-color: var(--color-primary-500); }
        .focus\:ring-primary-600:focus { --tw-ring-color: var(--color-primary-600); }
        .focus\:ring-primary-700:focus { --tw-ring-color: var(--color-primary-700); }
        .focus\:ring-primary-800:focus { --tw-ring-color: var(--color-primary-800); }
        .focus\:ring-primary-900:focus { --tw-ring-color: var(--color-primary-900); }
        
        /* Global Admin Styles */
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }
        
        .focus-within\:z-10:focus-within {
            z-index: 10;
        }
    </style>
    
    <!-- Page Specific CSS -->
    <?php if (isset($page_specific_css)): ?>
        <style>
            <?= $page_specific_css ?>
        </style>
    <?php endif; ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Global Admin JavaScript -->
    <script>
        // Common functions used across admin pages
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-NG', {
                style: 'currency',
                currency: 'NGN',
                minimumFractionDigits: 2
            }).format(amount);
        }
        
        function formatDate(dateString, format = 'long') {
            const date = new Date(dateString);
            
            if (format === 'short') {
                return date.toLocaleDateString('en-NG');
            } else if (format === 'time') {
                return date.toLocaleTimeString('en-NG');
            } else {
                return date.toLocaleDateString('en-NG', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        }
    </script>
    
    <!-- Page Specific JavaScript Variables -->
    <?php if (isset($page_specific_js_vars)): ?>
        <script>
            <?= $page_specific_js_vars ?>
        </script>
    <?php endif; ?>
</head>
<body class="h-full bg-gray-100">
    <!-- Top Navigation Bar -->
    <div class="bg-white shadow-sm fixed top-0 right-0 left-0 z-30">
        <div class="px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between">
                <!-- Left side: Logo and Main Nav -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="/Coops_Bichi/public/admin/dashboard" class="flex items-center">
                        <img src="/Coops_Bichi/public/assets/images/logo.png" alt="FCET Bichi Cooperative" class="h-8 w-auto">
                        <span class="ml-2 text-lg font-semibold text-gray-800">Admin Portal</span>
                    </a>
                    
                    <!-- Mobile menu button -->
                    <button type="button" id="mobileMenuButton" class="md:hidden ml-4 px-2 py-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    
                    <!-- Main Navigation (Desktop) -->
                    <nav class="hidden md:flex ml-8 space-x-6">
                        <a href="/Coops_Bichi/public/admin/dashboard" class="<?= $current_page === 'dashboard' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="/Coops_Bichi/public/admin/members" class="<?= $current_page === 'members' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Members
                        </a>
                        <a href="/Coops_Bichi/public/admin/savings" class="<?= $current_page === 'savings' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Savings
                        </a>
                        <a href="/Coops_Bichi/public/admin/shares" class="<?= $current_page === 'shares' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Shares
                        </a>
                        <a href="/Coops_Bichi/public/admin/loans" class="<?= $current_page === 'loans' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Loans
                        </a>
                        <a href="/Coops_Bichi/public/admin/household" class="<?= $current_page === 'household' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Household
                        </a>
                        <a href="/Coops_Bichi/public/admin/reports" class="<?= $current_page === 'reports' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-600 hover:text-gray-900' ?> px-1 py-2 text-sm font-medium">
                            Reports
                        </a>
                    </nav>
                </div>
                
                <!-- Right side: Admin tools -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Search -->
                    <div class="relative hidden sm:block">
                        <input type="text" id="globalSearch" 
                               placeholder="Search..." 
                               class="w-40 sm:w-64 pr-8 pl-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notificationsButton" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2">
                            <i class="fas fa-bell text-lg"></i>
                            <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                                <span class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center transform translate-x-1 -translate-y-1">
                                    <?= $unread_notifications_count > 9 ? '9+' : $unread_notifications_count ?>
                                </span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Notifications dropdown -->
                        <div id="notificationsDropdown" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                                        <a href="/Coops_Bichi/public/admin/notifications/mark-all-read" class="text-xs text-primary-600 hover:text-primary-800">Mark all as read</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="max-h-60 overflow-y-auto py-1">
                                <?php if (isset($recent_notifications) && !empty($recent_notifications)): ?>
                                    <?php foreach ($recent_notifications as $notification): ?>
                                        <a href="<?= htmlspecialchars($notification['link']) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?= $notification['is_read'] ? '' : 'bg-blue-50' ?>">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 pt-0.5">
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full 
                                                        <?php 
                                                        switch ($notification['type']) {
                                                            case 'loan': echo 'bg-blue-100 text-blue-600'; break;
                                                            case 'savings': echo 'bg-green-100 text-green-600'; break;
                                                            case 'member': echo 'bg-purple-100 text-purple-600'; break;
                                                            case 'system': echo 'bg-red-100 text-red-600'; break;
                                                            default: echo 'bg-gray-100 text-gray-600';
                                                        }
                                                        ?>">
                                                        <i class="fas 
                                                        <?php 
                                                        switch ($notification['type']) {
                                                            case 'loan': echo 'fa-hand-holding-usd'; break;
                                                            case 'savings': echo 'fa-piggy-bank'; break;
                                                            case 'member': echo 'fa-user'; break;
                                                            case 'system': echo 'fa-exclamation-circle'; break;
                                                            default: echo 'fa-bell';
                                                        }
                                                        ?>"></i>
                                                    </span>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($notification['title']) ?></p>
                                                    <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($notification['message']) ?></p>
                                                    <p class="text-xs text-gray-400 mt-1"><?= date('M d, H:i', strtotime($notification['created_at'])) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="px-4 py-6 text-center text-sm text-gray-500">
                                        <p>No notifications</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($recent_notifications) && !empty($recent_notifications)): ?>
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <a href="/Coops_Bichi/public/admin/notifications" class="block text-center text-xs font-medium text-primary-600 hover:text-primary-800">
                                        View all notifications
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Help -->
                    <button id="helpButton" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2">
                        <i class="fas fa-question-circle text-lg"></i>
                    </button>
                    
                    <!-- Profile dropdown -->
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center text-sm focus:outline-none p-1">
                            <img class="h-8 w-8 rounded-full" src="<?= isset($admin) && !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : '/Coops_Bichi/public/assets/images/profile.png' ?>" alt="Profile">
                            <span class="hidden md:block ml-2 text-sm text-gray-700"><?= isset($admin) && isset($admin['first_name']) ? htmlspecialchars($admin['first_name']) : 'Admin' ?></span>
                            <i class="ml-1 fas fa-chevron-down text-gray-400"></i>
                        </button>
                        
                                                    <!-- User dropdown menu -->
                        <div id="userMenu" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 hidden z-10">
                            <a href="/Coops_Bichi/public/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Your Profile
                            </a>
                            <a href="/Coops_Bichi/public/admin/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Settings
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <a href="/Coops_Bichi/public/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Help Modal -->
    <div id="helpModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-question text-primary-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Help & Resources
                            </h3>
                            <div class="mt-4">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-800">Admin Documentation</h4>
                                        <p class="text-sm text-gray-500">Browse the complete admin documentation for detailed instructions.</p>
                                        <a href="/Coops_Bichi/public/admin/docs" class="mt-2 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                                            View Documentation <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-800">Quick Help Videos</h4>
                                        <p class="text-sm text-gray-500">Watch short tutorial videos for common tasks.</p>
                                        <a href="/Coops_Bichi/public/admin/tutorials" class="mt-2 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                                            Watch Tutorials <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-800">Contact Support</h4>
                                        <p class="text-sm text-gray-500">Need further assistance? Reach out to our technical team.</p>
                                        <a href="/Coops_Bichi/public/admin/support" class="mt-2 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                                            Contact Support <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="closeHelpModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="pt-16">
        <!-- Mobile Navigation Menu (Hidden by default) -->
        <div id="mobileNav" class="fixed left-0 right-0 top-16 bg-white border-b border-gray-200 shadow-md z-20 hidden">
            <div class="py-3 px-4">
                <nav class="flex flex-col space-y-3">
                    <a href="/Coops_Bichi/public/admin/dashboard" class="<?= $current_page === 'dashboard' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
                    </a>
                    <a href="/Coops_Bichi/public/admin/members" class="<?= $current_page === 'members' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-users mr-3 w-5 text-center"></i> Members
                    </a>
                    <a href="/Coops_Bichi/public/admin/savings" class="<?= $current_page === 'savings' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-piggy-bank mr-3 w-5 text-center"></i> Savings
                    </a>
                    <a href="/Coops_Bichi/public/admin/shares" class="<?= $current_page === 'shares' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-chart-pie mr-3 w-5 text-center"></i> Shares
                    </a>
                    <a href="/Coops_Bichi/public/admin/loans" class="<?= $current_page === 'loans' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-hand-holding-usd mr-3 w-5 text-center"></i> Loans
                    </a>
                    <a href="/Coops_Bichi/public/admin/household" class="<?= $current_page === 'household' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-home mr-3 w-5 text-center"></i> Household
                    </a>
                    <a href="/Coops_Bichi/public/admin/reports" class="<?= $current_page === 'reports' ? 'text-primary-600 bg-primary-50 font-medium' : 'text-gray-700' ?> py-3 px-4 hover:bg-gray-50 rounded-md text-base flex items-center">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Reports
                    </a>
                </nav>
            </div>
        </div>
        <!-- Page content goes here, handled by specific view files -->
        <?php if (isset($_SESSION['admin_success'])): ?>
            <div id="successAlert" class="fixed top-20 right-4 z-50 max-w-md bg-green-50 border-l-4 border-green-400 p-4 shadow-md rounded-r">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800"><?= htmlspecialchars($_SESSION['admin_success']) ?></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" onclick="document.getElementById('successAlert').remove()" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none">
                                <span class="sr-only">Dismiss</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    const alert = document.getElementById('successAlert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            </script>
            <?php unset($_SESSION['admin_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['admin_error'])): ?>
            <div id="errorAlert" class="fixed top-20 right-4 z-50 max-w-md bg-red-50 border-l-4 border-red-400 p-4 shadow-md rounded-r">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800"><?= htmlspecialchars($_SESSION['admin_error']) ?></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" onclick="document.getElementById('errorAlert').remove()" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none">
                                <span class="sr-only">Dismiss</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    const alert = document.getElementById('errorAlert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            </script>
            <?php unset($_SESSION['admin_error']); ?>
        <?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Menu Toggle
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    
    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
    }
    
    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileNav = document.getElementById('mobileNav');
    
    if (mobileMenuButton && mobileNav) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileNav.classList.toggle('hidden');
        });
    }
    
    // Notifications Toggle
    const notificationsButton = document.getElementById('notificationsButton');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    
    if (notificationsButton && notificationsDropdown) {
        notificationsButton.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('hidden');
        });
    }
    
    // Help Modal Toggle
    const helpButton = document.getElementById('helpButton');
    const helpModal = document.getElementById('helpModal');
    const closeHelpModal = document.getElementById('closeHelpModal');
    
    if (helpButton && helpModal && closeHelpModal) {
        helpButton.addEventListener('click', function() {
            helpModal.classList.remove('hidden');
        });
        
        closeHelpModal.addEventListener('click', function() {
            helpModal.classList.add('hidden');
        });
    }
    
    // Close dropdowns when clicking elsewhere
    document.addEventListener('click', function(e) {
        if (userMenu && !userMenu.classList.contains('hidden')) {
            userMenu.classList.add('hidden');
        }
        
        if (notificationsDropdown && !notificationsDropdown.classList.contains('hidden')) {
            notificationsDropdown.classList.add('hidden');
        }
        
        if (mobileNav && !mobileNav.classList.contains('hidden')) {
            mobileNav.classList.add('hidden');
        }
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (helpModal && e.target === helpModal) {
            helpModal.classList.add('hidden');
        }
    });
    
    // Global Search functionality
    const globalSearch = document.getElementById('globalSearch');
    
    if (globalSearch) {
        globalSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.location.href = '/Coops_Bichi/public/admin/search?q=' + encodeURIComponent(this.value);
            }
        });
    }
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</body>
</html> 
