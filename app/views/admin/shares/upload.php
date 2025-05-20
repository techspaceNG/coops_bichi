<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Upload Share Contributions (View Only)</h1>
        <div class="flex space-x-2">
            <a href="/Coops_Bichi/public/admin/shares" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Shares
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
    
    <!-- View Only Mode Notice -->
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="ml-3">
                <p class="font-bold">View-Only Mode</p>
                <p>Shares module is currently in view-only mode. Upload functionality is disabled.</p>
            </div>
        </div>
    </div>
    
    <!-- Upload Form (Disabled) -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 opacity-50">
        <h2 class="text-lg font-semibold mb-4">Upload Share Contributions (Disabled)</h2>
        
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="upload_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File (CSV or Excel)</label>
                <input type="file" name="upload_file" id="upload_file" class="w-full bg-gray-50 border border-gray-300 rounded-md shadow-sm py-2 px-3" disabled>
                <p class="mt-1 text-xs text-gray-500">Accepted formats: CSV, XLS, XLSX</p>
            </div>
            
            <div class="mb-4">
                <label for="contribution_date" class="block text-sm font-medium text-gray-700 mb-1">Contribution Date</label>
                <input type="date" name="contribution_date" id="contribution_date" value="<?= date('Y-m-d') ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" disabled>
            </div>
            
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Enter any additional notes or details about this upload" disabled></textarea>
            </div>
            
            <div class="mt-6">
                <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md font-medium cursor-not-allowed" disabled>
                    <i class="fas fa-upload mr-2"></i> Upload Contributions
                </button>
                <a href="/Coops_Bichi/public/admin/shares/download-template" class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-download mr-2"></i> Download Template
                </a>
            </div>
        </form>
    </div>
    
    <!-- Instructions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-4">Upload Instructions</h2>
        
        <div class="mb-4 bg-blue-50 text-blue-800 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">The uploaded file must contain the following columns in order:</p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Column</th>
                        <th class="px-4 py-2 border bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-2 border bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                        <th class="px-4 py-2 border bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-2 border text-sm font-medium">Coop Number</td>
                        <td class="px-4 py-2 border text-sm">Member's Cooperative Number</td>
                        <td class="px-4 py-2 border text-sm">Text</td>
                        <td class="px-4 py-2 border text-sm">Yes</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border text-sm font-medium">Member Name</td>
                        <td class="px-4 py-2 border text-sm">Full name of the member</td>
                        <td class="px-4 py-2 border text-sm">Text</td>
                        <td class="px-4 py-2 border text-sm">Yes</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border text-sm font-medium">Units</td>
                        <td class="px-4 py-2 border text-sm">Number of share units</td>
                        <td class="px-4 py-2 border text-sm">Number</td>
                        <td class="px-4 py-2 border text-sm">Yes</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border text-sm font-medium">Unit Value</td>
                        <td class="px-4 py-2 border text-sm">Value per share unit</td>
                        <td class="px-4 py-2 border text-sm">Number (0.00)</td>
                        <td class="px-4 py-2 border text-sm">Yes</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border text-sm font-medium">Notes</td>
                        <td class="px-4 py-2 border text-sm">Additional information</td>
                        <td class="px-4 py-2 border text-sm">Text</td>
                        <td class="px-4 py-2 border text-sm">No</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <h3 class="text-md font-medium mb-2">Example:</h3>
            <div class="bg-gray-50 p-4 rounded-md overflow-x-auto">
                <pre class="text-sm">Coop Number,Member Name,Units,Unit Value,Notes
C001,John Doe,5,1000.00,New purchase
C002,Jane Smith,3,1000.00,Additional shares
C003,Robert Johnson,10,1000.00,Monthly contribution</pre>
            </div>
        </div>
        
        <div class="mt-4 bg-yellow-50 text-yellow-800 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">Important Notes:</p>
                    <ul class="list-disc list-inside mt-1 text-sm space-y-1">
                        <li>The system will match members by their Coop Number.</li>
                        <li>If a member is not found, the row will be skipped.</li>
                        <li>Make sure the file is properly formatted to avoid errors.</li>
                        <li>The maximum file size allowed is 5MB.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> 