<?php
/**
 * Admin Reports Dashboard
 * 
 * @var array $stats Contains total_members, total_loans, total_savings, total_household
 * @var array $admin Admin user information
 */
?>

<!-- Page header -->
<div class="page-header">
    <div class="row">
        <div class="col-md-6">
            <h3>Reports Dashboard</h3>
            <p class="text-muted">View and generate reports for the cooperative</p>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group" role="group">
                <a href="/Coops_Bichi/public/admin/dashboard" class="btn btn-secondary btn-sm">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stats overview -->
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h4><?= number_format($stats['total_members']) ?></h4>
                <div>Total Members</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="/Coops_Bichi/public/admin/members" class="text-white">View Details</a>
                <i class="fa fa-angle-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h4>₦<?= number_format($stats['total_savings'], 2) ?></h4>
                <div>Total Savings</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="/Coops_Bichi/public/admin/savings" class="text-white">View Details</a>
                <i class="fa fa-angle-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h4><?= number_format($stats['total_loans']) ?></h4>
                <div>Total Loans</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="/Coops_Bichi/public/admin/loans" class="text-white">View Details</a>
                <i class="fa fa-angle-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <h4><?= number_format($stats['total_household']) ?></h4>
                <div>Household Purchases</div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="/Coops_Bichi/public/admin/household" class="text-white">View Details</a>
                <i class="fa fa-angle-right"></i>
            </div>
        </div>
    </div>
</div>

<!-- Reports list -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Available Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Savings Reports</h5>
                                <p class="card-text">Generate reports on member savings and contributions.</p>
                                <a href="/Coops_Bichi/public/admin/reports/savings" class="btn btn-primary">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Loan Reports</h5>
                                <p class="card-text">Generate reports on loan applications and repayments.</p>
                                <a href="/Coops_Bichi/public/admin/reports/loans" class="btn btn-success">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Household Reports</h5>
                                <p class="card-text">Generate reports on household purchase applications.</p>
                                <a href="/Coops_Bichi/public/admin/reports/household" class="btn btn-info">Generate Report</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Financial Reports</h5>
                                <p class="card-text">Generate comprehensive financial reports for the cooperative.</p>
                                <a href="/Coops_Bichi/public/admin/reports/financial" class="btn btn-warning">Generate Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 