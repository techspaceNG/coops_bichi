<?php
// Member Savings Withdrawals View

$flashMessage = \App\Helpers\Utility::getFlashMessage();
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Withdrawal Requests</h1>
                <p class="text-primary-100">View and manage your savings withdrawal requests</p>
            </div>
        </div>
        
        <?php if ($flashMessage): ?>
            <div class="bg-<?= $flashMessage['type'] === 'error' ? 'red' : ($flashMessage['type'] === 'success' ? 'green' : 'blue') ?>-50 
                text-<?= $flashMessage['type'] === 'error' ? 'red' : ($flashMessage['type'] === 'success' ? 'green' : 'blue') ?>-800 
                p-4 rounded-md mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-<?= $flashMessage['type'] === 'error' ? 'exclamation-circle' : 
                            ($flashMessage['type'] === 'success' ? 'check-circle' : 'info-circle') ?> 
                            text-<?= $flashMessage['type'] === 'error' ? 'red' : ($flashMessage['type'] === 'success' ? 'green' : 'blue') ?>-400"></i>
                    </div>
                    <div class="ml-3">
                        <p><?= htmlspecialchars($flashMessage['message']) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Your Withdrawal Requests</h2>
                        <div>
                            <a href="/Coops_Bichi/public/member/savings/withdraw" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-plus mr-2"></i>
                                New Request
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filter Form -->
                    <div class="border-b p-4 bg-gray-50">
                        <form action="/Coops_Bichi/public/member/savings/withdrawals" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 w-full">
                                    <i class="fas fa-filter mr-2"></i>
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Withdrawals Table -->
                    <div class="overflow-x-auto">
                        <?php if (!empty($withdrawals)): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Payment Method
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
                                    <?php foreach ($withdrawals as $withdrawal): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M d, Y', strtotime($withdrawal['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-primary-600">₦<?= number_format($withdrawal['amount'], 2) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($withdrawal['payment_method'] ?? 'Bank Transfer') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php
                                                    switch($withdrawal['status']) {
                                                        case 'pending':
                                                            echo 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'approved':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'completed':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'rejected':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                    ?>">
                                                    <?= ucfirst($withdrawal['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="/Coops_Bichi/public/member/savings/withdrawals/<?= $withdrawal['id'] ?>" class="text-primary-600 hover:text-primary-900">
                                                    View Details
                                                </a>
                                                
                                                <?php if ($withdrawal['status'] === 'pending'): ?>
                                                    <a href="/Coops_Bichi/public/member/savings/withdraw/cancel/<?= $withdrawal['id'] ?>" 
                                                        class="ml-4 text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to cancel this withdrawal request?');">
                                                        Cancel
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="py-16 text-center">
                                <div class="text-gray-400 mb-4">
                                    <i class="fas fa-inbox fa-3x"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No withdrawal requests found</h3>
                                <p class="mt-1 text-sm text-gray-500">Start by creating a new withdrawal request.</p>
                                <div class="mt-6">
                                    <a href="/Coops_Bichi/public/member/savings/withdraw" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        New Withdrawal Request
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (!empty($withdrawals) && isset($pagination)): ?>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium"><?= $pagination['start'] ?></span> to <span class="font-medium"><?= $pagination['end'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <?php if ($pagination['current_page'] > 1): ?>
                                            <a href="/Coops_Bichi/public/member/savings/withdrawals?page=<?= $pagination['current_page'] - 1 ?><?= isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : '' ?><?= isset($_GET['date_from']) ? '&date_from=' . htmlspecialchars($_GET['date_from']) : '' ?><?= isset($_GET['date_to']) ? '&date_to=' . htmlspecialchars($_GET['date_to']) : '' ?>" 
                                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Previous</span>
                                                <i class="fas fa-chevron-left h-5 w-5"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                            <a href="/Coops_Bichi/public/member/savings/withdrawals?page=<?= $i ?><?= isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : '' ?><?= isset($_GET['date_from']) ? '&date_from=' . htmlspecialchars($_GET['date_from']) : '' ?><?= isset($_GET['date_to']) ? '&date_to=' . htmlspecialchars($_GET['date_to']) : '' ?>" 
                                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $pagination['current_page'] ? 'text-primary-600 bg-primary-50 border-primary-500 z-10' : 'text-gray-500 hover:bg-gray-50' ?>">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                            <a href="/Coops_Bichi/public/member/savings/withdrawals?page=<?= $pagination['current_page'] + 1 ?><?= isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : '' ?><?= isset($_GET['date_from']) ? '&date_from=' . htmlspecialchars($_GET['date_from']) : '' ?><?= isset($_GET['date_to']) ? '&date_to=' . htmlspecialchars($_GET['date_to']) : '' ?>" 
                                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <i class="fas fa-chevron-right h-5 w-5"></i>
                                            </a>
                                        <?php endif; ?>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <a href="/Coops_Bichi/public/member/savings" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Savings Dashboard
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/withdraw" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                New Withdrawal Request
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/statement" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                View Statement
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Withdrawal Guidelines -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Guidelines</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="text-sm text-gray-600 space-y-4">
                            <div>
                                <h3 class="text-base font-medium text-gray-800 mb-2">Withdrawal Processing Time</h3>
                                <p>Withdrawal requests are processed within 2-3 business days.</p>
                            </div>
                            
                            <div>
                                <h3 class="text-base font-medium text-gray-800 mb-2">Cancellation Policy</h3>
                                <p>Pending withdrawal requests can be cancelled at any time before approval.</p>
                            </div>
                            
                            <div>
                                <h3 class="text-base font-medium text-gray-800 mb-2">Status Updates</h3>
                                <p>You will receive email notifications when your withdrawal status changes.</p>
                            </div>
                            
                            <div class="pt-4 border-t">
                                <p class="text-xs text-gray-500">For any questions or assistance, please contact the cooperative support team at <a href="mailto:support@coopsbichi.org" class="text-primary-600 hover:text-primary-800">support@coopsbichi.org</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 