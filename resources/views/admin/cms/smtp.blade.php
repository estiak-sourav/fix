<x-layouts.admin title="SMTP Setup | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Settings</h1>
    </div>
  </div>

  <!-- Settings Navigation Tabs -->
  <div style="display:flex; gap:8px; border-bottom:1px solid var(--paces-border); margin-bottom:1.5rem; max-width:960px;">
    <a href="{{ route('admin.web.settings') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:500; color:var(--paces-text-muted); text-decoration:none; border-bottom:2px solid transparent; display:flex; align-items:center; gap:6px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
      General Site Settings
    </a>
    <a href="{{ route('admin.web.smtp-settings') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:600; color:var(--paces-primary); text-decoration:none; border-bottom:2px solid var(--paces-primary); display:flex; align-items:center; gap:6px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
      SMTP Gateway Setup
    </a>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between; max-width:960px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#16a34a; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if(session('error'))
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between; max-width:960px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span>{{ session('error') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:flex-start; justify-content:space-between; max-width:960px;">
      <div>
        <div style="font-weight:600; margin-bottom:4px;">Please correct the following errors:</div>
        <ul style="margin:0; padding-left:1.25rem; font-size:0.88rem;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 1fr; gap:1.5rem; max-width:960px;">

    <!-- SMTP Configuration Card -->
    <div class="card-paces">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Outgoing Mail Server Configuration</h3>
          <p style="font-size:0.82rem; color:var(--paces-text-muted); margin:4px 0 0;">Configures the SMTP mail server used for user authentication OTPs, booking confirmations, and dispatch alerts.</p>
        </div>
        <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem;">
          Driver: {{ strtoupper(\App\Models\SiteSetting::get('mail_mailer', 'smtp')) }}
        </span>
      </div>

      <form action="{{ route('admin.web.smtp-settings.update') }}" method="POST">
        @csrf

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">Mail Driver / Mailer *</label>
            <select name="mail_mailer" class="form-control-paces" required>
              <option value="smtp" {{ \App\Models\SiteSetting::get('mail_mailer', 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP (Production Live Gateway)</option>
              <option value="log" {{ \App\Models\SiteSetting::get('mail_mailer', 'smtp') === 'log' ? 'selected' : '' }}>Log (Testing / Log Files Only)</option>
            </select>
            <span class="form-help-paces">Select SMTP for live delivery to user inboxes.</span>
          </div>

          <div>
            <label class="form-label-paces">SMTP Server Host *</label>
            <input type="text" name="mail_host" value="{{ \App\Models\SiteSetting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com')) }}" class="form-control-paces" placeholder="e.g. smtp.gmail.com or mail.fix.com.bd" required>
            <span class="form-help-paces">Hostname of your email provider.</span>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">SMTP Port *</label>
            <input type="number" name="mail_port" value="{{ \App\Models\SiteSetting::get('mail_port', config('mail.mailers.smtp.port', 587)) }}" class="form-control-paces" placeholder="587 / 465 / 25" required>
            <span class="form-help-paces">Common ports: 587 (TLS), 465 (SSL), 25.</span>
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

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">SMTP Username</label>
            <input type="text" name="mail_username" value="{{ \App\Models\SiteSetting::get('mail_username', config('mail.mailers.smtp.username')) }}" class="form-control-paces" placeholder="e.g. support@fix.com.bd or API key username">
            <span class="form-help-paces">Your SMTP username or account email.</span>
          </div>

          <div>
            <label class="form-label-paces">SMTP Password / App Secret</label>
            <input type="password" name="mail_password" value="{{ \App\Models\SiteSetting::get('mail_password', config('mail.mailers.smtp.password')) }}" class="form-control-paces" placeholder="Enter SMTP password or App Password">
            <span class="form-help-paces">For Gmail or Workspace, use an App Password.</span>
          </div>
        </div>

        <div style="margin-bottom:1.5rem;">
          <label class="form-label-paces">Sender From Name *</label>
          <input type="text" name="mail_from_name" value="{{ \App\Models\SiteSetting::get('mail_from_name', config('mail.from.name', 'FIX Operations')) }}" class="form-control-paces" placeholder="FIX Operations" required>
          <span class="form-help-paces">Display name that recipients see in their inbox.</span>
        </div>

        @if(auth('admin')->user()?->hasPermission('settings.edit'))
          <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.75rem 1.75rem; font-size:0.95rem; font-weight:500;">
            Save SMTP Gateway Settings
          </button>
        @else
          <div style="padding:0.75rem 1.25rem; background:#f1f5f9; border-radius:8px; color:var(--paces-text-muted); font-size:0.875rem; display:inline-flex; align-items:center; gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <span>You have view-only access to SMTP Gateway Settings.</span>
          </div>
        @endif
      </form>
    </div>

    <!-- Test SMTP Connection Card -->
    <div class="card-paces" style="background:#f8fafc; border:1px solid var(--paces-primary-border);">
      <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.75rem;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Test SMTP Gateway Connection</h3>
          <p style="font-size:0.82rem; color:var(--paces-text-muted); margin:4px 0 0;">
            Send an instant test email to verify host connection, port accessibility, authentication credentials, and TLS handshake.
          </p>
        </div>
      </div>

      <form action="{{ route('admin.web.settings.test-smtp') }}" method="POST" style="margin-top:1rem; display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        @csrf
        <div style="flex:1; min-width:280px;">
          <label class="form-label-paces" style="margin-bottom:4px;">Recipient Email Address</label>
          <input type="email" name="test_email" required placeholder="your-email@example.com" class="form-control-paces" style="background:#ffffff;">
        </div>
        @if(auth('admin')->user()?->hasPermission('settings.edit'))
          <button type="submit" class="btn-paces btn-paces-primary" style="background:var(--paces-success); border-color:var(--paces-success); padding:0.65rem 1.4rem;">
            Send Test Email
          </button>
        @else
          <button type="button" disabled class="btn-paces btn-paces-outline" style="opacity:0.6; cursor:not-allowed; padding:0.65rem 1.4rem;">
            Requires Edit Permission
          </button>
        @endif
      </form>
    </div>

  </div>
</x-layouts.admin>
