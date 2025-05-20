<?php
// Member Household Purchase Application View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_household_order');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Apply for Household Purchase</h1>
                <p class="text-primary-100">Purchase household items with the FCET Bichi Staff Multipurpose Cooperative Society</p>
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
            <!-- Main Content: Application Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Household Purchase Application</h2>
                        <p class="text-gray-600 text-sm">Fill the form below to apply for household items</p>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="bg-red-50 text-red-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <?php if (isset($errors['general'])): ?>
                                            <p><?= htmlspecialchars($errors['general']) ?></p>
                                        <?php else: ?>
                                            <p>Please correct the following errors:</p>
                                            <ul class="mt-2 list-disc list-inside">
                                                <?php foreach ($errors as $field => $error): ?>
                                                    <li><?= htmlspecialchars($error) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($hasActiveApplication) && $hasActiveApplication): ?>
                            <div class="bg-yellow-50 text-yellow-800 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">You have an active household purchase application</p>
                                        <p class="mt-1">Your application is being processed. You will be notified once a decision is made.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/Coops_Bichi/public/member/household/order" method="POST" class="space-y-6">
                            <div>
                                <label for="item_name" class="block text-gray-700 font-medium mb-2">Item Name</label>
                                <input type="text" id="item_name" name="item_name"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['item_name']) ? 'border-red-500' : 'border-gray-300' ?>"
                                    value="<?= isset($_POST['item_name']) ? htmlspecialchars($_POST['item_name']) : '' ?>" placeholder="Enter the name of the item">
                                <?php if (isset($errors['item_name'])): ?>
                                    <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['item_name']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label for="household_amount" class="block text-gray-700 font-medium mb-2">Estimated Cost (₦)</label>
                                <input type="number" id="household_amount" name="household_amount" step="0.01" min="1000" 
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['household_amount']) ? 'border-red-500' : 'border-gray-300' ?>"
                                    value="<?= isset($_POST['household_amount']) ? htmlspecialchars($_POST['household_amount']) : '' ?>" placeholder="Enter the estimated cost">
                                <?php if (isset($errors['household_amount'])): ?>
                                    <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['household_amount']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label for="purchase_duration" class="block text-gray-700 font-medium mb-2">Payment Duration (Months)</label>
                                <select id="purchase_duration" name="purchase_duration" 
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['purchase_duration']) ? 'border-red-500' : 'border-gray-300' ?>" required>
                                    <option value="" disabled <?= !isset($_POST['purchase_duration']) ? 'selected' : '' ?>>Select duration</option>
                                    <?php for ($i = 6; $i <= 24; $i++): ?>
                                        <option value="<?= $i ?>" <?= isset($_POST['purchase_duration']) && (int)$_POST['purchase_duration'] === $i ? 'selected' : '' ?>><?= $i ?> months</option>
                                    <?php endfor; ?>
                                </select>
                                <?php if (isset($errors['purchase_duration'])): ?>
                                    <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['purchase_duration']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label for="ip_figure" class="block text-gray-700 font-medium mb-2">Monthly Payment (₦)</label>
                                <input type="number" id="ip_figure" name="ip_figure" step="0.01" min="500" 
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['ip_figure']) ? 'border-red-500' : 'border-gray-300' ?>"
                                    value="<?= isset($_POST['ip_figure']) ? htmlspecialchars($_POST['ip_figure']) : '' ?>" placeholder="Enter your preferred monthly payment">
                                <?php if (isset($errors['ip_figure'])): ?>
                                    <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['ip_figure']) ?></p>
                                <?php endif; ?>
                                <p class="text-gray-500 text-sm mt-1">Amount to be deducted from your salary each month</p>
                            </div>
                            
                            <!-- Payment Summary Section -->
                            <div id="payment-summary" class="bg-gray-50 p-4 rounded-lg border border-gray-200 hidden">
                                <h3 class="text-lg font-medium text-gray-800 mb-3">Payment Summary</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Item Cost:</span>
                                        <span id="summary-item-cost" class="font-medium">₦0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Admin Charge (5%):</span>
                                        <span id="summary-admin-charge" class="font-medium">₦0.00</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                        <span class="text-gray-700 font-medium">Total Payable:</span>
                                        <span id="summary-total-amount" class="font-medium">₦0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-700 font-medium">Monthly Payment:</span>
                                        <span id="summary-monthly-payment" class="font-medium">₦0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span id="summary-duration" class="font-medium">0 months</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="vendor_details" class="block text-gray-700 font-medium mb-2">Vendor Details (Optional)</label>
                                <textarea id="vendor_details" name="vendor_details" rows="2"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 <?= isset($errors['vendor_details']) ? 'border-red-500' : 'border-gray-300' ?>"
                                    placeholder="Provide details of the vendor if you have a specific one in mind"><?= isset($_POST['vendor_details']) ? htmlspecialchars($_POST['vendor_details']) : '' ?></textarea>
                                <?php if (isset($errors['vendor_details'])): ?>
                                    <p class="text-red-500 text-sm mt-1"><?= htmlspecialchars($errors['vendor_details']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-span-2">
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
                            
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="agreement" name="agreement" required
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="agreement" class="font-medium text-gray-700">I agree to the terms and conditions</label>
                                        <p class="text-gray-500">By applying for this household purchase, I confirm that all information provided is accurate. I understand that monthly payments will be deducted from my salary.</p>
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
            
            <!-- Sidebar -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">About Household Purchases</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Convenient Payments</p>
                                    <p class="text-gray-500">Pay for household items in affordable monthly installments.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Wide Range of Items</p>
                                    <p class="text-gray-500">Choose from electronics, furniture, kitchen appliances, and more.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">5% Admin Charge</p>
                                    <p class="text-gray-500">A 5% administrative charge is applied to cover processing costs.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-primary-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3 text-sm">
                                    <p class="font-medium text-gray-700">Quick Processing</p>
                                    <p class="text-gray-500">Applications are reviewed within 5 working days.</p>
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
                        <p class="text-gray-600 mb-4">If you have any questions about household purchases or need assistance with your application, please contact our support team.</p>
                        
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
document.addEventListener('DOMContentLoaded', function() {
    const itemCostInput = document.getElementById('household_amount');
    const monthlyPaymentInput = document.getElementById('ip_figure');
    const durationSelect = document.getElementById('purchase_duration');
    const paymentSummary = document.getElementById('payment-summary');
    
    // Summary elements
    const summaryItemCost = document.getElementById('summary-item-cost');
    const summaryAdminCharge = document.getElementById('summary-admin-charge');
    const summaryTotalAmount = document.getElementById('summary-total-amount');
    const summaryMonthlyPayment = document.getElementById('summary-monthly-payment');
    const summaryDuration = document.getElementById('summary-duration');
    
    // Function to format currency
    function formatCurrency(amount) {
        return '₦' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
    
    // Function to calculate monthly payment and update summary
    function calculateMonthlyPayment() {
        const itemCost = parseFloat(itemCostInput.value);
        const duration = parseInt(durationSelect.value);
        
        if (!isNaN(itemCost) && itemCost >= 1000 && !isNaN(duration) && duration > 0) {
            // Calculate admin charge (5%)
            const adminCharge = itemCost * 0.05;
            const totalWithAdminCharge = itemCost + adminCharge;
            const monthlyPayment = Math.ceil(totalWithAdminCharge / duration);
            
            // Update inputs
            monthlyPaymentInput.value = monthlyPayment;
            
            // Update summary
            summaryItemCost.textContent = formatCurrency(itemCost);
            summaryAdminCharge.textContent = formatCurrency(adminCharge);
            summaryTotalAmount.textContent = formatCurrency(totalWithAdminCharge);
            summaryMonthlyPayment.textContent = formatCurrency(monthlyPayment);
            summaryDuration.textContent = duration + ' months';
            
            // Show summary
            paymentSummary.classList.remove('hidden');
        } else {
            // Hide summary if inputs are invalid
            paymentSummary.classList.add('hidden');
        }
    }
    
    // Update calculation when item cost changes
    itemCostInput.addEventListener('input', calculateMonthlyPayment);
    
    // Update calculation when duration changes
    durationSelect.addEventListener('change', calculateMonthlyPayment);
    
    // Initial calculation if values are present
    if (itemCostInput.value && durationSelect.value) {
        calculateMonthlyPayment();
    }
});
</script> 