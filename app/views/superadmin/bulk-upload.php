<?php require_once VIEWS_PATH . '/layouts/admin_header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Bulk Data Upload</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/superadmin/dashboard">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item active">Bulk Data Upload</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-lg-7">
            <!-- Upload Forms -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-upload me-1"></i>
                    Upload Options
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="uploadTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings-content" type="button" role="tab" aria-controls="savings-content" aria-selected="true">Savings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="loans-tab" data-bs-toggle="tab" data-bs-target="#loans-content" type="button" role="tab" aria-controls="loans-content" aria-selected="false">Loans</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="household-tab" data-bs-toggle="tab" data-bs-target="#household-content" type="button" role="tab" aria-controls="household-content" aria-selected="false">Household</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-content" type="button" role="tab" aria-controls="members-content" aria-selected="false">Members</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content pt-4" id="uploadTabsContent">
                        <!-- Savings Upload -->
                        <div class="tab-pane fade show active" id="savings-content" role="tabpanel" aria-labelledby="savings-tab">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Note:</strong> Upload monthly deductions for all members in a single file. The system will automatically update each member's savings record.
                            </div>
                            
                            <form action="/superadmin/upload-savings" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="savingsFile" class="form-label">Select Savings Data File</label>
                                    <input class="form-control" type="file" id="savingsFile" name="savings_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text">File must include COOPS No., Amount, and Deduction Date columns.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="savingsDate" class="form-label">Deduction Month</label>
                                    <input type="month" class="form-control" id="savingsDate" name="deduction_month" value="<?php echo date('Y-m'); ?>" required>
                                    <div class="form-text">The month for which deductions were made.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="overrideSavings" name="override_existing" value="1">
                                        <label class="form-check-label" for="overrideSavings">
                                            Override existing deductions for this month
                                        </label>
                                    </div>
                                    <div class="form-text">Check this to replace any existing deductions for the selected month.</div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="/superadmin/download-template/savings" class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload Savings Data
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Loans Upload -->
                        <div class="tab-pane fade" id="loans-content" role="tabpanel" aria-labelledby="loans-tab">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Note:</strong> Upload monthly loan repayments for members. This helps track repayments against existing loans.
                            </div>
                            
                            <form action="/superadmin/upload-loan-repayments" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="loansFile" class="form-label">Select Loan Repayments File</label>
                                    <input class="form-control" type="file" id="loansFile" name="loans_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text">File must include COOPS No., Loan ID, Amount Paid, and Payment Date columns.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="loansMonth" class="form-label">Repayment Month</label>
                                    <input type="month" class="form-control" id="loansMonth" name="repayment_month" value="<?php echo date('Y-m'); ?>" required>
                                    <div class="form-text">The month for which repayments were made.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="overrideLoans" name="override_existing" value="1">
                                        <label class="form-check-label" for="overrideLoans">
                                            Override existing repayments for this month
                                        </label>
                                    </div>
                                    <div class="form-text">Check this to replace any existing repayments for the selected month.</div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="/superadmin/download-template/loans" class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload Loan Repayments
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Household Upload -->
                        <div class="tab-pane fade" id="household-content" role="tabpanel" aria-labelledby="household-tab">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Note:</strong> Upload monthly household purchase repayments for members. This helps track repayments against existing purchases.
                            </div>
                            
                            <form action="/superadmin/upload-household-repayments" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="householdFile" class="form-label">Select Household Repayments File</label>
                                    <input class="form-control" type="file" id="householdFile" name="household_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text">File must include COOPS No., Purchase ID, Amount Paid, and Payment Date columns.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="householdMonth" class="form-label">Repayment Month</label>
                                    <input type="month" class="form-control" id="householdMonth" name="repayment_month" value="<?php echo date('Y-m'); ?>" required>
                                    <div class="form-text">The month for which repayments were made.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="overrideHousehold" name="override_existing" value="1">
                                        <label class="form-check-label" for="overrideHousehold">
                                            Override existing repayments for this month
                                        </label>
                                    </div>
                                    <div class="form-text">Check this to replace any existing repayments for the selected month.</div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="/superadmin/download-template/household" class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload Household Repayments
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Members Upload -->
                        <div class="tab-pane fade" id="members-content" role="tabpanel" aria-labelledby="members-tab">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Note:</strong> Use this form to register multiple new members at once. Each member will need to complete registration by setting their password.
                            </div>
                            
                            <form action="/superadmin/upload-members" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="membersFile" class="form-label">Select Members Data File</label>
                                    <input class="form-control" type="file" id="membersFile" name="members_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text">File must include COOPS No., Name, Email, Department, and Initial Savings Amount columns.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sendInvitations" name="send_invitations" value="1" checked>
                                        <label class="form-check-label" for="sendInvitations">
                                            Send email invitations to new members
                                        </label>
                                    </div>
                                    <div class="form-text">Each member will receive an email with instructions to complete their registration.</div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="/superadmin/download-template/members" class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload Members Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <!-- Upload History -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Recent Upload History
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($uploads)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No upload history found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($uploads as $upload): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y H:i', strtotime($upload['created_at'])); ?></td>
                                            <td><?php echo ucfirst(htmlspecialchars($upload['upload_type'])); ?></td>
                                            <td><?php echo htmlspecialchars($upload['filename']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                switch ($upload['status']) {
                                                    case 'completed':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'processing':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'failed':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst(htmlspecialchars($upload['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDetailsModal" data-upload-id="<?php echo $upload['id']; ?>">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (!empty($uploads)): ?>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="/superadmin/upload-history" class="btn btn-sm btn-outline-secondary">
                                View All History
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-question-circle me-1"></i>
                    Upload Instructions
                </div>
                <div class="card-body">
                    <div class="accordion" id="uploadInstructions">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    File Format Requirements
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#uploadInstructions">
                                <div class="accordion-body">
                                    <ul class="small mb-0">
                                        <li>Files must be in .CSV, .XLS, or .XLSX format</li>
                                        <li>First row should contain column headers</li>
                                        <li>COOPS No. must be in the format "COOPS/XX/XXX"</li>
                                        <li>Date columns should be in YYYY-MM-DD format</li>
                                        <li>Amount columns should contain only numbers</li>
                                        <li>Download the respective template for each upload type</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Processing & Validation
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#uploadInstructions">
                                <div class="accordion-body">
                                    <ul class="small mb-0">
                                        <li>Files are validated before processing to check for formatting errors</li>
                                        <li>Invalid COOPS Numbers will be skipped and reported in the results</li>
                                        <li>Large files may take longer to process</li>
                                        <li>Check the upload history for processing status and detailed results</li>
                                        <li>If an upload fails, fix the errors and try again</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Troubleshooting
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#uploadInstructions">
                                <div class="accordion-body">
                                    <ul class="small mb-0">
                                        <li>If your upload fails, check for formatting issues in your file</li>
                                        <li>Ensure all required columns are present in the file</li>
                                        <li>Verify that member COOPS numbers match exactly with the database</li>
                                        <li>For loan or household repayments, ensure the loan IDs exist</li>
                                        <li>If problems persist, try using the template and re-uploading</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Details Modal -->
<div class="modal fade" id="uploadDetailsModal" tabindex="-1" aria-labelledby="uploadDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDetailsModalLabel">Upload Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="uploadDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading upload details...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize upload details modal
    var uploadDetailsModal = document.getElementById('uploadDetailsModal');
    if (uploadDetailsModal) {
        uploadDetailsModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var uploadId = button.getAttribute('data-upload-id');
            
            // Fetch upload details
            fetch('/superadmin/get-upload-details/' + uploadId)
                .then(response => response.json())
                .then(data => {
                    let content = '';
                    
                    if (data.error) {
                        content = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                ${data.error}
                            </div>
                        `;
                    } else {
                        content = `
                            <div class="mb-4">
                                <h6>Upload Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 150px;">Upload Type:</th>
                                        <td>${data.upload.upload_type}</td>
                                    </tr>
                                    <tr>
                                        <th>Filename:</th>
                                        <td>${data.upload.filename}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge ${data.upload.status === 'completed' ? 'bg-success' : (data.upload.status === 'processing' ? 'bg-warning' : 'bg-danger')}">
                                                ${data.upload.status}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Uploaded By:</th>
                                        <td>${data.upload.uploader_name}</td>
                                    </tr>
                                    <tr>
                                        <th>Upload Date:</th>
                                        <td>${new Date(data.upload.created_at).toLocaleString()}</td>
                                    </tr>
                                </table>
                            </div>
                        `;
                        
                        if (data.stats) {
                            content += `
                                <div class="mb-4">
                                    <h6>Processing Statistics</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 150px;">Total Records:</th>
                                            <td>${data.stats.total_records}</td>
                                        </tr>
                                        <tr>
                                            <th>Processed:</th>
                                            <td>${data.stats.processed_records}</td>
                                        </tr>
                                        <tr>
                                            <th>Successful:</th>
                                            <td>${data.stats.successful_records}</td>
                                        </tr>
                                        <tr>
                                            <th>Failed:</th>
                                            <td>${data.stats.failed_records}</td>
                                        </tr>
                                        <tr>
                                            <th>Skipped:</th>
                                            <td>${data.stats.skipped_records}</td>
                                        </tr>
                                    </table>
                                </div>
                            `;
                        }
                        
                        if (data.errors && data.errors.length > 0) {
                            content += `
                                <div>
                                    <h6>Error Details</h6>
                                    <div class="alert alert-danger small">
                                        <ul class="mb-0">
                            `;
                            
                            data.errors.forEach(error => {
                                content += `<li>${error}</li>`;
                            });
                            
                            content += `
                                        </ul>
                                    </div>
                                </div>
                            `;
                        }
                    }
                    
                    document.getElementById('uploadDetailsContent').innerHTML = content;
                })
                .catch(error => {
                    document.getElementById('uploadDetailsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Error loading upload details: ${error.message}
                        </div>
                    `;
                });
        });
    }
});
</script>

<?php /* No need to include footer as it's already included by renderSuperAdmin method */ ?> 