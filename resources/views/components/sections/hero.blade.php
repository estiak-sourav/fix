@props([
  'content' => null,
  'categories' => [],
  'services' => [],
])

<!-- Hero Section -->
<section class="hero" id="hero" data-section="hero" data-section-key="hero">
  <div id="hero-section" class="section-anchor-target"></div>
  <div class="container hero-grid">
    <div class="hero-content">
      <h1>{!! $content->title ?? 'Your Trusted Partner for <span>Every Building</span> Service' !!}</h1>

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
    aspect-ratio: 4 / 3 !important;
    min-height: unset !important;
    height: auto !important;
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
    object-position: center 25% !important;
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
  .hero-floating-card {
    padding: 0.55rem 0.95rem !important;
    gap: 0.7rem !important;
    max-width: 280px !important;
    bottom: -20px !important;
    left: -15px !important;
  }
  .floating-card-icon {
    width: 34px !important;
    height: 34px !important;
    border-radius: 8px !important;
  }
  .floating-card-icon svg {
    width: 17px !important;
    height: 17px !important;
  }
  .floating-card-text h4 {
    font-size: 0.85rem !important;
    margin-bottom: 2px !important;
    line-height: 1.2 !important;
  }
  .floating-card-text p {
    font-size: 0.72rem !important;
    line-height: 1.25 !important;
    margin-bottom: 0 !important;
  }

  @media (min-width: 992px) {
    .hero-media {
      width: 100% !important;
      max-width: 520px !important;
      margin-left: auto !important;
    }
  }

  /* Responsive Rules for Tablet & Mobile */
  @media (max-width: 991px) {
    .hero {
      padding: 2.25rem 0 3.5rem !important;
    }
    .hero-grid {
      display: flex !important;
      flex-direction: column !important;
      gap: 0 !important;
    }
    .hero-content {
      display: contents !important;
    }
    .hero-content h1 {
      order: 1 !important;
      text-align: center !important;
      font-size: 2.05rem !important;
      line-height: 1.22 !important;
      margin: 0 auto 1.15rem auto !important;
      padding-bottom: 0 !important;
      max-width: 540px !important;
    }
    /* Image section placed above booking service button */
    .hero-media {
      order: 2 !important;
      width: 100% !important;
      max-width: 380px !important;
      margin: 0 auto 2.85rem auto !important;
      position: relative !important;
    }
    .hero-slider-wrap {
      aspect-ratio: 4 / 3 !important;
      min-height: unset !important;
      height: auto !important;
      max-height: unset !important;
      border-radius: 14px !important;
    }
    /* Floating Quality Guaranteed Card slightly overlapping bottom-left of image (like desktop view) */
    .hero-floating-card {
      position: absolute !important;
      bottom: -28px !important;
      left: -8px !important;
      right: auto !important;
      margin-top: 0 !important;
      width: auto !important;
      max-width: 220px !important;
      padding: 0.42rem 0.68rem !important;
      gap: 0.5rem !important;
      background: rgba(255, 255, 255, 0.96) !important;
      backdrop-filter: blur(10px) !important;
      box-shadow: 0 10px 22px -4px rgba(0, 0, 0, 0.22), 0 2px 6px rgba(0, 0, 0, 0.1) !important;
      border: 1px solid rgba(255, 255, 255, 0.9) !important;
      border-radius: 9px !important;
      z-index: 5 !important;
    }
    .hero-floating-card .floating-card-icon {
      width: 28px !important;
      height: 28px !important;
      border-radius: 6px !important;
      flex-shrink: 0 !important;
    }
    .hero-floating-card .floating-card-icon svg {
      width: 14px !important;
      height: 14px !important;
    }
    .hero-floating-card .floating-card-text h4 {
      font-size: 0.74rem !important;
      font-weight: 700 !important;
      margin-bottom: 1px !important;
      line-height: 1.15 !important;
      color: #0f172a !important;
      white-space: nowrap !important;
    }
    .hero-floating-card .floating-card-text p {
      font-size: 0.63rem !important;
      line-height: 1.2 !important;
      margin-bottom: 0 !important;
      color: #475569 !important;
      display: -webkit-box !important;
      -webkit-line-clamp: 2 !important;
      -webkit-box-orient: vertical !important;
      overflow: hidden !important;
    }
    .hero-slide-dots {
      bottom: 10px !important;
      right: 10px !important;
      padding: 4px 8px !important;
      z-index: 4 !important;
    }
    /* Booking and explore service buttons centered in the middle */
    .hero-actions {
      order: 3 !important;
      display: flex !important;
      justify-content: center !important;
      align-items: center !important;
      text-align: center !important;
      width: 100% !important;
      margin: 0 auto 1.5rem auto !important;
      gap: 12px !important;
    }
    .trust-badges {
      order: 4 !important;
      width: 100% !important;
      max-width: 440px !important;
      margin: 0 auto 1.5rem auto !important;
      border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
      padding-top: 1.25rem !important;
    }
    .quick-book-strip {
      margin-top: -1.5rem !important;
    }
  }

  @media (max-width: 540px) {
    .hero-content h1 {
      font-size: 1.75rem !important;
      letter-spacing: -0.02em !important;
    }
    .hero-media {
      max-width: 350px !important;
      margin: 0 auto 2.75rem auto !important;
    }
    .hero-slider-wrap {
      aspect-ratio: 4 / 3 !important;
      min-height: unset !important;
      height: auto !important;
      max-height: unset !important;
    }
    .hero-floating-card {
      bottom: -25px !important;
      left: -4px !important;
      max-width: 200px !important;
      padding: 0.38rem 0.58rem !important;
      gap: 0.45rem !important;
      border-radius: 8px !important;
    }
    .hero-floating-card .floating-card-icon {
      width: 26px !important;
      height: 26px !important;
    }
    .hero-floating-card .floating-card-icon svg {
      width: 13px !important;
      height: 13px !important;
    }
    .hero-floating-card .floating-card-text h4 {
      font-size: 0.70rem !important;
    }
    .hero-floating-card .floating-card-text p {
      font-size: 0.59rem !important;
      line-height: 1.16 !important;
    }
    .hero-actions {
      flex-direction: column !important;
      gap: 10px !important;
    }
    .hero-actions .btn {
      width: 100% !important;
      max-width: 290px !important;
      padding: 0.8rem 1.4rem !important;
      font-size: 0.95rem !important;
      justify-content: center !important;
      text-align: center !important;
      margin: 0 auto !important;
    }
    .trust-badges {
      gap: 0.75rem !important;
      margin: 0 auto 1.25rem auto !important;
    }
    .quick-book-strip {
      margin-top: -1.25rem !important;
    }
    .trust-badge-item {
      font-size: 0.82rem !important;
    }
  }

  @media (max-width: 360px) {
    .hero-media {
      max-width: 300px !important;
      margin: 0 auto 2.5rem auto !important;
    }
    .hero-floating-card {
      left: 0 !important;
      bottom: -22px !important;
      max-width: 185px !important;
      padding: 0.32rem 0.48rem !important;
    }
    .hero-floating-card .floating-card-text h4 {
      font-size: 0.67rem !important;
    }
    .hero-floating-card .floating-card-text p {
      font-size: 0.56rem !important;
    }
    .quick-book-strip {
      margin-top: -1rem !important;
    }
  }
      font-size: 0.56rem !important;
    }
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
        <label for="quickService">1. Select Service</label>
        <select id="quickService" name="service" class="select-custom" required>
          <option value="">-- Choose Required Service --</option>
          @foreach($services as $service)
            <option value="{{ $service->title }}">{{ $service->title }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group-quick">
        <label for="quickProperty">2. Property Type</label>
        <select id="quickProperty" name="property_type" class="select-custom">
          <option value="Corporate Office">Corporate Office</option>
          <option value="Commercial Building / Plaza">Commercial Building / Plaza</option>
          <option value="Residential Apartment / Flat">Residential Apartment / Flat</option>
          <option value="Residential Building (Full Complex)">Residential Building (Full Complex)</option>
          <option value="Hospital / Healthcare Clinic">Hospital / Healthcare Clinic</option>
          <option value="Industrial Factory / EPZ Facility">Industrial Factory / EPZ</option>
          <option value="Educational Institution">Educational Campus</option>
        </select>
      </div>

      <div class="form-group-quick">
        <label for="quickSchedule">3. Preferred Schedule</label>
        <select id="quickSchedule" name="schedule" class="select-custom">
          <option value="Standard Inspection (Next Day)">Standard Inspection (Next Day)</option>
          <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
          <option value="Afternoon (12:00 PM - 4:00 PM)">Afternoon (12:00 PM - 4:00 PM)</option>
          <option value="Evening (4:00 PM - 8:00 PM)">Evening (4:00 PM - 8:00 PM)</option>
          <option value="Emergency (Immediate Dispatch ASAP)">🚨 Emergency (Immediate Dispatch)</option>
        </select>
      </div>

      <div>
        <button type="submit" class="btn btn-accent btn-block" style="padding:0.75rem 1.5rem; margin-top:1.45rem; font-weight:700;">
          Book Now
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>
    </form>
  </div>
</div>
