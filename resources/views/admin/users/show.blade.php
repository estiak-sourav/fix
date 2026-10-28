<x-layouts.admin title="Client Profile: {{ $user->name }} | FIX Admin">
  <div class="page-header-box">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
        <a href="{{ route('admin.users.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">← Back to Client Directory</a>
      </div>
      <h1 class="page-header-title">{{ $user->name }}</h1>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <a href="{{ route('admin.tickets.create', ['user_id' => $user->id]) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>+ Open Support Ticket</span>
      </a>
      <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('Toggle status for this client?');" style="margin:0;">
        @csrf
        @if($user->is_active)
          <button type="submit" class="btn-paces btn-paces-danger btn-paces-sm">
            Deactivate Account
          </button>
        @else
          <button type="submit" class="btn-paces btn-paces-success btn-paces-sm">
            Activate Account
          </button>
        @endif
      </form>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between; gap:8px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 360px 1fr; gap:1.5rem; align-items:start;">
    <!-- Left Column: User Profile Card & Edit Form -->
    <div class="card-paces">
      <div style="text-align:center; padding-bottom:1.25rem; border-bottom:1px solid var(--paces-border); margin-bottom:1.25rem;">
        @if($user->avatar_url)
          <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:72px; height:72px; border-radius:50%; object-fit:cover; margin:0 auto 12px; display:block; border:2px solid var(--paces-border);">
        @else
          <div style="width:72px; height:72px; border-radius:50%; background:var(--paces-primary); color:#ffffff; font-size:1.75rem; font-weight:500; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
        @endif
        <h3 style="font-size:1.15rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">{{ $user->name }}</h3>
        <div style="font-size:0.85rem; color:var(--paces-text-muted);">{{ $user->email }}</div>

        <div style="margin-top:10px;">
          @if($user->is_active)
            <span class="badge-pill-paces bg-success-subtle">
              <span style="width:6px; height:6px; border-radius:50%; background:#16a34a;"></span>
              Active Account
            </span>
          @else
            <span class="badge-pill-paces bg-danger-subtle">
              <span style="width:6px; height:6px; border-radius:50%; background:#dc2626;"></span>
              Deactivated Account
            </span>
          @endif
        </div>
      </div>

      <!-- Profile Update Form -->
      <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
          $canEditUser = auth('admin')->user()?->hasPermission('users.edit');
        @endphp

        <div style="margin-bottom:12px;">
          <label class="form-label-paces">Profile Picture (Avatar)</label>
          <input type="file" name="avatar" accept="image/*" class="form-control-paces" {{ $canEditUser ? '' : 'disabled' }}>
          <span class="form-help-paces" style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px; display:block;">Supported: JPG, PNG, WEBP (Max: 2MB)</span>
        </div>

        <div style="margin-bottom:12px;">
          <label class="form-label-paces">Full Name</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control-paces" required {{ $canEditUser ? '' : 'disabled' }}>
        </div>

        <div style="margin-bottom:12px;">
          <label class="form-label-paces">Contact Phone (Bangladesh)</label>
          <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control-paces" placeholder="+8801XXXXXXXXX" {{ $canEditUser ? '' : 'disabled' }}>
        </div>

        <div style="margin-bottom:12px;">
          <label class="form-label-paces">Company / Organization</label>
          <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="form-control-paces" placeholder="Optional enterprise name" {{ $canEditUser ? '' : 'disabled' }}>
        </div>

        <div style="margin-bottom:12px;">
          <label class="form-label-paces">Property / Facility Type</label>
          <select name="property_type" class="form-control-paces" {{ $canEditUser ? '' : 'disabled' }}>
            <option value="residential" {{ $user->property_type === 'residential' ? 'selected' : '' }}>Residential Apartment / House</option>
            <option value="commercial" {{ $user->property_type === 'commercial' ? 'selected' : '' }}>Commercial Office / Building</option>
            <option value="industrial" {{ $user->property_type === 'industrial' ? 'selected' : '' }}>Industrial / Factory / Warehouse</option>
            <option value="retail" {{ $user->property_type === 'retail' ? 'selected' : '' }}>Retail Store / Restaurant</option>
          </select>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
          <div>
            <label class="form-label-paces">Area / Thana</label>
            <input type="text" name="area" value="{{ old('area', $user->area) }}" class="form-control-paces" placeholder="e.g. Gulshan" {{ $canEditUser ? '' : 'disabled' }}>
          </div>
          <div>
            <label class="form-label-paces">District</label>
            <input type="text" name="district" value="{{ old('district', $user->district) }}" class="form-control-paces" placeholder="e.g. Dhaka" {{ $canEditUser ? '' : 'disabled' }}>
          </div>
        </div>

        <div style="margin-bottom:16px;">
          <label class="form-label-paces">Default Service Address</label>
          <textarea name="default_address" rows="5" class="form-control-paces" placeholder="House/Road/Block details..." {{ $canEditUser ? '' : 'disabled' }}>{{ old('default_address', $user->default_address) }}</textarea>
        </div>

        <div style="margin-bottom:16px;">
          <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; font-weight:500; cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} style="width:16px; height:16px;" {{ $canEditUser ? '' : 'disabled' }}>
            <span>Allow Client Login & Booking Dispatch</span>
          </label>
        </div>

        @if($canEditUser)
          <button type="submit" class="btn-paces btn-paces-primary" style="width:100%; justify-content:center;">
            Save Client Changes
          </button>
        @else
          <div style="font-size:0.8rem; color:#64748b; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:8px 12px; text-align:center;">
            👁️ Read-Only Mode: Client details cannot be modified without Edit permissions.
          </div>
        @endif
      </form>
    </div>

    <!-- Right Column: Booking History -->
    <div class="card-paces">
      <div class="card-header-paces">
        <h2 class="card-title-paces">Service Work Orders & Booking History ({{ $user->bookings_count }})</h2>
      </div>

      @if($bookings->isEmpty())
        <div style="text-align:center; padding:3rem 1rem; color:var(--paces-text-muted);">
          <div style="font-size:2rem; margin-bottom:8px;">📦</div>
          <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Bookings Placed Yet</h4>
          <p style="font-size:0.85rem;">This client has registered but hasn't submitted any service requests yet.</p>
        </div>
      @else
        <div class="table-responsive-paces">
          <table class="table-paces">
            <thead>
              <tr>
                <th>Order Number</th>
                <th>Service Scope</th>
                <th>Schedule</th>
                <th>Estimated Cost</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bookings as $b)
                <tr>
                  <td>
                    <strong style="color:var(--paces-primary); font-family:monospace; font-weight:500;">#{{ $b->booking_number }}</strong>
                    <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->created_at->format('M d, Y') }}</div>
                  </td>
                  <td>
                    <div style="font-weight:500;">{{ $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service') }}</div>
                    <div style="font-size:0.75rem; color:var(--paces-text-muted);">Area: {{ $b->area }}</div>
                  </td>
                  <td>
                    <div>{{ \Carbon\Carbon::parse($b->preferred_date)->format('M d, Y') }}</div>
                    <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize;">Slot: {{ $b->preferred_time_slot }}</div>
                  </td>
                  <td>
                    <div style="font-weight:500;">৳{{ number_format($b->total_estimated_cost, 2) }}</div>
                  </td>
                  <td>
                    @php
                      $statusClass = match($b->status) {
                        'pending_review' => 'bg-warning-subtle',
                        'accepted', 'confirmed' => 'bg-primary-subtle',
                        'in_progress', 'assigned' => 'bg-info-subtle',
                        'completed' => 'bg-success-subtle',
                        'cancelled' => 'bg-danger-subtle',
                        default => 'bg-primary-subtle'
                      };
                    @endphp
                    <span class="badge-pill-paces {{ $statusClass }}">
                      {{ ucfirst(str_replace('_', ' ', $b->status)) }}
                    </span>
                  </td>
                  <td style="text-align:right;">
                    <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-outline btn-paces-sm">
                      View Dispatch
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="margin-top:1.25rem;">
          {{ $bookings->links() }}
        </div>
      @endif
    </div>
  </div>
</x-layouts.admin>
