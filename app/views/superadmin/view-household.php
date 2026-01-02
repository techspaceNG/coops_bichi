<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Navigation -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Procurement Audit: #<?= htmlspecialchars($purchase['id']) ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/household') ?>" class="text-decoration-none text-muted">Household Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Verification</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/household') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Registry Home</span>
                </a>
                <a href="javascript:void(0);" onclick="printHouseholdDetails(<?= $purchase['id'] ?>);" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-print text-muted"></i>
                    <span>Print Receipt</span>
                </a>
                <?php if ($purchase['status'] === 'pending'): ?>
                    <button type="button" class="btn btn-success shadow-sm d-flex align-items-center gap-2 px-3" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="fas fa-check"></i>
                        <span>Finalize Approval</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <!-- Asset Owner Profile -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Beneficiary Entity</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4 text-center text-sm-start flex-column flex-sm-row">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 65px; height: 65px; font-size: 1.2rem;">
                            <?= strtoupper(substr($purchase['member_name'], 0, 2)) ?>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($purchase['member_name']) ?></h5>
                            <div class="text-muted small font-monospace">REG. <?= htmlspecialchars($purchase['member_coop_no'] ?? 'N/A') ?></div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.6rem;">Unit/Division</div>
                            <div class="fw-bold small"><?= !empty($purchase['department_name']) ? htmlspecialchars($purchase['department_name']) : 'General' ?></div>
                        </div>
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.6rem;">Electronic Correspondence</div>
                            <div class="fw-bold small text-break"><?= !empty($purchase['member_email']) ? htmlspecialchars($purchase['member_email']) : 'Not Provided' ?></div>
                        </div>
                        <div class="p-3 bg-light rounded-3">
                            <div class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.6rem;">Communication Terminal</div>
                            <div class="fw-bold small"><?= !empty($purchase['member_phone']) ? htmlspecialchars($purchase['member_phone']) : 'Not Provided' ?></div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <a href="<?= url('/superadmin/view-member/' . ($purchase['member_id'] ?? 0)) ?>" class="btn btn-outline-primary w-100 btn-sm fw-bold">
                            <i class="fas fa-fingerprint me-2"></i> Inspect Unified Identity
                        </a>
                    </div>
                </div>
            </div>

            <!-- Financial Settlement Channel -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Banking Resolution</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Institution</span>
                            <span class="small fw-bold text-dark"><?= htmlspecialchars($purchase['bank_name'] ?? 'Undefined') ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Account Ref</span>
                            <span class="small fw-bold font-monospace"><?= htmlspecialchars($purchase['account_number'] ?? '---') ?></span>
                        </div>
                        <div class="bg-light p-2 rounded text-center mt-2">
                            <span class="small text-muted italic" style="font-size: 0.7rem;"><?= htmlspecialchars($purchase['account_name'] ?? 'Beneficiary Name Missing') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Procurement Dynamics -->
        <div class="col-xl-8">
            <!-- Core Assets -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Asset Valuation</h6>
                    <?php
                    $status = $purchase['status'];
                    $statusClass = 'bg-secondary';
                    if ($status === 'pending') $statusClass = 'bg-warning text-warning';
                    elseif ($status === 'approved') $statusClass = 'bg-success text-success';
                    elseif ($status === 'declined' || $status === 'rejected') $statusClass = 'bg-danger text-danger';
                    ?>
                    <span class="badge <?= $statusClass ?> bg-opacity-10 rounded-pill px-3 py-1 fw-bold" style="font-size: 0.65rem;">
                        <?= strtoupper($status) ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4 border-end">
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Direct Invoiced Amount</div>
                            <h3 class="fw-bold text-primary mb-0">₦<?= number_format($purchase['amount'], 2) ?></h3>
                        </div>
                        <div class="col-md-4 border-end ps-md-4">
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Agg. Obligation (5% IP)</div>
                            <h3 class="fw-bold text-danger mb-0">₦<?= number_format($purchase['total_repayment'] ?? ($purchase['amount'] * 1.05), 2) ?></h3>
                        </div>
                        <div class="col-md-4 ps-md-4">
                            <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Recovery Progress</div>
                            <h3 class="fw-bold text-success mb-0">₦<?= number_format($totalPaid ?? 0, 2) ?></h3>
                        </div>
                    </div>

                    <div class="row g-4 pt-4 border-top">
                        <div class="col-md-6">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 0.5px;">Kinetic Parameters</h6>
                            <div class="d-grid gap-2">
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Repayment Cycle:</span>
                                    <span class="small fw-bold"><?= htmlspecialchars($purchase['purchase_duration'] ?? $purchase['repayment_period'] ?? '12') ?> Months</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Logged Ingress:</span>
                                    <span class="small fw-bold"><?= date('M d, Y', strtotime($purchase['created_at'])) ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Outstanding Risk:</span>
                                    <span class="small fw-bold text-danger">₦<?= number_format($remainingBalance, 2) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3" style="letter-spacing: 0.5px;">Asset Intelligence</h6>
                            <div class="bg-light p-3 rounded-3 h-100 overflow-auto" style="max-height: 100px;">
                                <p class="small mb-0 italic"><?= !empty($purchase['description']) ? nl2br(htmlspecialchars($purchase['description'])) : 'Description not recorded in system metadata.' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes & Auditor Commentary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Advisory & Administrative Trail</h6>
                </div>
                <div class="card-body p-4">
                    <div class="bg-white border p-3 rounded-3 border-start border-4 border-info">
                        <p class="small mb-0"><?= !empty($purchase['comment']) ? nl2br(htmlspecialchars($purchase['comment'])) : 'No historical commentary or notes attached to this procurement entity.' ?></p>
                    </div>
                </div>
            </div>

            <!-- Lifecycle Actions if Pending -->
            <?php if ($purchase['status'] === 'pending'): ?>
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 p-4">
                <i class="fas fa-exclamation-shield fa-2x opacity-50"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">Awaiting Risk Finalization</h6>
                    <p class="small text-muted mb-0">This procurement entity is currently in a 'Pending' state. Strategic approval or rejection is required to advance the lifecycle.</p>
                </div>
                <button type="button" class="btn btn-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#declineModal">Deny Logic</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function printHouseholdDetails(id) {
    window.open(<?= json_encode(url('/superadmin/print-household/')) ?> + id, '_blank');
}
</script>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= url('/superadmin/approve-household/' . $purchase['id']) ?>" method="post">
                <div class="modal-header bg-success text-white border-0">
                    <h6 class="modal-title fw-bold">Logic Verification: Approval</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-4">Verify approval of procurement entity for <span class="fw-bold text-dark">₦<?= number_format($purchase['amount'], 2) ?></span> belonging to <span class="fw-bold text-dark"><?= htmlspecialchars($purchase['member_name']) ?></span>.</p>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Final Auditor Remarks</label>
                        <textarea class="form-control border-0 bg-light shadow-none small" name="comment" rows="3" placeholder="Attach strategic notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal">Abort</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold shadow-sm">Authorize Procurement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= url('/superadmin/decline-household/' . $purchase['id']) ?>" method="post">
                <div class="modal-header bg-danger text-white border-0">
                    <h6 class="modal-title fw-bold">Logic Verification: Denial</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Denial Reason (Required)</label>
                        <textarea class="form-control border-0 bg-light shadow-none small" name="decline_reason" rows="3" required placeholder="Specify why this asset procurement failed verification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal">Abort</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold shadow-sm">Confirm Denial</button>
                </div>
            </form>
        </div>
    </div>
</div> 