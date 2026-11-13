<x-layouts.app :title="'Become a Service Partner | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
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
          <a href="{{ route('partner.login') }}" class="btn btn-outline-white btn-lg" style="background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.25);">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Partner Portal Login ↗
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
        <h2 class="section-title">Partner Enlistment</h2>
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

            <div class="form-group" style="margin-bottom:1.5rem;">
              <label>Active Field Technicians Count <span class="required">*</span></label>
              <select name="technicians_count" class="form-control" required>
                <option value="1-5" {{ old('technicians_count') === '1-5' ? 'selected' : '' }}>1 - 5 Technicians</option>
                <option value="6-15" {{ old('technicians_count') === '6-15' ? 'selected' : '' }}>6 - 15 Technicians</option>
                <option value="16-50" {{ old('technicians_count') === '16-50' ? 'selected' : '' }}>16 - 50 Technicians</option>
                <option value="50+" {{ old('technicians_count') === '50+' ? 'selected' : '' }}>50+ Field Workforce</option>
              </select>
            </div>

            @php
              $categoriesList = isset($serviceCategories) && $serviceCategories->isNotEmpty()
                ? $serviceCategories->pluck('name')->all()
                : [
                    'Electrical & Substation Power',
                    'HVAC & Central Air Conditioning',
                    'Plumbing & Water Boosting Systems',
                    'Fire Safety & NFPA Suppression',
                    'Commercial Janitorial & Sanitization',
                    'Interior Fit-Out & Renovation',
                    'General Facility Maintenance',
                  ];

              $tradeDomainOptions = $categoriesList;

              $locationsList = isset($activeDivisions) && $activeDivisions->isNotEmpty()
                ? $activeDivisions
                : \App\Models\Division::whereHas('districts', fn($q) => $q->where('is_active', true))->with(['districts' => fn($q) => $q->where('is_active', true)->with(['thanas' => fn($t) => $t->where('is_active', true)->orderBy('name')])->orderBy('name')])->orderBy('name')->get();

              $rawOldDomains = old('trade_domain', []);
              $selectedDomains = is_array($rawOldDomains) ? $rawOldDomains : array_map('trim', explode(',', (string) $rawOldDomains));

              $rawOldAreas = old('coverage_areas', []);
              $selectedAreas = is_array($rawOldAreas) ? $rawOldAreas : array_map('trim', explode(',', (string) $rawOldAreas));
            @endphp

            <div class="form-group" style="margin-bottom:1.75rem;">
              <label style="display:block; margin-bottom:0.6rem; font-weight:600;">
                Primary Technical Domain <span class="required">*</span>
                <span style="font-weight:400; color:#64748b; font-size:0.85rem; margin-left:6px;">(Select one or multiple core technical competencies)</span>
              </label>
              <div class="competency-checkbox-grid">
                @foreach($tradeDomainOptions as $domain)
                  @php
                    $isDomainChecked = in_array($domain, $selectedDomains, true) || in_array(str_replace([' Power', ' Systems'], '', $domain), $selectedDomains, true);
                  @endphp
                  <label class="competency-checkbox-card">
                    <input type="checkbox" name="trade_domain[]" value="{{ $domain }}" {{ $isDomainChecked ? 'checked' : '' }}>
                    <span class="checkbox-label-text">{{ $domain }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Operating Coverage Areas with Division/District Filters -->
            <div class="form-group" style="margin-bottom:1.5rem;">
              <div style="display:flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap; gap:8px; margin-bottom:0.6rem;">
                <label style="margin-bottom:0; font-weight:600;">
                  Operating Coverage Areas <span class="required">*</span>
                  <span style="font-weight:400; color:#64748b; font-size:0.85rem; margin-left:6px;">(Multi-select operating thanas/areas)</span>
                </label>
                <span id="vendorSelectedAreasCountBadge" style="font-size:0.8rem; font-weight:700; color:#0d9488; background:#f0fdfa; border:1px solid #ccfbf1; padding:2px 10px; border-radius:9999px;">
                  0 Selected
                </span>
              </div>

              <!-- Selected Areas Tag Container (Above filter) -->
              <div style="margin-bottom:14px;">
                <div style="font-size:0.8rem; font-weight:600; color:#64748b; margin-bottom:6px;">Selected Operating Areas:</div>
                <div id="vendorSelectedAreasTagContainer" style="display:flex; flex-wrap:wrap; gap:6px; min-height:34px; padding:8px 10px; background:#fff; border:1px dashed #cbd5e1; border-radius:8px;">
                  <!-- Dynamically rendered badge pills with × button -->
                </div>
              </div>

              <!-- Division & District Filter Dropdowns -->
              <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; padding:12px 14px;">
                <div>
                  <label style="font-size:0.8rem; color:#64748b; margin-bottom:4px; font-weight:600; display:block;">Division Filter</label>
                  <select id="vendorDivisionSelect" class="form-control" style="background:#fff; font-weight:600;" onchange="onVendorDivisionChange(this.value)">
                    <option value="">-- Select Division --</option>
                    @forelse($locationsList as $div)
                      <option value="{{ $div->id }}">{{ $div->name }}</option>
                    @empty
                      <option value="">No Active Divisions</option>
                    @endforelse
                  </select>
                </div>

                <div>
                  <label style="font-size:0.8rem; color:#64748b; margin-bottom:4px; font-weight:600; display:block;">District Filter</label>
                  <select id="vendorDistrictSelect" class="form-control" style="background:#fff; font-weight:600;" onchange="onVendorDistrictChange(this.value)" disabled>
                    <option value="">-- Select Division First --</option>
                  </select>
                </div>
              </div>

              <!-- Quick Selection Bar (Collapsed until district is selected) -->
              <div id="vendorQuickSelectionBar" style="display:none; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:10px; font-size:0.82rem;">
                <span style="color:#64748b; font-weight:500;">Select areas for chosen district:</span>
                <div style="display:flex; gap:8px;">
                  <button type="button" class="btn btn-sm" style="padding:4px 10px; font-size:0.76rem; border:1px solid #cbd5e1; background:#fff; border-radius:6px; cursor:pointer;" onclick="selectAllVendorDistrictAreas()">Select All in District</button>
                  <button type="button" class="btn btn-sm" style="padding:4px 10px; font-size:0.76rem; border:1px solid #fca5a5; background:#fff; color:#ef4444; border-radius:6px; cursor:pointer;" onclick="deselectAllVendorDistrictAreas()">Clear District</button>
                </div>
              </div>

              <!-- Collapsed Notice when not selected -->
              <div id="vendorDistrictAreasCollapsedNotice" style="padding:14px 16px; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:10px; font-size:0.84rem; color:#64748b; text-align:center; display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:12px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Select a <strong>Division</strong> and <strong>District</strong> above to filter and pick coverage areas.</span>
              </div>

              <!-- Thanas / Areas Checkbox Groups (Rendered per District) -->
              <div id="vendorDistrictAreasWrapper">
                @forelse($locationsList as $div)
                  @foreach($div->districts as $district)
                    <div class="vendor-district-areas-panel" id="vendorDistrictAreasPanel_{{ $district->id }}" data-division-id="{{ $div->id }}" data-district-id="{{ $district->id }}" style="display:none;">
                      @if($district->thanas->isEmpty())
                        <div style="padding:14px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; font-size:0.85rem; color:#64748b; text-align:center;">
                          No active areas/thanas configured under {{ $district->name }}.
                        </div>
                      @else
                        <div class="competency-checkbox-grid">
                          @foreach($district->thanas as $thana)
                            @php
                              $isThanaChecked = in_array($thana->name, $selectedAreas, true)
                                || in_array($thana->name . ' (' . $district->name . ')', $selectedAreas, true);
                            @endphp
                            <label class="competency-checkbox-card">
                              <input type="checkbox" name="coverage_areas[]" value="{{ $thana->name }}" {{ $isThanaChecked ? 'checked' : '' }} class="vendor-area-checkbox-item" data-district-id="{{ $district->id }}" data-area-name="{{ $thana->name }}" onchange="updateVendorSelectedAreasSummary()">
                              <span class="checkbox-label-text">{{ $thana->name }}</span>
                            </label>
                          @endforeach
                        </div>
                      @endif
                    </div>
                  @endforeach
                @empty
                  <div style="padding:14px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; font-size:0.85rem; color:#64748b; text-align:center;">
                    No active coverage areas configured.
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <style>
            .competency-checkbox-grid {
              display: grid;
              grid-template-columns: repeat(2, 1fr);
              gap: 10px;
            }
            @media (max-width: 640px) {
              .competency-checkbox-grid {
                grid-template-columns: 1fr;
              }
            }
            .competency-checkbox-card {
              display: flex;
              align-items: center;
              gap: 12px;
              padding: 12px 14px;
              background: #ffffff;
              border: 1.5px solid #e2e8f0;
              border-radius: 10px;
              cursor: pointer;
              transition: all 0.2s ease;
              user-select: none;
              margin: 0;
            }
            .competency-checkbox-card:hover {
              background: #f8fafc;
              border-color: #cbd5e1;
            }
            .competency-checkbox-card input[type="checkbox"] {
              width: 18px;
              height: 18px;
              accent-color: #0d9488;
              cursor: pointer;
              flex-shrink: 0;
              margin: 0;
            }
            .competency-checkbox-card:has(input[type="checkbox"]:checked) {
              background: #f0fdf4;
              border-color: #10b981;
              box-shadow: 0 1px 3px rgba(16, 185, 129, 0.12);
            }
            .competency-checkbox-card .checkbox-label-text {
              font-size: 0.9rem;
              font-weight: 500;
              color: #1e293b;
              line-height: 1.35;
            }
          </style>

          <button type="submit" class="btn btn-accent btn-lg btn-block" style="padding:1.1rem; width:100%;">
            Submit Enlistment Application
          </button>
        </form>
      </div>
    </div>
  </section>

  <script>
    const vendorLocationsData = @json($locationsList);

    function onVendorDivisionChange(selectedDivId) {
      const distSelect = document.getElementById('vendorDistrictSelect');
      if (!distSelect) return;
      distSelect.innerHTML = '';

      if (!selectedDivId) {
        distSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = '-- Select Division First --';
        distSelect.appendChild(opt);
        onVendorDistrictChange('');
        return;
      }

      const division = vendorLocationsData.find(d => String(d.id) === String(selectedDivId));
      if (!division || !division.districts || division.districts.length === 0) {
        distSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'No active districts';
        distSelect.appendChild(opt);
        onVendorDistrictChange('');
        return;
      }

      distSelect.disabled = false;
      const defaultOpt = document.createElement('option');
      defaultOpt.value = '';
      defaultOpt.textContent = '-- Select District --';
      distSelect.appendChild(defaultOpt);

      division.districts.forEach(dist => {
        const opt = document.createElement('option');
        opt.value = dist.id;
        opt.textContent = dist.name;
        distSelect.appendChild(opt);
      });

      onVendorDistrictChange('');
    }

    function onVendorDistrictChange(selectedDistId) {
      let hasActivePanel = false;
      document.querySelectorAll('.vendor-district-areas-panel').forEach(panel => {
        if (selectedDistId && String(panel.dataset.districtId) === String(selectedDistId)) {
          panel.style.display = 'block';
          hasActivePanel = true;
        } else {
          panel.style.display = 'none';
        }
      });

      const quickBar = document.getElementById('vendorQuickSelectionBar');
      if (quickBar) {
        quickBar.style.display = hasActivePanel ? 'flex' : 'none';
      }

      const notice = document.getElementById('vendorDistrictAreasCollapsedNotice');
      if (notice) {
        notice.style.display = hasActivePanel ? 'none' : 'flex';
      }
    }

    function selectAllVendorDistrictAreas() {
      const currentDistId = document.getElementById('vendorDistrictSelect')?.value;
      const panel = document.getElementById('vendorDistrictAreasPanel_' + currentDistId);
      if (panel) {
        panel.querySelectorAll('.vendor-area-checkbox-item').forEach(cb => cb.checked = true);
        updateVendorSelectedAreasSummary();
      }
    }

    function deselectAllVendorDistrictAreas() {
      const currentDistId = document.getElementById('vendorDistrictSelect')?.value;
      const panel = document.getElementById('vendorDistrictAreasPanel_' + currentDistId);
      if (panel) {
        panel.querySelectorAll('.vendor-area-checkbox-item').forEach(cb => cb.checked = false);
        updateVendorSelectedAreasSummary();
      }
    }

    function updateVendorSelectedAreasSummary() {
      const checkedBoxes = Array.from(document.querySelectorAll('.vendor-area-checkbox-item:checked'));
      const countBadge = document.getElementById('vendorSelectedAreasCountBadge');
      if (countBadge) {
        countBadge.textContent = checkedBoxes.length + ' Selected';
      }

      const container = document.getElementById('vendorSelectedAreasTagContainer');
      if (!container) return;

      if (checkedBoxes.length === 0) {
        container.innerHTML = '<span style="font-size:0.8rem; color:#94a3b8; font-style:italic;">No operating areas selected yet. Please select from the districts above.</span>';
        return;
      }

      container.innerHTML = '';
      checkedBoxes.forEach(cb => {
        const areaName = cb.dataset.areaName || cb.value;
        const pill = document.createElement('span');
        pill.style.cssText = 'display:inline-flex; align-items:center; gap:5px; padding:3px 9px; background:#f0fdfa; border:1px solid #ccfbf1; border-radius:9999px; font-size:0.78rem; font-weight:600; color:#0d9488;';
        pill.innerHTML = `<span>${areaName}</span><button type="button" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; line-height:1; font-size:0.85rem;" title="Remove area">&times;</button>`;
        pill.querySelector('button').onclick = function() {
          cb.checked = false;
          updateVendorSelectedAreasSummary();
        };
        container.appendChild(pill);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Keep coverage filter collapsed until user selects division & district
      onVendorDivisionChange('');
      updateVendorSelectedAreasSummary();

      const form = document.querySelector('form[action="{{ route('vendor.apply') }}"]');
      if (!form) return;
      form.addEventListener('submit', function(e) {
        const domains = form.querySelectorAll('input[name="trade_domain[]"]:checked');
        const areas = form.querySelectorAll('input[name="coverage_areas[]"]:checked');
        
        if (domains.length === 0) {
          e.preventDefault();
          alert('Please select at least one Primary Technical Domain.');
          return false;
        }
        if (areas.length === 0) {
          e.preventDefault();
          alert('Please select at least one Operating Coverage Area.');
          return false;
        }
      });
    });
  </script>
</x-layouts.app>
