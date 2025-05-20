<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <img class="mx-auto h-16 w-auto" src="/assets/images/logo.png" alt="FCET Bichi Staff Multipurpose Cooperative Society">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Reset your password</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your new password to regain access to your account
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php if (isset($_SESSION['reset_error'])): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Password Reset Failed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p><?= htmlspecialchars($_SESSION['reset_error']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['reset_error']); ?>
            <?php endif; ?>

            <form class="space-y-6" action="/auth/reset-password" method="POST">
                <input type="hidden" name="token" value="<?= isset($_GET['token']) ? htmlspecialchars($_GET['token']) : '' ?>">
                <input type="hidden" name="email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['password']) ? 'border-red-500' : '' ?>">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if (isset($_SESSION['validation_errors']['password'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['password']) ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-xs text-gray-500">Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters</p>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm New Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['password_confirmation']) ? 'border-red-500' : '' ?>">
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if (isset($_SESSION['validation_errors']['password_confirmation'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['password_confirmation']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Reset Password
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            or
                        </span>
                    </div>
                </div>

                <div class="mt-6 flex justify-center text-sm">
                    <div class="space-x-4">
                        <a href="/auth/login" class="font-medium text-primary-600 hover:text-primary-500">
                            Back to login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md text-center text-sm text-gray-600">
        <p>Need help? <a href="/contact" class="font-medium text-primary-600 hover:text-primary-500">Contact support</a></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
    
    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmationInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
    
    // Password strength validation
    const passwordField = document.getElementById('password');
    
    passwordField.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Length check
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Uppercase check
        if (/[A-Z]/.test(password)) {
            strength += 1;
        }
        
        // Lowercase check
        if (/[a-z]/.test(password)) {
            strength += 1;
        }
        
        // Number check
        if (/[0-9]/.test(password)) {
            strength += 1;
        }
        
        // Special character check
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 1;
        }
        
        // Visual feedback based on strength
        this.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
        
        if (password.length === 0) {
            this.classList.add('border-gray-300');
        } else if (strength < 3) {
            this.classList.add('border-red-500');
        } else if (strength < 5) {
            this.classList.add('border-yellow-500');
        } else {
            this.classList.add('border-green-500');
        }
    });
    
    // Password confirmation match validation
    const confirmPasswordField = document.getElementById('password_confirmation');
    
    confirmPasswordField.addEventListener('input', function() {
        const password = passwordField.value;
        const confirmPassword = this.value;
        
        this.classList.remove('border-red-500', 'border-green-500');
        
        if (confirmPassword.length === 0) {
            this.classList.add('border-gray-300');
        } else if (password === confirmPassword) {
            this.classList.add('border-green-500');
        } else {
            this.classList.add('border-red-500');
        }
    });
});
</script>

<?php 
// Clean up validation errors after rendering the page
if (isset($_SESSION['validation_errors'])) {
    unset($_SESSION['validation_errors']);
}

// No need to include footer.php as it's already included by header.php 
?> 