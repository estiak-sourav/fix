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
                <div style="background:rgba(30,58,138,0.06); border:1px solid rgba(30,58,138,0.15); border-radius:10px; padding:1rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                  <div style="font-size:0.875rem; color:var(--primary);">
                    <strong>Customer Account Required:</strong> Log in with email OTP to easily track real-time work status.
                  </div>
                  <button type="button" class="btn btn-primary btn-sm" onclick="openAuthModal()">Sign In with OTP</button>
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

              <div class="form-row">
                <div class="form-group">
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

                <div class="form-group">
                  <label for="bookSqft">Estimated Area Size (sq ft)</label>
                  <input type="number" id="bookSqft" name="estimated_sqft" class="form-control" 
                    value="{{ old('estimated_sqft') }}" placeholder="e.g. 2400">
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

  <x-slot name="scripts">
    <script>
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
    </script>
    <script src="{{ asset('assets/js/booking.js') }}"></script>
  </x-slot>
</x-layouts.app>
