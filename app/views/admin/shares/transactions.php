<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Share Transactions (View Only)</h1>
        <div class="flex space-x-2">
            <a href="/Coops_Bichi/public/admin/shares" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Shares
            </a>
            <a href="/Coops_Bichi/public/admin/shares/export-transactions" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-file-export mr-2"></i> Export Transactions
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
    
    <!-- View Only Mode Notice -->
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="ml-3">
                <p class="font-bold">View-Only Mode</p>
                <p>Shares module is currently in view-only mode. You can view transaction information but cannot modify records.</p>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter Form -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="/Coops_Bichi/public/admin/shares/transactions" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Member Name, Coop No" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            </div>
            <div class="col-span-1">
                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <input type="text" name="date_range" id="date_range" value="<?= $_GET['date_range'] ?? '' ?>" placeholder="MM/DD/YYYY - MM/DD/YYYY" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 date-range-picker">
            </div>
            <div class="col-span-1">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Types</option>
                    <option value="purchase" <?= isset($_GET['type']) && $_GET['type'] === 'purchase' ? 'selected' : '' ?>>Purchase</option>
                    <option value="sell" <?= isset($_GET['type']) && $_GET['type'] === 'sell' ? 'selected' : '' ?>>Sell</option>
                    <option value="transfer" <?= isset($_GET['type']) && $_GET['type'] === 'transfer' ? 'selected' : '' ?>>Transfer</option>
                </select>
            </div>
            <div class="col-span-1 flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="/Coops_Bichi/public/admin/shares/transactions" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if (empty($transactions)): ?>
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-exchange-alt text-5xl mb-4 opacity-30"></i>
                <p class="text-lg">No transactions found</p>
                <p class="text-sm mt-2">Try a different search criteria or add new transactions</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coop No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($transaction['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($transaction['coop_no']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($transaction['member_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($transaction['transaction_type'] === 'purchase'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Purchase</span>
                                    <?php elseif ($transaction['transaction_type'] === 'sell'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Sell</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Transfer</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($transaction['units']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($transaction['unit_value'], 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?= $transaction['transaction_type'] === 'purchase' ? 'text-green-600' : 'text-red-600' ?>">
                                    <?php 
                                    echo $transaction['transaction_type'] === 'purchase' ? '+' : '-';
                                    echo number_format($transaction['total_amount'], 2); 
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($transaction['transaction_date'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="/Coops_Bichi/public/admin/members/view/<?= $transaction['member_id'] ?>" class="text-blue-600 hover:text-blue-900" title="View member details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" onclick="showDetailModal(<?= $transaction['id'] ?>)" class="text-indigo-600 hover:text-indigo-900" title="View transaction details">
                                        <i class="fas fa-info-circle"></i>
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

<!-- Modal placeholder for transaction details -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="detailTitle">Transaction Details</h3>
            <div class="mt-4" id="detailContent">
                <!-- Content will be loaded here -->
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-primary-600 text-2xl"></i>
                    <p class="mt-2 text-gray-500">Loading transaction details...</p>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetailModal(transactionId) {
        document.getElementById('detailModal').classList.remove('hidden');
        // In a real implementation, you would load transaction details via AJAX
        document.getElementById('detailContent').innerHTML = `
            <div class="mt-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Transaction ID:</span>
                    <span class="text-sm">${transactionId}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Reference:</span>
                    <span class="text-sm">SHR-${transactionId.toString().padStart(6, '0')}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Created By:</span>
                    <span class="text-sm">Admin User</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500">Created At:</span>
                    <span class="text-sm">${new Date().toLocaleString()}</span>
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <p class="text-sm font-medium text-gray-500">Notes:</p>
                    <p class="text-sm mt-1">Share purchase transaction for cooperative member.</p>
                </div>
            </div>
        `;
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeDetailModal();
            }
        }
        
        // Date range picker initialization would go here
        // In a real implementation, you would initialize a date range picker library
    });
</script> 