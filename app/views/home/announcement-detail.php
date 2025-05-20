<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-6">
                            <?php echo htmlspecialchars($announcement->title); ?>
                        </h1>
                        
                        <div class="prose prose-lg max-w-none text-gray-600 mb-8">
                            <?php echo nl2br(htmlspecialchars($announcement->content)); ?>
                        </div>
                        
                        <?php if (!empty($announcement->expire_date)): ?>
                            <div class="mt-8 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                <p class="text-yellow-700 text-sm">
                                    This announcement will expire on <?php echo date('F j, Y', strtotime($announcement->expire_date)); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Share Links (Optional) -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Share this announcement:</h3>
                            <div class="flex space-x-4">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . '/announcement/' . $announcement->id); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    <span class="sr-only">Share on Facebook</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($announcement->title); ?>&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . '/announcement/' . $announcement->id); ?>" target="_blank" class="text-blue-400 hover:text-blue-600">
                                    <span class="sr-only">Share on Twitter</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode($announcement->title . ' - https://' . $_SERVER['HTTP_HOST'] . '/announcement/' . $announcement->id); ?>" target="_blank" class="text-green-600 hover:text-green-800">
                                    <span class="sr-only">Share on WhatsApp</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.27.072.37-.043.101-.116.433-.506.549-.677.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.13.332.202.043.72.043.419-.101.824H15.423z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Back to Announcements -->
                <div class="mt-6">
                    <a href="/announcements" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to all announcements
                    </a>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
                <!-- Recent Announcements -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Announcements</h3>
                        
                        <?php if (empty($recentAnnouncements)): ?>
                            <p class="text-gray-500 text-sm">No other announcements available.</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($recentAnnouncements as $recent): ?>
                                    <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                        <span class="text-xs text-gray-500 block">
                                            <?php 
                                                $recentDate = !empty($recent->publish_date) 
                                                    ? $recent->publish_date 
                                                    : $recent->created_at;
                                                echo date('M j, Y', strtotime($recentDate)); 
                                            ?>
                                        </span>
                                        <a href="/announcement/<?php echo $recent->id; ?>" class="text-gray-700 hover:text-blue-600 font-medium block mt-1">
                                            <?php echo htmlspecialchars($recent->title); ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <a href="/announcements" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View all announcements
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Us</h3>
                        <p class="text-gray-600 mb-4">Have questions about this announcement or need more information?</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-400 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-600">+234 (0) 123 456 7890</span>
                            </div>
                            
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-400 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600">coops@fcetbichi.edu.ng</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <a href="/contact" class="inline-block bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 