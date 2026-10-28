@props([
  'content' => null,
  'faqs' => [],
])

<!-- FAQ Section -->
<section id="faq" class="section" data-section="faq" data-section-key="faq">
  <div id="faqs" class="section-anchor-target"></div>
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">{!! $content->title ?? 'Frequently Asked Questions' !!}</h2>
      @if(!empty($content?->subtitle))
        <p class="section-subtitle">{{ $content->subtitle }}</p>
      @endif
    </div>

    <!-- FAQ Category Tabs -->
    <div class="faq-categories-bar">
      <button type="button" class="faq-cat-btn active" data-cat="all">All Questions</button>
      <button type="button" class="faq-cat-btn" data-cat="services">Services & Booking</button>
      <button type="button" class="faq-cat-btn" data-cat="amc">AMC & Corporate</button>
      <button type="button" class="faq-cat-btn" data-cat="safety">Technicians & Safety</button>
      <button type="button" class="faq-cat-btn" data-cat="billing">Billing & Partners</button>
    </div>

    <div class="faq-compact-grid">
      @foreach($faqs as $faq)
        <div class="faq-item" data-category="{{ $faq->category ?? 'services' }}">
          <button type="button" class="faq-question">
            <span>{{ $faq->question }}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
          <div class="faq-answer">
            <p>{{ $faq->answer }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
