        </div> <!-- This closes the pt-16 div from admin_header.php -->
<?php $publicUrl = \App\Core\Config::getPublicUrl(); ?>
    </div> <!-- This closes any remaining open divs -->

    <footer class="bg-white py-4 border-t border-gray-200 mt-auto">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex items-center">
                    <img src="<?= $publicUrl ?>/assets/images/logo.png" alt="FCET Bichi Cooperative" class="h-10 w-auto mr-2">
                    <span class="text-sm text-gray-500">&copy; <?= date('Y') ?> FCET Bichi Staff Multipurpose Cooperative Society</span>
                </div>
                <div class="mt-4 md:mt-0">
                    <p class="text-sm text-gray-500">
                        Portal Version <?= defined('APP_VERSION') ? htmlspecialchars(APP_VERSION) : '1.0.0' ?> | <a href="<?= $publicUrl ?>/admin/changelog" class="text-primary-600 hover:text-primary-700">View Changelog</a>
                    </p>
                </div>
            </div>
            
            <div class="mt-4 flex justify-between border-t border-gray-200 pt-4">
                <p class="text-xs text-gray-500">
                    Last login: <?= isset($admin['last_login_at']) ? date('M d, Y H:i', strtotime($admin['last_login_at'])) : 'N/A' ?> 
                    from <?= isset($admin['last_login_ip']) ? htmlspecialchars($admin['last_login_ip']) : 'Unknown' ?>
                </p>
                <p class="text-xs text-gray-500">
                    <a href="<?= $publicUrl ?>/admin/logs" class="text-primary-600 hover:text-primary-700">Activity Logs</a> | 
                    <a href="<?= $publicUrl ?>/admin/terms" class="text-primary-600 hover:text-primary-700">Terms of Use</a> | 
                    <a href="<?= $publicUrl ?>/admin/privacy" class="text-primary-600 hover:text-primary-700">Privacy Policy</a> |
                    <span class="text-gray-600">Built by TechspaceNG</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Page Specific JavaScript -->
    <?php if (isset($page_specific_js)): ?>
        <script>
            <?= $page_specific_js ?>
        </script>
    <?php endif; ?>

    <!-- Admin Toast Notifications System -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 flex flex-col space-y-2"></div>
    
    <script>
        // Admin Toast Notification System
        function showToast(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toastContainer');
            const types = {
                success: { icon: 'fa-check-circle', bgColor: 'bg-green-500' },
                error: { icon: 'fa-exclamation-circle', bgColor: 'bg-red-500' },
                warning: { icon: 'fa-exclamation-triangle', bgColor: 'bg-yellow-500' },
                info: { icon: 'fa-info-circle', bgColor: 'bg-blue-500' }
            };
            
            const toast = document.createElement('div');
            toast.className = `${types[type].bgColor} text-white px-4 py-3 rounded shadow-md flex items-center max-w-md transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="flex-shrink-0 mr-3">
                    <i class="fas ${types[type].icon}"></i>
                </div>
                <div class="flex-1 mr-2 text-sm">${message}</div>
                <button class="flex-shrink-0 text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Set up dismiss button
            const dismissButton = toast.querySelector('button');
            dismissButton.addEventListener('click', () => {
                dismissToast(toast);
            });
            
            // Auto dismiss after duration
            if (duration) {
                setTimeout(() => {
                    dismissToast(toast);
                }, duration);
            }
        }
        
        function dismissToast(toast) {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
        
        // Initialize any additional components or plugins
        document.addEventListener('DOMContentLoaded', function() {
            // Any additional initialization code for the admin panel
        });
    </script>
</body>
</html> 