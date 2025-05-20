<?php require_once APP_ROOT . '/views/layouts/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Main Content (now full-width) -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-semibold text-gray-800">Loan Management</h1>
                    <div>
                        <a href="/Coops_Bichi/public/admin/loans/reports" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Loan Reports
                        </a>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-600">Manage all loan applications, active loans, and repayments from members.</p>
            </div>

            <!-- Loans Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Active Loans -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-hand-holding-usd fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active Loans</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= number_format($loan_stats['total_active']) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Value: <span class="font-medium text-gray-800">₦<?= number_format($loan_stats['active_value'], 2) ?></span></p>
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
                            <p class="text-2xl font-semibold text-gray-800"><?= number_format($loan_stats['pending_applications']) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="/Coops_Bichi/public/admin/loans/applications" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            View Applications <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Overdue Payments -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Overdue Payments</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= number_format($loan_stats['overdue_count']) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Overdue: <span class="font-medium text-red-600">₦<?= number_format($loan_stats['overdue_amount'], 2) ?></span></p>
                    </div>
                </div>

                <!-- Disbursed This Month -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Disbursed (This Month)</p>
                            <p class="text-2xl font-semibold text-gray-800">₦<?= number_format($loan_stats['monthly_disbursed'], 2) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Count: <span class="font-medium text-gray-800"><?= number_format($loan_stats['monthly_count']) ?> loans</span></p>
                    </div>
                </div>
            </div>

            <!-- Loans Filter and Search -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" action="/Coops_Bichi/public/admin/loans" id="searchForm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between space-y-3 md:space-y-0 md:space-x-4">
                        <div class="flex flex-col md:flex-row md:items-center flex-1 space-y-3 md:space-y-0 md:space-x-4">
                            <!-- Search -->
                            <div class="w-full md:w-72">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search by name, email, ID, or phone" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="w-full md:w-48">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                    <option value="">All Statuses</option>
                                    <option value="active" <?= (isset($_GET['status']) && $_GET['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                                    <option value="overdue" <?= (isset($_GET['status']) && $_GET['status'] === 'overdue') ? 'selected' : '' ?>>Overdue</option>
                                    <option value="defaulted" <?= (isset($_GET['status']) && $_GET['status'] === 'defaulted') ? 'selected' : '' ?>>Defaulted</option>
                                </select>
                            </div>

                            <!-- Loan Type Filter -->
                            <div class="w-full md:w-48">
                                <label for="loan_type" class="block text-sm font-medium text-gray-700 mb-1">Loan Type</label>
                                <select id="loan_type" name="loan_type" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                    <option value="">All Loan Types</option>
                                    <option value="standard" <?= (isset($_GET['loan_type']) && $_GET['loan_type'] === 'standard') ? 'selected' : '' ?>>Standard Loan</option>
                                    <option value="emergency" <?= (isset($_GET['loan_type']) && $_GET['loan_type'] === 'emergency') ? 'selected' : '' ?>>Emergency Loan</option>
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
                            <a href="/Coops_Bichi/public/admin/loans" class="text-sm text-gray-600 hover:text-gray-900">
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

            <!-- Loans Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800">Active Loans</h2>
                </div>
                
                <?php if (empty($loans)): ?>
                    <div class="p-6 text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-search text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No loans found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php if (isset($_GET['search']) || isset($_GET['status']) || isset($_GET['loan_type'])): ?>
                                No loans match your current filters. Try adjusting your search criteria.
                            <?php else: ?>
                                There are no active loans in the system.
                            <?php endif; ?>
                        </p>
                        <div class="mt-6">
                            <a href="/Coops_Bichi/public/admin/loans/applications" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
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
                                        Loan ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Member
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Balance
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Issued Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        End Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($loans as $loan): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="/Coops_Bichi/public/admin/loans/view/<?= htmlspecialchars($loan['id']) ?>" class="hover:text-primary-600">
                                                        LN-<?= htmlspecialchars($loan['id']) ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="<?= isset($loan['member_photo']) && $loan['member_photo'] ? htmlspecialchars($loan['member_photo']) : '/assets/images/default-avatar.png' ?>" alt="Member Photo">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="/Coops_Bichi/public/admin/members/view/<?= htmlspecialchars($loan['member_id']) ?>" class="hover:text-primary-600">
                                                            <?= htmlspecialchars($loan['member_name']) ?>
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= isset($loan['coop_no']) ? htmlspecialchars($loan['coop_no']) : '' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">₦<?= number_format($loan['loan_amount'], 2) ?></div>
                                            <div class="text-xs text-gray-500"><?= $loan['purpose'] ? htmlspecialchars(ucfirst($loan['purpose'])) : 'N/A' ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₦<?= number_format($loan['balance'], 2) ?></div>
                                            <div class="mt-1 relative pt-1">
                                                <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                                    <?php $progress = max(0, min(100, 100 - (($loan['balance'] / $loan['total_repayment']) * 100))); ?>
                                                    <div style="width: <?= $progress ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($loan['status'] === 'approved'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            <?php elseif ($loan['status'] === 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            <?php elseif ($loan['status'] === 'completed'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Completed
                                                </span>
                                            <?php elseif ($loan['status'] === 'declined'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Declined
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?= ucfirst(htmlspecialchars($loan['status'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($loan['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (isset($loan['end_date'])): ?>
                                                <div class="text-sm text-gray-900"><?= date('M d, Y', strtotime($loan['end_date'])) ?></div>
                                                <?php 
                                                $days_left = (strtotime($loan['end_date']) - time()) / (60 * 60 * 24);
                                                if ($days_left < 0): 
                                                ?>
                                                    <div class="text-xs text-red-600"><?= abs(round($days_left)) ?> days overdue</div>
                                                <?php elseif ($days_left <= 7): ?>
                                                    <div class="text-xs text-yellow-600"><?= round($days_left) ?> days left</div>
                                                <?php else: ?>
                                                    <div class="text-xs text-gray-500"><?= round($days_left) ?> days left</div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="text-sm text-gray-500">N/A</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/Coops_Bichi/public/admin/loans/view/<?= htmlspecialchars($loan['id']) ?>" class="text-primary-600 hover:text-primary-900" title="View Loan Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php 
                    // Default pagination variables if not set
                    $total_pages = $total_pages ?? 1;
                    $current_page = $current_page ?? 1;
                    $per_page = $per_page ?? 10;
                    $total_records = $total_records ?? count($loans);
                    $query_string = $query_string ?? '';
                    ?>
                    <?php if ($total_pages > 1): ?>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a href="?page=<?= max(1, $current_page - 1) ?><?= $query_string ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                                <a href="?page=<?= min($total_pages, $current_page + 1) ?><?= $query_string ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium"><?= ($current_page - 1) * $per_page + 1 ?></span> to <span class="font-medium"><?= min($current_page * $per_page, $total_records) ?></span> of <span class="font-medium"><?= $total_records ?></span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <a href="?page=<?= max(1, $current_page - 1) ?><?= $query_string ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                        
                                        <?php 
                                        $start_page = max(1, $current_page - 2);
                                        $end_page = min($total_pages, $start_page + 4);
                                        if ($end_page - $start_page < 4 && $total_pages > 4) {
                                            $start_page = max(1, $end_page - 4);
                                        }
                                        
                                        for ($i = $start_page; $i <= $end_page; $i++): 
                                        ?>
                                            <a href="?page=<?= $i ?><?= $query_string ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $current_page ? 'text-primary-600 bg-primary-50 z-10' : 'text-gray-500 hover:bg-gray-50' ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <a href="?page=<?= min($total_pages, $current_page + 1) ?><?= $query_string ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

<!-- Print Statement Modal -->
<div id="printModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-print text-primary-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Print Loan Statement
                        </h3>
                        <div class="mt-4">
                            <div class="space-y-4">
                                <p class="text-sm text-gray-500">
                                    Select options for the loan statement report:
                                </p>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date Range</label>
                                    <div class="mt-1 grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="start_date" class="sr-only">Start Date</label>
                                            <input type="date" id="start_date" name="start_date" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label for="end_date" class="sr-only">End Date</label>
                                            <input type="date" id="end_date" name="end_date" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="include_details" class="flex items-center">
                                        <input type="checkbox" id="include_details" name="include_details" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded" checked>
                                        <span class="ml-2 text-sm text-gray-700">Include payment details</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmPrint" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Print Statement
                </button>
                <button type="button" id="cancelPrint" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
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
    
    // Print Statement Logic
    let selectedLoanId = null;
    const printModal = document.getElementById('printModal');
    const confirmPrint = document.getElementById('confirmPrint');
    const cancelPrint = document.getElementById('cancelPrint');
    
    window.printStatement = function(event, loanId) {
        event.preventDefault();
        selectedLoanId = loanId;
        printModal.classList.remove('hidden');
    };
    
    if (cancelPrint) {
        cancelPrint.addEventListener('click', function() {
            printModal.classList.add('hidden');
        });
    }
    
    if (confirmPrint) {
        confirmPrint.addEventListener('click', function() {
            if (!selectedLoanId) return;
            
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const includeDetails = document.getElementById('include_details').checked;
            
            const queryParams = [];
            if (startDate) queryParams.push(`start_date=${encodeURIComponent(startDate)}`);
            if (endDate) queryParams.push(`end_date=${encodeURIComponent(endDate)}`);
            if (includeDetails) queryParams.push('include_details=1');
            
            const url = `/Coops_Bichi/public/admin/loans/print-statement/${selectedLoanId}${queryParams.length ? '?' + queryParams.join('&') : ''}`;
            window.open(url, '_blank');
            printModal.classList.add('hidden');
        });
    }
    
    // Close modal on outside click
    window.addEventListener('click', function(e) {
        if (e.target === printModal) {
            printModal.classList.add('hidden');
        }
    });
});
</script> 