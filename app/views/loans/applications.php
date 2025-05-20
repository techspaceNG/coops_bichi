<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">My Loan Applications</h1>
                <p class="text-primary-100">View and track the status of your loan applications</p>
            </div>
        </div>
        
        <!-- Applications List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Application History</h2>
                <a href="/loans/apply" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 px-4 rounded-lg">
                    Apply for New Loan
                </a>
            </div>
            
            <div class="p-6">
                <?php if (empty($applications)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No applications found</h3>
                        <p class="text-gray-500 mb-6">You haven't submitted any loan applications yet.</p>
                        <a href="/loans/apply" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg">
                            Apply for a Loan
                        </a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Loan Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purpose
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        IP Figure
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($applications as $application): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($application['application_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">₦<?= number_format($application['loan_amount'], 2) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= ucfirst(str_replace('_', ' ', htmlspecialchars($application['purpose']))) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₦<?= number_format($application['monthly_payment'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                                <?= ucfirst(htmlspecialchars($application['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/loans/details/<?= $application['id'] ?>" class="text-primary-600 hover:text-primary-900">
                                                View Details
                                            </a>
                                            <?php if ($application['status'] === 'pending'): ?>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <a href="/loans/cancel/<?= $application['id'] ?>" class="text-red-600 hover:text-red-900" 
                                                   onclick="return confirm('Are you sure you want to cancel this application?')">
                                                    Cancel
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="flex justify-center mt-6">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <?php if ($current_page > 1): ?>
                                    <a href="/loans/applications?page=<?= $current_page - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i === $current_page): ?>
                                        <span class="relative inline-flex items-center px-4 py-2 border border-primary-500 bg-primary-50 text-sm font-medium text-primary-600">
                                            <?= $i ?>
                                        </span>
                                    <?php else: ?>
                                        <a href="/loans/applications?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            <?= $i ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($current_page < $total_pages): ?>
                                    <a href="/loans/applications?page=<?= $current_page + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                <?php endif; ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Application Info -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Application Process</h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/4 mb-4 md:mb-0">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-2">
                                <span class="font-bold">1</span>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-1">Submit Application</h3>
                            <p class="text-sm text-gray-600">Fill out the loan application form</p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/4 mb-4 md:mb-0">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-2">
                                <span class="font-bold">2</span>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-1">Review</h3>
                            <p class="text-sm text-gray-600">Application is reviewed by the cooperative society</p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/4 mb-4 md:mb-0">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-2">
                                <span class="font-bold">3</span>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-1">Approval</h3>
                            <p class="text-sm text-gray-600">Application is approved or rejected</p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/4">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-2">
                                <span class="font-bold">4</span>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-1">Disbursement</h3>
                            <p class="text-sm text-gray-600">Loan amount is disbursed upon approval</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-2">Need Help?</h3>
                    <p class="text-gray-600">If you have any questions about your loan application, please contact the cooperative society office:</p>
                    <p class="text-gray-600 mt-1">Email: loans@coopsbichi.org | Phone: +234 xxx xxx xxxx</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 