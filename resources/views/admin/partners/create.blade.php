<x-layouts.admin title="Add Partner / Field Crew | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="margin-bottom:6px;">
        <a href="{{ route('admin.partners.index') }}" style="color:var(--paces-primary); font-size:0.85rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
          ← Back to Partners & Field Crews
        </a>
      </div>
      <h1 class="page-header-title">Enlist New Partner / Field Crew</h1>
    </div>
  </div>

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:14px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-size:0.875rem;">
      <div style="font-weight:600; margin-bottom:6px;">Please fix the following validation errors:</div>
      <ul style="padding-left:20px; margin:0;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.partners.store') }}" method="POST">
    @csrf

    <!-- 1. Partner Identity & Contact Profile -->
    <div class="card-paces" style="margin-bottom:1.5rem;">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
        <h3 class="card-title-paces" style="margin:0; font-size:1.05rem; display:flex; align-items:center; gap:8px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Partner Identity &amp; Contact Details
        </h3>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label class="form-label-paces" for="company_name">Company / Trade Name or Technician Name <span style="color:#ef4444;">*</span></label>
          <input 
            type="text" 
            name="company_name" 
            id="company_name" 
            class="form-control-paces" 
            value="{{ old('company_name') }}" 
            placeholder="e.g. Apex Engineering Ltd or Md. Rafiqul Islam" 
            required
          >
        </div>

        <div>
          <label class="form-label-paces" for="contact_person">Lead Representative / Contact Person <span style="color:#ef4444;">*</span></label>
          <input 
            type="text" 
            name="contact_person" 
            id="contact_person" 
            class="form-control-paces" 
            value="{{ old('contact_person') }}" 
            placeholder="e.g. Engr. Tanvir Ahmed" 
            required
          >
        </div>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.25rem;">
        <div>
          <label class="form-label-paces" for="phone">Contact Phone / Hotline Number <span style="color:#ef4444;">*</span></label>
          <input 
            type="text" 
            name="phone" 
            id="phone" 
            class="form-control-paces" 
            value="{{ old('phone') }}" 
            placeholder="+880 1700-000000" 
            required
          >
        </div>

        <div>
          <label class="form-label-paces" for="email">Official Email Address (Optional)</label>
          <input 
            type="email" 
            name="email" 
            id="email" 
            class="form-control-paces" 
            value="{{ old('email') }}" 
            placeholder="e.g. contact@apexengineering.com"
          >
        </div>

        <div>
          <label class="form-label-paces" for="partner_type">Partner Classification <span style="color:#ef4444;">*</span></label>
          <select name="partner_type" id="partner_type" class="form-control-paces" required>
            <option value="company" {{ old('partner_type') === 'company' ? 'selected' : '' }}>Registered Contractor / Agency (Company)</option>
            <option value="individual" {{ old('partner_type') === 'individual' ? 'selected' : '' }}>Individual Lead Technician / Freelance Specialist</option>
          </select>
        </div>

        <div>
          <label class="form-label-paces" for="password">Partner Portal Initial Password</label>
          <input 
            type="password" 
            name="password" 
            id="password" 
            class="form-control-paces" 
            placeholder="Default: 123456 (if left blank on approval)"
          >
          <span style="font-size:0.75rem; color:var(--paces-text-muted);">Used with phone number to sign in to /partner/login</span>
        </div>
      </div>
    </div>

    <!-- 2. Operational Capacity & Trade Scope -->
    <div class="card-paces" style="margin-bottom:1.5rem;">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
        <h3 class="card-title-paces" style="margin:0; font-size:1.05rem; display:flex; align-items:center; gap:8px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
          Technical Domain &amp; Operational Capacity
        </h3>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem; margin-bottom:1.5rem;">
        <div>
          <label class="form-label-paces" for="technicians_count">Active Field Workforce Count <span style="color:#ef4444;">*</span></label>
          <select name="technicians_count" id="technicians_count" class="form-control-paces" required>
            <option value="1-5" {{ old('technicians_count', '1-5') === '1-5' ? 'selected' : '' }}>1 - 5 Certified Technicians</option>
            <option value="6-15" {{ old('technicians_count') === '6-15' ? 'selected' : '' }}>6 - 15 Field Crew Staff</option>
            <option value="16-50" {{ old('technicians_count') === '16-50' ? 'selected' : '' }}>16 - 50 Technical Workforce</option>
            <option value="50+" {{ old('technicians_count') === '50+' ? 'selected' : '' }}>50+ Enterprise Field Personnel</option>
          </select>
        </div>

        <div>
          <label class="form-label-paces" for="status">Initial Network Status <span style="color:#ef4444;">*</span></label>
          <select name="status" id="status" class="form-control-paces" required>
            <option value="approved" {{ old('status', 'approved') === 'approved' ? 'selected' : '' }} style="color:#16a34a; font-weight:600;">✓ Approved & Active for Dispatch (Recommended)</option>
            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }} style="color:#d97706; font-weight:600;">⏳ Pending Document Verification</option>
            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }} style="color:#dc2626; font-weight:600;">✕ Suspended / Inactive</option>
          </select>
        </div>
      </div>

      @php
        $tradeDomainOptions = isset($activeCategories) && $activeCategories->isNotEmpty()
          ? $activeCategories->pluck('name')->all()
          : [
              'Electrical & Substation Power',
              'HVAC & Central Air Conditioning',
              'Plumbing & Water Boosting Systems',
              'Fire Safety & NFPA Suppression',
              'Commercial Janitorial & Sanitization',
              'Interior Fit-Out & Renovation',
              'General Facility Maintenance',
            ];

        $locationsList = isset($activeDivisions) && $activeDivisions->isNotEmpty()
          ? $activeDivisions
          : \App\Models\Division::whereHas('districts', fn($q) => $q->where('is_active', true))->with(['districts' => fn($q) => $q->where('is_active', true)->with(['thanas' => fn($t) => $t->where('is_active', true)->orderBy('name')])->orderBy('name')])->orderBy('name')->get();

        $rawOldDomains = old('trade_domain', ['Electrical & Substation Power']);
        $selectedDomains = is_array($rawOldDomains) ? $rawOldDomains : array_map('trim', explode(',', (string) $rawOldDomains));

        $rawOldAreas = old('coverage_areas', []);
        $selectedAreas = is_array($rawOldAreas) ? $rawOldAreas : array_map('trim', explode(',', (string) $rawOldAreas));
      @endphp

      <!-- Primary Technical Domain Checkboxes -->
      <div style="margin-bottom:1.5rem;">
        <label class="form-label-paces" style="margin-bottom:8px; display:block;">
          Primary Technical Domain <span style="color:#ef4444;">*</span>
        </label>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:10px;">
          @forelse($tradeDomainOptions as $domain)
            @php
              $isDomainChecked = in_array($domain, $selectedDomains, true) || in_array(str_replace([' Power', ' Systems'], '', $domain), $selectedDomains, true);
            @endphp
            <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1px solid var(--paces-border); border-radius:var(--paces-radius-sm); background:#fff; cursor:pointer; font-size:0.875rem; transition:border-color 0.2s, background 0.2s;">
              <input type="checkbox" name="trade_domain[]" value="{{ $domain }}" {{ $isDomainChecked ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--paces-primary); cursor:pointer;">
              <span style="font-weight:500;">{{ $domain }}</span>
            </label>
          @empty
            <p style="font-size:0.85rem; color:#64748b; margin:0;">No active service categories configured. Please add categories in Services & CMS.</p>
          @endforelse
        </div>
      </div>

      <!-- Operating Coverage Areas Selection -->
      <div style="margin-bottom:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap; gap:8px; margin-bottom:8px;">
          <label class="form-label-paces" style="margin-bottom:0; display:block;">
            Operating Coverage Areas <span style="color:#ef4444;">*</span>
          </label>
          <span id="adminSelectedAreasCountBadge" style="font-size:0.78rem; font-weight:700; color:var(--paces-primary); background:#e6f9f6; border:1px solid #b3ece5; padding:2px 8px; border-radius:9999px;">
            0 Selected
          </span>
        </div>

        <!-- Selected Areas Tag Container (Above filter) -->
        <div style="margin-bottom:14px;">
          <div style="font-size:0.76rem; font-weight:600; color:#64748b; margin-bottom:6px;">All Active Selected Operating Areas:</div>
          <div id="adminSelectedAreasTagContainer" style="display:flex; flex-wrap:wrap; gap:6px; min-height:30px; padding:8px 10px; background:#fff; border:1px dashed #cbd5e1; border-radius:var(--paces-radius-sm);">
            <!-- Dynamically rendered badge pills with × button -->
          </div>
        </div>

        <!-- Division & District Filter Dropdowns -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-bottom:12px; background:#f8fafc; border:1px solid var(--paces-border); border-radius:var(--paces-radius-sm); padding:12px 14px;">
          <div>
            <label class="form-label-paces" style="font-size:0.78rem; color:#64748b; margin-bottom:4px; font-weight:600;">Division Filter</label>
            <select id="adminCoverageDivisionSelect" class="form-control-paces" style="background:#fff; font-weight:600;" onchange="onAdminCoverageDivisionChange(this.value)">
              <option value="">-- Select Division --</option>
              @forelse($locationsList as $div)
                <option value="{{ $div->id }}">{{ $div->name }}</option>
              @empty
                <option value="">No Active Divisions</option>
              @endforelse
            </select>
          </div>

          <div>
            <label class="form-label-paces" style="font-size:0.78rem; color:#64748b; margin-bottom:4px; font-weight:600;">District Filter</label>
            <select id="adminCoverageDistrictSelect" class="form-control-paces" style="background:#fff; font-weight:600;" onchange="onAdminCoverageDistrictChange(this.value)" disabled>
              <option value="">-- Select Division First --</option>
            </select>
          </div>
        </div>

        <!-- Quick Selection Bar (Collapsed until district is selected) -->
        <div id="adminQuickSelectionBar" style="display:none; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:10px; font-size:0.8rem;">
          <span style="color:#64748b; font-weight:500;">Multi-select areas (thanas) for the chosen district:</span>
          <div style="display:flex; gap:8px;">
            <button type="button" class="btn-paces btn-paces-secondary" style="padding:4px 10px; font-size:0.74rem;" onclick="selectAllAdminDistrictAreas()">Select All in District</button>
            <button type="button" class="btn-paces btn-paces-secondary" style="padding:4px 10px; font-size:0.74rem; color:#ef4444; border-color:#fca5a5;" onclick="deselectAllAdminDistrictAreas()">Clear District</button>
          </div>
        </div>

        <!-- Collapsed Notice when not selected -->
        <div id="adminDistrictAreasCollapsedNotice" style="padding:14px 16px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:var(--paces-radius-sm); font-size:0.84rem; color:#64748b; text-align:center; display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:10px;">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
          <span>Select a <strong>Division</strong> and <strong>District</strong> above to filter and pick coverage areas.</span>
        </div>

        <!-- Thanas / Areas Checkbox Groups (Rendered per District) -->
        <div id="adminDistrictAreasWrapper">
          @forelse($locationsList as $div)
            @foreach($div->districts as $district)
              <div class="admin-district-areas-panel" id="adminDistrictAreasPanel_{{ $district->id }}" data-division-id="{{ $div->id }}" data-district-id="{{ $district->id }}" style="display:none;">
                @if($district->thanas->isEmpty())
                  <div style="padding:14px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:var(--paces-radius-sm); font-size:0.85rem; color:#64748b; text-align:center;">
                    No active areas/thanas configured under {{ $district->name }}.
                  </div>
                @else
                  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:8px;">
                    @foreach($district->thanas as $thana)
                      @php
                        $isThanaChecked = in_array($thana->name, $selectedAreas, true)
                          || in_array($thana->name . ' (' . $district->name . ')', $selectedAreas, true);
                      @endphp
                      <label style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:#fff; border:1px solid var(--paces-border); border-radius:var(--paces-radius-sm); cursor:pointer; font-size:0.875rem; transition:border-color 0.2s, background 0.2s;">
                        <input type="checkbox" name="coverage_areas[]" value="{{ $thana->name }}" {{ $isThanaChecked ? 'checked' : '' }} class="admin-area-checkbox-item" data-district-id="{{ $district->id }}" data-area-name="{{ $thana->name }}" onchange="updateAdminSelectedAreasSummary()" style="width:16px; height:16px; accent-color:var(--paces-primary); cursor:pointer;">
                        <span style="font-weight:500;">{{ $thana->name }}</span>
                      </label>
                    @endforeach
                  </div>
                @endif
              </div>
            @endforeach
          @empty
            <div style="padding:14px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:var(--paces-radius-sm); font-size:0.85rem; color:#64748b; text-align:center;">
              No active coverage areas configured in database.
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- 3. Contracting Terms & Payment Disbursement -->
    <div class="card-paces" style="margin-bottom:1.5rem;">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1.25rem;">
        <h3 class="card-title-paces" style="margin:0; font-size:1.05rem; display:flex; align-items:center; gap:8px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          Contracting Terms &amp; Disbursement Setup
        </h3>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label class="form-label-paces" for="payout_terms">Disbursement Schedule <span style="color:#ef4444;">*</span></label>
          <select name="payout_terms" id="payout_terms" class="form-control-paces" required>
            <option value="bi_weekly" {{ old('payout_terms', 'bi_weekly') === 'bi_weekly' ? 'selected' : '' }}>Bi-Weekly (Standard 15-Day Payout)</option>
            <option value="weekly" {{ old('payout_terms') === 'weekly' ? 'selected' : '' }}>Weekly (Every 7 Days)</option>
            <option value="monthly" {{ old('payout_terms') === 'monthly' ? 'selected' : '' }}>Monthly (End of Calendar Month)</option>
            <option value="per_job" {{ old('payout_terms') === 'per_job' ? 'selected' : '' }}>Per Work Order Completed</option>
          </select>
        </div>

        <div>
          <label class="form-label-paces" for="earning_model">Earning Model <span style="color:#ef4444;">*</span></label>
          <select name="earning_model" id="earning_model" class="form-control-paces" onchange="updateEarningRateUI(this.value)">
            <option value="commission" {{ old('earning_model', 'commission') === 'commission' ? 'selected' : '' }}>Commission Based (% of Job Total)</option>
            <option value="hourly" {{ old('earning_model') === 'hourly' ? 'selected' : '' }}>Hourly Rate (Fixed ৳ per hour)</option>
            <option value="daily" {{ old('earning_model') === 'daily' ? 'selected' : '' }}>Daily Rate (Fixed ৳ per day)</option>
            <option value="monthly" {{ old('earning_model') === 'monthly' ? 'selected' : '' }}>Monthly Salary / Retainer (Fixed ৳ per month)</option>
            <option value="per_job" {{ old('earning_model') === 'per_job' ? 'selected' : '' }}>Fixed Per Job (Flat ৳ per work order)</option>
          </select>
        </div>

        <div>
          <label class="form-label-paces" for="payout_rate" id="payoutRateLabel">Rate Value (%)</label>
          <input 
            type="number" 
            step="0.01" 
            name="payout_rate" 
            id="payout_rate" 
            class="form-control-paces" 
            value="{{ old('payout_rate', old('commission_rate', '15.00')) }}" 
            placeholder="15.00"
          >
          <input type="hidden" name="commission_rate" id="hidden_commission_rate" value="{{ old('commission_rate', '15.00') }}">
        </div>

        <div>
          <label class="form-label-paces" for="paymentMethodSelect">Preferred Disbursement Channel</label>
          <select name="payment_method" id="paymentMethodSelect" onchange="togglePaymentInputs(this.value)" class="form-control-paces">
            <option value="bank" {{ old('payment_method', 'bank') === 'bank' ? 'selected' : '' }}>Official Bank Account (BEFTN / RTGS)</option>
            <option value="bkash" {{ old('payment_method') === 'bkash' ? 'selected' : '' }}>bKash Merchant / Personal Wallet</option>
            <option value="nagad" {{ old('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad Wallet</option>
            <option value="rocket" {{ old('payment_method') === 'rocket' ? 'selected' : '' }}>Rocket (Dutch-Bangla Bank)</option>
          </select>
        </div>
      </div>

      <!-- Bank Details Block -->
      <div id="bankDetailsContainer" style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:16px; margin-bottom:1.25rem;">
        <div style="font-size:0.85rem; font-weight:600; color:var(--paces-text-dark); margin-bottom:12px; display:flex; align-items:center; gap:6px;">
          <span>🏦</span> Official Bank Account Information
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:12px; margin-bottom:12px;">
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Bank Name</label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-control-paces" placeholder="e.g. City Bank, BRAC Bank, Dutch-Bangla Bank">
          </div>
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Account Holder Name</label>
            <input type="text" name="account_holder" value="{{ old('account_holder') }}" class="form-control-paces" placeholder="e.g. Apex Engineering Ltd">
          </div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:12px;">
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Account Number</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" class="form-control-paces" placeholder="e.g. 1502938475001">
          </div>
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Branch Name / Routing Number</label>
            <input type="text" name="branch_name" value="{{ old('branch_name') }}" class="form-control-paces" placeholder="e.g. Gulshan Branch / 225271345">
          </div>
        </div>
      </div>

      <!-- MFS Details Block -->
      <div id="mfsDetailsContainer" style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:16px; margin-bottom:1.25rem; display:none;">
        <div style="font-size:0.85rem; font-weight:600; color:var(--paces-text-dark); margin-bottom:10px; display:flex; align-items:center; gap:6px;">
          <span>📱</span> Mobile Financial Services (MFS) Wallet
        </div>
        <div>
          <label class="form-label-paces" style="font-size:0.8rem;">Wallet / Merchant Account Number</label>
          <input type="text" name="mfs_number" value="{{ old('mfs_number') }}" class="form-control-paces" placeholder="e.g. 017xxxxxxxx">
        </div>
      </div>

      <div>
        <label class="form-label-paces" for="admin_notes">Administrative Internal Notes (Optional)</label>
        <textarea 
          name="admin_notes" 
          id="admin_notes" 
          class="form-control-paces" 
          rows="3" 
          placeholder="e.g. Verified trade license & NID on 31 Oct. Certified for 11kV substation and central chillers."
        >{{ old('admin_notes') }}</textarea>
      </div>
    </div>

    <!-- Submit & Cancel Actions -->
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <a href="{{ route('admin.partners.index') }}" class="btn-paces btn-paces-outline">
        Cancel &amp; Return
      </a>

      <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>Enlist &amp; Save Partner</span>
      </button>
    </div>
  </form>

  <script>
    function updateEarningRateUI(model) {
      const label = document.getElementById('payoutRateLabel');
      const input = document.getElementById('payout_rate');
      if (!label || !input) return;

      if (model === 'commission') {
        label.innerText = 'Platform Margin / Commission Rate (%)';
        input.placeholder = '15.00';
      } else if (model === 'hourly') {
        label.innerText = 'Hourly Payout Rate (৳ / hr)';
        input.placeholder = '500';
      } else if (model === 'daily') {
        label.innerText = 'Daily Payout Rate (৳ / day)';
        input.placeholder = '1500';
      } else if (model === 'monthly') {
        label.innerText = 'Monthly Salary / Retainer (৳ / month)';
        input.placeholder = '30000';
      } else if (model === 'per_job') {
        label.innerText = 'Flat Payout Per Completed Job (৳ / job)';
        input.placeholder = '800';
      }
      const hiddenComm = document.getElementById('hidden_commission_rate');
      if (hiddenComm && model === 'commission') {
        hiddenComm.value = input.value;
      }
    }

    function togglePaymentInputs(method) {
      const bankBox = document.getElementById('bankDetailsContainer');
      const mfsBox = document.getElementById('mfsDetailsContainer');

      if (method === 'bank') {
        bankBox.style.display = 'block';
        mfsBox.style.display = 'none';
      } else {
        bankBox.style.display = 'none';
        mfsBox.style.display = 'block';
      }
    }

    // Coverage Areas Filtering & Summary Logic
    const adminLocationsData = @json($locationsList);

    function onAdminCoverageDivisionChange(selectedDivId) {
      const distSelect = document.getElementById('adminCoverageDistrictSelect');
      if (!distSelect) return;
      distSelect.innerHTML = '';

      if (!selectedDivId) {
        distSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = '-- Select Division First --';
        distSelect.appendChild(opt);
        onAdminCoverageDistrictChange('');
        return;
      }

      const division = adminLocationsData.find(d => String(d.id) === String(selectedDivId));
      if (!division || !division.districts || division.districts.length === 0) {
        distSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'No active districts';
        distSelect.appendChild(opt);
        onAdminCoverageDistrictChange('');
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

      onAdminCoverageDistrictChange('');
    }

    function onAdminCoverageDistrictChange(selectedDistId) {
      let hasActivePanel = false;
      document.querySelectorAll('.admin-district-areas-panel').forEach(panel => {
        if (selectedDistId && String(panel.dataset.districtId) === String(selectedDistId)) {
          panel.style.display = 'block';
          hasActivePanel = true;
        } else {
          panel.style.display = 'none';
        }
      });

      const quickBar = document.getElementById('adminQuickSelectionBar');
      if (quickBar) {
        quickBar.style.display = hasActivePanel ? 'flex' : 'none';
      }

      const notice = document.getElementById('adminDistrictAreasCollapsedNotice');
      if (notice) {
        notice.style.display = hasActivePanel ? 'none' : 'flex';
      }
    }

    function selectAllAdminDistrictAreas() {
      const currentDistId = document.getElementById('adminCoverageDistrictSelect')?.value;
      const panel = document.getElementById('adminDistrictAreasPanel_' + currentDistId);
      if (panel) {
        panel.querySelectorAll('.admin-area-checkbox-item').forEach(cb => cb.checked = true);
        updateAdminSelectedAreasSummary();
      }
    }

    function deselectAllAdminDistrictAreas() {
      const currentDistId = document.getElementById('adminCoverageDistrictSelect')?.value;
      const panel = document.getElementById('adminDistrictAreasPanel_' + currentDistId);
      if (panel) {
        panel.querySelectorAll('.admin-area-checkbox-item').forEach(cb => cb.checked = false);
        updateAdminSelectedAreasSummary();
      }
    }

    function updateAdminSelectedAreasSummary() {
      const checkedBoxes = Array.from(document.querySelectorAll('.admin-area-checkbox-item:checked'));
      const countBadge = document.getElementById('adminSelectedAreasCountBadge');
      if (countBadge) {
        countBadge.textContent = checkedBoxes.length + ' Selected';
      }

      const container = document.getElementById('adminSelectedAreasTagContainer');
      if (!container) return;

      if (checkedBoxes.length === 0) {
        container.innerHTML = '<span style="font-size:0.78rem; color:#94a3b8; font-style:italic;">No operating areas selected yet. Please select from the districts above.</span>';
        return;
      }

      container.innerHTML = '';
      checkedBoxes.forEach(cb => {
        const areaName = cb.dataset.areaName || cb.value;
        const pill = document.createElement('span');
        pill.style.cssText = 'display:inline-flex; align-items:center; gap:5px; padding:3px 9px; background:#e6f9f6; border:1px solid #b3ece5; border-radius:9999px; font-size:0.75rem; font-weight:600; color:var(--paces-primary);';
        pill.innerHTML = `<span>${areaName}</span><button type="button" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; line-height:1; font-size:0.85rem;" title="Remove area">&times;</button>`;
        pill.querySelector('button').onclick = function() {
          cb.checked = false;
          updateAdminSelectedAreasSummary();
        };
        container.appendChild(pill);
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      // Keep coverage filter collapsed until user selects division & district
      onAdminCoverageDivisionChange('');
      updateAdminSelectedAreasSummary();

      const select = document.getElementById('paymentMethodSelect');
      if (select) {
        togglePaymentInputs(select.value);
      }
      const modelSelect = document.getElementById('earning_model');
      if (modelSelect) {
        updateEarningRateUI(modelSelect.value);
      }
      const rateInput = document.getElementById('payout_rate');
      if (rateInput) {
        rateInput.addEventListener('input', function() {
          const m = document.getElementById('earning_model')?.value;
          const h = document.getElementById('hidden_commission_rate');
          if (h && m === 'commission') h.value = this.value;
        });
      }
    });
  </script>
</x-layouts.admin>

