<?php 
// Set current page for active menu highlighting
$current_page = 'dashboard';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 fw-bold text-gray-800">Dashboard Overview</h1>
        <p class="text-muted small mb-0">Welcome back to the Superadmin portal.</p>
    </div>
    <div>
        <a href="<?= url('/superadmin/reports') ?>" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
            <i class="fas fa-download fa-sm text-white-50 me-2"></i> Generate Reports
        </a>
    </div>
</div>

<!-- Main Stats Overview -->
<div class="row mb-4 g-3">
    <!-- Admin Stats Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: var(--color-primary-100); color: var(--color-primary-600); flex-shrink: 0;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">Admins</p>
                        <h5 class="mb-0 fw-bold text-dark"><?php echo $adminStats['total_admins']; ?></h5>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light">
                    <a href="<?= url('/superadmin/manage-admins') ?>" class="text-decoration-none small fw-bold" style="color: var(--color-primary-600);">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Members Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #d1e7dd; color: #0f5132; flex-shrink: 0;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">Members</p>
                        <h5 class="mb-0 fw-bold text-dark"><?php echo (int)($memberStats['total_members'] ?? 0); ?></h5>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light">
                    <a href="<?= url('/superadmin/members') ?>" class="text-decoration-none small fw-bold text-success">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Savings Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #cff4fc; color: #055160; flex-shrink: 0;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">Savings</p>
                        <h5 class="mb-0 fw-bold text-dark">₦<?php echo number_format((float)($financialStats['total_savings'] ?? 0), 2); ?></h5>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light">
                    <a href="<?= url('/superadmin/savings') ?>" class="text-decoration-none small fw-bold text-info">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Loans Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #fff3cd; color: #664d03; flex-shrink: 0;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.65rem;">Active Loans</p>
                        <h5 class="mb-0 fw-bold text-dark"><?php echo (int)($financialStats['active_loans'] ?? 0); ?></h5>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light">
                    <a href="<?= url('/superadmin/loans?status=active') ?>" class="text-decoration-none small fw-bold text-warning">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Overview & Member Stats -->
