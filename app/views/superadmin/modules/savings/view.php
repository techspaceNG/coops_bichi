<?php /* This file is included by the renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Navigation -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Savings Portfolio Analysis</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/savings') ?>" class="text-decoration-none text-muted">Savings Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Entity Audit</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <a href="<?= url('/superadmin/savings') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Portfolio Home</span>
                </a>
                <a href="<?= url('/superadmin/savings/edit/' . $member['id']) ?>" class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-edit"></i>
                    <span>Modify Strategy</span>
                </a>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <!-- Entity Profile & Financial Summary -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="bg-primary py-4 px-4 text-white position-relative">
                    <div class="d-flex align-items-center gap-3 position-relative" style="z-index: 2;">
                        <div class="bg-white bg-opacity-20 rounded-circle p-1">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold text-primary shadow-sm" style="width: 60px; height: 60px; font-size: 1.2rem;">
                                <?= strtoupper(substr($member['name'], 0, 2)) ?>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($member['name']) ?></h5>
                            <div class="small opacity-75 font-monospace">REG. #<?= htmlspecialchars($member['coop_no']) ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="small fw-bold text-muted text-uppercase">Division</span>
                            <span class="badge bg-light text-dark fw-normal border"><?= htmlspecialchars($member['department_name'] ?? 'General') ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="small fw-bold text-muted text-uppercase">Monthly Commitment</span>
                            <span class="fw-bold text-primary">₦<?= number_format($savings['monthly_deduction'] ?? 0, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-muted text-uppercase">Portfolio Lifespan</span>
                            <span class="small"><?= isset($savings['last_deduction_date']) && $savings['last_deduction_date'] ? date('M d, Y', strtotime($savings['last_deduction_date'])) : 'Launch Phase' ?></span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <a href="<?= url('/superadmin/view-member/' . $member['id']) ?>" class="btn btn-light w-100 btn-sm text-primary fw-bold">
                            <i class="fas fa-fingerprint me-2"></i> View Unified Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kinetic Indicators -->
            <div class="card border-0 shadow-sm overflow-hidden bg-dark text-white">
                <div class="card-body p-4">
                    <div class="mb-4 d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-10 p-2 rounded-3 text-white">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Capital Distribution</h6>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50 small">Aggregate Balance</span>
                            <span class="fw-bold">₦<?= number_format($statistics['current_balance'] ?? 0, 2) ?></span>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-white bg-opacity-5">
                                <div class="text-white-50 small text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Agg. Inflow</div>
                                <div class="fw-bold text-success">₦<?= number_format($statistics['total_deposits'] ?? 0, 2) ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-white bg-opacity-5">
                                <div class="text-white-50 small text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Agg. Outflow</div>
                                <div class="fw-bold text-danger">₦<?= number_format($statistics['total_withdrawals'] ?? 0, 2) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Archives -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs border-bottom-0" id="transactionTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="all-transactions-tab" data-bs-toggle="tab" data-bs-target="#all-transactions" type="button" role="tab">
                                <i class="fas fa-exchange-alt me-2"></i> Unified Ledger
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="deposits-tab" data-bs-toggle="tab" data-bs-target="#deposits" type="button" role="tab">
                                <i class="fas fa-arrow-down me-2 text-success"></i> Inflows
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="withdrawals-tab" data-bs-toggle="tab" data-bs-target="#withdrawals" type="button" role="tab">
                                <i class="fas fa-arrow-up me-2 text-danger"></i> Outflows
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="transactionTabsContent">
                        <!-- All Transactions -->
                        <div class="tab-pane fade show active" id="all-transactions" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 datatable-lite">
                                    <thead class="bg-light">
                                        <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                            <th class="ps-4">Temporal Mark</th>
                                            <th>Transaction ID/Type</th>
                                            <th>Volume (₦)</th>
                                            <th>Context/Description</th>
                                            <th class="pe-4 text-end">Audit Ref</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        <?php if (empty($transactions)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted small">No transactions detected in this ledger segment.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($transactions as $transaction): ?>
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="fw-bold small"><?= date('M d, Y', strtotime($transaction['created_at'])) ?></div>
                                                        <div class="text-muted" style="font-size: 0.6rem;"><?= date('h:i A', strtotime($transaction['created_at'])) ?></div>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $typeClass = 'bg-secondary';
                                                        $typeIcon = 'fa-dot-circle';
                                                        switch($transaction['transaction_type']) {
                                                            case 'deposit': $typeClass = 'bg-success text-success'; $typeIcon = 'fa-arrow-down'; break;
                                                            case 'withdrawal': $typeClass = 'bg-danger text-danger'; $typeIcon = 'fa-arrow-up'; break;
                                                            case 'interest': $typeClass = 'bg-primary text-primary'; $typeIcon = 'fa-percentage'; break;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $typeClass ?> bg-opacity-10 border border-<?= explode(' ', $typeClass)[1] ?> border-opacity-10 rounded-pill px-3 py-1" style="font-size: 0.65rem;">
                                                            <i class="fas <?= $typeIcon ?> me-1" style="font-size: 0.5rem;"></i> <?= strtoupper($transaction['transaction_type']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold small <?= $transaction['transaction_type'] === 'withdrawal' ? 'text-danger' : 'text-success' ?>">
                                                            <?= $transaction['transaction_type'] === 'withdrawal' ? '-' : '+' ?>₦<?= number_format($transaction['amount'], 2) ?>
                                                        </div>
                                                    </td>
                                                    <td><div class="small text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($transaction['description'] ?? 'System Processed') ?>"><?= htmlspecialchars($transaction['description'] ?? 'System Processed') ?></div></td>
                                                    <td class="pe-4 text-end"><span class="small font-monospace text-muted opacity-50"><?= htmlspecialchars($transaction['admin_name'] ?? 'CORE-SYS') ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Inflows Only -->
                        <div class="tab-pane fade" id="deposits" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 datatable-lite">
                                    <thead class="bg-light">
                                        <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                            <th class="ps-4">Date</th>
                                            <th>Amount (₦)</th>
                                            <th>Provenance</th>
                                            <th class="pe-4 text-end">Registrar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        <?php foreach ($deductions as $deduction): ?>
                                            <tr>
                                                <td class="ps-4 small fw-bold"><?= date('M d, Y', strtotime($deduction['created_at'])) ?></td>
                                                <td class="fw-bold text-success">₦<?= number_format($deduction['amount'], 2) ?></td>
                                                <td class="small"><?= htmlspecialchars($deduction['description'] ?? 'Monthly Contribution') ?></td>
                                                <td class="pe-4 text-end small font-monospace opacity-50"><?= htmlspecialchars($deduction['admin_name'] ?? 'CORE-SYS') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Outflows Only -->
                        <div class="tab-pane fade" id="withdrawals" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 datatable-lite">
                                    <thead class="bg-light">
                                        <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                            <th class="ps-4">Date</th>
                                            <th>Amount (₦)</th>
                                            <th>Reasoning</th>
                                            <th class="pe-4 text-end">Registrar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        <?php foreach ($withdrawals as $withdrawal): ?>
                                            <tr>
                                                <td class="ps-4 small fw-bold"><?= date('M d, Y', strtotime($withdrawal['created_at'])) ?></td>
                                                <td class="fw-bold text-danger">₦<?= number_format($withdrawal['amount'], 2) ?></td>
                                                <td class="small"><?= htmlspecialchars($withdrawal['description'] ?? 'Withdrawal') ?></td>
                                                <td class="pe-4 text-end small font-monospace opacity-50"><?= htmlspecialchars($withdrawal['admin_name'] ?? 'CORE-SYS') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Basic DataTable Init
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.datatable-lite').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            searching: true,
            lengthChange: false,
            dom: '<"p-3 fb-header"f>t<"p-3 fb-footer"p>',
            language: {
                search: "",
                searchPlaceholder: "Search ledger..."
            }
        });
    }
});
</script> 