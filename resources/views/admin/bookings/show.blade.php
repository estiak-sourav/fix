<x-layouts.admin title="Work Order #{{ $booking->booking_number }} | FIX Operations">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="margin-bottom:6px;">
        <a href="{{ route('admin.bookings.index') }}" style="color:var(--paces-primary); font-size:0.85rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
          ← Back to All Work Orders
        </a>
      </div>
      <h1 class="page-header-title">Work Order #{{ $booking->booking_number }}</h1>
      <p class="page-header-subtitle">
        Submitted: {{ $booking->created_at->format('M d, Y • h:i A') }} • Urgency: 
        <strong style="color:{{ $booking->urgency === 'emergency_1h' ? '#dc2626' : '#d97706' }}; font-weight:500;">
          {{ ucfirst(str_replace('_', ' ', $booking->urgency)) }}
        </strong>
      </p>
    </div>

    @php
      $statusClass = match($booking->status) {
        'pending_review' => 'bg-warning-subtle',
        'accepted', 'confirmed' => 'bg-primary-subtle',
        'in_progress', 'assigned' => 'bg-info-subtle',
        'completed' => 'bg-success-subtle',
        'cancelled' => 'bg-danger-subtle',
        default => 'bg-primary-subtle'
      };
    @endphp
    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      <a href="tel:{{ $booking->customer_phone }}" class="btn-paces" style="background:#0284c7; color:#fff; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:var(--paces-radius-sm); font-size:0.85rem;">
        📞 Call Client: {{ $booking->customer_phone }}
      </a>
      <span class="badge-pill-paces {{ $statusClass }}" style="font-size:0.85rem; padding:6px 16px; font-weight:500;">
        {{ $booking->status_label }}
      </span>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-size:0.875rem;">
      <div style="font-weight:500; margin-bottom:4px;">Please review the following errors:</div>
      <ul style="padding-left:18px; margin:0;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Step 1 Notice Banner for Pending Orders: Phone Discussion & Acceptance -->
  @if($booking->status === 'pending_review')
    <div class="card-paces" style="background:#fffbeb; border:1px solid #fde68a; margin-bottom:1.5rem; padding:1.25rem 1.5rem;">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
        <div style="flex:1; min-width:280px;">
          <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <span style="font-size:1.2rem;">📞</span>
            <h3 style="font-size:1.05rem; font-weight:500; color:#92400e; margin:0;">
              Pending — Over-The-Phone Price Agreement Required
            </h3>
          </div>
          <p style="font-size:0.85rem; color:#78350f; margin:0 0 10px 0; line-height:1.5; font-weight:400;">
            Before accepting this order, talk with client <strong>{{ $booking->customer_name }}</strong> over the phone at <strong>{{ $booking->customer_phone }}</strong> to confirm problem details, requirements, and service price. You can set the agreed price now to accept, or accept with post-work billing if the price will be determined based on actual work.
          </p>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="tel:{{ $booking->customer_phone }}" class="btn-paces" style="background:#92400e; color:#fff; font-weight:500; font-size:0.85rem; text-decoration:none; padding:8px 16px; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
              <span>📞 Call Client Now</span>
            </a>
            <button type="button" onclick="document.getElementById('acceptOrderCard').scrollIntoView({behavior:'smooth'})" class="btn-paces" style="background:#16a34a; color:#fff; font-weight:500; font-size:0.85rem; border:none; padding:8px 18px; border-radius:6px; cursor:pointer;">
              ✓ Accept Order
            </button>
            <button type="button" onclick="document.getElementById('cancelOrderCard').scrollIntoView({behavior:'smooth'})" class="btn-paces" style="background:#dc2626; color:#fff; font-weight:500; font-size:0.85rem; border:none; padding:8px 18px; border-radius:6px; cursor:pointer;">
              ✕ Cancel Order
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; align-items:start;">
    <!-- Left Column: Details & Modifiers -->
    <div>
      <!-- Order Scope & Property Location Card -->
      <div class="card-paces">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem;">
          <div>
            <h3 class="card-title-paces">Service Scope & Property Location</h3>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); font-weight:400;">Customer profile and facility address</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Current Cost</div>
            <div style="font-size:1.35rem; font-weight:500; color:var(--paces-success); font-family:'Poppins', sans-serif;">
              @if($booking->final_cost > 0)
                ৳ {{ number_format($booking->final_cost, 2) }}
                <div style="font-size:0.7rem; color:var(--paces-text-muted); font-weight:400;">Final Invoiced Total</div>
              @elseif($booking->estimated_cost > 0)
                ৳ {{ number_format($booking->estimated_cost, 2) }}
                <div style="font-size:0.7rem; color:var(--paces-text-muted); font-weight:400;">Agreed Estimated Price</div>
              @else
                <span style="font-size:0.95rem; color:#d97706; font-weight:500;">To be billed upon work</span>
              @endif
            </div>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
          <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border);">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500; letter-spacing:0.05em; margin-bottom:8px;">Customer Profile</div>
            <div style="font-size:1.1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">{{ $booking->customer_name }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-dark); margin-bottom:3px; font-weight:500;">
              <a href="tel:{{ $booking->customer_phone }}" style="color:var(--paces-primary); text-decoration:none;">{{ $booking->customer_phone }}</a>
            </div>
            <div style="font-size:0.85rem; color:var(--paces-text-muted); margin-bottom:3px;">✉️ {{ $booking->customer_email }}</div>
            @if($booking->company_name)
              <div style="font-size:0.85rem; color:var(--paces-primary); font-weight:500; margin-top:6px;">🏢 {{ $booking->company_name }}</div>
            @endif
          </div>

          <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border);">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500; letter-spacing:0.05em; margin-bottom:8px;">Facility Location</div>
            <div style="font-size:1.1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px; text-transform:capitalize;">{{ $booking->property_type }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-dark); font-weight:400; margin-bottom:3px;">{{ $booking->street_address }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-muted); font-weight:500;">📍 {{ $booking->area }}, {{ $booking->district }}, {{ $booking->division }}</div>
          </div>
        </div>

        <!-- Problem Description Box -->
        <div style="background:var(--paces-primary-subtle); border-radius:10px; padding:1.25rem; border:1px solid var(--paces-primary-border); margin-bottom:1.5rem;">
          <div style="font-size:0.75rem; font-weight:500; color:var(--paces-primary); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Requested Service</div>
          <div style="font-size:1.2rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:8px;">
            {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Facility Service') }}
          </div>
          <div style="font-size:0.9rem; color:var(--paces-text-dark); line-height:1.6; white-space:pre-line; font-weight:400;">
            {{ $booking->problem_description }}
          </div>
        </div>

        <!-- Schedule & Team Assignment Grid -->
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1.25rem; border-top:1px solid var(--paces-border); padding-top:1.25rem;">
          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Scheduled Date</div>
            <div style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">{{ $booking->preferred_date->format('M d, Y') }}</div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); text-transform:capitalize; font-weight:400;">Slot: {{ $booking->preferred_time_slot }}</div>
          </div>

          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Assigned Crew</div>
            <div style="font-size:0.95rem; font-weight:500; color:var(--paces-primary); margin-top:2px;">
              {{ $booking->assigned_team ?: 'Awaiting Dispatch' }}
            </div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); font-weight:400;">Field Engineering Unit</div>
          </div>

          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Billing State</div>
            <div style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
              @if($booking->final_cost > 0)
                ৳ {{ number_format($booking->final_cost, 2) }} (Invoiced)
              @elseif($booking->estimated_cost > 0)
                ৳ {{ number_format($booking->estimated_cost, 2) }} (Agreed)
              @else
                Bill After Completion
              @endif
            </div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); font-weight:400;">
              {{ $booking->status === 'completed' ? 'Work completed' : 'Subject to work report' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Accept Order Section (Active for Pending Orders) -->
      @if($booking->status === 'pending_review' && auth('admin')->user()?->hasPermission('bookings.edit'))
        <div id="acceptOrderCard" class="card-paces" style="border:2px solid #16a34a; margin-bottom:1.5rem;">
          <div class="card-header-paces" style="background:#f0fdf4; border-bottom:1px solid #bbf7d0;">
            <h3 class="card-title-paces" style="color:#166534; display:flex; align-items:center; gap:8px;">
              <span>✓</span> Accept Work Order & Set Pricing
            </h3>
          </div>
          <div class="card-body-paces" style="padding:1.5rem;">
            <form action="{{ route('admin.bookings.accept', $booking) }}" method="POST">
              @csrf

              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" style="font-weight:500;">Pricing Agreement Mode *</label>
                <div style="display:flex; flex-direction:column; gap:8px; margin-top:6px;">
                  <label style="display:flex; align-items:flex-start; gap:8px; cursor:pointer; font-size:0.875rem;">
                    <input type="radio" name="pricing_type" value="agreed" checked onchange="togglePricingInput(true)" style="margin-top:3px;">
                    <div>
                      <strong style="font-weight:500;">Set Agreed Price Now</strong> — Price has been finalized over the phone with client.
                    </div>
                  </label>
                  <label style="display:flex; align-items:flex-start; gap:8px; cursor:pointer; font-size:0.875rem;">
                    <input type="radio" name="pricing_type" value="bill_later" onchange="togglePricingInput(false)" style="margin-top:3px;">
                    <div>
                      <strong style="font-weight:500;">Set Price Later (Bill Upon Completion / Onsite Work)</strong> — Price will be calculated and invoiced after inspection or work completion.
                    </div>
                  </label>
                </div>
              </div>

              <div id="agreedPriceBox" style="margin-bottom:1.25rem;">
                <label class="form-label-paces" for="agreedPriceInput" style="font-weight:500;">
                  Agreed Service Price (৳) <span style="color:#ef4444;">*</span>
                </label>
                <input 
                  type="number" 
                  step="0.01" 
                  name="agreed_price" 
                  id="agreedPriceInput" 
                  class="form-control-paces" 
                  placeholder="e.g. 3500.00" 
                  value="{{ old('agreed_price', $booking->estimated_cost) }}"
                >
                <small style="color:var(--paces-text-muted); font-size:0.75rem; font-weight:400; display:block; margin-top:4px;">
                  Client will see this agreed price on their tracking portal.
                </small>
              </div>

              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" for="assignedTeamInput" style="font-weight:500;">Assign Field Crew / Lead (Optional)</label>
                <input 
                  type="text" 
                  name="assigned_team" 
                  id="assignedTeamInput" 
                  class="form-control-paces" 
                  placeholder="e.g. Lead Engr. Tanvir & Squad B" 
                  value="{{ old('assigned_team', $booking->assigned_team) }}"
                >
              </div>

              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" for="acceptCommentInput" style="font-weight:500;">Notification Note for Client (Optional)</label>
                <textarea 
                  name="comment" 
                  id="acceptCommentInput" 
                  class="form-control-paces" 
                  rows="2" 
                  placeholder="e.g. Confirmed over phone with client. Crew dispatched for scheduled slot."
                ></textarea>
              </div>

              <button type="submit" class="btn-paces" style="background:#16a34a; color:#fff; font-weight:500; padding:10px 24px; border:none; border-radius:var(--paces-radius-sm); cursor:pointer;">
                ✓ Confirm & Accept Work Order
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Comprehensive Order Modifier Card (Modify all order details, set final bill, etc.) -->
      @if(auth('admin')->user()?->hasPermission('bookings.edit'))
        <div class="card-paces" style="margin-bottom:1.5rem;">
          <div class="card-header-paces">
            <h3 class="card-title-paces">Modify & Update Work Order Details</h3>
            <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">Adjust schedule, pricing, address, crew, or status</span>
          </div>

          <div class="card-body-paces" style="padding:1.5rem;">
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
              @csrf
              @method('PUT')

              <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.25rem; margin-bottom:1.25rem;">
                <div>
                  <label class="form-label-paces" style="font-weight:500;">Order Status *</label>
                  <select name="status" class="form-control-paces" required>
                    <option value="pending_review" {{ $booking->status === 'pending_review' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ $booking->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="assigned" {{ $booking->status === 'assigned' ? 'selected' : '' }}>Team Assigned</option>
                    <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>In Progress (On-Site)</option>
                    <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed (Work Done)</option>
                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                </div>

                <div>
                  <label class="form-label-paces" style="font-weight:500;">Assigned Crew / Lead</label>
                  <input type="text" name="assigned_team" class="form-control-paces" value="{{ old('assigned_team', $booking->assigned_team) }}" placeholder="e.g. Engr. Tanvir">
                </div>

                <div>
                  <label class="form-label-paces" style="font-weight:500;">Estimated / Agreed Cost (৳)</label>
                  <input type="number" step="0.01" name="estimated_cost" class="form-control-paces" value="{{ old('estimated_cost', $booking->estimated_cost) }}" placeholder="0.00">
                </div>

                <div>
                  <label class="form-label-paces" style="font-weight:500; color:#15803d;">
                    Final Invoiced Bill (৳) <span style="font-size:0.75rem; font-weight:400; color:var(--paces-text-muted);">(Set after work done)</span>
                  </label>
                  <input type="number" step="0.01" name="final_cost" class="form-control-paces" value="{{ old('final_cost', $booking->final_cost) }}" placeholder="0.00" style="border-color:#86efac;">
                </div>

                <div>
                  <label class="form-label-paces" style="font-weight:500;">Scheduled Date</label>
                  <input type="date" name="preferred_date" class="form-control-paces" value="{{ old('preferred_date', $booking->preferred_date?->format('Y-m-d')) }}">
                </div>

                <div>
                  <label class="form-label-paces" style="font-weight:500;">Preferred Slot</label>
                  <input type="text" name="preferred_time_slot" class="form-control-paces" value="{{ old('preferred_time_slot', $booking->preferred_time_slot) }}" placeholder="e.g. 10:00 AM - 01:00 PM">
                </div>
              </div>

              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" style="font-weight:500;">Street Address / Building Specifics</label>
                <input type="text" name="street_address" class="form-control-paces" value="{{ old('street_address', $booking->street_address) }}">
              </div>

              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" style="font-weight:500;">Problem Description / Technical Notes</label>
                <textarea name="problem_description" class="form-control-paces" rows="3">{{ old('problem_description', $booking->problem_description) }}</textarea>
              </div>

              <div style="margin-bottom:1.25rem;">
                <label class="form-label-paces" style="font-weight:500;">Internal Admin Notes</label>
                <textarea name="admin_notes" class="form-control-paces" rows="2" placeholder="Internal remarks not shown to client...">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
              </div>

              <div style="margin-bottom:1.5rem;">
                <label class="form-label-paces" style="font-weight:500;">Customer Timeline Update Note</label>
                <input type="text" name="comment" class="form-control-paces" placeholder="Optional note for the customer's live tracking timeline...">
              </div>

              <button type="submit" class="btn-paces btn-paces-primary" style="font-weight:500; padding:10px 24px;">
                Save & Update Work Order Details
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Status Logs & Live Audit Timeline -->
      <div class="card-paces">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem;">
          <div>
            <h3 class="card-title-paces">Operational Audit Timeline</h3>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); font-weight:400;">Real-time history synchronized with client portal</div>
          </div>
        </div>
        
        @if($booking->statusLogs->isEmpty())
          <div style="text-align:center; padding:2rem 1rem; color:var(--paces-text-muted); font-size:0.875rem;">
            No historical status modifications logged yet.
          </div>
        @else
          <div style="display:flex; flex-direction:column; gap:1.25rem; padding-left:8px;">
            @foreach($booking->statusLogs as $log)
              <div style="display:flex; gap:16px; font-size:0.875rem; border-left:2px solid var(--paces-primary); padding-left:16px; position:relative;">
                <div style="position:absolute; left:-7px; top:4px; width:12px; height:12px; border-radius:50%; background:var(--paces-primary); border:2px solid #fff;"></div>
                <div style="flex:1;">
                  <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    <span style="font-weight:500; color:var(--paces-text-dark);">
                      {{ ucfirst(str_replace('_', ' ', $log->from_status)) }} ➔ {{ ucfirst(str_replace('_', ' ', $log->to_status)) }}
                    </span>
                    <span style="font-size:0.75rem; font-weight:500; color:var(--paces-text-muted); background:#f1f5f9; padding:2px 8px; border-radius:12px;">
                      {{ $log->changedByAdmin?->name ?? ($log->changedByUser?->name ?? 'System Automated') }}
                    </span>
                    <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">
                      • {{ $log->created_at->format('M d, Y • h:i A') }}
                    </span>
                  </div>
                  @if($log->comment)
                    <div style="margin-top:6px; background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:8px 12px; font-size:0.85rem; color:var(--paces-text-dark); line-height:1.5; font-weight:400;">
                      "{{ $log->comment }}"
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- Right Column: Quick Status / Cancellation / Team Actions -->
    <div>
      <!-- Cancel Order Card -->
      @if($booking->status !== 'cancelled' && auth('admin')->user()?->hasPermission('bookings.edit'))
        <div id="cancelOrderCard" class="card-paces" style="margin-bottom:1.5rem; border:1px solid #fecaca;">
          <div class="card-header-paces" style="background:#fef2f2; border-bottom:1px solid #fecaca;">
            <h3 class="card-title-paces" style="color:#dc2626; font-size:1rem; display:flex; align-items:center; gap:6px;">
              <span>✕</span> Cancel This Work Order
            </h3>
          </div>
          <div class="card-body-paces" style="padding:1.25rem;">
            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this work order?');">
              @csrf

              <div style="margin-bottom:1rem;">
                <label class="form-label-paces" for="cancelReasonInput" style="font-weight:500; font-size:0.825rem;">
                  Cancellation Reason <span style="color:#ef4444;">*</span>
                </label>
                <textarea 
                  name="cancellation_reason" 
                  id="cancelReasonInput" 
                  class="form-control-paces" 
                  rows="3" 
                  placeholder="Reason for cancellation (e.g. client requested cancellation, duplicate request)..." 
                  required
                ></textarea>
              </div>

              <button type="submit" class="btn-paces" style="background:#dc2626; color:#fff; font-weight:500; font-size:0.85rem; width:100%; border:none; padding:8px 16px; border-radius:var(--paces-radius-sm); cursor:pointer;">
                ✕ Confirm Cancellation
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Client Quick Contact Card -->
      <div class="card-paces" style="margin-bottom:1.5rem;">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem;">
          <h3 class="card-title-paces" style="font-size:1rem;">Direct Contact</h3>
        </div>
        <div class="card-body-paces" style="padding:1.25rem;">
          <div style="margin-bottom:12px;">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">Customer Phone</div>
            <a href="tel:{{ $booking->customer_phone }}" style="font-size:1.05rem; font-weight:500; color:var(--paces-primary); text-decoration:none; display:inline-flex; align-items:center; gap:6px; margin-top:2px;">
              📞 {{ $booking->customer_phone }}
            </a>
          </div>

          <div style="margin-bottom:12px;">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">Customer Email</div>
            <a href="mailto:{{ $booking->customer_email }}" style="font-size:0.875rem; color:var(--paces-text-dark); text-decoration:none;">
              ✉️ {{ $booking->customer_email }}
            </a>
          </div>

          @if($booking->company_name)
            <div>
              <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">Company / Org</div>
              <div style="font-size:0.875rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
                🏢 {{ $booking->company_name }}
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePricingInput(show) {
      const box = document.getElementById('agreedPriceBox');
      const input = document.getElementById('agreedPriceInput');
      if (box && input) {
        if (show) {
          box.style.display = 'block';
          input.required = true;
        } else {
          box.style.display = 'none';
          input.required = false;
        }
      }
    }
  </script>
</x-layouts.admin>
