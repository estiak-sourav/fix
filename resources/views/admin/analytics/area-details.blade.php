<x-layouts.admin title="Area & Hotspots | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.dashboard') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Dashboard
        </a>
      </div>
      <h1 class="page-header-title">Operations Hotspots & Area Directory</h1>
    </div>
    <div style="display:flex; gap:8px;">
      <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
        All Work Orders ({{ $kpis['total_bookings'] }}) →
      </a>
    </div>
  </div>

  <!-- 4 KPI High-Level Highlights -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:1.5rem;">
    <!-- Active Areas -->
    <div class="card-paces" style="margin-bottom:0; padding:18px;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Covered Areas</span>
        <span style="font-size:1.25rem;">📍</span>
      </div>
      <div style="font-size:1.75rem; font-weight:500; color:var(--paces-text-dark); margin:8px 0 4px;">{{ $kpis['total_areas'] }}</div>
      <div style="font-size:0.75rem; color:var(--paces-text-muted);">Active regional clusters</div>
    </div>

    <!-- Top Hotspot -->
    <div class="card-paces" style="margin-bottom:0; padding:18px;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Top Hotspot</span>
        <span style="font-size:1.25rem;">🔥</span>
      </div>
      <div style="font-size:1.3rem; font-weight:500; color:var(--paces-primary); margin:8px 0 4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
        {{ $kpis['top_hotspot'] }}
      </div>
      <div style="font-size:0.75rem; color:var(--paces-text-muted);">
        {{ $kpis['top_hotspot_count'] }} recorded work orders
      </div>
    </div>

    <!-- Total Work Orders -->
    <div class="card-paces" style="margin-bottom:0; padding:18px;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Scope Bookings</span>
        <span style="font-size:1.25rem;">📋</span>
      </div>
      <div style="font-size:1.75rem; font-weight:500; color:var(--paces-text-dark); margin:8px 0 4px;">{{ $kpis['total_bookings'] }}</div>
      <div style="font-size:0.75rem; color:var(--paces-text-muted);">Facility service requests</div>
    </div>

    <!-- Rapid / Emergency -->
    <div class="card-paces" style="margin-bottom:0; padding:18px;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Emergency Demands</span>
        <span style="font-size:1.25rem;">⚡</span>
      </div>
      <div style="font-size:1.75rem; font-weight:500; color:#dc2626; margin:8px 0 4px;">{{ $kpis['emergency_total'] }}</div>
      <div style="font-size:0.75rem; color:var(--paces-text-muted);">Rapid 1h–4h emergency jobs</div>
    </div>
  </div>

  <!-- Search & Division Filter Controls -->
  <div class="card-paces" style="margin-bottom:2rem; padding:20px;">
    <form action="{{ route('admin.area-analytics') }}" method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
      <!-- Search Input -->
      <div style="position:relative; flex:1; min-width:240px;">
        <input type="text" name="search" value="{{ $search }}" class="topbar-search-input" placeholder="Search area, thana, district, or division..." style="width:100%; border-radius:8px; padding-left:14px;">
      </div>

      <!-- Division Select -->
      <div style="display:flex; gap:8px; align-items:center;">
        <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Division:</label>
        <select name="division" onchange="this.form.submit()" style="padding:7px 12px; font-size:0.82rem; border-radius:8px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500; cursor:pointer;">
          <option value="all" {{ $selectedDivision === 'all' ? 'selected' : '' }}>All Divisions ({{ array_sum($divisionList) }})</option>
          @foreach(['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'] as $dName)
            <option value="{{ $dName }}" {{ $selectedDivision === $dName ? 'selected' : '' }}>
              {{ $dName }} {{ isset($divisionList[$dName]) ? '('.$divisionList[$dName].')' : '(0)' }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Period Range Select -->
      <div style="display:flex; gap:8px; align-items:center;">
        <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Period:</label>
        <select name="range" onchange="this.form.submit()" style="padding:7px 12px; font-size:0.82rem; border-radius:8px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500; cursor:pointer;">
          <option value="all_time" {{ $range === 'all_time' ? 'selected' : '' }}>All Time</option>
          <option value="month" {{ $range === 'month' ? 'selected' : '' }}>Last 30 Days</option>
          <option value="week" {{ $range === 'week' ? 'selected' : '' }}>Last 7 Days</option>
          <option value="today" {{ $range === 'today' ? 'selected' : '' }}>Today Only</option>
        </select>
      </div>

      <!-- Action Buttons -->
      <div style="display:flex; gap:8px;">
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Filter</button>
        @if($search || $selectedDivision !== 'all' || $range !== 'all_time')
          <a href="{{ route('admin.area-analytics') }}" class="btn-paces btn-paces-outline btn-paces-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <!-- Area Breakdown Table -->
  <div class="card-paces">
    <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center;">
      <h3 class="card-title-paces" style="margin:0;">
        Area Performance Directory ({{ $areasCollection->count() }} Areas)
      </h3>
      <span style="font-size:0.75rem; color:var(--paces-text-muted);">Sorted by total demand volume</span>
    </div>

    @if($areasCollection->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">📍</div>
        <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Area Records Found</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">
          No service bookings found matching the selected area, division, or time period filter.
        </p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>AREA</th>
              <th>DISTRICT</th>
              <th>ORDERS</th>
              <th>URGENCY</th>
              <th>REQUESTED SERVICE</th>
              <th>STATUS</th>
              <th style="white-space:nowrap;">TOTAL VALUE</th>
              <th style="text-align:right; white-space:nowrap;">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @foreach($areasCollection as $item)
              <tr>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark); font-size:0.92rem;">
                    📍 {{ $item['area'] }}
                  </div>
                  @if($item['latest_booking_at'])
                    <div style="font-size:0.75rem; color:var(--paces-text-dark); margin-top:4px; line-height:1.3;">
                      <div style="font-weight:500; color:var(--paces-primary);">{{ \Carbon\Carbon::parse($item['latest_booking_at'])->format('h:i A') }}</div>
                      <div style="color:var(--paces-text-muted); font-size:0.72rem;">{{ \Carbon\Carbon::parse($item['latest_booking_at'])->format('M d, Y') }}</div>
                    </div>
                  @endif
                </td>
                <td>
                  <div style="font-weight:500; font-size:0.85rem; color:var(--paces-text-dark);">{{ $item['district'] }}</div>
                </td>
                <td>
                  <div style="display:flex; align-items:center; gap:8px;">
                    <strong style="font-size:1.05rem; color:var(--paces-primary); font-weight:500;">{{ $item['total_count'] }}</strong>
                    <span style="font-size:0.75rem; color:var(--paces-text-muted);">orders</span>
                  </div>
                </td>
                <td>
                  <div style="display:flex; flex-direction:column; gap:4px; font-size:0.78rem;">
                    <div>Standard: <strong style="font-weight:500;">{{ $item['total_count'] - $item['emergency_count'] }}</strong></div>
                    @if($item['emergency_count'] > 0)
                      <div style="color:#dc2626; font-weight:500; white-space:nowrap;">Emergency: {{ $item['emergency_count'] }}</div>
                    @endif
                  </div>
                </td>
                <td>
                  <div style="font-size:0.85rem; font-weight:500; color:var(--paces-text-dark); max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $item['top_service'] }}
                  </div>
                </td>
                <td>
                  <div style="display:flex; gap:4px; flex-wrap:wrap;">
                    @if($item['pending_count'] > 0)
                      <span class="badge-pill-paces bg-warning-subtle" style="font-size:0.7rem; font-weight:500;">
                        {{ $item['pending_count'] }} Pending
                      </span>
                    @endif
                    @if($item['active_count'] > 0)
                      <span class="badge-pill-paces bg-info-subtle" style="font-size:0.7rem; font-weight:500;">
                        {{ $item['active_count'] }} Active
                      </span>
                    @endif
                    @if($item['completed_count'] > 0)
                      <span class="badge-pill-paces bg-success-subtle" style="font-size:0.7rem; font-weight:500;">
                        {{ $item['completed_count'] }} Done
                      </span>
                    @endif
                  </div>
                </td>
                <td style="white-space:nowrap;">
                  <strong style="font-size:0.9rem; color:var(--paces-text-dark);">
                    ৳{{ number_format($item['total_revenue']) }}
                  </strong>
                </td>
                <td style="text-align:right; white-space:nowrap;">
                  <a href="{{ route('admin.bookings.index', ['area' => $item['area']]) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="white-space:nowrap; display:inline-flex; align-items:center; gap:4px;">
                    View Orders →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</x-layouts.admin>
