<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Contact Us</h1>
        
        <p class="text-gray-600 text-center mb-12">Get in touch with the FCET Bichi Staff Multipurpose Cooperative Society team for inquiries, support, or feedback.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Contact Information</h2>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Phone</h3>
                            <p class="text-gray-600 mt-1">+234 (0) 123 456 7890</p>
                            <p class="text-gray-600">+234 (0) 987 654 3210</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Email</h3>
                            <p class="text-gray-600 mt-1">coops@fcetbichi.edu.ng</p>
                            <p class="text-gray-600">info@fcetbichi-coops.org</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Address</h3>
                            <p class="text-gray-600 mt-1">FCET Bichi Staff Multipurpose Cooperative Society,</p>
                            <p class="text-gray-600">Federal College of Education (Technical),</p>
                            <p class="text-gray-600">Bichi, Kano State, Nigeria</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Office Hours</h3>
                            <p class="text-gray-600 mt-1">Monday - Friday: 8:00 AM - 4:00 PM</p>
                            <p class="text-gray-600">Closed on Weekends and Public Holidays</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Send us a Message</h2>
                
                <?php if (isset($success)): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p class="font-medium">Message Sent Successfully!</p>
                        <p>Thank you for contacting us. We will get back to you soon.</p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-medium">Error!</p>
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>
                
                <form id="contactForm" action="/contact/submit" method="post" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" id="subject" name="subject" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Find Us</h2>
            <div class="aspect-w-16 aspect-h-9">
                <div class="w-full h-80 bg-gray-200 rounded-lg">
                    <!-- Replace with actual map integration -->
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <p>Google Maps integration will be placed here</p>
                    </div>
                </div>
            </div>
            <p class="text-gray-600 mt-4 text-center">
                Located within the Federal College of Education (Technical) Bichi campus, Kano State.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const contactForm = document.getElementById('contactForm');
    
    contactForm.addEventListener('submit', function(e) {
        let isValid = true;
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();
        
        // Basic validation
        if (name === '') {
            isValid = false;
            showError('name', 'Please enter your name');
        } else {
            removeError('name');
        }
        
        if (email === '') {
            isValid = false;
            showError('email', 'Please enter your email');
        } else if (!isValidEmail(email)) {
            isValid = false;
            showError('email', 'Please enter a valid email address');
        } else {
            removeError('email');
        }
        
        if (message === '') {
            isValid = false;
            showError('message', 'Please enter your message');
        } else {
            removeError('message');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = field.parentNode.querySelector('.error-message');
        
        if (!errorElement) {
            const error = document.createElement('p');
            error.className = 'error-message text-red-600 text-sm mt-1';
            error.textContent = message;
            field.parentNode.appendChild(error);
        } else {
            errorElement.textContent = message;
        }
        
        field.classList.add('border-red-500');
    }
    
    function removeError(fieldId) {
        const field = document.getElementById(fieldId);
        const errorElement = field.parentNode.querySelector('.error-message');
        
        if (errorElement) {
            errorElement.remove();
        }
        
        field.classList.remove('border-red-500');
    }
    
    function isValidEmail(email) {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(email);
    }
});
</script> 