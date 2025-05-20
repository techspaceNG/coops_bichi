<?php require_once APP_ROOT . '/views/layouts/admin_header.php'; ?>

<div class="flex h-screen bg-gray-100">
    <!-- Main Content (now full-width) -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-semibold text-gray-800">Household Purchase Applications</h1>
                    <a href="/Coops_Bichi/public/admin/household" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Purchases
                    </a>
                </div>
                <p class="mt-2 text-sm text-gray-600">Review and process household purchase applications from members.</p>
            </div>

            <!-- Applications Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Pending Applications -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Applications</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= isset($app_stats['pending']) ? number_format($app_stats['pending']) : '0' ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Value: <span class="font-medium text-gray-800">₦<?= isset($app_stats['pending_value']) ? number_format($app_stats['pending_value'], 2) : '0.00' ?></span></p>
                    </div>
                </div>

                <!-- Approved Applications -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved Applications</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= isset($app_stats['approved']) ? number_format($app_stats['approved']) : '0' ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Total Value: <span class="font-medium text-gray-800">₦<?= isset($app_stats['approved_value']) ? number_format($app_stats['approved_value'], 2) : '0.00' ?></span></p>
                    </div>
                </div>

                <!-- Rejected Applications -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rejected Applications</p>
                            <p class="text-2xl font-semibold text-gray-800"><?= isset($app_stats['rejected']) ? number_format($app_stats['rejected']) : '0' ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Last 30 days: <span class="font-medium text-gray-800"><?= isset($app_stats['rejected_month']) ? number_format($app_stats['rejected_month']) : '0' ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <div class="bg-white shadow-sm rounded-lg mb-6">
                <div class="px-4 py-2 flex border-b border-gray-200">
                    <a href="?status=pending" class="px-4 py-2 mr-4 text-sm font-medium <?= $status === 'pending' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' ?>">
                        Pending
                    </a>
                    <a href="?status=approved" class="px-4 py-2 mr-4 text-sm font-medium <?= $status === 'approved' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' ?>">
                        Approved
                    </a>
                    <a href="?status=rejected" class="px-4 py-2 mr-4 text-sm font-medium <?= $status === 'rejected' ? 'text-primary-600 border-b-2 border-primary-600' : 'text-gray-500 hover:text-gray-700' ?>">
                        Rejected
                    </a>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800"><?= ucfirst($status) ?> Household Purchase Applications</h2>
                </div>
                
                <?php if (empty($applications)): ?>
                    <div class="p-6 text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-inbox text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No applications found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            There are no <?= strtolower($status) ?> household purchase applications at this time.
                        </p>
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
                                        Item Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        IP Figure
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Applied Date
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
                                <?php foreach ($applications as $application): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="/Coops_Bichi/public/admin/household/view/<?= $application['id'] ?>" class="hover:text-primary-600">
                                                    HP-<?= $application['id'] ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="/Coops_Bichi/public/assets/images/default-avatar.png" alt="Member Photo">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($application['member_name'] ?? $application['fullname']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= htmlspecialchars($application['coop_no'] ?? '') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($application['item_name']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₦<?= number_format($application['household_amount'], 2) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">₦<?= number_format($application['ip_figure'], 2) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($application['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($application['status'] === 'approved'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            <?php elseif ($application['status'] === 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            <?php elseif ($application['status'] === 'rejected'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?= ucfirst(htmlspecialchars($application['status'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/Coops_Bichi/public/admin/household/view/<?= $application['id'] ?>" class="text-primary-600 hover:text-primary-900 mr-3" title="View Application">
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
                                <a href="?status=<?= $status ?>&page=<?= max(1, $pagination['current_page'] - 1) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                                <a href="?status=<?= $status ?>&page=<?= min($pagination['last_page'], $pagination['current_page'] + 1) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                        <a href="?status=<?= $status ?>&page=<?= max(1, $pagination['current_page'] - 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
                                            <a href="?status=<?= $status ?>&page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $pagination['current_page'] ? 'text-primary-600 bg-primary-50 z-10' : 'text-gray-500 hover:bg-gray-50' ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <a href="?status=<?= $status ?>&page=<?= min($pagination['last_page'], $pagination['current_page'] + 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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