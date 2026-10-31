<x-layouts.admin title="Work Order #{{ $booking->booking_number }} | FIX Admin">
  <!-- Top Navigation & Order ID Header -->
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:1.25rem;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.bookings.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Bookings List
        </a>
      </div>
      <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <h1 class="page-header-title" style="margin:0; font-family:monospace; font-size:1.35rem; color:var(--paces-primary);">
          #{{ $booking->booking_number }}
        </h1>
        <span style="font-size:0.8rem; color:var(--paces-text-muted); font-weight:400;">
          • Created {{ $booking->created_at->format('M d, Y • h:i A') }}
        </span>
      </div>
    </div>

    <div>
      @php
        $badgeClass = match($booking->status) {
          'pending_review' => 'bg-warning-subtle',
          'accepted', 'confirmed' => 'bg-primary-subtle',
          'in_progress', 'assigned' => 'bg-info-subtle',
          'completed' => 'bg-success-subtle',
          'cancelled' => 'bg-danger-subtle',
          default => 'bg-primary-subtle'
        };
      @endphp
      <span class="badge-pill-paces {{ $badgeClass }}" style="font-size:0.82rem; font-weight:500; text-transform:uppercase; padding:6px 14px;">
        ● {{ $booking->status_label }}
      </span>
    </div>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:10px 16px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-weight:500; font-size:0.85rem; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#166534; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:10px 16px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-size:0.85rem; display:flex; align-items:flex-start; justify-content:space-between;">
      <div>
        <div style="font-weight:500; margin-bottom:4px;">Please review the following errors:</div>
        <ul style="padding-left:18px; margin:0;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Top Warning Notice for Pending Orders (Smaller font, no icon) -->
  @if($booking->status === 'pending_review')
    <div class="card-paces" style="background:#fffbeb; border:1px solid #fde68a; margin-bottom:1.25rem; padding:0.9rem 1.25rem;">
      <div>
        <h3 style="font-size:0.92rem; font-weight:500; color:#92400e; margin:0 0 3px 0;">
          Pending — Discuss with client before Agreement Required
        </h3>
        <p style="font-size:0.8rem; color:#78350f; margin:0; line-height:1.45; font-weight:400;">
          Before accepting this work order, talk with client <strong>{{ $booking->customer_name }}</strong> over the phone at <strong>{{ $booking->customer_phone }}</strong> to confirm problem details, requirements, and service price.
        </p>
      </div>
    </div>
  @endif

  <!-- Main 2-Column Layout: Left (Client Details & Audit Timeline) & Right (Accept/Details, wider) -->
  <div style="display:grid; grid-template-columns:340px 1fr; gap:1.5rem; align-items:start;" class="workorder-grid">
    
    <!-- LEFT COLUMN: Client Details & Operational Audit Timeline -->
    <div>
      <!-- Client & Site Profile Card (No Registered badge) -->
      <div class="card-paces" style="padding:1.25rem; margin-bottom:1.25rem;">
        <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
          <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Customer & Site Profile</h3>
        </div>

        <!-- Full Name with Avatar -->
        <div style="margin-bottom:12px; display:flex; align-items:center; gap:12px;">
          @if($booking->user?->avatar_url)
            <img src="{{ $booking->user->avatar_url }}" alt="{{ $booking->customer_name }}" style="width:42px; height:42px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
          @else
            <div style="width:42px; height:42px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:600; display:flex; align-items:center; justify-content:center; font-size:1.05rem; flex-shrink:0;">
              {{ strtoupper(substr($booking->customer_name ?: 'C', 0, 1)) }}
            </div>
          @endif
          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Client Name</div>
            <div style="font-size:1.02rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
              {{ $booking->customer_name }}
            </div>
            @if($booking->company_name)
              <div style="font-size:0.78rem; color:var(--paces-primary); font-weight:500; margin-top:2px;">
                🏢 {{ $booking->company_name }}
              </div>
            @endif
          </div>
        </div>

        <!-- Phone with Copy Button -->
        <div style="margin-bottom:12px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Phone Number</div>
          <div style="display:flex; align-items:center; justify-content:space-between; gap:6px; margin-top:2px;">
            <a href="tel:{{ $booking->customer_phone }}" style="font-size:0.9rem; font-weight:500; color:var(--paces-primary); text-decoration:none;">
              📞 {{ $booking->customer_phone }}
            </a>
            <button 
              type="button" 
              onclick="copyToClipboard('{{ $booking->customer_phone }}', this)" 
              class="btn-paces btn-paces-outline btn-paces-sm" 
              style="padding:2px 8px; font-size:0.72rem; border-radius:4px;" 
              title="Copy Phone Number"
            >
              📋 Copy
            </button>
          </div>
        </div>

        <!-- Email with Copy Button -->
        <div style="margin-bottom:12px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Email Address</div>
          <div style="display:flex; align-items:center; justify-content:space-between; gap:6px; margin-top:2px;">
            <a href="mailto:{{ $booking->customer_email }}" style="font-size:0.82rem; color:var(--paces-text-dark); text-decoration:none; word-break:break-all;">
              ✉️ {{ $booking->customer_email }}
            </a>
            <button 
              type="button" 
              onclick="copyToClipboard('{{ $booking->customer_email }}', this)" 
              class="btn-paces btn-paces-outline btn-paces-sm" 
              style="padding:2px 8px; font-size:0.72rem; border-radius:4px;" 
              title="Copy Email Address"
            >
              📋 Copy
            </button>
          </div>
        </div>

        <!-- Property & Location -->
        <div style="border-top:1px dashed var(--paces-border); padding-top:10px; margin-bottom:12px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Facility Specifics</div>
          <div style="font-size:0.85rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px; text-transform:capitalize;">
            {{ $booking->property_type }} {{ $booking->estimated_sqft ? '• ' . number_format($booking->estimated_sqft) . ' sqft' : '' }}
          </div>
          <div style="font-size:0.8rem; color:var(--paces-text-dark); margin-top:3px; line-height:1.4;">
            {{ $booking->street_address }}
          </div>
          <div style="font-size:0.8rem; font-weight:500; color:var(--paces-primary); margin-top:2px;">
            📍 {{ $booking->area }}, {{ $booking->district }}, {{ $booking->division }}
          </div>
        </div>

        <!-- Requested Scope -->
        <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
          <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Requested Service</div>
          <div style="font-size:0.92rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
            {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Facility Service') }}
          </div>
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:6px; padding:8px 10px; font-size:0.8rem; color:var(--paces-text-dark); line-height:1.45; margin-top:6px; white-space:pre-line;">
            {{ $booking->problem_description }}
          </div>

          @php
            $attachedImages = $booking->attached_images ?? [];
          @endphp
          @if(!empty($attachedImages))
            <div style="margin-top:10px; border-top:1px dashed var(--paces-border); padding-top:10px;">
              <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:600; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                <span>Uploaded Problem Photos ({{ count($attachedImages) }})</span>
              </div>
              <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(70px, 1fr)); gap:8px;">
                @foreach($attachedImages as $imgIndex => $imgUrl)
                  <div style="position:relative; aspect-ratio:1; border-radius:8px; overflow:hidden; border:1px solid var(--paces-border); cursor:pointer; background:#0f172a;" 
                       onclick="openImageLightbox('{{ asset($imgUrl) }}', 'Problem Photo #{{ $imgIndex + 1 }} - #{{ $booking->booking_number }}')" 
                       title="Click to view & zoom">
                    <img src="{{ asset($imgUrl) }}" alt="Problem Photo" style="width:100%; height:100%; object-fit:cover; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="position:absolute; bottom:3px; right:4px; font-size:0.65rem; background:rgba(0,0,0,0.65); color:#fff; padding:1px 4px; border-radius:4px; pointer-events:none;">🔍</div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        <!-- Phone Contact Status (if logged) -->
        @if($booking->call_status)
          <div style="margin-top:12px; background:{{ $booking->call_status === 'unreachable' ? '#fef2f2' : ($booking->call_status === 'connected' ? '#f0fdf4' : '#fffbeb') }}; border:1px solid {{ $booking->call_status === 'unreachable' ? '#fecaca' : ($booking->call_status === 'connected' ? '#bbf7d0' : '#fde68a') }}; border-radius:6px; padding:10px; font-size:0.8rem;">
            <div style="font-weight:500; color:{{ $booking->call_status === 'unreachable' ? '#dc2626' : ($booking->call_status === 'connected' ? '#166534' : '#b45309') }};">
              @if($booking->call_status === 'unreachable')
                ⚠️ Client Marked Unreachable
              @elseif($booking->call_status === 'connected')
                ✓ Phone Discussion Connected
              @else
                ⏱️ Call Back Later / Follow-up
              @endif
            </div>
            @if($booking->call_notes)
              <div style="color:var(--paces-text-dark); margin-top:3px; font-size:0.78rem;">
                {{ $booking->call_notes }}
              </div>
            @endif
          </div>
        @endif
      </div>

      <!-- OPERATIONAL AUDIT TIMELINE (Under User Info Section on Left Column) -->
      <div class="card-paces" style="padding:1.25rem;">
        <div style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
          <h3 class="card-title-paces" style="font-size:0.95rem; margin:0;">Operational Audit Timeline</h3>
          <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400; margin-top:2px;">Real-time dispatch log visible to client</div>
        </div>
        
        @if($booking->statusLogs->isEmpty())
          <div style="text-align:center; padding:1.5rem 0.5rem; color:var(--paces-text-muted); font-size:0.82rem;">
            No historical logs recorded yet.
          </div>
        @else
          <div style="display:flex; flex-direction:column; gap:1rem; padding-left:4px;">
            @foreach($booking->statusLogs as $log)
              <div style="display:flex; gap:12px; font-size:0.82rem; border-left:2px solid var(--paces-primary); padding-left:12px; position:relative;">
                <div style="position:absolute; left:-5px; top:4px; width:8px; height:8px; border-radius:50%; background:var(--paces-primary); border:1.5px solid #fff;"></div>
                <div style="flex:1;">
                  <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                    <span style="font-weight:500; color:var(--paces-text-dark); font-size:0.82rem;">
                      {{ ucfirst(str_replace('_', ' ', $log->from_status)) }} ➔ {{ ucfirst(str_replace('_', ' ', $log->to_status)) }}
                    </span>
                  </div>
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); margin-top:1px;">
                    {{ $log->changedByAdmin?->name ?? ($log->changedByUser?->name ?? 'System Automated') }} • {{ $log->created_at->format('M d, Y • h:i A') }}
                  </div>
                  @if($log->comment)
                    <div style="margin-top:4px; background:#f8fafc; border:1px solid var(--paces-border); border-radius:6px; padding:6px 8px; font-size:0.78rem; color:var(--paces-text-dark); line-height:1.4;">
                      {{ $log->comment }}
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- RIGHT COLUMN: Booking Accept / Booking Details / Edit / Complete -->
    <div>
      @if(!auth('admin')->user()?->hasPermission('bookings.edit'))
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1.25rem; font-size:0.85rem; color:#475569; line-height:1.5; margin-bottom:1.5rem;">
          <div style="font-weight:500; color:#1e293b; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
            <span>👁️ Read-Only Access</span>
          </div>
          You have view-only privileges for Work Orders. Status adjustments, team dispatch, and invoice modification require Edit permissions.
        </div>
      @endif

      <!-- Annual Maintenance Contract (AMC) & Agreement Management -->
      <div class="card-paces" style="margin-bottom:1.5rem; border:1px solid {{ $booking->contract_status === 'active' ? '#10b981' : 'var(--paces-border)' }};">
        <div class="card-header-paces" style="background:{{ $booking->contract_status === 'active' ? '#f0fdf4' : '#f8fafc' }}; border-bottom:1px solid {{ $booking->contract_status === 'active' ? '#bbf7d0' : 'var(--paces-border)' }}; display:flex; justify-content:space-between; align-items:center;">
          <div style="display:flex; align-items:center; gap:8px;">
            <div>
              <h3 class="card-title-paces" style="margin:0; font-size:0.98rem; color:{{ $booking->contract_status === 'active' ? '#166534' : 'var(--paces-text-dark)' }};">
                Annual Maintenance Contract (AMC) & Agreement
              </h3>
            </div>
          </div>
          @if($booking->contract_status === 'active')
            <span class="badge-pill-paces" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-weight:600; font-size:0.75rem;">
              Active Contract
            </span>
          @else
            <span class="badge-pill-paces" style="background:#f1f5f9; color:#64748b; border:1px solid #cbd5e1; font-size:0.75rem;">
              Contract Not Activated
            </span>
          @endif
        </div>

        <div class="card-body-paces" style="padding:1.25rem;">
          @if($booking->contract_status === 'active')
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:1rem;">
              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px 12px;">
                <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase;">AMC Package</div>
                <strong style="font-size:0.95rem; color:var(--paces-text-dark); display:block; margin-top:2px;">
                  {{ $booking->amcPlan?->name ?? 'Custom AMC Package' }}
                </strong>
                @if($booking->amcPlan?->tier)
                  <span style="font-size:0.7rem; color:var(--paces-primary);">{{ $booking->amcPlan->tier }} Tier</span>
                @endif
              </div>

              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px 12px;">
                <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase;">Contract Period</div>
                <strong style="font-size:0.95rem; color:var(--paces-text-dark); display:block; margin-top:2px;">
                  {{ $booking->contract_start_date?->format('M d, Y') }} — {{ $booking->contract_end_date?->format('M d, Y') }}
                </strong>
                <span style="font-size:0.7rem; color:#10b981;">Valid & Active ({{ $booking->days_remaining_in_contract }} days remaining)</span>
              </div>

              <div style="background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; padding:10px 12px;">
                <div style="font-size:0.72rem; color:#7c3aed; text-transform:uppercase; font-weight:700;">Recurring Billing Cycle</div>
                <strong style="font-size:1.05rem; color:#6d28d9; display:block; margin-top:2px;">
                  ৳ {{ number_format($booking->recurring_amount ?: $booking->final_cost, 2) }}
                  <span style="font-size:0.75rem; font-weight:500;">/ {{ $booking->billing_cycle_label }}</span>
                </strong>
                <span style="font-size:0.72rem; color:#64748b;">Next Due: {{ $booking->next_billing_date ? $booking->next_billing_date->format('M d, Y') : 'Active' }}</span>
              </div>

              <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:10px 12px;">
                <div style="font-size:0.72rem; color:#065f46; text-transform:uppercase; font-weight:700;">Collected Revenue</div>
                <strong style="font-size:1.05rem; color:#047857; display:block; margin-top:2px;">
                  ৳ {{ number_format($booking->collected_amc_revenue, 2) }}
                </strong>
                @if($booking->pending_amc_due > 0)
                  <span style="font-size:0.72rem; color:#b45309; font-weight:600;">Due: ৳ {{ number_format($booking->pending_amc_due, 2) }}</span>
                @else
                  <span style="font-size:0.72rem; color:#10b981;">All Bills Paid Up to Date</span>
                @endif
              </div>
            </div>

            <!-- AMC Recurring Invoices & Bill Collection History -->
            <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:10px; padding:14px; margin-bottom:1.25rem;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
                <div>
                  <h4 style="margin:0; font-size:0.92rem; font-weight:600; color:var(--paces-text-dark); display:flex; align-items:center; gap:6px;">
                    AMC Recurring Invoices & Collection Log
                  </h4>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">Recurring billing cycles scheduled for this agreement</div>
                </div>
                @if(auth('admin')->user()?->hasPermission('bookings.edit'))
                  <form action="{{ route('admin.bookings.amc-bills.generate', $booking) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.78rem; padding:4px 10px; display:inline-flex; align-items:center; gap:4px;">
                      <span>+ Generate Next Cycle Bill</span>
                    </button>
                  </form>
                @endif
              </div>

              @if($booking->amcInvoices->isEmpty())
                <div style="text-align:center; padding:1.5rem; color:var(--paces-text-muted); font-size:0.85rem; background:#f8fafc; border-radius:8px;">
                  No recurring invoices recorded yet. Click "+ Generate Next Cycle Bill" above to generate the first bill.
                </div>
              @else
                <div style="overflow-x:auto;">
                  <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                    <thead>
                      <tr style="background:#f8fafc; border-bottom:1px solid var(--paces-border); text-align:left; color:var(--paces-text-muted);">
                        <th style="padding:8px 10px;">Invoice #</th>
                        <th style="padding:8px 10px;">Cycle Label</th>
                        <th style="padding:8px 10px;">Period</th>
                        <th style="padding:8px 10px;">Due Date</th>
                        <th style="padding:8px 10px;">Amount</th>
                        <th style="padding:8px 10px;">Status</th>
                        <th style="padding:8px 10px;">Payment Details</th>
                        <th style="padding:8px 10px; text-align:right;">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($booking->amcInvoices as $inv)
                        <tr style="border-bottom:1px solid var(--paces-border);">
                          <td style="padding:8px 10px; font-weight:600; font-family:monospace; color:var(--paces-primary);">
                            {{ $inv->invoice_number }}
                          </td>
                          <td style="padding:8px 10px; color:var(--paces-text-dark);">
                            {{ $inv->billing_cycle_label }}
                          </td>
                          <td style="padding:8px 10px; color:var(--paces-text-muted); font-size:0.75rem;">
                            {{ $inv->period_start?->format('M d, Y') }} — {{ $inv->period_end?->format('M d, Y') }}
                          </td>
                          <td style="padding:8px 10px; font-weight:500;">
                            {{ $inv->due_date?->format('M d, Y') }}
                          </td>
                          <td style="padding:8px 10px; font-weight:700; color:var(--paces-text-dark);">
                            ৳ {{ number_format($inv->amount, 2) }}
                          </td>
                          <td style="padding:8px 10px;">
                            @if($inv->status === 'paid')
                              <span class="badge-pill-paces" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-weight:600; font-size:0.72rem;">
                                ✓ Paid
                              </span>
                            @elseif($inv->is_overdue)
                              <span class="badge-pill-paces" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-weight:700; font-size:0.72rem;">
                                ⚠️ Overdue
                              </span>
                            @else
                              <span class="badge-pill-paces" style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; font-weight:600; font-size:0.72rem;">
                                Due for Collection
                              </span>
                            @endif
                          </td>
                          <td style="padding:8px 10px; font-size:0.75rem; color:var(--paces-text-muted);">
                            @if($inv->status === 'paid')
                              <strong style="color:var(--paces-text-dark);">{{ $inv->payment_method }}</strong>
                              @if($inv->payment_reference) ({{ $inv->payment_reference }}) @endif
                              <div style="color:#64748b;">Paid on {{ $inv->paid_at?->format('M d, Y') }}</div>
                            @else
                              <span style="color:#d97706;">Pending Collection</span>
                            @endif
                          </td>
                          <td style="padding:8px 10px; text-align:right;">
                            @if($inv->status !== 'paid' && auth('admin')->user()?->hasPermission('bookings.edit'))
                              <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.72rem; padding:3px 8px; font-weight:600;" onclick="openCollectBillModal('{{ $inv->id }}', '{{ $inv->invoice_number }}', '{{ number_format($inv->amount, 2) }}')">
                                Collect Bill
                              </button>
                            @else
                              <span style="font-size:0.75rem; color:#10b981; font-weight:600;">✓ Settled</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>

            <!-- AMC Recurring Invoices & Bill Collection History -->
            <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:10px; padding:14px; margin-bottom:1.25rem;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
                <div>
                  <h4 style="margin:0; font-size:0.92rem; font-weight:600; color:var(--paces-text-dark); display:flex; align-items:center; gap:6px;">
                    AMC Recurring Invoices & Collection Log
                  </h4>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">Recurring billing cycles scheduled for this agreement</div>
                </div>
                @if(auth('admin')->user()?->hasPermission('bookings.edit'))
                  <form action="{{ route('admin.bookings.amc-bills.generate', $booking) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.78rem; padding:4px 10px; display:inline-flex; align-items:center; gap:4px;">
                      <span>+ Generate Next Cycle Bill</span>
                    </button>
                  </form>
                @endif
              </div>

              @if($booking->amcInvoices->isEmpty())
                <div style="text-align:center; padding:1.5rem; color:var(--paces-text-muted); font-size:0.85rem; background:#f8fafc; border-radius:8px;">
                  No recurring invoices recorded yet. Click "+ Generate Next Cycle Bill" above to generate the first bill.
                </div>
              @else
                <div style="overflow-x:auto;">
                  <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                    <thead>
                      <tr style="background:#f8fafc; border-bottom:1px solid var(--paces-border); text-align:left; color:var(--paces-text-muted);">
                        <th style="padding:8px 10px;">Invoice #</th>
                        <th style="padding:8px 10px;">Cycle Label</th>
                        <th style="padding:8px 10px;">Period</th>
                        <th style="padding:8px 10px;">Due Date</th>
                        <th style="padding:8px 10px;">Amount</th>
                        <th style="padding:8px 10px;">Status</th>
                        <th style="padding:8px 10px;">Payment Details</th>
                        <th style="padding:8px 10px; text-align:right;">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($booking->amcInvoices as $inv)
                        <tr style="border-bottom:1px solid var(--paces-border);">
                          <td style="padding:8px 10px; font-weight:600; font-family:monospace; color:var(--paces-primary);">
                            {{ $inv->invoice_number }}
                          </td>
                          <td style="padding:8px 10px; color:var(--paces-text-dark);">
                            {{ $inv->billing_cycle_label }}
                          </td>
                          <td style="padding:8px 10px; color:var(--paces-text-muted); font-size:0.75rem;">
                            {{ $inv->period_start?->format('M d, Y') }} — {{ $inv->period_end?->format('M d, Y') }}
                          </td>
                          <td style="padding:8px 10px; font-weight:500;">
                            {{ $inv->due_date?->format('M d, Y') }}
                          </td>
                          <td style="padding:8px 10px; font-weight:700; color:var(--paces-text-dark);">
                            ৳ {{ number_format($inv->amount, 2) }}
                          </td>
                          <td style="padding:8px 10px;">
                            @if($inv->status === 'paid')
                              <span class="badge-pill-paces" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-weight:600; font-size:0.72rem;">
                                ✓ Paid
                              </span>
                            @elseif($inv->is_overdue)
                              <span class="badge-pill-paces" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-weight:700; font-size:0.72rem;">
                                ⚠️ Overdue
                              </span>
                            @else
                              <span class="badge-pill-paces" style="background:#fffbeb; color:#b45309; border:1px solid #fde68a; font-weight:600; font-size:0.72rem;">
                                Due for Collection
                              </span>
                            @endif
                          </td>
                          <td style="padding:8px 10px; font-size:0.75rem; color:var(--paces-text-muted);">
                            @if($inv->status === 'paid')
                              <strong style="color:var(--paces-text-dark);">{{ $inv->payment_method }}</strong>
                              @if($inv->payment_reference) ({{ $inv->payment_reference }}) @endif
                              <div style="color:#64748b;">Paid on {{ $inv->paid_at?->format('M d, Y') }}</div>
                            @else
                              <span style="color:#d97706;">Pending Collection</span>
                            @endif
                          </td>
                          <td style="padding:8px 10px; text-align:right;">
                            @if($inv->status !== 'paid' && auth('admin')->user()?->hasPermission('bookings.edit'))
                              <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.72rem; padding:3px 8px; font-weight:600;" onclick="openCollectBillModal('{{ $inv->id }}', '{{ $inv->invoice_number }}', '{{ number_format($inv->amount, 2) }}')">
                                Collect Bill
                              </button>
                            @else
                              <span style="font-size:0.75rem; color:#10b981; font-weight:600;">✓ Settled</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>

            @if($booking->amc_package_notes)
              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px 12px; margin-bottom:1rem; font-size:0.82rem; color:var(--paces-text-dark);">
                <strong style="display:block; margin-bottom:4px; font-size:0.78rem; text-transform:uppercase; color:var(--paces-text-muted);">Agreement Terms & Scope Notes:</strong>
                {{ $booking->amc_package_notes }}
              </div>
            @endif

            <!-- Signed Agreement Document Preview / Download -->
            @if($booking->agreement_document_path)
              <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:1rem;">
                <div style="display:flex; align-items:center; gap:12px;">
                  <div>
                    <div style="font-weight:600; font-size:0.88rem; color:#166534;">Signed Agreement Document Uploaded</div>
                    <div style="font-size:0.75rem; color:#14532d;">Stored copy of verified contract document</div>
                  </div>
                </div>
                <div style="display:flex; gap:8px;">
                  <a href="{{ $booking->agreement_url }}" target="_blank" class="btn-paces btn-paces-primary btn-paces-sm" style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem;">
                    <span>View Agreement Copy ↗</span>
                  </a>
                </div>
              </div>
            @endif

            @if(auth('admin')->user()?->hasPermission('bookings.edit'))
              <div style="text-align:right;">
                <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="const f = document.getElementById('amcActivationForm'); if(f) f.style.display = f.style.display === 'none' ? 'block' : 'none';" style="font-size:0.8rem;">
                  Update Contract Details / Re-upload Agreement
                </button>
              </div>
            @endif
          @endif

          @if(auth('admin')->user()?->hasPermission('bookings.edit'))
            <!-- AMC Activation / Update Form -->
            <div id="amcActivationForm" style="display:{{ $booking->contract_status === 'active' ? 'none' : 'block' }}; {{ $booking->contract_status === 'active' ? 'margin-top:1rem; border-top:1px dashed var(--paces-border); padding-top:1rem;' : '' }}">
              @if($booking->contract_status !== 'active')
                <p style="font-size:0.825rem; color:var(--paces-text-muted); margin-bottom:1rem;">
                  Once the initial facility inspection is complete and terms are agreed with the client, activate the AMC contract and upload a scan or photo of the signed agreement.
                </p>
              @endif

              <form action="{{ route('admin.bookings.activate-amc', $booking) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Select AMC Package *</label>
                    <select name="amc_plan_id" class="form-control-paces" required>
                      <option value="">-- Choose AMC Package --</option>
                      @foreach($amcPlans as $plan)
                        <option value="{{ $plan->id }}" {{ (old('amc_plan_id', $booking->amc_plan_id) == $plan->id) ? 'selected' : '' }}>
                          {{ $plan->name }} ({{ $plan->tier }} - {{ $plan->price_period }})
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Recurring Billing Cycle *</label>
                    <select name="billing_cycle" class="form-control-paces" required>
                      <option value="monthly" {{ old('billing_cycle', $booking->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly (Standard Cycle)</option>
                      <option value="half_monthly" {{ old('billing_cycle', $booking->billing_cycle) === 'half_monthly' ? 'selected' : '' }}>Bi-Weekly / Every 15 Days</option>
                      <option value="weekly" {{ old('billing_cycle', $booking->billing_cycle) === 'weekly' ? 'selected' : '' }}>Weekly Cycle</option>
                      <option value="daily" {{ old('billing_cycle', $booking->billing_cycle) === 'daily' ? 'selected' : '' }}>Daily Billing</option>
                      <option value="quarterly" {{ old('billing_cycle', $booking->billing_cycle) === 'quarterly' ? 'selected' : '' }}>Quarterly (Every 3 Months)</option>
                      <option value="yearly" {{ old('billing_cycle', $booking->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly (Annual Settlement)</option>
                    </select>
                  </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:12px;">
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Recurring Amount / Cycle (৳) *</label>
                    <input type="number" step="0.01" name="recurring_amount" value="{{ old('recurring_amount', $booking->recurring_amount ?: $booking->final_cost) }}" placeholder="e.g. 3000.00" class="form-control-paces" required>
                    <span class="form-help-paces">Amount billed per recurring cycle.</span>
                  </div>
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Set Billing Day (1–31)</label>
                    <input type="number" min="1" max="31" name="billing_day" value="{{ old('billing_day', $booking->billing_day ?? 1) }}" placeholder="e.g. 1" class="form-control-paces">
                    <span class="form-help-paces">Target day of period for bill generation.</span>
                  </div>
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">First Bill Due Date</label>
                    <input type="date" name="first_bill_due_date" value="{{ old('first_bill_due_date', $booking->next_billing_date?->format('Y-m-d') ?: ($booking->contract_start_date?->format('Y-m-d') ?: date('Y-m-d'))) }}" class="form-control-paces">
                    <span class="form-help-paces">Due date for cycle #1 invoice.</span>
                  </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:12px;">
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Contract Start Date *</label>
                    <input type="date" name="contract_start_date" value="{{ old('contract_start_date', $booking->contract_start_date?->format('Y-m-d') ?: date('Y-m-d')) }}" class="form-control-paces" required>
                  </div>
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Contract End Date *</label>
                    <input type="date" name="contract_end_date" value="{{ old('contract_end_date', $booking->contract_end_date?->format('Y-m-d') ?: date('Y-m-d', strtotime('+1 year'))) }}" class="form-control-paces" required>
                  </div>
                  <div>
                    <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Total Annual Commitment (৳)</label>
                    <input type="number" step="0.01" name="contract_cost" value="{{ old('contract_cost', $booking->final_cost) }}" placeholder="e.g. 36000.00" class="form-control-paces">
                  </div>
                </div>

                <div style="margin-bottom:12px;">
                  <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Upload Signed Agreement Copy (Image or PDF)</label>
                  <input type="file" name="agreement_file" accept="image/*,.pdf" class="form-control-paces">
                  <span class="form-help-paces">Upload scan/photo of the signed agreement or contract PDF (Max: 10MB).</span>
                </div>

                <div style="margin-bottom:12px;">
                  <label class="form-label-paces" style="font-size:0.8rem; font-weight:500;">Agreement Notes / Scope Summary</label>
                  <textarea name="amc_package_notes" class="form-control-paces" rows="3" placeholder="Key service scope deliverables, quarterly visit frequency, assigned lead team...">{{ old('amc_package_notes', $booking->amc_package_notes) }}</textarea>
                </div>

                <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
                  <span>✓ {{ $booking->contract_status === 'active' ? 'Save & Update Contract Details' : 'Activate AMC Contract' }}</span>
                </button>
              </form>
            </div>
          @elseif($booking->contract_status !== 'active')
            <div style="font-size:0.825rem; color:var(--paces-text-muted); font-style:italic;">
              AMC contract activation requires edit permission.
            </div>
          @endif
        </div>
      </div>

      <!-- ============================================== -->
      <!-- SECTION 1: IF STATUS IS PENDING REVIEW         -->
      <!-- ============================================== -->
      @if($booking->status === 'pending_review' && auth('admin')->user()?->hasPermission('bookings.edit'))
        
        <!-- Call Tracking Bar (Unreachable / Call Back Later / Custom Task) -->
        <div class="card-paces" style="padding:1rem 1.25rem; margin-bottom:1.5rem; background:#f8fafc; border:1px solid #e2e8f0;">
          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <div>
              <strong style="font-size:0.88rem; color:var(--paces-text-dark); font-weight:500;">Phone Outreach & Follow-up Tasks:</strong>
              <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">Log contact attempt, callback request, or schedule follow-up tasks</div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <!-- Button to open Unreachable Dropdown Form -->
              <button type="button" onclick="toggleUnreachableBox()" class="btn-paces btn-paces-outline btn-paces-sm" style="border-color:#fca5a5; color:#dc2626; font-weight:500;">
                ⚠️ Mark Unreachable ▼
              </button>

              <!-- Button: Call Back Later Toggle -->
              <button type="button" onclick="toggleCallBackLaterBox()" class="btn-paces btn-paces-outline btn-paces-sm" style="border-color:#fde68a; color:#b45309; font-weight:500;">
                ⏱️ Call Back Later ▼
              </button>

              <!-- Button: Add Custom Task -->
              <button type="button" onclick="toggleCustomTaskBox()" class="btn-paces btn-paces-outline btn-paces-sm" style="border-color:#93c5fd; color:#1d4ed8; font-weight:500;">
                📝 + Add Outreach Task ▼
              </button>
            </div>
          </div>

          <!-- Unreachable Form with Reasons Dropdown -->
          <div id="unreachableReasonBox" style="display:none; margin-top:12px; padding-top:12px; border-top:1px dashed #cbd5e1;">
            <form action="{{ route('admin.bookings.log-call', $booking) }}" method="POST">
              @csrf
              <input type="hidden" name="call_status" value="unreachable">
              
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:8px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Select Unreachable Reason *</label>
                  <select name="unreachable_reason" class="form-control-paces" required style="font-size:0.82rem; padding:6px 12px;">
                    <option value="Phone Switched Off / Out of Coverage">Phone Switched Off / Out of Coverage</option>
                    <option value="Ringing but No Answer">Ringing but No Answer</option>
                    <option value="Busy / Call Waiting">Busy / Call Waiting</option>
                    <option value="Number Invalid / Disconnected">Number Invalid / Disconnected</option>
                    <option value="Call Rejected by Client">Call Rejected by Client</option>
                    <option value="Other / Client Unavailable">Other / Client Unavailable</option>
                  </select>
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Additional Note (Optional)</label>
                  <input type="text" name="call_notes" class="form-control-paces" placeholder="e.g. Tried 2 times at 11:30 AM..." style="font-size:0.82rem; padding:6px 10px;">
                </div>
              </div>

              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-paces btn-paces-danger btn-paces-sm" style="font-size:0.82rem;">
                  Save Unreachable (Auto-creates Task)
                </button>
              </div>
            </form>
          </div>

          <!-- Inline Box for Call Back Later Note -->
          <div id="callBackLaterBox" style="display:none; margin-top:12px; padding-top:12px; border-top:1px dashed #cbd5e1;">
            <form action="{{ route('admin.bookings.log-call', $booking) }}" method="POST">
              @csrf
              <input type="hidden" name="call_status" value="call_back_later">
              
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:8px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Requested Follow-up Time *</label>
                  <select name="callback_time" class="form-control-paces" required style="font-size:0.82rem; padding:6px 12px;">
                    <option value="Call after 1 hour">Call after 1 hour</option>
                    <option value="Call in the afternoon (2-4 PM)">Call in the afternoon (2-4 PM)</option>
                    <option value="Call in the evening (6-8 PM)">Call in the evening (6-8 PM)</option>
                    <option value="Call tomorrow morning">Call tomorrow morning</option>
                    <option value="Client in meeting - call later">Client in meeting - call later</option>
                  </select>
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Discussion Note (Optional)</label>
                  <input type="text" name="call_notes" class="form-control-paces" placeholder="e.g. Client requested callback after budget approval..." style="font-size:0.82rem; padding:6px 10px;">
                </div>
              </div>

              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.82rem;">
                  Save Follow-up (Auto-creates Task)
                </button>
              </div>
            </form>
          </div>

          <!-- Inline Box for Manual Custom Outreach Task -->
          <div id="customTaskBox" style="display:none; margin-top:12px; padding-top:12px; border-top:1px dashed #cbd5e1;">
            <form action="{{ route('admin.outreach-tasks.store') }}" method="POST">
              @csrf
              <input type="hidden" name="booking_id" value="{{ $booking->id }}">
              <input type="hidden" name="customer_name" value="{{ $booking->customer_name }}">
              <input type="hidden" name="customer_phone" value="{{ $booking->customer_phone }}">

              <div style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:10px; margin-bottom:8px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Task Action / Title *</label>
                  <input type="text" name="title" class="form-control-paces" placeholder="e.g. Call client regarding quotation approval" required style="font-size:0.82rem; padding:6px 10px;">
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Task Type *</label>
                  <select name="task_type" class="form-control-paces" required style="font-size:0.82rem; padding:6px 10px;">
                    <option value="follow_up">Follow-up Call</option>
                    <option value="quotation">Send Quotation</option>
                    <option value="inspection">Site Inspection</option>
                    <option value="booking_confirmation">Confirmation</option>
                    <option value="general">General Task</option>
                  </select>
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Priority *</label>
                  <select name="priority" class="form-control-paces" required style="font-size:0.82rem; padding:6px 10px;">
                    <option value="normal" selected>Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">🚨 Urgent</option>
                  </select>
                </div>
              </div>

              <div style="display:grid; grid-template-columns:1fr 2fr; gap:10px; margin-bottom:8px;">
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Due Time (Optional)</label>
                  <input type="datetime-local" name="due_at" class="form-control-paces" style="font-size:0.82rem; padding:6px 10px;">
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Notes (Optional)</label>
                  <input type="text" name="notes" class="form-control-paces" placeholder="e.g. Client requested callback before 4 PM..." style="font-size:0.82rem; padding:6px 10px;">
                </div>
              </div>

              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.82rem;">
                  Save Task
                </button>
              </div>
            </form>
          </div>

          <!-- Existing Outreach Tasks for this booking -->
          @if($booking->outreachTasks->isNotEmpty())
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #e2e8f0;">
              <strong style="font-size:0.8rem; color:#1e293b; display:block; margin-bottom:8px;">Outreach Tasks for this Order:</strong>
              <div style="display:flex; flex-direction:column; gap:6px;">
                @foreach($booking->outreachTasks as $ot)
                  <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 10px; background:#ffffff; border:1px solid #edf2f7; border-radius:6px; font-size:0.8rem; gap:8px;">
                    <div>
                      <div style="font-weight:600; color:{{ $ot->status === 'completed' ? '#64748b' : '#1e293b' }}; text-decoration:{{ $ot->status === 'completed' ? 'line-through' : 'none' }};">
                        {{ preg_replace('/\s*\(#[A-Za-z0-9-]+\)\s*$/', '', $ot->title) }}
                      </div>
                      <div style="font-size:0.72rem; color:#64748b; margin-top:2px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span style="font-weight:500; text-transform:uppercase;">{{ $ot->type_label }}</span>
                        @if($ot->due_at)
                          <span>• Due: {{ $ot->due_at->diffForHumans() }}</span>
                        @endif
                        @if($ot->notes)
                          <span>• Note: {{ $ot->notes }}</span>
                        @endif
                        @if($ot->status === 'completed')
                          <span style="color:#16a34a; font-weight:600;">• ✓ Completed</span>
                        @endif
                      </div>
                    </div>

                    @if($ot->status === 'pending')
                      <form action="{{ route('admin.outreach-tasks.complete', $ot) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-paces btn-paces-sm" style="padding:3px 8px; font-size:0.72rem; background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; font-weight:600; border-radius:5px; cursor:pointer;" title="Mark Task Complete">
                          ✓ Done
                        </button>
                      </form>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        <!-- Accept Work Order Card -->
        <div class="card-paces" style="border:2px solid #16a34a; margin-bottom:1.5rem;">
          <div class="card-header-paces" style="background:#f0fdf4; border-bottom:1px solid #bbf7d0;">
            <h3 class="card-title-paces" style="color:#166534; display:flex; align-items:center; gap:8px;">
              <span>✓</span> Accept Work Order & Assign Dispatch
            </h3>
          </div>
          
          <div class="card-body-paces" style="padding:1.5rem;">
            <form action="{{ route('admin.bookings.accept', $booking) }}" method="POST">
              @csrf

              <!-- Pricing Agreement Section (Default OPEN with Radio Buttons & Estimated Checkbox) -->
              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:14px; margin-bottom:1.25rem;">
                <div style="font-weight:500; font-size:0.88rem; color:var(--paces-text-dark); margin-bottom:10px;">
                  Set Service Price Agreement
                </div>

                <!-- 2 Radio Buttons: Price (Default) vs Set Price Later -->
                <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:center; margin-bottom:12px;">
                  <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:500; color:var(--paces-text-dark);">
                    <input type="radio" name="pricing_mode" value="price" id="pricingModePrice" checked onchange="togglePricingInput(this.value)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                    <span>Price</span>
                  </label>

                  <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:500; color:var(--paces-text-dark);">
                    <input type="radio" name="pricing_mode" value="later" id="pricingModeLater" onchange="togglePricingInput(this.value)" style="accent-color:var(--paces-primary); width:16px; height:16px;">
                    <span>Set Price Later</span>
                  </label>
                </div>

                <!-- Amount Input Row with Estimated Checkbox on the right -->
                <div id="priceAmountRow" style="display:flex; align-items:flex-end; gap:16px; flex-wrap:wrap; padding-top:8px; border-top:1px dashed var(--paces-border);">
                  <div>
                    <label class="form-label-paces" for="costAmountInput" style="font-weight:500; font-size:0.82rem;">Service Amount (৳) <span style="color:#ef4444;">*</span></label>
                    <input 
                      type="number" 
                      step="0.01" 
                      name="cost_amount" 
                      id="costAmountInput" 
                      class="form-control-paces" 
                      placeholder="e.g. 3500.00" 
                      value="{{ $booking->final_cost > 0 ? $booking->final_cost : ($booking->estimated_cost > 0 ? $booking->estimated_cost : '') }}"
                      style="width:200px;"
                      required
                    >
                  </div>

                  <div style="padding-bottom:10px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.85rem; font-weight:500; color:var(--paces-text-dark);">
                      <input type="checkbox" name="is_estimated" value="1" {{ $booking->estimated_cost > 0 && !$booking->final_cost ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--paces-primary); cursor:pointer;">
                      <span>Estimated</span>
                    </label>
                  </div>
                </div>

                <div id="priceLaterNote" style="display:none; font-size:0.78rem; color:var(--paces-text-muted); padding-top:6px;">
                  Price will be assessed on site and billed upon work completion.
                </div>
              </div>

              <!-- Scheduled Date & Time Slot Grid -->
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
                <div>
                  <label class="form-label-paces" style="font-weight:500; font-size:0.82rem;">Scheduled Date *</label>
                  <input 
                    type="date" 
                    name="preferred_date" 
                    class="form-control-paces" 
                    value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d')) }}" 
                    required
                  >
                </div>
                <div>
                  <label class="form-label-paces" style="font-weight:500; font-size:0.82rem;">Time Slot *</label>
                  <select name="preferred_time_slot" class="form-control-paces" required>
                    @foreach(['09:00 AM - 01:00 PM', '02:00 PM - 05:00 PM', '06:00 PM - 09:00 PM', 'Immediate Emergency Dispatch'] as $slot)
                      <option value="{{ $slot }}" {{ $booking->preferred_time_slot === $slot ? 'selected' : '' }}>
                        {{ $slot }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <!-- Field Crew / Lead Assignment (Searchable Selection) -->
              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" style="font-weight:500; font-size:0.82rem;">
                  Assign Field Crew / Lead / Enlisted Partner
                </label>
                <div style="position:relative;" id="crewDropdownWrapper_accept">
                  <div style="position:relative; display:flex; align-items:center;">
                    <input 
                      type="text" 
                      id="crewInput_accept" 
                      name="assigned_team" 
                      class="form-control-paces" 
                      placeholder="Click or search contractor, technician or lead..." 
                      value="{{ old('assigned_team', $booking->assigned_team) }}"
                      autocomplete="off"
                      style="padding-right:55px;"
                      onfocus="openCrewDropdown('accept')"
                      oninput="filterCrewOptions('accept')"
                    >
                    <div style="position:absolute; right:8px; display:flex; align-items:center; gap:4px;">
                      <button type="button" onclick="clearCrewSelection('accept')" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1rem; padding:0 4px; display:{{ $booking->assigned_team ? 'inline' : 'none' }};" id="crewClearBtn_accept" title="Clear selection">✕</button>
                      <button type="button" onclick="toggleCrewDropdown('accept')" style="background:none; border:none; color:#64748b; cursor:pointer; padding:2px;" title="View list">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                      </button>
                    </div>
                  </div>

                  <!-- Searchable Dropdown List -->
                  <div id="crewDropdownMenu_accept" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; max-height:240px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.12); z-index:9999; padding:4px 0;">
                    <div style="padding:6px 12px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase;">
                      Select Enlisted Partner or Field Technician ({{ $fieldCrews->count() }} available)
                    </div>
                    <div id="crewListItems_accept">
                      @foreach($fieldCrews as $crew)
                        @php
                          $compOrRole = $crew->company_name ?: $crew->role;
                          $crewFullVal = "{$crew->name} ({$compOrRole}) - {$crew->phone}";
                        @endphp
                        <div class="crew-option-item" 
                             data-val="{{ strtolower($crewFullVal) }}"
                             onclick="selectCrew('accept', '{{ addslashes($crewFullVal) }}', false)"
                             style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                             onmouseover="this.style.background='#eff6ff'" 
                             onmouseout="this.style.background='transparent'">
                          <div style="font-weight:600; font-size:0.85rem; color:#1e293b;">{{ $crew->name }}</div>
                          <div style="font-size:0.75rem; color:#64748b; display:flex; justify-content:space-between; margin-top:2px;">
                            <span>{{ $crew->role }}</span>
                            <span style="color:#0284c7; font-weight:500;">📞 {{ $crew->phone }}</span>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <div id="crewNoResults_accept" style="display:none; padding:12px; text-align:center; font-size:0.8rem; color:#94a3b8;">
                      No matching crew found. Custom text will be saved.
                    </div>
                  </div>
                </div>
              </div>

              <!-- Optional Customer Timeline Comment -->
              <div style="margin-bottom:1.5rem;">
                <label class="form-label-paces" style="font-weight:500; font-size:0.82rem;">Customer Note (Optional)</label>
                <input 
                  type="text" 
                  name="comment" 
                  class="form-control-paces" 
                  placeholder="e.g. Phone confirmation complete. Lead crew dispatched for scheduled inspection."
                >
              </div>

              <button type="submit" class="btn-paces" style="background:#16a34a; color:#fff; font-weight:500; padding:10px 24px; border:none; border-radius:var(--paces-radius-sm); cursor:pointer;">
                ✓ Confirm & Accept Work Order
              </button>
            </form>
          </div>
        </div>

        <!-- Cancel Section for Pending Orders -->
        <div class="card-paces" style="border:1px solid #fecaca; margin-bottom:1.5rem;">
          <div class="card-header-paces" style="background:#fef2f2; border-bottom:1px solid #fecaca;">
            <h3 class="card-title-paces" style="color:#dc2626; font-size:0.95rem;">✕ Cancel This Work Order</h3>
          </div>
          <div class="card-body-paces" style="padding:1.25rem;">
            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Cancel this work order?');">
              @csrf
              <div style="margin-bottom:10px;">
                <label class="form-label-paces" style="font-size:0.82rem; font-weight:500;">Cancellation Reason <span style="color:#ef4444;">*</span></label>
                <textarea name="cancellation_reason" class="form-control-paces" rows="4" placeholder="e.g. Client requested cancellation or duplicate request..." required></textarea>
              </div>
              <button type="submit" class="btn-paces" style="background:#dc2626; color:#fff; font-weight:500; font-size:0.85rem; border:none; padding:8px 16px; border-radius:var(--paces-radius-sm); cursor:pointer;">
                ✕ Confirm Cancellation
              </button>
            </form>
          </div>
        </div>

      <!-- ============================================== -->
      <!-- SECTION 2: IF ORDER IS ACCEPTED / IN PROGRESS  -->
      <!-- ============================================== -->
      @else
        <!-- Booking Details Card (Clean Presentation with Edit Button) -->
        <div class="card-paces" style="margin-bottom:1.5rem;">
          <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--paces-border); padding-bottom:1rem;">
            <div>
              <h3 class="card-title-paces" style="margin:0;">Booking Details</h3>
              <div style="font-size:0.8rem; color:var(--paces-text-muted);">Current operational execution state</div>
            </div>
            
            <div style="display:flex; gap:8px;">
              @if(auth('admin')->user()?->hasPermission('bookings.edit'))
                <button type="button" onclick="toggleEditOrderModal()" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:500; display:inline-flex; align-items:center; gap:6px;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  <span>Edit Order</span>
                </button>
              @endif
            </div>
          </div>

          <div class="card-body-paces" style="padding:1.5rem;">
            <!-- Summary Metric Grid -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1.25rem; margin-bottom:1.5rem;">
              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Scheduled Date & Slot</div>
                <div style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
                  {{ $booking->preferred_date?->format('M d, Y') }}
                </div>
                <div style="font-size:0.8rem; color:var(--paces-text-muted);">Slot: {{ $booking->preferred_time_slot }}</div>
              </div>

              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Assigned Field Crew</div>
                <div style="font-size:0.95rem; font-weight:500; color:var(--paces-primary); margin-top:2px;">
                  {{ $booking->assigned_team ?: 'Awaiting Team Dispatch' }}
                </div>
                <div style="font-size:0.8rem; color:var(--paces-text-muted);">Field Technical Lead</div>
              </div>

              @if($booking->is_amc)
                <div style="background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; padding:12px;">
                  <div style="font-size:0.72rem; color:#7c3aed; text-transform:uppercase; font-weight:700;">AMC Recurring Billing</div>
                  <div style="font-size:1.15rem; font-weight:700; color:#6d28d9; margin-top:2px;">
                    ৳ {{ number_format($booking->recurring_amount ?: $booking->final_cost, 2) }}
                    <span style="font-size:0.75rem; color:#7c3aed; font-weight:500;">/ {{ $booking->billing_cycle_label }}</span>
                  </div>
                  <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                    Next Due: {{ $booking->next_billing_date ? $booking->next_billing_date->format('M d, Y') : 'Active' }}
                  </div>
                </div>
              @else
                <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">One-Off Final Bill</div>
                  <div style="font-size:1.15rem; font-weight:500; color:var(--paces-success); margin-top:2px;">
                    @if($booking->final_cost > 0)
                      ৳ {{ number_format($booking->final_cost, 2) }} <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">(Invoiced)</span>
                    @elseif($booking->estimated_cost > 0)
                      ৳ {{ number_format($booking->estimated_cost, 2) }} <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">(Agreed Estimate)</span>
                    @else
                      <span style="font-size:0.88rem; color:#d97706; font-weight:500;">Billed upon completion</span>
                    @endif
                  </div>
                </div>
              @endif
            </div>

            <!-- Completion Banner & Feedback (If Completed) -->
            @if($booking->status === 'completed')
              <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:1.25rem; margin-bottom:1.5rem;">
                <div style="display:flex; align-items:center; gap:8px; color:#166534; font-weight:500; font-size:1rem; margin-bottom:6px;">
                  <span>✓</span> Work Order Completed
                </div>
                @if($booking->completion_feedback)
                  <div style="font-size:0.88rem; color:#14532d; line-height:1.5;">
                    <strong>Operations Remarks for Client:</strong> "{{ $booking->completion_feedback }}"
                  </div>
                @endif
              </div>

              <!-- Client Review & Rating Display (If Submitted) -->
              @if($booking->review_rating)
                <div style="background:#ffffff; border:1px solid #fed7aa; border-radius:8px; padding:1.25rem; margin-bottom:1.5rem;">
                  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <div style="font-weight:500; color:#9a3412; font-size:0.95rem;">
                      Client Review & Rating
                    </div>
                    <div style="color:#f59e0b; font-size:1.1rem;">
                      @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $booking->review_rating ? '★' : '☆' }}
                      @endfor
                      <span style="font-size:0.85rem; font-weight:500; color:var(--paces-text-dark); margin-left:4px;">({{ $booking->review_rating }}/5)</span>
                    </div>
                  </div>
                  <div style="font-size:0.88rem; color:var(--paces-text-dark); line-height:1.5;">
                    "{{ $booking->review_comment }}"
                  </div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:6px;">
                    Submitted by {{ $booking->customer_name }} • {{ $booking->reviewed_at?->format('M d, Y • h:i A') }}
                  </div>
                </div>
              @endif

            <!-- Complete Order Action Box (Only if assigned team is present) -->
            @elseif(in_array($booking->status, ['accepted', 'assigned', 'in_progress']) && auth('admin')->user()?->hasPermission('bookings.edit'))
              @if(!empty($booking->assigned_team))
                <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:1.25rem; margin-bottom:1rem;">
                  <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin:0 0 8px;">
                    Mark Work Order as Completed
                  </h4>
                  <p style="font-size:0.8rem; color:var(--paces-text-muted); margin-bottom:12px;">
                    Provide final completion feedback / remarks for the customer. The customer will then be invited to submit their service rating and review.
                  </p>

                  <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:12px;">
                      <label class="form-label-paces" style="font-size:0.82rem; font-weight:500;">
                        Completion Remarks / Feedback for Client <span style="color:#ef4444;">*</span>
                      </label>
                      <textarea 
                        name="completion_feedback" 
                        class="form-control-paces" 
                        rows="2" 
                        placeholder="e.g. Servicing complete. Substation capacitors tested and operational. 90-day maintenance warranty issued." 
                        required
                      ></textarea>
                    </div>

                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:12px;">
                      <div>
                        <label class="form-label-paces" style="font-size:0.82rem; font-weight:500;">Final Invoiced Bill (৳)</label>
                        <input 
                          type="number" 
                          step="0.01" 
                          name="final_cost" 
                          class="form-control-paces" 
                          value="{{ $booking->final_cost > 0 ? $booking->final_cost : ($booking->estimated_cost > 0 ? $booking->estimated_cost : '') }}" 
                          placeholder="e.g. 4500.00" 
                          style="width:180px;"
                        >
                      </div>
                    </div>

                    <button type="submit" class="btn-paces" style="background:#16a34a; color:#fff; font-weight:500; font-size:0.85rem; padding:8px 18px; border:none; border-radius:6px; cursor:pointer;">
                      ✓ Mark Completed
                    </button>
                  </form>
                </div>
              @else
                <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:1.25rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                  <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:1.4rem;">⚠️</span>
                    <div>
                      <div style="font-weight:600; font-size:0.88rem; color:#92400e;">Team Assignment Required Before Completion</div>
                      <div style="font-size:0.8rem; color:#b45309; margin-top:2px;">You must assign a field crew or lead technician before marking this work order as completed.</div>
                    </div>
                  </div>
                  <button type="button" onclick="toggleEditOrderModal()" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:600;">
                    Assign Crew Now
                  </button>
                </div>
              @endif
            @endif
          </div>
        </div>

        <!-- Collapsible / Editable Form Modal (Toggled via "Edit Order" button - Contains BOTH Edit & Cancel) -->
        <div id="editOrderSection" style="display:none; margin-bottom:1.5rem;">
          <div class="card-paces" style="border:2px solid var(--paces-primary);">
            <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center;">
              <h3 class="card-title-paces" style="margin:0;">Modify Work Order Details</h3>
              <button type="button" onclick="toggleEditOrderModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--paces-text-muted);">✕</button>
            </div>
            <div class="card-body-paces" style="padding:1.5rem;">
              <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
                  <div>
                    <label class="form-label-paces" style="font-weight:500;">
                      Work Order Status
                      <span id="statusAutoBadge" style="display:none; font-size:0.7rem; color:#10b981; font-weight:700; margin-left:6px;">✓ Auto Changed to In Progress</span>
                    </label>
                    <select name="status" class="form-control-paces" id="workOrderStatusSelect" required style="transition:border-color 0.25s, box-shadow 0.25s;">
                      @foreach(['pending_review' => 'Pending', 'accepted' => 'Accepted', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $stKey => $stLbl)
                        <option value="{{ $stKey }}" {{ $booking->status === $stKey ? 'selected' : '' }}>
                          {{ $stLbl }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div>
                    <label class="form-label-paces" style="font-weight:500;">Assigned Field Crew / Lead</label>
                    <div style="position:relative;" id="crewDropdownWrapper_edit">
                      <div style="position:relative; display:flex; align-items:center;">
                        <input 
                          type="text" 
                          id="crewInput_edit" 
                          name="assigned_team" 
                          class="form-control-paces" 
                          value="{{ old('assigned_team', $booking->assigned_team) }}" 
                          placeholder="Search contractor, technician or lead..."
                          autocomplete="off"
                          style="padding-right:55px;"
                          onfocus="openCrewDropdown('edit')"
                          oninput="filterCrewOptions('edit')"
                        >
                        <div style="position:absolute; right:8px; display:flex; align-items:center; gap:4px;">
                          <button type="button" onclick="clearCrewSelection('edit')" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1rem; padding:0 4px; display:{{ $booking->assigned_team ? 'inline' : 'none' }};" id="crewClearBtn_edit" title="Clear selection">✕</button>
                          <button type="button" onclick="toggleCrewDropdown('edit')" style="background:none; border:none; color:#64748b; cursor:pointer; padding:2px;" title="View list">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                          </button>
                        </div>
                      </div>

                      <!-- Searchable Dropdown List -->
                      <div id="crewDropdownMenu_edit" style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; max-height:260px; overflow-y:auto; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.12); z-index:9999; padding:4px 0;">
                        <div style="padding:6px 12px; background:#f8fafc; border-bottom:1px solid #f1f5f9; font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase;">
                          Select Enlisted Partner or Field Technician ({{ $fieldCrews->count() }} available)
                        </div>
                        <div id="crewListItems_edit">
                          @foreach($fieldCrews as $crew)
                            @php
                              $compOrRole = $crew->company_name ?: $crew->role;
                              $crewFullVal = "{$crew->name} ({$compOrRole}) - {$crew->phone}";
                            @endphp
                            <div class="crew-option-item" 
                                 data-val="{{ strtolower($crewFullVal) }}"
                                 onclick="selectCrew('edit', '{{ addslashes($crewFullVal) }}', true)"
                                 style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #f8fafc; transition:background 0.15s ease;"
                                 onmouseover="this.style.background='#eff6ff'" 
                                 onmouseout="this.style.background='transparent'">
                              <div style="font-weight:600; font-size:0.85rem; color:#1e293b;">{{ $crew->name }}</div>
                              <div style="font-size:0.75rem; color:#64748b; display:flex; justify-content:space-between; margin-top:2px;">
                                <span>{{ $crew->role }}</span>
                                <span style="color:#0284c7; font-weight:500;">📞 {{ $crew->phone }}</span>
                              </div>
                            </div>
                          @endforeach
                        </div>
                        <div id="crewNoResults_edit" style="display:none; padding:12px; text-align:center; font-size:0.8rem; color:#94a3b8;">
                          No matching crew found. Custom text will be saved.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
                  <div>
                    <label class="form-label-paces" style="font-weight:500;">Agreed Estimated Cost (৳)</label>
                    <input type="number" step="0.01" name="estimated_cost" class="form-control-paces" value="{{ old('estimated_cost', $booking->estimated_cost) }}">
                  </div>

                  <div>
                    <label class="form-label-paces" style="font-weight:500;">Final Invoiced Bill (৳)</label>
                    <input type="number" step="0.01" name="final_cost" class="form-control-paces" value="{{ old('final_cost', $booking->final_cost) }}">
                  </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:1.25rem;">
                  <div>
                    <label class="form-label-paces" style="font-weight:500;">Scheduled Date</label>
                    <input type="date" name="preferred_date" class="form-control-paces" value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d')) }}">
                  </div>

                  <div>
                    <label class="form-label-paces" style="font-weight:500;">Time Slot</label>
                    <select name="preferred_time_slot" class="form-control-paces">
                      @foreach(['09:00 AM - 01:00 PM', '02:00 PM - 05:00 PM', '06:00 PM - 09:00 PM', 'Immediate Emergency Dispatch'] as $slot)
                        <option value="{{ $slot }}" {{ $booking->preferred_time_slot === $slot ? 'selected' : '' }}>
                          {{ $slot }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                  <label class="form-label-paces" style="font-weight:500;">Internal Admin Notes</label>
                  <textarea name="admin_notes" class="form-control-paces" rows="5" placeholder="Internal remarks...">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                </div>

                <div style="margin-bottom:1.5rem;">
                  <label class="form-label-paces" style="font-weight:500;">Customer Note (Optional)</label>
                  <input type="text" name="comment" class="form-control-paces" placeholder="Optional update note for customer live timeline...">
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center;">
                  <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-paces btn-paces-primary" style="font-weight:500; padding:8px 20px;">
                      Save Updates
                    </button>
                    <button type="button" onclick="toggleEditOrderModal()" class="btn-paces btn-paces-outline" style="padding:8px 16px;">
                      Close
                    </button>
                  </div>
                </div>
              </form>

              <!-- Cancel Section is INSIDE Edit Order section (Item 13) -->
              @if($booking->status !== 'cancelled' && $booking->status !== 'completed' && auth('admin')->user()?->hasPermission('bookings.edit'))
                <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px dashed #fca5a5;">
                  <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:1.25rem;">
                    <h4 style="color:#dc2626; font-size:0.92rem; font-weight:500; margin:0 0 6px;">
                      ✕ Cancel This Work Order
                    </h4>
                    <p style="font-size:0.78rem; color:#991b1b; margin:0 0 10px;">
                      Cancelling will notify the client and free up the assigned technician.
                    </p>
                    <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this work order?');">
                      @csrf
                      <div style="margin-bottom:10px;">
                        <textarea name="cancellation_reason" class="form-control-paces" rows="4" placeholder="State reason for cancellation..." required></textarea>
                      </div>
                      <button type="submit" class="btn-paces" style="background:#dc2626; color:#fff; font-weight:500; font-size:0.82rem; border:none; padding:7px 16px; border-radius:var(--paces-radius-sm); cursor:pointer;">
                        ✕ Confirm Cancellation
                      </button>
                    </form>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>

      @endif

    </div>
  </div>

  <style>
    @media (max-width: 900px) {
      .workorder-grid {
        grid-template-columns: 1fr !important;
      }
    }
  </style>

  <script>
    function copyToClipboard(text, btn) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
          const original = btn.innerText;
          btn.innerText = '✓ Copied!';
          btn.style.color = '#16a34a';
          setTimeout(() => {
            btn.innerText = original;
            btn.style.color = '';
          }, 1800);
        });
      } else {
        const temp = document.createElement('input');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        btn.innerText = '✓ Copied!';
        setTimeout(() => { btn.innerText = '📋 Copy'; }, 1800);
      }
    }

    function togglePricingInput(mode) {
      const row = document.getElementById('priceAmountRow');
      const note = document.getElementById('priceLaterNote');
      const input = document.getElementById('costAmountInput');
      if (mode === 'price') {
        row.style.display = 'flex';
        note.style.display = 'none';
        if (input) input.required = true;
      } else {
        row.style.display = 'none';
        note.style.display = 'block';
        if (input) {
          input.required = false;
          input.value = '';
        }
      }
    }

    function toggleUnreachableBox() {
      const box = document.getElementById('unreachableReasonBox');
      const cbBox = document.getElementById('callBackLaterBox');
      const cBox = document.getElementById('customTaskBox');
      if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (cbBox) cbBox.style.display = 'none';
        if (cBox) cBox.style.display = 'none';
      }
    }

    function toggleCallBackLaterBox() {
      const cbBox = document.getElementById('callBackLaterBox');
      const box = document.getElementById('unreachableReasonBox');
      const cBox = document.getElementById('customTaskBox');
      if (cbBox) {
        cbBox.style.display = cbBox.style.display === 'none' ? 'block' : 'none';
        if (box) box.style.display = 'none';
        if (cBox) cBox.style.display = 'none';
      }
    }

    function toggleCustomTaskBox() {
      const cBox = document.getElementById('customTaskBox');
      const box = document.getElementById('unreachableReasonBox');
      const cbBox = document.getElementById('callBackLaterBox');
      if (cBox) {
        cBox.style.display = cBox.style.display === 'none' ? 'block' : 'none';
        if (box) box.style.display = 'none';
        if (cbBox) cbBox.style.display = 'none';
      }
    }

    function toggleEditOrderModal() {
      const el = document.getElementById('editOrderSection');
      if (el) {
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
        if (el.style.display === 'block') {
          el.scrollIntoView({ behavior: 'smooth' });
        }
      }
    }

    // Searchable Crew Selection & Auto Status Change
    function selectCrew(prefix, value, autoChangeStatus) {
      const input = document.getElementById('crewInput_' + prefix);
      const clearBtn = document.getElementById('crewClearBtn_' + prefix);
      if (input) {
        input.value = value;
      }
      if (clearBtn) {
        clearBtn.style.display = value ? 'inline' : 'none';
      }
      closeCrewDropdown(prefix);

      // Work Order Status automatic change
      if (autoChangeStatus && value) {
        const statusSelect = document.getElementById('workOrderStatusSelect');
        const badge = document.getElementById('statusAutoBadge');
        if (statusSelect) {
          statusSelect.value = 'in_progress';
          statusSelect.style.borderColor = '#10b981';
          statusSelect.style.boxShadow = '0 0 0 3px rgba(16,185,129,0.25)';
          if (badge) {
            badge.style.display = 'inline';
          }
          setTimeout(() => {
            statusSelect.style.borderColor = '';
            statusSelect.style.boxShadow = '';
          }, 2000);
        }
      }
    }

    function clearCrewSelection(prefix) {
      const input = document.getElementById('crewInput_' + prefix);
      const clearBtn = document.getElementById('crewClearBtn_' + prefix);
      if (input) {
        input.value = '';
        input.focus();
      }
      if (clearBtn) {
        clearBtn.style.display = 'none';
      }
      openCrewDropdown(prefix);
      filterCrewOptions(prefix);
    }

    function openCrewDropdown(prefix) {
      const menu = document.getElementById('crewDropdownMenu_' + prefix);
      if (menu) {
        menu.style.display = 'block';
        filterCrewOptions(prefix);
      }
    }

    function closeCrewDropdown(prefix) {
      const menu = document.getElementById('crewDropdownMenu_' + prefix);
      if (menu) menu.style.display = 'none';
    }

    function toggleCrewDropdown(prefix) {
      const menu = document.getElementById('crewDropdownMenu_' + prefix);
      if (menu) {
        if (menu.style.display === 'block') {
          menu.style.display = 'none';
        } else {
          openCrewDropdown(prefix);
          document.getElementById('crewInput_' + prefix)?.focus();
        }
      }
    }

    function filterCrewOptions(prefix) {
      const input = document.getElementById('crewInput_' + prefix);
      const filter = (input ? input.value : '').toLowerCase().trim();
      const container = document.getElementById('crewListItems_' + prefix);
      const noResults = document.getElementById('crewNoResults_' + prefix);
      const clearBtn = document.getElementById('crewClearBtn_' + prefix);

      if (clearBtn) {
        clearBtn.style.display = filter ? 'inline' : 'none';
      }

      if (!container) return;
      const items = container.getElementsByClassName('crew-option-item');
      let visibleCount = 0;

      for (let i = 0; i < items.length; i++) {
        const text = items[i].getAttribute('data-val').toLowerCase();
        if (!filter || text.includes(filter)) {
          items[i].style.display = 'block';
          visibleCount++;
        } else {
          items[i].style.display = 'none';
        }
      }

      if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    }

    document.addEventListener('click', function(e) {
      ['edit', 'accept'].forEach(function(prefix) {
        const wrapper = document.getElementById('crewDropdownWrapper_' + prefix);
        if (wrapper && !wrapper.contains(e.target)) {
          closeCrewDropdown(prefix);
        }
      });
    });

    // Image Lightbox & Zoom Functions
    let currentZoom = 1;
    let isDragging = false;
    let startX = 0, startY = 0;
    let translateX = 0, translateY = 0;

    function openImageLightbox(src, caption) {
      const modal = document.getElementById('imageLightboxModal');
      const img = document.getElementById('lightboxImage');
      const cap = document.getElementById('lightboxCaption');
      if (!modal || !img) return;

      img.src = src;
      if (cap) cap.textContent = caption || 'Problem Photo';
      resetLightboxZoom();
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeImageLightbox() {
      const modal = document.getElementById('imageLightboxModal');
      if (modal) modal.style.display = 'none';
      document.body.style.overflow = '';
      resetLightboxZoom();
    }

    function closeLightboxOnBackdrop(e) {
      if (e.target.id === 'imageLightboxModal' || e.target.id === 'lightboxViewport') {
        closeImageLightbox();
      }
    }

    function zoomLightbox(delta) {
      currentZoom = Math.max(0.5, Math.min(4, currentZoom + delta));
      updateLightboxTransform();
    }

    function resetLightboxZoom() {
      currentZoom = 1;
      translateX = 0;
      translateY = 0;
      updateLightboxTransform();
    }

    function updateLightboxTransform() {
      const img = document.getElementById('lightboxImage');
      const level = document.getElementById('lightboxZoomLevel');
      if (img) {
        img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
      }
      if (level) {
        level.textContent = Math.round(currentZoom * 100) + '%';
      }
    }

    function wheelZoomLightbox(e) {
      e.preventDefault();
      const delta = e.deltaY < 0 ? 0.2 : -0.2;
      zoomLightbox(delta);
    }

    function startDragLightbox(e) {
      if (currentZoom <= 1) return;
      isDragging = true;
      startX = e.clientX - translateX;
      startY = e.clientY - translateY;
      const vp = document.getElementById('lightboxViewport');
      if (vp) vp.style.cursor = 'grabbing';
    }

    function doDragLightbox(e) {
      if (!isDragging) return;
      translateX = e.clientX - startX;
      translateY = e.clientY - startY;
      updateLightboxTransform();
    }

    function stopDragLightbox() {
      isDragging = false;
      const vp = document.getElementById('lightboxViewport');
      if (vp) vp.style.cursor = 'grab';
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeImageLightbox();
      }
    });
  </script>

  <!-- Interactive Image Lightbox Modal with Zoom & Pan -->
  <div id="imageLightboxModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.92); z-index:99999; backdrop-filter:blur(6px); align-items:center; justify-content:center; flex-direction:column; padding:20px;" onclick="closeLightboxOnBackdrop(event)">
    <!-- Lightbox Toolbar -->
    <div style="position:absolute; top:18px; right:20px; display:flex; align-items:center; gap:10px; z-index:100000;">
      <div style="background:rgba(255,255,255,0.15); border-radius:8px; padding:4px 8px; display:flex; align-items:center; gap:8px; color:#fff; font-size:0.85rem;">
        <button type="button" onclick="zoomLightbox(-0.25)" style="background:none; border:none; color:#fff; cursor:pointer; font-size:1.2rem; font-weight:700; padding:2px 8px;" title="Zoom Out">−</button>
        <span id="lightboxZoomLevel" style="font-weight:600; min-width:44px; text-align:center;">100%</span>
        <button type="button" onclick="zoomLightbox(0.25)" style="background:none; border:none; color:#fff; cursor:pointer; font-size:1.2rem; font-weight:700; padding:2px 8px;" title="Zoom In">+</button>
        <button type="button" onclick="resetLightboxZoom()" style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:0.8rem; padding:2px 6px;" title="Reset Zoom">Reset</button>
      </div>
      <button type="button" onclick="closeImageLightbox()" style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:36px; height:36px; border-radius:50%; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center;" title="Close (Esc)">✕</button>
    </div>

    <div style="position:absolute; top:20px; left:24px; color:#f8fafc; font-weight:600; font-size:0.95rem; z-index:100000;" id="lightboxCaption">
      Problem Photo
    </div>

    <!-- Image Container with smooth transform and drag -->
    <div style="width:100%; height:100%; max-width:92vw; max-height:86vh; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative; cursor:grab;" id="lightboxViewport" onmousedown="startDragLightbox(event)" onmousemove="doDragLightbox(event)" onmouseup="stopDragLightbox()" onmouseleave="stopDragLightbox()" onwheel="wheelZoomLightbox(event)">
      <img id="lightboxImage" src="" alt="Zoomable View" style="max-width:100%; max-height:100%; object-fit:contain; transform-origin:center center; transition:transform 0.15s ease-out; user-select:none; pointer-events:none;">
    </div>
  </div>

  <!-- Modal: Record AMC Bill Collection -->
  <div id="collectAmcBillModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#ffffff; border-radius:12px; max-width:480px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); border:1px solid var(--paces-border); overflow:hidden;">
      <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
        <div>
          <h3 style="margin:0; font-size:1.05rem; font-weight:600; color:var(--paces-text-dark);">Record AMC Bill Payment</h3>
          <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;" id="modalInvoiceSubtitle">Invoice Collection</div>
        </div>
        <button type="button" onclick="closeCollectBillModal()" style="background:none; border:none; color:#64748b; font-size:1.2rem; cursor:pointer;">✕</button>
      </div>

      <form id="collectAmcBillForm" method="POST" action="" style="padding:1.5rem;">
        @csrf
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.82rem; font-weight:600; color:#065f46;">Amount to Collect:</span>
          <strong style="font-size:1.15rem; color:#047857;" id="modalInvoiceAmount">৳ 0.00</strong>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Payment Method *</label>
          <select name="payment_method" class="form-control-paces" required>
            <option value="Bank Transfer">Bank Transfer / Cheque</option>
            <option value="bKash">bKash (Merchant / Personal)</option>
            <option value="Nagad">Nagad</option>
            <option value="Cash">Cash on Delivery / Field Handover</option>
            <option value="POS / Card">Credit / Debit Card (POS)</option>
          </select>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Transaction / Cheque Reference</label>
          <input type="text" name="payment_reference" placeholder="e.g. TrxID 9H82J1 or Cheque #12345" class="form-control-paces">
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Payment Date *</label>
          <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" class="form-control-paces" required>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces" style="font-size:0.8rem; font-weight:600;">Receipt Notes</label>
          <textarea name="notes" rows="2" placeholder="Received by manager, deposit slip filed..." class="form-control-paces"></textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeCollectBillModal()" class="btn-paces btn-paces-outline btn-paces-sm">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:600; padding:6px 16px;">
            ✓ Confirm Bill Collection
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openCollectBillModal(invoiceId, invoiceNumber, amountFormatted) {
      const modal = document.getElementById('collectAmcBillModal');
      const form = document.getElementById('collectAmcBillForm');
      const sub = document.getElementById('modalInvoiceSubtitle');
      const amt = document.getElementById('modalInvoiceAmount');
      if (modal && form) {
        form.action = "{{ url('admin/bookings/' . $booking->id . '/amc-bills') }}/" + invoiceId + "/collect";
        if (sub) sub.textContent = "Invoice #" + invoiceNumber + " • Work Order #{{ $booking->booking_number }}";
        if (amt) amt.textContent = "৳ " + amountFormatted;
        modal.style.display = 'flex';
      }
    }
    function closeCollectBillModal() {
      const modal = document.getElementById('collectAmcBillModal');
      if (modal) modal.style.display = 'none';
    }
  </script>
</x-layouts.admin>
