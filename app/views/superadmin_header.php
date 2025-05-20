        <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>" href="/superadmin/dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'manage_admins' ? 'active' : '' ?>" href="/superadmin/manage-admins">
                        <i class="fas fa-users-cog me-2"></i> Manage Administrators
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'create_admin' ? 'active' : '' ?>" href="/superadmin/create-admin">
                        <i class="fas fa-user-plus me-2"></i> Add New Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'system_settings' ? 'active' : '' ?>" href="/superadmin/system-settings">
                        <i class="fas fa-cogs me-2"></i> System Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'database_backup' ? 'active' : '' ?>" href="/superadmin/database-backup">
                        <i class="fas fa-database me-2"></i> Database Backup
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'system_logs' ? 'active' : '' ?>" href="/superadmin/system-logs">
                        <i class="fas fa-history me-2"></i> System Logs
                    </a>
                </li>
        </ul> 