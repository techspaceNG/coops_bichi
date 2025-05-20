<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">About Us</h1>
                <p class="text-primary-100">Learn about the FCET Bichi Staff Multipurpose Cooperative Society</p>
            </div>
        </div>
        
        <!-- About Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-6 md:p-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Story</h2>
                    <div class="space-y-4 text-gray-600">
                        <p>The FCET Bichi Staff Multipurpose Cooperative Society was established in 2005 to cater to the financial needs of staff members of the Federal College of Education (Technical) Bichi. What began as a small savings group has grown into a thriving financial cooperative with over 500 members.</p>
                        <p>Our cooperative society operates based on the principles of mutual aid, democracy, equality, and self-responsibility. We believe in the power of pooling resources to create collective prosperity and provide financial support to our members.</p>
                        <p>Over the years, we have expanded our services from basic savings and loans to include household items financing, investment opportunities, financial education, and more. Our growth reflects our commitment to meeting the evolving needs of our members.</p>
                    </div>
                </div>
                <div class="bg-gray-100 flex items-center justify-center p-10">
                    <div class="relative h-80 w-full md:w-80">
                        <div class="absolute inset-0 rounded-lg overflow-hidden">
                            <img src="/assets/images/cooperative-image.jpg" alt="Cooperative Society Members" class="object-cover w-full h-full">
                            <!-- Fallback if image is not available -->
                            <div class="absolute inset-0 flex items-center justify-center bg-primary-100 text-primary-600">
                                <i class="fas fa-users text-6xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mission and Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Our Mission</h2>
                </div>
                <div class="p-6">
                    <div class="flex h-full">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-bullseye text-xl text-primary-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-600">To improve the economic and social well-being of our members through the provision of affordable financial services, promoting thrift, and encouraging responsible financial management.</p>
                            <p class="mt-4 text-gray-600">We are dedicated to creating a cooperative community that empowers members to achieve financial stability and growth through mutual support and collective action.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">Our Vision</h2>
                </div>
                <div class="p-6">
                    <div class="flex h-full">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-eye text-xl text-primary-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-600">To be the leading cooperative society in the education sector, recognized for excellence in member services, financial stability, and positive impact on members' lives.</p>
                            <p class="mt-4 text-gray-600">We envision a future where every staff member of FCET Bichi enjoys financial security, access to affordable credit, and the benefits of cooperative ownership.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Core Values -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Our Core Values</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-handshake text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Integrity</h3>
                        </div>
                        <p class="text-gray-600">We conduct all operations with honesty, transparency, and accountability. We maintain the highest ethical standards in all our dealings with members and partners.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-users text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Solidarity</h3>
                        </div>
                        <p class="text-gray-600">We believe in the power of collective action. By working together, we can achieve more than what is possible individually. We support one another in times of need.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-balance-scale text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Equity</h3>
                        </div>
                        <p class="text-gray-600">We treat all members fairly and ensure that benefits and services are distributed equitably. We strive to create an inclusive environment where everyone has equal access to opportunities.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-lightbulb text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Innovation</h3>
                        </div>
                        <p class="text-gray-600">We continuously seek to improve our services and processes. We embrace new ideas and technologies to enhance the member experience and operational efficiency.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-shield-alt text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Security</h3>
                        </div>
                        <p class="text-gray-600">We prioritize the security of our members' funds and data. We implement robust systems and processes to safeguard assets and maintain confidentiality.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                <i class="fas fa-graduation-cap text-primary-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Education</h3>
                        </div>
                        <p class="text-gray-600">We believe in empowering our members through financial education. We provide resources and guidance to help members make informed financial decisions.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leadership Team -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Our Leadership Team</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Executive Members -->
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden bg-gray-200">
                            <img src="/assets/images/leader1.jpg" alt="President" class="object-cover w-full h-full">
                            <!-- Fallback -->
                            <div class="absolute inset-0 flex items-center justify-center bg-primary-100 text-primary-600">
                                <i class="fas fa-user text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Dr. Abubakar Mohammed</h3>
                        <p class="text-primary-600 font-medium">President</p>
                        <p class="text-gray-500 text-sm mt-1">Since 2020</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden bg-gray-200">
                            <img src="/assets/images/leader2.jpg" alt="Vice President" class="object-cover w-full h-full">
                            <!-- Fallback -->
                            <div class="absolute inset-0 flex items-center justify-center bg-primary-100 text-primary-600">
                                <i class="fas fa-user text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Mrs. Fatima Ibrahim</h3>
                        <p class="text-primary-600 font-medium">Vice President</p>
                        <p class="text-gray-500 text-sm mt-1">Since 2020</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden bg-gray-200">
                            <img src="/assets/images/leader3.jpg" alt="Secretary" class="object-cover w-full h-full">
                            <!-- Fallback -->
                            <div class="absolute inset-0 flex items-center justify-center bg-primary-100 text-primary-600">
                                <i class="fas fa-user text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Mr. John Adebayo</h3>
                        <p class="text-primary-600 font-medium">Secretary</p>
                        <p class="text-gray-500 text-sm mt-1">Since 2021</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden bg-gray-200">
                            <img src="/assets/images/leader4.jpg" alt="Treasurer" class="object-cover w-full h-full">
                            <!-- Fallback -->
                            <div class="absolute inset-0 flex items-center justify-center bg-primary-100 text-primary-600">
                                <i class="fas fa-user text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Mrs. Amina Yusuf</h3>
                        <p class="text-primary-600 font-medium">Treasurer</p>
                        <p class="text-gray-500 text-sm mt-1">Since 2019</p>
                    </div>
                </div>
                
                <div class="mt-8 text-center">
                    <a href="/leadership" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                        View All Executive Committee Members <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Achievements and Stats -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Our Achievements</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-primary-50 rounded-lg p-6 text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">500+</div>
                        <div class="text-gray-700">Active Members</div>
                    </div>
                    
                    <div class="bg-primary-50 rounded-lg p-6 text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">₦50M+</div>
                        <div class="text-gray-700">Loan Disbursements</div>
                    </div>
                    
                    <div class="bg-primary-50 rounded-lg p-6 text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">15+</div>
                        <div class="text-gray-700">Years of Service</div>
                    </div>
                    
                    <div class="bg-primary-50 rounded-lg p-6 text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">98%</div>
                        <div class="text-gray-700">Member Satisfaction</div>
                    </div>
                </div>
                
                <div class="space-y-4 text-gray-600">
                    <p>Since our establishment, we have achieved significant milestones that have positively impacted our members and the broader FCET Bichi community:</p>
                    
                    <ul class="list-disc list-inside space-y-2">
                        <li>Successfully disbursed over ₦50 million in loans to members for various purposes including education, housing, and business ventures.</li>
                        <li>Maintained a loan recovery rate of over 95%, demonstrating our members' commitment and financial responsibility.</li>
                        <li>Facilitated the acquisition of household items worth millions of Naira, improving the living standards of our members.</li>
                        <li>Consistently paid competitive dividends to members based on their savings contributions.</li>
                        <li>Implemented a digital platform for easier access to cooperative services, enhancing member experience.</li>
                        <li>Conducted numerous financial literacy workshops and seminars for members.</li>
                        <li>Established partnerships with financial institutions to provide additional benefits to our members.</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Join Us -->
        <div class="bg-primary-600 rounded-lg shadow-md overflow-hidden">
            <div class="p-8 text-center">
                <h2 class="text-2xl font-bold text-white mb-4">Join Our Cooperative Society</h2>
                <p class="text-primary-100 max-w-2xl mx-auto mb-6">Become a member of the FCET Bichi Staff Multipurpose Cooperative Society today and enjoy the benefits of cooperative membership. Together, we can achieve financial security and growth.</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/register" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-700 focus:ring-white">
                        Register Now
                    </a>
                    <a href="/contact" class="inline-flex items-center justify-center px-6 py-3 border border-white rounded-md shadow-sm text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-700 focus:ring-white">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 