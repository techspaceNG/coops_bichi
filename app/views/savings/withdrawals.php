<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Withdrawal History</h1>
                        <p class="text-primary-100">Track your savings withdrawal requests and their status</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="/savings/overview" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Savings
                        </a>
                        <?php if ($can_request_withdrawal): ?>
                        <a href="/savings/withdraw" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i> New Request
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Filter Requests</h2>
            </div>
            
            <div class="p-6">
                <form action="/savings/withdrawals" method="GET" class="space-y-4 md:space-y-0 md:flex md:flex-wrap md:items-end md:gap-4">
                    <div class="md:w-1/4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= isset($_GET['status']) && $_GET['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="processed" <?= isset($_GET['status']) && $_GET['status'] === 'processed' ? 'selected' : '' ?>>Processed</option>
                            <option value="rejected" <?= isset($_GET['status']) && $_GET['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="md:w-1/4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="start_date" name="start_date" 
                               value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>"
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div class="md:w-1/4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" id="end_date" name="end_date" 
                               value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>"
                               class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-filter mr-2"></i> Apply Filter
                        </button>
                        
                        <a href="/savings/withdrawals" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Withdrawal Requests -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Withdrawal Requests</h2>
                <p class="text-sm text-gray-500">
                    <?= $totalRequests ?> request(s) found
                </p>
            </div>
            
            <div class="p-6">
                <?php if (empty($withdrawals)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No withdrawal requests found</h3>
                        <p class="text-gray-500 mb-4">You haven't made any withdrawal requests yet or no requests match your filter criteria.</p>
                        
                        <?php if ($can_request_withdrawal): ?>
                        <a href="/savings/withdraw" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-plus mr-2"></i> Request a Withdrawal
                        </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Request Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purpose
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Last Updated
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($withdrawals as $withdrawal): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($withdrawal['request_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">
                                                ₦<?= number_format($withdrawal['amount'], 2) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= ucfirst(htmlspecialchars($withdrawal['purpose'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($withdrawal['status'] === 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            <?php elseif ($withdrawal['status'] === 'approved'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Approved
                                                </span>
                                            <?php elseif ($withdrawal['status'] === 'processed'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Processed
                                                </span>
                                            <?php elseif ($withdrawal['status'] === 'rejected'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($withdrawal['updated_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/savings/withdrawals/<?= $withdrawal['id'] ?>" class="text-primary-600 hover:text-primary-900">View Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="flex justify-center mt-6">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <?php if ($currentPage > 1): ?>
                                    <a href="/savings/withdrawals?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <?php if ($i === $currentPage): ?>
                                        <span class="relative inline-flex items-center px-4 py-2 border border-primary-500 bg-primary-50 text-sm font-medium text-primary-600">
                                            <?= $i ?>
                                        </span>
                                    <?php else: ?>
                                        <a href="/savings/withdrawals?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            <?= $i ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="/savings/withdrawals?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                <?php endif; ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Withdrawal Process</h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                                    <span class="text-sm font-medium">1</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Request Submission</h3>
                                <p class="mt-1 text-sm text-gray-500">Member submits a withdrawal request through the portal, specifying the amount and purpose.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600">
                                    <span class="text-sm font-medium">2</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Review</h3>
                                <p class="mt-1 text-sm text-gray-500">The cooperative society reviews the request within 2-3 working days.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-green-600">
                                    <span class="text-sm font-medium">3</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Approval</h3>
                                <p class="mt-1 text-sm text-gray-500">If approved, the request is forwarded for processing. If rejected, a reason will be provided.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-purple-100 text-purple-600">
                                    <span class="text-sm font-medium">4</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Processing & Disbursement</h3>
                                <p class="mt-1 text-sm text-gray-500">Once approved, funds are transferred to the member's bank account within 3-5 working days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Withdrawal Status Definitions</h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="h-5 w-5 rounded-full bg-yellow-400 flex items-center justify-center">
                                        <span class="text-white text-xs">!</span>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Pending</h3>
                                    <div class="mt-1 text-sm text-yellow-700">
                                        <p>Your request has been submitted and is awaiting review by the cooperative society administrators.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="rounded-md bg-blue-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="h-5 w-5 rounded-full bg-blue-400 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Approved</h3>
                                    <div class="mt-1 text-sm text-blue-700">
                                        <p>Your request has been reviewed and approved. The funds are now being prepared for disbursement.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="h-5 w-5 rounded-full bg-green-400 flex items-center justify-center">
                                        <i class="fas fa-check-double text-white text-xs"></i>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Processed</h3>
                                    <div class="mt-1 text-sm text-green-700">
                                        <p>The funds have been successfully transferred to your bank account. Please check your account to confirm receipt.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="h-5 w-5 rounded-full bg-red-400 flex items-center justify-center">
                                        <i class="fas fa-times text-white text-xs"></i>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Rejected</h3>
                                    <div class="mt-1 text-sm text-red-700">
                                        <p>Your request has been declined. Please check the rejection reason and contact the cooperative office for more information if needed.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 