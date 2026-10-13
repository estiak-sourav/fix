<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="nav-brand" style="align-items:flex-start;">
          <img src="{{ asset(\App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png')) }}" alt="FIX Logo" style="height:44px;">
          <span class="nav-brand-tagline">CLEAN • SAFE • SUSTAINABLE</span>
        </div>
        <p>
          FIX is your trusted partner for complete building maintenance and facility management solutions in
          Bangladesh. We protect property value and enhance liveability through certified engineering.
        </p>
        <div class="footer-socials">
          <a href="#" class="social-btn" aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
            </svg>
          </a>
          <a href="#" class="social-btn" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
              <rect x="2" y="9" width="4" height="12"></rect>
              <circle cx="4" cy="4" r="2"></circle>
            </svg>
          </a>
          <a href="#" class="social-btn" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
            </svg>
          </a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('home') }}#services">Our Services</a></li>
          <li><a href="{{ route('home') }}#howItWorks">How It Works</a></li>
          <li><a href="{{ route('home') }}#whyFix">Why Choose FIX</a></li>
          <li><a href="{{ route('home') }}#amc">AMC Packages</a></li>
          <li><a href="{{ route('vendor') }}">Become a Partner</a></li>
          <li><a href="{{ route('booking.create') }}">Book a Service</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Core Services</h4>
        <ul class="footer-links">
          <li><a href="{{ route('booking.create', ['service' => 'Mechanical & Electrical']) }}">Mechanical & Electrical</a></li>
          <li><a href="{{ route('booking.create', ['service' => 'Plumbing & Sanitary Solutions']) }}">Plumbing & Sanitary</a></li>
          <li><a href="{{ route('booking.create', ['service' => 'HVAC & Air Conditioning']) }}">HVAC & Central AC</a></li>
          <li><a href="{{ route('booking.create', ['service' => 'Commercial & Office Cleaning']) }}">Commercial Cleaning</a></li>
          <li><a href="{{ route('booking.create', ['service' => 'Fire Safety & Alarm Systems']) }}">Fire Safety Systems</a></li>
          <li><a href="{{ route('booking.create', ['service' => 'CCTV Installation & Surveillance']) }}">CCTV & Surveillance</a></li>
          <li><a href="{{ route('booking.create', ['service' => 'Interior Works & Renovation']) }}">Interior & Renovation</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Contact Information</h4>
        <ul class="footer-contact-list">
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <span>{{ \App\Models\SiteSetting::get('head_office_address', 'Plot 12, Road 11, Block D, Banani, Dhaka-1213') }}</span>
          </li>
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span>{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }} (Hotline)</span>
          </li>
          <li class="footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <div>
        &copy; 2026 FIX Facility Management Services. All Rights Reserved. Clean • Safe • Sustainable.
      </div>
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">SLA & Guarantee</a>
        <a href="#">Sitemap</a>
      </div>
    </div>
  </div>
</footer>

<!-- Service Details Modal -->
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
        <span class="modal-service-sla">Emergency SLA: Dispatch within 60 mins</span>
      </div>
    </div>
    <div class="modal-footer" style="padding:1.25rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--bg-card);">
      <a href="#" class="btn btn-outline modal-close-trigger">Close</a>
      <a href="#" class="btn btn-accent modal-book-btn">Book This Service Now</a>
    </div>
  </div>
</div>
