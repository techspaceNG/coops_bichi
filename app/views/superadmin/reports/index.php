<?php
// Superadmin Reports Index View
?>

<!-- Page header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reports Dashboard</h1>
</div>

<!-- Content Row -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Generate Reports</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Member Report -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-primary mb-3">Member Report</h5>
                        <p class="card-text">Generate reports about cooperative members, including their details and account information.</p>
                        <form action="<?= function_exists('url') ? url('/superadmin/reports/member') : '/Coops_Bichi/public/superadmin/reports/member' ?>" method="GET" class="mt-3">
                            <div class="form-group mb-3">
                                <label for="department_id_member" class="form-label">Department</label>
                                <select name="department_id" id="department_id_member" class="form-control">
                                    <option value="">All Departments</option>
                                    <?php if(isset($departments) && is_array($departments)): ?>
                                        <?php foreach($departments as $department): ?>
                                            <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="status_member" class="form-label">Status</label>
                                <select name="status" id="status_member" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="join_date_from" class="form-label">Join Date From</label>
                                    <input type="date" name="join_date_from" id="join_date_from" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="join_date_to" class="form-label">Join Date To</label>
                                    <input type="date" name="join_date_to" id="join_date_to" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Savings Report -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-success h-100">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-success mb-3">Savings Report</h5>
                        <p class="card-text">Generate reports on savings transactions, including deposits and withdrawals.</p>
                        <form action="<?= function_exists('url') ? url('/superadmin/reports/savings') : '/Coops_Bichi/public/superadmin/reports/savings' ?>" method="GET" class="mt-3">
                            <div class="form-group mb-3">
                                <label for="department_id_savings" class="form-label">Department</label>
                                <select name="department_id" id="department_id_savings" class="form-control">
                                    <option value="">All Departments</option>
                                    <?php if(isset($departments) && is_array($departments)): ?>
                                        <?php foreach($departments as $department): ?>
                                            <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="transaction_type_savings" class="form-label">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type_savings" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="deposit">Deposits</option>
                                    <option value="withdrawal">Withdrawals</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_from_savings" class="form-label">Date From</label>
                                    <input type="date" name="date_from" id="date_from_savings" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to_savings" class="form-label">Date To</label>
                                    <input type="date" name="date_to" id="date_to_savings" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Generate Report</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Loan Report -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-info h-100">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-info mb-3">Loan Report</h5>
                        <p class="card-text">Generate reports on loans, including loan applications, approvals, and repayments.</p>
                        <form action="<?= function_exists('url') ? url('/superadmin/reports/loan') : '/Coops_Bichi/public/superadmin/reports/loan' ?>" method="GET" class="mt-3">
                            <div class="form-group mb-3">
                                <label for="department_id_loan" class="form-label">Department</label>
                                <select name="department_id" id="department_id_loan" class="form-control">
                                    <option value="">All Departments</option>
                                    <?php if(isset($departments) && is_array($departments)): ?>
                                        <?php foreach($departments as $department): ?>
                                            <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="status_loan" class="form-label">Status</label>
                                <select name="status" id="status_loan" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_from_loan" class="form-label">Date From</label>
                                    <input type="date" name="date_from" id="date_from_loan" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to_loan" class="form-label">Date To</label>
                                    <input type="date" name="date_to" id="date_to_loan" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Generate Report</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Household Report -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-warning h-100">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-warning mb-3">Household Report</h5>
                        <p class="card-text">Generate reports on household purchases and applications.</p>
                        <form action="<?= function_exists('url') ? url('/superadmin/reports/household') : '/Coops_Bichi/public/superadmin/reports/household' ?>" method="GET" class="mt-3">
                            <div class="form-group mb-3">
                                <label for="department_id_household" class="form-label">Department</label>
                                <select name="department_id" id="department_id_household" class="form-control">
                                    <option value="">All Departments</option>
                                    <?php if(isset($departments) && is_array($departments)): ?>
                                        <?php foreach($departments as $department): ?>
                                            <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="status_household" class="form-label">Status</label>
                                <select name="status" id="status_household" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_from_household" class="form-label">Date From</label>
                                    <input type="date" name="date_from" id="date_from_household" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to_household" class="form-label">Date To</label>
                                    <input type="date" name="date_to" id="date_to_household" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning">Generate Report</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Transaction Report -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-left-danger h-100">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-danger mb-3">Transaction Report</h5>
                        <p class="card-text">Generate comprehensive reports on all financial transactions.</p>
                        <form action="<?= function_exists('url') ? url('/superadmin/reports/transaction') : '/Coops_Bichi/public/superadmin/reports/transaction' ?>" method="GET" class="mt-3">
                            <div class="form-group mb-3">
                                <label for="transaction_type_all" class="form-label">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type_all" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="savings">Savings</option>
                                    <option value="loan">Loan</option>
                                    <option value="household">Household</option>
                                    <option value="share">Share</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_from_transaction" class="form-label">Date From</label>
                                    <input type="date" name="date_from" id="date_from_transaction" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to_transaction" class="form-label">Date To</label>
                                    <input type="date" name="date_to" id="date_to_transaction" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger">Generate Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default date ranges (current month)
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    const formatDate = date => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    const fromInputs = document.querySelectorAll('input[name$="_from"], input[name="date_from"]');
    const toInputs = document.querySelectorAll('input[name$="_to"], input[name="date_to"]');
    
    fromInputs.forEach(input => {
        input.value = formatDate(firstDay);
    });
    
    toInputs.forEach(input => {
        input.value = formatDate(lastDay);
    });
});
</script> 