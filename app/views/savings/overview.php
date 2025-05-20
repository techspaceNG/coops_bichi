<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Savings</h1>
                <p class="text-primary-100">Track and manage your cooperative society savings</p>
            </div>
        </div>
        
        <!-- Savings Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-coins text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-blue-800">Monthly Contribution</h2>
                            <p class="text-2xl font-bold text-blue-900">₦<?= number_format($member['monthly_deduction'], 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Your regular monthly contribution amount deducted from your salary.</p>
                    <?php if ($canUpdateDeduction): ?>
                    <div class="mt-3">
                        <a href="/savings/update" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-edit mr-1"></i> Update Monthly Amount
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-green-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-wallet text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-green-800">Current Savings Balance</h2>
                            <p class="text-2xl font-bold text-green-900">₦<?= number_format($member['savings_balance'], 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Your total savings balance as of <?= date('F d, Y') ?></p>
                    <div class="mt-3">
                        <a href="/savings/statement" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-file-alt mr-1"></i> View Full Statement
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-purple-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-chart-line text-purple-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-purple-800">Cumulative Savings</h2>
                            <p class="text-2xl font-bold text-purple-900">₦<?= number_format($member['cumulative_savings'], 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Total amount saved since joining the cooperative society.</p>
                    <div class="mt-3">
                        <a href="/savings/analytics" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-chart-bar mr-1"></i> View Savings Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Savings History -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Recent Savings Contributions</h2>
                <a href="/savings/statement" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                    View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            
            <div class="p-6">
                <?php if (empty($contributions)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No contribution history yet</h3>
                        <p class="text-gray-500">Your monthly contributions will appear here once they start.</p>
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
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Balance After
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($contributions as $contribution): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($contribution['date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($contribution['description']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-700">
                                            +₦<?= number_format($contribution['amount'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₦<?= number_format($contribution['balance_after'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Savings Information & Tips -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Savings Information</h2>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-medium text-gray-800">About Your Savings</h3>
                            <p class="text-gray-600 mt-1">Your monthly contributions are automatically deducted from your salary at source. These savings form the basis of your membership in the cooperative society.</p>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-800">Withdrawal Policy</h3>
                            <p class="text-gray-600 mt-1">Members may request withdrawal of savings subject to the following conditions:</p>
                            <ul class="list-disc list-inside text-gray-600 mt-1 space-y-1">
                                <li>No active loans outstanding</li>
                                <li>Maximum of 50% of total savings can be withdrawn at once</li>
                                <li>Minimum of 3 months between withdrawal requests</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-800">Annual Dividends</h3>
                            <p class="text-gray-600 mt-1">At the end of each financial year, dividends are paid to members based on their savings contribution and the society's performance.</p>
                        </div>
                    </div>
                    
                    <?php if ($canRequestWithdrawal): ?>
                    <div class="mt-6">
                        <a href="/savings/withdraw" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Request Withdrawal
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Savings Analytics</h2>
                </div>
                
                <div class="p-6">
                    <?php if (empty($savingsData)): ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500">Not enough data to display savings analytics.</p>
                        </div>
                    <?php else: ?>
                        <div class="h-64">
                            <canvas id="savingsChart"></canvas>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-blue-800 font-medium">Average Monthly Contribution</p>
                                <p class="text-xl font-bold text-blue-900 mt-1">₦<?= number_format($averageContribution, 2) ?></p>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-green-800 font-medium">Growth This Year</p>
                                <p class="text-xl font-bold text-green-900 mt-1"><?= number_format($growthPercentage, 1) ?>%</p>
                            </div>
                        </div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const ctx = document.getElementById('savingsChart').getContext('2d');
                                const savingsChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: <?= json_encode(array_map(function($item) { 
                                            return date('M Y', strtotime($item['month'])); 
                                        }, $savingsData)) ?>,
                                        datasets: [{
                                            label: 'Savings Balance',
                                            data: <?= json_encode(array_map(function($item) { 
                                                return $item['balance']; 
                                            }, $savingsData)) ?>,
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            borderColor: 'rgba(59, 130, 246, 1)',
                                            borderWidth: 2,
                                            tension: 0.1,
                                            fill: true
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        return '₦' + value.toLocaleString();
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return 'Balance: ₦' + context.raw.toLocaleString();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    <?php endif; ?>
                    
                    <div class="mt-6">
                        <a href="/savings/analytics" class="text-primary-600 hover:text-primary-800 font-medium text-sm">
                            View Detailed Analytics <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 