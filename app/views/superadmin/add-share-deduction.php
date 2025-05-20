<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Add Share Deduction</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/shares'); ?>">Shares Management</a></li>
        <li class="breadcrumb-item active">Add Share Deduction</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="deductionTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo (!isset($activeTab) || $activeTab !== 'bulk') ? 'active' : ''; ?>" 
                            id="single-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#single" 
                            type="button" 
                            role="tab" 
                            aria-controls="single" 
                            aria-selected="<?php echo (!isset($activeTab) || $activeTab !== 'bulk') ? 'true' : 'false'; ?>">
                        <i class="fas fa-user me-1"></i> Single Deduction
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo (isset($activeTab) && $activeTab === 'bulk') ? 'active' : ''; ?>" 
                            id="bulk-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#bulk" 
                            type="button" 
                            role="tab" 
                            aria-controls="bulk" 
                            aria-selected="<?php echo (isset($activeTab) && $activeTab === 'bulk') ? 'true' : 'false'; ?>">
                        <i class="fas fa-file-upload me-1"></i> Bulk Deductions
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="deductionTabContent">
                <!-- Single Deduction Tab -->
                <div class="tab-pane fade <?php echo (!isset($activeTab) || $activeTab !== 'bulk') ? 'show active' : ''; ?>" 
                     id="single" 
                     role="tabpanel" 
                     aria-labelledby="single-tab">
                    <form action="<?php echo url('/superadmin/save-share-deduction'); ?>" method="post" class="needs-validation" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="member_id" class="form-label">Select Member <span class="text-danger">*</span></label>
                                <select class="form-select" id="member_id" name="member_id" required>
                                    <option value="">-- Select Member --</option>
                                    <?php foreach ($members as $member): ?>
                                    <option value="<?php echo $member['id']; ?>">
                                        <?php echo htmlspecialchars($member['name'] . ' (' . $member['coop_no'] . ')'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a member</div>
                            </div>
                            <div class="col-md-6">
                                <div class="member-details mt-4" id="memberDetails" style="display: none;">
                                    <p class="mb-1"><strong>Current Shares Value:</strong> <span id="currentSharesValue">₦0.00</span></p>
                                    <p class="mb-1"><strong>Current Units:</strong> <span id="currentUnits">0</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="units" class="form-label">Number of Units <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="units" name="units" min="1" step="1" required>
                                <div class="invalid-feedback">Please enter a valid number of units</div>
                            </div>
                            <div class="col-md-6">
                                <label for="unit_value" class="form-label">Unit Value (₦) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="unit_value" name="unit_value" min="0.01" step="0.01" required>
                                <div class="invalid-feedback">Please enter a valid unit value</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_amount" class="form-label">Total Amount (₦)</label>
                                <input type="text" class="form-control" id="total_amount" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes about this deduction"></textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo url('/superadmin/shares'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Shares
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Deduction
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Bulk Deductions Tab -->
                <div class="tab-pane fade <?php echo (isset($activeTab) && $activeTab === 'bulk') ? 'show active' : ''; ?>" 
                     id="bulk" 
                     role="tabpanel" 
                     aria-labelledby="bulk-tab">
                    <?php if (isset($bulk_results)): ?>
                        <div class="alert alert-info mb-4">
                            <h5>Bulk Upload Results</h5>
                            <p>Total processed: <?php echo $bulk_results['total']; ?></p>
                            <p>Successful: <?php echo $bulk_results['success']; ?></p>
                            <p>Failed: <?php echo $bulk_results['failed']; ?></p>
                            
                            <?php if (count($bulk_results['errors']) > 0): ?>
                                <h6>Errors:</h6>
                                <ul>
                                    <?php foreach ($bulk_results['errors'] as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo url('/superadmin/process-bulk-share-deductions'); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="file" class="form-label">Upload CSV File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                                <div class="invalid-feedback">Please select a CSV file</div>
                                <div class="form-text">
                                    Upload a CSV file with the following columns: coops_number, units, unit_value, notes (optional)
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end mb-3">
                                <a href="<?php echo url('/superadmin/download-share-deduction-template'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-download me-1"></i> Download Template
                                </a>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <i class="fas fa-info-circle me-1"></i>
                                Instructions
                            </div>
                            <div class="card-body">
                                <ol>
                                    <li>Download the CSV template using the button above.</li>
                                    <li>Fill in the template with the required information for each member.</li>
                                    <li>Save the file as CSV and upload it using the form above.</li>
                                    <li>Required columns: <code>coops_number</code>, <code>units</code>, <code>unit_value</code></li>
                                    <li>Optional columns: <code>notes</code></li>
                                </ol>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Note: Any records with errors will be skipped. The system will continue processing the valid records.
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo url('/superadmin/shares'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Shares
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload and Process
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
        
        // Calculate total amount
        const unitsInput = document.getElementById('units');
        const unitValueInput = document.getElementById('unit_value');
        const totalAmountInput = document.getElementById('total_amount');
        
        function calculateTotal() {
            const units = unitsInput.value ? parseInt(unitsInput.value) : 0;
            const unitValue = unitValueInput.value ? parseFloat(unitValueInput.value) : 0;
            const total = units * unitValue;
            totalAmountInput.value = '₦' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        unitsInput.addEventListener('input', calculateTotal);
        unitValueInput.addEventListener('input', calculateTotal);
        
        // Member dropdown change event to fetch member share details
        const memberSelect = document.getElementById('member_id');
        const memberDetails = document.getElementById('memberDetails');
        const currentSharesValue = document.getElementById('currentSharesValue');
        const currentUnits = document.getElementById('currentUnits');
        
        memberSelect.addEventListener('change', function() {
            const memberId = this.value;
            if (memberId) {
                // Fetch member share details via AJAX
                fetch(`<?php echo url('/api/member/'); ?>${memberId}/shares`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentSharesValue.textContent = '₦' + parseFloat(data.shares_balance || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            currentUnits.textContent = data.units || 0;
                            memberDetails.style.display = 'block';
                            
                            // Set default unit value if available
                            if (data.unit_value) {
                                unitValueInput.value = data.unit_value;
                                calculateTotal();
                            }
                        } else {
                            memberDetails.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching member details:', error);
                        memberDetails.style.display = 'none';
                    });
            } else {
                memberDetails.style.display = 'none';
            }
        });
    });
</script> 