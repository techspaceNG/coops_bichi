<?php 
// Set current page for active menu highlighting
$current_page = 'profile';
// Set page title
$page_title = 'Admin Profile';
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-600 text-sm mt-1">Manage your account information</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="/Coops_Bichi/public/admin/dashboard" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Alerts will be dynamically inserted by the system -->

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-user mr-2 text-primary-600"></i> Profile Information</h3>
                </div>
                <div class="p-6">
                    <form action="/Coops_Bichi/public/admin/profile/update" method="POST">
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" class="bg-gray-100 border border-gray-300 text-gray-700 rounded-md w-full px-3 py-2" 
                                id="username" value="<?= htmlspecialchars($admin['username'] ?? '') ?>" disabled>
                            <p class="mt-1 text-xs text-gray-500">Your username cannot be changed.</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" class="border border-gray-300 rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500" 
                                id="name" name="name" required value="<?= htmlspecialchars($admin['name'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" class="border border-gray-300 rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500" 
                                id="email" name="email" required value="<?= htmlspecialchars($admin['email'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" class="border border-gray-300 rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500" 
                                id="phone" name="phone" value="<?= htmlspecialchars($admin['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-save mr-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div>
            <!-- Profile Picture -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-camera mr-2 text-primary-600"></i> Profile Picture</h3>
                </div>
                <div class="p-6 flex flex-col items-center">
                    <img src="<?= !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : '/Coops_Bichi/public/profile.png' ?>" 
                         alt="Profile" class="h-32 w-32 rounded-full mb-4 object-cover border-4 border-gray-200">
                    
                    <p class="text-xs text-gray-500 mb-4">JPG or PNG no larger than 2 MB</p>
                    
                    <form action="/Coops_Bichi/public/admin/profile/upload-image" method="POST" enctype="multipart/form-data" class="w-full">
                        <div class="mb-4">
                            <input class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" 
                                type="file" id="profile_image" name="profile_image" accept="image/jpeg, image/png">
                        </div>
                        <button type="submit" class="w-full bg-primary-100 hover:bg-primary-200 text-primary-700 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-upload mr-2"></i> Upload New Picture
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-info-circle mr-2 text-primary-600"></i> Account Information</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="font-medium"><?= ucfirst(htmlspecialchars($admin['role'] ?? 'Admin')) ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Account Created</p>
                        <p><?= isset($admin['created_at']) ? date('F j, Y', strtotime($admin['created_at'])) : 'N/A' ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Last Login</p>
                        <p><?= isset($admin['last_login']) ? date('F j, Y, g:i a', strtotime($admin['last_login'])) : 'Never' ?></p>
                    </div>
                    
                    <div class="mt-6">
                        <a href="/Coops_Bichi/public/admin/change-password" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 