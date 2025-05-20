            </div> <!-- Close container-fluid -->
        </div> <!-- Close main-content -->
    </div> <!-- Close page-wrapper -->

    <!-- Footer -->
    <footer class="bg-light py-3 mt-auto border-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <span class="bg-primary rounded p-1 text-white me-2">
                            <i class="fas fa-university"></i>
                        </span>
                        <span class="small text-muted">&copy; <?= date('Y') ?> FCET Bichi Staff Multipurpose Cooperative Society</span>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small text-muted mb-0">
                        Superadmin Portal Version <?= defined('APP_VERSION') ? htmlspecialchars(APP_VERSION) : '1.0.0' ?> | 
                        <a href="<?= url('/superadmin/system-logs') ?>" class="text-decoration-none">View Logs</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast container for notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <!-- Toasts will be added here dynamically -->
    </div>

    <!-- Common JavaScript for all superadmin pages -->
    <script>
        // Initialize all Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize all Bootstrap popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });

        // Show toast notification
        function showToast(message, type = 'primary') {
            const toastContainer = document.querySelector('.toast-container');
            const toastHTML = `
                <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Remove toast from DOM after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }

        // Toggle mobile sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.navbar-toggler');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    const isClickInside = sidebar.contains(event.target) || sidebarToggle.contains(event.target);
                    
                    if (!isClickInside && window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                });
            }
        });
    </script>

    <!-- Page specific JavaScript -->
    <?php if (isset($page_specific_js)): ?>
        <script>
            <?= $page_specific_js ?>
        </script>
    <?php endif; ?>
</body>
</html> 