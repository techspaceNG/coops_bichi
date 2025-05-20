<?php
declare(strict_types=1);
/**
 * Review and approve/reject loan template
 */
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Review Loan: <?= htmlspecialchars($loan['loan_number'] ?? 'Unknown') ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= url('/superadmin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/superadmin/loans') ?>">Loans</a></li>
        <li class="breadcrumb-item active">Review Loan</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-check-alt me-1"></i>
                    Loan Review
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Please review the loan application details below and take action as appropriate.
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Member Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Member Name</th>
                                    <td><?= htmlspecialchars($loan['member_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Cooperative Number</th>
                                    <td><?= htmlspecialchars($loan['member_coop_no'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= htmlspecialchars($loan['member_email'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Loan Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Loan Number</th>
                                    <td><?= htmlspecialchars($loan['loan_number'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Application Date</th>
                                    <td><?= date('M d, Y', strtotime($loan['application_date'] ?? 'now')) ?></td>
                                </tr>
                                <tr>
                                    <th>Loan Amount</th>
                                    <td>₦<?= number_format((float)($loan['loan_amount'] ?? 0), 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <form action="<?= url('/superadmin/review-loan/' . $loan['id']) ?>" method="post" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Request::getCsrfToken() ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                                <input type="number" class="form-control" id="interest_rate" name="interest_rate" 
                                       value="<?= htmlspecialchars((string)($loan['interest_rate'] ?? 5)) ?>" step="0.01" min="0" max="100" required>
                                <div class="form-text">Default interest rate is 5%</div>
                            </div>
                            <div class="col-md-6">
                                <label for="ip_figure" class="form-label">Administrative Charge (₦)</label>
                                <input type="number" class="form-control" id="ip_figure" name="ip_figure" 
                                       value="<?= htmlspecialchars((string)($loan['ip_figure'] ?? (float)($loan['loan_amount'] ?? 0) * 0.05)) ?>" step="0.01" min="0" required>
                                <div class="form-text">Default is 5% of loan amount</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($loan['admin_notes'] ?? '') ?></textarea>
                            <div class="form-text">Optional notes about this loan application</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= url('/superadmin/view-loan/' . $loan['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Loan Details
                            </a>
                            
                            <div>
                                <button type="submit" name="action" value="reject" class="btn btn-danger me-2">
                                    <i class="fas fa-times"></i> Reject Loan
                                </button>
                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                    <i class="fas fa-check"></i> Approve Loan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate admin charge based on interest rate
    const loanAmount = <?= (float)($loan['loan_amount'] ?? 0) ?>;
    const interestRateField = document.getElementById('interest_rate');
    const adminChargeField = document.getElementById('ip_figure');
    
    interestRateField.addEventListener('change', function() {
        const rate = parseFloat(this.value) || 5;
        const adminCharge = (loanAmount * rate / 100).toFixed(2);
        adminChargeField.value = adminCharge;
    });
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script> 