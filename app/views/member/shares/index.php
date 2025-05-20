<?php
require_once APP_ROOT . '/views/layouts/header.php';
?>

<main class="flex-grow">
    <div class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">My Shares</h1>
                        <p class="text-gray-600 mt-1">Total Shares Value: ₦<?= number_format($total_value, 2) ?></p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="/Coops_Bichi/public/member/shares/purchase" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i> Purchase Shares
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Shares List -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <?php if (empty($shares)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">No Shares Found</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't purchased any shares yet.</p>
                        <div class="mt-6">
                            <a href="/Coops_Bichi/public/member/shares/purchase" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i> Purchase Your First Shares
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Share Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($shares as $share): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= ucfirst(htmlspecialchars($share->getShareType())) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= number_format($share->getQuantity()) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₦<?= number_format($share->getUnitPrice(), 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₦<?= number_format($share->getTotalValue(), 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($share->getPurchaseDate())) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                <?php
                                                switch ($share->getStatus()) {
                                                    case 'active':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'sold':
                                                        echo 'bg-gray-100 text-gray-800';
                                                        break;
                                                    case 'forfeited':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                }
                                                ?>">
                                                <?= ucfirst(htmlspecialchars($share->getStatus())) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex space-x-2">
                                                <a href="/Coops_Bichi/public/member/shares/view/<?= $share->getId() ?>" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($share->getStatus() === 'active'): ?>
                                                    <a href="/Coops_Bichi/public/member/shares/sell/<?= $share->getId() ?>" class="text-green-600 hover:text-green-800">
                                                        <i class="fas fa-dollar-sign"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
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
</main>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 