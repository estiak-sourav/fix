<x-layouts.app title="Become a Service Partner | FIX Facility Management">
  @php
    $heroContent = \App\Models\SiteContent::forSection('vendor_hero');
    $benefitsContent = \App\Models\SiteContent::forSection('vendor_benefits');
    $hMeta = $heroContent?->meta_data ?? [];
    $bMeta = $benefitsContent?->meta_data ?? [];
    $perks = $bMeta['perks'] ?? [
      [
        'title' => 'Consistent Commercial Orders',
        'description' => 'Gain direct access to corporate headquarters, hospitals, EPZ industrial facilities, and luxury residences without spending on marketing.'
      ],
      [
        'title' => 'Guaranteed Timely Payments',
        'description' => 'Transparent job completion verification with automatic bi-weekly or monthly disbursements directly to your company bank or bKash account.'
      ],
      [
        'title' => 'Free Safety Gear & ID Kits',
        'description' => 'Enlisted partner technicians receive standardized FIX professional uniform vests, high-altitude harnesses, safety kits, and verified photo ID badges.'
      ]
    ];
  @endphp

  <!-- Hero Section -->
  <section class="vendor-hero" id="vendor-hero">
    <div id="vendor_hero"></div>
    <div class="container vendor-hero-grid">
      <div class="vendor-hero-content">
        <h1>{!! $heroContent?->title ?? 'Grow Your Facility Service Business with <span>Institutional Work Orders</span>' !!}</h1>
        <p>
          {{ $heroContent?->subtitle ?? "Partner with Bangladesh's unified facility operations network. Gain steady access to corporate towers, hospitals, factories, and commercial complexes with guaranteed bi-weekly payouts and zero customer acquisition cost." }}
        </p>

        <div class="vendor-hero-actions">
          <a href="{{ $heroContent?->button_url ?? '#vendorFormSection' }}" class="btn btn-accent btn-lg">
            {{ $heroContent?->button_text ?? 'Apply for Enlistment' }}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </a>
          <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+8801700000000') }}" class="btn btn-outline-white btn-lg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:18px;height:18px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            Hotline: {{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}
          </a>
        </div>

        <div class="vendor-stats-strip">
          <div class="vendor-stat-item">
            <div class="vendor-stat-num">{{ $hMeta['stat_partners'] ?? '500+' }}</div>
            <div class="vendor-stat-label">{{ $hMeta['stat_partners_label'] ?? 'Enlisted Vendor Partners' }}</div>
          </div>
          <div class="vendor-stat-item">
            <div class="vendor-stat-num">{{ $hMeta['stat_payouts'] ?? '৳ 2.5 Cr+' }}</div>
            <div class="vendor-stat-label">{{ $hMeta['stat_payouts_label'] ?? 'Partner Payouts Disbursed' }}</div>
          </div>
          <div class="vendor-stat-item">
            <div class="vendor-stat-num">{{ $hMeta['stat_verification'] ?? '24-48h' }}</div>
            <div class="vendor-stat-label">{{ $hMeta['stat_verification_label'] ?? 'Fast-Track Verification' }}</div>
          </div>
        </div>
      </div>

      <div class="vendor-hero-visual">
        <div class="vendor-hero-card">
          <img src="{{ $heroContent?->media_path ? (str_starts_with($heroContent->media_path, 'http') ? $heroContent->media_path : asset($heroContent->media_path)) : 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80' }}" alt="Professional Facility Maintenance Technician" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=800&q=80';">
          
          <div class="vendor-floating-badge vendor-badge-top">
            <span style="display:flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:var(--accent); color:#fff; font-weight:800;">✓</span>
            <div>
              <strong style="display:block; font-size:0.875rem;">{{ $hMeta['floating_badge_top_title'] ?? 'Guaranteed Corporate Payouts' }}</strong>
              <span style="font-size:0.75rem; color:#94a3b8;">{{ $hMeta['floating_badge_top_text'] ?? 'Direct Bank & bKash Bi-Weekly Settlement' }}</span>
            </div>
          </div>

          <div class="vendor-floating-badge vendor-badge-bottom">
            <span style="color:#fbbf24; font-size:1.1rem;">★★★★★</span>
            <div>
              <strong style="display:block; font-size:0.875rem;">{{ $hMeta['floating_badge_bottom_title'] ?? '4.9 Partner Rating' }}</strong>
              <span style="font-size:0.75rem; color:#94a3b8;">{{ $hMeta['floating_badge_bottom_text'] ?? '98% Contractor Retention Across Dhaka & EPZ' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vendor Benefits Strip -->
  @if($benefitsContent?->is_active ?? true)
    <section class="section" id="vendor-benefits">
      <div id="vendor_benefits"></div>
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">{!! $benefitsContent?->title ?? 'Why Top Technical Contractors Partner with FIX' !!}</h2>
          <p class="section-subtitle">{{ $benefitsContent?->subtitle ?? 'We manage corporate sales, compliance, client billing, and dispatch — so you can focus 100% on quality service execution.' }}</p>
        </div>

        <div class="vendor-perks-grid">
          @foreach($perks as $pIdx => $perk)
            <div class="vendor-perk-card">
              <div class="vendor-perk-icon">
                @if($pIdx === 0)
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                @elseif($pIdx === 1)
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="6" y1="12" x2="18" y2="12"></line></svg>
                @else
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                @endif
              </div>
              <h4>{{ $perk['title'] ?? '' }}</h4>
              <p>{{ $perk['description'] ?? '' }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <!-- Registration Form Section -->
  <section id="vendorFormSection" class="section section-alt">
    <div class="container" style="max-width:850px;">
      <div class="section-header">
        <h2 class="section-title">Partner Enlistment Application</h2>
        <p class="section-subtitle">Join our verified network of engineering contractors and technical experts.</p>
      </div>

      @if(session('success'))
        <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:14px 20px; border-radius:12px; margin-bottom:1.5rem; font-weight:500; font-size:0.95rem; display:flex; align-items:center; justify-content:space-between;">
          <span>✓ {{ session('success') }}</span>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#166534; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      @if($errors->any())
        <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:14px 20px; border-radius:12px; margin-bottom:1.5rem; font-size:0.9rem; display:flex; align-items:flex-start; justify-content:space-between;">
          <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <div class="form-card">
        <form action="{{ route('vendor.apply') }}" method="POST">
          @csrf
          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">1</span>
              <h3>Business & Representative Profile</h3>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Company / Trade Name <span class="required">*</span></label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control" placeholder="e.g. Dhaka HVAC Engineering Works" required>
              </div>
              <div class="form-group">
                <label>Lead Contact Person <span class="required">*</span></label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control" placeholder="e.g. Engr. Shafiqul Alam" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Mobile Number <span class="required">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="017xxxxxxxx" required>
              </div>
              <div class="form-group">
                <label>Official Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="contact@company.com">
              </div>
            </div>
          </div>

          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">2</span>
              <h3>Core Trade Competencies</h3>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Primary Technical Domain <span class="required">*</span></label>
                <select name="trade_domain" class="form-control" required>
                  <option value="Electrical & Substation">Electrical & Substation Power</option>
                  <option value="HVAC & Central Air Conditioning">HVAC & Central Air Conditioning</option>
                  <option value="Plumbing & Water Boosting">Plumbing & Water Boosting Systems</option>
                  <option value="Fire Safety & NFPA Suppression">Fire Safety & NFPA Suppression</option>
                  <option value="Commercial Janitorial & Sanitization">Commercial Janitorial & Sanitization</option>
                  <option value="Interior Fit-Out & Renovation">Interior Fit-Out & Renovation</option>
                  <option value="Pest Control & Vector Management">Pest Control & Vector Management</option>
                </select>
              </div>

              <div class="form-group">
                <label>Active Field Technicians Count <span class="required">*</span></label>
                <select name="technicians_count" class="form-control" required>
                  <option value="1-5">1 - 5 Technicians</option>
                  <option value="6-15">6 - 15 Technicians</option>
                  <option value="16-50">16 - 50 Technicians</option>
                  <option value="50+">50+ Field Workforce</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Operating Coverage Areas <span class="required">*</span></label>
              <input type="text" name="coverage_areas" value="{{ old('coverage_areas') }}" class="form-control" placeholder="e.g. Dhaka Metro, Savar EPZ, Gazipur, Narayanganj" required>
            </div>
          </div>

          <button type="submit" class="btn btn-accent btn-lg btn-block" style="padding:1.1rem; width:100%;">
            Submit Enlistment Application
          </button>
        </form>
      </div>
    </div>
  </section>
</x-layouts.app>
