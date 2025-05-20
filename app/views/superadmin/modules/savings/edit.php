<?php /* Don't include admin_header.php here as it's already included by renderSuperAdmin method */ ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Savings Settings</h1>
    <ol class="breadcrumb mb-4">
        <?php foreach ($breadcrumb as $item): ?>
            <li class="breadcrumb-item">
                <?php if (!empty($item['url'])): ?>
                    <a href="<?php echo url($item['url']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
                <?php else: ?>
                    <?php echo htmlspecialchars($item['label']); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
    
    <?php include VIEWS_PATH . '/layouts/alerts.php'; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Monthly Deduction
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Member Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>COOPS No.</th>
                            <td><?php echo htmlspecialchars($member['coop_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo htmlspecialchars($member['name']); ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php echo htmlspecialchars($member['department_name'] ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Current Balance</th>
                            <td>₦<?php echo number_format($savings['cumulative_amount'] ?? 0, 2); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <form method="POST" action="<?php echo url("/superadmin/savings/edit/{$member['id']}"); ?>" class="row g-3">
                <div class="col-md-6">
                    <label for="monthly_deduction" class="form-label">Monthly Deduction Amount (₦)</label>
                    <input type="number" 
                           class="form-control <?php echo isset($errors['monthly_deduction']) ? 'is-invalid' : ''; ?>" 
                           id="monthly_deduction" 
                           name="monthly_deduction" 
                           value="<?php echo htmlspecialchars(($input['monthly_deduction'] ?? $savings['monthly_deduction'] ?? 0)); ?>" 
                           min="0" 
                           step="0.01" 
                           required>
                    <?php if (isset($errors['monthly_deduction'])): ?>
                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['monthly_deduction']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                    <a href="<?php echo url("/superadmin/savings/view/{$member['id']}"); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div> 