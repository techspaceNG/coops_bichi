<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Decline Loan Application</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/loans'); ?>">Loans</a></li>
        <li class="breadcrumb-item active">Decline Application</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-times-circle me-1"></i>
            Decline Loan Application
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Application Details</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 40%">Member Name:</th>
                            <td><?php echo htmlspecialchars($application['fullname']); ?></td>
                        </tr>
                        <tr>
                            <th>COOPS Number:</th>
                            <td><?php echo htmlspecialchars($application['coop_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Loan Amount:</th>
                            <td>₦<?php echo number_format($application['loan_amount'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>IP Figure:</th>
                            <td>₦<?php echo number_format($application['ip_figure'] ?? 0, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Application Date:</th>
                            <td><?php echo date('M d, Y', strtotime($application['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <th>Purpose:</th>
                            <td><?php echo htmlspecialchars($application['purpose'] ?? 'Not specified'); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important Note</h5>
                        <p>You are about to decline this loan application. This action cannot be undone.</p>
                        <p>Please provide a reason for declining this application. This reason will be shared with the member.</p>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="<?php echo url('/superadmin/decline-loan-application/' . $application['id']); ?>">
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Declining</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    <div class="form-text">Please provide a clear explanation for declining the loan application.</div>
                </div>
                
                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-danger me-2">
                        <i class="fas fa-times-circle me-2"></i>Decline Application
                    </button>
                    <a href="<?php echo url('/superadmin/loans'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div> 