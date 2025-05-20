<?php
/**
 * Alerts/Notifications view component
 * Displays flash messages and alerts
 */

use App\Helpers\Session;

// Check for flash messages
$hasFlash = isset($_SESSION['flash']) && !empty($_SESSION['flash']);
$globalFlash = $hasFlash && isset($_SESSION['flash']['global']) ? $_SESSION['flash']['global'] : null;
$pageFlash = $hasFlash && isset($page_identifier) && isset($_SESSION['flash'][$page_identifier]) ? $_SESSION['flash'][$page_identifier] : null;

// Display flash messages if any
if ($globalFlash || $pageFlash):
    $flash = $pageFlash ?: $globalFlash;
    $type = $flash['type'] ?? 'info';
    $message = $flash['message'] ?? '';
    
    // Map flash type to Bootstrap alert class
    $alertClass = 'alert-info';
    $iconClass = 'fa-info-circle';
    
    switch ($type) {
        case 'success':
            $alertClass = 'alert-success';
            $iconClass = 'fa-check-circle';
            break;
        case 'error':
            $alertClass = 'alert-danger';
            $iconClass = 'fa-exclamation-circle';
            break;
        case 'warning':
            $alertClass = 'alert-warning';
            $iconClass = 'fa-exclamation-triangle';
            break;
    }
?>
<div class="alert <?php echo $alertClass; ?> alert-dismissible fade show mb-4" role="alert">
    <i class="fas <?php echo $iconClass; ?> me-2"></i>
    <?php echo htmlspecialchars($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php 
    // Clear the flash message after displaying
    if ($pageFlash) {
        unset($_SESSION['flash'][$page_identifier]);
    } elseif ($globalFlash) {
        unset($_SESSION['flash']['global']);
    }
endif; 
?> 