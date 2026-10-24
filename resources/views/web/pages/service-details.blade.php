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

          <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:2rem;">
            <a href="{{ route('booking.create', ['service' => $service->title]) }}" class="btn btn-accent btn-lg">
              Book {{ $service->title }} Now →
            </a>
            <a href="{{ route('home') }}#services" class="btn btn-outline btn-lg">
              Explore All Services
            </a>
          </div>

          <!-- Client Ratings & Verified Reviews -->
          <div style="margin-top:2.5rem; border-top:1px solid var(--border); padding-top:2rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:10px;">
              <div>
                <h3 style="font-size:1.25rem; font-weight:700; color:var(--text-main); margin:0;">
                  Client Ratings & Reviews ({{ $service->reviewsCount() }})
                </h3>
                <div style="font-size:0.85rem; color:var(--text-muted); margin-top:2px;">
                  ⭐ Average Satisfaction: <strong>{{ $service->averageRating() }} / 5.0</strong>
                </div>
              </div>
            </div>

            @if($service->reviews->isEmpty())
              <div style="background:var(--bg-page); border:1px dashed var(--border); border-radius:10px; padding:1.5rem; text-align:center; color:var(--text-muted); font-size:0.875rem;">
                Verified customer reviews for this service will appear here after work orders are completed.
              </div>
            @else
              <div style="display:flex; flex-direction:column; gap:1rem;">
                @foreach($service->reviews as $rev)
                  <div style="background:#ffffff; border:1px solid var(--border); border-radius:10px; padding:1.25rem; box-shadow:0 1px 4px rgba(0,0,0,0.02);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                      <div style="font-weight:700; color:var(--text-main); font-size:0.95rem;">
                        {{ $rev->customer_name }}
                        @if($rev->company_name)
                          <span style="font-weight:400; font-size:0.8rem; color:var(--text-muted);">({{ $rev->company_name }})</span>
                        @endif
                      </div>
                      <div style="color:#f59e0b; font-size:0.95rem;">
                        @for($i = 1; $i <= 5; $i++)
                          {{ $i <= $rev->review_rating ? '★' : '☆' }}
                        @endfor
                      </div>
                    </div>
                    <div style="font-size:0.88rem; color:var(--text-muted); line-height:1.5;">
                      "{{ $rev->review_comment }}"
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:6px;">
                      Work Order #{{ $rev->booking_number }} • {{ $rev->reviewed_at?->format('M d, Y') }}
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
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
