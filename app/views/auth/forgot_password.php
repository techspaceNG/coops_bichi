<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-100">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <img class="mx-auto h-16 w-auto" src="/assets/images/logo.png" alt="FCET Bichi Staff Multipurpose Cooperative Society">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Forgot your password?</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email address and we'll send you a link to reset your password
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php if (isset($_SESSION['status'])): ?>
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Email Sent</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p><?= htmlspecialchars($_SESSION['status']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['status']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p><?= htmlspecialchars($_SESSION['error']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="space-y-6" action="/auth/forgot-password" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               placeholder="Enter your registered email address"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm
                               <?= isset($_SESSION['validation_errors']['email']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($_SESSION['validation_errors']['email'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= htmlspecialchars($_SESSION['validation_errors']['email']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Send Password Reset Link
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

                <div class="mt-6 flex justify-center space-x-4 text-sm">
                    <a href="/auth/login" class="font-medium text-primary-600 hover:text-primary-500">
                        Back to login
                    </a>
                    <span class="text-gray-500">|</span>
                    <a href="/auth/register" class="font-medium text-primary-600 hover:text-primary-500">
                        Register an account
                    </a>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-700">Can't access your email?</h3>
                <p class="mt-1 text-sm text-gray-500">If you no longer have access to your registered email address, please contact the administrative office directly with your cooperative number and valid identification.</p>
                <a href="/contact" class="mt-2 inline-flex text-sm font-medium text-primary-600 hover:text-primary-500">
                    Contact Support <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php 
// Clean up validation errors after rendering the page
if (isset($_SESSION['validation_errors'])) {
    unset($_SESSION['validation_errors']);
}

// No need to include footer.php as it's already included by header.php 
?> 