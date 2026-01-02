<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Actions -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Savings Portfolio</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Savings Management</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/savings/add') ?>" class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span class="d-none d-sm-inline">New Deduction</span>
                </a>
                <a href="<?= url('/superadmin/bulk-upload') ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-file-upload text-muted"></i>
                    <span class="d-none d-sm-inline">Bulk Ingest</span>
                </a>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <!-- Performance Indicators -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-piggy-bank text-primary"></i>
                        </div>
                        <span class="text-success small fw-bold"><i class="fas fa-arrow-up me-1"></i>4.2%</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Assets Under Management</div>
                    <h3 class="fw-bold mb-0">₦<?= number_format($stats['total_savings'], 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-hand-holding-usd text-success"></i>
                        </div>
                        <span class="text-muted small fw-bold opacity-50">Monthly</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Scheduled Monthly Volume</div>
                    <h3 class="fw-bold mb-0">₦<?= number_format($stats['monthly_deductions'], 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-users text-info"></i>
                        </div>
                        <span class="text-muted small fw-bold opacity-50">Active Users</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Contributing Member Base</div>
                    <h3 class="fw-bold mb-0"><?= number_format($stats['total_members']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-2 rounded-3">
                            <i class="fas fa-chart-pie text-warning"></i>
                        </div>
                        <span class="text-muted small fw-bold opacity-50">Efficiency</span>
                    </div>
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Average Capitalization</div>
                    <h3 class="fw-bold mb-0">₦<?= number_format($stats['average_savings'], 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Data Table -->
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Capital Distribution Ledger</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="fas fa-filter text-muted"></i>
                        </button>
                        <a href="<?= url('/superadmin/savings/export?' . http_build_query($_GET)) ?>" class="btn btn-light btn-sm d-flex align-items-center gap-2">
                            <i class="fas fa-file-excel text-success"></i>
                            <span>Intelligence Export</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Collapse -->
                <div class="collapse <?= !empty(array_filter($_GET)) && !isset($_GET['page']) ? 'show' : '' ?>" id="filterCollapse">
                    <div class="card-body bg-light bg-opacity-50 border-bottom p-4">
                        <form method="GET" action="<?= url('/superadmin/savings') ?>" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Division/Department</label>
                                <select name="department" class="form-select border-0 shadow-sm small">
                                    <option value="">All Functional Units</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" <?= (isset($_GET['department']) && $_GET['department'] == $dept['id']) ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Entity Search</label>
                                <input type="text" name="search" class="form-control border-0 shadow-sm small" placeholder="Name or Registry ID..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Financial Threshold (Min)</label>
                                <input type="number" name="min_amount" class="form-control border-0 shadow-sm small" placeholder="Minimum ₦" value="<?= htmlspecialchars($_GET['min_amount'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Financial Threshold (Max)</label>
                                <input type="number" name="max_amount" class="form-control border-0 shadow-sm small" placeholder="Maximum ₦" value="<?= htmlspecialchars($_GET['max_amount'] ?? '') ?>">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                <a href="<?= url('/superadmin/savings') ?>" class="btn btn-link text-muted text-decoration-none small">Clear Logic</a>
                                <button type="submit" class="btn btn-dark btn-sm px-4">Execute Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="savingsTable">
                            <thead class="bg-light">
                                <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                    <th class="ps-4">Entity Identity</th>
                                    <th>Division</th>
                                    <th>Deduction Profile</th>
                                    <th>Total Liquidity</th>
                                    <th>Last Activity</th>
                                    <th class="pe-4 text-end">Audit</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php if (empty($savingsData)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-search fa-3x mb-3 opacity-10"></i>
                                            <p class="mb-0">No savings data matching current audit parameters.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($savingsData as $saving): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary shadow-sm" style="width: 38px; height: 38px; font-size: 0.8rem;">
                                                        <?= strtoupper(substr($saving['name'] ?? 'M', 0, 2)) ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold small mb-0"><?= htmlspecialchars($saving['name'] ?? 'Unknown Member') ?></div>
                                                        <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($saving['coop_no'] ?? 'N/A') ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark fw-normal border border-opacity-10 small"><?= htmlspecialchars($saving['department'] ?? 'Corporate') ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-bold small text-primary">₦<?= number_format($saving['monthly_deduction'] ?? 0, 2) ?></div>
                                                <div class="text-muted" style="font-size: 0.65rem;">Monthly Recurring</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold small">₦<?= number_format($saving['cumulative_amount'] ?? 0, 2) ?></div>
                                                <div class="progress mt-1" style="height: 3px; max-width: 100px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-muted opacity-75">
                                                    <?= (isset($saving['last_deduction_date']) && $saving['last_deduction_date']) ? date('M d, Y', strtotime($saving['last_deduction_date'])) : 'Inception' ?>
                                                </div>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-white btn-sm border-0 shadow-none text-muted" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 small">
                                                        <li><a class="dropdown-item py-2" href="<?= url('/superadmin/savings/view/' . $saving['member_id']) ?>"><i class="fas fa-eye me-2 opacity-50"></i> View Portfolio</a></li>
                                                        <li><a class="dropdown-item py-2" href="<?= url('/superadmin/savings/edit/' . $saving['member_id']) ?>"><i class="fas fa-edit me-2 opacity-50"></i> Modify Structure</a></li>
                                                        <li><hr class="dropdown-divider opacity-10"></li>
                                                        <li><a class="dropdown-item py-2 text-primary" href="#" onclick="viewDeductionHistory(<?= $saving['member_id'] ?>)"><i class="fas fa-history me-2 opacity-50"></i> Ledger History</a></li>
                                                    </ul>
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

<!-- Ledger History Modal -->
<div class="modal fade" id="deductionHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold">Liquidity Sync History</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="deductionHistoryContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary opacity-25" role="status"></div>
                        <p class="mt-3 small text-muted">Tracing financial footprints across the ledger...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close Audit</button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to view deduction history
function viewDeductionHistory(memberId) {
    var modalElement = document.getElementById('deductionHistoryModal');
    var modal = new bootstrap.Modal(modalElement);
    var container = document.getElementById('deductionHistoryContent');
    
    container.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary opacity-25" role="status"></div><p class="mt-3 small text-muted">Tracing financial footprints...</p></div>`;
    modal.show();
    
    fetch('<?= url('/superadmin/savings/history/') ?>' + memberId)
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            if (data.member) {
                html += `
                    <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                            <i class="fas fa-fingerprint fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">${data.member.name}</h6>
                            <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">${data.member.coop_no} • Audit Scope</div>
                        </div>
                    </div>
                `;
            }
            
            if (data.deductions && data.deductions.length > 0) {
                html += `
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle small">
                        <thead class="text-muted fw-bold border-bottom-0" style="font-size: 0.65rem;">
                            <tr>
                                <th class="py-2 text-uppercase">Financial Period</th>
                                <th class="py-2 text-uppercase">Ingestion Vector</th>
                                <th class="py-2 text-uppercase text-end">Volume (₦)</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                `;
                
                data.deductions.forEach(deduction => {
                    html += `
                        <tr>
                            <td class="py-3">
                                <div class="fw-bold">${new Date(deduction.deduction_date).toLocaleDateString('en-GB', { month: 'short', year: 'numeric' })}</div>
                                <div class="text-muted opacity-50" style="font-size: 0.6rem;">${new Date(deduction.deduction_date).toLocaleDateString()}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-normal border opacity-75">${deduction.description || 'Monthly Ingestion'}</span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                ₦${parseFloat(deduction.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}
                            </td>
                        </tr>
                    `;
                });
                
                html += `</tbody></table></div>`;
            } else {
                html += `
                    <div class="text-center py-5">
                        <i class="fas fa-ghost fa-3x text-muted opacity-10 mb-3"></i>
                        <p class="text-muted small">No financial footprints detected in this registry segment.</p>
                    </div>
                `;
            }
            
            container.innerHTML = html;
        })
        .catch(error => {
            container.innerHTML = `
                <div class="alert alert-danger border-0 small">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Audit Interrupted: ${error.message}
                </div>
            `;
        });
}
</script> 