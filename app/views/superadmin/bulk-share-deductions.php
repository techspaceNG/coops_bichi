<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Bulk Share Deductions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/shares'); ?>">Shares Management</a></li>
        <li class="breadcrumb-item active">Bulk Share Deductions</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-upload me-1"></i>
            Upload Bulk Share Deductions
        </div>
        <div class="card-body">
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
    });
</script> 