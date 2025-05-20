<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Savings Management</h1>
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
                <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Savings</p>
                    <p class="text-xl font-bold text-gray-700"><?= number_format($totalSavings, 2) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
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
        <form method="GET" action="/Coops_Bichi/public/admin/savings" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <a href="/Coops_Bichi/public/admin/savings" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Quick Links -->
    <div class="flex mb-6 space-x-2">
        <a href="/Coops_Bichi/public/admin/savings/contributions" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-history mr-2"></i> View Contributions
        </a>
        <a href="/Coops_Bichi/public/admin/savings/withdrawals" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-money-check-alt mr-2"></i> Manage Withdrawals
        </a>
    </div>
    
    <!-- Savings List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if (empty($members)): ?>
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-piggy-bank text-5xl mb-4 opacity-30"></i>
                <p class="text-lg">No savings records found</p>
                <p class="text-sm mt-2">Add new members or upload savings data</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coop Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Deduction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cumulative Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Deduction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($member['coop_no'] ?? '') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($member['name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($member['department_name'] ?? 'Unknown') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($member['monthly_deduction'] ?? 0, 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?= ($member['cumulative_amount'] ?? 0) > 0 ? 'text-green-600' : 'text-gray-500' ?>">
                                    <?= number_format($member['cumulative_amount'] ?? 0, 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $member['last_deduction_date'] ? date('M d, Y', strtotime($member['last_deduction_date'])) : 'Never' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="/Coops_Bichi/public/admin/members/view/<?= $member['id'] ?>" class="text-blue-600 hover:text-blue-900" title="View member details">
                                        <i class="fas fa-eye"></i>
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