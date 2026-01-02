<?php
declare(strict_types=1);
defined('BASE_PATH') or exit('Access Denied!');
?>

<div class="container-fluid p-0">
    <!-- Header & Breadcrumbs -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Bulk Household Settlement</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/household') ?>" class="text-decoration-none text-muted">Household Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Bulk Ingest</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/add-household-deduction') ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus text-muted"></i>
                <span class="d-none d-sm-inline">Individual Deduction</span>
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-xl-7">
            <?php if (isset($bulk_results)): ?>
                <div class="card border-0 shadow-sm mb-4 border-start border-4 <?= isset($bulk_results['failed']) && $bulk_results['failed'] > 0 ? 'border-warning' : 'border-success' ?>">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-<?= isset($bulk_results['failed']) && $bulk_results['failed'] > 0 ? 'warning' : 'success' ?> bg-opacity-10 p-2 rounded-3 text-<?= isset($bulk_results['failed']) && $bulk_results['failed'] > 0 ? 'warning' : 'success' ?>">
                                <i class="fas fa-poll-h fa-lg"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 0.5px;">Ingestion Report Summary</h6>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="p-3 bg-light rounded-3 text-center">
                                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Agg. Volume</div>
                                    <div class="fw-bold"><?= $bulk_results['total'] ?></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-light rounded-3 text-center border-bottom border-4 border-success">
                                    <div class="text-muted small fw-bold text-uppercase text-success" style="font-size: 0.6rem;">Committed</div>
                                    <div class="fw-bold"><?= $bulk_results['success'] ?></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-light rounded-3 text-center border-bottom border-4 border-danger">
                                    <div class="text-muted small fw-bold text-uppercase text-danger" style="font-size: 0.6rem;">Anomalies</div>
                                    <div class="fw-bold"><?= $bulk_results['failed'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($bulk_results['errors'])): ?>
                            <div class="mt-4 p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                                <h6 class="fw-bold small mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Anomaly Logs:</h6>
                                <ul class="mb-0 small ps-3">
                                    <?php foreach ($bulk_results['errors'] as $error): ?>
                                        <li class="mb-1"><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Ingestion Terminal</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/process-bulk-household-deductions') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="file" class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Manifest File (CSV)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-muted"><i class="fas fa-file-csv"></i></span>
                                <input type="file" class="form-control border-light bg-light shadow-none small" id="file" name="file" accept=".csv" required>
                            </div>
                            <div class="form-text small italic opacity-75 mt-2">Maximum file allocation: 5MB. Ensure system compatibility across all nodes.</div>
                        </div>
                        
                        <div class="bg-light p-4 rounded-4 mb-4 border">
                            <h6 class="fw-bold small mb-3 text-uppercase text-muted" style="letter-spacing: 0.5px;">System Schema Requirements</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success small"></i>
                                        <span class="small fw-bold">reference_number</span>
                                    </div>
                                    <div class="ps-4 small text-muted opacity-75">The unique HP-XXXX-XXX identity of the asset purchase.</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success small"></i>
                                        <span class="small fw-bold">amount</span>
                                    </div>
                                    <div class="ps-4 small text-muted opacity-75">Numerical credit volume to be recovered.</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success small"></i>
                                        <span class="small fw-bold">payment_date</span>
                                    </div>
                                    <div class="ps-4 small text-muted opacity-75">Temporal mark in ISO format (YYYY-MM-DD).</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2 mb-2 text-muted opacity-50">
                                        <i class="fas fa-dot-circle small"></i>
                                        <span class="small fw-bold">receipt_number</span>
                                    </div>
                                    <div class="ps-4 small text-muted opacity-75">(Optional) Audit reference from paper/local bank.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <a href="<?= url('/superadmin/download-household-deduction-template') ?>" class="btn btn-link text-decoration-none text-muted small fw-bold">
                                <i class="fas fa-cloud-download-alt me-2"></i> Download Schema Template
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                                <i class="fas fa-microchip me-2"></i> Process Manifest
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm bg-dark text-white mb-4 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <div class="bg-white bg-opacity-10 p-2 rounded-3 text-white d-inline-block mb-3">
                        <i class="fas fa-terminal"></i>
                    </div>
                    <h6 class="fw-bold mb-3">Auditor Ingestion Protocol</h6>
                    <p class="small text-white-50">Ingest multiple household recovery records through a centralized manifest file.</p>
                    <div class="d-grid gap-2 small">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-chevron-right mt-1 text-primary"></i>
                            <span>Prepare manifest utilizing the CSV schema provided.</span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-chevron-right mt-1 text-primary"></i>
                            <span>Header alignment is mandatory for logic synchronization.</span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-chevron-right mt-1 text-primary"></i>
                            <span>System validates each node before committing to the ledger.</span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-chevron-right mt-1 text-primary"></i>
                            <span>Anomalies will be flagged in the post-process report.</span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 p-3 opacity-10">
                    <i class="fas fa-file-invoice-dollar fa-8x"></i>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Schema Preview</h6>
                </div>
                <div class="card-body p-0 bg-light">
                    <pre class="mb-0 p-4 small font-monospace" style="background: transparent; color: #444;"><code>reference_number,amount,payment_date,receipt_number,notes
HP-2023-001,5000.00,2025-04-15,REC-12345,Monthly payment
HP-2023-002,10000.00,2025-04-15,REC-12346,Full payment</code></pre>
                </div>
            </div>
        </div>
    </div>
</div> 