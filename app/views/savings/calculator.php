<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Savings Calculator</h1>
                        <p class="text-primary-100">Plan and estimate your future savings with our calculator</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="/savings/overview" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Savings
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Calculator Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Savings Projection Calculator</h2>
                    </div>
                    
                    <div class="p-6">
                        <form id="calculatorForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_balance" class="block text-sm font-medium text-gray-700 mb-1">Current Savings Balance (₦)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">₦</span>
                                        </div>
                                        <input type="number" id="current_balance" name="current_balance" value="<?= $member['savings_balance'] ?? 0 ?>" 
                                               class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="monthly_contribution" class="block text-sm font-medium text-gray-700 mb-1">Monthly Contribution (₦)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">₦</span>
                                        </div>
                                        <input type="number" id="monthly_contribution" name="monthly_contribution" value="<?= $member['monthly_deduction'] ?? 5000 ?>" min="5000" step="500" 
                                               class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="annual_interest_rate" class="block text-sm font-medium text-gray-700 mb-1">Annual Dividend Rate (%)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="annual_interest_rate" name="annual_interest_rate" value="5" min="0" max="20" step="0.1"
                                               class="focus:ring-primary-500 focus:border-primary-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Estimated based on historical dividend rates</p>
                                </div>
                                
                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Projection Period (Months)</label>
                                    <select id="duration" name="duration"
                                           class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="12">1 Year (12 Months)</option>
                                        <option value="24">2 Years (24 Months)</option>
                                        <option value="36">3 Years (36 Months)</option>
                                        <option value="60" selected>5 Years (60 Months)</option>
                                        <option value="120">10 Years (120 Months)</option>
                                        <option value="240">20 Years (240 Months)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label for="additional_contributions" class="block text-sm font-medium text-gray-700 mb-1">Additional One-time Contributions</label>
                                <div id="additional_contributions_container">
                                    <!-- Additional contributions will be added here dynamically -->
                                </div>
                                <div class="mt-2">
                                    <button type="button" id="add_contribution" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-plus mr-1"></i> Add One-time Contribution
                                    </button>
                                </div>
                            </div>
                            
                            <div class="pt-4 flex justify-center">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-calculator mr-2"></i> Calculate Projection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Information and Tips -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Savings Tips</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-800">Increase Your Savings</h3>
                                <ul class="mt-2 text-gray-600 list-disc list-inside space-y-1">
                                    <li>Consistently contribute to your savings each month</li>
                                    <li>Increase your monthly contribution when possible</li>
                                    <li>Make additional one-time contributions when you receive bonuses or windfalls</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Maximize Returns</h3>
                                <ul class="mt-2 text-gray-600 list-disc list-inside space-y-1">
                                    <li>Higher savings balance leads to higher dividends</li>
                                    <li>Long-term saving benefits from compound growth</li>
                                    <li>Avoid unnecessary withdrawals to allow your savings to grow</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Plan for the Future</h3>
                                <ul class="mt-2 text-gray-600 list-disc list-inside space-y-1">
                                    <li>Set specific savings goals and timeframes</li>
                                    <li>Regularly review and adjust your savings strategy</li>
                                    <li>Consider how your savings can support your long-term financial objectives</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-primary-50 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-info-circle text-primary-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-medium text-primary-800">About the Calculator</h3>
                                <p class="mt-1 text-primary-700">This calculator provides an estimate based on your inputs. Actual results may vary based on dividend rates, contribution changes, and other factors. The calculator assumes dividends are calculated annually and reinvested.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results Section (initially hidden) -->
        <div id="results_section" class="mt-6 bg-white rounded-lg shadow-md overflow-hidden hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Savings Projection Results</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-green-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-green-800">Projected Final Balance</h3>
                        <p class="text-2xl font-bold text-green-900 mt-2" id="final_balance">₦0.00</p>
                        <p class="text-xs text-green-700 mt-1" id="projection_period">After 0 months</p>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-blue-800">Total Contributions</h3>
                        <p class="text-2xl font-bold text-blue-900 mt-2" id="total_contributions">₦0.00</p>
                        <p class="text-xs text-blue-700 mt-1">Total amount deposited</p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-6">
                        <h3 class="text-sm font-medium text-purple-800">Dividend Earnings</h3>
                        <p class="text-2xl font-bold text-purple-900 mt-2" id="total_dividends">₦0.00</p>
                        <p class="text-xs text-purple-700 mt-1">Total dividends earned</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Projected Growth Chart</h3>
                    <div class="bg-gray-50 rounded-lg p-4" style="height: 300px;">
                        <canvas id="savingsChart"></canvas>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <h3 class="text-md font-medium text-gray-800 mb-3">Detailed Projection Table</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regular Contribution</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Additional Contribution</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dividend Earned</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            </tr>
                        </thead>
                        <tbody id="projection_table" class="bg-white divide-y divide-gray-200">
                            <!-- Projection data will be inserted here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Want to increase your savings?</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Consider increasing your monthly contribution to achieve your financial goals faster. You can update your monthly contribution amount through the <a href="/savings/update" class="font-medium text-yellow-800 underline">Update Monthly Contribution</a> page.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const calculatorForm = document.getElementById('calculatorForm');
    const addContributionBtn = document.getElementById('add_contribution');
    const additionalContributionsContainer = document.getElementById('additional_contributions_container');
    const resultsSection = document.getElementById('results_section');
    
    // Track additional contributions
    let contributionCounter = 0;
    
    // Add one-time contribution form fields
    addContributionBtn.addEventListener('click', function() {
        contributionCounter++;
        const contributionHtml = `
            <div class="grid grid-cols-12 gap-2 mt-2 additional-contribution">
                <div class="col-span-5">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₦</span>
                        </div>
                        <input type="number" name="amount_${contributionCounter}" placeholder="Amount" min="1000" step="1000"
                               class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="month_${contributionCounter}" placeholder="Month" min="1" max="240"
                               class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="col-span-2 flex items-center">
                    <button type="button" class="remove-contribution text-red-600 hover:text-red-800">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            </div>
        `;
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = contributionHtml;
        const newContribution = tempDiv.firstElementChild;
        
        // Add event listener to remove button
        newContribution.querySelector('.remove-contribution').addEventListener('click', function() {
            additionalContributionsContainer.removeChild(newContribution);
        });
        
        additionalContributionsContainer.appendChild(newContribution);
    });
    
    // Calculate savings projection
    calculatorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const currentBalance = parseFloat(document.getElementById('current_balance').value) || 0;
        const monthlyContribution = parseFloat(document.getElementById('monthly_contribution').value) || 0;
        const annualInterestRate = parseFloat(document.getElementById('annual_interest_rate').value) || 0;
        const monthlyInterestRate = annualInterestRate / 12 / 100;
        const duration = parseInt(document.getElementById('duration').value) || 60;
        
        // Get additional contributions
        const additionalContributions = [];
        document.querySelectorAll('.additional-contribution').forEach(function(elem) {
            const amount = parseFloat(elem.querySelector('input[name^="amount_"]').value) || 0;
            const month = parseInt(elem.querySelector('input[name^="month_"]').value) || 0;
            
            if (amount > 0 && month > 0 && month <= duration) {
                additionalContributions.push({ amount, month });
            }
        });
        
        // Sort additional contributions by month
        additionalContributions.sort((a, b) => a.month - b.month);
        
        // Calculate projection
        let balance = currentBalance;
        let totalContributions = currentBalance;
        let totalDividends = 0;
        let monthlyData = [];
        
        for (let month = 1; month <= duration; month++) {
            // Add monthly contribution
            balance += monthlyContribution;
            totalContributions += monthlyContribution;
            
            // Add any one-time contributions for this month
            let additionalContribution = 0;
            additionalContributions.forEach(function(contribution) {
                if (contribution.month === month) {
                    balance += contribution.amount;
                    totalContributions += contribution.amount;
                    additionalContribution += contribution.amount;
                }
            });
            
            // Calculate interest for this month
            let dividend = balance * monthlyInterestRate;
            balance += dividend;
            totalDividends += dividend;
            
            // Add data for this month
            monthlyData.push({
                month,
                regularContribution: monthlyContribution,
                additionalContribution,
                dividend,
                balance
            });
        }
        
        // Display results
        document.getElementById('final_balance').textContent = '₦' + numberWithCommas(balance.toFixed(2));
        document.getElementById('projection_period').textContent = `After ${duration} months (${(duration / 12).toFixed(1)} years)`;
        document.getElementById('total_contributions').textContent = '₦' + numberWithCommas(totalContributions.toFixed(2));
        document.getElementById('total_dividends').textContent = '₦' + numberWithCommas(totalDividends.toFixed(2));
        
        // Generate table
        generateProjectionTable(monthlyData);
        
        // Generate chart
        generateChart(monthlyData);
        
        // Show results section
        resultsSection.classList.remove('hidden');
        
        // Scroll to results
        resultsSection.scrollIntoView({ behavior: 'smooth' });
    });
    
    // Generate projection table
    function generateProjectionTable(data) {
        const tableBody = document.getElementById('projection_table');
        tableBody.innerHTML = '';
        
        // Determine if we need to show all months or a summary
        let filteredData = data;
        if (data.length > 60) {
            // For long projections, show annually instead of monthly
            filteredData = data.filter((item, index) => (index + 1) % 12 === 0 || index === 0 || index === data.length - 1);
        }
        
        filteredData.forEach(function(item) {
            const row = document.createElement('tr');
            
            // Period
            const periodCell = document.createElement('td');
            periodCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
            if (item.month % 12 === 0) {
                periodCell.textContent = `Year ${item.month / 12}`;
            } else {
                periodCell.textContent = `Month ${item.month}`;
            }
            row.appendChild(periodCell);
            
            // Regular Contribution
            const regularCell = document.createElement('td');
            regularCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
            regularCell.textContent = '₦' + numberWithCommas(item.regularContribution.toFixed(2));
            row.appendChild(regularCell);
            
            // Additional Contribution
            const additionalCell = document.createElement('td');
            additionalCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
            additionalCell.textContent = item.additionalContribution > 0 ? 
                '₦' + numberWithCommas(item.additionalContribution.toFixed(2)) : '-';
            row.appendChild(additionalCell);
            
            // Dividend
            const dividendCell = document.createElement('td');
            dividendCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
            dividendCell.textContent = '₦' + numberWithCommas(item.dividend.toFixed(2));
            row.appendChild(dividendCell);
            
            // Balance
            const balanceCell = document.createElement('td');
            balanceCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900';
            balanceCell.textContent = '₦' + numberWithCommas(item.balance.toFixed(2));
            row.appendChild(balanceCell);
            
            tableBody.appendChild(row);
        });
    }
    
    // Generate chart
    function generateChart(data) {
        const ctx = document.getElementById('savingsChart').getContext('2d');
        
        // Determine if we need to show all months or a summary
        let chartLabels;
        let chartData;
        
        if (data.length > 60) {
            // For long projections, show annually
            chartLabels = data
                .filter((item, index) => (index + 1) % 12 === 0 || index === 0 || index === data.length - 1)
                .map(item => item.month % 12 === 0 ? `Year ${item.month / 12}` : `Month ${item.month}`);
                
            chartData = data
                .filter((item, index) => (index + 1) % 12 === 0 || index === 0 || index === data.length - 1)
                .map(item => item.balance);
        } else {
            // Show monthly data
            chartLabels = data.map(item => `Month ${item.month}`);
            chartData = data.map(item => item.balance);
        }
        
        // Check if we have an existing chart and destroy it
        if (window.savingsChart instanceof Chart) {
            window.savingsChart.destroy();
        }
        
        // Create new chart
        window.savingsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Balance',
                    data: chartData,
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.3,
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
                                return '₦' + numberWithCommas(value.toFixed(0));
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '₦' + numberWithCommas(context.parsed.y.toFixed(2));
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Format numbers with commas
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 