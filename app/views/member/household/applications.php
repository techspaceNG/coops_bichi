<?php
// Member Household Applications View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_household_applications');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Household Purchase Applications</h1>
                <p class="text-primary-100">View all your household purchase applications with FCET Bichi Staff Multipurpose Cooperative Society</p>
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
                <!-- Applications List -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">All Applications</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($applications['data']) && !empty($applications['data'])): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($applications['data'] as $application): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M d, Y', strtotime($application['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <?= htmlspecialchars($application['item_description']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <?= ucfirst(htmlspecialchars($application['item_category'] ?? 'General')) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                ₦<?= number_format($application['item_cost'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="/Coops_Bichi/public/member/household/applications/<?= $application['id'] ?>" class="text-primary-600 hover:text-primary-900">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <?php if ($applications['pagination']['total_pages'] > 1): ?>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Showing <span class="font-medium"><?= $applications['pagination']['offset'] + 1 ?></span> to 
                                        <span class="font-medium">
                                            <?= min($applications['pagination']['offset'] + $applications['pagination']['per_page'], $applications['pagination']['total_records']) ?>
                                        </span> of 
                                        <span class="font-medium"><?= $applications['pagination']['total_records'] ?></span> applications
                                    </div>
                                    <div class="flex-1 flex justify-end">
                                        <?php if ($applications['pagination']['current_page'] > 1): ?>
                                        <a href="?page=<?= $applications['pagination']['current_page'] - 1 ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-2">
                                            Previous
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($applications['pagination']['current_page'] < $applications['pagination']['total_pages']): ?>
                                        <a href="?page=<?= $applications['pagination']['current_page'] + 1 ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Next
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>No application history available</p>
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
                            <a href="/Coops_Bichi/public/member/household/order" class="block bg-primary-600 hover:bg-primary-700 text-white text-center py-3 px-4 rounded-lg font-medium">
                                Apply for Household Purchase
                            </a>
                            
                            <a href="/Coops_Bichi/public/member/household" class="block bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 text-center py-3 px-4 rounded-lg font-medium">
                                Back to Household Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Application Status Guide -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Application Status Guide</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <span class="w-16 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mr-3 px-2 py-1 text-center">
                                    Pending
                                </span>
                                <span class="text-sm text-gray-600">Application is under review</span>
                            </li>
                            <li class="flex items-center">
                                <span class="w-16 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mr-3 px-2 py-1 text-center">
                                    Approved
                                </span>
                                <span class="text-sm text-gray-600">Application has been approved</span>
                            </li>
                            <li class="flex items-center">
                                <span class="w-16 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-3 px-2 py-1 text-center">
                                    Delivered
                                </span>
                                <span class="text-sm text-gray-600">Item has been delivered</span>
                            </li>
                            <li class="flex items-center">
                                <span class="w-16 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 mr-3 px-2 py-1 text-center">
                                    Completed
                                </span>
                                <span class="text-sm text-gray-600">Purchase fully paid off</span>
                            </li>
                            <li class="flex items-center">
                                <span class="w-16 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mr-3 px-2 py-1 text-center">
                                    Rejected
                                </span>
                                <span class="text-sm text-gray-600">Application was not approved</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 