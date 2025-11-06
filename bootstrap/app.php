<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Render Optimization: HTTPS & Trusted Proxies
|--------------------------------------------------------------------------
|
| This ensures Laravel correctly detects HTTPS requests behind Render’s
| proxy servers and prevents redirect loops or mixed-content issues.
|
*/

$app->afterBootstrapping(Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class, function ($app) {
    // Force HTTPS in production
    if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }

    // Trust Render's proxies for X-Forwarded headers
    Request::setTrustedProxies(
        ['*'],
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO
    );
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
*/

return $app;
