<x-layouts.app :title="'Booking #' . $booking->booking_number . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
  <style>
    /* Strict single-line rule for all badges and buttons */
    .badge, .btn, span[style*="border-radius"], [class*="badge"], [class*="btn"] {
      white-space: nowrap !important;
      flex-shrink: 0 !important;
      word-break: keep-all !important;
    }
  </style>

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

      @if(session('error'))
        <div data-alert style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:12px 18px; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; font-size:0.9rem;">
          <div style="display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#991b1b; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      @if(session('info'))
        <div data-alert style="background:#eff6ff; border:1px solid #60a5fa; color:#1e40af; padding:12px 18px; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; font-size:0.9rem;">
          <div style="display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>{{ session('info') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#1e40af; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
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
          <span class="badge" style="font-size:0.85rem; font-weight:700; padding:6px 14px; border-radius:20px; background:{{ $booking->status_badge_color }}22; color:{{ $booking->status_badge_color }}; white-space:nowrap !important; flex-shrink:0 !important;">
            {{ $booking->status_label }}
          </span>
        </div>

        @if($booking->canBeCancelledByCustomer())
          <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:10px; padding:12px 18px; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:10px;">
              <span style="display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:50%; background:#ffe4e6; color:#e11d48; font-weight:800;">✕</span>
              <div>
                <strong style="color:#9f1239; font-size:0.9rem; display:block;">Need to cancel this service request?</strong>
                <span style="font-size:0.8rem; color:#be123c;">This booking is pending review and has not yet been accepted by Operations. You can cancel it directly anytime.</span>
              </div>
            </div>
            <button type="button" onclick="openCancelModal('{{ $booking->booking_number }}', '{{ addslashes($booking->service?->title ?? ($booking->amcPlan?->name ?? 'Facility Service')) }}')" class="btn btn-danger btn-sm" style="background:#e11d48; color:#fff; border:none; padding:8px 16px; font-weight:700; border-radius:8px; cursor:pointer;">
              Cancel Request
            </button>
          </div>
        @elseif(!in_array($booking->status, ['completed', 'cancelled']))
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px;">
            <span style="font-size:1.2rem;">🔒</span>
            <div>
              <strong style="color:#1e293b; font-size:0.875rem; display:block;">Work Order Accepted by Operations</strong>
              <span style="font-size:0.8rem; color:#64748b;">This request has already been accepted and entered dispatch. Direct client cancellation is locked. If you need urgent changes or cancellation, please contact our operations desk.</span>
            </div>
          </div>
        @elseif($booking->status === 'completed' && !$booking->review_rating)
          <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 50%, #fff7ed 100%); border: 1.5px solid #f59e0b; border-radius: 12px; padding: 14px 18px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.12);">
            <div style="display: flex; align-items: center; gap: 10px;">
              <span style="font-size: 1.4rem;">⭐</span>
              <div>
                <strong style="color: #78350f; font-size: 0.95rem; display: block;">Service Completed — How was your experience?</strong>
                <span style="font-size: 0.8rem; color: #92400e;">Please take a moment to rate and review this completed service below.</span>
              </div>
            </div>
            <a href="#customerReviewCard" class="btn btn-accent btn-sm" style="font-weight: 700; padding: 7px 16px; border-radius: 8px; text-decoration: none; white-space: nowrap !important; flex-shrink: 0 !important; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff;">
              ⭐ Rate Service Below ↓
            </a>
          </div>
        @endif

        @if($booking->status === 'cancelled')
          <!-- Prominent Cancellation Banner -->
          <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:1.75rem; display:flex; align-items:flex-start; gap:16px;">
            <div style="width:40px; height:40px; border-radius:50%; background:#fee2e2; border:1.5px solid #fca5a5; color:#dc2626; display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:800; flex-shrink:0;">
              ✕
            </div>
            <div style="flex:1;">
              <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#991b1b;">
                  Service Request Cancelled
                </h3>
                <span style="font-size:0.75rem; font-weight:700; color:#dc2626; background:#ffffff; border:1px solid #fca5a5; padding:3px 10px; border-radius:8px;">
                  Cancelled on {{ $booking->cancelled_at ? $booking->cancelled_at->format('M d, Y • h:i A') : $booking->updated_at->format('M d, Y • h:i A') }}
                </span>
              </div>
              <p style="margin:8px 0 0; color:#7f1d1d; font-size:0.875rem; line-height:1.5;">
                @if($booking->cancellation_reason)
                  <strong>Cancellation Reason:</strong> {{ $booking->cancellation_reason }}
                @else
                  This service request was cancelled. If you need any assistance or would like to schedule a new appointment, you may create a new service booking anytime.
                @endif
              </p>
            </div>
          </div>
        @elseif(!$booking->is_amc)
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
                <strong style="color:#6d28d9; font-size:0.9rem; display:block;">Technician team is on the way to your address!</strong>
                <span style="font-size:0.82rem; color:#7c3aed;">Our technician team has started journey. {{ $booking->arrival_window ? 'Expected arrival window: ' . $booking->arrival_window : 'Heading to location.' }}</span>
              </div>
            </div>
          @elseif($booking->status === 'in_progress')
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">🔧</span>
              <div>
                <strong style="color:#15803d; font-size:0.9rem; display:block;">Working On Site</strong>
                <span style="font-size:0.82rem; color:#166534;">Our engineering team is actively servicing your facility on site.</span>
              </div>
            </div>
          @elseif($booking->status === 'assigned')
            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">👷</span>
              <div>
                <strong style="color:#1d4ed8; font-size:0.9rem; display:block;">Service Confirmed & Scheduled</strong>
                <span style="font-size:0.82rem; color:#2563eb;">A certified engineering team has been assigned and confirmed for your service booking.</span>
              </div>
            </div>
          @elseif($booking->status === 'accepted')
            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px 16px; margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
              <span style="font-size:1.5rem;">✓</span>
              <div>
                <strong style="color:#0369a1; font-size:0.9rem; display:block;">Booking Accepted</strong>
                <span style="font-size:0.82rem; color:#0284c7;">Operations headquarters accepted your booking. Preparing dispatch.</span>
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
                @if($booking->payment_status === 'paid' || $booking->payment_collected_by)
                  <div style="margin-top:4px;">
                    <span style="font-weight:700; color:#15803d; background:#dcfce7; font-size:0.75rem; padding:2px 8px; border-radius:6px; display:inline-block;">
                      ✓ Paid via {{ strtoupper($booking->payment_method ?: 'Cash') }}
                    </span>
                    <span style="font-size:0.72rem; color:#64748b; margin-top:2px; display:block;">
                      {{ $booking->payment_collected_by === 'crew' ? 'Paid on site to assigned crew' : 'Paid to FIX Operations' }}
                    </span>
                  </div>
                @endif
              @elseif($booking->estimated_cost > 0)
                <p style="margin:0; color:var(--primary); font-weight:700; font-size:1.1rem;">৳ {{ number_format($booking->estimated_cost, 2) }} <span style="font-size:0.75rem; font-weight:400; color:var(--text-muted);">(Agreed)</span></p>
              @elseif(in_array($booking->status, ['accepted', 'assigned', 'in_progress']))
                <p style="margin:0; color:#d97706; font-weight:600;">To be billed upon completion</p>
              @elseif($booking->status === 'cancelled')
                <p style="margin:0; color:#dc2626; font-weight:600;">Cancelled (No charge)</p>
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
                <span class="badge" style="font-size:0.8rem; background:#dcfce7; color:#166534; padding:3px 10px; border-radius:12px; font-weight:600; white-space:nowrap !important; flex-shrink:0 !important;">
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
                          <span class="badge" style="background:#dcfce7; color:#15803d; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:10px; white-space:nowrap !important; flex-shrink:0 !important;">✓ Paid</span>
                        @else
                          <span class="badge" style="background:#fef3c7; color:#92400e; font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:10px; white-space:nowrap !important; flex-shrink:0 !important;">Due: {{ $inv->due_date?->format('M d') }}</span>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endif

        @if($booking->arrival_window)
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px;">
            <span style="font-size:1.1rem;">⏱</span>
            <div style="font-size:0.875rem; color:#0f766e; font-weight:600;">
              Expected Arrival Window: {{ $booking->arrival_window }}
            </div>
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
            <div id="customerReviewCard" style="background:#fffdf5; border:2px solid #f59e0b; border-radius:12px; padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 4px 16px rgba(245,158,11,0.12);">
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                <span style="font-size:1.3rem;">⭐</span>
                <h3 style="font-size:1.2rem; font-weight:800; color:#78350f; margin:0;">
                  Rate Your Service Experience
                </h3>
              </div>
              <p style="font-size:0.85rem; color:#92400e; margin-bottom:16px;">
                How was the service delivered by our technicians? Your feedback helps us maintain high quality standards.
              </p>

              <form action="{{ route('customer.bookings.review', $booking->booking_number) }}" method="POST">
                @csrf
                <div style="margin-bottom:14px; background:#ffffff; border:1px solid #fde68a; border-radius:10px; padding:12px 14px;">
                  <label style="font-size:0.85rem; font-weight:700; color:#92400e; display:block; margin-bottom:6px;">Your Rating *</label>
                  <div class="star-rating-picker" data-target="detail_review_rating_input" data-label="detail_rating_label" style="display:inline-flex; align-items:center; gap:6px; font-size:1.8rem; color:#cbd5e1; cursor:pointer; user-select:none;">
                    <span class="star-item" data-value="1" style="color:#f59e0b; transition:transform 0.15s ease;" title="1 Star">★</span>
                    <span class="star-item" data-value="2" style="color:#f59e0b; transition:transform 0.15s ease;" title="2 Stars">★</span>
                    <span class="star-item" data-value="3" style="color:#f59e0b; transition:transform 0.15s ease;" title="3 Stars">★</span>
                    <span class="star-item" data-value="4" style="color:#f59e0b; transition:transform 0.15s ease;" title="4 Stars">★</span>
                    <span class="star-item" data-value="5" style="color:#f59e0b; transition:transform 0.15s ease;" title="5 Stars">★</span>
                    <span id="detail_rating_label" style="font-size:0.85rem; font-weight:800; color:#b45309; margin-left:10px;">5.0 (Excellent)</span>
                  </div>
                  <input type="hidden" name="review_rating" id="detail_review_rating_input" value="5">
                </div>

                <div style="margin-bottom:16px;">
                  <label style="font-size:0.85rem; font-weight:700; color:#78350f; display:block; margin-bottom:6px;">Your Comments / Review *</label>
                  <textarea name="review_comment" class="form-control" rows="3" placeholder="Share your feedback about the technicians, punctuality, and work quality..." style="width:100%; padding:10px 14px; border:1.5px solid #cbd5e1; border-radius:8px; font-size:0.875rem;" required></textarea>
                </div>

                <button type="submit" class="btn btn-accent" style="padding:0.75rem 1.6rem; font-size:0.9rem; font-weight:700; background:linear-gradient(135deg, #f59e0b, #d97706); border:none; color:#ffffff; border-radius:8px; box-shadow:0 2px 8px rgba(217,119,6,0.25); display:inline-flex; align-items:center; gap:6px;">
                  <span>⭐ Submit Feedback & Review</span>
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
                <div style="color:var(--text-muted); margin-top:2px;">{{ $log->customer_comment }}</div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  @if($booking->canBeCancelledByCustomer())
  <!-- ========================================================================= -->
  <!-- CUSTOMER CANCEL REQUEST MODAL                                             -->
  <!-- ========================================================================= -->
  <div id="customerCancelModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="if(event.target === this) closeCancelModal()">
    <div style="background:#ffffff; border-radius:14px; max-width:480px; width:100%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:modalPopIn 0.2s ease; border:1px solid #e2e8f0; overflow:hidden;">
      <div style="padding:16px 20px; border-bottom:1px solid #fee2e2; background:#fff1f2; display:flex; justify-content:space-between; align-items:center;">
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:#fecdd3; color:#e11d48; font-weight:800; font-size:0.9rem;">✕</span>
          <h4 style="margin:0; font-size:1.05rem; font-weight:800; color:#9f1239;">Cancel Service Request</h4>
        </div>
        <button type="button" onclick="closeCancelModal()" style="background:none; border:none; font-size:1.3rem; color:#94a3b8; cursor:pointer; padding:0 4px; line-height:1;">✕</button>
      </div>

      <form id="customerCancelForm" method="POST" action="">
        @csrf
        <div style="padding:20px;">
          <p style="font-size:0.875rem; color:#334155; margin:0 0 12px; line-height:1.5;">
            Are you sure you want to cancel request <strong id="cancelModalBookingNumber" style="color:var(--primary); font-family:monospace;"></strong> (<span id="cancelModalServiceTitle"></span>)?
          </p>
          <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 12px; margin-bottom:16px; font-size:0.8rem; color:#b91c1c;">
            ⚠️ Since this request hasn't been accepted yet, you can cancel it immediately without any charges.
          </div>

          <label style="font-size:0.825rem; font-weight:700; color:#1e293b; display:block; margin-bottom:6px;">
            Reason for cancellation (Optional):
          </label>
          <select name="cancellation_quick_reason" class="form-control" style="width:100%; font-size:0.85rem; margin-bottom:8px; padding:6px 10px; border:1px solid #cbd5e1; border-radius:6px;" onchange="if(this.value) { document.getElementById('cancelReasonTextarea').value = this.value; }">
            <option value="">-- Select a reason (or write details below) --</option>
            <option value="Schedule or timing conflict">Schedule or timing conflict</option>
            <option value="Booked by mistake / duplicate request">Booked by mistake / duplicate request</option>
            <option value="Problem resolved already">Problem resolved already</option>
            <option value="Found alternative solution">Found alternative solution</option>
            <option value="Changed mind">Changed my mind</option>
          </select>
          <textarea id="cancelReasonTextarea" name="cancellation_reason" class="form-control" rows="2" placeholder="Optional details..." style="width:100%; font-size:0.85rem; padding:8px 10px; border:1px solid #cbd5e1; border-radius:6px; box-sizing:border-box;"></textarea>
        </div>

        <div style="padding:14px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeCancelModal()" class="btn btn-outline btn-sm" style="font-size:0.825rem; font-weight:600; padding:6px 14px; border:1px solid #cbd5e1; background:#fff; cursor:pointer;">
            Keep Request
          </button>
          <button type="submit" class="btn btn-danger btn-sm" style="font-size:0.825rem; font-weight:700; padding:6px 16px; background:#e11d48; color:#fff; border:none; border-radius:6px; cursor:pointer;">
            Yes, Cancel Request
          </button>
        </div>
      </form>
    </div>
  </div>
  @endif

  <script>
    @if($booking->canBeCancelledByCustomer())
      function openCancelModal(bookingNumber, serviceTitle) {
        const modal = document.getElementById('customerCancelModal');
        const form = document.getElementById('customerCancelForm');
        const numSpan = document.getElementById('cancelModalBookingNumber');
        const titleSpan = document.getElementById('cancelModalServiceTitle');
        const textarea = document.getElementById('cancelReasonTextarea');

        form.action = '/my-bookings/' + encodeURIComponent(bookingNumber) + '/cancel';
        numSpan.textContent = '#' + bookingNumber;
        titleSpan.textContent = serviceTitle;
        textarea.value = '';

        modal.style.display = 'flex';
      }

      function closeCancelModal() {
        const modal = document.getElementById('customerCancelModal');
        if (modal) modal.style.display = 'none';
      }
    @endif

    document.addEventListener('DOMContentLoaded', function() {
      const labels = {
        1: '1.0 (Unsatisfactory)',
        2: '2.0 (Needs Improvement)',
        3: '3.0 (Average)',
        4: '4.0 (Good)',
        5: '5.0 (Excellent)'
      };

      document.querySelectorAll('.star-rating-picker').forEach(function(picker) {
        const targetInputId = picker.dataset.target;
        const targetLabelId = picker.dataset.label;
        const targetInput = document.getElementById(targetInputId);
        const targetLabel = document.getElementById(targetLabelId);
        const stars = picker.querySelectorAll('.star-item');

        function updateStars(val) {
          stars.forEach(function(s) {
            const sVal = parseInt(s.dataset.value);
            s.style.color = sVal <= val ? '#f59e0b' : '#cbd5e1';
          });
          if (targetLabel && labels[val]) {
            targetLabel.textContent = labels[val];
          }
          if (targetInput) {
            targetInput.value = val;
          }
        }

        stars.forEach(function(star) {
          star.addEventListener('click', function(e) {
            e.stopPropagation();
            const val = parseInt(this.dataset.value);
            updateStars(val);
          });
          star.addEventListener('mouseenter', function() {
            const val = parseInt(this.dataset.value);
            stars.forEach(function(s) {
              const sVal = parseInt(s.dataset.value);
              s.style.color = sVal <= val ? '#f59e0b' : '#cbd5e1';
            });
            if (targetLabel && labels[val]) {
              targetLabel.textContent = labels[val];
            }
          });
          star.addEventListener('mouseleave', function() {
            const currentVal = targetInput ? parseInt(targetInput.value) : 5;
            updateStars(currentVal);
          });
        });
      });
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        @if($booking->canBeCancelledByCustomer())
          closeCancelModal();
        @endif
      }
    });
  </script>
</x-layouts.app>
