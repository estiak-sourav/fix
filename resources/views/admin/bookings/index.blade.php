<x-layouts.admin title="Booking Management | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <div>
      <h1 class="page-header-title">Booking</h1>
    </div>
  </div>

  <div class="card-paces">
    <!-- Status Tabs -->
    <div class="card-header-paces" style="flex-wrap:wrap; gap:10px; border-bottom:1px solid var(--paces-border); padding-bottom:1rem;">
      <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
        @php
          $curStatus = request('status');
        @endphp
        <a href="{{ route('admin.bookings.index', request()->except(['status', 'page'])) }}" class="btn-paces {{ !$curStatus ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          All Orders ({{ $statusCounts['all'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}" class="btn-paces {{ ($curStatus === 'pending' || $curStatus === 'pending_review') ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Pending ({{ $statusCounts['pending'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'accepted'])) }}" class="btn-paces {{ $curStatus === 'accepted' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Accepted ({{ $statusCounts['accepted'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'assigned'])) }}" class="btn-paces {{ $curStatus === 'assigned' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Assigned ({{ $statusCounts['assigned'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'in_progress'])) }}" class="btn-paces {{ $curStatus === 'in_progress' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          In Progress ({{ $statusCounts['in_progress'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'completed'])) }}" class="btn-paces {{ $curStatus === 'completed' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Completed ({{ $statusCounts['completed'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'cancelled'])) }}" class="btn-paces {{ $curStatus === 'cancelled' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Cancelled ({{ $statusCounts['cancelled'] ?? 0 }})
        </a>
      </div>
    </div>

    <!-- Comprehensive Filter Toolbar (Division, District, Area, Urgency, Range, Search) -->
    <div style="padding:1rem 1.5rem; background:#f8fafc; border-bottom:1px solid var(--paces-border);">
      <form action="{{ route('admin.bookings.index') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
        @if(request('status'))
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <!-- Division Filter -->
        <div style="display:flex; align-items:center; gap:6px;">
          <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Division:</label>
          <select name="division" onchange="this.form.submit()" style="padding:6px 10px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500;">
            <option value="all">All Divisions</option>
            @foreach(['Dhaka', 'Chattogram', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh'] as $divName)
              <option value="{{ $divName }}" {{ request('division') === $divName ? 'selected' : '' }}>
                {{ $divName }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- District Filter -->
        <div style="display:flex; align-items:center; gap:6px;">
          <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">District:</label>
          <select name="district" onchange="this.form.submit()" style="padding:6px 10px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500; max-width:140px;">
            <option value="all">All Districts</option>
            @foreach($availableDistricts as $dist)
              <option value="{{ $dist }}" {{ request('district') === $dist ? 'selected' : '' }}>
                {{ $dist }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Area Filter -->
        <div style="display:flex; align-items:center; gap:6px;">
          <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Area:</label>
          <select name="area" onchange="this.form.submit()" style="padding:6px 10px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500; max-width:160px;">
            <option value="all">All Areas</option>
            @foreach($availableAreas as $aName)
              <option value="{{ $aName }}" {{ request('area') === $aName ? 'selected' : '' }}>
                {{ $aName }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Urgency Filter -->
        <div style="display:flex; align-items:center; gap:6px;">
          <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Urgency:</label>
          <select name="urgency" onchange="this.form.submit()" style="padding:6px 10px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500;">
            <option value="all">All</option>
            <option value="standard" {{ request('urgency') === 'standard' ? 'selected' : '' }}>Standard</option>
            <option value="emergency_1h" {{ request('urgency') === 'emergency_1h' ? 'selected' : '' }}>Emergency (1h)</option>
            <option value="rapid_4h" {{ request('urgency') === 'rapid_4h' ? 'selected' : '' }}>Rapid (4h)</option>
          </select>
        </div>

        <!-- Period Filter -->
        <div style="display:flex; align-items:center; gap:6px;">
          <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Period:</label>
          <select name="range" onchange="this.form.submit()" style="padding:6px 10px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500;">
            <option value="all" {{ (!request('range') || request('range') === 'all') ? 'selected' : '' }}>All Time</option>
            <option value="month" {{ request('range') === 'month' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="week" {{ request('range') === 'week' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="today" {{ request('range') === 'today' ? 'selected' : '' }}>Today Only</option>
          </select>
        </div>

        <!-- Quick Search Input -->
        <div style="flex:1; min-width:200px; display:flex; gap:6px;">
          <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            class="topbar-search-input" 
            placeholder="Search #ID, client, phone, area..." 
            style="width:100%; border-radius:6px; padding:6px 12px; font-size:0.82rem;"
          >
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="padding:6px 14px;">
            Search
          </button>
        </div>

        @if(request()->hasAny(['division', 'district', 'area', 'urgency', 'range', 'search', 'status']))
          <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#ef4444; border-color:#fca5a5; padding:6px 12px; text-decoration:none;">
            ✕ Reset
          </a>
        @endif
      </form>
    </div>

    <!-- Active Filter Indicators -->
    @if(request('area') && request('area') !== 'all')
      <div style="background:#eef2ff; border:1px solid #c7d2fe; padding:8px 16px; margin:0.75rem 1.5rem 0; border-radius:var(--paces-radius-sm); display:flex; justify-content:space-between; align-items:center;">
        <div style="font-size:0.85rem; color:#3730a3; font-weight:500; display:flex; align-items:center; gap:6px;">
          <span>📍 Filtered for Area: <strong>{{ request('area') }}</strong> ({{ $bookings->total() }} results)</span>
        </div>
        <a href="{{ route('admin.bookings.index', request()->except('area')) }}" style="font-size:0.78rem; font-weight:500; color:#dc2626; text-decoration:none;">
          ✕ Clear Area
        </a>
      </div>
    @endif

    @if($bookings->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">📋</div>
        <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Orders Found</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto; font-weight:400;">No service bookings match the selected status, region, area, or search criteria.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Service Scope</th>
              <th>Schedule</th>
              <th>Location</th>
              <th>Status</th>
              <th style="text-align:right; white-space:nowrap;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $b)
              <tr>
                <td>
                  <strong style="color:var(--paces-primary); font-family:monospace; font-size:0.95rem; font-weight:500;">
                    {{ $b->booking_number }}
                  </strong>
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); margin-top:2px; font-weight:400;">
                    {{ $b->created_at->format('M d, Y • h:i A') }}
                  </div>
                </td>
                <td>
                  <div style="display:flex; align-items:center; gap:10px;">
                    @if($b->user?->avatar_url)
                      <img src="{{ $b->user->avatar_url }}" alt="{{ $b->customer_name }}" style="width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                    @else
                      <div style="width:34px; height:34px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:600; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;">
                        {{ strtoupper(substr($b->customer_name ?: ($b->user?->name ?? 'C'), 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <div style="font-weight:500; color:var(--paces-text-dark);">
                        {{ $b->customer_name ?: ($b->user?->name ?? 'Customer') }}
                      </div>
                      <div style="font-size:0.78rem; color:var(--paces-text-muted); font-weight:400;">
                        {{ $b->customer_phone }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">
                    {{ $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service') }}
                  </div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize; font-weight:400;">
                    {{ $b->property_type }}
                  </div>
                </td>
                <td>
                  <div style="font-weight:400;">{{ \Carbon\Carbon::parse($b->preferred_date)->format('M d, Y') }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize; font-weight:400;">
                    Slot: {{ $b->preferred_time_slot }}
                  </div>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->area }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">{{ $b->district }}</div>
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
                  <span class="badge-pill-paces {{ $statusClass }}" style="font-weight:500; font-size:0.78rem;">
                    {{ $b->status_label }}
                  </span>
                </td>
                <td style="text-align:right; white-space:nowrap;">
                  <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="white-space:nowrap; display:inline-flex; align-items:center; gap:4px; font-weight:500; text-decoration:none;">
                    View Details →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Styled Paces Pagination -->
      <div style="padding:0 1.5rem 1rem 1.5rem;">
        {{ $bookings->links('vendor.pagination.paces') }}
      </div>
    @endif
  </div>
</x-layouts.admin>
