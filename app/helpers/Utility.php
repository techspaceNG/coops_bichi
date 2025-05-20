<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Utility Helper
 */
class Utility
{
    /**
     * Format currency value
     *
     * @param float $amount
     * @return string
     */
    public static function formatCurrency(float $amount): string
    {
        return '₦' . number_format($amount, 2);
    }
    
    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function formatDate(string $date, string $format = 'd M, Y'): string
    {
        return date($format, strtotime($date));
    }
    
    /**
     * Generate a random token
     *
     * @param int $length
     * @return string
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Redirect to a URL
     *
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        // Check if the URL is for the application but missing /public
        if (strpos(strtolower($url), '/coops_bichi/') === 0 && strpos(strtolower($url), '/public/') === false) {
            // Insert /public/ after /Coops_Bichi/
            $url = preg_replace('#^(/Coops_Bichi/)#i', '$1public/', $url);
        }
        
        // Ensure we have the correct casing and path format
        if (strpos(strtolower($url), '/coops_bichi') === 0) {
            // Force consistent case for /Coops_Bichi/
            $url = preg_replace('#^/coops_bichi#i', '/Coops_Bichi', $url);
        }
        
        header("Location: $url");
        exit;
    }
    
    /**
     * Set flash message
     *
     * @param string $type
     * @param string $message
     * @param string $page Optional page identifier to limit where message appears
     * @return void
     */
    public static function setFlashMessage(string $type, string $message, string $page = 'global'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['flash'][$page] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get flash message from session
     *
     * @param string $page Optional page identifier to get messages for
     * @return array|null
     */
    public static function getFlashMessage(string $page = 'global'): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $flash = $_SESSION['flash'][$page] ?? null;
        
        // Clear only this page's flash message
        if (isset($_SESSION['flash'][$page])) {
            unset($_SESSION['flash'][$page]);
        }
        
        return $flash;
    }
    
    /**
     * Get pagination data
     *
     * @param int $totalItems
     * @param int $currentPage
     * @param int $perPage
     * @return array
     */
    public static function getPagination(int $totalItems, int $currentPage = 1, int $perPage = 10): array
    {
        $totalPages = ceil($totalItems / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        
        $offset = ($currentPage - 1) * $perPage;
        
        return [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'offset' => $offset
        ];
    }
    
    /**
     * Calculate loan repayment
     *
     * @param float $loanAmount
     * @param float $ipFigure
     * @return array
     */
    public static function calculateLoanRepayment(float $loanAmount, float $ipFigure): array
    {
        $ipNo = ceil($loanAmount / $ipFigure);
        $totalRepayment = $ipFigure * $ipNo;
        
        return [
            'ip_no' => (int)$ipNo,
            'ip_figure' => $ipFigure,
            'total_repayment' => $totalRepayment,
            'balance' => $totalRepayment
        ];
    }
    
    /**
     * Send email
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array $headers
     * @return bool
     */
    public static function sendEmail(string $to, string $subject, string $message, array $headers = []): bool
    {
        $defaultHeaders = [
            'From' => 'noreply@coopsbichi.org',
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=utf-8'
        ];
        
        $mergedHeaders = array_merge($defaultHeaders, $headers);
        $headerString = '';
        
        foreach ($mergedHeaders as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }
        
        return mail($to, $subject, $message, $headerString);
    }
    
    /**
     * Convert file size from bytes to human-readable format
     *
     * @param int $bytes
     * @return string
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Safe JSON encode
     *
     * @param mixed $data
     * @return string
     */
    public static function jsonEncode(mixed $data): string
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
    
    /**
     * Safe JSON decode
     *
     * @param string $json
     * @param bool $assoc
     * @return mixed
     */
    public static function jsonDecode(string $json, bool $assoc = true): mixed
    {
        try {
            return json_decode($json, $assoc, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            error_log("JSON decode error: " . $e->getMessage());
            return $assoc ? [] : new \stdClass();
        }
    }
    
    /**
     * Convert a timestamp to a human-readable time ago format
     *
     * @param string $datetime The datetime string to convert
     * @return string Human-readable time ago
     */
    public static function timeAgo(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        $current_time = time();
        $time_difference = $current_time - $timestamp;
        
        // Define time intervals in seconds
        $intervals = [
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        ];
        
        // Calculate the appropriate time unit
        foreach ($intervals as $seconds => $unit) {
            $interval = floor($time_difference / $seconds);
            
            if ($interval > 0) {
                $plural = $interval === 1 ? '' : 's';
                return "$interval $unit$plural ago";
            }
        }
        
        return 'just now';
    }
} 