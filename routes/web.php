<?php

use App\Http\Controllers\Web\Auth\OtpAuthController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\CustomerPortalController;
use App\Http\Controllers\Web\HomeController;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Main User-Facing Domain)
|--------------------------------------------------------------------------
| Isolated Multi-Guard Architecture: Uses 'web' session guard and
| App\Models\User provider.
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.send');

Route::get('/services/{slug}', function (string $slug) {
    $service = Service::with('category')->where('slug', $slug)->firstOrFail();
    $relatedServices = Service::where('id', '!=', $service->id)->where('is_active', true)->take(3)->get();
    return view('web.pages.service-details', compact('service', 'relatedServices'));
})->name('services.show');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

Route::get('/vendor', function () {
    return view('web.pages.vendor');
})->name('vendor');

// Web Login Redirect (triggers modal on booking page)
Route::get('/login', function () {
    return redirect()->route('booking.create')->with('trigger_auth_modal', true);
})->name('login');

/*
|--------------------------------------------------------------------------
| Customer Email OTP Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/send-otp', [OtpAuthController::class, 'sendOtp'])->name('send-otp');
    Route::post('/verify-otp', [OtpAuthController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('/logout', [OtpAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Customer Portal (Authenticated Web Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->prefix('my-bookings')->name('customer.')->group(function () {
    Route::get('/', [CustomerPortalController::class, 'myBookings'])->name('bookings');
    Route::get('/{bookingNumber}', [CustomerPortalController::class, 'showBooking'])->name('bookings.show');
});

Route::middleware('auth:web')->prefix('profile')->name('customer.')->group(function () {
    Route::get('/', [CustomerPortalController::class, 'profile'])->name('profile');
    Route::put('/', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');
});
