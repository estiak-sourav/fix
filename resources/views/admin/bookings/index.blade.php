<x-layouts.admin title="Booking Management | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <div>
      <h1 class="page-header-title">Booking</h1>
    </div>
  </div>

  <div class="card-paces">
    <!-- Order Type & AMC Segments Bar -->
    @php
      $curType = request('service_type', request('type'));
      $curAmcStatus = request('amc_status');
      $curStatus = request('status');
      $isAllType = empty($curType) && empty($curAmcStatus);
      $isRegular = $curType === 'service' || $curType === 'regular';
      $isAmc = ($curType === 'amc' || $curType === 'amc_contract') && empty($curAmcStatus);
      $isRunningAmc = $curAmcStatus === 'running' || $curAmcStatus === 'active';
    @endphp
    <div style="padding:0.85rem 1.25rem; background:#ffffff; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
      <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
        <span style="font-size:0.8rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em; margin-right:4px;">Workflow Scope:</span>
        <a href="{{ route('admin.bookings.index', request()->except(['service_type', 'type', 'amc_status', 'page'])) }}" class="btn-paces {{ $isAllType ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:600; padding:5px 12px; font-size:0.8rem;">
          All Orders ({{ $typeCounts['all'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page', 'amc_status']), ['service_type' => 'service'])) }}" class="btn-paces {{ $isRegular ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:600; padding:5px 12px; font-size:0.8rem;">
          🛠️ Regular Services ({{ $typeCounts['regular'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page', 'amc_status']), ['service_type' => 'amc_contract'])) }}" class="btn-paces {{ $isAmc ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:600; padding:5px 12px; font-size:0.8rem;">
          🏢 AMC Contracts ({{ $typeCounts['amc'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page', 'service_type', 'type']), ['amc_status' => 'running'])) }}" class="btn-paces {{ $isRunningAmc ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:600; padding:5px 12px; font-size:0.8rem; border-color:{{ $isRunningAmc ? 'var(--paces-primary)' : '#10b981' }}; color:{{ $isRunningAmc ? '#ffffff' : '#059669' }}; background:{{ $isRunningAmc ? 'var(--paces-primary)' : '#ecfdf5' }};">
          <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981; margin-right:4px;"></span>
          Active Contracts (Running: {{ $typeCounts['amc_running'] ?? 0 }})
        </a>
      </div>
    </div>

    <!-- Status Tabs -->
    <div class="card-header-paces" style="flex-wrap:wrap; gap:10px; border-bottom:1px solid var(--paces-border); padding:0.75rem 1.25rem;">
      <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
        <span style="font-size:0.78rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; margin-right:2px;">Status:</span>
        <a href="{{ route('admin.bookings.index', request()->except(['status', 'page'])) }}" class="btn-paces {{ !$curStatus ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
          All ({{ $statusCounts['all'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}" class="btn-paces {{ ($curStatus === 'pending' || $curStatus === 'pending_review') ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
          Pending ({{ $statusCounts['pending'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'accepted'])) }}" class="btn-paces {{ $curStatus === 'accepted' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
          Accepted ({{ $statusCounts['accepted'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'assigned'])) }}" class="btn-paces {{ $curStatus === 'assigned' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
          Assigned ({{ $statusCounts['assigned'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'in_progress'])) }}" class="btn-paces {{ $curStatus === 'in_progress' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
          In Progress ({{ $statusCounts['in_progress'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'completed'])) }}" class="btn-paces {{ $curStatus === 'completed' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
          Completed ({{ $statusCounts['completed'] ?? 0 }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except(['page']), ['status' => 'cancelled'])) }}" class="btn-paces {{ $curStatus === 'cancelled' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500; font-size:0.78rem; padding:4px 10px;">
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
              <th style="white-space:nowrap;">Order ID</th>
              <th style="white-space:nowrap;">Customer</th>
              <th style="white-space:nowrap;">Service Scope</th>
              <th style="white-space:nowrap;">Schedule</th>
              <th style="white-space:nowrap;">Location</th>
              <th style="white-space:nowrap;">Status</th>
              <th style="text-align:right; white-space:nowrap;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $b)
              <tr>
                <td style="white-space:nowrap;">
                  <strong style="color:var(--paces-primary); font-family:monospace; font-size:0.95rem; font-weight:500; white-space:nowrap;">
                    {{ $b->booking_number }}
                  </strong>
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); margin-top:2px; font-weight:400; white-space:nowrap;">
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
                  @if($b->is_amc)
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:3px; flex-wrap:wrap;">
                      <span style="font-size:0.68rem; font-weight:700; text-transform:uppercase; background:#f5f3ff; color:#7c3aed; border:1px solid #ef0b0bff; padding:1px 6px; border-radius:4px;">
                        🏢 AMC Contract
                      </span>
                      <span style="font-size:0.72rem; color:#475569; font-weight:600;">
                        {{ $b->billing_cycle_label }} • ৳ {{ number_format($b->recurring_amount ?: $b->final_cost, 0) }}
                      </span>
                    </div>
                    <div style="font-weight:600; color:var(--paces-text-dark);">
                      {{ $b->amcPlan?->name ?? 'Annual Maintenance Plan' }}
                    </div>
                  @else
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:3px;">
                      <span style="font-size:0.68rem; font-weight:600; text-transform:uppercase; background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; padding:1px 5px; border-radius:4px;">
                        🛠️ One-Off Service
                      </span>
                    </div>
                    <div style="font-weight:500; color:var(--paces-text-dark);">
                      {{ $b->service?->title ?? 'General Facility Service' }}
                    </div>
                  @endif
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize; font-weight:400; margin-top:2px;">
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
                  @if($b->is_running_contract)
                    <span class="badge-pill-paces" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-weight:600; font-size:0.75rem; display:inline-flex; align-items:center; gap:5px;">
                      <span style="width:6px; height:6px; border-radius:50%; background:#10b981; display:inline-block;"></span>
                      Running Contract
                    </span>
                  @else
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
                  @endif
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
