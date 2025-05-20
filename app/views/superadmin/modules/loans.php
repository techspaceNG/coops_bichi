<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Loan Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item active">Loan Management</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-filter me-1"></i>
                Filter Loan Applications
            </div>
            <div>
                <a href="<?php echo url('/superadmin/export-loans'); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo url('/superadmin/loans'); ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo isset($_GET['status']) && $_GET['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo isset($_GET['status']) && ($_GET['status'] === 'rejected' || $_GET['status'] === 'declined') ? 'selected' : ''; ?>>Rejected</option>
                        <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo htmlspecialchars($dept['id']); ?>" <?php echo isset($_GET['department']) && $_GET['department'] == $dept['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Name or COOPS No." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Date Range</label>
                    <input type="text" class="form-control date-range-picker" id="date_range" name="date_range" placeholder="Select date range" value="<?php echo htmlspecialchars($_GET['date_range'] ?? ''); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="/superadmin/loans" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Loans Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Loan Applications
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="loansTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>COOPS No.</th>
                            <th>Member Name</th>
                            <th>Amount (₦)</th>
                            <th>IP Figure (₦)</th>
                            <th>Duration (Months)</th>
                            <th>Application Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($loans)): ?>
                            <tr>
                                <td colspan="9" class="text-center">No loan applications found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($loans as $loan): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($loan['display_id'] ?? $loan['loan_id'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($loan['coop_no'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['member_name'] ?? ''); ?></td>
                                    <td><?php echo number_format($loan['loan_amount'] ?? 0, 2); ?></td>
                                    <td><?php echo number_format($loan['ip_figure'] ?? 0, 2); ?></td>
                                    <td><?php echo htmlspecialchars($loan['loan_duration'] ?? '12'); ?></td>
                                    <td><?php echo isset($loan['application_date']) && $loan['application_date'] ? date('M d, Y', strtotime($loan['application_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $status = $loan['status'] ?? '';
                                        switch ($status) {
                                            case 'pending':
                                                $statusClass = 'bg-warning';
                                                break;
                                            case 'approved':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'rejected':
                                            case 'declined':
                                                $statusClass = 'bg-danger';
                                                break;
                                            case 'completed':
                                                $statusClass = 'bg-info';
                                                break;
                                        }
                                        
                                        // Normalize status text for display
                                        $statusText = $status;
                                        if ($statusText === 'declined' || $statusText === 'rejected') {
                                            $statusText = 'Rejected';
                                        } else if ($statusText === '') {
                                            $statusText = 'N/A';
                                        } else {
                                            $statusText = ucfirst($statusText);
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($statusText); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php 
                                            $loanId = $loan['loan_id'] ?? ($loan['id'] ?? '');
                                            if (!empty($loanId)):
                                            ?>
                                            <a href="<?php echo url('/superadmin/view-loan/' . $loanId); ?>" class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (($loan['status'] ?? '') === 'pending'): ?>
                                                <!-- Application actions -->
                                                <a href="<?php echo url('/superadmin/approve-loan-application/' . $loanId); ?>" class="btn btn-outline-success" title="Approve Application">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="<?php echo url('/superadmin/decline-loan-application/' . $loanId); ?>" class="btn btn-outline-danger" title="Decline Application">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="#" class="btn btn-outline-info print-btn" title="Print Details" data-loan-id="<?php echo $loanId; ?>" data-status="<?php echo htmlspecialchars($loan['status'] ?? ''); ?>">
                                                <i class="fas fa-print"></i>
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
            
            <!-- Pagination -->
            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1<?php echo $pagination['query_string']; ?>" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $pagination['query_string']; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                            <li class="page-item <?php echo $i === (int)$pagination['current_page'] ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $pagination['query_string']; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $pagination['query_string']; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['total_pages']; ?><?php echo $pagination['query_string']; ?>" aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Loan Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Loans</div>
                            <div class="display-6"><?php echo $stats['total']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Total Amount: ₦<?php echo number_format($stats['total_amount'] ?? 0, 2); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Pending</div>
                            <div class="display-6"><?php echo $stats['pending'] ?? 0; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Amount: ₦<?php echo number_format($stats['pending_amount'] ?? 0, 2); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Approved</div>
                            <div class="display-6"><?php echo $stats['approved'] ?? 0; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Amount: ₦<?php echo number_format($stats['approved_amount'] ?? 0, 2); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Rejected</div>
                            <div class="display-6"><?php echo $stats['rejected'] ?? 0; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-times-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Amount: ₦<?php echo number_format($stats['rejected_amount'] ?? 0, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range picker
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.date-range-picker', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    }
    
    // Initialize loan table with sorting, if DataTables is available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#loansTable').DataTable({
            order: [[6, 'desc']], // Sort by application date by default
            paging: false,
            searching: false,
            info: false
        });
    }
    
    // Handle print button clicks
    document.querySelectorAll('.print-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const loanId = this.getAttribute('data-loan-id');
            const status = this.getAttribute('data-status');
            if (loanId) {
                // If it's an initial balance ID (starts with IB), don't modify it
                window.open('<?php echo url('/superadmin/print-loan/'); ?>' + loanId + '?status=' + encodeURIComponent(status), '_blank');
            }
        });
    });
});
</script>