<?php
declare(strict_types=1);
defined('BASE_PATH') or exit('Access Denied!');
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Bulk Household Deductions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/household'); ?>">Household Management</a></li>
        <li class="breadcrumb-item active">Bulk Deductions</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-upload me-1"></i>
                    Bulk Household Deductions
                </div>
                <div class="card-body">
                    <?php if (isset($bulk_results)): ?>
                        <div class="alert <?php echo isset($bulk_results['failed']) && $bulk_results['failed'] > 0 ? 'alert-warning' : 'alert-success'; ?> mb-4">
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
                            <label for="file" class="form-label">Upload CSV File</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                            <div class="form-text">Upload a CSV file with household deduction data.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">CSV Format Requirements</label>
                            <div class="alert alert-secondary">
                                <p class="mb-1"><strong>reference_number:</strong> The reference number of the household purchase</p>
                                <p class="mb-1"><strong>amount:</strong> Payment amount</p>
                                <p class="mb-1"><strong>payment_date:</strong> Date of payment (YYYY-MM-DD)</p>
                                <p class="mb-1"><strong>receipt_number:</strong> Receipt number (optional)</p>
                                <p class="mb-0"><strong>notes:</strong> Additional notes (optional)</p>
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
                            <li>The first row of your CSV file must contain the column headers</li>
                            <li>Required headers: reference_number, amount, payment_date</li>
                            <li>Optional headers: receipt_number, notes</li>
                            <li>All deductions will be validated before processing</li>
                            <li>A detailed report will be provided after processing</li>
                        </ul>
                        <p class="mb-0">For adding a single household deduction, use the <a href="<?php echo url('/superadmin/add-household-deduction'); ?>">Individual Deduction</a> page.</p>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-csv me-1"></i>
                    Sample CSV Format
                </div>
                <div class="card-body">
                    <pre class="mb-0"><code>reference_number,amount,payment_date,receipt_number,notes
HP-2023-001,5000.00,2025-04-15,REC-12345,Monthly payment
HP-2023-002,10000.00,2025-04-15,REC-12346,Full payment</code></pre>
                </div>
            </div>
        </div>
    </div>
</div> 