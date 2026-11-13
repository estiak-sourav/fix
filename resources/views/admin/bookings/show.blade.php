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
          <span style="background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:12px;">
            • AMC Agreement
          </span>
        @else
          <span style="background:#f1f5f9; color:#475569; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:12px;">
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
        @else
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
            <div style="font-size:0.8rem; color:#475569; margin-top:3px;">
              <strong>Latest Audit Action:</strong> {{ $latestLog->changedByAdmin?->name ?? ($latestLog->changedByUser?->name ?? 'System Automated') }}
              updated to <em>{{ ucfirst(str_replace('_', ' ', $latestLog->to_status)) }}</em> ({{ $latestLog->created_at->diffForHumans() }})
              @if($latestLog->comment)
                • <span style="color:#0f172a;">"{{ Str::limit($latestLog->comment, 95) }}"</span>
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

  @if($booking->status !== 'cancelled')
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
        <span style="background:#fee2e2; color:#b91c1c; font-size:0.75rem; font-weight:700; padding:2px 8px; border-radius:10px;">Cancelled</span>
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

    @if(auth('admin')->user()?->hasPermission('bookings.edit'))
      <button type="button" class="booking-tab-btn" data-tab="tab-manage-dispatch" onclick="switchBookingTab('tab-manage-dispatch')">
        <span>⚙️ Manage & Edit Dispatch</span>
      </button>
    @endif

    <button type="button" class="booking-tab-btn" data-tab="tab-audit-logs" onclick="switchBookingTab('tab-audit-logs')">
      <span>📜 Operational Audit Timeline</span>
      <span style="background:#f1f5f9; color:#475569; font-size:0.72rem; padding:2px 8px; border-radius:10px; font-weight:700;">
        {{ $booking->statusLogs->count() }}
      </span>
    </button>

    <!-- AMC TAB: Context-aware label (Active AMC vs Convert to AMC) -->
    <button type="button" class="booking-tab-btn" data-tab="tab-amc-contract" onclick="switchBookingTab('tab-amc-contract')">
      @if($booking->contract_status === 'active')
        <span>🏢 Active AMC Contract</span>
        <span style="background:#dcfce7; color:#166534; font-size:0.7rem; padding:2px 6px; border-radius:4px; font-weight:700;">Active</span>
      @elseif($booking->is_amc)
        <span>🏢 AMC Agreement</span>
      @else
        <span>🏢 Convert to AMC</span>
      @endif
    </button>
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
              <img src="{{ $booking->user->avatar_url }}" alt="{{ $booking->customer_name }}" style="width:44px; height:44px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
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
        @else
          <!-- CURRENT OPERATIONAL EXECUTION CARD -->
          <div class="card-paces" style="margin-bottom:1.5rem;">
            <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--paces-border); padding-bottom:1rem;">
              <div>
                <h3 class="card-title-paces" style="margin:0; font-size:1.05rem;">Current Execution State</h3>
                <div style="font-size:0.78rem; color:var(--paces-text-muted);">Real-time dispatch and field delivery status</div>
              </div>

              @if(auth('admin')->user()?->hasPermission('bookings.edit'))
                <button type="button" onclick="switchBookingTab('tab-manage-dispatch')" class="btn-paces btn-paces-outline btn-paces-sm" style="font-weight:600;">
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
  <!-- TAB 2: MANAGE & EDIT DISPATCH                                             -->
  <!-- ========================================================================= -->
  @if(auth('admin')->user()?->hasPermission('bookings.edit'))
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
        <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.78rem; padding:4px 12px; border-radius:12px; font-weight:700;">
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
              <span style="font-size:0.72rem; color:var(--paces-primary);">{{ $booking->amcPlan->tier }} Tier</span>
            @endif
          </div>

          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Contract Period</div>
            <div style="font-size:0.88rem; font-weight:600; color:var(--paces-text-dark); margin-top:2px;">
              {{ $booking->contract_start_date?->format('M d, Y') ?? 'Start' }} – {{ $booking->contract_end_date?->format('M d, Y') ?? 'Ongoing' }}
            </div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted);">{{ $booking->days_remaining_in_contract }} days remaining</div>
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

      <!-- AMC Activation / Update Form -->
      @if(auth('admin')->user()?->hasPermission('bookings.edit'))
        <div style="border-top:1px dashed var(--paces-border); padding-top:1.25rem;">
          <h4 style="margin:0 0 8px 0; font-size:0.95rem; font-weight:700; color:#1e293b;">
            {{ $booking->contract_status === 'active' ? 'Update AMC Contract Details' : 'Activate AMC Contract for this Customer' }}
          </h4>
          <p style="font-size:0.8rem; color:var(--paces-text-muted); margin-bottom:1rem;">
            Select an AMC package, contract billing cycle, and upload signed agreement document scan.
          </p>

          <form action="{{ route('admin.bookings.activate-amc', $booking) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
              <div>
                <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Select AMC Package *</label>
                <select name="amc_plan_id" class="form-control-paces" required>
                  <option value="">-- Choose AMC Package --</option>
                  @foreach($amcPlans as $plan)
                    <option value="{{ $plan->id }}" {{ (old('amc_plan_id', $booking->amc_plan_id) == $plan->id) ? 'selected' : '' }}>
                      {{ $plan->name }} ({{ $plan->tier }} - {{ $plan->price_period }})
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Billing Cycle *</label>
                <select name="billing_cycle" class="form-control-paces" required>
                  @foreach(['monthly' => 'Monthly Invoicing', 'quarterly' => 'Quarterly Invoicing', 'half_yearly' => 'Half-Yearly (Every 6 Months)', 'annually' => 'Annual (One-time Lump Sum)'] as $cycleKey => $cycleLabel)
                    <option value="{{ $cycleKey }}" {{ (old('billing_cycle', $booking->billing_cycle) == $cycleKey) ? 'selected' : '' }}>
                      {{ $cycleLabel }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div style="margin-bottom:12px;">
              <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Upload Signed Agreement Copy (PDF / Image)</label>
              <input type="file" name="agreement_document" class="form-control-paces" accept=".pdf,.png,.jpg,.jpeg">
            </div>

            <div style="margin-bottom:14px;">
              <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Contract Notes & Service Deliverables</label>
              <textarea name="amc_package_notes" class="form-control-paces" rows="2" placeholder="Key service scope deliverables, quarterly visit frequency...">{{ old('amc_package_notes', $booking->amc_package_notes) }}</textarea>
            </div>

            <button type="submit" class="btn-paces btn-paces-primary" style="font-weight:700;">
              ✓ {{ $booking->contract_status === 'active' ? 'Save & Update Contract Details' : 'Activate AMC Contract' }}
            </button>
          </form>
        </div>
      @endif
    </div>
  </div>

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
            <option value="Cash">Cash on Delivery / Field Handover</option>
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

    function selectCrew(prefix, value, autoChangeStatus, partnerId, warningMsg) {
      const input = document.getElementById('crewInput_' + prefix);
      const partnerInput = document.getElementById('partner_id_' + prefix);
      if (input) input.value = value;
      if (partnerInput) partnerInput.value = partnerId;
      closeCrewDropdown(prefix);
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

    // Close crew dropdown when clicking outside
    document.addEventListener('click', function(e) {
      ['accept', 'edit'].forEach(prefix => {
        const wrapper = document.getElementById('crewDropdownWrapper_' + prefix);
        if (wrapper && !wrapper.contains(e.target)) {
          closeCrewDropdown(prefix);
        }
      });
    });

    // Check URL hash on page load
    document.addEventListener('DOMContentLoaded', function() {
      const hash = window.location.hash.replace('#', '');
      if (hash && document.getElementById('tab-' + hash)) {
        switchBookingTab('tab-' + hash);
      } else {
        switchBookingTab('tab-overview');
      }
    });
  </script>
</x-layouts.admin>
