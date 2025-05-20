<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <img class="mx-auto h-16 w-auto" src="<?= $publicUrl ?>/assets/images/logo.png" alt="FCET Bichi Staff Multipurpose Cooperative Society">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Register your account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Complete your registration to access the cooperative society portal
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php if (isset($_SESSION['register_error'])): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Registration Failed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p><?= htmlspecialchars($_SESSION['register_error']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>

            <form class="space-y-6" action="<?= $publicUrl ?>/register/process" method="POST">
                <div>
                    <label for="coop_no" class="block text-sm font-medium text-gray-700">
                        Cooperative Number
                    </label>
                    <div class="mt-1">
                        <input id="coop_no" name="coop_no" type="text" required 
                               placeholder="e.g., COOPS/04/001" 
                               value="<?= isset($_SESSION['old_input']['coop_no']) ? htmlspecialchars($_SESSION['old_input']['coop_no']) : '' ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['coop_no']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($_SESSION['validation_errors']['coop_no'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['coop_no']) ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-xs text-gray-500">Your cooperative number was provided by the cooperative society</p>
                    </div>
                </div>

                <div>
                    <label for="ti_number" class="block text-sm font-medium text-gray-700">
                        TI Number
                    </label>
                    <div class="mt-1">
                        <input id="ti_number" name="ti_number" type="text" required 
                               placeholder="Enter your TI Number" 
                               value="<?= isset($_SESSION['old_input']['ti_number']) ? htmlspecialchars($_SESSION['old_input']['ti_number']) : '' ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['ti_number']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($_SESSION['validation_errors']['ti_number'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['ti_number']) ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-xs text-gray-500">Your Treasury Integrated Number (TI Number) issued by FCET Bichi</p>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Full Name
                    </label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required 
                               placeholder="Enter your full name" 
                               value="<?= isset($_SESSION['old_input']['name']) ? htmlspecialchars($_SESSION['old_input']['name']) : '' ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['name']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($_SESSION['validation_errors']['name'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['name']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="<?= isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : '' ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['email']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($_SESSION['validation_errors']['email'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['email']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Phone Number
                    </label>
                    <div class="mt-1">
                        <input id="phone" name="phone" type="text" required 
                               placeholder="e.g., 08012345678"
                               value="<?= isset($_SESSION['old_input']['phone']) ? htmlspecialchars($_SESSION['old_input']['phone']) : '' ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['phone']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($_SESSION['validation_errors']['phone'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['phone']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">
                        Department
                    </label>
                    <div class="mt-1">
                        <select id="department" name="department" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                                <?= isset($_SESSION['validation_errors']['department']) ? 'border-red-500' : '' ?>">
                            <option value="">Select your department</option>
                            <?php if (isset($departments) && is_array($departments)): ?>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= htmlspecialchars($dept['name']) ?>" 
                                            <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === $dept['name']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Administration" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Administration') ? 'selected' : '' ?>>Administration</option>
                                <option value="Academics" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Academics') ? 'selected' : '' ?>>Academics</option>
                                <option value="Computer Science" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Computer Science') ? 'selected' : '' ?>>Computer Science</option>
                                <option value="Mathematics" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Mathematics') ? 'selected' : '' ?>>Mathematics</option>
                                <option value="Physics" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Physics') ? 'selected' : '' ?>>Physics</option>
                                <option value="Chemistry" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Chemistry') ? 'selected' : '' ?>>Chemistry</option>
                                <option value="Biology" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Biology') ? 'selected' : '' ?>>Biology</option>
                                <option value="Technical Education" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Technical Education') ? 'selected' : '' ?>>Technical Education</option>
                                <option value="Vocational Education" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Vocational Education') ? 'selected' : '' ?>>Vocational Education</option>
                                <option value="Business Education" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Business Education') ? 'selected' : '' ?>>Business Education</option>
                                <option value="Other" <?= (isset($_SESSION['old_input']['department']) && $_SESSION['old_input']['department'] === 'Other') ? 'selected' : '' ?>>Other</option>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($_SESSION['validation_errors']['department'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['department']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
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
                        <p class="mt-2 text-xs text-gray-500">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character</p>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm Password
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

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required 
                               class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded 
                               <?= isset($_SESSION['validation_errors']['terms']) ? 'border-red-500' : '' ?>">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-gray-700">
                            I agree to the 
                            <a href="<?= $publicUrl ?>/terms" class="text-primary-600 hover:text-primary-500">Terms and Conditions</a> 
                            and 
                            <a href="<?= $publicUrl ?>/privacy" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
                        </label>
                        <?php if (isset($_SESSION['validation_errors']['terms'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['terms']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Register Account
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
                            Already have an account?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= $publicUrl ?>/login" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Sign in instead
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md text-center text-sm text-gray-600">
        <p>Need help? <a href="<?= $publicUrl ?>/contact" class="font-medium text-primary-600 hover:text-primary-500">Contact support</a></p>
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
// Clean up old input and validation errors after rendering the page
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}

if (isset($_SESSION['validation_errors'])) {
    unset($_SESSION['validation_errors']);
}
?> 