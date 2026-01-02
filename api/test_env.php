<?php
// Force error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Vercel Environment Diagnostic</h1>";

// 1. Check PHP Version
echo "<h3>PHP Version</h3>";
echo phpversion();

// 2. Check Environment Variables
echo "<h3>Environment Variables Check</h3>";
$vars = [
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASS',
    'DB_CHARSET'
];

echo "<table border='1' cellpadding='5'><tr><th>Variable</th><th>getenv()</th><th>\$_ENV</th></tr>";

foreach ($vars as $var) {
    $val_getenv = getenv($var);
    $val_env = $_ENV[$var] ?? 'NOT SET';
    
    // Mask password
    if ($var === 'DB_PASS') {
        $val_getenv = $val_getenv ? '******** (Length: ' . strlen($val_getenv) . ')' : 'FALSE/EMPTY';
        $val_env = $val_env !== 'NOT SET' ? '********' : 'NOT SET';
    }
    
    echo "<tr><td>$var</td><td>" . var_export($val_getenv, true) . "</td><td>" . var_export($val_env, true) . "</td></tr>";
}
echo "</table>";

// 3. Test Database Connection
echo "<h3>Database Connection Test</h3>";

$host = getenv('DB_HOST');
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

if (!$host || !$user) {
    echo "<p style='color:red'><strong>CRITICAL:</strong> Database credentials are missing from Environment.</p>";
    echo "<p>Please go to Vercel Dashboard -> Settings -> Environment Variables and ensure they are added correctly.</p>";
} else {
    try {
        echo "<p>Attempting connection to <strong>$host</strong>...</p>";
        
        $dsn = "mysql:host=$host;dbname=$name;charset=$charset;connect_timeout=5";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        
        echo "<p style='color:green; font-weight:bold'>SUCCESS: Connected to Database!</p>";
        
        // Test Query
        $stmt = $pdo->query("SELECT @@version");
        $ver = $stmt->fetchColumn();
        echo "<p>Database Version: $ver</p>";
        
    } catch (PDOException $e) {
        echo "<p style='color:red; font-weight:bold'>CONNECTION FAILED</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>
