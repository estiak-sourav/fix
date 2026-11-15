<x-layouts.admin title="Work Order #{{ $booking->booking_number }} | FIX Admin">
  <style>
    /* Tab Navigation Styles */
    .booking-tabs-nav {
      display: flex;
      gap: 6px;
      border-bottom: 2px solid var(--paces-border);
      margin-bottom: 1.5rem;
      overflow-x: auto;
      white-space: nowrap;
      padding-bottom: 0;
    }
    .booking-tab-btn {
      padding: 10px 18px;
      border: none;
      background: transparent;
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--paces-text-muted);
      cursor: pointer;
      border-bottom: 2px solid transparent;
      margin-bottom: -2px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      border-radius: 6px 6px 0 0;
    }
    .booking-tab-btn:hover {
      color: var(--paces-primary);
      background: #f8fafc;
    }
    .booking-tab-btn.active {
      color: var(--paces-primary);
      border-bottom-color: var(--paces-primary);
      font-weight: 700;
      background: #ffffff;
    }
    .booking-tab-pane {
      display: none;
    }
    .booking-tab-pane.active {
      display: block;
      animation: fadeInPaces 0.2s ease;
    }
    @keyframes fadeInPaces {
      from { opacity: 0; transform: translateY(4px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Visual Lifecycle Progress Tracker */
    .lifecycle-tracker {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 8px;
      margin-bottom: 1.25rem;
      background: #ffffff;
      border: 1px solid var(--paces-border);
      border-radius: 12px;
      padding: 14px 16px;
    }
    @media (max-width: 992px) {
      .lifecycle-tracker {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
      }
    }
    @media (max-width: 576px) {
      .lifecycle-tracker {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    .lifecycle-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative;
      padding: 6px 4px;
      border-radius: 8px;
      transition: all 0.2s ease;
    }
    .lifecycle-step.completed {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
    }
    .lifecycle-step.active {
      background: #eff6ff;
      border: 2px solid #3b82f6;
      box-shadow: 0 4px 12px rgba(59,130,246,0.15);
    }
    .lifecycle-step.upcoming {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      opacity: 0.65;
    }
    .lifecycle-step-num {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      margin-bottom: 4px;
    }
    .lifecycle-step.completed .lifecycle-step-num {
      background: #16a34a;
      color: #ffffff;
    }
    .lifecycle-step.active .lifecycle-step-num {
      background: #3b82f6;
      color: #ffffff;
      animation: pulseStep 1.8s infinite;
    }
    @keyframes pulseStep {
      0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.6); }
      70% { box-shadow: 0 0 0 8px rgba(59, 130, 246, 0); }
      100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .lifecycle-step.upcoming .lifecycle-step-num {
      background: #e2e8f0;
      color: #64748b;
    }
    .lifecycle-step-title {
      font-size: 0.78rem;
      font-weight: 700;
      line-height: 1.2;
    }
    .lifecycle-step.completed .lifecycle-step-title {
      color: #166534;
    }
    .lifecycle-step.active .lifecycle-step-title {
      color: #1e40af;
    }
    .lifecycle-step.upcoming .lifecycle-step-title {
      color: #64748b;
    }
    .lifecycle-step-sub {
      font-size: 0.68rem;
      color: #64748b;
      margin-top: 2px;
    }
  </style>

  <!-- Top Navigation & Order ID Header -->
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:1.25rem;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.bookings.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Bookings List
        </a>
      </div>
      <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <h1 class="page-header-title" style="margin:0; font-family:monospace; font-size:1.35rem; color:var(--paces-primary);">
          #{{ $booking->booking_number }}
        </h1>
        <span style="font-size:0.8rem; color:var(--paces-text-muted); font-weight:400;">
          • Created {{ $booking->created_at->format('M d, Y • h:i A') }}
        </span>
        @if($booking->is_amc || $booking->contract_status === 'active')
          <span class="badge-paces" style="background:#e6f8f5; color:#0f766e; border:1px solid #99f6e4; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:12px; white-space:nowrap !important; flex-shrink:0 !important;">
            • AMC Agreement
          </span>
        @else
          <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:12px; white-space:nowrap !important; flex-shrink:0 !important;">
            • Service
          </span>
        @endif
      </div>
    </div>

    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
      @php
        $badgeClass = match($booking->status) {
          'pending_review' => 'bg-warning-subtle',
          'accepted', 'confirmed' => 'bg-primary-subtle',
          'assigned' => 'bg-info-subtle',
          'on_the_way' => 'bg-warning-subtle',
          'in_progress' => 'bg-info-subtle',
          'completed' => 'bg-success-subtle',
          'cancelled' => 'bg-danger-subtle',
          default => 'bg-primary-subtle'
        };
      @endphp
      <span class="badge-pill-paces {{ $badgeClass }}" style="font-size:0.85rem; font-weight:700; text-transform:uppercase; padding:6px 16px;">
        ● {{ $booking->status_label }}
      </span>

      @if(auth('admin')->user()?->hasPermission('bookings.edit'))
        @if($booking->status === 'cancelled')
          <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#16a34a; border-color:#16a34a; font-weight:700;" onclick="document.getElementById('restartWorkOrderModal').style.display='flex'">
            🔄 Re-start Work Order
          </button>
        @elseif(!$booking->is_amc)
          <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="switchBookingTab('tab-manage-dispatch')">
            ⚙️ Manage & Edit Dispatch
          </button>
        @endif
      @endif
    </div>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:10px 16px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-weight:500; font-size:0.85rem; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#166534; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:10px 16px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-size:0.85rem; display:flex; align-items:flex-start; justify-content:space-between;">
      <div>
        <div style="font-weight:500; margin-bottom:4px;">Please review the following errors:</div>
        <ul style="padding-left:18px; margin:0;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if(!auth('admin')->user()?->hasPermission('bookings.edit'))
    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; font-size:0.85rem; color:#475569; line-height:1.5; margin-bottom:1.25rem;">
      <div style="font-weight:600; color:#1e293b; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
        <span>👁️ Read-Only Access</span>
      </div>
      You have view-only privileges for Work Orders. Status adjustments, team dispatch, and invoice modification require Edit permissions.
    </div>
  @endif

  <!-- ========================================================================= -->
  <!-- AMC CLIENT CANCELLATION REQUEST REVIEW ALERT                              -->
  <!-- ========================================================================= -->
  @if($booking->is_amc && $booking->cancellation_requested && $booking->cancellation_review_status === 'pending')
    <div style="background:#fef2f2; border:2px solid #ef4444; border-radius:12px; padding:16px 20px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; box-shadow:0 4px 12px rgba(239,68,68,0.15);">
      <div style="display:flex; align-items:center; gap:12px;">
        <div style="width:42px; height:42px; border-radius:50%; background:#fee2e2; color:#dc2626; display:flex; align-items:center; justify-content:center; font-size:1.35rem; font-weight:800; flex-shrink:0;">
          🚨
        </div>
        <div>
          <strong style="color:#991b1b; font-size:1.02rem; display:block;">
            Client Requested AMC Deal Cancellation (Operations Review Required)
          </strong>
          <div style="font-size:0.875rem; color:#7f1d1d; margin-top:3px;">
            <strong>Reason Reported by Client:</strong> "{{ $booking->cancellation_request_reason }}"
          </div>
          <span style="font-size:0.75rem; color:#b91c1c; display:block; margin-top:2px;">
            Submitted on {{ $booking->cancellation_requested_at?->format('M d, Y • h:i A') }} ({{ $booking->cancellation_requested_at?->diffForHumans() }})
          </span>
        </div>
      </div>
      <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <form action="{{ route('admin.bookings.amc-cancellation.review', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve cancellation and terminate this AMC contract? All upcoming routine shifts will be cancelled.');" style="margin:0;">
          @csrf
          <input type="hidden" name="action" value="approve">
          <input type="hidden" name="admin_notes" value="Approved by operations lead. Deal terminated.">
          <button type="submit" class="btn-paces" style="background:#dc2626; color:#ffffff; border:none; padding:8px 16px; font-weight:700; border-radius:6px; cursor:pointer;">
            ✓ Approve Cancellation &amp; Terminate Deal
          </button>
        </form>

        <form action="{{ route('admin.bookings.amc-cancellation.review', $booking) }}" method="POST" onsubmit="return confirm('Reject client cancellation request and keep AMC contract active?');" style="margin:0;">
          @csrf
          <input type="hidden" name="action" value="reject">
          <input type="hidden" name="admin_notes" value="Reviewed by operations lead. Discussed with client and contract remains active.">
          <button type="submit" class="btn-paces btn-paces-outline" style="border-color:#cbd5e1; background:#ffffff; color:#334155; padding:8px 14px; font-weight:600; border-radius:6px; cursor:pointer;">
            Reject Request (Keep Active)
          </button>
        </form>
      </div>
    </div>
  @endif

  @if($booking->is_amc && $booking->crew_cancellation_reason)
    <div style="background:#fffbeb; border:2px solid #f59e0b; border-radius:12px; padding:14px 18px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <div style="display:flex; align-items:center; gap:12px;">
        <span style="font-size:1.5rem;">⚠️</span>
        <div>
          <strong style="color:#92400e; font-size:0.95rem; display:block;">
            Assigned Crew Relinquished AMC Contract Assignment
          </strong>
          <span style="font-size:0.82rem; color:#78350f;">
            Reason reported: "{{ $booking->crew_cancellation_reason }}" ({{ $booking->crew_cancellation_requested_at?->diffForHumans() }})
          </span>
        </div>
      </div>
      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="switchBookingTab('tab-amc-contract')">
        Reassign New Crew →
      </button>
    </div>
  @endif

  <!-- ========================================================================= -->
  <!-- 1. HIGHLIGHTED OPERATIONAL AUDIT TIMELINE BANNER                         -->
  <!-- ========================================================================= -->
  <div class="card-paces" style="margin-bottom:1.25rem; background:linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border:2px solid #86efac; border-radius:12px; padding:18px 22px; box-shadow:0 4px 14px rgba(22,163,74,0.08);">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:46px; height:46px; border-radius:10px; background:#16a34a; color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:1.4rem; box-shadow:0 4px 10px rgba(22,163,74,0.3); flex-shrink:0;">
          ⚡
        </div>
        <div>
          <div style="font-size:1.15rem; font-weight:800; color:#0f172a; margin-top:2px;">
            @if($booking->status === 'pending_review')
              Awaiting Discussion with Client & Order Acceptance
            @elseif($booking->status === 'accepted' && !$booking->partner_id)
              Order Accepted — Field Crew / Partner Assignment Required
            @elseif($booking->status === 'accepted' && $booking->partner_id && $booking->partner_job_status === 'assigned')
              Assigned to {{ $booking->partner?->company_name }} — Awaiting Crew Acceptance
            @elseif($booking->status === 'accepted' && $booking->partner_id && $booking->partner_job_status === 'accepted')
              Crew Accepted Job — Preparing Departure to Site
            @elseif($booking->status === 'on_the_way')
              🚗 Crew Is On The Way to Customer Site
            @elseif($booking->status === 'in_progress')
              🔧 Working On Site (Service In Progress)
            @elseif($booking->status === 'completed')
              ✓ Work Order Completed & Delivered
            @elseif($booking->status === 'cancelled')
              ✕ Work Order Cancelled
            @else
              {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            @endif
          </div>

          @php $latestLog = $booking->statusLogs->sortByDesc('id')->first(); @endphp
          @if($latestLog)
            <div style="font-size:0.78rem; color:#64748b; margin-top:3px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
              <span>Updated {{ $latestLog->created_at->diffForHumans() }} by <strong>{{ $latestLog->changedByAdmin?->name ?? ($latestLog->changedByUser?->name ?? 'System') }}</strong></span>
              @if($latestLog->comment)
                <span style="color:#cbd5e1;">•</span>
                <span style="color:#475569;" title="{{ $latestLog->comment }}">{{ Str::limit($latestLog->comment, 40) }}</span>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- 2. VISUAL LIFECYCLE PROGRESS TRACKER                                      -->
  <!-- ========================================================================= -->
  @php
    $stageRank = [
      'pending_review' => 1,
      'accepted' => 2,
      'assigned' => 3,
      'on_the_way' => 4,
      'in_progress' => 5,
      'completed' => 6,
    ];
    $activeRank = $stageRank[$booking->status] ?? 1;
    if ($booking->status === 'accepted' && $booking->partner_id) {
      $activeRank = 3;
    }
  @endphp

  @if($booking->is_amc)
    <!-- Specialized AMC Contract Lifecycle & Operations Ribbon -->
    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:16px 20px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
      <div style="display:flex; align-items:center; gap:14px;">
        <div style="width:44px; height:44px; border-radius:12px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0;">
          🏢
        </div>
        <div>
          <div style="display:flex; align-items:center; gap:8px;">
            <strong style="font-size:1.05rem; color:#0f172a;">{{ $booking->amcPlan?->name ?? 'Annual Maintenance Contract' }}</strong>
            <span class="badge-paces" style="background:#dbeafe; color:#1e40af; font-size:0.75rem; font-weight:700; padding:2px 8px; border-radius:10px;">
              {{ strtoupper($booking->contract_status ?: 'Active') }}
            </span>
            <span class="badge-paces" style="background:#f0fdf4; color:#166534; font-size:0.75rem; font-weight:700; padding:2px 8px; border-radius:10px;">
              {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
            </span>
          </div>
          <div style="font-size:0.8rem; color:#64748b; margin-top:3px;">
            <span>🗓️ Contract Duration: <strong>{{ $booking->contract_start_date ? $booking->contract_start_date->format('M d, Y') : ($booking->preferred_date ? $booking->preferred_date->format('M d, Y') : 'Active') }}</strong> – <strong>{{ $booking->contract_end_date ? $booking->contract_end_date->format('M d, Y') : ($booking->contract_tenure === 'Lifetime' ? 'Lifetime / Ongoing' : '1 Year Period') }}</strong>@if($booking->contract_tenure) ({{ $booking->contract_tenure }})@endif</span>
            <span style="margin:0 6px;">•</span>
            <span>Billing: <strong style="text-transform:capitalize;">{{ str_replace('_', ' ', $booking->billing_cycle ?? 'Monthly') }}</strong></span>
            @if($booking->final_cost > 0)
              <span style="margin:0 6px;">•</span>
              <span>Agreed Fee: <strong style="color:#0f172a;">৳ {{ number_format($booking->final_cost, 2) }}</strong></span>
            @endif
          </div>
        </div>
      </div>

      <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
        <div style="text-align:right;">
          <span style="font-size:0.72rem; color:#64748b; text-transform:uppercase; font-weight:700; display:block;">Routine Shifts</span>
          <strong style="font-size:1rem; color:#16a34a;">
            {{ $booking->amcShifts->where('status', 'completed')->count() }} / {{ $booking->amcShifts->count() }} Completed
          </strong>
        </div>
        <div style="text-align:right; border-left:1px solid #e2e8f0; padding-left:16px;">
          <span style="font-size:0.72rem; color:#64748b; text-transform:uppercase; font-weight:700; display:block;">Assigned Recurring Crew</span>
          <strong style="font-size:0.92rem; color:#1e293b;">
            {{ $booking->partner?->company_name ?? 'No Crew Assigned' }}
          </strong>
        </div>
      </div>
    </div>

    @php
      $amcTotalContract = (float) ($booking->final_cost ?: ($booking->recurring_amount ? $booking->recurring_amount * 12 : ($booking->estimated_cost ?: 0)));
      $amcInvoicePaid = (float) $booking->amcInvoices->where('status', 'paid')->sum('amount');
      $amcShiftCollected = (float) $booking->amcShifts->sum('collected_amount');
      $amcBookingPaid = ($booking->payment_status === 'paid' && $amcInvoicePaid == 0) ? (float) ($booking->collected_amount ?: $booking->final_cost) : 0;
      $amcTotalPaid = $amcInvoicePaid + $amcShiftCollected + $amcBookingPaid;
      $amcRemainingDue = max(0.0, $amcTotalContract - $amcTotalPaid);
      
      $crewCollected = (float) ($booking->amcShifts->where('payment_collected_by', 'crew')->sum('collected_amount') + ($booking->payment_collected_by === 'crew' ? ($booking->collected_amount ?: 0) : 0));
      $officeCollected = max(0.0, $amcTotalPaid - $crewCollected);
    @endphp

    <!-- AMC Financial Balance & Payment Overview -->
    <div class="card-paces" style="margin-bottom:1.5rem; padding:16px 20px; background:#f0fdfa; border:1.5px solid #99f6e4;">
      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:14px;">
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="font-size:1.3rem;">💳</span>
          <div>
            <h4 style="margin:0; font-size:1.02rem; font-weight:800; color:#0f2744;">AMC Contract Balance &amp; Payment Overview</h4>
            <span style="font-size:0.75rem; color:#0f766e;">
              Charge Model: <strong>{{ $booking->amc_billing_type_label }}</strong> 
              @if($booking->payout_per_shift)
                • Crew Payout: <strong>৳ {{ number_format($booking->payout_per_shift, 2) }} / shift</strong>
              @endif
            </span>
          </div>
        </div>

        <div>
          <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#0f766e; border-color:#0f766e; font-weight:700; display:inline-flex; align-items:center; gap:5px;" onclick="openCollectOfficePaymentModal()">
            <span>🏢 + Input Office Payment</span>
          </button>
        </div>
      </div>

      <!-- Financial Metrics Grid -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
        <div style="background:#ffffff; border:1px solid #99f6e4; border-radius:8px; padding:12px 14px;">
          <span style="font-size:0.72rem; color:#64748b; font-weight:700; text-transform:uppercase;">Total Contract Value</span>
          <strong style="display:block; font-size:1.2rem; color:#0f2744; margin-top:2px;">
            ৳ {{ number_format($booking->amc_total_budget, 2) }}
          </strong>
        </div>

        <div style="background:#ffffff; border:1px solid #86efac; border-radius:8px; padding:12px 14px;">
          <span style="font-size:0.72rem; color:#166534; font-weight:700; text-transform:uppercase;">Total Received / Paid</span>
          <strong style="display:block; font-size:1.2rem; color:#16a34a; margin-top:2px;">
            ৳ {{ number_format($booking->amc_total_paid, 2) }}
          </strong>
        </div>

        <div style="background:#ffffff; border:1px solid {{ $booking->amc_remaining_due > 0 ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; padding:12px 14px;">
          <span style="font-size:0.72rem; color:{{ $booking->amc_remaining_due > 0 ? '#991b1b' : '#64748b' }}; font-weight:700; text-transform:uppercase;">Outstanding Due</span>
          <strong style="display:block; font-size:1.2rem; color:{{ $booking->amc_remaining_due > 0 ? '#dc2626' : '#64748b' }}; margin-top:2px;">
            ৳ {{ number_format($booking->amc_remaining_due, 2) }}
          </strong>
        </div>

        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
            <span style="font-size:0.72rem; color:#475569; font-weight:700; text-transform:uppercase;">Payment Channels</span>
            <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="font-size:0.68rem; padding:1px 6px; font-weight:700; color:#0f766e; border-color:#99f6e4;" onclick="openCollectOfficePaymentModal()">
              + Input
            </button>
          </div>
          <div style="font-size:0.78rem; margin-top:4px; line-height:1.55;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span>🏢 Direct to FIX Office:</span>
              <strong style="color:#0f2744; font-size:0.86rem;">৳ {{ number_format($booking->amc_office_collected, 2) }}</strong>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2px;">
              <span>👷 Collected by Crew on Site:</span>
              <strong style="color:#0f766e; font-size:0.86rem;">৳ {{ number_format($booking->amc_crew_collected, 2) }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  @elseif($booking->status !== 'cancelled')
    <div class="lifecycle-tracker">
      <!-- Step 1: Requested -->
      <div class="lifecycle-step {{ $activeRank > 1 ? 'completed' : ($activeRank === 1 ? 'active' : 'upcoming') }}">
        <div class="lifecycle-step-num">{{ $activeRank > 1 ? '✓' : '1' }}</div>
        <div class="lifecycle-step-title">Requested</div>
        <div class="lifecycle-step-sub">{{ $booking->created_at->format('M d, h:i A') }}</div>
      </div>

      <!-- Step 2: Accepted -->
      <div class="lifecycle-step {{ $activeRank > 2 ? 'completed' : ($activeRank === 2 ? 'active' : 'upcoming') }}">
        <div class="lifecycle-step-num">{{ $activeRank > 2 ? '✓' : '2' }}</div>
        <div class="lifecycle-step-title">Accepted</div>
        <div class="lifecycle-step-sub">{{ $booking->estimated_cost ? '৳'.number_format($booking->estimated_cost, 0) : ($booking->is_price_set_later ? 'Price Later' : 'Agreed') }}</div>
      </div>

      <!-- Step 3: Assigned -->
      <div class="lifecycle-step {{ $activeRank > 3 ? 'completed' : ($activeRank === 3 ? 'active' : 'upcoming') }}">
        <div class="lifecycle-step-num">{{ $activeRank > 3 ? '✓' : '3' }}</div>
        <div class="lifecycle-step-title">Assigned</div>
        <div class="lifecycle-step-sub">{{ $booking->partner ? Str::limit($booking->partner->company_name, 12) : ($booking->assigned_team ? Str::limit($booking->assigned_team, 12) : 'Awaiting') }}</div>
      </div>

      <!-- Step 4: On The Way -->
      <div class="lifecycle-step {{ $activeRank > 4 ? 'completed' : ($activeRank === 4 ? 'active' : 'upcoming') }}">
        <div class="lifecycle-step-num">{{ $activeRank > 4 ? '✓' : '4' }}</div>
        <div class="lifecycle-step-title">On The Way</div>
        <div class="lifecycle-step-sub">{{ $booking->status === 'on_the_way' ? 'En Route' : ($activeRank > 4 ? 'Arrived' : 'Transit') }}</div>
      </div>

      <!-- Step 5: Working -->
      <div class="lifecycle-step {{ $activeRank > 5 ? 'completed' : ($activeRank === 5 ? 'active' : 'upcoming') }}">
        <div class="lifecycle-step-num">{{ $activeRank > 5 ? '✓' : '5' }}</div>
        <div class="lifecycle-step-title">Working</div>
        <div class="lifecycle-step-sub">{{ $booking->status === 'in_progress' ? 'In Progress' : ($activeRank > 5 ? 'Done' : 'On-Site') }}</div>
      </div>

      <!-- Step 6: Completed -->
      <div class="lifecycle-step {{ $activeRank === 6 ? 'completed' : 'upcoming' }}">
        <div class="lifecycle-step-num">{{ $activeRank === 6 ? '✓' : '6' }}</div>
        <div class="lifecycle-step-title">Completed</div>
        <div class="lifecycle-step-sub">{{ $booking->final_cost > 0 ? '৳'.number_format($booking->final_cost, 0) : 'Final Bill' }}</div>
      </div>
    </div>
  @else
    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:12px 18px; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <span style="font-size:1.2rem; color:#dc2626;">✕</span>
        <div>
          <strong style="color:#991b1b; font-size:0.9rem;">Work Order Cancelled</strong>
          @if($booking->cancellation_reason)
            <span style="font-size:0.82rem; color:#7f1d1d;">• Reason: {{ $booking->cancellation_reason }}</span>
          @endif
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:10px;">
        <span class="badge-paces" style="background:#fee2e2; color:#b91c1c; font-size:0.75rem; font-weight:700; padding:2px 8px; border-radius:10px; white-space:nowrap !important; flex-shrink:0 !important;">Cancelled</span>
        @if(auth('admin')->user()?->hasPermission('bookings.edit'))
          <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#16a34a; border-color:#16a34a; font-weight:700; display:inline-flex; align-items:center; gap:6px;" onclick="document.getElementById('restartWorkOrderModal').style.display='flex'">
            <span>🔄 Re-start Work Order</span>
          </button>
        @endif
      </div>
    </div>
  @endif

  <!-- ========================================================================= -->
  <!-- 3. TAB NAVIGATION MENU BAR                                                -->
  <!-- ========================================================================= -->
  <div class="booking-tabs-nav">
    <button type="button" class="booking-tab-btn active" data-tab="tab-overview" onclick="switchBookingTab('tab-overview')">
      <span>📋 Overview & Details</span>
    </button>

    @if(auth('admin')->user()?->hasPermission('bookings.edit') && !$booking->is_amc)
      <button type="button" class="booking-tab-btn" data-tab="tab-manage-dispatch" onclick="switchBookingTab('tab-manage-dispatch')">
        <span>⚙️ Manage & Edit Dispatch</span>
      </button>
    @endif

    <button type="button" class="booking-tab-btn" data-tab="tab-audit-logs" onclick="switchBookingTab('tab-audit-logs')">
      <span>📜 Operational Audit Timeline</span>
      <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.72rem; padding:2px 8px; border-radius:10px; font-weight:700; white-space:nowrap !important; flex-shrink:0 !important;">
        {{ $booking->statusLogs->count() }}
      </span>
    </button>

    <!-- AMC TAB: Context-aware label (Active AMC vs AMC Agreement vs Convert to AMC) -->
    <button type="button" class="booking-tab-btn" data-tab="tab-amc-contract" onclick="switchBookingTab('tab-amc-contract')">
      @if($booking->contract_status === 'active')
        <span>🏢 Active AMC Contract</span>
        <span style="background:#dcfce7; color:#166534; font-size:0.7rem; padding:2px 6px; border-radius:4px; font-weight:700;">Active</span>
      @elseif($booking->is_amc)
        <span>🏢 AMC Agreement</span>
        @if($booking->status === 'pending_review')
          <span style="background:#fef3c7; color:#92400e; font-size:0.7rem; padding:2px 6px; border-radius:4px; font-weight:700;">Pending Setup</span>
        @endif
      @else
        <span>🏢 Convert to AMC</span>
      @endif
    </button>

    @if($booking->is_amc)
      <button type="button" class="booking-tab-btn" data-tab="tab-amc-shifts" onclick="switchBookingTab('tab-amc-shifts')">
        <span>🗓️ Routine Shifts ({{ $booking->amcShifts->count() }})</span>
        <span style="background:#eff6ff; color:#1d4ed8; font-size:0.7rem; padding:2px 6px; border-radius:4px; font-weight:700;">{{ $booking->completed_shifts_count }}/{{ $booking->amcShifts->count() }}</span>
      </button>
    @endif
  </div>

  <!-- ========================================================================= -->
  <!-- TAB 1: OVERVIEW & DETAILS                                                 -->
  <!-- ========================================================================= -->
  <div id="tab-overview" class="booking-tab-pane active">
    <!-- Top Warning Notice for Pending Orders -->
    @if($booking->status === 'pending_review')
      <div class="card-paces" style="background:#fffbeb; border:1px solid #fde68a; margin-bottom:1.25rem; padding:1rem 1.25rem; border-radius:10px;">
        <h3 style="font-size:0.92rem; font-weight:600; color:#92400e; margin:0 0 4px 0;">
          ⚠️ Pending Review — Discussion with Client Recommended
        </h3>
        <p style="font-size:0.82rem; color:#78350f; margin:0; line-height:1.45;">
          Talk with client <strong>{{ $booking->customer_name }}</strong> at <strong>{{ $booking->customer_phone }}</strong> to confirm problem scope, preferred schedule, and agreed service price before accepting.
        </p>
      </div>
    @endif

    <div style="display:grid; grid-template-columns:360px 1fr; gap:1.5rem; align-items:start;" class="workorder-grid">
      
      <!-- LEFT: Client & Property Profile -->
      <div>
        <div class="card-paces" style="padding:1.25rem; margin-bottom:1.25rem;">
          <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Customer & Site Profile</h3>
            <span style="font-size:0.75rem; color:#64748b;">ID #{{ $booking->user_id }}</span>
          </div>

          <div style="margin-bottom:14px; display:flex; align-items:center; gap:12px;">
            @if($booking->user?->avatar_url)
              <img src="{{ $booking->user?->avatar_url }}" alt="{{ $booking->customer_name }}" style="width:44px; height:44px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
            @else
              <div style="width:44px; height:44px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:700; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;">
                {{ strtoupper(substr($booking->customer_name ?: 'C', 0, 1)) }}
              </div>
            @endif
            <div>
              <div style="font-size:1.05rem; font-weight:700; color:var(--paces-text-dark);">
                {{ $booking->customer_name }}
              </div>
              @if($booking->company_name)
                <div style="font-size:0.8rem; color:var(--paces-primary); font-weight:500;">
                  🏢 {{ $booking->company_name }}
                </div>
              @endif
              <div style="font-size:0.8rem; color:var(--paces-text-muted); margin-top:2px;">
                <a href="tel:{{ $booking->customer_phone }}" style="color:var(--paces-primary); font-weight:600; text-decoration:none;">
                  📞 {{ $booking->customer_phone }}
                </a>
              </div>
            </div>
          </div>

          <div style="display:flex; flex-direction:column; gap:8px; font-size:0.82rem; margin-bottom:12px;">
            @if($booking->customer_email)
              <div>
                <span style="color:var(--paces-text-muted);">Email:</span>
                <a href="mailto:{{ $booking->customer_email }}" style="color:var(--paces-primary); text-decoration:none; margin-left:4px;">
                  {{ $booking->customer_email }}
                </a>
              </div>
            @endif
            <div>
              <span style="color:var(--paces-text-muted);">Property:</span>
              <strong style="text-transform:capitalize; margin-left:4px;">{{ $booking->property_type }}</strong>
              @if($booking->estimated_sqft)
                <span style="color:var(--paces-text-muted);">({{ number_format($booking->estimated_sqft) }} sq.ft)</span>
              @endif
            </div>
          </div>

          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px 12px; margin-bottom:12px;">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700; margin-bottom:4px;">Service Location</div>
            <div style="font-size:0.85rem; font-weight:600; color:var(--paces-text-dark);">
              📍 {{ $booking->street_address }}
            </div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); margin-top:2px;">
              {{ $booking->area }}, {{ $booking->district }}, {{ $booking->division }}
            </div>
            <div style="margin-top:6px;">
              <a href="https://maps.google.com/?q={{ urlencode($booking->street_address . ', ' . $booking->area . ', ' . $booking->district) }}" target="_blank" style="font-size:0.75rem; color:#2563eb; text-decoration:none; font-weight:600;">
                ↗ Open in Google Maps
              </a>
            </div>
          </div>

          <!-- Problem Description & Attached Photos -->
          <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Requested Service Scope</div>
            <div style="font-size:0.92rem; font-weight:700; color:var(--paces-text-dark); margin-top:2px;">
              {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Facility Service') }}
            </div>
            <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:6px; padding:8px 10px; font-size:0.8rem; color:var(--paces-text-dark); line-height:1.45; margin-top:6px; white-space:pre-line;">
              {{ $booking->problem_description }}
            </div>

            @if(!empty($booking->problem_photos) && is_array($booking->problem_photos))
              <div style="margin-top:10px;">
                <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700; margin-bottom:6px;">Customer Uploaded Photos</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                  @foreach($booking->problem_photos as $idx => $photo)
                    <div style="cursor:pointer;" onclick="openImageLightbox('{{ asset($photo) }}', 'Site Photo #{{ $idx + 1 }}')">
                      <img src="{{ asset($photo) }}" alt="Photo {{ $idx + 1 }}" style="width:55px; height:55px; object-fit:cover; border-radius:6px; border:1px solid #cbd5e1;">
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Phone Outreach & Quick Tasks (If present) -->
        @if($booking->outreachTasks->isNotEmpty() || $booking->status === 'pending_review')
          <div class="card-paces" style="padding:1.25rem; margin-bottom:1.25rem;">
            <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
              <h4 style="margin:0; font-size:0.88rem; font-weight:700; color:#1e293b;">Phone Outreach & Follow-ups</h4>
              <button type="button" onclick="toggleCustomTaskBox()" class="btn-paces btn-paces-outline btn-paces-sm" style="font-size:0.72rem; padding:2px 8px;">
                + Add Task
              </button>
            </div>

            @if($booking->call_status)
              <div style="font-size:0.8rem; margin-bottom:8px; padding:6px 10px; background:#f8fafc; border-radius:6px;">
                Call Status: <strong>{{ ucfirst(str_replace('_', ' ', $booking->call_status)) }}</strong>
                @if($booking->call_notes)
                  <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">{{ $booking->call_notes }}</div>
                @endif
              </div>
            @endif

            <!-- Custom Task creation inline box -->
            <div id="customTaskBox" style="display:none; margin-bottom:10px; padding:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px;">
              <form action="{{ route('admin.outreach-tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="customer_name" value="{{ $booking->customer_name }}">
                <input type="hidden" name="customer_phone" value="{{ $booking->customer_phone }}">
                <input type="text" name="title" placeholder="Task title..." required class="form-control-paces" style="font-size:0.8rem; padding:4px 8px; margin-bottom:6px; width:100%;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-bottom:6px;">
                  <select name="task_type" class="form-control-paces" style="font-size:0.78rem; padding:4px 6px;">
                    <option value="follow_up">Follow-up Call</option>
                    <option value="quotation">Send Quotation</option>
                    <option value="inspection">Site Inspection</option>
                  </select>
                  <select name="priority" class="form-control-paces" style="font-size:0.78rem; padding:4px 6px;">
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">🚨 Urgent</option>
                  </select>
                </div>
                <div style="text-align:right;">
                  <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.75rem; padding:3px 10px;">Save Task</button>
                </div>
              </form>
            </div>

            @foreach($booking->outreachTasks as $ot)
              <div style="display:flex; justify-content:space-between; align-items:center; padding:6px 8px; border:1px solid #f1f5f9; border-radius:6px; margin-bottom:4px; font-size:0.78rem;">
                <div>
                  <div style="font-weight:600; color:{{ $ot->status === 'completed' ? '#94a3b8' : '#1e293b' }}; text-decoration:{{ $ot->status === 'completed' ? 'line-through' : 'none' }};">
                    {{ preg_replace('/\s*\(#[A-Za-z0-9-]+\)\s*$/', '', $ot->title) }}
                  </div>
                  @if($ot->due_at)
                    <div style="font-size:0.7rem; color:#64748b;">Due: {{ $ot->due_at->diffForHumans() }}</div>
                  @endif
                </div>
                @if($ot->status === 'pending')
                  <form action="{{ route('admin.outreach-tasks.complete', $ot) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-paces btn-paces-sm" style="padding:2px 6px; font-size:0.7rem; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; border-radius:4px; cursor:pointer;">
                      ✓ Done
                    </button>
                  </form>
                @endif
              </div>
            @endforeach
          </div>
        @endif

        @if($booking->is_amc)
          <!-- Customer AMC Notes & Instructions Thread -->
          <div class="card-paces" style="padding:1.25rem; margin-bottom:1.25rem; border:1px solid #99f6e4; background:#f0fdfa;">
            <div style="border-bottom:1px solid #ccfbf1; padding-bottom:0.75rem; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
              <h3 class="card-title-paces" style="font-size:0.95rem; margin:0; color:#0f2744;">
                📝 Client AMC Notes &amp; Special Instructions
              </h3>
              <span class="badge-paces" style="background:#ccfbf1; color:#0f766e; font-size:0.7rem; font-weight:700; padding:2px 8px; border-radius:10px;">
                {{ $booking->amcNotes->count() }}
              </span>
            </div>

            @if($booking->amcNotes->isNotEmpty())
              <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px; max-height:260px; overflow-y:auto; padding-right:4px;">
                @foreach($booking->amcNotes as $note)
                  <div style="background:#ffffff; border:1px solid {{ $note->sender_type === 'customer' ? '#bfdbfe' : '#e2e8f0' }}; border-radius:8px; padding:10px 12px; font-size:0.82rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                      <strong style="color:{{ $note->sender_type === 'customer' ? '#1d4ed8' : '#047857' }};">
                        {{ $note->sender_type === 'customer' ? 'Client (' . ($note->sender_name ?: 'Customer') . ')' : 'Admin (' . ($note->sender_name ?: 'Operations') . ')' }}
                      </strong>
                      <span style="font-size:0.7rem; color:#94a3b8;">{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="margin:0; color:#334155; line-height:1.45;">{{ $note->note }}</p>
                  </div>
                @endforeach
              </div>
            @else
              <p style="font-size:0.8rem; color:#64748b; margin-bottom:12px;">No special client instructions submitted yet.</p>
            @endif

            <form action="{{ route('admin.bookings.amc-notes.store', $booking) }}" method="POST">
              @csrf
              <div style="margin-bottom:8px;">
                <textarea name="note" class="form-control-paces" rows="2" placeholder="Send an operational instruction or note to client..." style="font-size:0.82rem;" required></textarea>
              </div>
              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.78rem;">
                  Send Note to Client
                </button>
              </div>
            </form>
          </div>
        @endif
      </div>

      <!-- RIGHT: Active Operational Execution & Actions -->
      <div>
        @if($booking->status === 'cancelled' && auth('admin')->user()?->hasPermission('bookings.edit'))
          <!-- RE-START WORK ORDER CARD -->
          <div class="card-paces" style="border:2px dashed #f87171; background:#fffafb; margin-bottom:1.5rem; padding:20px;">
            <div style="display:flex; align-items:flex-start; gap:14px;">
              <div style="width:42px; height:42px; border-radius:50%; background:#fee2e2; color:#dc2626; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0;">
                ✕
              </div>
              <div style="flex:1;">
                <h4 style="margin:0 0 4px; font-size:1rem; font-weight:700; color:#991b1b;">
                  Work Order Cancelled
                </h4>
                <p style="margin:0 0 12px; font-size:0.82rem; color:#7f1d1d; line-height:1.45;">
                  This work order is currently cancelled. You can restart it to return it to <strong>Pending Review</strong>, discuss terms with the customer, re-accept the order, and dispatch to field crew.
                </p>
                @if($booking->cancellation_reason)
                  <div style="background:#ffffff; border:1px solid #fecaca; border-radius:6px; padding:8px 12px; font-size:0.8rem; color:#991b1b; margin-bottom:14px;">
                    <strong>Cancellation Reason:</strong> {{ $booking->cancellation_reason }}
                  </div>
                @endif
                <button type="button" class="btn-paces btn-paces-primary" style="background:#16a34a; border-color:#16a34a; font-weight:700; display:inline-flex; align-items:center; gap:6px;" onclick="document.getElementById('restartWorkOrderModal').style.display='flex'">
                  <span>🔄 Re-start Work Order</span>
                </button>
              </div>
            </div>
          </div>
        @endif

        @if($booking->status === 'pending_review' && auth('admin')->user()?->hasPermission('bookings.edit'))
          @if($booking->is_amc)
            <!-- AMC PENDING REVIEW BANNER CARD -->
            <div class="card-paces" style="border:2px solid #0d9488; margin-bottom:1.5rem; background:linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%); padding:22px; border-radius:12px; box-shadow:0 4px 14px rgba(13,148,136,0.08);">
              <div style="display:flex; align-items:flex-start; gap:16px;">
                <div style="width:48px; height:48px; border-radius:12px; background:#ccfbf1; color:#0f766e; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0;">
                  🏢
                </div>
                <div style="flex:1;">
                  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:6px;">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#0f2744;">
                      Annual Maintenance Contract (AMC) Work Order
                    </h3>
                    <span class="badge-pill-paces" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:700; font-size:0.75rem; padding:4px 10px;">
                      Pending Agreement Setup
                    </span>
                  </div>
                  <p style="margin:0 0 16px; font-size:0.85rem; color:#334155; line-height:1.5;">
                    This customer requested an Annual Maintenance Contract. To activate this contract and dispatch the assigned field crew, configure the payment schedule (Monthly, Daily, Yearly, Half Yearly, One Time), set contract tenure, and assign the lead crew in the <strong>AMC Agreement</strong> tab.
                  </p>
                  <button type="button" class="btn-paces btn-paces-primary" onclick="switchBookingTab('tab-amc-contract')" style="font-weight:700; padding:9px 22px; background:#0d9488; border-color:#0d9488; font-size:0.9rem; display:inline-flex; align-items:center; gap:8px;">
                    <span>➔ Configure & Activate AMC Agreement</span>
                  </button>
                </div>
              </div>
            </div>
          @else
            <!-- ACCEPT WORK ORDER CARD -->
            <div class="card-paces" style="border:2px solid #16a34a; margin-bottom:1.5rem;">
              <div class="card-header-paces" style="background:#f0fdf4; border-bottom:1px solid #bbf7d0;">
                <h3 class="card-title-paces" style="color:#166534; display:flex; align-items:center; gap:8px; font-size:1.05rem;">
                  <span>✓</span> Accept Work Order & Assign Dispatch
                </h3>
              </div>
            
            <div class="card-body-paces" style="padding:1.5rem;">
              <form action="{{ route('admin.bookings.accept', $booking) }}" method="POST">
                @csrf

                <!-- Pricing Agreement Section -->
                <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; margin-bottom:1.25rem;">
                  <div style="font-weight:700; font-size:0.88rem; color:var(--paces-text-dark); margin-bottom:10px;">
                    Service Price Agreement
                  </div>

                  <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:center; margin-bottom:12px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:600; color:var(--paces-text-dark);">
                      <input type="radio" name="pricing_mode" value="price" id="pricingModePrice" checked onchange="togglePricingInput(this.value)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                      <span>Price Agreed Now</span>
                    </label>

                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:600; color:var(--paces-text-dark);">
                      <input type="radio" name="pricing_mode" value="later" id="pricingModeLater" onchange="togglePricingInput(this.value)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                      <span>Set Price Later (Crew sets before work starts)</span>
                    </label>
                  </div>

                  <div id="priceAmountRow" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                    <div style="position:relative; width:180px;">
                      <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--paces-text-muted);">৳</span>
                      <input 
                        type="number" 
                        step="0.01" 
                        name="cost_amount" 
                        id="costAmountInput" 
                        class="form-control-paces" 
                        value="{{ old('cost_amount', $booking->estimated_cost > 0 ? $booking->estimated_cost : '') }}" 
                        placeholder="0.00" 
                        style="padding-left:28px; font-weight:700; font-size:1.05rem;"
                      >
                    </div>

                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.85rem; color:var(--paces-text-dark);">
                      <input type="checkbox" name="is_estimated" id="isEstimatedCheckbox" value="1" {{ old('cost_type', $booking->cost_type) === 'estimated' ? 'checked' : '' }} onchange="toggleCostType(this.checked)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                      <span>Estimated Price (Subject to site verification)</span>
                    </label>
                    <input type="hidden" name="cost_type" id="costTypeHidden" value="{{ old('cost_type', $booking->cost_type === 'estimated' ? 'estimated' : 'final') }}">
                  </div>
                </div>

                <!-- Schedule & Slot -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
                  <div>
                    <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Scheduled Service Date</label>
                    <input type="date" name="preferred_date" class="form-control-paces" value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d')) }}">
                  </div>

                  <div>
                    <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Arrival Window / Time Slot</label>
                    <input type="text" name="preferred_time_slot" class="form-control-paces" value="{{ old('preferred_time_slot', $booking->preferred_time_slot) }}" placeholder="e.g. 10:00 AM - 12:00 PM">
                  </div>
                </div>

                <!-- STRICT CREW DROPDOWN SELECTION -->
                <div style="margin-bottom:1.25rem;">
                  <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">
                    Assign Field Crew / Partner Vendor
                    <span style="font-weight:400; color:#64748b;">(Filtered by Service & Area)</span>
                  </label>
                  <div style="position:relative;" id="crewDropdownWrapper_accept">
                    <input type="hidden" name="partner_id" id="partner_id_accept" value="{{ old('partner_id', $booking->partner_id) }}">
                    <input 
                      type="text" 
                      id="crewInput_accept" 
                      name="assigned_team" 
                      class="form-control-paces" 
                      value="{{ old('assigned_team', $booking->assigned_team) }}" 
                      placeholder="Select approved crew..." 
                      autocomplete="off"
                      onfocus="openCrewDropdown('accept')"
                      oninput="filterCrewOptions('accept')"
                    >

                    <!-- Searchable Dropdown Menu -->
                    <div id="crewDropdownMenu_accept" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; max-height:240px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.12); z-index:9999; padding:4px 0;">
                      <div style="padding:6px 12px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">
                        Available Matching Field Crews ({{ $fieldCrews->count() }})
                      </div>
                      <div id="crewListItems_accept">
                        @forelse($fieldCrews as $crew)
                          @php
                            $compOrRole = $crew->company_name ?: $crew->role;
                            $crewFullVal = "{$crew->name} ({$compOrRole}) - {$crew->phone}";
                            $crewPartnerId = $crew->partner_id ?? '';
                            $crewWarning = $crew->warning_msg ?? '';
                          @endphp
                          <div class="crew-option-item" 
                               data-val="{{ strtolower($crewFullVal) }}"
                               onclick="selectCrew('accept', '{{ addslashes($crewFullVal) }}', false, '{{ $crewPartnerId }}', '{{ addslashes($crewWarning) }}')"
                               style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                               onmouseover="this.style.background='#eff6ff'" 
                               onmouseout="this.style.background='transparent'">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                              <div style="font-weight:700; font-size:0.85rem; color:#1e293b;">
                                {{ $crew->name }}
                                @if(!empty($crew->match_badge))
                                  <span style="font-size:0.68rem; font-weight:700; color:#047857; background:#ecfdf5; border:1px solid #a7f3d0; padding:1px 6px; border-radius:4px; margin-left:4px;">
                                    {{ $crew->match_badge }}
                                  </span>
                                @endif
                              </div>
                              @if(!empty($crew->avail_label))
                                <span style="font-size:0.72rem; font-weight:600; color:{{ $crew->avail_color }}; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 6px; border-radius:4px;">
                                  {{ $crew->avail_label }}
                                </span>
                              @endif
                            </div>
                            <div style="font-size:0.75rem; color:#64748b; display:flex; justify-content:space-between; margin-top:2px;">
                              <span>{{ $crew->role }}</span>
                              <span style="color:#0284c7; font-weight:500;">📞 {{ $crew->phone }}</span>
                            </div>
                          </div>
                        @empty
                          <div style="padding:14px 12px; text-align:center; font-size:0.8rem; color:#dc2626; background:#fef2f2;">
                            ⚠️ No crews found matching this service ({{ $booking->service?->title ?? 'Service' }}) and location ({{ $booking->area ?: ($booking->district ?: 'Area') }}).
                          </div>
                        @endforelse
                      </div>
                    </div>
                  </div>
                </div>

                <div style="margin-bottom:1.5rem;">
                  <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Dispatch Comment (Visible in status history)</label>
                  <textarea name="comment" class="form-control-paces" rows="2" placeholder="e.g. Scope confirmed over phone. Crew dispatched for site delivery."></textarea>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:14px;">
                  <div style="font-size:0.78rem; color:#64748b;">
                    Client and crew will be notified upon acceptance.
                  </div>
                  <button type="submit" class="btn-paces btn-paces-primary" style="background:#16a34a; border-color:#16a34a; padding:8px 22px; font-weight:700;">
                    ✓ Accept Work Order & Dispatch
                  </button>
                </div>
              </form>
            </div>
          </div>
          @endif
        @else
          <!-- CURRENT OPERATIONAL EXECUTION CARD -->
          <div class="card-paces" style="margin-bottom:1.5rem;">
            <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--paces-border); padding-bottom:1rem;">
              <div>
                <h3 class="card-title-paces" style="margin:0; font-size:1.05rem;">Current Execution State</h3>
                <div style="font-size:0.78rem; color:var(--paces-text-muted);">Real-time dispatch and field delivery status</div>
              </div>

              @if(auth('admin')->user()?->hasPermission('bookings.edit'))
                <button type="button" onclick="switchBookingTab('{{ $booking->is_amc ? 'tab-amc-contract' : 'tab-manage-dispatch' }}')" class="btn-paces btn-paces-outline btn-paces-sm" style="font-weight:600;">
                  ✏️ Edit / Reassign
                </button>
              @endif
            </div>

            <div class="card-body-paces" style="padding:1.5rem;">
              <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1.25rem; margin-bottom:1.5rem;">
                <!-- Schedule -->
                <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Scheduled Date & Slot</div>
                  <div style="font-size:1rem; font-weight:700; color:var(--paces-text-dark); margin-top:2px;">
                    {{ $booking->preferred_date?->format('M d, Y') ?? 'Not Set' }}
                  </div>
                  <div style="font-size:0.8rem; color:var(--paces-text-muted); margin-top:2px;">Slot: {{ $booking->preferred_time_slot ?: 'Flexible' }}</div>
                </div>

                <!-- Assigned Crew -->
                <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Assigned Crew</div>
                  @if($booking->partner)
                    <div style="font-size:0.95rem; font-weight:700; color:var(--paces-text-dark); margin-top:2px;">
                      {{ $booking->partner->company_name }}
                    </div>
                    <div style="font-size:0.78rem; color:var(--paces-text-muted);">
                      📞 {{ $booking->partner->phone }}
                    </div>
                    @if($booking->partner_job_status)
                      <div style="margin-top:4px;">
                        <span style="font-size:0.72rem; font-weight:700; padding:2px 6px; border-radius:4px; background:#eff6ff; color:#2563eb;">
                          Crew Status: {{ ucfirst(str_replace('_', ' ', $booking->partner_job_status)) }}
                        </span>
                      </div>
                    @endif
                  @elseif($booking->assigned_team)
                    <div style="font-size:0.95rem; font-weight:700; color:var(--paces-text-dark); margin-top:2px;">
                      {{ $booking->assigned_team }}
                    </div>
                  @else
                    <div style="font-size:0.85rem; color:#dc2626; font-weight:600; margin-top:2px;">
                      ⚠️ No crew assigned yet
                    </div>
                  @endif
                </div>

                <!-- Pricing -->
                <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Service Pricing</div>
                  <div style="font-size:1.15rem; font-weight:800; color:var(--paces-text-dark); margin-top:2px;">
                    ৳ {{ number_format((float) ($booking->final_cost ?: $booking->estimated_cost ?: 0), 2) }}
                  </div>
                  <div style="font-size:0.78rem; color:var(--paces-text-muted);">
                    {{ $booking->final_cost > 0 ? 'Final Invoiced Bill' : ($booking->is_price_set_later ? 'Billed Upon Completion' : 'Agreed Price') }}
                  </div>
                </div>
              </div>

              <!-- State-specific Action Prompts -->
              @if($booking->status === 'in_progress' && auth('admin')->user()?->hasPermission('bookings.edit'))
                <div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:10px; padding:16px; margin-bottom:1rem;">
                  <div style="font-weight:700; color:#166534; font-size:0.95rem; margin-bottom:8px;">
                    🔧 Service is Currently In Progress
                  </div>
                  <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">
                    @csrf
                    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:10px;">
                      <div>
                        <label style="font-size:0.78rem; font-weight:600; color:#374151; display:block;">Final Invoiced Bill (৳) *</label>
                        <input type="number" step="0.01" name="final_cost" required value="{{ $booking->final_cost > 0 ? $booking->final_cost : ($booking->estimated_cost > 0 ? $booking->estimated_cost : '') }}" class="form-control-paces" style="width:160px; font-weight:700;">
                      </div>
                      <div style="flex:1;">
                        <label style="font-size:0.78rem; font-weight:600; color:#374151; display:block;">Completion Feedback / Remarks</label>
                        <input type="text" name="completion_feedback" placeholder="Work verified on site..." class="form-control-paces" style="width:100%;">
                      </div>
                    </div>
                    <button type="submit" class="btn-paces btn-paces-primary" style="background:#16a34a; border-color:#16a34a; font-weight:700;">
                      ✓ Mark Work Completed
                    </button>
                  </form>
                </div>
              @endif

              @if($booking->status === 'completed' && $booking->partner)
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                  <div>
                    <div style="font-weight:700; font-size:0.9rem; color:#1e293b;">Partner Settlement Share</div>
                    <div style="font-size:0.8rem; color:#64748b;">
                      Partner Due: <strong>৳{{ number_format((float) ($booking->partner_payout_amount ?? $booking->partner->calculateBookingEarning($booking)), 2) }}</strong>
                      • Status: <span style="font-weight:700;">{{ ucfirst($booking->partner_payout_status ?: 'unpaid') }}</span>
                    </div>
                  </div>
                  <a href="{{ route('admin.partners.show', $booking->partner) }}#tab-payouts" class="btn-paces btn-paces-primary btn-paces-sm">
                    Manage Partner Balance Payout →
                  </a>
                </div>
              @endif
            </div>
          </div>
        @endif

        <!-- NON-AMC CONVERT BANNER (Only on normal service requests, not cluttering the front!) -->
        @if(!$booking->is_amc && $booking->contract_status !== 'active')
          <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; padding:14px 18px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:1.5rem;">
            <div>
              <div style="font-size:0.88rem; font-weight:700; color:#334155;">
                🏢 Convert to Annual Maintenance Contract (AMC)
              </div>
              <div style="font-size:0.8rem; color:#64748b; margin-top:2px;">
                Client requested an on-demand service. If after site inspection terms are agreed for an annual contract, you can activate AMC terms.
              </div>
            </div>
            <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="switchBookingTab('tab-amc-contract')" style="font-weight:700;">
              Open AMC Setup →
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- TAB 2: MANAGE & EDIT DISPATCH (NON-AMC ORDERS ONLY)                       -->
  <!-- ========================================================================= -->
  @if(auth('admin')->user()?->hasPermission('bookings.edit') && !$booking->is_amc)
    <div id="tab-manage-dispatch" class="booking-tab-pane">
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem;">
          <h3 class="card-title-paces" style="margin:0; font-size:1.1rem;">Modify Work Order Details</h3>
          <p style="margin:3px 0 0; font-size:0.8rem; color:var(--paces-text-muted);">Update status, dispatch lead, scheduled date, costs, and internal operational logs.</p>
        </div>

        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
          @csrf
          @method('PUT')

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem; display:flex; justify-content:space-between; align-items:center;">
                <span>Work Order Status</span>
                <span style="font-size:0.7rem; color:#64748b; font-weight:500;">🔒 Auto-calculated (Read Only)</span>
              </label>
              <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:7px 12px; min-height:42px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                <div style="display:flex; align-items:center; gap:8px;">
                  <span class="badge-pill-paces {{ $badgeClass }}" style="font-size:0.82rem; font-weight:800; text-transform:uppercase; padding:4px 12px; letter-spacing:0.02em;">
                    ● {{ $booking->status_label }}
                  </span>
                  @if($booking->partner_id && $booking->partner_job_status)
                    <span style="font-size:0.75rem; color:#475569; font-weight:600; background:#e2e8f0; padding:2px 8px; border-radius:4px;">
                      Crew: {{ ucfirst(str_replace('_', ' ', $booking->partner_job_status)) }}
                    </span>
                  @endif
                </div>
                @php $latestLog = $booking->statusLogs->sortByDesc('id')->first(); @endphp
                @if($latestLog)
                  <span style="font-size:0.72rem; color:#64748b; font-weight:500;">
                    Updated {{ $latestLog->created_at->diffForHumans() }}
                  </span>
                @endif
              </div>
            </div>

            <!-- STRICT CREW DROPDOWN (Edit mode) -->
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Assigned Field Crew / Lead</label>
              <div style="position:relative;" id="crewDropdownWrapper_edit">
                <input type="hidden" name="partner_id" id="partner_id_edit" value="{{ old('partner_id', $booking->partner_id) }}">
                <input 
                  type="text" 
                  id="crewInput_edit" 
                  name="assigned_team" 
                  class="form-control-paces" 
                  value="{{ old('assigned_team', $booking->assigned_team) }}" 
                  placeholder="Select approved crew..." 
                  autocomplete="off"
                  onfocus="openCrewDropdown('edit')"
                  oninput="filterCrewOptions('edit')"
                >

                <div id="crewDropdownMenu_edit" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; max-height:260px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.12); z-index:9999; padding:4px 0;">
                  <div style="padding:6px 12px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">
                    Select Enlisted Partner or Field Technician ({{ $fieldCrews->count() }} available)
                  </div>
                  <div id="crewListItems_edit">
                    @forelse($fieldCrews as $crew)
                      @php
                        $compOrRole = $crew->company_name ?: $crew->role;
                        $crewFullVal = "{$crew->name} ({$compOrRole}) - {$crew->phone}";
                        $crewPartnerId = $crew->partner_id ?? '';
                        $crewWarning = $crew->warning_msg ?? '';
                      @endphp
                      <div class="crew-option-item" 
                           data-val="{{ strtolower($crewFullVal) }}"
                           onclick="selectCrew('edit', '{{ addslashes($crewFullVal) }}', true, '{{ $crewPartnerId }}', '{{ addslashes($crewWarning) }}')"
                           style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                           onmouseover="this.style.background='#eff6ff'" 
                           onmouseout="this.style.background='transparent'">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                          <div style="font-weight:700; font-size:0.85rem; color:#1e293b;">
                            {{ $crew->name }}
                            @if(!empty($crew->match_badge))
                              <span style="font-size:0.68rem; font-weight:700; color:#047857; background:#ecfdf5; border:1px solid #a7f3d0; padding:1px 6px; border-radius:4px; margin-left:4px;">
                                {{ $crew->match_badge }}
                              </span>
                            @endif
                          </div>
                          @if(!empty($crew->avail_label))
                            <span style="font-size:0.72rem; font-weight:600; color:{{ $crew->avail_color }}; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 6px; border-radius:4px;">
                              {{ $crew->avail_label }}
                            </span>
                          @endif
                        </div>
                        <div style="font-size:0.75rem; color:#64748b; display:flex; justify-content:space-between; margin-top:2px;">
                          <span>{{ $crew->role }}</span>
                          <span style="color:#0284c7; font-weight:500;">📞 {{ $crew->phone }}</span>
                        </div>
                      </div>
                    @empty
                      <div style="padding:14px 12px; text-align:center; font-size:0.8rem; color:#dc2626; background:#fef2f2;">
                        ⚠️ No crews found matching this service ({{ $booking->service?->title ?? 'Service' }}) and location ({{ $booking->area ?: ($booking->district ?: 'Area') }}).
                      </div>
                    @endforelse
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Scheduled Service Date</label>
              <input type="date" name="preferred_date" class="form-control-paces" value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d')) }}">
            </div>
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Arrival Window / Time Slot</label>
              <input type="text" name="preferred_time_slot" class="form-control-paces" value="{{ old('preferred_time_slot', $booking->preferred_time_slot) }}">
            </div>
          </div>

          <!-- Service Price Agreement Section -->
          @php
            $hasExistingCost = ($booking->final_cost > 0 || $booking->estimated_cost > 0);
            $initialPricingMode = old('pricing_mode', $hasExistingCost ? 'price' : 'later');
            $existingCostAmount = old('cost_amount', $booking->final_cost > 0 ? $booking->final_cost : ($booking->estimated_cost > 0 ? $booking->estimated_cost : ''));
            $isEstimatedCost = old('cost_type') ? (old('cost_type') === 'estimated') : ($booking->cost_type === 'estimated' || (! $booking->final_cost && $booking->estimated_cost > 0));
          @endphp
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; margin-bottom:1.25rem;">
            <div style="font-weight:700; font-size:0.88rem; color:var(--paces-text-dark); margin-bottom:10px;">
              Service Price Agreement
            </div>

            <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:center; margin-bottom:12px;">
              <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:600; color:var(--paces-text-dark);">
                <input type="radio" name="pricing_mode" value="price" id="pricingModePrice_edit" {{ $initialPricingMode === 'price' ? 'checked' : '' }} onchange="togglePricingInputEdit(this.value)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                <span>Price Agreed Now</span>
              </label>

              <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:600; color:var(--paces-text-dark);">
                <input type="radio" name="pricing_mode" value="later" id="pricingModeLater_edit" {{ $initialPricingMode === 'later' ? 'checked' : '' }} onchange="togglePricingInputEdit(this.value)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                <span>Set Price Later (Crew sets before work starts)</span>
              </label>
            </div>

            <div id="priceAmountRow_edit" style="display:{{ $initialPricingMode === 'later' ? 'none' : 'flex' }}; align-items:center; gap:16px; flex-wrap:wrap;">
              <div style="position:relative; width:180px;">
                <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--paces-text-muted);">৳</span>
                <input 
                  type="number" 
                  step="0.01" 
                  name="cost_amount" 
                  id="costAmountInput_edit" 
                  class="form-control-paces" 
                  value="{{ $existingCostAmount }}" 
                  placeholder="0.00" 
                  style="padding-left:28px; font-weight:700; font-size:1.05rem;"
                >
              </div>

              <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.85rem; color:var(--paces-text-dark);">
                <input type="checkbox" name="is_estimated" id="isEstimatedCheckbox_edit" value="1" {{ $isEstimatedCost ? 'checked' : '' }} onchange="toggleCostTypeEdit(this.checked)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                <span>Estimated Price (Subject to site verification)</span>
              </label>
              <input type="hidden" name="cost_type" id="costTypeHidden_edit" value="{{ $isEstimatedCost ? 'estimated' : 'final' }}">
            </div>

            @if($booking->is_amc)
              <div style="margin-top:14px; padding-top:14px; border-top:1px dashed #cbd5e1; display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.8rem; font-weight:700;">AMC Charge Model (Client Billing) *</label>
                  <select name="amc_billing_type" class="form-control-paces">
                    <option value="total" {{ in_array(old('amc_billing_type', $booking->amc_billing_type), ['total', 'overall']) ? 'selected' : '' }}>
                      Total Contract (Fixed total contract budget)
                    </option>
                    <option value="daily" {{ in_array(old('amc_billing_type', $booking->amc_billing_type), ['daily', 'per_shift']) ? 'selected' : '' }}>
                      Daily / Per Work Shift (Billed on day of service)
                    </option>
                    <option value="monthly" {{ old('amc_billing_type', $booking->amc_billing_type ?? 'monthly') === 'monthly' ? 'selected' : '' }}>
                      Monthly (Invoiced / billed monthly)
                    </option>
                    <option value="yearly" {{ in_array(old('amc_billing_type', $booking->amc_billing_type), ['yearly', 'annual']) ? 'selected' : '' }}>
                      Yearly / Annual (Invoiced annually)
                    </option>
                  </select>
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.8rem; font-weight:700;">Crew Payout Per Shift (৳)</label>
                  <input type="number" step="0.01" name="payout_per_shift" class="form-control-paces" value="{{ old('payout_per_shift', $booking->payout_per_shift) }}" placeholder="e.g. 500.00 (Amount added to crew panel per shift)">
                  <span style="font-size:0.72rem; color:#64748b; display:block; margin-top:2px;">Amount credited to assigned crew's panel upon shift completion.</span>
                </div>
              </div>
            @endif
          </div>

          <div style="margin-bottom:1.25rem;">
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Update Comment (Visible in audit log)</label>
            <textarea name="comment" class="form-control-paces" rows="2" placeholder="Reason for change or update note..."></textarea>
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:14px;">
            <button type="button" onclick="document.getElementById('cancelSectionBox').style.display = document.getElementById('cancelSectionBox').style.display === 'none' ? 'block' : 'none'" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#dc2626; border-color:#fca5a5;">
              ✕ Cancel Work Order...
            </button>
            <button type="submit" class="btn-paces btn-paces-primary" style="padding:8px 20px; font-weight:700;">
              Save & Update Work Order
            </button>
          </div>
        </form>

        <!-- Cancel Section -->
        <div id="cancelSectionBox" style="display:none; margin-top:16px; padding:16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px;">
          <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this work order?');">
            @csrf
            <h4 style="margin:0 0 8px 0; font-size:0.92rem; color:#991b1b; font-weight:700;">Cancel This Work Order</h4>
            <div style="margin-bottom:10px;">
              <label style="font-size:0.78rem; font-weight:600; color:#374151; display:block;">Cancellation Reason *</label>
              <textarea name="cancellation_reason" required class="form-control-paces" rows="2" placeholder="e.g. Client requested cancellation or duplicate request..."></textarea>
            </div>
            <div style="text-align:right;">
              <button type="submit" class="btn-paces" style="background:#dc2626; color:#ffffff; font-weight:700; padding:6px 16px; border:none; border-radius:6px; cursor:pointer;">
                Confirm Cancellation
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  <!-- ========================================================================= -->
  <!-- TAB 3: OPERATIONAL AUDIT TIMELINE (COMPREHENSIVE AUDIT TRAIL)             -->
  <!-- ========================================================================= -->
  <div id="tab-audit-logs" class="booking-tab-pane">
    <div class="card-paces" style="margin-bottom:1.5rem; padding:20px;">
      <div style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div>
          <h3 class="card-title-paces" style="margin:0; font-size:1.1rem;">Operational Audit Timeline</h3>
        </div>
        <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.78rem; padding:4px 12px; border-radius:12px; font-weight:700; white-space:nowrap !important; flex-shrink:0 !important;">
          Total Logs: {{ $booking->statusLogs->count() }}
        </span>
      </div>

      @if($booking->statusLogs->isEmpty())
        <div style="text-align:center; padding:3rem 1rem; color:var(--paces-text-muted);">
          <div style="font-size:2rem; margin-bottom:8px;">📜</div>
          <div style="font-weight:600; font-size:0.95rem; color:#475569;">No Operational Logs Recorded</div>
          <p style="font-size:0.8rem; margin:4px 0 0;">Status changes and dispatch events will be recorded here automatically.</p>
        </div>
      @else
        <div style="display:flex; flex-direction:column; gap:14px; position:relative; padding-left:10px;">
          @foreach($booking->statusLogs->sortByDesc('id') as $log)
            <div style="display:flex; gap:16px; font-size:0.85rem; border-left:3px solid var(--paces-primary); padding-left:16px; position:relative;">
              <div style="position:absolute; left:-7px; top:4px; width:11px; height:11px; border-radius:50%; background:var(--paces-primary); border:2px solid #ffffff;"></div>
              
              <div style="flex:1; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                  <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight:700; color:var(--paces-primary); font-size:0.9rem;">
                      {{ ucfirst(str_replace('_', ' ', $log->from_status)) }} ➔ {{ ucfirst(str_replace('_', ' ', $log->to_status)) }}
                    </span>
                    <span style="background:#f1f5f9; color:#475569; font-size:0.7rem; font-weight:700; padding:2px 6px; border-radius:4px;">
                      {{ $log->created_at->diffForHumans() }}
                    </span>
                  </div>
                  <span style="font-size:0.75rem; color:#64748b;">
                    {{ $log->created_at->format('M d, Y • h:i A') }}
                  </span>
                </div>

                <div style="font-size:0.8rem; color:#475569; margin-top:4px;">
                  Changed by: <strong>{{ $log->changedByAdmin?->name ?? ($log->changedByUser?->name ?? 'System Automated') }}</strong>
                  @if($log->changedByAdmin)
                    <span style="color:#2563eb; font-size:0.72rem; margin-left:4px;">(Admin Operations)</span>
                  @endif
                </div>

                @if($log->comment)
                  <div style="margin-top:8px; background:#f8fafc; border:1px solid #f1f5f9; border-radius:6px; padding:8px 12px; font-size:0.82rem; color:#1e293b; line-height:1.45;">
                    {{ $log->comment }}
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- TAB 4: AMC CONTRACT & AGREEMENT / CONVERT TO AMC                          -->
  <!-- ========================================================================= -->
  <div id="tab-amc-contract" class="booking-tab-pane">
    <div class="card-paces" style="margin-bottom:1.5rem; padding:20px; border:1px solid {{ $booking->contract_status === 'active' ? '#10b981' : 'var(--paces-border)' }};">
      <div style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div>
          <h3 class="card-title-paces" style="margin:0; font-size:1.1rem; color:{{ $booking->contract_status === 'active' ? '#166534' : 'var(--paces-text-dark)' }};">
            Annual Maintenance Contract (AMC) & Agreement
          </h3>
        </div>

        @if($booking->contract_status === 'active')
          <span class="badge-pill-paces" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-weight:700; font-size:0.82rem; padding:4px 12px;">
            Active Contract
          </span>
        @else
          <span class="badge-pill-paces" style="background:#f1f5f9; color:#64748b; font-size:0.82rem; padding:4px 12px;">
            Contract Not Activated
          </span>
        @endif
      </div>

      <!-- If Active Contract -->
      @if($booking->contract_status === 'active')
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:1.5rem;">
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">AMC Package</div>
            <strong style="font-size:1rem; color:var(--paces-text-dark); display:block; margin-top:2px;">
              {{ $booking->amcPlan?->name ?? 'Corporate Enterprise AMC' }}
            </strong>
            @if($booking->amcPlan?->tier)
              <span style="font-size:0.72rem; color:var(--paces-primary);">{{ $booking->amcPlan?->tier }} Tier</span>
            @endif
          </div>

          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Contract Period</div>
            <div style="font-size:0.88rem; font-weight:600; color:var(--paces-text-dark); margin-top:2px;">
              {{ $booking->contract_start_date?->format('M d, Y') ?? 'Start' }} – {{ $booking->contract_end_date?->format('M d, Y') ?? ($booking->contract_tenure === 'Lifetime' ? 'Lifetime SLA' : 'Ongoing') }}
            </div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted);">{{ $booking->days_remaining_in_contract !== null ? $booking->days_remaining_in_contract . ' days remaining' : 'Ongoing / Lifetime SLA' }}</div>
          </div>

          <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px;">
            <div style="font-size:0.72rem; color:#065f46; text-transform:uppercase; font-weight:700;">Collected Revenue</div>
            <strong style="font-size:1.15rem; color:#047857; display:block; margin-top:2px;">
              ৳ {{ number_format($booking->collected_amc_revenue, 2) }}
            </strong>
            @if($booking->pending_amc_due > 0)
              <span style="font-size:0.72rem; color:#b45309; font-weight:600;">Due: ৳ {{ number_format($booking->pending_amc_due, 2) }}</span>
            @else
              <span style="font-size:0.72rem; color:#10b981;">All Bills Paid Up to Date</span>
            @endif
          </div>
        </div>

        @if($booking->agreement_document_path)
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
            <div>
              <div style="font-weight:700; font-size:0.88rem; color:#1e293b;">Signed Agreement Document</div>
              <div style="font-size:0.75rem; color:#64748b;">Uploaded agreement contract copy</div>
            </div>
            <a href="{{ asset($booking->agreement_document_path) }}" target="_blank" class="btn-paces btn-paces-primary btn-paces-sm">
              View Agreement Copy ↗
            </a>
          </div>
        @endif

        <!-- Invoices List -->
        <div style="margin-bottom:1.5rem;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h4 style="margin:0; font-size:0.95rem; font-weight:700; color:#1e293b;">Recurring Invoices & Collection Log</h4>
            @if(auth('admin')->user()?->hasPermission('bookings.edit'))
              <form action="{{ route('admin.bookings.amc-bills.generate', $booking) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">
                  + Generate Next Cycle Bill
                </button>
              </form>
            @endif
          </div>

          @if($booking->amcInvoices->isEmpty())
            <div style="text-align:center; padding:1.5rem; color:var(--paces-text-muted); font-size:0.82rem; background:#f8fafc; border-radius:8px;">
              No recurring invoices generated yet.
            </div>
          @else
            <div class="table-responsive-paces">
              <table class="table-paces">
                <thead>
                  <tr>
                    <th>Invoice #</th>
                    <th>Billing Cycle</th>
                    <th>Due Date</th>
                    <th style="text-align:right;">Amount (৳)</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:right;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($booking->amcInvoices as $inv)
                    <tr>
                      <td style="font-weight:700; font-family:monospace; color:var(--paces-primary);">{{ $inv->invoice_number }}</td>
                      <td>{{ $inv->billing_period_label }}</td>
                      <td style="font-size:0.8rem;">{{ $inv->due_date?->format('M d, Y') }}</td>
                      <td style="text-align:right; font-weight:700;">৳ {{ number_format($inv->amount, 2) }}</td>
                      <td style="text-align:center;">
                        <span style="font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:4px; background:{{ $inv->status === 'paid' ? '#ecfdf5' : '#fffbeb' }}; color:{{ $inv->status === 'paid' ? '#047857' : '#b45309' }};">
                          {{ ucfirst($inv->status) }}
                        </span>
                      </td>
                      <td style="text-align:right;">
                        @if($inv->status !== 'paid' && auth('admin')->user()?->hasPermission('bookings.edit'))
                          <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openCollectBillModal('{{ $inv->id }}', '{{ $inv->invoice_number }}', '{{ number_format($inv->amount, 2) }}')">
                            Collect Bill
                          </button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      @endif

      <!-- AMC Agreement & Dispatch Management Form (Merged Dispatch & Agreement) -->
      @if(auth('admin')->user()?->hasPermission('bookings.edit'))
        <div style="border-top:1px dashed var(--paces-border); padding-top:1.25rem;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
            <div>
              <h4 style="margin:0 0 4px 0; font-size:1.05rem; font-weight:700; color:#1e293b;">
                {{ $booking->contract_status === 'active' ? 'Manage & Update AMC Agreement & Dispatch' : 'Configure & Activate AMC Agreement' }}
              </h4>
              <p style="font-size:0.8rem; color:var(--paces-text-muted); margin:0;">
                Configure contract terms, select payment schedule, assign lead field crew, set shift payouts, and upload signed agreements.
              </p>
            </div>
            @if($booking->contract_status === 'active')
              <span class="badge-pill-paces" style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; font-weight:700; font-size:0.75rem; padding:4px 10px;">
                ● Active Contract Scope
              </span>
            @endif
          </div>

          <form action="{{ route('admin.bookings.activate-amc', $booking) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 1. FIELD CREW & DISPATCH LOGISTICS (Merged from Manage Dispatch) -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:1.25rem;">
              <h5 style="margin:0 0 12px 0; font-size:0.9rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px;">
                <span>👷</span> Field Crew &amp; Dispatch Logistics
              </h5>

              <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                <!-- Searchable Enlisted Crew Dropdown -->
                <div>
                  <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">
                    Assigned Field Crew / Lead Partner
                    <span style="font-weight:400; color:#64748b;">(Filtered by Service & Area)</span>
                  </label>
                  <div style="position:relative;" id="crewDropdownWrapper_amc">
                    <input type="hidden" name="partner_id" id="partner_id_amc" value="{{ old('partner_id', $booking->partner_id) }}">
                    <input 
                      type="text" 
                      id="crewInput_amc" 
                      name="assigned_team" 
                      class="form-control-paces" 
                      value="{{ old('assigned_team', $booking->assigned_team) }}" 
                      placeholder="Select approved crew..." 
                      autocomplete="off"
                      onfocus="openCrewDropdown('amc')"
                      oninput="filterCrewOptions('amc')"
                    >

                    <div id="crewDropdownMenu_amc" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; max-height:260px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.12); z-index:9999; padding:4px 0;">
                      <div style="padding:6px 12px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">
                        Select Enlisted Partner or Field Technician ({{ $fieldCrews->count() }} available)
                      </div>
                      <div id="crewListItems_amc">
                        @forelse($fieldCrews as $crew)
                          @php
                            $compOrRole = $crew->company_name ?: $crew->role;
                            $crewFullVal = "{$crew->name} ({$compOrRole}) - {$crew->phone}";
                            $crewPartnerId = $crew->partner_id ?? '';
                            $crewWarning = $crew->warning_msg ?? '';
                            $crewEarningModel = $crew->earning_model ?? 'commission';
                            $crewPayoutRate = (float) ($crew->payout_rate ?? 0);
                            $crewCommissionRate = (float) ($crew->commission_rate ?? 15);
                            $crewFormattedRate = $crew->formatted_rate ?? '15.00%';
                          @endphp
                          <div class="crew-option-item" 
                               data-val="{{ strtolower($crewFullVal) }}"
                               onclick="selectCrew('amc', '{{ addslashes($crewFullVal) }}', true, '{{ $crewPartnerId }}', '{{ addslashes($crewWarning) }}', '{{ $crewEarningModel }}', {{ $crewPayoutRate }}, {{ $crewCommissionRate }}, '{{ addslashes($crewFormattedRate) }}')"
                               style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                               onmouseover="this.style.background='#eff6ff'" 
                               onmouseout="this.style.background='transparent'">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                              <div style="font-weight:700; font-size:0.85rem; color:#1e293b;">
                                {{ $crew->name }}
                                @if(!empty($crew->match_badge))
                                  <span style="font-size:0.68rem; font-weight:700; color:#047857; background:#ecfdf5; border:1px solid #a7f3d0; padding:1px 6px; border-radius:4px; margin-left:4px;">
                                    {{ $crew->match_badge }}
                                  </span>
                                @endif
                              </div>
                              @if(!empty($crew->avail_label))
                                <span style="font-size:0.72rem; font-weight:600; color:{{ $crew->avail_color }}; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 6px; border-radius:4px;">
                                  {{ $crew->avail_label }}
                                </span>
                              @endif
                            </div>
                            <div style="font-size:0.75rem; color:#64748b; display:flex; justify-content:space-between; margin-top:2px;">
                              <span>{{ $crew->role }}</span>
                              <span style="color:#0284c7; font-weight:500;">📞 {{ $crew->phone }}</span>
                            </div>
                          </div>
                        @empty
                          <div style="padding:14px 12px; text-align:center; font-size:0.8rem; color:#dc2626; background:#fef2f2;">
                            ⚠️ No crews found matching this service and location.
                          </div>
                        @endforelse
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Crew Payout Per Shift (Auto-calculated from Partner Remuneration Agreement) -->
                <div>
                  <label class="form-label-paces" style="font-weight:600; font-size:0.82rem; display:flex; justify-content:space-between; align-items:center;">
                    <span>Crew Payout Per Shift (৳)</span>
                    <span style="font-size:0.7rem; color:#64748b; font-weight:500;">🔒 Agreement Auto-calculated</span>
                  </label>
                  <input type="hidden" name="payout_per_shift" id="payoutPerShiftHidden" value="{{ old('payout_per_shift', $booking->payout_per_shift) }}">
                  <div style="position:relative;">
                    <input 
                      type="text" 
                      id="payoutPerShiftDisplay" 
                      class="form-control-paces" 
                      readonly 
                      style="background:#f1f5f9; cursor:not-allowed; font-weight:700; color:#0f172a;" 
                      value="{{ number_format((float) ($booking->payout_per_shift ?? 0), 2, '.', '') }}"
                    >
                    <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:0.72rem; color:#64748b; font-weight:600;">🔒 Auto-calculated</span>
                  </div>
                  <span id="payoutPerShiftNote" style="font-size:0.72rem; color:#64748b; display:block; margin-top:4px;">
                    @if($booking->partner)
                      Auto-calculated from partner agreement: <strong>{{ $booking->partner->formatted_rate }}</strong> ({{ $booking->partner->earning_model_label }}).
                    @else
                      Select an approved field crew to automatically calculate their agreed remuneration rate.
                    @endif
                  </span>
                </div>
              </div>

              <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                  <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">First Routine Shift Scheduled Date *</label>
                  <input type="date" name="preferred_date" class="form-control-paces" value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d') ?: ($booking->contract_start_date?->format('Y-m-d') ?: date('Y-m-d'))) }}" required>
                </div>
                <div>
                  <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Arrival Window / Time Slot</label>
                  <input type="text" name="preferred_time_slot" class="form-control-paces" value="{{ old('preferred_time_slot', $booking->preferred_time_slot ?: 'Morning (9:00 AM - 12:00 PM)') }}" placeholder="e.g. Morning (9:00 AM - 12:00 PM)">
                </div>
              </div>
            </div>

            <!-- 2. CONTRACT PACKAGE & PAYMENT SCHEDULE -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:1.25rem;">
              <h5 style="margin:0 0 12px 0; font-size:0.9rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px;">
                <span>💳</span> AMC Package &amp; Payment Schedule
              </h5>

              <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Select AMC Package *</label>
                  <select name="amc_plan_id" class="form-control-paces" required>
                    <option value="">-- Choose AMC Package --</option>
                    @foreach($amcPlans as $plan)
                      <option value="{{ $plan->id }}" {{ (old('amc_plan_id', $booking->amc_plan_id) == $plan->id) ? 'selected' : '' }}>
                        {{ $plan->name }} ({{ $plan->price_period }})
                      </option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Payment Schedule (Client Billing) *</label>
                  <select name="billing_cycle" id="amcBillingCycleSelect" class="form-control-paces" required onchange="recalculateAmcAmounts()">
                    <option value="monthly" {{ old('billing_cycle', $booking->billing_cycle ?? 'monthly') === 'monthly' ? 'selected' : '' }}>
                      Monthly (Invoiced / Billed Monthly)
                    </option>
                    <option value="daily" {{ in_array(old('billing_cycle', $booking->billing_cycle), ['daily', 'per_shift']) ? 'selected' : '' }}>
                      Daily (Billed Per Work Shift / Visit)
                    </option>
                    <option value="yearly" {{ in_array(old('billing_cycle', $booking->billing_cycle), ['yearly', 'annual', 'annually']) ? 'selected' : '' }}>
                      Yearly (Annual Contract Lump Sum)
                    </option>
                    <option value="half_yearly" {{ old('billing_cycle', $booking->billing_cycle) === 'half_yearly' ? 'selected' : '' }}>
                      Half Yearly (Billed Every 6 Months)
                    </option>
                    <option value="one_time" {{ in_array(old('billing_cycle', $booking->billing_cycle), ['one_time', 'total', 'overall']) ? 'selected' : '' }}>
                      One Time (Total Contract Budget Upfront)
                    </option>
                  </select>
                </div>
              </div>

              <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:14px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Contract Tenure</label>
                  <select name="contract_tenure" id="contractTenureSelect" class="form-control-paces" onchange="recalculateAmcAmounts()">
                    @php
                      $tenureOptionsList = [
                        '3 Months' => '3 Months',
                        '6 Months' => '6 Months',
                        '1 Year' => '1 Year (Annual)',
                        '2 Years' => '2 Years',
                        '3 Years' => '3 Years',
                        'Lifetime' => 'Lifetime (Continuous SLA)',
                      ];
                      if ($booking->contract_tenure && !isset($tenureOptionsList[$booking->contract_tenure])) {
                        $tenureOptionsList[$booking->contract_tenure] = $booking->contract_tenure . ' (Custom)';
                      }
                    @endphp
                    @foreach($tenureOptionsList as $tenureVal => $tenureText)
                      <option value="{{ $tenureVal }}" {{ old('contract_tenure', $booking->contract_tenure ?? '1 Year') === $tenureVal ? 'selected' : '' }}>
                        {{ $tenureText }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Contract Start Date *</label>
                  <input type="date" name="contract_start_date" id="contractStartDateInput" class="form-control-paces" value="{{ old('contract_start_date', $booking->contract_start_date?->format('Y-m-d') ?: ($booking->preferred_date?->format('Y-m-d') ?: date('Y-m-d'))) }}" required onchange="recalculateAmcAmounts()">
                </div>

                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Contract End Date (Optional for Lifetime)</label>
                  <input type="date" name="contract_end_date" id="contractEndDateInput" class="form-control-paces" value="{{ old('contract_end_date', $booking->contract_end_date?->format('Y-m-d') ?: ($booking->contract_tenure === 'Lifetime' ? '' : date('Y-m-d', strtotime('+1 year')))) }}" onchange="recalculateAmcAmounts()">
                </div>
              </div>

              <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Total Contract Budget / Price (৳) *</label>
                  <input type="number" step="0.01" name="contract_cost" id="contractCostInput" class="form-control-paces" placeholder="e.g. 50000.00" value="{{ old('contract_cost', $booking->final_cost ?: $booking->estimated_cost) }}" oninput="recalculateAmcAmounts()">
                </div>

                <div>
                  <label class="form-label-paces" style="font-size:0.82rem; font-weight:600; display:flex; justify-content:space-between; align-items:center;">
                    <span>Recurring Cycle Amount (৳)</span>
                    <span style="font-size:0.7rem; color:#64748b; font-weight:500;">🔒 Auto-calculated</span>
                  </label>
                  <div style="position:relative;">
                    <input 
                      type="number" 
                      step="0.01" 
                      name="recurring_amount" 
                      id="recurringAmountInput" 
                      class="form-control-paces" 
                      readonly 
                      style="background:#f1f5f9; cursor:not-allowed; font-weight:700; color:#0f172a;" 
                      placeholder="0.00" 
                      value="{{ old('recurring_amount', $booking->recurring_amount ?: ($booking->final_cost ?: $booking->estimated_cost)) }}"
                    >
                    <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:0.72rem; color:#64748b; font-weight:600;">🔒 Auto-calculated</span>
                  </div>
                  <span id="recurringCycleHint" style="font-size:0.72rem; color:#64748b; display:block; margin-top:4px;">
                    Auto-calculated per billing cycle based on Total Contract Budget, Tenure, and Payment Schedule.
                  </span>
                </div>
              </div>
            </div>

            <!-- 3. AGREEMENT DOCUMENT & SCOPE NOTES -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:1.25rem;">
              <h5 style="margin:0 0 12px 0; font-size:0.9rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px;">
                <span>📄</span> Signed Agreement &amp; Operational Notes
              </h5>

              <div style="margin-bottom:14px;">
                <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Upload Signed Agreement Copy (PDF / Image)</label>
                <input type="file" name="agreement_document" class="form-control-paces" accept=".pdf,.png,.jpg,.jpeg">
                @if($booking->agreement_document_path)
                  <span style="font-size:0.75rem; color:#16a34a; display:block; margin-top:4px;">
                    ✓ Current Agreement file on record: <a href="{{ asset($booking->agreement_document_path) }}" target="_blank" style="color:#2563eb; font-weight:600; text-decoration:none;">View Uploaded Agreement ↗</a>
                  </span>
                @endif
              </div>

              <div style="margin-bottom:14px;">
                <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Contract Notes &amp; Service Deliverables</label>
                <textarea name="amc_package_notes" class="form-control-paces" rows="2" placeholder="Key service scope deliverables, routine visit frequency, spare parts terms...">{{ old('amc_package_notes', $booking->amc_package_notes) }}</textarea>
              </div>

              <div>
                <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Update Comment / Audit Reason</label>
                <textarea name="comment" class="form-control-paces" rows="2" placeholder="Internal audit reason for agreement update or dispatch assignment..."></textarea>
              </div>
            </div>

            <!-- 4. ACTIONS & CANCELLATION -->
            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #e2e8f0; padding-top:16px; flex-wrap:wrap; gap:12px;">
              <button type="button" onclick="document.getElementById('cancelAmcSectionBox').style.display = document.getElementById('cancelAmcSectionBox').style.display === 'none' ? 'block' : 'none'" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#dc2626; border-color:#fca5a5;">
                ✕ Cancel Work Order...
              </button>

              <button type="submit" class="btn-paces btn-paces-primary" style="padding:10px 28px; font-weight:700; font-size:0.92rem; background:#0d9488; border-color:#0d9488;">
                ✓ {{ $booking->contract_status === 'active' ? 'Save & Update AMC Agreement & Dispatch' : 'Activate AMC Agreement & Dispatch Crew' }}
              </button>
            </div>
          </form>

          <!-- Cancel AMC Work Order Section -->
          <div id="cancelAmcSectionBox" style="display:none; margin-top:16px; padding:16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px;">
            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this work order?');">
              @csrf
              <h4 style="margin:0 0 8px 0; font-size:0.92rem; color:#991b1b; font-weight:700;">Cancel This Work Order</h4>
              <div style="margin-bottom:10px;">
                <label style="font-size:0.78rem; font-weight:600; color:#374151; display:block;">Cancellation Reason *</label>
                <textarea name="cancellation_reason" required class="form-control-paces" rows="2" placeholder="e.g. Client requested cancellation or duplicate request..."></textarea>
              </div>
              <div style="text-align:right;">
                <button type="submit" class="btn-paces" style="background:#dc2626; color:#ffffff; font-weight:700; padding:6px 16px; border:none; border-radius:6px; cursor:pointer;">
                  Confirm Cancellation
                </button>
              </div>
            </form>
          </div>
        </div>
      @endif
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- TAB 5: AMC ROUTINE SHIFTS & VISITS                                        -->
  <!-- ========================================================================= -->
  <div id="tab-amc-shifts" class="booking-tab-pane">
    <div class="card-paces" style="margin-bottom:1.5rem; padding:20px; border:1px solid #99f6e4;">
      <div style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
          <h3 class="card-title-paces" style="margin:0; font-size:1.1rem; color:#0f2744;">
            🗓️ Routine Maintenance Shifts ({{ $booking->amcShifts->count() }})
          </h3>
          <p style="margin:2px 0 0; font-size:0.8rem; color:#64748b;">
            Scheduled periodic maintenance visits assigned to crew for this AMC contract.
          </p>
        </div>

        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
          @if(auth('admin')->user()?->hasPermission('bookings.edit'))
            <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="document.getElementById('adminAddShiftModal').style.display='flex'">
              + Add Routine Shift
            </button>
            @if($booking->status !== 'cancelled')
              <button type="button" class="btn-paces btn-paces-sm" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; font-weight:700;" onclick="document.getElementById('cancelBookingModal').style.display='flex'">
                ✕ Cancel Full Deal
              </button>
            @endif
          @endif
        </div>
      </div>

      <!-- Shifts Metric Cards -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:10px; margin-bottom:1.25rem;">
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px;">
          <span style="font-size:0.72rem; color:#64748b; font-weight:700; text-transform:uppercase;">Total Visits</span>
          <strong style="display:block; font-size:1.2rem; color:#0f172a; margin-top:2px;">{{ $booking->amcShifts->count() }}</strong>
        </div>
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:10px 14px;">
          <span style="font-size:0.72rem; color:#065f46; font-weight:700; text-transform:uppercase;">Completed</span>
          <strong style="display:block; font-size:1.2rem; color:#047857; margin-top:2px;">{{ $booking->completed_shifts_count }}</strong>
        </div>
        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:10px 14px;">
          <span style="font-size:0.72rem; color:#1e40af; font-weight:700; text-transform:uppercase;">Scheduled / Pending</span>
          <strong style="display:block; font-size:1.2rem; color:#1d4ed8; margin-top:2px;">{{ $booking->pending_shifts_count }}</strong>
        </div>
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px;">
          <span style="font-size:0.72rem; color:#991b1b; font-weight:700; text-transform:uppercase;">Cancelled Shifts</span>
          <strong style="display:block; font-size:1.2rem; color:#dc2626; margin-top:2px;">{{ $booking->cancelled_shifts_count }}</strong>
        </div>
      </div>

      <!-- Shifts Table -->
      @if($booking->amcShifts->isEmpty())
        <div style="text-align:center; padding:2rem; background:#f8fafc; border-radius:8px; color:#64748b; font-size:0.85rem;">
          No shifts scheduled yet. Click <strong>Generate Shifts</strong> or <strong>Add Routine Shift</strong> above.
        </div>
      @else
        <div class="table-responsive-paces">
          <table class="table-paces">
            <thead>
              <tr>
                <th style="width:50px;">#</th>
                <th>Shift Title</th>
                <th>Scheduled Date &amp; Window</th>
                <th>Assigned Crew</th>
                <th>Shift Payout (৳)</th>
                <th style="text-align:center;">Status</th>
                <th>Remarks / Notes</th>
                <th style="text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($booking->amcShifts as $shift)
                <tr>
                  <td style="font-weight:700; color:#64748b;">#{{ $shift->shift_number }}</td>
                  <td>
                    <strong style="color:#0f172a; font-size:0.875rem;">{{ $shift->title }}</strong>
                  </td>
                  <td>
                    <div style="font-weight:600; font-size:0.84rem; color:#0f172a;">
                      📅 {{ $shift->scheduled_date->format('M d, Y') }}
                    </div>
                    <span style="font-size:0.75rem; color:#64748b;">{{ $shift->scheduled_time_slot ?: 'Morning' }}</span>
                  </td>
                  <td>
                    @if($shift->partner)
                      <strong style="font-size:0.84rem; color:#1d4ed8;">{{ $shift->partner->company_name }}</strong>
                    @else
                      <span style="font-size:0.78rem; color:#dc2626; font-style:italic;">⚠️ Unassigned</span>
                    @endif
                  </td>
                  <td>
                    @php
                      $shiftPayout = $shift->payout_amount ?: ($booking->payout_per_shift ?: 0);
                    @endphp
                    <strong style="color:#0f2744; font-size:0.84rem;">
                      ৳ {{ number_format($shiftPayout, 2) }}
                    </strong>
                    @if($shift->status === 'completed')
                      <span style="font-size:0.7rem; display:block; color:{{ $shift->payout_status === 'payable' ? '#15803d' : ($shift->payout_status === 'paid' ? '#0284c7' : '#d97706') }}; font-weight:700;">
                        {{ $shift->payout_status === 'payable' ? '✓ Payable' : ($shift->payout_status === 'paid' ? '✓ Paid' : '⏳ Pending Client Bill') }}
                      </span>
                    @endif
                  </td>
                  <td style="text-align:center;">
                    <span class="badge-pill-paces" style="background:{{ $shift->status_badge_color }}22; color:{{ $shift->status_badge_color }}; border:1px solid {{ $shift->status_badge_color }}44; font-size:0.75rem; font-weight:700; padding:2px 8px;">
                      {{ $shift->status_label }}
                    </span>
                  </td>
                  <td style="font-size:0.8rem; color:#475569; max-width:240px;">
                    @if($shift->completion_notes)
                      <div style="color:#15803d;">✓ {{ $shift->completion_notes }}</div>
                    @elseif($shift->cancellation_reason)
                      <div style="color:#dc2626;">⚠️ {{ $shift->cancellation_reason }}</div>
                    @elseif($shift->admin_notes)
                      <div>{{ $shift->admin_notes }}</div>
                    @else
                      <span style="color:#94a3b8;">—</span>
                    @endif
                    @if($shift->payment_collected_by === 'crew' && $shift->collected_amount > 0)
                      <div style="font-size:0.74rem; color:#0f766e; font-weight:700; margin-top:3px;">
                        💰 Collected on site: ৳ {{ number_format($shift->collected_amount, 2) }} ({{ $shift->payment_method ?: 'Cash' }})
                        @if($shift->payment_reference) <span style="font-weight:normal; color:#475569;">• Ref: {{ $shift->payment_reference }}</span> @endif
                      </div>
                    @endif
                  </td>
                  <td style="text-align:right; white-space:nowrap;">
                    @if(!$shift->is_completed && auth('admin')->user()?->hasPermission('bookings.edit'))
                      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.74rem; padding:3px 8px;" onclick="openAdminReassignShiftModal('{{ $shift->id }}', '{{ $shift->shift_number }}', '{{ $shift->partner_id }}', '{{ $shift->scheduled_date->format('Y-m-d') }}', '{{ addslashes($shift->scheduled_time_slot ?? '') }}', '{{ $shiftPayout > 0 ? $shiftPayout : '' }}')">
                        Reassign
                      </button>
                      @if($shift->status !== 'cancelled')
                        <button type="button" class="btn-paces btn-paces-sm" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; font-size:0.74rem; padding:3px 8px; font-weight:700;" onclick="openAdminCancelShiftModal('{{ $shift->id }}', '{{ $shift->shift_number }}')">
                          Cancel
                        </button>
                      @endif
                    @else
                      <span style="font-size:0.75rem; color:#94a3b8;">Locked</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  @if($booking->is_amc)
  <!-- Modal: Admin Add Routine Shift -->
  <div id="adminAddShiftModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target===this) this.style.display='none'">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border:1px solid #e2e8f0; overflow:hidden;">
      <div style="padding:14px 18px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center;">
        <h4 style="margin:0; font-size:1rem; font-weight:700; color:#0f172a;">Add Routine Maintenance Shift</h4>
        <button type="button" onclick="document.getElementById('adminAddShiftModal').style.display='none'" style="background:none; border:none; font-size:1.2rem; color:#64748b; cursor:pointer;">✕</button>
      </div>

      <form action="{{ route('admin.bookings.amc-shifts.store', $booking) }}" method="POST">
        @csrf
        <div style="padding:18px;">
          <div style="margin-bottom:12px;">
            <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Shift Title *</label>
            <input type="text" name="title" class="form-control-paces" value="Periodic Preventive Maintenance & Inspection" required>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
            <div>
              <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Scheduled Date *</label>
              <input type="date" name="scheduled_date" class="form-control-paces" value="{{ date('Y-m-d', strtotime('+1 week')) }}" required>
            </div>
            <div>
              <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Time Slot</label>
              <select name="scheduled_time_slot" class="form-control-paces">
                <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                <option value="Afternoon (12:00 PM - 4:00 PM)">Afternoon (12:00 PM - 4:00 PM)</option>
                <option value="Evening (4:00 PM - 8:00 PM)">Evening (4:00 PM - 8:00 PM)</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom:12px;">
            <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Assigned Crew / Partner</label>
            <select name="partner_id" class="form-control-paces">
              <option value="">-- Use Default Booking Partner ({{ $booking->partner?->company_name ?? 'None' }}) --</option>
              @foreach($enlistedPartners as $p)
                <option value="{{ $p->id }}" {{ $booking->partner_id == $p->id ? 'selected' : '' }}>
                  {{ $p->company_name }} ({{ $p->contact_person }})
                </option>
              @endforeach
            </select>
          </div>

          <div style="margin-bottom:14px;">
            <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Operational Notes (Optional)</label>
            <textarea name="admin_notes" class="form-control-paces" rows="2" placeholder="Specific routine check list or premises access instructions..."></textarea>
          </div>
        </div>

        <div style="padding:12px 18px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="document.getElementById('adminAddShiftModal').style.display='none'" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Schedule Shift</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Admin Cancel Shift -->
  <div id="adminCancelShiftModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target===this) this.style.display='none'">
    <div style="background:#ffffff; border-radius:12px; max-width:440px; width:100%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border:1px solid #e2e8f0; overflow:hidden;">
      <div style="padding:14px 18px; border-bottom:1px solid #fee2e2; background:#fff1f2; display:flex; justify-content:space-between; align-items:center;">
        <h4 style="margin:0; font-size:1rem; font-weight:700; color:#991b1b;">Cancel Shift <span id="adminCancelShiftNum"></span></h4>
        <button type="button" onclick="document.getElementById('adminCancelShiftModal').style.display='none'" style="background:none; border:none; font-size:1.2rem; color:#64748b; cursor:pointer;">✕</button>
      </div>

      <form id="adminCancelShiftForm" method="POST" action="">
        @csrf
        <div style="padding:18px;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Reason for Cancellation *</label>
          <textarea name="cancellation_reason" class="form-control-paces" rows="3" placeholder="Reason (e.g. Rescheduled by client, holiday, redundant inspection)..." required></textarea>
        </div>

        <div style="padding:12px 18px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="document.getElementById('adminCancelShiftModal').style.display='none'" class="btn-paces btn-paces-outline btn-paces-sm">Go Back</button>
          <button type="submit" class="btn-paces btn-paces-sm" style="background:#dc2626; color:#fff; border:none; font-weight:700;">Cancel Shift</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Admin Reassign Shift -->
  <div id="adminReassignShiftModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target===this) this.style.display='none'">
    <div style="background:#ffffff; border-radius:12px; max-width:520px; width:100%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border:1px solid #e2e8f0; position:relative;">
      <div style="padding:14px 18px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; border-top-left-radius:12px; border-top-right-radius:12px;">
        <h4 style="margin:0; font-size:1rem; font-weight:700; color:#0f172a;">Reassign Shift <span id="adminReassignShiftNum"></span></h4>
        <button type="button" onclick="document.getElementById('adminReassignShiftModal').style.display='none'" style="background:none; border:none; font-size:1.2rem; color:#64748b; cursor:pointer;">✕</button>
      </div>

      <form id="adminReassignShiftForm" method="POST" action="">
        @csrf
        <div style="padding:18px;">
          <!-- 1. Searchable Crew Dropdown -->
          <div style="margin-bottom:14px;">
            <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">
              Assigned Field Crew / Lead Partner
              <span style="font-weight:400; color:#64748b;">(Filtered by Service &amp; Area)</span>
            </label>
            <div style="position:relative;" id="crewDropdownWrapper_shift">
              <input type="hidden" name="partner_id" id="partner_id_shift" value="" required>
              <input 
                type="text" 
                id="crewInput_shift" 
                class="form-control-paces" 
                placeholder="Select approved crew..." 
                autocomplete="off"
                onfocus="openCrewDropdown('shift')"
                oninput="filterCrewOptions('shift')"
                required
              >

              <div id="crewDropdownMenu_shift" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; max-height:220px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.15); z-index:100000; padding:4px 0;">
                <div style="padding:6px 12px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">
                  Select Enlisted Partner or Field Technician ({{ $fieldCrews->count() }} available)
                </div>
                <div id="crewListItems_shift">
                  @forelse($fieldCrews as $crew)
                    @php
                      $compOrRole = $crew->company_name ?: $crew->role;
                      $crewFullVal = "{$crew->name} ({$compOrRole}) - {$crew->phone}";
                      $crewPartnerId = $crew->partner_id ?? '';
                      $crewWarning = $crew->warning_msg ?? '';
                      $crewEarningModel = $crew->earning_model ?? 'commission';
                      $crewPayoutRate = (float) ($crew->payout_rate ?? 0);
                      $crewCommissionRate = (float) ($crew->commission_rate ?? 15);
                      $crewFormattedRate = $crew->formatted_rate ?? '15.00%';
                    @endphp
                    <div class="crew-option-item" 
                         data-val="{{ strtolower($crewFullVal) }}"
                         data-partner-id="{{ $crewPartnerId }}"
                         data-full-val="{{ $crewFullVal }}"
                         data-earning-model="{{ $crewEarningModel }}"
                         data-payout-rate="{{ $crewPayoutRate }}"
                         data-commission-rate="{{ $crewCommissionRate }}"
                         data-formatted-rate="{{ $crewFormattedRate }}"
                         onclick="selectCrew('shift', '{{ addslashes($crewFullVal) }}', true, '{{ $crewPartnerId }}', '{{ addslashes($crewWarning) }}', '{{ $crewEarningModel }}', {{ $crewPayoutRate }}, {{ $crewCommissionRate }}, '{{ addslashes($crewFormattedRate) }}')"
                         style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                         onmouseover="this.style.background='#eff6ff'" 
                         onmouseout="this.style.background='transparent'">
                      <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="font-weight:700; font-size:0.85rem; color:#1e293b;">
                          {{ $crew->name }}
                          @if(!empty($crew->match_badge))
                            <span style="font-size:0.68rem; font-weight:700; color:#047857; background:#ecfdf5; border:1px solid #a7f3d0; padding:1px 6px; border-radius:4px; margin-left:4px;">
                              {{ $crew->match_badge }}
                            </span>
                          @endif
                        </div>
                        @if(!empty($crew->avail_label))
                          <span style="font-size:0.72rem; font-weight:600; color:{{ $crew->avail_color }}; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 6px; border-radius:4px;">
                            {{ $crew->avail_label }}
                          </span>
                        @endif
                      </div>
                      <div style="font-size:0.75rem; color:#64748b; display:flex; justify-content:space-between; margin-top:2px;">
                        <span>{{ $crew->role }}</span>
                        <span style="color:#0284c7; font-weight:500;">📞 {{ $crew->phone }}</span>
                      </div>
                    </div>
                  @empty
                    <div style="padding:14px 12px; text-align:center; font-size:0.8rem; color:#dc2626; background:#fef2f2;">
                      ⚠️ No crews found matching this service and location.
                    </div>
                  @endforelse
                </div>
              </div>
            </div>
            <span style="font-size:0.72rem; color:#0f766e; display:block; margin-top:4px;">
              Whichever crew is assigned will receive the payout for this shift upon completion.
            </span>
          </div>

          <!-- 2. Scheduled Date & Time Slot -->
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px;">
            <div>
              <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Scheduled Date *</label>
              <input type="date" name="scheduled_date" id="adminReassignDateInput" class="form-control-paces" required>
            </div>
            <div>
              <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Arrival Window / Time Slot</label>
              <select name="scheduled_time_slot" id="adminReassignSlotSelect" class="form-control-paces">
                <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                <option value="Afternoon (12:00 PM - 4:00 PM)">Afternoon (12:00 PM - 4:00 PM)</option>
                <option value="Evening (4:00 PM - 8:00 PM)">Evening (4:00 PM - 8:00 PM)</option>
              </select>
            </div>
          </div>

          <!-- 3. Crew Payout Per Shift (Auto-calculated from Remuneration Agreement) -->
          <div style="margin-bottom:14px;">
            <label class="form-label-paces" style="font-size:0.82rem; font-weight:600; display:flex; justify-content:space-between; align-items:center;">
              <span>Crew Payout Per Shift (৳)</span>
              <span style="font-size:0.7rem; color:#64748b; font-weight:500;">🔒 Agreement Auto-calculated</span>
            </label>
            <input type="hidden" name="payout_amount" id="adminReassignPayoutHidden" value="">
            <div style="position:relative;">
              <input 
                type="text" 
                id="adminReassignPayoutDisplay" 
                class="form-control-paces" 
                readonly 
                style="background:#f1f5f9; cursor:not-allowed; font-weight:700; color:#0f172a;" 
                placeholder="0.00"
              >
              <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:0.72rem; color:#64748b; font-weight:600;">🔒 Auto-calculated</span>
            </div>
            <span id="adminReassignPayoutNote" style="font-size:0.72rem; color:#64748b; display:block; margin-top:4px;">
              Select an approved field crew to automatically calculate their agreed remuneration rate.
            </span>
          </div>

          <!-- 4. Operational Notes -->
          <div style="margin-bottom:8px;">
            <label class="form-label-paces" style="font-size:0.82rem; font-weight:600;">Operational Notes / Instructions</label>
            <textarea name="admin_notes" id="adminReassignNotesInput" class="form-control-paces" rows="2" placeholder="Instructions or reason for reassignment..."></textarea>
          </div>
        </div>

        <div style="padding:12px 18px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:8px; border-bottom-left-radius:12px; border-bottom-right-radius:12px;">
          <button type="button" onclick="document.getElementById('adminReassignShiftModal').style.display='none'" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Confirm Reassignment</button>
        </div>
      </form>
    </div>
  </div>
  @endif

  <!-- ========================================================================= -->
  <!-- MODALS (Image Lightbox, Collect AMC Bill, Payout Settlement)               -->
  <!-- ========================================================================= -->

  <!-- Interactive Image Lightbox Modal with Zoom & Pan -->
  <div id="imageLightboxModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.92); z-index:99999; backdrop-filter:blur(6px); align-items:center; justify-content:center; flex-direction:column; padding:20px;" onclick="closeLightboxOnBackdrop(event)">
    <div style="position:absolute; top:18px; right:20px; display:flex; align-items:center; gap:10px; z-index:100000;">
      <div style="background:rgba(255,255,255,0.15); border-radius:8px; padding:4px 8px; display:flex; align-items:center; gap:8px; color:#fff; font-size:0.85rem;">
        <button type="button" onclick="zoomLightbox(-0.25)" style="background:none; border:none; color:#fff; cursor:pointer; font-size:1.2rem; font-weight:700; padding:2px 8px;">−</button>
        <span id="lightboxZoomLevel" style="font-weight:600; min-width:44px; text-align:center;">100%</span>
        <button type="button" onclick="zoomLightbox(0.25)" style="background:none; border:none; color:#fff; cursor:pointer; font-size:1.2rem; font-weight:700; padding:2px 8px;">+</button>
      </div>
      <button type="button" onclick="closeImageLightbox()" style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:36px; height:36px; border-radius:50%; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">✕</button>
    </div>

    <div style="position:absolute; top:20px; left:24px; color:#f8fafc; font-weight:600; font-size:0.95rem; z-index:100000;" id="lightboxCaption">
      Problem Photo
    </div>

    <div style="width:100%; height:100%; max-width:92vw; max-height:86vh; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; cursor:grab;" id="lightboxViewport">
      <img id="lightboxImage" src="" alt="Zoomable View" style="max-width:100%; max-height:100%; object-fit:contain; transform-origin:center center; transition:transform 0.15s ease-out; user-select:none; pointer-events:none;">
    </div>
  </div>

  <!-- Modal: Record AMC Bill Collection -->
  <div id="collectAmcBillModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); border:1px solid var(--paces-border); overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <div>
          <h3 style="margin:0; font-size:1.05rem; font-weight:600; color:var(--paces-text-dark);">Record AMC Bill Payment</h3>
          <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;" id="modalInvoiceSubtitle">Invoice Collection</div>
        </div>
        <button type="button" onclick="closeCollectBillModal()" style="background:none; border:none; color:#64748b; font-size:1.2rem; cursor:pointer;">✕</button>
      </div>

      <form id="collectAmcBillForm" method="POST" action="" style="padding:1.5rem;">
        @csrf
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.82rem; font-weight:600; color:#065f46;">Amount to Collect:</span>
          <strong style="font-size:1.15rem; color:#047857;" id="modalInvoiceAmount">৳ 0.00</strong>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Payment Method *</label>
          <select name="payment_method" class="form-control-paces" required>
            <option value="Bank Transfer">Bank Transfer / Cheque</option>
            <option value="bKash">bKash (Merchant / Personal)</option>
            <option value="Nagad">Nagad</option>
            <option value="Cash">Cash/ Field Handover</option>
            <option value="POS / Card">Credit / Debit Card (POS)</option>
          </select>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Transaction Reference</label>
          <input type="text" name="payment_reference" placeholder="e.g. TrxID 9H82J1" class="form-control-paces">
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Payment Date *</label>
          <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" class="form-control-paces" required>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Receipt Notes</label>
          <textarea name="notes" rows="2" placeholder="Received notes..." class="form-control-paces"></textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeCollectBillModal()" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:600; padding:6px 16px;">
            ✓ Confirm Bill Collection
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Record Direct Payment to FIX Office -->
  <div id="collectOfficePaymentModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;" onclick="if(event.target===this) closeCollectOfficePaymentModal()">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); border:1px solid var(--paces-border); overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <div>
          <h3 style="margin:0; font-size:1.05rem; font-weight:700; color:var(--paces-text-dark);">Record Direct Office Payment</h3>
          <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">AMC Contract #{{ $booking->booking_number }} • Client: {{ $booking->customer_name }}</div>
        </div>
        <button type="button" onclick="closeCollectOfficePaymentModal()" style="background:none; border:none; color:#64748b; font-size:1.2rem; cursor:pointer;">✕</button>
      </div>

      <form action="{{ route('admin.bookings.amc-office-payment.store', $booking) }}" method="POST" style="padding:1.5rem;">
        @csrf
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.82rem; font-weight:600; color:#065f46;">Current Outstanding Due:</span>
          <strong style="font-size:1.15rem; color:#047857;">৳ {{ number_format($booking->amc_remaining_due, 2) }}</strong>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Amount Received at Office (৳) *</label>
          <input type="number" step="0.01" min="1" name="amount" value="{{ $booking->amc_remaining_due > 0 ? $booking->amc_remaining_due : ($booking->recurring_amount ?: '') }}" class="form-control-paces" required style="font-weight:700; font-size:1.05rem; color:#0f2744;">
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Payment Channel / Method *</label>
          <select name="payment_method" class="form-control-paces" required>
            <option value="Bank Transfer">Bank Transfer / Cheque</option>
            <option value="bKash">bKash (Merchant / Personal)</option>
            <option value="Nagad">Nagad</option>
            <option value="Cash at Office">Cash at Office</option>
            <option value="POS / Card">Credit / Debit Card (POS)</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Transaction / Cheque / Deposit Slip Reference</label>
          <input type="text" name="payment_reference" placeholder="e.g. TrxID 9H82J1 / Cheque #882190 / Slip #441" class="form-control-paces">
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Payment Received Date *</label>
          <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" class="form-control-paces" required>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Receipt Notes</label>
          <textarea name="notes" rows="2" placeholder="e.g. Settle advance payment / quarterly tranche..." class="form-control-paces"></textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeCollectOfficePaymentModal()" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#0f766e; border-color:#0f766e; font-weight:700; padding:6px 16px;">
            ✓ Confirm Office Payment
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Re-start Work Order -->
  <div id="restartWorkOrderModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:9999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;" onclick="if(event.target===this) this.style.display='none'">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 20px 35px rgba(0,0,0,0.18); border:1px solid var(--paces-border); overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div style="width:34px; height:34px; border-radius:50%; background:#f0fdf4; color:#16a34a; display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:700;">
            🔄
          </div>
          <div>
            <h3 style="margin:0; font-size:1.05rem; font-weight:700; color:var(--paces-text-dark);">Re-start Work Order</h3>
            <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">Order #{{ $booking->booking_number }}</div>
          </div>
        </div>
        <button type="button" onclick="document.getElementById('restartWorkOrderModal').style.display='none'" style="background:none; border:none; color:#64748b; font-size:1.2rem; cursor:pointer;">✕</button>
      </div>

      <form action="{{ route('admin.bookings.restart', $booking) }}" method="POST" style="padding:1.5rem;">
        @csrf
        <p style="font-size:0.84rem; color:#475569; margin:0 0 14px; line-height:1.5;">
          Restarting will return this cancelled work order back to <strong>Pending Review</strong>. You will be able to discuss with the client, confirm pricing, and dispatch to a field crew.
        </p>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:0.78rem; color:#334155; line-height:1.45;">
          ✓ Order status resets to <strong>Pending Review</strong><br>
          ✓ Previous cancellation locks and crew assignments will be cleared<br>
          ✓ Audit log will record the restart event
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Restart Reason / Note (Optional)</label>
          <textarea name="restart_reason" rows="2" placeholder="e.g. Client requested to resume work order / rescheduled after inspection..." class="form-control-paces"></textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="document.getElementById('restartWorkOrderModal').style.display='none'" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#16a34a; border-color:#16a34a; font-weight:700; padding:7px 18px;">
            ✓ Confirm & Re-start Order
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- JAVASCRIPT CONTROLLERS                                                    -->
  <!-- ========================================================================= -->
  <script>
    // Tab switching controller
    function switchBookingTab(tabId) {
      document.querySelectorAll('.booking-tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-tab') === tabId);
      });
      document.querySelectorAll('.booking-tab-pane').forEach(pane => {
        pane.classList.toggle('active', pane.id === tabId);
      });
      if (history.replaceState) {
        history.replaceState(null, null, '#' + tabId.replace('tab-', ''));
      }
    }

    // Pricing input toggles
    function togglePricingInput(val) {
      const row = document.getElementById('priceAmountRow');
      if (row) {
        row.style.display = (val === 'later') ? 'none' : 'flex';
      }
    }

    function toggleCostType(isEst) {
      const hidden = document.getElementById('costTypeHidden');
      if (hidden) hidden.value = isEst ? 'estimated' : 'final';
    }

    function togglePricingInputEdit(val) {
      const row = document.getElementById('priceAmountRow_edit');
      if (row) {
        row.style.display = (val === 'later') ? 'none' : 'flex';
      }
    }

    function toggleCostTypeEdit(isEst) {
      const hidden = document.getElementById('costTypeHidden_edit');
      if (hidden) hidden.value = isEst ? 'estimated' : 'final';
    }

    // Custom Task & Outreach Forms
    function toggleCustomTaskBox() {
      const box = document.getElementById('customTaskBox');
      if (box) box.style.display = box.style.display === 'none' ? 'block' : 'none';
    }

    // Searchable Crew Dropdown Logic
    function openCrewDropdown(prefix) {
      const menu = document.getElementById('crewDropdownMenu_' + prefix);
      if (menu) menu.style.display = 'block';
    }

    function closeCrewDropdown(prefix) {
      const menu = document.getElementById('crewDropdownMenu_' + prefix);
      if (menu) menu.style.display = 'none';
    }

    function filterCrewOptions(prefix) {
      const input = document.getElementById('crewInput_' + prefix);
      const query = (input?.value || '').toLowerCase().trim();
      const items = document.querySelectorAll('#crewListItems_' + prefix + ' .crew-option-item');

      items.forEach(el => {
        const val = el.getAttribute('data-val') || '';
        el.style.display = val.includes(query) ? 'block' : 'none';
      });
    }

    function selectCrew(prefix, value, autoChangeStatus, partnerId, warningMsg, earningModel, payoutRate, commissionRate, formattedRate, customPayout) {
      const input = document.getElementById('crewInput_' + prefix);
      const partnerInput = document.getElementById('partner_id_' + prefix);
      if (input) input.value = value;
      if (partnerInput) partnerInput.value = partnerId;
      closeCrewDropdown(prefix);

      if (prefix === 'amc') {
        window.selectedAmcCrewAgreement = {
          partnerId: partnerId,
          earningModel: earningModel || 'commission',
          payoutRate: parseFloat(payoutRate) || 0,
          commissionRate: parseFloat(commissionRate) || 15,
          formattedRate: formattedRate || '15.00%'
        };
        recalculateAmcAmounts();
      }

      if (prefix === 'shift') {
        recalculateShiftModalPayout(partnerId, earningModel, payoutRate, commissionRate, formattedRate, customPayout);
      }
    }

    // Image Lightbox
    let currentZoom = 1;
    function openImageLightbox(src, caption) {
      const modal = document.getElementById('imageLightboxModal');
      const img = document.getElementById('lightboxImage');
      const cap = document.getElementById('lightboxCaption');
      if (modal && img) {
        img.src = src;
        if (cap) cap.textContent = caption || 'Site Photo';
        modal.style.display = 'flex';
        resetLightboxZoom();
      }
    }
    function closeImageLightbox() {
      const modal = document.getElementById('imageLightboxModal');
      if (modal) modal.style.display = 'none';
    }
    function closeLightboxOnBackdrop(e) {
      if (e.target.id === 'imageLightboxModal' || e.target.id === 'lightboxViewport') {
        closeImageLightbox();
      }
    }
    function zoomLightbox(delta) {
      currentZoom = Math.min(Math.max(currentZoom + delta, 0.5), 3);
      const img = document.getElementById('lightboxImage');
      const lvl = document.getElementById('lightboxZoomLevel');
      if (img) img.style.transform = `scale(${currentZoom})`;
      if (lvl) lvl.textContent = Math.round(currentZoom * 100) + '%';
    }
    function resetLightboxZoom() {
      currentZoom = 1;
      const img = document.getElementById('lightboxImage');
      const lvl = document.getElementById('lightboxZoomLevel');
      if (img) img.style.transform = 'scale(1)';
      if (lvl) lvl.textContent = '100%';
    }

    // Admin Reassign Shift Modal
    function openAdminReassignShiftModal(shiftId, shiftNumber, partnerId, scheduledDate, timeSlot, payoutAmount) {
      const modal = document.getElementById('adminReassignShiftModal');
      const form = document.getElementById('adminReassignShiftForm');
      if (form) {
        form.action = "{{ url('admin/bookings/' . $booking->id . '/amc-shifts') }}/" + shiftId + "/reassign";
      }
      const numSpan = document.getElementById('adminReassignShiftNum');
      if (numSpan) numSpan.textContent = '#' + shiftNumber;

      const dateInput = document.getElementById('adminReassignDateInput');
      if (dateInput) dateInput.value = scheduledDate || '';
      const slotSelect = document.getElementById('adminReassignSlotSelect');
      if (slotSelect) slotSelect.value = timeSlot || 'Morning (9:00 AM - 12:00 PM)';

      // Auto-populate crew & agreement payout
      if (partnerId) {
        const crewItem = document.querySelector('#crewListItems_shift .crew-option-item[data-partner-id="' + partnerId + '"]');
        if (crewItem) {
          const fullVal = crewItem.getAttribute('data-full-val');
          const model = crewItem.getAttribute('data-earning-model');
          const rate = parseFloat(crewItem.getAttribute('data-payout-rate')) || 0;
          const comm = parseFloat(crewItem.getAttribute('data-commission-rate')) || 15;
          const formatted = crewItem.getAttribute('data-formatted-rate') || '15.00%';
          selectCrew('shift', fullVal, false, partnerId, '', model, rate, comm, formatted, payoutAmount);
        } else {
          const partnerInput = document.getElementById('partner_id_shift');
          const crewInput = document.getElementById('crewInput_shift');
          if (partnerInput) partnerInput.value = partnerId;
          if (crewInput) crewInput.value = 'Assigned Partner #' + partnerId;
          recalculateShiftModalPayout(partnerId, 'commission', 0, 15, '15.00%', payoutAmount);
        }
      } else {
        const partnerInput = document.getElementById('partner_id_shift');
        const crewInput = document.getElementById('crewInput_shift');
        if (partnerInput) partnerInput.value = '';
        if (crewInput) crewInput.value = '';
        const payoutDisplay = document.getElementById('adminReassignPayoutDisplay');
        const payoutHidden = document.getElementById('adminReassignPayoutHidden');
        const payoutNote = document.getElementById('adminReassignPayoutNote');
        if (payoutDisplay) payoutDisplay.value = '';
        if (payoutHidden) payoutHidden.value = '';
        if (payoutNote) payoutNote.textContent = 'Select an approved field crew to automatically calculate their agreed remuneration rate.';
      }

      if (modal) modal.style.display = 'flex';
    }

    function recalculateShiftModalPayout(partnerId, model, rate, commission, formattedRate, customPayout) {
      const payoutDisplay = document.getElementById('adminReassignPayoutDisplay');
      const payoutHidden = document.getElementById('adminReassignPayoutHidden');
      const payoutNote = document.getElementById('adminReassignPayoutNote');
      if (!payoutDisplay || !payoutHidden) return;

      const budget = {{ (float) ($booking->final_cost ?: $booking->estimated_cost) }};
      const shiftsCount = {{ max(1, $booking->amcShifts->count() ?: 12) }};
      const shiftValue = shiftsCount > 0 ? (budget / shiftsCount) : 0;

      let payout = 0;
      let explanation = '';

      if (model === 'per_job') {
        payout = rate;
        explanation = 'Fixed Per Job Agreement: ৳ ' + rate.toFixed(2) + ' per shift';
      } else if (model === 'daily') {
        payout = rate;
        explanation = 'Daily Rate Agreement: ৳ ' + rate.toFixed(2) + ' per shift';
      } else if (model === 'hourly') {
        payout = rate * 2;
        explanation = 'Hourly Rate Agreement: ৳ ' + rate.toFixed(2) + '/hr × 2 hrs = ৳ ' + payout.toFixed(2);
      } else if (model === 'monthly') {
        payout = shiftsCount > 0 ? (rate / shiftsCount) : rate;
        explanation = 'Monthly Retainer: ৳ ' + rate.toFixed(2) + ' ÷ ' + shiftsCount + ' shifts = ৳ ' + payout.toFixed(2);
      } else {
        payout = (shiftValue * commission) / 100;
        explanation = commission + '% Commission Agreement on shift value (৳ ' + shiftValue.toFixed(2) + ' × ' + commission + '%)';
      }

      if (customPayout !== undefined && customPayout !== null && customPayout !== '' && parseFloat(customPayout) > 0) {
        payout = parseFloat(customPayout);
      }

      payoutDisplay.value = payout.toFixed(2);
      payoutHidden.value = payout.toFixed(2);
      if (payoutNote) {
        payoutNote.innerHTML = '🔒 Auto-calculated from crew agreement: <strong>' + explanation + '</strong>';
      }
    }

    // Admin Cancel Shift Modal
    function openAdminCancelShiftModal(shiftId, shiftNumber) {
      const modal = document.getElementById('adminCancelShiftModal');
      const form = document.getElementById('adminCancelShiftForm');
      if (form) {
        form.action = "{{ url('admin/bookings/' . $booking->id . '/amc-shifts') }}/" + shiftId + "/cancel";
      }
      const numSpan = document.getElementById('adminCancelShiftNum');
      if (numSpan) numSpan.textContent = '#' + shiftNumber;
      if (modal) modal.style.display = 'flex';
    }

    // AMC Bill Modal
    function openCollectBillModal(invoiceId, invoiceNumber, amountFormatted) {
      const modal = document.getElementById('collectAmcBillModal');
      const form = document.getElementById('collectAmcBillForm');
      const sub = document.getElementById('modalInvoiceSubtitle');
      const amt = document.getElementById('modalInvoiceAmount');
      if (modal && form) {
        form.action = "{{ url('admin/bookings/' . $booking->id . '/amc-bills') }}/" + invoiceId + "/collect";
        if (sub) sub.textContent = "Invoice #" + invoiceNumber + " • Work Order #{{ $booking->booking_number }}";
        if (amt) amt.textContent = "৳ " + amountFormatted;
        modal.style.display = 'flex';
      }
    }
    function closeCollectBillModal() {
      const modal = document.getElementById('collectAmcBillModal');
      if (modal) modal.style.display = 'none';
    }

    // Office Payment Modal
    function openCollectOfficePaymentModal() {
      const modal = document.getElementById('collectOfficePaymentModal');
      if (modal) modal.style.display = 'flex';
    }
    function closeCollectOfficePaymentModal() {
      const modal = document.getElementById('collectOfficePaymentModal');
      if (modal) modal.style.display = 'none';
    }

    // Initial crew agreement info for active or assigned partner
    @php
      $initialPartner = $booking->partner;
    @endphp
    window.selectedAmcCrewAgreement = {
      partnerId: "{{ $booking->partner_id }}",
      earningModel: "{{ $initialPartner?->earning_model ?? 'commission' }}",
      payoutRate: {{ (float) ($initialPartner?->payout_rate ?? 0) }},
      commissionRate: {{ (float) ($initialPartner?->commission_rate ?? 15) }},
      formattedRate: "{{ $initialPartner?->formatted_rate ?? '15.00%' }}"
    };

    function getTenureMonths() {
      const tenureSelect = document.getElementById('contractTenureSelect');
      const tenureVal = tenureSelect ? tenureSelect.value : '1 Year';
      if (tenureVal.includes('3 Month')) return 3;
      if (tenureVal.includes('6 Month')) return 6;
      if (tenureVal.includes('1 Year')) return 12;
      if (tenureVal.includes('2 Year')) return 24;
      if (tenureVal.includes('3 Year')) return 36;
      if (tenureVal.includes('Lifetime')) return 12;

      const startInput = document.getElementById('contractStartDateInput');
      const endInput = document.getElementById('contractEndDateInput');
      if (startInput && endInput && startInput.value && endInput.value) {
        const d1 = new Date(startInput.value);
        const d2 = new Date(endInput.value);
        const diffM = (d2.getFullYear() - d1.getFullYear()) * 12 + (d2.getMonth() - d1.getMonth());
        if (diffM > 0) return diffM;
      }
      const match = tenureVal.match(/(\d+)\s*(month|year)/i);
      if (match) {
        const num = parseInt(match[1]);
        return match[2].toLowerCase().startsWith('year') ? num * 12 : num;
      }
      return 12;
    }

    function recalculateAmcAmounts() {
      const costInput = document.getElementById('contractCostInput');
      const recurringInput = document.getElementById('recurringAmountInput');
      const cycleSelect = document.getElementById('amcBillingCycleSelect');
      const cycleHint = document.getElementById('recurringCycleHint');

      const budget = costInput ? (parseFloat(costInput.value) || 0) : 0;
      const cycle = cycleSelect ? cycleSelect.value : 'monthly';
      const tenureMonths = getTenureMonths();

      let recurringAmount = 0;
      let hint = '';

      if (cycle === 'one_time') {
        recurringAmount = budget;
        hint = 'Full total contract budget billed upfront in a single invoice (৳ ' + budget.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ').';
      } else if (cycle === 'monthly') {
        const months = Math.max(1, tenureMonths);
        recurringAmount = budget / months;
        hint = 'Auto-calculated: ৳ ' + budget.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ÷ ' + months + ' months = ৳ ' + recurringAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' per month.';
      } else if (cycle === 'half_yearly') {
        const cycles = Math.max(1, Math.round(tenureMonths / 6));
        recurringAmount = budget / cycles;
        hint = 'Auto-calculated: ৳ ' + budget.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ÷ ' + cycles + ' half-yearly cycles = ৳ ' + recurringAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' per 6 months.';
      } else if (cycle === 'yearly') {
        const cycles = Math.max(1, Math.round(tenureMonths / 12));
        recurringAmount = budget / cycles;
        hint = 'Auto-calculated: ৳ ' + budget.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ÷ ' + cycles + ' years = ৳ ' + recurringAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' per year.';
      } else if (cycle === 'daily') {
        const shifts = Math.max(1, tenureMonths);
        recurringAmount = budget / shifts;
        hint = 'Auto-calculated: ৳ ' + budget.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ÷ ' + shifts + ' routine shifts = ৳ ' + recurringAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' per shift.';
      }

      if (recurringInput) {
        recurringInput.value = recurringAmount > 0 ? recurringAmount.toFixed(2) : '0.00';
      }
      if (cycleHint) {
        cycleHint.innerHTML = '🔒 ' + hint;
      }

      recalculateCrewPayout(budget, tenureMonths);
    }

    function recalculateCrewPayout(budget, tenureMonths) {
      const payoutHidden = document.getElementById('payoutPerShiftHidden');
      const payoutDisplay = document.getElementById('payoutPerShiftDisplay');
      const payoutNote = document.getElementById('payoutPerShiftNote');

      const partnerId = document.getElementById('partner_id_amc')?.value;
      if (!partnerId || !window.selectedAmcCrewAgreement) {
        if (payoutDisplay) payoutDisplay.value = '0.00';
        if (payoutHidden) payoutHidden.value = '0.00';
        if (payoutNote) payoutNote.innerHTML = 'Select an approved field crew above to calculate their agreed remuneration payout.';
        return;
      }

      const agreement = window.selectedAmcCrewAgreement;
      const model = agreement.earningModel || 'commission';
      const rate = parseFloat(agreement.payoutRate) || 0;
      const commission = parseFloat(agreement.commissionRate) || (rate > 0 ? rate : 15);
      const shiftsCount = Math.max(1, tenureMonths || 12);
      const shiftValue = shiftsCount > 0 ? (budget / shiftsCount) : 0;

      let payout = 0;
      let explanation = '';

      if (model === 'per_job') {
        payout = rate;
        explanation = 'Fixed Per Job Agreement: ৳ ' + rate.toFixed(2) + ' per shift';
      } else if (model === 'daily') {
        payout = rate;
        explanation = 'Daily Rate Agreement: ৳ ' + rate.toFixed(2) + ' per shift';
      } else if (model === 'hourly') {
        payout = rate * 2;
        explanation = 'Hourly Rate Agreement: ৳ ' + rate.toFixed(2) + '/hr × 2 hrs = ৳ ' + payout.toFixed(2);
      } else if (model === 'monthly') {
        payout = shiftsCount > 0 ? (rate / shiftsCount) : rate;
        explanation = 'Monthly Retainer: ৳ ' + rate.toFixed(2) + ' ÷ ' + shiftsCount + ' shifts = ৳ ' + payout.toFixed(2);
      } else {
        // Commission %
        payout = (shiftValue * commission) / 100;
        explanation = commission + '% Commission Agreement on shift value (৳ ' + shiftValue.toFixed(2) + ' × ' + commission + '%)';
      }

      if (payoutDisplay) {
        payoutDisplay.value = payout.toFixed(2);
      }
      if (payoutHidden) {
        payoutHidden.value = payout.toFixed(2);
      }
      if (payoutNote) {
        payoutNote.innerHTML = '🔒 Auto-calculated from crew agreement: <strong>' + explanation + '</strong>';
      }
    }

    // Close crew dropdown when clicking outside
    document.addEventListener('click', function(e) {
      ['accept', 'edit', 'amc', 'shift'].forEach(prefix => {
        const wrapper = document.getElementById('crewDropdownWrapper_' + prefix);
        if (wrapper && !wrapper.contains(e.target)) {
          closeCrewDropdown(prefix);
        }
      });
    });

    // Check URL hash and trigger initial recalculation on page load
    document.addEventListener('DOMContentLoaded', function() {
      recalculateAmcAmounts();

      const rawHash = window.location.hash.replace('#', '');
      let activeTabSet = false;
      if (rawHash) {
        const tabId = rawHash.startsWith('tab-') ? rawHash : 'tab-' + rawHash;
        if (document.getElementById(tabId)) {
          switchBookingTab(tabId);
          activeTabSet = true;
        }
      }

      const urlParams = new URLSearchParams(window.location.search);
      const collectInvoiceId = urlParams.get('collect_invoice');
      if (collectInvoiceId) {
        switchBookingTab('tab-amc-contract');
        activeTabSet = true;
        setTimeout(function() {
          const btn = document.querySelector(`button[onclick*="openCollectBillModal('${collectInvoiceId}'"]`);
          if (btn) {
            btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            btn.click();
          }
        }, 150);
      }

      if (!activeTabSet) {
        switchBookingTab('tab-overview');
      }
    });
  </script>
</x-layouts.admin>
