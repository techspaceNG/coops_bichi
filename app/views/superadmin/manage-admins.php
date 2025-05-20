<?php 
// Set current page for active menu highlighting
$current_page = 'manage_admins';
// Set page title
$page_title = 'Manage Administrators';
?>

<!-- Page Content -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manage Administrators</h1>
    <a href="<?= url('/superadmin/create-admin') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Create New Admin
    </a>
</div>

<!-- Alerts -->
<?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users-cog fa-fw"></i> Administrator Accounts</h6>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form action="<?= url('/superadmin/manage-admins') ?>" method="GET" class="row mb-3">
            <div class="col-md-3 mb-2">
                <select name="role" class="form-control form-control-sm">
                    <option value="">All Roles</option>
                    <option value="admin" <?php echo isset($_GET['role']) && $_GET['role'] === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                    <option value="superadmin" <?php echo isset($_GET['role']) && $_GET['role'] === 'superadmin' ? 'selected' : ''; ?>>Superadministrator</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select name="status" class="form-control form-control-sm">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo isset($_GET['status']) && $_GET['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="locked" <?php echo isset($_GET['status']) && $_GET['status'] === 'locked' ? 'selected' : ''; ?>>Locked</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Search admins..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-2 text-right">
                <a href="<?= url('/superadmin/manage-admins') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
        
        <!-- Admins Table -->
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Username</th>
                        <th width="20%">Name</th>
                        <th width="20%">Email</th>
                        <th width="10%">Role</th>
                        <th width="10%">Status</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No administrators found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?php echo $admin['id']; ?></td>
                                <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                <td><?php echo htmlspecialchars($admin['name']); ?></td>
                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td>
                                    <?php if ($admin['role'] === 'superadmin'): ?>
                                        <span class="badge badge-warning">Superadmin</span>
                                    <?php else: ?>
                                        <span class="badge badge-primary">Admin</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ((int)$admin['is_locked'] === 1): ?>
                                        <span class="badge badge-danger">Locked</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= url('/superadmin/edit-admin/' . $admin['id']) ?>" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= url('/superadmin/reset-password/' . $admin['id']) ?>" class="btn btn-outline-warning" title="Reset Password">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        
                                        <?php if ((int)$admin['is_locked'] === 1): ?>
                                            <a href="<?= url('/superadmin/toggle-lock/' . $admin['id']) ?>" class="btn btn-outline-success" 
                                               title="Unlock Account" onclick="return confirm('Are you sure you want to unlock this account?')">
                                                <i class="fas fa-unlock"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= url('/superadmin/toggle-lock/' . $admin['id']) ?>" class="btn btn-outline-danger" 
                                               title="Lock Account" onclick="return confirm('Are you sure you want to lock this account?')">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($_SESSION['admin_id']) && $admin['id'] !== $_SESSION['admin_id']): ?>
                                            <a href="<?= url('/superadmin/delete-admin/' . $admin['id']) ?>" class="btn btn-outline-danger" 
                                               title="Delete" onclick="return confirm('Are you sure you want to delete this administrator account? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
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
        <?php if (!empty($pagination) && isset($pagination['last_page']) && $pagination['last_page'] > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <li class="page-item <?php echo isset($pagination['current_page']) && $pagination['current_page'] == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="<?= url('/superadmin/manage-admins?page=' . $i . 
                               (isset($_GET['role']) ? '&role=' . $_GET['role'] : '') . 
                               (isset($_GET['status']) ? '&status=' . $_GET['status'] : '') . 
                               (isset($_GET['search']) ? '&search=' . $_GET['search'] : '')) ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div> 