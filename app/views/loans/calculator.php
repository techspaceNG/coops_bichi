<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Loan Repayment Calculator</h1>
                <p class="text-primary-100">Calculate your loan repayments and plan your finances</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Calculator Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 md:mb-0">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Loan Calculator</h2>
                        <p class="text-gray-600 text-sm">Enter loan details to calculate your repayment plan</p>
                    </div>
                    
                    <div class="p-6">
                        <form id="loanCalculatorForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="loanAmount" class="block text-gray-700 font-medium mb-2">Loan Amount (₦)</label>
                                    <input type="number" id="loanAmount" name="loanAmount" min="1000" step="1000" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                        required>
                                </div>
                                
                                <div>
                                    <label for="monthlyPayment" class="block text-gray-700 font-medium mb-2">Monthly Payment (₦)</label>
                                    <input type="number" id="monthlyPayment" name="monthlyPayment" min="100" step="100" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                        required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="interest_rate" class="block text-gray-700 font-medium mb-2">Admin Charges (%)</label>
                                    <input type="number" id="interest_rate" name="interest_rate" step="0.1" min="1" max="20" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                        value="5" required readonly>
                                    <p class="text-gray-500 text-sm mt-1">Fixed at 5% flat rate (as per cooperative policy)</p>
                                </div>
                                
                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg">
                                        Calculate Repayment
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div id="calculationError" class="mt-8 border-t pt-6 hidden">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Calculation Error</h3>
                            <p class="text-red-600" id="errorMessage"></p>
                        </div>
                        
                        <div id="calculation-results" class="mt-8 border-t pt-6 hidden">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Calculation Results</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 font-medium">Monthly Payment (IP Figure)</p>
                                    <p class="text-2xl font-bold text-blue-900 mt-2" id="summaryMonthlyPayment">₦0.00</p>
                                </div>
                                
                                <div class="bg-green-50 rounded-lg p-4">
                                    <p class="text-sm text-green-800 font-medium">Admin Charges</p>
                                    <p class="text-2xl font-bold text-green-900 mt-2" id="total-interest">₦0.00</p>
                                </div>
                                
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <p class="text-sm text-purple-800 font-medium">Total Repayment</p>
                                    <p class="text-2xl font-bold text-purple-900 mt-2" id="summaryTotalPayment">₦0.00</p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-800 mb-3">Repayment Schedule</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Payment</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Charges</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="scheduleBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Schedule will be filled by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-center">
                                <button type="button" id="print-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-print mr-2"></i> Print Schedule
                                </button>
                                
                                <a href="/loans/apply" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-paper-plane mr-2"></i> Apply for Loan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Loan Information -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">About Our Loans</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-800">Admin Charges</h3>
                                <p class="text-gray-600">Our cooperative society offers loans at a 5% flat rate on the principal amount.</p>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Loan Limits</h3>
                                <p class="text-gray-600">Loan amounts are based on your savings balance and membership duration:</p>
                                <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                                    <li>Minimum loan amount: ₦10,000</li>
                                    <li>Maximum loan amount: Up to 3 times your savings balance</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Repayment Terms</h3>
                                <p class="text-gray-600">You can select a repayment period that suits your financial situation:</p>
                                <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                                    <li>Short-term: 6 months</li>
                                    <li>Medium-term: 12-18 months</li>
                                    <li>Long-term: 24-36 months</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-blue-50 rounded-md p-4">
                            <h3 class="font-medium text-blue-800">How It Works</h3>
                            <p class="text-blue-600 mt-2">The monthly repayments (IP Figure) will be automatically deducted from your salary each month until the loan is fully repaid.</p>
                            <p class="text-blue-600 mt-2">Our flat rate interest system means the interest is calculated on the initial loan amount, making it easy to understand your total repayment obligation from day one.</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Need Assistance?</h2>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-600">If you have any questions about our loan services or need help with your application, please contact our cooperative society office:</p>
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope text-primary-500 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700">loans@coopsbichi.org</p>
                                </div>
                            </div>
                            
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone-alt text-primary-500 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700">+234 xxx xxx xxxx</p>
                                </div>
                            </div>
                            
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-primary-500 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700">Cooperative Society Office, FCET Bichi Campus</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calculator JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculatorForm = document.getElementById('loanCalculatorForm');
    const errorDiv = document.getElementById('calculationError');
    const resultsDiv = document.getElementById('calculation-results');
    const scheduleBody = document.getElementById('scheduleBody');
    
    calculatorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hide any previous errors
        errorDiv.classList.add('hidden');
        errorDiv.textContent = '';
        
        // Get form values
        const loanAmount = parseFloat(document.getElementById('loanAmount').value);
        const monthlyPayment = parseFloat(document.getElementById('monthlyPayment').value);
        const interestRate = parseFloat(document.getElementById('interest_rate').value);
        
        // Basic validation
        if (isNaN(loanAmount) || loanAmount < 1000 || loanAmount > 5000000) {
            showError('Please enter a valid loan amount between ₦10,000 and ₦5,000,000');
            return;
        }
        
        if (isNaN(monthlyPayment) || monthlyPayment < 100 || monthlyPayment > loanAmount) {
            showError('Please enter a valid monthly payment between ₦100 and the loan amount');
            return;
        }
        
        // Calculate loan details
        calculateLoan(loanAmount, monthlyPayment, interestRate);
    });
    
    function showError(message) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    function calculateLoan(loanAmount, monthlyPayment, interestRate) {
        // Show loading state
        document.body.classList.add('cursor-wait');
        
        // Calculate loan details
        const interestAmount = loanAmount * (interestRate / 100);
        const totalRepayment = loanAmount + interestAmount;
        const totalMonths = Math.ceil(totalRepayment / monthlyPayment);
        
        // Prepare data for the API call
        const requestData = {
            loanAmount: loanAmount,
            monthlyPayment: monthlyPayment,
            interestRate: interestRate,
            totalMonths: totalMonths,
            totalRepayment: totalRepayment,
            interestAmount: interestAmount
        };
        
        // Make API request
        fetch('/api/loan/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Failed to calculate loan');
                });
            }
            return response.json();
        })
        .then(data => {
            displayResults(data);
        })
        .catch(error => {
            showError(error.message);
        })
        .finally(() => {
            document.body.classList.remove('cursor-wait');
        });
    }
    
    function displayResults(data) {
        // Update summary values
        document.getElementById('summaryMonthlyPayment').textContent = '₦' + data.monthlyPayment.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('total-interest').textContent = '₦' + data.interestAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('summaryTotalPayment').textContent = '₦' + data.totalPayments.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Generate repayment schedule table
        scheduleBody.innerHTML = '';
        
        data.schedule.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${item.month}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${item.paymentDate}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">₦${item.payment.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">₦${item.principal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">₦${item.interest.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</td>
                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">₦${item.balance.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</td>
            `;
            scheduleBody.appendChild(row);
        });
        
        // Show the results section, hide the error message
        resultsDiv.classList.remove('hidden');
    }
});
</script>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #calculation-results, #calculation-results * {
        visibility: visible;
    }
    #calculation-results {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    #print-btn, #calculation-results a {
        display: none;
    }
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 