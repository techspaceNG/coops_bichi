<?php 
// Set current page for active menu highlighting
$current_page = 'system_logs';
// Set page title
$page_title = 'System Logs';
?>

<!-- Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">System & Audit Logs</h1>
    </div>
    
    <!-- Alerts -->
    <?php include BASE_DIR . '/app/views/layouts/alerts.php'; ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history fa-fw"></i> System Activity Logs</h6>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form action="<?= url('/superadmin/system-logs') ?>" method="GET" class="row mb-3">
                <div class="col-md-3 mb-2">
                    <select name="type" class="form-control form-control-sm">
                        <option value="">All Types</option>
                        <option value="general" <?php echo isset($_GET['type']) && $_GET['type'] === 'general' ? 'selected' : ''; ?>>General</option>
                        <option value="security" <?php echo isset($_GET['type']) && $_GET['type'] === 'security' ? 'selected' : ''; ?>>Security</option>
                        <option value="admin" <?php echo isset($_GET['type']) && $_GET['type'] === 'admin' ? 'selected' : ''; ?>>Administrative</option>
                        <option value="member" <?php echo isset($_GET['type']) && $_GET['type'] === 'member' ? 'selected' : ''; ?>>Member</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="user_type" class="form-control form-control-sm">
                        <option value="">All Users</option>
                        <option value="admin" <?php echo isset($_GET['user_type']) && $_GET['user_type'] === 'admin' ? 'selected' : ''; ?>>Administrators</option>
                        <option value="member" <?php echo isset($_GET['user_type']) && $_GET['user_type'] === 'member' ? 'selected' : ''; ?>>Members</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Search logs..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-2 text-right">
                    <a href="<?= url('/superadmin/system-logs') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
            
            <!-- Logs Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Timestamp</th>
                            <th width="10%">User Type</th>
                            <th width="10%">User ID</th>
                            <th width="10%">Action Type</th>
                            <th width="30%">Action</th>
                            <th width="15%">IP Address</th>
                            <th width="5%">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No logs found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo $log['id']; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?></td>
                                    <td>
                                        <?php if ($log['user_type'] === 'admin'): ?>
                                            <span class="badge badge-primary">Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Member</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $log['user_id'] ?? 'System'; ?></td>
                                    <td>
                                        <?php 
                                        switch($log['action_type']) {
                                            case 'security':
                                                echo '<span class="badge badge-danger">Security</span>';
                                                break;
                                            case 'admin':
                                                echo '<span class="badge badge-warning">Admin</span>';
                                                break;
                                            case 'member':
                                                echo '<span class="badge badge-info">Member</span>';
                                                break;
                                            default:
                                                echo '<span class="badge badge-secondary">General</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($log['action']); ?></td>
                                    <td><?php echo htmlspecialchars($log['ip_address'] ?? 'Unknown'); ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($log['details'])): ?>
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-toggle="modal" 
                                                    data-target="#logDetails<?php echo $log['id']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Log Details Modal -->
                                            <div class="modal fade" id="logDetails<?php echo $log['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="logDetailsLabel<?php echo $log['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="logDetailsLabel<?php echo $log['id']; ?>">Log Details #<?php echo $log['id']; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <th>Action:</th>
                                                                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Details:</th>
                                                                        <td>
                                                                            <pre class="bg-light p-2 mb-0"><?php echo htmlspecialchars(print_r(json_decode($log['details'], true), true)); ?></pre>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Timestamp:</th>
                                                                        <td><?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>IP Address:</th>
                                                                        <td><?php echo htmlspecialchars($log['ip_address'] ?? 'Unknown'); ?></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination) && isset($pagination['last_page']) && $pagination['last_page'] > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                            <li class="page-item <?php echo isset($pagination['current_page']) && $pagination['current_page'] == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="<?= url('/superadmin/system-logs?page=' . $i . 
                                   (isset($_GET['type']) ? '&type=' . $_GET['type'] : '') . 
                                   (isset($_GET['user_type']) ? '&user_type=' . $_GET['user_type'] : '') . 
                                   (isset($_GET['search']) ? '&search=' . $_GET['search'] : '')) ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div> 