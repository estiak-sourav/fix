<x-layouts.admin title="Web Control Center | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">🌐 Web Control Center</h1>
      <p class="page-header-subtitle">Manage all frontend website content, customer portal modules, branding, and SMTP communications</p>
    </div>
    <div style="display:flex; gap:10px;">
      <a href="/" target="_blank" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:8px;">
        <span>View Live Website</span>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <!-- Module Cards Grid -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:1.5rem; margin-bottom:2rem;">
    
    <!-- 1. Settings & SMTP -->
    <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
          <div style="width:44px; height:44px; border-radius:10px; background:var(--paces-primary-subtle); color:var(--paces-primary); display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
            ⚙️
          </div>
          <span class="badge-pill-paces bg-success-subtle">
            Active: {{ strtoupper($stats['mail_mailer']) }}
          </span>
        </div>
        <h3 class="card-title-paces" style="margin-bottom:6px;">Site Settings & SMTP Gateway</h3>
        <p style="font-size:0.85rem; color:var(--paces-text-muted); line-height:1.5; margin-bottom:1rem;">
          Configure logo branding, emergency hotline ({{ $stats['emergency_phone'] }}), support email, SEO meta tags, and live SMTP email delivery credentials.
        </p>
      </div>
      <div style="border-top:1px solid var(--paces-border); padding-top:1rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; color:var(--paces-text-muted);">Hotline & Mailer</span>
        <a href="{{ route('admin.web.settings') }}" class="btn-paces btn-paces-primary" style="padding:0.45rem 1rem; font-size:0.82rem;">
          Configure Settings →
        </a>
      </div>
    </div>

    <!-- 2. Services & Catalog -->
    <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
          <div style="width:44px; height:44px; border-radius:10px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
            🛠️
          </div>
          <span class="badge-pill-paces bg-primary-subtle">
            {{ $stats['active_services'] }} Active Services
          </span>
        </div>
        <h3 class="card-title-paces" style="margin-bottom:6px;">Services Portfolio & Pricing</h3>
        <p style="font-size:0.85rem; color:var(--paces-text-muted); line-height:1.5; margin-bottom:1rem;">
          Manage the public services directory, service categories, base pricing (৳), SLA hours, scope bullets, and thumbnail imagery.
        </p>
      </div>
      <div style="border-top:1px solid var(--paces-border); padding-top:1rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; color:var(--paces-text-muted);">{{ $stats['categories_count'] }} Categories</span>
        <a href="{{ route('admin.web.services') }}" class="btn-paces btn-paces-primary" style="padding:0.45rem 1rem; font-size:0.82rem;">
          Manage Services →
        </a>
      </div>
    </div>

    <!-- 3. AMC Packages -->
    <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
          <div style="width:44px; height:44px; border-radius:10px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
            📋
          </div>
          <span class="badge-pill-paces bg-warning-subtle">
            {{ $stats['amc_plans_count'] }} Plans Configured
          </span>
        </div>
        <h3 class="card-title-paces" style="margin-bottom:6px;">AMC Maintenance Packages</h3>
        <p style="font-size:0.85rem; color:var(--paces-text-muted); line-height:1.5; margin-bottom:1rem;">
          Control Annual Maintenance Contract packages (Home, Commercial, Industrial), pricing, emergency response SLAs, and feature perks.
        </p>
      </div>
      <div style="border-top:1px solid var(--paces-border); padding-top:1rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; color:var(--paces-text-muted);">Annual Contracts</span>
        <a href="{{ route('admin.web.amc-plans') }}" class="btn-paces btn-paces-primary" style="padding:0.45rem 1rem; font-size:0.82rem;">
          Manage Packages →
        </a>
      </div>
    </div>

    <!-- 4. Section Contents -->
    <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
          <div style="width:44px; height:44px; border-radius:10px; background:#f5f3ff; color:#7c3aed; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
            📝
          </div>
          <span class="badge-pill-paces bg-info-subtle">
            {{ $stats['contents_count'] }} Sections
          </span>
        </div>
        <h3 class="card-title-paces" style="margin-bottom:6px;">Hero Banner & Section Contents</h3>
        <p style="font-size:0.85rem; color:var(--paces-text-muted); line-height:1.5; margin-bottom:1rem;">
          Update homepage hero titles, taglines, YouTube operations video portfolio URL, and "Why Choose FIX" engineering highlights.
        </p>
      </div>
      <div style="border-top:1px solid var(--paces-border); padding-top:1rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; color:var(--paces-text-muted);">Landing Page Copy</span>
        <a href="{{ route('admin.web.contents') }}" class="btn-paces btn-paces-primary" style="padding:0.45rem 1rem; font-size:0.82rem;">
          Edit Contents →
        </a>
      </div>
    </div>

    <!-- 5. FAQs Knowledgebase -->
    <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
          <div style="width:44px; height:44px; border-radius:10px; background:#ecfdf5; color:#059669; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
            ❓
          </div>
          <span class="badge-pill-paces bg-success-subtle">
            {{ $stats['faqs_count'] }} Questions Published
          </span>
        </div>
        <h3 class="card-title-paces" style="margin-bottom:6px;">Frequently Asked Questions (FAQ)</h3>
        <p style="font-size:0.85rem; color:var(--paces-text-muted); line-height:1.5; margin-bottom:1rem;">
          Manage customer inquiries, booking instructions, AMC payment terms, and emergency response knowledge items.
        </p>
      </div>
      <div style="border-top:1px solid var(--paces-border); padding-top:1rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; color:var(--paces-text-muted);">Customer Help</span>
        <a href="{{ route('admin.web.faqs') }}" class="btn-paces btn-paces-primary" style="padding:0.45rem 1rem; font-size:0.82rem;">
          Manage FAQs →
        </a>
      </div>
    </div>

    <!-- 6. Client Testimonials -->
    <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
          <div style="width:44px; height:44px; border-radius:10px; background:#fff1f2; color:#e11d48; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
            ⭐
          </div>
          <span class="badge-pill-paces bg-danger-subtle">
            {{ $stats['testimonials_count'] }} Reviews
          </span>
        </div>
        <h3 class="card-title-paces" style="margin-bottom:6px;">Client Testimonials & Trust</h3>
        <p style="font-size:0.85rem; color:var(--paces-text-muted); line-height:1.5; margin-bottom:1rem;">
          Review and feature customer feedback, company endorsements, and 5-star ratings on the homepage social proof section.
        </p>
      </div>
      <div style="border-top:1px solid var(--paces-border); padding-top:1rem; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; color:var(--paces-text-muted);">Social Proof</span>
        <a href="{{ route('admin.web.testimonials') }}" class="btn-paces btn-paces-primary" style="padding:0.45rem 1rem; font-size:0.82rem;">
          Manage Reviews →
        </a>
      </div>
    </div>

  </div>
</x-layouts.admin>
