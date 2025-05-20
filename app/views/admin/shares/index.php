<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Shares Management</h1>
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
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Shares Value</p>
                    <p class="text-xl font-bold text-gray-700"><?= number_format($totalShares, 2) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Members</p>
                    <p class="text-xl font-bold text-gray-700"><?= count($members) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Last Updated</p>
                    <p class="text-xl font-bold text-gray-700"><?= date('M d, Y') ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter Form -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="/Coops_Bichi/public/admin/shares" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Name, Email, ID" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
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
            <div class="col-span-1">
                <label for="balance" class="block text-sm font-medium text-gray-700 mb-1">Balance</label>
                <select name="balance" id="balance" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Balances</option>
                    <option value="high" <?= isset($_GET['balance']) && $_GET['balance'] === 'high' ? 'selected' : '' ?>>Highest First</option>
                    <option value="low" <?= isset($_GET['balance']) && $_GET['balance'] === 'low' ? 'selected' : '' ?>>Lowest First</option>
                </select>
            </div>
            <div class="col-span-1 flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="/Coops_Bichi/public/admin/shares" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Shares List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if (empty($members)): ?>
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-chart-pie text-5xl mb-4 opacity-30"></i>
                <p class="text-lg">No shares records found</p>
                <p class="text-sm mt-2">Add new members or upload shares data</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coop Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Share Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Transaction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($members as $member): ?>
                            <?php 
                                // Determine if data is from shares table or members table
                                $hasShares = !is_null($member['units']) && !is_null($member['unit_value']);
                                $shareUnits = $hasShares ? $member['units'] : ($member['shares_balance'] > 0 ? floor($member['shares_balance'] / 2000) : 0); // Assuming default unit value is 2000
                                $shareUnitValue = $hasShares ? $member['unit_value'] : 2000; // Default unit value
                                $totalValue = $hasShares ? $member['total_value'] : $member['shares_balance'];
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($member['coop_no'] ?? '') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($member['name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($member['department_name'] ?? 'Unknown') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($shareUnits) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($shareUnitValue, 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?= $totalValue > 0 ? 'text-green-600' : 'text-gray-500' ?>">
                                    <?= number_format($totalValue, 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $member['purchase_date'] ? date('M d, Y', strtotime($member['purchase_date'])) : ($member['shares_balance'] > 0 ? 'From Balance' : 'Never') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="/Coops_Bichi/public/admin/members/view/<?= $member['id'] ?>" class="text-blue-600 hover:text-blue-900" title="View member details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" onclick="showHistoryModal(<?= $member['id'] ?>, '<?= htmlspecialchars($member['name']) ?>')" class="text-indigo-600 hover:text-indigo-900" title="View history">
                                        <i class="fas fa-history"></i>
                                    </a>
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
    function showHistoryModal(memberId, memberName) {
        // This would be implemented to show transaction history
        alert('View history for ' + memberName + ' (ID: ' + memberId + ')');
        // In a real implementation, you would load transaction history via AJAX
    }
</script> 