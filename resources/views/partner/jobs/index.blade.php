<x-partner.layouts.app :title="'Work Orders'">
  <div class="p-card">
    <div class="p-card-header" style="flex-wrap: wrap; gap: 14px;">
      <h1 class="p-card-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Work Orders</span>
      </h1>

      <!-- Filter Pills -->
      <div style="display: flex; gap: 6px; flex-wrap: wrap;">
        <a href="{{ route('partner.jobs.index', ['status' => 'all']) }}" class="btn-p {{ $status === 'all' ? 'btn-p-primary' : 'btn-p-outline' }}" style="padding: 5px 12px; font-size: 0.78rem;">
          All ({{ $stats['total'] }})
        </a>
        <a href="{{ route('partner.jobs.index', ['status' => 'active']) }}" class="btn-p {{ $status === 'active' ? 'btn-p-primary' : 'btn-p-outline' }}" style="padding: 5px 12px; font-size: 0.78rem;">
          Active ({{ $stats['active'] }})
        </a>
        <a href="{{ route('partner.jobs.index', ['status' => 'completed']) }}" class="btn-p {{ $status === 'completed' ? 'btn-p-primary' : 'btn-p-outline' }}" style="padding: 5px 12px; font-size: 0.78rem;">
          Completed ({{ $stats['completed'] }})
        </a>
        <a href="{{ route('partner.jobs.index', ['status' => 'declined']) }}" class="btn-p {{ $status === 'declined' ? 'btn-p-primary' : 'btn-p-outline' }}" style="padding: 5px 12px; font-size: 0.78rem;">
          Declined
        </a>
      </div>
    </div>

    @if($jobs->isEmpty())
      <div style="text-align: center; padding: 48px 16px; color: var(--p-muted);">
        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; color: #cbd5e1;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div style="font-weight: 600; font-size: 0.95rem; color: #475569;">No Work Orders Found</div>
      </div>
    @else
      <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($jobs as $job)
          @php
            $badge = match($job->partner_job_status) {
              'assigned' => ['class' => 'p-badge-warning', 'label' => 'New'],
              'accepted' => ['class' => 'p-badge-info', 'label' => 'Accepted'],
              'on_the_way' => ['class' => 'p-badge-purple', 'label' => 'En Route'],
              'in_progress' => ['class' => 'p-badge-success', 'label' => 'In Progress'],
              'completed' => ['class' => 'p-badge-success', 'label' => 'Completed'],
              'declined' => ['class' => 'p-badge-danger', 'label' => 'Declined'],
              default => ['class' => 'p-badge-neutral', 'label' => ucfirst($job->partner_job_status ?? 'Assigned')],
            };
          @endphp
          <div style="border: 1px solid var(--p-border); border-radius: 12px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; background: #ffffff;">
            <div style="flex: 1; min-width: 250px;">
              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <strong style="color: var(--p-primary); font-size: 0.95rem;">#{{ $job->booking_number }}</strong>
                <span class="p-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                @if($job->urgency && $job->urgency !== 'standard')
                  <span class="p-badge p-badge-danger" style="text-transform: uppercase;">{{ str_replace('_', ' ', $job->urgency) }}</span>
                @endif
              </div>

              <div style="font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-top: 4px;">
                {{ $job->service?->title ?? $job->amcPlan?->name ?? 'Facility Service' }}
              </div>

              <div style="display: flex; align-items: center; gap: 16px; font-size: 0.78rem; color: var(--p-muted); margin-top: 5px; flex-wrap: wrap;">
                <span style="display: flex; align-items: center; gap: 4px;">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                  {{ $job->area }}
                </span>
                <span style="display: flex; align-items: center; gap: 4px;">
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  {{ $job->preferred_date?->format('M d') }} ({{ $job->arrival_window ?: $job->preferred_time_slot }})
                </span>
                @if($job->assignedCrew)
                  <span style="display: flex; align-items: center; gap: 4px; color: var(--p-accent); font-weight: 600;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    {{ $job->assignedCrew->name }}
                  </span>
                @endif
              </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; align-items: center; gap: 8px;">
              @if($job->partner_job_status === 'assigned')
                <form id="jobAccept_{{ $job->id }}" action="{{ route('partner.jobs.accept', $job) }}" method="POST" style="margin: 0;">
                  @csrf
                  <button type="button" class="btn-p btn-p-primary" style="padding: 6px 14px; font-size: 0.82rem;" onclick="confirmDialog({ title: 'Accept Job #{{ $job->booking_number }}?', text: 'Confirm accepting this service assignment.', type: 'success', confirmText: 'Accept Job', formId: 'jobAccept_{{ $job->id }}' })">
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

      <div style="margin-top: 20px;">
        {{ $jobs->links() }}
      </div>
    @endif
  </div>
</x-partner.layouts.app>
