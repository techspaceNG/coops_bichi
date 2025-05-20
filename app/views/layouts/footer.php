    </main>
    
    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-8">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">About Us</h3>
                    <p class="text-gray-600">FCET Bichi Staff Multipurpose Cooperative Society serves the staff members of the Federal College of Education (Technical) Bichi.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="<?= $publicUrl ?>/home" class="text-gray-600 hover:text-primary-600">Home</a></li>
                        <li><a href="<?= $publicUrl ?>/about" class="text-gray-600 hover:text-primary-600">About Us</a></li>
                        <li><a href="<?= $publicUrl ?>/contact" class="text-gray-600 hover:text-primary-600">Contact Us</a></li>
                        <li><a href="<?= $publicUrl ?>/faq" class="text-gray-600 hover:text-primary-600">FAQ</a></li>
                    </ul>
                </div>
                
                <!-- Member Area -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Member Area</h3>
                    <ul class="space-y-2">
                        <li><a href="<?= $publicUrl ?>/login" class="text-gray-600 hover:text-primary-600">Login</a></li>
                        <li><a href="<?= $publicUrl ?>/register" class="text-gray-600 hover:text-primary-600">Register</a></li>
                        <li><a href="<?= $publicUrl ?>/member/loans/calculator" class="text-gray-600 hover:text-primary-600">Loan Calculator</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Info</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-primary-600"></i>
                            <span class="text-gray-600">FCET Bichi, Kano State, Nigeria</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-primary-600"></i>
                            <span class="text-gray-600">+234 xxx xxx xxxx</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-primary-600"></i>
                            <span class="text-gray-600">info@coopsbichi.org</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-600">
                <p>&copy; <?= date('Y') ?> FCET Bichi Staff Multipurpose Cooperative Society. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript for mobile menu toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>
</html> 