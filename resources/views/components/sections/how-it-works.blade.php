@props([
  'content' => null,
])

<!-- How It Works Section -->
<section id="howItWorks" class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">{!! $content->title ?? 'How It Works' !!}</h2>
      <p class="section-subtitle">
        {{ $content->subtitle ?? 'Getting professional maintenance for your facility is straightforward, transparent, and hassle-free in just 4 simple steps.' }}
      </p>
    </div>

    <div class="how-it-works-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <div class="step-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
          </svg>
        </div>
        <h4>Choose a Service</h4>
        <p>Select from our comprehensive list of building services or request a tailored Annual Maintenance Contract (AMC).</p>
      </div>

      <div class="step-card">
        <div class="step-number">2</div>
        <div class="step-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
          </svg>
        </div>
        <h4>Submit Your Request</h4>
        <p>Fill out the simple booking form with your property type, preferred date, address, and problem description with optional photos.</p>
      </div>

      <div class="step-card">
        <div class="step-number">3</div>
        <div class="step-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
        </div>
        <h4>Our Team Contacts You</h4>
        <p>Our centralized coordination team calls to verify requirements, discuss pricing estimates, and schedule the exact inspection slot.</p>
      </div>

      <div class="step-card">
        <div class="step-number">4</div>
        <div class="step-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <polyline points="17 11 19 13 23 9"></polyline>
          </svg>
        </div>
        <h4>Service Provider Assigned</h4>
        <p>Certified technicians execute the work following strict safety guidelines, followed by formal quality sign-off and reporting.</p>
      </div>
    </div>
  </div>
</section>
