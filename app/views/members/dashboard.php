<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Welcome, <?= htmlspecialchars($member->first_name) ?>!</h1>
                    <p class="text-gray-600 mt-1">Cooperative No: <?= htmlspecialchars($member->coop_no) ?></p>
                    <p class="text-gray-600 mt-1">TI Number: <?= htmlspecialchars($member->ti_number ?? 'Not Available') ?></p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="px-3 py-1 text-sm rounded-full <?= $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= ucfirst(htmlspecialchars($member->status)) ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Account Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Savings Balance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                        <i class="fas fa-piggy-bank text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Savings Balance</p>
                        <h3 class="text-2xl font-bold text-gray-800">₦<?= number_format($member->savings_balance, 2) ?></h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= url('/member/savings') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Savings Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Loan Balance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                        <i class="fas fa-hand-holding-usd text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loan Balance</p>
                        <h3 class="text-2xl font-bold text-gray-800">₦<?= number_format($member->loan_balance, 2) ?></h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= url('/member/loans') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Loan Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Household Purchases -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                        <i class="fas fa-shopping-basket text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Household Purchases</p>
                        <h3 class="text-2xl font-bold text-gray-800">₦<?= number_format($member->household_balance, 2) ?></h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= url('/member/household') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Purchase Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Shares Balance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Shares Value</p>
                        <h3 class="text-2xl font-bold text-gray-800">₦<?= number_format($member->shares_balance, 2) ?></h3>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= url('/member/shares') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Shares Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Links Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?= url('/member/loans/apply') ?>" class="flex flex-col items-center p-4 border rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-3">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <span class="text-gray-800 font-medium">Apply for Loan</span>
                </a>
                
                <a href="<?= url('/member/savings/withdraw') ?>" class="flex flex-col items-center p-4 border rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-3">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <span class="text-gray-800 font-medium">Request Withdrawal</span>
                </a>
                
                <a href="<?= url('/member/household/order') ?>" class="flex flex-col items-center p-4 border rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-3">
                        <i class="fas fa-cart-plus text-xl"></i>
                    </div>
                    <span class="text-gray-800 font-medium">Apply for Household Purchase</span>
                </a>
                
                <a href="<?= url('/member/profile') ?>" class="flex flex-col items-center p-4 border rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-3">
                        <i class="fas fa-user-edit text-xl"></i>
                    </div>
                    <span class="text-gray-800 font-medium">Update Profile</span>
                </a>
            </div>
        </div>
        
        <!-- Notifications Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Notifications</h2>
                <a href="<?= url('/member/notifications') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <?php if (empty($notifications)): ?>
                <div class="text-center py-4">
                    <p class="text-gray-500">No notifications found.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="p-3 border rounded-md flex items-start <?= $notification['is_read'] ? 'bg-white' : 'bg-blue-50' ?>">
                            <!-- Notification Icon based on type -->
                            <div class="mr-3 mt-1">
                                <?php $notificationType = $notification['type'] ?? 'info'; ?>
                                <?php if ($notificationType === 'success'): ?>
                                    <i class="fas fa-check-circle text-green-500"></i>
                                <?php elseif ($notificationType === 'warning'): ?>
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                <?php elseif ($notificationType === 'error'): ?>
                                    <i class="fas fa-times-circle text-red-500"></i>
                                <?php else: ?>
                                    <i class="fas fa-info-circle text-blue-500"></i>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-medium text-gray-800"><?= htmlspecialchars($notification['title']) ?></h3>
                                    <div class="text-xs text-gray-500">
                                        <?= date('M d', strtotime($notification['created_at'])) ?>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-1"><?= htmlspecialchars(substr($notification['message'], 0, 100)) ?><?= strlen($notification['message']) > 100 ? '...' : '' ?></p>
                                
                                <?php if (isset($notification['link']) && $notification['link']): ?>
                                    <div class="mt-2">
                                        <a href="<?= formatNotificationLink($notification['link'], $publicUrl ?? '/Coops_Bichi/public', $notification['id']) ?>" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            View Details <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                <?php elseif (isset($notification['action_url']) && $notification['action_url']): ?>
                                    <div class="mt-2">
                                        <a href="<?= $notification['action_url'] ?>" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            View Details <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Applications Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
            <!-- Recent Loan Applications -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Loan Applications</h2>
                    <a href="<?= url('/member/loans/applications') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <?php if (empty($loanApplications)): ?>
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">No recent loan applications.</p>
                        <a href="<?= url('/member/loans/apply') ?>" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">Apply Now</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($loanApplications, 0, 3) as $app): ?>
                            <div class="flex items-center justify-between p-3 border rounded-md">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">₦<?= number_format($app['loan_amount'], 2) ?></p>
                                    <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($app['application_date'])) ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        switch ($app['status']) {
                                            case 'approved': echo 'bg-green-100 text-green-800'; break;
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(htmlspecialchars($app['status'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Household Applications -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Household Applications</h2>
                    <a href="<?= url('/member/household/applications') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <?php if (empty($householdApplications)): ?>
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">No recent household applications.</p>
                        <a href="<?= url('/member/household/order') ?>" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">Apply Now</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($householdApplications, 0, 3) as $app): ?>
                            <div class="flex items-center justify-between p-3 border rounded-md">
                                <div>
                                    <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($app['product_name'] ?? 'Household Item') ?></p>
                                    <p class="text-xs text-gray-500">₦<?= number_format($app['purchase_amount'], 2) ?> • <?= date('M d, Y', strtotime($app['application_date'])) ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        switch ($app['status']) {
                                            case 'approved': echo 'bg-green-100 text-green-800'; break;
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(htmlspecialchars($app['status'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Recent Transactions</h2>
                <a href="<?= url('/member/transactions') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <?php if (empty($recent_transactions)): ?>
                <div class="text-center py-4">
                    <p class="text-gray-500">No recent transactions found.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recent_transactions as $transaction): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('M d, Y', strtotime($transaction['date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php
                                            switch ($transaction['type']) {
                                                case 'savings':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'loan':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'household':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst(htmlspecialchars($transaction['type'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($transaction['description']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm 
                                        <?= $transaction['amount'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $transaction['amount'] > 0 ? '+' : '' ?>₦<?= number_format(abs($transaction['amount']), 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php
                                            switch ($transaction['status']) {
                                                case 'completed':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'pending':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                case 'declined':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst(htmlspecialchars($transaction['status'])) ?>
                                        </span>
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

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 