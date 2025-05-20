<?php
// Member Loan Calculator View
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Loan Calculator</h1>
                <p class="text-primary-100">Calculate your loan repayments with FCET Bichi Staff Multipurpose Cooperative Society</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Calculator Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Loan Repayment Calculator</h2>
                        <p class="text-gray-600 text-sm">Enter loan details to calculate your repayment plan</p>
                    </div>
                    
                    <div class="p-6">
                        <form id="loan-calculator-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="loan_amount" class="block text-gray-700 font-medium mb-2">Loan Amount (₦)</label>
                                    <input type="number" id="loan_amount" name="loan_amount" step="1000" min="10000" max="10000000" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                        value="<?= isset($loanAmount) ? $loanAmount : 100000 ?>">
                                    <p class="text-gray-500 text-sm mt-1">Enter the amount you wish to borrow</p>
                                </div>
                                
                                <div>
                                    <label for="interest_rate" class="block text-gray-700 font-medium mb-2">Admin Charges (%)</label>
                                    <input type="number" id="interest_rate" name="interest_rate" step="0.1" min="1" max="20" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" value="5">
                                    <p class="text-gray-500 text-sm mt-1">Our standard admin charge is 5%</p>
                                </div>
                                
                                <div>
                                    <label for="repayment_period" class="block text-gray-700 font-medium mb-2">Repayment Period (Months)</label>
                                    <select id="repayment_period" name="repayment_period" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300">
                                        <option value="6" <?= isset($repaymentPeriod) && $repaymentPeriod == 6 ? 'selected' : '' ?>>6 months</option>
                                        <option value="12" <?= isset($repaymentPeriod) && $repaymentPeriod == 12 ? 'selected' : (!isset($repaymentPeriod) ? 'selected' : '') ?>>12 months</option>
                                        <option value="18" <?= isset($repaymentPeriod) && $repaymentPeriod == 18 ? 'selected' : '' ?>>18 months</option>
                                        <option value="24" <?= isset($repaymentPeriod) && $repaymentPeriod == 24 ? 'selected' : '' ?>>24 months</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="monthly_payment" class="block text-gray-700 font-medium mb-2">Monthly Repayment (₦)</label>
                                    <input type="number" id="monthly_payment" name="monthly_payment" step="100" min="1000" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                        value="<?= isset($ipFigure) ? $ipFigure : 0 ?>">
                                    <p class="text-gray-500 text-sm mt-1">Monthly amount to be deducted</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" id="calculate-btn" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg">
                                    Calculate
                                </button>
                                
                                <button type="button" id="reset-btn" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Calculation Results -->
                <div id="calculation-results" class="bg-white rounded-lg shadow-md overflow-hidden mt-6 <?= isset($repaymentDetails) ? '' : 'hidden' ?>">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Calculation Results</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-gray-600 text-sm mb-1">Loan Amount</h3>
                                <p id="result-loan-amount" class="text-2xl font-semibold text-gray-900">
                                    ₦<?= isset($loanAmount) ? number_format($loanAmount, 2) : '0.00' ?>
                                </p>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-gray-600 text-sm mb-1">Admin Charges</h3>
                                <p id="result-interest-amount" class="text-2xl font-semibold text-gray-900">
                                    ₦<?= isset($repaymentDetails) ? number_format($repaymentDetails['total_repayment'] - $loanAmount, 2) : '0.00' ?>
                                </p>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-gray-600 text-sm mb-1">Monthly Repayment</h3>
                                <p id="result-monthly-payment" class="text-2xl font-semibold text-gray-900">
                                    ₦<?= isset($ipFigure) ? number_format($ipFigure, 2) : '0.00' ?>
                                </p>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-gray-600 text-sm mb-1">Total Repayment</h3>
                                <p id="result-total-repayment" class="text-2xl font-semibold text-gray-900">
                                    ₦<?= isset($repaymentDetails) ? number_format($repaymentDetails['total_repayment'], 2) : '0.00' ?>
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-800 mb-4">Repayment Schedule</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200" id="repayment-schedule">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Charges</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (isset($repaymentDetails) && isset($repaymentDetails['schedule'])): ?>
                                            <?php foreach ($repaymentDetails['schedule'] as $month => $payment): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $month ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₦<?= number_format($payment['payment'], 2) ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₦<?= number_format($payment['principal'], 2) ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₦<?= number_format($payment['admin_charges'] ?? $payment['interest'], 2) ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">₦<?= number_format($payment['balance'], 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div>
                <!-- Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">About Our Loans</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Fixed 5% Admin Charges</p>
                                    <p class="text-gray-500">All loans have a fixed 5% admin charge for the entire duration.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Flexible Repayment</p>
                                    <p class="text-gray-500">Choose a repayment period between 6 and 24 months.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">No Hidden Fees</p>
                                    <p class="text-gray-500">We don't charge any processing or administrative fees.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Quick Approval</p>
                                    <p class="text-gray-500">Applications are processed within 5 working days.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Quick Links</h2>
                    </div>
                    
                    <div class="p-6">
                        <nav class="space-y-2">
                            <a href="/Coops_Bichi/public/member/loans" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-arrow-left mr-2 text-gray-400"></i> Back to Loans
                            </a>
                            <a href="/Coops_Bichi/public/member/loans/apply" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-plus-circle mr-2 text-gray-400"></i> Apply for Loan
                            </a>
                            <a href="/Coops_Bichi/public/member/loans/applications" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-list mr-2 text-gray-400"></i> My Applications
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loanAmountInput = document.getElementById('loan_amount');
    const interestRateInput = document.getElementById('interest_rate');
    const repaymentPeriodInput = document.getElementById('repayment_period');
    const monthlyPaymentInput = document.getElementById('monthly_payment');
    const calculateBtn = document.getElementById('calculate-btn');
    const resetBtn = document.getElementById('reset-btn');
    const calculationResults = document.getElementById('calculation-results');
    
    const resultLoanAmount = document.getElementById('result-loan-amount');
    const resultInterestAmount = document.getElementById('result-interest-amount');
    const resultMonthlyPayment = document.getElementById('result-monthly-payment');
    const resultTotalRepayment = document.getElementById('result-total-repayment');
    
    // Calculate loan details on button click
    calculateBtn.addEventListener('click', function() {
        // Get values from inputs
        const loanAmount = parseFloat(loanAmountInput.value);
        const adminChargeRate = parseFloat(interestRateInput.value);
        const repaymentPeriod = parseInt(repaymentPeriodInput.value);
        let monthlyPayment = parseFloat(monthlyPaymentInput.value);
        
        // Validate inputs
        if (isNaN(loanAmount) || loanAmount <= 0) {
            alert('Please enter a valid loan amount');
            return;
        }
        
        if (isNaN(monthlyPayment) || monthlyPayment <= 0) {
            // Calculate default monthly payment based on loan amount and period
            monthlyPayment = Math.ceil((loanAmount * (1 + (adminChargeRate / 100))) / repaymentPeriod);
            monthlyPaymentInput.value = monthlyPayment;
        }
        
        // Calculate loan details
        const totalAdminCharges = loanAmount * (adminChargeRate / 100);
        const totalRepayment = loanAmount + totalAdminCharges;
        
        // Update results
        resultLoanAmount.textContent = '₦' + loanAmount.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        resultInterestAmount.textContent = '₦' + totalAdminCharges.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        resultMonthlyPayment.textContent = '₦' + monthlyPayment.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        resultTotalRepayment.textContent = '₦' + totalRepayment.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        // Generate repayment schedule
        const scheduleTable = document.getElementById('repayment-schedule').getElementsByTagName('tbody')[0];
        scheduleTable.innerHTML = '';
        
        let remainingBalance = loanAmount;
        const monthlyAdminChargeRate = adminChargeRate / 100 / 12;
        
        for (let month = 1; month <= repaymentPeriod; month++) {
            // Calculate admin charges for this month
            const adminChargesForMonth = remainingBalance * monthlyAdminChargeRate;
            
            // Calculate principal for this month
            const principalForMonth = Math.min(monthlyPayment - adminChargesForMonth, remainingBalance);
            
            // Calculate new balance
            remainingBalance -= principalForMonth;
            
            // Add row to table
            const row = scheduleTable.insertRow();
            
            const monthCell = row.insertCell(0);
            const paymentCell = row.insertCell(1);
            const principalCell = row.insertCell(2);
            const adminChargesCell = row.insertCell(3);
            const balanceCell = row.insertCell(4);
            
            monthCell.textContent = month;
            monthCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
            
            paymentCell.textContent = '₦' + monthlyPayment.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            paymentCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-800';
            
            principalCell.textContent = '₦' + principalForMonth.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            principalCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-800';
            
            adminChargesCell.textContent = '₦' + adminChargesForMonth.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            adminChargesCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-800';
            
            balanceCell.textContent = '₦' + remainingBalance.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            balanceCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-800';
        }
        
        // Show results section
        calculationResults.classList.remove('hidden');
    });
    
    // Reset calculator
    resetBtn.addEventListener('click', function() {
        loanAmountInput.value = 100000;
        interestRateInput.value = 5;
        repaymentPeriodInput.value = 12;
        monthlyPaymentInput.value = '';
        calculationResults.classList.add('hidden');
    });
    
    // Auto-calculate monthly payment when loan amount changes
    loanAmountInput.addEventListener('change', function() {
        const loanAmount = parseFloat(loanAmountInput.value);
        const adminChargeRate = parseFloat(interestRateInput.value);
        const repaymentPeriod = parseInt(repaymentPeriodInput.value);
        
        if (!isNaN(loanAmount) && loanAmount > 0) {
            // Calculate default monthly payment
            const monthlyPayment = Math.ceil((loanAmount * (1 + (adminChargeRate / 100))) / repaymentPeriod);
            monthlyPaymentInput.value = monthlyPayment;
        }
    });
    
    // Auto-calculate monthly payment when repayment period changes
    repaymentPeriodInput.addEventListener('change', function() {
        const loanAmount = parseFloat(loanAmountInput.value);
        const adminChargeRate = parseFloat(interestRateInput.value);
        const repaymentPeriod = parseInt(repaymentPeriodInput.value);
        
        if (!isNaN(loanAmount) && loanAmount > 0) {
            // Calculate default monthly payment
            const monthlyPayment = Math.ceil((loanAmount * (1 + (adminChargeRate / 100))) / repaymentPeriod);
            monthlyPaymentInput.value = monthlyPayment;
        }
    });
});
</script> 