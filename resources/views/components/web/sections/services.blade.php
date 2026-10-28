@props([
  'categories' => [],
  'services' => [],
])

<!-- Service Categories Section -->
<section id="services" class="section section-alt">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Comprehensive Building & Facility Services</h2>
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
            <h3 class="service-card-title">{{ $service->title }}</h3>
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

            <div class="service-card-footer">
              <a href="{{ route('booking.create', ['service' => $service->title]) }}" class="btn btn-outline btn-block">
                Book Service
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
