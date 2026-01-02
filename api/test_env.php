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
echo "Step 3: Attempting Database Connection (Timeout: 5s)... <br>";
flush();

$start = microtime(true);

try {
    $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    
    $end = microtime(true);
    echo "<strong style='color:green'>SUCCESS! Connected in " . round($end - $start, 4) . " seconds.</strong> <br>";
    
} catch (PDOException $e) {
    $end = microtime(true);
    echo "<strong style='color:red'>FAILED in " . round($end - $start, 4) . " seconds.</strong> <br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "Step 4: Script Complete. <br>";
?>
