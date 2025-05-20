                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'system_logs' ? 'active' : '' ?>" href="<?= url('/superadmin/system-logs') ?>">
                            <i class="fas fa-history me-2"></i> System Logs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'notifications' ? 'active' : '' ?>" href="<?= url('/superadmin/notifications') ?>">
                            <i class="fas fa-bell me-2"></i> Notifications
                            <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-2"><?= $unread_notifications_count > 9 ? '9+' : $unread_notifications_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul> 