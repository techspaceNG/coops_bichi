<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Withdrawal Request Details</h1>
                        <p class="text-primary-100">Request ID: #<?= $withdrawal['reference_id'] ?></p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="/savings/withdrawals" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Withdrawals
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Request Status</h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
                    <div>
                        <div class="flex items-center">
                            <?php if ($withdrawal['status'] === 'pending'): ?>
                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <span class="ml-3 text-lg font-medium text-yellow-800">Pending Review</span>
                            <?php elseif ($withdrawal['status'] === 'approved'): ?>
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-check text-blue-600"></i>
                                </div>
                                <span class="ml-3 text-lg font-medium text-blue-800">Approved</span>
                            <?php elseif ($withdrawal['status'] === 'processed'): ?>
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check-double text-green-600"></i>
                                </div>
                                <span class="ml-3 text-lg font-medium text-green-800">Processed</span>
                            <?php elseif ($withdrawal['status'] === 'rejected'): ?>
                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                                <span class="ml-3 text-lg font-medium text-red-800">Rejected</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($withdrawal['status'] === 'rejected'): ?>
                            <div class="mt-3 bg-red-50 border-l-4 border-red-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Reason for Rejection</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p><?= htmlspecialchars($withdrawal['rejection_reason']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 md:mt-0 text-right">
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="text-base font-medium text-gray-900"><?= date('F d, Y, h:i A', strtotime($withdrawal['updated_at'])) ?></p>
                    </div>
                </div>
                
                <div class="relative">
                    <!-- Timeline Progress Bar -->
                    <div class="hidden md:block absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 z-0"></div>
                    
                    <!-- Timeline Steps -->
                    <div class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Step 1: Submitted -->
                        <div class="flex flex-col items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <p class="mt-2 font-medium text-gray-900">Submitted</p>
                            <p class="mt-1 text-xs text-gray-500"><?= date('M d, Y', strtotime($withdrawal['request_date'])) ?></p>
                        </div>
                        
                        <!-- Step 2: Under Review -->
                        <div class="flex flex-col items-center">
                            <?php if ($withdrawal['status'] !== 'pending'): ?>
                                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                                    <i class="fas fa-search text-white"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-900">Reviewed</p>
                                <p class="mt-1 text-xs text-gray-500"><?= date('M d, Y', strtotime($withdrawal['review_date'])) ?></p>
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-search text-yellow-600"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-500">Under Review</p>
                                <p class="mt-1 text-xs text-gray-500">Pending</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Step 3: Approved/Rejected -->
                        <div class="flex flex-col items-center">
                            <?php if ($withdrawal['status'] === 'approved' || $withdrawal['status'] === 'processed'): ?>
                                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                                    <i class="fas fa-thumbs-up text-white"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-900">Approved</p>
                                <p class="mt-1 text-xs text-gray-500"><?= date('M d, Y', strtotime($withdrawal['approval_date'])) ?></p>
                            <?php elseif ($withdrawal['status'] === 'rejected'): ?>
                                <div class="h-10 w-10 rounded-full bg-red-600 flex items-center justify-center">
                                    <i class="fas fa-thumbs-down text-white"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-900">Rejected</p>
                                <p class="mt-1 text-xs text-gray-500"><?= date('M d, Y', strtotime($withdrawal['rejection_date'])) ?></p>
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-thumbs-up text-gray-400"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-400">Awaiting Decision</p>
                                <p class="mt-1 text-xs text-gray-500">Pending</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Step 4: Processed -->
                        <div class="flex flex-col items-center">
                            <?php if ($withdrawal['status'] === 'processed'): ?>
                                <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-900">Disbursed</p>
                                <p class="mt-1 text-xs text-gray-500"><?= date('M d, Y', strtotime($withdrawal['processed_date'])) ?></p>
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-gray-400"></i>
                                </div>
                                <p class="mt-2 font-medium text-gray-400">Disbursement</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    <?= ($withdrawal['status'] === 'approved') ? 'In Progress' : 'Pending' ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if ($withdrawal['status'] === 'approved'): ?>
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Processing Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Your request has been approved and is currently being processed. Funds will be disbursed to your bank account within 3-5 working days. You will receive a confirmation once the funds have been transferred.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Withdrawal Details -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Withdrawal Details</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Amount Requested</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">₦<?= number_format($withdrawal['amount'], 2) ?></p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Request Date</h3>
                                <p class="mt-1 text-gray-900"><?= date('F d, Y', strtotime($withdrawal['request_date'])) ?></p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Savings Balance Before</h3>
                                <p class="mt-1 text-gray-900">₦<?= number_format($withdrawal['balance_before'], 2) ?></p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Savings Balance After</h3>
                                <p class="mt-1 text-gray-900">
                                    <?php if ($withdrawal['status'] === 'processed'): ?>
                                        ₦<?= number_format($withdrawal['balance_after'], 2) ?>
                                    <?php else: ?>
                                        <span class="text-gray-500">Pending processing</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Purpose</h3>
                                <p class="mt-1 text-gray-900"><?= ucfirst(htmlspecialchars($withdrawal['purpose'])) ?></p>
                                <?php if ($withdrawal['purpose'] === 'other' && !empty($withdrawal['other_purpose'])): ?>
                                    <p class="mt-1 text-gray-600"><?= htmlspecialchars($withdrawal['other_purpose']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Additional Information</h3>
                                <p class="mt-1 text-gray-900">
                                    <?= !empty($withdrawal['additional_info']) ? htmlspecialchars($withdrawal['additional_info']) : '<span class="text-gray-500">None provided</span>' ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 mb-3">Bank Account Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <h4 class="text-xs text-gray-500">Account Name</h4>
                                    <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($withdrawal['account_name']) ?></p>
                                </div>
                                
                                <div>
                                    <h4 class="text-xs text-gray-500">Bank Name</h4>
                                    <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($withdrawal['bank_name']) ?></p>
                                </div>
                                
                                <div>
                                    <h4 class="text-xs text-gray-500">Account Number</h4>
                                    <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($withdrawal['account_number']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Request Summary</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Reference ID:</p>
                                <p class="text-sm font-medium text-gray-900">#<?= $withdrawal['reference_id'] ?></p>
                            </div>
                            
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Request Type:</p>
                                <p class="text-sm font-medium text-primary-600">Savings Withdrawal</p>
                            </div>
                            
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Current Status:</p>
                                <p class="text-sm font-medium 
                                    <?php if ($withdrawal['status'] === 'pending'): echo 'text-yellow-600';
                                    elseif ($withdrawal['status'] === 'approved'): echo 'text-blue-600';
                                    elseif ($withdrawal['status'] === 'processed'): echo 'text-green-600';
                                    elseif ($withdrawal['status'] === 'rejected'): echo 'text-red-600';
                                    endif; ?>">
                                    <?= ucfirst($withdrawal['status']) ?>
                                </p>
                            </div>
                            
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Amount:</p>
                                <p class="text-sm font-medium text-gray-900">₦<?= number_format($withdrawal['amount'], 2) ?></p>
                            </div>
                            
                            <?php if ($withdrawal['status'] === 'processed' && !empty($withdrawal['processed_date'])): ?>
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Processed Date:</p>
                                <p class="text-sm font-medium text-gray-900"><?= date('M d, Y', strtotime($withdrawal['processed_date'])) ?></p>
                            </div>
                            
                            <div class="flex justify-between">
                                <p class="text-sm text-gray-500">Transaction ID:</p>
                                <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($withdrawal['transaction_id']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="bg-primary-50 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-question-circle text-primary-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-medium text-primary-800">Need Help?</h3>
                                <p class="mt-1 text-primary-700">If you have any questions about this withdrawal request, please contact the cooperative society office.</p>
                                <div class="mt-4">
                                    <a href="/contact" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        Contact Support
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

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 