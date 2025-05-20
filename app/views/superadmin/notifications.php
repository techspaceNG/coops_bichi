<?php 
// Ensure this page isn't accessed directly
if (!defined('BASE_DIR')) exit('No direct script access allowed');
?>

<div class="container-fluid p-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
        
        <div>
            <?php if (isset($notifications) && !empty($notifications) && isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                <a href="<?= url('/superadmin/mark-all-notifications-read') ?>" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-check-double me-1"></i> Mark All as Read
                </a>
            <?php endif; ?>
            
            <a href="<?= url('/superadmin/create-notification') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Create Notification
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Notifications</h6>
            
            <?php if (isset($pagination) && $pagination['total'] > 0): ?>
                <span class="text-muted small">
                    Showing <?= ($pagination['current_page'] - 1) * 10 + 1 ?> - 
                    <?= min($pagination['current_page'] * 10, $pagination['total']) ?> 
                    of <?= $pagination['total'] ?> notifications
                </span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (isset($notifications) && !empty($notifications)): ?>
                <div class="list-group">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="list-group-item <?= !$notification['is_read'] ? 'bg-light' : '' ?>">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="me-2">
                                            <i class="fas fa-circle <?= !$notification['is_read'] ? 'text-primary' : 'text-secondary' ?>" style="font-size: 0.5rem;"></i>
                                        </span>
                                        <h5 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h5>
                                    </div>
                                    <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i> <?= date('F j, Y, g:i a', strtotime($notification['created_at'])) ?>
                                        </small>
                                        <div>
                                            <?php if ($notification['link']): ?>
                                                <a href="<?= url($notification['link']) ?>" class="btn btn-sm btn-primary me-2">
                                                    <i class="fas fa-external-link-alt me-1"></i> View
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (!$notification['is_read']): ?>
                                                <a href="<?= url('/superadmin/mark-notification-read/' . $notification['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-check me-1"></i> Mark as Read
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-check me-1"></i> Read <?= $notification['read_at'] ? '(' . date('M j, g:i a', strtotime($notification['read_at'])) . ')' : '' ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $pagination['current_page'] <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= url('/superadmin/notifications?page=' . ($pagination['current_page'] - 1)) ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            
                            <?php
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            if ($start > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/superadmin/notifications?page=1') ?>">1</a>
                                </li>
                                <?php if ($start > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('/superadmin/notifications?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($end < $pagination['total_pages']): ?>
                                <?php if ($end < $pagination['total_pages'] - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/superadmin/notifications?page=' . $pagination['total_pages']) ?>"><?= $pagination['total_pages'] ?></a>
                                </li>
                            <?php endif; ?>
                            
                            <li class="page-item <?= $pagination['current_page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= url('/superadmin/notifications?page=' . ($pagination['current_page'] + 1)) ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                    <h5>No Notifications</h5>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                    
                    <?php
                    // Debug information
                    try {
                        $db = \App\Config\Database::getConnection();
                        $adminId = \App\Helpers\Auth::getAdminId();
                        
                        echo '<div class="alert alert-info mt-3">';
                        echo '<strong>Debug Information</strong><br>';
                        echo 'Admin ID: ' . $adminId . '<br>';
                        
                        // Check if database connection is active
                        echo 'Database connected: ' . ($db ? 'Yes' : 'No') . '<br>';
                        
                        // Check if notifications table exists
                        $stmt = $db->query("SHOW TABLES LIKE 'notifications'");
                        $tableExists = $stmt->rowCount() > 0;
                        echo 'Notifications table exists: ' . ($tableExists ? 'Yes' : 'No') . '<br>';
                        
                        // Check notification count in database
                        if ($tableExists) {
                            $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications");
                            $stmt->execute();
                            $totalCount = $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
                            echo 'Total notifications in database: ' . $totalCount . '<br>';
                            
                            $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND user_type = 'admin'");
                            $stmt->execute([$adminId]);
                            $adminCount = $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
                            echo 'Notifications for this admin: ' . $adminCount . '<br>';
                        }
                        
                        echo '</div>';
                    } catch (\Exception $e) {
                        echo '<div class="alert alert-danger mt-3">';
                        echo 'Error connecting to database: ' . $e->getMessage();
                        echo '</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 