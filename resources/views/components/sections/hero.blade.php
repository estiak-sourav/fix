@props([
  'content' => null,
  'categories' => [],
  'services' => [],
])

<!-- Hero Section -->
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-content">
      <h1 style="padding-bottom: 160px;">{!! $content->title ?? 'Your Trusted Partner for <span>Every Building</span> Service' !!}</h1>

      <div class="hero-actions">
        <a href="{{ route('booking.create') }}" class="btn btn-accent btn-lg">
          {{ $content->button_text ?? 'Book a Service' }}
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>
        <a href="#services" class="btn btn-outline-white btn-lg">
          {{ $content->secondary_button_text ?? 'Explore Services' }}
        </a>
      </div>

      <div class="trust-badges">
        @php
          $badges = $content->meta_data['trust_badges'] ?? [
            'Verified & Certified Providers',
            'Quick Service Request & Dispatch',
            'Multiple Building Solutions',
            '24/7 Dedicated Operations Support'
          ];
        @endphp
        @foreach($badges as $badge)
          <div class="trust-badge-item">
            <span class="check-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
            </span>
            <span>{{ $badge }}</span>
          </div>
        @endforeach
      </div>
    </div>

    <div class="hero-media">
      <div class="hero-image-wrapper">
        <img src="{{ $content->media_url ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1000&q=80' }}"
          alt="Building Facility Maintenance" loading="eager"
          onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80';">
      </div>

      <!-- Floating Info Cards -->
      <div class="hero-floating-card">
        <div class="floating-card-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
        </div>
        <div class="floating-card-text">
          <h4>{{ $content->meta_data['floating_badge_title'] ?? 'Quality Guaranteed' }}</h4>
          <p>{{ $content->meta_data['floating_badge_text'] ?? '100% Verified Technical Teams & Rigorous Safety' }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Quick Service Selector Strip (Overlapping Hero) -->
<div class="container quick-book-strip">
  <div class="quick-book-box">
    <div class="quick-book-header">
      <h3>
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 14 14"></polyline>
        </svg>
        Instant Service Finder & Booking
      </h3>
    </div>

    <form id="quickBookForm" class="quick-book-form" action="{{ route('booking.create') }}" method="GET">
      <div class="form-group-quick">
        <label for="quickCategory">1. Service Category</label>
        <select id="quickCategory" name="category" class="select-custom">
          <option value="all">All Service Categories</option>
          @foreach($categories as $category)
            <option value="{{ $category->slug }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group-quick">
        <label for="quickService">2. Specific Service</label>
        <select id="quickService" name="service" class="select-custom">
          @foreach($services as $service)
            <option value="{{ $service->title }}" data-category="{{ $service->category?->slug ?? 'maintenance' }}">{{ $service->title }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group-quick">
        <label for="quickProperty">3. Property Type</label>
        <select id="quickProperty" name="property_type" class="select-custom">
          <option value="Corporate Office">Corporate Office</option>
          <option value="Residential Complex / Apartment">Residential Apartment</option>
          <option value="Commercial Building">Commercial Building</option>
          <option value="Hospital / Healthcare">Hospital / Clinic</option>
          <option value="Industrial Factory / EPZ">Factory / Industrial</option>
          <option value="Educational Institution">Educational Campus</option>
        </select>
      </div>

      <div>
        <button type="submit" class="btn btn-accent btn-block" style="padding:0.75rem 1.5rem; margin-top:1.45rem;">
          Book Now
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>
    </form>
  </div>
</div>
