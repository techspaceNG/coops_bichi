<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Household Items</h1>
                <p class="text-primary-100">Browse and purchase household items through the cooperative society</p>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-shopping-basket text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-blue-800">Available Credit</h2>
                            <p class="text-2xl font-bold text-blue-900">₦<?= number_format($available_credit, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Your available credit for purchasing household items</p>
                    <div class="mt-3">
                        <a href="/household/orders" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-shopping-cart mr-1"></i> View My Orders
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-amber-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-500 bg-opacity-10">
                            <i class="fas fa-box text-amber-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-amber-800">Current Orders</h2>
                            <p class="text-2xl font-bold text-amber-900"><?= $order_count ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Your current active household item orders</p>
                    <div class="mt-3">
                        <a href="/household/orders/pending" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-clock mr-1"></i> Track Pending Orders
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-green-50 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-green-800">Monthly Repayment</h2>
                            <p class="text-2xl font-bold text-green-900">₦<?= number_format($monthly_repayment, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Your current monthly repayment for household items</p>
                    <div class="mt-3">
                        <a href="/household/statement" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-file-invoice mr-1"></i> View Complete Statement
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Categories -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Product Categories</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="/household/categories/electronics" class="block group">
                        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg overflow-hidden aspect-square shadow-md group-hover:shadow-lg transition-all">
                            <div class="h-full w-full p-6 flex flex-col items-center justify-center">
                                <i class="fas fa-tv text-white text-4xl mb-3"></i>
                                <h3 class="text-white font-medium text-center">Electronics</h3>
                                <p class="text-blue-100 text-sm text-center mt-2"><?= $category_counts['electronics'] ?? 0 ?> items</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="/household/categories/furniture" class="block group">
                        <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg overflow-hidden aspect-square shadow-md group-hover:shadow-lg transition-all">
                            <div class="h-full w-full p-6 flex flex-col items-center justify-center">
                                <i class="fas fa-couch text-white text-4xl mb-3"></i>
                                <h3 class="text-white font-medium text-center">Furniture</h3>
                                <p class="text-amber-100 text-sm text-center mt-2"><?= $category_counts['furniture'] ?? 0 ?> items</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="/household/categories/kitchen" class="block group">
                        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg overflow-hidden aspect-square shadow-md group-hover:shadow-lg transition-all">
                            <div class="h-full w-full p-6 flex flex-col items-center justify-center">
                                <i class="fas fa-blender text-white text-4xl mb-3"></i>
                                <h3 class="text-white font-medium text-center">Kitchen Appliances</h3>
                                <p class="text-green-100 text-sm text-center mt-2"><?= $category_counts['kitchen'] ?? 0 ?> items</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="/household/categories/all" class="block group">
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg overflow-hidden aspect-square shadow-md group-hover:shadow-lg transition-all">
                            <div class="h-full w-full p-6 flex flex-col items-center justify-center">
                                <i class="fas fa-th-large text-white text-4xl mb-3"></i>
                                <h3 class="text-white font-medium text-center">All Categories</h3>
                                <p class="text-purple-100 text-sm text-center mt-2"><?= $total_items ?> items</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Featured Products -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Featured Products</h2>
                <a href="/household/products" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                    View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
            
            <div class="p-6">
                <?php if (empty($featured_products)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No featured products available</h3>
                        <p class="text-gray-500 mb-6">Check back later for featured household items or browse all products.</p>
                        <a href="/household/products" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg">
                            Browse All Products
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <?php foreach ($featured_products as $product): ?>
                            <div class="bg-white rounded-lg border overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="relative">
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover">
                                    <?php if ($product['discount_percentage'] > 0): ?>
                                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            <?= $product['discount_percentage'] ?>% OFF
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="font-medium text-gray-900 mb-1"><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="text-gray-500 text-sm mb-2"><?= htmlspecialchars($product['category']) ?></p>
                                    
                                    <div class="flex items-baseline space-x-2 mb-2">
                                        <span class="text-lg font-bold text-gray-900">₦<?= number_format($product['price'], 2) ?></span>
                                        <?php if ($product['original_price'] > $product['price']): ?>
                                            <span class="text-sm text-gray-500 line-through">₦<?= number_format($product['original_price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 mb-3">
                                        <span class="font-medium">Monthly Payment: </span>
                                        <span>₦<?= number_format($product['monthly_payment'], 2) ?> for <?= $product['installment_months'] ?> months</span>
                                    </div>
                                    
                                    <div>
                                        <a href="/household/products/<?= $product['id'] ?>" class="block text-center bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 px-4 rounded">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- How It Works -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">How It Works</h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 mb-4">
                                <i class="fas fa-search text-2xl"></i>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-2">1. Browse & Select</h3>
                            <p class="text-gray-600">Browse through our catalog of household items and select the ones you want to purchase.</p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/3 mb-6 md:mb-0 md:px-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 mb-4">
                                <i class="fas fa-credit-card text-2xl"></i>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-2">2. Place Order</h3>
                            <p class="text-gray-600">Place your order through the platform. No upfront payment required.</p>
                        </div>
                    </div>
                    
                    <div class="md:w-1/3 md:pl-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 mb-4">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                            <h3 class="font-medium text-gray-800 mb-2">3. Easy Repayment</h3>
                            <p class="text-gray-600">Pay in monthly installments that are automatically deducted from your salary.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-800 mb-2">Terms & Conditions</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-1">
                        <li>Maximum credit limit is based on your savings balance and membership duration</li>
                        <li>Monthly installments are automatically deducted from your salary</li>
                        <li>Installment periods range from 6 to 24 months depending on the item value</li>
                        <li>Delivery is arranged by the cooperative society within 2 weeks of order approval</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 