<?php
// Member Loan Summary Page

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_loans');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Loans</h1>
                <p class="text-primary-100">Manage your loans with FCET Bichi Staff Multipurpose Cooperative Society</p>
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
                <!-- Current Loan Status -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Current Loan Status</h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($loan) && $loan): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-gray-600 text-sm mb-1">Loan Amount</h3>
                                    <p class="text-2xl font-semibold text-gray-900">₦<?= number_format($loan['loan_amount'], 2) ?></p>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-gray-600 text-sm mb-1">Current Balance</h3>
                                    <p class="text-2xl font-semibold text-gray-900">₦<?= number_format($loan['balance'], 2) ?></p>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-gray-600 text-sm mb-1">Monthly Repayment</h3>
                                    <p class="text-2xl font-semibold text-gray-900">₦<?= number_format($loan['ip_figure'], 2) ?></p>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-gray-600 text-sm mb-1">Status</h3>
                                    <p class="text-2xl font-semibold text-gray-900"><?= ucfirst(htmlspecialchars($loan['status'])) ?></p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-gray-600">Repayment Progress</h3>
                                    <span class="text-sm text-gray-500">
                                        <?= round(100 - (($loan['balance'] / $loan['total_repayment']) * 100)) ?>% Completed
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: <?= round(100 - (($loan['balance'] / $loan['total_repayment']) * 100)) ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-gray-600 text-sm mb-1">Start Date</h3>
                                    <p class="font-medium"><?= date('d M, Y', strtotime($loan['start_date'])) ?></p>
                                </div>
                                
                                <div>
                                    <h3 class="text-gray-600 text-sm mb-1">End Date</h3>
                                    <p class="font-medium"><?= date('d M, Y', strtotime($loan['end_date'])) ?></p>
                                </div>
                                
                                <div>
                                    <h3 class="text-gray-600 text-sm mb-1">Admin Charges</h3>
                                    <p class="font-medium"><?= number_format($loan['interest_rate'], 1) ?>%</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-gray-600 text-sm mb-1">Repayment Period</h3>
                                    <p class="font-medium"><?= htmlspecialchars($loan['repayment_period']) ?> Months</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="bg-blue-50 text-blue-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You don't have any active loans</p>
                                        <p class="mt-1">Apply for a loan by clicking the button below.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <a href="/Coops_Bichi/public/member/loans/apply" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                    <i class="fas fa-plus-circle mr-2"></i> Apply for Loan
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Transactions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Transactions</h2>
                        
                        <?php if (isset($transactions) && count($transactions) > 0): ?>
                            <a href="/Coops_Bichi/public/member/loans/transactions" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($transactions) && count($transactions) > 0): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('d M, Y', strtotime($transaction['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <?= htmlspecialchars($transaction['description']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                                <?= strpos(strtolower($transaction['description']), 'repayment') !== false ? 'text-green-600' : 'text-red-600' ?>">
                                                <?= strpos(strtolower($transaction['description']), 'repayment') !== false ? '-' : '+' ?>₦<?= number_format($transaction['amount'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-history text-4xl mb-3 text-gray-300"></i>
                                <p>No transaction history available</p>
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
                            <a href="/Coops_Bichi/public/member/loans/apply" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Apply for Loan
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/loans/applications" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                View Loan Applications
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/loans/calculator" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Loan Calculator
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Loan Summary -->
                <?php if (isset($loan) && $loan): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Loan Summary</h2>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Initial Loan Amount</span>
                                <span class="font-medium">₦<?= number_format($loan['loan_amount'], 2) ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Admin Charges</span>
                                <span class="font-medium">₦<?= number_format($loan['total_repayment'] - $loan['loan_amount'], 2) ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Total Repayment Amount</span>
                                <span class="font-medium">₦<?= number_format($loan['total_repayment'], 2) ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Amount Paid</span>
                                <span class="font-medium text-green-600">₦<?= number_format($loan['total_repayment'] - $loan['balance'], 2) ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Remaining Balance</span>
                                <span class="font-medium text-red-600">₦<?= number_format($loan['balance'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Loan Information</h2>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Our cooperative offers affordable loans to members with the following features:</p>
                            
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                    <span>5% admin charges on all loans</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                    <span>Flexible repayment periods up to 24 months</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                    <span>Quick approval process</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-primary-600 mt-1 mr-2"></i>
                                    <span>No hidden fees or charges</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 