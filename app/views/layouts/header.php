<?php
$publicUrl = \App\Core\Config::getPublicUrl();

// Add this helper method at the beginning of the file, after the first PHP opening tag
function formatNotificationLink($link, $publicUrl, $id) {
    if (empty($link)) {
        return $publicUrl . '/member/notifications/' . $id;
    }
    
    // If link already starts with http:// or https://, use as is
    if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0) {
        return $link;
    }
    
    // If link already includes the publicUrl, don't add it again
    if (strpos($link, $publicUrl) === 0) {
        return $link;
    }
    
    // If link starts with /, assume it's a relative path and add publicUrl
    if (strpos($link, '/') === 0) {
        return $publicUrl . $link;
    }
    
    // Otherwise, assume it's a relative path and add publicUrl/
    return $publicUrl . '/' . $link;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCET Bichi Staff Multipurpose Cooperative Society</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= $publicUrl ?>/assets/css/custom.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">
    <!-- Header Navigation -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?= $publicUrl ?>" class="text-primary-700 font-bold text-xl">
                        FCET Bichi COOPS
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="<?= $publicUrl ?>" class="text-gray-600 hover:text-primary-600">Home</a>
                    <a href="<?= $publicUrl ?>/about" class="text-gray-600 hover:text-primary-600">About</a>
                    <a href="<?= $publicUrl ?>/contact" class="text-gray-600 hover:text-primary-600">Contact</a>
                    <a href="<?= $publicUrl ?>/faq" class="text-gray-600 hover:text-primary-600">FAQ</a>
                    
                    <?php if (\App\Helpers\Auth::isMemberLoggedIn()): ?>
                        <a href="<?= $publicUrl ?>/member/dashboard" class="text-gray-600 hover:text-primary-600">Dashboard</a>
                        <a href="<?= $publicUrl ?>/member/savings" class="text-gray-600 hover:text-primary-600">Savings</a>
                        <a href="<?= $publicUrl ?>/member/loans" class="text-gray-600 hover:text-primary-600">Loans</a>
                        <a href="<?= $publicUrl ?>/member/household" class="text-gray-600 hover:text-primary-600">Household</a>
                        <a href="<?= $publicUrl ?>/member/shares" class="text-gray-600 hover:text-primary-600">Shares</a>
                        
                        <!-- Notification Bell -->
                        <div class="relative group">
                            <button class="text-gray-600 hover:text-primary-600 flex items-center">
                                <i class="fas fa-bell"></i>
                                <?php 
                                // Get notification count if not already set from controller
                                if (!isset($notification_count)) {
                                    $member_id = \App\Helpers\Session::userId();
                                    $notification_count = \App\Models\Notification::getUnreadCount($member_id);
                                }
                                
                                if ($notification_count > 0): 
                                ?>
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    <?= $notification_count > 9 ? '9+' : $notification_count ?>
                                </span>
                                <?php endif; ?>
                            </button>
                            <div class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-20 hidden group-hover:block">
                                <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                    <h4 class="font-medium text-gray-800">Notifications</h4>
                                    <a href="<?= $publicUrl ?>/member/notifications" class="text-xs text-primary-600 hover:text-primary-800">View All</a>
                                </div>
                                
                                <div class="max-h-60 overflow-y-auto">
                                <?php 
                                // Get notifications if not already set from controller
                                if (!isset($notifications) || empty($notifications)) {
                                    $member_id = \App\Helpers\Session::userId();
                                    try {
                                        $db = \App\Config\Database::getConnection();
                                        // Directly query notifications table with correct field names
                                        $stmt = $db->prepare("
                                            SELECT * FROM notifications 
                                            WHERE user_id = ? AND user_type = 'member'
                                            ORDER BY created_at DESC LIMIT 5
                                        ");
                                        $stmt->execute([$member_id]);
                                        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        // Debug info
                                        error_log("Header found " . count($notifications) . " notifications for member ID: $member_id");
                                        
                                        // Log links for debugging
                                        if (!empty($notifications)) {
                                            foreach ($notifications as $n) {
                                                $originalLink = $n['link'] ?? 'none';
                                                $formattedLink = formatNotificationLink($originalLink, $publicUrl, $n['id']);
                                                error_log("Header: Notification #{$n['id']} has link: '$originalLink' → formatted: '$formattedLink'");
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        error_log("Error getting notifications in header: " . $e->getMessage());
                                        $notifications = [];
                                    }
                                }
                                
                                if (!empty($notifications)): 
                                    foreach ($notifications as $notification):
                                        // Set appropriate icon based on notification type
                                        $iconClass = 'fas fa-info-circle text-blue-500';
                                        $type = $notification['type'] ?? 'info';
                                        switch ($type) {
                                            case 'success':
                                                $iconClass = 'fas fa-check-circle text-green-500';
                                                break;
                                            case 'warning':
                                                $iconClass = 'fas fa-exclamation-triangle text-yellow-500';
                                                break;
                                            case 'error':
                                                $iconClass = 'fas fa-exclamation-circle text-red-500';
                                                break;
                                        }
                                ?>
                                    <a href="<?= formatNotificationLink($notification['link'], $publicUrl, $notification['id']) ?>" 
                                       class="notification-dropdown-link block px-4 py-2 text-sm hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                        <div class="flex">
                                            <div class="flex-shrink-0 mr-3">
                                                <i class="<?= $iconClass ?>"></i>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="font-medium text-gray-800 truncate"><?= htmlspecialchars($notification['title'] ?? 'Notification') ?></p>
                                                <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($notification['message'] ?? '') ?></p>
                                                <p class="text-xs text-gray-400 mt-1"><?= isset($notification['created_at']) ? \App\Helpers\Utility::timeAgo($notification['created_at']) : '' ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php 
                                    endforeach;
                                else: 
                                ?>
                                    <div class="px-4 py-6 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-xl mb-2"></i>
                                        <p class="text-sm">No new notifications</p>
                                    </div>
                                <?php endif; ?>
                                </div>
                                
                                <div class="px-4 py-2 border-t border-gray-100">
                                    <a href="<?= $publicUrl ?>/member/notifications/mark-all-read" class="text-xs text-center block text-primary-600 hover:text-primary-800">
                                        Mark All as Read
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative group">
                            <button id="account-button" class="text-gray-600 hover:text-primary-600 flex items-center">
                                Account <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div id="account-dropdown" class="account-dropdown absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden">
                                <a href="<?= $publicUrl ?>/member/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="<?= $publicUrl ?>/member/change-password" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Password</a>
                                <a href="<?= $publicUrl ?>/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php elseif (\App\Helpers\Auth::isAdminLoggedIn()): ?>
                        <a href="<?= $publicUrl ?>/admin/dashboard" class="text-gray-600 hover:text-primary-600">Dashboard</a>
                        <div class="relative group">
                            <button class="text-gray-600 hover:text-primary-600 flex items-center">
                                Account <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                                <a href="<?= $publicUrl ?>/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="<?= $publicUrl ?>/admin/change-password" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Password</a>
                                <a href="<?= $publicUrl ?>/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= $publicUrl ?>/login" class="text-gray-600 hover:text-primary-600">Member Login</a>
                        <a href="<?= $publicUrl ?>/register" class="text-gray-600 hover:text-primary-600">Register</a>
                        <a href="<?= $publicUrl ?>/admin/login" class="text-gray-600 hover:text-primary-600">Admin Login</a>
                    <?php endif; ?>
                </nav>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-primary-600 focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div class="md:hidden hidden mt-4 pb-3" id="mobile-menu">
                <a href="<?= $publicUrl ?>" class="block py-2 text-gray-600 hover:text-primary-600">Home</a>
                <a href="<?= $publicUrl ?>/about" class="block py-2 text-gray-600 hover:text-primary-600">About</a>
                <a href="<?= $publicUrl ?>/contact" class="block py-2 text-gray-600 hover:text-primary-600">Contact</a>
                <a href="<?= $publicUrl ?>/faq" class="block py-2 text-gray-600 hover:text-primary-600">FAQ</a>
                
                <?php if (\App\Helpers\Auth::isMemberLoggedIn()): ?>
                    <a href="<?= $publicUrl ?>/member/dashboard" class="block py-2 text-gray-600 hover:text-primary-600">Dashboard</a>
                    <a href="<?= $publicUrl ?>/member/savings" class="block py-2 text-gray-600 hover:text-primary-600">Savings</a>
                    <a href="<?= $publicUrl ?>/member/loans" class="block py-2 text-gray-600 hover:text-primary-600">Loans</a>
                    <a href="<?= $publicUrl ?>/member/household" class="block py-2 text-gray-600 hover:text-primary-600">Household</a>
                    <a href="<?= $publicUrl ?>/member/shares" class="block py-2 text-gray-600 hover:text-primary-600">Shares</a>
                    
                    <!-- Mobile Notifications Link -->
                    <a href="<?= $publicUrl ?>/member/notifications" class="block py-2 text-gray-600 hover:text-primary-600">
                        Notifications
                        <?php 
                        // Get notification count if not already set from controller
                        if (!isset($notification_count)) {
                            $member_id = \App\Helpers\Session::userId();
                            $notification_count = \App\Models\Notification::getUnreadCount($member_id);
                        }
                        
                        if ($notification_count > 0): 
                        ?>
                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                            <?= $notification_count > 9 ? '9+' : $notification_count ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?= $publicUrl ?>/member/profile" class="block py-2 text-gray-600 hover:text-primary-600">Profile</a>
                    <a href="<?= $publicUrl ?>/member/change-password" class="block py-2 text-gray-600 hover:text-primary-600">Change Password</a>
                    <a href="<?= $publicUrl ?>/logout" class="block py-2 text-gray-600 hover:text-primary-600">Logout</a>
                <?php elseif (\App\Helpers\Auth::isAdminLoggedIn()): ?>
                    <a href="<?= $publicUrl ?>/admin/dashboard" class="block py-2 text-gray-600 hover:text-primary-600">Dashboard</a>
                    <a href="<?= $publicUrl ?>/admin/profile" class="block py-2 text-gray-600 hover:text-primary-600">Profile</a>
                    <a href="<?= $publicUrl ?>/admin/change-password" class="block py-2 text-gray-600 hover:text-primary-600">Change Password</a>
                    <a href="<?= $publicUrl ?>/logout" class="block py-2 text-gray-600 hover:text-primary-600">Logout</a>
                <?php else: ?>
                    <a href="<?= $publicUrl ?>/login" class="block py-2 text-gray-600 hover:text-primary-600">Member Login</a>
                    <a href="<?= $publicUrl ?>/register" class="block py-2 text-gray-600 hover:text-primary-600">Register</a>
                    <a href="<?= $publicUrl ?>/admin/login" class="block py-2 text-gray-600 hover:text-primary-600">Admin Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <?php $flash = \App\Helpers\Utility::getFlashMessage('global'); ?>
    <?php if ($flash): ?>
        <div class="container mx-auto px-4 mt-4">
            <div class="rounded-md p-4 <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-800' : ($flash['type'] === 'error' ? 'bg-red-50 text-red-800' : 'bg-blue-50 text-blue-800') ?>">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <?php if ($flash['type'] === 'success'): ?>
                            <i class="fas fa-check-circle text-green-400"></i>
                        <?php elseif ($flash['type'] === 'error'): ?>
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        <?php else: ?>
                            <i class="fas fa-info-circle text-blue-400"></i>
                        <?php endif; ?>
                    </div>
                    <div class="ml-3">
                        <p><?= htmlspecialchars($flash['message']) ?></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Dismiss</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="flex-grow"> 
    
<script>
// Account dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const accountButton = document.getElementById('account-button');
    const accountDropdown = document.getElementById('account-dropdown');
    
    if (accountButton && accountDropdown) {
        let isDropdownOpen = false;
        
        // Toggle dropdown when clicking on the button
        accountButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            isDropdownOpen = !isDropdownOpen;
            
            if (isDropdownOpen) {
                accountDropdown.classList.remove('hidden');
            } else {
                accountDropdown.classList.add('hidden');
            }
        });
        
        // Keep dropdown open when hovering over it
        accountDropdown.addEventListener('mouseover', function() {
            isDropdownOpen = true;
            accountDropdown.classList.remove('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!accountButton.contains(e.target) && !accountDropdown.contains(e.target)) {
                isDropdownOpen = false;
                accountDropdown.classList.add('hidden');
            }
        });
    }
    
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

// Fix notification links
document.addEventListener('DOMContentLoaded', function() {
    // Get all notification links in the dropdown
    const notificationLinks = document.querySelectorAll('.notification-dropdown-link');
    
    // Add click event listener to each link
    notificationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Prevent default only if debugging
            // e.preventDefault();
            
            // Get the href attribute
            const href = this.getAttribute('href');
            console.log('Clicked notification link:', href);
            
            // Navigate to the link
            window.location.href = href;
        });
    });
});
</script>
</main>
</body>
</html> 