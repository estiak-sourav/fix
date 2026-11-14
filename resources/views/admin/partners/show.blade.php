<x-layouts.admin title="Partner: {{ $partner->company_name }} | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:1.25rem;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.partners.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Partners
        </a>
        <span style="color:var(--paces-border);">/</span>
        <span style="font-size:0.82rem; color:var(--paces-text-muted);">{{ $partner->isVendor() ? 'Vendor Company' : 'Lead Technician' }}</span>
      </div>
      <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
        <div style="position:relative; width:54px; height:54px; flex-shrink:0;">
          <div style="width:54px; height:54px; border-radius:50%; background:linear-gradient(135deg, var(--paces-primary) 0%, #1e293b 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:1.15rem; font-weight:700; overflow:hidden; border:2.5px solid #e2e8f0;">
            @if($partner->avatar_url)
              <img src="{{ $partner->avatar_url }}" alt="{{ $partner->company_name }}" style="width:100%; height:100%; object-fit:cover; display:block;">
            @else
              <span>{{ $partner->initials }}</span>
            @endif
          </div>
          <button type="button" onclick="openAdminAvatarModal()" title="Change Profile Picture" style="position:absolute; bottom:-3px; right:-3px; width:22px; height:22px; border-radius:50%; background:var(--paces-primary); color:#ffffff; border:2px solid #ffffff; display:flex; align-items:center; justify-content:center; cursor:pointer; padding:0; box-shadow:0 1px 3px rgba(0,0,0,0.25);">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
          </button>
        </div>
        <div>
          <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <h1 class="page-header-title" style="margin:0;">{{ $partner->company_name }}</h1>
            @php $badge = $partner->status_badge; @endphp
            <span class="badge-pill-paces {{ $badge['bg'] }}" style="color:{{ $badge['color'] }}; font-size:0.82rem; padding:4px 12px; font-weight:700;">
              {{ $badge['label'] }}
            </span>
            <span class="badge-paces" style="background:{{ $partner->isVendor() ? '#f0fdf4' : '#eff6ff' }}; color:{{ $partner->isVendor() ? '#166534' : '#1d4ed8' }}; font-weight:600; font-size:0.78rem; padding:4px 10px; border-radius:6px;">
              {{ $partner->isVendor() ? '🏢 Registered Vendor' : '👷 Individual Contractor' }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
      <a href="{{ route('admin.partners.edit', $partner) }}" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        <span>Edit Partner</span>
      </a>
      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="switchPartnerTab('tab-admin-setup')" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <span>Manage Settings</span>
      </button>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between; gap:8px;" data-alert>
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6;" title="Close" aria-label="Close">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-size:0.875rem; display:flex; align-items:flex-start; justify-content:space-between; gap:8px;" data-alert>
      <ul style="margin:0; padding-left:18px;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6;" title="Close" aria-label="Close">&times;</button>
    </div>
  @endif

  <!-- Compact Overview KPI Strip (Quick info & Crew stats) -->
  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:1.5rem;">
    <!-- Payable Due Card -->
    <div class="card-paces" style="margin-bottom:0; padding:14px 18px; border-left:4px solid {{ $pendingDue > 0 ? '#dc2626' : '#16a34a' }};">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase;">Payable Due</span>
        <span style="font-size:1.1rem;">💰</span>
      </div>
      <div style="font-size:1.45rem; font-weight:800; color:{{ $pendingDue > 0 ? '#dc2626' : '#16a34a' }}; margin:4px 0 2px;">
        ৳ {{ number_format($pendingDue, 2) }}
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">
        Earned: ৳ {{ number_format($totalEarned, 2) }} • Paid: ৳ {{ number_format($totalPaid, 2) }}
      </div>
    </div>

    <!-- Work Delivered Summary -->
    <div class="card-paces" style="margin-bottom:0; padding:14px 18px; border-left:4px solid var(--paces-primary);">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase;">Work Orders</span>
        <span style="font-size:1.1rem;">📋</span>
      </div>
      <div style="font-size:1.45rem; font-weight:800; color:var(--paces-primary); margin:4px 0 2px;">
        {{ $completedJobsCount }} Completed
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">
        Total: {{ $totalJobsCount }} orders • Active: {{ $activeJobsCount }} in-progress
      </div>
    </div>

    <!-- Crew Key Info (Just 2/1 Key Stats as requested) -->
    <div class="card-paces" style="margin-bottom:0; padding:14px 18px; border-left:4px solid #0d9488;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:#0f766e; text-transform:uppercase;">Field Crew Status</span>
        <span style="font-size:1.1rem;">👷</span>
      </div>
      <div style="font-size:1.45rem; font-weight:800; color:#0f766e; margin:4px 0 2px;">
        {{ $partner->isVendor() ? ($crewsCount ?: $partner->technicians_count).' Technicians' : 'Individual Tech' }}
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">
        @if($partner->isVendor())
          {{ $activeCrewsCount }} Active on duty roster • {{ $partner->trade_domain }}
        @else
          Solo lead contractor • {{ $partner->trade_domain }}
        @endif
      </div>
    </div>

    <!-- Contact & Rating Quick Card -->
    <div class="card-paces" style="margin-bottom:0; padding:14px 18px; border-left:4px solid #6366f1;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:#4338ca; text-transform:uppercase;">Contact & Performance</span>
        <span style="font-size:1.1rem;">⭐</span>
      </div>
      <div style="font-size:1.1rem; font-weight:700; color:var(--paces-text-dark); margin:4px 0 2px; display:flex; align-items:center; gap:6px;">
        <span>{{ $partner->contact_person }}</span>
        @if($avgRating)
          <span style="font-size:0.8rem; background:#fef3c7; color:#b45309; padding:2px 6px; border-radius:6px; font-weight:800;">
            ★ {{ number_format($avgRating, 1) }}
          </span>
        @endif
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">
        📞 <a href="tel:{{ $partner->phone }}" style="color:inherit; text-decoration:none;">{{ $partner->phone }}</a>
        • 📍 {{ Str::limit($partner->coverage_areas, 24) }}
      </div>
    </div>
  </div>

  <!-- Tabbed Menu Navigation Bar -->
  <div class="partner-tabs-nav" style="display:flex; gap:6px; border-bottom:2px solid var(--paces-border); margin-bottom:1.5rem; overflow-x:auto; white-space:nowrap;">
    <button type="button" class="partner-tab-btn active" data-tab="tab-work-history" onclick="switchPartnerTab('tab-work-history')">
      <span>📋 Work History</span>
      <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.72rem; padding:2px 8px; border-radius:10px;">{{ $totalJobsCount }}</span>
    </button>

    <button type="button" class="partner-tab-btn" data-tab="tab-payouts" onclick="switchPartnerTab('tab-payouts')">
      <span>💰 Payables & Financials</span>
      @if($pendingDue > 0)
        <span class="badge-paces" style="background:#fee2e2; color:#dc2626; font-size:0.72rem; padding:2px 8px; border-radius:10px; font-weight:700;">
          Due: ৳{{ number_format($pendingDue, 0) }}
        </span>
      @else
        <span class="badge-paces" style="background:#dcfce7; color:#16a34a; font-size:0.72rem; padding:2px 8px; border-radius:10px;">✓ Settled</span>
      @endif
    </button>

    <button type="button" class="partner-tab-btn" data-tab="tab-crews" onclick="switchPartnerTab('tab-crews')">
      <span>👷 Crew Members</span>
      <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.72rem; padding:2px 8px; border-radius:10px;">{{ $crewsCount }}</span>
    </button>

    <button type="button" class="partner-tab-btn" data-tab="tab-admin-setup" onclick="switchPartnerTab('tab-admin-setup')">
      <span>⚙️ Admin & Payout Setup</span>
    </button>

    <button type="button" class="partner-tab-btn" data-tab="tab-profile" onclick="switchPartnerTab('tab-profile')">
      <span>🏢 Business Profile & Docs</span>
    </button>
  </div>

  <!-- TAB 1: Work History (Vendor's complete job records) -->
  <div id="tab-work-history" class="partner-tab-pane active">
    <div class="card-paces" style="padding:0; overflow:hidden;">
      <div class="card-header-paces" style="padding:16px 20px; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Dispatched Work Orders & Job History</h3>
          <p class="card-subtitle-paces" style="margin:2px 0 0;">All service bookings, maintenance calls, and dispatch assignments for this partner</p>
        </div>

        <div style="display:flex; align-items:center; gap:8px;">
          <input type="text" id="workHistorySearch" onkeyup="filterWorkOrders()" placeholder="Search order #, customer, area..." style="padding:6px 12px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); width:240px;">
          <select id="workHistoryStatusFilter" onchange="filterWorkOrders()" style="padding:6px 12px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#fff;">
            <option value="all">All Statuses</option>
            <option value="completed">Completed</option>
            <option value="in_progress">In Progress</option>
            <option value="assigned">Assigned</option>
            <option value="pending_review">Pending</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <div class="table-responsive-paces">
        <table class="table-paces" id="workOrdersTable">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Service & Trade</th>
              <th>Location</th>
              <th>Date</th>
              <th style="text-align:right;">Billed Total</th>
              <th style="text-align:right;">Vendor Share</th>
              <th style="text-align:center;">Job Status</th>
              <th style="text-align:center;">Payout Status</th>
              <th style="text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($partner->bookings as $booking)
              @php
                $payoutAmt = (float) ($booking->partner_payout_amount ?? $partner->calculateBookingEarning($booking));
                $pStatus = $booking->partner_payout_status ?: 'unpaid';
                $payoutBadge = match($pStatus) {
                  'paid' => ['bg' => '#ecfdf5', 'color' => '#047857', 'label' => 'Paid'],
                  'processing' => ['bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'Processing'],
                  default => ['bg' => '#fef2f2', 'color' => '#b91c1c', 'label' => 'Due / Unpaid'],
                };
                $statusColors = [
                  'completed' => ['bg' => '#ecfdf5', 'color' => '#047857', 'label' => 'Completed'],
                  'in_progress' => ['bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'In Progress'],
                  'assigned' => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'label' => 'Assigned'],
                  'pending_review' => ['bg' => '#fffbeb', 'color' => '#b45309', 'label' => 'Pending Review'],
                  'cancelled' => ['bg' => '#fef2f2', 'color' => '#b91c1c', 'label' => 'Cancelled'],
                ];
                $bBadge = $statusColors[$booking->status] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => ucfirst($booking->status)];
              @endphp
              <tr data-status="{{ $booking->status }}" data-search="{{ strtolower($booking->booking_number . ' ' . $booking->customer_name . ' ' . $booking->area . ' ' . ($booking->service?->title ?? '')) }}">
                <td>
                  <a href="{{ route('admin.bookings.show', $booking) }}" style="font-weight:700; color:var(--paces-primary); text-decoration:none; font-family:monospace;">
                    #{{ $booking->booking_number }}
                  </a>
                  @if($booking->assignedCrew)
                    <div style="font-size:0.72rem; color:var(--paces-text-muted); margin-top:2px;">
                      👷 {{ $booking->assignedCrew->name }}
                    </div>
                  @endif
                </td>
                <td>
                  <div style="font-weight:600; color:var(--paces-text-dark); font-size:0.88rem;">{{ $booking->customer_name }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">📞 {{ $booking->phone }}</div>
                </td>
                <td>
                  <div style="font-weight:600; font-size:0.85rem; color:var(--paces-text-dark);">
                    {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Facility Service') }}
                  </div>
                  @if($booking->review_rating)
                    <div style="font-size:0.75rem; color:#b45309; font-weight:700; margin-top:2px;">
                      ⭐ {{ $booking->review_rating }}/5
                      @if($booking->review_comment)
                        <span style="font-size:0.72rem; color:var(--paces-text-muted); font-weight:normal;" title="{{ $booking->review_comment }}">
                          • "{{ Str::limit($booking->review_comment, 24) }}"
                        </span>
                      @endif
                    </div>
                  @endif
                </td>
                <td style="font-size:0.82rem; color:var(--paces-text-dark);">
                  📍 {{ $booking->area }}
                </td>
                <td style="font-size:0.8rem; color:var(--paces-text-muted);">
                  {{ $booking->completed_at ? $booking->completed_at->format('M d, Y') : $booking->created_at->format('M d, Y') }}
                </td>
                <td style="text-align:right; font-weight:600;">
                  ৳ {{ number_format((float) ($booking->final_cost ?: $booking->estimated_cost ?: 0), 2) }}
                </td>
                <td style="text-align:right; font-weight:800; color:#b45309;">
                  ৳ {{ number_format($payoutAmt, 2) }}
                </td>
                <td style="text-align:center;">
                  <span class="badge-paces" style="background:{{ $bBadge['bg'] }}; color:{{ $bBadge['color'] }}; font-weight:700; font-size:0.74rem; padding:3px 10px; border-radius:12px;">
                    {{ $bBadge['label'] }}
                  </span>
                </td>
                <td style="text-align:center;">
                  <span class="badge-paces" style="background:{{ $payoutBadge['bg'] }}; color:{{ $payoutBadge['color'] }}; font-weight:700; font-size:0.74rem; padding:3px 10px; border-radius:12px;">
                    {{ $payoutBadge['label'] }}
                  </span>
                  @if($booking->partner_payout_reference)
                    <div style="font-size:0.68rem; color:var(--paces-text-muted); margin-top:2px;">{{ $booking->partner_payout_reference }}</div>
                  @endif
                </td>
                <td style="text-align:right;">
                  <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px; font-size:0.78rem;">
                    View →
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" style="text-align:center; padding:3rem 1rem; color:var(--paces-text-muted);">
                  <div style="font-size:2rem; margin-bottom:8px;">📋</div>
                  <h4 style="margin:0 0 4px; color:var(--paces-text-dark);">No Work Orders Assigned Yet</h4>
                  <p style="margin:0; font-size:0.85rem;">When new jobs are dispatched to this partner, their operational lifecycle and reviews will appear here.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TAB 2: Payables & Financials (Payable amount, payout history, bank/MFS info) -->
  <div id="tab-payouts" class="partner-tab-pane">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.5rem;">
      <!-- Financial Summary Box -->
      <div class="card-paces" style="margin-bottom:0; padding:20px;">
        <h3 class="card-title-paces" style="font-size:0.95rem; margin-bottom:14px;">Payable & Earnings Ledger Summary</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
          <div style="background:#f8fafc; border:1px solid var(--paces-border); padding:12px; border-radius:8px;">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Total Earned by Crew</div>
            <div style="font-size:1.35rem; font-weight:800; color:var(--paces-primary); margin-top:4px;">৳ {{ number_format($totalEarned, 2) }}</div>
          </div>
          <div style="background:#f8fafc; border:1px solid var(--paces-border); padding:12px; border-radius:8px;">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Total Paid Out</div>
            <div style="font-size:1.35rem; font-weight:800; color:#16a34a; margin-top:4px;">৳ {{ number_format($totalPaid, 2) }}</div>
          </div>
        </div>

        <div style="background:{{ $pendingDue > 0 ? '#fef2f2' : '#f0fdf4' }}; border:1px solid {{ $pendingDue > 0 ? '#fecaca' : '#bbf7d0' }}; border-radius:10px; padding:16px; display:flex; justify-content:space-between; align-items:center;">
          <div>
            <div style="font-size:0.78rem; font-weight:700; color:{{ $pendingDue > 0 ? '#991b1b' : '#166534' }}; text-transform:uppercase;">Current Payable Balance</div>
            <div style="font-size:1.75rem; font-weight:900; color:{{ $pendingDue > 0 ? '#dc2626' : '#16a34a' }};">
              ৳ {{ number_format($pendingDue, 2) }}
            </div>
            <div style="font-size:0.75rem; color:{{ $pendingDue > 0 ? '#b91c1c' : '#15803d' }}; margin-top:2px;">
              {{ $completedJobsCount }} total completed jobs • Any amount can be paid from this balance
            </div>
          </div>
          @if($pendingDue > 0)
            <span class="badge-paces" style="background:#fee2e2; color:#b91c1c; font-weight:800; padding:6px 14px; border-radius:20px; font-size:0.85rem;">
              Payment Due
            </span>
          @else
            <span class="badge-paces" style="background:#dcfce7; color:#166534; font-weight:800; padding:6px 14px; border-radius:20px; font-size:0.85rem;">
              ✓ All Settled
            </span>
          @endif
        </div>
      </div>

      <!-- Configured Disbursement & Payment Methods Info -->
      <div class="card-paces" style="margin-bottom:0; padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; gap:8px; flex-wrap:wrap;">
          <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Saved Payment &amp; Receiving Methods</h3>
          <div style="display:flex; align-items:center; gap:8px;">
            <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddPaymentMethodModal()" style="font-size:0.75rem; padding:4px 10px; display:inline-flex; align-items:center; gap:4px;">
              <span>+ Add Method</span>
            </button>
            <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="switchPartnerTab('tab-admin-setup')" style="font-size:0.75rem; padding:4px 8px;">
              Edit Terms ⚙️
            </button>
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px;">
          <div>
            <span style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Earning Model</span>
            <div style="font-size:0.92rem; font-weight:700; color:var(--paces-text-dark); margin-top:2px;">
              {{ $partner->earning_model_label }}
            </div>
          </div>
          <div>
            <span style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Agreed Rate</span>
            <div style="font-size:0.92rem; font-weight:800; color:#0f766e; margin-top:2px;">
              {{ $partner->formatted_rate }}
            </div>
          </div>
        </div>

        <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px; max-height:220px; overflow-y:auto;">
          <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
            <span>Registered Payment Methods ({{ $paymentMethods->count() }})</span>
            <span style="font-size:0.68rem; color:#64748b; font-weight:normal;">Default method is auto-selected for direct payouts</span>
          </div>
          @forelse($paymentMethods as $pm)
            <div style="padding:8px 10px; background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center; gap:8px;">
              <div style="min-width:0; flex:1;">
                <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                  <span style="font-weight:700; font-size:0.85rem; color:#1e293b;">{{ $pm->formatted_method }}</span>
                  @if($pm->is_default)
                    <span class="badge-paces" style="background:#dbeafe; color:#1d4ed8; font-size:0.68rem; font-weight:800; padding:2px 7px; border-radius:4px; white-space:nowrap !important; flex-shrink:0 !important;">★ Default</span>
                  @endif
                </div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                  <span>Acc: <strong style="font-family:monospace; color:#0f172a;">{{ $pm->account_number }}</strong></span>
                  @if($pm->account_name)
                    <span> • {{ $pm->account_name }}</span>
                  @endif
                  @if($pm->bank_name)
                    <span> • {{ $pm->bank_name }}{{ $pm->branch_name ? ' ('.$pm->branch_name.')' : '' }}</span>
                  @endif
                </div>
              </div>
              <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                <button type="button" 
                  onclick='openAdminEditPaymentMethodModal(@json($pm))' 
                  class="btn-paces btn-paces-outline btn-paces-sm" 
                  title="Edit payment method" 
                  style="font-size:0.7rem; padding:3px 8px;">
                  Edit
                </button>
                @if(! $pm->is_default)
                  <form id="adminDefaultPmForm_{{ $pm->id }}" action="{{ route('admin.partners.payment-methods.default', [$partner, $pm]) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" title="Set as default receiving method" style="font-size:0.7rem; padding:3px 8px; white-space:nowrap !important; flex-shrink:0 !important;" onclick="openAdminConfirmModal({ title:'Set Default Method?', message:'Set {{ $pm->formatted_method }} as the primary default payout target for this crew?', confirmText:'Set Default', confirmColor:'var(--paces-primary)', iconType:'warning', onConfirm: () => document.getElementById('adminDefaultPmForm_{{ $pm->id }}').submit() })">
                      Make Default
                    </button>
                  </form>
                @endif
                <form id="adminDeletePmForm_{{ $pm->id }}" action="{{ route('admin.partners.payment-methods.destroy', [$partner, $pm]) }}" method="POST" style="margin:0;">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn-paces btn-paces-danger btn-paces-sm" title="Delete payment method" style="font-size:0.7rem; padding:3px 7px; background:#fef2f2; border-color:#fecaca; color:#dc2626; white-space:nowrap !important; flex-shrink:0 !important;" onclick="openAdminConfirmModal({ title:'Delete Payment Method?', message:'Are you sure you want to delete this payment method ({{ $pm->formatted_method }}) for {{ $partner->contact_person }}?', confirmText:'Delete Method', confirmColor:'#dc2626', onConfirm: () => document.getElementById('adminDeletePmForm_{{ $pm->id }}').submit() })">
                    ✕
                  </button>
                </form>
              </div>
            </div>
          @empty
            <div style="font-size:0.8rem; color:#94a3b8; text-align:center; padding:12px;">
              No payment methods registered yet. Click <strong>+ Add Method</strong> to add bKash, Nagad, Rocket, or Bank details.
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- PENDING PAYOUT REQUESTS FROM CREW -->
    @if($pendingPayoutRequests->isNotEmpty())
      <div class="card-paces" style="margin-bottom:1.5rem; border:1px solid #fed7aa; background:#fffbeb; padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
          <div>
            <h4 style="margin:0; font-size:1rem; color:#9a3412; font-weight:700;">
              🔔 Pending Withdrawal / Payout Requests ({{ $pendingPayoutRequests->count() }})
            </h4>
            <p style="margin:2px 0 0; font-size:0.78rem; color:#c2410c;">
              This crew has requested a payout disbursement from their earned balance.
            </p>
          </div>
          <span style="font-size:0.85rem; font-weight:800; background:#ffedd5; color:#9a3412; padding:4px 10px; border-radius:12px;">
            Total Requested: ৳ {{ number_format((float) $pendingPayoutRequests->sum('amount'), 2) }}
          </span>
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">
          @foreach($pendingPayoutRequests as $req)
            <div style="background:#ffffff; border:1px solid #fbd38d; border-radius:8px; padding:16px;">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">
                <div>
                  <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight:700; font-size:0.9rem; color:#1e293b; font-family:monospace;">#{{ $req->payout_number }}</span>
                    <span style="background:#fef3c7; color:#b45309; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:4px;">Pending Review</span>
                    <span style="font-size:0.75rem; color:#64748b;">Requested {{ $req->requested_at?->diffForHumans() ?? 'recently' }}</span>
                  </div>
                  <div style="margin-top:6px; font-size:0.84rem; color:#334155;">
                    Receiving Method: <strong>{{ $req->payment_method_details ?: ($req->paymentMethod?->formatted_method ?? 'Not specified') }}</strong>
                  </div>
                  @if($req->partner_notes)
                    <div style="margin-top:4px; font-size:0.78rem; color:#64748b; font-style:italic;">
                      Note from crew: "{{ $req->partner_notes }}"
                    </div>
                  @endif
                </div>
                <div style="text-align:right;">
                  <div style="font-size:1.4rem; font-weight:800; color:#b45309;">
                    ৳ {{ number_format((float) $req->amount, 2) }}
                  </div>
                </div>
              </div>

              <!-- Action buttons and inline forms -->
              <div style="margin-top:14px; padding-top:12px; border-top:1px dashed #fed7aa; display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap;">
                <!-- Reject Button & Modal toggle -->
                <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#dc2626; border-color:#fca5a5;" onclick="document.getElementById('rejectForm_{{ $req->id }}').style.display = document.getElementById('rejectForm_{{ $req->id }}').style.display === 'none' ? 'block' : 'none'">
                  ✕ Reject Request
                </button>

                <!-- Approve & Mark as Paid toggle -->
                <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#16a34a; border-color:#16a34a;" onclick="document.getElementById('approveForm_{{ $req->id }}').style.display = document.getElementById('approveForm_{{ $req->id }}').style.display === 'none' ? 'block' : 'none'">
                  ✓ Approve & Mark as Paid
                </button>
              </div>

              <!-- Inline Approve Form -->
              <div id="approveForm_{{ $req->id }}" style="display:none; margin-top:12px; padding:12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px;">
                <form action="{{ route('admin.partners.payouts.approve', $req) }}" method="POST">
                  @csrf
                  <div style="font-size:0.82rem; font-weight:700; color:#166534; margin-bottom:8px;">
                    Approve Payout for ৳{{ number_format((float) $req->amount, 2) }}
                  </div>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:8px;">
                    <div>
                      <label style="display:block; font-size:0.72rem; font-weight:600; color:#374151;">Transaction Reference / TrxID</label>
                      <input type="text" name="transaction_reference" class="form-control-paces" placeholder="e.g. 9J47X90LA" style="padding:6px 10px; font-size:0.8rem; width:100%;">
                    </div>
                    <div>
                      <label style="display:block; font-size:0.72rem; font-weight:600; color:#374151;">Admin Note (Optional)</label>
                      <input type="text" name="admin_notes" class="form-control-paces" placeholder="e.g. Sent via bKash Merchant API" style="padding:6px 10px; font-size:0.8rem; width:100%;">
                    </div>
                  </div>
                  <div style="text-align:right;">
                    <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="background:#16a34a; border-color:#16a34a; padding:6px 14px;">
                      Confirm Approval & Mark as Paid
                    </button>
                  </div>
                </form>
              </div>

              <!-- Inline Reject Form -->
              <div id="rejectForm_{{ $req->id }}" style="display:none; margin-top:12px; padding:12px; background:#fef2f2; border:1px solid #fecaca; border-radius:6px;">
                <form action="{{ route('admin.partners.payouts.reject', $req) }}" method="POST">
                  @csrf
                  <div style="font-size:0.82rem; font-weight:700; color:#991b1b; margin-bottom:8px;">
                    Reject Payout Request #{{ $req->payout_number }}
                  </div>
                  <div style="margin-bottom:8px;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:#374151;">Rejection Reason <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="rejection_reason" required class="form-control-paces" placeholder="e.g. Incorrect account number or pending audit" style="padding:6px 10px; font-size:0.8rem; width:100%;">
                  </div>
                  <div style="text-align:right;">
                    <button type="submit" class="btn-paces btn-paces-danger btn-paces-sm" style="padding:6px 14px;">
                      Confirm Rejection
                    </button>
                  </div>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- DISBURSE PAYMENT FROM BALANCE CARD (Admin can pay ANY custom amount from balance) -->
    <div class="card-paces" style="margin-bottom:1.5rem; padding:20px; border:1px solid var(--paces-border); background:#ffffff;">
      <div style="border-bottom:1px solid var(--paces-border); padding-bottom:12px; margin-bottom:16px;">
        <h4 style="margin:0; font-size:1.05rem; font-weight:700; color:var(--paces-text-dark);">
          💵 Disburse Payment from Balance
        </h4>
        <p style="margin:4px 0 0; font-size:0.8rem; color:var(--paces-text-muted);">
          Admin can pay any custom amount (full or partial) from the crew's outstanding payable balance. The remaining unpaid amount will automatically adjust in their balance.
        </p>
      </div>

      @if($pendingDue > 0)
        <form action="{{ route('admin.partners.payouts.disburse', $partner) }}" method="POST">
          @csrf

          <!-- Quick Balance Helpers -->
          <div style="margin-bottom:14px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase;">Quick Fill:</span>
            <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="font-size:0.75rem; padding:3px 10px; background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;" onclick="setDisburseAmount({{ $pendingDue }})">
              Full Balance (৳{{ number_format($pendingDue, 2) }})
            </button>
            @foreach([500, 1000, 2000, 5000, 10000] as $chipAmt)
              @if($chipAmt < $pendingDue)
                <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="font-size:0.75rem; padding:3px 10px;" onclick="setDisburseAmount({{ $chipAmt }})">
                  ৳ {{ number_format($chipAmt) }}
                </button>
              @endif
            @endforeach
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:14px;">
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">
                Disbursement Amount (৳) <span style="color:#dc2626;">*</span>
              </label>
              <div style="position:relative;">
                <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-weight:700; color:#64748b;">৳</span>
                <input 
                  type="number" 
                  step="0.01" 
                  min="1" 
                  max="{{ $pendingDue }}" 
                  id="disburse_amount_input"
                  name="amount" 
                  required 
                  value="{{ old('amount', $pendingDue) }}" 
                  class="form-control-paces" 
                  style="padding-left:28px; font-size:1rem; font-weight:700;"
                >
              </div>
              <div style="font-size:0.72rem; color:#64748b; margin-top:4px;">
                Max payable balance: ৳{{ number_format($pendingDue, 2) }}
              </div>
            </div>

            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">
                Receiving Payment Method
              </label>
              <select name="partner_payment_method_id" id="disburse_method_select" class="form-control-paces" style="font-size:0.85rem;" onchange="toggleCustomMethod(this.value)">
                @foreach($paymentMethods as $pm)
                  <option value="{{ $pm->id }}" {{ $pm->is_default ? 'selected' : '' }}>
                    {{ $pm->formatted_method }}{{ $pm->is_default ? ' ★ (Default)' : '' }}
                  </option>
                @endforeach
                <option value="">-- Custom / Direct Cash Handover / Other --</option>
              </select>
            </div>
          </div>

          <!-- Custom payment method text (visible if Other is selected or no methods exist) -->
          <div id="customMethodWrapper" style="display:{{ $paymentMethods->isEmpty() ? 'block' : 'none' }}; margin-bottom:14px;">
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Custom Payment Method Name</label>
            <input type="text" name="custom_payment_method" class="form-control-paces" placeholder="e.g. Direct Cash Handover at Office, Bank Transfer" style="font-size:0.85rem;">
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Transaction Reference / TrxID</label>
              <input type="text" name="transaction_reference" class="form-control-paces" placeholder="e.g. bKash TrxID, Bank Vchr #, Cheque #" style="font-size:0.85rem;">
            </div>
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Admin Notes (Optional)</label>
              <input type="text" name="admin_notes" class="form-control-paces" placeholder="e.g. Mid-month balance disbursal" style="font-size:0.85rem;">
            </div>
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:14px;">
            <div style="font-size:0.78rem; color:#64748b;">
              ⚠️ Remaining balance after payment will immediately reflect on both Admin & Crew portals.
            </div>
            <button type="submit" class="btn-paces btn-paces-primary" style="background:#16a34a; border-color:#16a34a; padding:8px 20px; font-weight:700;">
              Confirm & Disburse Payment
            </button>
          </div>
        </form>
      @else
        <div style="text-align:center; padding:1.5rem; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px;">
          <div style="font-size:1.8rem; margin-bottom:6px;">✓</div>
          <div style="font-weight:700; color:#166534; font-size:0.95rem;">Crew Balance is Fully Settled</div>
          <div style="font-size:0.8rem; color:#15803d; margin-top:2px;">There are no outstanding payable dues for this partner at this moment.</div>
        </div>
      @endif
    </div>

    <!-- ALL HISTORICAL DISBURSED PAYOUT TRANSACTIONS TABLE -->
    <div class="card-paces" style="margin-bottom:1.5rem; padding:0; overflow:hidden;">
      <div class="card-header-paces" style="padding:16px 20px; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Disbursed Payout Transactions History</h3>
          <p class="card-subtitle-paces" style="margin:2px 0 0;">Record of all payment disbursements made to this partner from balance</p>
        </div>
        <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.75rem; padding:3px 10px;">
          Total Disbursed: ৳ {{ number_format($totalPaid, 2) }}
        </span>
      </div>
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Payout #</th>
              <th>Date & Time</th>
              <th style="text-align:right;">Amount Paid</th>
              <th>Payment Method</th>
              <th style="text-align:center;">Status</th>
              <th>Transaction TrxID</th>
              <th>Processed By</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            @forelse($payoutHistory as $pay)
              <tr>
                <td style="font-family:monospace; font-weight:700; color:var(--paces-primary);">
                  #{{ $pay->payout_number }}
                </td>
                <td style="font-size:0.8rem; color:var(--paces-text-muted);">
                  {{ $pay->paid_at ? $pay->paid_at->format('M d, Y h:i A') : $pay->created_at->format('M d, Y') }}
                </td>
                <td style="text-align:right; font-weight:800; color:#16a34a; font-size:0.92rem;">
                  ৳ {{ number_format((float) $pay->amount, 2) }}
                </td>
                <td style="font-size:0.82rem;">
                  {{ $pay->payment_method_details ?: ($pay->paymentMethod?->formatted_method ?? 'Direct Settlement') }}
                </td>
                <td style="text-align:center;">
                  @php $badge = $pay->status_badge; @endphp
                  <span style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }}; font-weight:700; font-size:0.72rem; padding:3px 10px; border-radius:12px; display:inline-block;">
                    {{ $badge['label'] }}
                  </span>
                </td>
                <td style="font-family:monospace; font-size:0.8rem; color:#475569;">
                  {{ $pay->transaction_reference ?: '—' }}
                </td>
                <td style="font-size:0.8rem; color:#64748b;">
                  {{ $pay->processedByAdmin?->name ?? 'Admin' }}
                </td>
                <td style="font-size:0.78rem; color:#64748b; max-width:180px;">
                  {{ $pay->admin_notes ?: ($pay->partner_notes ?: '—') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align:center; padding:2rem; color:var(--paces-text-muted);">
                  No payout transactions disbursed yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- CONTRIBUTING COMPLETED JOBS (Earning Ledger) -->
    <div class="card-paces" style="padding:0; overflow:hidden;">
      <div class="card-header-paces" style="padding:16px 20px; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Completed Jobs Contributing to Earned Balance</h3>
          <p class="card-subtitle-paces" style="margin:2px 0 0;">Work orders completed by this crew that generated the total earned amount</p>
        </div>
        <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-size:0.75rem; padding:3px 10px;">
          Total Earned: ৳ {{ number_format($totalEarned, 2) }}
        </span>
      </div>
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Service</th>
              <th>Completed Date</th>
              <th>Location</th>
              <th style="text-align:right;">Order Cost</th>
              <th style="text-align:right;">Partner Share Earned</th>
              <th style="text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($partner->bookings->where('status', 'completed') as $b)
              @php
                $pShare = (float) ($b->partner_payout_amount ?? $partner->calculateBookingEarning($b));
              @endphp
              <tr>
                <td>
                  <a href="{{ route('admin.bookings.show', $b) }}" style="font-weight:700; color:var(--paces-primary); font-family:monospace; text-decoration:none;">
                    #{{ $b->booking_number }}
                  </a>
                </td>
                <td>{{ $b->service?->title ?? 'Facility Service' }}</td>
                <td style="font-size:0.8rem; color:var(--paces-text-muted);">
                  {{ $b->completed_at ? $b->completed_at->format('M d, Y') : $b->created_at->format('M d, Y') }}
                </td>
                <td style="font-size:0.82rem;">📍 {{ $b->area ?: ($b->district ?: '—') }}</td>
                <td style="text-align:right; font-weight:600;">৳ {{ number_format((float) ($b->final_cost ?: $b->estimated_cost ?: 0), 2) }}</td>
                <td style="text-align:right; font-weight:700; color:#059669;">৳ {{ number_format($pShare, 2) }}</td>
                <td style="text-align:right; white-space:nowrap;">
                  <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px; font-size:0.78rem; white-space:nowrap; display:inline-flex; align-items:center;">
                    View Order →
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align:center; padding:2rem; color:var(--paces-text-muted);">
                  No completed work orders on record yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TAB 3: Crew Members (Roster of technicians) -->
  <div id="tab-crews" class="partner-tab-pane">
    <div class="card-paces" style="padding:0; overflow:hidden;">
      <div class="card-header-paces" style="padding:16px 20px; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Registered Field Crew Members ({{ $crewsCount }})</h3>
          <p class="card-subtitle-paces" style="margin:2px 0 0;">Internal field technicians under {{ $partner->company_name }} dispatched to work orders</p>
        </div>
        <div style="font-size:0.82rem; color:var(--paces-text-muted);">
          Active on Duty: <strong style="color:#0f766e;">{{ $activeCrewsCount }}</strong>
        </div>
      </div>

      @if($partner->crews->isEmpty())
        <div style="text-align:center; padding:3rem 1rem; color:var(--paces-text-muted);">
          <div style="font-size:2.5rem; margin-bottom:8px;">👷</div>
          <h4 style="margin:0 0 4px; color:var(--paces-text-dark);">
            {{ $partner->isVendor() ? 'No Internal Crew Enlisted Yet' : 'Individual Lead Technician Account' }}
          </h4>
          <p style="margin:0 auto; font-size:0.85rem; max-width:450px;">
            @if($partner->isVendor())
              The vendor has declared {{ $partner->technicians_count }} active technicians. They can add individual crew members from their Partner Command Center.
            @else
              This partner is registered as an individual technician and personally handles assigned work orders.
            @endif
          </p>
        </div>
      @else
        <div class="table-responsive-paces">
          <table class="table-paces">
            <thead>
              <tr>
                <th>Crew Member Name</th>
                <th>Phone Number</th>
                <th>Trade Skill / Expertise</th>
                <th>Assigned Tasks</th>
                <th style="text-align:center;">Status</th>
                <th>Joined Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($partner->crews as $crew)
                <tr>
                  <td>
                    <div style="font-weight:700; color:var(--paces-text-dark); font-size:0.9rem;">
                      👤 {{ $crew->name }}
                    </div>
                  </td>
                  <td>
                    <a href="tel:{{ $crew->phone }}" style="color:inherit; text-decoration:none; font-size:0.85rem;">
                      📞 {{ $crew->phone }}
                    </a>
                  </td>
                  <td>
                    <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-weight:600; font-size:0.75rem; padding:3px 8px; border-radius:6px;">
                      {{ $crew->trade_skill ?: $partner->trade_domain }}
                    </span>
                  </td>
                  <td>
                    <span style="font-weight:700; font-size:0.85rem;">{{ $crew->bookings_count ?? 0 }}</span>
                    <span style="font-size:0.75rem; color:var(--paces-text-muted);">work orders</span>
                  </td>
                  <td style="text-align:center;">
                    @if($crew->is_active)
                      <span class="badge-paces" style="background:#dcfce7; color:#166534; font-weight:700; font-size:0.72rem; padding:3px 10px; border-radius:12px;">Active</span>
                    @else
                      <span class="badge-paces" style="background:#fee2e2; color:#dc2626; font-weight:700; font-size:0.72rem; padding:3px 10px; border-radius:12px;">Inactive</span>
                    @endif
                  </td>
                  <td style="font-size:0.8rem; color:var(--paces-text-muted);">
                    {{ $crew->created_at->format('M d, Y') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <!-- TAB 4: Admin & Payout Setup (Form for terms, approval, passwords, rates) -->
  <div id="tab-admin-setup" class="partner-tab-pane">
    <div class="card-paces" style="max-width:960px; margin:0 auto;">
      <div style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <h3 class="card-title-paces" style="margin:0;">Administrative Credentials, Rates & Payout Setup</h3>
          <p class="card-subtitle-paces" style="margin:2px 0 0;">Manage commission/rates, banking coordinates, and portal credentials</p>
        </div>
        <span class="badge-pill-paces {{ $partner->status_badge['bg'] }}" style="color:{{ $partner->status_badge['color'] }}; font-size:0.8rem; padding:4px 12px;">
          {{ $partner->status_badge['label'] }}
        </span>
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

        <div style="margin-bottom:1.25rem;">
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

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">Earning Model *</label>
            <select name="earning_model" id="earning_model" class="form-control-paces" onchange="updateEarningRateUI(this.value)">
              <option value="commission" {{ old('earning_model', $partner->earning_model ?? 'commission') === 'commission' ? 'selected' : '' }}>Commission Based (% of Job Total)</option>
              <option value="hourly" {{ old('earning_model', $partner->earning_model) === 'hourly' ? 'selected' : '' }}>Hourly Rate (Fixed ৳ per hour)</option>
              <option value="daily" {{ old('earning_model', $partner->earning_model) === 'daily' ? 'selected' : '' }}>Daily Rate (Fixed ৳ per day)</option>
              <option value="monthly" {{ old('earning_model', $partner->earning_model) === 'monthly' ? 'selected' : '' }}>Monthly Salary / Retainer (Fixed ৳ per month)</option>
              <option value="per_job" {{ old('earning_model', $partner->earning_model) === 'per_job' ? 'selected' : '' }}>Fixed Per Job (Flat ৳ per work order)</option>
            </select>
          </div>

          <div>
            <label class="form-label-paces" id="payoutRateLabel">Rate Value (%)</label>
            <input 
              type="number" 
              step="0.01" 
              name="payout_rate" 
              id="payout_rate" 
              value="{{ old('payout_rate', $partner->payout_rate ?? $partner->commission_rate ?? '15.00') }}" 
              class="form-control-paces" 
              placeholder="15.00"
            >
            <input type="hidden" name="commission_rate" id="hidden_commission_rate" value="{{ old('commission_rate', $partner->commission_rate ?: '15.00') }}">
          </div>
        </div>

        <!-- Bank Account Details (Shown if Bank) -->
        <div id="bankDetailsContainer" style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; margin-bottom:1.25rem;">
          <div style="font-size:0.82rem; font-weight:600; color:var(--paces-text-dark); margin-bottom:10px;">
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
          <div style="font-size:0.82rem; font-weight:600; color:var(--paces-text-dark); margin-bottom:10px;">
            📱 MFS Wallet Details
          </div>
          <div>
            <label class="form-label-paces" style="font-size:0.78rem;">Wallet / Merchant Number</label>
            <input type="text" name="mfs_number" value="{{ old('mfs_number', $partner->mfs_number) }}" class="form-control-paces" placeholder="e.g. 017xxxxxxxx (bKash/Nagad)">
          </div>
        </div>

        <!-- Partner Portal Authentication Credentials -->
        <div style="background:#f0fdfa; border:1px solid #99f6e4; border-radius:8px; padding:14px; margin-bottom:1.25rem;">
          <div style="font-size:0.85rem; font-weight:600; color:#0f766e; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Partner Command Center Login Credentials
          </div>
          <div style="font-size:0.82rem; color:#134e4a; margin-bottom:10px;">
            Partners access their portal at <a href="{{ url('/partner/login') }}" target="_blank" style="color:#0d9488; font-weight:700; text-decoration:underline;">/partner/login</a> using registered phone (<strong>{{ $partner->phone }}</strong>) and password.
          </div>
          <div>
            <label class="form-label-paces" style="font-size:0.78rem;">
              Set / Reset Login Password
              @if(!empty($partner->password))
                <span style="color:#059669; font-weight:600; margin-left:6px;">✓ Active Password Set</span>
              @else
                <span style="color:#d97706; font-weight:600; margin-left:6px;">⚠️ No Password Set (Will default to 123456 upon approval)</span>
              @endif
            </label>
            <input type="password" name="password" class="form-control-paces" placeholder="Enter new password (leave blank to keep unchanged)">
          </div>
        </div>

        <!-- Internal Admin Notes -->
        <div style="margin-bottom:1.5rem;">
          <label class="form-label-paces">Internal Admin Notes & Contract Verification</label>
          <textarea name="admin_notes" class="form-control-paces" rows="4" placeholder="e.g. Trade license verified, physical inspection scheduled, certified safety gear issued...">{{ old('admin_notes', $partner->admin_notes) }}</textarea>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; border-top:1px solid var(--paces-border); padding-top:1.25rem;">
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
              View-only access. Account modification requires edit permissions.
            </span>
          @endif
        </div>
      </form>
    </div>
  </div>

  <!-- TAB 5: Company Profile & Verification -->
  <div id="tab-profile" class="partner-tab-pane">
    <div style="display:grid; grid-template-columns:360px 1fr; gap:1.5rem; align-items:start;">
      <!-- Profile Card -->
      <div class="card-paces">
        <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
          <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Enlistment & Application Profile</h3>
        </div>

        <div style="margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Company / Trade Name</div>
          <div style="font-size:1.05rem; font-weight:700; color:var(--paces-text-dark); margin-top:2px;">
            {{ $partner->company_name }}
          </div>
        </div>

        <div style="margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Lead Representative</div>
          <div style="font-size:0.92rem; font-weight:600; color:var(--paces-primary); margin-top:2px;">
            👤 {{ $partner->contact_person }}
          </div>
        </div>

        <div style="margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Contact Phone</div>
          <div style="font-size:0.92rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
            📞 <a href="tel:{{ $partner->phone }}" style="color:inherit; text-decoration:none;">{{ $partner->phone }}</a>
          </div>
        </div>

        @if($partner->email)
          <div style="margin-bottom:14px;">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Official Email</div>
            <div style="font-size:0.85rem; color:var(--paces-text-dark); margin-top:2px;">
              ✉️ <a href="mailto:{{ $partner->email }}" style="color:inherit; text-decoration:none;">{{ $partner->email }}</a>
            </div>
          </div>
        @endif

        <div style="border-top:1px dashed var(--paces-border); padding-top:12px; margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Trade Domain</div>
          <div style="font-size:0.9rem; font-weight:600; color:var(--paces-text-dark); margin-top:2px;">
            🛠️ {{ $partner->trade_domain }}
          </div>
        </div>

        <div style="margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Declared Workforce</div>
          <div style="font-size:0.85rem; font-weight:600; color:var(--paces-primary); margin-top:2px;">
            👷 {{ $partner->technicians_count }} Active Technicians
          </div>
        </div>

        <div style="border-top:1px dashed var(--paces-border); padding-top:12px; margin-bottom:14px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Coverage Regions</div>
          <div style="font-size:0.82rem; color:var(--paces-text-dark); margin-top:4px; line-height:1.4;">
            📍 {{ $partner->coverage_areas }}
          </div>
        </div>

        <div style="font-size:0.75rem; color:var(--paces-text-muted); border-top:1px solid var(--paces-border); padding-top:10px;">
          Applied: {{ $partner->created_at->format('M d, Y • h:i A') }}
          @if($partner->approved_at)
            <div style="margin-top:2px;">Approved: {{ $partner->approved_at->format('M d, Y') }}</div>
          @endif
        </div>
      </div>

      <!-- Verification Notes & Details -->
      <div class="card-paces">
        <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
          <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Contractor Verification & Credentials</h3>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label style="font-size:0.78rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase;">Admin Verification Notes</label>
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; font-size:0.85rem; color:var(--paces-text-dark); margin-top:6px; min-height:80px; white-space:pre-line;">
            {{ $partner->admin_notes ?: 'No verification notes added yet.' }}
          </div>
        </div>

        <div style="display:flex; gap:10px;">
          <a href="{{ route('admin.partners.edit', $partner) }}" class="btn-paces btn-paces-primary btn-paces-sm">
            Edit Full Profile →
          </a>
        </div>
      </div>
    </div>
  </div>

  <style>
    .partner-tabs-nav {
      scrollbar-width: thin;
    }
    .partner-tab-btn {
      padding: 10px 16px;
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--paces-text-muted);
      border: none;
      background: none;
      border-bottom: 3px solid transparent;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      margin-bottom: -2px;
    }
    .partner-tab-btn:hover {
      color: var(--paces-primary);
    }
    .partner-tab-btn.active {
      color: var(--paces-primary);
      border-bottom-color: var(--paces-primary);
      background: rgba(62, 96, 213, 0.05);
      border-radius: 6px 6px 0 0;
    }
    .partner-tab-pane {
      display: none;
    }
    .partner-tab-pane.active {
      display: block;
      animation: fadeInPaces 0.2s ease;
    }
    @keyframes fadeInPaces {
      from { opacity: 0; transform: translateY(4px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <script>
    function switchPartnerTab(tabId) {
      document.querySelectorAll('.partner-tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-tab') === tabId);
      });
      document.querySelectorAll('.partner-tab-pane').forEach(pane => {
        pane.classList.toggle('active', pane.id === tabId);
      });
      if (history.replaceState) {
        history.replaceState(null, null, '#' + tabId.replace('tab-', ''));
      }
    }

    function filterWorkOrders() {
      const q = (document.getElementById('workHistorySearch')?.value || '').toLowerCase().trim();
      const status = document.getElementById('workHistoryStatusFilter')?.value || 'all';
      const rows = document.querySelectorAll('#workOrdersTable tbody tr[data-status]');

      rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowSearch = row.getAttribute('data-search') || '';

        const matchesStatus = (status === 'all' || rowStatus === status);
        const matchesQuery = (!q || rowSearch.includes(q));

        row.style.display = (matchesStatus && matchesQuery) ? '' : 'none';
      });
    }

    function setDisburseAmount(amt) {
      const inp = document.getElementById('disburse_amount_input');
      if (inp) {
        inp.value = parseFloat(amt).toFixed(2);
        inp.focus();
      }
    }

    function toggleCustomMethod(val) {
      const wrapper = document.getElementById('customMethodWrapper');
      if (wrapper) {
        wrapper.style.display = (val === '' || val === null) ? 'block' : 'none';
      }
    }

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

    function togglePaymentInputs(val) {
      const bankDiv = document.getElementById('bankDetailsContainer');
      const mfsDiv = document.getElementById('mfsDetailsContainer');
      if (!bankDiv || !mfsDiv) return;
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

      const modelSelect = document.getElementById('earning_model');
      if (modelSelect) updateEarningRateUI(modelSelect.value);

      const rateInput = document.getElementById('payout_rate');
      if (rateInput) {
        rateInput.addEventListener('input', function() {
          const m = document.getElementById('earning_model')?.value;
          const h = document.getElementById('hidden_commission_rate');
          if (h && m === 'commission') h.value = this.value;
        });
      }

      // Check URL hash for direct tab linking (e.g. #payouts, #crews, #admin-setup)
      const hash = window.location.hash.replace('#', '');
      if (hash && document.getElementById('tab-' + hash)) {
        switchPartnerTab('tab-' + hash);
      } else {
        switchPartnerTab('tab-work-history');
      }
    });

    // Modals Logic
    function openAdminAvatarModal() {
      const modal = document.getElementById('adminAvatarModal');
      if (modal) modal.style.display = 'flex';
    }
    function closeAdminAvatarModal() {
      const modal = document.getElementById('adminAvatarModal');
      if (modal) modal.style.display = 'none';
    }
    function openAddPaymentMethodModal() {
      const modal = document.getElementById('adminPaymentMethodModal');
      if (modal) modal.style.display = 'flex';
    }
    function closeAddPaymentMethodModal() {
      const modal = document.getElementById('adminPaymentMethodModal');
      if (modal) modal.style.display = 'none';
    }
    function toggleModalPaymentType(val) {
      const bankFields = document.getElementById('modalBankFields');
      if (bankFields) {
        bankFields.style.display = (val === 'bank') ? 'block' : 'none';
      }
    }

    function openAdminEditPaymentMethodModal(pm) {
      const form = document.getElementById('adminEditPaymentMethodForm');
      form.action = '/admin/partners/{{ $partner->id }}/payment-methods/' + pm.id;
      document.getElementById('admin_edit_method_type').value = pm.method_type;
      toggleAdminEditPaymentType(pm.method_type);
      document.getElementById('admin_edit_number_type').value = pm.number_type || 'personal';
      document.getElementById('admin_edit_account_number').value = pm.account_number || '';
      document.getElementById('admin_edit_account_name').value = pm.account_name || '';
      document.getElementById('admin_edit_bank_name').value = pm.bank_name || '';
      document.getElementById('admin_edit_branch_name').value = pm.branch_name || '';
      document.getElementById('admin_edit_routing_number').value = pm.routing_number || '';
      document.getElementById('admin_edit_is_default').checked = !!pm.is_default;
      document.getElementById('adminEditPaymentMethodModal').style.display = 'flex';
    }

    function closeAdminEditPaymentMethodModal() {
      const modal = document.getElementById('adminEditPaymentMethodModal');
      if (modal) modal.style.display = 'none';
    }

    function toggleAdminEditPaymentType(val) {
      const bankFields = document.getElementById('adminEditModalBankFields');
      if (bankFields) {
        bankFields.style.display = (val === 'bank') ? 'block' : 'none';
      }
    }
  </script>

  <!-- ========================================================================= -->
  <!-- ADMIN AVATAR QUICK UPDATE MODAL                                           -->
  <!-- ========================================================================= -->
  <div id="adminAvatarModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:99999; backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target===this) closeAdminAvatarModal()">
    <div style="background:#ffffff; border-radius:12px; max-width:440px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.2); overflow:hidden; border:1px solid #e2e8f0;">
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <h4 style="margin:0; font-size:1rem; font-weight:700; color:var(--paces-primary);">
          📷 Update Profile Picture
        </h4>
        <button type="button" onclick="closeAdminAvatarModal()" style="background:none; border:none; font-size:1.25rem; color:#94a3b8; cursor:pointer; line-height:1;">✕</button>
      </div>

      <form action="{{ route('admin.partners.avatar.update', $partner) }}" method="POST" enctype="multipart/form-data" style="margin:0;">
        @csrf
        <div style="padding:20px;">
          <div style="text-align:center; margin-bottom:18px;">
            <div style="width:72px; height:72px; border-radius:50%; background:linear-gradient(135deg, var(--paces-primary) 0%, #1e293b 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:1.6rem; font-weight:700; overflow:hidden; border:3px solid #cbd5e1; margin:0 auto 10px;">
              @if($partner->avatar_url)
                <img src="{{ $partner->avatar_url }}" alt="{{ $partner->company_name }}" style="width:100%; height:100%; object-fit:cover;">
              @else
                <span>{{ $partner->initials }}</span>
              @endif
            </div>
            <div style="font-weight:700; font-size:0.92rem; color:#1e293b;">{{ $partner->company_name }}</div>
            <span style="font-size:0.75rem; color:#64748b;">{{ $partner->contact_person }}</span>
          </div>

          <div style="margin-bottom:14px;">
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem; margin-bottom:6px; display:block;">Select New Photo</label>
            <input type="file" name="avatar" class="form-control-paces" accept="image/jpeg,image/png,image/jpg,image/webp">
            <span style="font-size:0.72rem; color:#64748b; margin-top:4px; display:block;">JPG, PNG or WEBP (max 5MB).</span>
          </div>

          @if($partner->avatar)
            <div style="margin-top:10px; background:#fef2f2; border:1px solid #fecaca; border-radius:6px; padding:8px 12px;">
              <label style="display:flex; align-items:center; gap:8px; font-size:0.82rem; color:#991b1b; cursor:pointer; margin:0;">
                <input type="checkbox" name="remove_avatar" value="1" style="accent-color:#dc2626; width:16px; height:16px;">
                <span>Remove current profile picture and use initials</span>
              </label>
            </div>
          @endif
        </div>

        <div style="padding:14px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeAdminAvatarModal()" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:700;">Save Photo</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- ADMIN ADD PAYMENT METHOD MODAL                                            -->
  <!-- ========================================================================= -->
  <div id="adminPaymentMethodModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:99999; backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target===this) closeAddPaymentMethodModal()">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.2); overflow:hidden; border:1px solid #e2e8f0;">
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <div>
          <h4 style="margin:0; font-size:1.02rem; font-weight:700; color:var(--paces-primary);">
            + Add Payout / Receiving Method
          </h4>
          <span style="font-size:0.75rem; color:#64748b;">For {{ $partner->company_name }}</span>
        </div>
        <button type="button" onclick="closeAddPaymentMethodModal()" style="background:none; border:none; font-size:1.25rem; color:#94a3b8; cursor:pointer; line-height:1;">✕</button>
      </div>

      <form action="{{ route('admin.partners.payment-methods.store', $partner) }}" method="POST" style="margin:0;">
        @csrf
        <div style="padding:20px; display:flex; flex-direction:column; gap:14px;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Method Type <span style="color:#ef4444;">*</span></label>
              <select name="method_type" class="form-control-paces" required onchange="toggleModalPaymentType(this.value)">
                <option value="bkash">bKash (MFS)</option>
                <option value="nagad">Nagad (MFS)</option>
                <option value="rocket">Rocket (MFS)</option>
                <option value="upay">Upay (MFS)</option>
                <option value="bank">Bank Transfer</option>
              </select>
            </div>
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Wallet / Number Type <span style="color:#ef4444;">*</span></label>
              <select name="number_type" class="form-control-paces" required>
                <option value="personal">Personal</option>
                <option value="merchant">Merchant</option>
                <option value="agent">Agent</option>
              </select>
            </div>
          </div>

          <div>
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Account / Mobile Number <span style="color:#ef4444;">*</span></label>
            <input type="text" name="account_number" class="form-control-paces" required placeholder="e.g. 01700-000000 or Account No" value="{{ old('account_number') }}">
          </div>

          <div>
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Account Holder Name <span style="color:#ef4444;">*</span></label>
            <input type="text" name="account_name" class="form-control-paces" required placeholder="e.g. {{ $partner->contact_person }}" value="{{ old('account_name', $partner->contact_person) }}">
          </div>

          <!-- Bank-Specific Fields -->
          <div id="modalBankFields" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
            <div style="margin-bottom:10px;">
              <label class="form-label-paces" style="font-weight:600; font-size:0.78rem;">Bank Name</label>
              <input type="text" name="bank_name" class="form-control-paces" placeholder="e.g. Dutch-Bangla Bank PLC, BRAC Bank" style="font-size:0.82rem;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
              <div>
                <label class="form-label-paces" style="font-weight:600; font-size:0.78rem;">Branch Name</label>
                <input type="text" name="branch_name" class="form-control-paces" placeholder="e.g. Mirpur-1 Branch" style="font-size:0.82rem;">
              </div>
              <div>
                <label class="form-label-paces" style="font-weight:600; font-size:0.78rem;">Routing Number</label>
                <input type="text" name="routing_number" class="form-control-paces" placeholder="9-digit routing" style="font-size:0.82rem;">
              </div>
            </div>
          </div>

          <div>
            <label style="display:flex; align-items:center; gap:8px; font-size:0.82rem; color:#1e293b; cursor:pointer;">
              <input type="checkbox" name="is_default" value="1" {{ $paymentMethods->isEmpty() ? 'checked' : '' }} style="accent-color:var(--paces-primary); width:16px; height:16px;">
              <span style="font-weight:600;">Set as default receiving method</span>
            </label>
          </div>
        </div>

        <div style="padding:14px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeAddPaymentMethodModal()" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:700;">+ Save Payment Method</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- ADMIN EDIT PAYMENT METHOD MODAL                                           -->
  <!-- ========================================================================= -->
  <div id="adminEditPaymentMethodModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:99999; backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target===this) closeAdminEditPaymentMethodModal()">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.2); overflow:hidden; border:1px solid #e2e8f0;">
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <div>
          <h4 style="margin:0; font-size:1.02rem; font-weight:700; color:var(--paces-primary);">
            Edit Payout / Receiving Method
          </h4>
          <span style="font-size:0.75rem; color:#64748b;">For {{ $partner->company_name }}</span>
        </div>
        <button type="button" onclick="closeAdminEditPaymentMethodModal()" style="background:none; border:none; font-size:1.25rem; color:#94a3b8; cursor:pointer; line-height:1;">✕</button>
      </div>

      <form id="adminEditPaymentMethodForm" method="POST" style="margin:0;">
        @csrf
        @method('PUT')
        <div style="padding:20px; display:flex; flex-direction:column; gap:14px;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Method Type <span style="color:#ef4444;">*</span></label>
              <select name="method_type" id="admin_edit_method_type" class="form-control-paces" required onchange="toggleAdminEditPaymentType(this.value)">
                <option value="bkash">bKash (MFS)</option>
                <option value="nagad">Nagad (MFS)</option>
                <option value="rocket">Rocket (MFS)</option>
                <option value="upay">Upay (MFS)</option>
                <option value="bank">Bank Transfer</option>
              </select>
            </div>
            <div>
              <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Wallet / Number Type <span style="color:#ef4444;">*</span></label>
              <select name="number_type" id="admin_edit_number_type" class="form-control-paces" required>
                <option value="personal">Personal</option>
                <option value="merchant">Merchant</option>
                <option value="agent">Agent</option>
              </select>
            </div>
          </div>

          <div>
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Account / Mobile Number <span style="color:#ef4444;">*</span></label>
            <input type="text" name="account_number" id="admin_edit_account_number" class="form-control-paces" required placeholder="e.g. 01700-000000 or Account No">
          </div>

          <div>
            <label class="form-label-paces" style="font-weight:600; font-size:0.82rem;">Account Holder Name <span style="color:#ef4444;">*</span></label>
            <input type="text" name="account_name" id="admin_edit_account_name" class="form-control-paces" required placeholder="e.g. Account name">
          </div>

          <!-- Bank-Specific Fields -->
          <div id="adminEditModalBankFields" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px;">
            <div style="margin-bottom:10px;">
              <label class="form-label-paces" style="font-weight:600; font-size:0.78rem;">Bank Name</label>
              <input type="text" name="bank_name" id="admin_edit_bank_name" class="form-control-paces" placeholder="e.g. Dutch-Bangla Bank PLC, BRAC Bank" style="font-size:0.82rem;">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
              <div>
                <label class="form-label-paces" style="font-weight:600; font-size:0.78rem;">Branch Name</label>
                <input type="text" name="branch_name" id="admin_edit_branch_name" class="form-control-paces" placeholder="e.g. Mirpur-1 Branch" style="font-size:0.82rem;">
              </div>
              <div>
                <label class="form-label-paces" style="font-weight:600; font-size:0.78rem;">Routing Number</label>
                <input type="text" name="routing_number" id="admin_edit_routing_number" class="form-control-paces" placeholder="9-digit routing" style="font-size:0.82rem;">
              </div>
            </div>
          </div>

          <div>
            <label style="display:flex; align-items:center; gap:8px; font-size:0.82rem; color:#1e293b; cursor:pointer;">
              <input type="checkbox" name="is_default" id="admin_edit_is_default" value="1" style="accent-color:var(--paces-primary); width:16px; height:16px;">
              <span style="font-weight:600;">Set as default receiving method</span>
            </label>
          </div>
        </div>

        <div style="padding:14px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeAdminEditPaymentMethodModal()" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:700;">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</x-layouts.admin>
