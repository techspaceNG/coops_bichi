<!-- Admin Edit Member -->
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="/Coops_Bichi/public/admin/members" class="text-blue-600 hover:text-blue-800 mr-2">
            <i class="fas fa-arrow-left"></i> Back to Members
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Member</h1>
    </div>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 <?= $_SESSION['flash_type'] === 'success' ? 'bg-green-100 border border-green-200 text-green-700' : 'bg-red-100 border border-red-200 text-red-700' ?> px-4 py-3 rounded relative">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    
    <!-- Edit Member Form -->
    <?php if (isset($member)): ?>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-medium text-gray-800">Member Information</h2>
            <p class="text-sm text-gray-500 mt-1">Update member details</p>
        </div>
        <div class="p-6">
            <form method="POST" action="/Coops_Bichi/public/admin/members/update/<?= $member['id'] ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : $member['name'] ?>" required>
                        <?php if (isset($_SESSION['form_errors']['name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['form_errors']['name'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Employee ID -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Coop Number</label>
                        <input type="text" name="employee_id" id="employee_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" value="<?= isset($_SESSION['form_data']['employee_id']) ? $_SESSION['form_data']['employee_id'] : $member['employee_id'] ?>" required>
                        <?php if (isset($_SESSION['form_errors']['employee_id'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['form_errors']['employee_id'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Email -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" value="<?= isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : $member['email'] ?>" required>
                        <?php if (isset($_SESSION['form_errors']['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['form_errors']['email'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Phone -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : $member['phone'] ?>" required>
                        <?php if (isset($_SESSION['form_errors']['phone'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['form_errors']['phone'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Department -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department_id" id="department_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" required>
                            <option value="">Select Department</option>
                            <?php if (isset($departments) && is_array($departments)): ?>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= $department['id'] ?>" <?= ((isset($_SESSION['form_data']['department_id']) && $_SESSION['form_data']['department_id'] == $department['id']) || (!isset($_SESSION['form_data']['department_id']) && $member['department_id'] == $department['id'])) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($department['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($_SESSION['form_errors']['department_id'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['form_errors']['department_id'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Status -->
                    <div class="col-span-2 sm:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" required>
                            <option value="active" <?= ((isset($_SESSION['form_data']['status']) && $_SESSION['form_data']['status'] == 'active') || (!isset($_SESSION['form_data']['status']) && $member['status'] == 'active')) ? 'selected' : '' ?>>Active</option>
                            <option value="pending" <?= ((isset($_SESSION['form_data']['status']) && $_SESSION['form_data']['status'] == 'pending') || (!isset($_SESSION['form_data']['status']) && $member['status'] == 'pending')) ? 'selected' : '' ?>>Pending</option>
                            <option value="locked" <?= ((isset($_SESSION['form_data']['status']) && $_SESSION['form_data']['status'] == 'locked') || (!isset($_SESSION['form_data']['status']) && $member['status'] == 'locked')) ? 'selected' : '' ?>>Locked</option>
                        </select>
                        <?php if (isset($_SESSION['form_errors']['status'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $_SESSION['form_errors']['status'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="mt-8 flex justify-end">
                    <a href="/Coops_Bichi/public/admin/members" class="bg-gray-100 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                        <i class="fas fa-save mr-1"></i> Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-red-500 mb-4">
            <i class="fas fa-exclamation-circle text-5xl"></i>
        </div>
        <h2 class="text-xl font-medium text-gray-900 mb-2">Member Not Found</h2>
        <p class="text-gray-500 mb-4">The member you are trying to edit does not exist or has been removed.</p>
        <a href="/Coops_Bichi/public/admin/members" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
            <i class="fas fa-arrow-left mr-2"></i> Return to Members List
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Any JavaScript for the form can go here
});
</script> 