@props([
  'content' => null,
  'sectors' => [],
])

<!-- Who We Serve Section -->
<section id="who-we-serve" class="section" data-section="who_we_serve" data-section-key="who_we_serve">
  <div id="whoWeServe" class="section-anchor-target"></div>
  <div id="who_we_serve" class="section-anchor-target"></div>
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">{!! $content->title ?? 'Who We Serve' !!}</h2>
      @if(!empty($content?->subtitle) && trim($content->subtitle) !== 'Tailored engineering and facility maintenance protocols across diverse industries and property classes.')
        <p class="section-subtitle">{{ $content->subtitle }}</p>
      @endif
    </div>

    <div class="sectors-grid">
      @foreach($sectors as $sector)
        <div class="sector-card">
          <div class="sector-header">
            <div class="sector-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
            </div>
            <h4>{{ $sector->name }}</h4>
          </div>
          <p>{{ $sector->short_desc }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
