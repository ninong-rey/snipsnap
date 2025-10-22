<?php
echo "<h1>✅ PHP Test - Success!</h1>";
echo "<p>Server Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>If you can see this, PHP and Apache are working correctly.</p>";

// Test Laravel bootstrap
try {
    require __DIR__.'/../vendor/autoload.php';
    echo "<p style='color: green;'>✅ Composer autoloader loaded</p>";
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "<p style='color: green;'>✅ Laravel app bootstrapped</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Laravel error: " . $e->getMessage() . "</p>";
}
?>