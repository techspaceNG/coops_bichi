<?php /* No header needed - included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Edit Administrator</h4>
            <p class="text-muted small mb-0">Updating profile for <strong><?= htmlspecialchars($admin['username']) ?></strong></p>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/manage-admins') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> 
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">Administrative Profile Details</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/update-admin/'.$admin['id']) ?>" method="POST">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase">Username</label>
                                <div class="bg-light p-3 rounded border-light text-muted fw-bold">
                                    <i class="fas fa-at me-2 opacity-50"></i><?= htmlspecialchars($admin['username']) ?>
                                    <span class="float-end badge bg-secondary bg-opacity-10 text-secondary border rounded-pill fw-normal" style="font-size: 0.65rem;">Read Only</span>
                                </div>
                                <div class="form-text small opacity-75">Username tokens are permanent and cannot be changed for audit integrity.</div>
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="role" class="form-label fw-semibold small text-muted text-uppercase">System Role <span class="text-danger">*</span></label>
                                <select class="form-select border-light bg-light" id="role" name="role" required>
                                    <option value="admin" <?= (($_SESSION['form_data']['role'] ?? $admin['role']) === 'admin') ? 'selected' : ''; ?>>
                                        Administrator
                                    </option>
                                    <option value="superadmin" <?= (($_SESSION['form_data']['role'] ?? $admin['role']) === 'superadmin') ? 'selected' : ''; ?>>
                                        Superadministrator
                                    </option>
                                </select>
                                <?php if (isset($_SESSION['errors']['role'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['role']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mt-4">
                                <label class="form-label fw-semibold small text-muted text-uppercase">Account Status</label>
                                <div class="d-flex align-items-center gap-3 py-2 px-3 bg-light rounded border-light">
                                    <?php if ((int)$admin['is_locked'] === 1): ?>
                                        <div class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">
                                            <i class="fas fa-lock me-1"></i> Locked
                                        </div>
                                        <span class="text-muted small">Account is restricted</span>
                                    <?php else: ?>
                                        <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </div>
                                        <span class="text-muted small">Full system access</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6 mt-4">
                                <label for="name" class="form-label fw-semibold small text-muted text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light bg-light" id="name" name="name" required 
                                    value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? $admin['name']); ?>">
                                <?php if (isset($_SESSION['errors']['name'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['name']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control border-light bg-light" id="email" name="email" required 
                                    value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? $admin['email']); ?>">
                                <?php if (isset($_SESSION['errors']['email'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($admin['role'] === 'superadmin' && $admin['id'] === $_SESSION['admin_id']): ?>
                            <div class="alert alert-info border-0 shadow-sm mt-4 d-flex align-items-center gap-2 py-2 px-3">
                                <i class="fas fa-info-circle"></i>
                                <span class="small">You are currently editing your own <strong>Superadministrator</strong> account.</span>
                            </div>
                        <?php endif; ?>
                        
                        <hr class="my-5 opacity-50">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-history me-1 opacity-50"></i>
                                Last updated on <?= date('M j, Y \a\t g:i a', strtotime($admin['updated_at'] ?? $admin['created_at'])) ?>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?= url('/superadmin/reset-password/'.$admin['id']) ?>" class="btn btn-outline-warning">
                                    <i class="fas fa-key me-2"></i> Reset Password
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Account Metrics -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 fw-bold">Account Insights</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Last Active</span>
                            <span class="fw-bold">
                                <?= isset($admin['last_login']) ? date('M j, Y, g:i a', strtotime($admin['last_login'])) : '<span class="text-muted fw-normal">Never</span>' ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Failed Attempts</span>
                            <span class="fw-bold <?= ($admin['failed_attempts'] ?? 0) > 0 ? 'text-danger' : 'text-success' ?>">
                                <?= $admin['failed_attempts'] ?? 0 ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted">Member Since</span>
                            <span class="fw-bold">
                                <?= date('M j, Y', strtotime($admin['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                <!-- Danger Zone -->
                <div class="card border-0 shadow-sm bg-danger bg-opacity-10 border-danger border-opacity-10">
                    <div class="card-header border-0 bg-transparent text-danger fw-bold py-3"><i class="fas fa-exclamation-triangle me-2"></i>Restrict Access</div>
                    <div class="card-body p-4 pt-0 text-center">
                        <p class="text-muted small mb-4">Temporarily block login or permanently remove this user account from the system.</p>
                        
                        <div class="d-grid gap-2">
                            <?php if ((int)$admin['is_locked'] === 1): ?>
                                <a href="<?= url('/superadmin/toggle-lock/'.$admin['id']) ?>" class="btn btn-success" 
                                   onclick="return confirm('Are you sure you want to unlock this account?')">
                                    <i class="fas fa-unlock me-2"></i> Unlock Account
                                </a>
                            <?php else: ?>
                                <a href="<?= url('/superadmin/toggle-lock/'.$admin['id']) ?>" class="btn btn-warning text-white" 
                                   onclick="return confirm('Are you sure you want to lock this account? This will prevent any login attempts.')">
                                    <i class="fas fa-lock me-2"></i> Lock Account
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= url('/superadmin/delete-admin/'.$admin['id']) ?>" class="btn btn-danger" 
                               onclick="return confirm('WARNING: Permanent deletion. This cannot be undone. Are you sure?')">
                                <i class="fas fa-trash me-2"></i> Delete Forever
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// Clear session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?> 
