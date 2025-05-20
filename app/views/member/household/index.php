<?php
// Member Household Purchases View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_household');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Household Purchases</h1>
                <p class="text-primary-100">Manage your household purchases with FCET Bichi Staff Multipurpose Cooperative Society</p>
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
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2">
                <!-- Current Household Status -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Current Household Purchases</h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($activePurchases) && !empty($activePurchases)): ?>
                            <div class="space-y-6">
                                <?php foreach ($activePurchases as $purchase): ?>
                                    <?php
                                    // Get item description - handle either field name
                                    $itemDescription = '';
                                    if (isset($purchase['item_description']) && !empty($purchase['item_description'])) {
                                        $itemDescription = $purchase['item_description'];
                                    } elseif (isset($purchase['item_name']) && !empty($purchase['item_name'])) {
                                        $itemDescription = $purchase['item_name'];
                                    } elseif (isset($purchase['household_item']) && !empty($purchase['household_item'])) {
                                        $itemDescription = $purchase['household_item'];
                                    } else {
                                        $itemDescription = 'Household Item';
                                    }
                                    
                                    // Get item cost - handle either field name
                                    $itemCost = 0;
                                    if (isset($purchase['item_cost']) && !empty($purchase['item_cost'])) {
                                        $itemCost = $purchase['item_cost'];
                                    } elseif (isset($purchase['household_amount']) && !empty($purchase['household_amount'])) {
                                        $itemCost = $purchase['household_amount'];
                                    }
                                    ?>
                                    <div class="border rounded-lg overflow-hidden">
                                        <div class="bg-gray-50 px-4 py-3 border-b">
                                            <div class="flex justify-between items-center">
                                                <h3 class="font-medium text-gray-800"><?= htmlspecialchars($itemDescription) ?></h3>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    <?php
                                                    $statusClass = '';
                                                    $status = strtolower($purchase['status']);
                                                    switch($status) {
                                                        case 'pending':
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'approved':
                                                        case 'active':
                                                        case '1': // For numeric status
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'delivered':
                                                            $statusClass = 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'completed':
                                                            $statusClass = 'bg-purple-100 text-purple-800';
                                                            break;
                                                        case 'rejected':
                                                        case 'declined':
                                                        case 'cancelled':
                                                        case '0': // For numeric status
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-gray-100 text-gray-800';
                                                    }
                                                    echo $statusClass;
                                                    ?>">
                                                    <?= ucfirst(htmlspecialchars($purchase['status'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <p class="text-sm text-gray-600">Purchased On</p>
                                                    <p class="font-medium"><?= date('M d, Y', strtotime($purchase['created_at'])) ?></p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Item Category</p>
                                                    <p class="font-medium"><?= ucfirst(htmlspecialchars($purchase['item_category'] ?? 'General')) ?></p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Total Amount</p>
                                                    <p class="font-medium">₦<?= number_format($purchase['item_cost'], 2) ?></p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Monthly Payment</p>
                                                    <p class="font-medium">₦<?= number_format($purchase['monthly_payment'], 2) ?></p>
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <p class="text-sm text-gray-600">Admin Charge (<?= number_format($purchase['admin_charge_rate'] ?? 5, 1) ?>%)</p>
                                                    <p class="font-medium">₦<?= number_format($purchase['admin_charge'], 2) ?></p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600">Total Amount with Charges</p>
                                                    <p class="font-medium">₦<?= number_format($purchase['total_repayment'], 2) ?></p>
                                                </div>
                                            </div>
                                            
                                            <?php 
                                            // Get the remaining balance
                                            $remainingBalance = $purchase['remaining_balance'] ?? 0;
                                            $totalAmount = $purchase['total_repayment'] ?? 0;
                                            
                                            // Calculate payment progress percentage - avoid division by zero
                                            $progressPercent = 0;
                                            if ($totalAmount > 0) {
                                                $progressPercent = round(100 - (($remainingBalance / $totalAmount) * 100));
                                                // Ensure it's between 0 and 100
                                                $progressPercent = max(0, min(100, $progressPercent));
                                            }
                                            
                                            if ($remainingBalance > 0 && $totalAmount > 0): 
                                            ?>
                                                <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm text-gray-600">Payment Progress</span>
                                                        <span class="text-xs font-medium text-gray-700">
                                                            <?= $progressPercent ?>% Paid
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: <?= $progressPercent ?>%"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-info-circle mr-1"></i> The 5% admin charge is included in the total amount to be repaid
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="text-right">
                                                <a href="/Coops_Bichi/public/member/household/details/<?= $purchase['id'] ?>" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                                    View Details <i class="fas fa-chevron-right ml-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="bg-blue-50 text-blue-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You don't have any active household purchases</p>
                                        <p class="mt-1">Apply for a household purchase by clicking the button below.</p>
                                        
                                        <?php if (defined('APP_DEBUG') && APP_DEBUG): ?>
                                        <div class="mt-3 p-3 bg-gray-100 rounded text-xs text-gray-700">
                                            <p><strong>Debug Info:</strong></p>
                                            <p>Active Purchases: <?= isset($activePurchases) ? count($activePurchases) : 'Not set' ?></p>
                                            <p>Purchase Applications: <?= isset($purchaseApplications) ? count($purchaseApplications) : 'Not set' ?></p>
                                            <p>Household Balance: <?= isset($household_balance) ? $household_balance : 'Not set' ?></p>
                                            
                                            <?php if (isset($activePurchases) && !empty($activePurchases)): ?>
                                            <p class="mt-2"><strong>Active Purchases Data:</strong></p>
                                            <ul class="list-disc pl-4">
                                                <?php foreach ($activePurchases as $p): ?>
                                                <li>
                                                    ID: <?= $p['id'] ?>, 
                                                    Item: <?= $p['item_description'] ?? 'N/A' ?>, 
                                                    Cost: ₦<?= number_format($p['item_cost'] ?? 0, 2) ?>, 
                                                    Balance: ₦<?= number_format($p['remaining_balance'] ?? 0, 2) ?>, 
                                                    Status: <?= $p['status'] ?>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php endif; ?>
                                            
                                            <?php if (isset($purchaseApplications) && !empty($purchaseApplications)): ?>
                                            <p class="mt-2"><strong>Purchase Applications:</strong></p>
                                            <ul class="list-disc pl-4">
                                                <?php foreach ($purchaseApplications as $app): ?>
                                                <li>
                                                    ID: <?= $app['id'] ?>, 
                                                    Item: <?= $app['item_name'] ?? $app['item_description'] ?? 'N/A' ?>, 
                                                    Amount: ₦<?= number_format($app['household_amount'] ?? $app['item_cost'] ?? 0, 2) ?>,
                                                    Status: <?= $app['status'] ?>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <a href="/Coops_Bichi/public/member/household/order" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                    <i class="fas fa-plus-circle mr-2"></i> Apply for Household Purchase
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Applications -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Applications</h2>
                        
                        <?php if (isset($purchaseApplications) && !empty($purchaseApplications)): ?>
                            <a href="/Coops_Bichi/public/member/household/applications" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($purchaseApplications) && !empty($purchaseApplications)): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($purchaseApplications as $application): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M d, Y', strtotime($application['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <?= htmlspecialchars($application['item_description'] ?? $application['item_name'] ?? $application['description'] ?? 'Household Item') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                ₦<?= number_format($application['item_cost'] ?? $application['household_amount'] ?? $application['amount'] ?? 0, 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    <?php
                                                    $statusClass = '';
                                                    switch($application['status']) {
                                                        case 'pending':
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'approved':
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'rejected':
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-gray-100 text-gray-800';
                                                    }
                                                    echo $statusClass;
                                                    ?>
                                                ">
                                                    <?= ucfirst(htmlspecialchars($application['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="/Coops_Bichi/public/member/household/applications/<?= $application['id'] ?>" class="text-primary-600 hover:text-primary-900">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>No application history available</p>
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
                            <a href="/Coops_Bichi/public/member/household/order" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Apply for Household Purchase
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/household/applications" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                View Applications
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Summary -->
                <?php if (isset($household_balance) && $household_balance > 0): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Payment Summary</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-gray-600">Total Purchase Amount</span>
                                    <span class="font-medium">₦<?= number_format($total_purchase_amount ?? 0, 2) ?></span>
                                </div>
                                
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-gray-600">Amount Paid</span>
                                    <span class="font-medium text-green-600">₦<?= number_format(($total_purchase_amount ?? 0) - ($household_balance ?? 0), 2) ?></span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Outstanding Balance</span>
                                    <span class="font-medium text-primary-600">₦<?= number_format($household_balance, 2) ?></span>
                                </div>
                                <div class="pt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i> The 5% admin charge is included in the total balance
                                </div>
                                
                                <?php if ($household_balance > 0 && isset($total_purchase_amount) && $total_purchase_amount > 0): ?>
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-gray-600">Payment Progress</span>
                                        <span class="text-xs font-medium text-gray-700">
                                            <?= round(100 - (($household_balance / $total_purchase_amount) * 100)) ?>% Paid
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-primary-600 h-2.5 rounded-full" style="width: <?= round(100 - (($household_balance / $total_purchase_amount) * 100)) ?>%"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">About Household Purchases</h2>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Our cooperative offers affordable household item purchases with the following benefits:</p>
                        
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Pay in convenient monthly installments</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>5% admin charge applies to all purchases</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Wide range of household items available</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Quick approval and delivery process</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 