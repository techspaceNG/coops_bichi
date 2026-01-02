<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Bulk Data Ingestion</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Bulk Data Upload</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="btn-group shadow-sm">
                <button type="button" class="btn btn-white border dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <i class="fas fa-file-download text-muted"></i>
                    <span>Download Templates</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item small" href="<?= url('/superadmin/download-template/savings') ?>"><i class="fas fa-piggy-bank me-2 opacity-50"></i> Savings Template</a></li>
                    <li><a class="dropdown-item small" href="<?= url('/superadmin/download-template/loans') ?>"><i class="fas fa-hand-holding-usd me-2 opacity-50"></i> Loans Template</a></li>
                    <li><a class="dropdown-item small" href="<?= url('/superadmin/download-template/household') ?>"><i class="fas fa-shopping-basket me-2 opacity-50"></i> Household Template</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item small" href="<?= url('/superadmin/download-template/members') ?>"><i class="fas fa-users me-2 opacity-50"></i> Members Template</a></li>
                </ul>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <div class="col-xl-7">
            <!-- Upload Configuration -->
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs border-bottom-0" id="uploadTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings-content" type="button" role="tab">
                                <i class="fas fa-wallet me-2"></i> Savings
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="loans-tab" data-bs-toggle="tab" data-bs-target="#loans-content" type="button" role="tab">
                                <i class="fas fa-exchange-alt me-2"></i> Loans
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="household-tab" data-bs-toggle="tab" data-bs-target="#household-content" type="button" role="tab">
                                <i class="fas fa-shopping-cart me-2"></i> Household
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 px-4 border-bottom border-top-0 border-start-0 border-end-0 fw-bold small text-uppercase" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-content" type="button" role="tab">
                                <i class="fas fa-user-plus me-2"></i> Members
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="uploadTabsContent">
                        <!-- Savings Upload -->
                        <div class="tab-pane fade show active" id="savings-content" role="tabpanel">
                            <div class="alert alert-info border-0 bg-primary bg-opacity-10 d-flex gap-3 align-items-center mb-4">
                                <i class="fas fa-info-circle fa-lg text-primary"></i>
                                <div class="small fw-semibold">Upload monthly deductions for all members. The system reconciles savings balances automatically.</div>
                            </div>
                            
                            <form action="<?= url('/superadmin/upload-savings') ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="savingsFile" class="form-label fw-bold small text-muted text-uppercase">Data Feed Asset</label>
                                    <input class="form-control border-light bg-light py-2" type="file" id="savingsFile" name="savings_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text small opacity-75">Required: COOPS No., Amount, and Deduction Date columns.</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="savingsDate" class="form-label fw-bold small text-muted text-uppercase">Financial Period (Month)</label>
                                    <input type="month" class="form-control border-light bg-light" id="savingsDate" name="deduction_month" value="<?= date('Y-m') ?>" required>
                                </div>
                                
                                <div class="p-3 bg-light rounded-3 mb-4">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="overrideSavings" name="override_existing" value="1">
                                        <label class="form-check-label small fw-bold text-danger" for="overrideSavings">
                                            OVERWRITE EXISTING PERIOD DATA
                                        </label>
                                        <div class="form-text small mt-0">DANGEROUS: Replaces all previous deductions for the selected month.</div>
                                    </div>
                                </div>
                                
                                <div class="d-grid shadow-sm">
                                    <button type="submit" class="btn btn-primary py-2 fw-bold">
                                        <i class="fas fa-cloud-upload-alt me-2"></i> COMMENCE SAVINGS INGESTION
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Loans Upload -->
                        <div class="tab-pane fade" id="loans-content" role="tabpanel">
                            <div class="alert alert-info border-0 bg-primary bg-opacity-10 d-flex gap-3 align-items-center mb-4">
                                <i class="fas fa-info-circle fa-lg text-primary"></i>
                                <div class="small fw-semibold">Update member loan repayment cycles. Updates outstanding loan liabilities.</div>
                            </div>
                            
                            <form action="<?= url('/superadmin/upload-loan-repayments') ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="loansFile" class="form-label fw-bold small text-muted text-uppercase">Loan Ledger File</label>
                                    <input class="form-control border-light bg-light py-2" type="file" id="loansFile" name="loans_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text small opacity-75">Required: COOPS No., Loan ID, Amount Paid, and Payment Date.</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="loansMonth" class="form-label fw-bold small text-muted text-uppercase">Settlement Cycle (Month)</label>
                                    <input type="month" class="form-control border-light bg-light" id="loansMonth" name="repayment_month" value="<?= date('Y-m') ?>" required>
                                </div>
                                
                                <div class="p-3 bg-light rounded-3 mb-4">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="overrideLoans" name="override_existing" value="1">
                                        <label class="form-check-label small fw-bold text-danger" for="overrideLoans">
                                            PURGE PREVIOUS CYCLE REPAYMENTS
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid shadow-sm">
                                    <button type="submit" class="btn btn-primary py-2 fw-bold">
                                        <i class="fas fa-cloud-upload-alt me-2"></i> EXECUTE LOAN SYNC
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Household Upload -->
                        <div class="tab-pane fade" id="household-content" role="tabpanel">
                            <div class="alert alert-info border-0 bg-primary bg-opacity-10 d-flex gap-3 align-items-center mb-4">
                                <i class="fas fa-info-circle fa-lg text-primary"></i>
                                <div class="small fw-semibold">Track repayments for household credit disbursements.</div>
                            </div>
                            
                            <form action="<?= url('/superadmin/upload-household-repayments') ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="householdFile" class="form-label fw-bold small text-muted text-uppercase">Household Credit Sheet</label>
                                    <input class="form-control border-light bg-light py-2" type="file" id="householdFile" name="household_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text small opacity-75">Required: COOPS No., Purchase ID, Amount, and Date.</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="householdMonth" class="form-label fw-bold small text-muted text-uppercase">Reconciliation Month</label>
                                    <input type="month" class="form-control border-light bg-light" id="householdMonth" name="repayment_month" value="<?= date('Y-m') ?>" required>
                                </div>
                                
                                <div class="p-3 bg-light rounded-3 mb-4">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="overrideHousehold" name="override_existing" value="1">
                                        <label class="form-check-label small fw-bold text-danger" for="overrideHousehold">
                                            REPLACE RECENT CREDIT ENTRIES
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid shadow-sm">
                                    <button type="submit" class="btn btn-primary py-2 fw-bold">
                                        <i class="fas fa-cloud-upload-alt me-2"></i> SYNC HOUSEHOLD LEDGER
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Members Upload -->
                        <div class="tab-pane fade" id="members-content" role="tabpanel">
                            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 d-flex gap-3 align-items-center mb-4">
                                <i class="fas fa-exclamation-triangle fa-lg text-warning"></i>
                                <div class="small fw-semibold text-dark">Registering multiple new entities. System will generate secure credentials automatically.</div>
                            </div>
                            
                            <form action="<?= url('/superadmin/upload-members') ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="membersFile" class="form-label fw-bold small text-muted text-uppercase">New Membership Registry (File)</label>
                                    <input class="form-control border-light bg-light py-2" type="file" id="membersFile" name="members_file" accept=".csv, .xlsx, .xls" required>
                                    <div class="form-text small opacity-75">Required: COOPS No., Name, Email, Department, and Initial Savings.</div>
                                </div>
                                
                                <div class="p-3 bg-light rounded-3 border-start border-4 border-primary mb-4">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="sendInvitations" name="send_invitations" value="1" checked>
                                        <label class="form-check-label small fw-bold" for="sendInvitations">
                                            DISPATCH ONBOARDING INVITATIONS
                                        </label>
                                        <div class="form-text small mt-0">New members will receive immediate portal access instructions via email.</div>
                                    </div>
                                </div>
                                
                                <div class="d-grid shadow-sm">
                                    <button type="submit" class="btn btn-primary py-3 fw-bold shadow-sm">
                                        <i class="fas fa-users-cog me-2"></i> MASS REGISTRATION INITIATE
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-5">
            <!-- Documentation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                    <i class="fas fa-book text-primary opacity-50"></i>
                    <h6 class="fw-bold mb-0">Protocol & Syntax</h6>
                </div>
                <div class="card-body p-4">
                    <div class="accordion accordion-flush" id="protocolAccordion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button px-0 py-2 small fw-bold text-uppercase text-muted shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#formatReq">
                                    File Standards
                                </button>
                            </h2>
                            <div id="formatReq" class="accordion-collapse collapse show">
                                <div class="accordion-body px-0 py-2">
                                    <ul class="list-unstyled small mb-0">
                                        <li class="mb-2 d-flex gap-2 align-items-start">
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            <span>Accepted: <strong>.CSV, .XLS, .XLSX</strong> formats only.</span>
                                        </li>
                                        <li class="mb-2 d-flex gap-2 align-items-start">
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            <span>Column headers are <strong>mandatory</strong> in row 1.</span>
                                        </li>
                                        <li class="mb-2 d-flex gap-2 align-items-start">
                                            <i class="fas fa-check-circle text-success mt-1"></i>
                                            <span>Registry IDs: <strong>COOPS/XX/XXX</strong> syntax required.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ingestion History -->
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Ingestion History</h6>
                    <a href="<?= url('/superadmin/upload-history') ?>" class="small text-decoration-none">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">
                                    <th class="ps-3 py-2">Entity</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Status</th>
                                    <th class="pe-3 py-2 text-end">Logs</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php if (empty($uploads)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">No recent activity detected.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($uploads as $upload): ?>
                                        <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#uploadDetailsModal" data-upload-id="<?= $upload['id'] ?>">
                                            <td class="ps-3">
                                                <div class="fw-bold small text-truncate" style="max-width: 120px;"><?= htmlspecialchars($upload['filename']) ?></div>
                                                <div class="text-muted" style="font-size: 0.65rem;"><?= date('M d, H:i', strtotime($upload['created_at'])) ?></div>
                                            </td>
                                            <td><span class="small badge bg-light text-dark fw-semibold"><?= strtoupper($upload['upload_type']) ?></span></td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                switch ($upload['status']) {
                                                    case 'completed': $statusClass = 'bg-success'; break;
                                                    case 'processing': $statusClass = 'bg-warning'; break;
                                                    case 'failed': $statusClass = 'bg-danger'; break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $statusClass) ?> rounded-pill px-2" style="font-size: 0.65rem;">
                                                    <?= strtoupper($upload['status']) ?>
                                                </span>
                                            </td>
                                            <td class="pe-3 text-end">
                                                <i class="fas fa-chevron-right small text-muted"></i>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Details Modal -->
<div class="modal fade" id="uploadDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold">Ingestion Metadata & Audit</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="uploadDetailsContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary opacity-25" role="status"></div>
                        <p class="mt-3 small text-muted">Retrieving detailed ledger sync logs...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close Audit</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var uploadDetailsModal = document.getElementById('uploadDetailsModal');
    if (uploadDetailsModal) {
        uploadDetailsModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var uploadId = button.getAttribute('data-upload-id');
            var container = document.getElementById('uploadDetailsContent');
            
            fetch('/superadmin/get-upload-details/' + uploadId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        container.innerHTML = `<div class="alert alert-danger border-0 small">${data.error}</div>`;
                        return;
                    }
                    
                    let statsHtml = '';
                    if (data.stats) {
                        statsHtml = `
                            <div class="row g-3 mb-4">
                                <div class="col-3 text-center">
                                    <div class="h4 fw-bold text-primary mb-0">${data.stats.total_records}</div>
                                    <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Total</div>
                                </div>
                                <div class="col-3 text-center">
                                    <div class="h4 fw-bold text-success mb-0">${data.stats.successful_records}</div>
                                    <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Success</div>
                                </div>
                                <div class="col-3 text-center">
                                    <div class="h4 fw-bold text-danger mb-0">${data.stats.failed_records}</div>
                                    <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Failed</div>
                                </div>
                                <div class="col-3 text-center">
                                    <div class="h4 fw-bold text-warning mb-0">${data.stats.skipped_records}</div>
                                    <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">Skipped</div>
                                </div>
                            </div>
                        `;
                    }

                    let errorsHtml = '';
                    if (data.errors && data.errors.length > 0) {
                        errorsHtml = `
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="small fw-bold text-danger text-uppercase mb-3">Collision & Error Log</h6>
                                <div class="bg-danger bg-opacity-10 rounded-3 p-3 overflow-auto" style="max-height: 200px;">
                                    <ul class="list-unstyled small mb-0 font-monospace">
                                        ${data.errors.map(err => `<li class="mb-2 pb-2 border-bottom border-danger border-opacity-10">${err}</li>`).join('')}
                                    </ul>
                                </div>
                            </div>
                        `;
                    }

                    container.innerHTML = `
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-file-invoice text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-truncate" style="max-width: 400px;">${data.upload.filename}</h5>
                                <div class="small text-muted">Uploaded by ${data.upload.uploader_name} on ${new Date(data.upload.created_at).toLocaleString()}</div>
                            </div>
                        </div>
                        ${statsHtml}
                        ${errorsHtml}
                    `;
                })
                .catch(err => {
                    container.innerHTML = `<div class="alert alert-danger border-0 small">Network failure: ${err.message}</div>`;
                });
        });
    }
});
</script>

<?php /* Footer managed by Central Renderer */ ?> 
