<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Enables Sanctum-like stateful API if you still need it
        $middleware->statefulApi();

        // Optionally, if you have custom JWT middleware:
        // $middleware->append(App\Http\Middleware\JwtMiddleware::class);
    })
    ->withProviders([
        LaravelServiceProvider::class, // Register Tymon JWTAuth
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
