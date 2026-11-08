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

    <!-- Total Earnings -->
    <div class="p-card p-stat-card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
        <div style="min-width: 0; flex: 1;">
          <span class="p-stat-label">Earnings</span>
          <div class="p-stat-value" style="color: #b45309;">৳ {{ number_format($stats['total_earnings']) }}</div>
        </div>
        <div class="p-stat-icon" style="background: #fffbeb; color: #d97706;">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Active Work Orders -->
  <div class="p-card">
    <div class="p-card-header">
      <h2 class="p-card-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
        <span>Active Work Orders</span>
      </h2>
      <a href="{{ route('partner.jobs.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--p-accent); text-decoration: none;">View All ({{ $activeJobs->count() }}) →</a>
    </div>

    @if($activeJobs->isEmpty())
      <div style="text-align: center; padding: 40px 16px; color: var(--p-muted);">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; color: #cbd5e1;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div style="font-weight: 600; font-size: 0.92rem; color: #475569;">No Active Orders</div>
      </div>
    @else
      <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($activeJobs->take(5) as $job)
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
            <div style="min-width: 220px;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <strong style="color: var(--p-primary); font-size: 0.94rem;">#{{ $job->booking_number }}</strong>
                <span class="p-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                @if($job->urgency && $job->urgency !== 'standard')
                  <span class="p-badge p-badge-danger" style="text-transform: uppercase;">{{ str_replace('_', ' ', $job->urgency) }}</span>
                @endif
              </div>
              <div style="font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-top: 3px;">
                {{ $job->service?->title ?? $job->amcPlan?->name ?? 'Facility Service' }}
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
                  <button type="button" class="btn-p btn-p-primary" style="padding: 6px 14px; font-size: 0.82rem;" onclick="confirmDialog({ title: 'Accept Job #{{ $job->booking_number }}?', text: 'Confirm accepting this service assignment.', type: 'success', confirmText: 'Accept Job', formId: 'dashAccept_{{ $job->id }}' })">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Accept</span>
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
      </div>
    @endif
  </div>

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
</x-partner.layouts.app>
