<?php 
// Set current page for active menu highlighting
$current_page = 'profile';
// Set page title
$page_title = 'SuperAdmin Profile';
?>

<!-- Page Content -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
    <a href="<?= url('/superadmin/dashboard') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Dashboard
    </a>
</div>

<!-- Alerts -->
<?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>

<div class="row">
    <div class="col-lg-8">
        <!-- Profile Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user me-1"></i> Profile Information</h6>
            </div>
            <div class="card-body">
                <form action="<?= url('/superadmin/profile/update') ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($admin['username']) ?>" disabled>
                        <div class="form-text">Your username cannot be changed.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="<?= htmlspecialchars($admin['name']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="<?= htmlspecialchars($admin['email']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="<?= htmlspecialchars($admin['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Profile Picture -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-camera me-1"></i> Profile Picture</h6>
            </div>
            <div class="card-body text-center">
                <img src="<?= !empty($admin['profile_image']) ? htmlspecialchars($admin['profile_image']) : url('/profile.png') ?>" 
                     alt="Profile" class="img-profile rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                
                <div class="small text-gray-600 mb-3">JPG or PNG no larger than 2 MB</div>
                
                <form action="<?= url('/superadmin/profile/upload-image') ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input class="form-control" type="file" id="profile_image" name="profile_image" accept="image/jpeg, image/png">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-upload me-1"></i> Upload New Picture
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Account Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle me-1"></i> Account Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-gray-600">Role</label>
                    <div class="fw-bold"><?= ucfirst(htmlspecialchars($admin['role'])) ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-gray-600">Account Created</label>
                    <div>
                        <?= date('F j, Y', strtotime($admin['created_at'])) ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-gray-600">Last Login</label>
                    <div>
                        <?= isset($admin['last_login']) ? date('F j, Y, g:i a', strtotime($admin['last_login'])) : 'Never' ?>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="<?= url('/superadmin/settings') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-cog me-1"></i> Account Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 