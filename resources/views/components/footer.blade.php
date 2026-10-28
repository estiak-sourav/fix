<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <!-- 1. Brand & Slogan -->
      <div class="footer-brand">
        <div class="nav-brand" style="align-items:flex-start;">
          <a href="{{ route('home') }}" style="display:inline-block; line-height:1;">
            <img src="{{ asset(\App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png')) }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'FIX') }} Logo" style="height:44px;">
          </a>
          <span class="nav-brand-tagline">{{ strtoupper(\App\Models\SiteSetting::get('site_tagline', 'CLEAN • SAFE • SUSTAINABLE')) }}</span>
        </div>
        <p>
          {{ \App\Models\SiteSetting::get('site_description', 'FIX is your trusted partner for complete building maintenance and facility management solutions in Bangladesh. We protect property value and enhance liveability through certified engineering.') }}
        </p>

        @php
          $facebookUrl = \App\Models\SiteSetting::get('facebook_url');
          $linkedinUrl = \App\Models\SiteSetting::get('linkedin_url');
          $instagramUrl = \App\Models\SiteSetting::get('instagram_url');
          $youtubeUrl = \App\Models\SiteSetting::get('youtube_url');
          $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number');
          $whatsappUrl = $whatsappNumber ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsappNumber) : null;
        @endphp

        <div class="footer-socials">
          @if($facebookUrl)
            <a href="{{ $facebookUrl }}" class="social-btn" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
            </a>
          @endif
          @if($linkedinUrl)
            <a href="{{ $linkedinUrl }}" class="social-btn" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                <rect x="2" y="9" width="4" height="12"></rect>
                <circle cx="4" cy="4" r="2"></circle>
              </svg>
            </a>
          @endif
          @if($instagramUrl)
            <a href="{{ $instagramUrl }}" class="social-btn" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
            </a>
          @endif
          @if($youtubeUrl)
            <a href="{{ $youtubeUrl }}" class="social-btn" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
              </svg>
            </a>
          @endif
          @if($whatsappUrl)
            <a href="{{ $whatsappUrl }}" class="social-btn" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
              </svg>
            </a>
          @endif
        </div>
      </div>

      <!-- 2. Quick Links -->
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('home') }}#services">Our Services</a></li>
          <li><a href="{{ route('home') }}#amc">AMC Packages</a></li>
          <li><a href="{{ route('booking.create') }}">Book a Service</a></li>
          <li><a href="{{ route('vendor') }}">Become a Partner</a></li>
        </ul>
      </div>

      <!-- 3. Support Section -->
      <div class="footer-col">
        <h4>Support</h4>
        <ul class="footer-links">
          <li><a href="{{ route('support.create') }}">Helpdesk &amp; Support</a></li>
          <li><a href="{{ route('support.track') }}">Track Ticket</a></li>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('home') }}#testimonials">Testimonials</a></li>
          <li><a href="{{ route('sla') }}">SLA &amp; Guarantee</a></li>
        </ul>
      </div>

      <!-- 4. Contact Information -->
      <div class="footer-col">
        <h4>Contact Information</h4>
        <ul class="footer-contact-list">
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <span>{{ \App\Models\SiteSetting::get('head_office_address', 'Plot 12, Road 11, Block D, Banani, Dhaka-1213, Bangladesh') }}</span>
          </li>
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            @php
              $phone = \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000');
              $phoneTel = preg_replace('/[^0-9+]/', '', $phone);
            @endphp
            <a href="tel:{{ $phoneTel }}" style="color:inherit; text-decoration:none;" title="Click to call hotline">
              {{ $phone }} (Hotline)
            </a>
          </li>
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            @php
              $email = \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd');
            @endphp
            <a href="mailto:{{ $email }}" style="color:inherit; text-decoration:none;" title="Click to send email">
              {{ $email }}
            </a>
          </li>
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>{{ \App\Models\SiteSetting::get('operating_hours', '24/7 Operations Command Center') }}</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <div>
        {{ \App\Models\SiteSetting::get('copyright_text', '© 2026 FIX Facility Management Services. All Rights Reserved. Clean • Safe • Sustainable.') }}
      </div>
      <div class="footer-bottom-links">
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <a href="{{ route('terms') }}">Terms of Service</a>
        <a href="{{ route('sla') }}">SLA & Guarantee</a>
      </div>
    </div>
  </div>
</footer>

<!-- Service Details Modal (Universal Compatibility) -->
<div id="serviceDetailsModal" class="modal-overlay">
  <div class="modal-card">
    <div class="modal-header">
      <h3 class="modal-service-title">Service Details</h3>
      <button type="button" class="modal-close-btn" aria-label="Close Modal">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>
    <div class="modal-body">
      <div style="height:200px; border-radius:var(--radius-lg); overflow:hidden; margin-bottom:1.25rem;">
        <img class="modal-service-img" src="" alt="Service" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <p class="modal-service-desc" style="color:var(--text-main); font-size:1rem; margin-bottom:1.5rem;"></p>

      <div style="background:var(--bg-page); padding:1.25rem; border-radius:var(--radius-md); border:1px solid var(--border); margin-bottom:1.25rem;">
        <h4 style="font-size:0.95rem; margin-bottom:0.5rem; color:var(--primary);">🏠 Residential Scope:</h4>
        <p class="modal-residential-scope" style="font-size:0.875rem; color:var(--text-muted); margin-bottom:0;"></p>
      </div>

      <div style="background:var(--bg-page); padding:1.25rem; border-radius:var(--radius-md); border:1px solid var(--border); margin-bottom:1.25rem;">
        <h4 style="font-size:0.95rem; margin-bottom:0.5rem; color:var(--primary);">🏢 Commercial & Facility Scope:</h4>
        <p class="modal-commercial-scope" style="font-size:0.875rem; color:var(--text-muted); margin-bottom:0;"></p>
      </div>

      <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; color:var(--accent); font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 14 14"></polyline>
        </svg>
        <span class="modal-service-sla modal-sla-text">Emergency SLA: Dispatch within 60 mins</span>
      </div>
    </div>
    <div class="modal-footer" style="padding:1.25rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--bg-card);">
      <button type="button" class="btn btn-outline modal-close-trigger modal-close-btn">Close</button>
      <a href="{{ route('booking.create') }}" class="btn btn-accent modal-book-btn modal-book-cta">Book This Service Now</a>
    </div>
  </div>
</div>
