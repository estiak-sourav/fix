<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            $adminDomain = config('app.admin_domain', 'admin.localhost');

            // 1. Subdomain Routing (admin.domain.com and admin.localhost)
            Route::domain($adminDomain)
                ->middleware(['web'])
                ->as('admin.')
                ->group(base_path('routes/admin.php'));

            // 2. Direct path fallback for local dev (http://127.0.0.1:8000/admin)
            Route::prefix('admin')
                ->middleware(['web'])
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (Request $request) =>
            str_starts_with($request->getHost(), 'admin.') || $request->is('admin*')
                ? route('admin.login')
                : route('login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
