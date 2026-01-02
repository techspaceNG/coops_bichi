<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Household Procurement</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Household Management</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/bulk-upload') ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-file-invoice text-muted"></i>
                    <span class="d-none d-sm-inline">Sync Ledger</span>
                </a>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <!-- Vital Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-3 text-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto; flex-shrink: 0;">
                        <i class="fas fa-shopping-basket small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Aggregate Inventory</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['total_amount'], 2) ?></h5>
                    <div class="text-primary mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['total'] ?> Entities</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden border-top border-4 border-warning">
                <div class="card-body p-3 text-center">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto; flex-shrink: 0;">
                        <i class="fas fa-hourglass-half small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">In Queue</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['pending_amount'], 2) ?></h5>
                    <div class="text-warning mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['pending'] ?> Awaiting Review</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden border-top border-4 border-success">
                <div class="card-body p-3 text-center">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto; flex-shrink: 0;">
                        <i class="fas fa-truck-loading small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Approved Assets</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['approved_amount'], 2) ?></h5>
                    <div class="text-success mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['approved'] ?> Finalized</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden border-top border-4 border-danger">
                <div class="card-body p-3 text-center">
                    <div class="bg-danger bg-opacity-10 p-2 rounded-3 text-danger d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto; flex-shrink: 0;">
                        <i class="fas fa-ban small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Declined Volume</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['declined_amount'], 2) ?></h5>
                    <div class="text-danger mt-1 fw-semibold" style="font-size: 0.7rem;"><?= $stats['declined'] ?> Blocked</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Data Table -->
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Asset Procurement Registry</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="fas fa-filter text-muted"></i>
                        </button>
                        <a href="<?= url('/superadmin/export-household?' . http_build_query($_GET)) ?>" class="btn btn-light btn-sm d-flex align-items-center gap-2">
                            <i class="fas fa-file-excel text-success"></i>
                            <span>Intelligence Export</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Collapse -->
                <div class="collapse <?= !empty(array_filter($_GET)) && !isset($_GET['page']) ? 'show' : '' ?>" id="filterCollapse">
                    <div class="card-body bg-light bg-opacity-50 border-bottom p-4">
                        <form method="GET" action="<?= url('/superadmin/household') ?>" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Financial Milestone</label>
                                <select name="status" class="form-select border-0 shadow-sm small">
                                    <option value="">All Milestones</option>
                                    <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : '' ?>>Pending Verification</option>
                                    <option value="approved" <?= (isset($_GET['status']) && $_GET['status'] === 'approved') ? 'selected' : '' ?>>Finalized/Approved</option>
                                    <option value="rejected" <?= (isset($_GET['status']) && ($_GET['status'] === 'rejected' || $_GET['status'] === 'declined')) ? 'selected' : '' ?>>Failed/Declined</option>
                                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>Closed/Settled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Organizational Unit</label>
                                <select name="department" class="form-select border-0 shadow-sm small">
                                    <option value="">All Units</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" <?= (isset($_GET['department']) && $_GET['department'] == $dept['id']) ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Entity Search</label>
                                <input type="text" name="search" class="form-control border-0 shadow-sm small" placeholder="Owner or ID..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Temporal Range</label>
                                <input type="text" name="date_range" class="form-control border-0 shadow-sm small date-range-picker" placeholder="Select dates..." value="<?= htmlspecialchars($_GET['date_range'] ?? '') ?>">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                <a href="<?= url('/superadmin/household') ?>" class="btn btn-link text-muted text-decoration-none small">Clear Logic</a>
                                <button type="submit" class="btn btn-dark btn-sm px-4">Execute Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="householdTable">
                            <thead class="bg-light">
                                <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                    <th class="ps-4">Contract Identity</th>
                                    <th>Procured Asset</th>
                                    <th>Financial Valuation</th>
                                    <th>Tenure</th>
                                    <th>Temporal Stamp</th>
                                    <th>Lifecycle</th>
                                    <th class="pe-4 text-end">Audit</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php if (empty($household)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-boxes fa-3x mb-3 opacity-10"></i>
                                            <p class="mb-0">No procurement entities identified in this scope.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($household as $purchase): ?>
                                        <?php 
                                        $isApplication = isset($purchase['source_table']) && $purchase['source_table'] === 'application';
                                        $viewUrl = $isApplication ? url('/superadmin/view-household-application/' . $purchase['id']) : url('/superadmin/view-household/' . $purchase['id']);
                                        $totalRepayment = isset($purchase['total_repayment']) ? $purchase['total_repayment'] : ($purchase['amount'] * 1.05);
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold small mb-0"><?= htmlspecialchars($purchase['member_name']) ?></div>
                                                <div class="text-muted font-monospace" style="font-size: 0.65rem;">ID: <?= htmlspecialchars($purchase['id']) ?> • <?= htmlspecialchars($purchase['member_coop_no'] ?? 'N/A') ?></div>
                                            </td>
                                            <td>
                                                <div class="small fw-bold text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($purchase['description']) ?>">
                                                    <?= htmlspecialchars($purchase['description']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold small">₦<?= number_format($purchase['amount'], 2) ?></div>
                                                <div class="text-muted" style="font-size: 0.65rem;">Final: ₦<?= number_format($totalRepayment, 2) ?></div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark fw-normal border opacity-75 small"><?= htmlspecialchars($purchase['purchase_duration'] ?? $purchase['repayment_period'] ?? '12') ?> Mo.</span>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-muted opacity-75">
                                                    <?= date('M d, Y', strtotime($purchase['created_at'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                $status = $purchase['status'];
                                                if ($status === 'declined' || $status === 'rejected') $status = 'rejected';
                                                switch ($status) {
                                                    case 'pending': $statusClass = 'bg-warning text-warning'; break;
                                                    case 'approved': $statusClass = 'bg-success text-success'; break;
                                                    case 'rejected': $statusClass = 'bg-danger text-danger'; break;
                                                    case 'completed': $statusClass = 'bg-info text-info'; break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?> bg-opacity-10 rounded-pill px-3 py-1" style="font-size: 0.65rem;">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i> <?= strtoupper($status) ?>
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group btn-group-sm shadow-sm">
                                                    <a href="<?= $viewUrl ?>" class="btn btn-white border-end" title="Inspect">
                                                        <i class="fas fa-eye text-primary"></i>
                                                    </a>
                                                    <?php if ($purchase['status'] === 'pending'): ?>
                                                        <a href="<?= $viewUrl ?>#approve" class="btn btn-white border-end text-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="<?= $viewUrl ?>#decline" class="btn btn-white border-end text-danger" title="Refuse">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <button class="btn btn-white text-muted" title="Print Audit" onclick="printHouseholdDetails(<?= $purchase['id'] ?>, '<?= $isApplication ? 'application' : 'purchase' ?>')">
                                                        <i class="fas fa-print"></i>
                                                    </button>
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
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.date-range-picker', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }
});

function printHouseholdDetails(id, type) {
    window.open(<?= json_encode(url('/superadmin/print-household/')) ?> + id + '?type=' + type, '_blank', 'width=800,height=600');
}
</script> 