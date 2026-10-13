<x-layouts.admin title="Work Order #{{ $booking->booking_number }} | FIX Operations">
  <div class="page-header-box">
    <div>
      <div style="margin-bottom:0.5rem;">
        <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          ← Back to All Orders
        </a>
      </div>
      <h1 class="page-header-title">Work Order #{{ $booking->booking_number }}</h1>
      <p class="page-header-subtitle">
        Submitted: {{ $booking->created_at->format('M d, Y • h:i A') }} • SLA Priority: 
        <strong style="color:{{ $booking->urgency === 'emergency_1h' ? 'var(--paces-danger)' : 'var(--paces-warning)' }};">
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
    <div>
      <span class="badge-pill-paces {{ $statusClass }}" style="font-size:0.9rem; padding:6px 16px;">
        {{ $booking->status_label }}
      </span>
    </div>
  </div>

  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; align-items:start;">
    <!-- Left Column: Details & Audit Trail -->
    <div>
      <!-- Order Overview Card -->
      <div class="card-paces">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem;">
          <div>
            <h3 class="card-title-paces">Service Scope & Property Location</h3>
            <div style="font-size:0.8rem; color:var(--paces-text-muted);">Customer profile and facility address</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Billing Total</div>
            <div style="font-size:1.35rem; font-weight:800; color:var(--paces-success); font-family:'Outfit', sans-serif;">
              ৳ {{ number_format($booking->final_cost ?? ($booking->estimated_cost ?? 0), 2) }}
            </div>
          </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
          <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border);">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:800; letter-spacing:0.05em; margin-bottom:8px;">Customer Profile</div>
            <div style="font-size:1.1rem; font-weight:800; color:var(--paces-text-dark); margin-bottom:4px;">{{ $booking->customer_name }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-muted); margin-bottom:3px;">📞 {{ $booking->customer_phone }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-muted); margin-bottom:3px;">✉️ {{ $booking->customer_email }}</div>
            @if($booking->company_name)
              <div style="font-size:0.85rem; color:var(--paces-primary); font-weight:700; margin-top:6px;">🏢 {{ $booking->company_name }}</div>
            @endif
          </div>

          <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border);">
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:800; letter-spacing:0.05em; margin-bottom:8px;">Facility Location</div>
            <div style="font-size:1.1rem; font-weight:800; color:var(--paces-text-dark); margin-bottom:4px; text-transform:capitalize;">{{ $booking->property_type }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-dark); font-weight:500; margin-bottom:3px;">{{ $booking->street_address }}</div>
            <div style="font-size:0.85rem; color:var(--paces-text-muted);">📍 {{ $booking->area }}, {{ $booking->district }}, {{ $booking->division }}</div>
          </div>
        </div>

        <!-- Problem Description Box -->
        <div style="background:var(--paces-primary-subtle); border-radius:10px; padding:1.25rem; border:1px solid var(--paces-primary-border); margin-bottom:1.5rem;">
          <div style="font-size:0.75rem; font-weight:800; color:var(--paces-primary); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Requested Service</div>
          <div style="font-size:1.2rem; font-weight:800; color:var(--paces-text-dark); margin-bottom:8px; font-family:'Outfit', sans-serif;">
            {{ $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Facility Service') }}
          </div>
          <div style="font-size:0.9rem; color:var(--paces-text-dark); line-height:1.6; white-space:pre-line;">
            {{ $booking->problem_description }}
          </div>
        </div>

        <!-- Schedule & Team Assignment Grid -->
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1.25rem; border-top:1px solid var(--paces-border); padding-top:1.25rem;">
          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Scheduled Date</div>
            <div style="font-size:0.95rem; font-weight:800; color:var(--paces-text-dark); margin-top:2px;">{{ $booking->preferred_date->format('M d, Y') }}</div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted); text-transform:capitalize;">Slot: {{ $booking->preferred_time_slot }}</div>
          </div>

          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Assigned Crew</div>
            <div style="font-size:0.95rem; font-weight:800; color:var(--paces-primary); margin-top:2px;">
              {{ $booking->assigned_team ?: 'Awaiting Dispatch' }}
            </div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted);">Field Engineering Unit</div>
          </div>

          <div>
            <div style="font-size:0.72rem; color:var(--paces-text-muted); text-transform:uppercase; font-weight:700;">Estimated Cost</div>
            <div style="font-size:0.95rem; font-weight:800; color:var(--paces-text-dark); margin-top:2px;">
              ৳ {{ number_format($booking->estimated_cost ?? 0, 2) }}
            </div>
            <div style="font-size:0.8rem; color:var(--paces-text-muted);">Subject to onsite diagnostic</div>
          </div>
        </div>
      </div>

      <!-- Status Logs & Live Audit Timeline -->
      <div class="card-paces">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem;">
          <div>
            <h3 class="card-title-paces">Operational Audit Timeline</h3>
            <div style="font-size:0.8rem; color:var(--paces-text-muted);">Real-time history synchronized with client portal</div>
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
                    <span style="font-weight:800; color:var(--paces-text-dark);">
                      {{ ucfirst(str_replace('_', ' ', $log->from_status)) }} ➔ {{ ucfirst(str_replace('_', ' ', $log->to_status)) }}
                    </span>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); background:#f1f5f9; padding:2px 8px; border-radius:12px;">
                      {{ $log->changedByUser?->name ?? 'System Automated' }}
                    </span>
                    <span style="font-size:0.75rem; color:var(--paces-text-muted);">
                      • {{ $log->created_at->format('M d, Y • h:i A') }}
                    </span>
                  </div>
                  @if($log->comment)
                    <div style="margin-top:6px; background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:8px 12px; font-size:0.85rem; color:var(--paces-text-dark); line-height:1.5;">
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

    <!-- Right Column: Operations Workflow Update Form -->
    <div class="card-paces" style="position:sticky; top:90px;">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.25rem;">
        <h3 class="card-title-paces">Dispatch & Status Action</h3>
      </div>

      <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
        @csrf

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Workflow Status *</label>
          <select name="status" class="form-control-paces" required>
            <option value="pending_review" {{ $booking->status === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
            <option value="accepted" {{ $booking->status === 'accepted' ? 'selected' : '' }}>Accepted (Approved by Ops)</option>
            <option value="assigned" {{ $booking->status === 'assigned' ? 'selected' : '' }}>Team Assigned</option>
            <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>In Progress (On-Site)</option>
            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed (Work Verified)</option>
            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Assign Field Crew / Lead</label>
          <input type="text" name="assigned_team" class="form-control-paces" value="{{ old('assigned_team', $booking->assigned_team) }}" placeholder="e.g. Engr. Tanvir & Squad A">
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Estimated Cost (৳)</label>
          <input type="number" step="0.01" name="estimated_cost" class="form-control-paces" value="{{ old('estimated_cost', $booking->estimated_cost) }}" placeholder="0.00">
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Final Invoiced Cost (৳)</label>
          <input type="number" step="0.01" name="final_cost" class="form-control-paces" value="{{ old('final_cost', $booking->final_cost) }}" placeholder="0.00">
        </div>

        <div style="margin-bottom:1.25rem;">
          <label class="form-label-paces">Status Update Comment</label>
          <textarea name="comment" class="form-control-paces" rows="3" placeholder="Public note visible on customer's tracking portal..."></textarea>
          <span class="form-help-paces">Client will immediately receive live status notification.</span>
        </div>

        <button type="submit" class="btn-paces btn-paces-primary" style="width:100%; justify-content:center; padding:0.75rem 1rem;">
          Save Status Update & Notify Client
        </button>
      </form>
    </div>
  </div>
</x-layouts.admin>
