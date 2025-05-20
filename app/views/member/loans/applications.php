<?php
// Member Loan Applications View

$flashMessage = \App\Helpers\Utility::getFlashMessage('member_loan_applications');
?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Loan Applications</h1>
                <p class="text-primary-100">View and track your loan applications with FCET Bichi Staff Multipurpose Cooperative Society</p>
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
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Loan Applications</h2>
                        
                        <a href="/Coops_Bichi/public/member/loans/apply" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> New Application
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <?php if (isset($applications['data']) && count($applications['data']) > 0): ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($applications['data'] as $application): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                #<?= htmlspecialchars($application['id']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('d M, Y', strtotime($application['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                                                ₦<?= number_format($application['loan_amount'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                ₦<?= number_format($application['ip_figure'], 2) ?>
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
                                                <a href="/Coops_Bichi/public/member/loans/applications/<?= $application['id'] ?>" class="text-primary-600 hover:text-primary-900">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <?php if ($applications['pagination']['total_pages'] > 1): ?>
                                <div class="px-6 py-4 border-t">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing 
                                                <span class="font-medium"><?= $applications['pagination']['offset'] + 1 ?></span>
                                                to 
                                                <span class="font-medium"><?= min($applications['pagination']['offset'] + $applications['pagination']['per_page'], $applications['pagination']['total_records']) ?></span>
                                                of 
                                                <span class="font-medium"><?= $applications['pagination']['total_records'] ?></span>
                                                results
                                            </p>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <?php if ($applications['pagination']['current_page'] > 1): ?>
                                                <a href="?page=<?= $applications['pagination']['current_page'] - 1 ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    Previous
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($applications['pagination']['current_page'] < $applications['pagination']['total_pages']): ?>
                                                <a href="?page=<?= $applications['pagination']['current_page'] + 1 ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    Next
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="p-6 text-center">
                                <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 mb-4">You haven't submitted any loan applications yet</p>
                                <a href="/Coops_Bichi/public/member/loans/apply" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Apply for a Loan
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div>
                <!-- Application Status Guide -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Application Status Guide</h2>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mr-3">
                                Pending
                            </span>
                            <span class="text-sm text-gray-600">Your application is being reviewed</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mr-3">
                                Approved
                            </span>
                            <span class="text-sm text-gray-600">Your loan has been approved</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mr-3">
                                Rejected
                            </span>
                            <span class="text-sm text-gray-600">Your application was not approved</span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Quick Links</h2>
                    </div>
                    
                    <div class="p-6">
                        <nav class="space-y-2">
                            <a href="/Coops_Bichi/public/member/loans" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-arrow-left mr-2 text-gray-400"></i> Back to Loans
                            </a>
                            <a href="/Coops_Bichi/public/member/loans/apply" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-plus-circle mr-2 text-gray-400"></i> Apply for Loan
                            </a>
                            <a href="/Coops_Bichi/public/member/loans/calculator" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-calculator mr-2 text-gray-400"></i> Loan Calculator
                            </a>
                            <a href="/Coops_Bichi/public/contact" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-700">
                                <i class="fas fa-question-circle mr-2 text-gray-400"></i> Get Support
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 