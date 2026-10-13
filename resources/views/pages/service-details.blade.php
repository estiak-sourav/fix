<x-layouts.app title="{{ $service->title }} | FIX Facility Management">
  <section class="page-hero">
    <div class="container">
      <h1>{{ $service->title }}</h1>
      <p>{{ $service->subtitle ?? $service->category?->name }}</p>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container">
      <div class="booking-layout-grid">
        <div class="form-card" style="padding:2.5rem;">
          <div style="height:320px; border-radius:12px; overflow:hidden; margin-bottom:1.75rem;">
            <img src="{{ $service->thumbnail_url }}" alt="{{ $service->title }}" style="width:100%; height:100%; object-fit:cover;">
          </div>

          <h2 style="font-size:1.6rem; font-weight:800; color:var(--text-main); margin-bottom:1rem;">About This Service</h2>
          <p style="font-size:1rem; color:var(--text-muted); line-height:1.7; margin-bottom:1.5rem;">
            {{ $service->description }}
          </p>

          <h3 style="font-size:1.15rem; font-weight:700; color:var(--primary); margin-bottom:1rem;">Scope of Work & Compliance:</h3>
          <ul class="service-features-list" style="margin-bottom:2rem;">
            @if(is_array($service->scope_included))
              @foreach($service->scope_included as $item)
                <li style="margin-bottom:0.5rem; display:flex; align-items:center; gap:8px;">
                  <span style="color:#10b981; font-weight:bold;">✓</span>
                  <span>{{ $item }}</span>
                </li>
              @endforeach
            @endif
          </ul>

          <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('booking.create', ['service' => $service->title]) }}" class="btn btn-accent btn-lg">
              Book {{ $service->title }} Now →
            </a>
            <a href="{{ route('home') }}#services" class="btn btn-outline btn-lg">
              Explore All Services
            </a>
          </div>
        </div>

        <aside>
          <div class="booking-sidebar-card">
            <h3 style="font-size:1.2rem; margin-bottom:1rem;">Service Quick Info</h3>
            <div style="font-size:0.9rem; margin-bottom:1rem;">
              <span style="color:var(--text-muted); display:block;">Starting Price:</span>
              <strong style="font-size:1.25rem; color:var(--primary);">৳ {{ number_format($service->price_starting ?? 0, 2) }}</strong>
              <span style="font-size:0.8rem; color:var(--text-muted);">/ {{ $service->pricing_unit }}</span>
            </div>
            <div style="font-size:0.9rem; margin-bottom:1rem;">
              <span style="color:var(--text-muted); display:block;">Emergency SLA Dispatch:</span>
              <strong style="color:#10b981;">Within {{ $service->sla_hours }} Hours</strong>
            </div>
            <hr style="border:none; border-top:1px solid var(--border); margin:1.25rem 0;">
            <a href="{{ route('booking.create', ['service' => $service->title]) }}" class="btn btn-accent btn-block">
              Request Site Inspection
            </a>
          </div>
        </aside>
      </div>
    </div>
  </section>
</x-layouts.app>