<div class="row mb-4 g-3">
    <!-- Financial Overview -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-bottom-0">
                <h6 class="m-0 font-weight-bold text-dark">Financial Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle text-muted" href="#" role="button" id="financialDropdown" 
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 animated--fade-in" 
                        aria-labelledby="financialDropdown">
                        <span class="dropdown-header text-uppercase small fw-bold">View Options:</span>
                        <a class="dropdown-item small" href="<?= url('/superadmin/reports/financial') ?>">Generate Report</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item small" href="<?= url('/superadmin/financial-dashboard') ?>">Detailed View</a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="border-0 small text-uppercase">Category</th>
                                <th class="border-0 small text-uppercase">Total Amount</th>
                                <th class="border-0 small text-uppercase">Monthly Change</th>
                                <th class="border-0 small text-uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <tr>
                                <td class="py-3 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-2 p-2 me-3 bg-light text-primary">
                                            <i class="fas fa-piggy-bank"></i>
                                        </div>
                                        <span class="fw-medium">Total Savings</span>
                                    </div>
                                </td>
                                <td class="py-3 border-0 fw-bold">₦<?php echo number_format((float)($financialStats['total_savings'] ?? 0), 2); ?></td>
                                <td class="py-3 border-0 <?php echo ($financialStats['savings_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <i class="fas fa-<?php echo ($financialStats['savings_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down'; ?> small me-1"></i>
                                    <?php echo number_format(abs((float)($financialStats['savings_change'] ?? 0)), 2); ?>%
                                </td>
                                <td class="py-3 border-0"><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-2 p-2 me-3 bg-light text-warning">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </div>
                                        <span class="fw-medium">Total Loans</span>
                                    </div>
                                </td>
                                <td class="py-3 border-0 fw-bold">₦<?php echo number_format((float)($financialStats['total_loans'] ?? 0), 2); ?></td>
                                <td class="py-3 border-0 <?php echo ($financialStats['loans_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <i class="fas fa-<?php echo ($financialStats['loans_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down'; ?> small me-1"></i>
                                    <?php echo number_format(abs((float)($financialStats['loans_change'] ?? 0)), 2); ?>%
                                </td>
                                <td class="py-3 border-0"><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Tracking</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-2 p-2 me-3 bg-light text-success">
                                            <i class="fas fa-shopping-basket"></i>
                                        </div>
                                        <span class="fw-medium">Household</span>
                                    </div>
                                </td>
                                <td class="py-3 border-0 fw-bold">₦<?php echo number_format((float)($financialStats['total_household'] ?? 0), 2); ?></td>
                                <td class="py-3 border-0 <?php echo ($financialStats['household_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <i class="fas fa-<?php echo ($financialStats['household_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down'; ?> small me-1"></i>
                                    <?php echo number_format(abs((float)($financialStats['household_change'] ?? 0)), 2); ?>%
                                </td>
                                <td class="py-3 border-0"><span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">Monitoring</span></td>
                            </tr>
                            <tr>
                                <td class="py-3 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-2 p-2 me-3 bg-light text-secondary">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <span class="fw-medium">Total Shares</span>
                                    </div>
                                </td>
                                <td class="py-3 border-0 fw-bold">₦<?php echo number_format((float)($financialStats['total_shares'] ?? 0), 2); ?></td>
                                <td class="py-3 border-0 <?php echo ($financialStats['shares_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <i class="fas fa-<?php echo ($financialStats['shares_change'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down'; ?> small me-1"></i>
                                    <?php echo number_format(abs((float)($financialStats['shares_change'] ?? 0)), 2); ?>%
                                </td>
                                <td class="py-3 border-0"><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Stable</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-2">
                    <a href="<?= url('/superadmin/reports/financial') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Full Financial Report <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Member Statistics -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="m-0 font-weight-bold text-dark">Member Insights</h6>
            </div>
            <div class="card-body pt-0">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-muted">Active Members</span>
                        <span class="small fw-bold text-success"><?php echo (int)($memberStats['active_percentage'] ?? 0); ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo max(1, (int)($memberStats['active_percentage'] ?? 0)); ?>%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-muted">Members with Loans</span>
                        <span class="small fw-bold text-warning"><?php echo (int)($memberStats['with_loans_percentage'] ?? 0); ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo max(1, (int)($memberStats['with_loans_percentage'] ?? 0)); ?>%"></div>
                    </div>
                </div>
                
                <div class="bg-light p-3 rounded-3 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="small text-muted mb-0">New Members (This Month)</p>
                            <h4 class="mb-0 fw-bold"><?php echo (int)($memberStats['new_this_month'] ?? 0); ?></h4>
                        </div>
                        <div class="text-primary opacity-50">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="<?= url('/superadmin/members') ?>" class="btn btn-primary btn-sm rounded-pill">View Member List</a>
                    <a href="<?= url('/superadmin/reports/member') ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Generate Report</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Stats & Log Row -->
<div class="row mb-4 g-3">
    <!-- System Stats -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-server me-2 text-muted"></i>System Health</h6>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-0 text-muted">Database Size</td>
                                <td class="fw-bold text-end"><?php echo $systemStats['db_size_mb']; ?> MB</td>
                            </tr>
                            <tr class="border-top border-light">
                                <td class="ps-0 text-muted">Audit Logs</td>
                                <td class="fw-bold text-end"><?php echo $systemStats['audit_log_count']; ?></td>
                            </tr>
                            <tr class="border-top border-light">
                                <td class="ps-0 text-muted">Last Backup</td>
                                <td class="fw-bold text-end">
                                    <?php
                                    if ($systemStats['last_backup'] === 'Never') {
                                        echo '<span class="badge bg-danger-subtle text-danger rounded-pill">Never</span>';
                                    } else {
                                        $lastBackupDate = new DateTime($systemStats['last_backup']);
                                        $now = new DateTime();
                                        $diff = $now->diff($lastBackupDate);
                                        $colorClass = $diff->days > 7 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';
                                        
                                        echo '<span class="badge ' . $colorClass . ' rounded-pill">' . $diff->days . ' days ago</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr class="border-top border-light">
                                <td class="ps-0 text-muted">PHP Version</td>
                                <td class="fw-bold text-end"><?php echo phpversion(); ?></td>
                            </tr>
                            <tr class="border-top border-light">
                                <td class="ps-0 text-muted">System Status</td>
                                <td class="fw-bold text-end">
                                    <?php 
                                    $systemStatus = $systemStats['system_status'] ?? 'unknown';
                                    if ($systemStatus === 'healthy'): ?>
                                        <span class="badge bg-success text-white rounded-pill"><i class="fas fa-check-circle me-1"></i> Healthy</span>
                                    <?php elseif ($systemStatus === 'warning'): ?>
                                        <span class="badge bg-warning text-dark rounded-pill"><i class="fas fa-exclamation-triangle me-1"></i> Warning</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white rounded-pill"><i class="fas fa-times-circle me-1"></i> Critical</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="row mt-4 g-2">
                    <div class="col-6">
                        <a href="<?= url('/superadmin/system-settings') ?>" class="btn btn-light btn-sm w-100 rounded-pill text-dark border">
                           System Settings
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= url('/superadmin/database-backup') ?>" class="btn btn-light btn-sm w-100 rounded-pill text-dark border">
                           Backups
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Logs -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-shield-alt me-2 text-muted"></i>Security Activity</h6>
                <a href="<?= url('/superadmin/system-logs?type=security') ?>" class="btn btn-link btn-sm p-0 text-decoration-none">View All</a>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($securityLogs)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-shield-alt fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted small">No recent security activity found.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($securityLogs as $log): ?>
                            <div class="list-group-item px-0 border-light">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center">
                                        <?php if ($log['status'] === 'success'): ?>
                                            <span class="badge rounded-circle bg-success-subtle text-success p-1 me-2" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-check fa-xs"></i></span>
                                        <?php elseif ($log['status'] === 'warning'): ?>
                                            <span class="badge rounded-circle bg-warning-subtle text-warning p-1 me-2" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-exclamation fa-xs"></i></span>
                                        <?php else: ?>
                                            <span class="badge rounded-circle bg-danger-subtle text-danger p-1 me-2" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times fa-xs"></i></span>
                                        <?php endif; ?>
                                        <span class="fw-medium text-dark small"><?php echo htmlspecialchars($log['action']); ?></span>
                                    </div>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($log['timestamp'])); ?></small>
                                </div>
                                <div class="d-flex justify-content-between ps-4 ms-1">
                                    <small class="text-muted" style="font-size: 0.75rem;">IP: <?php echo htmlspecialchars($log['ip_address'] ?? 'Unknown'); ?></small>
                                    <span class="badge bg-light text-muted border rounded-pill" style="font-size: 0.7rem;"><?php echo ucfirst($log['status']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h6>
    </div>
    <div class="card-body pt-0">
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <a href="<?= url('/superadmin/manage-admins') ?>" class="card h-100 border-0 bg-light hover-shadow text-decoration-none transition-all">
                    <div class="card-body text-center p-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-2 p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h6 class="text-dark mb-1">Manage Admins</h6>
                        <p class="text-muted small mb-0">Create & edit accounts</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <a href="<?= url('/superadmin/system-settings') ?>" class="card h-100 border-0 bg-light hover-shadow text-decoration-none transition-all">
                    <div class="card-body text-center p-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success mx-auto mb-2 p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h6 class="text-dark mb-1">System Settings</h6>
                        <p class="text-muted small mb-0">Configure parameters</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <a href="<?= url('/superadmin/system-logs') ?>" class="card h-100 border-0 bg-light hover-shadow text-decoration-none transition-all">
                    <div class="card-body text-center p-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto mb-2 p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-history"></i>
                        </div>
                        <h6 class="text-dark mb-1">System Logs</h6>
                        <p class="text-muted small mb-0">View audit trails</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <a href="<?= url('/superadmin/reports/financial') ?>" class="card h-100 border-0 bg-light hover-shadow text-decoration-none transition-all">
                    <div class="card-body text-center p-3">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info mx-auto mb-2 p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h6 class="text-dark mb-1">Financial Report</h6>
                        <p class="text-muted small mb-0">Generate statements</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom utility helpers for compatibility */
.bg-primary-subtle { background-color: #cfe2ff; }
.bg-success-subtle { background-color: #d1e7dd; }
.bg-warning-subtle { background-color: #fff3cd; }
.bg-danger-subtle { background-color: #f8d7da; }
.bg-info-subtle { background-color: #cff4fc; }
.bg-secondary-subtle { background-color: #e2e3e5; }
.text-primary-emphasis { color: #0a58ca; }

.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>