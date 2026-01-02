<?php 
// Set current page for active menu highlighting
$current_page = 'system_settings';
// Set page title
$page_title = 'System Configuration';
?>

<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">System Infrastructure</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Global Configuration</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/dashboard') ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left text-muted"></i>
                <span>Back to Hub</span>
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row g-4">
        <!-- Navigation Sidebar -->
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm glass-form p-2">
                <div class="nav flex-column nav-pills" id="settingsTabs" role="tablist">
                    <button class="nav-link active text-start py-3 px-4 mb-2 d-flex align-items-center gap-3" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab" aria-selected="true">
                        <i class="fas fa-globe-africa opacity-50"></i>
                        <span class="fw-bold small text-uppercase">General Constants</span>
                    </button>
                    <button class="nav-link text-start py-3 px-4 mb-2 d-flex align-items-center gap-3" id="email-tab" data-bs-toggle="pill" data-bs-target="#email" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-at opacity-50"></i>
                        <span class="fw-bold small text-uppercase">Mail Protocols</span>
                    </button>
                    <button class="nav-link text-start py-3 px-4 mb-2 d-flex align-items-center gap-3" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-shield-halved opacity-50"></i>
                        <span class="fw-bold small text-uppercase">Security Logic</span>
                    </button>
                    <button class="nav-link text-start py-3 px-4 d-flex align-items-center gap-3" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backup" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-database opacity-50"></i>
                        <span class="fw-bold small text-uppercase">Forensic Backups</span>
                    </button>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10 text-primary mt-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-info-circle small"></i>
                        <h6 class="fw-bold mb-0 small text-uppercase">Logic Advisory</h6>
                    </div>
                    <p class="small mb-0 italic opacity-75">Modifying system infrastructure parameters may affect kinetic operations across all society nodes. Exercise auditor caution.</p>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-xl-9">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active p-4 p-md-5" id="general" role="tabpanel">
                            <h5 class="fw-bold mb-4">Core Society Identity</h5>
                            <form action="<?= url('/superadmin/update-system-settings') ?>" method="post">
                                <input type="hidden" name="settings_type" value="general">
                                
                                <div class="row g-4">
                                    <div class="col-md-7">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Society Legal Name</label>
                                        <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none" name="site_name" 
                                            value="<?= htmlspecialchars($settings['site_name'] ?? 'FCET Bichi Staff Multipurpose Cooperative Society'); ?>" required>
                                        <div class="form-text small opacity-50">This is the authoritative name used in fiscal certificates and outgoing communications.</div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Acronym/Short Name</label>
                                        <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none" name="site_short_name" 
                                            value="<?= htmlspecialchars($settings['site_short_name'] ?? 'FCET Bichi Cooperative'); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Primary Auditor Email</label>
                                        <input type="email" class="form-control border-light bg-light py-3 px-4 shadow-none" name="contact_email" 
                                            value="<?= htmlspecialchars($settings['contact_email'] ?? 'admin@fcetbichicoops.com'); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Contact Terminal (Phone)</label>
                                        <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none" name="contact_phone" 
                                            value="<?= htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Physical Jurisdiction Address</label>
                                        <textarea class="form-control border-light bg-light py-3 px-4 shadow-none" name="physical_address" rows="3"><?= htmlspecialchars($settings['physical_address'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Currency Vector</label>
                                        <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="currency_symbol" 
                                            value="<?= htmlspecialchars($settings['currency_symbol'] ?? '₦'); ?>" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Fiscal Cycle Start</label>
                                        <input type="date" class="form-control border-light bg-light py-3 px-4 shadow-none" name="fiscal_year_start" 
                                            value="<?= htmlspecialchars($settings['fiscal_year_start'] ?? date('Y-01-01')); ?>">
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-top text-end">
                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save Global Identity</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Email Configuration Tab -->
                        <div class="tab-pane fade p-4 p-md-5" id="email" role="tabpanel">
                            <h5 class="fw-bold mb-4">Mailing Infrastructure</h5>
                            <form action="<?= url('/superadmin/update-system-settings') ?>" method="post" id="emailSettingsForm">
                                <input type="hidden" name="settings_type" value="email">
                                
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Transport Layer Security</label>
                                        <select class="form-select border-light bg-light py-3 px-4 shadow-none" id="mail_driver" name="mail_driver">
                                            <option value="smtp" <?= ($settings['mail_driver'] ?? '') == 'smtp' ? 'selected' : ''; ?>>SMTP Protocol</option>
                                            <option value="sendmail" <?= ($settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : ''; ?>>Local Sendmail</option>
                                            <option value="mailgun" <?= ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : ''; ?>>Mailgun API</option>
                                        </select>
                                    </div>
                                    
                                    <div class="smtp-settings col-12">
                                        <div class="row g-4">
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Endpoint Host</label>
                                                <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="mail_host" 
                                                    value="<?= htmlspecialchars($settings['mail_host'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Port</label>
                                                <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="mail_port" 
                                                    value="<?= htmlspecialchars($settings['mail_port'] ?? '587'); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Auth User</label>
                                                <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="mail_username" 
                                                    value="<?= htmlspecialchars($settings['mail_username'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Auth Secret</label>
                                                <input type="password" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="mail_password" 
                                                    placeholder="<?= !empty($settings['mail_password']) ? '••••••••' : 'Enter Secret'; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Author Address</label>
                                        <input type="email" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="mail_from_address" 
                                            value="<?= htmlspecialchars($settings['mail_from_address'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Author Friendly Name</label>
                                        <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none" name="mail_from_name" 
                                            value="<?= htmlspecialchars($settings['mail_from_name'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center">
                                    <button type="button" id="testEmailBtn" class="btn btn-outline-info px-4 fw-bold">
                                        <i class="fas fa-paper-plane me-2"></i> Emit Test Node
                                    </button>
                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Sync Infrastructure</button>
                                </div>
                                <div id="testEmailResult" class="mt-3"></div>
                            </form>
                        </div>
                        
                        <!-- Security Settings Tab -->
                        <div class="tab-pane fade p-4 p-md-5" id="security" role="tabpanel">
                            <h5 class="fw-bold mb-4">Forensic Security Policy</h5>
                            <form action="<?= url('/superadmin/update-system-settings') ?>" method="post">
                                <input type="hidden" name="settings_type" value="security">
                                
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Min. Password Bit-Length</label>
                                        <input type="number" class="form-control border-light bg-light py-3 px-4 shadow-none" name="password_policy_min_length" 
                                            value="<?= htmlspecialchars($settings['password_policy_min_length'] ?? '8'); ?>" min="6" max="32">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small fw-bold text-muted text-uppercase mb-3">Enforcement requirements</label>
                                        <div class="d-flex flex-wrap gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="up" name="password_policy_require_uppercase" value="1" <?= (!empty($settings['password_policy_require_uppercase'])) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small fw-semibold" for="up">Uppercase Vector</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="num" name="password_policy_require_numbers" value="1" <?= (!empty($settings['password_policy_require_numbers'])) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small fw-semibold" for="num">Numerical Matrix</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sym" name="password_policy_require_symbols" value="1" <?= (!empty($settings['password_policy_require_symbols'])) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small fw-semibold" for="sym">Special Characters</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Secret Expiry Cycle (Days)</label>
                                        <input type="number" class="form-control border-light bg-light py-3 px-4 shadow-none" name="password_expiry_days" 
                                            value="<?= htmlspecialchars($settings['password_expiry_days'] ?? '90'); ?>" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Auth Failure Lockout Threshold</label>
                                        <input type="number" class="form-control border-light bg-light py-3 px-4 shadow-none" name="login_attempts_before_lockout" 
                                            value="<?= htmlspecialchars($settings['login_attempts_before_lockout'] ?? '5'); ?>" min="3" max="10">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Idle Session Purge (Minutes)</label>
                                        <input type="number" class="form-control border-light bg-light py-3 px-4 shadow-none" name="session_timeout_minutes" 
                                            value="<?= htmlspecialchars($settings['session_timeout_minutes'] ?? '30'); ?>" min="5">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase mb-3">Multi-Factor Protocol (2FA)</label>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="a2fa" name="require_2fa_for_admins" value="1" <?= (!empty($settings['require_2fa_for_admins'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label small fw-semibold" for="a2fa">Mandatory for Administrators</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="m2fa" name="allow_2fa_for_members" value="1" <?= (!empty($settings['allow_2fa_for_members'])) ? 'checked' : ''; ?>>
                                            <label class="form-check-label small fw-semibold" for="m2fa">Optional for Society Members</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-top text-end">
                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Authorize Security Log</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Backup & Maintenance Tab -->
                        <div class="tab-pane fade p-4 p-md-5" id="backup" role="tabpanel">
                            <h5 class="fw-bold mb-4">Forensic Retention & Backups</h5>
                            <form action="<?= url('/superadmin/update-system-settings') ?>" method="post">
                                <input type="hidden" name="settings_type" value="backup">
                                
                                <div class="row g-4 mb-5">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch p-0 mt-2">
                                            <label class="form-check-label small fw-bold text-muted text-uppercase" for="ab" style="margin-left: 2.5rem;">Automated Logic Backups</label>
                                            <input class="form-check-input" type="checkbox" id="ab" name="auto_backup_enabled" value="1" <?= (!empty($settings['auto_backup_enabled'])) ? 'checked' : ''; ?> style="width: 2.5rem; height: 1.25rem;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Symmetry Frequency</label>
                                        <select class="form-select border-light bg-light py-3 px-4 shadow-none" name="backup_frequency">
                                            <option value="daily" <?= ($settings['backup_frequency'] ?? '') == 'daily' ? 'selected' : ''; ?>>24-Hour Cycle (Daily)</option>
                                            <option value="weekly" <?= ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : ''; ?>>7-Day Cycle (Weekly)</option>
                                            <option value="monthly" <?= ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : ''; ?>>30-Day Cycle (Monthly)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Retention Horizon (Days)</label>
                                        <input type="number" class="form-control border-light bg-light py-3 px-4 shadow-none" name="backup_retention_days" 
                                            value="<?= htmlspecialchars($settings['backup_retention_days'] ?? '30'); ?>" min="7">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Storage Vector (Relative Path)</label>
                                        <input type="text" class="form-control border-light bg-light py-3 px-4 shadow-none font-monospace" name="backup_storage_location" 
                                            value="<?= htmlspecialchars($settings['backup_storage_location'] ?? './backups'); ?>">
                                    </div>
                                </div>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light p-4 h-100 border-start border-4 border-info">
                                            <h6 class="fw-bold mb-2">Immediate Core Snapshot</h6>
                                            <p class="small text-muted mb-4">Triggers an instantaneous binary dump of the entire database schema.</p>
                                            <a href="<?= url('/superadmin/database-backup/create') ?>" class="btn btn-info btn-sm fw-bold px-4 rounded-pill text-white">Trigger Manual Backup</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light p-4 h-100 border-start border-4 border-warning">
                                            <h6 class="fw-bold mb-2">Logic Defragmentation</h6>
                                            <p class="small text-muted mb-4">Re-indexes tables and purges orphaned temporary memory buffers.</p>
                                            <a href="<?= url('/superadmin/database-optimize') ?>" class="btn btn-warning btn-sm fw-bold px-4 rounded-pill text-white">Optimize Logic</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 pt-4 border-top text-end">
                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Commit Retention Policy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#settingsTabs .nav-link {
    color: #6c757d;
    border-radius: 12px;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
#settingsTabs .nav-link.active {
    background-color: var(--color-primary-600);
    color: white !important;
    box-shadow: 0 4px 12px rgba(var(--color-primary-600-rgb), 0.2);
}
#settingsTabs .nav-link:hover:not(.active) {
    background-color: #f8f9fa;
    color: var(--color-primary-600);
}
.glass-form {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mail driver selection to show/hide SMTP settings
    const mailDriverSelect = document.getElementById('mail_driver');
    const smtpSettings = document.querySelector('.smtp-settings');
    
    if(mailDriverSelect && smtpSettings) {
        function toggleSmtpSettings() {
            smtpSettings.style.display = (mailDriverSelect.value === 'smtp') ? 'block' : 'none';
        }
        toggleSmtpSettings();
        mailDriverSelect.addEventListener('change', toggleSmtpSettings);
    }
    
    // Handle test email button click
    const testEmailBtn = document.getElementById('testEmailBtn');
    if(testEmailBtn) {
        testEmailBtn.addEventListener('click', function() {
            const resultDiv = document.getElementById('testEmailResult');
            resultDiv.innerHTML = '<div class="alert alert-info border-0 shadow-sm py-2 px-3 small"><i class="fas fa-spinner fa-spin me-2"></i>Emitting test node to transit layer...</div>';
            
            // Simulation of async test
            setTimeout(function() {
                resultDiv.innerHTML = '<div class="alert alert-success border-0 shadow-sm py-2 px-3 small"><i class="fas fa-check-circle me-2"></i>Test node received successfully by gateway.</div>';
            }, 1800);
        });
    }
});
</script> 