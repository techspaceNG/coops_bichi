<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Member</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/members'); ?>">Members Management</a></li>
        <li class="breadcrumb-item active">Add New Member</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-plus me-1"></i>
            Member Information
        </div>
        <div class="card-body">
            <form action="<?php echo url('/superadmin/add-member'); ?>" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="coop_no" class="form-label">COOPS No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="coop_no" name="coop_no" required>
                            <div class="form-text">Unique identifier for the member</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ti_number" class="form-label">TI Number</label>
                            <input type="text" class="form-control" id="ti_number" name="ti_number">
                            <div class="form-text">Tax Identification Number</div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select" id="department_id" name="department_id">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo htmlspecialchars($dept['id']); ?>">
                                        <?php echo htmlspecialchars($dept['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active Member</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                </div>
                
                <hr>
                
                <h5 class="mb-3">Bank Account Information</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" maxlength="10">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="account_name" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="account_name" name="account_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bvn" class="form-label">BVN</label>
                            <input type="text" class="form-control" id="bvn" name="bvn" maxlength="11">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h5 class="mb-3">Initial Balances</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="savings_balance" class="form-label">Initial Savings Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" class="form-control" id="savings_balance" name="savings_balance" value="0.00" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="loan_balance" class="form-label">Initial Loan Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" class="form-control" id="loan_balance" name="loan_balance" value="0.00" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="household_balance" class="form-label">Initial Household Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" class="form-control" id="household_balance" name="household_balance" value="0.00" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="shares_balance" class="form-label">Initial Shares Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" class="form-control" id="shares_balance" name="shares_balance" value="0.00" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <a href="<?php echo url('/superadmin/members'); ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        // Validate COOPS No.
        const coopNo = document.getElementById('coop_no').value.trim();
        if (!coopNo) {
            valid = false;
            document.getElementById('coop_no').classList.add('is-invalid');
        } else {
            document.getElementById('coop_no').classList.remove('is-invalid');
        }
        
        // Validate Name
        const name = document.getElementById('name').value.trim();
        if (!name) {
            valid = false;
            document.getElementById('name').classList.add('is-invalid');
        } else {
            document.getElementById('name').classList.remove('is-invalid');
        }
        
        // Validate Email
        const email = document.getElementById('email').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            valid = false;
            document.getElementById('email').classList.add('is-invalid');
        } else {
            document.getElementById('email').classList.remove('is-invalid');
        }
        
        if (!valid) {
            e.preventDefault();
        }
    });
});
</script> 