<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Member Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/members'); ?>">Members Management</a></li>
        <li class="breadcrumb-item active">View Member</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <?php if (isset($member) && !empty($member)): ?>
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Member Information
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-secondary bg-opacity-25 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($member['name']); ?></h4>
                        <p class="text-muted mb-0">COOPS No: <?php echo htmlspecialchars($member['coop_no']); ?></p>
                        <p class="text-muted mb-0">TI No: <?php echo !empty($member['ti_number']) ? htmlspecialchars($member['ti_number']) : 'Not provided'; ?></p>
                        <p class="badge <?php echo $member['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                        </p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Department:</label>
                        <p><?php echo !empty($member['department_name']) ? htmlspecialchars($member['department_name']) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Email Address:</label>
                        <p><?php echo htmlspecialchars($member['email']); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Phone Number:</label>
                        <p><?php echo !empty($member['phone']) ? htmlspecialchars($member['phone']) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Address:</label>
                        <p><?php echo !empty($member['address']) ? htmlspecialchars($member['address']) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Joined Date:</label>
                        <p><?php echo date('F j, Y', strtotime($member['created_at'])); ?></p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo url('/superadmin/members'); ?>" class="btn btn-secondary">Back to List</a>
                        <a href="<?php echo url('/superadmin/edit-member/' . $member['id']); ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header nav nav-tabs card-header-tabs" id="memberTabs" role="tablist">
                    <div class="nav-item">
                        <a class="nav-link active" id="savings-tab" data-bs-toggle="tab" href="#savings" role="tab" aria-controls="savings" aria-selected="true">
                            <i class="fas fa-piggy-bank me-1"></i> Savings
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" id="loans-tab" data-bs-toggle="tab" href="#loans" role="tab" aria-controls="loans" aria-selected="false">
                            <i class="fas fa-money-bill-wave me-1"></i> Loans
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" id="purchases-tab" data-bs-toggle="tab" href="#purchases" role="tab" aria-controls="purchases" aria-selected="false">
                            <i class="fas fa-shopping-cart me-1"></i> Household Purchases
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <!-- Savings Tab -->
                        <div class="tab-pane fade show active" id="savings" role="tabpanel" aria-labelledby="savings-tab">
                            <?php if (isset($savings) && !empty($savings)): ?>
                                <div class="mt-3">
                                    <h5>Monthly Deduction: <span class="text-primary">₦<?php echo number_format($savings['monthly_deduction'] ?? 0, 2); ?></span></h5>
                                    <h5>Cumulative Amount: <span class="text-success">₦<?php echo number_format($savings['cumulative_amount'] ?? 0, 2); ?></span></h5>
                                    <p>Last Deduction Date: 
                                        <?php echo isset($savings['last_deduction_date']) && $savings['last_deduction_date'] ? 
                                            date('M j, Y', strtotime($savings['last_deduction_date'])) : 
                                            'No deductions yet'; 
                                        ?>
                                    </p>
                                </div>
                                
                                <!-- Link to view deduction history -->
                                <div class="mt-3">
                                    <a href="<?php echo url('/superadmin/savings-history/' . $member['id']); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-history me-1"></i> View Deduction History
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i> No savings records found for this member.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Loans Tab -->
                        <div class="tab-pane fade" id="loans" role="tabpanel" aria-labelledby="loans-tab">
                            <?php if (isset($loans) && !empty($loans)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Loan ID</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($loans as $loan): ?>
                                            <tr>
                                                <td><?php echo $loan['id']; ?></td>
                                                <td>₦<?php echo number_format($loan['loan_amount'] ?? 0, 2); ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?php 
                                                        switch($loan['status']) {
                                                            case 'pending': echo 'bg-warning'; break;
                                                            case 'approved': echo 'bg-success'; break;
                                                            case 'rejected': echo 'bg-danger'; break;
                                                            case 'completed': echo 'bg-info'; break;
                                                            default: echo 'bg-secondary';
                                                        }
                                                        ?>">
                                                        <?php echo ucfirst($loan['status'] ?? 'unknown'); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($loan['created_at']) && $loan['created_at'] ? date('M j, Y', strtotime($loan['created_at'])) : 'N/A'; ?></td>
                                                <td>
                                                    <a href="<?php echo url('/superadmin/view-loan/' . $loan['id']); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i> No loan records found for this member.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Purchases Tab -->
                        <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                            <?php if (isset($household) && !empty($household)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($household as $purchase): ?>
                                            <tr>
                                                <td><?php echo $purchase['id']; ?></td>
                                                <td><?php echo htmlspecialchars($purchase['description'] ?? 'N/A'); ?></td>
                                                <td>₦<?php echo number_format($purchase['amount'] ?? 0, 2); ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?php 
                                                        switch($purchase['status']) {
                                                            case 'pending': echo 'bg-warning'; break;
                                                            case 'processing': echo 'bg-primary'; break;
                                                            case 'approved': echo 'bg-success'; break;
                                                            case 'declined': echo 'bg-danger'; break;
                                                            default: echo 'bg-secondary';
                                                        }
                                                        ?>">
                                                        <?php echo ucfirst($purchase['status'] ?? 'unknown'); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo isset($purchase['created_at']) && $purchase['created_at'] ? date('M j, Y', strtotime($purchase['created_at'])) : 'N/A'; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i> No household purchase records found for this member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Financial Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-1"></i>
                    Financial Summary
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary bg-opacity-10 h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-piggy-bank fa-2x text-primary mb-2"></i>
                                    <h6 class="card-title">Savings Balance</h6>
                                    <h5 class="card-text">₦<?php echo number_format($member['savings_balance'] ?? 0, 2); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger bg-opacity-10 h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-hand-holding-usd fa-2x text-danger mb-2"></i>
                                    <h6 class="card-title">Loan Balance</h6>
                                    <h5 class="card-text">₦<?php echo number_format($member['loan_balance'] ?? 0, 2); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success bg-opacity-10 h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-basket fa-2x text-success mb-2"></i>
                                    <h6 class="card-title">Household Balance</h6>
                                    <h5 class="card-text">₦<?php echo number_format($member['household_balance'] ?? 0, 2); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info bg-opacity-10 h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                    <h6 class="card-title">Shares Balance</h6>
                                    <h5 class="card-text">₦<?php echo number_format($member['shares_balance'] ?? 0, 2); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bank Account Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-university me-1"></i>
                    Bank Account Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Account Number:</label>
                                <p><?php echo !empty($member['account_number']) ? htmlspecialchars($member['account_number']) : 'Not provided'; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Bank Name:</label>
                                <p><?php echo !empty($member['bank_name']) ? htmlspecialchars($member['bank_name']) : 'Not provided'; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Account Name:</label>
                                <p><?php echo !empty($member['account_name']) ? htmlspecialchars($member['account_name']) : 'Not provided'; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">BVN:</label>
                                <p><?php echo !empty($member['bvn']) ? htmlspecialchars($member['bvn']) : 'Not provided'; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($member['bank_branch'])): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Bank Branch:</label>
                                <p><?php echo htmlspecialchars($member['bank_branch']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-1"></i> Member not found or has been deleted.
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap tab functionality
    const triggerTabList = [].slice.call(document.querySelectorAll('#memberTabs a'));
    triggerTabList.forEach(function(triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script> 