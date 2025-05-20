<?php require_once APP_ROOT . '/views/layouts/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Main Content (now full-width) -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-semibold text-gray-800">Household Management</h1>
                    <div>
                        <a href="/Coops_Bichi/public/admin/household/applications" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-list mr-2"></i>
                            View Applications
                        </a>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600">Manage all household purchases and applications from members.</p>
            </div>

            <!-- Household Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Active Purchases -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active Purchases</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= isset($household_stats['total_active']) ? number_format($household_stats['total_active']) : 0 ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Value: <span class="font-medium text-gray-800">₦<?= isset($household_stats['active_value']) ? number_format($household_stats['active_value'], 2) : '0.00' ?></span></p>
                    </div>
                </div>

                <!-- Pending Applications -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Applications</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= isset($household_stats['pending_applications']) ? number_format($household_stats['pending_applications']) : 0 ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="/Coops_Bichi/public/admin/household/applications" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            View Applications <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Outstanding Balance -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Outstanding Balance</p>
                            <p class="text-2xl font-semibold text-gray-800">₦<?= isset($household_stats['outstanding_balance']) ? number_format($household_stats['outstanding_balance'], 2) : '0.00' ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Count: <span class="font-medium text-red-600"><?= isset($household_stats['outstanding_count']) ? number_format($household_stats['outstanding_count']) : 0 ?></span> purchases</p>
                    </div>
                </div>

                <!-- Monthly Purchases -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">This Month</p>
                            <p class="text-2xl font-semibold text-gray-800">₦<?= isset($household_stats['monthly_purchases']) ? number_format($household_stats['monthly_purchases'], 2) : '0.00' ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Count: <span class="font-medium text-gray-800"><?= isset($household_stats['monthly_count']) ? number_format($household_stats['monthly_count']) : 0 ?> purchases</span></p>
                    </div>
                </div>
            </div>

            <!-- Household Filter and Search -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" action="/Coops_Bichi/public/admin/household" id="searchForm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between space-y-3 md:space-y-0 md:space-x-4">
                        <div class="flex flex-col md:flex-row md:items-center flex-1 space-y-3 md:space-y-0 md:space-x-4">
                            <!-- Search -->
                            <div class="w-full md:w-72">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search by name, coop no, or description" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="w-full md:w-48">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                    <option value="">All Statuses</option>
                                    <option value="approved" <?= (isset($_GET['status']) && $_GET['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                                    <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="rejected" <?= (isset($_GET['status']) && $_GET['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-end space-x-3">
                            <button type="button" id="filterButton" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-filter mr-2"></i>
                                Show More Filters
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-search mr-2"></i>
                                Search
                            </button>
                            <a href="/Coops_Bichi/public/admin/household" class="text-sm text-gray-600 hover:text-gray-900">
                                <i class="fas fa-redo"></i>
                                <span class="hidden md:inline ml-1">Reset</span>
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Filters (hidden by default) -->
                    <div id="advancedFilters" class="hidden mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Date Range -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                <input type="date" id="date_from" name="date_from" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="<?= isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : '' ?>">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                <input type="date" id="date_to" name="date_to" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="<?= isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : '' ?>">
                            </div>
                            
                            <!-- Amount Range -->
                            <div>
                                <label for="amount_min" class="block text-sm font-medium text-gray-700">Min Amount (₦)</label>
                                <input type="number" id="amount_min" name="amount_min" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0.00" value="<?= isset($_GET['amount_min']) ? htmlspecialchars($_GET['amount_min']) : '' ?>">
                            </div>
                            <div>
                                <label for="amount_max" class="block text-sm font-medium text-gray-700">Max Amount (₦)</label>
                                <input type="number" id="amount_max" name="amount_max" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0.00" value="<?= isset($_GET['amount_max']) ? htmlspecialchars($_GET['amount_max']) : '' ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Household Purchases Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800">Household Purchases</h2>
                    <div class="mt-2">
                        <a href="/Coops_Bichi/public/admin/household/upload" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                            <i class="fas fa-upload mr-1"></i> Upload Payments
                        </a>
                    </div>
                </div>
                
                <?php if (empty($household_purchases)): ?>
                    <div class="p-6 text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-search text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No household purchases found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php if (isset($_GET['search']) || isset($_GET['status'])): ?>
                                No purchases match your current filters. Try adjusting your search criteria.
                            <?php else: ?>
                                There are no household purchases in the system.
                            <?php endif; ?>
                        </p>
                        <div class="mt-6">
                            <a href="/Coops_Bichi/public/admin/household/applications" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                View Pending Applications
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Member
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Balance
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        IP Figure
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($household_purchases as $purchase): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="/Coops_Bichi/public/admin/household/view/<?= $purchase['id'] ?>" class="hover:text-primary-600">
                                                    HP-<?= $purchase['id'] ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="<?= isset($purchase['member_photo']) && $purchase['member_photo'] ? htmlspecialchars($purchase['member_photo']) : '/assets/images/default-avatar.png' ?>" alt="Member Photo">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($purchase['name']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= htmlspecialchars($purchase['coop_no']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($purchase['description'] ?? 'N/A') ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₦<?= number_format($purchase['amount'], 2) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₦<?= number_format($purchase['balance'], 2) ?></div>
                                            <div class="mt-1 relative pt-1">
                                                <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                                    <?php $progress = max(0, min(100, 100 - (($purchase['balance'] / $purchase['amount']) * 100))); ?>
                                                    <div style="width: <?= $progress ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₦<?= number_format($purchase['ip_figure'], 2) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($purchase['status'] === 'approved'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            <?php elseif ($purchase['status'] === 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            <?php elseif ($purchase['status'] === 'rejected'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?= ucfirst(htmlspecialchars($purchase['status'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/Coops_Bichi/public/admin/household/view/<?= $purchase['id'] ?>" class="text-primary-600 hover:text-primary-900" title="View Purchase Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['last_page'] > 1): ?>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a href="?page=<?= max(1, $pagination['current_page'] - 1) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                                <a href="?page=<?= min($pagination['last_page'], $pagination['current_page'] + 1) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium"><?= ($pagination['current_page'] - 1) * $pagination['per_page'] + 1 ?></span> to <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <a href="?page=<?= max(1, $pagination['current_page'] - 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                        
                                        <?php 
                                        $start_page = max(1, $pagination['current_page'] - 2);
                                        $end_page = min($pagination['last_page'], $start_page + 4);
                                        if ($end_page - $start_page < 4 && $pagination['last_page'] > 4) {
                                            $start_page = max(1, $end_page - 4);
                                        }
                                        
                                        for ($i = $start_page; $i <= $end_page; $i++): 
                                        ?>
                                            <a href="?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $pagination['current_page'] ? 'text-primary-600 bg-primary-50 z-10' : 'text-gray-500 hover:bg-gray-50' ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <a href="?page=<?= min($pagination['last_page'], $pagination['current_page'] + 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Advanced Filters Toggle
    const filterButton = document.getElementById('filterButton');
    const advancedFilters = document.getElementById('advancedFilters');
    
    // Show/hide advanced filters when filter button is clicked
    filterButton.addEventListener('click', function() {
        if (advancedFilters.classList.contains('hidden')) {
            advancedFilters.classList.remove('hidden');
            filterButton.innerHTML = '<i class="fas fa-filter mr-2"></i> Hide Filters';
        } else {
            advancedFilters.classList.add('hidden');
            filterButton.innerHTML = '<i class="fas fa-filter mr-2"></i> Show More Filters';
        }
    });
    
    // Show advanced filters if any of their inputs have values
    const advancedInputs = ['date_from', 'date_to', 'amount_min', 'amount_max'];
    
    let hasAdvancedFilter = false;
    advancedInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input && input.value) {
            hasAdvancedFilter = true;
        }
    });
    
    if (hasAdvancedFilter) {
        advancedFilters.classList.remove('hidden');
        filterButton.innerHTML = '<i class="fas fa-filter mr-2"></i> Hide Filters';
    }
});
</script>