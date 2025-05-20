<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Bulk Upload Members</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/members'); ?>">Members Management</a></li>
        <li class="breadcrumb-item active">Bulk Upload</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-upload me-1"></i>
                    Upload CSV File
                </div>
                <div class="card-body">
                    <form action="<?php echo url('/superadmin/upload-members'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="file" class="form-label">CSV File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                            <div class="form-text">Upload a CSV file with member data</div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo url('/superadmin/members'); ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Upload and Process</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Instructions
                </div>
                <div class="card-body">
                    <h5>CSV File Format</h5>
                    <p>Your CSV file should include the following columns:</p>
                    <ul>
                        <li><strong>coop_no</strong> - Unique identifier (required)</li>
                        <li><strong>name</strong> - Full name (required)</li>
                        <li><strong>email</strong> - Email address (required)</li>
                        <li><strong>phone</strong> - Phone number (optional)</li>
                        <li><strong>department</strong> - Department name (optional)</li>
                        <li><strong>address</strong> - Address (optional)</li>
                    </ul>
                    
                    <h5>Additional Optional Fields</h5>
                    <p>You can also include these additional fields:</p>
                    <ul>
                        <li><strong>ti_number</strong> - Tax Identification Number</li>
                        <li><strong>account_number</strong> - Bank account number</li>
                        <li><strong>bank_name</strong> - Name of bank</li>
                        <li><strong>account_name</strong> - Name on bank account</li>
                        <li><strong>bvn</strong> - Bank Verification Number</li>
                        <li><strong>savings_balance</strong> - Initial savings balance</li>
                        <li><strong>loan_balance</strong> - Initial loan balance</li>
                        <li><strong>household_balance</strong> - Initial household purchases balance</li>
                        <li><strong>shares_balance</strong> - Initial shares balance</li>
                        <li><strong>is_active</strong> - Member status (1=active, 0=inactive)</li>
                    </ul>
                    
                    <h5>Example File</h5>
                    <p>Download a sample CSV template:</p>
                    <a href="#" id="downloadSample" class="btn btn-outline-primary">
                        <i class="fas fa-download me-1"></i> Download Template
                    </a>
                    
                    <hr>
                    
                    <h5>Notes:</h5>
                    <ul>
                        <li>The first row should contain column headers</li>
                        <li>All members will be set as active by default (unless is_active=0 is specified)</li>
                        <li>If a department doesn't exist, it will be created</li>
                        <li>Duplicate COOPS numbers or emails will be skipped</li>
                        <li>Temporary passwords will be auto-generated for all members</li>
                        <li>Passwords for the first 5 members will be displayed after import</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate and download sample CSV
    document.getElementById('downloadSample').addEventListener('click', function(e) {
        e.preventDefault();
        
        const csvContent = 'coop_no,name,email,phone,department,address,ti_number,account_number,bank_name,savings_balance,is_active\n' +
                          'COOPS001,John Doe,john@example.com,08012345678,Administration,"123 Main St, Bichi",1234567890,0123456789,"First Bank",5000,1\n' +
                          'COOPS002,Jane Smith,jane@example.com,08098765432,Finance,"456 Park Ave, Bichi",0987654321,9876543210,"Zenith Bank",2500,1';
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'members_template.csv');
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
    
    // File validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file');
        
        if (fileInput.files.length === 0) {
            e.preventDefault();
            alert('Please select a CSV file to upload');
            return false;
        }
        
        const fileName = fileInput.files[0].name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        
        if (fileExt !== 'csv') {
            e.preventDefault();
            alert('Please upload a CSV file');
            return false;
        }
    });
});
</script> 