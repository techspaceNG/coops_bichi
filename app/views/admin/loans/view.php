<!-- Admin Loan View -->
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <a href="/Coops_Bichi/public/admin/loans" class="text-blue-600 hover:text-blue-800 mr-2">
            <i class="fas fa-arrow-left"></i> Back to Loans
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Loan Details</h1>
        <div>
            <!-- All action buttons removed to make view-only -->
        </div>
    </div>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 <?= $_SESSION['flash_type'] === 'success' ? 'bg-green-100 border border-green-200 text-green-700' : 'bg-red-100 border border-red-200 text-red-700' ?> px-4 py-3 rounded relative">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    
    <!-- Loan Information -->
    <?php if (isset($loan)): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Basic Loan Information -->
        <div class="col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">Loan Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Loan ID</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['id'] ?? '') ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= isset($loan['status']) && $loan['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                    (isset($loan['status']) && $loan['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    (isset($loan['status']) && $loan['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) ?>">
                                <?= ucfirst(htmlspecialchars($loan['status'] ?? 'Unknown')) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Principal Amount</h3>
                        <p class="mt-1 text-sm text-gray-900">₦<?= number_format($loan['loan_amount'] ?? 0, 2) ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Current Balance</h3>
                        <p class="mt-1 text-sm font-bold text-gray-900">₦<?= number_format($loan['balance'] ?? 0, 2) ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Interest Rate</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= number_format($loan['interest_rate'] ?? 0, 2) ?>%</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Term</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['repayment_period'] ?? '0') ?> months</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Monthly Payment</h3>
                        <p class="mt-1 text-sm text-gray-900">₦<?= number_format($loan['ip_figure'] ?? 0, 2) ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Disbursement Date</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= isset($loan['created_at']) ? date('M d, Y', strtotime($loan['created_at'])) : '--' ?></p>
                    </div>
                    <div class="col-span-2">
                        <h3 class="text-sm font-medium text-gray-500">Description/Purpose</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['description'] ?? 'No description provided') ?></p>
                    </div>
                </div>
                
                <!-- Loan Actions removed to make view-only -->
            </div>
        </div>
        
        <!-- Borrower Information -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-800">Borrower Information</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Member Name</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['member_name'] ?? '') ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Coop Number</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['coop_no'] ?? '') ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Email</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['email'] ?? '') ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Phone</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($loan['phone'] ?? '') ?></p>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="/Coops_Bichi/public/admin/members/view/<?= $loan['member_id'] ?? '' ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        <i class="fas fa-user mr-2"></i> View Member Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Repayment History -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-800">Repayment History</h2>
                <!-- Add Payment button removed to make view-only -->
            </div>
            
            <?php if (empty($repayments)): ?>
                <div class="p-6 text-center text-gray-500">
                    <p>No repayment records found for this loan.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <!-- Actions column removed to make view-only -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($repayments as $repayment): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($repayment['id'] ?? '') ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₦<?= number_format($repayment['amount'] ?? 0, 2) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= isset($repayment['created_at']) ? date('M d, Y', strtotime($repayment['created_at'])) : '--' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($repayment['payment_method'] ?? 'Unknown') ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($repayment['reference'] ?? '--') ?></td>
                                    <!-- Actions column removed to make view-only -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-red-500 mb-4">
            <i class="fas fa-exclamation-circle text-5xl"></i>
        </div>
        <h2 class="text-xl font-medium text-gray-900 mb-2">Loan Not Found</h2>
        <p class="text-gray-500 mb-4">The loan you are looking for does not exist or has been removed.</p>
        <a href="/Coops_Bichi/public/admin/loans" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
            <i class="fas fa-arrow-left mr-2"></i> Return to Loans List
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Additional JavaScript functionality if needed
    });
</script> 