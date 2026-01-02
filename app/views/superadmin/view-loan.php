<?php
declare(strict_types=1);
/**
 * Loan details view template
 */
?>
<div class="container-fluid p-0">
    <?php $id = $loan['id'] ?? $loan['loan_id'] ?? 0; ?>
    
    <!-- Header & Navigation -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">
                <?php if ($is_application ?? false): ?>
                    Credit Application: <?= htmlspecialchars($loan['application_number'] ?? $loan['display_id'] ?? 'N/A') ?>
                <?php else: ?>
                    Loan Portfolio: <?= htmlspecialchars($loan['loan_number'] ?? 'N/A') ?>
                <?php endif; ?>
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/loans') ?>" class="text-decoration-none text-muted">Loan Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page"><?= $is_application ? 'Application Audit' : 'Active Contract' ?></li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/print-loan/' . $id) ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2" target="_blank">
                    <i class="fas fa-print text-muted"></i>
                    <span>Print Invoice</span>
                </a>
                <?php if (!($is_application ?? false) && ($loan['status'] ?? '') === 'active'): ?>
                <a href="<?= url('/superadmin/add-loan-deduction') ?>" class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Record Repayment</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <!-- Sidebar: Member & Status -->
        <div class="col-xl-4">
            <!-- Status Card -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <?php
                $status = $loan['status'] ?? 'pending';
                $statusClass = 'bg-warning';
                if ($status === 'approved' || $status === 'active') $statusClass = 'bg-success';
                elseif ($status === 'rejected' || $status === 'declined') $statusClass = 'bg-danger';
                elseif ($status === 'completed' || $status === 'closed') $statusClass = 'bg-info';
                ?>
                <div class="<?= $statusClass ?> py-3 px-4 text-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold small text-uppercase" style="letter-spacing: 1px;">Lifecycle Status</span>
                    <span class="badge bg-white bg-opacity-20 rounded-pill"><?= strtoupper($status) ?></span>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="bg-light rounded-3 p-3 mb-3 d-inline-block">
                        <i class="fas fa-file-contract fa-2x text-muted opacity-50"></i>
                    </div>
                    <?php if (isset($loan['approval_date']) && $loan['approval_date']): ?>
                        <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.65rem;">Approved On</div>
                        <div class="fw-bold mb-3"><?= date('M d, Y', strtotime($loan['approval_date'])) ?></div>
                    <?php endif; ?>
                    
                    <?php if (($is_application ?? false) && ($loan['status'] ?? '') === 'pending'): ?>
                        <div class="d-grid gap-2">
                            <a href="<?= url('/superadmin/approve-loan-application/' . $id) ?>" class="btn btn-success fw-bold">
                                <i class="fas fa-check me-2"></i> Approve Application
                            </a>
                            <a href="<?= url('/superadmin/decline-loan-application/' . $id) ?>" class="btn btn-link text-danger text-decoration-none small">
                                <i class="fas fa-times me-1"></i> Decline Application
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Member Identity -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Beneficiary Identity</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;">
                            <?= strtoupper(substr($loan['member_name'] ?? 'M', 0, 2)) ?>
                        </div>
                        <div>
                            <div class="fw-bold mb-0 text-truncate" style="max-width: 200px;"><?= htmlspecialchars($loan['member_name'] ?? 'Unknown Member') ?></div>
                            <div class="text-muted small font-monospace"><?= htmlspecialchars($loan['coop_no'] ?? $loan['member_coop_no'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                    <div class="d-grid gap-3">
                        <div class="row small g-2">
                            <div class="col-4 text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Division</div>
                            <div class="col-8 fw-semibold"><?= htmlspecialchars($loan['department_name'] ?? 'N/A') ?></div>
                        </div>
                        <div class="row small g-2 border-top pt-2">
                            <div class="col-4 text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Contact</div>
                            <div class="col-8 fw-semibold text-break"><?= htmlspecialchars($loan['email'] ?? $loan['member_email'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-top">
                        <a href="<?= url('/superadmin/view-member/' . ($loan['member_id'] ?? 0)) ?>" class="btn btn-light w-100 btn-sm text-primary fw-bold">
                            <i class="fas fa-user-circle me-2"></i> View Full Identity
                        </a>
                    </div>
                </div>
            </div>

            <!-- Banking Vector -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Settlement Channels</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.6rem;">Bank Name</div>
                            <div class="fw-bold small"><?= htmlspecialchars($loan['bank_name'] ?? 'Not Provided') ?></div>
                        </div>
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.6rem;">Account Credentials</div>
                            <div class="fw-bold small"><?= htmlspecialchars($loan['account_number'] ?? '---') ?></div>
                            <div class="text-muted" style="font-size: 0.65rem;"><?= htmlspecialchars($loan['account_name'] ?? '---') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Financial Details -->
        <div class="col-xl-8">
            <!-- Financial Blueprint -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Financial Structure</h6>
                    <span class="small text-muted font-monospace">Applied: <?= isset($loan['application_date']) ? date('M d, Y', strtotime($loan['application_date'])) : '---' ?></span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 border-end">
                            <div class="mb-4">
                                <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Principal Volume</div>
                                <h3 class="fw-bold text-primary mb-0">₦<?= number_format((float)($loan['loan_amount'] ?? 0), 2) ?></h3>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.6rem;">Yield/Interest</div>
                                    <div class="fw-bold"><?= number_format((float)($loan['interest_rate'] ?? 0), 2) ?>%</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.6rem;">Administrative</div>
                                    <div class="fw-bold">₦<?= number_format((float)($loan['ip_figure'] ?? 0), 2) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <div class="mb-4">
                                <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Aggregate Obligation</div>
                                <h3 class="fw-bold text-dark mb-0">₦<?= number_format((float)($loan['total_repayment'] ?? 0), 2) ?></h3>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.6rem;">Tenure</div>
                                    <div class="fw-bold"><?= (int)($loan['repayment_period'] ?? 0) ?> Months</div>
                                </div>
                                <?php if (!($is_application ?? false)): ?>
                                <div class="col-6">
                                    <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.6rem;">Outstanding</div>
                                    <div class="fw-bold text-danger">₦<?= number_format((float)($remaining_balance ?? $loan['balance'] ?? 0), 2) ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($loan['admin_notes']) && $loan['admin_notes']): ?>
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-primary">
                        <div class="small text-muted fw-bold text-uppercase mb-1" style="font-size: 0.6rem;">Intelligence Notes</div>
                        <p class="mb-0 small italic"><?= nl2br(htmlspecialchars($loan['admin_notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Repayment Timeline -->
            <?php if (!($is_application ?? false) && isset($repayments) && !empty($repayments)): ?>
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Amortization Logs</h6>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Agg. Recovery: ₦<?= number_format((float)($total_paid ?? 0), 2) ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                    <th class="ps-4">Period</th>
                                    <th>Volume Recovered</th>
                                    <th>Registry Ref</th>
                                    <th class="pe-4 text-end">Audit Ref</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php foreach ($repayments as $repayment): ?>
                                <tr>
                                    <td class="ps-4 small fw-bold"><?= date('M d, Y', strtotime($repayment['payment_date'])) ?></td>
                                    <td class="fw-bold text-success font-monospace">₦<?= number_format((float)$repayment['amount'], 2) ?></td>
                                    <td><span class="badge bg-light text-dark fw-normal border opacity-75"><?= htmlspecialchars($repayment['receipt_number'] ?? 'N/A') ?></span></td>
                                    <td class="pe-4 text-end">
                                        <div class="small text-muted font-monospace opacity-50"><?= htmlspecialchars($repayment['processed_by_name'] ?? 'CORE-SYS') ?></div>
                                        <div class="text-muted italic" style="font-size: 0.65rem;"><?= htmlspecialchars($repayment['notes'] ?? '') ?></div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Forensic Audit Log -->
            <?php if (isset($audit_logs) && !empty($audit_logs)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">System Forensic Trail</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <?php foreach ($audit_logs as $log): ?>
                        <div class="list-group-item py-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-primary"><?= htmlspecialchars($log['user_name'] ?? 'System Process') ?></span>
                                <span class="text-muted font-monospace" style="font-size: 0.65rem;"><?= date('M d, Y H:i', strtotime($log['timestamp'])) ?></span>
                            </div>
                            <div class="text-muted"><?= htmlspecialchars($log['action_description'] ?? 'Event triggers detected') ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="<?= url('/superadmin/loans') ?>" class="btn btn-outline-secondary btn-sm px-4">
                    <i class="fas fa-arrow-left me-2"></i> Portfolio Directory
                </a>
            </div>
        </div>
    </div>
</div> 