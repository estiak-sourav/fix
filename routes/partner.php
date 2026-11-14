<?php

use App\Http\Controllers\Partner\Auth\PartnerAuthController;
use App\Http\Controllers\Partner\PartnerAvailabilityController;
use App\Http\Controllers\Partner\PartnerCrewController;
use App\Http\Controllers\Partner\PartnerDashboardController;
use App\Http\Controllers\Partner\PartnerJobController;
use App\Http\Controllers\Partner\PartnerNotificationController;
use App\Http\Controllers\Partner\PartnerPayoutController;
use App\Http\Controllers\Partner\PartnerProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Partner & Vendor Portal Routes (/partner/*)
|--------------------------------------------------------------------------
| Isolated Multi-Guard Architecture: Uses 'partner' session guard.
|--------------------------------------------------------------------------
*/

// Public Auth Routes
Route::get('/login', [PartnerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PartnerAuthController::class, 'login'])->name('login.submit');

// Public Partner & Crew Policies
Route::get('/policy', function () {
    return view('web.pages.partner-policy', [
        'title' => 'Crew Policies & Partner Guidelines',
        'activeTab' => request('tab', 'terms'),
    ]);
})->name('policy');

Route::get('/terms', function () {
    return view('web.pages.partner-policy', [
        'title' => 'Crew & Partner Terms of Service',
        'activeTab' => 'terms',
    ]);
})->name('terms');

Route::get('/work-policy', function () {
    return view('web.pages.partner-policy', [
        'title' => 'Crew Work Policy & Safety SOP',
        'activeTab' => 'work',
    ]);
})->name('work-policy');

Route::get('/privacy-policy', function () {
    return view('web.pages.partner-policy', [
        'title' => 'Crew Privacy & Data Protection Policy',
        'activeTab' => 'privacy',
    ]);
})->name('privacy');

Route::get('/payout-policy', function () {
    return view('web.pages.partner-policy', [
        'title' => 'Crew Payout & Earnings Settlement Policy',
        'activeTab' => 'payout',
    ]);
})->name('payout-policy');

// Authenticated Partner Routes
Route::middleware(['auth.partner'])->group(function () {
    Route::post('/logout', [PartnerAuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('partner.dashboard');
    });

    // Command Center
    Route::get('/dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/availability/toggle', [PartnerDashboardController::class, 'toggleAvailability'])->name('availability.toggle');

    // Jobs & Work Orders
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [PartnerJobController::class, 'index'])->name('index');
        Route::get('/{booking}', [PartnerJobController::class, 'show'])->name('show');
        Route::post('/{booking}/accept', [PartnerJobController::class, 'accept'])->name('accept');
        Route::post('/{booking}/decline', [PartnerJobController::class, 'decline'])->name('decline');
        Route::post('/{booking}/status', [PartnerJobController::class, 'updateStatus'])->name('status');
        Route::post('/{booking}/crew', [PartnerJobController::class, 'assignInternalCrew'])->name('crew');
        Route::post('/{booking}/price', [PartnerJobController::class, 'updatePrice'])->name('price');
    });

    // Availability & Leave Schedules
    Route::prefix('availability')->name('availability.')->group(function () {
        Route::get('/', [PartnerAvailabilityController::class, 'index'])->name('index');
        Route::post('/leave', [PartnerAvailabilityController::class, 'storeLeave'])->name('leave.store');
        Route::delete('/leave/{leave}', [PartnerAvailabilityController::class, 'destroyLeave'])->name('leave.destroy');
    });

    // Internal Field Technicians (Vendor Crew Management)
    Route::prefix('crews')->name('crews.')->group(function () {
        Route::get('/', [PartnerCrewController::class, 'index'])->name('index');
        Route::post('/', [PartnerCrewController::class, 'store'])->name('store');
        Route::put('/{crew}', [PartnerCrewController::class, 'update'])->name('update');
        Route::delete('/{crew}', [PartnerCrewController::class, 'destroy'])->name('destroy');
    });

    // Payouts & Earnings & Payment Methods
    Route::get('/payouts', [PartnerPayoutController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/request', [PartnerPayoutController::class, 'requestPayout'])->name('payouts.request');
    Route::post('/payment-methods', [PartnerPayoutController::class, 'storePaymentMethod'])->name('payment-methods.store');
    Route::put('/payment-methods/{paymentMethod}', [PartnerPayoutController::class, 'updatePaymentMethod'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [PartnerPayoutController::class, 'destroyPaymentMethod'])->name('payment-methods.destroy');
    Route::post('/payment-methods/{paymentMethod}/default', [PartnerPayoutController::class, 'setDefaultPaymentMethod'])->name('payment-methods.default');

    // In-App Notification Center
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [PartnerNotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [PartnerNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::match(['get', 'post'], '/{notification}/read', [PartnerNotificationController::class, 'markAsRead'])->name('read');
        Route::get('/unread-count', [PartnerNotificationController::class, 'unreadCount'])->name('unread-count');
    });

    // Profile & Email Notification Preferences
    Route::get('/profile', [PartnerProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [PartnerProfileController::class, 'update'])->name('profile.update');
});
