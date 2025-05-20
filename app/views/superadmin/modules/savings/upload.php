<?php /* This file is included by the renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Upload Savings Deductions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/savings'); ?>">Savings Management</a></li>
        <li class="breadcrumb-item active">Upload Deductions</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-upload me-1"></i>
                    Upload Savings Deductions
                </div>
                <div class="card-body">
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['file'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['file']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['processing'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['processing']); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">Deductions uploaded successfully!</div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>Instructions</h5>
                        <p>Upload a CSV file containing member savings deductions with the following columns:</p>
                        <ul>
                            <li><strong>coop_no</strong> - The cooperative number of the member</li>
                            <li><strong>amount</strong> - The deduction amount in Naira</li>
                        </ul>
                        <p>The first row should contain the column headers. Each subsequent row should contain data for a single member.</p>
                    </div>
                    
                    <form action="<?php echo url('/superadmin/savings/upload'); ?>" method="POST" enctype="multipart/form-data" class="mt-4">
                        <div class="mb-4">
                            <label for="deduction_file" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control <?php echo isset($errors['file']) ? 'is-invalid' : ''; ?>" 
                                   id="deduction_file" name="deduction_file" accept=".csv" required>
                            <div class="form-text">Only CSV files are supported.</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="updateMonthly" name="update_monthly">
                                <label class="form-check-label" for="updateMonthly">
                                    Update monthly deduction amounts
                                </label>
                                <div class="form-text">If checked, the uploaded amounts will also be set as the members' monthly deduction amounts.</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deduction_date" class="form-label">Deduction Date</label>
                            <input type="date" class="form-control" id="deduction_date" name="deduction_date" 
                                   value="<?php echo date('Y-m-d'); ?>">
                            <div class="form-text">Date when these deductions were processed.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" 
                                   value="Monthly Deduction" placeholder="e.g., Monthly Deduction">
                            <div class="form-text">Description for these deductions.</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo url('/superadmin/savings'); ?>" class="btn btn-outline-secondary">Cancel</a>
                            <a href="<?php echo url('/superadmin/savings/download-template'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download Template
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Deductions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 