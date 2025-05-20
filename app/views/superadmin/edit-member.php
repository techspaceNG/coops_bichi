<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Member</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/members'); ?>">Members Management</a></li>
        <li class="breadcrumb-item active">Edit Member</li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <?php if (isset($member) && !empty($member)): ?>
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-edit me-1"></i>
                    Edit Member Information
                </div>
                <div class="card-body">
                    <form action="<?php echo url('/superadmin/edit-member/' . $member['id']); ?>" method="POST" id="editMemberForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="coop_no" class="form-label">Cooperative Number</label>
                                <input type="text" id="coop_no" name="coop_no" class="form-control" value="<?php echo htmlspecialchars($member['coop_no'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ti_number" class="form-label">TI Number</label>
                                <input type="text" id="ti_number" name="ti_number" class="form-control" value="<?php echo htmlspecialchars($member['ti_number'] ?? ''); ?>">
                                <div class="form-text">Treasury Integrated Number issued by FCET Bichi</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">Select Department</option>
                                    <?php if (isset($departments) && !empty($departments)): ?>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" <?php echo ($member['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($dept['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" name="is_active" id="statusActive" value="1" <?php echo $member['is_active'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="statusActive">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="statusInactive" value="0" <?php echo !$member['is_active'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="statusInactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($member['address'] ?? ''); ?></textarea>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo url('/superadmin/view-member/' . $member['id']); ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Member Information
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-secondary bg-opacity-25 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        </div>
                        <h4><?php echo htmlspecialchars($member['name']); ?></h4>
                        <p class="text-muted mb-0">COOPS No: <?php echo htmlspecialchars($member['coop_no']); ?></p>
                        <p class="badge <?php echo $member['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                        </p>
                    </div>
                    
                    <hr>
                    
                    <p><strong>Joined Date:</strong> <?php echo date('F j, Y', strtotime($member['created_at'])); ?></p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i> Editing this member will update their information across all systems.
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i> Changing a member's status to inactive will prevent them from accessing the system.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-1"></i> Member not found or has been deleted.
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('editMemberForm');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const coopNo = document.getElementById('coop_no').value.trim();
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        // Simple validation
        if (coopNo === '') {
            isValid = false;
            alert('Please enter COOPS No.');
        } else if (name === '') {
            isValid = false;
            alert('Please enter Full Name.');
        } else if (email === '') {
            isValid = false;
            alert('Please enter Email Address.');
        } else if (!validateEmail(email)) {
            isValid = false;
            alert('Please enter a valid Email Address.');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Email validation helper
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script> 