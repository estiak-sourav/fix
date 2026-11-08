<?php

use App\Http\Middleware\AuthenticatePartner;
use App\Http\Middleware\AuthorizeAdminPermission;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\EnsureAdminPasswordUpdated;
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

            // Dedicated routing for Partner/Vendor Portal (/partner)
            Route::prefix('partner')
                ->middleware(['web'])
                ->as('partner.')
                ->group(base_path('routes/partner.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            CheckMaintenanceMode::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'analytics/ping',
        ]);

        $middleware->alias([
            'auth.partner' => AuthenticatePartner::class,
            'admin.can' => AuthorizeAdminPermission::class,
            'admin.must_change_password' => EnsureAdminPasswordUpdated::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('partner*')) {
                return route('partner.login');
            }

            if (str_starts_with($request->getHost(), 'admin.') || $request->is('admin*')) {
                return route('admin.login');
            }

            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('partner*') || Auth::guard('partner')->check()) {
                return route('partner.dashboard');
            }

            if (str_starts_with($request->getHost(), 'admin.') || $request->is('admin*') || Auth::guard('admin')->check()) {
                return route('admin.dashboard');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
