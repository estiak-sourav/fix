@props([
  'content' => null,
])

@php
  $defaultCards = [
    1 => [
      'title' => 'One-Stop Solution',
      'desc' => 'No need to manage dozens of separate contractors. Access every building and facility service through a unified platform.',
      'svg' => '<circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>',
    ],
    2 => [
      'title' => 'Verified & Trained Teams',
      'desc' => 'Every technician undergoes comprehensive background verification, safety protocol training, and trade credential validation.',
      'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
    ],
    3 => [
      'title' => 'Structured Quality Control',
      'desc' => 'Our dedicated operations supervisors conduct random site audits, standard checklist compliance, and post-service quality assurance.',
      'svg' => '<polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>',
    ],
    4 => [
      'title' => 'Transparent Pricing',
      'desc' => 'No hidden charges or surprise invoices. Upfront scope definition, itemized quotations, and predictable maintenance budgets.',
      'svg' => '<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>',
    ],
    5 => [
      'title' => 'Annual Maintenance Contracts (AMC)',
      'desc' => 'Predictable operational expenditure and scheduled preventive checkups that minimize costly equipment breakdowns.',
      'svg' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
    ],
    6 => [
      'title' => '24/7 Dedicated Support',
      'desc' => 'Round-the-clock emergency response protocol for critical power failures, major water leaks, or security breakdowns.',
      'svg' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
    ],
  ];

  $customCards = $content?->meta_data['cards'] ?? [];
@endphp

<!-- Why Choose FIX Section -->
<section id="why-fix" class="section section-alt" data-section="why_fix" data-section-key="why_fix">
  <div id="whyFix" class="section-anchor-target"></div>
  <div id="why_fix" class="section-anchor-target"></div>
  <div id="about" class="section-anchor-target"></div>
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">{!! $content->title ?? 'Why Choose FIX?' !!}</h2>
      <p class="section-subtitle">
        {{ $content->subtitle ?? 'We combine skilled engineering, vetted vendor networks, and institutional-grade accountability to protect property value and enhance liveability.' }}
      </p>
    </div>

    <div class="why-fix-grid">
      @foreach($defaultCards as $num => $card)
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              {!! $card['svg'] !!}
            </svg>
          </div>
          <h4>{{ $customCards[$num]['title'] ?? $card['title'] }}</h4>
          <p>{{ $customCards[$num]['desc'] ?? $card['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
