<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/household" class="text-sm font-medium text-gray-700 hover:text-primary-600">
                        <i class="fas fa-home mr-2"></i> Household
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                        <a href="/household/categories/<?= strtolower($product['category']) ?>" class="text-sm font-medium text-gray-700 hover:text-primary-600">
                            <?= htmlspecialchars($product['category']) ?>
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 truncate max-w-xs">
                            <?= htmlspecialchars($product['name']) ?>
                        </span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Product Details -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Product Images -->
                <div class="p-6 bg-gray-50">
                    <div class="relative mb-4 h-80 rounded-lg overflow-hidden">
                        <img id="mainImage" src="<?= htmlspecialchars($product['image_url']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="w-full h-full object-contain">
                        
                        <?php if ($product['discount_percentage'] > 0): ?>
                            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                <?= $product['discount_percentage'] ?>% OFF
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($product['stock'] <= 0): ?>
                            <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                                <span class="text-white text-xl font-bold">Out of Stock</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($product['additional_images'])): ?>
                        <div class="grid grid-cols-4 gap-2">
                            <div class="cursor-pointer border-2 border-primary-500 rounded overflow-hidden">
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                     onclick="changeImage('<?= htmlspecialchars($product['image_url']) ?>')"
                                     alt="<?= htmlspecialchars($product['name']) ?> - Main" 
                                     class="w-full h-20 object-contain">
                            </div>
                            
                            <?php foreach ($product['additional_images'] as $index => $image): ?>
                                <div class="cursor-pointer border-2 border-gray-200 hover:border-primary-500 rounded overflow-hidden">
                                    <img src="<?= htmlspecialchars($image) ?>" 
                                         onclick="changeImage('<?= htmlspecialchars($image) ?>')"
                                         alt="<?= htmlspecialchars($product['name']) ?> - Image <?= $index + 1 ?>" 
                                         class="w-full h-20 object-contain">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Product Information -->
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 mr-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= round($product['rating'])): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i - 0.5 <= $product['rating']): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <span class="text-gray-600 text-sm">(<?= $product['review_count'] ?> reviews)</span>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-baseline space-x-2">
                            <span class="text-3xl font-bold text-gray-900">₦<?= number_format($product['price'], 2) ?></span>
                            <?php if ($product['original_price'] > $product['price']): ?>
                                <span class="text-xl text-gray-500 line-through">₦<?= number_format($product['original_price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Includes all taxes and fees</p>
                    </div>
                    
                    <div class="border-t border-b py-4 mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">Installment Options</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center bg-blue-50 p-3 rounded">
                                <div>
                                    <span class="font-medium">₦<?= number_format($product['monthly_payment'], 2) ?>/month</span>
                                    <span class="text-sm text-gray-600 ml-2">for <?= $product['installment_months'] ?> months</span>
                                </div>
                                <span class="text-sm px-2 py-1 bg-blue-100 text-blue-800 rounded">Recommended</span>
                            </div>
                            
                            <?php if (!empty($product['installment_options'])): ?>
                                <?php foreach ($product['installment_options'] as $option): ?>
                                    <div class="flex justify-between items-center p-3 border rounded">
                                        <div>
                                            <span class="font-medium">₦<?= number_format($option['monthly_payment'], 2) ?>/month</span>
                                            <span class="text-sm text-gray-600 ml-2">for <?= $option['months'] ?> months</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center text-sm mb-1">
                            <i class="<?= $product['stock'] > 0 ? 'fas fa-check-circle text-green-500' : 'fas fa-times-circle text-red-500' ?> mr-1"></i>
                            <span><?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></span>
                            
                            <?php if ($product['stock'] > 0 && $product['stock'] <= 5): ?>
                                <span class="ml-2 text-red-600">(Only <?= $product['stock'] ?> left)</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center text-sm">
                            <i class="fas fa-truck text-gray-500 mr-1"></i>
                            <span>Delivery: 1-2 weeks</span>
                        </div>
                    </div>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <form action="/household/cart/add" method="POST" class="mb-6">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <input type="hidden" name="product_price" value="<?= $product['price'] ?>">
                            
                            <?php if (!empty($product['variations'])): ?>
                                <div class="mb-4">
                                    <label for="variation" class="block text-sm font-medium text-gray-700 mb-1">Select Variant</label>
                                    <select id="variation" name="variation" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <?php foreach ($product['variations'] as $variation): ?>
                                            <option value="<?= htmlspecialchars($variation['id']) ?>">
                                                <?= htmlspecialchars($variation['name']) ?> 
                                                <?php if ($variation['price_difference'] > 0): ?>
                                                    (+₦<?= number_format($variation['price_difference'], 2) ?>)
                                                <?php elseif ($variation['price_difference'] < 0): ?>
                                                    (-₦<?= number_format(abs($variation['price_difference']), 2) ?>)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-4">
                                <label for="installment_period" class="block text-sm font-medium text-gray-700 mb-1">Installment Period</label>
                                <select id="installment_period" name="installment_period" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="<?= $product['installment_months'] ?>" selected>
                                        <?= $product['installment_months'] ?> months (₦<?= number_format($product['monthly_payment'], 2) ?>/month)
                                    </option>
                                    <?php if (!empty($product['installment_options'])): ?>
                                        <?php foreach ($product['installment_options'] as $option): ?>
                                            <option value="<?= $option['months'] ?>">
                                                <?= $option['months'] ?> months (₦<?= number_format($option['monthly_payment'], 2) ?>/month)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <?php if ($can_purchase): ?>
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded transition duration-150 ease-in-out">
                                    Place Order
                                </button>
                            <?php else: ?>
                                <button type="button" class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded cursor-not-allowed" disabled>
                                    Insufficient Credit Limit
                                </button>
                                <p class="text-sm text-red-600 mt-2">
                                    Your available credit limit is ₦<?= number_format($available_credit, 2) ?>, which is less than the product price.
                                </p>
                            <?php endif; ?>
                        </form>
                        
                        <div class="flex space-x-2">
                            <button type="button" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-150 ease-in-out">
                                <i class="far fa-heart mr-1"></i> Add to Wishlist
                            </button>
                            <button type="button" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition duration-150 ease-in-out">
                                <i class="fas fa-share-alt mr-1"></i> Share
                            </button>
                        </div>
                    <?php else: ?>
                        <button type="button" class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded cursor-not-allowed mb-4" disabled>
                            Out of Stock
                        </button>
                        
                        <button type="button" class="w-full border border-primary-600 text-primary-600 hover:bg-primary-50 font-medium py-3 px-4 rounded transition duration-150 ease-in-out">
                            Notify Me When Available
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b">
                <div class="flex flex-wrap -mb-px">
                    <button id="descTab" onclick="showTab('description')" class="tab-button active">
                        Description
                    </button>
                    <button id="specTab" onclick="showTab('specifications')" class="tab-button">
                        Specifications
                    </button>
                    <button id="reviewsTab" onclick="showTab('reviews')" class="tab-button">
                        Reviews (<?= $product['review_count'] ?>)
                    </button>
                </div>
            </div>
            
            <div id="description" class="tab-content active p-6">
                <div class="prose prose-sm max-w-none">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </div>
                
                <?php if (!empty($product['features'])): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Key Features</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                            <?php foreach ($product['features'] as $feature): ?>
                                <li><?= htmlspecialchars($feature) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            
            <div id="specifications" class="tab-content p-6 hidden">
                <?php if (!empty($product['specifications'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($product['specifications'] as $category => $specs): ?>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3"><?= htmlspecialchars($category) ?></h3>
                                <div class="bg-gray-50 rounded-lg overflow-hidden">
                                    <dl>
                                        <?php foreach ($specs as $key => $value): ?>
                                            <div class="px-4 py-3 <?= $loop_index % 2 == 0 ? 'bg-white' : 'bg-gray-50' ?> flex">
                                                <dt class="text-sm font-medium text-gray-500 w-1/2"><?= htmlspecialchars($key) ?></dt>
                                                <dd class="text-sm text-gray-900 w-1/2"><?= htmlspecialchars($value) ?></dd>
                                            </div>
                                        <?php endforeach; ?>
                                    </dl>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No detailed specifications available for this product.</p>
                <?php endif; ?>
            </div>
            
            <div id="reviews" class="tab-content p-6 hidden">
                <?php if (!empty($product['reviews'])): ?>
                    <div class="space-y-6">
                        <?php foreach ($product['reviews'] as $review): ?>
                            <div class="border-b pb-6 last:border-b-0 last:pb-0">
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 mr-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($review['title']) ?></div>
                                </div>
                                
                                <div class="mb-2">
                                    <p class="text-gray-600"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="font-medium"><?= htmlspecialchars($review['name']) ?></span>
                                    <span class="mx-1">•</span>
                                    <span><?= date('M d, Y', strtotime($review['date'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="far fa-star text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No reviews yet</h3>
                        <p class="text-gray-500">Be the first to review this product.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Related Products</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <?php foreach ($related_products as $rel_product): ?>
                            <div class="bg-white rounded-lg border overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="relative">
                                    <img src="<?= htmlspecialchars($rel_product['image_url']) ?>" alt="<?= htmlspecialchars($rel_product['name']) ?>" class="w-full h-48 object-cover">
                                    <?php if ($rel_product['discount_percentage'] > 0): ?>
                                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            <?= $rel_product['discount_percentage'] ?>% OFF
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="font-medium text-gray-900 mb-1 truncate"><?= htmlspecialchars($rel_product['name']) ?></h3>
                                    
                                    <div class="flex items-baseline space-x-2 mb-2">
                                        <span class="text-lg font-bold text-gray-900">₦<?= number_format($rel_product['price'], 2) ?></span>
                                        <?php if ($rel_product['original_price'] > $rel_product['price']): ?>
                                            <span class="text-sm text-gray-500 line-through">₦<?= number_format($rel_product['original_price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 mb-3">
                                        <span>₦<?= number_format($rel_product['monthly_payment'], 2) ?>/month</span>
                                    </div>
                                    
                                    <div>
                                        <a href="/household/products/<?= $rel_product['id'] ?>" class="block text-center bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 px-4 rounded">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function changeImage(imageUrl) {
    document.getElementById('mainImage').src = imageUrl;
    
    // Update border highlight for thumbnails
    const thumbnails = document.querySelectorAll('.cursor-pointer');
    thumbnails.forEach(thumb => {
        if (thumb.querySelector('img').src === imageUrl) {
            thumb.classList.add('border-primary-500');
            thumb.classList.remove('border-gray-200');
        } else {
            thumb.classList.remove('border-primary-500');
            thumb.classList.add('border-gray-200');
        }
    });
}

function showTab(tabName) {
    // Hide all tabs
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('active');
    });
    
    // Show selected tab
    const activeTab = document.getElementById(tabName);
    activeTab.classList.remove('hidden');
    activeTab.classList.add('active');
    
    // Update tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });
    
    // Highlight the active tab button
    document.getElementById(tabName + 'Tab').classList.add('active');
}
</script>

<style>
.tab-button {
    @apply px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700;
}
.tab-button.active {
    @apply border-primary-600 text-primary-600;
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 