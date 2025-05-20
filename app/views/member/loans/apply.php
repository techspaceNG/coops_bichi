<?php
// Member Loan Application View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_loan_apply');
?>

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
                        <?php if (isset($errors['application'])): ?>
                            <div class="bg-red-50 text-red-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p><?= htmlspecialchars($errors['application']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($flashMessage): ?>
                            <div class="bg-<?= $flashMessage['type'] === 'error' ? 'red' : ($flashMessage['type'] === 'success' ? 'green' : 'blue') ?>-50 
                                text-<?= $flashMessage['type'] === 'error' ? 'red' : ($flashMessage['type'] === 'success' ? 'green' : 'blue') ?>-800 p-4 rounded-md mb-6">
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
                        
                        <?php if (isset($hasActiveLoan) && $hasActiveLoan): ?>
                            <div class="bg-yellow-50 text-yellow-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You have an active loan</p>
                                        <p class="mt-1">You may not be eligible for a new loan until your current loan is fully repaid.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($pendingApplication) && $pendingApplication): ?>
                            <div class="bg-blue-50 text-blue-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You have a pending loan application</p>
                                        <p class="mt-1">Amount: ₦<?= number_format($pendingApplication['loan_amount'], 2) ?></p>
                                        <p class="mt-1">Status: <?= ucfirst(htmlspecialchars($pendingApplication['status'])) ?></p>
                                        <p>Your application is being processed. You will be notified once a decision is made.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/Coops_Bichi/public/member/loans/apply" method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="loan_amount" class="block text-gray-700 font-medium mb-2">Loan Amount (₦)</label>
                                    <input type="number" id="loan_amount" name="loan_amount" step="0.01" min="1000" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['loan_amount']) ? 'border-red-500' : 'border-gray-300' ?>"
                                        value="<?= isset($_POST['loan_amount']) ? htmlspecialchars($_POST['loan_amount']) : '' ?>" required>
                                    <?php if (isset($errors['loan_amount'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['loan_amount']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="loan_duration" class="block text-gray-700 font-medium mb-2">Loan Duration (Months)</label>
                                    <select id="loan_duration" name="loan_duration" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['loan_duration']) ? 'border-red-500' : 'border-gray-300' ?>" required>
                                        <option value="" disabled <?= !isset($_POST['loan_duration']) ? 'selected' : '' ?>>Select duration</option>
                                        <?php for ($i = 6; $i <= 24; $i++): ?>
                                            <option value="<?= $i ?>" <?= isset($_POST['loan_duration']) && (int)$_POST['loan_duration'] === $i ? 'selected' : '' ?>><?= $i ?> months</option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php if (isset($errors['loan_duration'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['loan_duration']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="ip_figure" class="block text-gray-700 font-medium mb-2">Monthly Repayment Amount (₦)</label>
                                    <input type="number" id="ip_figure" name="ip_figure" step="0.01" min="100" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['ip_figure']) ? 'border-red-500' : 'border-gray-300' ?>"
                                        value="<?= isset($_POST['ip_figure']) ? htmlspecialchars($_POST['ip_figure']) : '' ?>" required>
                                    <?php if (isset($errors['ip_figure'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['ip_figure']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-gray-500 text-sm mt-1">The amount to be deducted from your salary each month</p>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-800 mb-3">Bank Account Information</h3>
                                </div>
                                
                                <div>
                                    <label for="bank_name" class="block text-gray-700 font-medium mb-2">Bank Name</label>
                                    <select id="bank_name" name="bank_name" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['bank_name']) ? 'border-red-500' : 'border-gray-300' ?>" required>
                                        <option value="" disabled <?= !isset($_POST['bank_name']) ? 'selected' : '' ?>>Select bank</option>
                                        <option value="Access Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Access Bank' ? 'selected' : '' ?>>Access Bank</option>
                                        <option value="Citibank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Citibank' ? 'selected' : '' ?>>Citibank</option>
                                        <option value="Diamond Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Diamond Bank' ? 'selected' : '' ?>>Diamond Bank</option>
                                        <option value="Ecobank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Ecobank' ? 'selected' : '' ?>>Ecobank</option>
                                        <option value="FCMB" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'FCMB' ? 'selected' : '' ?>>FCMB</option>
                                        <option value="Fidelity Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Fidelity Bank' ? 'selected' : '' ?>>Fidelity Bank</option>
                                        <option value="First Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'First Bank' ? 'selected' : '' ?>>First Bank</option>
                                        <option value="GTBank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'GTBank' ? 'selected' : '' ?>>GTBank</option>
                                        <option value="Heritage Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Heritage Bank' ? 'selected' : '' ?>>Heritage Bank</option>
                                        <option value="Jaiz Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Jaiz Bank' ? 'selected' : '' ?>>Jaiz Bank</option>
                                        <option value="Keystone Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Keystone Bank' ? 'selected' : '' ?>>Keystone Bank</option>
                                        <option value="Polaris Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Polaris Bank' ? 'selected' : '' ?>>Polaris Bank</option>
                                        <option value="Providus Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Providus Bank' ? 'selected' : '' ?>>Providus Bank</option>
                                        <option value="Stanbic IBTC" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Stanbic IBTC' ? 'selected' : '' ?>>Stanbic IBTC</option>
                                        <option value="Standard Chartered" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Standard Chartered' ? 'selected' : '' ?>>Standard Chartered</option>
                                        <option value="Sterling Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Sterling Bank' ? 'selected' : '' ?>>Sterling Bank</option>
                                        <option value="SunTrust Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'SunTrust Bank' ? 'selected' : '' ?>>SunTrust Bank</option>
                                        <option value="Union Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Union Bank' ? 'selected' : '' ?>>Union Bank</option>
                                        <option value="UBA" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'UBA' ? 'selected' : '' ?>>UBA</option>
                                        <option value="Unity Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Unity Bank' ? 'selected' : '' ?>>Unity Bank</option>
                                        <option value="Wema Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Wema Bank' ? 'selected' : '' ?>>Wema Bank</option>
                                        <option value="Zenith Bank" <?= isset($_POST['bank_name']) && $_POST['bank_name'] === 'Zenith Bank' ? 'selected' : '' ?>>Zenith Bank</option>
                                    </select>
                                    <?php if (isset($errors['bank_name'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['bank_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <label for="account_number" class="block text-gray-700 font-medium mb-2">Account Number</label>
                                    <input type="text" id="account_number" name="account_number" maxlength="10" pattern="[0-9]{10}"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['account_number']) ? 'border-red-500' : 'border-gray-300' ?>"
                                        value="<?= isset($_POST['account_number']) ? htmlspecialchars($_POST['account_number']) : '' ?>" required>
                                    <?php if (isset($errors['account_number'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['account_number']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-gray-500 text-sm mt-1">Enter your 10-digit account number</p>
                                </div>
                                
                                <div>
                                    <label for="account_name" class="block text-gray-700 font-medium mb-2">Account Name</label>
                                    <input type="text" id="account_name" name="account_name"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['account_name']) ? 'border-red-500' : 'border-gray-300' ?>"
                                        value="<?= isset($_POST['account_name']) ? htmlspecialchars($_POST['account_name']) : '' ?>" required>
                                    <?php if (isset($errors['account_name'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['account_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <label for="account_type" class="block text-gray-700 font-medium mb-2">Account Type</label>
                                    <select id="account_type" name="account_type" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['account_type']) ? 'border-red-500' : 'border-gray-300' ?>" required>
                                        <option value="" disabled <?= !isset($_POST['account_type']) ? 'selected' : '' ?>>Select account type</option>
                                        <option value="Savings" <?= isset($_POST['account_type']) && $_POST['account_type'] === 'Savings' ? 'selected' : '' ?>>Savings</option>
                                        <option value="Current" <?= isset($_POST['account_type']) && $_POST['account_type'] === 'Current' ? 'selected' : '' ?>>Current</option>
                                    </select>
                                    <?php if (isset($errors['account_type'])): ?>
                                        <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['account_type']) ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="purpose" class="block text-gray-700 font-medium mb-2">Purpose of Loan</label>
                                    <select id="purpose" name="purpose" 
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
                        <h2 class="text-lg font-semibold text-gray-800">Loan Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Admin Charges</p>
                                    <p class="text-gray-500">Our loan admin charge is fixed at 5%.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Repayment Period</p>
                                    <p class="text-gray-500">Loans can be repaid over a period of up to 24 months.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Approval Process</p>
                                    <p class="text-gray-500">Applications are reviewed within 5 working days.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Disbursement</p>
                                    <p class="text-gray-500">Approved loans are disbursed within 3 working days.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Need Help?</h2>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">If you have any questions about loans or need assistance with your application, please contact our support team.</p>
                        
                        <a href="/Coops_Bichi/public/contact" class="block bg-gray-100 hover:bg-gray-200 text-center py-3 px-4 rounded-lg font-medium text-gray-800">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Loan calculator script
document.addEventListener('DOMContentLoaded', function() {
    const loanAmountInput = document.getElementById('loan_amount');
    const ipFigureInput = document.getElementById('ip_figure');
    const loanDurationSelect = document.getElementById('loan_duration');
    
    // Function to calculate monthly payment
    function calculateMonthlyPayment() {
        const loanAmount = parseFloat(loanAmountInput.value);
        const duration = parseInt(loanDurationSelect.value);
        
        if (!isNaN(loanAmount) && loanAmount >= 1000 && !isNaN(duration) && duration > 0) {
            // Calculate with 5% admin charge
            const totalAmount = loanAmount * 1.05;
            const monthlyPayment = Math.ceil(totalAmount / duration);
            ipFigureInput.value = monthlyPayment;
        }
    }
    
    // Update calculation when loan amount changes
    loanAmountInput.addEventListener('change', calculateMonthlyPayment);
    
    // Update calculation when duration changes
    loanDurationSelect.addEventListener('change', calculateMonthlyPayment);
});
</script> 