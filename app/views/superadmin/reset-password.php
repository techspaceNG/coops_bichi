<?php 
// Set current page for active menu highlighting
$current_page = 'manage_admins';
// Set page title
$page_title = 'Reset Administrator Password';
?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Reset Password</h4>
            <p class="text-muted small mb-0">Security update for user <strong><?= htmlspecialchars($admin['username']) ?></strong></p>
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
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">Security Credentials Update</h6>
                </div>
                <div class="card-body p-4">
                    <!-- Target Account Summary -->
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border-light border d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Target Administrator</div>
                                    <div class="fw-bold"><?= htmlspecialchars($admin['name']) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border-light border d-flex align-items-center gap-3">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Contact Email</div>
                                    <div class="fw-bold small"><?= htmlspecialchars($admin['email']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="<?= url('/superadmin/update-password/' . $admin['id']) ?>" method="POST">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold small text-muted text-uppercase">New Secret Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control border-light bg-light" id="password" name="password" required minlength="8" placeholder="Enter new password">
                                    <button class="btn btn-light border-light" type="button" id="togglePassword">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <?php if (isset($_SESSION['errors']['password'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label fw-semibold small text-muted text-uppercase">Confirm New Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control border-light bg-light" id="password_confirm" name="password_confirm" required minlength="8" placeholder="Repeat new password">
                                    <button class="btn btn-light border-light" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <?php if (isset($_SESSION['errors']['password_confirm'])): ?>
                                    <div class="text-danger small mt-1"><?= $_SESSION['errors']['password_confirm']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <div class="p-4 bg-light bg-opacity-50 rounded-4 border">
                                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Policy & Notifications</h6>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="force_reset" name="force_reset" value="1">
                                        <label class="form-check-label ms-2 h6 mb-0" for="force_reset">Require password change on next login</label>
                                        <div class="form-text mt-1">Recommended for security. Forces the user to set their own secret key.</div>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="unlock_account" name="unlock_account" value="1" <?= (int)$admin['is_locked'] === 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2 h6 mb-0" for="unlock_account">Grant account unlock permission</label>
                                        <div class="form-text mt-1">Restores access if the account was automatically locked due to login failures.</div>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="send_email" name="send_email" value="1" checked>
                                        <label class="form-check-label ms-2 h6 mb-0" for="send_email">Push email notification alerts</label>
                                        <div class="form-text mt-1">Sends an automated security alert with the new credentials to the user.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-5 opacity-50">
                        
                        <div class="d-flex justify-content-end gap-2">
                             <a href="<?= url('/superadmin/manage-admins') ?>" class="btn btn-light px-4 text-muted">Discard Changes</a>
                             <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                 <i class="fas fa-shield-alt me-2"></i> Update Credentials
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 fw-bold">Entropic Guard</div>
                <div class="card-body p-4 text-center">
                    <p class="text-muted small mb-4">Need a secure key? Generate a high-entropy password instantly.</p>
                    
                    <button id="generatePassword" class="btn btn-outline-primary btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-random fa-sm"></i>
                        <span>Generate Strong Password</span>
                    </button>
                    
                    <div class="mt-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Password Integrity</span>
                            <span id="passwordFeedback" class="small fw-bold text-danger animate-pulse">Enter a key</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div id="passwordStrength" class="progress-bar bg-danger transition-all" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body p-4 small">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="fas fa-lightbulb text-info"></i> Security Standards</h6>
                    <ul class="ps-3 text-muted mb-0 d-grid gap-2">
                        <li>Minimum <strong>8 characters</strong> length</li>
                        <li>Mix of <strong>UPPER</strong> and <strong>lower</strong> case</li>
                        <li>Include at least <strong>one digit</strong> (0-9)</li>
                        <li>Include <strong>symbols</strong> (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordConfirm = document.getElementById('password_confirm');
    const generatePasswordBtn = document.getElementById('generatePassword');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye text-muted"></i>' : '<i class="fas fa-eye-slash text-muted"></i>';
        });
    }

    if (toggleConfirmPassword && passwordConfirm) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            toggleConfirmPassword.innerHTML = type === 'password' ? '<i class="fas fa-eye text-muted"></i>' : '<i class="fas fa-eye-slash text-muted"></i>';
        });
    }

    if (generatePasswordBtn) {
        generatePasswordBtn.addEventListener('click', () => {
            const pwd = generatePassword(14);
            password.value = pwd;
            passwordConfirm.value = pwd;
            password.type = 'text';
            passwordConfirm.type = 'text';
            updatePasswordStrength(pwd);
        });
    }

    password.addEventListener('input', (e) => updatePasswordStrength(e.target.value));

    function updatePasswordStrength(pwd) {
        const strength = calculatePasswordStrength(pwd);
        const bar = document.getElementById('passwordStrength');
        const feedback = document.getElementById('passwordFeedback');
        
        bar.style.width = strength + '%';
        bar.setAttribute('aria-valuenow', strength);
        
        if (strength < 25) {
            bar.className = 'progress-bar bg-danger';
            feedback.textContent = 'Critically Weak';
            feedback.className = 'small fw-bold text-danger';
        } else if (strength < 50) {
            bar.className = 'progress-bar bg-warning';
            feedback.textContent = 'Weak';
            feedback.className = 'small fw-bold text-warning';
        } else if (strength < 75) {
            bar.className = 'progress-bar bg-info';
            feedback.textContent = 'Compelling';
            feedback.className = 'small fw-bold text-info';
        } else {
            bar.className = 'progress-bar bg-success';
            feedback.textContent = 'Unbreakable';
            feedback.className = 'small fw-bold text-success';
        }
    }

    function calculatePasswordStrength(p) {
        if (!p) return 0;
        let s = 0;
        s += Math.min(25, p.length * 2);
        if (/[a-z]/.test(p)) s += 15;
        if (/[A-Z]/.test(p)) s += 15;
        if (/[0-9]/.test(p)) s += 15;
        if (/[^a-zA-Z0-9]/.test(p)) s += 30;
        return Math.min(100, s);
    }

    function generatePassword(l) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
        let retVal = "";
        for (let i = 0; i < l; ++i) retVal += charset.charAt(Math.floor(Math.random() * charset.length));
        return retVal;
    }
});
</script>

<?php 
// Clear session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?> 