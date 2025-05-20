<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Loan Application Details</h1>
                        <p class="text-primary-100">Application ID: <?= htmlspecialchars($application['id']) ?></p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="/loans/applications" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Applications
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Application Status -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Application Status</h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between">
                    <div class="mb-4 sm:mb-0">
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="mt-1 px-3 py-1 text-sm rounded-full inline-flex items-center
                            <?php
                            switch ($application['status']) {
                                case 'pending':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'approved':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'rejected':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                case 'completed':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?php if ($application['status'] === 'pending'): ?>
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            <?php elseif ($application['status'] === 'approved'): ?>
                                <i class="fas fa-check-circle mr-2"></i>
                            <?php elseif ($application['status'] === 'rejected'): ?>
                                <i class="fas fa-times-circle mr-2"></i>
                            <?php elseif ($application['status'] === 'completed'): ?>
                                <i class="fas fa-check-double mr-2"></i>
                            <?php endif; ?>
                            <?= ucfirst(htmlspecialchars($application['status'])) ?>
                        </span>
                    </div>
                    
                    <div class="mb-4 sm:mb-0">
                        <p class="text-sm text-gray-500">Application Date</p>
                        <p class="mt-1 text-lg font-medium text-gray-900"><?= date('F d, Y', strtotime($application['application_date'])) ?></p>
                    </div>
                    
                    <?php if (!empty($application['approval_date'])): ?>
                        <div class="mb-4 sm:mb-0">
                            <p class="text-sm text-gray-500">Approval Date</p>
                            <p class="mt-1 text-lg font-medium text-gray-900"><?= date('F d, Y', strtotime($application['approval_date'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($application['disbursement_date'])): ?>
                        <div>
                            <p class="text-sm text-gray-500">Disbursement Date</p>
                            <p class="mt-1 text-lg font-medium text-gray-900"><?= date('F d, Y', strtotime($application['disbursement_date'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($application['status'] === 'rejected' && !empty($application['rejection_reason'])): ?>
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Reason for Rejection</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p><?= nl2br(htmlspecialchars($application['rejection_reason'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($application['status'] === 'pending'): ?>
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Application Processing</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Your application is currently being processed by the cooperative society. You will be notified once a decision has been made.</p>
                                    <?php if (!empty($application['estimated_completion_date'])): ?>
                                        <p class="mt-1">Estimated completion: <?= date('F d, Y', strtotime($application['estimated_completion_date'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-4">
                                    <a href="/loans/cancel/<?= $application['id'] ?>" 
                                       onclick="return confirm('Are you sure you want to cancel this application?')"
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-times mr-2"></i> Cancel Application
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Loan Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 md:mb-0">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Loan Details</h2>
                    </div>
                    
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Loan Amount</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">₦<?= number_format($application['loan_amount'], 2) ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Monthly Payment (IP Figure)</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">₦<?= number_format($application['monthly_payment'], 2) ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Repayment Period</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900"><?= $application['repayment_period'] ?> months</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Admin Charges</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">5% flat rate</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Repayment Amount</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">₦<?= number_format($application['total_repayment'], 2) ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                                <dd class="mt-1 text-lg font-medium text-gray-900"><?= ucfirst(str_replace('_', ' ', htmlspecialchars($application['purpose']))) ?></dd>
                            </div>
                        </dl>
                        
                        <?php if (!empty($application['additional_info'])): ?>
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-sm font-medium text-gray-500">Additional Information</h3>
                                <p class="mt-2 text-gray-700"><?= nl2br(htmlspecialchars($application['additional_info'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Repayment Schedule</h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if ($application['status'] === 'approved' || $application['status'] === 'completed'): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php 
                                        // Sample repayment schedule
                                        $startDate = !empty($application['disbursement_date']) ? strtotime($application['disbursement_date']) : strtotime($application['approval_date']);
                                        $monthlyPayment = $application['monthly_payment'];
                                        
                                        for ($i = 1; $i <= $application['repayment_period']; $i++): 
                                            $paymentDate = strtotime("+$i month", $startDate);
                                        ?>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"><?= $i ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"><?= date('M d, Y', $paymentDate) ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">₦<?= number_format($monthlyPayment, 2) ?></td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <h3 class="text-base font-medium text-gray-800 mb-1">Repayment Schedule Not Available</h3>
                                <p class="text-sm text-gray-500">Repayment schedule will be available once your loan is approved.</p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($application['status'] === 'approved' || $application['status'] === 'completed'): ?>
                            <div class="mt-6 text-center">
                                <a href="/loans/schedule/<?= $application['id'] ?>/download" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-download mr-2"></i> Download Schedule
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 