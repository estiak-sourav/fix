@props([
  'content' => null,
  'testimonials' => [],
])

<!-- Client Testimonials -->
<section id="testimonials" class="section section-alt" data-section="testimonials" data-section-key="testimonials">
  <div id="reviews" class="section-anchor-target"></div>
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">{!! $content->title ?? 'Trusted by Facility Owners & Managers' !!}</h2>
      @if(!empty($content?->subtitle) && trim($content->subtitle) !== 'Hear from building managers, facility heads, and property owners who trust FIX.')
        <p class="section-subtitle">{{ $content->subtitle }}</p>
      @endif
    </div>

    <div class="testimonials-grid">
      @foreach($testimonials as $t)
        <div class="testimonial-card">
          <div class="testimonial-stars">{{ str_repeat('★', $t->rating) }}</div>
          <p>"{{ $t->quote }}"</p>
          <div class="testimonial-author">
            @if($t->avatar_url)
              <img src="{{ $t->avatar_url }}" alt="{{ $t->client_name }}" class="author-avatar">
            @else
              <div class="author-avatar" style="background:#3e60d5; color:#ffffff; display:inline-flex; align-items:center; justify-content:center; font-weight:600; font-size:1.05rem; border-radius:50%; width:44px; height:44px; flex-shrink:0;">
                {{ strtoupper(substr($t->client_name, 0, 1)) }}
              </div>
            @endif
            <div class="author-info">
              <h5>{{ $t->client_name }}</h5>
              <span>{{ $t->client_title }}, {{ $t->company_name }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
