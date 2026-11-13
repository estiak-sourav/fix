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

        @if(!$booking->is_amc)
          <!-- Live Progress Stepper for One-Off Services -->
          @php
            $statusOrder = ['pending_review', 'accepted', 'assigned', 'on_the_way', 'in_progress', 'completed'];
            $currentIdx = array_search($booking->status, $statusOrder);
            if ($currentIdx === false) {
                $currentIdx = ($booking->status === 'cancelled') ? -1 : 0;
            }
          @endphp

          <div class="customer-stepper-wrap" style="margin:1rem 0 1.5rem; padding:0 0.5rem; overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <div style="display:flex; justify-content:space-between; position:relative; min-width:420px;">
              <div style="position:absolute; top:14px; left:16px; right:16px; height:3px; background:#e2e8f0; z-index:1;"></div>
              @if($currentIdx >= 0)
                <div style="position:absolute; top:14px; left:16px; width:{{ ($currentIdx / (count($statusOrder) - 1)) * 100 }}%; height:3px; background:#10b981; z-index:2; transition:width 0.4s ease;"></div>
              @endif

              @foreach([
                'pending_review' => 'Pending',
                'accepted' => 'Accepted',
                'assigned' => 'Assigned',
                'on_the_way' => 'On the Way',
                'in_progress' => 'Working',
                'completed' => 'Completed'
              ] as $stepKey => $stepLabel)
                @php
                  $stepIdx = array_search($stepKey, $statusOrder);
                  $isPassed = $currentIdx !== false && $currentIdx >= $stepIdx;
                  $isCurrent = $currentIdx === $stepIdx;
                @endphp
                <div style="position:relative; z-index:3; text-align:center; min-width:60px;">
                  <div style="width:30px; height:30px; border-radius:50%; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;
                    background: {{ $isPassed ? '#10b981' : '#f1f5f9' }}; 
                    color: {{ $isPassed ? '#ffffff' : '#64748b' }};
                    border: 2px solid {{ $isPassed ? '#10b981' : '#cbd5e1' }};
                    box-shadow: {{ $isCurrent ? '0 0 0 4px rgba(16,185,129,0.2)' : 'none' }};">
                    @if($isPassed && !$isCurrent)
                      ✓
                    @else
                      {{ $stepIdx + 1 }}
                    @endif
                  </div>
                  <div style="font-size:0.75rem; font-weight:{{ $isCurrent ? '800' : '500' }}; color:{{ $isCurrent ? 'var(--primary)' : 'var(--text-muted)' }}; line-height:1.2;">
                    {{ $stepLabel }}
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          {{-- Live Real-time Status Alert Banner --}}
          @if($booking->status === 'on_the_way')
            <div style="background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">🚗</span>
              <div>
                <strong style="color:#6d28d9; font-size:0.9rem; display:block;">Crew is on the way to your address!</strong>
                <span style="font-size:0.82rem; color:#7c3aed;">Our technician team has started journey. {{ $booking->arrival_window ? 'Expected arrival window: ' . $booking->arrival_window : 'Heading to location.' }}</span>
              </div>
            </div>
          @elseif($booking->status === 'in_progress')
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">🔧</span>
              <div>
                <strong style="color:#15803d; font-size:0.9rem; display:block;">Working On Site</strong>
                <span style="font-size:0.82rem; color:#166534;">Our engineering crew is actively servicing your facility on site.</span>
              </div>
            </div>
          @elseif($booking->status === 'assigned')
            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">👷</span>
              <div>
                <strong style="color:#1d4ed8; font-size:0.9rem; display:block;">Field Crew Assigned & Confirmed</strong>
                <span style="font-size:0.82rem; color:#2563eb;">{{ $booking->assigned_team ?: ($booking->partner ? $booking->partner->company_name : 'Technician Team') }} has accepted your service booking.</span>
              </div>
            </div>
          @elseif($booking->status === 'accepted')
            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">✓</span>
              <div>
                <strong style="color:#0369a1; font-size:0.9rem; display:block;">Booking Accepted</strong>
                <span style="font-size:0.82rem; color:#0284c7;">Operations headquarters accepted your booking. Dispatching technician team shortly.</span>
              </div>
            </div>
          @endif
        @endif

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
            @if($booking->is_amc)
              <strong style="color:var(--text-main); display:block; margin-bottom:4px;">AMC Recurring Billing:</strong>
              <p style="margin:0; color:#7c3aed; font-weight:700; font-size:1.1rem;">
                ৳ {{ number_format($booking->recurring_amount ?: $booking->final_cost, 2) }}
                <span style="font-size:0.75rem; font-weight:500; color:var(--text-muted);">/ {{ $booking->billing_cycle_label }}</span>
              </p>
              <span style="font-size:0.75rem; color:#64748b;">Next Due: {{ $booking->next_billing_date ? $booking->next_billing_date->format('M d, Y') : 'Active' }}</span>
            @else
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

            @if($booking->amcInvoices->isNotEmpty())
              <div style="margin-top:1rem; border-top:1px dashed #bbf7d0; padding-top:0.75rem;">
                <strong style="font-size:0.82rem; color:#166534; display:block; margin-bottom:6px;">Recurring Invoices & Payment Status:</strong>
                <div style="display:flex; flex-direction:column; gap:6px;">
                  @foreach($booking->amcInvoices as $inv)
                    <div style="background:#ffffff; border:1px solid #dcfce7; border-radius:6px; padding:8px 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.82rem; flex-wrap:wrap; gap:6px;">
                      <div>
                        <strong style="color:#0f172a;">{{ $inv->invoice_number }}</strong>
                        <span style="color:#64748b; font-size:0.75rem; margin-left:4px;">({{ $inv->billing_cycle_label }})</span>
                      </div>
                      <div style="display:flex; align-items:center; gap:8px;">
                        <strong style="color:#166534;">৳ {{ number_format($inv->amount, 2) }}</strong>
                        @if($inv->status === 'paid')
                          <span style="background:#dcfce7; color:#15803d; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:10px;">✓ Paid</span>
                        @else
                          <span style="background:#fef3c7; color:#92400e; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:10px;">Due: {{ $inv->due_date?->format('M d') }}</span>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endif

        @if($booking->partner || $booking->assigned_team)
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1.2rem; margin-bottom:1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:6px;">
              <span style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Assigned Service Partner &amp; Crew</span>
              @if($booking->partner_job_status)
                @php
                  $pBadge = match($booking->partner_job_status) {
                    'on_the_way' => ['bg' => '#f3e8ff', 'color' => '#7e22ce', 'label' => 'On the Way (En Route)'],
                    'in_progress' => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Working On Site'],
                    'completed' => ['bg' => '#ecfdf5', 'color' => '#047857', 'label' => 'Service Completed'],
                    'accepted' => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'label' => 'Crew Assigned & Confirmed'],
                    'assigned' => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'Awaiting Crew Acceptance'],
                    'declined' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Declined (Reassigning)'],
                    default => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Dispatched'],
                  };
                @endphp
                <span style="background:{{ $pBadge['bg'] }}; color:{{ $pBadge['color'] }}; font-size:0.75rem; font-weight:700; padding:3px 10px; border-radius:12px;">
                  ● {{ $pBadge['label'] }}
                </span>
              @endif
            </div>

            <div style="font-size:1rem; font-weight:700; color:var(--primary);">
              {{ $booking->partner ? $booking->partner->company_name : $booking->assigned_team }}
            </div>

            @if($booking->assignedCrew)
              <div style="font-size:0.85rem; color:#334155; margin-top:4px;">
                👷 Lead Technician: <strong>{{ $booking->assignedCrew->name }}</strong> ({{ $booking->assignedCrew->trade_skill ?: 'Technician' }}) • 📞 <a href="tel:{{ $booking->assignedCrew->phone }}" style="color:var(--primary); font-weight:600; text-decoration:none;">{{ $booking->assignedCrew->phone }}</a>
              </div>
            @elseif($booking->partner)
              <div style="font-size:0.85rem; color:#475569; margin-top:4px;">
                Contact: {{ $booking->partner->contact_person }} • 📞 <a href="tel:{{ $booking->partner->phone }}" style="color:var(--primary); font-weight:600; text-decoration:none;">{{ $booking->partner->phone }}</a>
              </div>
            @endif

            @if($booking->arrival_window)
              <div style="font-size:0.82rem; color:#0d9488; font-weight:600; margin-top:6px;">
                ⏱ Expected Arrival Slot: {{ $booking->arrival_window }}
              </div>
            @endif
          </div>
        @endif

        <!-- Completed Work Order Feedback & Proof Photos from Operations -->
        @if($booking->status === 'completed')
          @if($booking->completion_feedback || (!empty($booking->completion_photos) && is_array($booking->completion_photos) && count($booking->completion_photos) > 0))
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1.25rem; margin-bottom:1.5rem;">
              <div style="font-weight:700; color:#166534; margin-bottom:6px; font-size:0.95rem;">
                ✓ Operations &amp; Work Completion Summary
              </div>
              @if($booking->completion_feedback)
                <p style="margin:0 0 10px 0; color:#14532d; font-size:0.9rem; line-height:1.5;">
                  "{{ $booking->completion_feedback }}"
                </p>
              @endif
              @if(!empty($booking->completion_photos) && is_array($booking->completion_photos) && count($booking->completion_photos) > 0)
                <div style="margin-top:8px;">
                  <span style="font-size:0.8rem; font-weight:700; color:#166534; display:block; margin-bottom:6px;">📸 On-Site Service Completion Photos:</span>
                  <div style="display:flex; flex-wrap:wrap; gap:8px;">
                    @foreach($booking->completion_photos as $cImg)
                      <a href="{{ asset(str_starts_with($cImg, 'storage/') ? $cImg : 'storage/' . $cImg) }}" target="_blank">
                        <img src="{{ asset(str_starts_with($cImg, 'storage/') ? $cImg : 'storage/' . $cImg) }}" alt="Completion Proof" style="width:76px; height:76px; object-fit:cover; border-radius:8px; border:2px solid #16a34a;">
                      </a>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          @endif
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
