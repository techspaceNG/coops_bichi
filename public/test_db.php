<?php
// Display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Set valid execution time
set_time_limit(10); 

echo "<h1>Database Connection Test (v2)</h1>";

// Define paths
define('BASE_DIR', __DIR__ . '/..');

echo "<h3>1. Environment Check</h3>";
// Try to load Environment class
if (!file_exists(BASE_DIR . '/app/helpers/Environment.php')) {
    die("<p style='color:red'>Critical: Environment.php not found.</p>");
}

require_once BASE_DIR . '/app/helpers/Environment.php';

try {
    App\Helpers\Environment::load();
    echo "<p>Environment loaded successfully.</p>";
} catch (Throwable $e) {
    echo "<p style='color:red'>Env Load Error: " . $e->getMessage() . "</p>";
}

// Get Credentials explicitly
$host = App\Helpers\Environment::get('DB_HOST');
$name = App\Helpers\Environment::get('DB_NAME');
$user = App\Helpers\Environment::get('DB_USER');
$pass = App\Helpers\Environment::get('DB_PASS');
$port = 3306;

echo "<pre>Host: " . htmlspecialchars($host) . "\nUser: " . htmlspecialchars($user) . "\nPort: $port</pre>";

echo "<h3>2. TCP Network Test</h3>";
// Test simple TCP connectivity first
$fp = @fsockopen($host, $port, $errno, $errstr, 5);
if (!$fp) {
    echo "<p style='color:red; font-weight:bold'>NETWORK ERROR: Could not connect to host at port $port.</p>";
    echo "<p>Error: $errstr ($errno)</p>";
    echo "<p><em>Possible causes: Firewall blocking outbound to port 3306, DNS resolution failure, or wrong Host.</em></p>";
} else {
    echo "<p style='color:green; font-weight:bold'>SUCCESS: TCP connection established to $host:$port.</p>";
    fclose($fp);
    
    echo "<h3>3. Database Login Test</h3>";
    try {
        $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4;connect_timeout=5";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5 // 5 Seconds SQL timeout
        ];
        
        $start = microtime(true);
        $pdo = new PDO($dsn, $user, $pass, $options);
        $end = microtime(true);
        
        echo "<h2 style='color:green'>FULL SUCCESS! Connected to MySQL Database.</h2>";
        echo "<p>Time taken: " . round($end - $start, 4) . " seconds.</p>";
        
        // Try a query
        $stmt = $pdo->query("SELECT VERSION() as v");
        $row = $stmt->fetch();
        echo "<p>MySQL Version: " . $row['v'] . "</p>";
        
    } catch (PDOException $e) {
        echo "<h2 style='color:red'>LOGIN FAILED</h2>";
        echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
        echo "<p><em>This means we reached the server, but it rejected our login or SSL handshake failed.</em></p>";
    }
}
?>
