<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Household Purchase Application Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/dashboard'); ?>">Superadmin Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo url('/superadmin/household'); ?>">Household Management</a></li>
        <li class="breadcrumb-item active">View Purchase #<?php echo htmlspecialchars($purchase['id']); ?></li>
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
                        <p><?php echo htmlspecialchars($purchase['member_name']); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">COOPS No:</label>
                        <p><?php echo htmlspecialchars($purchase['member_coop_no'] ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Department:</label>
                        <p><?php echo !empty($purchase['department_name']) ? htmlspecialchars($purchase['department_name']) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Email:</label>
                        <p><?php echo !empty($purchase['member_email']) ? htmlspecialchars($purchase['member_email']) : 'Not provided'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Phone:</label>
                        <p><?php echo !empty($purchase['member_phone']) ? htmlspecialchars($purchase['member_phone']) : 'Not provided'; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Household Purchase Details
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Purchase Amount:</label>
                            <p class="text-primary fw-bold">₦<?php echo number_format($purchase['amount'], 2); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">IP Figure:</label>
                            <p>₦<?php echo isset($purchase['ip_figure']) ? number_format($purchase['ip_figure'], 2) : 'N/A'; ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Total Repayment (incl. 5% admin charges):</label>
                            <p class="text-danger fw-bold">₦<?php echo number_format($purchase['total_repayment'] ?? ($purchase['amount'] * 1.05), 2); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Amount Paid:</label>
                            <p>₦<?php echo number_format($totalPaid ?? 0, 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Duration:</label>
                            <p><?php echo htmlspecialchars($purchase['purchase_duration'] ?? $purchase['repayment_period'] ?? '12'); ?> months</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Application Date:</label>
                            <p><?php echo date('F j, Y', strtotime($purchase['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Remaining Balance (incl. admin charges):</label>
                            <p class="fw-bold <?php echo ($remainingBalance ?? 0) <= 0 ? 'text-success' : 'text-warning'; ?>">
                                ₦<?php echo number_format($remainingBalance, 2); ?>
                                <?php if (($remainingBalance ?? 0) <= 0): ?>
                                    <span class="badge bg-success ms-2">Fully Paid</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Status:</label>
                            <p>
                                <span class="badge 
                                    <?php 
                                    switch($purchase['status']) {
                                        case 'pending': echo 'bg-warning'; break;
                                        case 'approved': echo 'bg-success'; break;
                                        case 'declined': 
                                        case 'rejected': echo 'bg-danger'; break;
                                        case 'completed': echo 'bg-info'; break;
                                        default: echo 'bg-secondary';
                                    }
                                    ?>">
                                    <?php echo ucfirst($purchase['status']); ?>
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
                                    <p><?php echo htmlspecialchars($purchase['bank_name'] ?? 'Not provided'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Account Number:</label>
                                    <p><?php echo htmlspecialchars($purchase['account_number'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Account Name:</label>
                                    <p><?php echo htmlspecialchars($purchase['account_name'] ?? 'Not provided'); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Account Type:</label>
                                    <p><?php echo htmlspecialchars($purchase['account_type'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Description:</label>
                        <p><?php echo !empty($purchase['description']) ? nl2br(htmlspecialchars($purchase['description'])) : 'Not specified'; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold">Comment/Notes:</label>
                        <p><?php echo !empty($purchase['comment']) ? nl2br(htmlspecialchars($purchase['comment'])) : 'No additional notes'; ?></p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo url('/superadmin/household'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        
                        <div>
                            <?php if ($purchase['status'] === 'pending'): ?>
                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#declineModal">
                                    <i class="fas fa-times me-1"></i> Decline
                                </button>
                            <?php endif; ?>
                            
                            <a href="javascript:void(0);" onclick="printHouseholdDetails(<?php echo $purchase['id']; ?>);" class="btn btn-primary">
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
function printHouseholdDetails(id) {
    window.open(<?php echo json_encode(url('/superadmin/print-household/')); ?> + id, '_blank');
}
</script>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo url('/superadmin/approve-household/' . $purchase['id']); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="approveModalLabel">Approve Household Purchase Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to approve this household purchase application for ₦<?php echo number_format($purchase['amount'], 2); ?>?</p>
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
      <form action="<?php echo url('/superadmin/decline-household/' . $purchase['id']); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="declineModalLabel">Decline Household Purchase Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to decline this household purchase application?</p>
          <div class="mb-3">
            <label for="decline_reason" class="form-label">Reason for Declining <span class="text-danger">*</span></label>
            <textarea class="form-control" id="decline_reason" name="decline_reason" rows="3" required placeholder="Please provide a reason for declining this application"></textarea>
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