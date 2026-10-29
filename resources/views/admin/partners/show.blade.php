<x-layouts.admin title="Partner Application: {{ $partner->company_name }} | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.partners.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Partners
        </a>
      </div>
      <h1 class="page-header-title">{{ $partner->company_name }}</h1>
    </div>

    <div>
      @php $badge = $partner->status_badge; @endphp
      <span class="badge-pill-paces {{ $badge['bg'] }}" style="color:{{ $badge['color'] }}; font-size:0.85rem; padding:6px 14px;">
        {{ $badge['label'] }}
      </span>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between; gap:8px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-size:0.875rem; display:flex; align-items:flex-start; justify-content:space-between; gap:8px;">
      <ul style="margin:0; padding-left:18px;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  <div style="display:grid; grid-template-columns:360px 1fr; gap:1.5rem; align-items:start;">
    <!-- LEFT: Applicant Profile (Submitted by Partner on Web) -->
    <div class="card-paces">
      <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
        <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Applicant Profile (Web Submission)</h3>
      </div>

      <div style="margin-bottom:14px;">
        <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Company / Trade Name</div>
        <div style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
          {{ $partner->company_name }}
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Lead Representative</div>
        <div style="font-size:0.92rem; font-weight:500; color:var(--paces-primary); margin-top:2px;">
          👤 {{ $partner->contact_person }}
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Contact Phone</div>
        <div style="font-size:0.92rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
          📞 <a href="tel:{{ $partner->phone }}" style="color:inherit; text-decoration:none;">{{ $partner->phone }}</a>
        </div>
      </div>

      @if($partner->email)
        <div style="margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Official Email</div>
          <div style="font-size:0.85rem; color:var(--paces-text-dark); margin-top:2px;">
            ✉️ <a href="mailto:{{ $partner->email }}" style="color:inherit; text-decoration:none;">{{ $partner->email }}</a>
          </div>
        </div>
      @endif

      <div style="border-top:1px dashed var(--paces-border); padding-top:12px; margin-bottom:14px;">
        <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Trade Domain</div>
        <div style="font-size:0.9rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
          🛠️ {{ $partner->trade_domain }}
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Workforce Strength</div>
        <div style="font-size:0.85rem; font-weight:500; color:var(--paces-primary); margin-top:2px;">
          👷 {{ $partner->technicians_count }} Active Technicians
        </div>
      </div>

      <div style="border-top:1px dashed var(--paces-border); padding-top:12px; margin-bottom:14px;">
        <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Coverage Regions</div>
        <div style="font-size:0.82rem; color:var(--paces-text-dark); margin-top:4px; line-height:1.4;">
          📍 {{ $partner->coverage_areas }}
        </div>
      </div>

      <div style="font-size:0.75rem; color:var(--paces-text-muted); border-top:1px solid var(--paces-border); padding-top:10px;">
        Applied: {{ $partner->created_at->format('M d, Y • h:i A') }}
      </div>
    </div>

    <!-- RIGHT: Administrative Approval & Payout Setup (Admin ONLY) -->
    <div>
      <div class="card-paces">
        <div style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem;">
          <h3 class="card-title-paces" style="margin:0;">Administrative Credentials & Payout Setup</h3>
        </div>

        <form action="{{ route('admin.partners.approve', $partner) }}" method="POST">
          @csrf
          @method('PUT')

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.25rem;">
            <div>
              <label class="form-label-paces">Partner Classification *</label>
              <select name="partner_type" class="form-control-paces" required>
                <option value="individual" {{ old('partner_type', $partner->partner_type) === 'individual' ? 'selected' : '' }}>
                  Individual Lead Technician
                </option>
                <option value="company" {{ old('partner_type', $partner->partner_type) === 'company' ? 'selected' : '' }}>
                  Registered Contractor / Company
                </option>
              </select>
            </div>

            <div>
              <label class="form-label-paces">Disbursement Schedule *</label>
              <select name="payout_terms" class="form-control-paces" required>
                <option value="bi_weekly" {{ old('payout_terms', $partner->payout_terms) === 'bi_weekly' ? 'selected' : '' }}>
                  Bi-Weekly (Standard)
                </option>
                <option value="weekly" {{ old('payout_terms', $partner->payout_terms) === 'weekly' ? 'selected' : '' }}>
                  Weekly
                </option>
                <option value="monthly" {{ old('payout_terms', $partner->payout_terms) === 'monthly' ? 'selected' : '' }}>
                  Monthly
                </option>
                <option value="per_job" {{ old('payout_terms', $partner->payout_terms) === 'per_job' ? 'selected' : '' }}>
                  Per Work Order Completed
                </option>
              </select>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.25rem;">
            <div>
              <label class="form-label-paces">Disbursement Method *</label>
              <select name="payment_method" id="paymentMethodSelect" onchange="togglePaymentInputs(this.value)" class="form-control-paces" required>
                <option value="bank" {{ old('payment_method', $partner->payment_method) === 'bank' ? 'selected' : '' }}>
                  Bank Transfer (BEFTN / NPSB)
                </option>
                <option value="bkash" {{ old('payment_method', $partner->payment_method) === 'bkash' ? 'selected' : '' }}>
                  bKash Merchant / Personal
                </option>
                <option value="nagad" {{ old('payment_method', $partner->payment_method) === 'nagad' ? 'selected' : '' }}>
                  Nagad
                </option>
                <option value="rocket" {{ old('payment_method', $partner->payment_method) === 'rocket' ? 'selected' : '' }}>
                  Rocket
                </option>
              </select>
            </div>

            <div>
              <label class="form-label-paces">Contracted Commission / Rate (%)</label>
              <input 
                type="number" 
                step="0.01" 
                name="commission_rate" 
                value="{{ old('commission_rate', $partner->commission_rate ?: 15.00) }}" 
                class="form-control-paces" 
                placeholder="e.g. 15.00"
              >
            </div>
          </div>

          <!-- Bank Account Details (Shown if Bank) -->
          <div id="bankDetailsContainer" style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; margin-bottom:1.25rem;">
            <div style="font-size:0.82rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:10px;">
              🏦 Official Banking Information
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:10px;">
              <div>
                <label class="form-label-paces" style="font-size:0.78rem;">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $partner->bank_name) }}" class="form-control-paces" placeholder="e.g. City Bank, BRAC Bank, Dutch-Bangla">
              </div>
              <div>
                <label class="form-label-paces" style="font-size:0.78rem;">Account Holder Name</label>
                <input type="text" name="account_holder" value="{{ old('account_holder', $partner->account_holder) }}" class="form-control-paces" placeholder="e.g. Delta HVAC Solutions Ltd">
              </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
              <div>
                <label class="form-label-paces" style="font-size:0.78rem;">Account Number</label>
                <input type="text" name="account_number" value="{{ old('account_number', $partner->account_number) }}" class="form-control-paces" placeholder="e.g. 1502938475001">
              </div>
              <div>
                <label class="form-label-paces" style="font-size:0.78rem;">Branch Name / Routing</label>
                <input type="text" name="branch_name" value="{{ old('branch_name', $partner->branch_name) }}" class="form-control-paces" placeholder="e.g. Gulshan Branch / 225271345">
              </div>
            </div>
          </div>

          <!-- MFS Account Details (Shown if bKash / Nagad / Rocket) -->
          <div id="mfsDetailsContainer" style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; margin-bottom:1.25rem; display:none;">
            <div style="font-size:0.82rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:10px;">
              📱 MFS Wallet Details
            </div>
            <div>
              <label class="form-label-paces" style="font-size:0.78rem;">Wallet / Merchant Number</label>
              <input type="text" name="mfs_number" value="{{ old('mfs_number', $partner->mfs_number) }}" class="form-control-paces" placeholder="e.g. 017xxxxxxxx (bKash/Nagad)">
            </div>
          </div>

          <!-- Internal Admin Notes -->
          <div style="margin-bottom:1.5rem;">
            <label class="form-label-paces">Internal Admin Notes & Contract Verification</label>
            <textarea name="admin_notes" class="form-control-paces" rows="5" placeholder="e.g. Trade license verified, physical inspection scheduled, certified safety gear issued...">{{ old('admin_notes', $partner->admin_notes) }}</textarea>
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            @if(auth('admin')->user()?->hasPermission('users.edit'))
              <button type="submit" class="btn-paces btn-paces-primary" style="padding:10px 24px;">
                {{ $partner->status === 'approved' ? '✓ Update Partner & Payout Details' : '✓ Approve & Enlist in Dispatch Network' }}
              </button>

              @if($partner->status !== 'rejected')
                <form action="{{ route('admin.partners.status', $partner) }}" method="POST" style="margin:0; display:inline;" data-confirm="Reject this contractor application?" data-confirm-title="Reject Partner Application?" data-confirm-btn="Reject Application">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="rejected">
                  <button type="submit" class="btn-paces btn-paces-outline" style="color:#dc2626; border-color:#fca5a5;">
                    ✕ Reject Application
                  </button>
                </form>
              @endif
            @else
              <span style="font-size:0.825rem; color:var(--paces-text-muted);">
                View-only access. Account approval requires edit permission.
              </span>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function togglePaymentInputs(val) {
      const bankDiv = document.getElementById('bankDetailsContainer');
      const mfsDiv = document.getElementById('mfsDetailsContainer');
      if (val === 'bank') {
        bankDiv.style.display = 'block';
        mfsDiv.style.display = 'none';
      } else {
        bankDiv.style.display = 'none';
        mfsDiv.style.display = 'block';
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      const select = document.getElementById('paymentMethodSelect');
      if (select) togglePaymentInputs(select.value);
    });
  </script>
</x-layouts.admin>
