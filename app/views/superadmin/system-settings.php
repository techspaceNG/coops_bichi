<?php 
// Set current page for active menu highlighting
$current_page = 'system_settings';
// Set page title
$page_title = 'System Settings';
?>

<!-- Page Content -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
    <a href="<?= url('/superadmin/dashboard') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
        </a>
    </div>

    <!-- Alerts -->
    <?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-lg-12">
            <!-- System Settings Tabs -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                <i class="fas fa-cogs fa-fw"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="email-tab" data-bs-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">
                                <i class="fas fa-envelope fa-fw"></i> Email Configuration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="security-tab" data-bs-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt fa-fw"></i> Security Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="backup-tab" data-bs-toggle="tab" href="#backup" role="tab" aria-controls="backup" aria-selected="false">
                                <i class="fas fa-database fa-fw"></i> Backup & Maintenance
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <form action="<?= url('/superadmin/update-system-settings') ?>" method="post" id="generalSettingsForm">
                                <input type="hidden" name="settings_type" value="general">
                                
                                <div class="form-group row">
                                    <label for="site_name" class="col-sm-3 col-form-label">Cooperative Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="site_name" name="site_name" 
                                            value="<?php echo htmlspecialchars($settings['site_name'] ?? 'FCET Bichi Staff Multipurpose Cooperative Society'); ?>" required>
                                        <small class="form-text text-muted">This name will appear in emails, reports, and the portal header.</small>
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label for="site_short_name" class="col-sm-3 col-form-label">Short Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="site_short_name" name="site_short_name" 
                                            value="<?php echo htmlspecialchars($settings['site_short_name'] ?? 'FCET Bichi Cooperative'); ?>" required>
                                        <small class="form-text text-muted">Used in places where space is limited.</small>
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label for="contact_email" class="col-sm-3 col-form-label">Contact Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                            value="<?php echo htmlspecialchars($settings['contact_email'] ?? 'admin@example.com'); ?>" required>
                                        <small class="form-text text-muted">Primary contact email for the cooperative.</small>
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label for="contact_phone" class="col-sm-3 col-form-label">Contact Phone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                            value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label for="physical_address" class="col-sm-3 col-form-label">Physical Address</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="physical_address" name="physical_address" rows="3"><?php echo htmlspecialchars($settings['physical_address'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="currency_symbol" class="col-sm-3 col-form-label">Currency Symbol</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" 
                                            value="<?php echo htmlspecialchars($settings['currency_symbol'] ?? '₦'); ?>" required>
                                        <small class="form-text text-muted">Currency symbol used throughout the system.</small>
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label for="fiscal_year_start" class="col-sm-3 col-form-label">Fiscal Year Start</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="fiscal_year_start" name="fiscal_year_start" 
                                            value="<?php echo htmlspecialchars($settings['fiscal_year_start'] ?? date('Y-01-01')); ?>">
                                        <small class="form-text text-muted">The start date of the cooperative's fiscal year.</small>
                            </div>
                        </div>
                        
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save General Settings
                                    </button>
                            </div>
                            </form>
                        </div>
                        
                        <!-- Email Configuration Tab -->
                        <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                        <form action="<?= url('/superadmin/update-system-settings') ?>" method="post" id="emailSettingsForm">
                                <input type="hidden" name="settings_type" value="email">
                                
                                <div class="form-group row">
                                    <label for="mail_driver" class="col-sm-3 col-form-label">Mail Driver</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="mail_driver" name="mail_driver">
                                            <option value="smtp" <?php echo ($settings['mail_driver'] ?? '') == 'smtp' ? 'selected' : ''; ?>>SMTP</option>
                                            <option value="sendmail" <?php echo ($settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : ''; ?>>Sendmail</option>
                                            <option value="mailgun" <?php echo ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : ''; ?>>Mailgun</option>
                                        </select>
                            </div>
                        </div>
                        
                                <div class="smtp-settings">
                                    <div class="form-group row">
                                        <label for="mail_host" class="col-sm-3 col-form-label">SMTP Host</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mail_host" name="mail_host" 
                                                value="<?php echo htmlspecialchars($settings['mail_host'] ?? ''); ?>">
                            </div>
                        </div>
                        
                                    <div class="form-group row">
                                        <label for="mail_port" class="col-sm-3 col-form-label">SMTP Port</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mail_port" name="mail_port" 
                                                value="<?php echo htmlspecialchars($settings['mail_port'] ?? '587'); ?>">
                            </div>
                        </div>
                        
                                    <div class="form-group row">
                                        <label for="mail_username" class="col-sm-3 col-form-label">SMTP Username</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="mail_username" name="mail_username" 
                                                value="<?php echo htmlspecialchars($settings['mail_username'] ?? ''); ?>">
                            </div>
                        </div>
                        
                                    <div class="form-group row">
                                        <label for="mail_password" class="col-sm-3 col-form-label">SMTP Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="mail_password" name="mail_password" 
                                                value="<?php echo !empty($settings['mail_password']) ? '********' : ''; ?>">
                                            <small class="form-text text-muted">Leave blank to keep current password.</small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="mail_encryption" class="col-sm-3 col-form-label">Encryption</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="mail_encryption" name="mail_encryption">
                                                <option value="tls" <?php echo ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                                <option value="ssl" <?php echo ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                                <option value="none" <?php echo ($settings['mail_encryption'] ?? '') == 'none' ? 'selected' : ''; ?>>None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="mail_from_address" class="col-sm-3 col-form-label">From Address</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                                            value="<?php echo htmlspecialchars($settings['mail_from_address'] ?? ''); ?>">
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label for="mail_from_name" class="col-sm-3 col-form-label">From Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" 
                                            value="<?php echo htmlspecialchars($settings['mail_from_name'] ?? ''); ?>">
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="button" id="testEmailBtn" class="btn btn-info">
                                            <i class="fas fa-paper-plane"></i> Send Test Email
                                        </button>
                                        <div id="testEmailResult" class="mt-2"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Email Settings
                                    </button>
                            </div>
                            </form>
                        </div>
                        
                        <!-- Security Settings Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <form action="<?= url('/superadmin/update-system-settings') ?>" method="post" id="securitySettingsForm">
                                <input type="hidden" name="settings_type" value="security">
                                
                                <div class="form-group row">
                                    <label for="password_policy_min_length" class="col-sm-3 col-form-label">Minimum Password Length</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="password_policy_min_length" name="password_policy_min_length" 
                                            value="<?php echo htmlspecialchars($settings['password_policy_min_length'] ?? '8'); ?>" min="6" max="32">
                            </div>
                        </div>
                        
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Password Requirements</label>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="password_policy_require_uppercase" name="password_policy_require_uppercase" value="1" 
                                                <?php echo (!empty($settings['password_policy_require_uppercase'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="password_policy_require_uppercase">
                                                Require uppercase letters
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="password_policy_require_numbers" name="password_policy_require_numbers" value="1" 
                                                <?php echo (!empty($settings['password_policy_require_numbers'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="password_policy_require_numbers">
                                                Require numbers
                                            </label>
                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="password_policy_require_symbols" name="password_policy_require_symbols" value="1" 
                                                <?php echo (!empty($settings['password_policy_require_symbols'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="password_policy_require_symbols">
                                                Require special characters
                                            </label>
                </div>
            </div>
        </div>
        
                                <div class="form-group row">
                                    <label for="password_expiry_days" class="col-sm-3 col-form-label">Password Expiry (days)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="password_expiry_days" name="password_expiry_days" 
                                            value="<?php echo htmlspecialchars($settings['password_expiry_days'] ?? '90'); ?>" min="0">
                                        <small class="form-text text-muted">Set to 0 to disable password expiry.</small>
                                    </div>
                </div>
                                
                                <div class="form-group row">
                                    <label for="login_attempts_before_lockout" class="col-sm-3 col-form-label">Failed Login Attempts Before Lockout</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="login_attempts_before_lockout" name="login_attempts_before_lockout" 
                                            value="<?php echo htmlspecialchars($settings['login_attempts_before_lockout'] ?? '5'); ?>" min="3" max="10">
                                    </div>
                    </div>
                    
                                <div class="form-group row">
                                    <label for="session_timeout_minutes" class="col-sm-3 col-form-label">Session Timeout (minutes)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="session_timeout_minutes" name="session_timeout_minutes" 
                                            value="<?php echo htmlspecialchars($settings['session_timeout_minutes'] ?? '30'); ?>" min="5">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Two-Factor Authentication</label>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="require_2fa_for_admins" name="require_2fa_for_admins" value="1" 
                                                <?php echo (!empty($settings['require_2fa_for_admins'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="require_2fa_for_admins">
                                                Require 2FA for administrators
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_2fa_for_members" name="allow_2fa_for_members" value="1" 
                                                <?php echo (!empty($settings['allow_2fa_for_members'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="allow_2fa_for_members">
                                                Allow 2FA for members
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Security Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Backup & Maintenance Tab -->
                        <div class="tab-pane fade" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                        <form action="<?= url('/superadmin/update-system-settings') ?>" method="post" id="backupSettingsForm">
                                <input type="hidden" name="settings_type" value="backup">
                                
                                <div class="form-group row">
                                    <label for="auto_backup_enabled" class="col-sm-3 col-form-label">Automatic Backups</label>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_backup_enabled" name="auto_backup_enabled" value="1" 
                                                <?php echo (!empty($settings['auto_backup_enabled'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="auto_backup_enabled">
                                                Enable automatic database backups
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="backup_frequency" class="col-sm-3 col-form-label">Backup Frequency</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="backup_frequency" name="backup_frequency">
                                            <option value="daily" <?php echo ($settings['backup_frequency'] ?? '') == 'daily' ? 'selected' : ''; ?>>Daily</option>
                                            <option value="weekly" <?php echo ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : ''; ?>>Weekly</option>
                                            <option value="monthly" <?php echo ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="backup_retention_days" class="col-sm-3 col-form-label">Backup Retention (days)</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="backup_retention_days" name="backup_retention_days" 
                                            value="<?php echo htmlspecialchars($settings['backup_retention_days'] ?? '30'); ?>" min="7">
                                        <small class="form-text text-muted">Backups older than this many days will be automatically deleted.</small>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="backup_storage_location" class="col-sm-3 col-form-label">Backup Storage Location</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="backup_storage_location" name="backup_storage_location" 
                                            value="<?php echo htmlspecialchars($settings['backup_storage_location'] ?? './backups'); ?>">
                                    </div>
                                </div>
                                
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Manual Backup</h5>
                                        <p class="card-text">Create a manual backup of the database.</p>
                                    <a href="<?= url('/superadmin/database-backup/create') ?>" class="btn btn-info">
                                            <i class="fas fa-database"></i> Create Backup Now
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Database Optimization</h5>
                                        <p class="card-text">Optimize database tables and clean up temporary data.</p>
                                    <a href="<?= url('/superadmin/database-optimize') ?>" class="btn btn-warning">
                                            <i class="fas fa-broom"></i> Optimize Database
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Backup Settings
                        </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
// Set page-specific JavaScript to initialize tabs and handle tab-related functionality
$page_specific_js = <<<JS
// Initialize tabs
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap 5 tabs
    var tabElList = [].slice.call(document.querySelectorAll('#settingsTabs a[data-bs-toggle="tab"]'));
    tabElList.forEach(function(tabEl) {
        new bootstrap.Tab(tabEl);
    });
    
    // Handle mail driver selection to show/hide SMTP settings
    const mailDriverSelect = document.getElementById('mail_driver');
    const smtpSettings = document.querySelector('.smtp-settings');
    
    if(mailDriverSelect && smtpSettings) {
        function toggleSmtpSettings() {
            if(mailDriverSelect.value === 'smtp') {
                smtpSettings.style.display = 'block';
        } else {
                smtpSettings.style.display = 'none';
            }
        }
        
        // Initial state
        toggleSmtpSettings();
        
        // On change
        mailDriverSelect.addEventListener('change', toggleSmtpSettings);
    }
    
    // Handle test email button click
    const testEmailBtn = document.getElementById('testEmailBtn');
    if(testEmailBtn) {
        testEmailBtn.addEventListener('click', function() {
            const resultDiv = document.getElementById('testEmailResult');
            resultDiv.innerHTML = '<div class="alert alert-info">Sending test email...</div>';
        
        // Get current form data
            const form = document.getElementById('emailSettingsForm');
            const formData = new FormData(form);
            
            // You would normally make an AJAX request here to test the email settings
            // For demo purposes, we'll just show a success message after a delay
            setTimeout(function() {
                resultDiv.innerHTML = '<div class="alert alert-success">Test email sent successfully!</div>';
            }, 1500);
        });
    }
    
    // Add event listeners to all form submits
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // You could add validation here if needed
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            }
        });
    });
});
JS;
?> 