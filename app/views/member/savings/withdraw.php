<?php
// Member Savings Withdrawal View

$flashMessage = \App\Helpers\Utility::getFlashMessage();
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Request Withdrawal</h1>
                <p class="text-primary-100">Submit a request to withdraw funds from your savings account</p>
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
                <?php if (isset($pendingWithdrawal) && $pendingWithdrawal): ?>
                    <!-- Pending Withdrawal Notice -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-md mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-yellow-800">Pending Withdrawal Request</h3>
                                <div class="mt-2 text-yellow-700">
                                    <p>You already have a pending withdrawal request for ₦<?= number_format($pendingWithdrawal['amount'], 2) ?> submitted on <?= date('M d, Y', strtotime($pendingWithdrawal['request_date'])) ?>.</p>
                                    <p class="mt-2">You cannot submit a new withdrawal request until this request is processed. Please contact the cooperative office if you need assistance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif (isset($balanceInsufficient) && $balanceInsufficient): ?>
                    <!-- Insufficient Balance Notice -->
                    <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-lg shadow-md mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-red-800">Insufficient Balance</h3>
                                <div class="mt-2 text-red-700">
                                    <p>Your current savings balance is too low to make a withdrawal. The minimum required balance is ₦<?= number_format($minimumBalance, 2) ?>.</p>
                                    <p class="mt-2">Please continue making contributions to increase your savings balance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Withdrawal Request Form -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Withdrawal Request Form</h2>
                        </div>
                        
                        <div class="p-6">
                            <form action="/Coops_Bichi/public/member/savings/withdraw" method="POST" id="withdrawal-form">
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-blue-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <?php
                                                // Set default values if variables are not defined
                                                $currentBalance = $currentBalance ?? $savings_balance ?? 0.0;
                                                $maxWithdrawalAmount = $maxWithdrawalAmount ?? ($currentBalance * 0.8);
                                                ?>
                                                <p class="text-sm text-blue-700">Your current savings balance is <strong>₦<?= number_format($currentBalance, 2) ?></strong>.</p>
                                                <p class="text-sm text-blue-700 mt-1">Maximum withdrawal amount: <strong>₦<?= number_format($maxWithdrawalAmount, 2) ?></strong></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="amount" class="block text-gray-700 font-medium mb-2">Withdrawal Amount (₦) <span class="text-red-500">*</span></label>
                                        <input type="number" id="amount" name="amount" min="1000" max="<?= $maxWithdrawalAmount ?>" step="100" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            placeholder="Enter withdrawal amount">
                                        <p class="text-gray-500 text-sm mt-1">Minimum withdrawal: ₦1,000</p>
                                    </div>
                                    
                                    <div>
                                        <label for="reason" class="block text-gray-700 font-medium mb-2">Reason for Withdrawal <span class="text-red-500">*</span></label>
                                        <select id="reason" name="reason" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300">
                                            <option value="">Select a reason</option>
                                            <option value="Emergency">Emergency</option>
                                            <option value="Education">Education</option>
                                            <option value="Medical">Medical</option>
                                            <option value="Housing">Housing</option>
                                            <option value="Family Support">Family Support</option>
                                            <option value="Business Investment">Business Investment</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div id="other-reason-container" style="display: none;">
                                        <label for="other_reason" class="block text-gray-700 font-medium mb-2">Specify Other Reason <span class="text-red-500">*</span></label>
                                        <textarea id="other_reason" name="other_reason" rows="3"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            placeholder="Please specify your reason for withdrawal"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="payment_method" class="block text-gray-700 font-medium mb-2">Preferred Payment Method <span class="text-red-500">*</span></label>
                                        <select id="payment_method" name="payment_method" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300">
                                            <option value="">Select payment method</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Check">Check</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>
                                    
                                    <div id="bank-details-container" style="display: none;">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h3 class="font-medium text-gray-700 mb-2">Bank Details</h3>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="bank_name" class="block text-gray-700 font-medium mb-2">Bank Name <span class="text-red-500">*</span></label>
                                                    <input type="text" id="bank_name" name="bank_name"
                                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                                        placeholder="Enter bank name">
                                                </div>
                                                
                                                <div>
                                                    <label for="account_number" class="block text-gray-700 font-medium mb-2">Account Number <span class="text-red-500">*</span></label>
                                                    <input type="text" id="account_number" name="account_number"
                                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                                        placeholder="Enter account number">
                                                </div>
                                                
                                                <div>
                                                    <label for="account_name" class="block text-gray-700 font-medium mb-2">Account Name <span class="text-red-500">*</span></label>
                                                    <input type="text" id="account_name" name="account_name"
                                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                                        placeholder="Enter account name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="comments" class="block text-gray-700 font-medium mb-2">Additional Comments</label>
                                        <textarea id="comments" name="comments" rows="3"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            placeholder="Any additional information you want to provide"></textarea>
                                    </div>
                                    
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="terms" name="terms" type="checkbox" required
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="terms" class="font-medium text-gray-700">I understand and agree to the withdrawal terms</label>
                                                <p class="text-gray-500">I confirm that all information provided is accurate. I understand that withdrawal requests may take 2-5 business days to process.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" 
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-paper-plane mr-2"></i> Submit Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
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
                                Back to Savings Dashboard
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/statement" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                View Savings Statement
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings/update" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Update Monthly Contribution
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Withdrawal Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Withdrawal Guidelines</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Processing Time</p>
                                    <p class="text-gray-500">Withdrawal requests are typically processed within 2-5 business days.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Minimum Balance</p>
                                    <?php $minimumBalance = $minimumBalance ?? 1000.00; ?>
                                    <p class="text-gray-500">A minimum balance of ₦<?= number_format($minimumBalance, 2) ?> must be maintained in your savings account.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Withdrawal Limits</p>
                                    <p class="text-gray-500">Minimum withdrawal amount: ₦1,000. Maximum withdrawal: 70% of your available balance.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Required Documentation</p>
                                    <p class="text-gray-500">For amounts over ₦100,000, you may be asked to provide additional documentation.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Frequency</p>
                                    <p class="text-gray-500">Members may make up to 4 withdrawals per year, with at least 3 months between withdrawals.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reasonSelect = document.getElementById('reason');
    const otherReasonContainer = document.getElementById('other-reason-container');
    const paymentMethodSelect = document.getElementById('payment_method');
    const bankDetailsContainer = document.getElementById('bank-details-container');
    
    // Show/hide other reason field based on selection
    reasonSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            otherReasonContainer.style.display = 'block';
            document.getElementById('other_reason').setAttribute('required', 'required');
        } else {
            otherReasonContainer.style.display = 'none';
            document.getElementById('other_reason').removeAttribute('required');
        }
    });
    
    // Show/hide bank details based on payment method
    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'Bank Transfer') {
            bankDetailsContainer.style.display = 'block';
            document.getElementById('bank_name').setAttribute('required', 'required');
            document.getElementById('account_number').setAttribute('required', 'required');
            document.getElementById('account_name').setAttribute('required', 'required');
        } else {
            bankDetailsContainer.style.display = 'none';
            document.getElementById('bank_name').removeAttribute('required');
            document.getElementById('account_number').removeAttribute('required');
            document.getElementById('account_name').removeAttribute('required');
        }
    });
    
    // Validate withdrawal amount
    const amountInput = document.getElementById('amount');
    const maxAmount = <?= $maxWithdrawalAmount ?>;
    
    amountInput.addEventListener('input', function() {
        const enteredAmount = parseFloat(this.value);
        if (enteredAmount > maxAmount) {
            this.value = maxAmount;
            alert('The maximum withdrawal amount is ₦' + maxAmount.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        }
    });
});
</script> 