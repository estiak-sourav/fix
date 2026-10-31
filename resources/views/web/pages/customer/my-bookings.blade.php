<x-layouts.app :title="'My Bookings & Live Status | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
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

      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:16px;">
        <div>
          <div style="display:flex; align-items:center; gap:10px;">
            <h2 style="font-size:1.35rem; font-weight:800; margin:0; color:var(--text-main);">
              Active & Past Bookings
            </h2>
            <span style="font-size:0.78rem; font-weight:700; background:rgba(67,97,238,0.1); color:var(--primary); padding:3px 10px; border-radius:12px;">
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
        <div style="display:flex; flex-direction:column; gap:20px;">
          @foreach($bookings as $booking)
            <div class="customer-booking-card" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem 2rem; box-shadow:0 2px 6px rgba(0,0,0,0.03); transition:all 0.2s ease;" onmouseover="this.style.boxShadow='0 6px 18px rgba(0,0,0,0.06)'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.03)'; this.style.borderColor='#e2e8f0';">
              <!-- Booking Card Header -->
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px; margin-bottom:1.5rem; border-bottom:1px solid #f1f5f9; padding-bottom:1.25rem;">
                <div>
                  <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    <span style="font-size:1.1rem; font-weight:800; color:var(--primary); font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace; background:rgba(67,97,238,0.08); padding:3px 10px; border-radius:6px; letter-spacing:0.02em;">
                      #{{ $booking->booking_number }}
                    </span>
                    @if($booking->is_amc)
                      <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe;">
                        🏢 AMC Contract
                      </span>
                      @if($booking->is_running_contract)
                        <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; display:inline-flex; align-items:center; gap:4px;">
                          <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#10b981;"></span>
                          Running Contract
                        </span>
                      @else
                        <span style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:{{ $booking->status_badge_color }}22; color:{{ $booking->status_badge_color }}; border:1px solid {{ $booking->status_badge_color }}44;">
                          ● {{ $booking->status_label }}
                        </span>
                      @endif
                    @else
                      <span style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:{{ $booking->status_badge_color }}22; color:{{ $booking->status_badge_color }}; border:1px solid {{ $booking->status_badge_color }}44;">
                        ● {{ $booking->status_label }}
                      </span>
                    @endif
                    @if($booking->urgency === 'emergency_1h')
                      <span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.02em; padding:2px 8px; border-radius:10px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">
                        🚨 Critical Emergency
                      </span>
                    @endif
                  </div>
                  <div style="font-size:0.85rem; color:var(--text-muted); margin-top:6px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <span>Created on {{ $booking->created_at->format('M d, Y • h:i A') }}</span>
                    <span>•</span>
                    @if($booking->is_amc && $booking->contract_start_date)
                      <span style="color:#0f172a; font-weight:600;">
                        📋 Active Period: {{ $booking->contract_start_date->format('M d, Y') }} — {{ $booking->contract_end_date?->format('M d, Y') }}
                      </span>
                    @else
                      <span style="color:#0f172a; font-weight:600;">
                        📅 Scheduled: {{ $booking->preferred_date ? $booking->preferred_date->format('M d, Y') : 'N/A' }} ({{ $booking->preferred_time_slot }})
                      </span>
                    @endif
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
                <!-- Live Progress Stepper for One-Off Services -->
                @php
                  $statusOrder = ['pending_review', 'accepted', 'assigned', 'in_progress', 'completed'];
                  $currentIdx = array_search($booking->status, $statusOrder);
                  if ($currentIdx === false && $booking->status === 'cancelled') $currentIdx = -1;
                  // When crew is assigned, advance both Assigned and In Progress together
                  if ($booking->status === 'assigned' || ($booking->assigned_team && in_array($booking->status, ['accepted', 'assigned']))) {
                      $currentIdx = 3; // 'in_progress'
                  }
                @endphp

                <div class="customer-stepper-wrap" style="margin:1.75rem 0 2rem; padding:0 0.5rem; overflow-x:auto; -webkit-overflow-scrolling:touch;">
                  <div style="display:flex; justify-content:space-between; position:relative; min-width:320px;">
                    <div style="position:absolute; top:14px; left:16px; right:16px; height:3px; background:#e2e8f0; z-index:1;"></div>
                    @if($currentIdx >= 0)
                      <div style="position:absolute; top:14px; left:16px; width:{{ ($currentIdx / (count($statusOrder) - 1)) * 100 }}%; height:3px; background:#10b981; z-index:2; transition:width 0.4s ease;"></div>
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
                      <div style="position:relative; z-index:3; text-align:center; min-width:64px;">
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
                        <span>• {{ $log->comment ?: 'Status updated to ' . ucfirst(str_replace('_', ' ', $log->to_status)) }}</span>
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
                  <a href="{{ route('customer.bookings.show', $booking->booking_number) }}" class="btn btn-outline" style="padding:8px 16px; font-size:0.85rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; color:var(--primary); border-color:#cbd5e1; background:#ffffff;">
                    <span>View Details & Review</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                  </a>
                </div>
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
