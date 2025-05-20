<?php
// Member Savings Monthly Contribution Update View

$flashMessage = \App\Helpers\Utility::getFlashMessage();

// Set default values for variables that might not be defined
$minimumContribution = $minimumContribution ?? 1000.00;
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Update Monthly Contribution</h1>
                <p class="text-primary-100">Adjust your monthly savings contribution amount</p>
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
                <?php if (isset($recentlyUpdated) && $recentlyUpdated): ?>
                    <!-- Recently Updated Notice -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-md mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-yellow-800">Recently Updated</h3>
                                <div class="mt-2 text-yellow-700">
                                    <p>You have recently updated your monthly contribution amount on <?= date('M d, Y', strtotime($lastUpdateDate)) ?>.</p>
                                    <p class="mt-2">You can only update your contribution amount once every 3 months unless there are special circumstances.</p>
                                    <p class="mt-2">If you need to make another change, please contact the cooperative office.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Update Contribution Form -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Update Monthly Contribution</h2>
                        </div>
                        
                        <div class="p-6">
                            <form action="/Coops_Bichi/public/member/savings/update" method="POST" id="contribution-form">
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Current Contribution Info -->
                                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-blue-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <?php 
                                                // Set default value for minimumContribution if not defined
                                                $minimumContribution = $minimumContribution ?? 1000.00;
                                                ?>
                                                <p class="text-sm text-blue-700">Your current monthly contribution is <strong>₦<?= number_format($currentContribution, 2) ?></strong>.</p>
                                                <p class="text-sm text-blue-700 mt-1">Minimum contribution: <strong>₦<?= number_format($minimumContribution, 2) ?></strong></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- New Contribution Amount -->
                                    <div>
                                        <label for="new_contribution" class="block text-gray-700 font-medium mb-2">New Monthly Contribution (₦) <span class="text-red-500">*</span></label>
                                        <input type="number" id="new_contribution" name="new_contribution" min="<?= $minimumContribution ?>" step="500" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            placeholder="Enter your new monthly contribution amount">
                                        <p class="text-gray-500 text-sm mt-1">Your contribution must be at least ₦<?= number_format($minimumContribution, 2) ?> per month</p>
                                    </div>
                                    
                                    <!-- Effective Date -->
                                    <div>
                                        <label for="effective_date" class="block text-gray-700 font-medium mb-2">Effective Date <span class="text-red-500">*</span></label>
                                        <input type="date" id="effective_date" name="effective_date" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            min="<?= date('Y-m-d', strtotime('+7 days')) ?>" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                                        <p class="text-gray-500 text-sm mt-1">Changes will take effect from this date (minimum 7 days from today)</p>
                                    </div>
                                    
                                    <!-- Reason for Change -->
                                    <div>
                                        <label for="reason" class="block text-gray-700 font-medium mb-2">Reason for Change <span class="text-red-500">*</span></label>
                                        <select id="reason" name="reason" required
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300">
                                            <option value="">Select a reason</option>
                                            <option value="Increased Income">Increased Income</option>
                                            <option value="Financial Goals">Financial Goals</option>
                                            <option value="Financial Difficulty">Financial Difficulty</option>
                                            <option value="Family Changes">Family Changes</option>
                                            <option value="Career Change">Career Change</option>
                                            <option value="Saving for Specific Purpose">Saving for Specific Purpose</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Other Reason -->
                                    <div id="other-reason-container" style="display: none;">
                                        <label for="other_reason" class="block text-gray-700 font-medium mb-2">Specify Other Reason <span class="text-red-500">*</span></label>
                                        <textarea id="other_reason" name="other_reason" rows="3"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            placeholder="Please specify your reason for changing the contribution amount"></textarea>
                                    </div>
                                    
                                    <!-- Additional Comments -->
                                    <div>
                                        <label for="comments" class="block text-gray-700 font-medium mb-2">Additional Comments</label>
                                        <textarea id="comments" name="comments" rows="3"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300"
                                            placeholder="Any additional information you want to provide"></textarea>
                                    </div>
                                    
                                    <!-- Display Projected Savings -->
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h3 class="font-medium text-gray-700 mb-3">Projected Savings (1 Year)</h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="bg-white p-3 rounded border border-gray-200">
                                                <p class="text-sm text-gray-600">Current Monthly: ₦<?= number_format($currentContribution, 2) ?></p>
                                                <p class="text-lg font-bold text-gray-800">₦<?= number_format($currentContribution * 12, 2) ?></p>
                                                <p class="text-xs text-gray-500">Total in 12 months</p>
                                            </div>
                                            <div id="new-contribution-projection" class="bg-white p-3 rounded border border-gray-200">
                                                <p class="text-sm text-gray-600">New Monthly: ₦<span id="new-amount-display">0.00</span></p>
                                                <p class="text-lg font-bold text-primary-600">₦<span id="new-total-display">0.00</span></p>
                                                <p class="text-xs text-gray-500">Total in 12 months</p>
                                            </div>
                                        </div>
                                        <div id="projection-difference" class="mt-3 pt-3 border-t text-center" style="display: none;">
                                            <p class="text-sm">You will save <span id="difference-text" class="font-bold"></span> in 12 months by updating your contribution.</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Terms Agreement -->
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="terms" name="terms" type="checkbox" required
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="terms" class="font-medium text-gray-700">I confirm this contribution update request</label>
                                                <p class="text-gray-500">I understand that this change will take effect from the effective date specified. I am aware that I can only change my contribution amount once every 3 months.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="text-center">
                                        <button type="submit" 
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-save mr-2"></i> Update Monthly Contribution
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
                            
                            <a href="/Coops_Bichi/public/member/savings/calculator" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Savings Calculator
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contribution Guidelines -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Contribution Guidelines</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Minimum Contribution</p>
                                    <p class="text-gray-500">The minimum monthly contribution amount is ₦<?= number_format($minimumContribution, 2) ?>.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Update Frequency</p>
                                    <p class="text-gray-500">You can update your contribution amount once every 3 months.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Implementation</p>
                                    <p class="text-gray-500">Changes take effect from the effective date, which must be at least 7 days from the submission date.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Approval</p>
                                    <p class="text-gray-500">For significant increases or decreases, approval may be required from the cooperative management.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Benefits</p>
                                    <p class="text-gray-500">Higher contributions increase your eligibility for larger loans and dividends at the end of the financial year.</p>
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
    const newContributionInput = document.getElementById('new_contribution');
    const newAmountDisplay = document.getElementById('new-amount-display');
    const newTotalDisplay = document.getElementById('new-total-display');
    const projectionDifference = document.getElementById('projection-difference');
    const differenceText = document.getElementById('difference-text');
    const currentMonthly = <?= $currentContribution ?>;
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
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
    
    // Calculate and display projection
    newContributionInput.addEventListener('input', function() {
        const newAmount = parseFloat(this.value) || 0;
        const newTotal = newAmount * 12;
        const difference = newTotal - (currentMonthly * 12);
        
        newAmountDisplay.textContent = formatNumber(newAmount.toFixed(2));
        newTotalDisplay.textContent = formatNumber(newTotal.toFixed(2));
        
        if (newAmount > 0) {
            projectionDifference.style.display = 'block';
            
            if (difference > 0) {
                differenceText.textContent = '₦' + formatNumber(difference.toFixed(2)) + ' more';
                differenceText.className = 'font-bold text-green-600';
            } else if (difference < 0) {
                differenceText.textContent = '₦' + formatNumber(Math.abs(difference).toFixed(2)) + ' less';
                differenceText.className = 'font-bold text-red-600';
            } else {
                differenceText.textContent = 'the same amount';
                differenceText.className = 'font-bold text-gray-600';
            }
        } else {
            projectionDifference.style.display = 'none';
        }
    });
});
</script> 