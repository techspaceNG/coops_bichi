<?php 
// Set current page for active menu highlighting
$current_page = 'manage_admins';
// Set page title
$page_title = 'Reset Administrator Password';
?>

<!-- Page Content -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reset Password</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-key fa-fw"></i> Reset Administrator Password</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-4 font-weight-bold">Administrator:</div>
                            <div class="col-md-8"><?php echo htmlspecialchars($admin['name']); ?></div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4 font-weight-bold">Username:</div>
                            <div class="col-md-8"><?php echo htmlspecialchars($admin['username']); ?></div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4 font-weight-bold">Email:</div>
                            <div class="col-md-8"><?php echo htmlspecialchars($admin['email']); ?></div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4 font-weight-bold">Role:</div>
                            <div class="col-md-8">
                                <?php if ($admin['role'] === 'superadmin'): ?>
                                    <span class="badge badge-warning">Superadministrator</span>
                                <?php else: ?>
                                    <span class="badge badge-primary">Administrator</span>
                            <?php endif; ?>
                            </div>
                        </div>
                        </div>
                        
                <form action="<?= url('/superadmin/update-password/' . $admin['id']) ?>" method="POST">
                        <div class="form-group">
                            <label for="password">New Password *</label>
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
                            <label for="password_confirm">Confirm New Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="8">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <?php if (isset($_SESSION['errors']['password_confirm'])): ?>
                                <div class="small text-danger"><?php echo $_SESSION['errors']['password_confirm']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="force_reset" name="force_reset" value="1">
                                <label class="custom-control-label" for="force_reset">
                                    Force password change on next login
                                </label>
                                <small class="form-text text-muted">If checked, the administrator will be required to change their password when they next log in.</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="unlock_account" name="unlock_account" value="1" <?php echo (int)$admin['is_locked'] === 1 ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="unlock_account">
                                    Unlock account (if locked)
                                </label>
                                <small class="form-text text-muted">This will unlock the account if it has been locked due to failed login attempts.</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="1" checked>
                                <label class="custom-control-label" for="send_email">
                                    Send email notification with new password
                            </label>
                                <small class="form-text text-muted">An email will be sent to the administrator with their new password.</small>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="/superadmin/manage-admins" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle fa-fw"></i> Password Guidelines</h6>
                </div>
                <div class="card-body">
                    <p class="small">A strong password should:</p>
                    <ul class="small">
                        <li>Be at least 8 characters long</li>
                        <li>Include at least one uppercase letter</li>
                        <li>Include at least one lowercase letter</li>
                        <li>Include at least one number</li>
                        <li>Include at least one special character</li>
                        <li>Not be based on easily guessable information</li>
                    </ul>
                    
                    <div class="mt-3">
                        <button id="generatePassword" class="btn btn-sm btn-success btn-block">
                            <i class="fas fa-random mr-1"></i> Generate Strong Password
                        </button>
                    </div>
                    
                    <div class="card bg-light mt-3">
                        <div class="card-body py-2">
                            <div class="small font-weight-bold">Password strength:</div>
                            <div class="progress mt-1 mb-1">
                                <div id="passwordStrength" class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div id="passwordFeedback" class="small text-muted">Enter a password</div>
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
        togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordConfirm = document.getElementById('password_confirm');
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        toggleConfirmPassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Generate random password
    const generatePasswordBtn = document.getElementById('generatePassword');
    
    generatePasswordBtn.addEventListener('click', function() {
        const generatedPassword = generatePassword(12);
        password.value = generatedPassword;
        passwordConfirm.value = generatedPassword;
        
        // Update strength meter
        updatePasswordStrength(generatedPassword);
    });
    
    // Password strength meter
    password.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    
    function updatePasswordStrength(pwd) {
        const strength = calculatePasswordStrength(pwd);
        const strengthBar = document.getElementById('passwordStrength');
        const feedback = document.getElementById('passwordFeedback');
        
        // Update progress bar
        strengthBar.style.width = strength + '%';
        strengthBar.setAttribute('aria-valuenow', strength);
        
        // Update color and feedback
        if (strength < 25) {
            strengthBar.className = 'progress-bar bg-danger';
            feedback.textContent = 'Very weak';
        } else if (strength < 50) {
            strengthBar.className = 'progress-bar bg-warning';
            feedback.textContent = 'Weak';
        } else if (strength < 75) {
            strengthBar.className = 'progress-bar bg-info';
            feedback.textContent = 'Moderate';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            feedback.textContent = 'Strong';
        }
    }
    
    function calculatePasswordStrength(password) {
        if (!password) return 0;
        
        let strength = 0;
        
        // Length contribution (up to 25 points)
        strength += Math.min(25, Math.floor(password.length * 2.5));
        
        // Complexity contribution
        if (/[a-z]/.test(password)) strength += 10;  // lowercase
        if (/[A-Z]/.test(password)) strength += 10;  // uppercase
        if (/[0-9]/.test(password)) strength += 10;  // numbers
        if (/[^a-zA-Z0-9]/.test(password)) strength += 15;  // special chars
        
        // Variety contribution
        const uniqueChars = new Set(password.split('')).size;
        strength += Math.min(20, uniqueChars * 2);
        
        // Return capped at 100
        return Math.min(100, strength);
    }
    
    function generatePassword(length = 12) {
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const numbers = '0123456789';
        const special = '!@#$%^&*()-_=+';
        const allChars = lowercase + uppercase + numbers + special;
        
        let password = '';
        
        // Ensure we have at least one of each type
        password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
        password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
        password += numbers.charAt(Math.floor(Math.random() * numbers.length));
        password += special.charAt(Math.floor(Math.random() * special.length));
        
        // Fill the rest randomly
        for (let i = 4; i < length; i++) {
            password += allChars.charAt(Math.floor(Math.random() * allChars.length));
        }
        
        // Shuffle the password
        return password.split('').sort(() => 0.5 - Math.random()).join('');
    }
});
</script>

<?php 
// Clear session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['form_data']);
?> 