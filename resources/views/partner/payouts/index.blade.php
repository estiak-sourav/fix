<x-partner.layouts.app :title="'Earnings & Financial Settlements'">
  <style>
    /* Strict single line rule for all badges and buttons */
    .p-badge, .btn-p, span[style*="border-radius"], [class*="p-badge"], [class*="btn-p"], button {
      white-space: nowrap !important;
      flex-shrink: 0 !important;
      word-break: keep-all !important;
    }
    /* Clean and balanced typography */
    .p-stat-value {
      font-weight: 700 !important;
      font-size: 1.55rem !important;
      letter-spacing: -0.01em;
      white-space: nowrap !important;
    }
    .p-stat-label {
      font-weight: 600 !important;
      letter-spacing: 0.02em;
    }
    .p-card-title {
      font-weight: 600 !important;
    }
    .p-card {
      min-width: 0 !important;
      max-width: 100% !important;
      overflow: hidden !important;
    }
    /* Table responsive scrolling without expanding screen width */
    .p-table-responsive {
      width: 100% !important;
      max-width: 100% !important;
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch !important;
      display: block !important;
    }
    .p-table {
      width: 100% !important;
      min-width: 720px !important;
      border-collapse: collapse !important;
    }
    .p-table th {
      font-weight: 600 !important;
      font-size: 0.74rem !important;
      white-space: nowrap !important;
    }
    .p-table td {
      white-space: nowrap !important;
    }
  </style>

  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div>
        <h1 style="font-size: 1.35rem; font-weight: 700; color: var(--p-primary); letter-spacing: -0.02em; margin: 0;">Earnings &amp; Settlements</h1>
        <p style="margin: 4px 0 0; font-size: 0.8rem; color: var(--p-muted); display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
          <span style="background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 6px; font-weight: 500; font-size: 0.75rem; white-space: nowrap;">
            {{ $partner->earning_model_label }}: {{ $partner->formatted_rate }}
          </span>
        </p>
      </div>

      <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('partner.profile.index') }}#payout" class="btn-p btn-p-outline" style="font-size: 0.8rem; padding: 7px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <span>Payout Accounts</span>
        </a>

        @if($crewOwesAdmin)
          <button type="button" class="btn-p btn-p-primary" style="background: #dc2626; border-color: #dc2626;" onclick="openPayAdminModal({{ $crewDueAmount }})">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
            <span>Pay FIX Dues (৳{{ number_format($crewDueAmount, 0) }})</span>
          </button>
        @endif

        @if($adminOwesCrew)
          <button type="button" class="btn-p btn-p-primary" onclick="openModal('requestPayoutModal')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            <span>Request Payout (৳{{ number_format($pendingDue, 0) }})</span>
          </button>
        @endif

        @if(!$crewOwesAdmin && !$adminOwesCrew)
          <button type="button" class="btn-p btn-p-primary" style="opacity: 0.7; cursor: default; background: #059669; border-color: #059669;" disabled>
            <span>✓ Balance All Settled</span>
          </button>
        @endif
      </div>
    </div>
  </div>

  {{-- Admin Payment Requests Alert (When FIX requested money from crew) --}}
  @if($adminPaymentRequests->isNotEmpty())
    <div style="background: #fff7ed; border: 1.5px solid #fdba74; border-radius: 12px; padding: 14px 18px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 2px 8px rgba(234, 88, 12, 0.08);">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.3rem;">⚠️</span>
        <div>
          <span style="font-size: 0.9rem; font-weight: 600; color: #9a3412; display: block;">
            Payment Settlement Requested by FIX Operations: <span style="font-weight: 700;">৳{{ number_format((float) $adminPaymentRequests->sum('amount'), 2) }}</span>
          </span>
          <span style="font-size: 0.78rem; color: #c2410c; font-weight: 400;">
            FIX Operations has requested clearance for client cash collected on site. Please pay via bKash, Bank, or Cash at FIX Office.
          </span>
        </div>
      </div>
      @php $firstReq = $adminPaymentRequests->first(); @endphp
      <button type="button" class="btn-p btn-p-primary" style="background: #ea580c; border-color: #ea580c; padding: 7px 14px; font-size: 0.8rem;" onclick="openPayAdminModal({{ (float) $firstReq->amount }}, {{ $firstReq->id }})">
        Pay Settlement Request →
      </button>
    </div>
  @endif

  {{-- Pending Settlement Submissions Alert (Crew paid and waiting for admin approval) --}}
  @if($pendingSettlementSubmissions->isNotEmpty())
    <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 1.1rem;">⏳</span>
        <span style="font-size: 0.84rem; font-weight: 400; color: #166534;">
          You submitted {{ $pendingSettlementSubmissions->count() }} payment settlement(s) totaling <span style="font-weight: 600;">৳{{ number_format((float) $pendingSettlementSubmissions->sum('amount'), 2) }}</span>. FIX Admin will verify and clear your dues shortly.
        </span>
      </div>
      <span style="background: #dcfce7; color: #15803d; font-size: 0.72rem; font-weight: 500; padding: 2px 8px; border-radius: 12px;">Verification Pending</span>
    </div>
  @endif

  {{-- Pending Payout Requests Alert (Crew requested withdrawal) --}}
  @if($pendingRequestedAmount > 0)
    <div style="background: #fffbeb; border: 1px solid #fed7aa; border-radius: 10px; padding: 12px 16px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 1.1rem;">⏳</span>
        <span style="font-size: 0.84rem; font-weight: 400; color: #9a3412;">
          You have {{ $pendingPayoutRequests->count() }} withdrawal request(s) totaling <span style="font-weight: 600;">৳{{ number_format($pendingRequestedAmount, 2) }}</span> awaiting admin review &amp; disbursement.
        </span>
      </div>
      <span style="background: #ffedd5; color: #9a3412; font-size: 0.72rem; font-weight: 500; padding: 2px 8px; border-radius: 12px;">In Review</span>
    </div>
  @endif

  <!-- 4 Top Cards Grid -->
  <div class="p-stats-grid">
    <div class="p-card p-stat-card">
      <span class="p-stat-label">Total Net Earned</span>
      <div class="p-stat-value" style="color: var(--p-primary);">৳ {{ number_format($totalEarned, 2) }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">Cash Collected</span>
      <div class="p-stat-value" style="color: #b45309;">৳ {{ number_format($cashCollectedByCrew, 2) }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">Payouts Received</span>
      <div class="p-stat-value" style="color: #15803d;">৳ {{ number_format($totalPaid, 2) }}</div>
      <div style="font-size: 0.72rem; color: var(--p-muted); margin-top: 4px;">Disbursed by Admin to you</div>
    </div>

    <div class="p-card p-stat-card" style="border: 1.5px solid {{ $crewOwesAdmin ? '#fca5a5' : ($adminOwesCrew ? '#86efac' : '#cbd5e1') }}; background: {{ $crewOwesAdmin ? '#fef2f2' : ($adminOwesCrew ? '#f0fdf4' : '#ffffff') }};">
      <span class="p-stat-label">
        @if($crewOwesAdmin)
          ⚠️ Payable Due to FIX
        @elseif($adminOwesCrew)
          ✓ Receivable
        @else
          Net Account Position
        @endif
      </span>
      <div class="p-stat-value" style="color: {{ $crewOwesAdmin ? '#dc2626' : ($adminOwesCrew ? '#15803d' : '#334155') }};">
        @if($crewOwesAdmin)
          ৳ {{ number_format($crewDueAmount, 2) }}
        @elseif($adminOwesCrew)
          ৳ {{ number_format($pendingDue, 2) }}
        @else
          ৳ 0.00
        @endif
      </div>
      <div style="font-size: 0.72rem; color: {{ $crewOwesAdmin ? '#b91c1c' : ($adminOwesCrew ? '#15803d' : '#64748b') }}; margin-top: 4px; font-weight: 500;">
        @if($crewOwesAdmin)
          Cash held exceeds earnings share
        @elseif($adminOwesCrew)
          Available for withdrawal
        @else
          Account is completely balanced
        @endif
      </div>
    </div>
  </div>

  <!-- PAYOUT & SETTLEMENT TRANSACTIONS HISTORY TABLE -->
  <div class="p-card" style="margin-bottom: 22px;">
    <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h3 class="p-card-title" style="margin: 0;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          <span>Financial Transactions &amp; Settlements History</span>
        </h3>
      </div>
      <div style="display: flex; gap: 8px;">
        <span style="font-size: 0.72rem; background: #ecfdf5; color: #047857; font-weight: 500; padding: 3px 8px; border-radius: 8px;">
          Payouts Received: ৳ {{ number_format($totalPaid, 2) }}
        </span>
        <span style="font-size: 0.72rem; background: #eff6ff; color: #1e40af; font-weight: 500; padding: 3px 8px; border-radius: 8px;">
          Dues Paid to FIX: ৳ {{ number_format($paidByCrew, 2) }}
        </span>
      </div>
    </div>

    @if($payouts->isEmpty())
      <div style="text-align: center; padding: 32px 16px; color: var(--p-muted);">
        <p style="font-size: 0.88rem; margin: 0;">No financial transactions or settlement records yet.</p>
      </div>
    @else
      <div class="p-table-responsive">
        <table class="p-table">
          <thead>
            <tr>
              <th>Trx Number</th>
              <th>Direction</th>
              <th>Date</th>
              <th style="text-align: right;">Amount</th>
              <th>Method / Account</th>
              <th style="text-align: center;">Status</th>
              <th>Trx ID / Proof</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payouts as $payout)
              <tr>
                <td style="font-weight: 500; font-family: monospace; color: var(--p-primary); font-size: 0.84rem;">
                  #{{ $payout->payout_number }}
                </td>
                <td>
                  @if($payout->direction === 'partner_to_admin')
                    <span style="font-size: 0.72rem; font-weight: 500; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 6px;">
                      📥 You → FIX (Settlement)
                    </span>
                  @else
                    <span style="font-size: 0.72rem; font-weight: 500; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 2px 8px; border-radius: 6px;">
                      📤 FIX → You (Payout)
                    </span>
                  @endif
                </td>
                <td style="font-size: 0.8rem; color: var(--p-muted);">
                  {{ $payout->paid_at ? $payout->paid_at->format('M d, Y') : ($payout->requested_at ? $payout->requested_at->format('M d, Y') : $payout->created_at->format('M d, Y')) }}
                </td>
                <td style="text-align: right; font-weight: 600; color: {{ $payout->direction === 'partner_to_admin' ? '#059669' : '#2563eb' }}; font-size: 0.9rem; white-space: nowrap !important;">
                  ৳&nbsp;{{ number_format((float) $payout->amount, 2) }}
                </td>
                <td style="font-size: 0.82rem;">
                  {{ $payout->payment_method_details ?: ($payout->paymentMethod?->formatted_method ?? ($payout->adminPaymentMethod?->formatted_method ?? 'Direct')) }}
                </td>
                <td style="text-align: center;">
                  @php $badge = $payout->status_badge; @endphp
                  <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; font-size: 0.7rem; font-weight: 500; padding: 3px 8px; border-radius: 12px; display: inline-block;">
                    {{ $badge['label'] }}
                  </span>
                </td>
                <td style="font-size: 0.8rem; color: #475569;">
                  <span style="font-family: monospace;">{{ $payout->transaction_reference ?: '—' }}</span>
                  @if($payout->proof_file)
                    <div style="margin-top: 2px;">
                      <a href="{{ asset('storage/' . $payout->proof_file) }}" target="_blank" style="font-size: 0.72rem; color: #2563eb; text-decoration: underline;">
                        📎 View Receipt
                      </a>
                    </div>
                  @endif
                </td>
                <td style="font-size: 0.78rem; color: var(--p-muted); max-width: 180px;">
                  {{ $payout->partner_notes ?: ($payout->admin_notes ?: '—') }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top: 18px;">
        {{ $payouts->links() }}
      </div>
    @endif
  </div>

  <!-- COMPLETED JOBS EARNING LEDGER -->
  <div class="p-card">
    <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h3 class="p-card-title" style="margin: 0;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>Completed Jobs Contributing to Your Earnings</span>
        </h3>
      </div>
      <span style="font-size: 0.8rem; color: var(--p-muted);">
        Total: {{ $completedJobs->total() }} jobs
      </span>
    </div>

    @if($completedJobs->isEmpty())
      <div style="text-align: center; padding: 32px 16px; color: var(--p-muted);">
        <p style="font-size: 0.88rem; margin: 0;">No completed service jobs yet.</p>
      </div>
    @else
      <div class="p-table-responsive">
        <table class="p-table">
          <thead>
            <tr>
              <th>Job #</th>
              <th>Service</th>
              <th>Completed Date</th>
              <th>Area</th>
              <th style="text-align: right;">Job Price</th>
              <th>Payment By</th>
              <th style="text-align: right;">Your Earning</th>
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
                  <a href="{{ route('partner.jobs.show', $job) }}" style="font-weight: 500; color: var(--p-accent); text-decoration: none;">
                    #{{ $job->booking_number }}
                  </a>
                </td>
                <td>
                  <div style="font-weight: 500; color: var(--p-primary); font-size: 0.84rem;">{{ $job->service?->title ?? $job->amcPlan?->name ?? 'Facility Service' }}</div>
                </td>
                <td style="font-size: 0.8rem; color: var(--p-muted);">{{ $job->completed_at ? $job->completed_at->format('M d, Y') : $job->updated_at->format('M d, Y') }}</td>
                <td style="font-size: 0.8rem;">{{ $job->area }}</td>
                <td style="text-align: right; font-weight: 400; font-size: 0.84rem; white-space: nowrap !important;">৳&nbsp;{{ number_format($jobValue, 2) }}</td>
                <td style="font-size: 0.8rem; white-space: nowrap !important;">
                  @if($job->payment_collected_by === 'crew')
                    <span style="color:#047857; font-weight: 500;">💵 Crew On Site</span>
                  @elseif($job->payment_collected_by === 'admin')
                    <span style="color:#1e40af; font-weight: 500;">🏢 Paid to Admin</span>
                  @else
                    <span style="color:#64748b;">—</span>
                  @endif
                </td>
                <td style="text-align: right; font-weight: 600; color: #15803d; font-size: 0.88rem; white-space: nowrap !important;">৳&nbsp;{{ number_format($partnerShare, 2) }}</td>
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

  <!-- MODAL 1: REQUEST PAYOUT WITHDRAWAL (When FIX owes Crew) -->
  <div id="requestPayoutModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 12px; max-width: 480px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: popIn 0.2s ease;">
      <div style="padding: 18px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">Request Payout</h3>
          <p style="margin: 2px 0 0; font-size: 0.78rem; color: #64748b;">Submit a withdrawal request for any amount from your balance</p>
        </div>
        <button type="button" onclick="closeModal('requestPayoutModal')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94a3b8;">✕</button>
      </div>

      <form action="{{ route('partner.payouts.request') }}" method="POST" style="padding: 20px;">
        @csrf

        <!-- Available Balance Notification -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 0.8rem; font-weight: 500; color: #166534;">Current Payable Balance:</span>
          <span style="font-size: 1.2rem; font-weight: 700; color: #15803d;">৳ {{ number_format($pendingDue, 2) }}</span>
        </div>

        <!-- Quick Fill Balance Chips -->
        <div style="margin-bottom: 14px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
          <span style="font-size: 0.72rem; font-weight: 500; color: #64748b; text-transform: uppercase;">Quick Fill:</span>
          <button type="button" onclick="document.getElementById('payout_amount_input').value = '{{ $pendingDue }}'" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 4px; padding: 3px 8px; font-size: 0.75rem; font-weight: 500; cursor: pointer;">
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
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
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
            style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 1rem; font-weight: 600; color: #0f172a;"
          >
          <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px; display: block;">You can request any custom amount up to your available balance.</span>
        </div>

        <!-- Receiving Payment Method Select -->
        <div style="margin-bottom: 14px;">
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
            Receiving Payment Method <span style="color: #dc2626;">*</span>
          </label>
          @if($paymentMethods->isEmpty())
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px; font-size: 0.8rem; color: #92400e;">
              ⚠️ You have not saved any payment methods yet. Please <a href="{{ route('partner.profile.index') }}#payout" style="color: #b45309; font-weight: 600; text-decoration: underline;">add a payment method in Settings</a> first.
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
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
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

  <!-- MODAL 2: PAY ADMIN / SETTLE DUES (When Crew owes FIX) -->
  <div id="payAdminModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #ffffff; border-radius: 12px; max-width: 520px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: popIn 0.2s ease;">
      <div style="padding: 18px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #fff7ed;">
        <div>
          <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #9a3412;">Pay Dues to FIX Operations</h3>
          <p style="margin: 2px 0 0; font-size: 0.78rem; color: #c2410c;">Clear client cash collections via FIX accounts or office cash</p>
        </div>
        <button type="button" onclick="closeModal('payAdminModal')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #9a3412;">✕</button>
      </div>

      <form action="{{ route('partner.payouts.pay-admin') }}" method="POST" enctype="multipart/form-data" style="padding: 20px;">
        @csrf
        <input type="hidden" name="admin_request_id" id="pay_admin_request_id" value="">

        <!-- Outstanding Dues Banner -->
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 0.8rem; font-weight: 500; color: #991b1b;">Total Outstanding Dues:</span>
          <span style="font-size: 1.2rem; font-weight: 700; color: #dc2626;">৳ {{ number_format($crewDueAmount, 2) }}</span>
        </div>

        <!-- Available Admin Receiving Accounts Info Box -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; font-size: 0.8rem;">
          <span style="font-weight: 600; color: #1e293b; display: block; margin-bottom: 4px;">🏢 FIX Official Payment Methods:</span>
          @foreach($adminPaymentMethods as $apm)
            <div style="padding: 4px 0; border-bottom: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
              <span><span style="font-weight: 600;">{{ strtoupper($apm->method_type) }}:</span> {{ $apm->account_number }} ({{ $apm->account_name }})</span>
              <span style="font-size: 0.7rem; color: #64748b;">{{ $apm->number_type }}</span>
            </div>
          @endforeach
          <div style="padding: 4px 0; color: #047857; font-weight: 500;">
            💵 Cash at Office: FIX HQ Accounts Counter (Level 4, Banani 11, Dhaka)
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
          <div>
            <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
              Payment Method *
            </label>
            <select name="payment_method_type" id="pay_method_type" class="p-input" required onchange="updatePayAdminFields()">
              <option value="bkash">bKash Merchant</option>
              <option value="nagad">Nagad</option>
              <option value="rocket">Rocket</option>
              <option value="bank">Bank Transfer</option>
              <option value="cash">Cash Handover at Office</option>
              <option value="offline">Offline Other</option>
            </select>
          </div>

          <div>
            <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
              Amount to Pay (৳) *
            </label>
            <input 
              type="number" 
              step="0.01" 
              min="1" 
              id="pay_amount_input"
              name="amount" 
              required 
              value="{{ old('amount', $crewDueAmount > 0 ? $crewDueAmount : 500) }}" 
              class="p-input" 
              style="font-size: 1rem; font-weight: 600;"
            >
          </div>
        </div>

        <div style="margin-bottom: 12px;">
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
            Target FIX Account (Optional)
          </label>
          <select name="admin_payment_method_id" class="p-input">
            <option value="">-- Auto Match / General --</option>
            @foreach($adminPaymentMethods as $apm)
              <option value="{{ $apm->id }}" {{ $apm->is_default ? 'selected' : '' }}>
                {{ $apm->formatted_method }}
              </option>
            @endforeach
          </select>
        </div>

        <div style="margin-bottom: 12px;">
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;" id="pay_ref_label">
            Transaction ID / Receipt No. / Cash Note
          </label>
          <input type="text" name="transaction_reference" class="p-input" placeholder="e.g. bKash TrxID or Handed to Mr. X at office">
        </div>

        <div style="margin-bottom: 12px;">
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
            Attach Receipt / Proof Screenshot (Optional)
          </label>
          <input type="file" name="proof_file" accept="image/*,.pdf" class="p-input" style="height: auto; padding: 6px;">
        </div>

        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 0.78rem; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
            Remarks / Note (Optional)
          </label>
          <textarea name="partner_notes" rows="2" class="p-input" style="height: auto; resize: vertical;" placeholder="e.g. Paid from booking cash collections"></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="btn-p btn-p-outline" onclick="closeModal('payAdminModal')">Cancel</button>
          <button type="submit" class="btn-p btn-p-primary" style="background: #ea580c; border-color: #ea580c;">
            Submit Settlement Payment
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

    function openPayAdminModal(amount, requestId = null) {
      if (amount && amount > 0) {
        document.getElementById('pay_amount_input').value = amount;
      }
      if (requestId) {
        document.getElementById('pay_admin_request_id').value = requestId;
      }
      openModal('payAdminModal');
    }

    function updatePayAdminFields() {
      var type = document.getElementById('pay_method_type').value;
      var lbl = document.getElementById('pay_ref_label');
      if (type === 'cash' || type === 'offline') {
        lbl.innerText = 'Office Cash Receipt No. or Received By Note';
      } else if (type === 'bank') {
        lbl.innerText = 'Bank Deposit Slip / Reference Number';
      } else {
        lbl.innerText = 'Mobile Wallet Transaction ID (TrxID) *';
      }
    }
  </script>
</x-partner.layouts.app>
