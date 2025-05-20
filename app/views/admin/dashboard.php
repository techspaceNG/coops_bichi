<!-- Dashboard content starts here -->
<div class="container-fluid mx-auto px-6 py-8">
    <!-- Dashboard Header and Quick Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-600 text-sm mt-1">Welcome back, <?php echo isset($admin['name']) ? htmlspecialchars($admin['name']) : 'Admin'; ?></p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            <a href="/Coops_Bichi/public/admin/loans/applications" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                View Applications
            </a>
            <a href="/Coops_Bichi/public/admin/reports" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Reports
            </a>
        </div>
    </div>
    
    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <!-- Total Members Card -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col">
            <div class="text-gray-500 text-sm mb-2">Total Members</div>
            <div class="flex items-center justify-between">
                <div class="text-3xl font-bold text-gray-800">
                    <?= number_format($stats['total_members']) ?>
                </div>
                <div class="bg-blue-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <?php if ($stats['member_growth'] > 0): ?>
                <div class="text-green-500 text-sm mt-3">
                    <i class="fas fa-arrow-up mr-1"></i> <?= number_format($stats['member_growth'], 1) ?>% from last month
                </div>
            <?php elseif ($stats['member_growth'] < 0): ?>
                <div class="text-red-500 text-sm mt-3">
                    <i class="fas fa-arrow-down mr-1"></i> <?= number_format(abs($stats['member_growth']), 1) ?>% from last month
                </div>
            <?php else: ?>
                <div class="text-gray-500 text-sm mt-3">
                    <i class="fas fa-minus mr-1"></i> No change from last month
                </div>
            <?php endif; ?>
            <div class="mt-3">
                <a href="/Coops_Bichi/public/admin/members" class="text-blue-600 text-sm hover:text-blue-800">View all members →</a>
            </div>
        </div>
        
        <!-- Total Savings Card -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col">
            <div class="text-gray-500 text-sm mb-2">Total Savings</div>
            <div class="flex items-center justify-between">
                <div class="text-3xl font-bold text-gray-800 truncate">
                    ₦<?= number_format($stats['total_savings'], 2) ?>
                </div>
                <div class="bg-green-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <?php if ($stats['savings_growth'] > 0): ?>
                <div class="text-green-500 text-sm mt-3">
                    <i class="fas fa-arrow-up mr-1"></i> <?= number_format($stats['savings_growth'], 1) ?>% from last month
                </div>
            <?php elseif ($stats['savings_growth'] < 0): ?>
                <div class="text-red-500 text-sm mt-3">
                    <i class="fas fa-arrow-down mr-1"></i> <?= number_format(abs($stats['savings_growth']), 1) ?>% from last month
                </div>
            <?php else: ?>
                <div class="text-gray-500 text-sm mt-3">
                    <i class="fas fa-minus mr-1"></i> No change from last month
                </div>
            <?php endif; ?>
            <div class="mt-3">
                <a href="/Coops_Bichi/public/admin/savings" class="text-blue-600 text-sm hover:text-blue-800">View all savings →</a>
            </div>
        </div>

        <!-- Total Shares Card -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col">
            <div class="text-gray-500 text-sm mb-2">Total Shares</div>
            <div class="flex items-center justify-between">
                <div class="text-3xl font-bold text-gray-800 truncate">
                    ₦<?= number_format($stats['total_shares'], 2) ?>
                </div>
                <div class="bg-blue-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
            <?php if ($stats['shares_growth'] > 0): ?>
                <div class="text-green-500 text-sm mt-3">
                    <i class="fas fa-arrow-up mr-1"></i> <?= number_format($stats['shares_growth'], 1) ?>% from last month
                </div>
            <?php elseif ($stats['shares_growth'] < 0): ?>
                <div class="text-red-500 text-sm mt-3">
                    <i class="fas fa-arrow-down mr-1"></i> <?= number_format(abs($stats['shares_growth']), 1) ?>% from last month
                </div>
            <?php else: ?>
                <div class="text-gray-500 text-sm mt-3">
                    <i class="fas fa-minus mr-1"></i> No change from last month
                </div>
            <?php endif; ?>
            <div class="mt-3">
                <a href="/Coops_Bichi/public/admin/shares" class="text-blue-600 text-sm hover:text-blue-800">View all shares →</a>
            </div>
        </div>
        
        <!-- Active Loans Card -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col">
            <div class="text-gray-500 text-sm mb-2">Active Loans</div>
            <div class="flex items-center justify-between">
                <div class="text-3xl font-bold text-gray-800">
                    <?= number_format($stats['active_loans']) ?>
                </div>
                <div class="bg-yellow-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-gray-600 text-sm">Outstanding balance</div>
                <div class="text-xl font-semibold text-gray-800 truncate">
                    ₦<?= number_format($stats['total_loan_balance'], 2) ?>
                </div>
            </div>
            <div class="mt-3">
                <a href="/Coops_Bichi/public/admin/loans" class="text-blue-600 text-sm hover:text-blue-800">View all loans →</a>
            </div>
        </div>

        <!-- Household Purchases Card -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex flex-col">
            <div class="text-gray-500 text-sm mb-2">Household Purchases</div>
            <div class="flex items-center justify-between">
                <div class="text-3xl font-bold text-gray-800">
                    <?= number_format($stats['active_purchases']) ?>
                </div>
                <div class="bg-purple-50 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-gray-600 text-sm">Outstanding balance</div>
                <div class="text-xl font-semibold text-gray-800 truncate">
                    ₦<?= number_format($stats['total_purchase_balance'], 2) ?>
                </div>
            </div>
            <div class="mt-3">
                <a href="/Coops_Bichi/public/admin/household" class="text-blue-600 text-sm hover:text-blue-800">View all purchases →</a>
            </div>
        </div>
    </div>

    <!-- Cooperative Overview -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Cooperative Overview</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="text-blue-600 text-2xl font-bold mb-1">
                    <?= number_format($stats['total_members']) ?>
                </div>
                <div class="text-gray-500 text-sm">Total Members</div>
            </div>
            
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="text-green-600 text-2xl font-bold mb-1 truncate">
                    ₦<?= number_format($stats['total_savings'], 2) ?>
                </div>
                <div class="text-gray-500 text-sm">Total Savings</div>
            </div>
            
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="text-blue-600 text-2xl font-bold mb-1 truncate">
                    ₦<?= number_format($stats['total_shares'], 2) ?>
                </div>
                <div class="text-gray-500 text-sm">Total Shares</div>
            </div>
            
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="text-amber-600 text-2xl font-bold mb-1 truncate">
                    ₦<?= number_format($stats['total_loan_balance'], 2) ?>
                </div>
                <div class="text-gray-500 text-sm">Loan Balance</div>
            </div>
            
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="text-purple-600 text-2xl font-bold mb-1 truncate">
                    ₦<?= number_format($stats['total_purchase_balance'], 2) ?>
                </div>
                <div class="text-gray-500 text-sm">Household Balance</div>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Savings Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Monthly Savings Trend</h3>
                <div class="flex items-center">
                    <button id="savingsChartDownload" class="text-blue-600 hover:text-blue-800 mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </button>
                    <button id="savingsChartInfo" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="savingsChart"></canvas>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p>Total monthly savings contributions over the last 12 months.</p>
            </div>
        </div>
        
        <!-- Loan vs Savings Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Financial Comparison by Department</h3>
                <div class="flex items-center">
                    <button id="loanSavingsChartDownload" class="text-blue-600 hover:text-blue-800 mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </button>
                    <button id="loanSavingsChartInfo" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="loanSavingsChart"></canvas>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p>Comparison of savings, shares, loans, and household purchases by department.</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Pending Approvals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                <div class="flex items-center">
                    <select id="activityFilter" class="text-sm border-gray-300 rounded-md mr-2">
                        <option value="all">All Activities</option>
                        <option value="member">Members</option>
                        <option value="loan">Loans</option>
                        <option value="saving">Savings</option>
                        <option value="household">Household</option>
                    </select>
                    <a href="/Coops_Bichi/public/admin/audit-logs" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        View All
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($recentActivity)): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">No recent activity found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentActivity as $activity): ?>
                            <tr class="hover:bg-gray-50 activity-row" data-type="<?php echo strtolower($activity['action']); ?>">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $activity['action']; ?></div>
                                    <div class="text-xs text-gray-500"><?php echo $activity['details']; ?></div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo $activity['user_name']; ?></div>
                                    <div class="text-xs text-gray-500 px-2 py-0.5 rounded-full inline-block
                                        <?php echo $activity['user_type'] === 'Admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                        <?php echo $activity['user_type']; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('M j, Y g:i A', strtotime($activity['timestamp'])); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pending Approvals -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pending Approvals</h3>
                <div class="flex items-center">
                    <select id="approvalFilter" class="text-sm border-gray-300 rounded-md">
                        <option value="all">All Applications</option>
                        <option value="loans">Loans</option>
                        <option value="household">Household</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Pending Loans -->
                <div id="pendingLoansSection" class="approval-section">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-md font-medium text-gray-700">Loan Applications (<?php echo count($pendingLoans); ?>)</h4>
                        <?php if (!empty($pendingLoans)): ?>
                            <a href="/Coops_Bichi/public/admin/loans/applications" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                View All
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($pendingLoans)): ?>
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center text-gray-500">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm">No pending loan applications</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto bg-gray-50 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-50 divide-y divide-gray-200">
                                    <?php foreach ($pendingLoans as $loan): ?>
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900"><?php echo $loan['member_name']; ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">₦<?php echo number_format($loan['loan_amount'], 2); ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($loan['application_date'])); ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <a href="/Coops_Bichi/public/admin/loans/view/<?php echo $loan['id']; ?>" class="text-blue-600 hover:text-blue-800 flex items-center">
                                                View
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pending Household Purchases -->
                <div id="pendingPurchasesSection" class="approval-section">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-md font-medium text-gray-700">Household Purchase Applications (<?php echo count($pendingPurchases); ?>)</h4>
                        <?php if (!empty($pendingPurchases)): ?>
                            <a href="/Coops_Bichi/public/admin/household/applications" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                View All
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($pendingPurchases)): ?>
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center text-gray-500">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm">No pending household purchase applications</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto bg-gray-50 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-50 divide-y divide-gray-200">
                                    <?php foreach ($pendingPurchases as $purchase): ?>
                                    <tr class="hover:bg-white">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900"><?php echo $purchase['member_name']; ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">₦<?php echo number_format($purchase['purchase_amount'], 2); ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($purchase['application_date'])); ?></td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <a href="/Coops_Bichi/public/admin/household/view/<?php echo $purchase['id']; ?>" class="text-blue-600 hover:text-blue-800 flex items-center">
                                                View
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
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
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Savings Chart
    const savingsCtx = document.getElementById('savingsChart').getContext('2d');
    const savingsChart = new Chart(savingsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($charts['savings']['labels']); ?>,
            datasets: [{
                label: 'Monthly Savings (₦)',
                data: <?php echo json_encode($charts['savings']['data']); ?>,
                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 2,
                tension: 0.4,
                pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₦' + value.toLocaleString();
                        }
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(200, 200, 200, 0.2)',
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Total Savings: ₦' + context.raw.toLocaleString();
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
    
    // Loan vs Savings Chart
    const loanSavingsCtx = document.getElementById('loanSavingsChart').getContext('2d');
    const loanSavingsChart = new Chart(loanSavingsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($charts['loanVsSavings']['labels']); ?>,
            datasets: [
                {
                    label: 'Savings (₦)',
                    data: <?php echo json_encode($charts['loanVsSavings']['savings']); ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderWidth: 0,
                    borderRadius: 4
                },
                {
                    label: 'Shares (₦)',
                    data: <?php echo json_encode($charts['loanVsSavings']['shares']); ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderWidth: 0,
                    borderRadius: 4
                },
                {
                    label: 'Loans (₦)',
                    data: <?php echo json_encode($charts['loanVsSavings']['loans']); ?>,
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderWidth: 0,
                    borderRadius: 4
                },
                {
                    label: 'Household Purchases (₦)',
                    data: <?php echo json_encode($charts['loanVsSavings']['purchases']); ?>,
                    backgroundColor: 'rgba(139, 92, 246, 0.7)',
                    borderWidth: 0,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₦' + value.toLocaleString();
                        }
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(200, 200, 200, 0.2)',
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₦' + context.raw.toLocaleString();
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Chart download functionality
    document.getElementById('savingsChartDownload').addEventListener('click', function() {
        const link = document.createElement('a');
        link.download = 'monthly_savings_trend.png';
        link.href = savingsChart.toBase64Image();
        link.click();
    });

    document.getElementById('loanSavingsChartDownload').addEventListener('click', function() {
        const link = document.createElement('a');
        link.download = 'loans_vs_savings.png';
        link.href = loanSavingsChart.toBase64Image();
        link.click();
    });

    // Chart info tooltips
    const tooltips = {
        'savingsChartInfo': 'Shows the total monthly savings contributions over the last 12 months.',
        'loanSavingsChartInfo': 'Compares savings, shares, loans, and household purchases by department.'
    };

    for (const id in tooltips) {
        const element = document.getElementById(id);
        if (element) {
            element.setAttribute('title', tooltips[id]);
            element.setAttribute('data-tooltip', tooltips[id]);
        }
    }

    // Filter functionality for activity log
    const activityFilter = document.getElementById('activityFilter');
    if (activityFilter) {
        activityFilter.addEventListener('change', function() {
            const selectedValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('.activity-row');
            
            rows.forEach(row => {
                const type = row.getAttribute('data-type').toLowerCase();
                if (selectedValue === 'all' || type.includes(selectedValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Filter functionality for approvals
    const approvalFilter = document.getElementById('approvalFilter');
    if (approvalFilter) {
        approvalFilter.addEventListener('change', function() {
            const selectedValue = this.value.toLowerCase();
            const sections = document.querySelectorAll('.approval-section');
            
            sections.forEach(section => {
                const sectionId = section.getAttribute('id').toLowerCase();
                if (selectedValue === 'all' || sectionId.includes(selectedValue)) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    }
    
    // Add animation to cards
    const cards = document.querySelectorAll('.bg-white.rounded-lg');
    cards.forEach(card => {
        card.classList.add('transition-all', 'duration-300');
    });

    // Responsive adjustments
    function handleResponsiveChanges() {
        if (window.innerWidth < 768) {
            // Adjust for mobile views
            savingsChart.options.plugins.legend.display = false;
            loanSavingsChart.options.plugins.legend.display = false;
        } else {
            // Adjust for desktop views
            savingsChart.options.plugins.legend.display = true;
            loanSavingsChart.options.plugins.legend.display = true;
        }
        savingsChart.update();
        loanSavingsChart.update();
    }

    // Initial call and window resize event
    handleResponsiveChanges();
    window.addEventListener('resize', handleResponsiveChanges);
});
</script> 