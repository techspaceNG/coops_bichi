<?php
// Member Household Application Details View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_household_application_details');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Application Details</h1>
                <p class="text-primary-100">Viewing details for your household purchase application</p>
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
                <!-- Application Details -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Application Information</h2>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            <?php
                            $statusClass = '';
                            switch($application['status']) {
                                case 'pending':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'approved':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'rejected':
                                    $statusClass = 'bg-red-100 text-red-800';
                                    break;
                                case 'delivered':
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    break;
                                case 'completed':
                                    $statusClass = 'bg-purple-100 text-purple-800';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                            }
                            echo $statusClass;
                            ?>
                        ">
                            <?= ucfirst(htmlspecialchars($application['status'])) ?>
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-sm text-gray-600">Application Date</p>
                                <p class="font-medium text-gray-800">
                                    <?= date('F d, Y', strtotime($application['created_at'])) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Application ID</p>
                                <p class="font-medium text-gray-800">
                                    <?= htmlspecialchars($application['id']) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Item Category</p>
                                <p class="font-medium text-gray-800">
                                    <?= ucfirst(htmlspecialchars($application['item_category'])) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-medium text-gray-800">
                                    ₦<?= number_format($application['item_cost'], 2) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Monthly Payment</p>
                                <p class="font-medium text-gray-800">
                                    ₦<?= number_format($application['monthly_payment'], 2) ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Last Updated</p>
                                <p class="font-medium text-gray-800">
                                    <?= date('F d, Y', strtotime($application['updated_at'])) ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-1">Item Description</p>
                            <p class="text-gray-800 bg-gray-50 p-3 rounded">
                                <?= nl2br(htmlspecialchars($application['item_description'])) ?>
                            </p>
                        </div>
                        
                        <?php if (!empty($application['vendor_details'])): ?>
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-1">Vendor Details</p>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">
                                    <?= nl2br(htmlspecialchars($application['vendor_details'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($application['admin_remark'])): ?>
                            <div class="mb-6 border-t pt-6">
                                <p class="text-sm text-gray-600 mb-1">Admin Remark</p>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">
                                    <?= nl2br(htmlspecialchars($application['admin_remark'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($application['status'] === 'rejected'): ?>
                            <div class="bg-red-50 border border-red-100 text-red-800 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">Your application has been rejected</p>
                                        <?php if (!empty($application['reject_reason'])): ?>
                                            <p class="mt-1">Reason: <?= htmlspecialchars($application['reject_reason']) ?></p>
                                        <?php endif; ?>
                                        <p class="mt-2">You can apply for a new household purchase from your dashboard.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($application['status'] === 'approved'): ?>
                            <div class="bg-green-50 border border-green-100 text-green-800 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium">Your application has been approved</p>
                                        <p class="mt-1">We will proceed with the purchase and notify you once the item is ready for collection.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Timeline -->
                <?php if (isset($applicationTimeline) && !empty($applicationTimeline)): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Application Timeline</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-6">
                                <?php foreach ($applicationTimeline as $timeline): ?>
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-8 w-8 rounded-full 
                                                <?php
                                                $iconClass = '';
                                                $iconName = '';
                                                
                                                switch($timeline['action']) {
                                                    case 'created':
                                                        $iconClass = 'bg-blue-100 text-blue-600';
                                                        $iconName = 'plus-circle';
                                                        break;
                                                    case 'updated':
                                                        $iconClass = 'bg-gray-100 text-gray-600';
                                                        $iconName = 'edit';
                                                        break;
                                                    case 'approved':
                                                        $iconClass = 'bg-green-100 text-green-600';
                                                        $iconName = 'check-circle';
                                                        break;
                                                    case 'rejected':
                                                        $iconClass = 'bg-red-100 text-red-600';
                                                        $iconName = 'times-circle';
                                                        break;
                                                    case 'delivered':
                                                        $iconClass = 'bg-blue-100 text-blue-600';
                                                        $iconName = 'truck';
                                                        break;
                                                    case 'completed':
                                                        $iconClass = 'bg-purple-100 text-purple-600';
                                                        $iconName = 'flag-checkered';
                                                        break;
                                                    default:
                                                        $iconClass = 'bg-gray-100 text-gray-600';
                                                        $iconName = 'circle';
                                                }
                                                echo $iconClass;
                                                ?>
                                            ">
                                                <i class="fas fa-<?= $iconName ?> text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-800">
                                                <?= ucfirst(htmlspecialchars($timeline['action'])) ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?= date('M d, Y - h:i A', strtotime($timeline['date'])) ?>
                                            </p>
                                            <?php if (!empty($timeline['description'])): ?>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <?= htmlspecialchars($timeline['description']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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
                            <a href="/Coops_Bichi/public/member/household/applications" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Back to Applications
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/household" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Back to Household Dashboard
                            </a>
                            
                            <?php if ($application['status'] === 'pending'): ?>
                                <button id="cancelApplicationBtn" class="block w-full bg-red-50 border border-red-300 hover:bg-red-100 text-red-800 text-center py-3 px-4 rounded-lg font-medium">
                                    Cancel Application
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Support Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Need Help?</h2>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">If you have any questions about this application or need assistance, please contact our support team.</p>
                        
                        <a href="/Coops_Bichi/public/contact" class="block bg-gray-100 hover:bg-gray-200 text-center py-3 px-4 rounded-lg font-medium text-gray-800">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($application['status'] === 'pending'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelBtn = document.getElementById('cancelApplicationBtn');
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel this application? This action cannot be undone.')) {
                window.location.href = '/Coops_Bichi/public/member/household/applications/<?= $application['id'] ?>/cancel';
            }
        });
    }
});
</script>
<?php endif; ?> 