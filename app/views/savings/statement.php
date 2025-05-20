<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Savings Statement</h1>
                        <p class="text-primary-100">Complete history of your savings transactions</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="/savings/overview" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Savings
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters & Export -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Filter Transactions</h2>
            </div>
            
            <div class="p-6">
                <form action="/savings/statement" method="GET" class="space-y-4 md:space-y-0 md:flex md:flex-wrap md:items-end md:gap-4">
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
                    
                    <div class="md:w-1/4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                        <select id="type" name="type" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">All Transactions</option>
                            <option value="contribution" <?= isset($_GET['type']) && $_GET['type'] === 'contribution' ? 'selected' : '' ?>>Contributions</option>
                            <option value="withdrawal" <?= isset($_GET['type']) && $_GET['type'] === 'withdrawal' ? 'selected' : '' ?>>Withdrawals</option>
                            <option value="dividend" <?= isset($_GET['type']) && $_GET['type'] === 'dividend' ? 'selected' : '' ?>>Dividends</option>
                        </select>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-filter mr-2"></i> Apply Filter
                        </button>
                        
                        <a href="/savings/statement" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Clear Filters
                        </a>
                        
                        <a href="/savings/statement/export<?= !empty($_SERVER['QUERY_STRING']) ? '?' . htmlspecialchars($_SERVER['QUERY_STRING']) : '' ?>" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-file-excel mr-2"></i> Export to Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Statement Summary -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Statement Summary</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-800 font-medium">Opening Balance</p>
                        <p class="text-xl font-bold text-blue-900 mt-1">₦<?= number_format($summary['opening_balance'], 2) ?></p>
                        <p class="text-xs text-blue-600 mt-1"><?= date('M d, Y', strtotime($summary['start_date'])) ?></p>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-green-800 font-medium">Total Contributions</p>
                        <p class="text-xl font-bold text-green-900 mt-1">₦<?= number_format($summary['total_contributions'], 2) ?></p>
                        <p class="text-xs text-green-600 mt-1"><?= $summary['contribution_count'] ?> transaction(s)</p>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-sm text-red-800 font-medium">Total Withdrawals</p>
                        <p class="text-xl font-bold text-red-900 mt-1">₦<?= number_format($summary['total_withdrawals'], 2) ?></p>
                        <p class="text-xs text-red-600 mt-1"><?= $summary['withdrawal_count'] ?> transaction(s)</p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm text-purple-800 font-medium">Closing Balance</p>
                        <p class="text-xl font-bold text-purple-900 mt-1">₦<?= number_format($summary['closing_balance'], 2) ?></p>
                        <p class="text-xs text-purple-600 mt-1"><?= date('M d, Y', strtotime($summary['end_date'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transaction Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Transaction History</h2>
                <p class="text-sm text-gray-500">
                    <?= $totalTransactions ?> transaction(s) found
                    <?php if (!empty($_GET)): ?>
                        <a href="/savings/statement" class="ml-2 text-primary-600 hover:text-primary-800">(View All)</a>
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="p-6">
                <?php if (empty($transactions)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No transactions found</h3>
                        <p class="text-gray-500">Try adjusting your filters or viewing all transactions.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transaction ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Balance
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($transaction['date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($transaction['transaction_id']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($transaction['description']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($transaction['type'] === 'contribution'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Contribution
                                                </span>
                                            <?php elseif ($transaction['type'] === 'withdrawal'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Withdrawal
                                                </span>
                                            <?php elseif ($transaction['type'] === 'dividend'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Dividend
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?= ucfirst(htmlspecialchars($transaction['type'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                            <?= $transaction['type'] === 'withdrawal' ? 'text-red-600' : 'text-green-600' ?>">
                                            <?= $transaction['type'] === 'withdrawal' ? '-' : '+' ?>₦<?= number_format($transaction['amount'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₦<?= number_format($transaction['balance_after'], 2) ?>
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
                                    <a href="/savings/statement?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
                                        <a href="/savings/statement?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            <?= $i ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="/savings/statement?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 