@props([
  'testimonials' => [],
])

<!-- Client Testimonials -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Trusted by Facility Owners & Managers</h2>
    </div>

    <div class="testimonials-grid">
      @foreach($testimonials as $t)
        <div class="testimonial-card">
          <div class="testimonial-stars">{{ str_repeat('★', $t->rating) }}</div>
          <p>"{{ $t->quote }}"</p>
          <div class="testimonial-author">
            <img src="{{ $t->avatar_path }}" alt="{{ $t->client_name }}" class="author-avatar">
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
