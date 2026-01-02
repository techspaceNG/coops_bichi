<?php
$publicUrl = \App\Core\Config::getPublicUrl();

require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <img class="mx-auto h-16 w-auto" src="<?= $publicUrl ?>/assets/images/logo.png" alt="FCET Bichi Staff Multipurpose Cooperative Society">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Access your cooperative society portal
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $flash = \App\Helpers\Utility::getFlashMessage('member_login'); ?>
            <?php if ($flash && $flash['type'] === 'error'): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Login Failed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p><?= htmlspecialchars($flash['message']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['account_locked'])): ?>
                <div class="rounded-md bg-yellow-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lock text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Account Locked</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p><?= htmlspecialchars($_SESSION['account_locked']) ?></p>
                                <p class="mt-1">Please contact the administrator for assistance.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['account_locked']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Registration Successful</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p><?= htmlspecialchars($_SESSION['register_success']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>

            <form class="space-y-6" action="<?= $publicUrl ?>/login/process" method="POST">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        Cooperative Number
                    </label>
                    <div class="mt-1">
                        <input id="username" name="username" type="text" autocomplete="username" required 
                               placeholder="e.g., COOPS/04/001"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox" 
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="<?= $publicUrl ?>/forgot-password" class="font-medium text-primary-600 hover:text-primary-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Sign in
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
                            New to the platform?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="<?= $publicUrl ?>/register" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Register your account
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
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        // Toggle the password visibility
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle the icon
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
</script>

