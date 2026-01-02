<?php 
// Set current page for active menu highlighting
$current_page = 'create_admin';
// Set page title
$page_title = 'Create Administrator';
?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Create Administrator</h4>
            <p class="text-muted small mb-0">Add a new administrative user to the system</p>
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
                    <h6 class="fw-bold mb-0">New Administrator Account</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/store-admin') ?>" method="POST" id="adminForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold small text-muted text-uppercase">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light bg-light" id="username" name="username" required 
                                    value="<?= htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>" placeholder="e.g. jdoe">
                                <?php if (isset($_SESSION['errors']['username'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['username']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-semibold small text-muted text-uppercase">System Role <span class="text-danger">*</span></label>
                                <select class="form-select border-light bg-light" id="role" name="role" required>
                                    <option value="admin" <?= (isset($_SESSION['form_data']['role']) && $_SESSION['form_data']['role'] === 'admin') ? 'selected' : ''; ?>>
                                        Administrator
                                    </option>
                                    <option value="superadmin" <?= (isset($_SESSION['form_data']['role']) && $_SESSION['form_data']['role'] === 'superadmin') ? 'selected' : ''; ?>>
                                        Superadministrator
                                    </option>
                                </select>
                                <?php if (isset($_SESSION['errors']['role'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['role']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small text-muted text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light bg-light" id="name" name="name" required 
                                    value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? ''); ?>" placeholder="e.g. John Doe">
                                <?php if (isset($_SESSION['errors']['name'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['name']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control border-light bg-light" id="email" name="email" required 
                                    value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>" placeholder="e.g. john@example.com">
                                <?php if (isset($_SESSION['errors']['email'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12">
                                <label for="password" class="form-label fw-semibold small text-muted text-uppercase">Login Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control border-light bg-light" id="password" name="password" required minlength="8" placeholder="At least 8 characters">
                                    <button class="btn btn-light border-light" type="button" id="togglePassword">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <?php if (isset($_SESSION['errors']['password'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['password']; ?></div>
                                <?php endif; ?>
                                <div class="form-text small opacity-75">Must include a mix of letters, numbers, and symbols.</div>
                            </div>
                        </div>
                        
                        <hr class="my-4 opacity-50">
                        
                        <div class="d-flex justify-content-end gap-2">
                             <a href="<?= url('/superadmin/manage-admins') ?>" class="btn btn-light px-4">Cancel</a>
                             <button type="submit" class="btn btn-primary px-4">
                                 <i class="fas fa-save me-2"></i> Create Account
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">Role Descriptions</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-circle">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Administrator</h6>
                        </div>
                        <p class="text-muted small ms-5 mb-0">
                            Standard role for daily operations. Can manage members, contributions, and review loan applications.
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-circle">
                                <i class="fas fa-shield-alt"></i>
                            </span>
                            <h6 class="fw-bold mb-0 text-warning">Superadministrator</h6>
                        </div>
                        <p class="text-muted small ms-5 mb-0">
                            Highest privilege. Can manage other admins, access system logs, and modify system settings.
                        </p>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-start gap-3 p-4">
                <i class="fas fa-exclamation-triangle mt-1"></i>
                <div class="small">
                    <strong>Security Warning:</strong> Only grant <strong>Superadministrator</strong> access to trusted individuals. They have full control over the database and security configurations.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye text-muted"></i>' : '<i class="fas fa-eye-slash text-muted"></i>';
        });
    }
});
</script>

<?php 
// Clear session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?> 
