<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title ?? \App\Models\SiteSetting::get('meta_title', 'FIX - Comprehensive Building & Facility Services in Bangladesh') }}</title>
  <meta name="description" content="{{ $metaDescription ?? \App\Models\SiteSetting::get('meta_description', 'Professional facility management, HVAC chillers, electrical, plumbing, fire safety & AMC services in Dhaka, Bangladesh.') }}">
  <meta name="keywords" content="facility management dhaka, commercial building maintenance bangladesh, hvac servicing, fire safety inspection, amc contract bangladesh">
  @if(\App\Models\SiteSetting::get('search_engine_visibility', '1') == '0')
  <meta name="robots" content="noindex, nofollow">
  @else
  <meta name="robots" content="index, follow">
  @endif
  <link rel="canonical" href="{{ url()->current() }}">
  
  <!-- OpenGraph Meta -->
  <meta property="og:title" content="{{ $title ?? \App\Models\SiteSetting::get('meta_title', 'FIX - Comprehensive Building & Facility Services') }}">
  <meta property="og:description" content="{{ $metaDescription ?? \App\Models\SiteSetting::get('meta_description', 'Certified commercial and residential facility engineering in Bangladesh.') }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset(\App\Models\SiteSetting::get('og_image', 'assets/images/logo-dark-crop.png')) }}">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $title ?? 'FIX - Facility Services' }}">
  <meta name="twitter:description" content="{{ $metaDescription ?? 'Certified facility engineering in Bangladesh.' }}">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}">

  <!-- Google Fonts Preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Core Stylesheet (100% Preserved) -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <!-- JSON-LD Structured Data for Local Business SEO -->
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@type": "LocalBusiness",
    "name": "FIX Facility Management",
    "image": "{{ asset('assets/images/logo-dark-crop.png') }}",
    "telephone": "{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}",
    "email": "{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}",
    "address": {
      "@@type": "PostalAddress",
      "streetAddress": "Plot 12, Road 11, Block D, Banani",
      "addressLocality": "Dhaka",
      "postalCode": "1213",
      "addressCountry": "BD"
    },
    "openingHoursSpecification": {
      "@@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
      "opens": "00:00",
      "closes": "23:59"
    },
    "sameAs": [
      "https://facebook.com/fixbangladesh",
      "https://linkedin.com/company/fix-bangladesh"
    ]
  }
  </script>

  {{ $head ?? '' }}
</head>
<body>

  <!-- Top Announcement Bar -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-left">
        <div class="top-bar-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          <span>Emergency 24/7: </span>
          <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+8801700000000') }}">{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}</a>
        </div>
        <div class="top-bar-item hide-mobile">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}">{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}</a>
        </div>
      </div>
      <div class="top-bar-right">
        <div class="top-bar-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span>Coverage: Dhaka & EPZ Zones</span>
        </div>
      </div>
    </div>
  </div>

  <x-navbar />

  <main id="main-content">
    {{ $slot }}
  </main>

  <x-footer />

  <x-auth-modal />

  <!-- Global Toast Alert Container -->
  <div id="fixToastContainer" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;"></div>

  @if(\App\Models\SiteSetting::get('cookie_consent_enabled', '1') == '1')
  <!-- Cookie Consent Notice Banner -->
  <div id="cookieConsentBanner" style="display:none; position:fixed; bottom:20px; left:20px; right:20px; max-width:640px; margin:0 auto; background:rgba(15,23,42,0.96); border:1px solid rgba(255,255,255,0.15); border-radius:14px; padding:16px 20px; z-index:99998; box-shadow:0 20px 35px -5px rgba(0,0,0,0.5); backdrop-filter:blur(12px); color:#f1f5f9; align-items:center; justify-content:space-between; gap:16px; font-size:0.875rem;">
    <div style="flex:1; line-height:1.5;">
      We use cookies to deliver secure authentication, analyze service performance, and ensure smooth dispatches. By browsing FIX, you agree to our 
      <a href="{{ route('privacy') }}" style="color:#38bdf8; text-decoration:underline;">Privacy Policy</a> and 
      <a href="{{ route('terms') }}" style="color:#38bdf8; text-decoration:underline;">Terms of Service</a>.
    </div>
    <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
      <button type="button" onclick="acceptCookieConsent()" style="background:#2563eb; color:#ffffff; border:none; padding:8px 16px; border-radius:8px; font-weight:600; font-size:0.825rem; cursor:pointer;">Accept</button>
      <button type="button" onclick="dismissCookieConsent()" style="background:none; border:none; color:#94a3b8; font-size:1.3rem; line-height:1; cursor:pointer; padding:0 4px;" aria-label="Dismiss">&times;</button>
    </div>
  </div>
  <script>
    function acceptCookieConsent() {
      localStorage.setItem('fix_cookie_consent', 'accepted');
      const b = document.getElementById('cookieConsentBanner');
      if (b) b.style.display = 'none';
    }
    function dismissCookieConsent() {
      localStorage.setItem('fix_cookie_consent', 'dismissed');
      const b = document.getElementById('cookieConsentBanner');
      if (b) b.style.display = 'none';
    }
    document.addEventListener('DOMContentLoaded', function() {
      if (!localStorage.getItem('fix_cookie_consent')) {
        const b = document.getElementById('cookieConsentBanner');
        if (b) b.style.display = 'flex';
      }
    });
  </script>
  @endif

  <!-- Core Javascript (100% Preserved) -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="{{ asset('assets/js/auth.js') }}?v={{ filemtime(public_path('assets/js/auth.js')) }}"></script>

  {{ $scripts ?? '' }}
</body>
</html>
