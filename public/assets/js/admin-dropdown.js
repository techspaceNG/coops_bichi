/**
 * Admin Profile Dropdown Functionality
 * Handles toggling the profile dropdown menu in the admin header
 */
document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown toggle
    const profileDropdownButton = document.getElementById('profile-dropdown-button');
    const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
    
    if (profileDropdownButton && profileDropdownMenu) {
        // Toggle dropdown on button click
        profileDropdownButton.addEventListener('click', function() {
            const expanded = profileDropdownMenu.classList.contains('hidden');
            profileDropdownMenu.classList.toggle('hidden');
            profileDropdownButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!profileDropdownButton.contains(event.target) && 
                !profileDropdownMenu.contains(event.target)) {
                profileDropdownMenu.classList.add('hidden');
                profileDropdownButton.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Mobile menu toggle (if exists)
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const expanded = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            });
        }
    } else {
        console.warn('Admin dropdown elements not found in the DOM');
    }
}); 