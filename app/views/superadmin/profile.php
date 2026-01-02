<?php 
// Set current page for active menu highlighting
$current_page = 'profile';
// Set page title
$page_title = 'SuperAdmin Profile';
?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">My Profile</h4>
            <p class="text-muted small mb-0">Manage your account details and security settings</p>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/dashboard') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> 
                <span>Back to Dashboard</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Profile Information -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">Profile Information</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/profile/update') ?>" method="POST">
                        <div class="row g-3">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold small text-muted text-uppercase">Username</label>
                                <div class="bg-light p-3 rounded border-light text-muted fw-bold d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-at me-2 opacity-50"></i><?= htmlspecialchars($admin['username']) ?></span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill fw-normal" style="font-size: 0.65rem;">Locked</span>
                                </div>
                                <div class="form-text small opacity-75 mt-2">Username is used for system logs and cannot be modified.</div>
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="name" class="form-label fw-semibold small text-muted text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control border-light bg-light" id="name" name="name" required 
                                       value="<?= htmlspecialchars($admin['name']) ?>">
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="email" class="form-label fw-semibold small text-muted text-uppercase">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control border-light bg-light" id="email" name="email" required 
                                       value="<?= htmlspecialchars($admin['email']) ?>">
                            </div>
                            
                            <div class="col-md-12 mt-4">
                                <label for="phone" class="form-label fw-semibold small text-muted text-uppercase">Phone Number</label>
                                <input type="text" class="form-control border-light bg-light" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($admin['phone'] ?? '') ?>" placeholder="e.g. +234 ...">
                            </div>
                        </div>
                        
                        <hr class="my-5 opacity-50">
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Profile Picture -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 fw-bold">Profile Identity</div>
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <img src="<?= !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : url('/profile.png') ?>" 
                             alt="Profile" class="rounded-circle shadow-sm border border-4 border-light" style="width: 140px; height: 140px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 bg-primary text-white p-2 rounded-circle border border-4 border-white shadow-sm" style="line-height: 1;">
                            <i class="fas fa-camera fa-sm"></i>
                        </span>
                    </div>
                    
                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($admin['name']) ?></h6>
                    <p class="text-muted small mb-4"><?= ucfirst(htmlspecialchars($admin['role'])) ?></p>
                    
                    <form action="<?= url('/superadmin/profile/upload-image') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input class="form-control form-control-sm border-light bg-light" type="file" id="profile_image" name="profile_image" accept="image/jpeg, image/png">
                            <div class="small text-muted mt-2">Recommended: Square, JPG/PNG < 2MB</div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                             Upload Image
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 fw-bold">Access Insights</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Account Tier</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3"><?= ucfirst(htmlspecialchars($admin['role'])) ?></span>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Registered On</span>
                            <span class="fw-bold"><?= date('M j, Y', strtotime($admin['created_at'])) ?></span>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Last Activity</span>
                            <span class="fw-bold text-success">
                                <?= isset($admin['last_login']) ? date('M j, Y, H:i', strtotime($admin['last_login'])) : 'New Account' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4">
                    <a href="<?= url('/superadmin/settings') ?>" class="btn btn-light w-100 text-muted small py-2">
                        <i class="fas fa-shield-alt me-2 text-primary opacity-50"></i> Account Security Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 
