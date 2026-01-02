<?php 
// Set current page for active menu highlighting
$current_page = 'manage_admins';
// Set page title
$page_title = 'Manage Administrators';
?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Manage Administrators</h4>
            <p class="text-muted small mb-0">Manage system users and their access roles</p>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/create-admin') ?>" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> 
                <span>Create New Admin</span>
            </a>
        </div>
    </div>

    <!-- Stats Summary (Optional but cool) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                        <i class="fas fa-users-cog fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Admins</div>
                        <div class="h5 fw-bold mb-0"><?= count($admins) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row align-items-center g-2">
                <div class="col">
                     <h6 class="fw-bold mb-0">Administrator Accounts</h6>
                </div>
                <div class="col-auto">
                    <!-- Filter Toggle/Collapse if needed, but simple is better here -->
                    <form action="<?= url('/superadmin/manage-admins') ?>" method="GET" class="row g-2">
                        <div class="col-auto">
                            <select name="role" class="form-select form-select-sm border-light bg-light" onchange="this.form.submit()">
                                <option value="">All Roles</option>
                                <option value="admin" <?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                <option value="superadmin" <?= (isset($_GET['role']) && $_GET['role'] === 'superadmin') ? 'selected' : '' ?>>Superadmin</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="status" class="form-select form-select-sm border-light bg-light" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="active" <?= (isset($_GET['status']) && $_GET['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                                <option value="locked" <?= (isset($_GET['status']) && $_GET['status'] === 'locked') ? 'selected' : '' ?>>Locked</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control border-light bg-light" placeholder="Search..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button class="btn btn-light border-light" type="submit">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="<?= url('/superadmin/manage-admins') ?>" class="btn btn-sm btn-light border-light" title="Reset">
                                <i class="fas fa-redo-alt text-muted"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4" width="5%">ID</th>
                        <th width="35%">Administrator</th>
                        <th width="15%">Role</th>
                        <th width="15%">Status</th>
                        <th class="text-end pe-4" width="30%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                                    <p>No administrators found matching your criteria</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="text-muted small">#<?= $admin['id'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-0"><?= htmlspecialchars($admin['name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($admin['email']) ?> | @<?= htmlspecialchars($admin['username']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($admin['role'] === 'superadmin'): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Superadmin</span>
                                    <?php else: ?>
                                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">Administrator</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ((int)$admin['is_locked'] === 1): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                            <i class="fas fa-lock me-1"></i> Locked
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= url('/superadmin/edit-admin/' . $admin['id']) ?>" class="btn btn-sm btn-outline-light border text-primary" title="Edit Admin">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= url('/superadmin/reset-password/' . $admin['id']) ?>" class="btn btn-sm btn-outline-light border text-warning" title="Reset Password">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        
                                        <?php if ((int)$admin['is_locked'] === 1): ?>
                                            <a href="<?= url('/superadmin/toggle-lock/' . $admin['id']) ?>" 
                                               class="btn btn-sm btn-outline-light border text-success" 
                                               title="Unlock Account" 
                                               onclick="return confirm('Are you sure you want to unlock this account?')">
                                                <i class="fas fa-unlock"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= url('/superadmin/toggle-lock/' . $admin['id']) ?>" 
                                               class="btn btn-sm btn-outline-light border text-danger" 
                                               title="Lock Account" 
                                               onclick="return confirm('Are you sure you want to lock this account?')">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($_SESSION['admin_id']) && $admin['id'] !== $_SESSION['admin_id']): ?>
                                            <a href="<?= url('/superadmin/delete-admin/' . $admin['id']) ?>" 
                                               class="btn btn-sm btn-outline-light border text-danger" 
                                               title="Delete Admin" 
                                               onclick="return confirm('Are you sure you want to delete this administrator account? This action cannot be undone.')">
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

        <?php if (!empty($pagination) && isset($pagination['last_page']) && $pagination['last_page'] > 1): ?>
        <div class="card-footer bg-white border-top py-3">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <li class="page-item <?= (isset($pagination['current_page']) && $pagination['current_page'] == $i) ? 'active' : '' ?>">
                            <a class="page-link shadow-none" href="<?= url('/superadmin/manage-admins?page=' . $i . 
                               (isset($_GET['role']) ? '&role=' . $_GET['role'] : '') . 
                               (isset($_GET['status']) ? '&status=' . $_GET['status'] : '') . 
                               (isset($_GET['search']) ? '&search=' . $_GET['search'] : '')) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div> 
