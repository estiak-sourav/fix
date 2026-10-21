<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminUserController;
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

    // Notifications Center
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/clear-read', [AdminNotificationController::class, 'clearRead'])->name('clear-read');
        Route::post('/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
    });

    // Client User Directory & Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::post('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
    });

    // Bookings Management (Work Orders & Bookings)
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Web Control & Content Management
    Route::prefix('web-control')->name('web.')->group(function () {
        Route::get('/', [AdminCmsController::class, 'webControlHub'])->name('index');
        
        // Site Settings & SMTP Gateway
        Route::get('/settings', [AdminCmsController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminCmsController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/test-smtp', [AdminCmsController::class, 'testSmtp'])->name('settings.test-smtp');

        // Services & Catalog
        Route::get('/services', [AdminCmsController::class, 'services'])->name('services');
        Route::get('/services/{service}/edit', [AdminCmsController::class, 'editService'])->name('services.edit');
        Route::put('/services/{service}', [AdminCmsController::class, 'updateService'])->name('services.update');

        // AMC Packages
        Route::get('/amc-plans', [AdminCmsController::class, 'amcPlans'])->name('amc-plans');
        Route::post('/amc-plans/{plan}', [AdminCmsController::class, 'updateAmcPlan'])->name('amc-plans.update');

        // Section Contents & Hero
        Route::get('/contents', [AdminCmsController::class, 'contents'])->name('contents');
        Route::post('/contents/{content}', [AdminCmsController::class, 'updateContent'])->name('contents.update');

        // FAQs Knowledgebase
        Route::get('/faqs', [AdminCmsController::class, 'faqs'])->name('faqs');
        Route::post('/faqs', [AdminCmsController::class, 'storeFaq'])->name('faqs.store');
        Route::put('/faqs/{faq}', [AdminCmsController::class, 'updateFaq'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [AdminCmsController::class, 'deleteFaq'])->name('faqs.destroy');

        // Client Testimonials
        Route::get('/testimonials', [AdminCmsController::class, 'testimonials'])->name('testimonials');
        Route::post('/testimonials', [AdminCmsController::class, 'storeTestimonial'])->name('testimonials.store');
        Route::put('/testimonials/{testimonial}', [AdminCmsController::class, 'updateTestimonial'])->name('testimonials.update');
        Route::delete('/testimonials/{testimonial}', [AdminCmsController::class, 'deleteTestimonial'])->name('testimonials.destroy');
    });

    // Backward compatibility for legacy cms.* routes
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/settings', [AdminCmsController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminCmsController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/test-smtp', [AdminCmsController::class, 'testSmtp'])->name('settings.test-smtp');
        Route::get('/services', [AdminCmsController::class, 'services'])->name('services');
        Route::get('/services/{service}/edit', [AdminCmsController::class, 'editService'])->name('services.edit');
        Route::put('/services/{service}', [AdminCmsController::class, 'updateService'])->name('services.update');
    });
});
