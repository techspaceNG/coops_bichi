<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Household Purchase Application Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/household'); ?>">Household Management</a></li>
        <li class="breadcrumb-item active">View Application #<?php echo htmlspecialchars($application['id']); ?></li>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Member Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold">Name:</label>
                        <p><?php echo htmlspecialchars($application['fullname']); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">COOPS No:</label>
                        <p><?php echo htmlspecialchars($application['coop_no'] ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Department:</label>
                        <p><?php echo !empty($member['department']) ? htmlspecialchars($member['department']) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Email:</label>
                        <p><?php echo !empty($member['email']) ? htmlspecialchars($member['email']) : 'Not provided'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Phone:</label>
                        <p><?php echo !empty($member['phone']) ? htmlspecialchars($member['phone']) : 'Not provided'; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Household Purchase Application Details
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Purchase Amount:</label>
                            <p class="text-primary fw-bold">₦<?php echo number_format($application['household_amount'], 2); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">IP Figure:</label>
                            <p>₦<?php echo isset($application['ip_figure']) ? number_format($application['ip_figure'], 2) : 'N/A'; ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Duration:</label>
                            <p><?php echo htmlspecialchars($application['purchase_duration'] ?? '12'); ?> months</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Application Date:</label>
                            <p><?php echo date('F j, Y', strtotime($application['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Status:</label>
                            <p>
                                <span class="badge 
                                    <?php 
                                    switch($application['status']) {
                                        case 'pending': echo 'bg-warning'; break;
                                        case 'approved': echo 'bg-success'; break;
                                        case 'rejected': echo 'bg-danger'; break;
                                        default: echo 'bg-secondary';
                                    }
                                    ?>">
                                    <?php echo ucfirst($application['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Bank Account Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <i class="fas fa-university me-1"></i>
                            Bank Account Information
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Bank Name:</label>
                                    <p><?php echo htmlspecialchars($application['bank_name'] ?? 'Not provided'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Account Number:</label>
                                    <p><?php echo htmlspecialchars($application['account_number'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Account Name:</label>
                                    <p><?php echo htmlspecialchars($application['account_name'] ?? 'Not provided'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Account Type:</label>
                                    <p><?php echo htmlspecialchars($application['account_type'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Item Name:</label>
                        <p><?php echo !empty($application['item_name']) ? nl2br(htmlspecialchars($application['item_name'])) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Comment/Notes:</label>
                        <p><?php echo !empty($application['comment']) ? nl2br(htmlspecialchars($application['comment'])) : 'No additional notes'; ?></p>
                    </div>
                    
                    <?php if (!empty($application['vendor_details'])): ?>
                    <div class="mb-3">
                        <label class="fw-bold">Vendor Details:</label>
                        <p><?php echo nl2br(htmlspecialchars($application['vendor_details'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo url('/superadmin/household'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        
                        <div>
                            <?php if ($application['status'] === 'pending'): ?>
                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#declineModal">
                                    <i class="fas fa-times me-1"></i> Decline
                                </button>
                            <?php endif; ?>
                            
                            <a href="javascript:void(0);" onclick="printApplicationDetails(<?php echo $application['id']; ?>);" class="btn btn-primary">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printApplicationDetails(id) {
    window.open(<?php echo json_encode(url('/superadmin/print-household/')); ?> + id + '?type=application', '_blank');
}
</script>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo url('/superadmin/approve-household-application/' . $application['id']); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="approveModalLabel">Approve Household Purchase Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to approve this household purchase application for ₦<?php echo number_format($application['household_amount'], 2); ?>?</p>
          <div class="mb-3">
            <label for="comment" class="form-label">Comment (Optional)</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Add any comments or notes regarding this approval"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Approve Application</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo url('/superadmin/decline-household-application/' . $application['id']); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="declineModalLabel">Decline Household Purchase Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to decline this household purchase application?</p>
          <div class="mb-3">
            <label for="comment" class="form-label">Reason for Decline (Required)</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Provide reason for declining this application" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Decline Application</button>
        </div>
      </form>
    </div>
  </div>
</div> 