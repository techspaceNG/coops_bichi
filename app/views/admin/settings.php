<?php 
// Set current page for active menu highlighting
$current_page = 'settings';
// Set page title
$page_title = 'Account Settings';
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Account Settings</h1>
            <p class="text-gray-600 text-sm mt-1">Manage your account settings and preferences</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="/Coops_Bichi/public/admin/dashboard" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Alerts will be dynamically inserted by the system -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-key mr-2 text-primary-600"></i> Change Password</h3>
            </div>
            <div class="p-6">
                <form action="/Coops_Bichi/public/admin/update-password" method="POST">
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                        <input type="password" class="border border-gray-300 rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500" 
                            id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                        <input type="password" class="border border-gray-300 rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500" 
                            id="new_password" name="new_password" required>
                        <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                        <input type="password" class="border border-gray-300 rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500" 
                            id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-save mr-2"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Security Settings -->
        <div>
            <!-- Account Security -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-shield-alt mr-2 text-primary-600"></i> Account Security</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Two-Factor Authentication</label>
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" id="enable2fa" disabled>
                            <label class="ml-2 block text-sm text-gray-700" for="enable2fa">Enable Two-Factor Authentication</label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">This feature will be available in a future update.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Login Notifications</label>
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" id="loginNotifications" disabled>
                            <label class="ml-2 block text-sm text-gray-700" for="loginNotifications">Receive email notifications on login</label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">This feature will be available in a future update.</p>
                    </div>
                    
                    <hr class="my-4 border-gray-200">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Information</label>
                        <div class="mb-2 text-sm">
                            <span class="font-medium text-gray-700">Name:</span> <span class="text-gray-600"><?= htmlspecialchars($admin['name']) ?></span>
                        </div>
                        <div class="mb-2 text-sm">
                            <span class="font-medium text-gray-700">Email:</span> <span class="text-gray-600"><?= htmlspecialchars($admin['email']) ?></span>
                        </div>
                        <div class="mb-2 text-sm">
                            <span class="font-medium text-gray-700">Phone:</span> <span class="text-gray-600"><?= htmlspecialchars($admin['phone'] ?? 'Not set') ?></span>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="/Coops_Bichi/public/admin/profile" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user mr-2"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Session Management -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-history mr-2 text-primary-600"></i> Session Management</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Session</label>
                        <div class="mb-2 text-sm">
                            <span class="font-medium text-gray-700">Last activity:</span> <span class="text-gray-600"><?= date('F j, Y, g:i a') ?></span>
                        </div>
                        <div class="mb-2 text-sm">
                            <span class="font-medium text-gray-700">IP address:</span> <span class="text-gray-600"><?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?></span>
                        </div>
                        <div class="mb-2 text-sm">
                            <span class="font-medium text-gray-700">Browser:</span> <span class="text-gray-600"><?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']) ?></span>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="/Coops_Bichi/public/logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 