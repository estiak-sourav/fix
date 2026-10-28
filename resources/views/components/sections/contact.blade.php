@props([
  'content' => null,
])

<!-- Contact Section -->
<section id="contact" class="section" data-section="contact" data-section-key="contact">
  <div class="container">
    <div class="section-header" style="text-align:center; margin-bottom: 3rem;">
      <h2 class="section-title">{!! $content->title ?? 'Get in Touch with Our Operations Command' !!}</h2>
      <p class="section-subtitle">
        {{ $content->subtitle ?? 'Have an urgent facility breakdown or need an on-site engineering audit for your commercial property?' }}
      </p>
    </div>

    <div class="contact-grid">
      <!-- Left Column: Operations Command Center Info Card -->
      <div class="contact-info-card">
        <div>
          <h3>{{ $content?->meta_data['card_title'] ?? 'Operations Command & Technical Support' }}</h3>
          <p>{{ $content?->meta_data['card_desc'] ?? 'Have an urgent maintenance request or need facility engineering assistance? Contact our central operations center 24/7.' }}</p>

          <ul class="contact-details-list">
            <li class="contact-detail-item">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </div>
              <div class="contact-detail-text">
                <h5>Emergency Hotline</h5>
                <p>
                  <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}">
                    {{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}
                  </a>
                </p>
              </div>
            </li>

            <li class="contact-detail-item">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </div>
              <div class="contact-detail-text">
                <h5>Official Email</h5>
                <p>
                  <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}">
                    {{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}
                  </a>
                </p>
              </div>
            </li>

            <li class="contact-detail-item">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <div class="contact-detail-text">
                <h5>Central Office</h5>
                <p>{{ \App\Models\SiteSetting::get('head_office_address', 'Plot 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh') }}</p>
              </div>
            </li>

            <li class="contact-detail-item">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 14 14"></polyline>
                </svg>
              </div>
              <div class="contact-detail-text">
                <h5>Operating Hours</h5>
                <p>{!! nl2br(e($content?->meta_data['operating_hours'] ?? "General Support: 8:00 AM – 10:00 PM (Daily)\nEmergency Callouts: 24/7 Available")) !!}</p>
              </div>
            </li>
          </ul>

          <!-- Unified Support Tracker Quick Link Box -->
          <div style="margin-top:1.5rem; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.18); border-radius:10px; padding:1rem 1.15rem; text-align:left;">
            <div style="font-size:0.875rem; font-weight:700; color:#ffffff; margin-bottom:4px; display:flex; align-items:center; gap:8px;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <span>Already Have a Support Ticket?</span>
            </div>
            <p style="font-size:0.8rem; color:#cbd5e1; margin-bottom:10px; line-height:1.4;">
              Track real-time engineering work progress, replies, and status updates.
            </p>
            <a href="{{ route('support.track') }}" style="display:inline-flex; align-items:center; gap:6px; color:#38bdf8; font-size:0.825rem; font-weight:700; text-decoration:none;">
              Track Your Existing Ticket &rarr;
            </a>
          </div>
        </div>

        <div style="border-top:1px solid rgba(255,255,255,0.15); padding-top:1.25rem; margin-top:1rem; font-size:0.85rem; color:#cbd5e1;">
          {{ $content?->meta_data['coverage_text'] ?? 'Covering Dhaka North, Dhaka South, Gazipur, Savar, EPZ & Major Industrial Zones.' }}
        </div>
      </div>

      <!-- Right Column: Interactive Send Us a Message Card -->
      <div class="contact-form-card">
        <h3>Contact &amp; Submit Support Inquiry</h3>
        <p>Send your message below. A unified support ticket reference with live status tracking will be generated instantly.</p>

        @if(session('success'))
          <div data-alert style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:1.25rem; font-size:0.9rem; display:flex; align-items:center; justify-content:space-between;">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
          </div>
        @endif

        <form id="homeContactForm" action="{{ route('contact.send') }}" method="POST">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label for="contactName">Your Name <span class="required" style="color:var(--accent);">*</span></label>
              <input type="text" id="contactName" name="name" value="{{ auth()->user()?->name ?? '' }}" class="form-control" placeholder="e.g. Asif Chowdhury" required>
            </div>
            <div class="form-group">
              <label for="contactPhone">Mobile Number <span class="required" style="color:var(--accent);">*</span></label>
              <input type="tel" id="contactPhone" name="phone" value="{{ auth()->user()?->phone ?? '' }}" class="form-control" placeholder="017xxxxxxxx" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="contactEmail">Email Address <span class="required" style="color:var(--accent);">*</span></label>
              <input type="email" id="contactEmail" name="email" value="{{ auth()->user()?->email ?? '' }}" class="form-control" placeholder="name@company.com" required>
            </div>
            <div class="form-group">
              <label for="contactSubject">Service Interest</label>
              <select id="contactSubject" name="subject" class="form-control">
                <option value="General Facility Inquiry">General Facility Inquiry</option>
                <option value="Corporate AMC Maintenance">Corporate Annual Maintenance (AMC)</option>
                <option value="Residential Building Care">Residential Building Care</option>
                <option value="Vendor Partnership Inquiry">Vendor Partnership Inquiry</option>
                <option value="Emergency Technical Breakdown">Emergency Technical Breakdown</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="contactMessage">Message / Service Requirements <span class="required" style="color:var(--accent);">*</span></label>
            <textarea id="contactMessage" name="message" class="form-control" rows="4"
              placeholder="Briefly describe your building type, issues, or requirements..." required></textarea>
          </div>

          <button type="submit" class="btn btn-accent btn-lg btn-block" style="display:flex; align-items:center; justify-content:center; gap:8px;">
            <span>Submit Inquiry &amp; Generate Ticket</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:18px; height:18px;">
              <line x1="22" y1="2" x2="11" y2="13"></line>
              <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
