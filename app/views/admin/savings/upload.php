<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Upload Savings Contributions</h1>
        <div class="flex space-x-2">
            <a href="/Coops_Bichi/public/admin/savings" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Savings
            </a>
        </div>
    </div>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="mb-4 <?= $_SESSION['flash_type'] === 'success' ? 'bg-green-100 border border-green-200 text-green-700' : 'bg-red-100 border border-red-200 text-red-700' ?> px-4 py-3 rounded relative">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Upload Savings Contributions</h2>
            <p class="text-gray-600 mb-4">Upload a CSV file containing member savings contributions data. The system will process the file and update member savings accounts.</p>
            
            <div class="bg-blue-50 text-blue-700 p-4 rounded-md mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-700">File Format Instructions</h3>
                        <div class="mt-2 text-sm">
                            <p>The CSV file should contain the following columns:</p>
                            <ul class="list-disc list-inside mt-1 ml-2">
                                <li>Coop Number or Member ID (required)</li>
                                <li>Amount (required)</li>
                                <li>Transaction Date (YYYY-MM-DD format, required)</li>
                                <li>Notes (optional)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <form action="/Coops_Bichi/public/admin/savings/process-upload" method="POST" enctype="multipart/form-data" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">CSV File</label>
                        <input type="file" name="file" id="file" accept=".csv" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-300 focus:border-primary-300" required>
                    </div>
                    
                    <div>
                        <label for="contribution_date" class="block text-sm font-medium text-gray-700 mb-1">Default Contribution Date (if not in file)</label>
                        <input type="date" name="contribution_date" id="contribution_date" value="<?= date('Y-m-d') ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-upload mr-2"></i> Upload File
                    </button>
                    <a href="/Coops_Bichi/public/admin/savings/download-template" class="ml-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-download mr-2"></i> Download Template
                    </a>
                </div>
            </form>
        </div>
        
        <hr class="my-6">
        
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Manual Contribution Entry</h2>
            <p class="text-gray-600 mb-4">Use this form to enter individual contributions manually.</p>
            
            <form action="/Coops_Bichi/public/admin/savings/add-contribution" method="POST" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                        <select name="member_id" id="member_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" required>
                            <option value="">Select Member</option>
                            <?php
                            $members = \App\Core\Database::fetchAll("SELECT id, name, coop_no FROM members ORDER BY name");
                            foreach ($members as $member):
                            ?>
                            <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['name'] . ' (' . $member['coop_no'] . ')') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" step="0.01" min="0" name="amount" id="amount" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Contribution Date</label>
                        <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"></textarea>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus-circle mr-2"></i> Add Contribution
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Recent Uploads -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Uploads</h2>
        
        <?php
        $recentUploads = \App\Core\Database::fetchAll(
            "SELECT * FROM upload_history 
            WHERE upload_type = 'savings' 
            ORDER BY upload_date DESC 
            LIMIT 5"
        );
        ?>
        
        <?php if (empty($recentUploads)): ?>
            <div class="text-gray-500 text-center py-4">
                <p>No recent uploads found</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filename</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recentUploads as $upload): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M d, Y H:i', strtotime($upload['upload_date'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($upload['filename']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $upload['records_processed'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $upload['status'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst(htmlspecialchars($upload['status'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($upload['uploaded_by']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div> 