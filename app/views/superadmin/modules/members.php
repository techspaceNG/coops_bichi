<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Members Management</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Members Management</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="<?= url('/superadmin/export-members') ?>" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="fas fa-file-excel"></i>
                <span class="d-none d-md-inline">Export Excel</span>
            </a>
            <a href="<?= url('/superadmin/upload-members') ?>" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="fas fa-upload"></i>
                <span class="d-none d-md-inline">Bulk Upload</span>
            </a>
            <a href="<?= url('/superadmin/add-member') ?>" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-user-plus"></i>
                <span>Add Member</span>
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users-cog fa-lg"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill fw-normal">Total</span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= isset($stats['total']) ? number_format($stats['total']) : '0' ?></h3>
                    <p class="text-muted small mb-0 font-weight-medium">Registered Society Members</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-check fa-lg"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-normal">+<?= isset($stats['active']) ? number_format($stats['active']) : '0' ?></span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= isset($stats['active']) ? number_format($stats['active']) : '0' ?></h3>
                    <p class="text-muted small mb-0 font-weight-medium">Active & Operational</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 bg-info bg-opacity-10 text-info p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-sitemap fa-lg"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill fw-normal">Units</span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= isset($stats['departments']) ? number_format($stats['departments']) : '0' ?></h3>
                    <p class="text-muted small mb-0 font-weight-medium">Registered Departments</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-plus fa-lg"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill fw-normal">Growth</span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= isset($stats['new_this_month']) ? number_format($stats['new_this_month']) : '0' ?></h3>
                    <p class="text-muted small mb-0 font-weight-medium">New Onboarded This Month</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="<?= url('/superadmin/members') ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label fw-semibold small text-muted text-uppercase mb-1">Status</label>
                    <select name="is_active" id="status" class="form-select border-light bg-light">
                        <option value="">All Statuses</option>
                        <option value="1" <?= isset($_GET['is_active']) && $_GET['is_active'] === '1' ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?= isset($_GET['is_active']) && $_GET['is_active'] === '0' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="department" class="form-label fw-semibold small text-muted text-uppercase mb-1">Department</label>
                    <select name="department" id="department" class="form-select border-light bg-light">
                        <option value="">All Departments</option>
                        <?php if(isset($departments)): ?>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['id']) ?>" <?= isset($_GET['department']) && $_GET['department'] == $dept['id'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($dept['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold small text-muted text-uppercase mb-1">Search Directory</label>
                    <div class="input-group">
                        <span class="input-group-text border-light bg-light text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-light bg-light" id="search" name="search" placeholder="Name, COOPS No., Email" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 shadow-sm">Filter</button>
                    <a href="<?= url('/superadmin/members') ?>" class="btn btn-light" title="Reset Filters"><i class="fas fa-redo"></i></a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Members Table Table -->
    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0">Registered Society Members</h6>
            <div class="text-muted small">Showing records for the entire directory</div>
        </div>
        <div class="card-body p-0">
            <?php if(empty($members)): ?>
                <div class="p-5 text-center">
                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                        <i class="fas fa-user-slash fa-2x opacity-25"></i>
                    </div>
                    <h5 class="fw-bold">No Records Found</h5>
                    <p class="text-muted mb-0 small">Try adjusting your filters or adding a new member record.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="membersTable">
                        <thead class="bg-light shadow-sm text-uppercase" style="font-size: 0.70rem;">
                            <tr>
                                <th class="ps-4 py-3 border-0 fw-bold">Identification</th>
                                <th class="py-3 border-0 fw-bold">Member Information</th>
                                <th class="py-3 border-0 fw-bold">Organizational Unit</th>
                                <th class="py-3 border-0 fw-bold">System Status</th>
                                <th class="py-3 border-0 fw-bold">Onboarded</th>
                                <th class="pe-4 py-3 border-0 fw-bold text-end">Action Control</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-primary"><?= htmlspecialchars($member['coop_no']) ?></div>
                                        <div class="small text-muted" style="font-size: 0.7rem;">TI: <?= !empty($member['ti_number']) ? htmlspecialchars($member['ti_number']) : 'N/A' ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted" style="width: 38px; height: 38px; font-weight: 500;">
                                                <?= strtoupper(substr($member['name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold mb-0 h6 small"><?= htmlspecialchars($member['name']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($member['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge bg-light text-dark fw-normal border px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-building text-muted me-1 small"></i>
                                            <?= htmlspecialchars($member['department_name'] ?? 'Unassigned') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($member['is_active']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 fw-normal" style="font-size: 0.7rem;">
                                                <i class="fas fa-circle fa-2xs me-1"></i> Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 fw-normal" style="font-size: 0.7rem;">
                                                <i class="fas fa-circle fa-2xs me-1"></i> Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small font-weight-medium mb-0"><?= date('M d, Y', strtotime($member['created_at'])) ?></div>
                                        <div class="text-muted small" style="font-size: 0.65rem;">Membership ID: #<?= $member['id'] ?></div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group shadow-sm border rounded">
                                            <a href="<?= url('/superadmin/view-member/'.$member['id']) ?>" class="btn btn-white btn-sm" title="Profile Profile">
                                                <i class="fas fa-id-card text-muted"></i>
                                            </a>
                                            <a href="<?= url('/superadmin/edit-member/'.$member['id']) ?>" class="btn btn-white btn-sm" title="Edit Metadata">
                                                <i class="fas fa-edit text-muted"></i>
                                            </a>
                                            <button type="button" onclick="toggleMemberStatus(<?= $member['id'] ?>, <?= $member['is_active'] ? 0 : 1 ?>)" 
                                                    class="btn btn-white btn-sm" title="<?= $member['is_active'] ? 'Restrict Access' : 'Restore Access' ?>">
                                                <i class="fas fa-<?= $member['is_active'] ? 'lock text-warning' : 'unlock text-success' ?>"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="card-footer bg-white border-top-0 py-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm justify-content-center gap-1 mb-0">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link border-light rounded-2 px-3" href="?page=1<?= $pagination['query_string'] ?>" aria-label="First">
                                            <i class="fas fa-angle-double-left small"></i>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link border-light rounded-2 px-3" href="?page=<?= $pagination['current_page'] - 1 ?><?= $pagination['query_string'] ?>" aria-label="Previous">
                                            <i class="fas fa-angle-left small"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                                    <li class="page-item <?= $i === (int)$pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link border-light rounded-2 px-3 fw-bold" href="?page=<?= $i ?><?= $pagination['query_string'] ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <li class="page-item">
                                        <a class="page-link border-light rounded-2 px-3" href="?page=<?= $pagination['current_page'] + 1 ?><?= $pagination['query_string'] ?>" aria-label="Next">
                                            <i class="fas fa-angle-right small"></i>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link border-light rounded-2 px-3" href="?page=<?= $pagination['total_pages'] ?><?= $pagination['query_string'] ?>" aria-label="Last">
                                            <i class="fas fa-angle-double-right small"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize members table with sorting
    if (typeof $.fn.DataTable !== 'undefined' && $('#membersTable').length) {
        $('#membersTable').DataTable({
            order: [[4, 'desc']], // Sort by onboarded date by default
            paging: false,
            searching: false,
            info: false,
            columnDefs: [
                { orderable: false, targets: [1, 5] }
            ]
        });
    }
});

// Function to toggle member status
function toggleMemberStatus(memberId, newStatus) {
    const action = newStatus ? 'RESTORE ACCESS to' : 'RESTRICT ACCESS for';
    if (confirm(`SECURITY ALERT: Are you sure you want to ${action} this member record?`)) {
        window.location.href = '<?= url('/superadmin/toggle-member-status/') ?>' + memberId + '/' + newStatus;
    }
}
</script> 
