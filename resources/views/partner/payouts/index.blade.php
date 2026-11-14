<x-partner.layouts.app :title="'Earnings & Payouts'">
  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em; margin: 0;">Earnings & Payouts</h1>
      </div>

      <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @if($pendingDue > 0)
          <button type="button" class="btn-p btn-p-primary" onclick="openModal('requestPayoutModal')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            <span>Request Payout (৳{{ number_format($pendingDue, 0) }})</span>
          </button>
        @else
          <button type="button" class="btn-p btn-p-primary" style="opacity: 0.6; cursor: not-allowed;" disabled title="No outstanding balance to withdraw">
            <span>✓ Balance Settled</span>
          </button>
        @endif
      </div>
    </div>
  </div>

  @if($pendingRequestedAmount > 0)
    <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 1.1rem;">⏳</span>
        <span style="font-size: 0.85rem; font-weight: 600; color: #9a3412;">
          You have {{ $pendingPayoutRequests->count() }} withdrawal request(s) totaling <strong>৳{{ number_format($pendingRequestedAmount, 2) }}</strong> awaiting admin review & disbursement.
        </span>
      </div>
      <span style="background: #ffedd5; color: #9a3412; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 12px;">In Review</span>
    </div>
  @endif

  <!-- 4 Top Cards Grid -->
  <div class="p-stats-grid">
    <div class="p-card p-stat-card">
      <span class="p-stat-label">Total Net Earned</span>
      <div class="p-stat-value" style="color: var(--p-primary);">৳ {{ number_format($totalEarned, 2) }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">Disbursed / Paid</span>
      <div class="p-stat-value" style="color: #15803d;">৳ {{ number_format($totalPaid, 2) }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">Payable Balance</span>
      <div class="p-stat-value" style="color: {{ $pendingDue > 0 ? '#dc2626' : '#15803d' }};">৳ {{ number_format($pendingDue, 2) }}</div>
      <div style="font-size: 0.72rem; color: {{ $pendingDue > 0 ? '#b91c1c' : '#15803d' }}; margin-top: 4px;">
        {{ $pendingDue > 0 ? 'Available for payout withdrawal' : 'All earnings fully settled' }}
      </div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">{{ $partner->earning_model_label }}</span>
      <div class="p-stat-value" style="color: var(--p-accent); font-size: 1.25rem;">{{ $partner->formatted_rate }}</div>
      <div style="font-size: 0.72rem; color: var(--p-muted); margin-top: 4px;">
        @if($pendingRequestedAmount > 0)
          <span style="color: #b45309; font-weight: 700;">In Review: ৳{{ number_format($pendingRequestedAmount, 2) }}</span>
        @else
          Agreed Partner Earning Terms
        @endif
      </div>
    </div>
  </div>

  <!-- SAVED PAYMENT METHODS SECTION -->
  <div class="p-card" style="margin-bottom: 22px;">
    <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h3 class="p-card-title" style="margin: 0;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <span>Saved Receiving Payment Methods</span>
        </h3>
      </div>

      <a href="{{ route('partner.profile.index') }}#payout" class="btn-p btn-p-outline" style="font-size: 0.78rem; padding: 6px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <span>⚙️ Manage in Settings</span>
      </a>
    </div>

    @if($paymentMethods->isEmpty())
      <div style="text-align: center; padding: 32px 16px; color: var(--p-muted); background: #f8fafc; border-radius: 8px;">
        <div style="font-size: 2rem; margin-bottom: 8px;">💳</div>
        <div style="font-weight: 600; color: #475569; font-size: 0.92rem;">No Saved Payment Methods</div>
        <p style="font-size: 0.8rem; margin: 4px 0 12px;">Add your bKash, Nagad, Rocket, or Bank account in Settings to receive earned payments.</p>
        <a href="{{ route('partner.profile.index') }}#payout" class="btn-p btn-p-primary btn-p-sm" style="text-decoration: none;">
          Go to Settings to Add Method →
        </a>
      </div>
    @else
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
        @foreach($paymentMethods as $method)
          <div style="background: {{ $method->is_default ? '#f0fdf4' : '#ffffff' }}; border: 1px solid {{ $method->is_default ? '#86efac' : '#e2e8f0' }}; border-radius: 10px; padding: 16px; position: relative; transition: all 0.2s ease;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
              <div>
                <span style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">
                  {{ $method->formatted_method }}
                </span>
                @if($method->is_default)
                  <span style="background: #16a34a; color: #ffffff; font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; margin-left: 6px; display: inline-block;">
                    ★ Primary Default
                  </span>
                @endif
              </div>
            </div>

            <div style="font-size: 1rem; font-family: monospace; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
              {{ $method->account_number }}
            </div>

            @if($method->account_name)
              <div style="font-size: 0.78rem; color: #64748b;">
                A/C Holder: <strong>{{ $method->account_name }}</strong>
              </div>
            @endif

            @if($method->method_type === 'bank' && $method->branch_name)
              <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">
                Branch: {{ $method->branch_name }} {{ $method->routing_number ? '• Routing: '.$method->routing_number : '' }}
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <!-- PAYOUT / WITHDRAWAL REQUESTS HISTORY SECTION -->
  <div class="p-card" style="margin-bottom: 22px;">
    <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h3 class="p-card-title" style="margin: 0;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          <span>Withdrawal & Payout Requests</span>
        </h3>
      </div>

      @if($pendingDue > 0)
        <button type="button" class="btn-p btn-p-primary btn-p-sm" onclick="openModal('requestPayoutModal')">
          + Request Payout
        </button>
      @endif
    </div>

    @if($payouts->isEmpty())
      <div style="text-align: center; padding: 36px 16px; color: var(--p-muted);">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; color: #cbd5e1;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div style="font-weight: 600; font-size: 0.9rem; color: #475569;">No Payout Requests Yet</div>
        <p style="font-size: 0.78rem; margin: 4px 0 0;">When you request a payout from your balance, it will appear here with live review status.</p>
      </div>
    @else
      <div class="p-table-wrapper">
        <table class="p-table">
          <thead>
            <tr>
              <th>Request #</th>
              <th>Date</th>
              <th style="text-align: right;">Amount</th>
              <th>Receiving Method</th>
              <th style="text-align: center;">Status</th>
              <th>Transaction TrxID</th>
              <th>Notes / Remarks</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payouts as $payout)
              <tr>
                <td style="font-weight: 700; font-family: monospace; color: var(--p-primary);">
                  #{{ $payout->payout_number }}
                </td>
                <td style="font-size: 0.8rem; color: var(--p-muted);">
                  {{ $payout->requested_at ? $payout->requested_at->format('M d, Y h:i A') : $payout->created_at->format('M d, Y') }}
                </td>
                <td style="text-align: right; font-weight: 800; font-size: 0.95rem; color: {{ $payout->status === 'paid' ? '#15803d' : ($payout->status === 'pending' ? '#b45309' : '#dc2626') }};">
                  ৳ {{ number_format((float) $payout->amount, 2) }}
                </td>
                <td style="font-size: 0.82rem;">
                  {{ $payout->payment_method_details ?: ($payout->paymentMethod?->formatted_method ?? 'Direct Disbursement') }}
                </td>
                <td style="text-align: center;">
                  @php $badge = $payout->status_badge; @endphp
                  <span class="p-badge" style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; font-size: 0.72rem; font-weight: 700;">
                    {{ $badge['label'] }}
                  </span>
                </td>
                <td style="font-size: 0.78rem; font-family: monospace; color: #475569;">
                  {{ $payout->transaction_reference ?: '—' }}
                </td>
                <td style="font-size: 0.78rem; color: var(--p-muted); max-width: 200px;">
                  @if($payout->admin_notes)
                    <span style="color: #1e293b; font-weight: 500;">Admin:</span> {{ $payout->admin_notes }}
                  @elseif($payout->partner_notes)
                    {{ $payout->partner_notes }}
                  @else
                    —
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top: 14px;">
        {{ $payouts->links() }}
      </div>
    @endif
  </div>

  {{-- Completed Work Orders Table --}}
  <div class="p-card">
    <div class="p-card-header">
      <h3 class="p-card-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        <span>Delivered Jobs Contributing to Balance</span>
      </h3>
    </div>

    @if($completedJobs->isEmpty())
      <div style="text-align: center; padding: 48px 16px; color: var(--p-muted);">
        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; color: #cbd5e1;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div style="font-weight: 600; font-size: 0.95rem; color: #475569;">No Delivered Jobs Yet</div>
      </div>
    @else
      <div class="p-table-wrapper">
        <table class="p-table">
          <thead>
            <tr>
              <th>Order</th>
              <th>Service</th>
              <th>Completed</th>
              <th>Area</th>
              <th style="text-align: right;">Total Bill</th>
              <th style="text-align: right;">Rate / Model</th>
              <th style="text-align: right;">Your Share</th>
            </tr>
          </thead>
          <tbody>
            @foreach($completedJobs as $job)
              @php
                $jobValue = (float) ($job->final_cost ?? $job->estimated_cost ?? 0);
                $partnerShare = $job->partner_payout_amount !== null ? (float) $job->partner_payout_amount : $partner->calculateBookingEarning($job);
              @endphp
              <tr>
                <td>
                  <a href="{{ route('partner.jobs.show', $job) }}" style="font-weight: 700; color: var(--p-accent); text-decoration: none;">
                    #{{ $job->booking_number }}
                  </a>
                </td>
                <td>
                  <div style="font-weight: 600; color: var(--p-primary); font-size: 0.84rem;">{{ $job->service?->title ?? $job->amcPlan?->name ?? 'Facility Service' }}</div>
                </td>
                <td style="font-size: 0.8rem; color: var(--p-muted);">{{ $job->completed_at ? $job->completed_at->format('M d, Y') : $job->updated_at->format('M d, Y') }}</td>
                <td style="font-size: 0.8rem;">{{ $job->area }}</td>
                <td style="text-align: right; font-weight: 600;">৳ {{ number_format($jobValue, 2) }}</td>
                <td style="text-align: right; font-size: 0.8rem; color: var(--p-muted);">
                  @if($partner->earning_model === 'commission')
                    {{ (float) ($partner->payout_rate ?? $partner->commission_rate ?? 15) }}%
                  @elseif($partner->earning_model === 'hourly')
                    ৳ {{ number_format($partner->payout_rate ?? 0) }}/hr ({{ $job->partner_worked_hours ?: 1 }}h)
                  @elseif($partner->earning_model === 'daily')
                    ৳ {{ number_format($partner->payout_rate ?? 0) }}/day
                  @elseif($partner->earning_model === 'monthly')
                    Salary
                  @elseif($partner->earning_model === 'per_job')
                    ৳ {{ number_format($partner->payout_rate ?? 0) }}/job
                  @else
                    {{ $partner->formatted_rate }}
                  @endif
                </td>
                <td style="text-align: right; font-weight: 800; color: #15803d;">৳ {{ number_format($partnerShare, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top: 20px;">
        {{ $completedJobs->links() }}
      </div>
    @endif
  </div>

  <!-- MODAL 1: REQUEST PAYOUT WITHDRAWAL -->
  <div id="requestPayoutModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 12px; max-width: 480px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: popIn 0.2s ease;">
      <div style="padding: 18px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Request Payout</h3>
          <p style="margin: 2px 0 0; font-size: 0.78rem; color: #64748b;">Submit a withdrawal request for any amount from your balance</p>
        </div>
        <button type="button" onclick="closeModal('requestPayoutModal')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94a3b8;">✕</button>
      </div>

      <form action="{{ route('partner.payouts.request') }}" method="POST" style="padding: 20px;">
        @csrf

        <!-- Available Balance Notification -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 0.8rem; font-weight: 700; color: #166534;">Current Payable Balance:</span>
          <span style="font-size: 1.25rem; font-weight: 900; color: #15803d;">৳ {{ number_format($pendingDue, 2) }}</span>
        </div>

        <!-- Quick Fill Balance Chips -->
        <div style="margin-bottom: 14px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
          <span style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Quick Fill:</span>
          <button type="button" onclick="document.getElementById('payout_amount_input').value = '{{ $pendingDue }}'" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
            Full Balance (৳{{ number_format($pendingDue, 2) }})
          </button>
          @foreach([500, 1000, 2000, 5000] as $chip)
            @if($chip < $pendingDue)
              <button type="button" onclick="document.getElementById('payout_amount_input').value = '{{ $chip }}'" style="background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; cursor: pointer;">
                ৳ {{ number_format($chip) }}
              </button>
            @endif
          @endforeach
        </div>

        <!-- Amount input -->
        <div style="margin-bottom: 14px;">
          <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
            Requested Amount (৳) <span style="color: #dc2626;">*</span>
          </label>
          <input 
            type="number" 
            step="0.01" 
            min="1" 
            max="{{ $pendingDue }}" 
            id="payout_amount_input"
            name="amount" 
            required 
            value="{{ old('amount', $pendingDue) }}" 
            style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 1.1rem; font-weight: 800; color: #0f172a;"
          >
          <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px; display: block;">You can request any custom amount up to your available balance.</span>
        </div>

        <!-- Receiving Payment Method Select -->
        <div style="margin-bottom: 14px;">
          <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
            Receiving Payment Method <span style="color: #dc2626;">*</span>
          </label>
          @if($paymentMethods->isEmpty())
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px; font-size: 0.8rem; color: #92400e;">
              ⚠️ You have not saved any payment methods yet. Please <a href="{{ route('partner.profile.index') }}#payout" style="color: #b45309; font-weight: 700; text-decoration: underline;">add a payment method in Settings</a> first.
            </div>
          @else
            <select name="partner_payment_method_id" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem; color: #0f172a;">
              @foreach($paymentMethods as $pm)
                <option value="{{ $pm->id }}" {{ $pm->is_default ? 'selected' : '' }}>
                  {{ $pm->formatted_method }} — {{ $pm->account_number }}{{ $pm->is_default ? ' ★ (Default)' : '' }}
                </option>
              @endforeach
            </select>
          @endif
        </div>

        <!-- Notes -->
        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
            Note for Operations Team (Optional)
          </label>
          <textarea name="partner_notes" rows="2" placeholder="e.g. Please process before tomorrow evening if possible" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.82rem;"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="btn-p btn-p-outline" onclick="closeModal('requestPayoutModal')">Cancel</button>
          <button type="submit" class="btn-p btn-p-primary" {{ $paymentMethods->isEmpty() ? 'disabled' : '' }}>
            Submit Payout Request
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(id) {
      const modal = document.getElementById(id);
      if (modal) modal.style.display = 'flex';
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (modal) modal.style.display = 'none';
    }
  </script>
</x-partner.layouts.app>