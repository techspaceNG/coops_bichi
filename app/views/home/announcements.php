<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between mb-12">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Announcements & News</h1>
                <p class="text-gray-600">Latest updates and announcements from FCET Bichi Staff Multipurpose Cooperative Society</p>
            </div>
            
            <!-- Categories Filter (Optional) -->
            <div class="mt-4 md:mt-0">
                <select id="categoryFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option value="all">All Categories</option>
                    <option value="general">General</option>
                    <option value="important">Important</option>
                    <option value="event">Events</option>
                </select>
            </div>
        </div>
        
        <?php if (empty($announcements)): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-700 mb-2">No Announcements Available</h2>
                <p class="text-gray-500">There are no announcements to display at this time. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6" id="announcementsList">
                <?php foreach ($announcements as $index => $announcement): ?>
                    <div class="announcement-item bg-white rounded-lg shadow-md overflow-hidden" data-category="<?php echo htmlspecialchars($announcement->category); ?>">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <!-- Category Badge -->
                                <?php $badgeClass = ''; ?>
                                <?php if ($announcement->category === 'important'): ?>
                                    <?php $badgeClass = 'bg-red-100 text-red-800'; ?>
                                <?php elseif ($announcement->category === 'event'): ?>
                                    <?php $badgeClass = 'bg-purple-100 text-purple-800'; ?>
                                <?php else: ?>
                                    <?php $badgeClass = 'bg-blue-100 text-blue-800'; ?>
                                <?php endif; ?>
                                
                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full <?php echo $badgeClass; ?>">
                                    <?php echo ucfirst(htmlspecialchars($announcement->category)); ?>
                                </span>
                                
                                <span class="mx-2 text-gray-300">•</span>
                                
                                <!-- Publication Date -->
                                <span class="text-sm text-gray-500">
                                    <?php 
                                        $dateToShow = !empty($announcement->publish_date) 
                                            ? $announcement->publish_date 
                                            : $announcement->created_at;
                                        echo date('F j, Y', strtotime($dateToShow)); 
                                    ?>
                                </span>
                            </div>
                            
                            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                                <?php echo htmlspecialchars($announcement->title); ?>
                            </h2>
                            
                            <div class="prose prose-sm max-w-none text-gray-600 mb-4">
                                <?php 
                                    // If content is longer than 300 chars, truncate it
                                    $content = $announcement->content;
                                    if (strlen($content) > 300) {
                                        $content = substr($content, 0, 300) . '...';
                                    }
                                    echo nl2br(htmlspecialchars($content)); 
                                ?>
                            </div>
                            
                            <a href="/announcement/<?php echo $announcement->id; ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                Read more
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filter functionality
    const categorySelect = document.getElementById('categoryFilter');
    const announcementItems = document.querySelectorAll('.announcement-item');
    
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            
            announcementItems.forEach(function(item) {
                if (selectedCategory === 'all' || item.dataset.category === selectedCategory) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script> 