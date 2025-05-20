<?php 
// Set current page for active menu highlighting
$current_page = 'dashboard';
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Superadmin Dashboard</h1>
    <div>
        <a href="<?= url('/superadmin/reports') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Reports
        </a>
    </div>
</div>

<!-- Main Stats Overview -->
<div class="row mb-4">
    <!-- Admin Stats Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Administrators</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $adminStats['total_admins']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <a href="<?= url('/superadmin/manage-admins') ?>" class="text-decoration-none">View Details <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Total Members Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Members</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)($memberStats['total_members'] ?? 0); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <a href="<?= url('/superadmin/members') ?>" class="text-decoration-none">View Details <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Total Savings Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Savings</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₦<?php echo number_format((float)($financialStats['total_savings'] ?? 0), 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <a href="<?= url('/superadmin/savings') ?>" class="text-decoration-none">View Details <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Active Loans Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Active Loans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)($financialStats['active_loans'] ?? 0); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <a href="<?= url('/superadmin/loans?status=active') ?>" class="text-decoration-none">View Details <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Financial Overview & Member Stats -->
<div class="row mb-4">
    <!-- Financial Overview -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line me-1"></i> Financial Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="financialDropdown" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" 
                        aria-labelledby="financialDropdown">
                        <div class="dropdown-header">View Options:</div>
                        <a class="dropdown-item" href="<?= url('/superadmin/reports/financial') ?>">Generate Report</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= url('/superadmin/financial-dashboard') ?>">Detailed View</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Category</th>
                                <th>Total Amount</th>
                                <th>Monthly Change</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Total Savings</strong></td>
                                <td>₦<?php echo number_format((float)($financialStats['total_savings'] ?? 0), 2); ?></td>
                                <td class="<?php echo ($financialStats['savings_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo ($financialStats['savings_change'] ?? 0) >= 0 ? '+' : ''; ?>
                                    <?php echo number_format((float)($financialStats['savings_change'] ?? 0), 2); ?>%
                                </td>
                                <td><span class="badge bg-success text-white">Active</span></td>
                            </tr>
                            <tr>
                                <td><strong>Total Loans</strong></td>
                                <td>₦<?php echo number_format((float)($financialStats['total_loans'] ?? 0), 2); ?></td>
                                <td class="<?php echo ($financialStats['loans_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo ($financialStats['loans_change'] ?? 0) >= 0 ? '+' : ''; ?>
                                    <?php echo number_format((float)($financialStats['loans_change'] ?? 0), 2); ?>%
                                </td>
                                <td><span class="badge bg-primary text-white">Tracking</span></td>
                            </tr>
                            <tr>
                                <td><strong>Household Purchases</strong></td>
                                <td>₦<?php echo number_format((float)($financialStats['total_household'] ?? 0), 2); ?></td>
                                <td class="<?php echo ($financialStats['household_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo ($financialStats['household_change'] ?? 0) >= 0 ? '+' : ''; ?>
                                    <?php echo number_format((float)($financialStats['household_change'] ?? 0), 2); ?>%
                                </td>
                                <td><span class="badge bg-info text-white">Monitoring</span></td>
                            </tr>
                            <tr>
                                <td><strong>Total Shares</strong></td>
                                <td>₦<?php echo number_format((float)($financialStats['total_shares'] ?? 0), 2); ?></td>
                                <td class="<?php echo ($financialStats['shares_change'] ?? 0) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo ($financialStats['shares_change'] ?? 0) >= 0 ? '+' : ''; ?>
                                    <?php echo number_format((float)($financialStats['shares_change'] ?? 0), 2); ?>%
                                </td>
                                <td><span class="badge bg-secondary text-white">Stable</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= url('/superadmin/reports/financial') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-chart-bar me-1"></i> Full Financial Report
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Member Statistics -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users me-1"></i> Member Statistics</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-gray-500 mb-1">Total Members</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)($memberStats['total_members'] ?? 0); ?></div>
                </div>
                
                <div class="mb-3">
                    <div class="small text-gray-500 mb-1">Active Members</div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo (int)($memberStats['active_percentage'] ?? 0); ?>%</div>
                        </div>
                        <div class="col">
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-success" role="progressbar" 
                                    style="width: <?php echo max(1, (int)($memberStats['active_percentage'] ?? 0)); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="small text-gray-500 mb-1">New Members (This Month)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)($memberStats['new_this_month'] ?? 0); ?></div>
                </div>
                
                <div class="mb-3">
                    <div class="small text-gray-500 mb-1">Members with Loans</div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo (int)($memberStats['with_loans_percentage'] ?? 0); ?>%</div>
                        </div>
                        <div class="col">
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                    style="width: <?php echo max(1, (int)($memberStats['with_loans_percentage'] ?? 0)); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= url('/superadmin/members') ?>" class="btn btn-sm btn-outline-primary">Member List</a>
                    <a href="<?= url('/superadmin/reports/member') ?>" class="btn btn-sm btn-primary">Member Report</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Stats & Security Logs Row -->
