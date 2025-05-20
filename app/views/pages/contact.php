<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Contact Us</h1>
                <p class="text-primary-100">Get in touch with the FCET Bichi Staff Multipurpose Cooperative Society</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Contact Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Send Us a Message</h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if (isset($_SESSION['contact_success'])): ?>
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            <?= htmlspecialchars($_SESSION['contact_success']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php unset($_SESSION['contact_success']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['contact_error'])): ?>
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            <?= htmlspecialchars($_SESSION['contact_error']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php unset($_SESSION['contact_error']); ?>
                        <?php endif; ?>
                        
                        <form action="/contact/submit" method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" id="name" name="name" placeholder="Enter your full name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300" required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" id="email" name="email" placeholder="Enter your email address" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300">
                                <p class="mt-1 text-sm text-gray-500">Optional but recommended for urgent queries</p>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <select id="subject" name="subject" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300" required>
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="membership">Membership</option>
                                    <option value="loans">Loans</option>
                                    <option value="savings">Savings</option>
                                    <option value="household">Household Items</option>
                                    <option value="technical">Technical Support</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea id="message" name="message" rows="5" placeholder="Enter your message here" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300" required></textarea>
                            </div>
                            
                            <?php if (isset($captcha) && $captcha): ?>
                                <div>
                                    <label for="captcha" class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                                    <div class="flex space-x-4">
                                        <div class="bg-gray-200 px-4 py-2 rounded-lg text-center w-32">
                                            <span class="text-lg font-bold text-gray-700 tracking-wider"><?= $captcha_code ?></span>
                                        </div>
                                        <input type="text" id="captcha" name="captcha" placeholder="Enter the code" class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 border-gray-300" required>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Enter the code shown to verify you're not a robot</p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center">
                                <input id="privacy_policy" name="privacy_policy" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" required>
                                <label for="privacy_policy" class="ml-2 block text-sm text-gray-900">
                                    I agree to the <a href="/privacy-policy" class="text-primary-600 hover:text-primary-900">Privacy Policy</a> and consent to the processing of my data.
                                </label>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-lg transition duration-150 ease-in-out">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Contact Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-xl text-primary-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-md font-medium text-gray-900">Office Address</h3>
                                    <p class="mt-1 text-gray-600">Cooperative Society Office<br>Federal College of Education (Technical)<br>Bichi, Kano State</p>
                                </div>
                            </div>
                            
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-phone-alt text-xl text-primary-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-md font-medium text-gray-900">Phone Numbers</h3>
                                    <p class="mt-1 text-gray-600">+234 xxx xxx xxxx (General Inquiries)<br>+234 xxx xxx xxxx (Membership)<br>+234 xxx xxx xxxx (Loans)</p>
                                </div>
                            </div>
                            
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-envelope text-xl text-primary-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-md font-medium text-gray-900">Email Addresses</h3>
                                    <p class="mt-1 text-gray-600">info@coopsbichi.org (General Inquiries)<br>membership@coopsbichi.org (Membership)<br>loans@coopsbichi.org (Loans)</p>
                                </div>
                            </div>
                            
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-clock text-xl text-primary-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-md font-medium text-gray-900">Office Hours</h3>
                                    <p class="mt-1 text-gray-600">Monday - Friday: 8:00 AM - 4:00 PM<br>Saturday: 9:00 AM - 1:00 PM<br>Sunday & Public Holidays: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Follow Us</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="h-10 w-10 rounded-full bg-blue-600 hover:bg-blue-700 transition-colors flex items-center justify-center">
                                <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-blue-400 hover:bg-blue-500 transition-colors flex items-center justify-center">
                                <i class="fab fa-twitter text-white"></i>
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-red-600 hover:bg-red-700 transition-colors flex items-center justify-center">
                                <i class="fab fa-instagram text-white"></i>
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-green-600 hover:bg-green-700 transition-colors flex items-center justify-center">
                                <i class="fab fa-whatsapp text-white"></i>
                            </a>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-gray-600 text-sm">Connect with us on social media for updates and announcements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
            <div class="border-b px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Our Location</h2>
            </div>
            
            <div class="p-6">
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <!-- Replace with actual Google Maps iframe when available -->
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt text-4xl mb-3"></i>
                            <p>Google Maps Embed would be displayed here</p>
                            <p class="text-sm mt-2">Federal College of Education (Technical), Bichi, Kano State</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="https://maps.google.com" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-900">
                        <i class="fas fa-directions mr-1"></i> Get Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 