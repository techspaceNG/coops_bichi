<?php require_once APP_ROOT . '/views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">My Notifications</h1>
                    <p class="text-gray-600 mt-1">View and manage your notifications</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <?php if (!empty($notifications)): ?>
                    <a href="/Coops_Bichi/public/member/notifications/mark-all-read" 
                       class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm mark-all-read-btn"
                       data-action="mark-all-read">
                        Mark All as Read
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-1">No Notifications</h3>
                    <p class="text-gray-500">You don't have any notifications at the moment.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="p-4 border rounded-lg flex items-start <?= $notification['is_read'] ? 'bg-white' : 'bg-blue-50' ?>">
                            <!-- Notification Icon based on type -->
                            <div class="mr-4">
                                <?php 
                                $type = $notification['type'] ?? 'info';
                                if ($type === 'success'): ?>
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-500">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                <?php elseif ($type === 'warning'): ?>
                                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-500">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                <?php elseif ($type === 'error'): ?>
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-500">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-500">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-medium text-gray-800"><?= htmlspecialchars($notification['title']) ?></h3>
                                    <div class="text-sm text-gray-500">
                                        <?= date('M d, Y', strtotime($notification['created_at'])) ?>
                                    </div>
                                </div>
                                <p class="text-gray-600 mt-1"><?= htmlspecialchars($notification['message']) ?></p>
                                
                                <!-- Action buttons -->
                                <div class="mt-3 flex items-center justify-between">
                                    <?php if (isset($notification['link']) && $notification['link']): ?>
                                        <a href="<?= formatNotificationLink($notification['link']) ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    <?php else: ?>
                                        <span></span>
                                    <?php endif; ?>
                                    
                                    <?php if (!$notification['is_read']): ?>
                                        <!-- Debug info -->
                                        <div class="text-xs text-gray-400 mb-1">ID: <?= $notification['id'] ?>, Read: <?= $notification['is_read'] ? 'Yes' : 'No' ?></div>
                                        
                                        <a href="/Coops_Bichi/public/member/notifications/mark-read/<?= $notification['id'] ?>" 
                                           class="text-sm text-gray-500 hover:text-gray-700 mark-read-btn" 
                                           data-id="<?= $notification['id'] ?>"
                                           data-action="mark-read">
                                            Mark as Read
                                        </a>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-400">Read</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Toast notification container -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Notification handlers initialized');
    
    // Enhanced toast notification function
    function showToast(message, type = 'success', duration = 4000) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `transform translate-x-full opacity-0 transition-all duration-500 rounded-lg p-4 mb-4 flex items-center shadow-lg`;
        
        // Set toast style based on type
        let iconClass, bgClass, textClass, borderClass;
        switch(type) {
            case 'success':
                iconClass = 'fas fa-check-circle';
                bgClass = 'bg-green-50';
                textClass = 'text-green-800';
                borderClass = 'border-l-4 border-green-500';
                break;
            case 'error':
                iconClass = 'fas fa-exclamation-circle';
                bgClass = 'bg-red-50';
                textClass = 'text-red-800';
                borderClass = 'border-l-4 border-red-500';
                break;
            case 'warning':
                iconClass = 'fas fa-exclamation-triangle';
                bgClass = 'bg-yellow-50';
                textClass = 'text-yellow-800';
                borderClass = 'border-l-4 border-yellow-500';
                break;
            case 'info':
            default:
                iconClass = 'fas fa-info-circle';
                bgClass = 'bg-blue-50';
                textClass = 'text-blue-800';
                borderClass = 'border-l-4 border-blue-500';
                break;
        }
        
        toast.classList.add(bgClass, textClass, borderClass);
        
        // Create toast content
        const content = `
            <div class="mr-3 text-xl">
                <i class="${iconClass}"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium">${message}</p>
            </div>
            <button class="ml-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        toast.innerHTML = content;
        
        // Add close button functionality
        const closeBtn = toast.querySelector('button');
        closeBtn.addEventListener('click', () => {
            dismissToast(toast);
        });
        
        // Add to container
        const container = document.getElementById('toast-container');
        container.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 10);
        
        // Auto dismiss after duration
        const timeoutId = setTimeout(() => {
            dismissToast(toast);
        }, duration);
        
        // Store timeout ID on element to cancel if manually closed
        toast._timeoutId = timeoutId;
        
        // Return toast element in case needed for reference
        return toast;
    }
    
    // Helper function to dismiss toast with animation
    function dismissToast(toast) {
        // Clear any existing timeout
        if (toast._timeoutId) {
            clearTimeout(toast._timeoutId);
        }
        
        // Animate out
        toast.classList.add('opacity-0', 'translate-x-full');
        
        // Remove after animation completes
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }
    
    // Handle mark as read for individual notifications
    const markReadButtons = document.querySelectorAll('.mark-read-btn');
    
    markReadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the URL from the button
            const url = this.getAttribute('href');
            const notificationId = this.getAttribute('data-id');
            
            // Store reference to the notification container
            const notificationContainer = this.closest('.p-4.border.rounded-lg');
            
            // Get notification title for better feedback
            const notificationTitle = notificationContainer.querySelector('h3').textContent.trim();
            const shortTitle = notificationTitle.length > 30 
                ? notificationTitle.substring(0, 30) + '...' 
                : notificationTitle;
            
            // Add a loading state
            this.innerHTML = '<span class="inline-block animate-pulse">Processing...</span>';
            this.classList.add('opacity-70');
            
            // Make the AJAX request
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/javascript, */*; q=0.01',
                    'Cache-Control': 'no-cache'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                
                // Show detailed success message
                showToast(`"${shortTitle}" marked as read successfully`, 'success');
                
                // Update UI
                if (notificationContainer) {
                    notificationContainer.classList.remove('bg-blue-50');
                    notificationContainer.classList.add('bg-white');
                    
                    // Replace the button with "Read" text
                    this.parentNode.innerHTML = '<span class="text-sm text-gray-400">Read</span>';
                }
                
                // Update notification counts if needed
                updateNotificationCount();
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = 'Mark as Read';
                this.classList.remove('opacity-70');
                showToast(`Failed to mark notification as read: ${error.message}`, 'error');
            });
        });
    });

    // Handle mark all as read
    const markAllReadButton = document.querySelector('.mark-all-read-btn');
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the URL from the button
            const url = this.getAttribute('href');
            
            // Add a loading state
            this.innerHTML = '<span class="inline-block animate-pulse">Processing...</span>';
            this.classList.add('opacity-70');
            
            // Make the AJAX request
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/javascript, */*; q=0.01',
                    'Cache-Control': 'no-cache'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                
                // Show success message with count
                const count = data.affected_rows || 0;
                if (count > 0) {
                    showToast(`✅ Success! ${count} notification${count !== 1 ? 's' : ''} marked as read`, 'success');
                } else {
                    showToast('All notifications are already marked as read', 'info');
                }
                
                // Update all unread notifications in the UI
                document.querySelectorAll('.bg-blue-50').forEach(el => {
                    el.classList.remove('bg-blue-50');
                    el.classList.add('bg-white');
                    
                    // Find and replace all "Mark as Read" buttons
                    const markButton = el.querySelector('.mark-read-btn');
                    if (markButton) {
                        const parentNode = markButton.parentNode;
                        parentNode.innerHTML = '<span class="text-sm text-gray-400">Read</span>';
                    }
                });
                
                // Hide the "Mark All as Read" button after success
                this.style.display = 'none';
                
                // Update notification counts if needed
                updateNotificationCount();
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = 'Mark All as Read';
                this.classList.remove('opacity-70');
                showToast(`❌ Error: Failed to mark notifications as read. ${error.message}`, 'error');
            });
        });
    }
    
    // Function to update notification count in the header
    function updateNotificationCount() {
        const countBadge = document.querySelector('.notification-count');
        if (countBadge) {
            countBadge.textContent = '0';
            countBadge.style.display = 'none';
        }
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?> 