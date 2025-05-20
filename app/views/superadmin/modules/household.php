<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Household Purchase Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item active">Household Purchase Management</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-filter me-1"></i>
                Filter Household Purchase Applications
            </div>
            <div>
                <a href="<?php echo url('/superadmin/export-household'); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo url('/superadmin/household'); ?>" class="row g-3">
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
                    <a href="<?php echo url('/superadmin/household'); ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Household Purchases Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Household Purchase Applications
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="householdTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>COOPS No.</th>
                            <th>Member Name</th>
                            <th>Description</th>
                            <th>Amount (₦)</th>
                            <th>IP Figure (₦)</th>
                            <th>Total Repayment (₦)</th>
                            <th>Duration (Months)</th>
                            <th>Application Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($household)): ?>
                            <tr>
                                <td colspan="11" class="text-center">No household purchase applications found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($household as $purchase): ?>
                                <?php 
                                // Determine if this is an application or a purchase
                                $isApplication = isset($purchase['source_table']) && $purchase['source_table'] === 'application';
                                $purchaseId = $purchase['id'];
                                $viewUrl = $isApplication 
                                    ? url('/superadmin/view-household-application/' . $purchaseId)
                                    : url('/superadmin/view-household/' . $purchaseId);
                                
                                // Calculate total repayment (amount + 5% admin charge)
                                $totalRepayment = isset($purchase['total_repayment']) 
                                    ? $purchase['total_repayment'] 
                                    : ($purchase['amount'] * 1.05);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($purchase['id']); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['member_coop_no'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['member_name']); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['description']); ?></td>
                                    <td><?php echo number_format($purchase['amount'], 2); ?></td>
                                    <td><?php echo number_format($purchase['ip_figure'], 2); ?></td>
                                    <td><?php echo number_format($totalRepayment, 2); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['purchase_duration'] ?? $purchase['repayment_period'] ?? '12'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($purchase['created_at'])); ?></td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch ($purchase['status']) {
                                            case 'pending':
                                                $statusClass = 'bg-warning';
                                                break;
                                            case 'approved':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'declined':
                                            case 'rejected':
                                                $statusClass = 'bg-danger';
                                                $purchase['status'] = 'rejected'; // Normalize display text
                                                break;
                                            case 'completed':
                                                $statusClass = 'bg-info';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($purchase['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo $viewUrl; ?>" class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($purchase['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-outline-success" title="Approve Purchase" 
                                                        onclick="window.location.href='<?php echo $viewUrl; ?>#approve'">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" title="Decline Purchase"
                                                        onclick="window.location.href='<?php echo $viewUrl; ?>#decline'">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                            <a href="#" class="btn btn-outline-info" title="Print Details" onclick="printHouseholdDetails(<?php echo $purchase['id']; ?>, '<?php echo $isApplication ? 'application' : 'purchase'; ?>')">
                                                <i class="fas fa-print"></i>
                                            </a>
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
                                <a class="page-link" href="<?php echo url('/superadmin/household'); ?>?page=1<?php echo $pagination['query_string']; ?>" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo url('/superadmin/household'); ?>?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $pagination['query_string']; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                            <li class="page-item <?php echo $i === (int)$pagination['current_page'] ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo url('/superadmin/household'); ?>?page=<?php echo $i; ?><?php echo $pagination['query_string']; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo url('/superadmin/household'); ?>?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $pagination['query_string']; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo url('/superadmin/household'); ?>?page=<?php echo $pagination['total_pages']; ?><?php echo $pagination['query_string']; ?>" aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Household Purchase Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Purchases</div>
                            <div class="display-6"><?php echo $stats['total']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Total Amount: ₦<?php echo number_format($stats['total_amount'], 2); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Pending</div>
                            <div class="display-6"><?php echo $stats['pending']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Amount: ₦<?php echo number_format($stats['pending_amount'], 2); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Approved</div>
                            <div class="display-6"><?php echo $stats['approved']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Amount: ₦<?php echo number_format($stats['approved_amount'], 2); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Declined</div>
                            <div class="display-6"><?php echo $stats['declined']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-times-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Amount: ₦<?php echo number_format($stats['declined_amount'], 2); ?></span>
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
    
    // Initialize household table with sorting, if DataTables is available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#householdTable').DataTable({
            order: [[6, 'desc']], // Sort by application date by default
            paging: false,
            searching: false,
            info: false
        });
    }
});

// Function to print household purchase details
function printHouseholdDetails(id, type) {
    window.open(<?php echo json_encode(url('/superadmin/print-household/')); ?> + id + '?type=' + type, '_blank', 'width=800,height=600');
}
</script>
<?php /* Don't include admin_footer.php here as it's already included by renderSuperAdmin method */ ?>