<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Share Capital Ledger</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Shares Management</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/add-share-deduction') ?>" class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span class="d-none d-sm-inline">New Deduction</span>
                </a>
                <a href="<?= url('/superadmin/process-share-balances') ?>" class="btn btn-warning text-white shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-sync"></i>
                    <span class="d-none d-sm-inline">Sync Balances</span>
                </a>
                <a href="<?= url('/superadmin/shares/export?' . http_build_query($_GET)) ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-file-excel text-success"></i>
                    <span class="d-none d-sm-inline">Export Analytics</span>
                </a>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <!-- Portfolio Overview -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-chart-line small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Market Capitalization</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format($stats['total_value'] ?? 0, 2) ?></h5>
                </div>
                <div class="position-absolute opacity-10" style="right: -5px; bottom: -10px;">
                    <i class="fas fa-chart-line fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-layer-group small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Issued Equity Units</div>
                    <h5 class="fw-bold mb-0"><?= number_format($stats['total_shares'] ?? 0) ?></h5>
                </div>
                <div class="position-absolute opacity-10" style="right: -5px; bottom: -10px;">
                    <i class="fas fa-layer-group fa-4x text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="card-body p-3">
                    <div class="bg-info bg-opacity-10 p-2 rounded-3 text-info d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-users-cog small"></i>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Shareholding Base</div>
                    <h5 class="fw-bold mb-0"><?= number_format($stats['total_members'] ?? 0) ?></h5>
                </div>
                <div class="position-absolute opacity-10" style="right: -5px; bottom: -10px;">
                    <i class="fas fa-users-cog fa-4x text-info"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm bg-dark h-100 position-relative overflow-hidden">
                <div class="card-body p-3 text-white">
                    <div class="bg-white bg-opacity-10 p-2 rounded-3 text-white d-inline-block mb-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-file-invoice-dollar small"></i>
                    </div>
                    <div class="text-white-50 small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">Avg Equity/Member</div>
                    <h5 class="fw-bold mb-0">₦<?= number_format(($stats['total_value'] ?? 0) / max(1, $stats['total_members'] ?? 1), 2) ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <!-- Intelligence Filter -->
        <div class="card-header bg-white border-bottom py-3">
            <div class="row align-items-center g-3">
                <div class="col">
                    <h6 class="fw-bold mb-0">Equity Registry</h6>
                </div>
                <div class="col-auto">
                    <button class="btn btn-light btn-sm btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="fas fa-filter text-muted"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="collapse <?= !empty(array_filter($_GET)) && !isset($_GET['page']) ? 'show' : '' ?>" id="filterCollapse">
            <div class="card-body bg-light bg-opacity-50 border-bottom p-4">
                <form method="GET" action="<?= url('/superadmin/shares') ?>" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Member Entity</label>
                        <select name="member_id" class="form-select border-0 shadow-sm small select2">
                            <option value="">All Shareholders</option>
                            <?php foreach ($members as $member): ?>
                            <option value="<?= $member['id'] ?>" <?= (isset($_GET['member_id']) && $_GET['member_id'] == $member['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($member['name'] . ' (' . $member['coop_no'] . ')') ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Temporal Range</label>
                        <input type="text" name="date_range" id="date_range" class="form-control border-0 shadow-sm small" placeholder="Ingestion dates..." value="<?= htmlspecialchars($_GET['date_range'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Intelligence Search</label>
                        <input type="text" name="search" class="form-control border-0 shadow-sm small" placeholder="Search by name, COOPS ID or units..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-dark btn-sm w-100">Apply Logic</button>
                        <a href="<?= url('/superadmin/shares') ?>" class="btn btn-white border btn-sm"><i class="fas fa-times"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                            <th class="ps-4">Reference</th>
                            <th>Shareholder Entity</th>
                            <th>Equity Base (Units)</th>
                            <th>Valuation</th>
                            <th>Temporal Context</th>
                            <th class="pe-4 text-end">Audit</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (empty($shares)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-layer-group fa-3x mb-3 opacity-10"></i>
                                    <p class="mb-0">No equity records detected across this segment.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($shares as $share): ?>
                                <?php 
                                $isPending = is_null($share['id']);
                                $needsSync = isset($share['needs_syncing']) && $share['needs_syncing'];
                                ?>
                                <tr class="<?= $needsSync ? 'table-info' : '' ?>">
                                    <td class="ps-4">
                                        <?php if($isPending): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1" style="font-size: 0.6rem;">PENDING INGESTION</span>
                                        <?php else: ?>
                                            <span class="font-monospace small text-muted">ID:<?= $share['id'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold small mb-0"><?= htmlspecialchars($share['member_name'] ?? 'Undefined') ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($share['member_coop_no'] ?? 'N/A') ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold small"><?= number_format($share['units'] ?? 0) ?> Units</div>
                                        <div class="text-muted" style="font-size: 0.65rem;">@ ₦<?= number_format($share['unit_value'] ?? 0, 2) ?>/unit</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold small text-primary">₦<?= number_format($share['total_value'] ?? 0, 2) ?></div>
                                        <?php if($needsSync): ?>
                                            <div class="text-danger fw-bold" style="font-size: 0.6rem;"><i class="fas fa-exclamation-triangle me-1"></i>Diff: ₦<?= number_format($share['balance_difference'] ?? 0, 2) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-muted opacity-75">
                                            <?= $share['created_at'] ? date('M d, Y', strtotime($share['created_at'])) : '<span class="badge bg-light text-dark fw-normal border">Inception Load</span>' ?>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group btn-group-sm shadow-sm">
                                            <a href="<?= url('/superadmin/view-member/' . ($share['member_id'] ?? 0)) ?>" class="btn btn-white border-end" title="Profile View">
                                                <i class="fas fa-user-circle text-primary"></i>
                                            </a>
                                            <?php if(!$isPending): ?>
                                            <a href="<?= url('/superadmin/share-transactions/' . $share['id']) ?>" class="btn btn-white text-muted" title="Transaction Ledger">
                                                <i class="fas fa-list-ul"></i>
                                            </a>
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
        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="card-footer bg-white border-top py-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-center mb-0 gap-1">
                        <li class="page-item <?= ($pagination['current_page'] <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link border-0 rounded-3 shadow-none text-muted" href="<?= url('/superadmin/shares?page=' . ($pagination['current_page'] - 1) . $pagination['query_string']) ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        
                        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                            <li class="page-item <?= ($pagination['current_page'] == $i) ? 'active' : '' ?>">
                                <a class="page-link border-0 rounded-3 shadow-sm mx-1 fw-bold" href="<?= url('/superadmin/shares?page=' . $i . $pagination['query_string']) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= ($pagination['current_page'] >= $pagination['total_pages']) ? 'disabled' : '' ?>">
                            <a class="page-link border-0 rounded-3 shadow-none text-muted" href="<?= url('/superadmin/shares?page=' . ($pagination['current_page'] + 1) . $pagination['query_string']) ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof flatpickr !== 'undefined') {
            flatpickr('#date_range', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y',
                locale: { rangeSeparator: ' to ' }
            });
        }
    });
</script> 