<x-layouts.admin title="Site Settings | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Settings</h1>
    </div>
  </div>

  <!-- Settings Navigation Tabs -->
  <div style="display:flex; gap:8px; border-bottom:1px solid var(--paces-border); margin-bottom:1.5rem; max-width:960px;">
    <a href="{{ route('admin.web.settings') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:600; color:var(--paces-primary); text-decoration:none; border-bottom:2px solid var(--paces-primary); display:flex; align-items:center; gap:6px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
      General Site Settings
    </a>
    <a href="{{ route('admin.web.smtp-settings') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:500; color:var(--paces-text-muted); text-decoration:none; border-bottom:2px solid transparent; display:flex; align-items:center; gap:6px;">
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

  <div style="max-width:960px;">
    <form action="{{ route('admin.web.settings.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- 1. General Identity -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Site Identity</h3>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">Site Title / Name *</label>
            <input type="text" name="site_name" value="{{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Management Services') }}" class="form-control-paces" required>
            <span class="form-help-paces">The primary brand name displayed across the site and browser tabs.</span>
          </div>

          <div>
            <label class="form-label-paces">Tagline / Slogan</label>
            <input type="text" name="site_tagline" value="{{ \App\Models\SiteSetting::get('site_tagline', 'Clean • Safe • Sustainable') }}" class="form-control-paces">
            <span class="form-help-paces">A short slogan or mission statement displayed under the logo.</span>
          </div>
        </div>

        <div>
          <label class="form-label-paces">Site Description</label>
          <textarea name="site_description" class="form-control-paces" rows="5">{{ \App\Models\SiteSetting::get('site_description', 'Professional facility management, HVAC, electrical, plumbing, fire safety and AMC services in Bangladesh.') }}</textarea>
          <span class="form-help-paces">Brief overview used as default text for browsers and search engine indexers.</span>
        </div>
      </div>

      <!-- 2. Site Logos & Favicon -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Site Logos & Favicon</h3>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap:1.25rem;">
          <div>
            <label class="form-label-paces">Header Logo (Light Mode)</label>
            <div style="background:#ffffff; padding:12px; border-radius:8px; margin-bottom:8px; border:1px solid var(--paces-border); text-align:center;">
              <img src="{{ asset(\App\Models\SiteSetting::get('header_logo', 'assets/images/logo-dark-crop.png')) }}" alt="Header Logo" style="height:36px; max-width:180px; object-fit:contain;">
            </div>
            <input type="file" name="header_logo" accept="image/*" class="form-control-paces">
            <span class="form-help-paces">Main navigation logo for light backgrounds.</span>
          </div>

          <div>
            <label class="form-label-paces">Header Logo (Dark Mode)</label>
            <div style="background:#0f172a; padding:12px; border-radius:8px; margin-bottom:8px; border:1px solid #334155; text-align:center;">
              <img src="{{ asset(\App\Models\SiteSetting::get('header_logo_dark', \App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png'))) }}" alt="Dark Header Logo" style="height:36px; max-width:180px; object-fit:contain;">
            </div>
            <input type="file" name="header_logo_dark" accept="image/*" class="form-control-paces">
            <span class="form-help-paces">Alternate logo for dark navbar or dark mode.</span>
          </div>

          <div>
            <label class="form-label-paces">Footer Logo</label>
            <div style="background:#0f172a; padding:12px; border-radius:8px; margin-bottom:8px; border:1px solid #334155; text-align:center;">
              <img src="{{ asset(\App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png')) }}" alt="Footer Logo" style="height:36px; max-width:180px; object-fit:contain;">
            </div>
            <input type="file" name="footer_logo" accept="image/*" class="form-control-paces">
            <span class="form-help-paces">Used in footer section and dark themed dialogs.</span>
          </div>

          <div>
            <label class="form-label-paces">Favicon</label>
            <div style="background:#0f172a; padding:12px; border-radius:8px; margin-bottom:8px; border:1px solid #334155; text-align:center;">
              <img src="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}" alt="Favicon" style="height:36px; max-width:36px; object-fit:contain;">
            </div>
            <input type="file" name="favicon" accept="image/*" class="form-control-paces">
            <span class="form-help-paces">Browser tab icon (.ico or .png, 32x32 / 16x16).</span>
          </div>
        </div>
      </div>

      <!-- 3. Contact & Official Information -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Contact & Official Information</h3>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">Admin Email Address *</label>
            <input type="email" name="admin_email" value="{{ \App\Models\SiteSetting::get('admin_email', 'admin@fix.com.bd') }}" class="form-control-paces" required>
            <span class="form-help-paces">Primary email for system alerts, error reports, and password recoveries.</span>
          </div>

          <div>
            <label class="form-label-paces">Public Support Email *</label>
            <input type="email" name="support_email" value="{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}" class="form-control-paces" required>
            <span class="form-help-paces">Customer-facing contact email shown on website pages and footer.</span>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">Phone Number / Hotline *</label>
            <input type="text" name="emergency_phone" value="{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}" class="form-control-paces" required>
            <span class="form-help-paces">Customer service telephone number displayed for emergency dispatches.</span>
          </div>

          <div>
            <label class="form-label-paces">Copyright Notice *</label>
            <input type="text" name="copyright_text" value="{{ \App\Models\SiteSetting::get('copyright_text', '© 2026 FIX Facility Management Services. All Rights Reserved.') }}" class="form-control-paces" required>
            <span class="form-help-paces">Notice displayed at the bottom of all web pages.</span>
          </div>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Physical Office Address *</label>
          <input type="text" name="head_office_address" value="{{ \App\Models\SiteSetting::get('head_office_address', 'Plot 12, Road 11, Block D, Banani, Dhaka-1213, Bangladesh') }}" class="form-control-paces" required>
          <span class="form-help-paces">Official location rendered dynamically in contact section, website footer, and search engine schema.</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
          <div>
            <label class="form-label-paces">Operating Hours</label>
            <textarea name="operating_hours" class="form-control-paces" rows="3">{{ \App\Models\SiteSetting::get('operating_hours', "General Support: 8:00 AM – 10:00 PM (Daily)\nEmergency Callouts: 24/7 Available") }}</textarea>
            <span class="form-help-paces">Working schedule displayed in contact center card and website footer. Supports multi-line formatting.</span>
          </div>

          <div>
            <label class="form-label-paces">Service Coverage Area</label>
            <textarea name="coverage_area" class="form-control-paces" rows="3">{{ \App\Models\SiteSetting::get('coverage_area', 'Dhaka North, Dhaka South, Gazipur, Savar, EPZ & Major Industrial Zones') }}</textarea>
            <span class="form-help-paces">Operational service regions displayed in website top announcement bar and contact center card.</span>
          </div>
        </div>
      </div>

      <!-- Social Media & Messaging Channels -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
          <div>
            <h3 class="card-title-paces" style="margin:0;">Social Media & Communication Channels</h3>
            <span class="form-help-paces" style="margin:2px 0 0;">Manage official external communication links and toggle their visibility in the website footer.</span>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <!-- Facebook -->
          <div style="background:var(--paces-bg, #f8fafc); border:1px solid var(--paces-border, #e2e8f0); border-radius:8px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="form-label-paces" style="margin:0; font-weight:600;">Facebook Page URL</label>
              <div style="display:flex; align-items:center; gap:6px;">
                <input type="hidden" name="facebook_active" value="0">
                <label style="display:inline-flex; align-items:center; gap:5px; cursor:pointer; font-size:0.8rem; font-weight:600; margin:0; user-select:none;">
                  <input type="checkbox" name="facebook_active" value="1" {{ \App\Models\SiteSetting::get('facebook_active', '1') == '1' ? 'checked' : '' }} onchange="this.nextElementSibling.textContent = this.checked ? 'Active' : 'Inactive'; this.nextElementSibling.style.color = this.checked ? '#16a34a' : '#64748b';" style="width:16px; height:16px; accent-color:var(--paces-primary, #0284c7); cursor:pointer;">
                  <span style="color:{{ \App\Models\SiteSetting::get('facebook_active', '1') == '1' ? '#16a34a' : '#64748b' }};">
                    {{ \App\Models\SiteSetting::get('facebook_active', '1') == '1' ? 'Active' : 'Inactive' }}
                  </span>
                </label>
              </div>
            </div>
            <input type="url" name="facebook_url" value="{{ \App\Models\SiteSetting::get('facebook_url') }}" placeholder="https://facebook.com/yourpage" class="form-control-paces" style="background:#ffffff;">
            <span class="form-help-paces" style="margin-top:4px;">Target URL for Facebook icon in footer.</span>
          </div>

          <!-- LinkedIn -->
          <div style="background:var(--paces-bg, #f8fafc); border:1px solid var(--paces-border, #e2e8f0); border-radius:8px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="form-label-paces" style="margin:0; font-weight:600;">LinkedIn Company URL</label>
              <div style="display:flex; align-items:center; gap:6px;">
                <input type="hidden" name="linkedin_active" value="0">
                <label style="display:inline-flex; align-items:center; gap:5px; cursor:pointer; font-size:0.8rem; font-weight:600; margin:0; user-select:none;">
                  <input type="checkbox" name="linkedin_active" value="1" {{ \App\Models\SiteSetting::get('linkedin_active', '1') == '1' ? 'checked' : '' }} onchange="this.nextElementSibling.textContent = this.checked ? 'Active' : 'Inactive'; this.nextElementSibling.style.color = this.checked ? '#16a34a' : '#64748b';" style="width:16px; height:16px; accent-color:var(--paces-primary, #0284c7); cursor:pointer;">
                  <span style="color:{{ \App\Models\SiteSetting::get('linkedin_active', '1') == '1' ? '#16a34a' : '#64748b' }};">
                    {{ \App\Models\SiteSetting::get('linkedin_active', '1') == '1' ? 'Active' : 'Inactive' }}
                  </span>
                </label>
              </div>
            </div>
            <input type="url" name="linkedin_url" value="{{ \App\Models\SiteSetting::get('linkedin_url') }}" placeholder="https://linkedin.com/company/yourpage" class="form-control-paces" style="background:#ffffff;">
            <span class="form-help-paces" style="margin-top:4px;">Target URL for LinkedIn icon in footer.</span>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <!-- Instagram -->
          <div style="background:var(--paces-bg, #f8fafc); border:1px solid var(--paces-border, #e2e8f0); border-radius:8px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="form-label-paces" style="margin:0; font-weight:600;">Instagram Profile URL</label>
              <div style="display:flex; align-items:center; gap:6px;">
                <input type="hidden" name="instagram_active" value="0">
                <label style="display:inline-flex; align-items:center; gap:5px; cursor:pointer; font-size:0.8rem; font-weight:600; margin:0; user-select:none;">
                  <input type="checkbox" name="instagram_active" value="1" {{ \App\Models\SiteSetting::get('instagram_active', '1') == '1' ? 'checked' : '' }} onchange="this.nextElementSibling.textContent = this.checked ? 'Active' : 'Inactive'; this.nextElementSibling.style.color = this.checked ? '#16a34a' : '#64748b';" style="width:16px; height:16px; accent-color:var(--paces-primary, #0284c7); cursor:pointer;">
                  <span style="color:{{ \App\Models\SiteSetting::get('instagram_active', '1') == '1' ? '#16a34a' : '#64748b' }};">
                    {{ \App\Models\SiteSetting::get('instagram_active', '1') == '1' ? 'Active' : 'Inactive' }}
                  </span>
                </label>
              </div>
            </div>
            <input type="url" name="instagram_url" value="{{ \App\Models\SiteSetting::get('instagram_url') }}" placeholder="https://instagram.com/yourprofile" class="form-control-paces" style="background:#ffffff;">
            <span class="form-help-paces" style="margin-top:4px;">Target URL for Instagram icon in footer.</span>
          </div>

          <!-- YouTube -->
          <div style="background:var(--paces-bg, #f8fafc); border:1px solid var(--paces-border, #e2e8f0); border-radius:8px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
              <label class="form-label-paces" style="margin:0; font-weight:600;">YouTube Channel URL</label>
              <div style="display:flex; align-items:center; gap:6px;">
                <input type="hidden" name="youtube_active" value="0">
                <label style="display:inline-flex; align-items:center; gap:5px; cursor:pointer; font-size:0.8rem; font-weight:600; margin:0; user-select:none;">
                  <input type="checkbox" name="youtube_active" value="1" {{ \App\Models\SiteSetting::get('youtube_active', '1') == '1' ? 'checked' : '' }} onchange="this.nextElementSibling.textContent = this.checked ? 'Active' : 'Inactive'; this.nextElementSibling.style.color = this.checked ? '#16a34a' : '#64748b';" style="width:16px; height:16px; accent-color:var(--paces-primary, #0284c7); cursor:pointer;">
                  <span style="color:{{ \App\Models\SiteSetting::get('youtube_active', '1') == '1' ? '#16a34a' : '#64748b' }};">
                    {{ \App\Models\SiteSetting::get('youtube_active', '1') == '1' ? 'Active' : 'Inactive' }}
                  </span>
                </label>
              </div>
            </div>
            <input type="url" name="youtube_url" value="{{ \App\Models\SiteSetting::get('youtube_url') }}" placeholder="https://youtube.com/@yourchannel" class="form-control-paces" style="background:#ffffff;">
            <span class="form-help-paces" style="margin-top:4px;">Target URL for YouTube video channels.</span>
          </div>
        </div>

        <!-- WhatsApp -->
        <div style="background:var(--paces-bg, #f8fafc); border:1px solid var(--paces-border, #e2e8f0); border-radius:8px; padding:12px 14px;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
            <label class="form-label-paces" style="margin:0; font-weight:600;">WhatsApp Contact Number</label>
            <div style="display:flex; align-items:center; gap:6px;">
              <input type="hidden" name="whatsapp_active" value="0">
              <label style="display:inline-flex; align-items:center; gap:5px; cursor:pointer; font-size:0.8rem; font-weight:600; margin:0; user-select:none;">
                <input type="checkbox" name="whatsapp_active" value="1" {{ \App\Models\SiteSetting::get('whatsapp_active', '1') == '1' ? 'checked' : '' }} onchange="this.nextElementSibling.textContent = this.checked ? 'Active' : 'Inactive'; this.nextElementSibling.style.color = this.checked ? '#16a34a' : '#64748b';" style="width:16px; height:16px; accent-color:var(--paces-primary, #0284c7); cursor:pointer;">
                <span style="color:{{ \App\Models\SiteSetting::get('whatsapp_active', '1') == '1' ? '#16a34a' : '#64748b' }};">
                  {{ \App\Models\SiteSetting::get('whatsapp_active', '1') == '1' ? 'Active' : 'Inactive' }}
                </span>
              </label>
            </div>
          </div>
          <input type="text" name="whatsapp_number" value="{{ \App\Models\SiteSetting::get('whatsapp_number') }}" placeholder="+8801700000000" class="form-control-paces" style="background:#ffffff;">
          <span class="form-help-paces" style="margin-top:4px;">Direct WhatsApp helpline number (with international country code).</span>
        </div>
      </div>

      <!-- 4. Maintenance & Operations Mode -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Maintenance Mode</h3>
        </div>

        <div style="margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:12px;">
          <input type="hidden" name="maintenance_mode" value="0">
          <input type="checkbox" name="maintenance_mode" value="1" id="maintenance_mode_toggle" {{ \App\Models\SiteSetting::get('maintenance_mode', '0') == '1' ? 'checked' : '' }} style="width:18px; height:18px; margin-top:3px; accent-color:var(--paces-primary); cursor:pointer;">
          <div>
            <label for="maintenance_mode_toggle" style="font-weight:600; font-size:0.9rem; cursor:pointer; color:var(--paces-text-dark); display:block;">Enable Maintenance / Under Construction Mode</label>
            <span class="form-help-paces" style="margin-top:2px;">When active, public visitors will see a maintenance screen. Authenticated administrators can preview the full website.</span>
          </div>
        </div>

        <div>
          <label class="form-label-paces">Maintenance Message</label>
          <textarea name="maintenance_message" class="form-control-paces" rows="5" placeholder="We are currently undergoing scheduled maintenance. We will be back online shortly.">{{ \App\Models\SiteSetting::get('maintenance_message', 'We are currently performing scheduled maintenance to upgrade our systems. We will be back online shortly.') }}</textarea>
          <span class="form-help-paces">Message displayed to visitors when maintenance mode is active.</span>
        </div>
      </div>

      <!-- 5. Search Engine Optimization (SEO) & Social Meta -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Search Engine Optimization (SEO) & Social Sharing</h3>
        </div>

        <div style="margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:12px;">
          <input type="hidden" name="search_engine_visibility" value="0">
          <input type="checkbox" name="search_engine_visibility" value="1" id="search_engine_visibility_toggle" {{ \App\Models\SiteSetting::get('search_engine_visibility', '1') == '1' ? 'checked' : '' }} style="width:18px; height:18px; margin-top:3px; accent-color:var(--paces-primary); cursor:pointer;">
          <div>
            <label for="search_engine_visibility_toggle" style="font-weight:600; font-size:0.9rem; cursor:pointer; color:var(--paces-text-dark); display:block;">Allow Search Engines to Index This Site</label>
            <span class="form-help-paces" style="margin-top:2px;">Uncheck to discourage search engines from indexing this site (outputs noindex, nofollow - recommended for staging/development).</span>
          </div>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Default Meta Title</label>
          <input type="text" name="meta_title" value="{{ \App\Models\SiteSetting::get('meta_title', 'FIX - Comprehensive Building & Facility Services in Bangladesh') }}" class="form-control-paces">
          <span class="form-help-paces">Fallback title tag when a page does not define a custom title.</span>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Default Meta Description</label>
          <textarea name="meta_description" class="form-control-paces" rows="5">{{ \App\Models\SiteSetting::get('meta_description', 'Professional facility management, HVAC chillers, electrical, plumbing, fire safety & AMC services in Dhaka, Bangladesh.') }}</textarea>
          <span class="form-help-paces">Fallback meta description for search engines.</span>
        </div>

        <div>
          <label class="form-label-paces">Default Open Graph (OG) Image</label>
          <div style="display:flex; align-items:center; gap:15px; margin-bottom:8px;">
            <div style="background:#f1f5f9; padding:8px; border-radius:6px; border:1px solid var(--paces-border);">
              <img src="{{ asset(\App\Models\SiteSetting::get('og_image', 'assets/images/logo-dark-crop.png')) }}" alt="OG Preview" style="height:44px; max-width:140px; object-fit:contain;">
            </div>
            <input type="file" name="og_image" accept="image/*" class="form-control-paces" style="flex:1;">
          </div>
          <span class="form-help-paces">Preview image shown when pages are shared on Facebook, LinkedIn, Twitter, and WhatsApp.</span>
        </div>
      </div>

      <!-- 6. Compliance & Legal Notices -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Compliance & Legal Policies</h3>
        </div>

        <div style="margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:12px;">
          <input type="hidden" name="cookie_consent_enabled" value="0">
          <input type="checkbox" name="cookie_consent_enabled" value="1" id="cookie_consent_toggle" {{ \App\Models\SiteSetting::get('cookie_consent_enabled', '1') == '1' ? 'checked' : '' }} style="width:18px; height:18px; margin-top:3px; accent-color:var(--paces-primary); cursor:pointer;">
          <div>
            <label for="cookie_consent_toggle" style="font-weight:600; font-size:0.9rem; cursor:pointer; color:var(--paces-text-dark); display:block;">Enable Cookie Consent Banner</label>
            <span class="form-help-paces" style="margin-top:2px;">Display a GDPR and privacy policy acknowledgement banner to first-time visitors.</span>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
          <div>
            <label class="form-label-paces">Terms of Service Page</label>
            <select name="terms_page_slug" class="form-control-paces">
              <option value="terms" {{ \App\Models\SiteSetting::get('terms_page_slug', 'terms') === 'terms' ? 'selected' : '' }}>Terms of Service (Default)</option>
              <option value="sla-guarantee" {{ \App\Models\SiteSetting::get('terms_page_slug') === 'sla-guarantee' ? 'selected' : '' }}>SLA & Guarantee</option>
              @if(isset($siteContents))
                @foreach($siteContents as $sc)
                  <option value="{{ $sc->section_key }}" {{ \App\Models\SiteSetting::get('terms_page_slug') === $sc->section_key ? 'selected' : '' }}>
                    {{ $sc->title ?: $sc->section_key }}
                  </option>
                @endforeach
              @endif
            </select>
            <span class="form-help-paces">Target page linked in registration forms and checkout disclaimers.</span>
          </div>

          <div>
            <label class="form-label-paces">Privacy Policy Page</label>
            <select name="privacy_page_slug" class="form-control-paces">
              <option value="privacy" {{ \App\Models\SiteSetting::get('privacy_page_slug', 'privacy') === 'privacy' ? 'selected' : '' }}>Privacy Policy (Default)</option>
              <option value="data-security" {{ \App\Models\SiteSetting::get('privacy_page_slug') === 'data-security' ? 'selected' : '' }}>Data Security Policy</option>
              @if(isset($siteContents))
                @foreach($siteContents as $sc)
                  <option value="{{ $sc->section_key }}" {{ \App\Models\SiteSetting::get('privacy_page_slug') === $sc->section_key ? 'selected' : '' }}>
                    {{ $sc->title ?: $sc->section_key }}
                  </option>
                @endforeach
              @endif
            </select>
            <span class="form-help-paces">Target page linked in cookie notices and privacy disclosures.</span>
          </div>
        </div>
      </div>

      <!-- 7. Email Templates Header Branding & Customer OTP Content -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0;">Email Templates Header Branding &amp; OTP Content</h3>
          <span style="font-size:0.8rem; color:#64748b; margin-top:3px; display:block;">Customize the sub-heading text displayed directly underneath the header logo across system emails.</span>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">New Ticket Confirmation (Under Logo)</label>
            <input type="text" name="email_header_ticket_created" value="{{ \App\Models\SiteSetting::get('email_header_ticket_created', 'Engineering Helpdesk Confirmation') }}" class="form-control-paces">
            <span class="form-help-paces">Shown under logo when a customer submits a new support ticket.</span>
          </div>

          <div>
            <label class="form-label-paces">Ticket Admin Reply (Under Logo)</label>
            <input type="text" name="email_header_ticket_reply" value="{{ \App\Models\SiteSetting::get('email_header_ticket_reply', 'Engineering Helpdesk — New Response') }}" class="form-control-paces">
            <span class="form-help-paces">Shown under logo when admin responds to a customer ticket.</span>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.5rem;">
          <div>
            <label class="form-label-paces">Customer OTP / Login Email (Under Logo)</label>
            <input type="text" name="email_header_otp" value="{{ \App\Models\SiteSetting::get('email_header_otp', \App\Models\SiteSetting::get('site_tagline', 'Clean • Safe • Sustainable Engineering')) }}" class="form-control-paces">
            <span class="form-help-paces">Shown under logo in OTP verification &amp; registration emails.</span>
          </div>

          <div>
            <label class="form-label-paces">SMTP Test Verification Email (Under Logo)</label>
            <input type="text" name="email_header_test_smtp" value="{{ \App\Models\SiteSetting::get('email_header_test_smtp', 'Admin Command Center') }}" class="form-control-paces">
            <span class="form-help-paces">Shown under logo in SMTP gateway test emails sent from admin.</span>
          </div>
        </div>

        <div style="border-top:1px dashed #e2e8f0; padding-top:1.25rem; margin-top:0.5rem;">
          <div style="margin-bottom:1.25rem;">
            <label class="form-label-paces">OTP Security Notice</label>
            <input type="text" name="otp_security_notice" value="{{ \App\Models\SiteSetting::get('otp_security_notice', 'This code is valid for 10 minutes. Never share your OTP with anyone, including FIX representatives.') }}" class="form-control-paces">
            <span class="form-help-paces">The security notice box text displayed right below the OTP code box.</span>
          </div>

          <div>
            <label class="form-label-paces">OTP Disclaimer / Help Notice</label>
            <textarea name="otp_disclaimer_text" class="form-control-paces" rows="3">{{ \App\Models\SiteSetting::get('otp_disclaimer_text', 'If you did not request this verification code, please ignore this email or contact our 24/7 emergency dispatch immediately.') }}</textarea>
            <span class="form-help-paces">Instructions for customers if they did not request the verification code.</span>
          </div>
        </div>
      </div>

      <!-- Action Button -->
      <div style="margin-top:1.5rem; margin-bottom:2rem;">
        @if(auth('admin')->user()?->hasPermission('settings.edit'))
          <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.75rem 2rem; font-size:0.95rem; font-weight:500;">
            Save Site Settings
          </button>
        @else
          <div style="padding:0.75rem 1.25rem; background:#f1f5f9; border-radius:8px; color:var(--paces-text-muted); font-size:0.875rem; display:inline-flex; align-items:center; gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <span>You have view-only access to Site Settings.</span>
          </div>
        @endif
      </div>

    </form>
  </div>
</x-layouts.admin>
