<?php
// The url() function is now defined in app/helpers/url_helper.php
// and is included by the Controller class
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>FCET Bichi Cooperative Superadmin</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/assets/css/admin.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Global Superadmin Styles -->
    <style>
        :root {
            --color-primary-50: #f0f8ff;
            --color-primary-100: #e0f0fe;
            --color-primary-200: #bae0fd;
            --color-primary-300: #90cbfb;
            --color-primary-400: #60b0f7;
            --color-primary-500: #3892f3;
            --color-primary-600: #2574e6;
            --color-primary-700: #1c5bd0;
            --color-primary-800: #1b49a8;
            --color-primary-900: #1a3f85;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            padding-top: 56px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        
        .bg-superadmin {
            background-color: #1a3f85;
            color: white;
        }
        
        .text-superadmin {
            color: #1a3f85;
        }
        
        /* Layout structure */
        .page-wrapper {
            display: flex;
            width: 100%;
            min-height: calc(100vh - 56px);
        }
        
        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            width: 250px;
            height: calc(100vh - 56px);
            overflow-y: auto;
            background-color: #1a3f85; /* Dark Blue match header */
            border-right: 1px solid #1a3f85;
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            flex: 1;
            transition: all 0.3s ease;
        }
        
        .main-content .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        
        /* Sidebar Links */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            margin-bottom: 0.2rem;
            border-radius: 0.25rem;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            background-color: #fff;
            color: #1a3f85;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
        }
        
        .sidebar .border-bottom {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        .sidebar .border-top {
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        .sidebar .fw-bold {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        /* Custom styling for dashboard cards */
        .card.border-left-primary {
            border-left: 4px solid var(--color-primary-500) !important;
        }
        
        .card.border-left-success {
            border-left: 4px solid #28a745 !important;
        }
        
        .card.border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
        
        .card.border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                opacity: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
            .sidebar.show {
                width: 250px;
                opacity: 1;
            }
        }
        
        /* Ensure proper padding on all screen sizes */
        @media (min-width: 992px) {
            .main-content .container-fluid {
                padding-right: 20px;
                padding-left: 20px;
            }
        }
        
        /* Ensure tables are responsive */
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
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
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-superadmin fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('/superadmin/dashboard') ?>">
                <div class="d-inline-flex align-items-center">
                    <div class="bg-white rounded p-1 me-2 d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                        <i class="fas fa-university text-primary"></i>
                    </div>
                    <span>Superadmin Portal</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#superadminNavbar" aria-controls="superadminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="superadminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/superadmin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/superadmin/manage-admins') ?>">Administrators</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/superadmin/system-settings') ?>">System Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/superadmin/system-logs') ?>">System Logs</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <!-- Notifications -->
                    <div class="dropdown me-3">
                        <a class="text-white position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $unread_notifications_count > 9 ? '9+' : $unread_notifications_count ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <li>
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                    <h6 class="dropdown-header p-0 m-0">Notifications</h6>
                                    <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                                        <a href="<?= url('/superadmin/mark-all-notifications-read') ?>" class="text-primary small">Mark all as read</a>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php if (isset($recent_notifications) && !empty($recent_notifications)): ?>
                                <?php foreach ($recent_notifications as $notification): ?>
                                    <li>
                                        <div class="dropdown-item <?= !$notification['is_read'] ? 'bg-light' : '' ?>">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="fas fa-circle <?= !$notification['is_read'] ? 'text-primary' : 'text-secondary' ?>" style="font-size: 0.5rem; margin-top: 0.5rem;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($notification['title']) ?></h6>
                                                        <small class="text-muted ms-2"><?= date('M d, H:i', strtotime($notification['created_at'])) ?></small>
                                                    </div>
                                                    <p class="mb-1 small"><?= htmlspecialchars($notification['message']) ?></p>
                                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                                        <?php if ($notification['link']): ?>
                                                            <a href="<?= url($notification['link']) ?>" class="btn btn-sm btn-primary">View</a>
                                                        <?php endif; ?>
                                                        <?php if (!$notification['is_read']): ?>
                                                            <a href="<?= url('/superadmin/mark-notification-read/' . $notification['id']) ?>" class="small text-muted">Mark as read</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <li><hr class="dropdown-divider my-0"></li>
                                <li><a class="dropdown-item text-center py-2" href="<?= url('/superadmin/notifications') ?>">View all notifications</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item text-center py-3" href="#">No notifications</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <!-- User dropdown -->
                    <div class="dropdown">
                        <a class="text-white d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= isset($admin) && !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : url('/profile.png') ?>" alt="Profile" width="32" height="32" class="rounded-circle me-2">
                            <span class="d-none d-sm-inline"><?= isset($admin) && isset($admin['name']) ? htmlspecialchars($admin['name']) : 'Superadmin' ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= url('/superadmin/profile') ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('/superadmin/settings') ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Sidebar -->
        <div class="sidebar d-none d-md-block">
            <div class="p-3">
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                    <i class="fas fa-user-shield me-2"></i>
                    <span class="fw-bold">SUPERADMIN MENU</span>
                </div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>" href="<?= url('/superadmin/dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'manage_admins' ? 'active' : '' ?>" href="<?= url('/superadmin/manage-admins') ?>">
                            <i class="fas fa-users-cog me-2"></i> Manage Administrators
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'create_admin' ? 'active' : '' ?>" href="<?= url('/superadmin/create-admin') ?>">
                            <i class="fas fa-user-plus me-2"></i> Add New Admin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'system_settings' ? 'active' : '' ?>" href="<?= url('/superadmin/system-settings') ?>">
                            <i class="fas fa-cogs me-2"></i> System Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'database_backup' ? 'active' : '' ?>" href="<?= url('/superadmin/database-backup') ?>">
                            <i class="fas fa-database me-2"></i> Database Backup
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'system_logs' ? 'active' : '' ?>" href="<?= url('/superadmin/system-logs') ?>">
                            <i class="fas fa-history me-2"></i> System Logs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'notifications' ? 'active' : '' ?>" href="<?= url('/superadmin/notifications') ?>">
                            <i class="fas fa-bell me-2"></i> Notifications
                            <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-2"><?= $unread_notifications_count > 9 ? '9+' : $unread_notifications_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
                
                <div class="mt-4 pt-2 border-top">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-box me-2"></i>
                        <span class="fw-bold">MODULES</span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/superadmin/members') ?>">
                                <i class="fas fa-users me-2"></i> Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/superadmin/savings') ?>">
                                <i class="fas fa-piggy-bank me-2"></i> Savings
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="loansDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-hand-holding-usd me-2"></i> Loans
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="loansDropdown">
                                <li><a class="dropdown-item" href="<?= url('/superadmin/loans') ?>">View Loans</a></li>
                                <li><a class="dropdown-item" href="<?= url('/superadmin/add-loan-deduction') ?>">Add Loan Deduction</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="householdDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shopping-basket me-2"></i> Household
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="householdDropdown">
                                <li><a class="dropdown-item" href="<?= url('/superadmin/household') ?>">View Household</a></li>
                                <li><a class="dropdown-item" href="<?= url('/superadmin/add-household-deduction') ?>">Add Household Deduction</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $current_page === 'shares' ? 'active' : '' ?>" href="#" id="sharesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-pie me-2"></i> Shares
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="sharesDropdown">
                                <li><a class="dropdown-item" href="<?= url('/superadmin/shares') ?>">View Shares</a></li>
                                <li><a class="dropdown-item" href="<?= url('/superadmin/add-share-deduction') ?>">Add Share Deduction</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/superadmin/reports') ?>">
                                <i class="fas fa-chart-line me-2"></i> Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Wrapper -->
        <div class="main-content">
            <!-- Alerts will be shown here -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Content Container -->
            <div class="container-fluid">