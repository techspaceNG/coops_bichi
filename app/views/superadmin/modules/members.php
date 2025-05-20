<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Members Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item active">Members Management</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-filter me-1"></i>
                Filter Members
            </div>
            <div>
                <a href="<?php echo url('/superadmin/export-members'); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo url('/superadmin/members'); ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="is_active" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="1" <?php echo isset($_GET['is_active']) && $_GET['is_active'] === '1' ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo isset($_GET['is_active']) && $_GET['is_active'] === '0' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">All Departments</option>
                        <?php if(isset($departments)): ?>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept['id']); ?>" <?php echo isset($_GET['department']) && $_GET['department'] == $dept['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Name, COOPS No., Email" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="join_date" class="form-label">Join Date Range</label>
                    <input type="text" class="form-control date-range-picker" id="join_date" name="join_date" placeholder="Select date range" value="<?php echo htmlspecialchars($_GET['join_date'] ?? ''); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?php echo url('/superadmin/members'); ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Actions Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-tasks me-1"></i>
            Member Actions
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Add New Member</h5>
                            <p class="card-text">Register a new member to the cooperative society.</p>
                            <a href="<?php echo url('/superadmin/add-member'); ?>" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i> Add Member
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Bulk Upload Members</h5>
                            <p class="card-text">Upload a CSV or Excel file containing multiple members.</p>
                            <a href="<?php echo url('/superadmin/upload-members'); ?>" class="btn btn-success">
                                <i class="fas fa-upload me-1"></i> Bulk Upload
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Members Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            All Members
        </div>
        <div class="card-body">
            <?php if(empty($members)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No members found matching your criteria.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="membersTable">
                        <thead>
                            <tr>
                                <th>COOPS No.</th>
                                <th>TI Number</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['coop_no']); ?></td>
                                    <td><?php echo !empty($member['ti_number']) ? htmlspecialchars($member['ti_number']) : 'N/A'; ?></td>
                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                    <td><?php echo htmlspecialchars($member['email']); ?></td>
                                    <td><?php echo htmlspecialchars($member['phone'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($member['department_name'] ?? 'Not Assigned'); ?></td>
                                    <td>
                                        <?php if($member['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($member['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo url('/superadmin/view-member/'.$member['id']); ?>" class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo url('/superadmin/edit-member/'.$member['id']); ?>" class="btn btn-outline-secondary" title="Edit Member">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" onclick="toggleMemberStatus(<?php echo $member['id']; ?>, <?php echo $member['is_active'] ? 0 : 1; ?>)" class="btn btn-outline-<?php echo $member['is_active'] ? 'warning' : 'success'; ?>" title="<?php echo $member['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                <i class="fas fa-<?php echo $member['is_active'] ? 'ban' : 'check'; ?>"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Member Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Members</div>
                            <div class="display-6"><?php echo isset($stats['total']) ? $stats['total'] : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Registered members</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Active Members</div>
                            <div class="display-6"><?php echo isset($stats['active']) ? $stats['active'] : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-user-check fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Currently active</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Departments</div>
                            <div class="display-6"><?php echo isset($stats['departments']) ? $stats['departments'] : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-building fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Total departments</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">New this Month</div>
                            <div class="display-6"><?php echo isset($stats['new_this_month']) ? $stats['new_this_month'] : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-user-plus fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>Recently joined</span>
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
    
    // Initialize members table with sorting, if DataTables is available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#membersTable').DataTable({
            order: [[6, 'desc']], // Sort by join date by default
            paging: false,
            searching: false,
            info: false
        });
    }
});

// Function to toggle member status
function toggleMemberStatus(memberId, newStatus) {
    if (confirm('Are you sure you want to ' + (newStatus ? 'activate' : 'deactivate') + ' this member?')) {
        window.location.href = '<?php echo url('/superadmin/toggle-member-status/'); ?>' + memberId + '/' + newStatus;
    }
}
</script>