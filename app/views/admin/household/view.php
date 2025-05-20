<?php
// Admin Household Application View
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Household Purchase Application</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/Coops_Bichi/public/admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/Coops_Bichi/public/admin/household">Household Purchases</a></li>
        <li class="breadcrumb-item"><a href="/Coops_Bichi/public/admin/household/applications">Applications</a></li>
        <li class="breadcrumb-item active">View Application</li>
    </ol>
    
    <div class="row">
        <!-- Application Details -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-alt me-1"></i>
                    Application Details
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Item Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Item Name:</strong>
                                    <p><?= htmlspecialchars($application['item_name']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Amount:</strong>
                                    <p>₦<?= number_format($application['household_amount'], 2) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>IP Figure:</strong>
                                    <p>₦<?= number_format($application['ip_figure'], 2) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    <p>
                                        <span class="badge bg-<?= $application['status'] === 'approved' ? 'success' : ($application['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($application['status']) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($application['vendor_details'])): ?>
                    <div class="mb-3">
                        <h5>Vendor Information</h5>
                        <p><?= nl2br(htmlspecialchars($application['vendor_details'])) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($application['bank_name']) || !empty($application['account_number'])): ?>
                    <div class="mb-3">
                        <h5>Payment Information</h5>
                        <div class="row">
                            <?php if (!empty($application['bank_name'])): ?>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Bank Name:</strong>
                                    <p><?= htmlspecialchars($application['bank_name']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($application['account_number'])): ?>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Account Number:</strong>
                                    <p><?= htmlspecialchars($application['account_number']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($application['account_name'])): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Account Name:</strong>
                                    <p><?= htmlspecialchars($application['account_name']) ?></p>
                                </div>
                            </div>
                            
                            <?php if (!empty($application['account_type'])): ?>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Account Type:</strong>
                                    <p><?= htmlspecialchars($application['account_type']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <h5>Application Timeline</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Application Date:</strong>
                                    <p><?= date('Y-m-d', strtotime($application['created_at'])) ?></p>
                                </div>
                            </div>
                            
                            <?php if (!empty($application['approval_date'])): ?>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Approval Date:</strong>
                                    <p><?= date('Y-m-d', strtotime($application['approval_date'])) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($application['comment'])): ?>
                    <div class="mb-3">
                        <h5>Comments</h5>
                        <p><?= nl2br(htmlspecialchars($application['comment'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Member Information -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Member Information
                </div>
                <div class="card-body">
                    <?php if (isset($member) && $member): ?>
                        <div class="text-center mb-3">
                            <?php if (!empty($member->profile_image)): ?>
                                <img src="/Coops_Bichi/public/uploads/profiles/<?= htmlspecialchars($member->profile_image) ?>" class="rounded-circle img-fluid" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <img src="/Coops_Bichi/public/assets/img/default-profile.png" class="rounded-circle img-fluid" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Name:</strong>
                            <p><?= htmlspecialchars($member->first_name . ' ' . $member->last_name) ?></p>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Coop Number:</strong>
                            <p><?= htmlspecialchars($member->coop_no) ?></p>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Email:</strong>
                            <p><?= htmlspecialchars($member->email) ?></p>
                        </div>
                        
                        <?php if (!empty($member->phone)): ?>
                        <div class="mb-2">
                            <strong>Phone:</strong>
                            <p><?= htmlspecialchars($member->phone) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($member->department)): ?>
                        <div class="mb-2">
                            <strong>Department:</strong>
                            <p><?= htmlspecialchars($member->department) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <a href="/Coops_Bichi/public/admin/members/view/<?= $member->id ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-user"></i> View Full Profile
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Member information not available
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Event handlers removed - admin view-only role
    });
</script> 