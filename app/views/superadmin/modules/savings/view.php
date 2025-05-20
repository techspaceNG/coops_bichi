<?php /* This file is included by the renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Member Savings Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/savings'); ?>">Savings Management</a></li>
        <li class="breadcrumb-item active">View Details</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user me-1"></i>
                        Member Information
                    </div>
                    <div>
                        <a href="<?php echo url('/superadmin/savings/edit/' . $member['id']); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit Monthly Deduction
                        </a>
                        <a href="<?php echo url('/superadmin/savings/add'); ?>?member_id=<?php echo $member['id']; ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Add Deduction
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Full Name:</th>
                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                </tr>
                                <tr>
                                    <th>COOPS No.:</th>
                                    <td><?php echo htmlspecialchars($member['coop_no']); ?></td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td><?php echo htmlspecialchars($member['department_name'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo htmlspecialchars($member['phone']); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Monthly Deduction:</th>
                                    <td>₦<?php echo number_format($savings['monthly_deduction'] ?? 0, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Cumulative Savings:</th>
                                    <td>₦<?php echo number_format($savings['cumulative_amount'] ?? 0, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Deposits:</th>
                                    <td>₦<?php echo number_format($statistics['total_deposits'] ?? 0, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Withdrawals:</th>
                                    <td>₦<?php echo number_format($statistics['total_withdrawals'] ?? 0, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Current Balance:</th>
                                    <td>₦<?php echo number_format($statistics['current_balance'] ?? 0, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Deduction Date:</th>
                                    <td><?php echo isset($savings['last_deduction_date']) && $savings['last_deduction_date'] ? date('M d, Y', strtotime($savings['last_deduction_date'])) : 'N/A'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Transaction History
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="transactionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-transactions-tab" data-bs-toggle="tab" data-bs-target="#all-transactions" type="button" role="tab" aria-controls="all-transactions" aria-selected="true">
                                All Transactions (<?php echo count($transactions); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="deposits-tab" data-bs-toggle="tab" data-bs-target="#deposits" type="button" role="tab" aria-controls="deposits" aria-selected="false">
                                Deposits (<?php echo count($deductions); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="withdrawals-tab" data-bs-toggle="tab" data-bs-target="#withdrawals" type="button" role="tab" aria-controls="withdrawals" aria-selected="false">
                                Withdrawals (<?php echo count($withdrawals); ?>)
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="transactionTabsContent">
                        <div class="tab-pane fade show active" id="all-transactions" role="tabpanel" aria-labelledby="all-transactions-tab">
                            <?php if (empty($transactions)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    No transactions found for this member.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Amount (₦)</th>
                                                <th>Description</th>
                                                <th>Processed By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($transactions as $transaction): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($transaction['created_at'])); ?></td>
                                                    <td>
                                                        <?php if ($transaction['transaction_type'] === 'deposit'): ?>
                                                            <span class="badge bg-success">Deposit</span>
                                                        <?php elseif ($transaction['transaction_type'] === 'withdrawal'): ?>
                                                            <span class="badge bg-danger">Withdrawal</span>
                                                        <?php elseif ($transaction['transaction_type'] === 'interest'): ?>
                                                            <span class="badge bg-primary">Interest</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Adjustment</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo number_format($transaction['amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($transaction['description'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($transaction['admin_name'] ?? 'System'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="deposits" role="tabpanel" aria-labelledby="deposits-tab">
                            <?php if (empty($deductions)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    No deposits found for this member.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount (₦)</th>
                                                <th>Description</th>
                                                <th>Processed By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($deductions as $deduction): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($deduction['created_at'])); ?></td>
                                                    <td><?php echo number_format($deduction['amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($deduction['description'] ?? 'Monthly Deduction'); ?></td>
                                                    <td><?php echo htmlspecialchars($deduction['admin_name'] ?? 'System'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="withdrawals" role="tabpanel" aria-labelledby="withdrawals-tab">
                            <?php if (empty($withdrawals)): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    No withdrawals found for this member.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount (₦)</th>
                                                <th>Description</th>
                                                <th>Processed By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($withdrawals as $withdrawal): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($withdrawal['created_at'])); ?></td>
                                                    <td><?php echo number_format($withdrawal['amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($withdrawal['description'] ?? 'Withdrawal'); ?></td>
                                                    <td><?php echo htmlspecialchars($withdrawal['admin_name'] ?? 'System'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between">
                <a href="<?php echo url('/superadmin/savings'); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Savings Management
                </a>
                <div>
                    <a href="<?php echo url('/superadmin/savings/edit/' . $member['id']); ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Monthly Deduction
                    </a>
                    <a href="<?php echo url('/superadmin/view-member/' . $member['id']); ?>" class="btn btn-info">
                        <i class="fas fa-user me-1"></i> View Member Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the tabs
    var tabs = document.querySelectorAll('#transactionTabs button')
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function(event) {
            event.preventDefault();
            // Activate the selected tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            // Show the corresponding tab content
            var tabId = this.getAttribute('data-bs-target');
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            document.querySelector(tabId).classList.add('show', 'active');
        });
    });
    
    // Initialize DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('table.table').DataTable({
            order: [[0, 'desc']], // Sort by date by default
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            responsive: true
        });
    }
});
</script> 