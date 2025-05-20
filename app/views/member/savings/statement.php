<?php
// Member Savings Statement View

$flashMessage = \App\Helpers\Utility::getFlashMessage();
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Savings Statement</h1>
                <p class="text-primary-100">View your savings transaction history</p>
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
                <!-- Filter Form -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Filter Transactions</h2>
                    </div>
                    
                    <div class="p-6">
                        <form action="/Coops_Bichi/public/member/savings/statement" method="GET" class="space-y-4 md:space-y-0 md:flex md:space-x-4">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" id="start_date" name="start_date" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                    value="<?= isset($start_date) ? htmlspecialchars($start_date) : date('Y-m-d', strtotime('-3 months')) ?>">
                            </div>
                            
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" id="end_date" name="end_date" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                    value="<?= isset($end_date) ? htmlspecialchars($end_date) : date('Y-m-d') ?>">
                            </div>
                            
                            <div class="flex-1">
                                <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                                <select id="transaction_type" name="transaction_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                    <option value="all" <?= (!isset($transaction_type) || $transaction_type === 'all') ? 'selected' : '' ?>>All Transactions</option>
                                    <option value="deposit" <?= (isset($transaction_type) && $transaction_type === 'deposit') ? 'selected' : '' ?>>Deposits Only</option>
                                    <option value="withdrawal" <?= (isset($transaction_type) && $transaction_type === 'withdrawal') ? 'selected' : '' ?>>Withdrawals Only</option>
                                    <option value="interest" <?= (isset($transaction_type) && $transaction_type === 'interest') ? 'selected' : '' ?>>Interest Only</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="w-full md:w-auto px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Transaction Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Transaction History</h2>
                        
                        <a href="/Coops_Bichi/public/member/transactions/download?type=savings&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>&transaction_type=<?= urlencode($transaction_type) ?>" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-download mr-2"></i> Export
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($transactions) && !empty($transactions)): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M d, Y', strtotime($transaction['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <?= htmlspecialchars($transaction['description']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($transaction['reference_no'] ?? 'N/A') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm <?= $transaction['transaction_type'] === 'deposit' ? 'text-green-600' : 'text-red-600' ?>">
                                                <?= $transaction['transaction_type'] === 'deposit' ? '+' : '-' ?>₦<?= number_format($transaction['amount'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    <?php
                                                    switch($transaction['transaction_type']) {
                                                        case 'deposit':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'withdrawal':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        case 'interest':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                    ?>">
                                                    <?= ucfirst($transaction['transaction_type']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                                <div class="px-6 py-4 border-t">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-700">
                                            Showing <span class="font-medium"><?= ($pagination['current_page'] - 1) * $pagination['per_page'] + 1 ?></span> to 
                                            <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']) ?></span> of 
                                            <span class="font-medium"><?= $pagination['total_records'] ?></span> results
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <?php if ($pagination['current_page'] > 1): ?>
                                                <a href="/Coops_Bichi/public/member/savings/statement?page=<?= $pagination['current_page'] - 1 ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>&transaction_type=<?= urlencode($transaction_type) ?>" 
                                                    class="px-3 py-1 border rounded text-sm text-gray-700 hover:bg-gray-50">
                                                    Previous
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                                <a href="/Coops_Bichi/public/member/savings/statement?page=<?= $pagination['current_page'] + 1 ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>&transaction_type=<?= urlencode($transaction_type) ?>" 
                                                    class="px-3 py-1 border rounded text-sm text-gray-700 hover:bg-gray-50">
                                                    Next
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-file-alt text-4xl mb-3 text-gray-300"></i>
                                <p>No transactions found for the selected period</p>
                            </div>
                        <?php endif; ?>
                    </div>
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
                                Back to Savings Dashboard
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/withdraw" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Request Withdrawal
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Statement Tips -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Statement Tips</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Use Filters</p>
                                    <p class="text-gray-500">Filter by date range and transaction type to find specific transactions.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Export Statement</p>
                                    <p class="text-gray-500">Use the Export button to download your statement as a PDF or Excel file.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Transaction Types</p>
                                    <p class="text-gray-500">Green amounts are deposits, red amounts are withdrawals.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 