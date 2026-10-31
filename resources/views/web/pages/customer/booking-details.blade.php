<x-layouts.app :title="'Booking #' . $booking->booking_number . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
  <x-page-hero 
    title="Booking #{{ $booking->booking_number }}"
    subtitle="Real-time dispatch timeline and technical work progress."
  />

  <section class="section" style="padding-top:0;">
    <div class="container" style="max-width:850px;">
      <div style="margin-bottom:1rem;">
        <a href="{{ route('customer.bookings') }}" class="btn btn-outline btn-sm">← Back to My Bookings</a>
      </div>

      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:12px 18px; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; font-size:0.9rem;">
          <div style="display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

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

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem; margin-bottom:1.5rem; font-size:0.9rem;">
          <div>
            <strong style="color:var(--text-main); display:block; margin-bottom:4px;">Scheduled Appointment:</strong>
            <p style="margin:0; color:var(--text-muted);">{{ $booking->preferred_date?->format('M d, Y') }} ({{ $booking->preferred_time_slot }})</p>
          </div>
          <div>
            <strong style="color:var(--text-main); display:block; margin-bottom:4px;">Service Address:</strong>
            <p style="margin:0; color:var(--text-muted);">{{ $booking->street_address }}, {{ $booking->area }}, {{ $booking->district }}</p>
          </div>
          <div>
            <strong style="color:var(--text-main); display:block; margin-bottom:4px;">Billing / Agreed Price:</strong>
            @if($booking->final_cost > 0)
              <p style="margin:0; color:#16a34a; font-weight:700; font-size:1.1rem;">৳ {{ number_format($booking->final_cost, 2) }} <span style="font-size:0.75rem; font-weight:400; color:var(--text-muted);">(Invoiced)</span></p>
            @elseif($booking->estimated_cost > 0)
              <p style="margin:0; color:var(--primary); font-weight:700; font-size:1.1rem;">৳ {{ number_format($booking->estimated_cost, 2) }} <span style="font-size:0.75rem; font-weight:400; color:var(--text-muted);">(Agreed)</span></p>
            @elseif(in_array($booking->status, ['accepted', 'assigned', 'in_progress']))
              <p style="margin:0; color:#d97706; font-weight:600;">To be billed upon completion</p>
            @else
              <p style="margin:0; color:var(--text-muted);">Pending phone discussion</p>
            @endif
          </div>
        </div>

        <div style="background:var(--bg-page); border-radius:10px; padding:1.25rem; margin-bottom:1.5rem; border:1px solid var(--border);">
          <strong style="color:var(--text-main); display:block; margin-bottom:6px;">Issue Description:</strong>
          <p style="margin:0; color:var(--text-muted); line-height:1.5;">{{ $booking->problem_description }}</p>
        </div>

        @if($booking->contract_status === 'active' || $booking->amcPlan)
          <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1.25rem; margin-bottom:1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; flex-wrap:wrap; gap:8px;">
              <strong style="color:#166534; font-size:1rem;">
                ✓ Active Annual Maintenance Contract (AMC): {{ $booking->amcPlan?->name ?? 'Annual Enterprise AMC' }}
              </strong>
              @if($booking->contract_start_date && $booking->contract_end_date)
                <span style="font-size:0.8rem; background:#dcfce7; color:#166534; padding:3px 10px; border-radius:12px; font-weight:600;">
                  Period: {{ $booking->contract_start_date->format('M d, Y') }} — {{ $booking->contract_end_date->format('M d, Y') }}
                </span>
              @endif
            </div>

            @if($booking->amc_package_notes)
              <p style="margin:0 0 10px 0; font-size:0.88rem; color:#14532d; line-height:1.5;">
                {{ $booking->amc_package_notes }}
              </p>
            @endif

            @if($booking->agreement_document_path)
              <div style="margin-top:8px;">
                <a href="{{ $booking->agreement_url }}" target="_blank" class="btn btn-outline btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-size:0.82rem; background:#fff;">
                  <span>📄 View Signed Agreement Document ↗</span>
                </a>
              </div>
            @endif
          </div>
        @endif

        @if($booking->assigned_team)
          <div style="background:rgba(30,58,138,0.06); border:1px solid rgba(30,58,138,0.15); border-radius:10px; padding:1rem; margin-bottom:1.5rem; color:var(--primary); font-size:0.9rem;">
            <strong>Assigned Field Team:</strong> {{ $booking->assigned_team }}
          </div>
        @endif

        <!-- Completed Work Order Feedback from Operations -->
        @if($booking->status === 'completed' && $booking->completion_feedback)
          <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1.25rem; margin-bottom:1.5rem;">
            <div style="font-weight:700; color:#166534; margin-bottom:4px; font-size:0.95rem;">
              ✓ Operations Team Completion Remarks
            </div>
            <p style="margin:0; color:#14532d; font-size:0.9rem; line-height:1.5;">
              "{{ $booking->completion_feedback }}"
            </p>
          </div>
        @endif

        <!-- Customer Review & Rating Section -->
        @if($booking->status === 'completed')
          @if($booking->review_rating)
            <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:1.25rem; margin-bottom:1.5rem;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-weight:700; color:#92400e;">Your Submitted Review</span>
                <span style="color:#f59e0b; font-size:1.1rem;">
                  @for($i = 1; $i <= 5; $i++)
                    {{ $i <= $booking->review_rating ? '★' : '☆' }}
                  @endfor
                </span>
              </div>
              <p style="margin:0; color:#78350f; font-size:0.9rem; line-height:1.5;">
                "{{ $booking->review_comment }}"
              </p>
              <div style="font-size:0.75rem; color:#b45309; margin-top:6px;">
                Reviewed on {{ $booking->reviewed_at?->format('M d, Y') }}
              </div>
            </div>
          @else
            <div style="background:#ffffff; border:2px dashed var(--primary); border-radius:10px; padding:1.5rem; margin-bottom:1.5rem;">
              <h3 style="font-size:1.15rem; font-weight:700; color:var(--text-main); margin:0 0 6px;">
                Rate Your Service Experience
              </h3>
              <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:14px;">
                How was the service provided by our engineering team? Your review helps us continuously improve.
              </p>

              <form action="{{ route('customer.bookings.review', $booking->booking_number) }}" method="POST">
                @csrf
                <div style="margin-bottom:12px;">
                  <label style="font-size:0.85rem; font-weight:700; color:var(--text-main); display:block; margin-bottom:6px;">Your Rating (1 to 5 Stars) *</label>
                  <select name="review_rating" class="form-control" style="max-width:200px;" required>
                    <option value="5" selected>★★★★★ 5 Stars (Excellent)</option>
                    <option value="4">★★★★☆ 4 Stars (Good)</option>
                    <option value="3">★★★☆☆ 3 Stars (Average)</option>
                    <option value="2">★★☆☆☆ 2 Stars (Needs Improvement)</option>
                    <option value="1">★☆☆☆☆ 1 Star (Unsatisfactory)</option>
                  </select>
                </div>

                <div style="margin-bottom:14px;">
                  <label style="font-size:0.85rem; font-weight:700; color:var(--text-main); display:block; margin-bottom:6px;">Your Comments / Review *</label>
                  <textarea name="review_comment" class="form-control" rows="3" placeholder="Share your feedback about the technicians, punctuality, and work quality..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="padding:0.6rem 1.4rem; font-size:0.875rem;">
                  Submit Feedback & Review
                </button>
              </form>
            </div>
          @endif
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
