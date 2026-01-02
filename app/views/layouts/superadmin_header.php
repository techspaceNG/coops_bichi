<?php
// The url() function is now defined in app/helpers/url_helper.php
// and is included by the Controller class
if (!isset($current_page)) {
    $current_page = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>FCET Bichi Cooperative Superadmin</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1a3f85;
            --primary-hover: #14326b;
            --secondary-color: #64748b;
            --sidebar-width: 260px;
            --header-height: 64px;
            --bg-body: #f1f5f9;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            overflow-x: hidden;
        }
        
        /* Layout Structure */
        .wrapper {
            display: flex;
            width: 100%;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--primary-color);
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .sidebar-brand:hover {
            color: #e2e8f0;
        }

        .sidebar-content {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }
        
        .sidebar-label {
            padding: 0.75rem 1.5rem 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.5);
        }
        
        .nav-link {
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            transition: all 0.2s;
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.05);
        }
        
        .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            border-left-color: #38bdf8; /* Highlight accent */
        }
        
        .nav-link i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
            opacity: 0.9;
        }
        
        .dropdown-toggle::after {
            margin-left: auto;
        }
        
        /* Main Content Area */
        .main {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        
        /* Navbar Styles */
        .top-navbar {
            height: var(--header-height);
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        
        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #334155;
            cursor: pointer;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .notification-btn {
            position: relative;
            color: #64748b;
            font-size: 1.1rem;
            text-decoration: none;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            font-size: 0.65rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #334155;
            font-weight: 500;
        }
        
        .profile-dropdown .dropdown-toggle::after {
            display: none; 
        }
        
        .profile-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
        
        /* Pages Content */
        .content-wrapper {
            padding: 2rem;
            flex: 1;
        }
        
        /* Card Utilities */
        .card {
            border: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
            border-radius: 0.75rem;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main {
                width: 100%;
                margin-left: 0;
            }
            .navbar-toggle {
                display: block;
            }
        }
    </style>
    
    <!-- Page Specific CSS -->
    <?php if (isset($page_specific_css)): ?><style><?= $page_specific_css ?></style><?php endif; ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Admin Helper Scripts -->
    <script>
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(amount);
        }
    </script>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= url('/superadmin/dashboard') ?>" class="sidebar-brand">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas fa-layer-group fa-sm"></i>
                </div>
                <span>Superadmin</span>
            </a>
            <button class="btn btn-link text-white d-lg-none ms-auto" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <div class="sidebar-label">Main Menu</div>
            <a href="<?= url('/superadmin/dashboard') ?>" class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="<?= url('/superadmin/manage-admins') ?>" class="nav-link <?= $current_page === 'manage_admins' ? 'active' : '' ?>">
                <i class="fas fa-user-shield"></i> Admins
            </a>
            <a href="<?= url('/superadmin/system-settings') ?>" class="nav-link <?= $current_page === 'system_settings' ? 'active' : '' ?>">
                <i class="fas fa-sliders-h"></i> Settings
            </a>

            <div class="sidebar-label mt-3">Modules</div>
            <a href="<?= url('/superadmin/members') ?>" class="nav-link <?= $current_page === 'members' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Members
            </a>
            
            <a href="#savingsSubmenu" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <span><i class="fas fa-piggy-bank"></i> Savings</span>
                <i class="fas fa-chevron-down fa-xs ms-auto" style="width: auto; opacity: 0.5;"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['savings', 'savings_reports']) ? 'show' : '' ?>" id="savingsSubmenu" style="background: rgba(0,0,0,0.1);">
                <a href="<?= url('/superadmin/savings') ?>" class="nav-link ps-5 small text-white-50">View All</a>
            </div>

            <a href="#loansSubmenu" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <span><i class="fas fa-hand-holding-usd"></i> Loans</span>
                <i class="fas fa-chevron-down fa-xs ms-auto" style="width: auto; opacity: 0.5;"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['loans', 'add_loan']) ? 'show' : '' ?>" id="loansSubmenu" style="background: rgba(0,0,0,0.1);">
                <a href="<?= url('/superadmin/loans') ?>" class="nav-link ps-5 small text-white-50">View Loans</a>
                <a href="<?= url('/superadmin/add-loan-deduction') ?>" class="nav-link ps-5 small text-white-50">Add Deduction</a>
            </div>

            <a href="#householdSubmenu" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <span><i class="fas fa-shopping-basket"></i> Household</span>
                <i class="fas fa-chevron-down fa-xs ms-auto" style="width: auto; opacity: 0.5;"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['household', 'add_household']) ? 'show' : '' ?>" id="householdSubmenu" style="background: rgba(0,0,0,0.1);">
                <a href="<?= url('/superadmin/household') ?>" class="nav-link ps-5 small text-white-50">View Household</a>
                <a href="<?= url('/superadmin/add-household-deduction') ?>" class="nav-link ps-5 small text-white-50">Add Deduction</a>
            </div>

            <a href="#sharesSubmenu" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <span><i class="fas fa-chart-pie"></i> Shares</span>
                <i class="fas fa-chevron-down fa-xs ms-auto" style="width: auto; opacity: 0.5;"></i>
            </a>
            <div class="collapse <?= in_array($current_page, ['shares', 'add_share']) ? 'show' : '' ?>" id="sharesSubmenu" style="background: rgba(0,0,0,0.1);">
                <a href="<?= url('/superadmin/shares') ?>" class="nav-link ps-5 small text-white-50">View Shares</a>
                <a href="<?= url('/superadmin/add-share-deduction') ?>" class="nav-link ps-5 small text-white-50">Add Deduction</a>
            </div>

            <a href="<?= url('/superadmin/reports') ?>" class="nav-link <?= $current_page === 'reports' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Reports
            </a>

            <div class="sidebar-label mt-3">System</div>
            <a href="<?= url('/superadmin/system-logs') ?>" class="nav-link <?= $current_page === 'system_logs' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> Logs
            </a>
            <a href="<?= url('/superadmin/database-backup') ?>" class="nav-link <?= $current_page === 'database_backup' ? 'active' : '' ?>">
                <i class="fas fa-database"></i> Backups
            </a>
        </div>
        
        <div class="p-3 border-top border-secondary">
            <a href="<?= url('/logout') ?>" class="btn btn-outline-light w-100 btn-sm">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <button class="navbar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <h5 class="mb-0 d-none d-md-block fw-bold text-dark"><?= isset($page_title) ? $page_title : 'Dashboard' ?></h5>
            
            <div class="user-menu">
                <!-- Notifications -->
                <a href="<?= url('/superadmin/notifications') ?>" class="notification-btn">
                    <i class="far fa-bell"></i>
                    <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                        <span class="notification-badge"><?= $unread_notifications_count ?></span>
                    <?php endif; ?>
                </a>
                
                <!-- Profile -->
                <div class="dropdown profile-dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="<?= isset($admin) && !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : url('/assets/images/default-profile.png') ?>" alt="Admin" class="profile-img">
                        <div class="d-none d-md-block text-start">
                            <div class="small fw-bold text-dark"><?= isset($admin['name']) ? htmlspecialchars($admin['name']) : 'Super Admin' ?></div>
                            <div class="small text-muted" style="font-size: 0.7rem;">Administrator</div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="<?= url('/superadmin/profile') ?>"><i class="fas fa-user-circle me-2 text-muted"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="<?= url('/superadmin/settings') ?>"><i class="fas fa-cog me-2 text-muted"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Page -->
        <div class="content-wrapper">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>