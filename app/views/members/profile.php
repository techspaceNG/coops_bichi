<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Member Profile</h1>
                <p class="text-primary-100">Manage your personal information and account settings</p>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-start">
                    <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 mb-4 md:mb-0 md:mr-6">
                        <span class="text-4xl font-bold"><?= strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) ?></span>
                    </div>
                    
                    <div>
                        <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($member->getFullName()) ?></h2>
                        <p class="text-gray-600">Cooperative No: <span class="font-medium"><?= htmlspecialchars($member->coop_no) ?></span></p>
                        <p class="text-gray-600">TI Number: <span class="font-medium"><?= htmlspecialchars($member->ti_number ?? 'Not Available') ?></span></p>
                        <p class="text-gray-600">Department: 
                            <span class="font-medium">
                                <?php if (isset($member->department) && $member->department): ?>
                                    <?= htmlspecialchars($member->department) ?>
                                <?php else: ?>
                                    Not Available
                                    <?php if (isset($member->department_id) && $member->department_id): ?>
                                        <!-- Hidden for developers - Dept ID: <?= $member->department_id ?> -->
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </p>
                        <p class="text-gray-600">Member Since: <span class="font-medium"><?= isset($member->registration_date) && $member->registration_date ? date('F d, Y', strtotime($member->registration_date)) : date('F d, Y', strtotime($member->created_at)) ?></span></p>
                        <p class="text-gray-600 mt-2">
                            Status: 
                            <span class="px-2 py-1 text-xs rounded-full <?= $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= ucfirst(htmlspecialchars($member->status)) ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Edit Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Personal Information</h2>
                <p class="text-gray-600 text-sm">Update your personal details</p>
            </div>
            
            <div class="p-6">
                <?php if (isset($errors['general'])): ?>
                    <div class="bg-red-50 text-red-800 p-4 rounded-md mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p><?= htmlspecialchars($errors['general']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form action="/Coops_Bichi/public/member/profile/update" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-gray-700 font-medium mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['first_name']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->first_name) ?>">
                            <?php if (isset($errors['first_name'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['first_name']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-gray-700 font-medium mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['last_name']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->last_name) ?>">
                            <?php if (isset($errors['last_name'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['last_name']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                            <input type="email" id="email" name="email" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->email) ?>">
                            <?php if (isset($errors['email'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="ti_number" class="block text-gray-700 font-medium mb-2">TI Number</label>
                            <input type="text" id="ti_number" name="ti_number" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['ti_number']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->ti_number ?? '') ?>">
                            <?php if (isset($errors['ti_number'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['ti_number']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['phone']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->phone) ?>">
                            <?php if (isset($errors['phone'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['phone']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="department" class="block text-gray-700 font-medium mb-2">Department</label>
                            <select id="department" name="department" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['department']) ? 'border-red-500' : 'border-gray-300' ?>">
                                <option value="">Select Department</option>
                                <?php
                                // Fetch departments from database
                                $departments = \App\Models\Department::getAll();
                                foreach ($departments as $dept):
                                ?>
                                    <option value="<?= htmlspecialchars($dept['name']) ?>" <?= isset($member->department) && $member->department === $dept['name'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['department'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['department']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Bank Information Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Bank Information</h2>
                <p class="text-gray-600 text-sm">Update your bank account details</p>
            </div>
            
            <div class="p-6">
                <form action="/Coops_Bichi/public/member/profile/update-bank" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="bank_name" class="block text-gray-700 font-medium mb-2">Bank Name</label>
                            <select id="bank_name" name="bank_name" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['bank_name']) ? 'border-red-500' : 'border-gray-300' ?>">
                                <option value="" <?= empty($member->bank_name) ? 'selected' : '' ?>>Select Bank</option>
                                <option value="Access Bank" <?= $member->bank_name === 'Access Bank' ? 'selected' : '' ?>>Access Bank</option>
                                <option value="Citibank" <?= $member->bank_name === 'Citibank' ? 'selected' : '' ?>>Citibank</option>
                                <option value="Diamond Bank" <?= $member->bank_name === 'Diamond Bank' ? 'selected' : '' ?>>Diamond Bank</option>
                                <option value="Ecobank" <?= $member->bank_name === 'Ecobank' ? 'selected' : '' ?>>Ecobank</option>
                                <option value="FCMB" <?= $member->bank_name === 'FCMB' ? 'selected' : '' ?>>FCMB</option>
                                <option value="Fidelity Bank" <?= $member->bank_name === 'Fidelity Bank' ? 'selected' : '' ?>>Fidelity Bank</option>
                                <option value="First Bank" <?= $member->bank_name === 'First Bank' ? 'selected' : '' ?>>First Bank</option>
                                <option value="GTBank" <?= $member->bank_name === 'GTBank' ? 'selected' : '' ?>>GTBank</option>
                                <option value="Heritage Bank" <?= $member->bank_name === 'Heritage Bank' ? 'selected' : '' ?>>Heritage Bank</option>
                                <option value="Jaiz Bank" <?= $member->bank_name === 'Jaiz Bank' ? 'selected' : '' ?>>Jaiz Bank</option>
                                <option value="Keystone Bank" <?= $member->bank_name === 'Keystone Bank' ? 'selected' : '' ?>>Keystone Bank</option>
                                <option value="Polaris Bank" <?= $member->bank_name === 'Polaris Bank' ? 'selected' : '' ?>>Polaris Bank</option>
                                <option value="Providus Bank" <?= $member->bank_name === 'Providus Bank' ? 'selected' : '' ?>>Providus Bank</option>
                                <option value="Stanbic IBTC" <?= $member->bank_name === 'Stanbic IBTC' ? 'selected' : '' ?>>Stanbic IBTC</option>
                                <option value="Standard Chartered" <?= $member->bank_name === 'Standard Chartered' ? 'selected' : '' ?>>Standard Chartered</option>
                                <option value="Sterling Bank" <?= $member->bank_name === 'Sterling Bank' ? 'selected' : '' ?>>Sterling Bank</option>
                                <option value="SunTrust Bank" <?= $member->bank_name === 'SunTrust Bank' ? 'selected' : '' ?>>SunTrust Bank</option>
                                <option value="Union Bank" <?= $member->bank_name === 'Union Bank' ? 'selected' : '' ?>>Union Bank</option>
                                <option value="UBA" <?= $member->bank_name === 'UBA' ? 'selected' : '' ?>>UBA</option>
                                <option value="Unity Bank" <?= $member->bank_name === 'Unity Bank' ? 'selected' : '' ?>>Unity Bank</option>
                                <option value="Wema Bank" <?= $member->bank_name === 'Wema Bank' ? 'selected' : '' ?>>Wema Bank</option>
                                <option value="Zenith Bank" <?= $member->bank_name === 'Zenith Bank' ? 'selected' : '' ?>>Zenith Bank</option>
                            </select>
                            <?php if (isset($errors['bank_name'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['bank_name']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="account_number" class="block text-gray-700 font-medium mb-2">Account Number</label>
                            <input type="text" id="account_number" name="account_number" maxlength="10" pattern="[0-9]{10}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['account_number']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->account_number ?? '') ?>">
                            <?php if (isset($errors['account_number'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['account_number']) ?></p>
                            <?php endif; ?>
                            <p class="text-gray-500 text-sm mt-1">Enter your 10-digit account number</p>
                        </div>
                        
                        <div>
                            <label for="account_name" class="block text-gray-700 font-medium mb-2">Account Name</label>
                            <input type="text" id="account_name" name="account_name"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['account_name']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->account_name ?? '') ?>">
                            <?php if (isset($errors['account_name'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['account_name']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="bvn" class="block text-gray-700 font-medium mb-2">BVN (Bank Verification Number)</label>
                            <input type="text" id="bvn" name="bvn" maxlength="11" pattern="[0-9]{11}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['bvn']) ? 'border-red-500' : 'border-gray-300' ?>"
                                value="<?= htmlspecialchars($member->bvn ?? '') ?>">
                            <?php if (isset($errors['bvn'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['bvn']) ?></p>
                            <?php endif; ?>
                            <p class="text-gray-500 text-sm mt-1">Optional - For enhanced security</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Update Bank Information
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Password Change Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Change Password</h2>
                <p class="text-gray-600 text-sm">Update your account password</p>
            </div>
            
            <div class="p-6">
                <form action="/Coops_Bichi/public/member/change-password" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password</label>
                            <input type="password" id="current_password" name="current_password" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['current_password']) ? 'border-red-500' : 'border-gray-300' ?>">
                            <?php if (isset($errors['current_password'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['current_password']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-gray-700 font-medium mb-2">New Password</label>
                            <input type="password" id="new_password" name="new_password" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['new_password']) ? 'border-red-500' : 'border-gray-300' ?>">
                            <?php if (isset($errors['new_password'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['new_password']) ?></p>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm mt-1">Minimum 8 characters, including uppercase, lowercase, and numbers</p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['confirm_password']) ? 'border-red-500' : 'border-gray-300' ?>">
                            <?php if (isset($errors['confirm_password'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['confirm_password']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 