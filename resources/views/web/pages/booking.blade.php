<x-layouts.app :title="'Book a Service | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
  <!-- Page Header -->
  <x-page-hero 
    title="Book a Building & Facility Service"
    subtitle="Submit your maintenance or inspection request. Our certified technical teams are ready to resolve any issue across your property."
  />

  <!-- Main Booking Form Layout -->
  <section class="section" style="padding-top:0;">
    <div class="container">
      @if(session('error'))
        <div style="background:#fee2e2; border:1px solid #f87171; color:#991b1b; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div>{{ session('error') }}</div>
          @guest
            <button type="button" class="btn btn-accent btn-sm" onclick="openAuthModal()">Sign In Now</button>
          @endguest
        </div>
      @endif

      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <span>{{ session('success') }}</span>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <div class="booking-layout-grid">
        <!-- Booking Form Container -->
        <div class="form-card">
          <form id="serviceBookingForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Hidden field indicating service or AMC plan -->
            <input type="hidden" name="service_type" id="bookServiceType" value="{{ $selectedPlan ? 'amc_contract' : 'on_demand' }}">

            <!-- Step 1: Personal Contact Information -->
            <div class="form-step-section">
              <div class="step-header-title">
                <span class="step-num-pill">1</span>
                <h3>Personal & Contact Information</h3>
              </div>

              @guest
                <div style="background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:8px; padding:0.85rem 1.25rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                  <div style="display:flex; align-items:center; gap:8px; font-weight:700; color:#92400e; font-size:0.95rem;">
                    <svg style="width:19px; height:19px; flex-shrink:0; color:#d97706;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Login Required</span>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm" onclick="openAuthModal()" style="font-weight:600; padding:0.45rem 1.15rem; border-radius:6px;">Sign Up</button>
                </div>
              @endguest

              <div class="form-row">
                <div class="form-group">
                  <label for="bookFullName">Full Name <span class="required">*</span></label>
                  <input type="text" id="bookFullName" name="customer_name" class="form-control" 
                    value="{{ old('customer_name', auth()->user()?->name ?? '') }}" placeholder="e.g. Tanvir Mahmud" required>
                </div>
                <div class="form-group">
                  <label for="bookPhone">Mobile Number (Bangladesh) <span class="required">*</span></label>
                  <input type="tel" id="bookPhone" name="customer_phone" class="form-control" 
                    value="{{ old('customer_phone', auth()->user()?->phone ?? '') }}" placeholder="017xxxxxxxx" required>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="bookEmail">Email Address</label>
                  <input type="email" id="bookEmail" class="form-control" 
                    value="{{ auth()->user()?->email ?? '' }}" placeholder="client@company.com"
                    {{ auth()->check() ? 'readonly style="background:var(--bg-page); color:var(--text-muted); cursor:not-allowed;"' : '' }}>
                </div>
                <div class="form-group">
                  <label for="bookCompany">Company / Organization (Optional)</label>
                  <input type="text" id="bookCompany" name="company_name" class="form-control" 
                    value="{{ old('company_name', auth()->user()?->company_name ?? '') }}" placeholder="e.g. Apex Holdings Ltd.">
                </div>
              </div>
            </div>

            <!-- Step 2: Service & Property Details -->
            <div class="form-step-section">
              <div class="step-header-title">
                <span class="step-num-pill">2</span>
                <h3>Service Requirements</h3>
              </div>

              <!-- Booking Type Switcher: Single Service vs AMC Contract -->
              <div style="background:#f1f5f9; border:1px solid #cbd5e1; border-radius:12px; padding:6px; margin-bottom:1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                <button type="button" id="btnTypeOneTime" onclick="setBookingType('on_demand')" style="padding:10px 14px; border-radius:8px; border:none; background:{{ $selectedPlan ? 'transparent' : '#ffffff' }}; box-shadow:{{ $selectedPlan ? 'none' : '0 2px 6px rgba(0,0,0,0.08)' }}; font-weight:{{ $selectedPlan ? '600' : '800' }}; font-size:0.9rem; color:{{ $selectedPlan ? '#64748b' : '#0f172a' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.2s ease;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                  <span>Single Service (One-Time)</span>
                </button>
                <button type="button" id="btnTypeAmc" onclick="setBookingType('amc_contract')" style="padding:10px 14px; border-radius:8px; border:none; background:{{ $selectedPlan ? '#ffffff' : 'transparent' }}; box-shadow:{{ $selectedPlan ? '0 2px 6px rgba(0,0,0,0.08)' : 'none' }}; font-weight:{{ $selectedPlan ? '800' : '600' }}; font-size:0.9rem; color:{{ $selectedPlan ? '#00a896' : '#64748b' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.2s ease;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                  <span>AMC Contract (Recurring Routine)</span>
                </button>
              </div>

              <div class="form-row">
                <div class="form-group" style="position:relative;" id="serviceSearchableGroup">
                  <label for="bookCategory">Select Service Required <span class="required">*</span></label>
                  
                  <!-- Native fallback select (kept for form submission & automated binding) -->
                  <select id="bookCategory" name="service_id" class="form-control" required style="display:none;">
                    <option value="">-- Choose Required Service --</option>
                    @foreach($services as $srv)
                      <option value="{{ $srv->id }}"
                        data-title="{{ $srv->title }}"
                        data-slug="{{ $srv->slug }}"
                        data-thumbnail="{{ $srv->thumbnail_url }}"
                        data-subtitle="{{ $srv->subtitle }}"
                        data-price="{{ $srv->price_starting }}"
                        data-unit="{{ $srv->pricing_unit }}"
                        data-sla="{{ $srv->sla_hours }}"
                        {{ (old('service_id') == $srv->id || (isset($selectedService) && (strtolower(trim($selectedService)) == strtolower(trim($srv->title)) || strtolower(trim($selectedService)) == strtolower(trim($srv->slug)) || $selectedService == $srv->id))) ? 'selected' : '' }}>
                        {{ $srv->title }}
                      </option>
                    @endforeach
                  </select>

                  <!-- Custom Searchable Trigger & Dropdown -->
                  <div id="serviceSearchTrigger" class="form-control" style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; padding:0.75rem 1rem; background:#fff; min-height:48px;" onclick="toggleServiceDropdown()">
                    <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:0;">
                      <div id="selectedServiceThumb" style="width:28px; height:28px; border-radius:6px; overflow:hidden; background:#f1f5f9; display:none; flex-shrink:0;">
                        <img id="selectedServiceThumbImg" src="" style="width:100%; height:100%; object-fit:cover;">
                      </div>
                      <span id="selectedServiceText" style="font-weight:500; color:var(--text-main); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        -- Choose Required Service --
                      </span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; color:#64748b;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                  </div>

                  <!-- Dropdown Menu with Live Search Filter -->
                  <div id="serviceDropdownMenu" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; background:#ffffff; border:1px solid var(--border); border-radius:10px; box-shadow:0 15px 35px rgba(0,0,0,0.12); z-index:9999; padding:8px 0; max-height:320px; overflow-y:auto;">
                    <!-- Live Search Input -->
                    <div style="padding:4px 12px 10px; border-bottom:1px solid #f1f5f9;">
                      <div style="position:relative;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="serviceSearchInput" placeholder="Search service name (e.g. HVAC, Electrical, Plumbing)..." 
                          oninput="filterServicesInDropdown(this.value)" 
                          style="width:100%; box-sizing:border-box; padding:8px 12px 8px 32px; border-radius:6px; border:1px solid #cbd5e1; font-size:0.85rem; outline:none;" 
                          onclick="event.stopPropagation()">
                      </div>
                    </div>

                    <!-- Options List -->
                    <div id="serviceOptionsList">
                      @foreach($services as $srv)
                        <div class="service-search-option" 
                             data-id="{{ $srv->id }}"
                             data-title="{{ $srv->title }}"
                             data-search="{{ strtolower($srv->title . ' ' . $srv->subtitle . ' ' . ($srv->category?->name ?? '')) }}"
                             onclick="selectServiceFromDropdown('{{ $srv->id }}')"
                             style="display:flex; align-items:center; gap:12px; padding:10px 14px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                             onmouseover="this.style.background='#eff6ff'" 
                             onmouseout="this.style.background='transparent'">
                          <img src="{{ $srv->thumbnail_url }}" alt="{{ $srv->title }}" style="width:40px; height:40px; border-radius:8px; object-fit:cover; border:1px solid #e2e8f0; flex-shrink:0;">
                          <div style="flex:1; min-width:0;">
                            <div style="font-weight:600; font-size:0.9rem; color:#0f172a;">{{ $srv->title }}</div>
                            <div style="font-size:0.75rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                              {{ $srv->subtitle ?: ($srv->category?->name ?? 'Commercial & Residential Engineering') }}
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <div id="noServicesFound" style="display:none; padding:16px; text-align:center; color:#64748b; font-size:0.85rem;">
                      No services match your search query.
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="bookSpecificService">Specific Task / Scope</label>
                  <select id="bookSpecificService" name="specific_service" class="form-control">
                    <option value="">Comprehensive Inspection / General Service</option>
                  </select>
                </div>
              </div>

              <div class="form-row" id="propertySqftRow" style="grid-template-columns: 1fr;">
                <div class="form-group" id="propertyTypeGroup">
                  <label for="bookProperty">Property Type <span class="required">*</span></label>
                  <select id="bookProperty" name="property_type" class="form-control" required>
                    <option value="Corporate Office" {{ old('property_type', auth()->user()?->property_type) == 'Corporate Office' ? 'selected' : '' }}>Corporate Office</option>
                    <option value="Residential Apartment / Flat" {{ old('property_type', auth()->user()?->property_type) == 'Residential Apartment / Flat' ? 'selected' : '' }}>Residential Apartment / Flat</option>
                    <option value="Residential Building (Full Complex)" {{ old('property_type', auth()->user()?->property_type) == 'Residential Building (Full Complex)' ? 'selected' : '' }}>Residential Building (Full Complex)</option>
                    <option value="Commercial Tower / Plaza" {{ old('property_type', auth()->user()?->property_type) == 'Commercial Tower / Plaza' ? 'selected' : '' }}>Commercial Tower / Plaza</option>
                    <option value="Industrial Factory / EPZ Facility" {{ old('property_type', auth()->user()?->property_type) == 'Industrial Factory / EPZ Facility' ? 'selected' : '' }}>Industrial Factory / EPZ Facility</option>
                    <option value="Hospital / Healthcare Clinic" {{ old('property_type', auth()->user()?->property_type) == 'Hospital / Healthcare Clinic' ? 'selected' : '' }}>Hospital / Healthcare Clinic</option>
                    <option value="Educational Institution" {{ old('property_type', auth()->user()?->property_type) == 'Educational Institution' ? 'selected' : '' }}>Educational Institution</option>
                  </select>
                </div>

                <div class="form-group" id="sqftFormGroup" style="display: none;">
                  <label for="bookSqft">Estimated Area Size (sq ft) <span style="font-weight:400; color:var(--text-muted); font-size:0.75rem;">(Optional)</span></label>
                  <input type="number" id="bookSqft" name="estimated_sqft" class="form-control" 
                    value="{{ old('estimated_sqft') }}" placeholder="e.g. 2400" min="10" max="5000000">
                  <small style="color:var(--text-muted); font-size:0.72rem; display:block; margin-top:4px;">Relevant for deep cleaning, sanitization, or renovation floor scope.</small>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="bookDate">Preferred Service Date <span class="required">*</span></label>
                  <input type="date" id="bookDate" name="preferred_date" class="form-control" 
                    value="{{ old('preferred_date', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                  <label for="bookTimeSlot">Preferred Time Slot <span class="required">*</span></label>
                  <select id="bookTimeSlot" name="preferred_time_slot" class="form-control" required>
                    <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                    <option value="Afternoon (12:00 PM - 4:00 PM)">Afternoon (12:00 PM - 4:00 PM)</option>
                    <option value="Evening (4:00 PM - 8:00 PM)">Evening (4:00 PM - 8:00 PM)</option>
                    <option value="Emergency (Immediate Dispatch ASAP)">🚨 Emergency (Immediate Dispatch ASAP)</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="bookUrgency">Urgency Level <span class="required">*</span></label>
                  <select id="bookUrgency" name="urgency" class="form-control" required>
                    <option value="standard" selected>Standard Schedule (Next 24-48 Hours)</option>
                    <option value="urgent_4h">Urgent Dispatch (Within 4 Hours)</option>
                    <option value="emergency_1h">🚨 Critical Emergency (Within 45-60 Mins)</option>
                    <option value="scheduled">Scheduled Routine Inspection</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="bookEngagementModel">Engagement Model <span class="required">*</span></label>
                  <select id="bookEngagementModel" name="bookEngagementModel" class="form-control" required>
                    <option value="One-Time" selected>One-Time Service</option>
                    <option value="Daily">Daily Staffing</option>
                    <option value="Weekly">Weekly Preventive</option>
                    <option value="Monthly">Monthly AMC</option>
                    <option value="Yearly">Annual Enterprise AMC</option>
                  </select>
                </div>
              </div>

              <!-- AMC Plan / Package Selector (Positioned directly below Scheduled Inspection & Engagement Model) -->
              <div id="amcPlanSelectionBox" style="display:{{ $selectedPlan ? 'block' : 'none' }}; background:#f0fdfa; border:1.5px solid #99f6e4; border-radius:12px; padding:16px 18px; margin-top:1.25rem;">
                <div style="margin-bottom:14px;">
                  <strong style="color:#0f2744; font-size:0.95rem; font-weight:700;">
                    Select Maintenance Plan
                  </strong>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:14px; align-items:stretch;">
                  @foreach($amcPlans as $plan)
                    @php
                      $isPlanChecked = (old('amc_plan_id') == $plan->id || (isset($selectedPlan) && ($selectedPlan == $plan->id || $selectedPlan == $plan->slug)) || ($loop->first && !old('amc_plan_id') && !$selectedPlan));
                      $featuresList = is_array($plan->features) ? $plan->features : [];
                    @endphp
                    <label id="amcPlanCard_{{ $plan->id }}" class="amc-plan-card" style="display:flex; flex-direction:column; justify-content:space-between; height:100%; box-sizing:border-box; cursor:pointer; border:2px solid {{ $isPlanChecked ? '#00a896' : '#cbd5e1' }}; border-radius:10px; padding:16px; background:{{ $isPlanChecked ? '#f0fdfa' : '#ffffff' }}; box-shadow:{{ $isPlanChecked ? '0 4px 12px rgba(0, 168, 150, 0.12)' : 'none' }}; transition:all 0.2s ease;" onclick="highlightSelectedAmcPlan(this)">
                      <div style="flex:1; display:flex; flex-direction:column;">
                        <!-- Title & Custom Radio (No black dot) -->
                        <div style="display:flex; align-items:center; gap:8px;">
                          <div class="custom-radio-wrap" style="position:relative; width:16px; height:16px; flex-shrink:0;">
                            <input type="radio" name="amc_plan_id" value="{{ $plan->id }}" {{ $isPlanChecked ? 'checked' : '' }} style="position:absolute; opacity:0; width:0; height:0; pointer-events:none;">
                            <span class="custom-radio-circle" style="width:16px; height:16px; border-radius:50%; border:2px solid {{ $isPlanChecked ? '#00a896' : '#cbd5e1' }}; display:flex; align-items:center; justify-content:center; background:{{ $isPlanChecked ? '#00a896' : '#ffffff' }}; transition:all 0.2s ease; box-sizing:border-box;">
                              <span class="custom-radio-dot" style="width:6px; height:6px; border-radius:50%; background:#ffffff; display:{{ $isPlanChecked ? 'block' : 'none' }};"></span>
                            </span>
                          </div>
                          <div style="flex:1; min-width:0; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                            <span style="font-weight:700; font-size:0.95rem; color:#0f2744; line-height:1.3; word-break:break-word;">
                              {{ $plan->name }}
                            </span>
                            @if($plan->is_popular)
                              <span style="color:#f59e0b; font-size:0.95rem; line-height:1;" title="Featured">★</span>
                            @endif
                          </div>
                        </div>

                        <!-- Target Audience from CMS -->
                        @if(!empty($plan->target_audience))
                          <div style="font-size:0.78rem; color:#64748b; margin-top:8px; line-height:1.4;">
                            {{ $plan->target_audience }}
                          </div>
                        @endif

                        <!-- Top 3 Features from CMS with Tick Marks -->
                        @if(!empty($featuresList))
                          <div style="margin-top:10px; display:flex; flex-direction:column; gap:6px; flex:1;">
                            @foreach(array_slice($featuresList, 0, 3) as $feature)
                              <div style="display:flex; align-items:flex-start; gap:7px; font-size:0.75rem; color:#334155; line-height:1.35;">
                                <span style="display:inline-flex; align-items:center; justify-content:center; width:14px; height:14px; border-radius:50%; background:#dcfce7; color:#15803d; font-size:0.65rem; font-weight:800; flex-shrink:0; margin-top:1px;">✓</span>
                                <span>{{ $feature }}</span>
                              </div>
                            @endforeach
                          </div>
                        @endif

                        <!-- Contract Period from CMS (Only if set in CMS) -->
                        @if(!empty($plan->price_period))
                          <div style="font-size:0.78rem; font-weight:700; color:#0f766e; margin-top:12px;">
                            {{ $plan->price_period }}
                          </div>
                        @endif
                      </div>

                      <!-- Bottom Details Button Row -->
                      <div style="margin-top:12px; padding-top:8px; border-top:1px solid #f1f5f9; display:flex; justify-content:flex-end; align-items:center;">
                        <button type="button" 
                                onclick="event.stopPropagation(); openAmcPlanDetailsModal({{ $plan->id }})" 
                                style="background:#f0fdfa; border:1px solid #99f6e4; color:#0f766e; font-size:0.75rem; font-weight:700; padding:4px 12px; border-radius:6px; cursor:pointer; display:inline-flex; align-items:center; gap:4px; transition:all 0.15s ease;"
                                onmouseenter="this.style.background='#00a896'; this.style.color='#ffffff';"
                                onmouseleave="this.style.background='#f0fdfa'; this.style.color='#0f766e';">
                          <span>Details ↗</span>
                        </button>
                      </div>
                    </label>
                  @endforeach
                </div>

                <!-- Select Contract Tenure (AMC Options) -->
                <div style="margin-top:20px; padding-top:16px; border-top:1px dashed #99f6e4;">
                  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
                    <div>
                      <strong style="color:#0f2744; font-size:0.95rem; font-weight:700; display:flex; align-items:center; gap:6px;">
                        <span>Select Contract Tenure</span>
                        <span style="color:#ef4444;">*</span>
                      </strong>
                      <span style="font-size:0.75rem; color:#64748b; display:block; margin-top:2px;">
                        Choose your preferred commitment period (renewals with guaranteed SLA terms)
                      </span>
                    </div>
                    <div id="tenureDurationBadge" style="font-size:0.78rem; font-weight:700; color:#0f766e; background:#ffffff; padding:4px 10px; border-radius:6px; border:1px solid #99f6e4;">
                      Contract Duration: <span id="tenureDurationText">1 Year</span>
                    </div>
                  </div>

                  @php
                    $tenureOptions = [
                      ['value' => '3 Months', 'label' => '3 Months', 'sub' => 'Quarterly Trial', 'popular' => false],
                      ['value' => '6 Months', 'label' => '6 Months', 'sub' => 'Half-Yearly Scope', 'popular' => false],
                      ['value' => '1 Year', 'label' => '1 Year', 'sub' => 'Annual Agreement', 'popular' => true],
                      ['value' => '2 Years', 'label' => '2 Years', 'sub' => 'Multi-Year Corporate', 'popular' => false],
                      ['value' => '3 Years', 'label' => '3 Years', 'sub' => 'Long-Term Enterprise', 'popular' => false],
                    ];
                    $selectedTenure = old('contract_tenure', '1 Year');
                  @endphp

                  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:10px;">
                    @foreach($tenureOptions as $opt)
                      @php
                        $isTenureActive = ($selectedTenure === $opt['value']);
                      @endphp
                      <label id="tenureCard_{{ Str::slug($opt['value']) }}" class="contract-tenure-card" style="display:flex; flex-direction:column; justify-content:space-between; cursor:pointer; border:2px solid {{ $isTenureActive ? '#00a896' : '#cbd5e1' }}; border-radius:8px; padding:10px 12px; background:{{ $isTenureActive ? '#f0fdfa' : '#ffffff' }}; box-shadow:{{ $isTenureActive ? '0 2px 8px rgba(0, 168, 150, 0.12)' : 'none' }}; transition:all 0.2s ease;" onclick="highlightSelectedTenure(this, '{{ $opt['value'] }}')">
                        <div style="display:flex; align-items:flex-start; gap:8px;">
                          <div class="tenure-radio-wrap" style="position:relative; width:16px; height:16px; flex-shrink:0; margin-top:2px;">
                            <input type="radio" name="contract_tenure" value="{{ $opt['value'] }}" {{ $isTenureActive ? 'checked' : '' }} style="position:absolute; opacity:0; width:0; height:0; pointer-events:none;">
                            <span class="tenure-radio-circle" style="width:16px; height:16px; border-radius:50%; border:2px solid {{ $isTenureActive ? '#00a896' : '#cbd5e1' }}; display:flex; align-items:center; justify-content:center; background:{{ $isTenureActive ? '#00a896' : '#ffffff' }}; transition:all 0.2s ease; box-sizing:border-box;">
                              <span class="tenure-radio-dot" style="width:6px; height:6px; border-radius:50%; background:#ffffff; display:{{ $isTenureActive ? 'block' : 'none' }};"></span>
                            </span>
                          </div>
                          <div style="flex:1; min-width:0;">
                            <div style="font-weight:700; font-size:0.9rem; color:#0f2744; display:flex; align-items:center; gap:4px; flex-wrap:wrap; line-height:1.2;">
                              <span>{{ $opt['label'] }}</span>
                              @if($opt['popular'])
                                <span style="font-size:0.62rem; background:#fef3c7; color:#b45309; padding:1px 5px; border-radius:4px; font-weight:700;">★ Popular</span>
                              @endif
                            </div>
                            <div style="font-size:0.72rem; color:#64748b; margin-top:3px; line-height:1.25;">
                              {{ $opt['sub'] }}
                            </div>
                          </div>
                        </div>
                      </label>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <!-- Step 3: Location Details (Bangladesh) -->
            <div class="form-step-section">
              <div class="step-header-title">
                <span class="step-num-pill">3</span>
                <h3>Location Details (Bangladesh)</h3>
              </div>

              <div class="form-row form-row-3col" style="grid-template-columns: 1fr 1fr 1fr;">
                <div class="form-group">
                  <label for="bookDivision">Division <span class="required">*</span></label>
                  <select id="bookDivision" name="division" class="form-control" required>
                    <!-- Populated dynamically via BD_LOCATIONS in booking.js -->
                  </select>
                </div>

                <div class="form-group">
                  <label for="bookDistrict">District <span class="required">*</span></label>
                  <select id="bookDistrict" name="district" class="form-control" disabled required>
                    <option value="">Select Division First...</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="bookArea">Thana / Area <span class="required">*</span></label>
                  <select id="bookArea" name="area" class="form-control" disabled required>
                    <option value="">Select District First...</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="bookAddress">Full Address (Road, House, Flat/Floor) <span class="required">*</span></label>
                <input type="text" id="bookAddress" name="street_address" class="form-control" 
                  value="{{ old('street_address', auth()->user()?->default_address ?? '') }}" placeholder="e.g. House #14, Road #05, Block B, Floor 4th" required>
              </div>
            </div>

            <!-- Step 4: Problem Description & Photo Upload -->
            <div class="form-step-section">
              <div class="step-header-title">
                <span class="step-num-pill">4</span>
                <h3>Problem Description & Photos</h3>
              </div>

              <div class="form-group">
                <label for="bookProblemDesc">Describe the Issue or Service Scope <span class="required">*</span></label>
                <textarea id="bookProblemDesc" name="problem_description" class="form-control" rows="3" 
                  placeholder="Please describe the issue in detail (e.g., 'Water leaking behind wall in master bathroom' or 'Central chiller AC making vibration noise in 3rd floor server room')." required>{{ old('problem_description') }}</textarea>
              </div>

              <div class="form-group">
                <label>Upload Problem Photo (Optional) - <span style="font-weight:400; color:var(--text-muted);">Multiple photos supported</span></label>
                <div class="file-upload-zone" id="problemPhotoDropzone" onclick="document.getElementById('problemPhotoInput').click()" style="cursor:pointer;">
                  <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                  <div style="font-weight:600; color:var(--primary);">Click or Drag & Drop photos here</div>
                  <div style="font-size:0.775rem; color:var(--text-muted); margin-top:0.25rem;">Supports multiple PNG, JPG photos up to 10MB each</div>
                  <input type="file" id="problemPhotoInput" name="problem_photos[]" accept="image/*" multiple style="display:none;" onclick="event.stopPropagation()">
                </div>
                <!-- Multiple Photos Preview Grid -->
                <div id="photoPreviewContainer" style="display:none; margin-top:12px; grid-template-columns:repeat(auto-fill, minmax(90px, 1fr)); gap:10px;"></div>
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-accent btn-lg btn-block" style="padding:1.1rem; width:100%;">
              Submit Service Request
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>

            <p style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin-top:0.85rem;">
              🔒 Your contact information is protected. A FIX Operations Coordinator will call to confirm schedule.
            </p>
          </form>
        </div>

        <!-- Booking Summary Sidebar -->
        <aside>
          <div class="booking-sidebar-card">
            <h3 style="font-size:1.25rem; margin-bottom:1rem;">Booking Summary</h3>

            <!-- Dynamic Service Thumbnail / Header Photo -->
            <div id="sidebarServiceImageWrapper" style="display:none; margin-bottom:1rem; border-radius:10px; overflow:hidden; border:1px solid var(--border); max-height:160px; background:#f1f5f9; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
              <img id="sidebarServiceImage" src="" alt="Service preview" style="width:100%; height:160px; object-fit:cover; display:block;">
            </div>

            <div class="sidebar-service-preview">
              <div class="sidebar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
              </div>
              <div>
                <span id="sidebarCategoryName" style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--accent);">Service</span>
                <h4 id="sidebarServiceName" style="font-size:1.05rem; margin:0.15rem 0;">General Service</h4>
                <span id="sidebarPropertyType" style="font-size:0.8rem; color:var(--text-muted);">Corporate Office</span>
              </div>
            </div>

            <!-- Service Details / Brief Description -->
            <p id="sidebarServiceDescription" style="display:none; font-size:0.82rem; color:var(--text-muted); line-height:1.45; margin:0.4rem 0 0.85rem; padding:0 2px;"></p>

            <!-- Dynamic Service SLA & Pricing -->
            <div id="sidebarServiceMetaBox" style="display:none; background:rgba(2,132,199,0.06); border:1px solid rgba(2,132,199,0.18); border-radius:8px; padding:8px 12px; margin-bottom:1rem; font-size:0.82rem; justify-content:space-between; align-items:center;">
              <div>📋 Pricing: <strong style="color:var(--accent);">Custom Inspection Quote</strong></div>
              <div>⚡ Coordination: <strong style="color:#0284c7;">Direct Dispatch</strong></div>
            </div>

            <div style="font-size:0.875rem; color:var(--text-main); margin-bottom:1.5rem;">
              <div id="sidebarAmcTenureRow" style="display:{{ $selectedPlan ? 'flex' : 'none' }}; justify-content:space-between; margin-bottom:0.5rem; background:#f0fdfa; border:1px solid #99f6e4; padding:6px 10px; border-radius:6px;">
                <span style="color:#0f766e; font-weight:600;">Contract Tenure:</span>
                <strong id="sidebarAmcTenureValue" style="color:#0f2744;">{{ old('contract_tenure', '1 Year') }}</strong>
              </div>
              <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                <span style="color:var(--text-muted);">Consultation & Dispatch:</span>
                <strong style="color:var(--success);">Free Confirmation Call</strong>
              </div>
              <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                <span style="color:var(--text-muted);">Pricing Model:</span>
                <strong>Transparent Inspection Quote</strong>
              </div>
              <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">Quality Guarantee:</span>
                <strong>100% Certified Techs</strong>
              </div>
            </div>

            <hr style="border:none; border-top:1px solid var(--border); margin-bottom:1.5rem;">

            <h4 style="font-size:0.95rem; margin-bottom:0.75rem;">FIX Customer Assurance:</h4>
            <ul class="sidebar-guarantee-list">
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Verified national ID & trade credentials
              </li>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Standard safety gear & diagnostic tools
              </li>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>
                No hidden costs — upfront quotation
              </li>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Post-service clean up & quality sign-off
              </li>
            </ul>

            <div class="hotline-box">
              <h5>Emergency Coordination Desk</h5>
              <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+8801700000000') }}">{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}</a>
              <p style="font-size:0.75rem; color:#cbd5e1; margin:0.35rem 0 0;">Dedicated 24/7 facility emergency unit</p>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <!-- Booking Confirmation Success Modal (100% Preserved) -->
  <div id="bookingConfirmationModal" class="modal-overlay">
    <div class="modal-card">
      <div class="modal-header">
        <h3 style="color:var(--success); display:flex; align-items:center; gap:0.5rem;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          Request Submitted Successfully
        </h3>
        <button type="button" class="modal-close-btn" aria-label="Close">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body success-card-content">
        <div class="success-icon-large">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        
        <h3 style="font-size:1.6rem; color:var(--primary); margin-bottom:0.5rem;">Thank You, <span class="modal-client-name">Valued Client</span>!</h3>
        <p style="color:var(--text-muted); max-width:480px; margin:0 auto 1.25rem;">
          Your building service request has been registered in our centralized operations queue.
        </p>

        <div class="req-id-box">
          <span style="font-size:0.85rem; color:var(--text-muted); display:block; text-transform:uppercase; letter-spacing:0.05em;">Generated Request Tracking ID:</span>
          <strong class="modal-req-id">SR-1024</strong>
        </div>

        <div style="background:var(--bg-page); border:1px solid var(--border); border-radius:var(--radius-md); padding:1rem 1.25rem; text-align:left; margin:1rem 0; font-size:0.9rem;">
          <div style="margin-bottom:0.35rem;"><strong>Service:</strong> <span class="modal-client-service">Mechanical & Electrical</span></div>
          <div style="margin-bottom:0.35rem;"><strong>Schedule:</strong> <span class="modal-client-date">Today (Morning)</span></div>
          <div><strong>Location:</strong> <span class="modal-client-location">Gulshan, Dhaka</span></div>
        </div>

        <p style="font-size:0.875rem; color:var(--text-main); margin-bottom:1.5rem;">
          📞 Our support coordinator will contact you via phone within <strong>15–30 minutes</strong> to confirm technician arrival, exact diagnostic scope, and transparent pricing.
        </p>

        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
          <a href="{{ route('customer.bookings') }}" class="btn btn-primary">Track in Live Portal →</a>
          <button type="button" class="btn btn-outline modal-close-btn">Book Another Service</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- AMC PACKAGE DETAILS & DELIVERABLES MODAL                                  -->
  <!-- ========================================================================= -->
  <div id="amcPlanDetailsModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target === this) closeAmcPlanDetailsModal()">
    <div style="background:#ffffff; border-radius:14px; max-width:640px; width:100%; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border:1px solid #e2e8f0; animation:modalPopIn 0.2s ease; overflow:hidden;">
      
      <!-- Modal Header -->
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:1.15rem;">🏢</span>
            <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#0f2744;">AMC Package Scope &amp; Deliverables</h3>
          </div>
          <p style="margin:2px 0 0; font-size:0.8rem; color:#64748b;">Review included maintenance scopes, routine shifts, and contract specifications</p>
        </div>
        <button type="button" onclick="closeAmcPlanDetailsModal()" style="background:none; border:none; font-size:1.3rem; color:#94a3b8; cursor:pointer; padding:0 4px; line-height:1;">✕</button>
      </div>

      <!-- Plan Selector Nav Tabs -->
      <div style="display:flex; gap:6px; padding:10px 16px; background:#f1f5f9; border-bottom:1px solid #e2e8f0; overflow-x:auto;">
        @foreach($amcPlans as $p)
          <button type="button" 
                  id="modalPlanTab_{{ $p->id }}" 
                  onclick="selectModalPlan({{ $p->id }})" 
                  class="modal-plan-tab-btn" 
                  style="padding:6px 14px; font-size:0.82rem; font-weight:700; border-radius:8px; border:none; background:transparent; color:#64748b; cursor:pointer; white-space:nowrap; transition:all 0.2s ease;">
            {{ $p->name }}
          </button>
        @endforeach
      </div>

      <!-- Modal Body (Scrollable) -->
      <div style="padding:20px; overflow-y:auto; flex:1;">
        <!-- Dynamic Header info: 100% synchronized with CMS fields -->
        <div style="background:#f0fdfa; border:1px solid #99f6e4; border-radius:10px; padding:14px 16px; margin-bottom:16px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
            <div style="flex:1; min-width:220px;">
              <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <h4 id="modalPlanName" style="margin:0; font-size:1.18rem; font-weight:800; color:#0f2744;"></h4>
                <span id="modalPlanBadge" style="font-size:0.7rem; font-weight:800; background:#e6f8f5; color:#0f766e; border:1px solid #99f6e4; padding:2px 8px; border-radius:6px; display:none;">★ Featured</span>
              </div>
              <div style="margin-top:6px; font-size:0.82rem; color:#475569; line-height:1.45;">
                <span style="font-weight:600; color:#64748b;">Target Audience:</span>
                <span id="modalPlanTarget"></span>
              </div>
            </div>

            <!-- Contract Period Box from CMS -->
            <div id="modalPlanPeriodBox" style="text-align:right; background:#ffffff; border:1px solid #99f6e4; padding:6px 12px; border-radius:8px; min-width:130px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
              <div style="font-size:0.68rem; text-transform:uppercase; font-weight:700; color:#64748b; letter-spacing:0.04em;">Contract Period</div>
              <div id="modalPlanPeriod" style="font-size:0.88rem; font-weight:800; color:#0f766e; margin-top:2px;"></div>
            </div>
          </div>
        </div>

        <!-- Scope of Deliverables List -->
        <h5 style="margin:0 0 10px; font-size:0.85rem; font-weight:800; color:#0f2744; text-transform:uppercase; letter-spacing:0.04em; display:flex; justify-content:space-between; align-items:center;">
          <span>Included Deliverables &amp; Engineering Audits</span>
          <span id="modalPlanFeaturesCount" style="font-size:0.75rem; font-weight:600; color:#64748b; text-transform:none;"></span>
        </h5>
        <div id="modalPlanFeaturesList" style="display:flex; flex-direction:column; gap:8px;">
          <!-- Populated dynamically via JS -->
        </div>

        <div style="margin-top:16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; font-size:0.8rem; color:#475569; display:flex; align-items:center; gap:10px;">
          <span style="font-size:1.2rem;">🛡️</span>
          <div>
            <strong>Operations Guarantee:</strong> All scheduled routine shifts are handled by certified, background-verified engineers with full compliance sign-offs and digital work receipts.
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding:12px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <span style="font-size:0.78rem; color:#64748b;">
          Transparent pricing confirmed upon site verification.
        </span>
        <div style="display:flex; gap:10px;">
          <button type="button" onclick="closeAmcPlanDetailsModal()" class="btn btn-outline btn-sm" style="font-size:0.82rem; font-weight:600; padding:6px 14px; border:1px solid #cbd5e1; background:#ffffff; cursor:pointer;">
            Close
          </button>
          <button type="button" id="modalSelectPlanBtn" onclick="selectAndApplyPlan()" class="btn btn-accent btn-sm" style="font-size:0.82rem; font-weight:700; padding:6px 18px; border-radius:6px; cursor:pointer; background:#00a896; color:#ffffff; border:none; box-shadow:0 2px 6px rgba(0, 168, 150, 0.25);">
            Select This Package ✓
          </button>
        </div>
      </div>
    </div>
  </div>

  <x-slot name="scripts">
    <script>
      window.BD_LOCATIONS = @json($bdLocations ?? []);
      window.FIX_AUTH_USER = @json(auth()->user());
      window.IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};
      window.FIX_SERVICES_DATA = {
        @foreach($services as $srv)
          "{{ $srv->id }}": {
            id: "{{ $srv->id }}",
            title: @json($srv->title),
            subtitle: @json($srv->subtitle),
            description: @json($srv->description),
            thumbnail_url: @json($srv->thumbnail_url),
            price_starting: @json($srv->price_starting),
            pricing_unit: @json($srv->pricing_unit),
            sla_hours: @json($srv->sla_hours)
          },
        @endforeach
      };
      @if(session('trigger_auth_modal'))
        document.addEventListener('DOMContentLoaded', () => {
          openAuthModal();
        });
      @endif

      function setBookingType(type) {
        const typeInput = document.getElementById('bookServiceType');
        const planBox = document.getElementById('amcPlanSelectionBox');
        const btnOneTime = document.getElementById('btnTypeOneTime');
        const btnAmc = document.getElementById('btnTypeAmc');
        const engagementSelect = document.getElementById('bookEngagementModel');
        const urgencySelect = document.getElementById('bookUrgency');

        if (type === 'amc_contract') {
          if (typeInput) typeInput.value = 'amc_contract';
          if (planBox) planBox.style.display = 'block';
          const tenureRow = document.getElementById('sidebarAmcTenureRow');
          if (tenureRow) tenureRow.style.display = 'flex';
          if (btnAmc) {
            btnAmc.style.background = '#ffffff';
            btnAmc.style.color = '#00a896';
            btnAmc.style.fontWeight = '800';
            btnAmc.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
          }
          if (btnOneTime) {
            btnOneTime.style.background = 'transparent';
            btnOneTime.style.color = '#64748b';
            btnOneTime.style.fontWeight = '600';
            btnOneTime.style.boxShadow = 'none';
          }
          if (engagementSelect && engagementSelect.value === 'One-Time') {
            engagementSelect.value = 'Monthly AMC';
          }
          if (urgencySelect) {
            urgencySelect.value = 'scheduled';
          }
        } else {
          if (typeInput) typeInput.value = 'on_demand';
          if (planBox) planBox.style.display = 'none';
          const tenureRow = document.getElementById('sidebarAmcTenureRow');
          if (tenureRow) tenureRow.style.display = 'none';
          if (btnOneTime) {
            btnOneTime.style.background = '#ffffff';
            btnOneTime.style.color = '#0f2744';
            btnOneTime.style.fontWeight = '800';
            btnOneTime.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
          }
          if (btnAmc) {
            btnAmc.style.background = 'transparent';
            btnAmc.style.color = '#64748b';
            btnAmc.style.fontWeight = '600';
            btnAmc.style.boxShadow = 'none';
          }
          if (engagementSelect && engagementSelect.value !== 'One-Time') {
            engagementSelect.value = 'One-Time';
          }
          if (urgencySelect && urgencySelect.value === 'scheduled') {
            urgencySelect.value = 'standard';
          }
        }
      }

      function highlightSelectedAmcPlan(labelEl) {
        document.querySelectorAll('.amc-plan-card').forEach(el => {
          el.style.borderColor = '#cbd5e1';
          el.style.background = '#ffffff';
          el.style.boxShadow = 'none';
          const circle = el.querySelector('.custom-radio-circle');
          if (circle) {
            circle.style.borderColor = '#cbd5e1';
            circle.style.background = '#ffffff';
          }
          const dot = el.querySelector('.custom-radio-dot');
          if (dot) dot.style.display = 'none';
        });
        if (labelEl) {
          labelEl.style.borderColor = '#00a896';
          labelEl.style.background = '#f0fdfa';
          labelEl.style.boxShadow = '0 4px 12px rgba(0, 168, 150, 0.12)';
          const circle = labelEl.querySelector('.custom-radio-circle');
          if (circle) {
            circle.style.borderColor = '#00a896';
            circle.style.background = '#00a896';
          }
          const dot = labelEl.querySelector('.custom-radio-dot');
          if (dot) dot.style.display = 'block';
          const radio = labelEl.querySelector('input[type="radio"]');
          if (radio) radio.checked = true;
        }
      }

      function highlightSelectedTenure(labelEl, tenureValue) {
        document.querySelectorAll('.contract-tenure-card').forEach(el => {
          el.style.borderColor = '#cbd5e1';
          el.style.background = '#ffffff';
          el.style.boxShadow = 'none';
          const circle = el.querySelector('.tenure-radio-circle');
          if (circle) {
            circle.style.borderColor = '#cbd5e1';
            circle.style.background = '#ffffff';
          }
          const dot = el.querySelector('.tenure-radio-dot');
          if (dot) dot.style.display = 'none';
        });

        if (labelEl) {
          labelEl.style.borderColor = '#00a896';
          labelEl.style.background = '#f0fdfa';
          labelEl.style.boxShadow = '0 2px 8px rgba(0, 168, 150, 0.12)';
          const circle = labelEl.querySelector('.tenure-radio-circle');
          if (circle) {
            circle.style.borderColor = '#00a896';
            circle.style.background = '#00a896';
          }
          const dot = labelEl.querySelector('.tenure-radio-dot');
          if (dot) dot.style.display = 'block';
          const radio = labelEl.querySelector('input[type="radio"]');
          if (radio) radio.checked = true;
        }

        updateContractDurationPreview(tenureValue);
      }

      function updateContractDurationPreview(selectedTenure = null) {
        if (!selectedTenure) {
          const checkedRadio = document.querySelector('input[name="contract_tenure"]:checked');
          selectedTenure = checkedRadio ? checkedRadio.value : '1 Year';
        }

        const dateInput = document.getElementById('bookDate');
        let startDate = dateInput && dateInput.value ? new Date(dateInput.value) : new Date();
        if (isNaN(startDate.getTime())) {
          startDate = new Date();
        }

        let endDate = new Date(startDate);
        if (selectedTenure === '3 Months') {
          endDate.setMonth(endDate.getMonth() + 3);
        } else if (selectedTenure === '6 Months') {
          endDate.setMonth(endDate.getMonth() + 6);
        } else if (selectedTenure === '2 Years') {
          endDate.setFullYear(endDate.getFullYear() + 2);
        } else if (selectedTenure === '3 Years') {
          endDate.setFullYear(endDate.getFullYear() + 3);
        } else {
          endDate.setFullYear(endDate.getFullYear() + 1);
        }

        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        const startStr = startDate.toLocaleDateString('en-US', options);
        const endStr = endDate.toLocaleDateString('en-US', options);

        const badgeText = document.getElementById('tenureDurationText');
        if (badgeText) {
          badgeText.textContent = `${selectedTenure} (${startStr} – ${endStr})`;
        }

        const sidebarTenureVal = document.getElementById('sidebarAmcTenureValue');
        if (sidebarTenureVal) {
          sidebarTenureVal.textContent = selectedTenure;
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.getElementById('bookDate');
        if (dateInput) {
          dateInput.addEventListener('change', () => updateContractDurationPreview());
        }
        updateContractDurationPreview();

        const engagementSelect = document.getElementById('bookEngagementModel');
        const urgencySelect = document.getElementById('bookUrgency');

        if (engagementSelect) {
          engagementSelect.addEventListener('change', function() {
            if (this.value === 'Monthly AMC' || this.value === 'Yearly' || this.value === 'Daily' || this.value === 'Weekly') {
              setBookingType('amc_contract');
            } else if (this.value === 'One-Time') {
              setBookingType('on_demand');
            }
          });
        }

        if (urgencySelect) {
          urgencySelect.addEventListener('change', function() {
            if (this.value === 'scheduled') {
              setBookingType('amc_contract');
            }
          });
        }
      });

      window.AMC_PLANS_DATA = @json($amcPlans);
      let currentModalPlanId = null;

      function openAmcPlanDetailsModal(planId = null) {
        if (!window.AMC_PLANS_DATA || window.AMC_PLANS_DATA.length === 0) return;

        if (!planId) {
          const checkedRadio = document.querySelector('input[name="amc_plan_id"]:checked');
          planId = checkedRadio ? parseInt(checkedRadio.value) : window.AMC_PLANS_DATA[0].id;
        }

        selectModalPlan(planId);

        const modal = document.getElementById('amcPlanDetailsModal');
        if (modal) {
          modal.style.display = 'flex';
        }
      }

      function closeAmcPlanDetailsModal() {
        const modal = document.getElementById('amcPlanDetailsModal');
        if (modal) {
          modal.style.display = 'none';
        }
      }

      function selectModalPlan(planId) {
        currentModalPlanId = planId;
        const plan = window.AMC_PLANS_DATA.find(p => p.id === planId) || window.AMC_PLANS_DATA[0];
        if (!plan) return;

        document.querySelectorAll('.modal-plan-tab-btn').forEach(btn => {
          btn.style.background = 'transparent';
          btn.style.color = '#64748b';
          btn.style.boxShadow = 'none';
        });
        const activeTab = document.getElementById('modalPlanTab_' + plan.id);
        if (activeTab) {
          activeTab.style.background = '#00a896';
          activeTab.style.color = '#ffffff';
          activeTab.style.boxShadow = '0 2px 6px rgba(0, 168, 150, 0.25)';
        }

        document.getElementById('modalPlanName').textContent = plan.name;

        const targetEl = document.getElementById('modalPlanTarget');
        if (targetEl) {
          targetEl.textContent = plan.target_audience || 'All Commercial & Residential Facilities';
        }

        const periodBox = document.getElementById('modalPlanPeriodBox');
        if (periodBox) {
          if (plan.price_period) {
            document.getElementById('modalPlanPeriod').textContent = plan.price_period;
            periodBox.style.display = 'block';
          } else {
            periodBox.style.display = 'none';
          }
        }

        const badgeEl = document.getElementById('modalPlanBadge');
        if (badgeEl) {
          if (plan.is_popular || plan.badge) {
            badgeEl.textContent = plan.badge || '★ Featured';
            badgeEl.style.display = 'inline-block';
          } else {
            badgeEl.style.display = 'none';
          }
        }

        const features = Array.isArray(plan.features) ? plan.features : [];
        const countEl = document.getElementById('modalPlanFeaturesCount');
        if (countEl) {
          countEl.textContent = features.length + ' Deliverables';
        }

        const listEl = document.getElementById('modalPlanFeaturesList');
        listEl.innerHTML = '';
        if (features.length === 0) {
          listEl.innerHTML = '<div style="font-size:0.82rem; color:#64748b; padding:8px 0;">Custom preventive maintenance scopes verified during on-site audit.</div>';
        } else {
          features.forEach(f => {
            const item = document.createElement('div');
            item.style.cssText = 'display:flex; align-items:flex-start; gap:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px; font-size:0.83rem; color:#1e293b;';
            item.innerHTML = `
              <span style="display:inline-flex; align-items:center; justify-content:center; width:20px; height:20px; border-radius:50%; background:#dcfce7; color:#15803d; font-size:0.75rem; font-weight:800; flex-shrink:0; margin-top:1px;">✓</span>
              <span style="flex:1; line-height:1.45;">${f}</span>
            `;
            listEl.appendChild(item);
          });
        }
      }

      function selectAndApplyPlan() {
        if (!currentModalPlanId) return;

        const radio = document.querySelector(`input[name="amc_plan_id"][value="${currentModalPlanId}"]`);
        if (radio) {
          radio.checked = true;
          const label = radio.closest('.amc-plan-card');
          if (label) {
            highlightSelectedAmcPlan(label);
          }
        }

        closeAmcPlanDetailsModal();
      }

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          closeAmcPlanDetailsModal();
        }
      });
    </script>
    <script src="{{ asset('assets/js/booking.js') }}"></script>
  </x-slot>
</x-layouts.app>
