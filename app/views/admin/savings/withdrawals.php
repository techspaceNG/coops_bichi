<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Savings Withdrawal Requests</h1>
        <div class="flex space-x-2">
            <a href="/Coops_Bichi/public/admin/savings" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Savings
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
    
    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="/Coops_Bichi/public/admin/savings/withdrawals" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="Member Name or Coop Number" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            </div>
            <div class="col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="pending" <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= isset($_GET['status']) && $_GET['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= isset($_GET['status']) && $_GET['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    <option value="processed" <?= isset($_GET['status']) && $_GET['status'] === 'processed' ? 'selected' : '' ?>>Processed</option>
                </select>
            </div>
            <div class="col-span-1 flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="/Coops_Bichi/public/admin/savings/withdrawals" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Withdrawals List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <?php if (empty($withdrawals)): ?>
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-money-bill-wave text-5xl mb-4 opacity-30"></i>
                <p class="text-lg">No withdrawal requests found</p>
                <p class="text-sm mt-2">Members haven't made any withdrawal requests yet</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coop Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($withdrawals as $withdrawal): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $withdrawal['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M d, Y', strtotime($withdrawal['created_at'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($withdrawal['member_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($withdrawal['coop_no']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600"><?= number_format($withdrawal['amount'], 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($withdrawal['description'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php 
                                    if (!isset($withdrawal['status']) || $withdrawal['status'] === 'pending') {
                                        echo 'bg-yellow-100 text-yellow-800';
                                    } elseif ($withdrawal['status'] === 'approved') {
                                        echo 'bg-green-100 text-green-800';
                                    } elseif ($withdrawal['status'] === 'rejected') {
                                        echo 'bg-red-100 text-red-800';
                                    }
                                    ?>">
                                        <?= ucfirst($withdrawal['status'] ?? 'pending') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="/Coops_Bichi/public/admin/members/view/<?= $withdrawal['member_id'] ?>" class="text-blue-600 hover:text-blue-900" title="View member details">
                                        <i class="fas fa-user"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close modal when clicking outside
        window.onclick = function(event) {
            // No modals needed for now
        }
    });
</script> 