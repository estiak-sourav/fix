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

    @php
      $slides = $content?->meta_data['slides'] ?? [];
      if (empty($slides)) {
          if ($content && $content->media_path) {
              $slides = [$content->media_path];
          } else {
              $slides = [
                  'uploads/hero/slide_1.jpg',
                  'uploads/hero/slide_2.jpg',
                  'uploads/hero/slide_3.jpg',
              ];
          }
      }
    @endphp

    <div class="hero-media">
      <div class="hero-image-wrapper hero-slider-wrap" id="heroAutoSlider">
        @foreach($slides as $sIdx => $slide)
          <div class="hero-slide-item {{ $sIdx === 0 ? 'active' : '' }}" data-slide-index="{{ $sIdx }}">
            <img src="{{ asset($slide) }}" alt="FIX Building Service Facility {{ $sIdx + 1 }}" loading="{{ $sIdx === 0 ? 'eager' : 'lazy' }}">
          </div>
        @endforeach

        @if(count($slides) > 1)
          <div class="hero-slide-dots">
            @foreach($slides as $sIdx => $slide)
              <button type="button" class="hero-dot {{ $sIdx === 0 ? 'active' : '' }}" onclick="goToHeroSlide({{ $sIdx }})" aria-label="Slide {{ $sIdx + 1 }}"></button>
            @endforeach
          </div>
        @endif
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

<style>
  .hero-slider-wrap {
    position: relative !important;
    overflow: hidden !important;
    border-radius: var(--radius-lg, 16px) !important;
    width: 100% !important;
    min-height: 420px !important;
    background: #0f172a !important;
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.25) !important;
  }
  .hero-slide-item {
    position: absolute !important;
    inset: 0 !important;
    width: 100% !important;
    height: 100% !important;
    opacity: 0 !important;
    transform: scale(1.05) !important;
    transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), transform 1.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
    pointer-events: none !important;
    z-index: 1 !important;
  }
  .hero-slide-item.active {
    opacity: 1 !important;
    transform: scale(1) !important;
    pointer-events: auto !important;
    z-index: 2 !important;
  }
  .hero-slide-item img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
  }
  .hero-slide-dots {
    position: absolute !important;
    bottom: 18px !important;
    right: 20px !important;
    display: flex !important;
    gap: 8px !important;
    z-index: 10 !important;
    background: rgba(15, 23, 42, 0.65) !important;
    padding: 6px 12px !important;
    border-radius: 20px !important;
    backdrop-filter: blur(8px) !important;
  }
  .hero-dot {
    width: 8px !important;
    height: 8px !important;
    border-radius: 50% !important;
    background: rgba(255, 255, 255, 0.45) !important;
    border: none !important;
    padding: 0 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
  }
  .hero-dot.active {
    width: 24px !important;
    border-radius: 10px !important;
    background: #0d9488 !important;
  }
</style>

<script>
  let currentHeroSlide = 0;
  let heroSlideTimer = null;

  function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (slides.length <= 1) return;

    if (heroSlideTimer) clearInterval(heroSlideTimer);
    heroSlideTimer = setInterval(() => {
      goToHeroSlide((currentHeroSlide + 1) % slides.length);
    }, 4500);
  }

  function goToHeroSlide(index) {
    const slides = document.querySelectorAll('.hero-slide-item');
    const dots = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;

    slides.forEach((slide, i) => {
      if (i === index) {
        slide.classList.add('active');
      } else {
        slide.classList.remove('active');
      }
    });

    dots.forEach((dot, i) => {
      if (i === index) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });

    currentHeroSlide = index;
  }

  document.addEventListener('DOMContentLoaded', initHeroSlider);
  if (document.readyState === 'complete' || document.readyState === 'interactive') {
    initHeroSlider();
  }
</script>

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
