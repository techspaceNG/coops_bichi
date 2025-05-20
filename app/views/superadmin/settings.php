<?php 
// Set current page for active menu highlighting
$current_page = 'settings';
// Set page title
$page_title = 'Account Settings';
?>

<!-- Page Content -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Account Settings</h1>
    <a href="<?= url('/superadmin/dashboard') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
    </a>
</div>

<!-- Alerts -->
<?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>

<div class="row">
    <div class="col-lg-6">
        <!-- Change Password -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-key me-1"></i> Change Password</h6>
            </div>
            <div class="card-body">
                <form action="<?= url('/superadmin/settings/update-password') ?>" method="POST">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="form-text">Password must be at least 8 characters long.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <!-- Security Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-shield-alt me-1"></i> Account Security</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-gray-600">Two-Factor Authentication</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enable2fa" disabled>
                        <label class="form-check-label" for="enable2fa">Enable Two-Factor Authentication</label>
                    </div>
                    <div class="form-text mb-3">This feature will be available in a future update.</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-gray-600">Login Notifications</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="loginNotifications" disabled>
                        <label class="form-check-label" for="loginNotifications">Receive email notifications on login</label>
                    </div>
                    <div class="form-text mb-3">This feature will be available in a future update.</div>
                </div>
                
                <hr class="my-4">
                
                <div class="mb-3">
                    <label class="form-label text-gray-600">Account Information</label>
                    <div class="mb-2">
                        <span class="fw-bold">Username:</span> <?= htmlspecialchars($admin['username']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">Email:</span> <?= htmlspecialchars($admin['email']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">Role:</span> <?= ucfirst(htmlspecialchars($admin['role'])) ?>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= url('/superadmin/profile') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-1"></i> View Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Session Management -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-1"></i> Session Management</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-gray-600">Current Session</label>
                    <div class="mb-2">
                        <span class="fw-bold">Last activity:</span> <?= date('F j, Y, g:i a') ?>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">IP address:</span> <?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">Browser:</span> <?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']) ?>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= url('/logout') ?>" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 