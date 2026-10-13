<x-layouts.app title="Booking #{{ $booking->booking_number }} | FIX Facility Management">
  <section class="page-hero">
    <div class="container">
      <h1>Booking #{{ $booking->booking_number }}</h1>
      <p>Real-time dispatch timeline and technical work progress.</p>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container" style="max-width:850px;">
      <div style="margin-bottom:1rem;">
        <a href="{{ route('customer.bookings') }}" class="btn btn-outline btn-sm">← Back to My Bookings</a>
      </div>

      <div class="form-card" style="padding:2rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); padding-bottom:1.25rem; margin-bottom:1.5rem; flex-wrap:wrap; gap:12px;">
          <div>
            <h2 style="font-size:1.4rem; font-weight:800; color:var(--text-main); margin:0;">
              {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Service') }}
            </h2>
            <div style="font-size:0.85rem; color:var(--text-muted); margin-top:4px;">
              Booking Reference: <strong>#{{ $booking->booking_number }}</strong> • Requested on {{ $booking->created_at->format('M d, Y') }}
            </div>
          </div>
          <span style="font-size:0.85rem; font-weight:700; padding:6px 14px; border-radius:20px; background:{{ $booking->status_badge_color }}22; color:{{ $booking->status_badge_color }};">
            {{ $booking->status_label }}
          </span>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem; font-size:0.9rem;">
          <div>
            <strong style="color:var(--text-main); display:block; margin-bottom:4px;">Scheduled Appointment:</strong>
            <p style="margin:0; color:var(--text-muted);">{{ $booking->preferred_date->format('M d, Y') }} ({{ $booking->preferred_time_slot }})</p>
          </div>
          <div>
            <strong style="color:var(--text-main); display:block; margin-bottom:4px;">Service Address:</strong>
            <p style="margin:0; color:var(--text-muted);">{{ $booking->street_address }}, {{ $booking->area }}, {{ $booking->district }}</p>
          </div>
        </div>

        <div style="background:var(--bg-page); border-radius:10px; padding:1.25rem; margin-bottom:1.5rem; border:1px solid var(--border);">
          <strong style="color:var(--text-main); display:block; margin-bottom:6px;">Issue Description:</strong>
          <p style="margin:0; color:var(--text-muted); line-height:1.5;">{{ $booking->problem_description }}</p>
        </div>

        @if($booking->assigned_team)
          <div style="background:rgba(30,58,138,0.06); border:1px solid rgba(30,58,138,0.15); border-radius:10px; padding:1rem; margin-bottom:1.5rem; color:var(--primary); font-size:0.9rem;">
            <strong>Assigned Field Team:</strong> {{ $booking->assigned_team }}
          </div>
        @endif

        <!-- Audit Timeline -->
        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; color:var(--text-main);">Live Status Updates</h3>
        <div style="display:flex; flex-direction:column; gap:1rem;">
          @foreach($booking->statusLogs as $log)
            <div style="border-left:3px solid var(--primary); padding-left:14px; font-size:0.875rem;">
              <div style="font-weight:700; color:var(--text-main);">
                {{ ucfirst(str_replace('_', ' ', $log->to_status)) }}
                <span style="font-weight:400; font-size:0.75rem; color:var(--text-muted);">• {{ $log->created_at->format('M d, Y h:i A') }}</span>
              </div>
              @if($log->comment)
                <div style="color:var(--text-muted); margin-top:2px;">{{ $log->comment }}</div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
</x-layouts.app>
