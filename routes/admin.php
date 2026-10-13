<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\AuthenticateAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Command Center Routes (admin.{domain} Subdomain & /admin)
|--------------------------------------------------------------------------
| Isolated Multi-Guard Architecture: Uses 'admin' session guard and
| App\Models\Admin provider with dedicated session and authentication cookies.
|--------------------------------------------------------------------------
*/

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::middleware([AuthenticateAdmin::class])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/alerts/check', [AdminDashboardController::class, 'checkNewAlerts'])->name('alerts.check');

    // Bookings Management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Dynamic CMS & Media Management
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/settings', [AdminCmsController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminCmsController::class, 'updateSettings'])->name('settings.update');

        Route::get('/services', [AdminCmsController::class, 'services'])->name('services');
        Route::get('/services/{service}/edit', [AdminCmsController::class, 'editService'])->name('services.edit');
        Route::put('/services/{service}', [AdminCmsController::class, 'updateService'])->name('services.update');
    });
});