<div class="row mb-4">
    <!-- System Stats -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-server me-1"></i> System Statistics</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="40%"><strong>Database Size:</strong></td>
                                <td><?php echo $systemStats['db_size_mb']; ?> MB</td>
                            </tr>
                            <tr>
                                <td><strong>Audit Log Entries:</strong></td>
                                <td><?php echo $systemStats['audit_log_count']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Last Backup:</strong></td>
                                <td>
                                    <?php
                                    if ($systemStats['last_backup'] === 'Never') {
                                        echo '<span class="text-danger">Never</span>';
                                    } else {
                                        $lastBackupDate = new DateTime($systemStats['last_backup']);
                                        $now = new DateTime();
                                        $diff = $now->diff($lastBackupDate);
                                        
                                        if ($diff->days > 7) {
                                            echo '<span class="text-danger">' . $systemStats['last_backup'] . ' (' . $diff->days . ' days ago)</span>';
                                        } else {
                                            echo '<span class="text-success">' . $systemStats['last_backup'] . ' (' . $diff->days . ' days ago)</span>';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Server Software:</strong></td>
                                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>System Status:</strong></td>
                                <td>
                                    <?php 
                                    $systemStatus = $systemStats['system_status'] ?? 'unknown';
                                    if ($systemStatus === 'healthy'): ?>
                                        <span class="text-success"><i class="fas fa-check-circle"></i> Healthy</span>
                                    <?php elseif ($systemStatus === 'warning'): ?>
                                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Warning</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fas fa-times-circle"></i> Attention Required</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <a href="<?= url('/superadmin/system-settings') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-cogs me-1"></i> System Settings
                    </a>
                    <a href="<?= url('/superadmin/database-backup') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-database me-1"></i> Database Backup
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Logs -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-shield-alt me-1"></i> Recent Security Activity</h6>
            </div>
            <div class="card-body">
                <?php if (empty($securityLogs)): ?>
                    <div class="alert alert-info">No recent security activity found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($securityLogs as $log): ?>
                                    <tr>
                                        <td><?php echo date('M d, H:i', strtotime($log['timestamp'])); ?></td>
                                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                                        <td><?php echo htmlspecialchars($log['ip_address'] ?? 'Unknown'); ?></td>
                                        <td>
                                            <?php if ($log['status'] === 'success'): ?>
                                                <span class="badge bg-success text-white">Success</span>
                                            <?php elseif ($log['status'] === 'warning'): ?>
                                                <span class="badge bg-warning text-white">Warning</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger text-white">Failed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-2">
                        <a href="<?= url('/superadmin/system-logs?type=security') ?>" class="btn btn-sm btn-outline-secondary">View All Logs</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Notices -->
<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt me-1"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-start border-primary border-3">
                            <div class="card-body">
                                <h5 class="card-title">Administrator Management</h5>
                                <p class="card-text">Create, edit, or manage administrator accounts.</p>
                                <a href="<?= url('/superadmin/manage-admins') ?>" class="btn btn-primary">Manage Admins</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-start border-success border-3">
                            <div class="card-body">
                                <h5 class="card-title">System Settings</h5>
                                <p class="card-text">Configure system-wide settings and parameters.</p>
                                <a href="<?= url('/superadmin/system-settings') ?>" class="btn btn-success">Settings</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-start border-warning border-3">
                            <div class="card-body">
                                <h5 class="card-title">Audit & System Logs</h5>
                                <p class="card-text">View and manage system logs and audit trails.</p>
                                <a href="<?= url('/superadmin/system-logs') ?>" class="btn btn-warning">View Logs</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-start border-info border-3">
                            <div class="card-body">
                                <h5 class="card-title">Financial Reports</h5>
                                <p class="card-text">Access and generate financial reports.</p>
                                <a href="<?= url('/superadmin/reports/financial') ?>" class="btn btn-info">Reports</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-start border-secondary border-3">
                            <div class="card-body">
                                <h5 class="card-title">Bulk Operations</h5>
                                <p class="card-text">Perform bulk imports and data operations.</p>
                                <a href="<?= url('/superadmin/bulk-operations') ?>" class="btn btn-secondary">Bulk Ops</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-start border-dark border-3">
                            <div class="card-body">
                                <h5 class="card-title">Announcements</h5>
                                <p class="card-text">Manage system-wide announcements and notices.</p>
                                <a href="<?= url('/superadmin/announcements') ?>" class="btn btn-dark">Manage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Notices & Alerts -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bell me-1"></i> System Notices</h6>
            </div>
            <div class="card-body">
                <?php if (empty($systemNotices)): ?>
                    <div class="alert alert-success">No system notices at this time.</div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($systemNotices as $notice): ?>
                            <a href="<?= url($notice['link']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($notice['title']); ?></h6>
                                    <small><?php echo date('M d', strtotime($notice['date'])); ?></small>
                                </div>
                                <p class="mb-1"><?php echo htmlspecialchars($notice['message']); ?></p>
                                <small class="text-<?php echo $notice['type']; ?>">
                                    <?php echo ucfirst($notice['type']); ?> Notice
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-end mt-3">
                        <a href="<?= url('/superadmin/notices') ?>" class="btn btn-sm btn-outline-primary">View All Notices</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 