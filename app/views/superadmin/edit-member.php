<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Modify Member Metadata</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/members') ?>" class="text-decoration-none text-muted">Members Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Edit Configuration</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/view-member/' . ($member['id'] ?? '')) ?>" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="fas fa-id-card"></i> 
                <span>View Profile</span>
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <?php if (isset($member) && !empty($member)): ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-edit text-primary"></i>
                    <h6 class="fw-bold mb-0">Primary Identity & Records</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/edit-member/' . $member['id']) ?>" method="POST" class="needs-validation" novalidate id="editMemberForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="coop_no" class="form-label fw-semibold small text-muted text-uppercase">COOPS Registry ID <span class="text-danger">*</span></label>
                                <input type="text" id="coop_no" name="coop_no" class="form-control border-light bg-light" value="<?= htmlspecialchars($member['coop_no'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ti_number" class="form-label fw-semibold small text-muted text-uppercase">TI Number Reference</label>
                                <input type="text" id="ti_number" name="ti_number" class="form-control border-light bg-light" value="<?= htmlspecialchars($member['ti_number'] ?? '') ?>">
                                <div class="form-text small opacity-75">Treasury Integrated ID (FCET Bichi)</div>
                            </div>
                            
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold small text-muted text-uppercase">Legal Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control border-light bg-light" value="<?= htmlspecialchars($member['name'] ?? '') ?>" required>
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Primary Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control border-light bg-light" id="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <label for="phone" class="form-label fw-semibold small text-muted text-uppercase">Contact Line</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control border-light bg-light" id="phone" name="phone" value="<?= htmlspecialchars($member['phone'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-8 mt-4">
                                <label for="department" class="form-label fw-semibold small text-muted text-uppercase">Departmental Attachment</label>
                                <select class="form-select border-light bg-light" id="department" name="department">
                                    <option value="">Select Department</option>
                                    <?php if (isset($departments) && !empty($departments)): ?>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?= $dept['id'] ?>" <?= ($member['department_id'] == $dept['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dept['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-4 mt-4 d-flex align-items-end">
                                <div class="p-3 bg-light border rounded-3 w-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="is_active" id="statusActive" value="1" <?= $member['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label small fw-bold text-success" for="statusActive">ACTIVE</label>
                                        </div>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="is_active" id="statusInactive" value="0" <?= !$member['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label small fw-bold text-danger" for="statusInactive">INACTIVE</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <label for="address" class="form-label fw-semibold small text-muted text-uppercase">Known Residential Address</label>
                                <textarea class="form-control border-light bg-light" id="address" name="address" rows="3"><?= htmlspecialchars($member['address'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-5 pt-3 border-top d-flex justify-content-between">
                            <a href="<?= url('/superadmin/members') ?>" class="btn btn-light px-4">Discard Changes</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Update Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 shadow-sm d-flex align-items-center justify-content-center border border-4 border-light" style="width: 100px; height: 100px;">
                            <span class="h1 mb-0 fw-bold text-primary"><?= strtoupper(substr($member['name'], 0, 1)) ?></span>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-2 rounded-circle border border-4 border-white shadow-sm <?= $member['is_active'] ? 'bg-success' : 'bg-danger' ?>" style="width: 24px; height: 24px;"></span>
                    </div>
                    
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($member['name']) ?></h5>
                    <p class="text-muted small mb-3">System Identity Profile</p>
                    
                    <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Registry No:</span>
                            <span class="small fw-bold text-primary"><?= htmlspecialchars($member['coop_no']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">Joined On:</span>
                            <span class="small fw-bold"><?= date('M d, Y', strtotime($member['created_at'])) ?></span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <div class="alert alert-info border-0 small mb-0 d-flex gap-3 align-items-center">
                            <i class="fas fa-info-circle fa-lg text-primary opacity-50"></i>
                            <div class="text-start">Updates synchronized across all system modules immediately.</div>
                        </div>
                        <?php if($member['is_active']): ?>
                            <div class="alert alert-warning border-0 small mb-0 d-flex gap-3 align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg text-warning opacity-50"></i>
                                <div class="text-start">Deactivating results in immediate portal access revocation.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Historical Context (Optional Visual) -->
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 small text-primary text-uppercase">Record Consistency</h6>
                    <p class="text-muted small mb-0">
                        Maintaining accurate member metadata ensures integrity in financial disbursements and organizational reporting. 
                        Verify <strong>TI Numbers</strong> for payroll alignment.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-4 d-inline-block mb-4">
                <i class="fas fa-exclamation-triangle fa-3x"></i>
            </div>
            <h4 class="fw-bold">Member Record Missing</h4>
            <p class="text-muted mb-4 small">The requested member file could not be retrieved from the central registry.</p>
            <a href="<?= url('/superadmin/members') ?>" class="btn btn-primary shadow-sm">Return to Directory</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editMemberForm');
    if(form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
});
</script> 
