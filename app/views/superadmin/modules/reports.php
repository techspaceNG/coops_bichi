<?php
// Set current page for active menu highlighting
$current_page = 'reports';
?>

<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Intelligence Center</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Reporting Terminal</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" disabled>
                <i class="fas fa-sync-alt"></i>
                <span class="d-none d-sm-inline">Sync Analytics</span>
            </button>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <!-- Main Reporting Console -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Analytics Hub</h6>
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 fw-normal" style="font-size: 0.65rem;">
                        <i class="fas fa-code-branch me-1"></i> Beta v0.9
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-4 rounded-4 mb-4 border border-primary border-opacity-10">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-info-circle fa-2x mt-1 opacity-75"></i>
                            <div>
                                <h6 class="fw-bold">Development Synchronization</h6>
                                <p class="small mb-0 opacity-75">Full forensic reporting and PDF generation capabilities are currently being synchronized with the core engine. Standard data exports remain fully operational.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 pt-2">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light p-4 h-100 hover-shadow transition-all border-start border-4 border-primary">
                                <div class="bg-white rounded-circle p-3 d-inline-block mb-3 text-primary shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Financial Statements</h6>
                                <p class="small text-muted mb-4">Income statements, balance sheets, and cash flow reconciliation logs.</p>
                                <a href="#" class="btn btn-primary btn-sm px-4 fw-bold rounded-pill disabled opacity-50">Generate Report</a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-0 bg-light p-4 h-100 hover-shadow transition-all border-start border-4 border-success">
                                <div class="bg-white rounded-circle p-3 d-inline-block mb-3 text-success shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Operational Analytics</h6>
                                <p class="small text-muted mb-4">Savings growth patterns, loan performance metrics, and asset cycles.</p>
                                <a href="#" class="btn btn-success btn-sm px-4 fw-bold rounded-pill text-white disabled opacity-50">View Analytics</a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-0 bg-light p-4 h-100 hover-shadow transition-all border-start border-4 border-warning">
                                <div class="bg-white rounded-circle p-3 d-inline-block mb-3 text-warning shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-chart"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Member Profiles</h6>
                                <p class="small text-muted mb-4">Engagement tracking, membership density by unit, and growth forecasts.</p>
                                <a href="#" class="btn btn-warning btn-sm px-4 fw-bold rounded-pill text-white disabled opacity-50">Member Report</a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-0 bg-light p-4 h-100 hover-shadow transition-all border-start border-4 border-dark">
                                <div class="bg-white rounded-circle p-3 d-inline-block mb-3 text-dark shadow-sm" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Audit Trails</h6>
                                <p class="small text-muted mb-4">Comprehensive forensic logs of all system activities and security events.</p>
                                <a href="<?= url('/superadmin/system-logs') ?>" class="btn btn-dark btn-sm px-4 fw-bold rounded-pill">View Audit Logs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Summary Sidebar -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm bg-dark text-white mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-uppercase small" style="letter-spacing: 1px;">Ready for Ingestion</h6>
                    <div class="d-grid gap-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-white-50">Active Registrations</span>
                            <span class="fw-bold text-primary"><?= number_format($memberStats['total_members'] ?? 0) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top border-white border-opacity-10 pt-2">
                            <span class="small text-white-50">Portfolio Liquidity</span>
                            <span class="fw-bold text-success">₦<?= number_format($financialStats['total_savings'] ?? 0, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top border-white border-opacity-10 pt-2">
                            <span class="small text-white-50">Outstanding Assets</span>
                            <span class="fw-bold text-danger">₦<?= number_format($financialStats['total_loans'] ?? 0, 2) ?></span>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 btn-sm fw-bold shadow-sm" onclick="window.location.href='<?= url('/superadmin/dashboard') ?>'">
                        <i class="fas fa-tachometer-alt me-2"></i> Global Dashboard
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4 text-center">
                <i class="fas fa-cloud-download-alt fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="fw-bold mb-2">Direct Data Export</h6>
                <p class="small text-muted mb-4">Download the entire database logic for external forensic analysis.</p>
                <div class="d-grid gap-2">
                    <a href="<?= url('/superadmin/export-members') ?>" class="btn btn-outline-success btn-sm border-2 fw-bold">
                        <i class="fas fa-file-excel me-2"></i> Excel Core Dump
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1)!important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style> 