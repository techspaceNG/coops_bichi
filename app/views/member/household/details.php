<?php
// Member Household Purchase Details View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_household_details');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Household Purchase Details</h1>
                    <p class="text-primary-100">View details and payment history for your household purchase</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full bg-white text-primary-700 font-semibold">
                    <?= ucfirst(htmlspecialchars($purchase['status'])) ?>
                </span>
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
                <!-- Purchase Details -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Item Details</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-sm text-gray-600">Item Description</p>
                                <p class="font-medium text-gray-800">
                                    <?= htmlspecialchars($purchase['item_description']) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Item Category</p>
                                <p class="font-medium text-gray-800">
                                    <?= ucfirst(htmlspecialchars($purchase['item_category'])) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Purchase Date</p>
                                <p class="font-medium text-gray-800">
                                    <?= date('F d, Y', strtotime($purchase['created_at'])) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Purchase ID</p>
                                <p class="font-medium text-gray-800">
                                    <?= htmlspecialchars($purchase['id']) ?>
                                </p>
                            </div>
                        </div>
                        
                        <?php if (!empty($purchase['vendor_details'])): ?>
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-1">Vendor Details</p>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">
                                    <?= nl2br(htmlspecialchars($purchase['vendor_details'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="border-t pt-6">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Payment Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <p class="text-sm text-gray-600">Total Cost</p>
                                    <p class="font-medium text-gray-800">
                                        ₦<?= number_format($purchase['item_cost'], 2) ?>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-600">Monthly Payment</p>
                                    <p class="font-medium text-gray-800">
                                        ₦<?= number_format($purchase['monthly_payment'], 2) ?>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-600">Remaining Balance</p>
                                    <p class="font-medium text-gray-800">
                                        ₦<?= number_format($purchase['remaining_balance'], 2) ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-gray-600">Payment Progress</span>
                                    <span class="text-xs font-medium text-gray-700">
                                        <?= round(100 - (($purchase['remaining_balance'] / $purchase['item_cost']) * 100)) ?>% Paid
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: <?= round(100 - (($purchase['remaining_balance'] / $purchase['item_cost']) * 100)) ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Expected Completion</p>
                                    <p class="font-medium text-gray-800">
                                        <?php
                                        $monthsRemaining = ceil($purchase['remaining_balance'] / $purchase['monthly_payment']);
                                        $completionDate = date('F Y', strtotime("+{$monthsRemaining} months"));
                                        echo $completionDate;
                                        ?>
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Months Remaining</p>
                                    <p class="font-medium text-gray-800">
                                        <?= $monthsRemaining ?> month<?= $monthsRemaining !== 1 ? 's' : '' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment History -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Payment History</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($paymentHistory) && !empty($paymentHistory)): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance After</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($paymentHistory as $payment): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M d, Y', strtotime($payment['date'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                                ₦<?= number_format($payment['amount'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($payment['receipt_no']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                ₦<?= number_format($payment['balance_after'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                                <p>No payment records available</p>
                            </div>
                        <?php endif; ?>
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
                            <a href="/Coops_Bichi/public/member/household" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Back to Household Dashboard
                            </a>
                            
                            <?php if ($purchase['status'] !== 'completed'): ?>
                                <button id="makePaymentBtn" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                    Make Extra Payment
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Delivery Information -->
                <?php if (isset($purchase['delivery_date']) && !empty($purchase['delivery_date'])): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Delivery Information</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-600">Delivery Date</p>
                                    <p class="font-medium text-gray-800">
                                        <?= date('F d, Y', strtotime($purchase['delivery_date'])) ?>
                                    </p>
                                </div>
                                
                                <?php if (!empty($purchase['delivery_location'])): ?>
                                    <div>
                                        <p class="text-sm text-gray-600">Delivery Location</p>
                                        <p class="font-medium text-gray-800">
                                            <?= htmlspecialchars($purchase['delivery_location']) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($purchase['delivery_notes'])): ?>
                                    <div>
                                        <p class="text-sm text-gray-600">Delivery Notes</p>
                                        <p class="font-medium text-gray-800">
                                            <?= htmlspecialchars($purchase['delivery_notes']) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Support Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Need Help?</h2>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">If you have any questions about this purchase or need assistance, please contact our support team.</p>
                        
                        <a href="/Coops_Bichi/public/contact" class="block bg-gray-100 hover:bg-gray-200 text-center py-3 px-4 rounded-lg font-medium text-gray-800">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($purchase['status'] !== 'completed'): ?>
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Make Extra Payment</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-6">
            <form id="extraPaymentForm" action="/Coops_Bichi/public/member/household/details/<?= $purchase['id'] ?>/payment" method="POST" class="space-y-4">
                <div>
                    <label for="payment_amount" class="block text-gray-700 font-medium mb-2">Payment Amount (₦)</label>
                    <input type="number" id="payment_amount" name="payment_amount" min="500" max="<?= $purchase['remaining_balance'] ?>" step="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600" 
                        placeholder="Enter payment amount" required>
                    <p class="text-gray-500 text-sm mt-1">Minimum: ₦500 | Maximum: ₦<?= number_format($purchase['remaining_balance'], 2) ?></p>
                </div>
                
                <div>
                    <label for="payment_method" class="block text-gray-700 font-medium mb-2">Payment Method</label>
                    <select id="payment_method" name="payment_method"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600" required>
                        <option value="" disabled selected>Select payment method</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="deduction">Salary Deduction</option>
                    </select>
                </div>
                
                <div class="pt-4 border-t">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg">
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const makePaymentBtn = document.getElementById('makePaymentBtn');
    const paymentModal = document.getElementById('paymentModal');
    const closeModal = document.getElementById('closeModal');
    
    if (makePaymentBtn && paymentModal && closeModal) {
        makePaymentBtn.addEventListener('click', function() {
            paymentModal.classList.remove('hidden');
        });
        
        closeModal.addEventListener('click', function() {
            paymentModal.classList.add('hidden');
        });
        
        window.addEventListener('click', function(event) {
            if (event.target === paymentModal) {
                paymentModal.classList.add('hidden');
            }
        });
    }
});
</script>
<?php endif; ?> 