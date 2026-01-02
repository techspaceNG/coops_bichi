<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Register Member</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/members') ?>" class="text-decoration-none text-muted">Members Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">New Registration</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/members') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> 
                <span>Back to Directory</span>
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <form action="<?= url('/superadmin/add-member') ?>" method="POST" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Personal & Work Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">Profile & Organizational Context</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="coop_no" class="form-label fw-semibold small text-muted text-uppercase">COOPS Registry No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light bg-light" id="coop_no" name="coop_no" required placeholder="e.g. CO-1234...">
                                <div class="form-text small opacity-75">Primary unique identifier for this member.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="ti_number" class="form-label fw-semibold small text-muted text-uppercase">Tax ID Reference</label>
                                <input type="text" class="form-control border-light bg-light" id="ti_number" name="ti_number" placeholder="Enter TI number if available">
                            </div>
                            
                            <div class="col-md-12 mt-4">
                                <label for="name" class="form-label fw-semibold small text-muted text-uppercase">Legal Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light bg-light" id="name" name="name" required placeholder="Enter surname and other names">
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Primary Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control border-light bg-light" id="email" name="email" required placeholder="name@example.com">
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <label for="phone" class="form-label fw-semibold small text-muted text-uppercase">Communication Line</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control border-light bg-light" id="phone" name="phone" placeholder="+234...">
                                </div>
                            </div>
                            
                            <div class="col-md-8 mt-4">
                                <label for="department_id" class="form-label fw-semibold small text-muted text-uppercase">Assigned Department</label>
                                <select class="form-select border-light bg-light" id="department_id" name="department_id">
                                    <option value="">Select Organizational Unit</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept['id']) ?>">
                                            <?= htmlspecialchars($dept['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mt-4 d-flex align-items-end">
                                <div class="p-3 bg-light border rounded-3 w-100">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label ms-2 fw-semibold small text-muted" for="is_active">ACTIVE STATUS</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <label for="address" class="form-label fw-semibold small text-muted text-uppercase">Residential Address</label>
                                <textarea class="form-control border-light bg-light" id="address" name="address" rows="3" placeholder="Enter physical address details..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Onboarding -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0">Bank Disbursement Accounts</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="account_number" class="form-label fw-semibold small text-muted text-uppercase">Account Number</label>
                                <input type="text" class="form-control border-light bg-light" id="account_number" name="account_number" maxlength="10" placeholder="10 Digits">
                            </div>
                            <div class="col-md-6">
                                <label for="bank_name" class="form-label fw-semibold small text-muted text-uppercase">Financial Institution</label>
                                <input type="text" class="form-control border-light bg-light" id="bank_name" name="bank_name" placeholder="e.g. Zenith, GTB...">
                            </div>
                            <div class="col-md-8">
                                <label for="account_name" class="form-label fw-semibold small text-muted text-uppercase">Registered Account Name</label>
                                <input type="text" class="form-control border-light bg-light" id="account_name" name="account_name" placeholder="Matches bank record">
                            </div>
                            <div class="col-md-4">
                                <label for="bvn" class="form-label fw-semibold small text-muted text-uppercase">BVN Verification</label>
                                <input type="text" class="form-control border-light bg-light" id="bvn" name="bvn" maxlength="11" placeholder="11 Digits">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Balance Initialization -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                        <i class="fas fa-coins text-primary"></i>
                        <h6 class="fw-bold mb-0">Balance Initialization</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-4">
                            <div>
                                <label for="savings_balance" class="form-label fw-semibold small text-muted text-uppercase">Opening Savings</label>
                                <div class="input-group shadow-sm border rounded">
                                    <span class="input-group-text border-0 bg-white">₦</span>
                                    <input type="number" class="form-control border-0" id="savings_balance" name="savings_balance" value="0.00" step="0.01" min="0">
                                </div>
                            </div>
                            <div>
                                <label for="loan_balance" class="form-label fw-semibold small text-muted text-uppercase">Opening Loan Bal</label>
                                <div class="input-group shadow-sm border rounded">
                                    <span class="input-group-text border-0 bg-white">₦</span>
                                    <input type="number" class="form-control border-0" id="loan_balance" name="loan_balance" value="0.00" step="0.01" min="0">
                                </div>
                            </div>
                            <div>
                                <label for="household_balance" class="form-label fw-semibold small text-muted text-uppercase">Household Credit</label>
                                <div class="input-group shadow-sm border rounded">
                                    <span class="input-group-text border-0 bg-white">₦</span>
                                    <input type="number" class="form-control border-0" id="household_balance" name="household_balance" value="0.00" step="0.01" min="0">
                                </div>
                            </div>
                            <div>
                                <label for="shares_balance" class="form-label fw-semibold small text-muted text-uppercase">Shares Capital</label>
                                <div class="input-group shadow-sm border rounded">
                                    <span class="input-group-text border-0 bg-white">₦</span>
                                    <input type="number" class="form-control border-0" id="shares_balance" name="shares_balance" value="0.00" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm fw-bold">
                                <i class="fas fa-user-plus me-2"></i> Register New Member
                            </button>
                            <a href="<?= url('/superadmin/members') ?>" class="btn btn-light w-100 mt-3 py-2 text-muted small">Discard & Cancel</a>
                        </div>
                    </div>
                </div>

                <!-- Registration Help -->
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body p-4 small">
                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-primary">
                            <i class="fas fa-info-circle"></i> Onboarding Guide
                        </h6>
                        <ul class="ps-3 mb-0 d-grid gap-2 text-muted">
                            <li>Ensure the <strong>COOPS No.</strong> matches the manual registry record.</li>
                            <li>The <strong>Email Address</strong> will be used for automated portal access notifications.</li>
                            <li>Initial balances should reflect the current audited state from the ledger.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    
    // Custom validation logic
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});
</script> 