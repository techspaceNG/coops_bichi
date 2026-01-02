        </div> <!-- End Content Wrapper -->
    </div> <!-- End Main -->
</div> <!-- End Wrapper -->

<!-- Sidebar Toggle Script -->
<script>
    $(document).ready(function() {
        $('#sidebarToggle, #sidebarClose').on('click', function() {
            $('#sidebar').toggleClass('show');
        });

        // Close sidebar when clicking outside on mobile
        $(document).on('click', function(e) {
            if ($(window).width() < 992) {
                if (!$(e.target).closest('#sidebar').length && !$(e.target).closest('#sidebarToggle').length && $('#sidebar').hasClass('show')) {
                    $('#sidebar').removeClass('show');
                }
            }
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<?php if (isset($page_specific_js)): ?>
    <script>
        <?= $page_specific_js ?>
    </script>
<?php endif; ?>

</body>
</html>