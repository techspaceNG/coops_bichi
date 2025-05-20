<?php
declare(strict_types=1);
/**
 * Loan details view template
 */
?>
<div class="container-fluid px-4">
    <?php $id = $loan['id'] ?? $loan['loan_id'] ?? 0; ?>
    <h1 class="mt-4">
        <?php if ($is_application ?? false): ?>
            Loan Application: <?= htmlspecialchars($loan['application_number'] ?? $loan['display_id'] ?? 'Unknown') ?>
        <?php else: ?>
            Loan Details: <?= htmlspecialchars($loan['loan_number'] ?? 'Unknown') ?>
        <?php endif; ?>
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= url('/superadmin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= url('/superadmin/loans') ?>">Loans</a></li>
        <li class="breadcrumb-item active">
            <?= $is_application ? 'View Application' : 'View Loan' ?>
        </li>
    </ol>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-money-check-alt me-1"></i>
                    <?= $is_application ? 'Loan Application Details' : 'Loan Details' ?>
                    <div class="float-end">
                        <a href="<?= url('/superadmin/print-loan/' . $id) ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status and action buttons -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Status: 
                                    <?php
                                    $status = $loan['status'] ?? 'pending';
                                    $statusBadgeClass = 'bg-warning text-dark';
                                    
                                    if ($status === 'approved' || $status === 'active') {
                                        $statusBadgeClass = 'bg-success';
                                    } elseif ($status === 'rejected' || $status === 'declined') {
                                        $statusBadgeClass = 'bg-danger';
                                    } elseif ($status === 'completed' || $status === 'closed') {
                                        $statusBadgeClass = 'bg-info';
                                    }
                                    ?>
                                    <span class="badge <?= $statusBadgeClass ?>">
                                        <?= ucfirst(htmlspecialchars($status)) ?>
                                    </span>
                                </h5>
                                
                                <?php if (isset($loan['approval_date']) && $loan['approval_date']): ?>
                                    <p class="mb-0">Approval Date: <?= date('M d, Y', strtotime($loan['approval_date'])) ?></p>
                                <?php endif; ?>
                                
                                <?php if (isset($loan['approved_by_name']) && $loan['approved_by_name']): ?>
                                    <p class="mb-0">Approved By: <?= htmlspecialchars($loan['approved_by_name']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <?php if (($is_application ?? false) && ($loan['status'] ?? '') === 'pending'): ?>
                                    <a href="<?= url('/superadmin/approve-loan-application/' . $id) ?>" class="btn btn-success me-2">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                    <a href="<?= url('/superadmin/decline-loan-application/' . $id) ?>" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Decline
                                    </a>
                                <?php elseif (!($is_application ?? false) && ($loan['status'] ?? '') === 'pending'): ?>
                                    <a href="<?= url('/superadmin/review-loan/' . $id) ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Review Loan
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loan information -->
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
                                    <td><?= htmlspecialchars($loan['coop_no'] ?? $loan['member_coop_no'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= htmlspecialchars($loan['email'] ?? $loan['member_email'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <td><?= htmlspecialchars($loan['department_name'] ?? 'N/A') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Loan Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Application Date</th>
                                    <td><?= isset($loan['application_date']) ? date('M d, Y', strtotime($loan['application_date'])) : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Loan Amount</th>
                                    <td>₦<?= number_format((float)($loan['loan_amount'] ?? 0), 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Interest Rate</th>
                                    <td><?= number_format((float)($loan['interest_rate'] ?? 0), 2) ?>%</td>
                                </tr>
                                <tr>
                                    <th>Administrative Charge</th>
                                    <td>₦<?= number_format((float)($loan['ip_figure'] ?? 0), 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Repayment Period</th>
                                    <td><?= (int)($loan['repayment_period'] ?? 0) ?> Months</td>
                                </tr>
                                <tr>
                                    <th>Total Repayment</th>
                                    <td>₦<?= number_format((float)($loan['total_repayment'] ?? 0), 2) ?></td>
                                </tr>
                                <?php if (!($is_application ?? false)): ?>
                                <tr>
                                    <th>Remaining Balance</th>
                                    <td>₦<?= number_format((float)($remaining_balance ?? $loan['balance'] ?? 0), 2) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Repayment schedule -->
                    <?php if (!($is_application ?? false) && isset($repayments) && !empty($repayments)): ?>
                    <div class="mb-4">
                        <h5>Repayment History</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Receipt Number</th>
                                        <th>Processed By</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($repayments as $repayment): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($repayment['payment_date'])) ?></td>
                                        <td>₦<?= number_format((float)$repayment['amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($repayment['receipt_number'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($repayment['processed_by_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($repayment['notes'] ?? '') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th>Total Paid</th>
                                        <th colspan="4">₦<?= number_format((float)($total_paid ?? 0), 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Admin notes -->
                    <?php if (isset($loan['admin_notes']) && $loan['admin_notes']): ?>
                    <div class="mb-4">
                        <h5>Administrative Notes</h5>
                        <div class="card p-3 bg-light">
                            <?= nl2br(htmlspecialchars($loan['admin_notes'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Bank details -->
                    <div class="mb-4">
                        <h5>Bank Details</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">Bank Name</th>
                                <td><?= htmlspecialchars($loan['bank_name'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Account Number</th>
                                <td><?= htmlspecialchars($loan['account_number'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Account Name</th>
                                <td><?= htmlspecialchars($loan['account_name'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Account Type</th>
                                <td><?= htmlspecialchars($loan['account_type'] ?? 'Savings') ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Audit logs -->
                    <?php if (isset($audit_logs) && !empty($audit_logs)): ?>
                    <div class="mb-4">
                        <h5>Audit Log</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>User</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($audit_logs as $log): ?>
                                    <tr>
                                        <td><?= date('M d, Y H:i:s', strtotime($log['timestamp'])) ?></td>
                                        <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?></td>
                                        <td><?= htmlspecialchars($log['action_description'] ?? 'No description available') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Action buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= url('/superadmin/loans') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Loans
                        </a>
                        
                        <?php if (!($is_application ?? false) && ($loan['status'] ?? '') === 'active'): ?>
                        <a href="<?= url('/superadmin/add-loan-deduction') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Record Payment
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 