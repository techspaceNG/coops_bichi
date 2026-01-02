<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Member Profile</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/members') ?>" class="text-decoration-none text-muted">Members Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">View Profile</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="<?= url('/superadmin/members') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Directory</span>
            </a>
            <a href="<?= url('/superadmin/edit-member/' . ($member['id'] ?? '')) ?>" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-edit"></i>
                <span>Edit Metadata</span>
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <?php if (isset($member) && !empty($member)): ?>
    <div class="row g-4">
        <!-- Profile Sidebar -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="bg-primary py-5 position-relative">
                    <div class="position-absolute w-100 h-100 top-0 start-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                </div>
                <div class="card-body p-3 text-center mt-n5 position-relative">
                    <div class="position-relative d-inline-block mb-3">
                        <div class="rounded-circle bg-white shadow-sm p-1">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 120px; height: 120px;">
                                <span class="h1 mb-0 fw-bold text-primary opacity-50"><?= strtoupper(substr($member['name'], 0, 1)) ?></span>
                            </div>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-2 rounded-circle border border-4 border-white shadow-sm <?= $member['is_active'] ? 'bg-success' : 'bg-danger' ?>" style="width: 28px; height: 28px;" title="<?= $member['is_active'] ? 'Active Member' : 'Inactive Member' ?>"></span>
                    </div>
                    
                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($member['name']) ?></h4>
                    <p class="text-muted small mb-4">COOPS Registry: <span class="text-primary fw-bold">#<?= htmlspecialchars($member['coop_no']) ?></span></p>
                    
                    <div class="d-grid gap-2 border-top pt-4">
                        <div class="d-flex align-items-center gap-3 text-start mb-3">
                            <div class="bg-light rounded-3 p-2 text-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.65rem;">Unit / Department</div>
                                <div class="small fw-bold"><?= !empty($member['department_name']) ? htmlspecialchars($member['department_name']) : 'Unassigned' ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 text-start mb-3">
                            <div class="bg-light rounded-3 p-2 text-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.65rem;">Email Communication</div>
                                <div class="small fw-bold text-break"><?= htmlspecialchars($member['email']) ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 text-start">
                            <div class="bg-light rounded-3 p-2 text-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <div class="small text-muted text-uppercase fw-semibold" style="font-size: 0.65rem;">Contact Number</div>
                                <div class="small fw-bold"><?= !empty($member['phone']) ? htmlspecialchars($member['phone']) : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Coordinates -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Banking & Settlement</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-3">
                        <div class="p-2 bg-light rounded-3">
                            <div class="small text-muted mb-0" style="font-size: 0.65rem;">Financial Institution</div>
                            <div class="fw-bold small"><?= !empty($member['bank_name']) ? htmlspecialchars($member['bank_name']) : '---' ?></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <div class="small text-muted mb-0" style="font-size: 0.65rem;">Account No.</div>
                                    <div class="fw-bold small"><?= !empty($member['account_number']) ? htmlspecialchars($member['account_number']) : '---' ?></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <div class="small text-muted mb-0" style="font-size: 0.65rem;">BVN Ref.</div>
                                    <div class="fw-bold small"><?= !empty($member['bvn']) ? htmlspecialchars($member['bvn']) : '---' ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 bg-light rounded-3">
                            <div class="small text-muted mb-0" style="font-size: 0.65rem;">Beneficiary Name</div>
                            <div class="fw-bold small"><?= !empty($member['account_name']) ? htmlspecialchars($member['account_name']) : '---' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-xl-8">
            <!-- Balances Dashboard -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-primary bg-opacity-10 h-100 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="text-primary small fw-bold mb-2">SAVINGS</div>
                            <h5 class="fw-bold mb-0">₦<?= number_format($member['savings_balance'] ?? 0, 2) ?></h5>
                            <i class="fas fa-piggy-bank position-absolute bottom-0 end-0 p-3 opacity-10 fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-danger bg-opacity-10 h-100 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="text-danger small fw-bold mb-2">LOANS</div>
                            <h5 class="fw-bold mb-0">₦<?= number_format($member['loan_balance'] ?? 0, 2) ?></h5>
                            <i class="fas fa-hand-holding-usd position-absolute bottom-0 end-0 p-3 opacity-10 fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success bg-opacity-10 h-100 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="text-success small fw-bold mb-2">HOUSEHOLD</div>
                            <h5 class="fw-bold mb-0">₦<?= number_format($member['household_balance'] ?? 0, 2) ?></h5>
                            <i class="fas fa-shopping-basket position-absolute bottom-0 end-0 p-3 opacity-10 fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-info bg-opacity-10 h-100 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="text-info small fw-bold mb-2">SHARES</div>
                            <h5 class="fw-bold mb-0">₦<?= number_format($member['shares_balance'] ?? 0, 2) ?></h5>
                            <i class="fas fa-chart-line position-absolute bottom-0 end-0 p-3 opacity-10 fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed History Tabs -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs border-bottom-0" id="memberTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings" type="button" role="tab">
                                <i class="fas fa-wallet me-2"></i> Savings Ledger
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="loans-tab" data-bs-toggle="tab" data-bs-target="#loans" type="button" role="tab">
                                <i class="fas fa-exchange-alt me-2"></i> Loan Registry
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab">
                                <i class="fas fa-cart-arrow-down me-2"></i> Household Credits
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="memberTabContent">
                        <!-- Savings Ledger -->
                        <div class="tab-pane fade show active" id="savings" role="tabpanel">
                            <div class="p-4">
                                <?php if (isset($savings) && !empty($savings)): ?>
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6 text-center text-md-start">
                                            <div class="small text-muted mb-1 text-uppercase fw-bold">Active Monthly Contribution</div>
                                            <h3 class="fw-bold text-primary">₦<?= number_format($savings['monthly_deduction'] ?? 0, 2) ?></h3>
                                        </div>
                                        <div class="col-md-6 text-center text-md-end">
                                            <div class="small text-muted mb-1 text-uppercase fw-bold">Last Deduction Snapshot</div>
                                            <div class="fw-bold">
                                                <?= isset($savings['last_deduction_date']) && $savings['last_deduction_date'] ? 
                                                    date('M j, Y', strtotime($savings['last_deduction_date'])) : 
                                                    '<span class="badge bg-light text-muted">No History Recorded</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-grid shadow-sm">
                                        <a href="<?= url('/superadmin/savings-history/' . $member['id']) ?>" class="btn btn-light py-3 fw-bold">
                                            <i class="fas fa-history me-2 text-primary"></i> Access Full Deduction Archives
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="py-5 text-center">
                                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                            <i class="fas fa-piggy-bank fa-2x opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold">No Active Savings Plan</h6>
                                        <p class="text-muted small mb-0">This member currently has no savings records on file.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Loan Registry -->
                        <div class="tab-pane fade" id="loans" role="tabpanel">
                            <div class="table-responsive">
                                <?php if (isset($loans) && !empty($loans)): ?>
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="small text-uppercase fw-bold text-muted">
                                                <th class="ps-4">Folio ID</th>
                                                <th>Financed Amount</th>
                                                <th>Status</th>
                                                <th>Approval Date</th>
                                                <th class="pe-4 text-end">Record</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            <?php foreach ($loans as $loan): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold">LN-<?= $loan['id'] ?></td>
                                                <td class="fw-bold text-primary">₦<?= number_format($loan['loan_amount'] ?? 0, 2) ?></td>
                                                <td>
                                                    <span class="badge rounded-pill px-3 py-2 fw-normal 
                                                        <?php 
                                                        switch($loan['status']) {
                                                            case 'pending': echo 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; break;
                                                            case 'approved': echo 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; break;
                                                            case 'rejected': echo 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; break;
                                                            case 'completed': echo 'bg-info bg-opacity-10 text-info border border-info border-opacity-25'; break;
                                                            default: echo 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
                                                        }
                                                        ?>">
                                                        <?= ucfirst($loan['status'] ?? 'Draft') ?>
                                                    </span>
                                                </td>
                                                <td><span class="small"><?= isset($loan['created_at']) && $loan['created_at'] ? date('M d, Y', strtotime($loan['created_at'])) : '---' ?></span></td>
                                                <td class="pe-4 text-end">
                                                    <a href="<?= url('/superadmin/view-loan/' . $loan['id']) ?>" class="btn btn-white btn-sm border shadow-sm px-3">
                                                        <i class="fas fa-chevron-right small text-muted"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="p-5 text-center">
                                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                            <i class="fas fa-file-invoice-dollar fa-2x opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold">No Loan Engagements</h6>
                                        <p class="text-muted small mb-0">No active or historical loan applications found.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Household Credit -->
                        <div class="tab-pane fade" id="purchases" role="tabpanel">
                            <div class="table-responsive">
                                <?php if (isset($household) && !empty($household)): ?>
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="small text-uppercase fw-bold text-muted">
                                                <th class="ps-4">Ticket</th>
                                                <th>Acquisition Desc</th>
                                                <th>Liability Value</th>
                                                <th>Phase</th>
                                                <th class="pe-4">Logged</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            <?php foreach ($household as $purchase): ?>
                                            <tr>
                                                <td class="ps-4 small fw-bold">#<?= $purchase['id'] ?></td>
                                                <td class="small fw-semibold"><?= htmlspecialchars($purchase['description'] ?? 'Product/Service') ?></td>
                                                <td class="fw-bold">₦<?= number_format($purchase['amount'] ?? 0, 2) ?></td>
                                                <td>
                                                    <span class="badge rounded-pill px-3 py-2 fw-normal
                                                        <?php 
                                                        switch($purchase['status']) {
                                                            case 'pending': echo 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; break;
                                                            case 'processing': echo 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25'; break;
                                                            case 'approved': echo 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; break;
                                                            case 'declined': echo 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; break;
                                                            default: echo 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
                                                        }
                                                        ?>">
                                                        <?= ucfirst($purchase['status'] ?? 'Queued') ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 small"><?= isset($purchase['created_at']) && $purchase['created_at'] ? date('M d, Y', strtotime($purchase['created_at'])) : '---' ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="p-5 text-center">
                                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                            <i class="fas fa-shopping-basket fa-2x opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold">No Household Purchases</h6>
                                        <p class="text-muted small mb-0">No historical data for household credit disbursements.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-4 rounded-3 border bg-light">
                <div class="d-flex gap-3 align-items-center mb-0 text-muted">
                    <i class="fas fa-shield-alt fa-lg text-primary opacity-50"></i>
                    <p class="small mb-0">Profile data integrity is maintained through central ledger synchronization. System timestamps reflect <strong>Server Time (UTC)</strong>. Membership identity joined on <span class="fw-bold"><?= date('M d, Y', strtotime($member['created_at'])) ?></span>.</p>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-4 d-inline-block mb-4">
                <i class="fas fa-user-slash fa-3x"></i>
            </div>
            <h4 class="fw-bold">Entity Not Located</h4>
            <p class="text-muted mb-4 small">The member record you are trying to access does not exist or has been permanently archived.</p>
            <a href="<?= url('/superadmin/members') ?>" class="btn btn-primary shadow-sm px-4">Registry Home</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab persist handle (optional)
});
</script> 
