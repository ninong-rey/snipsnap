<?php

define('LARAVEL_START', microtime(true));

// Load Composer's autoloader
require __DIR__.'/../vendor/autoload.php';

// Load the application
$app = require_once __DIR__.'/../bootstrap/app.php';

// Run the HTTP kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
