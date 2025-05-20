<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Page Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Purchase Shares</h1>
                        <p class="text-gray-600 mt-1">Buy new shares in the cooperative</p>
                    </div>
                    <a href="/Coops_Bichi/public/member/shares" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Shares
                    </a>
                </div>
            </div>

            <!-- Contact Admin Message -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center py-8">
                    <div class="text-blue-500 mb-4">
                        <i class="fas fa-info-circle text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Please Contact Admin</h2>
                    <p class="text-gray-600 mb-6">To purchase shares, please contact the cooperative administrator directly.</p>
                    <p class="text-gray-600 mb-6">The administrator will guide you through the share purchase process and provide you with all necessary information.</p>
                    <div class="mt-6">
                        <a href="/Coops_Bichi/public/member/shares" class="btn btn-primary">
                            <i class="fas fa-arrow-left mr-2"></i> Return to Shares
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 