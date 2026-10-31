<x-layouts.admin title="Edit Partner: {{ $partner->company_name }} | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="margin-bottom:6px;">
        <a href="{{ route('admin.partners.show', $partner) }}" style="color:var(--paces-primary); font-size:0.85rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
          ← Back to Partner Profile
        </a>
      </div>
      <h1 class="page-header-title">Edit Partner: {{ $partner->company_name }}</h1>
      <p class="page-header-subtitle">Update company information, technical skill domain, workforce capacity, and disbursement setup.</p>
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

  <form action="{{ route('admin.partners.update', $partner) }}" method="POST">
    @csrf
    @method('PUT')

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
            value="{{ old('company_name', $partner->company_name) }}" 
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
            value="{{ old('contact_person', $partner->contact_person) }}" 
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
            value="{{ old('phone', $partner->phone) }}" 
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
            value="{{ old('email', $partner->email) }}" 
            placeholder="e.g. contact@apexengineering.com"
          >
        </div>

        <div>
          <label class="form-label-paces" for="partner_type">Partner Classification <span style="color:#ef4444;">*</span></label>
          <select name="partner_type" id="partner_type" class="form-control-paces" required>
            <option value="company" {{ old('partner_type', $partner->partner_type) === 'company' ? 'selected' : '' }}>Registered Contractor / Agency (Company)</option>
            <option value="individual" {{ old('partner_type', $partner->partner_type) === 'individual' ? 'selected' : '' }}>Individual Lead Technician / Freelance Specialist</option>
          </select>
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
            <option value="1-5" {{ old('technicians_count', $partner->technicians_count) === '1-5' ? 'selected' : '' }}>1 - 5 Certified Technicians</option>
            <option value="6-15" {{ old('technicians_count', $partner->technicians_count) === '6-15' ? 'selected' : '' }}>6 - 15 Field Crew Staff</option>
            <option value="16-50" {{ old('technicians_count', $partner->technicians_count) === '16-50' ? 'selected' : '' }}>16 - 50 Technical Workforce</option>
            <option value="50+" {{ old('technicians_count', $partner->technicians_count) === '50+' ? 'selected' : '' }}>50+ Enterprise Field Personnel</option>
          </select>
        </div>

        <div>
          <label class="form-label-paces" for="status">Network Status <span style="color:#ef4444;">*</span></label>
          <select name="status" id="status" class="form-control-paces" required>
            <option value="approved" {{ old('status', $partner->status) === 'approved' ? 'selected' : '' }} style="color:#16a34a; font-weight:600;">✓ Approved & Active for Dispatch</option>
            <option value="pending" {{ old('status', $partner->status) === 'pending' ? 'selected' : '' }} style="color:#d97706; font-weight:600;">⏳ Pending Document Verification</option>
            <option value="rejected" {{ old('status', $partner->status) === 'rejected' ? 'selected' : '' }} style="color:#dc2626; font-weight:600;">✕ Application Rejected</option>
            <option value="suspended" {{ old('status', $partner->status) === 'suspended' ? 'selected' : '' }} style="color:#991b1b; font-weight:600;">✕ Suspended</option>
          </select>
        </div>
      </div>

      @php
        $tradeDomainOptions = [
          'Electrical & Substation Power',
          'HVAC & Central Air Conditioning',
          'Plumbing & Water Boosting Systems',
          'Fire Safety & NFPA Suppression',
          'Commercial Janitorial & Sanitization',
          'Interior Fit-Out & Renovation',
          'Pest Control & Vector Management',
          'General Facility Maintenance',
        ];

        $coverageAreaOptions = [
          'Dhaka North (Uttara, Gulshan, Banani, Mirpur)',
          'Dhaka South (Dhanmondi, Motijheel, Old Dhaka)',
          'Gazipur & Tongi Industrial Belt',
          'Savar, Ashulia & Dhaka EPZ',
          'Narayanganj & Keraniganj Industrial Area',
          'Chattogram & Regional Industrial Corridor',
          'Cumilla & Mymensingh Economic Zones',
          'Nationwide / Pan-Bangladesh Coverage',
        ];

        $rawOldDomains = old('trade_domain');
        if ($rawOldDomains !== null) {
          $selectedDomains = is_array($rawOldDomains) ? $rawOldDomains : array_map('trim', explode(',', (string) $rawOldDomains));
        } else {
          $selectedDomains = array_map('trim', explode(',', (string) $partner->trade_domain));
        }

        $rawOldAreas = old('coverage_areas');
        if ($rawOldAreas !== null) {
          $selectedAreas = is_array($rawOldAreas) ? $rawOldAreas : array_map('trim', explode(',', (string) $rawOldAreas));
        } else {
          $selectedAreas = array_map('trim', explode(',', (string) $partner->coverage_areas));
        }
      @endphp

      <!-- Primary Technical Domain Checkboxes -->
      <div style="margin-bottom:1.5rem;">
        <label class="form-label-paces" style="margin-bottom:8px; display:block;">
          Primary Technical Domain <span style="color:#ef4444;">*</span>
        </label>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:10px;">
          @foreach($tradeDomainOptions as $domain)
            @php
              $isDomainChecked = in_array($domain, $selectedDomains, true) 
                || Str::contains($partner->trade_domain ?? '', $domain) 
                || Str::contains($partner->trade_domain ?? '', str_replace([' Power', ' Systems'], '', $domain));
            @endphp
            <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1px solid var(--paces-border); border-radius:var(--paces-radius-sm); background:#fff; cursor:pointer; font-size:0.875rem; transition:border-color 0.2s, background 0.2s;">
              <input type="checkbox" name="trade_domain[]" value="{{ $domain }}" {{ $isDomainChecked ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--paces-primary); cursor:pointer;">
              <span style="font-weight:500;">{{ $domain }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- Operating Coverage Areas Checkboxes -->
      <div style="margin-bottom:1rem;">
        <label class="form-label-paces" style="margin-bottom:8px; display:block;">
          Operating Coverage Areas <span style="color:#ef4444;">*</span>
        </label>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:10px; margin-bottom:12px;">
          @foreach($coverageAreaOptions as $area)
            @php
              $areaShort = explode(' (', $area)[0];
              $isAreaChecked = in_array($area, $selectedAreas, true) 
                || Str::contains($partner->coverage_areas ?? '', $area) 
                || Str::contains($partner->coverage_areas ?? '', $areaShort);
            @endphp
            <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1px solid var(--paces-border); border-radius:var(--paces-radius-sm); background:#fff; cursor:pointer; font-size:0.875rem; transition:border-color 0.2s, background 0.2s;">
              <input type="checkbox" name="coverage_areas[]" value="{{ $area }}" {{ $isAreaChecked ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--paces-primary); cursor:pointer;">
              <span style="font-weight:500;">{{ $area }}</span>
            </label>
          @endforeach
        </div>
        <div>
          <label class="form-label-paces" style="font-size:0.82rem; margin-bottom:4px; font-weight:500;">Additional / Specific Locations (Optional):</label>
          <input type="text" name="other_coverage_areas" class="form-control-paces" value="{{ old('other_coverage_areas') }}" placeholder="e.g. Specific Industrial Estate, Tejgaon, Ashulia DEPZ Block C">
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
            <option value="bi_weekly" {{ old('payout_terms', $partner->payout_terms) === 'bi_weekly' ? 'selected' : '' }}>Bi-Weekly (Standard 15-Day Payout)</option>
            <option value="weekly" {{ old('payout_terms', $partner->payout_terms) === 'weekly' ? 'selected' : '' }}>Weekly (Every 7 Days)</option>
            <option value="monthly" {{ old('payout_terms', $partner->payout_terms) === 'monthly' ? 'selected' : '' }}>Monthly (End of Calendar Month)</option>
            <option value="per_job" {{ old('payout_terms', $partner->payout_terms) === 'per_job' ? 'selected' : '' }}>Per Work Order Completed</option>
          </select>
        </div>

        <div>
          <label class="form-label-paces" for="commission_rate">Platform Margin / Commission Rate (%)</label>
          <input 
            type="number" 
            step="0.01" 
            name="commission_rate" 
            id="commission_rate" 
            class="form-control-paces" 
            value="{{ old('commission_rate', $partner->commission_rate ?: '15.00') }}" 
            placeholder="15.00"
          >
        </div>

        <div>
          <label class="form-label-paces" for="paymentMethodSelect">Preferred Disbursement Channel</label>
          <select name="payment_method" id="paymentMethodSelect" onchange="togglePaymentInputs(this.value)" class="form-control-paces">
            <option value="bank" {{ old('payment_method', $partner->payment_method) === 'bank' ? 'selected' : '' }}>Official Bank Account (BEFTN / RTGS)</option>
            <option value="bkash" {{ old('payment_method', $partner->payment_method) === 'bkash' ? 'selected' : '' }}>bKash Merchant / Personal Wallet</option>
            <option value="nagad" {{ old('payment_method', $partner->payment_method) === 'nagad' ? 'selected' : '' }}>Nagad Wallet</option>
            <option value="rocket" {{ old('payment_method', $partner->payment_method) === 'rocket' ? 'selected' : '' }}>Rocket (Dutch-Bangla Bank)</option>
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
            <input type="text" name="bank_name" value="{{ old('bank_name', $partner->bank_name) }}" class="form-control-paces" placeholder="e.g. City Bank, BRAC Bank, Dutch-Bangla Bank">
          </div>
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Account Holder Name</label>
            <input type="text" name="account_holder" value="{{ old('account_holder', $partner->account_holder) }}" class="form-control-paces" placeholder="e.g. Apex Engineering Ltd">
          </div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:12px;">
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Account Number</label>
            <input type="text" name="account_number" value="{{ old('account_number', $partner->account_number) }}" class="form-control-paces" placeholder="e.g. 1502938475001">
          </div>
          <div>
            <label class="form-label-paces" style="font-size:0.8rem;">Branch Name / Routing Number</label>
            <input type="text" name="branch_name" value="{{ old('branch_name', $partner->branch_name) }}" class="form-control-paces" placeholder="e.g. Gulshan Branch / 225271345">
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
          <input type="text" name="mfs_number" value="{{ old('mfs_number', $partner->mfs_number) }}" class="form-control-paces" placeholder="e.g. 017xxxxxxxx">
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
        >{{ old('admin_notes', $partner->admin_notes) }}</textarea>
      </div>
    </div>

    <!-- Submit & Cancel Actions -->
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <a href="{{ route('admin.partners.show', $partner) }}" class="btn-paces btn-paces-outline">
        Cancel
      </a>

      <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>Update Partner Details</span>
      </button>
    </div>
  </form>

  <script>
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

    document.addEventListener('DOMContentLoaded', function () {
      const select = document.getElementById('paymentMethodSelect');
      if (select) {
        togglePaymentInputs(select.value);
      }
    });
  </script>
</x-layouts.admin>

