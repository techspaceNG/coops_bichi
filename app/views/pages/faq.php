<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Frequently Asked Questions</h1>
                <p class="text-primary-100">Find answers to common questions about the FCET Bichi Staff Multipurpose Cooperative Society</p>
            </div>
        </div>
        
        <!-- FAQ Categories -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Categories</h2>
                    </div>
                    
                    <div class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="#general" class="block px-4 py-2 rounded-md bg-primary-50 text-primary-700 font-medium">
                                    General Information
                                </a>
                            </li>
                            <li>
                                <a href="#membership" class="block px-4 py-2 rounded-md hover:bg-gray-50 text-gray-700 hover:text-primary-700">
                                    Membership
                                </a>
                            </li>
                            <li>
                                <a href="#savings" class="block px-4 py-2 rounded-md hover:bg-gray-50 text-gray-700 hover:text-primary-700">
                                    Savings & Contributions
                                </a>
                            </li>
                            <li>
                                <a href="#loans" class="block px-4 py-2 rounded-md hover:bg-gray-50 text-gray-700 hover:text-primary-700">
                                    Loans
                                </a>
                            </li>
                            <li>
                                <a href="#household" class="block px-4 py-2 rounded-md hover:bg-gray-50 text-gray-700 hover:text-primary-700">
                                    Household Items
                                </a>
                            </li>
                            <li>
                                <a href="#dividends" class="block px-4 py-2 rounded-md hover:bg-gray-50 text-gray-700 hover:text-primary-700">
                                    Dividends & Returns
                                </a>
                            </li>
                            <li>
                                <a href="#account" class="block px-4 py-2 rounded-md hover:bg-gray-50 text-gray-700 hover:text-primary-700">
                                    Account Management
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-span-1 md:col-span-3">
                <!-- General Information -->
                <div id="general" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">General Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="pt-6 first:pt-0">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">What is the FCET Bichi Staff Multipurpose Cooperative Society?</h3>
                                <div class="text-gray-600">
                                    <p>The FCET Bichi Staff Multipurpose Cooperative Society is a financial cooperative organization owned and managed by staff members of the Federal College of Education (Technical) Bichi. It was established to promote thrift and provide financial assistance to its members through savings and loans services.</p>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">How is the cooperative society managed?</h3>
                                <div class="text-gray-600">
                                    <p>The cooperative society is managed by an elected executive committee consisting of a President, Vice President, Secretary, Treasurer, and other officers. The committee is responsible for the day-to-day operations and decision-making of the society, in accordance with the bylaws approved by members during the Annual General Meeting (AGM).</p>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">What are the benefits of being a member?</h3>
                                <div class="text-gray-600">
                                    <p>Members of the cooperative society enjoy numerous benefits, including:</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Access to low-interest loans</li>
                                        <li>Secure savings facility with competitive returns</li>
                                        <li>Opportunity to purchase household items on installment</li>
                                        <li>Annual dividends based on savings contribution</li>
                                        <li>Financial education and support</li>
                                        <li>Community of like-minded colleagues</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">When and where are cooperative meetings held?</h3>
                                <div class="text-gray-600">
                                    <p>The cooperative society holds quarterly general meetings, usually on the last Saturday of the third month of each quarter. The Annual General Meeting (AGM) is held in December. All meetings take place at the FCET Bichi main auditorium, unless otherwise communicated to members.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Membership -->
                <div id="membership" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800">Membership</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="pt-6 first:pt-0">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Who is eligible to become a member?</h3>
                                <div class="text-gray-600">
                                    <p>All permanent staff members of the Federal College of Education (Technical) Bichi are eligible to become members of the cooperative society. This includes academic and non-academic staff who have completed their probation period.</p>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">How do I become a member?</h3>
                                <div class="text-gray-600">
                                    <p>To become a member, you need to:</p>
                                    <ol class="list-decimal list-inside mt-2 space-y-1">
                                        <li>Complete the membership application form (available at the cooperative office or downloadable from the portal)</li>
                                        <li>Submit the completed form along with required documents (staff ID, passport photograph, etc.)</li>
                                        <li>Pay the registration fee of ₦2,000</li>
                                        <li>Commit to a minimum monthly contribution of ₦5,000</li>
                                        <li>Upon approval, your membership becomes active and monthly deductions will commence from your salary</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Can I withdraw my membership?</h3>
                                <div class="text-gray-600">
                                    <p>Yes, membership is voluntary and you can withdraw at any time by submitting a formal withdrawal letter to the cooperative society. However, you must settle any outstanding loans or obligations before your withdrawal is processed. Upon approval, your savings balance will be paid out to you after deducting any applicable fees.</p>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">What happens to my membership if I leave FCET Bichi?</h3>
                                <div class="text-gray-600">
                                    <p>If you leave the employment of FCET Bichi, you will need to settle any outstanding loans and apply for withdrawal of your membership. Your savings balance will be paid out to you after all obligations are settled. In special cases, members who leave may maintain their membership subject to executive committee approval and alternative payment arrangements.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- More FAQ sections would be added in a similar format -->
                
                <!-- Contact for more questions -->
                <div class="bg-primary-50 rounded-lg shadow-md overflow-hidden p-6">
                    <h2 class="text-lg font-semibold text-primary-800 mb-3">Can't find an answer to your question?</h2>
                    <p class="text-primary-700 mb-4">If you couldn't find the information you're looking for, please contact us through any of the following channels:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-primary-600 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-primary-800">Email</h3>
                                <p class="text-primary-700">info@coopsbichi.org</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone-alt text-primary-600 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-primary-800">Phone</h3>
                                <p class="text-primary-700">+234 xxx xxx xxxx</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-primary-600 mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-primary-800">Office</h3>
                                <p class="text-primary-700">Cooperative Society Office, FCET Bichi Campus</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="/contact" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for smooth scrolling to anchors -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryLinks = document.querySelectorAll('a[href^="#"]');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 20,
                    behavior: 'smooth'
                });
                
                // Update active state
                document.querySelectorAll('a[href^="#"]').forEach(el => {
                    el.classList.remove('bg-primary-50', 'text-primary-700', 'font-medium');
                    el.classList.add('hover:bg-gray-50', 'text-gray-700', 'hover:text-primary-700');
                });
                
                this.classList.remove('hover:bg-gray-50', 'text-gray-700', 'hover:text-primary-700');
                this.classList.add('bg-primary-50', 'text-primary-700', 'font-medium');
            }
        });
    });
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 