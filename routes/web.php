<?php

use App\Http\Controllers\Web\Auth\OtpAuthController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\CustomerPortalController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PartnerApplicationController;
use App\Http\Controllers\Web\SupportTicketController;
use App\Models\AmcPlan;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
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
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/about-us', function () {
    return redirect()->route('about');
});
Route::get('/contact', function () {
    return redirect('/#contact');
})->name('contact');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.send');

// Services Portfolio & Details Routes
Route::get('/services', function () {
    return redirect('/#services');
})->name('services.index');

Route::get('/services/{slug}', function (string $slug) {
    $service = Service::with('category')
        ->where('slug', $slug)
        ->orWhere('id', is_numeric($slug) ? (int) $slug : 0)
        ->orWhere('title', $slug)
        ->first();

    if (! $service) {
        $service = Service::with('category')
            ->whereRaw('LOWER(slug) = ?', [strtolower($slug)])
            ->orWhereRaw('LOWER(title) = ?', [strtolower($slug)])
            ->first();
    }

    if (! $service) {
        $service = Service::with('category')
            ->where('title', 'like', "%{$slug}%")
            ->orWhere('slug', 'like', "%{$slug}%")
            ->first() ?? Service::where('is_active', true)->firstOrFail();
    }

    $relatedServices = Service::where('id', '!=', $service->id)->where('is_active', true)->take(3)->get();

    return view('web.pages.service-details', compact('service', 'relatedServices'));
})->name('services.show');

Route::get('/service/{slug}', function (string $slug) {
    return redirect()->route('services.show', ['slug' => $slug]);
});

// Service Request & Booking (both /booking and /book)
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::get('/book', [BookingController::class, 'create'])->name('book');
Route::get('/booking.html', function (Request $request) {
    return redirect()->route('booking.create', $request->query());
});
Route::get('/book-now', function (Request $request) {
    return redirect()->route('booking.create', $request->query());
});
Route::get('/booking/{service}', function (string $service) {
    return redirect()->route('booking.create', ['service' => $service]);
});
Route::get('/book/{service}', function (string $service) {
    return redirect()->route('booking.create', ['service' => $service]);
});
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::post('/book', [BookingController::class, 'store']);

Route::get('/vendor', function () {
    return view('web.pages.vendor');
})->name('vendor');
Route::post('/vendor/apply', [PartnerApplicationController::class, 'store'])->name('vendor.apply');
Route::post('/partner/register', [PartnerApplicationController::class, 'store'])->name('partner.register');

// Web Login Redirect (triggers modal on booking page or returns to intended redirect target)
Route::get('/login', function (Request $request) {
    $redirectUrl = $request->query('redirect', $request->query('return_url'));
    if ($redirectUrl) {
        $sep = str_contains($redirectUrl, '?') ? '&' : '?';

        return redirect($redirectUrl.$sep.'require_login=1')->with('trigger_auth_modal', true);
    }

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
    Route::post('/{bookingNumber}/review', [CustomerPortalController::class, 'submitReview'])->name('bookings.review');
});

Route::middleware('auth:web')->prefix('profile')->name('customer.')->group(function () {
    Route::get('/', [CustomerPortalController::class, 'profile'])->name('profile');
    Route::put('/', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/avatar', [CustomerPortalController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/avatar', [CustomerPortalController::class, 'removeAvatar'])->name('profile.avatar.remove');
});

/*
|--------------------------------------------------------------------------
| Public Support Ticket Routes (Guest & User Accessible)
|--------------------------------------------------------------------------
*/
Route::prefix('support')->name('support.')->group(function () {
    Route::get('/', [SupportTicketController::class, 'create'])->name('create');
    Route::post('/', [SupportTicketController::class, 'store'])->name('store');
    Route::match(['get', 'post'], '/track', [SupportTicketController::class, 'track'])->name('track');
    Route::get('/tickets/{ticketNumber}', [SupportTicketController::class, 'show'])->name('show');
    Route::post('/tickets/{ticketNumber}/reply', [SupportTicketController::class, 'reply'])->name('reply');
});

/*
|--------------------------------------------------------------------------
| Customer Portal Support Tickets (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->prefix('my-tickets')->name('customer.tickets.')->group(function () {
    Route::get('/', [SupportTicketController::class, 'customerIndex'])->name('index');
    Route::get('/{ticketNumber}', [SupportTicketController::class, 'customerShow'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Legal & Compliance Pages
|--------------------------------------------------------------------------
*/
Route::get('/terms', function () {
    return view('web.pages.legal', [
        'type' => 'terms',
        'title' => 'Terms of Service',
    ]);
})->name('terms');

Route::get('/privacy', function () {
    return view('web.pages.legal', [
        'type' => 'privacy',
        'title' => 'Privacy Policy',
    ]);
})->name('privacy');

Route::get('/sla', function () {
    return view('web.pages.legal', [
        'type' => 'sla',
        'title' => 'Service Level Agreement (SLA) & Guarantee',
    ]);
})->name('sla');

Route::get('/sitemap', function () {
    $services = Service::with('category')->where('is_active', true)->orderBy('order')->get();
    $categories = ServiceCategory::where('is_active', true)->orderBy('order')->get();
    $amcPlans = AmcPlan::where('is_active', true)->orderBy('order')->get();

    return view('web.pages.sitemap', compact('services', 'categories', 'amcPlans'));
})->name('sitemap');
