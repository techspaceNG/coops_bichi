<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Request Savings Withdrawal</h1>
                        <p class="text-primary-100">Submit a request to withdraw from your savings account</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="/savings/overview" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Savings
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Savings Information Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-purple-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-hand-holding-usd text-purple-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-purple-800">Maximum Withdrawable</h2>
                            <p class="text-2xl font-bold text-purple-900">₦<?= number_format($max_withdrawal, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">The maximum amount you can withdraw (50% of total savings)</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-amber-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-500 bg-opacity-10">
                            <i class="fas fa-calendar-alt text-amber-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-amber-800">Last Withdrawal</h2>
                            <p class="text-2xl font-bold text-amber-900">
                                <?php if ($last_withdrawal): ?>
                                    <?= date('M d, Y', strtotime($last_withdrawal['date'])) ?>
                                <?php else: ?>
                                    None
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">
                        <?php if ($last_withdrawal): ?>
                            Amount: ₦<?= number_format($last_withdrawal['amount'], 2) ?>
                        <?php else: ?>
                            You haven't made any withdrawals yet
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Withdrawal Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Withdrawal Request Form</h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($_SESSION['withdrawal_error'])): ?>
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            <?= htmlspecialchars($_SESSION['withdrawal_error']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php unset($_SESSION['withdrawal_error']); ?>
                        <?php endif; ?>
                        
                        <?php if (!$can_withdraw): ?>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700 font-medium">
                                            You are not eligible to request a withdrawal at this time
                                        </p>
                                        <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                            <?php if ($has_active_loan): ?>
                                                <li>You have an active loan. All loans must be fully repaid before withdrawals can be processed.</li>
                                            <?php endif; ?>
                                            
                                            <?php if ($recent_withdrawal): ?>
                                                <li>Your last withdrawal was less than 3 months ago. You must wait until <?= date('F d, Y', strtotime($next_eligible_date)) ?> to make another withdrawal.</li>
                                            <?php endif; ?>
                                            
                                            <?php if ($insufficient_balance): ?>
                                                <li>Your savings balance is too low to make a withdrawal. Minimum required balance is ₦<?= number_format($min_balance, 2) ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/savings/withdraw/submit" method="POST" class="space-y-6" <?= !$can_withdraw ? 'disabled' : '' ?>>
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Amount (₦)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₦</span>
                                    </div>
                                    <input type="number" id="amount" name="amount" min="5000" max="<?= $max_withdrawal ?>" step="1000" placeholder="0.00" 
                                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                                           <?= !$can_withdraw ? 'disabled' : '' ?> required>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Minimum withdrawal: ₦5,000 | Maximum: ₦<?= number_format($max_withdrawal, 2) ?></p>
                            </div>
                            
                            <div>
                                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose of Withdrawal</label>
                                <select id="purpose" name="purpose" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                        <?= !$can_withdraw ? 'disabled' : '' ?> required>
                                    <option value="">Select a purpose</option>
                                    <option value="education">Education Expenses</option>
                                    <option value="medical">Medical Expenses</option>
                                    <option value="family">Family Support</option>
                                    <option value="business">Business Investment</option>
                                    <option value="housing">Housing/Rent</option>
                                    <option value="emergency">Emergency Need</option>
                                    <option value="other">Other (Please Specify)</option>
                                </select>
                            </div>
                            
                            <div id="otherPurposeContainer" class="hidden">
                                <label for="other_purpose" class="block text-sm font-medium text-gray-700 mb-1">Specify Other Purpose</label>
                                <input type="text" id="other_purpose" name="other_purpose" 
                                       class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       <?= !$can_withdraw ? 'disabled' : '' ?>>
                            </div>
                            
                            <div>
                                <label for="account_name" class="block text-sm font-medium text-gray-700 mb-1">Bank Account Name</label>
                                <input type="text" id="account_name" name="account_name" value="<?= htmlspecialchars($member['full_name'] ?? '') ?>"
                                       class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       <?= !$can_withdraw ? 'disabled' : '' ?> required>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" value="<?= htmlspecialchars($member['bank_name'] ?? '') ?>"
                                           class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           <?= !$can_withdraw ? 'disabled' : '' ?> required>
                                </div>
                                
                                <div>
                                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                                    <input type="text" id="account_number" name="account_number" value="<?= htmlspecialchars($member['account_number'] ?? '') ?>"
                                           class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           pattern="[0-9]{10}" title="Account number must be 10 digits"
                                           <?= !$can_withdraw ? 'disabled' : '' ?> required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-1">Additional Information (Optional)</label>
                                <textarea id="additional_info" name="additional_info" rows="3" 
                                          class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                          <?= !$can_withdraw ? 'disabled' : '' ?>></textarea>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox" 
                                           class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded"
                                           <?= !$can_withdraw ? 'disabled' : '' ?> required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="font-medium text-gray-700">I confirm that the information provided is correct</label>
                                    <p class="text-gray-500">I understand that this withdrawal is subject to approval and processing may take 3-5 working days.</p>
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" 
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                        <?= !$can_withdraw ? 'disabled' : '' ?>>
                                    Submit Withdrawal Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Withdrawal Information -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Withdrawal Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-6">
                            <div>
                                <h3 class="font-medium text-gray-800">Withdrawal Eligibility</h3>
                                <ul class="mt-2 text-gray-600 list-disc list-inside space-y-1">
                                    <li>Members must have no active loans</li>
                                    <li>Minimum of 3 months between withdrawals</li>
                                    <li>Maximum withdrawal amount is 50% of total savings</li>
                                    <li>Minimum withdrawal amount is ₦5,000</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Processing Timeline</h3>
                                <p class="mt-2 text-gray-600">Withdrawal requests are typically processed within 3-5 working days after approval. Funds will be transferred to the bank account provided.</p>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Request Status</h3>
                                <p class="mt-2 text-gray-600">You can track the status of your withdrawal request in the <a href="/savings/statement" class="text-primary-600 hover:text-primary-800">Savings Statement</a> page.</p>
                            </div>
                            
                            <?php if ($pending_requests > 0): ?>
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Pending Request</h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>You have <?= $pending_requests ?> pending withdrawal request(s). Please wait for processing to complete before submitting a new request.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="bg-primary-50 rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-primary-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-medium text-primary-800">Need Help?</h3>
                                <p class="mt-1 text-primary-700">If you have questions about withdrawals or need assistance, please contact the cooperative office or visit during office hours.</p>
                                <a href="/contact" class="mt-3 inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
                                    Contact Us <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const purposeSelect = document.getElementById('purpose');
    const otherPurposeContainer = document.getElementById('otherPurposeContainer');
    const otherPurposeInput = document.getElementById('other_purpose');
    
    // Show/hide the "Other purpose" input field based on the selection
    purposeSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherPurposeContainer.classList.remove('hidden');
            otherPurposeInput.setAttribute('required', 'required');
        } else {
            otherPurposeContainer.classList.add('hidden');
            otherPurposeInput.removeAttribute('required');
        }
    });
    
    // Form validation for withdrawal amount
    const amountInput = document.getElementById('amount');
    const maxWithdrawal = <?= $max_withdrawal ?>;
    
    amountInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value > maxWithdrawal) {
            this.setCustomValidity(`Maximum withdrawal amount is ₦${maxWithdrawal.toLocaleString()}`);
        } else if (value < 5000) {
            this.setCustomValidity('Minimum withdrawal amount is ₦5,000');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 