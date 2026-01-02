<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Loan Administration</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Loan Management</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/bulk-upload') ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-file-upload text-muted"></i>
                    <span class="d-none d-sm-inline">Sync Repayments</span>
                </a>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <!-- Performance Indicators -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-money-bill-wave small"></i>
                        </div>
                        <span class="text-muted fw-bold" style="font-size: 0.7rem;">Total Exposure</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Aggregate Volume</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['total_amount'] ?? 0, 2) ?></h5>
                    <div class="text-primary mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['total'] ?> Active Contracts</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden border-start border-4 border-warning">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-clock small"></i>
                        </div>
                        <span class="text-warning fw-bold" style="font-size: 0.7rem;">Action Required</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Pending Approval</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['pending_amount'] ?? 0, 2) ?></h5>
                    <div class="text-warning mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['pending'] ?? 0 ?> Applications</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden border-start border-4 border-success">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-check-circle small"></i>
                        </div>
                        <span class="text-success fw-bold" style="font-size: 0.7rem;">Disbursed</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Approved Capital</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['approved_amount'] ?? 0, 2) ?></h5>
                    <div class="text-success mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['approved'] ?? 0 ?> Active Loans</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden border-start border-4 border-danger">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="bg-danger bg-opacity-10 p-2 rounded-3 text-danger" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-times-circle small"></i>
                        </div>
                        <span class="text-danger fw-bold" style="font-size: 0.7rem;">Rejected</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Declined Volume</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['rejected_amount'] ?? 0, 2) ?></h5>
                    <div class="text-danger mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['rejected'] ?? 0 ?> Applications</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Data Table -->
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Credit Registry & Disbursements</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="fas fa-filter text-muted"></i>
                        </button>
                        <a href="<?= url('/superadmin/export-loans?' . http_build_query($_GET)) ?>" class="btn btn-light btn-sm d-flex align-items-center gap-2">
                            <i class="fas fa-file-excel text-success"></i>
                            <span>Consolidated Export</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Collapse -->
                <div class="collapse <?= !empty(array_filter($_GET)) && !isset($_GET['page']) ? 'show' : '' ?>" id="filterCollapse">
                    <div class="card-body bg-light bg-opacity-50 border-bottom p-4">
                        <form method="GET" action="<?= url('/superadmin/loans') ?>" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Financial Status</label>
                                <select name="status" class="form-select border-0 shadow-sm small">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Pending Review</option>
                                    <option value="approved" <?= (isset($_GET['status']) && $_GET['status'] === 'approved') ? 'selected' : '' ?>>Active/Approved</option>
                                    <option value="rejected" <?= (isset($_GET['status']) && ($_GET['status'] === 'rejected' || $_GET['status'] === 'declined')) ? 'selected' : '' ?>>Declined/Void</option>
                                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>Settled/Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Division</label>
                                <select name="department" class="form-select border-0 shadow-sm small">
                                    <option value="">All Functional Units</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" <?= (isset($_GET['department']) && $_GET['department'] == $dept['id']) ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Principal Search</label>
                                <input type="text" name="search" class="form-control border-0 shadow-sm small" placeholder="Name or Registry ID..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Temporal Scope</label>
                                <input type="text" name="date_range" class="form-control border-0 shadow-sm small date-range-picker" placeholder="Select timeframe..." value="<?= htmlspecialchars($_GET['date_range'] ?? '') ?>">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                <a href="<?= url('/superadmin/loans') ?>" class="btn btn-link text-muted text-decoration-none small">Reset Parameters</a>
                                <button type="submit" class="btn btn-dark btn-sm px-4">Execute Evaluation</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="loansTable">
                            <thead class="bg-light">
                                <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                    <th class="ps-4">Reference No.</th>
                                    <th>Principal Borrower</th>
                                    <th>Credit Volume</th>
                                    <th>Tenure</th>
                                    <th>Ingestion Date</th>
                                    <th>Lifecycle Status</th>
                                    <th class="pe-4 text-end">Audit</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php if (empty($loans)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-10"></i>
                                            <p class="mb-0">No credit applications detected in this segment.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($loans as $loan): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold font-monospace small"><?= htmlspecialchars($loan['display_id'] ?? $loan['loan_id'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-bold small mb-0"><?= htmlspecialchars($loan['member_name'] ?? 'Incognito') ?></div>
                                                <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($loan['coop_no'] ?? 'N/A') ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-bold small">₦<?= number_format($loan['loan_amount'] ?? 0, 2) ?></div>
                                                <div class="text-muted" style="font-size: 0.65rem;">Interest (IP): ₦<?= number_format($loan['ip_figure'] ?? 0, 2) ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark fw-normal border opacity-75 small"><?= htmlspecialchars($loan['loan_duration'] ?? '12') ?> Months</span>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-muted opacity-75">
                                                    <?= (isset($loan['application_date']) && $loan['application_date']) ? date('M d, Y', strtotime($loan['application_date'])) : 'Inception' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                $status = $loan['status'] ?? '';
                                                switch ($status) {
                                                    case 'pending': $statusClass = 'bg-warning text-warning'; break;
                                                    case 'approved': $statusClass = 'bg-success text-success'; break;
                                                    case 'rejected':
                                                    case 'declined': $statusClass = 'bg-danger text-danger'; break;
                                                    case 'completed': $statusClass = 'bg-info text-info'; break;
                                                }
                                                $statusText = ($status === 'declined' || $status === 'rejected') ? 'Rejected' : ucfirst($status ?: 'N/A');
                                                ?>
                                                <span class="badge <?= $statusClass ?> bg-opacity-10 rounded-pill px-3 py-1" style="font-size: 0.65rem;">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i> <?= strtoupper($statusText) ?>
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group btn-group-sm shadow-sm">
                                                    <?php $loanId = $loan['loan_id'] ?? ($loan['id'] ?? ''); if (!empty($loanId)): ?>
                                                        <a href="<?= url('/superadmin/view-loan/' . $loanId) ?>" class="btn btn-white border-end" title="Inspect">
                                                            <i class="fas fa-eye text-primary"></i>
                                                        </a>
                                                        <?php if (($loan['status'] ?? '') === 'pending'): ?>
                                                            <a href="<?= url('/superadmin/approve-loan-application/' . $loanId) ?>" class="btn btn-white border-end text-success" title="Approve">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                            <a href="<?= url('/superadmin/decline-loan-application/' . $loanId) ?>" class="btn btn-white border-end text-danger" title="Reject">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <button class="btn btn-white print-btn text-muted" title="Print Audit" data-loan-id="<?= $loanId ?>" data-status="<?= htmlspecialchars($loan['status'] ?? '') ?>">
                                                            <i class="fas fa-print"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Pagination -->
                <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="card-footer bg-white border-top py-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm justify-content-center mb-0 gap-1">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-3 shadow-none text-muted" href="?page=<?= $pagination['current_page'] - 1 ?><?= $pagination['query_string'] ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                                    <li class="page-item <?= $i === (int)$pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link border-0 rounded-3 shadow-sm mx-1 fw-bold" href="?page=<?= $i ?><?= $pagination['query_string'] ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <li class="page-item">
                                        <a class="page-link border-0 rounded-3 shadow-none text-muted" href="?page=<?= $pagination['current_page'] + 1 ?><?= $pagination['query_string'] ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range picker
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.date-range-picker', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            allowInput: true,
            theme: 'light'
        });
    }
    
    // Handle print button clicks
    document.querySelectorAll('.print-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const loanId = this.getAttribute('data-loan-id');
            const status = this.getAttribute('data-status');
            if (loanId) {
                window.open('<?= url('/superadmin/print-loan/') ?>' + loanId + '?status=' + encodeURIComponent(status), '_blank');
            }
        });
    });
});
</script> 