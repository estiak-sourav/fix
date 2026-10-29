@props([
  'content' => null,
  'categories' => [],
  'services' => [],
])

<!-- Service Categories Section -->
<section id="services" class="section section-alt" data-section="services" data-section-key="services">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">{!! $content->title ?? 'Comprehensive Building & Facility Services' !!}</h2>
      @if(!empty($content?->subtitle) && trim($content->subtitle) !== 'Comprehensive engineering and facility maintenance solutions delivered across Bangladesh.')
        <p class="section-subtitle">{{ $content->subtitle }}</p>
      @endif
    </div>

    <!-- Filter Tabs -->
    <div class="service-tabs">
      <button type="button" class="tab-btn active" data-category="all">All Services</button>
      @foreach($categories as $category)
        <button type="button" class="tab-btn" data-category="{{ $category->slug }}">{{ $category->name }}</button>
      @endforeach
    </div>

    <!-- Dynamic Grid Rendered via Database Blade & Filterable via JS -->
    <div class="services-grid">
      @foreach($services as $service)
        <div class="service-card" data-category="{{ $service->category?->slug ?? 'maintenance' }}">
          <div class="service-card-image service-image-wrapper">
            <a href="{{ route('services.show', $service->slug ?? $service->id) }}">
              <img src="{{ $service->thumbnail_url }}" alt="{{ $service->title }}" loading="lazy">
            </a>
            <span class="service-category-tag">{{ $service->category?->name ?? 'Facility Maintenance' }}</span>
          </div>
          <div class="service-card-body">
            <h3 class="service-card-title">
              <a href="{{ route('services.show', $service->slug ?? $service->id) }}" style="color:inherit; text-decoration:none;">
                {{ $service->title }}
              </a>
            </h3>

            <!-- Rating and Review Count -->
            <div class="service-rating-wrap" style="display:inline-flex; align-items:center; gap:6px; margin-top:-0.4rem; margin-bottom:0.85rem; font-size:0.85rem;">
              <span style="color:#f59e0b; display:inline-flex; align-items:center; gap:3px; font-weight:700;">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>{{ number_format($service->averageRating(), 1) }}</span>
              </span>
              <span style="color:var(--text-muted, #64748b); font-size:0.8rem;">
                ({{ $service->reviewsCount() }} {{ $service->reviewsCount() === 1 ? 'review' : 'reviews' }})
              </span>
            </div>

            <p class="service-card-desc">{{ $service->description }}</p>
            
            <ul class="service-features-list">
              @if(is_array($service->scope_included))
                @foreach(array_slice($service->scope_included, 0, 4) as $item)
                  <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    {{ $item }}
                  </li>
                @endforeach
              @endif
            </ul>

            <div class="service-card-footer" style="display:flex; gap:8px;">
              <a href="{{ route('services.show', $service->slug ?? $service->id) }}" class="btn btn-outline" style="flex:1; text-align:center;">
                Details
              </a>
              <a href="{{ route('booking.create', ['service' => $service->title]) }}" class="btn btn-accent" style="flex:1; text-align:center;">
                Book Now
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
