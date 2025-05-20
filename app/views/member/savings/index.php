<?php
// Member Savings Dashboard View

$flashMessage = \App\Helpers\Utility::getFlashMessage();
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Savings</h1>
                <p class="text-primary-100">Manage your savings with FCET Bichi Staff Multipurpose Cooperative Society</p>
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
                <!-- Savings Summary -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Savings Summary</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Current Balance</p>
                                <p class="text-2xl font-bold text-primary-600">₦<?= number_format($savings_balance, 2) ?></p>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Monthly Contribution</p>
                                <p class="text-2xl font-bold text-gray-700">₦<?= number_format($monthly_contribution, 2) ?></p>
                                <p class="text-primary-600 text-sm font-medium">
                                    <i class="fas fa-info-circle mr-1"></i> Your current monthly savings deduction
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Transactions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Transactions</h2>
                        <a href="/Coops_Bichi/public/member/savings/statement" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                            View All <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($transactions) && !empty($transactions)): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
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
                                                <?= htmlspecialchars($transaction['description'] ?? 'Monthly Savings') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm <?= ($transaction['transaction_type'] ?? 'deposit') === 'deposit' ? 'text-green-600' : 'text-red-600' ?>">
                                                <?= ($transaction['transaction_type'] ?? 'deposit') === 'deposit' ? '+' : '-' ?>₦<?= number_format($transaction['amount'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    <?= ($transaction['transaction_type'] ?? 'deposit') === 'deposit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= ucfirst($transaction['transaction_type'] ?? 'deposit') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500">
                                <p>No recent transactions found</p>
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
                            <a href="/Coops_Bichi/public/member/savings/withdraw" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Request Withdrawal
                            </a>
                            
                            <a href="#" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium" 
                               onclick="document.getElementById('contact-admin-modal').classList.remove('hidden')">
                                Contact Admin for Contribution Update
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/statement" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                View Statement
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/calculator" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Savings Calculator
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Withdrawal Requests -->
                <?php if (isset($withdrawalRequests) && !empty($withdrawalRequests)): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b px-6 py-4 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Withdrawal Requests</h2>
                            <a href="/Coops_Bichi/public/member/savings/withdrawals" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <?php foreach ($withdrawalRequests as $request): ?>
                                <div class="border rounded-lg overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-2 border-b flex justify-between items-center">
                                        <span class="text-sm font-medium"><?= date('M d, Y', strtotime($request['created_at'])) ?></span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            <?php
                                            switch($request['status']) {
                                                case 'pending':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                case 'approved':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'rejected':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst($request['status']) ?>
                                        </span>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-sm text-gray-600 mb-1">Amount</p>
                                        <p class="font-medium text-gray-800">₦<?= number_format($request['amount'], 2) ?></p>
                                        <div class="mt-3 text-right">
                                            <a href="/Coops_Bichi/public/member/savings/withdrawals/<?= $request['id'] ?>" class="text-sm text-primary-600 hover:text-primary-800">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">About Your Savings</h2>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Our cooperative offers these benefits for your savings:</p>
                        
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Earn attractive annual returns on your savings</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Withdraw funds when you need them</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Automatic monthly deductions from your salary</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                <span>Adjust your monthly contribution as needed</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Contact Modal -->
<div id="contact-admin-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-envelope text-primary-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Contact Administrator
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                To update your monthly savings contribution, please contact the cooperative administrator using the information below:
                            </p>
                            
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie w-5 text-primary-600"></i>
                                    <span class="ml-2 text-gray-700">Administrator</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone-alt w-5 text-primary-600"></i>
                                    <span class="ml-2 text-gray-700">+234 8012345678</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope w-5 text-primary-600"></i>
                                    <span class="ml-2 text-gray-700">admin@fcetbichicoop.org</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-5 text-primary-600"></i>
                                    <span class="ml-2 text-gray-700">Cooperative Office, FCET Bichi Campus</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('contact-admin-modal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div> 