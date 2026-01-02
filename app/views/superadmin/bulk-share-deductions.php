<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid p-0">
    <!-- Header & Breadcrumbs -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-0">Bulk Equity Ingestion</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="<?= url('/superadmin/shares') ?>" class="text-decoration-none text-muted">Shares Management</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Bulk Strategy</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="<?= url('/superadmin/add-share-deduction') ?>" class="btn btn-white border shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus text-muted"></i>
                <span class="d-none d-sm-inline">New Unit Allocation</span>
            </a>
        </div>
    </div>

    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>

    <div class="row g-4">
        <!-- Ingestion Terminal -->
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Equity Manifest Terminal</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('/superadmin/process-bulk-share-deductions') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="file" class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Equity Dataset (CSV) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-muted"><i class="fas fa-file-csv"></i></span>
                                <input type="file" class="form-control border-light bg-light shadow-none small" id="file" name="file" accept=".csv" required>
                            </div>
                            <div class="invalid-feedback">A valid logic manifest in CSV format is required.</div>
                            <div class="form-text small italic opacity-75 mt-2">
                                System Schema: coops_number, units, unit_value, notes (optional)
                            </div>
                        </div>

                        <div class="p-4 bg-light rounded-4 mb-4 border">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <h6 class="fw-bold mb-0 small text-uppercase text-muted" style="letter-spacing: 0.5px;">Protocol Parameters</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="ps-2">
                                        <div class="small fw-bold mb-1">coops_number</div>
                                        <div class="small text-muted opacity-75">The primary registry identity of the member.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ps-2">
                                        <div class="small fw-bold mb-1">units</div>
                                        <div class="small text-muted opacity-75">Quantity of equity assets to allocate.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ps-2">
                                        <div class="small fw-bold mb-1">unit_value</div>
                                        <div class="small text-muted opacity-75">Valuation per unit (typically ₦20.00).</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ps-2">
                                        <div class="small fw-bold mb-1 text-muted opacity-50">notes (optional)</div>
                                        <div class="small text-muted opacity-75">Audit trail commentary for the ledger.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= url('/superadmin/download-share-deduction-template') ?>" class="btn btn-link text-decoration-none text-muted small fw-bold">
                                <i class="fas fa-cloud-download-alt me-2"></i> Download Schema Manifest
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold">
                                <i class="fas fa-upload me-2"></i> Ingest & Commit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 p-4">
                <div class="bg-warning bg-opacity-20 p-3 rounded-circle text-warning">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Data Integrity Protocol</h6>
                    <p class="small text-muted mb-0">Records containing logical inconsistencies or unresolvable identities will be automatically bypassed to maintain ledger health. Post-ingestion analytics will be generated.</p>
                </div>
            </div>
        </div>

        <!-- Guidance Column -->
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm bg-dark text-white mb-4 position-relative overflow-hidden">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <h6 class="fw-bold mb-4 text-uppercase fw-bold text-primary" style="letter-spacing: 1px;">Workflow Strategy</h6>
                    <div class="d-grid gap-4">
                        <div class="d-flex gap-3">
                            <div class="fw-bold text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">1</div>
                            <div class="small text-white-50">Extract current member directory and align with the equity manifest template.</div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="fw-bold text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">2</div>
                            <div class="small text-white-50">Quantify units and verify valuation per member entity.</div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="fw-bold text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;">3</div>
                            <div class="small text-white-50">Commit the manifest to the system for centralized ledger synchronization.</div>
                        </div>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 p-3 opacity-10">
                    <i class="fas fa-chart-line fa-8x"></i>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 small text-uppercase text-muted">Auditor Preview</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted fw-bold" style="font-size: 0.65rem;">
                                    <th class="ps-3 py-2">COOPS_ID</th>
                                    <th class="py-2">UNITS</th>
                                    <th class="pe-3 py-2 text-end">VALUATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="font-monospace small opacity-75">
                                    <td class="ps-3">10234</td>
                                    <td>500</td>
                                    <td class="pe-3 text-end">20.00</td>
                                </tr>
                                <tr class="font-monospace small opacity-75">
                                    <td class="ps-3 border-top-0">10567</td>
                                    <td class="border-top-0">250</td>
                                    <td class="pe-3 text-end border-top-0">20.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    });
</script> 