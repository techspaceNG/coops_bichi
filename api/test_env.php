<?php
// Disable buffering to show output immediately
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);

echo "<h1>Vercel Extended Diagnostic</h1>";
// Force buffer flush with padding
echo str_pad('', 4096, ' ');
flush();

echo "Step 1: PHP Script Started... <br>";
flush();

// Check if we can see the environment
echo "Step 2: Checking Environment... <br>";
flush();

$host = getenv('DB_HOST');
if ($host) {
    echo "DB_HOST is set to: " . $host . " <br>";
} else {
    echo "<strong style='color:red'>DB_HOST is MISSING!</strong> <br>";
}
flush();

// Time limit
set_time_limit(10);
echo "Step 3: Checking Network Connectivity (Port 3306)... <br>";
flush();

$host = getenv('DB_HOST');
$port = 3306;

$start = microtime(true);
$fp = @fsockopen($host, $port, $errno, $errstr, 2);
$end = microtime(true);

if ($fp) {
    echo "<strong style='color:green'>NETWORK SUCCESS: Port 3306 is OPEN.</strong> (Latency: " . round($end - $start, 4) . "s) <br>";
    fclose($fp);
    
    echo "Step 4: Attempting PDO Database Login... <br>";
    flush();
    
    try {
        $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
        $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        echo "<strong style='color:green'>LOGIN SUCCESS: Connected to Database!</strong> <br>";
    } catch (PDOException $e) {
        echo "<strong style='color:red'>LOGIN FAILED:</strong> " . $e->getMessage() . "<br>";
    }

} else {
    echo "<strong style='color:red'>NETWORK FAILED: Unable to reach Port 3306.</strong> (Latency: " . round($end - $start, 4) . "s) <br>";
    echo "Error $errno: $errstr <br>";
    echo "Diagnosis: <strong>Your Database Firewall is blocking Vercel.</strong><br>"; 
}

echo "Step 5: Script Complete. <br>";
?>
