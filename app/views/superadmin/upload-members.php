<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Mass Registry Import</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/members') ?>" class="text-decoration-none text-muted">Member Registry</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Bulk Import</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/members') ?>" class="btn btn-white border shadow-sm small fw-bold">
                <i class="fas fa-arrow-left me-2 text-muted"></i> Return to Registry
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-file-csv text-primary opacity-50"></i>
                    <h6 class="fw-bold mb-0">Ingestion Asset Configuration</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/upload-members') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="file" class="form-label fw-bold small text-muted text-uppercase">Raw Data File (CSV)</label>
                            <div class="input-group">
                                <input type="file" class="form-control border-light bg-light py-2" id="file" name="file" accept=".csv" required>
                                <span class="input-group-text bg-light border-light border-start-0 text-muted"><i class="fas fa-search"></i></span>
                            </div>
                            <div class="form-text small opacity-75 mt-2">Maximum file size: 10MB. Content must adhere to system syntax standards.</div>
                        </div>
                        
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-primary mb-4">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="sendInvitations" name="send_invitations" value="1" checked>
                                <label class="form-check-label small fw-bold" for="sendInvitations">
                                    DISPATCH CREDENTIALS IMMEDIATELY
                                </label>
                                <p class="small text-muted mb-0 mt-1">Automatic generation of secure access tokens and welcome emails to all valid entries.</p>
                            </div>
                        </div>
                        
                        <hr class="my-4 opacity-10">
                        
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <button type="reset" class="btn btn-link text-muted text-decoration-none small">Clear Form</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm py-2">
                                <i class="fas fa-database me-2"></i> COMMENCE MASS INGESTION
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <!-- Documentation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">Registry Schema Standard</h6>
                </div>
                <div class="card-body p-4">
                    <h6 class="small fw-bold text-uppercase text-primary mb-3">Mandatory Columns</h6>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2 d-flex gap-2 small">
                            <code>coop_no</code> <span class="text-muted">Unique Registry ID</span>
                        </li>
                        <li class="mb-2 d-flex gap-2 small">
                            <code>name</code> <span class="text-muted">Legal Entitiy Name</span>
                        </li>
                        <li class="mb-2 d-flex gap-2 small">
                            <code>email</code> <span class="text-muted">Communication Endpoint</span>
                        </li>
                    </ul>

                    <h6 class="small fw-bold text-uppercase text-muted mb-3">Optional Attributes</h6>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-light text-dark fw-normal border">phone</span>
                        <span class="badge bg-light text-dark fw-normal border">department</span>
                        <span class="badge bg-light text-dark fw-normal border">address</span>
                        <span class="badge bg-light text-dark fw-normal border">ti_number</span>
                        <span class="badge bg-light text-dark fw-normal border">bank_name</span>
                        <span class="badge bg-light text-dark fw-normal border">account_no</span>
                        <span class="badge bg-light text-dark fw-normal border">shares_bal</span>
                    </div>
                    
                    <div class="d-grid shadow-sm">
                        <button id="downloadSample" class="btn btn-outline-primary btn-sm fw-bold py-2">
                            <i class="fas fa-file-download me-2"></i> DOWNLOAD CSV SPECIFICATION
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card bg-primary bg-opacity-10 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold small text-primary text-uppercase mb-3"><i class="fas fa-terminal me-2"></i>System Behaviors</h6>
                    <ul class="list-unstyled small mb-0 opacity-75">
                        <li class="mb-2">• Duplicate IDs or emails will be <strong>discarded</strong></li>
                        <li class="mb-2">• New departments are created <strong>on-the-fly</strong></li>
                        <li class="mb-2">• Initial balances are <strong>automatically reconciled</strong></li>
                        <li>• Passwords for first 5 entries displayed in <strong>post-sync log</strong></li>
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
            return false;
        }
        
        const fileName = fileInput.files[0].name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        
        if (fileExt !== 'csv') {
            e.preventDefault();
            alert('Restricted: System only accepts valid .CSV data assets.');
            return false;
        }
    });
});
</script> 
