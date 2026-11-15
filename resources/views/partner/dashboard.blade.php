<x-partner.layouts.app :title="'Dashboard'">
  <!-- Metric Counters Grid (2x2 on mobile, 4 in a row on desktop) -->
  <div class="p-stats-grid">
    <!-- Active Jobs -->
    <div class="p-card p-stat-card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
        <div style="min-width: 0; flex: 1;">
          <span class="p-stat-label">Active Orders</span>
          <div class="p-stat-value" style="color: var(--p-primary);">{{ $stats['active_count'] }}</div>
        </div>
        <div class="p-stat-icon" style="background: #f0fdfa; color: var(--p-accent);">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
      </div>
    </div>

    <!-- Today's Jobs -->
    <div class="p-card p-stat-card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
        <div style="min-width: 0; flex: 1;">
          <span class="p-stat-label">Today</span>
          <div class="p-stat-value" style="color: #1d4ed8;">{{ $stats['today_count'] }}</div>
        </div>
        <div class="p-stat-icon" style="background: #eff6ff; color: #2563eb;">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
      </div>
    </div>

    <!-- Completed Jobs -->
    <div class="p-card p-stat-card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
        <div style="min-width: 0; flex: 1;">
          <span class="p-stat-label">Completed</span>
          <div class="p-stat-value" style="color: #15803d;">{{ $stats['completed_count'] }}</div>
        </div>
        <div class="p-stat-icon" style="background: #f0fdf4; color: #16a34a;">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
      </div>
    </div>

    <!-- Current Balance -->
    <div class="p-card p-stat-card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
        <div style="min-width: 0; flex: 1;">
          <span class="p-stat-label">Current Balance</span>
          @if($stats['crew_owes_admin'])
            <div class="p-stat-value" style="color: #dc2626; white-space: nowrap !important;" title="Payable to FIX">
              -৳&nbsp;{{ number_format($stats['crew_due_amount'], 2) }}
            </div>
            <div style="font-size: 0.68rem; color: #dc2626; font-weight: 500; margin-top: 2px; white-space: nowrap;">
              ⚠️ Payable to FIX
            </div>
          @elseif($stats['admin_owes_crew'])
            <div class="p-stat-value" style="color: #15803d; white-space: nowrap !important;" title="Available to withdraw">
              ৳&nbsp;{{ number_format($stats['pending_due'], 2) }}
            </div>
            <div style="font-size: 0.68rem; color: #15803d; font-weight: 500; margin-top: 2px; white-space: nowrap;">
              ✓ Available to withdraw
            </div>
          @else
            <div class="p-stat-value" style="color: #334155; white-space: nowrap !important;" title="All Settled">
              ৳&nbsp;0.00
            </div>
            <div style="font-size: 0.68rem; color: #64748b; font-weight: 500; margin-top: 2px; white-space: nowrap;">
              ✓ All Settled
            </div>
          @endif
        </div>
        <div class="p-stat-icon" style="background: {{ $stats['crew_owes_admin'] ? '#fef2f2' : ($stats['admin_owes_crew'] ? '#f0fdf4' : '#f8fafc') }}; color: {{ $stats['crew_owes_admin'] ? '#dc2626' : ($stats['admin_owes_crew'] ? '#16a34a' : '#64748b') }};">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
            <line x1="2" y1="10" x2="22" y2="10"></line>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Financial Settlement & Balance Overview Widget (Full Card Tapable/Clickable) -->
  <a href="{{ route('partner.payouts.index') }}" class="p-card" style="display: block; text-decoration: none; color: inherit; margin-bottom: 20px; border: 1.5px solid {{ $stats['crew_owes_admin'] ? '#fca5a5' : ($stats['admin_owes_crew'] ? '#86efac' : '#cbd5e1') }}; background: {{ $stats['crew_owes_admin'] ? '#fff7ed' : ($stats['admin_owes_crew'] ? '#f0fdf4' : '#f8fafc') }}; padding: 18px 20px; cursor: pointer; transition: transform 0.15s ease, box-shadow 0.15s ease;">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.35rem;">
          {{ $stats['crew_owes_admin'] ? '⚠️' : ($stats['admin_owes_crew'] ? '💰' : '✓') }}
        </span>
        <div>
          <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: {{ $stats['crew_owes_admin'] ? '#9a3412' : ($stats['admin_owes_crew'] ? '#166534' : '#334155') }};">
            @if($stats['crew_owes_admin'])
              Payable Dues: ৳ {{ number_format($stats['crew_due_amount'], 2) }}
            @elseif($stats['admin_owes_crew'])
              Receivable: ৳ {{ number_format($stats['pending_due'], 2) }}
            @else
              Balance: Fully Settled
            @endif
          </h3>
        </div>
      </div>

      <div style="display: flex; align-items: center; color: {{ $stats['crew_owes_admin'] ? '#ea580c' : ($stats['admin_owes_crew'] ? '#16a34a' : '#94a3b8') }}; flex-shrink: 0;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; background: #ffffff; border-radius: 8px; padding: 10px 14px; border: 1px solid {{ $stats['crew_owes_admin'] ? '#fed7aa' : ($stats['admin_owes_crew'] ? '#bbf7d0' : '#e2e8f0') }};">
      <div>
        <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Earned</span>
        <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; margin-top: 2px;">
          ৳ {{ number_format($stats['total_earnings'], 2) }}
        </div>
      </div>

      <div>
        <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Cash Collected</span>
        <div style="font-weight: 800; color: #b45309; font-size: 0.95rem; margin-top: 2px;">
          ৳ {{ number_format($stats['cash_collected'], 2) }}
        </div>
      </div>

      <div>
        <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Payouts Received</span>
        <div style="font-weight: 800; color: #16a34a; font-size: 0.95rem; margin-top: 2px;">
          ৳ {{ number_format($stats['total_paid'], 2) }}
        </div>
      </div>

      <div>
        <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Status</span>
        <div style="margin-top: 2px;">
          @if($stats['crew_owes_admin'])
            <span style="font-size: 0.72rem; font-weight: 700; background: #fee2e2; color: #dc2626; padding: 2px 7px; border-radius: 6px;">
              Dues Pending
            </span>
          @elseif($stats['admin_owes_crew'])
            <span style="font-size: 0.72rem; font-weight: 700; background: #dcfce7; color: #15803d; padding: 2px 7px; border-radius: 6px;">
              Payable to You
            </span>
          @else
            <span style="font-size: 0.72rem; font-weight: 700; background: #f1f5f9; color: #475569; padding: 2px 7px; border-radius: 6px;">
              Balanced
            </span>
          @endif
        </div>
      </div>
    </div>
  </a>

  @php
    $hasAnyContent = $todaySchedule->isNotEmpty()
      || $upcomingThreeDaysSchedule->isNotEmpty()
      || $activeServiceOrders->isNotEmpty()
      || $activeAmcContracts->isNotEmpty()
      || $recentWorkHistory->isNotEmpty();
  @endphp

  @if(!$hasAnyContent)
    <!-- Overall Empty State (Only shown if completely nothing is scheduled, active, or recent) -->
    <div class="p-card" style="text-align: center; padding: 48px 20px; margin-bottom: 20px;">
      <div style="font-size: 2.2rem; margin-bottom: 8px;">📋</div>
      <h3 style="margin: 0 0 6px; font-size: 1.05rem; font-weight: 700; color: #334155;">No Current Activity</h3>
      <p style="margin: 0 auto; max-width: 420px; font-size: 0.84rem; color: #64748b; line-height: 1.45;">
        You currently have no scheduled tasks today, upcoming shifts for the next 3 days, active work orders, or recent work.
      </p>
      @if($stats['completed_count'] > 0)
        <div style="margin-top: 16px;">
          <a href="{{ route('partner.jobs.index', ['status' => 'completed']) }}" class="btn-p btn-p-outline btn-p-sm" style="display: inline-flex; align-items: center; gap: 6px;">
            <span>View Completed Work History ({{ $stats['completed_count'] }})</span>
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </a>
        </div>
      @endif
    </div>
  @endif

  {{-- 1. TODAY'S SCHEDULE --}}
  @if($todaySchedule->isNotEmpty())
    <div class="p-card" style="margin-bottom: 20px; border-left: 4px solid #2563eb;">
      <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
        <h2 class="p-card-title">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          <span>Today's Schedule</span>
        </h2>
        <span style="font-size: 0.76rem; font-weight: 700; background: #eff6ff; color: #1e40af; padding: 4px 10px; border-radius: 12px;">
          {{ $todaySchedule->count() }} Tasks Today
        </span>
      </div>

      <div style="display: flex; flex-direction: column; gap: 10px;">
        @foreach($todaySchedule as $item)
          <div style="border: 1px solid var(--p-border); border-radius: 10px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #ffffff;">
            <div style="min-width: 220px; flex: 1;">
              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <strong style="color: var(--p-primary); font-size: 0.92rem;">{{ $item['reference'] }}</strong>
                <span class="p-badge {{ $item['badge_class'] }}">{{ $item['badge_label'] }}</span>
                <span style="font-size: 0.78rem; font-weight: 700; color: #1d4ed8; background: #eff6ff; padding: 2px 7px; border-radius: 4px;">
                  ⏰ {{ $item['time_slot'] }}
                </span>
                @if(isset($item['payout']) && $item['payout'] > 0)
                  <span style="font-size: 0.74rem; font-weight: 700; color: #15803d; background: #f0fdf4; padding: 2px 7px; border-radius: 4px; border: 1px solid #bbf7d0;">
                    ৳ {{ number_format($item['payout'], 2) }}
                  </span>
                @endif
              </div>
              <div style="font-size: 0.88rem; font-weight: 600; color: #1e293b; margin-top: 3px;">
                {{ $item['title'] }}
              </div>
              <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span>👤 {{ $item['client_name'] }}</span>
                @if(!empty($item['area']))
                  <span>📍 {{ $item['area'] }}</span>
                @endif
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
              @if($item['item_type'] === 'booking' && $item['status'] === 'assigned')
                <form id="todayAccept_{{ $item['booking']->id }}" action="{{ route('partner.jobs.accept', $item['booking']) }}" method="POST" style="margin: 0;">
                  @csrf
                  @if($item['booking']->final_cost > 0)
                    <input type="hidden" name="agreed_price" value="{{ $item['booking']->final_cost }}">
                  @endif
                  <button type="button" class="btn-p btn-p-primary" style="padding: 5px 12px; font-size: 0.8rem;" onclick="confirmDialog({ title: 'Accept Job #{{ $item['booking']->booking_number }}?', text: 'Confirm accepting this service assignment.', type: 'success', confirmText: 'Accept Job', formId: 'todayAccept_{{ $item['booking']->id }}' })">
                    <span>Accept Job</span>
                  </button>
                </form>
              @endif
              <a href="{{ $item['url'] }}" class="btn-p btn-p-outline" style="padding: 5px 12px; font-size: 0.8rem;">
                <span>View</span>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- 2. ACTIVE WORK (Service Orders & Active AMC Work) --}}
  @if($activeServiceOrders->isNotEmpty() || $activeAmcContracts->isNotEmpty())
    <div class="p-card" style="margin-bottom: 20px;">
      <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
        <h2 class="p-card-title">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
          <span>Active Work</span>
        </h2>
        <a href="{{ route('partner.jobs.index', ['status' => 'active']) }}" style="font-size: 0.8rem; font-weight: 700; color: var(--p-accent); text-decoration: none;">View All ({{ $stats['active_count'] }}) →</a>
      </div>

      <div style="display: flex; flex-direction: column; gap: 12px;">
        {{-- Regular Service Orders --}}
        @foreach($activeServiceOrders as $job)
          @php
            $badge = match($job->partner_job_status) {
              'assigned' => ['class' => 'p-badge-warning', 'label' => 'New'],
              'accepted' => ['class' => 'p-badge-info', 'label' => 'Accepted'],
              'on_the_way' => ['class' => 'p-badge-purple', 'label' => 'En Route'],
              'in_progress' => ['class' => 'p-badge-success', 'label' => 'In Progress'],
              default => ['class' => 'p-badge-neutral', 'label' => ucfirst($job->partner_job_status ?? 'Assigned')],
            };
          @endphp
          <div style="border: 1px solid var(--p-border); border-radius: 12px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; background: #ffffff;">
            <div style="min-width: 220px; flex: 1;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <strong style="color: var(--p-primary); font-size: 0.94rem;">#{{ $job->booking_number }}</strong>
                <span class="p-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                @if($job->urgency && $job->urgency !== 'standard')
                  <span class="p-badge p-badge-danger" style="text-transform: uppercase;">{{ str_replace('_', ' ', $job->urgency) }}</span>
                @endif
              </div>
              <div style="font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-top: 3px;">
                {{ $job->service?->title ?? 'Facility Service' }}
              </div>
              <div style="display: flex; align-items: center; gap: 14px; font-size: 0.78rem; color: var(--p-muted); margin-top: 5px; flex-wrap: wrap;">
                <span style="display: flex; align-items: center; gap: 4px;">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                  {{ $job->area }}
                </span>
                <span style="display: flex; align-items: center; gap: 4px;">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  {{ $job->preferred_date?->format('M d') }} ({{ $job->arrival_window ?: $job->preferred_time_slot }})
                </span>
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
              @if($job->partner_job_status === 'assigned')
                <form id="dashAccept_{{ $job->id }}" action="{{ route('partner.jobs.accept', $job) }}" method="POST" style="margin: 0;">
                  @csrf
                  @if($job->final_cost > 0)
                    <input type="hidden" name="agreed_price" value="{{ $job->final_cost }}">
                  @endif
                  <button type="button" class="btn-p btn-p-primary" style="padding: 6px 14px; font-size: 0.82rem;" onclick="confirmDialog({ title: 'Accept Job #{{ $job->booking_number }}?', text: '{{ $job->final_cost > 0 ? 'Agreed Price: ৳ ' . number_format($job->final_cost, 2) : ($job->estimated_cost > 0 ? 'Admin Estimate: ৳ ' . number_format($job->estimated_cost, 2) : 'Price will be set before starting work on site.') }}. Confirm accepting this assignment.', type: 'success', confirmText: 'Accept Job', formId: 'dashAccept_{{ $job->id }}' })">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Accept Job</span>
                  </button>
                </form>
              @endif
              <a href="{{ route('partner.jobs.show', $job) }}" class="btn-p btn-p-outline" style="padding: 6px 14px; font-size: 0.82rem;">
                <span>View</span>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </div>
          </div>
        @endforeach

        {{-- Active AMC Facility Work & Contracts --}}
        @foreach($activeAmcContracts as $contract)
          <div style="border: 1px solid #c7d2fe; border-radius: 12px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; background: #f8faff;">
            <div style="min-width: 220px; flex: 1;">
              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <strong style="color: #1e1b4b; font-size: 0.94rem;">#{{ $contract->booking_number }}</strong>
                <span class="p-badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700;">AMC CONTRACT</span>
                <span class="p-badge p-badge-info">{{ strtoupper($contract->contract_status ?: 'ACTIVE') }}</span>
                <span style="font-size: 0.76rem; font-weight: 700; color: #15803d; background: #f0fdf4; padding: 2px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                  {{ $contract->completed_shifts_count }} / {{ $contract->amcShifts()->count() }} Shifts Completed
                </span>
              </div>
              <div style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin-top: 4px;">
                {{ $contract->amcPlan?->name ?? 'Annual Facility Maintenance Contract' }}
              </div>
              <div style="display: flex; align-items: center; gap: 14px; font-size: 0.78rem; color: #64748b; margin-top: 5px; flex-wrap: wrap;">
                <span>👤 {{ $contract->customer_name }}</span>
                <span>📍 {{ $contract->area }}</span>
                <span>📋 Billing: {{ $contract->amc_billing_type_label }}</span>
                @if($contract->payout_per_shift > 0)
                  <span style="font-weight: 700; color: #15803d;">৳ {{ number_format($contract->payout_per_shift, 2) }} / Shift</span>
                @endif
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
              <a href="{{ route('partner.jobs.show', $contract) }}#tab-amc" class="btn-p btn-p-primary" style="padding: 6px 14px; font-size: 0.82rem; background: #4338ca; border-color: #4338ca;">
                <span>Open Contract & Shifts</span>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- 3. UPCOMING SCHEDULE (NEXT 3 DAYS ONLY) --}}
  @if($upcomingThreeDaysSchedule->isNotEmpty())
    <div class="p-card" style="margin-bottom: 20px;">
      <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
        <h2 class="p-card-title">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #0284c7;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          <span>Upcoming Schedule (Next 3 Days)</span>
        </h2>
        <span style="font-size: 0.76rem; font-weight: 700; background: #f0f9ff; color: #0369a1; padding: 4px 10px; border-radius: 12px;">
          {{ $upcomingThreeDaysSchedule->count() }} Dispatches
        </span>
      </div>

      <div style="display: flex; flex-direction: column; gap: 10px;">
        @foreach($upcomingThreeDaysSchedule as $item)
          <div style="border: 1px solid var(--p-border); border-radius: 10px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #ffffff;">
            <div style="min-width: 220px; flex: 1;">
              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="font-size: 0.78rem; font-weight: 800; color: #0284c7; background: #f0f9ff; padding: 2px 8px; border-radius: 4px; border: 1px solid #bae6fd;">
                  📅 {{ $item['date_label'] }}
                </span>
                <strong style="color: var(--p-primary); font-size: 0.88rem;">{{ $item['reference'] }}</strong>
                <span class="p-badge {{ $item['is_amc'] ? 'p-badge-purple' : 'p-badge-info' }}">{{ $item['is_amc'] ? 'AMC Routine' : 'Service Job' }}</span>
                <span style="font-size: 0.78rem; color: #64748b;">⏰ {{ $item['time_slot'] }}</span>
                @if(isset($item['payout']) && $item['payout'] > 0)
                  <span style="font-size: 0.74rem; font-weight: 700; color: #15803d; background: #f0fdf4; padding: 2px 7px; border-radius: 4px; border: 1px solid #bbf7d0;">
                    ৳ {{ number_format($item['payout'], 2) }}
                  </span>
                @endif
              </div>
              <div style="font-size: 0.88rem; font-weight: 600; color: #1e293b; margin-top: 3px;">
                {{ $item['title'] }}
              </div>
              <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span>👤 {{ $item['client_name'] }}</span>
                @if(!empty($item['area']))
                  <span>📍 {{ $item['area'] }}</span>
                @endif
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
              <a href="{{ $item['url'] }}" class="btn-p btn-p-outline" style="padding: 5px 12px; font-size: 0.8rem;">
                Open →
              </a>
              @if($item['item_type'] === 'amc_shift' && isset($item['shift']) && !in_array($item['shift']->status, ['completed', 'cancelled', 'reassignment_needed']))
                <button type="button" class="btn-p btn-p-sm" style="padding: 5px 12px; font-size: 0.8rem; background: #fff1f2; color: #dc2626; border: 1px solid #fecdd3; font-weight: 600; border-radius: 8px;" onclick="openCrewCancelShiftModal('{{ $item['booking']->id }}', '{{ $item['shift']->id }}', '{{ $item['shift']->shift_number }}')">
                  Decline
                </button>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- 4. RECENT WORK (LAST 3 DAYS HISTORY) --}}
  @if($recentWorkHistory->isNotEmpty())
    <div class="p-card" style="margin-bottom: 20px;">
      <div class="p-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
        <h2 class="p-card-title">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #16a34a;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          <span>Recent Work (Last 3 Days)</span>
        </h2>
        <a href="{{ route('partner.jobs.index', ['status' => 'completed']) }}" style="font-size: 0.8rem; font-weight: 700; color: var(--p-accent); text-decoration: none;">View All History ({{ $stats['completed_count'] }}) →</a>
      </div>

      <div style="display: flex; flex-direction: column; gap: 10px;">
        @foreach($recentWorkHistory as $item)
          <div style="border: 1px solid var(--p-border); border-radius: 10px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #ffffff;">
            <div style="min-width: 220px; flex: 1;">
              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <strong style="color: var(--p-primary); font-size: 0.88rem;">{{ $item['reference'] }}</strong>
                <span class="p-badge p-badge-success">COMPLETED</span>
                <span style="font-size: 0.76rem; color: #64748b;">✓ {{ $item['completed_at_label'] }}</span>
                @if(isset($item['earnings']) && $item['earnings'] > 0)
                  <span style="font-size: 0.76rem; font-weight: 700; color: #15803d; background: #f0fdf4; padding: 2px 7px; border-radius: 4px; border: 1px solid #bbf7d0;">
                    +৳ {{ number_format($item['earnings'], 2) }}
                  </span>
                @endif
              </div>
              <div style="font-size: 0.88rem; font-weight: 600; color: #1e293b; margin-top: 3px;">
                {{ $item['title'] }}
              </div>
              <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span>👤 {{ $item['client_name'] }}</span>
                @if(!empty($item['area']))
                  <span>📍 {{ $item['area'] }}</span>
                @endif
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
              <a href="{{ $item['url'] }}" class="btn-p btn-p-outline" style="padding: 5px 12px; font-size: 0.8rem;">
                View Details
              </a>
            </div>
          </div>
        @endforeach
      </div>

      <div style="margin-top: 14px; text-align: center; border-top: 1px dashed var(--p-border); padding-top: 12px;">
        <a href="{{ route('partner.jobs.index', ['status' => 'completed']) }}" style="font-size: 0.82rem; font-weight: 700; color: var(--p-accent); text-decoration: none;">
          View All Completed Work History ({{ $stats['completed_count'] }}) →
        </a>
      </div>
    </div>
  @endif

  <!-- Internal Crew Quick List (For Vendors only) -->
  @if($partner->isVendor())
    <div class="p-card">
      <div class="p-card-header">
        <h2 class="p-card-title">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          <span>Technicians</span>
        </h2>
        <a href="{{ route('partner.crews.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--p-accent); text-decoration: none;">Manage ({{ $partner->crews()->count() }}) →</a>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px;">
        @forelse($partner->crews()->where('is_active', true)->take(6)->get() as $cr)
          <div style="background: #f8fafc; border: 1px solid var(--p-border); border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 10px;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.82rem;">
              {{ strtoupper(substr($cr->name, 0, 1)) }}
            </div>
            <div>
              <strong style="color: var(--p-primary); font-size: 0.86rem; display: block;">{{ $cr->name }}</strong>
              <span style="font-size: 0.74rem; color: var(--p-muted);">{{ $cr->trade_skill ?: 'Technician' }}</span>
            </div>
          </div>
        @empty
          <div style="font-size: 0.82rem; color: var(--p-muted); padding: 8px 0;">
            No technicians added. <a href="{{ route('partner.crews.index') }}" style="color: var(--p-accent); font-weight: 600;">+ Add Technician</a>
          </div>
        @endforelse
      </div>
    </div>
  @endif

  <!-- Modal: Crew Decline/Cancel AMC Shift -->
  <div id="crewCancelShiftModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.65); z-index: 99999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px;" onclick="if(event.target===this) this.style.display='none'">
    <div style="background: #ffffff; border-radius: 12px; max-width: 440px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e2e8f0; overflow: hidden;">
      <div style="padding: 14px 18px; border-bottom: 1px solid #fee2e2; background: #fff1f2; display: flex; justify-content: space-between; align-items: center;">
        <h4 style="margin: 0; font-size: 0.98rem; font-weight: 700; color: #991b1b;">Decline AMC Shift <span id="crewCancelShiftNum"></span></h4>
        <button type="button" onclick="document.getElementById('crewCancelShiftModal').style.display='none'" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer;">✕</button>
      </div>

      <form id="crewCancelShiftForm" method="POST" action="">
        @csrf
        <div style="padding: 18px;">
          <p style="font-size: 0.82rem; color: #475569; margin: 0 0 12px; line-height: 1.45;">
            If your technician cannot attend this scheduled maintenance shift, submit your reason. The admin will be alerted immediately to assign a replacement crew.
          </p>

          <label class="p-form-label" style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 6px;">Reason for Declining *</label>
          <textarea name="cancellation_reason" class="p-form-input" rows="3" placeholder="e.g. Technician unavailable, emergency leave, equipment issue..." required style="width: 100%; box-sizing: border-box; border-radius: 8px; border: 1px solid var(--p-border); padding: 8px 10px; font-size: 0.84rem;"></textarea>
        </div>

        <div style="padding: 12px 18px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" onclick="document.getElementById('crewCancelShiftModal').style.display='none'" class="btn-p btn-p-outline btn-p-sm">Go Back</button>
          <button type="submit" class="btn-p btn-p-sm" style="background: #dc2626; color: #fff; border: none; font-weight: 700; padding: 6px 14px;">Confirm Decline</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openCrewCancelShiftModal(bookingId, shiftId, shiftNumber) {
      const modal = document.getElementById('crewCancelShiftModal');
      const form = document.getElementById('crewCancelShiftForm');
      if (form) {
        form.action = '/partner/jobs/' + bookingId + '/amc-shifts/' + shiftId + '/cancel';
      }
      const numSpan = document.getElementById('crewCancelShiftNum');
      if (numSpan) numSpan.textContent = '#' + shiftNumber;
      if (modal) modal.style.display = 'flex';
    }
  </script>
</x-partner.layouts.app>
