<?php 
// Set current page for active menu highlighting
$current_page = 'database_backup';
// Set page title
$page_title = 'Database Backup & Restore';
?>

<!-- Page Content -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Database Backup & Restore</h1>
    <a href="<?= url('/superadmin/dashboard') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
    </a>
</div>

<!-- Alerts -->
<?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Backup Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-database me-1"></i> Database Backup</h6>
                </div>
                <div class="card-body">
                    <p>
                        Creating a backup will export all database tables to a SQL file, which you can use to restore
                        the system in case of data loss. Regular backups are recommended to prevent data loss.
                    </p>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Note:</strong> Backups are stored in the <code>/backups</code> directory on the server.
                        It's recommended to download these files periodically and store them in a secure location.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Manual Backup</h5>
                                    <p class="card-text small">Create a full database backup immediately.</p>
                                <form action="<?= url('/superadmin/perform-backup') ?>" method="POST">
                                        <button type="submit" class="btn btn-primary">Create Backup Now</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Automatic Backups</h5>
                                    <p class="card-text small">Configure automatic backup schedule.</p>
                                <form action="<?= url('/superadmin/update-settings') ?>" method="POST">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="auto_backup" name="settings[auto_backup]" value="1"
                                                  <?php echo (isset($settings['auto_backup']) && $settings['auto_backup'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="auto_backup">Enable automatic backups</label>
                                        </div>
                                        
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">Every</span>
                                            <input type="number" class="form-control" id="backup_frequency" name="settings[backup_frequency]"
                                                   value="<?php echo htmlspecialchars($settings['backup_frequency'] ?? '7'); ?>" min="1" max="30">
                                            <span class="input-group-text">days</span>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-outline-primary">Save Settings</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Existing Backups Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-1"></i> Existing Backups</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Backup File</th>
                                    <th>Size</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($backups)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No backups found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($backups as $backup): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($backup['filename']); ?></td>
                                            <td><?php echo $backup['size']; ?></td>
                                            <td><?php echo date('F j, Y, g:i a', strtotime($backup['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                <a href="<?= url('/superadmin/download-backup/' . $backup['filename']) ?>" class="btn btn-outline-success" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-outline-danger" title="Delete" 
                                                       data-bs-toggle="modal" data-bs-target="#deleteBackupModal"
                                                       data-filename="<?php echo htmlspecialchars($backup['filename']); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Restore Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-upload me-1"></i> Restore from Backup</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Warning:</strong> Restoring from a backup will overwrite all existing data in the database.
                        This action cannot be undone. Make sure to create a backup of your current data before proceeding.
                    </div>
                    
                <form action="<?= url('/superadmin/upload-backup') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="backupFile" class="form-label">Upload Backup File</label>
                            <input class="form-control" type="file" id="backupFile" name="backup_file" accept=".sql">
                            <div class="form-text">Select a SQL backup file to restore the database.</div>
                        </div>
                        <button type="submit" class="btn btn-danger">Upload & Restore</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Backup Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle me-1"></i> Backup Information</h6>
                </div>
                <div class="card-body">
                    <h6>Database Information</h6>
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Database Size:</strong></td>
                                <td><?php echo $dbStats['size_mb'] ?? '0'; ?> MB</td>
                            </tr>
                            <tr>
                                <td><strong>Total Tables:</strong></td>
                                <td><?php echo $dbStats['tables'] ?? '0'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Total Records:</strong></td>
                                <td><?php echo $dbStats['records'] ?? '0'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <hr>
                    
                    <h6>Backup Schedule</h6>
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Auto Backup:</strong></td>
                                <td>
                                    <?php if (isset($settings['auto_backup']) && $settings['auto_backup'] == 1): ?>
                                        <span class="badge bg-success">Enabled</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Disabled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Frequency:</strong></td>
                                <td>Every <?php echo $settings['backup_frequency'] ?? '7'; ?> days</td>
                            </tr>
                            <tr>
                                <td><strong>Last Backup:</strong></td>
                                <td>
                                    <?php 
                                    if (isset($settings['last_backup_date']) && $settings['last_backup_date'] !== 'Never'): 
                                        echo date('F j, Y, g:i a', strtotime($settings['last_backup_date']));
                                    else:
                                        echo '<span class="text-danger">Never</span>';
                                    endif;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Next Backup:</strong></td>
                                <td>
                                    <?php 
                                    if (isset($settings['last_backup_date']) && $settings['last_backup_date'] !== 'Never' && isset($settings['auto_backup']) && $settings['auto_backup'] == 1):
                                        $lastBackup = new DateTime($settings['last_backup_date']);
                                        $frequency = (int)($settings['backup_frequency'] ?? 7);
                                        $nextBackup = clone $lastBackup;
                                        $nextBackup->modify("+{$frequency} days");
                                        echo date('F j, Y', $nextBackup->getTimestamp());
                                    else:
                                        echo '<span class="text-muted">Not scheduled</span>';
                                    endif;
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <hr>
                    
                    <div class="alert alert-info small">
                        <h6 class="alert-heading">Backup Best Practices</h6>
                        <ul class="mb-0 ps-3">
                            <li>Create regular backups of your database.</li>
                            <li>Store backups in multiple secure locations.</li>
                            <li>Test backup restoration periodically.</li>
                            <li>Create backups before major system changes.</li>
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Backup Confirmation Modal -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1" aria-labelledby="deleteBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBackupModalLabel">Confirm Delete Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the backup file <span id="deleteFilename" class="fw-bold"></span>?</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?= url('/superadmin/delete-backup') ?>" method="POST">
                    <input type="hidden" name="filename" id="deleteBackupFilename">
                    <button type="submit" class="btn btn-danger">Delete Backup</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize delete modal
    var deleteModal = document.getElementById('deleteBackupModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var filename = button.getAttribute('data-filename');
            document.getElementById('deleteFilename').textContent = filename;
            document.getElementById('deleteBackupFilename').value = filename;
        });
    }
});
</script>