<?php 
// Set current page for active menu highlighting
$current_page = 'create_admin';
// Set page title
$page_title = 'Create Administrator';
?>

<!-- Page Content -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create Administrator</h1>
    <a href="<?= url('/superadmin/manage-admins') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Administrators
    </a>
</div>

<!-- Alerts -->
<?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-plus fa-fw"></i> New Administrator Account</h6>
            </div>
            <div class="card-body">
                <form action="<?= url('/superadmin/store-admin') ?>" method="POST">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" required 
                            value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>">
                        <?php if (isset($_SESSION['errors']['username'])): ?>
                            <div class="small text-danger"><?php echo $_SESSION['errors']['username']; ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Username must be unique and will be used for login.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                            value="<?php echo htmlspecialchars($_SESSION['form_data']['name'] ?? ''); ?>">
                        <?php if (isset($_SESSION['errors']['name'])): ?>
                            <div class="small text-danger"><?php echo $_SESSION['errors']['name']; ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                            value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>">
                        <?php if (isset($_SESSION['errors']['email'])): ?>
                            <div class="small text-danger"><?php echo $_SESSION['errors']['email']; ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Email must be unique and will be used for password resets.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <?php if (isset($_SESSION['errors']['password'])): ?>
                            <div class="small text-danger"><?php echo $_SESSION['errors']['password']; ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Password must be at least 8 characters long and include uppercase, lowercase letters, and numbers.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin" <?php echo isset($_SESSION['form_data']['role']) && $_SESSION['form_data']['role'] === 'admin' ? 'selected' : ''; ?>>
                                Administrator
                            </option>
                            <option value="superadmin" <?php echo isset($_SESSION['form_data']['role']) && $_SESSION['form_data']['role'] === 'superadmin' ? 'selected' : ''; ?>>
                                Superadministrator
                            </option>
                        </select>
                        <?php if (isset($_SESSION['errors']['role'])): ?>
                            <div class="small text-danger"><?php echo $_SESSION['errors']['role']; ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">
                            <strong>Administrator:</strong> Can manage members and regular operations.<br>
                            <strong>Superadministrator:</strong> Has full system access, including admin management.
                        </small>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="/superadmin/manage-admins" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Administrator</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle fa-fw"></i> Administrator Roles</h6>
            </div>
            <div class="card-body">
                <h5 class="card-title">Role Permissions</h5>
                
                <div class="mb-3">
                    <h6 class="text-primary">Administrator</h6>
                    <ul class="small">
                        <li>View member records</li>
                        <li>Review loan applications</li>
                        <li>Access dashboards with reports</li>
                        <li>View audit logs</li>
                        <li>Cannot manage other admins</li>
                        <li>Cannot access system settings</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-warning">Superadministrator</h6>
                    <ul class="small">
                        <li>All Administrator permissions</li>
                        <li>Create, edit, and manage admin accounts</li>
                        <li>Configure system settings</li>
                        <li>Perform database backups</li>
                        <li>View and manage all system logs</li>
                        <li>Full control over the system</li>
                    </ul>
                </div>
                
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Exercise caution when creating Superadministrator accounts, as they have full system access.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle eye icon
        togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Generate random password
    function generatePassword(length = 12) {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }
        
        return password;
    }
});
</script>

<?php 
// Clear session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?> 