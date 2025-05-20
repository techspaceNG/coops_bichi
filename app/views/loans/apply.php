<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Loan Application</h1>
                <p class="text-primary-100">Apply for a loan with FCET Bichi Staff Multipurpose Cooperative Society</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Loan Application Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Fill Application Form</h2>
                        <p class="text-gray-600 text-sm">Please fill in the details below to apply for a loan</p>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($errors['general'])): ?>
                            <div class="bg-red-50 text-red-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p><?= htmlspecialchars($errors['general']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($active_loan) && $active_loan): ?>
                            <div class="bg-yellow-50 text-yellow-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You have an active loan</p>
                                        <p class="mt-1">Current Balance: ₦<?= number_format($active_loan['balance'], 2) ?></p>
                                        <p>You may not be eligible for a new loan until your current loan is fully repaid.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($pending_application) && $pending_application): ?>
                            <div class="bg-blue-50 text-blue-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You have a pending loan application</p>
                                        <p class="mt-1">Amount: ₦<?= number_format($pending_application['loan_amount'], 2) ?></p>
                                        <p class="mt-1">Status: <?= ucfirst(htmlspecialchars($pending_application['status'])) ?></p>
                                        <p>Your application is being processed. You will be notified once a decision is made.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/loans/apply" method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="loan_amount" class="block text-gray-700 font-medium mb-2">Loan Amount (₦)</label>
                                    <input type="number" id="loan_amount" name="loan_amount" step="0.01" min="1000" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['loan_amount']) ? 'border-red-500' : 'border-gray-300' ?>"
                                        value="<?= isset($_POST['loan_amount']) ? htmlspecialchars($_POST['loan_amount']) : '' ?>" required>
                                    <?php if (isset($errors['loan_amount'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['loan_amount']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-gray-500 text-sm mt-1">Your loan limit is up to ₦<?= number_format($loan_limit, 2) ?></p>
                                </div>
                                
                                <div>
                                    <label for="purpose" class="block text-gray-700 font-medium mb-2">Purpose of Loan</label>
                                    <select id="purpose" name="purpose" required
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['purpose']) ? 'border-red-500' : 'border-gray-300' ?>">
                                        <option value="" disabled <?= !isset($_POST['purpose']) ? 'selected' : '' ?>>Select purpose</option>
                                        <option value="education" <?= isset($_POST['purpose']) && $_POST['purpose'] === 'education' ? 'selected' : '' ?>>Education</option>
                                        <option value="medical" <?= isset($_POST['purpose']) && $_POST['purpose'] === 'medical' ? 'selected' : '' ?>>Medical</option>
                                        <option value="home_improvement" <?= isset($_POST['purpose']) && $_POST['purpose'] === 'home_improvement' ? 'selected' : '' ?>>Home Improvement</option>
                                        <option value="business" <?= isset($_POST['purpose']) && $_POST['purpose'] === 'business' ? 'selected' : '' ?>>Business</option>
                                        <option value="emergency" <?= isset($_POST['purpose']) && $_POST['purpose'] === 'emergency' ? 'selected' : '' ?>>Emergency</option>
                                        <option value="other" <?= isset($_POST['purpose']) && $_POST['purpose'] === 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                    <?php if (isset($errors['purpose'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['purpose']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <label for="repayment_period" class="block text-gray-700 font-medium mb-2">Repayment Period (Months)</label>
                                    <select id="repayment_period" name="repayment_period" required
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['repayment_period']) ? 'border-red-500' : 'border-gray-300' ?>">
                                        <option value="" disabled <?= !isset($_POST['repayment_period']) ? 'selected' : '' ?>>Select period</option>
                                        <option value="6" <?= isset($_POST['repayment_period']) && $_POST['repayment_period'] === '6' ? 'selected' : '' ?>>6 months</option>
                                        <option value="12" <?= isset($_POST['repayment_period']) && $_POST['repayment_period'] === '12' ? 'selected' : '' ?>>12 months</option>
                                        <option value="18" <?= isset($_POST['repayment_period']) && $_POST['repayment_period'] === '18' ? 'selected' : '' ?>>18 months</option>
                                        <option value="24" <?= isset($_POST['repayment_period']) && $_POST['repayment_period'] === '24' ? 'selected' : '' ?>>24 months</option>
                                    </select>
                                    <?php if (isset($errors['repayment_period'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['repayment_period']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="additional_info" class="block text-gray-700 font-medium mb-2">Additional Information (Optional)</label>
                                    <textarea id="additional_info" name="additional_info" rows="4"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['additional_info']) ? 'border-red-500' : 'border-gray-300' ?>"><?= isset($_POST['additional_info']) ? htmlspecialchars($_POST['additional_info']) : '' ?></textarea>
                                    <?php if (isset($errors['additional_info'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['additional_info']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="agreement" name="agreement" required
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="agreement" class="font-medium text-gray-700">I agree to the loan terms and conditions</label>
                                        <p class="text-gray-500">By applying for this loan, I confirm that all information provided is accurate. I understand that repayments will be deducted from my salary monthly.</p>
                                        <?php if (isset($errors['agreement'])): ?>
                                            <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['agreement']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg">
                                    Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Loan Information -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Loan Calculator</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="calc_amount" class="block text-gray-700 font-medium mb-2">Loan Amount (₦)</label>
                            <input type="number" id="calc_amount" name="calc_amount" step="0.01" min="1000" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                value="10000">
                        </div>
                        
                        <div class="mb-4">
                            <label for="calc_period" class="block text-gray-700 font-medium mb-2">Repayment Period (Months)</label>
                            <select id="calc_period" name="calc_period" 
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300">
                                <option value="6">6 months</option>
                                <option value="12" selected>12 months</option>
                                <option value="18">18 months</option>
                                <option value="24">24 months</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <button type="button" id="calculate-btn" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg">
                                Calculate
                            </button>
                        </div>
                        
                        <div id="calculation-result" class="hidden">
                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Monthly Repayment:</span>
                                    <span class="font-semibold" id="monthly-repayment">₦0.00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Repayment:</span>
                                    <span class="font-semibold" id="total-repayment">₦0.00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Admin Charges:</span>
                                    <span class="font-semibold" id="interest-amount">₦0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Loan Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-800">Admin Charges</h3>
                                <p class="text-gray-600">5% flat rate on the principal amount</p>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Eligibility</h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-1">
                                    <li>Active membership for at least 6 months</li>
                                    <li>Regular savings contributions</li>
                                    <li>No outstanding loan balance</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Processing Time</h3>
                                <p class="text-gray-600">Loan applications are typically processed within 5-7 working days</p>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Need Help?</h3>
                                <p class="text-gray-600">Contact the cooperative society office for assistance with your loan application.</p>
                                <p class="text-gray-600 mt-1">Email: loans@coopsbichi.org</p>
                                <p class="text-gray-600">Phone: +234 xxx xxx xxxx</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loan Calculator JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculateBtn = document.getElementById('calculate-btn');
    const resultDiv = document.getElementById('calculation-result');
    
    calculateBtn.addEventListener('click', function() {
        const loanAmount = parseFloat(document.getElementById('calc_amount').value);
        const repaymentPeriod = parseInt(document.getElementById('calc_period').value);
        
        if (isNaN(loanAmount) || loanAmount <= 0) {
            alert('Please enter a valid loan amount');
            return;
        }
        
        // Simple interest calculation (5% flat rate)
        const interestRate = 0.05;
        const interestAmount = loanAmount * interestRate;
        const totalRepayment = loanAmount + interestAmount;
        const monthlyRepayment = totalRepayment / repaymentPeriod;
        
        document.getElementById('monthly-repayment').textContent = '₦' + monthlyRepayment.toFixed(2);
        document.getElementById('total-repayment').textContent = '₦' + totalRepayment.toFixed(2);
        document.getElementById('interest-amount').textContent = '₦' + interestAmount.toFixed(2);
        
        resultDiv.classList.remove('hidden');
    });
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 