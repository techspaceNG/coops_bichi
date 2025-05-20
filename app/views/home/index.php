<?php
// Calculate base URL 
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = '';

// If we're in a subdirectory, extract it properly
if ($scriptDir !== '/' && $scriptDir !== '\\') {
    $baseUrl = $scriptDir;
    
    // Handle XAMPP-specific case
    if (strpos(strtolower($baseUrl), '/public') !== false) {
        $baseUrl = substr($baseUrl, 0, strpos(strtolower($baseUrl), '/public'));
        
        // Force consistent case
        if (strtolower($baseUrl) === '/coops_bichi') {
            $baseUrl = '/Coops_Bichi';
        }
    }
}

// Additional fallback
if (empty($baseUrl)) {
    $requestUrl = $_SERVER['REQUEST_URI'];
    $lowerRequestUrl = strtolower($requestUrl);
    if (strpos($lowerRequestUrl, '/coops_bichi/') === 0) {
        $baseUrl = '/Coops_Bichi';
    }
}

// Add /public to the base URL
$publicUrl = $baseUrl . '/public';
?>

<!-- Hero Section -->
<section class="bg-primary-700 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">FCET Bichi Staff Multipurpose Cooperative Society</h1>
                <p class="text-xl mb-6">Empowering staff through financial cooperation and mutual support.</p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="<?= $publicUrl ?>/register" class="bg-white text-primary-700 hover:bg-gray-100 font-semibold py-3 px-6 rounded-lg text-center">Register Now</a>
                    <a href="<?= $publicUrl ?>/login" class="bg-transparent border-2 border-white hover:bg-white/10 font-semibold py-3 px-6 rounded-lg text-center">Member Login</a>
                </div>
            </div>
            <div class="md:w-1/2">
                <img src="<?= $publicUrl ?>/assets/images/heroimage.jpg" alt="Nigerian Cooperative Society" class="rounded-lg shadow-xl w-full h-auto object-cover">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Our Services</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Loan Feature -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
                <div class="text-primary-600 mb-4">
                    <i class="fas fa-hand-holding-usd text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Loans</h3>
                <p class="text-gray-600 mb-4">Access to flexible loans with competitive admin charges for various needs including personal and emergency purposes.</p>
                <a href="<?= $publicUrl ?>/login" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Apply for Loan
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>
            
            <!-- Savings Feature -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
                <div class="text-primary-600 mb-4">
                    <i class="fas fa-piggy-bank text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Savings</h3>
                <p class="text-gray-600 mb-4">Build your financial security through our systematic savings program with attractive returns on your investments.</p>
                <a href="<?= $publicUrl ?>/login" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    View Savings
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>
            
            <!-- Household Purchase Feature -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
                <div class="text-primary-600 mb-4">
                    <i class="fas fa-home text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Household Purchases</h3>
                <p class="text-gray-600 mb-4">Acquire household items through our convenient purchase program with flexible payment terms.</p>
                <a href="<?= $publicUrl ?>/login" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Learn More
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Join Our Cooperative Society Today</h2>
        <p class="text-gray-600 max-w-3xl mx-auto mb-8">Become a member of FCET Bichi Staff Multipurpose Cooperative Society and enjoy financial benefits, support, and resources for your personal and professional growth.</p>
        <a href="<?= $publicUrl ?>/register" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg inline-block">Register Now</a>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">What Our Members Say</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-600 mb-6">"The loan program provided by the cooperative helped me finance my children's education. The process was straightforward and the repayment terms were very reasonable."</p>
                <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full h-10 w-10 flex items-center justify-center text-gray-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold">Ahmed Ibrahim</h4>
                        <p class="text-gray-500 text-sm">Lecturer</p>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="text-gray-600 mb-6">"I've been able to furnish my home through the household purchase program. The cooperative's terms were much better than what I could find elsewhere."</p>
                <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full h-10 w-10 flex items-center justify-center text-gray-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold">Fatima Mohammed</h4>
                        <p class="text-gray-500 text-sm">Administrator</p>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <p class="text-gray-600 mb-6">"The savings program has helped me build a financial safety net. I appreciate how the cooperative has made it easy to save consistently through automatic deductions."</p>
                <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full h-10 w-10 flex items-center justify-center text-gray-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold">John Okafor</h4>
                        <p class="text-gray-500 text-sm">Technical Staff</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 