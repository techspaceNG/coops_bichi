<?php
// Minimal Probe
header("Content-Type: text/plain");
echo "HELLO WORLD - IF YOU SEE THIS, PHP IS WORKING.\n";
echo "Server Time: " . date('Y-m-d H:i:s') . "\n";

$host = getenv('DB_HOST');
echo "DB_HOST Configured: " . ($host ? "YES ($host)" : "NO") . "\n";

// Test DNS resolution explicitly
echo "Attempting DNS Resolution of DB_HOST...\n";
$ip = gethostbyname($host);
echo "Resolved to: $ip\n";

if ($ip == $host) {
    echo "DNS LOOKUP FAILED! (IP matches Hostname)\n";
} else {
    echo "DNS LOOKUP SUCCESS!\n";
}
?>
