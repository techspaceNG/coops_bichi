<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Update Monthly Contribution</h1>
                        <p class="text-primary-100">Adjust your monthly savings contribution amount</p>
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
            <!-- Update Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Contribution Update Form</h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($_SESSION['update_success'])): ?>
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            <?= htmlspecialchars($_SESSION['update_success']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php unset($_SESSION['update_success']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['update_error'])): ?>
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            <?= htmlspecialchars($_SESSION['update_error']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php unset($_SESSION['update_error']); ?>
                        <?php endif; ?>
                        
                        <?php if (!$can_update): ?>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700 font-medium">
                                            You are not eligible to update your monthly contribution at this time
                                        </p>
                                        <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                            <?php if (isset($recent_update)): ?>
                                                <li>Your last update was less than 3 months ago on <?= date('F d, Y', strtotime($last_update_date)) ?>.</li>
                                                <li>You will be eligible to make another update on <?= date('F d, Y', strtotime($next_eligible_date)) ?>.</li>
                                            <?php endif; ?>
                                            
                                            <?php if (isset($has_pending_update)): ?>
                                                <li>You have a pending update request that is still under review. You cannot submit a new request until the current one is processed.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/savings/update/submit" method="POST" class="space-y-6" <?= !$can_update ? 'disabled' : '' ?>>
                            <div>
                                <label for="current_amount" class="block text-sm font-medium text-gray-700 mb-1">Current Monthly Contribution</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₦</span>
                                    </div>
                                    <input type="text" id="current_amount" name="current_amount" value="<?= number_format($member['monthly_deduction'], 2) ?>" 
                                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md bg-gray-50" readonly>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Your current monthly contribution automatically deducted from your salary</p>
                            </div>
                            
                            <div>
                                <label for="new_amount" class="block text-sm font-medium text-gray-700 mb-1">New Monthly Contribution</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₦</span>
                                    </div>
                                    <input type="number" id="new_amount" name="new_amount" min="<?= $min_contribution ?>" step="500" placeholder="Enter new amount" 
                                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                                           <?= !$can_update ? 'disabled' : '' ?> required>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Minimum contribution: ₦<?= number_format($min_contribution, 2) ?></p>
                                <p class="mt-1 text-sm text-gray-500">Please enter the new amount you would like to contribute monthly</p>
                            </div>
                            
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Change</label>
                                <select id="reason" name="reason" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                        <?= !$can_update ? 'disabled' : '' ?> required>
                                    <option value="">Select a reason</option>
                                    <option value="increase">Increased financial capacity</option>
                                    <option value="decrease">Financial constraints</option>
                                    <option value="adjustment">Adjustment to financial goals</option>
                                    <option value="other">Other (Please specify)</option>
                                </select>
                            </div>
                            
                            <div id="otherReasonContainer" class="hidden">
                                <label for="other_reason" class="block text-sm font-medium text-gray-700 mb-1">Specify Other Reason</label>
                                <input type="text" id="other_reason" name="other_reason" 
                                       class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       <?= !$can_update ? 'disabled' : '' ?>>
                            </div>
                            
                            <div>
                                <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-1">Additional Information (Optional)</label>
                                <textarea id="additional_info" name="additional_info" rows="3" 
                                          class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                          <?= !$can_update ? 'disabled' : '' ?>></textarea>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="confirmation" name="confirmation" type="checkbox" 
                                           class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded"
                                           <?= !$can_update ? 'disabled' : '' ?> required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="confirmation" class="font-medium text-gray-700">I confirm this change to my monthly contribution</label>
                                    <p class="text-gray-500">I understand that this change will take effect from the next payroll cycle and will affect my monthly deductions.</p>
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" 
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                        <?= !$can_update ? 'disabled' : '' ?>>
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Information -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-800">Update Eligibility</h3>
                                <ul class="mt-2 text-gray-600 list-disc list-inside space-y-1">
                                    <li>Monthly contribution updates are limited to once every 3 months</li>
                                    <li>Minimum contribution is ₦<?= number_format($min_contribution, 2) ?></li>
                                    <li>Changes will take effect on the next payroll cycle</li>
                                </ul>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Processing Timeline</h3>
                                <p class="mt-2 text-gray-600">Update requests are typically processed within 3-5 working days after submission. You will receive a notification once the request is processed.</p>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">Benefits of Higher Contributions</h3>
                                <ul class="mt-2 text-gray-600 list-disc list-inside space-y-1">
                                    <li>Higher loan eligibility based on your savings balance</li>
                                    <li>Increased dividends at the end of the financial year</li>
                                    <li>Better long-term financial security</li>
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
                                    <i class="fas fa-calculator text-primary-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-medium text-primary-800">Savings Calculator</h3>
                                <p class="mt-1 text-primary-700">Want to estimate your future savings balance? Use our savings calculator to plan ahead.</p>
                                <div class="mt-4">
                                    <a href="/savings/calculator" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
                                        Go to Savings Calculator <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
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
    const reasonSelect = document.getElementById('reason');
    const otherReasonContainer = document.getElementById('otherReasonContainer');
    const otherReasonInput = document.getElementById('other_reason');
    
    // Show/hide the "Other reason" input field based on the selection
    reasonSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherReasonContainer.classList.remove('hidden');
            otherReasonInput.setAttribute('required', 'required');
        } else {
            otherReasonContainer.classList.add('hidden');
            otherReasonInput.removeAttribute('required');
        }
    });
    
    // Form validation for new amount
    const newAmountInput = document.getElementById('new_amount');
    const currentAmountInput = document.getElementById('current_amount');
    const minContribution = <?= $min_contribution ?>;
    
    newAmountInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < minContribution) {
            this.setCustomValidity(`Minimum contribution amount is ₦${minContribution.toLocaleString()}`);
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 