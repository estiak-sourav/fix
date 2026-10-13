<x-layouts.admin title="Global Site Settings & CMS Branding | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Global Site Settings & CMS Branding</h1>
      <p class="page-header-subtitle">Manage corporate logos, emergency contact details, and SEO metadata</p>
    </div>
  </div>

  <div class="card-paces" style="max-width:850px;">
    <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem;">
      <div>
        <h3 class="card-title-paces">Corporate Identity & Operations</h3>
        <p style="font-size:0.8rem; color:var(--paces-text-muted);">Controls live branding across user frontend</p>
      </div>
    </div>

    <form action="{{ route('admin.cms.settings.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Logo & Media Uploads Card -->
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
            <span class="form-help-paces">Used on dark footer and login dialogs.</span>
          </div>
        </div>
      </div>

      <!-- Contact & Operations Info -->
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

      <!-- SEO Defaults -->
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

      <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.75rem 1.75rem;">
        Save Site Settings & Update Cache
      </button>
    </form>
  </div>
</x-layouts.admin>
