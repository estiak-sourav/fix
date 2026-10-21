<x-layouts.admin title="Global Site Settings & SMTP Configuration | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Global Site Settings & SMTP Configuration</h1>
      <p class="page-header-subtitle">Manage corporate logos, emergency contact details, email SMTP gateway, and SEO metadata</p>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; gap:8px; max-width:900px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; gap:8px; max-width:900px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
      {{ session('error') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; max-width:900px;">
      <div style="font-weight:700; margin-bottom:4px;">Please correct the following errors:</div>
      <ul style="margin:0; padding-left:1.25rem;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 1fr; gap:1.5rem; max-width:900px;">
    
    <!-- Primary Settings Form -->
    <div class="card-paces">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem;">
        <div>
          <h3 class="card-title-paces">Corporate Identity & Live Settings</h3>
          <p style="font-size:0.8rem; color:var(--paces-text-muted);">Controls live branding, email gateway, and operational parameters</p>
        </div>
      </div>

      <form action="{{ route('admin.cms.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 📧 Email Delivery & SMTP Gateway Setup -->
        <div id="smtpSettings" style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem; scroll-margin-top: 80px;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <div>
              <h4 style="font-size:1rem; font-weight:700; color:var(--paces-text-dark); margin:0;">📧 Email Delivery & SMTP Gateway Setup</h4>
              <p style="font-size:0.8rem; color:var(--paces-text-muted); margin:2px 0 0;">Configures outgoing email server for customer OTPs, registration, and dispatch notifications</p>
            </div>
            <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem;">
              Driver: {{ strtoupper(\App\Models\SiteSetting::get('mail_mailer', 'smtp')) }}
            </span>
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Mail Driver / Mailer *</label>
              <select name="mail_mailer" class="form-control-paces" required>
                <option value="smtp" {{ \App\Models\SiteSetting::get('mail_mailer', 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP (Production Live Gateway)</option>
                <option value="log" {{ \App\Models\SiteSetting::get('mail_mailer', 'smtp') === 'log' ? 'selected' : '' }}>Log (Dev/Testing - Log files only)</option>
              </select>
              <span class="form-help-paces">Select SMTP for real email delivery to user inboxes.</span>
            </div>

            <div>
              <label class="form-label-paces">SMTP Server Host *</label>
              <input type="text" name="mail_host" value="{{ \App\Models\SiteSetting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com')) }}" class="form-control-paces" placeholder="e.g. smtp.gmail.com or mail.fix.com.bd" required>
              <span class="form-help-paces">Hostname of your email provider.</span>
            </div>
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">SMTP Port *</label>
              <input type="number" name="mail_port" value="{{ \App\Models\SiteSetting::get('mail_port', config('mail.mailers.smtp.port', 587)) }}" class="form-control-paces" placeholder="587 / 465 / 25" required>
              <span class="form-help-paces">587 for TLS, 465 for SSL.</span>
            </div>

            <div>
              <label class="form-label-paces">Encryption Protocol *</label>
              <select name="mail_encryption" class="form-control-paces" required>
                <option value="tls" {{ \App\Models\SiteSetting::get('mail_encryption', 'tls') === 'tls' ? 'selected' : '' }}>TLS (Recommended - Port 587)</option>
                <option value="ssl" {{ \App\Models\SiteSetting::get('mail_encryption', 'tls') === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                <option value="none" {{ \App\Models\SiteSetting::get('mail_encryption', 'tls') === 'none' ? 'selected' : '' }}>None (Port 25 / Local)</option>
              </select>
            </div>

            <div>
              <label class="form-label-paces">Sender From Email *</label>
              <input type="email" name="mail_from_address" value="{{ \App\Models\SiteSetting::get('mail_from_address', config('mail.from.address', 'noreply@fix.com.bd')) }}" class="form-control-paces" placeholder="noreply@fix.com.bd" required>
              <span class="form-help-paces">Must match your authorized sender.</span>
            </div>
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">SMTP Username *</label>
              <input type="text" name="mail_username" value="{{ \App\Models\SiteSetting::get('mail_username', config('mail.mailers.smtp.username')) }}" class="form-control-paces" placeholder="support@fix.com.bd or SMTP User">
              <span class="form-help-paces">Your SMTP account email or login key.</span>
            </div>

            <div>
              <label class="form-label-paces">SMTP Password / App Secret</label>
              <input type="password" name="mail_password" value="{{ \App\Models\SiteSetting::get('mail_password', config('mail.mailers.smtp.password')) }}" class="form-control-paces" placeholder="Enter SMTP password or App Password">
              <span class="form-help-paces">For Gmail, use a 16-character Google App Password.</span>
            </div>
          </div>

          <div>
            <label class="form-label-paces">Sender From Name</label>
            <input type="text" name="mail_from_name" value="{{ \App\Models\SiteSetting::get('mail_from_name', config('mail.from.name', 'FIX Facility Operations')) }}" class="form-control-paces" placeholder="FIX Facility Operations">
            <span class="form-help-paces">Appears as the sender display name in users' inbox.</span>
          </div>
        </div>

        <!-- 🖼️ Logo & Brand Assets -->
        <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--paces-text-dark); margin-bottom:1rem;">🖼️ Logo & Brand Assets</h4>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
            <div>
              <label class="form-label-paces">Header Logo (Dark)</label>
              <div style="background:#ffffff; padding:12px; border-radius:8px; margin-bottom:10px; display:inline-block; border:1px solid var(--paces-border);">
                <img src="{{ asset(\App\Models\SiteSetting::get('header_logo', 'assets/images/logo-dark-crop.png')) }}" alt="Current Header Logo" style="height:36px; max-width:180px; object-fit:contain;">
              </div>
              <input type="file" name="header_logo" accept="image/*" class="form-control-paces">
              <span class="form-help-paces">Used on main navigation bar (PNG/SVG recommended).</span>
            </div>

            <div>
              <label class="form-label-paces">Footer Logo (Light / White)</label>
              <div style="background:#1e2229; padding:12px; border-radius:8px; margin-bottom:10px; display:inline-block; border:1px solid #334155;">
                <img src="{{ asset(\App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png')) }}" alt="Current Footer Logo" style="height:36px; max-width:180px; object-fit:contain;">
              </div>
              <input type="file" name="footer_logo" accept="image/*" class="form-control-paces">
              <span class="form-help-paces">Used on dark footer and auth dialogs.</span>
            </div>
          </div>
        </div>

        <!-- 📞 Contact & Operations Info -->
        <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--paces-text-dark); margin-bottom:1rem;">📞 Contact & Operations Info</h4>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Emergency Hotline *</label>
              <input type="text" name="emergency_phone" value="{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}" class="form-control-paces" required>
            </div>

            <div>
              <label class="form-label-paces">Support Email *</label>
              <input type="email" name="support_email" value="{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}" class="form-control-paces" required>
            </div>
          </div>

          <div>
            <label class="form-label-paces">Head Office Address *</label>
            <input type="text" name="head_office_address" value="{{ \App\Models\SiteSetting::get('head_office_address', 'Plot 12, Road 11, Block D, Banani, Dhaka-1213') }}" class="form-control-paces" required>
          </div>
        </div>

        <!-- 🔍 SEO Defaults -->
        <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--paces-text-dark); margin-bottom:1rem;">🔍 SEO Defaults</h4>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Default Meta Title</label>
            <input type="text" name="meta_title" value="{{ \App\Models\SiteSetting::get('meta_title') }}" class="form-control-paces">
          </div>

          <div>
            <label class="form-label-paces">Default Meta Description</label>
            <textarea name="meta_description" class="form-control-paces" rows="3">{{ \App\Models\SiteSetting::get('meta_description') }}</textarea>
          </div>
        </div>

        <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.75rem 1.75rem; font-size:0.95rem; font-weight:600;">
          Save All Site & SMTP Settings
        </button>
      </form>
    </div>

    <!-- 🚀 Test SMTP Gateway Card -->
    <div class="card-paces" style="background:#f8fafc; border:1px solid var(--paces-primary-border);">
      <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.75rem;">
        <span style="font-size:1.4rem;">🧪</span>
        <div>
          <h3 class="card-title-paces" style="margin:0;">Test SMTP Gateway Connection</h3>
          <p style="font-size:0.8rem; color:var(--paces-text-muted); margin:2px 0 0;">
            Dispatch an instant test email to verify your SMTP server authentication, host, port, and TLS handshake.
          </p>
        </div>
      </div>

      <form action="{{ route('admin.cms.settings.test-smtp') }}" method="POST" style="margin-top:1rem; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        @csrf
        <div style="flex:1; min-width:260px;">
          <label class="form-label-paces" style="margin-bottom:4px;">Test Recipient Email Address</label>
          <input type="email" name="test_email" required placeholder="your-email@example.com" class="form-control-paces" style="background:#ffffff;">
        </div>
        <button type="submit" class="btn-paces btn-paces-primary" style="background:var(--paces-success); border-color:var(--paces-success); padding:0.65rem 1.25rem;">
          🚀 Send Test Email Now
        </button>
      </form>
    </div>

  </div>
</x-layouts.admin>
