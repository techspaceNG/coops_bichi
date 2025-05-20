<!-- Admin Member View -->
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="/Coops_Bichi/public/admin/members" class="text-blue-600 hover:text-blue-800 mr-2">
            <i class="fas fa-arrow-left"></i> Back to Members
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Member Details</h1>
    </div>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 <?= $_SESSION['flash_type'] === 'success' ? 'bg-green-100 border border-green-200 text-green-700' : 'bg-red-100 border border-red-200 text-red-700' ?> px-4 py-3 rounded relative">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    
    <!-- Member Information -->
    <?php if (isset($member)): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">Basic Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-y-4">
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Coop Number</p>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($member['coop_no'] ?? '--') ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= isset($member['status']) && $member['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                    (isset($member['status']) && $member['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                <?= ucfirst(htmlspecialchars($member['status'] ?? 'Unknown')) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($member['name']) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($member['email']) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Phone</p>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($member['phone'] ?? '--') ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Department</p>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($member['department_name'] ?? '--') ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Joined Date</p>
                        <p class="mt-1 text-sm text-gray-900"><?= isset($member['created_at']) ? date('M d, Y', strtotime($member['created_at'])) : '--' ?></p>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3">
                    <a href="/Coops_Bichi/public/admin/members/edit/<?= $member['id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                        <i class="fas fa-edit mr-2"></i> Edit Member
                    </a>
                    <?php if ($member['status'] === 'active'): ?>
                        <a href="/Coops_Bichi/public/admin/members/lock/<?= $member['id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none" onclick="return confirm('Are you sure you want to lock this member account?')">
                            <i class="fas fa-lock mr-2"></i> Lock Account
                        </a>
                    <?php elseif ($member['status'] === 'locked'): ?>
                        <a href="/Coops_Bichi/public/admin/members/unlock/<?= $member['id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none" onclick="return confirm('Are you sure you want to unlock this member account?')">
                            <i class="fas fa-unlock mr-2"></i> Unlock Account
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Savings Information -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">Savings Information</h2>
            </div>
            <div class="p-6">
                <?php if (isset($savings) && $savings): ?>
                <div class="grid grid-cols-2 gap-y-4">
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Monthly Contribution</p>
                        <p class="mt-1 text-sm text-gray-900">₦<?= number_format($savings['monthly_deduction'] ?? 0, 2) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Current Balance</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">₦<?= number_format($savings['cumulative_amount'] ?? 0, 2) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Total Deposits</p>
                        <p class="mt-1 text-sm text-gray-900">₦<?= number_format($savings['total_deposits'] ?? 0, 2) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Total Withdrawals</p>
                        <p class="mt-1 text-sm text-gray-900">₦<?= number_format($savings['total_withdrawals'] ?? 0, 2) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Interest Earned</p>
                        <p class="mt-1 text-sm text-gray-900">₦<?= number_format($savings['interest_amount'] ?? 0, 2) ?></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                        <p class="mt-1 text-sm text-gray-900"><?= isset($savings['updated_at']) ? date('M d, Y', strtotime($savings['updated_at'])) : '--' ?></p>
                    </div>
                </div>
                <?php else: ?>
                <p class="text-sm text-gray-500">No savings information available for this member.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Activity Overview -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">Activity Overview</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Active Loans</h3>
                            <p class="text-sm text-gray-500">Currently active loans</p>
                        </div>
                        <span class="text-lg font-semibold text-gray-900"><?= count(array_filter($loans ?? [], function($loan) { return isset($loan['status']) && $loan['status'] === 'approved' && ($loan['balance'] ?? 0) > 0; })) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Active Purchases</h3>
                            <p class="text-sm text-gray-500">Currently active household purchases</p>
                        </div>
                        <span class="text-lg font-semibold text-gray-900"><?= count(array_filter($purchases ?? [], function($purchase) { return isset($purchase['status']) && $purchase['status'] === 'approved' && ($purchase['balance'] ?? 0) > 0; })) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Total Loan Amount</h3>
                            <p class="text-sm text-gray-500">Sum of all approved loans</p>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">₦<?= number_format(array_reduce($loans ?? [], function($carry, $loan) { return $carry + (isset($loan['status']) && $loan['status'] === 'approved' ? ($loan['amount'] ?? 0) : 0); }, 0), 2) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Total Purchase Amount</h3>
                            <p class="text-sm text-gray-500">Sum of all approved household purchases</p>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">₦<?= number_format(array_reduce($purchases ?? [], function($carry, $purchase) { return $carry + (isset($purchase['status']) && $purchase['status'] === 'approved' ? ($purchase['amount'] ?? 0) : 0); }, 0), 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loans and Purchases Tabs -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button class="tab-button active" data-target="loans-tab">
                        Loans (<?= count($loans ?? []) ?>)
                    </button>
                    <button class="tab-button" data-target="purchases-tab">
                        Household Purchases (<?= count($purchases ?? []) ?>)
                    </button>
                </nav>
            </div>
            
            <!-- Loans Tab -->
            <div id="loans-tab" class="tab-content block">
                <?php if (empty($loans)): ?>
                    <div class="p-6 text-center text-gray-500">
                        <p>No loan records found for this member.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($loans as $loan): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($loan['loan_id'] ?? $loan['id']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₦<?= number_format($loan['amount'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= isset($loan['status']) && $loan['status'] === 'approved' ? 'bg-green-100 text-green-800' : (isset($loan['status']) && $loan['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst(htmlspecialchars($loan['status'] ?? 'Unknown')) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= isset($loan['created_at']) ? date('M d, Y', strtotime($loan['created_at'])) : '--' ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₦<?= number_format($loan['balance'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/Coops_Bichi/public/admin/loans/view/<?= $loan['id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Purchases Tab -->
            <div id="purchases-tab" class="tab-content hidden">
                <?php if (empty($purchases)): ?>
                    <div class="p-6 text-center text-gray-500">
                        <p>No household purchase records found for this member.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($purchases as $purchase): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($purchase['purchase_id'] ?? $purchase['id']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($purchase['product_name'] ?? 'Unknown Product') ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₦<?= number_format($purchase['amount'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= isset($purchase['status']) && $purchase['status'] === 'approved' ? 'bg-green-100 text-green-800' : (isset($purchase['status']) && $purchase['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst(htmlspecialchars($purchase['status'] ?? 'Unknown')) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= isset($purchase['created_at']) ? date('M d, Y', strtotime($purchase['created_at'])) : '--' ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₦<?= number_format($purchase['balance'] ?? 0, 2) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/Coops_Bichi/public/admin/household/view/<?= $purchase['id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-red-500 mb-4">
            <i class="fas fa-exclamation-circle text-5xl"></i>
        </div>
        <h2 class="text-xl font-medium text-gray-900 mb-2">Member Not Found</h2>
        <p class="text-gray-500 mb-4">The member you are looking for does not exist or has been removed.</p>
        <a href="/Coops_Bichi/public/admin/members" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
            <i class="fas fa-arrow-left mr-2"></i> Return to Members List
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
.tab-button {
    @apply w-1/2 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300;
}
.tab-button.active {
    @apply border-primary-500 text-primary-600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.getAttribute('data-target');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show target tab content
            document.getElementById(target).classList.remove('hidden');
            
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        });
    });
});
</script> 