<?php

use App\Http\Middleware\AuthorizeAdminPermission;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\EnsureAdminPasswordUpdated;
use App\Http\Middleware\TrackWebsiteVisitor;
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
            // Direct path routing for admin command center (/admin)
            Route::prefix('admin')
                ->middleware(['web'])
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            CheckMaintenanceMode::class,
            TrackWebsiteVisitor::class,
        ]);

        $middleware->alias([
            'admin.can' => AuthorizeAdminPermission::class,
            'admin.must_change_password' => EnsureAdminPasswordUpdated::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => str_starts_with($request->getHost(), 'admin.') || $request->is('admin*')
                ? route('admin.login')
                : route('login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
