<?php
// Member Savings Calculator View

$flashMessage = \App\Helpers\Utility::getFlashMessage();
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Savings Calculator</h1>
                <p class="text-primary-100">Plan your future savings with our interactive calculator</p>
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
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Calculate Your Savings Growth</h2>
                    </div>
                    
                    <div class="p-6">
                        <form id="savingsCalculatorForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="startingBalance" class="block text-sm font-medium text-gray-700 mb-1">Starting Balance (₦)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">₦</span>
                                        </div>
                                        <input type="number" id="startingBalance" name="startingBalance" min="0" step="1000" 
                                            value="<?= htmlspecialchars($userSavings ?? 0) ?>"
                                            class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                            placeholder="0">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="monthlyContribution" class="block text-sm font-medium text-gray-700 mb-1">Monthly Contribution (₦)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">₦</span>
                                        </div>
                                        <input type="number" id="monthlyContribution" name="monthlyContribution" min="1000" step="500" 
                                            value="<?= htmlspecialchars($monthlySavings ?? 5000) ?>"
                                            class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                            placeholder="5,000">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="interestRate" class="block text-sm font-medium text-gray-700 mb-1">Annual Admin Charges (%)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" id="interestRate" name="interestRate" min="0" max="30" step="0.1" value="5" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                            placeholder="5">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="timeYears" class="block text-sm font-medium text-gray-700 mb-1">Time Period (Years)</label>
                                    <select id="timeYears" name="timeYears" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <?php for ($i = 1; $i <= 30; $i++): ?>
                                            <option value="<?= $i ?>" <?= ($i == 5) ? 'selected' : '' ?>><?= $i ?> <?= ($i == 1) ? 'Year' : 'Years' ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="compoundFrequency" class="block text-sm font-medium text-gray-700 mb-1">Compound Frequency</label>
                                    <div class="mt-1 grid grid-cols-4 gap-3">
                                        <div>
                                            <input type="radio" id="compoundMonthly" name="compoundFrequency" value="monthly" checked
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                            <label for="compoundMonthly" class="ml-2 block text-sm text-gray-700">Monthly</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="compoundQuarterly" name="compoundFrequency" value="quarterly"
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                            <label for="compoundQuarterly" class="ml-2 block text-sm text-gray-700">Quarterly</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="compoundSemiAnnually" name="compoundFrequency" value="semi-annually"
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                            <label for="compoundSemiAnnually" class="ml-2 block text-sm text-gray-700">Semi-Annually</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="compoundAnnually" name="compoundFrequency" value="annually"
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                            <label for="compoundAnnually" class="ml-2 block text-sm text-gray-700">Annually</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-4 flex flex-col sm:flex-row sm:justify-end gap-3">
                                <button type="button" id="calculateBtn" class="inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Calculate Savings
                                </button>
                                <button type="reset" class="inline-flex justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Results Section -->
                <div id="resultsSection" class="bg-white rounded-lg shadow-md overflow-hidden mb-6 hidden">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Savings Calculation Results</h2>
                        <button id="printResults" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-print mr-2"></i> Print
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Savings After <span id="resultYears">5</span> Years</h3>
                                <p class="text-2xl font-bold text-primary-600">₦<span id="totalSavings">0</span></p>
                                <div class="mt-1 text-sm text-gray-500 flex items-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i> <span id="totalGrowth">0</span>%
                                    </span>
                                    <span class="ml-2">growth from initial deposit</span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Breakdown</h3>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Initial Deposit</p>
                                        <p class="text-base font-medium text-gray-900">₦<span id="resultInitial">0</span></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Total Contributions</p>
                                        <p class="text-base font-medium text-gray-900">₦<span id="totalContributions">0</span></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Interest Earned</p>
                                        <p class="text-base font-medium text-green-600">₦<span id="totalInterest">0</span></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Monthly Average</p>
                                        <p class="text-base font-medium text-gray-900">₦<span id="monthlyAverage">0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6">
                            <h3 class="text-base font-medium text-gray-800 mb-4">Year-by-Year Growth</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Starting Balance</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contributions</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interest Earned</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ending Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="yearlyTable" class="bg-white divide-y divide-gray-200">
                                        <!-- Yearly data will be populated here by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-6 text-xs text-gray-500 bg-blue-50 p-3 rounded-md">
                            <p><strong>Note:</strong> This calculator provides estimates based on the information you provide. Actual results may vary based on the cooperative's dividend rates and policies.</p>
                        </div>
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
                            <a href="/Coops_Bichi/public/member/savings" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Savings Dashboard
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/statement" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                View Savings Statement
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculateBtn = document.getElementById('calculateBtn');
    const resultsSection = document.getElementById('resultsSection');
    const printResults = document.getElementById('printResults');
    
    calculateBtn.addEventListener('click', function() {
        // Get input values
        const startingBalance = parseFloat(document.getElementById('startingBalance').value) || 0;
        const monthlyContribution = parseFloat(document.getElementById('monthlyContribution').value) || 0;
        const interestRate = parseFloat(document.getElementById('interestRate').value) || 0;
        const years = parseInt(document.getElementById('timeYears').value) || 5;
        
        let compoundsPerYear;
        const compoundFrequency = document.querySelector('input[name="compoundFrequency"]:checked').value;
        switch(compoundFrequency) {
            case 'monthly':
                compoundsPerYear = 12;
                break;
            case 'quarterly':
                compoundsPerYear = 4;
                break;
            case 'semi-annually':
                compoundsPerYear = 2;
                break;
            case 'annually':
                compoundsPerYear = 1;
                break;
            default:
                compoundsPerYear = 12;
        }
        
        // Calculate savings
        const periodicRate = interestRate / 100 / compoundsPerYear;
        let balance = startingBalance;
        const yearlyResults = [];
        
        for (let year = 1; year <= years; year++) {
            const yearStartBalance = balance;
            let yearInterest = 0;
            const yearContributions = monthlyContribution * 12;
            
            for (let period = 1; period <= compoundsPerYear; period++) {
                const monthsInPeriod = 12 / compoundsPerYear;
                const contributionInPeriod = monthlyContribution * monthsInPeriod;
                
                // Add contribution for this period
                balance += contributionInPeriod;
                
                // Calculate interest for this period
                const periodInterest = balance * periodicRate;
                yearInterest += periodInterest;
                
                // Add interest to balance
                balance += periodInterest;
            }
            
            yearlyResults.push({
                year: year,
                startBalance: yearStartBalance,
                contributions: yearContributions,
                interest: yearInterest,
                endBalance: balance
            });
        }
        
        // Update result fields
        document.getElementById('resultYears').textContent = years;
        document.getElementById('totalSavings').textContent = formatCurrency(balance);
        
        const totalContributions = monthlyContribution * 12 * years;
        document.getElementById('totalContributions').textContent = formatCurrency(totalContributions);
        
        const totalInterest = balance - startingBalance - totalContributions;
        document.getElementById('totalInterest').textContent = formatCurrency(totalInterest);
        
        document.getElementById('resultInitial').textContent = formatCurrency(startingBalance);
        
        const growth = startingBalance > 0 ? ((balance - startingBalance) / startingBalance * 100).toFixed(2) : 0;
        document.getElementById('totalGrowth').textContent = growth;
        
        const monthlyAverage = balance / (years * 12);
        document.getElementById('monthlyAverage').textContent = formatCurrency(monthlyAverage);
        
        // Create yearly table
        const yearlyTable = document.getElementById('yearlyTable');
        yearlyTable.innerHTML = '';
        
        yearlyResults.forEach(result => {
            const row = document.createElement('tr');
            
            const yearCell = document.createElement('td');
            yearCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
            yearCell.textContent = result.year;
            row.appendChild(yearCell);
            
            const startBalanceCell = document.createElement('td');
            startBalanceCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            startBalanceCell.textContent = '₦' + formatCurrency(result.startBalance);
            row.appendChild(startBalanceCell);
            
            const contributionsCell = document.createElement('td');
            contributionsCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            contributionsCell.textContent = '₦' + formatCurrency(result.contributions);
            row.appendChild(contributionsCell);
            
            const interestCell = document.createElement('td');
            interestCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-green-600';
            interestCell.textContent = '₦' + formatCurrency(result.interest);
            row.appendChild(interestCell);
            
            const endBalanceCell = document.createElement('td');
            endBalanceCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900';
            endBalanceCell.textContent = '₦' + formatCurrency(result.endBalance);
            row.appendChild(endBalanceCell);
            
            yearlyTable.appendChild(row);
        });
        
        // Show results section
        resultsSection.classList.remove('hidden');
        resultsSection.scrollIntoView({ behavior: 'smooth' });
    });
    
    // Print functionality
    printResults.addEventListener('click', function() {
        window.print();
    });
    
    // Currency formatter
    function formatCurrency(value) {
        return new Intl.NumberFormat('en-NG').format(Math.round(value));
    }
    
    // Reset form handler
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        resultsSection.classList.add('hidden');
    });
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #resultsSection, #resultsSection * {
        visibility: visible;
    }
    #resultsSection {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    #printResults {
        display: none;
    }
}
</style> 