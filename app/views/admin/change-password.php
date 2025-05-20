<?php 
// Set current page for active menu highlighting
$current_page = 'change_password';
// Set page title
$page_title = 'Change Password';
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Change Password</h1>
            <p class="text-gray-600 text-sm mt-1">Update your account password</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="/Coops_Bichi/public/admin/profile" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Profile
            </a>
        </div>
    </div>

    <!-- Alerts will be dynamically inserted by the system -->

    <div class="max-w-2xl mx-auto">
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
        
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Password Security Tips</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Use a combination of letters, numbers, and special characters</li>
                            <li>Avoid using personal information or common words</li>
                            <li>Use a different password for each of your accounts</li>
                            <li>Change your passwords periodically</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 