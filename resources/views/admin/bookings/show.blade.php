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
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:10px 16px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-weight:500; font-size:0.85rem; display:flex; align-items:center; gap:8px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:10px 16px; border-radius:var(--paces-radius-sm); margin-bottom:1.25rem; font-size:0.85rem;">
      <div style="font-weight:500; margin-bottom:4px;">Please review the following errors:</div>
      <ul style="padding-left:18px; margin:0;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
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

        <!-- Full Name -->
        <div style="margin-bottom:12px;">
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
      
      <!-- ============================================== -->
      <!-- SECTION 1: IF STATUS IS PENDING REVIEW         -->
      <!-- ============================================== -->
      @if($booking->status === 'pending_review' && auth('admin')->user()?->hasPermission('bookings.edit'))
        
        <!-- Call Tracking Bar (Unreachable with Reasons Dropdown / Call Back Later) -->
        <div class="card-paces" style="padding:1rem 1.25rem; margin-bottom:1.5rem; background:#f8fafc; border:1px solid #e2e8f0;">
          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <div>
              <strong style="font-size:0.88rem; color:var(--paces-text-dark); font-weight:500;">Phone Outreach Status:</strong>
              <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">Log contact attempt if client is unavailable or requested callback</div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <!-- Button to open Unreachable Dropdown Form -->
              <button type="button" onclick="toggleUnreachableBox()" class="btn-paces btn-paces-outline btn-paces-sm" style="border-color:#fca5a5; color:#dc2626; font-weight:500;">
                ⚠️ Mark Unreachable ▼
              </button>

              <!-- Button: Call Back Later Toggle -->
              <button type="button" onclick="toggleCallBackLaterBox()" class="btn-paces btn-paces-outline btn-paces-sm" style="border-color:#fde68a; color:#b45309; font-weight:500;">
                ⏱️ Call Back Later Note ▼
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
                    <option value="Phone Switched Off / Out of Coverage">Phone Switched Off / Out of Coverage (ফোন বন্ধ / নেটওয়ার্কের বাইরে)</option>
                    <option value="Ringing but No Answer">Ringing but No Answer (রিং বাজে কিন্তু ধরে না)</option>
                    <option value="Busy / Call Waiting">Busy / Call Waiting (ব্যস্ত / কল ওয়েটিং)</option>
                    <option value="Number Invalid / Disconnected">Number Invalid / Disconnected (নাম্বার সঠিক নয়)</option>
                    <option value="Call Rejected by Client">Call Rejected by Client (কল কেটে দেওয়া হয়েছে)</option>
                    <option value="Other / Client Unavailable">Other / Client Unavailable (অন্যান্য)</option>
                  </select>
                </div>
                <div>
                  <label class="form-label-paces" style="font-size:0.78rem; font-weight:500;">Additional Note (Optional)</label>
                  <input type="text" name="call_notes" class="form-control-paces" placeholder="e.g. Tried 2 times at 11:30 AM..." style="font-size:0.82rem; padding:6px 10px;">
                </div>
              </div>

              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-paces btn-paces-danger btn-paces-sm" style="font-size:0.82rem;">
                  Save Unreachable Log
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
                  Save Follow-up Note
                </button>
              </div>
            </form>
          </div>
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
                    <label class="form-label-paces" for="costAmountInput" style="font-weight:500; font-size:0.82rem;">Service Amount (৳)</label>
                    <input 
                      type="number" 
                      step="0.01" 
                      name="cost_amount" 
                      id="costAmountInput" 
                      class="form-control-paces" 
                      placeholder="e.g. 3500.00" 
                      value="{{ $booking->final_cost > 0 ? $booking->final_cost : ($booking->estimated_cost > 0 ? $booking->estimated_cost : '') }}"
                      style="width:200px;"
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
                <input 
                  list="fieldCrewList" 
                  name="assigned_team" 
                  class="form-control-paces" 
                  placeholder="Select from registered crew/partner or type name..." 
                  value="{{ old('assigned_team', $booking->assigned_team) }}"
                >
                <datalist id="fieldCrewList">
                  @foreach($fieldCrews as $crew)
                    <option value="{{ $crew->name }} ({{ $crew->company_name ?: $crew->role }}) - {{ $crew->phone }}">
                  @endforeach
                </datalist>
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
                <textarea name="cancellation_reason" class="form-control-paces" rows="2" placeholder="e.g. Client requested cancellation or duplicate request..." required></textarea>
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
                  ✏️ Edit Order
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

              <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:12px;">
                <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:500;">Billing Total</div>
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

            <!-- Complete Order Action Box (If in progress, assigned, or accepted) -->
            @elseif(in_array($booking->status, ['accepted', 'assigned', 'in_progress']) && auth('admin')->user()?->hasPermission('bookings.edit'))
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
                    ✓ Mark Completed & Send Feedback to Client
                  </button>
                </form>
              </div>
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
                    <label class="form-label-paces" style="font-weight:500;">Work Order Status</label>
                    <select name="status" class="form-control-paces" required>
                      @foreach(['pending_review' => 'Pending Review', 'accepted' => 'Accepted', 'assigned' => 'Assigned', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $stKey => $stLbl)
                        <option value="{{ $stKey }}" {{ $booking->status === $stKey ? 'selected' : '' }}>
                          {{ $stLbl }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div>
                    <label class="form-label-paces" style="font-weight:500;">Assigned Field Crew / Lead</label>
                    <input 
                      list="fieldCrewListEdit" 
                      name="assigned_team" 
                      class="form-control-paces" 
                      value="{{ old('assigned_team', $booking->assigned_team) }}" 
                      placeholder="Assign contractor or lead..."
                    >
                    <datalist id="fieldCrewListEdit">
                      @foreach($fieldCrews as $crew)
                        <option value="{{ $crew->name }} ({{ $crew->company_name ?: $crew->role }}) - {{ $crew->phone }}">
                      @endforeach
                    </datalist>
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
                  <textarea name="admin_notes" class="form-control-paces" rows="2" placeholder="Internal remarks...">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
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
                        <textarea name="cancellation_reason" class="form-control-paces" rows="2" placeholder="State reason for cancellation..." required></textarea>
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
      if (mode === 'price') {
        row.style.display = 'flex';
        note.style.display = 'none';
      } else {
        row.style.display = 'none';
        note.style.display = 'block';
      }
    }

    function toggleUnreachableBox() {
      const box = document.getElementById('unreachableReasonBox');
      const cbBox = document.getElementById('callBackLaterBox');
      if (box) {
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (cbBox) cbBox.style.display = 'none';
      }
    }

    function toggleCallBackLaterBox() {
      const cbBox = document.getElementById('callBackLaterBox');
      const box = document.getElementById('unreachableReasonBox');
      if (cbBox) {
        cbBox.style.display = cbBox.style.display === 'none' ? 'block' : 'none';
        if (box) box.style.display = 'none';
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
  </script>
</x-layouts.admin>
