<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title ?? \App\Models\SiteSetting::get('meta_title', 'FIX - Comprehensive Building & Facility Services in Bangladesh') }}</title>
  <meta name="description" content="{{ $metaDescription ?? \App\Models\SiteSetting::get('meta_description', 'Professional facility management, HVAC chillers, electrical, plumbing, fire safety & AMC services in Dhaka, Bangladesh.') }}">
  <meta name="keywords" content="facility management dhaka, commercial building maintenance bangladesh, hvac servicing, fire safety inspection, amc contract bangladesh">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">
  
  <!-- OpenGraph Meta -->
  <meta property="og:title" content="{{ $title ?? 'FIX - Comprehensive Building & Facility Services' }}">
  <meta property="og:description" content="{{ $metaDescription ?? 'Certified commercial and residential facility engineering in Bangladesh.' }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('assets/images/logo-dark-crop.png') }}">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $title ?? 'FIX - Facility Services' }}">
  <meta name="twitter:description" content="{{ $metaDescription ?? 'Certified facility engineering in Bangladesh.' }}">

  <meta name="csrf-token" content="{{ csrf_token() }}">

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

  <!-- Core Javascript (100% Preserved) -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="{{ asset('assets/js/auth.js') }}?v={{ filemtime(public_path('assets/js/auth.js')) }}"></script>

  {{ $scripts ?? '' }}
</body>
</html>
