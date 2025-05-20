<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Loan Deductions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/loans'); ?>">Loan Management</a></li>
        <li class="breadcrumb-item active">Loan Deductions</li>
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
                            <i class="fas fa-money-bill-wave me-1"></i>
                            Individual Loan Deduction Form
                        </div>
                        <div class="card-body">
                            <form action="<?php echo url('/superadmin/save-loan-deduction'); ?>" method="post" id="individualDeductionForm">
                                <div class="mb-3">
                                    <label for="member_search" class="form-label">Search Member or Loan</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="member_search" placeholder="Search by COOPS no., name, or email">
                                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Enter COOPS number, member name, or email to find loans</div>
                                </div>

                                <div id="searchResults" class="mb-3 d-none">
                                    <label class="form-label">Search Results</label>
                                    <div class="list-group" id="loanResultsList">
                                        <!-- Search results will be populated here -->
                                    </div>
                                </div>
                                
                                <div id="selectedLoanDetails" class="mb-3 d-none">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title" id="selected_member_name">Member Name</h5>
                                            <p class="card-text" id="selected_loan_details">Loan details will appear here</p>
                                            <input type="hidden" id="loan_id" name="loan_id">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Deduction Amount (₦)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                                    <div class="form-text">Enter the amount to deduct from the loan balance.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" required value="<?php echo date('Y-m-d'); ?>">
                                    <div class="form-text">Date when the deduction was made.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                    <div class="form-text">Any additional information about this deduction.</div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo url('/superadmin/loans'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Loans
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
                                <h5><i class="fas fa-lightbulb me-1"></i> Adding Loan Deductions</h5>
                                <p>Use this form to record loan repayments made by members. The deduction will reduce the loan balance and update the loan status if fully repaid.</p>
                                <ul>
                                    <li>Search for a member or loan using COOPS number, name, or email</li>
                                    <li>Select the appropriate loan from the search results</li>
                                    <li>Enter the deduction amount (must be less than or equal to the remaining balance)</li>
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
                                    <strong>Balance Update:</strong> The loan balance will be automatically updated after saving this deduction.
                                </li>
                                <li class="list-group-item list-group-item-warning">
                                    <strong>Status Change:</strong> If the deduction fully repays the loan, the status will change to "completed".
                                </li>
                                <li class="list-group-item list-group-item-warning">
                                    <strong>Member Notification:</strong> The member will receive a notification about this deduction.
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
                            Bulk Loan Deductions
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
                            
                            <form action="<?php echo url('/superadmin/process-bulk-loan-deductions'); ?>" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="deduction_file" class="form-label">Upload CSV File</label>
                                    <input type="file" class="form-control" id="deduction_file" name="deduction_file" accept=".csv" required>
                                    <div class="form-text">Upload a CSV file with loan deduction data.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">CSV Format Requirements</label>
                                    <div class="alert alert-secondary">
                                        <p class="mb-1"><strong>Column 1:</strong> Loan ID or COOPS Number</p>
                                        <p class="mb-1"><strong>Column 2:</strong> Deduction Amount</p>
                                        <p class="mb-1"><strong>Column 3:</strong> Payment Date (YYYY-MM-DD)</p>
                                        <p class="mb-0"><strong>Column 4:</strong> Notes (Optional)</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo url('/superadmin/download-loan-deduction-template'); ?>" class="btn btn-outline-secondary">
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
                                <h5><i class="fas fa-lightbulb me-1"></i> Bulk Loan Deductions</h5>
                                <p>Upload a CSV file containing multiple loan deductions to process them all at once.</p>
                                <ul>
                                    <li>Prepare your data in a CSV file according to the required format</li>
                                    <li>You can use either Loan ID or COOPS Number to identify loans</li>
                                    <li>All deductions will be validated before processing</li>
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
    const loanResultsList = document.getElementById('loanResultsList');
    const selectedLoanDetails = document.getElementById('selectedLoanDetails');
    const loanIdInput = document.getElementById('loan_id');
    const selectedMemberName = document.getElementById('selected_member_name');
    const selectedLoanDetailsText = document.getElementById('selected_loan_details');
    const saveDeductionBtn = document.getElementById('saveDeductionBtn');
    
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length < 2) {
            alert('Please enter at least 2 characters to search');
            return;
        }
        
        // Show loading indicator
        loanResultsList.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        searchResults.classList.remove('d-none');
        
        // For debugging
        console.log(`Searching for: ${searchTerm}`);
        
        // Make AJAX request to search for loans
        fetch(`<?php echo url('/superadmin/api/search-loans'); ?>?term=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            cache: 'no-cache' // Prevent caching
        })
            .then(response => {
                console.log(`Response status: ${response.status}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Parsed data:', data);
                loanResultsList.innerHTML = '';
                
                if (data.error) {
                    loanResultsList.innerHTML = `<div class="alert alert-danger mb-0">${data.error}</div>`;
                    return;
                }
                
                if (data.loans && data.loans.length > 0) {
                    data.loans.forEach(loan => {
                        const loanItem = document.createElement('button');
                        loanItem.type = 'button';
                        loanItem.className = 'list-group-item list-group-item-action';
                        loanItem.innerHTML = `
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">${loan.member_name || 'Unknown Member'}</h5>
                                <small>Loan ID: ${loan.id}</small>
                            </div>
                            <p class="mb-1">COOPS No: ${loan.coop_no || 'N/A'} | Loan Amount: ₦${parseFloat(loan.loan_amount || 0).toLocaleString('en-NG', {minimumFractionDigits: 2})}</p>
                            <small>Balance: ₦${parseFloat(loan.balance || 0).toLocaleString('en-NG', {minimumFractionDigits: 2})} | Status: ${loan.status || 'Unknown'}</small>
                        `;
                        
                        loanItem.addEventListener('click', function() {
                            // Store loan data
                            loanIdInput.value = loan.id;
                            selectedMemberName.textContent = loan.member_name || 'Unknown Member';
                            selectedLoanDetailsText.innerHTML = `
                                <strong>COOPS No:</strong> ${loan.coop_no || 'N/A'}<br>
                                <strong>Loan Amount:</strong> ₦${parseFloat(loan.loan_amount || 0).toLocaleString('en-NG', {minimumFractionDigits: 2})}<br>
                                <strong>Balance:</strong> ₦${parseFloat(loan.balance || 0).toLocaleString('en-NG', {minimumFractionDigits: 2})}<br>
                                <strong>Status:</strong> ${loan.status || 'Unknown'}
                            `;
                            
                            // Show selected loan details
                            selectedLoanDetails.classList.remove('d-none');
                            
                            // Set max amount
                            const amountInput = document.getElementById('amount');
                            const balance = parseFloat(loan.balance || 0);
                            amountInput.setAttribute('max', balance);
                            amountInput.placeholder = `Maximum: ₦${balance.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
                            
                            // Enable the save button
                            saveDeductionBtn.disabled = false;
                            
                            // Mark this loan as selected
                            document.querySelectorAll('#loanResultsList button').forEach(btn => {
                                btn.classList.remove('active');
                            });
                            this.classList.add('active');
                        });
                        
                        loanResultsList.appendChild(loanItem);
                    });
                } else {
                    loanResultsList.innerHTML = '<div class="alert alert-warning mb-0">No loans found matching your search.</div>';
                }
            })
            .catch(error => {
                console.error('Error searching loans:', error);
                loanResultsList.innerHTML = '<div class="alert alert-danger mb-0">Error searching loans. Please try again later.</div>';
            });
    }
    
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('input', function() {
        // If the input is at least 3 characters, perform search after a short delay
        if (this.value.trim().length >= 3) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(performSearch, 500);
        }
    });
    
    // Form validation
    const individualDeductionForm = document.getElementById('individualDeductionForm');
    individualDeductionForm.addEventListener('submit', function(e) {
        const loanId = loanIdInput.value;
        if (!loanId) {
            e.preventDefault();
            alert('Please select a loan first');
        }
    });
});
</script> 