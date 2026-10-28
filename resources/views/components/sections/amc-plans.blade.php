@props([
  'content' => null,
  'plans' => [],
])

@php
  $defaultStats = [
    [
      'val' => '35% - 50%',
      'lbl' => 'Lower Breakdown Costs',
      'svg' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>',
    ],
    [
      'val' => '45 Min SLA',
      'lbl' => 'Priority Emergency Dispatch',
      'svg' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline>',
    ],
    [
      'val' => '100% Certified',
      'lbl' => 'BNBC & NFPA Safety Audits',
      'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
    ],
    [
      'val' => 'Dedicated Lead',
      'lbl' => 'Assigned Facility Engineer',
      'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
    ],
  ];

  $customStats = $content?->meta_data['highlight_stats'] ?? [];
@endphp

<!-- Annual Maintenance Contracts (AMC) Section -->
<section id="amc-plans" class="amc-highlighted-section" data-section="amc_plans" data-section-key="amc_plans">
  <div id="amc" class="section-anchor-target"></div>
  <div id="amc_plans" class="section-anchor-target"></div>
  <div id="packages" class="section-anchor-target"></div>
  <div class="container">
    <div class="section-header">
      <h2 class="amc-section-title">{!! $content->title ?? 'Annual Maintenance Contracts <span>(AMC)</span>' !!}</h2>
      @if(!empty($content?->subtitle))
        <p class="section-subtitle" style="text-align:center; margin-bottom:2rem; color:var(--text-muted);">{{ $content->subtitle }}</p>
      @endif
    </div>

    <!-- Interactive AMC Value Highlights Banner -->
    <div class="amc-highlights-banner">
      @foreach($defaultStats as $i => $dStat)
        <div class="amc-highlight-stat">
          <div class="amc-stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">{!! $dStat['svg'] !!}</svg>
          </div>
          <div class="amc-stat-info">
            <div class="amc-stat-val">{{ $customStats[$i]['val'] ?? $dStat['val'] }}</div>
            <div class="amc-stat-lbl">{{ $customStats[$i]['lbl'] ?? $dStat['lbl'] }}</div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="amc-grid">
      @foreach($plans as $plan)
        <div class="amc-card {{ $plan->is_popular ? 'featured' : '' }}">
          @if($plan->badge)
            <span class="amc-featured-badge">{{ $plan->badge }}</span>
          @endif
          <div class="amc-header">
            <h3>{{ $plan->name }}</h3>
            <p>{{ $plan->target_audience }}</p>
            <div class="amc-price">
              {{ $plan->tier === 'corporate_enterprise' ? 'Enterprise' : ($plan->tier === 'industrial_manufacturing' ? 'Industrial' : 'Custom') }}
              <span>{{ $plan->price_period }}</span>
            </div>
          </div>
          <ul class="amc-list">
            @if(is_array($plan->features))
              @foreach($plan->features as $feature)
                <li>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                  {{ $feature }}
                </li>
              @endforeach
            @endif
          </ul>
          <a href="{{ route('booking.create', ['plan' => $plan->slug]) }}" 
             class="btn {{ $plan->is_popular ? 'btn-accent' : 'btn-outline' }} btn-block">
            {{ $plan->button_text }}
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
