<?php
// Member Savings Withdrawal Detail View

$flashMessage = \App\Helpers\Utility::getFlashMessage();
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Withdrawal Request Details</h1>
                <p class="text-primary-100">View detailed information about your withdrawal request</p>
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
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Request Information</h2>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            <?php
                            switch($withdrawal['status']) {
                                case 'pending':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'approved':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                case 'completed':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'rejected':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?= ucfirst($withdrawal['status']) ?>
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Request ID</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900"><?= $withdrawal['id'] ?></p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Date Requested</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900"><?= date('M d, Y', strtotime($withdrawal['created_at'])) ?></p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Withdrawal Amount</h3>
                                <p class="mt-1 text-lg font-semibold text-primary-600">₦<?= number_format($withdrawal['amount'], 2) ?></p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Payment Method</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($withdrawal['payment_method'] ?? 'Bank Transfer') ?></p>
                            </div>
                            
                            <?php if ($withdrawal['processed_at']): ?>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date Processed</h3>
                                    <p class="mt-1 text-lg font-semibold text-gray-900"><?= date('M d, Y', strtotime($withdrawal['processed_at'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-500">Reason for Withdrawal</h3>
                                <p class="mt-1 text-base text-gray-900"><?= htmlspecialchars($withdrawal['purpose']) ?></p>
                            </div>
                            
                            <?php if ($withdrawal['bank_name'] ?? false): ?>
                                <div class="md:col-span-2 mt-2 pt-4 border-t">
                                    <h3 class="text-base font-medium text-gray-700 mb-2">Bank Details</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Bank Name</h4>
                                            <p class="mt-1 text-base text-gray-900"><?= htmlspecialchars($withdrawal['bank_name']) ?></p>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Account Number</h4>
                                            <p class="mt-1 text-base text-gray-900"><?= htmlspecialchars($withdrawal['account_number']) ?></p>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Account Name</h4>
                                            <p class="mt-1 text-base text-gray-900"><?= htmlspecialchars($withdrawal['account_name']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($withdrawal['admin_remarks'] && ($withdrawal['status'] === 'approved' || $withdrawal['status'] === 'rejected')): ?>
                                <div class="md:col-span-2 mt-2 pt-4 border-t">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Admin Remarks</h3>
                                    <div class="p-4 rounded-lg bg-gray-50">
                                        <p class="text-base text-gray-900"><?= htmlspecialchars($withdrawal['admin_remarks']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($withdrawal['status'] === 'pending'): ?>
                            <div class="mt-8 pt-6 border-t">
                                <div class="flex flex-col sm:flex-row sm:justify-end gap-4">
                                    <a href="/Coops_Bichi/public/member/savings/withdraw/cancel/<?= $withdrawal['id'] ?>" 
                                        class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="return confirm('Are you sure you want to cancel this withdrawal request?');">
                                        Cancel Request
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (isset($timeline) && !empty($timeline)): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Request Timeline</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <?php foreach ($timeline as $index => $item): ?>
                                        <li>
                                            <div class="relative pb-8">
                                                <?php if ($index !== count($timeline) - 1): ?>
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                <?php endif; ?>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                            <?php
                                                            switch($item['type']) {
                                                                case 'created':
                                                                    echo 'bg-blue-500';
                                                                    break;
                                                                case 'approved':
                                                                    echo 'bg-green-500';
                                                                    break;
                                                                case 'rejected':
                                                                    echo 'bg-red-500';
                                                                    break;
                                                                case 'completed':
                                                                    echo 'bg-purple-500';
                                                                    break;
                                                                default:
                                                                    echo 'bg-gray-500';
                                                            }
                                                            ?>">
                                                            <i class="fas fa-<?= $item['icon'] ?? 'circle' ?> text-white"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($item['description']) ?></p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time datetime="<?= $item['datetime'] ?>"><?= date('M d, Y', strtotime($item['datetime'])) ?></time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
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
                            <a href="/Coops_Bichi/public/member/savings/withdrawals" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Back to Withdrawals
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/savings" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Savings Dashboard
                            </a>
                            
                            <?php if ($withdrawal['status'] !== 'pending'): ?>
                                <a href="/Coops_Bichi/public/member/savings/withdraw" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                    New Withdrawal Request
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Status Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Status Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="rounded-md p-4 <?= $withdrawal['status'] === 'pending' ? 'bg-yellow-50' : 'bg-gray-50' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Pending</h3>
                                        <div class="mt-1 text-sm text-yellow-700">
                                            <p>Your request is under review by the cooperative staff.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rounded-md p-4 <?= $withdrawal['status'] === 'approved' ? 'bg-blue-50' : 'bg-gray-50' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Approved</h3>
                                        <div class="mt-1 text-sm text-blue-700">
                                            <p>Your request has been approved and is being processed for payment.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rounded-md p-4 <?= $withdrawal['status'] === 'completed' ? 'bg-green-50' : 'bg-gray-50' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-green-800">Completed</h3>
                                        <div class="mt-1 text-sm text-green-700">
                                            <p>Funds have been disbursed as requested. Please check your account.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rounded-md p-4 <?= $withdrawal['status'] === 'rejected' ? 'bg-red-50' : 'bg-gray-50' ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-times-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Rejected</h3>
                                        <div class="mt-1 text-sm text-red-700">
                                            <p>Your request has been declined. Please check admin remarks for details.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 