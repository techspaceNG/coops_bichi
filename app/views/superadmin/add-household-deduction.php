<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<?php 
$activeTab = $activeTab ?? 'individual';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Household Deductions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/household'); ?>">Household Management</a></li>
        <li class="breadcrumb-item active">Household Deductions</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="deductionTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php echo $activeTab === 'individual' ? 'active' : ''; ?>" 
                    id="individual-tab" data-bs-toggle="tab" data-bs-target="#individual-pane" 
                    type="button" role="tab" aria-controls="individual-pane" aria-selected="<?php echo $activeTab === 'individual' ? 'true' : 'false'; ?>">
                Individual Deduction
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?php echo $activeTab === 'bulk' ? 'active' : ''; ?>" 
                    id="bulk-tab" data-bs-toggle="tab" data-bs-target="#bulk-pane" 
                    type="button" role="tab" aria-controls="bulk-pane" aria-selected="<?php echo $activeTab === 'bulk' ? 'true' : 'false'; ?>">
                Bulk Deductions
            </button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="deductionTabsContent">
        <!-- Individual Deduction Tab -->
        <div class="tab-pane fade <?php echo $activeTab === 'individual' ? 'show active' : ''; ?>" id="individual-pane" role="tabpanel" aria-labelledby="individual-tab">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-shopping-basket me-1"></i>
                            Individual Household Deduction Form
                        </div>
                        <div class="card-body">
                            <form action="<?php echo url('/superadmin/save-household-deduction'); ?>" method="post" id="individualDeductionForm">
                                <div class="mb-3">
                                    <label for="member_search" class="form-label">Search Member or Purchase</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="member_search" placeholder="Search by COOPS no., name, or email">
                                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Enter COOPS number, member name, or email to find household purchases</div>
                                </div>

                                <div id="searchResults" class="mb-3 d-none">
                                    <label class="form-label">Search Results</label>
                                    <div class="list-group" id="purchaseResultsList">
                                        <!-- Search results will be populated here -->
                                    </div>
                                </div>
                                
                                <div id="selectedPurchaseDetails" class="mb-3 d-none">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title" id="selected_member_name">Member Name</h5>
                                            <p class="card-text" id="selected_purchase_details">Purchase details will appear here</p>
                                            <input type="hidden" id="purchase_id" name="purchase_id">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Deduction Amount (₦)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                                    <div class="form-text">Enter the amount to deduct for this household purchase.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" required value="<?php echo date('Y-m-d'); ?>">
                                    <div class="form-text">Date when the deduction was made.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="receipt_number" class="form-label">Receipt Number (Optional)</label>
                                    <input type="text" class="form-control" id="receipt_number" name="receipt_number">
                                    <div class="form-text">Optional receipt or reference number for this payment.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    <div class="form-text">Any additional information about this deduction.</div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo url('/superadmin/household'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Household
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="saveDeductionBtn" disabled>
                                        <i class="fas fa-save me-1"></i> Save Deduction
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-1"></i>
                            Information
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-lightbulb me-1"></i> Adding Household Deductions</h5>
                                <p>Use this form to record deductions for household purchases. This feature allows you to track payments made by members for household items purchased through the cooperative.</p>
                                <ul>
                                    <li>Search for a member or purchase using COOPS number, name, or email</li>
                                    <li>Select the appropriate purchase from the search results</li>
                                    <li>Enter the deduction amount</li>
                                    <li>Specify the date when the payment was made</li>
                                    <li>Add optional notes for record-keeping</li>
                                </ul>
                                <p class="mb-0">For multiple deductions, use the Bulk Deductions tab.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Important Notes
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-warning">
                                    <strong>Payment Tracking:</strong> Each deduction is recorded to track the member's payments for their household purchases.
                                </li>
                                <li class="list-group-item list-group-item-warning">
                                    <strong>Member Notification:</strong> The member will receive a notification about this deduction.
                                </li>
                                <li class="list-group-item list-group-item-warning">
                                    <strong>Transaction History:</strong> All deductions are recorded in the transaction history for audit purposes.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bulk Deduction Tab -->
        <div class="tab-pane fade <?php echo $activeTab === 'bulk' ? 'show active' : ''; ?>" id="bulk-pane" role="tabpanel" aria-labelledby="bulk-tab">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-upload me-1"></i>
                            Bulk Household Deductions
                        </div>
                        <div class="card-body">
                            <?php if (!empty($bulk_results)): ?>
                                <div class="alert <?php echo $bulk_results['failed'] > 0 ? 'alert-warning' : 'alert-success'; ?> mb-4">
                                    <h5><i class="fas fa-info-circle me-1"></i> Processing Results</h5>
                                    <p>Total Records: <?php echo $bulk_results['total']; ?></p>
                                    <p>Successful: <?php echo $bulk_results['success']; ?></p>
                                    <p>Failed: <?php echo $bulk_results['failed']; ?></p>
                                    
                                    <?php if (!empty($bulk_results['errors'])): ?>
                                        <hr>
                                        <h6>Errors:</h6>
                                        <ul>
                                            <?php foreach ($bulk_results['errors'] as $error): ?>
                                                <li><?php echo htmlspecialchars($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo url('/superadmin/process-bulk-household-deductions'); ?>" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="deduction_file" class="form-label">Upload CSV File</label>
                                    <input type="file" class="form-control" id="deduction_file" name="file" accept=".csv" required>
                                    <div class="form-text">Upload a CSV file with household deduction data.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">CSV Format Requirements</label>
                                    <div class="alert alert-secondary">
                                        <p class="mb-1"><strong>Column 1:</strong> COOPS Number</p>
                                        <p class="mb-1"><strong>Column 2:</strong> Deduction Amount</p>
                                        <p class="mb-1"><strong>Column 3:</strong> Payment Date (YYYY-MM-DD)</p>
                                        <p class="mb-0"><strong>Column 4:</strong> Notes (Optional)</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo url('/superadmin/download-household-deduction-template'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload and Process
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-1"></i>
                            Bulk Processing Information
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-lightbulb me-1"></i> Bulk Household Deductions</h5>
                                <p>Upload a CSV file containing multiple household deductions to process them all at once.</p>
                                <ul>
                                    <li>Prepare your data in a CSV file according to the required format</li>
                                    <li>Enter COOPS Number to identify members with active or approved purchases</li>
                                    <li>Each member must have at least one active or approved household purchase</li>
                                    <li>Deductions will be applied to the member's oldest purchase with remaining balance</li>
                                    <li>A detailed report will be provided after processing</li>
                                </ul>
                                <p class="mb-0">For adding a single deduction, use the Individual Deduction tab.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality (preserving query parameter)
    const tabLinks = document.querySelectorAll('#deductionTabs button');
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener('click', function(event) {
            const tabId = this.id.replace('-tab', '');
            // Update URL with the active tab
            const url = new URL(window.location);
            url.searchParams.set('tab', tabId);
            window.history.replaceState({}, '', url);
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('member_search');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');
    const purchaseResultsList = document.getElementById('purchaseResultsList');
    const selectedPurchaseDetails = document.getElementById('selectedPurchaseDetails');
    const purchaseIdInput = document.getElementById('purchase_id');
    const selectedMemberName = document.getElementById('selected_member_name');
    const selectedPurchaseDetailsText = document.getElementById('selected_purchase_details');
    const saveDeductionBtn = document.getElementById('saveDeductionBtn');
    
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length < 3) {
            alert('Please enter at least 3 characters to search');
            return;
        }
        
        // Show loading indicator
        purchaseResultsList.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        searchResults.classList.remove('d-none');
        
        // Make AJAX request to search for household purchases
        fetch(`<?php echo url('/superadmin/api/search-household-purchases'); ?>?term=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json().catch(error => {
                    console.error('JSON parsing error:', error);
                    throw new Error('Invalid JSON response from server');
                });
            })
            .then(data => {
                purchaseResultsList.innerHTML = '';
                
                if (data.error) {
                    purchaseResultsList.innerHTML = `<div class="alert alert-danger mb-0">${data.error}</div>`;
                    return;
                }
                
                if (data.purchases && data.purchases.length > 0) {
                    data.purchases.forEach(purchase => {
                        const purchaseItem = document.createElement('button');
                        purchaseItem.type = 'button';
                        purchaseItem.className = 'list-group-item list-group-item-action';
                        
                        // Truncate description if too long
                        const shortDescription = purchase.description.length > 30 
                            ? purchase.description.substring(0, 30) + '...' 
                            : purchase.description;
                            
                        purchaseItem.innerHTML = `
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">${purchase.member_name}</h5>
                                <small>Purchase ID: ${purchase.id}</small>
                            </div>
                            <p class="mb-1">COOPS No: ${purchase.coop_no} | Total Amount: ₦${parseFloat(purchase.amount).toLocaleString('en-NG', {minimumFractionDigits: 2})}</p>
                            <small>${shortDescription} | Status: ${purchase.status}</small>
                        `;
                        
                        purchaseItem.addEventListener('click', function() {
                            // Store purchase data
                            purchaseIdInput.value = purchase.id;
                            selectedMemberName.textContent = purchase.member_name;
                            selectedPurchaseDetailsText.innerHTML = `                                <strong>COOPS No:</strong> ${purchase.coop_no}<br>
                                <strong>Base Amount:</strong> ₦${parseFloat(purchase.raw_amount || 0).toLocaleString('en-NG', {minimumFractionDigits: 2})}<br>
                                <strong>Total Amount (with 5% admin):</strong> ₦${parseFloat(purchase.amount).toLocaleString('en-NG', {minimumFractionDigits: 2})}<br>
                                <strong>Paid Amount:</strong> ₦${parseFloat(purchase.paid_amount || 0).toLocaleString('en-NG', {minimumFractionDigits: 2})}<br>
                                <strong>Remaining Balance:</strong> ₦${parseFloat(purchase.remaining_amount || purchase.amount).toLocaleString('en-NG', {minimumFractionDigits: 2})}<br>
                                <strong>Description:</strong> ${purchase.description}<br>
                                <strong>Status:</strong> ${purchase.status}
                            `;
                            
                            // Show selected purchase details
                            selectedPurchaseDetails.classList.remove('d-none');
                            
                            // Set suggested amount
                            const amountInput = document.getElementById('amount');
                            amountInput.setAttribute('max', parseFloat(purchase.remaining_amount || purchase.amount));
                            amountInput.placeholder = `Suggested: ₦${parseFloat(purchase.remaining_amount || purchase.amount).toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
                            
                            // Enable the save button
                            saveDeductionBtn.disabled = false;
                            
                            // Mark this purchase as selected
                            document.querySelectorAll('#purchaseResultsList button').forEach(btn => {
                                btn.classList.remove('active');
                            });
                            this.classList.add('active');
                        });
                        
                        purchaseResultsList.appendChild(purchaseItem);
                    });
                } else {
                    purchaseResultsList.innerHTML = '<div class="alert alert-warning mb-0">No household purchases found matching your search.</div>';
                }
            })
            .catch(error => {
                console.error('Error searching household purchases:', error);
                purchaseResultsList.innerHTML = '<div class="alert alert-danger mb-0">Error searching household purchases. Please try again later or contact the administrator.</div>';
            });
    }
    
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
    
    // Form validation
    const individualDeductionForm = document.getElementById('individualDeductionForm');
    individualDeductionForm.addEventListener('submit', function(e) {
        const purchaseId = purchaseIdInput.value;
        if (!purchaseId) {
            e.preventDefault();
            alert('Please select a household purchase first');
        }
    });
});
</script> 
