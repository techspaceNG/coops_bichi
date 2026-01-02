<?php
// SSL Connection Probe
// Force aggressive flushing
if (function_exists('apache_setenv')) { @apache_setenv('no-gzip', 1); }
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);

echo "<h1>SSL Connection Diagnostic</h1>";
echo str_pad('', 4096, ' '); // Buffer flush
flush();

echo "Step 1: Script Started.<br>";
flush();

$host = getenv('DB_HOST');
$db = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

if (!$host) {
    echo "ERROR: DB_HOST not set.<br>";
    exit;
}

// Timeout
set_time_limit(15);
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

// TEST 1: SSL WITH NO VERIFICATION
echo "Step 2: Attempting SSL Connection (No Verify)...<br>";
flush();

$start = microtime(true);
try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    $end = microtime(true);
    echo "<strong style='color:green'>SSL SUCCESS!</strong> (Time: " . round($end - $start, 4) . "s)<br>";
    
    $stmt = $pdo->query("SELECT @@version");
    echo "Database Version: " . $stmt->fetchColumn() . "<br>";
    echo "Connection Secured: YES <br>";
    exit; // Stop here if custom
    
} catch (PDOException $e) {
    echo "<strong style='color:red'>SSL FAILED:</strong> " . $e->getMessage() . "<br>";
}

// TEST 2: PLAIN CONNECTION (Just in case)
echo "Step 3: Attempting Plain Connection (Fallback)...<br>";
flush();

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<strong style='color:green'>PLAIN SUCCESS!</strong><br>";
} catch (PDOException $e) {
    echo "<strong style='color:red'>PLAIN FAILED:</strong> " . $e->getMessage() . "<br>";
}

echo "Diagnostic Complete.<br>";
?>
