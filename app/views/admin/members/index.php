 
        <h1 class="text-2xl font-bold text-gray-800">Members Management</h1>
        <div class="flex space-x-2">
            <a href="/Coops_Bichi/public/admin/dashboard" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 <?= $_SESSION['flash_type'] === 'success' ? 'bg-green-100 border border-green-200 text-green-700' : 'bg-red-100 border border-red-200 text-red-700' ?> px-4 py-3 rounded relative">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    
    <!-- Search and Filter Form -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="/Coops_Bichi/public/admin/members" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Name, Email, ID" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            </div>
            <div class="col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="active" <?= isset($_GET['status']) && $_GET['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="pending" <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="locked" <?= isset($_GET['status']) && $_GET['status'] === 'locked' ? 'selected' : '' ?>>Locked</option>
                </select>
            </div>
            <div class="col-span-1">
                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department" id="department" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Departments</option>
                    <?php 
                    $departments = \App\Core\Database::fetchAll("SELECT * FROM departments ORDER BY name");
                    foreach($departments as $dept): 
                    ?>
                    <option value="<?= $dept['id'] ?>" <?= isset($_GET['department']) && $_GET['department'] == $dept['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dept['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-span-1 flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="/Coops_Bichi/public/admin/members" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Members List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if (empty($members)): ?>
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-users text-5xl mb-4 opacity-30"></i>
                <p class="text-lg">No members found</p>
                <p class="text-sm mt-2">Try a different search filter</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coop Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($members as $member): ?>
                            <?php 
                                $departmentName = \App\Core\Database::fetchOne("SELECT name FROM departments WHERE id = ?", [$member['department_id']]);
                                $statusClass = isset($member['status']) && $member['status'] === 'active' ? 'bg-green-100 text-green-800' : (isset($member['status']) && $member['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($member['coop_no'] ?? '') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($member['name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($member['email']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($departmentName['name'] ?? 'Unknown') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                        <?= ucfirst(htmlspecialchars($member['status'] ?? 'Unknown')) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M d, Y', strtotime($member['created_at'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="/Coops_Bichi/public/admin/members/view/<?= $member['id'] ?>" class="text-blue-600 hover:text-blue-900" title="View details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/Coops_Bichi/public/admin/members/edit/<?= $member['id'] ?>" class="text-indigo-600 hover:text-indigo-900" title="Edit member">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if (isset($member['status']) && $member['status'] === 'active'): ?>
                                        <a href="/Coops_Bichi/public/admin/members/lock/<?= $member['id'] ?>" class="text-yellow-600 hover:text-yellow-900" title="Lock account" onclick="return confirm('Are you sure you want to lock this member account?')">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    <?php elseif (isset($member['status']) && $member['status'] === 'locked'): ?>
                                        <a href="/Coops_Bichi/public/admin/members/unlock/<?= $member['id'] ?>" class="text-green-600 hover:text-green-900" title="Unlock account" onclick="return confirm('Are you sure you want to unlock this member account?')">
                                            <i class="fas fa-unlock"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript functionality for the members page here
    });
</script> <!-- Admin Members Index -->
 
