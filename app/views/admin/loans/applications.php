<?php require_once APP_ROOT . '/views/layouts/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Main Content (Sidebar removed) -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-semibold text-gray-800">Loan Applications</h1>
                    <a href="/Coops_Bichi/public/admin/loans" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Loans
                    </a>
                </div>
                <p class="mt-2 text-sm text-gray-600">Review and process pending loan applications from members.</p>
            </div>

            <!-- Applications Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Pending -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Applications</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['pending']) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Value: <span class="font-medium text-gray-800">₦<?= number_format($stats['pending_value'], 2) ?></span></p>
                    </div>
                </div>

                <!-- Today's Applications -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-calendar-day fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Today's Applications</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['today']) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">
                            <?php if ($stats['today_change'] > 0): ?>
                                <span class="text-green-500"><i class="fas fa-arrow-up mr-1"></i><?= $stats['today_change'] ?>%</span>
                            <?php elseif ($stats['today_change'] < 0): ?>
                                <span class="text-red-500"><i class="fas fa-arrow-down mr-1"></i><?= abs($stats['today_change']) ?>%</span>
                            <?php else: ?>
                                <span class="text-gray-500">No change</span>
                            <?php endif; ?>
                            compared to yesterday
                        </p>
                    </div>
                </div>

                <!-- Approved This Week -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved This Week</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= number_format($stats['approved_week']) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Value: <span class="font-medium text-gray-800">₦<?= number_format($stats['approved_value'], 2) ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Applications Filter and Search -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between space-y-3 md:space-y-0 md:space-x-4">
                    <div class="flex flex-col md:flex-row md:items-center flex-1 space-y-3 md:space-y-0 md:space-x-4">
                        <!-- Search -->
                        <div class="w-full md:w-72">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search by name or ID" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full md:w-48">
                            <label for="status" class="sr-only">Status</label>
                            <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">All Applications</option>
                                <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Pending Review</option>
                                <option value="review" <?= (isset($_GET['status']) && $_GET['status'] === 'review') ? 'selected' : '' ?>>Under Review</option>
                                <option value="approved" <?= (isset($_GET['status']) && $_GET['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= (isset($_GET['status']) && $_GET['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>

                        <!-- Loan Type Filter -->
                        <div class="w-full md:w-48">
                            <label for="loan_type" class="sr-only">Loan Type</label>
                            <select id="loan_type" name="loan_type" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">All Loan Types</option>
                                <?php if (empty($loan_types)): ?>
                                    <option value="" disabled>No loan types defined</option>
                                <?php else: ?>
                                    <?php foreach ($loan_types as $type): ?>
                                        <option value="<?= htmlspecialchars($type['id']) ?>" <?= (isset($_GET['loan_type']) && $_GET['loan_type'] == $type['id']) ? 'selected' : '' ?>><?= htmlspecialchars($type['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" id="applyFilters" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-filter mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="/Coops_Bichi/public/admin/loans/applications" class="text-sm text-gray-600 hover:text-gray-900">
                            <i class="fas fa-redo"></i>
                            <span class="sr-only">Reset</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800">Loan Applications</h2>
                </div>
                
                <?php if (empty($applications)): ?>
                    <div class="p-6 text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-inbox text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No applications found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php if (isset($_GET['search']) || isset($_GET['status']) || isset($_GET['loan_type'])): ?>
                                No loan applications match your current filters.
                            <?php else: ?>
                                There are no pending loan applications at this time.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Application ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Member
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Loan Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Applied Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Eligibility
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($applications as $app): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="/Coops_Bichi/public/admin/loans/application/<?= htmlspecialchars($app['id'] ?? '') ?>" class="hover:text-primary-600">
                                                        <?= htmlspecialchars($app['reference_id'] ?? 'APP-' . $app['id']) ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="<?= isset($app['member_photo']) && $app['member_photo'] ? htmlspecialchars($app['member_photo']) : '/Coops_Bichi/public/assets/images/default-avatar.png' ?>" alt="Member Photo">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="/Coops_Bichi/public/admin/members/view/<?= htmlspecialchars($app['member_id'] ?? '') ?>" class="hover:text-primary-600">
                                                            <?= htmlspecialchars($app['member_name'] ?? 'Unknown Member') ?>
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= htmlspecialchars($app['coop_no'] ?? $app['coop_number'] ?? 'N/A') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($app['loan_type_name'] ?? 'Standard Loan') ?></div>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($app['loan_tenure'] ?? $app['repayment_period'] ?? '12') ?> months</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₦<?= number_format($app['amount'] ?? $app['loan_amount'] ?? 0, 2) ?></div>
                                            <div class="text-xs text-gray-500">
                                                Monthly: ₦<?= number_format($app['estimated_monthly'] ?? ($app['loan_amount'] / 12) ?? 0, 2) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php $status = $app['status'] ?? 'pending'; ?>
                                            <?php if ($status === 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending Review
                                                </span>
                                            <?php elseif ($status === 'review'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Under Review
                                                </span>
                                            <?php elseif ($status === 'approved'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            <?php elseif ($status === 'rejected'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?= ucfirst(htmlspecialchars($status)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php 
                                            $dateValue = $app['applied_date'] ?? $app['created_at'] ?? null;
                                            $timestamp = $dateValue ? strtotime($dateValue) : time();
                                            echo date('M d, Y', $timestamp);
                                            ?>
                                            <div class="text-xs text-gray-500">
                                                <?php 
                                                // Replace the humanize_time function with a simple time ago display
                                                $now = time();
                                                $diff = $now - $timestamp;
                                                
                                                if ($diff < 60) {
                                                    echo "Just now";
                                                } elseif ($diff < 3600) {
                                                    echo floor($diff / 60) . " minutes ago";
                                                } elseif ($diff < 86400) {
                                                    echo floor($diff / 3600) . " hours ago";
                                                } elseif ($diff < 604800) {
                                                    echo floor($diff / 86400) . " days ago";
                                                } else {
                                                    echo date('M d, Y', $timestamp);
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php $isEligible = $app['is_eligible'] ?? true; ?>
                                            <?php if ($isEligible): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Eligible
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Not Eligible
                                                </span>
                                                <div class="text-xs text-red-600 mt-1"><?= htmlspecialchars($app['eligibility_reason'] ?? 'Unknown reason') ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/Coops_Bichi/public/admin/loans/application/<?= htmlspecialchars($app['id'] ?? '') ?>" class="text-primary-600 hover:text-primary-900" title="View Application">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($total_pages) && $total_pages > 1): ?>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <a href="?page=<?= max(1, $current_page - 1) ?><?= isset($query_string) ? $query_string : '' ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                                <a href="?page=<?= min($total_pages, $current_page + 1) ?><?= isset($query_string) ? $query_string : '' ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                        <a href="?page=<?= max(1, $current_page - 1) ?><?= isset($query_string) ? $query_string : '' ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
                                            <a href="?page=<?= $i ?><?= isset($query_string) ? $query_string : '' ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $current_page ? 'text-primary-600 bg-primary-50 z-10' : 'text-gray-500 hover:bg-gray-50' ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <a href="?page=<?= min($total_pages, $current_page + 1) ?><?= isset($query_string) ? $query_string : '' ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
    // Apply Filters
    const applyFilters = document.getElementById('applyFilters');
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            // Build filter query string
            const search = document.getElementById('search').value;
            const status = document.getElementById('status').value;
            const loanType = document.getElementById('loan_type').value;
            
            let queryParams = [];
            if (search) queryParams.push(`search=${encodeURIComponent(search)}`);
            if (status) queryParams.push(`status=${encodeURIComponent(status)}`);
            if (loanType) queryParams.push(`loan_type=${encodeURIComponent(loanType)}`);
            
            // Redirect with filter parameters
            window.location.href = `/Coops_Bichi/public/admin/loans/applications${queryParams.length ? '?' + queryParams.join('&') : ''}`;
        });
    }
});
</script>

<?php // removing the redundant footer include ?> 