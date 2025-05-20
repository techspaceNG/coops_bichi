<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Share Details</h1>
                        <p class="text-gray-600 mt-1">View detailed information about your share</p>
                    </div>
                    <a href="/Coops_Bichi/public/member/shares" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Shares
                    </a>
                </div>
            </div>

            <!-- Share Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Share Type</label>
                                <p class="mt-1 text-gray-900"><?= htmlspecialchars(ucfirst($share->getShareType())) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Quantity</label>
                                <p class="mt-1 text-gray-900"><?= number_format($share->getQuantity()) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Unit Price</label>
                                <p class="mt-1 text-gray-900">₦<?= number_format($share->getUnitPrice(), 2) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Total Value</label>
                                <p class="mt-1 text-gray-900">₦<?= number_format($share->getTotalValue(), 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Purchase Date</label>
                                <p class="mt-1 text-gray-900"><?= date('F j, Y', strtotime($share->getPurchaseDate())) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <p class="mt-1">
                                    <?php
                                    $statusClass = match($share->getStatus()) {
                                        'active' => 'bg-green-100 text-green-800',
                                        'sold' => 'bg-blue-100 text-blue-800',
                                        'forfeited' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= ucfirst($share->getStatus()) ?>
                                    </span>
                                </p>
                            </div>
                            <?php if ($share->getStatus() === 'sold'): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Sale Date</label>
                                <p class="mt-1 text-gray-900"><?= date('F j, Y', strtotime($share->getSaleDate())) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Sale Price</label>
                                <p class="mt-1 text-gray-900">₦<?= number_format($share->getSalePrice(), 2) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Transaction History</h2>
                
                <?php if (empty($transactions)): ?>
                    <p class="text-gray-600">No transactions found for this share.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('F j, Y', strtotime($transaction->getTransactionDate())) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= ucfirst($transaction->getTransactionType()) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= number_format($transaction->getQuantity()) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₦<?= number_format($transaction->getPrice(), 2) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?= htmlspecialchars($transaction->getDescription()) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 