<x-partner.layouts.app :title="'Leaves'">
  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em;">Leave Management</h1>
      </div>

      <div>
        @if($partner->is_currently_offline || $partner->leaves()->where('start_at', '<=', now())->where('end_at', '>=', now())->exists())
          <span class="p-badge p-badge-danger" style="font-size: 0.82rem; padding: 6px 14px;">
            <span class="status-dot offline" style="width: 7px; height: 7px;"></span>
            <span>Offline</span>
          </span>
        @else
          <span class="p-badge p-badge-success" style="font-size: 0.82rem; padding: 6px 14px;">
            <span class="status-dot online" style="width: 7px; height: 7px;"></span>
            <span>Online & Ready</span>
          </span>
        @endif
      </div>
    </div>
  </div>

  {{-- Status Toggle Strip --}}
  <div class="p-card" style="margin-bottom: 20px; padding: 14px 20px; border-left: 4px solid {{ $partner->is_currently_offline ? 'var(--p-danger)' : 'var(--p-success)' }};">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <span class="status-dot {{ $partner->is_currently_offline ? 'offline' : 'online' }}" style="width: 9px; height: 9px;"></span>
        <div>
          <strong style="font-size: 0.94rem; color: var(--p-primary);">
            Duty Status: {{ $partner->is_currently_offline ? 'Offline' : 'Online' }}
          </strong>
          @if($partner->is_currently_offline && $partner->offline_reason)
            <span style="font-size: 0.78rem; color: var(--p-muted); margin-left: 6px;">({{ $partner->offline_reason }})</span>
          @endif
        </div>
      </div>

      <div>
        <form id="availToggleForm" action="{{ route('partner.availability.toggle') }}" method="POST" style="margin: 0;">
          @csrf
          @if($partner->is_currently_offline)
            <button type="button" class="btn-p btn-p-success" onclick="confirmDialog({ title: 'Go Online?', text: 'You will become available for new jobs immediately.', type: 'success', confirmText: 'Go Online', formId: 'availToggleForm' })">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
              <span>Go Online</span>
            </button>
          @else
            <button type="button" class="btn-p btn-p-danger" onclick="confirmDialog({ title: 'Go Offline?', text: 'Fix dispatch will not assign new orders while offline.', type: 'warning', confirmText: 'Go Offline', formId: 'availToggleForm' })">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
              <span>Go Offline</span>
            </button>
          @endif
        </form>
      </div>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
    {{-- Schedule Leave Form --}}
    <div class="p-card">
      <h3 class="p-card-title" style="margin-bottom: 14px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        <span>Schedule Leave</span>
      </h3>

      <form id="createLeaveForm" action="{{ route('partner.availability.leave.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 12px;">
          <label class="p-label">Starts From *</label>
          <input type="datetime-local" name="start_at" class="p-input" value="{{ old('start_at', now()->addHours(1)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div style="margin-bottom: 12px;">
          <label class="p-label">Ends At *</label>
          <input type="datetime-local" name="end_at" class="p-input" value="{{ old('end_at', now()->addDays(1)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div style="margin-bottom: 16px;">
          <label class="p-label">Reason</label>
          <input type="text" name="reason" class="p-input" placeholder="e.g. Vacation, Personal" value="{{ old('reason') }}">
        </div>

        <button type="button" class="btn-p btn-p-primary" style="width: 100%; padding: 10px;" onclick="confirmDialog({ title: 'Schedule Leave?', text: 'You will be marked unavailable for assignments during this period.', type: 'primary', confirmText: 'Confirm Leave', formId: 'createLeaveForm' })">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          <span>Save Leave</span>
        </button>
      </form>
    </div>

    {{-- Scheduled Leaves Table --}}
    <div class="p-card">
      <h3 class="p-card-title" style="margin-bottom: 14px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span>Scheduled Leaves ({{ $leaves->count() }})</span>
      </h3>

      @if($leaves->isEmpty())
        <div style="text-align: center; padding: 36px 14px; color: var(--p-muted);">
          <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; color: #cbd5e1;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          <div style="font-weight: 600; font-size: 0.9rem; color: #475569;">No Leaves Scheduled</div>
        </div>
      @else
        <div class="p-table-wrapper">
          <table class="p-table">
            <thead>
              <tr>
                <th>Start</th>
                <th>End</th>
                <th>Reason</th>
                <th style="text-align: right;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($leaves as $leave)
                @php
                  $isActive = $leave->start_at->isPast() && $leave->end_at->isFuture();
                @endphp
                <tr>
                  <td>
                    <strong style="color: var(--p-primary); font-size: 0.82rem;">{{ $leave->start_at->format('M d, h:i A') }}</strong>
                    @if($isActive)
                      <span class="p-badge p-badge-danger" style="margin-left: 4px;">Active</span>
                    @endif
                  </td>
                  <td style="font-size: 0.82rem;">{{ $leave->end_at->format('M d, h:i A') }}</td>
                  <td style="font-size: 0.8rem; color: var(--p-muted);">{{ $leave->reason ?: 'Scheduled' }}</td>
                  <td style="text-align: right;">
                    <form id="deleteLeave_{{ $leave->id }}" action="{{ route('partner.availability.leave.destroy', $leave) }}" method="POST" style="margin: 0; display: inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn-p btn-p-outline" style="padding: 4px 8px; font-size: 0.75rem; color: #dc2626;" onclick="confirmDialog({ title: 'Remove Leave?', text: 'Are you sure you want to cancel this leave schedule?', type: 'danger', confirmText: 'Remove', formId: 'deleteLeave_{{ $leave->id }}' })" title="Delete">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</x-partner.layouts.app>
