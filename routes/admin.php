<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminSupportTicketController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\EnsureAdminPasswordUpdated;
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

Route::middleware([AuthenticateAdmin::class, EnsureAdminPasswordUpdated::class])->group(function () {
    // Mandatory Password Change on First Login
    Route::get('/password/change', [AdminAuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/update', [AdminAuthController::class, 'updatePassword'])->name('password.update');

    // Administrator Self-Service Profile & Display Picture
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Dashboard & Operations Overview
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard')->middleware('admin.can:dashboard.view');
    Route::get('/area-analytics', [AdminDashboardController::class, 'areaAnalytics'])->name('area-analytics')->middleware('admin.can:analytics.view');
    Route::get('/alerts/check', [AdminDashboardController::class, 'checkNewAlerts'])->name('alerts.check');

    // Notifications Center
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/clear-read', [AdminNotificationController::class, 'clearRead'])->name('clear-read');
        Route::post('/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
    });

    // Admin Staff & Role Permissions Management
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [AdminStaffController::class, 'index'])->name('index')->middleware('admin.can:staff_management.view');
        Route::get('/create', [AdminStaffController::class, 'create'])->name('create')->middleware('admin.can:staff_management.edit');
        Route::post('/', [AdminStaffController::class, 'store'])->name('store')->middleware('admin.can:staff_management.edit');
        Route::get('/{staff}/edit', [AdminStaffController::class, 'edit'])->name('edit')->middleware('admin.can:staff_management.edit');
        Route::put('/{staff}', [AdminStaffController::class, 'update'])->name('update')->middleware('admin.can:staff_management.edit');
        Route::post('/{staff}/toggle-status', [AdminStaffController::class, 'toggleStatus'])->name('toggle-status')->middleware('admin.can:staff_management.edit');
        Route::post('/{staff}/reset-password', [AdminStaffController::class, 'resetPassword'])->name('reset-password')->middleware('admin.can:staff_management.edit');
        Route::delete('/{staff}', [AdminStaffController::class, 'destroy'])->name('destroy')->middleware('admin.can:staff_management.edit');
    });

    // Activity Logs & Administrative Audit Trail
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])
        ->name('activity-logs.index')
        ->middleware('admin.can:activity_logs.view');

    // Client User Directory & Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index')->middleware('admin.can:users.view');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show')->middleware('admin.can:users.view');
        Route::post('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status')->middleware('admin.can:users.edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update')->middleware('admin.can:users.edit');
    });

    // Bookings Management (Work Orders & Bookings)
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index')->middleware('admin.can:bookings.view');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show')->middleware('admin.can:bookings.view');
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update')->middleware('admin.can:bookings.edit');
    Route::post('/bookings/{booking}/accept', [AdminBookingController::class, 'accept'])->name('bookings.accept')->middleware('admin.can:bookings.edit');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel')->middleware('admin.can:bookings.edit');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status')->middleware('admin.can:bookings.edit');

    // Support Tickets System
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [AdminSupportTicketController::class, 'index'])->name('index')->middleware('admin.can:tickets.view');
        Route::get('/create', [AdminSupportTicketController::class, 'create'])->name('create')->middleware('admin.can:tickets.edit');
        Route::post('/', [AdminSupportTicketController::class, 'store'])->name('store')->middleware('admin.can:tickets.edit');
        Route::get('/{ticket}', [AdminSupportTicketController::class, 'show'])->name('show')->middleware('admin.can:tickets.view');
        Route::post('/{ticket}/reply', [AdminSupportTicketController::class, 'reply'])->name('reply')->middleware('admin.can:tickets.edit');
        Route::post('/{ticket}/status', [AdminSupportTicketController::class, 'updateStatus'])->name('update-status')->middleware('admin.can:tickets.edit');
        Route::delete('/{ticket}', [AdminSupportTicketController::class, 'destroy'])->name('destroy')->middleware('admin.can:tickets.edit');
    });

    // Web Control & Content Management
    Route::prefix('web-control')->name('web.')->group(function () {
        Route::get('/', [AdminCmsController::class, 'webControlHub'])->name('index')->middleware('admin.can:web_content.view');

        // Site Settings & SMTP Gateway
        Route::get('/settings', [AdminCmsController::class, 'settings'])->name('settings')->middleware('admin.can:settings.view');
        Route::post('/settings', [AdminCmsController::class, 'updateSettings'])->name('settings.update')->middleware('admin.can:settings.edit');
        Route::post('/settings/test-smtp', [AdminCmsController::class, 'testSmtp'])->name('settings.test-smtp')->middleware('admin.can:settings.edit');

        // Services & Catalog
        Route::get('/services', [AdminCmsController::class, 'services'])->name('services')->middleware('admin.can:services.view');
        Route::get('/services/{service}/edit', [AdminCmsController::class, 'editService'])->name('services.edit')->middleware('admin.can:services.edit');
        Route::put('/services/{service}', [AdminCmsController::class, 'updateService'])->name('services.update')->middleware('admin.can:services.edit');

        // AMC Packages
        Route::get('/amc-plans', [AdminCmsController::class, 'amcPlans'])->name('amc-plans')->middleware('admin.can:amc_plans.view');
        Route::post('/amc-plans/{plan}', [AdminCmsController::class, 'updateAmcPlan'])->name('amc-plans.update')->middleware('admin.can:amc_plans.edit');

        // Section Contents & Hero
        Route::get('/contents', [AdminCmsController::class, 'contents'])->name('contents')->middleware('admin.can:web_content.view');
        Route::post('/contents/{content}', [AdminCmsController::class, 'updateContent'])->name('contents.update')->middleware('admin.can:web_content.edit');

        // FAQs Knowledgebase
        Route::get('/faqs', [AdminCmsController::class, 'faqs'])->name('faqs')->middleware('admin.can:web_content.view');
        Route::post('/faqs', [AdminCmsController::class, 'storeFaq'])->name('faqs.store')->middleware('admin.can:web_content.edit');
        Route::put('/faqs/{faq}', [AdminCmsController::class, 'updateFaq'])->name('faqs.update')->middleware('admin.can:web_content.edit');
        Route::delete('/faqs/{faq}', [AdminCmsController::class, 'deleteFaq'])->name('faqs.destroy')->middleware('admin.can:web_content.edit');

        // Client Testimonials
        Route::get('/testimonials', [AdminCmsController::class, 'testimonials'])->name('testimonials')->middleware('admin.can:web_content.view');
        Route::post('/testimonials', [AdminCmsController::class, 'storeTestimonial'])->name('testimonials.store')->middleware('admin.can:web_content.edit');
        Route::put('/testimonials/{testimonial}', [AdminCmsController::class, 'updateTestimonial'])->name('testimonials.update')->middleware('admin.can:web_content.edit');
        Route::delete('/testimonials/{testimonial}', [AdminCmsController::class, 'deleteTestimonial'])->name('testimonials.destroy')->middleware('admin.can:web_content.edit');
    });

    // Backward compatibility for legacy cms.* routes
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/settings', [AdminCmsController::class, 'settings'])->name('settings')->middleware('admin.can:settings.view');
        Route::post('/settings', [AdminCmsController::class, 'updateSettings'])->name('settings.update')->middleware('admin.can:settings.edit');
        Route::post('/settings/test-smtp', [AdminCmsController::class, 'testSmtp'])->name('settings.test-smtp')->middleware('admin.can:settings.edit');
        Route::get('/services', [AdminCmsController::class, 'services'])->name('services')->middleware('admin.can:services.view');
        Route::get('/services/{service}/edit', [AdminCmsController::class, 'editService'])->name('services.edit')->middleware('admin.can:services.edit');
        Route::put('/services/{service}', [AdminCmsController::class, 'updateService'])->name('services.update')->middleware('admin.can:services.edit');
    });
});
