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
          <!-- Service Main Photo & Media Showcase -->
          <div class="service-detail-gallery-wrapper" style="margin-bottom:2rem;">
            <!-- Main Display Image (Fixed 380px Height & Fixed Aspect Ratio) -->
            <div style="height:380px; width:100%; border-radius:12px; overflow:hidden; position:relative; background:#0f172a; box-shadow:var(--shadow-md);">
              <img id="activeGalleryImage" src="{{ $service->thumbnail_url }}" alt="{{ $service->title }}" 
                   style="width:100%; height:100%; object-fit:cover; object-position:center; transition:opacity 0.25s ease; display:block;">
              
              <div style="position:absolute; bottom:12px; right:14px; background:rgba(15, 23, 42, 0.75); backdrop-filter:blur(6px); color:#ffffff; font-size:0.75rem; font-weight:600; padding:5px 12px; border-radius:6px; letter-spacing:0.3px; display:inline-flex; align-items:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                <span>FIX Verified Quality</span>
              </div>
            </div>

            @php
              $allPhotos = array_merge([$service->thumbnail_url], $service->gallery_urls);
              $allPhotos = array_values(array_unique(array_filter($allPhotos)));
            @endphp

            @if(count($allPhotos) > 1)
              <!-- Gallery Thumbnails Strip -->
              <div style="margin-top:0.85rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                  <span style="font-size:0.85rem; font-weight:700; color:var(--text-main); display:inline-flex; align-items:center; gap:6px;">
                    📷 Service Photo Gallery ({{ count($allPhotos) }})
                  </span>
                  <span style="font-size:0.75rem; color:var(--text-muted);">Click thumbnail to switch photo</span>
                </div>

                <div class="service-gallery-thumbs" style="display:flex; gap:10px; overflow-x:auto; padding-bottom:6px; scrollbar-width:thin;">
                  @foreach($allPhotos as $idx => $photoUrl)
                    <button type="button" 
                            class="gallery-thumb-btn {{ $idx === 0 ? 'active' : '' }}" 
                            onclick="switchServiceGalleryPhoto('{{ $photoUrl }}', this)"
                            style="flex:0 0 100px; width:100px; height:70px; border-radius:8px; overflow:hidden; border:2.5px solid {{ $idx === 0 ? 'var(--accent)' : 'var(--border)' }}; padding:0; background:#f8fafc; cursor:pointer; transition:all 0.2s ease; position:relative; outline:none;"
                            aria-label="View Service Image {{ $idx + 1 }}">
                      <img src="{{ $photoUrl }}" alt="{{ $service->title }} Photo {{ $idx + 1 }}" 
                           style="width:100%; height:100%; object-fit:cover; object-position:center; display:block;">
                    </button>
                  @endforeach
                </div>
              </div>

              <script>
                function switchServiceGalleryPhoto(url, btn) {
                  const mainImg = document.getElementById('activeGalleryImage');
                  if (!mainImg) return;
                  mainImg.style.opacity = '0.35';
                  setTimeout(() => {
                    mainImg.src = url;
                    mainImg.style.opacity = '1';
                  }, 150);

                  document.querySelectorAll('.gallery-thumb-btn').forEach(b => {
                    b.style.borderColor = 'var(--border)';
                    b.classList.remove('active');
                  });
                  if (btn) {
                    btn.style.borderColor = 'var(--accent)';
                    btn.classList.add('active');
                  }
                }
              </script>
            @endif
          </div>

          <h2 style="font-size:1.6rem; font-weight:800; color:var(--text-main); margin-bottom:1rem;">About This Service</h2>
          <p style="font-size:1rem; color:var(--text-muted); line-height:1.7; margin-bottom:1.5rem;">
            {{ $service->description }}
          </p>

          @if(!empty($service->gallery_urls))
            <!-- Completed Work & Inspection Gallery Grid -->
            <div style="margin:2.5rem 0; padding-top:2rem; border-top:1px solid var(--border);">
              <h3 style="font-size:1.25rem; font-weight:700; color:var(--text-main); margin-bottom:0.5rem; display:flex; align-items:center; gap:8px;">
                <span>Completed Work &amp; Inspection Gallery</span>
                <span style="font-size:0.75rem; font-weight:600; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:2px 8px; border-radius:12px;">Verified Field Photos</span>
              </h3>
              <p style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1.25rem;">
                Actual photographic documentation from recent enterprise & residential service deployments.
              </p>

              <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:14px;">
                @foreach($service->gallery_urls as $gIdx => $gUrl)
                  <div style="height:140px; border-radius:10px; overflow:hidden; border:1px solid var(--border); box-shadow:0 2px 8px rgba(0,0,0,0.04); background:#f8fafc; cursor:pointer;"
                       onclick="switchServiceGalleryPhoto('{{ $gUrl }}', document.querySelectorAll('.gallery-thumb-btn')[{{ $gIdx + 1 }}]); window.scrollTo({top: 250, behavior: 'smooth'});">
                    <img src="{{ $gUrl }}" alt="Work photo {{ $gIdx + 1 }}" 
                         style="width:100%; height:100%; object-fit:cover; transition:transform 0.3s ease; display:block;"
                         onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                  </div>
                @endforeach
              </div>
            </div>
          @endif

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
              <span style="color:var(--text-muted); display:block;">Pricing:</span>
              <strong style="font-size:1.15rem; color:var(--primary);">Custom Quote on Inspection</strong>
              <span style="font-size:0.8rem; color:var(--text-muted); display:block; margin-top:2px;">Assessed & set post-inspection / upon service</span>
            </div>
            <div style="font-size:0.9rem; margin-bottom:1rem;">
              <span style="color:var(--text-muted); display:block;">Service Dispatch:</span>
              <strong style="color:#10b981;">Direct Engineering Team Coordination</strong>
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
