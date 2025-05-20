<?php /* No header needed - included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Administrator</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/manage-admins'); ?>">Manage Administrators</a></li>
        <li class="breadcrumb-item active">Edit Administrator</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-edit me-1"></i>
                    Edit Administrator: <?php echo htmlspecialchars($admin['username']); ?>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('/superadmin/update-admin/'.$admin['id']); ?>" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
                            <div class="form-text">Username cannot be changed.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                value="<?php echo htmlspecialchars($_SESSION['form_data']['name'] ?? $admin['name']); ?>">
                            <?php if (isset($_SESSION['errors']['name'])): ?>
                                <div class="text-danger"><?php echo $_SESSION['errors']['name']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? $admin['email']); ?>">
                            <?php if (isset($_SESSION['errors']['email'])): ?>
                                <div class="text-danger"><?php echo $_SESSION['errors']['email']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?php echo (($_SESSION['form_data']['role'] ?? $admin['role']) === 'admin') ? 'selected' : ''; ?>>
                                    Administrator
                                </option>
                                <option value="superadmin" <?php echo (($_SESSION['form_data']['role'] ?? $admin['role']) === 'superadmin') ? 'selected' : ''; ?>>
                                    Superadministrator
                                </option>
                            </select>
                            <?php if (isset($_SESSION['errors']['role'])): ?>
                                <div class="text-danger"><?php echo $_SESSION['errors']['role']; ?></div>
                            <?php endif; ?>
                            
                            <?php if ($admin['role'] === 'superadmin' && $admin['id'] === $_SESSION['admin_id']): ?>
                                <div class="alert alert-warning mt-2 small">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    You cannot demote your own account from Superadministrator.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <div class="form-control bg-light">
                                <?php if ((int)$admin['is_locked'] === 1): ?>
                                    <span class="badge bg-danger">Locked</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Active</span>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">
                                Account status can be changed from the <a href="<?php echo url('/superadmin/manage-admins'); ?>">administrators list</a>.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Last Updated</label>
                            <div class="form-control bg-light">
                                <?php echo date('F j, Y, g:i a', strtotime($admin['updated_at'] ?? $admin['created_at'])); ?>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="<?php echo url('/superadmin/manage-admins'); ?>" class="btn btn-secondary">Cancel</a>
                            
                            <div>
                                <a href="<?php echo url('/superadmin/reset-password/'.$admin['id']); ?>" class="btn btn-warning me-2">
                                    <i class="fas fa-key me-1"></i> Reset Password
                                </a>
                                <button type="submit" class="btn btn-primary">Update Administrator</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Administrator Info
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Last Login</h6>
                        <p class="text-muted">
                            <?php echo isset($admin['last_login']) ? date('F j, Y, g:i a', strtotime($admin['last_login'])) : 'Never'; ?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Failed Login Attempts</h6>
                        <p class="text-muted">
                            <?php echo $admin['failed_attempts'] ?? 0; ?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Account Created</h6>
                        <p class="text-muted">
                            <?php echo date('F j, Y', strtotime($admin['created_at'])); ?>
                        </p>
                    </div>
                    
                    <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                        <div class="border-top pt-3 mt-3">
                            <h6 class="text-danger">Danger Zone</h6>
                            
                            <div class="d-grid gap-2 mt-2">
                                <?php if ((int)$admin['is_locked'] === 1): ?>
                                    <a href="<?php echo url('/superadmin/toggle-lock/'.$admin['id']); ?>" class="btn btn-outline-success btn-sm" 
                                       onclick="return confirm('Are you sure you want to unlock this account?')">
                                        <i class="fas fa-unlock me-1"></i> Unlock Account
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo url('/superadmin/toggle-lock/'.$admin['id']); ?>" class="btn btn-outline-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to lock this account?')">
                                        <i class="fas fa-lock me-1"></i> Lock Account
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo url('/superadmin/delete-admin/'.$admin['id']); ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this administrator account? This action cannot be undone.')">
                                    <i class="fas fa-trash me-1"></i> Delete Administrator
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Clear session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
/* No footer needed - included by renderSuperAdmin method */
?> 