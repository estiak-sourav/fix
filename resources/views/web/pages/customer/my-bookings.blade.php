<x-layouts.app title="My Bookings & Live Status | FIX Facility Management">
  <section class="page-hero">
    <div class="container">
      <h1>My Service Requests & Live Tracking</h1>
      <p>Monitor real-time status updates, engineering assignments, and audit logs for your facilities.</p>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container">
      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <span>{{ session('success') }}</span>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:12px;">
        <div>
          <h2 style="font-size:1.35rem; font-weight:700; margin:0; color:var(--text-main);">Active & Past Bookings</h2>
          <p style="margin:0; font-size:0.875rem; color:var(--text-muted);">Account: {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
        </div>
        <div style="display:flex; gap:10px;">
          <a href="{{ route('customer.profile') }}" class="btn btn-outline" style="padding:0.6rem 1rem; font-size:0.875rem;">Edit Profile</a>
          <a href="{{ route('booking.create') }}" class="btn btn-accent" style="padding:0.6rem 1.2rem; font-size:0.875rem;">+ New Service Request</a>
        </div>
      </div>

      @if($bookings->isEmpty())
        <div class="form-card" style="text-align:center; padding:3.5rem 1.5rem;">
          <div style="width:64px; height:64px; border-radius:50%; background:rgba(30,58,138,0.08); color:var(--primary); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
          </div>
          <h3 style="font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">No Service Requests Yet</h3>
          <p style="color:var(--text-muted); max-width:400px; margin:0 auto 1.5rem; font-size:0.9rem;">
            You have not placed any maintenance or AMC requests yet. Browse our comprehensive services to schedule your first service.
          </p>
          <a href="{{ route('booking.create') }}" class="btn btn-accent">Book a Service Now</a>
        </div>
      @else
        <div style="display:flex; flex-direction:column; gap:1.5rem;">
          @foreach($bookings as $booking)
            <div class="form-card" style="padding:1.5rem 1.75rem; border:1px solid var(--border);">
              <!-- Booking Card Header -->
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:1.25rem; border-bottom:1px solid var(--border); padding-bottom:1rem;">
                <div>
                  <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:1.1rem; font-weight:800; color:var(--primary);">#{{ $booking->booking_number }}</span>
                    <span style="display:inline-block; font-size:0.75rem; font-weight:700; padding:2px 8px; border-radius:12px; background:{{ $booking->status_badge_color }}22; color:{{ $booking->status_badge_color }};">
                      {{ $booking->status_label }}
                    </span>
                  </div>
                  <div style="font-size:0.85rem; color:var(--text-muted); margin-top:2px;">
                    Requested on {{ $booking->created_at->format('M d, Y • h:i A') }} • Scheduled: <strong>{{ $booking->preferred_date->format('M d, Y') }} ({{ $booking->preferred_time_slot }})</strong>
                  </div>
                </div>

                <div style="text-align:right;">
                  <div style="font-size:1.05rem; font-weight:700; color:var(--text-main);">
                    {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Service') }}
                  </div>
                  <div style="font-size:0.8rem; color:var(--text-muted);">
                    📍 {{ $booking->area }}, {{ $booking->district }}
                  </div>
                </div>
              </div>

              <!-- Live Progress Stepper -->
              @php
                $statusOrder = ['pending_review', 'accepted', 'assigned', 'in_progress', 'completed'];
                $currentIdx = array_search($booking->status, $statusOrder);
                if ($currentIdx === false && $booking->status === 'cancelled') $currentIdx = -1;
              @endphp

              <div style="margin:1.5rem 0 2rem;">
                <div style="display:flex; justify-content:space-between; position:relative;">
                  <div style="position:absolute; top:14px; left:10px; right:10px; height:3px; background:#e2e8f0; z-index:1;"></div>
                  @if($currentIdx >= 0)
                    <div style="position:absolute; top:14px; left:10px; width:{{ ($currentIdx / (count($statusOrder) - 1)) * 100 }}%; height:3px; background:#10b981; z-index:2; transition:width 0.4s ease;"></div>
                  @endif

                  @foreach([
                    'pending_review' => 'Pending',
                    'accepted' => 'Accepted',
                    'assigned' => 'Assigned',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed'
                  ] as $stepKey => $stepLabel)
                    @php
                      $stepIdx = array_search($stepKey, $statusOrder);
                      $isPassed = $currentIdx !== false && $currentIdx >= $stepIdx;
                      $isCurrent = $currentIdx === $stepIdx;
                    @endphp
                    <div style="position:relative; z-index:3; text-align:center; width:60px;">
                      <div style="width:28px; height:28px; border-radius:50%; margin:0 auto 6px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;
                        background: {{ $isPassed ? '#10b981' : '#e2e8f0' }}; 
                        color: {{ $isPassed ? '#ffffff' : '#64748b' }};
                        box-shadow: {{ $isCurrent ? '0 0 0 4px rgba(16,185,129,0.2)' : 'none' }};">
                        @if($isPassed && !$isCurrent)
                          ✓
                        @else
                          {{ $stepIdx + 1 }}
                        @endif
                      </div>
                      <div style="font-size:0.7rem; font-weight:{{ $isCurrent ? '700' : '500' }}; color:{{ $isCurrent ? 'var(--primary)' : 'var(--text-muted)' }}; line-height:1.2;">
                        {{ $stepLabel }}
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <!-- Details & Timeline Logs -->
              <div style="background:var(--bg-page); border-radius:10px; padding:1.25rem; font-size:0.875rem;">
                <div style="margin-bottom:0.75rem;">
                  <strong style="color:var(--text-main);">Problem Description:</strong>
                  <p style="margin:4px 0 0; color:var(--text-muted);">{{ $booking->problem_description }}</p>
                </div>

                @if($booking->assigned_team)
                  <div style="margin-bottom:0.75rem; color:#1e40af;">
                    <strong>Assigned Operations Team:</strong> {{ $booking->assigned_team }}
                  </div>
                @endif

                @if($booking->statusLogs->isNotEmpty())
                  <div style="margin-top:1rem; border-top:1px dashed var(--border); padding-top:0.75rem;">
                    <strong style="color:var(--text-main); font-size:0.85rem; display:block; margin-bottom:6px;">Latest Operations Update:</strong>
                    @foreach($booking->statusLogs->take(2) as $log)
                      <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:4px; display:flex; justify-content:space-between;">
                        <span>• {{ $log->comment ?: 'Status updated to ' . ucfirst(str_replace('_', ' ', $log->to_status)) }}</span>
                        <span style="color:#94a3b8;">{{ $log->created_at->diffForHumans() }}</span>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          @endforeach

          <div style="margin-top:1.5rem;">
            {{ $bookings->links() }}
          </div>
        </div>
      @endif
    </div>
  </section>
</x-layouts.app>
