<x-layouts.app :title="'My Bookings & Live Status | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
  <style>
    .customer-booking-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 1.75rem 2rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.03);
      transition: all 0.2s ease;
    }
    .customer-booking-card:hover {
      box-shadow: 0 6px 18px rgba(0,0,0,0.06);
      border-color: #cbd5e1;
    }

    /* Completed Service Compact Item */
    .completed-booking-item {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .completed-booking-item:hover {
      border-color: #10b981;
      background: #f0fdf4;
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(16,185,129,0.08);
    }
    @media (max-width: 640px) {
      .completed-booking-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
      }
      .completed-item-right {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed #e2e8f0;
        padding-top: 10px;
      }
    }

    /* Strict single-line rule for all badges and buttons */
    .badge, .btn, span[style*="border-radius"], [class*="badge"], [class*="btn"] {
      white-space: nowrap !important;
      flex-shrink: 0 !important;
      word-break: keep-all !important;
    }

    @keyframes modalPopIn {
      from { opacity: 0; transform: scale(0.96) translateY(8px); }
      to { opacity: 1; transform: scale(1) translateY(0); }
    }
  </style>

  <x-page-hero 
    title="My Service Requests & Live Tracking" 
    subtitle="Monitor real-time status updates, engineering assignments, and audit logs for your facility bookings." 
  />

  <section class="section" style="padding-top:2.5rem; padding-bottom:4rem;">
    <div class="container" style="max-width:960px;">
      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.9rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      @if(session('error'))
        <div data-alert style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:0.9rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#991b1b; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      @if(session('info'))
        <div data-alert style="background:#eff6ff; border:1px solid #60a5fa; color:#1e40af; padding:0.9rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>{{ session('info') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#1e40af; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:16px;">
        <div>
          <div style="display:flex; align-items:center; gap:10px;">
            <h2 style="font-size:1.35rem; font-weight:800; margin:0; color:var(--text-main);">
              Active & Past Bookings
            </h2>
            <span class="badge" style="font-size:0.78rem; font-weight:700; background:rgba(67,97,238,0.1); color:var(--primary); padding:3px 10px; border-radius:12px; white-space:nowrap !important; flex-shrink:0 !important;">
              {{ $bookings->total() }} Total
            </span>
          </div>
          <p style="margin:4px 0 0; font-size:0.85rem; color:var(--text-muted);">
            Account: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
          </p>
        </div>

        <div style="display:flex; gap:10px; align-items:center;">
          <a href="{{ route('customer.profile') }}" class="btn btn-outline" style="padding:0.6rem 1.1rem; font-size:0.875rem; font-weight:600; border-radius:8px; display:inline-flex; align-items:center; gap:6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Edit Profile</span>
          </a>
          <a href="{{ route('booking.create') }}" class="btn btn-accent" style="padding:0.6rem 1.25rem; font-size:0.875rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>New Service Request</span>
          </a>
        </div>
      </div>

      @if($bookings->isEmpty())
        <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; text-align:center; padding:4rem 2rem; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
          <div style="width:68px; height:68px; border-radius:50%; background:rgba(30,58,138,0.08); color:var(--primary); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
          </div>
          <h3 style="font-size:1.25rem; font-weight:700; color:var(--text-main); margin-bottom:0.5rem;">No Service Requests Yet</h3>
          <p style="color:var(--text-muted); max-width:440px; margin:0 auto 1.75rem; font-size:0.925rem; line-height:1.5;">
            You have not placed any maintenance or AMC requests yet. Browse our comprehensive facility engineering services to schedule your first visit.
          </p>
          <a href="{{ route('booking.create') }}" class="btn btn-accent" style="padding:0.75rem 1.5rem; font-weight:700; border-radius:8px;">
            Book a Service Now →
          </a>
        </div>
      @else
        @php
          $runningBookings = $bookings->filter(fn ($b) => in_array($b->status, ['pending_review', 'accepted', 'assigned', 'on_the_way', 'in_progress']));
          $completedBookings = $bookings->filter(fn ($b) => $b->status === 'completed');
          $cancelledBookings = $bookings->filter(fn ($b) => $b->status === 'cancelled');
        @endphp

        <!-- ================================================================= -->
        <!-- 1. ACTIVE & RUNNING SERVICE REQUESTS (LIVE REAL-TIME TRACKING)   -->
        <!-- ================================================================= -->
        @if($runningBookings->isNotEmpty())
          <div style="margin-bottom: 2.5rem;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
              <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.25);"></span>
              <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#0f172a;">
                Running Services & Live Tracking ({{ $runningBookings->count() }})
              </h3>
            </div>

            <div style="display:flex; flex-direction:column; gap:20px;">
              @foreach($runningBookings as $booking)
                <div class="customer-booking-card">
                  <!-- Booking Card Header -->
                  <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px; margin-bottom:1.5rem; border-bottom:1px solid #f1f5f9; padding-bottom:1.25rem;">
                    <div>
                      <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                        <span class="badge" style="font-size:1.1rem; font-weight:800; color:var(--primary); font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace; background:rgba(67,97,238,0.08); padding:3px 10px; border-radius:6px; letter-spacing:0.02em; white-space:nowrap !important; flex-shrink:0 !important;">
                          #{{ $booking->booking_number }}
                        </span>
                        @if($booking->is_amc)
                          <span class="badge" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; white-space:nowrap !important; flex-shrink:0 !important;">
                            🏢 AMC Contract
                          </span>
                          @if($booking->is_running_contract)
                            <span class="badge" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; display:inline-flex; align-items:center; gap:4px; white-space:nowrap !important; flex-shrink:0 !important;">
                              <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#10b981;"></span>
                              Running Contract
                            </span>
                          @endif
                        @endif
                        <span class="badge" style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:{{ $booking->status_badge_color }}22; color:{{ $booking->status_badge_color }}; border:1px solid {{ $booking->status_badge_color }}44; white-space:nowrap !important; flex-shrink:0 !important;">
                          ● {{ $booking->status_label }}
                        </span>
                        @if($booking->urgency === 'emergency_1h')
                          <span class="badge" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.02em; padding:2px 8px; border-radius:10px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; white-space:nowrap !important; flex-shrink:0 !important;">
                            🚨 Critical Emergency
                          </span>
                        @endif
                      </div>

                      <div style="font-size:0.85rem; color:var(--text-muted); margin-top:6px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                        <span>Requested on {{ $booking->created_at->format('M d, Y • h:i A') }}</span>
                        <span>•</span>
                        <span style="color:#0f172a; font-weight:600;">
                          📅 Scheduled: {{ $booking->preferred_date ? $booking->preferred_date->format('M d, Y') : 'N/A' }} ({{ $booking->preferred_time_slot }})
                        </span>
                      </div>
                    </div>

                    <div class="booking-header-meta" style="text-align:right;">
                      <div style="font-size:1.15rem; font-weight:800; color:var(--text-main);">
                        {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Service') }}
                      </div>
                      <div style="font-size:0.825rem; color:var(--text-muted); margin-top:2px;">
                        📍 {{ $booking->area }}, {{ $booking->district }}
                      </div>
                    </div>
                  </div>

                  @if($booking->is_amc)
                    <!-- AMC Active Contract Status & Billing Overview -->
                    <div style="background:#fcfaff; border:1px solid #e9d5ff; border-radius:10px; padding:1.25rem 1.5rem; margin:1.5rem 0; display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                      <div>
                        <span style="font-size:0.72rem; text-transform:uppercase; color:#6b21a8; font-weight:700; letter-spacing:0.04em;">Contract Plan</span>
                        <strong style="display:block; font-size:1.05rem; color:#1e1b4b; margin-top:3px;">
                          {{ $booking->amcPlan?->name ?? 'Standard AMC' }}
                        </strong>
                        <span style="font-size:0.75rem; color:#7c3aed;">Annual Maintenance Agreement</span>
                      </div>
                      <div>
                        <span style="font-size:0.72rem; text-transform:uppercase; color:#6b21a8; font-weight:700; letter-spacing:0.04em;">Contract Tenure</span>
                        <strong style="display:block; font-size:0.95rem; color:#1e1b4b; margin-top:3px;">
                          {{ $booking->contract_start_date?->format('M d, Y') ?: 'Active' }} — {{ $booking->contract_end_date?->format('M d, Y') ?: '1 Year' }}
                        </strong>
                        <span style="font-size:0.75rem; color:#10b981; font-weight:600;">{{ $booking->days_remaining_in_contract ? $booking->days_remaining_in_contract . ' days remaining' : 'Active Service' }}</span>
                      </div>
                      <div>
                        <span style="font-size:0.72rem; text-transform:uppercase; color:#6b21a8; font-weight:700; letter-spacing:0.04em;">Recurring Billing</span>
                        <strong style="display:block; font-size:1.1rem; color:#6d28d9; margin-top:3px;">
                          ৳ {{ number_format($booking->recurring_amount ?: $booking->final_cost, 2) }}
                          <span style="font-size:0.75rem; color:#7c3aed; font-weight:500;">/ {{ $booking->billing_cycle_label }}</span>
                        </strong>
                        <span style="font-size:0.75rem; color:#64748b;">Next Due: {{ $booking->next_billing_date ? $booking->next_billing_date->format('M d, Y') : 'Active' }}</span>
                      </div>
                      @if($booking->agreement_document_path)
                        <div style="display:flex; align-items:flex-end;">
                          <a href="{{ $booking->agreement_url }}" target="_blank" class="btn btn-outline btn-sm" style="font-size:0.78rem; padding:6px 12px; background:#ffffff; color:#7c3aed; border-color:#d8b4fe; display:inline-flex; align-items:center; gap:5px;">
                            <span>📄 View Agreement Copy ↗</span>
                          </a>
                        </div>
                      @endif
                    </div>
                  @else
                    <!-- Live Progress Stepper for Running One-Off Services -->
                    @php
                      $statusOrder = ['pending_review', 'accepted', 'assigned', 'on_the_way', 'in_progress', 'completed'];
                      $currentIdx = array_search($booking->status, $statusOrder);
                      if ($currentIdx === false) {
                          $currentIdx = ($booking->status === 'cancelled') ? -1 : 0;
                      }
                    @endphp

                    <div class="customer-stepper-wrap" style="margin:1.75rem 0 1.25rem; padding:0 0.5rem; overflow-x:auto; -webkit-overflow-scrolling:touch;">
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
                      <div style="background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;">
                        <span style="font-size:1.3rem;">🚗</span>
                        <div>
                          <strong style="color:#6d28d9; font-size:0.85rem; display:block;">Crew is on the way to your address!</strong>
                          <span style="font-size:0.78rem; color:#7c3aed;">Our technician team has started journey. {{ $booking->arrival_window ? 'Arrival Slot: ' . $booking->arrival_window : 'Heading to your location.' }}</span>
                        </div>
                      </div>
                    @elseif($booking->status === 'in_progress')
                      <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;">
                        <span style="font-size:1.3rem;">🔧</span>
                        <div>
                          <strong style="color:#15803d; font-size:0.85rem; display:block;">Working On Site</strong>
                          <span style="font-size:0.78rem; color:#166534;">Our engineering team is actively servicing your facility on site.</span>
                        </div>
                      </div>
                    @elseif($booking->status === 'assigned')
                      <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;">
                        <span style="font-size:1.3rem;">👷</span>
                        <div>
                          <strong style="color:#1d4ed8; font-size:0.85rem; display:block;">Service Confirmed & Scheduled</strong>
                          <span style="font-size:0.78rem; color:#2563eb;">A certified engineering team has been assigned and confirmed for your service booking.</span>
                        </div>
                      </div>
                    @elseif($booking->status === 'accepted')
                      <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:10px;">
                        <span style="font-size:1.3rem;">✓</span>
                        <div>
                          <strong style="color:#0369a1; font-size:0.85rem; display:block;">Booking Accepted</strong>
                          <span style="font-size:0.78rem; color:#0284c7;">Operations headquarters accepted your booking. Preparing dispatch.</span>
                        </div>
                      </div>
                    @endif
                  @endif

                  <!-- Details & Timeline Logs -->
                  <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1.25rem 1.5rem; font-size:0.875rem; margin-bottom:1.25rem;">
                    <div style="margin-bottom:0.75rem;">
                      <strong style="color:var(--text-main); font-weight:700;">Problem Description:</strong>
                      <p style="margin:4px 0 0; color:#475569; line-height:1.5;">{{ $booking->problem_description }}</p>
                    </div>

                    @if($booking->statusLogs->isNotEmpty())
                      <div style="margin-top:0.75rem; border-top:1px dashed #cbd5e1; padding-top:0.75rem;">
                        <strong style="color:var(--text-main); font-size:0.825rem; display:block; margin-bottom:6px;">Latest Operations Update:</strong>
                        @foreach($booking->statusLogs->take(2) as $log)
                          <div style="font-size:0.825rem; color:#475569; margin-bottom:4px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                            <span>• {{ $log->customer_comment }}</span>
                            <span style="color:#94a3b8; font-size:0.75rem;">{{ $log->created_at->diffForHumans() }}</span>
                          </div>
                        @endforeach
                      </div>
                    @endif
                  </div>

                  <!-- Action Footer -->
                  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                    <div style="font-size:0.8rem; color:#64748b;">
                      Address: {{ $booking->street_address }}
                    </div>
                    <div style="display:flex; gap:10px; align-items:center;">
                      @if($booking->canBeCancelledByCustomer())
                        <button type="button" 
                                onclick="openCancelModal('{{ $booking->booking_number }}', '{{ addslashes($booking->service?->title ?? ($booking->amcPlan?->name ?? 'Facility Service')) }}')" 
                                class="btn btn-outline" 
                                style="padding:8px 14px; font-size:0.85rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; color:#dc2626; border-color:#fca5a5; background:#ffffff; cursor:pointer;">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                          <span>Cancel Request</span>
                        </button>
                      @endif

                      <a href="{{ route('customer.bookings.show', $booking->booking_number) }}" class="btn btn-outline" style="padding:8px 16px; font-size:0.85rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; color:var(--primary); border-color:#cbd5e1; background:#ffffff;">
                        <span>Live Tracking & Details</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- ================================================================= -->
        <!-- 2. COMPLETED SERVICE HISTORY (CLEAN ITEMS WITH INSTANT MODAL)    -->
        <!-- ================================================================= -->
        @if($completedBookings->isNotEmpty())
          <div style="margin-top: {{ $runningBookings->isNotEmpty() ? '1rem' : '0' }};">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:1.15rem;">✓</span>
                <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#0f172a;">
                  Completed Services ({{ $completedBookings->count() }})
                </h3>
              </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
              @foreach($completedBookings as $cb)
                <div class="completed-booking-item" onclick="openCompletedModal('{{ $cb->booking_number }}')">
                  <!-- Left: Icon + Service info -->
                  <div style="display:flex; align-items:center; gap:14px;">
                    <div style="width:42px; height:42px; border-radius:50%; background:#ecfdf5; border:1.5px solid #a7f3d0; color:#059669; display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:800; flex-shrink:0;">
                      ✓
                    </div>

                    <div>
                      <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span style="font-weight:800; font-size:0.95rem; color:#0f172a;">
                          {{ $cb->service?->title ?? ($cb->amcPlan?->name ?? 'Facility Service') }}
                        </span>
                        <span style="font-family:monospace; font-weight:700; font-size:0.75rem; color:#6366f1; background:#e0e7ff; padding:1px 6px; border-radius:4px;">
                          #{{ $cb->booking_number }}
                        </span>
                        @if($cb->review_rating)
                          <span style="color:#d97706; font-size:0.78rem; font-weight:700; background:#fef3c7; padding:1px 6px; border-radius:4px;">
                            ★ {{ $cb->review_rating }}.0
                          </span>
                        @endif
                      </div>

                      <div style="font-size:0.78rem; color:#64748b; margin-top:3px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span>Completed on {{ $cb->completed_at ? $cb->completed_at->format('M d, Y') : $cb->updated_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>📍 {{ $cb->area }}, {{ $cb->district }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Right: Bill + Completed Badge + View Details Button -->
                  <div class="completed-item-right" style="display:flex; align-items:center; gap:16px;">
                    <div style="text-align:right;">
                      <div style="font-size:1.05rem; font-weight:800; color:#059669;">
                        ৳ {{ number_format((float) ($cb->final_cost ?: $cb->estimated_cost ?: 0), 2) }}
                      </div>
                      <span class="badge" style="font-size:0.7rem; font-weight:700; color:#059669; background:#ecfdf5; padding:2px 8px; border-radius:10px; border:1px solid #a7f3d0; display:inline-block; margin-top:2px; white-space:nowrap !important; flex-shrink:0 !important;">
                        Completed ✓
                      </span>
                    </div>

                    <button type="button" class="btn btn-outline btn-sm" style="font-size:0.78rem; font-weight:700; padding:6px 12px; border-radius:6px; color:#0f172a; border-color:#cbd5e1; background:#ffffff; pointer-events:none;">
                      <span>View Details →</span>
                    </button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- ================================================================= -->
        <!-- 3. CANCELLED REQUESTS (NEVER MIXED WITH COMPLETED SERVICES)      -->
        <!-- ================================================================= -->
        @if($cancelledBookings->isNotEmpty())
          <div style="margin-top: {{ ($runningBookings->isNotEmpty() || $completedBookings->isNotEmpty()) ? '2rem' : '0' }};">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.2);"></span>
                <h3 style="margin:0; font-size:1.1rem; font-weight:800; color:#0f172a;">
                  Cancelled Requests ({{ $cancelledBookings->count() }})
                </h3>
              </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
              @foreach($cancelledBookings as $cb)
                <div class="completed-booking-item" style="border-left: 4px solid #ef4444; background:#fff;">
                  <!-- Left: Icon + Service info -->
                  <div style="display:flex; align-items:center; gap:14px;">
                    <div style="width:42px; height:42px; border-radius:50%; background:#fef2f2; border:1.5px solid #fecaca; color:#ef4444; display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:800; flex-shrink:0;">
                      ✕
                    </div>

                    <div>
                      <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span style="font-weight:800; font-size:0.95rem; color:#0f172a;">
                          {{ $cb->service?->title ?? ($cb->amcPlan?->name ?? 'Facility Service') }}
                        </span>
                        <span class="badge" style="font-family:monospace; font-weight:700; font-size:0.75rem; color:#dc2626; background:#fee2e2; padding:1px 6px; border-radius:4px; white-space:nowrap !important; flex-shrink:0 !important;">
                          #{{ $cb->booking_number }}
                        </span>
                      </div>

                      <div style="font-size:0.78rem; color:#64748b; margin-top:3px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span>Cancelled on {{ $cb->cancelled_at ? $cb->cancelled_at->format('M d, Y • h:i A') : $cb->updated_at->format('M d, Y • h:i A') }}</span>
                        <span>•</span>
                        <span>📍 {{ $cb->area }}, {{ $cb->district }}</span>
                      </div>

                      @if($cb->cancellation_reason)
                        <div class="badge" style="margin-top:6px; font-size:0.78rem; color:#b91c1c; background:#fef2f2; border:1px solid #fee2e2; border-radius:6px; padding:3px 8px; display:inline-block; white-space:nowrap !important; flex-shrink:0 !important;">
                          <strong>Reason:</strong> {{ $cb->cancellation_reason }}
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- Right: Cancelled Badge + View Details Button -->
                  <div class="completed-item-right" style="display:flex; align-items:center; gap:16px;">
                    <div style="text-align:right;">
                      <span class="badge" style="font-size:0.72rem; font-weight:700; color:#dc2626; background:#fef2f2; padding:3px 10px; border-radius:10px; border:1px solid #fecaca; display:inline-block; white-space:nowrap !important; flex-shrink:0 !important;">
                        Cancelled ✕
                      </span>
                    </div>

                    <a href="{{ route('customer.bookings.show', $cb->booking_number) }}" class="btn btn-outline btn-sm" style="font-size:0.78rem; font-weight:700; padding:6px 12px; border-radius:6px; color:#0f172a; border-color:#cbd5e1; background:#ffffff; text-decoration:none;">
                      <span>View Details →</span>
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <div style="margin-top:1.5rem;">
          {{ $bookings->links() }}
        </div>
      @endif
    </div>
  </section>

  <!-- ========================================================================= -->
  <!-- COMPLETED SERVICE DETAILS INTERACTIVE MODAL                               -->
  <!-- ========================================================================= -->
  <div id="completedBookingModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="closeModalOnBackdrop(event)">
    <div style="background:#ffffff; border-radius:14px; max-width:540px; width:100%; max-height:90vh; overflow-y:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation:modalPopIn 0.2s ease; border:1px solid #e2e8f0;">
      
      <!-- Modal Header -->
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border-radius:14px 14px 0 0;">
        <div>
          <div style="display:flex; align-items:center; gap:8px;">
            <span id="modalBookingNumber" style="font-weight:800; font-family:monospace; color:var(--primary); font-size:1.05rem;"></span>
            <span class="badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:0.7rem; font-weight:700; padding:2px 8px; border-radius:12px; white-space:nowrap !important; flex-shrink:0 !important;">Completed ✓</span>
          </div>
          <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">Service Request Completed & Invoiced</div>
        </div>
        <button type="button" onclick="closeCompletedModal()" style="background:none; border:none; font-size:1.3rem; color:#94a3b8; cursor:pointer; padding:0 4px; line-height:1;">✕</button>
      </div>

      <!-- Modal Body -->
      <div style="padding:20px;">
        <!-- Service & Bill Highlight Box -->
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:14px 16px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
          <div>
            <div style="font-size:0.72rem; color:#15803d; font-weight:700; text-transform:uppercase;">Delivered Service</div>
            <div id="modalServiceTitle" style="font-size:1.1rem; font-weight:800; color:#0f172a; margin-top:2px;"></div>
            <div id="modalCategory" style="font-size:0.78rem; color:#475569;"></div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:0.72rem; color:#15803d; font-weight:700; text-transform:uppercase;">Final Invoiced Bill</div>
            <div id="modalFinalBill" style="font-size:1.4rem; font-weight:800; color:#15803d; margin-top:2px;"></div>
            <div style="font-size:0.7rem; color:#059669;">Payment Settled</div>
          </div>
        </div>

        <!-- Execution Info Grid -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px;">
            <div style="font-size:0.72rem; color:#64748b; text-transform:uppercase; font-weight:700;">Completed Date</div>
            <div id="modalCompletedAt" style="font-size:0.85rem; font-weight:600; color:#1e293b; margin-top:2px;"></div>
          </div>
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px;">
            <div style="font-size:0.72rem; color:#64748b; text-transform:uppercase; font-weight:700;">Service Status</div>
            <div style="font-size:0.85rem; font-weight:700; color:#16a34a; margin-top:2px;">✓ Verified &amp; Completed</div>
          </div>
        </div>

        <!-- Location -->
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px; margin-bottom:16px;">
          <div style="font-size:0.72rem; color:#64748b; text-transform:uppercase; font-weight:700;">Service Location</div>
          <div id="modalAddress" style="font-size:0.85rem; font-weight:600; color:#1e293b; margin-top:2px;"></div>
        </div>

        <!-- Problem Description -->
        <div style="margin-bottom:16px;">
          <div style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase; margin-bottom:4px;">Problem Description</div>
          <div id="modalProblem" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 12px; font-size:0.82rem; color:#334155; line-height:1.45;"></div>
        </div>

        <!-- Review / Feedback Section -->
        <div id="modalReviewSection" style="border-top:1px dashed #cbd5e1; padding-top:14px; margin-top:14px;">
          <!-- Rendered dynamically via JavaScript -->
        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding:12px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; border-radius:0 0 14px 14px;">
        <a id="modalFullDetailsLink" href="#" class="btn btn-outline btn-sm" style="font-size:0.82rem; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
          <span>Open Full Receipt & History →</span>
        </a>
        <button type="button" onclick="closeCompletedModal()" class="btn btn-primary btn-sm" style="font-size:0.82rem; font-weight:700; padding:6px 16px;">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- CUSTOMER CANCEL REQUEST MODAL                                             -->
  <!-- ========================================================================= -->
  @if($runningBookings->contains(fn ($b) => $b->canBeCancelledByCustomer()))
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

  <!-- ========================================================================= -->
  <!-- JAVASCRIPT: DATA MAP & MODAL HANDLER                                     -->
  <!-- ========================================================================= -->
  <script>
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

    const completedBookingsData = {
      @foreach($completedBookings as $cb)
        "{{ $cb->booking_number }}": {
          number: "{{ $cb->booking_number }}",
          title: "{{ addslashes($cb->service?->title ?? ($cb->amcPlan?->name ?? 'Facility Service')) }}",
          category: "{{ addslashes($cb->service?->category?->name ?? 'Facility Engineering') }}",
          completed_at: "{{ $cb->completed_at ? $cb->completed_at->format('M d, Y • h:i A') : $cb->updated_at->format('M d, Y • h:i A') }}",
          bill: "৳ {{ number_format((float) ($cb->final_cost ?: $cb->estimated_cost ?: 0), 2) }}",
          address: "{{ addslashes($cb->street_address . ', ' . $cb->area . ', ' . $cb->district) }}",
          property: "{{ ucfirst($cb->property_type) }}",
          problem: "{{ addslashes($cb->problem_description) }}",
          has_reviewed: {{ $cb->review_rating ? 'true' : 'false' }},
          rating: {{ (int) ($cb->review_rating ?? 0) }},
          review_comment: "{{ addslashes($cb->review_comment ?? '') }}",
          review_url: "{{ route('customer.bookings.review', $cb->booking_number) }}",
          details_url: "{{ route('customer.bookings.show', $cb->booking_number) }}"
        },
      @endforeach
    };

    function openCompletedModal(bookingNumber) {
      const data = completedBookingsData[bookingNumber];
      if (!data) return;

      document.getElementById('modalBookingNumber').textContent = '#' + data.number;
      document.getElementById('modalServiceTitle').textContent = data.title;
      document.getElementById('modalCategory').textContent = data.category + ' • ' + data.property;
      document.getElementById('modalFinalBill').textContent = data.bill;
      document.getElementById('modalCompletedAt').textContent = data.completed_at;
      document.getElementById('modalAddress').textContent = '📍 ' + data.address;
      document.getElementById('modalProblem').textContent = data.problem || 'No specific description noted.';
      document.getElementById('modalFullDetailsLink').href = data.details_url;

      // Review Section Logic
      const reviewContainer = document.getElementById('modalReviewSection');
      if (data.has_reviewed) {
        reviewContainer.innerHTML = `
          <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px 14px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:0.75rem; color:#1e40af; font-weight:700; text-transform:uppercase;">Your Feedback Rating</span>
              <span style="color:#d97706; font-size:1rem; font-weight:800;">★ ${data.rating}.0 / 5.0</span>
            </div>
            <div style="font-size:0.82rem; color:#1e293b; margin-top:4px; font-style:italic;">
              "${data.review_comment}"
            </div>
          </div>
        `;
      } else {
        reviewContainer.innerHTML = `
          <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:12px 14px;">
            <div style="font-weight:700; font-size:0.82rem; color:#92400e; margin-bottom:8px;">Leave a Rating & Feedback:</div>
            <form method="POST" action="${data.review_url}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div style="display:flex; gap:12px; margin-bottom:8px; align-items:center; flex-wrap:wrap;">
                <label style="font-size:0.8rem; cursor:pointer; font-weight:600;"><input type="radio" name="review_rating" value="5" checked> ★★★★★ 5</label>
                <label style="font-size:0.8rem; cursor:pointer; font-weight:600;"><input type="radio" name="review_rating" value="4"> ★★★★ 4</label>
                <label style="font-size:0.8rem; cursor:pointer; font-weight:600;"><input type="radio" name="review_rating" value="3"> ★★★ 3</label>
                <label style="font-size:0.8rem; cursor:pointer; font-weight:600;"><input type="radio" name="review_rating" value="2"> ★★ 2</label>
                <label style="font-size:0.8rem; cursor:pointer; font-weight:600;"><input type="radio" name="review_rating" value="1"> ★ 1</label>
              </div>
              <div style="display:flex; gap:8px;">
                <input type="text" name="review_comment" required placeholder="Share your experience with this service..." style="flex:1; padding:7px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.82rem;">
                <button type="submit" class="btn btn-accent btn-sm" style="font-size:0.78rem; padding:7px 14px; font-weight:700;">Submit Review</button>
              </div>
            </form>
          </div>
        `;
      }

      const modal = document.getElementById('completedBookingModal');
      if (modal) modal.style.display = 'flex';
    }

    function closeCompletedModal() {
      const modal = document.getElementById('completedBookingModal');
      if (modal) modal.style.display = 'none';
    }

    function closeModalOnBackdrop(e) {
      if (e.target.id === 'completedBookingModal') {
        closeCompletedModal();
      }
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeCompletedModal();
        closeCancelModal();
      }
    });
  </script>
</x-layouts.app>