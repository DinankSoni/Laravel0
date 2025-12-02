<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Force JSON response for API routes
        // This prevents Laravel from redirecting to '/' when validation fails on API endpoints
        $exceptions->shouldRenderJsonWhen(function ($request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            
            return $request->expectsJson();
        });
    })->create();
