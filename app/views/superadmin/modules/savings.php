<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Savings Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item active">Savings Management</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <!-- Actions Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-tasks me-1"></i>
            Bulk Actions
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Upload Monthly Deductions</h5>
                            <p class="card-text small">Upload a CSV or Excel file containing monthly deductions for members.</p>
                            <a href="<?php echo url('/superadmin/savings/upload'); ?>" class="btn btn-primary w-100">
                                <i class="fas fa-upload me-1"></i> Upload Monthly Deductions
                            </a>
                            <div class="form-text mt-2">Click the button above to upload monthly deductions for multiple members.</div>
                            <a href="<?php echo url('/superadmin/savings/download-template'); ?>" class="btn btn-outline-secondary btn-sm mt-2">
                                <i class="fas fa-download me-1"></i> Download Template
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Add Single Deduction</h5>
                            <p class="card-text small">Add a manual deduction for a specific member.</p>
                            <a href="<?php echo url('/superadmin/savings/add'); ?>" class="btn btn-primary w-100">
                                <i class="fas fa-plus-circle me-1"></i> Add New Deduction
                            </a>
                            <div class="form-text mt-2">Click the button above to add a manual deduction for a specific member.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-filter me-1"></i>
                Filter Savings Records
            </div>
            <div>
                <a href="<?php echo url('/superadmin/savings/export'); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo url('/superadmin/savings'); ?>" class="row g-3">
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
                    <label for="min_amount" class="form-label">Min. Cumulative Amount</label>
                    <input type="number" class="form-control" id="min_amount" name="min_amount" placeholder="Minimum amount" value="<?php echo htmlspecialchars($_GET['min_amount'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="max_amount" class="form-label">Max. Cumulative Amount</label>
                    <input type="number" class="form-control" id="max_amount" name="max_amount" placeholder="Maximum amount" value="<?php echo htmlspecialchars($_GET['max_amount'] ?? ''); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?php echo url('/superadmin/savings'); ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Savings Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-piggy-bank me-1"></i>
            Member Savings Records
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="savingsTable">
                    <thead>
                        <tr>
                            <th>COOPS No.</th>
                            <th>Member Name</th>
                            <th>Department</th>
                            <th>Monthly Deduction (₦)</th>
                            <th>Cumulative Amount (₦)</th>
                            <th>Last Deduction Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($savingsData)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No savings records found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($savingsData as $saving): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($saving['coop_no']); ?></td>
                                    <td><?php echo htmlspecialchars($saving['name']); ?></td>
                                    <td><?php echo isset($saving['department']) && $saving['department'] !== null ? htmlspecialchars($saving['department']) : 'N/A'; ?></td>
                                    <td><?php echo number_format($saving['monthly_deduction'], 2); ?></td>
                                    <td><?php echo number_format($saving['cumulative_amount'], 2); ?></td>
                                    <td><?php echo isset($saving['last_deduction_date']) && $saving['last_deduction_date'] ? date('M d, Y', strtotime($saving['last_deduction_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo url('/superadmin/savings/view/' . $saving['member_id']); ?>" class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo url('/superadmin/savings/edit/' . $saving['member_id']); ?>" class="btn btn-outline-secondary" title="Edit Monthly Deduction">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-info" title="View Deduction History" onclick="viewDeductionHistory(<?php echo $saving['member_id']; ?>)">
                                                <i class="fas fa-history"></i>
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
    
    <!-- Savings Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Members</div>
                            <div class="display-6"><?php echo $stats['total_members']; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>With active savings</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Savings</div>
                            <div class="display-6">₦<?php echo number_format($stats['total_savings'] / 1000000, 1); ?>M</div>
                        </div>
                        <div>
                            <i class="fas fa-piggy-bank fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Cumulative amount for all members</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Monthly Deductions</div>
                            <div class="display-6">₦<?php echo number_format($stats['monthly_deductions'] / 1000000, 1); ?>M</div>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Total monthly deductions</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Average Savings</div>
                            <div class="display-6">₦<?php echo number_format($stats['average_savings'] / 1000, 1); ?>K</div>
                        </div>
                        <div>
                            <i class="fas fa-chart-line fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Average cumulative amount per member</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deduction History Modal -->
<div class="modal fade" id="deductionHistoryModal" tabindex="-1" aria-labelledby="deductionHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deductionHistoryModalLabel">Deduction History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="deductionHistoryContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading deduction history...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize savings table with sorting, if DataTables is available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#savingsTable').DataTable({
            order: [[4, 'desc']], // Sort by cumulative amount by default
            paging: false,
            searching: false,
            info: false
        });
    }
});

// Function to view deduction history
function viewDeductionHistory(memberId) {
    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('deductionHistoryModal'));
    modal.show();
    
    // Fetch deduction history
    fetch('<?php echo url('/superadmin/savings/history/'); ?>' + memberId)
        .then(response => response.json())
        .then(data => {
            let content = '';
            
            if (data.member) {
                content += `<h6>Member: ${data.member.name} (${data.member.coop_no})</h6>`;
            }
            
            if (data.deductions && data.deductions.length > 0) {
                content += `
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount (₦)</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                data.deductions.forEach(deduction => {
                    content += `
                        <tr>
                            <td>${new Date(deduction.deduction_date).toLocaleDateString()}</td>
                            <td>${parseFloat(deduction.amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td>${deduction.description || 'Monthly Deduction'}</td>
                        </tr>
                    `;
                });
                
                content += `
                        </tbody>
                    </table>
                </div>
                `;
            } else {
                content += '<p class="text-center">No deduction history found for this member.</p>';
            }
            
            document.getElementById('deductionHistoryContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('deductionHistoryContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Error loading deduction history: ${error.message}
                </div>
            `;
        });
}
</script> 