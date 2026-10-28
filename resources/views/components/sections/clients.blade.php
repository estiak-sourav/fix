@props([
  'content' => null,
])

@php
  $rawClients = $content?->meta_data['clients'] ?? null;
  if (!empty($rawClients) && is_array($rawClients)) {
      $clientsList = $rawClients;
  } else {
      $defaultNames = $content?->meta_data['partner_names'] ?? [
        'Standard Chartered Tower',
        'Grameenphone GPHouse',
        'Shanta Holdings Towers',
        'Apex Footwear Complex',
        'United Hospital Complex',
        'Square Pharmaceuticals',
        'Beximco Industrial Park',
        'BRAC Centre & Campus',
      ];
      $clientsList = array_map(fn($name) => ['name' => $name, 'logo' => null], $defaultNames);
  }

  $palette = [
    ['color' => '#0079c1'],
    ['color' => '#0072ce'],
    ['color' => '#d97706'],
    ['color' => '#15803d'],
    ['color' => '#dc2626'],
    ['color' => '#dc2626'],
    ['color' => '#4f46e5'],
    ['color' => '#be185d'],
  ];
@endphp

<!-- Reputed Clients & Corporate Partners Section -->
<section id="clients" class="clients-section" data-section="clients" data-section-key="clients">
  <div id="partners" class="section-anchor-target"></div>
  <div class="container">
    <div class="clients-header">
      <div class="clients-header-text">
        <h2 class="clients-title">{!! $content->title ?? 'Trusted by Leading Enterprises & Facilities' !!}</h2>
      </div>
      <div class="clients-nav-controls">
        <button type="button" class="clients-nav-arrow" id="clientsPrevBtn" aria-label="Previous Clients">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
        <button type="button" class="clients-nav-arrow" id="clientsNextBtn" aria-label="Next Clients">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>
    </div>

    <!-- Single-Row Horizontal Scrolling Client Logos Track -->
    <div class="clients-scroll-wrapper">
      <div class="clients-horizontal-track" id="clientsTrack">
        @foreach($clientsList as $idx => $client)
          @php
            $style = $palette[$idx % count($palette)];
          @endphp
          <div class="client-card" title="{{ $client['name'] }}" aria-label="{{ $client['name'] }}">
            <div class="client-logo-badge">
              @if(!empty($client['logo']))
                <img src="{{ asset($client['logo']) }}" alt="{{ $client['name'] }}" class="client-logo-img" loading="lazy">
              @else
                @php
                  $words = preg_split('/\s+/', trim($client['name']));
                  $initials = '';
                  foreach (array_slice($words, 0, 2) as $w) {
                      $initials .= mb_substr($w, 0, 1);
                  }
                @endphp
                <div class="client-logo-fallback" style="background:{{ $style['color'] }}15; color:{{ $style['color'] }};">
                  <span>{{ strtoupper($initials ?: 'C') }}</span>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

