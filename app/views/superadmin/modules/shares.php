<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Shares Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item active">Shares Management</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Total Shares Value</h5>
                            <div class="small">All members</div>
                        </div>
                        <div class="fs-3 fw-bold">₦<?php echo number_format($stats['total_value'] ?? 0, 2); ?></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('/superadmin/shares'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Total Share Records</h5>
                            <div class="small">All time</div>
                        </div>
                        <div class="fs-3 fw-bold"><?php echo number_format($stats['total_shares'] ?? 0); ?></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('/superadmin/shares'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Members with Shares</h5>
                            <div class="small">Active members</div>
                        </div>
                        <div class="fs-3 fw-bold"><?php echo number_format($stats['total_members'] ?? 0); ?></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('/superadmin/shares'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Actions</h5>
                            <div class="small">Manage shares</div>
                        </div>
                        <div class="fs-2"><i class="fas fa-cogs"></i></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo url('/superadmin/add-share-deduction'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Share Deduction
                    </a>
                    <a href="<?php echo url('/superadmin/process-share-balances'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-sync me-1"></i> Process Share Balances
                    </a>
                    <a href="<?php echo url('/superadmin/shares/export'); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Share Records
            </div>
            <div>
                <a href="<?php echo url('/superadmin/add-share-deduction'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Add Share Deduction
                </a>
                <a href="<?php echo url('/superadmin/process-share-balances'); ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-sync me-1"></i> Process Share Balances
                </a>
                <a href="<?php echo url('/superadmin/shares/export'); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="get" action="<?php echo url('/superadmin/shares'); ?>" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="member_id" class="form-label">Member</label>
                    <select class="form-select" id="member_id" name="member_id">
                        <option value="">All Members</option>
                        <?php foreach ($members as $member): ?>
                        <option value="<?php echo $member['id']; ?>" <?php echo (isset($_GET['member_id']) && $_GET['member_id'] == $member['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($member['name'] . ' (' . $member['coop_no'] . ')'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Date Range</label>
                    <input type="text" class="form-control" id="date_range" name="date_range" value="<?php echo htmlspecialchars($_GET['date_range'] ?? ''); ?>" placeholder="Select date range">
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Search by name or COOPS no.">
                </div>
                <div class="col-md-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="<?php echo url('/superadmin/shares'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
            
            <!-- Shares Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Member</th>
                            <th>COOPS No.</th>
                            <th>Units</th>
                            <th>Unit Value</th>
                            <th>Total Value</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($shares)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No share records found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($shares as $share): ?>
                                <tr<?php if(isset($share['needs_processing']) && $share['needs_processing']): ?> class="table-warning"<?php elseif(isset($share['needs_syncing']) && $share['needs_syncing']): ?> class="table-info"<?php endif; ?>>
                                    <td><?php echo $share['id'] ? $share['id'] : '<span class="badge bg-warning">Pending</span>'; ?></td>
                                    <td><?php echo htmlspecialchars($share['member_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($share['member_coop_no'] ?? 'N/A'); ?></td>
                                    <td><?php echo number_format($share['units'] ?? 0); ?></td>
                                    <td>₦<?php echo number_format($share['unit_value'] ?? 0, 2); ?></td>
                                    <td>
                                        ₦<?php echo number_format($share['total_value'] ?? 0, 2); ?>
                                        <?php if(isset($share['needs_syncing']) && $share['needs_syncing']): ?>
                                            <br><small class="text-danger">Diff: ₦<?php echo number_format($share['balance_difference'] ?? 0, 2); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($share['created_at']): ?>
                                            <?php echo date('F j, Y', strtotime($share['created_at'])); ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Initial Balance</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo url('/superadmin/view-member/' . ($share['member_id'] ?? 0)); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user me-1"></i> View Member
                                        </a>
                                        <?php if(!is_null($share['id'])): ?>
                                        <a href="<?php echo url('/superadmin/share-transactions/' . ($share['id'] ?? 0)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-list me-1"></i> Transactions
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <nav aria-label="Share records pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($pagination['current_page'] <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo url('/superadmin/shares?page=' . ($pagination['current_page'] - 1) . $pagination['query_string']); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        
                        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                            <li class="page-item <?php echo ($pagination['current_page'] == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo url('/superadmin/shares?page=' . $i . $pagination['query_string']); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?php echo ($pagination['current_page'] >= $pagination['total_pages']) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo url('/superadmin/shares?page=' . ($pagination['current_page'] + 1) . $pagination['query_string']); ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date range picker
        if (typeof flatpickr !== 'undefined') {
            flatpickr('#date_range', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y',
                locale: {
                    rangeSeparator: ' to '
                }
            });
        }
    });
</script> 