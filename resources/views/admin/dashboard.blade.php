<x-layouts.admin title="Dashboard | Fix">
  <style>
    .dashboard-sticky-bar {
      position: sticky;
      top: 70px;
      z-index: 950;
      background: var(--paces-bg, #f3f4f7);
      margin-top: -1.75rem;
      margin-left: -2rem;
      margin-right: -2rem;
      padding: 1rem 2rem;
      margin-bottom: 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      border-bottom: 1px solid rgba(226, 232, 240, 0.9);
      box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.03);
    }
    @media (max-width: 768px) {
      .dashboard-sticky-bar {
        margin-left: -1rem;
        margin-right: -1rem;
        padding: 0.75rem 1rem;
      }
    }
    .earnings-analytics-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .earning-card-body {
      display: grid;
      grid-template-columns: 230px 1fr;
      gap: 1.5rem;
      align-items: center;
    }
    @media (max-width: 992px) {
      .earnings-analytics-grid {
        grid-template-columns: 1fr !important;
      }
      .earning-card-body {
        grid-template-columns: 1fr !important;
      }
    }
    .top-tasks-chat-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }
    @media (max-width: 1200px) {
      .top-tasks-chat-grid {
        grid-template-columns: 1fr 1fr !important;
      }
    }
    @media (max-width: 768px) {
      .top-tasks-chat-grid {
        grid-template-columns: 1fr !important;
      }
    }
    .analytics-barchart-grid {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 24px;
      align-items: stretch;
    }
    .analytics-counters-panel {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      align-content: start;
      background: #fafbfc;
      border: 1px solid #edf2f7;
      border-radius: 12px;
      padding: 12px;
    }
    .analytics-counter-item {
      background: #ffffff;
      border: 1px solid #edf2f7;
      padding: 8px 10px;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .analytics-counter-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    @media (max-width: 1199px) {
      .analytics-barchart-grid {
        grid-template-columns: 1fr !important;
        gap: 20px;
      }
      .analytics-counters-panel {
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important;
      }
    }
    .top-kpi-card {
      background: var(--paces-card-bg, #ffffff);
      border: 1px solid var(--paces-border, #e2e8f0);
      border-radius: var(--paces-radius, 12px);
      box-shadow: var(--paces-shadow, 0 1px 3px rgba(0,0,0,0.04));
      padding: 0.85rem 1.15rem;
      display: flex;
      align-items: center;
      gap: 14px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .top-kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--paces-shadow-md, 0 4px 12px rgba(0,0,0,0.06));
    }
    .top-kpi-card .metric-icon-badge {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .top-kpi-card .metric-icon-badge svg {
      width: 22px;
      height: 22px;
      stroke-width: 2;
    }
    .top-kpi-info {
      min-width: 0;
    }
    .top-kpi-label {
      font-size: 0.72rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--paces-text-muted, #64748b);
      margin-bottom: 2px;
    }
    .top-kpi-value {
      font-size: 1.65rem;
      font-weight: 500;
      color: var(--paces-text-dark, #1e293b);
      font-family: 'Poppins', sans-serif;
      line-height: 1.1;
      margin: 0;
    }
  </style>

  <!-- Sticky Dashboard Header: Dashboard Title on Left, Global Date Range Filter on Right (Remains pinned on scroll) -->
  <div class="dashboard-sticky-bar">
    <div style="display:flex; align-items:center; gap:12px;">
      <h1 class="page-header-title" style="margin:0; font-size:1.35rem; font-weight:500; color:#1e293b;">Dashboard</h1>
      <span style="background:#3e60d5; color:#ffffff; font-size:0.78rem; font-weight:500; padding:5px 14px; border-radius:20px; white-space:nowrap; box-shadow:0 2px 6px rgba(62,96,213,0.25);">
        {{ $stats['range_label'] }}
      </span>
    </div>

    <!-- Date Range Action Pills (Global Dashboard Filter: Yesterday, Today, Week, Month, Year & Date to Date) -->
    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
      <div style="display:flex; align-items:center; background:#ffffff; border:1px solid #eaedf1; border-radius:8px; padding:3px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
        <!-- Yesterday -->
        <a href="{{ route('admin.dashboard', ['range' => 'yesterday']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:500; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'yesterday' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Yesterday
        </a>

        <!-- Today -->
        <a href="{{ route('admin.dashboard', ['range' => 'today']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:500; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'today' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Today
        </a>

        <!-- Week -->
        <a href="{{ route('admin.dashboard', ['range' => 'week']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:500; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'week' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Week
        </a>

        <!-- Month -->
        <a href="{{ route('admin.dashboard', ['range' => 'month']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:500; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'month' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Month
        </a>

        <!-- Year -->
        <a href="{{ route('admin.dashboard', ['range' => 'year']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:500; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'year' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Year
        </a>

        <!-- Date to Date (Clean popup trigger, no icon) -->
        <button type="button" onclick="openDateModal()" 
          style="border:none; cursor:pointer; font-size:0.825rem; font-weight:500; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ in_array($stats['range'], ['custom', 'date']) ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'background:transparent; color:#64748b;' }}">
          Date to Date
        </button>
      </div>
    </div>
  </div>

  <!-- Top Operational KPI Stat Cards: Orders, Subscriptions, Emergency, Processing, Pending -->
  <div class="metrics-grid-paces" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <!-- 1. Orders -->
    <div class="top-kpi-card">
      <div class="metric-icon-badge bg-primary-subtle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
      </div>
      <div class="top-kpi-info">
        <div class="top-kpi-label">Orders</div>
        <div class="top-kpi-value">{{ number_format($stats['service_requests']) }}</div>
      </div>
    </div>

    <!-- 2. Subscriptions -->
    <div class="top-kpi-card">
      <div class="metric-icon-badge bg-info-subtle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
      </div>
      <div class="top-kpi-info">
        <div class="top-kpi-label">Subscriptions</div>
        <div class="top-kpi-value" style="color:var(--paces-info, #3e60d5);">{{ number_format($stats['amc_requests']) }}</div>
      </div>
    </div>

    <!-- 3. Urgent -->
    <div class="top-kpi-card">
      <div class="metric-icon-badge bg-danger-subtle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
      </div>
      <div class="top-kpi-info">
        <div class="top-kpi-label">Urgent</div>
        <div class="top-kpi-value" style="color:var(--paces-danger, #ef4444);">{{ number_format($stats['emergency_orders']) }}</div>
      </div>
    </div>

    <!-- 4. Processing -->
    <div class="top-kpi-card">
      <div class="metric-icon-badge bg-warning-subtle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
      </div>
      <div class="top-kpi-info">
        <div class="top-kpi-label">Processing</div>
        <div class="top-kpi-value" style="color:var(--paces-warning, #f59e0b);">{{ number_format($stats['active_in_progress']) }}</div>
      </div>
    </div>

    <!-- 5. Pending -->
    <div class="top-kpi-card">
      <div class="metric-icon-badge" style="background:rgba(239, 68, 68, 0.1); color:#dc2626;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
      </div>
      <div class="top-kpi-info">
        <div class="top-kpi-label">Pending</div>
        <div class="top-kpi-value" style="color:#e11d48;">{{ number_format($stats['pending_review']) }}</div>
      </div>
    </div>
  </div>

  <!-- =========================================================================
       CLEAN BARCHART SECTION (SCREENSHOT DESIGN PATTERN - 10 DEDICATED METRIC BARS)
       ========================================================================= -->
  <div class="card-paces" style="padding: 24px 28px; margin-bottom: 1.75rem; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
    <!-- Header with Title -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 22px;">
      <h2 style="font-size: 1.25rem; font-weight: 500; color: #1e293b; margin: 0; font-family: 'Poppins', sans-serif;">
        Operations & Traffic Analytics
      </h2>
    </div>

  <!-- Main Analytics Body: BarChart on Left (Compact Width) and 10 Counters on Right -->
  <div class="analytics-barchart-grid">
    <!-- Left: Clean BarChart Canvas (Width reduced so bars are closer together) -->
    <div style="position:relative; min-height: 380px; width: 100%;">
      <canvas id="unifiedCleanBarChart"></canvas>
    </div>

    <!-- Right: 10 Metric Counter Cards (Organized in 2 columns of 5) -->
    <div class="analytics-counters-panel">
        <!-- 1. Total visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #4e73df;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Total visitor</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['total_visitors']) }}</div>
        </div>

        <!-- 2. New visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #60a5fa;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">New visitor</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['new_visitors']) }}</div>
        </div>

        <!-- 3. Login -->
        <div class="analytics-counter-item" style="border-top:3px solid #8b5cf6;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Login</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['logins']) }}</div>
        </div>

        <!-- 4. Registration -->
        <div class="analytics-counter-item" style="border-top:3px solid #a855f7;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Registration</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['registrations']) }}</div>
        </div>

        <!-- 5. Service visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #f6b749;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Service visitor</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['service_visitors']) }}</div>
        </div>

        <!-- 6. AMC visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #f59e0b;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">AMC visitor</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['amc_visitors']) }}</div>
        </div>

        <!-- 7. Service Request (Under Service visitor) -->
        <div class="analytics-counter-item" style="border-top:3px solid #06b6d4;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Service Request</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['service_requests']) }}</div>
        </div>

        <!-- 8. AMC Request (Under AMC visitor) -->
        <div class="analytics-counter-item" style="border-top:3px solid #ec4899;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">AMC Request</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['amc_requests']) }}</div>
        </div>

        <!-- 9. Service Book (Under Service Request) -->
        <div class="analytics-counter-item" style="border-top:3px solid #20c997;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Service Book</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['service_book']) }}</div>
        </div>

        <!-- 10. AMC Book (Under AMC Request) -->
        <div class="analytics-counter-item" style="border-top:3px solid #10b981;">
          <div style="font-size:0.68rem; font-weight:500; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">AMC Book</div>
          <div style="font-size:1.1rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['amc_book']) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Simple Calendar Date Selection Popup Modal -->
  <div id="dateToDateModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:400px; padding:22px 24px; position:relative;" onclick="event.stopPropagation()">
      <!-- Modal Header -->
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="margin:0; font-size:1.05rem; font-weight:500; color:#1e293b; font-family:'Poppins', sans-serif;">Select Date Range</h3>
        <button type="button" onclick="closeDateModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <!-- Modal Form -->
      <form action="{{ route('admin.dashboard') }}" method="GET" style="margin:0;">
        <input type="hidden" name="range" value="custom">

        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">From</label>
            <input type="date" name="start_date" id="modalStartDate" value="{{ $stats['start_date'] }}" class="form-control-paces" style="width:100%; height:38px; font-size:0.875rem; padding:6px 10px; border-radius:8px;" required>
          </div>
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">To</label>
            <input type="date" name="end_date" id="modalEndDate" value="{{ $stats['end_date'] }}" class="form-control-paces" style="width:100%; height:38px; font-size:0.875rem; padding:6px 10px; border-radius:8px;" required>
          </div>
        </div>

        <!-- Quick preset shortcuts -->
        <div style="display:flex; gap:6px; margin-bottom:18px; flex-wrap:wrap;">
          <button type="button" onclick="setModalQuickDates('today')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:500; color:#475569; cursor:pointer;">Today</button>
          <button type="button" onclick="setModalQuickDates('yesterday')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:500; color:#475569; cursor:pointer;">Yesterday</button>
          <button type="button" onclick="setModalQuickDates('last7')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:500; color:#475569; cursor:pointer;">Last 7 Days</button>
          <button type="button" onclick="setModalQuickDates('last30')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:500; color:#475569; cursor:pointer;">Last 30 Days</button>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeDateModal()" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 18px;">Apply Date</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Operational Insights: Category Demand & Dhaka Hotspots -->
  <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
    <!-- Service Category Demand -->
    <div class="card-paces" style="margin-bottom:0;">
      <div class="card-header-paces">
        <h3 class="card-title-paces" style="margin:0;">Demanding Services & Facilities</h3>
      </div>

      @if($categoryBreakdown->isEmpty())
        <div style="text-align:center; padding:2rem; color:var(--paces-text-muted);">
          No categorized bookings in this date period.
        </div>
      @else
        <div style="display:flex; flex-direction:column; gap:12px;">
          @php
            $maxCat = max($categoryBreakdown->max(), 1);
            $colors = ['#3e60d5', '#7c3aed', '#16a34a', '#d97706', '#06b6d4', '#dc2626'];
          @endphp
          @foreach($categoryBreakdown as $categoryName => $count)
            @php
              $pct = round(($count / $maxCat) * 100);
              $color = $colors[$loop->index % count($colors)];
            @endphp
            <div>
              <div style="display:flex; justify-content:space-between; font-size:0.825rem; font-weight:500; margin-bottom:4px;">
                <span>{{ $categoryName }}</span>
                <span><strong>{{ $count }}</strong> orders</span>
              </div>
              <div style="width:100%; height:8px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                <div style="width:{{ $pct }}%; height:100%; background:{{ $color }}; border-radius:10px; transition:width 0.4s ease;"></div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Countrywide Regional Operations Hotspots -->
    <div class="card-paces" style="margin-bottom:0;">
      <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
        <div style="display:flex; align-items:center; gap:8px;">
          <h3 class="card-title-paces" style="margin:0;">Operations Hotspots</h3>
          @if(($selectedHotspotDivision ?? 'all') !== 'all')
            <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem; font-weight:500;">
              {{ $selectedHotspotDivision }}
            </span>
          @endif
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
          <form method="GET" action="{{ route('admin.dashboard') }}" id="hotspotDivisionFilterForm" style="margin:0; display:inline-block;">
            @foreach(request()->except(['hotspot_division', 'page']) as $k => $v)
              @if(is_string($v) || is_numeric($v))
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
              @endif
            @endforeach
            <select name="hotspot_division" onchange="this.form.submit()" style="padding:4px 8px; font-size:0.8rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; color:var(--paces-text-dark); cursor:pointer; font-weight:500;">
              <option value="all" {{ ($selectedHotspotDivision ?? 'all') === 'all' ? 'selected' : '' }}>All Divisions</option>
              @foreach(['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'] as $divName)
                <option value="{{ $divName }}" {{ ($selectedHotspotDivision ?? 'all') === $divName ? 'selected' : '' }}>
                  {{ $divName }} {{ isset($divisionCounts[$divName]) ? '('.$divisionCounts[$divName].')' : '' }}
                </option>
              @endforeach
            </select>
          </form>
          <a href="{{ route('admin.area-analytics') }}" class="btn-paces btn-paces-primary btn-paces-sm" style="text-decoration:none;">
            {{ $areaHotspots->count() > 8 ? 'More Areas (' . $areaHotspots->count() . ') →' : 'Area Details →' }}
          </a>
        </div>
      </div>

      @if($areaHotspots->isEmpty())
        <div style="text-align:center; padding:2rem; color:var(--paces-text-muted);">
          No hotspot records for {{ ($selectedHotspotDivision ?? 'all') === 'all' ? 'the selected date period' : $selectedHotspotDivision }}.
        </div>
      @else
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
          @foreach($areaHotspots->take(8) as $hotspot)
            @php
              $areaName = is_array($hotspot) ? ($hotspot['area'] ?? $hotspot['name'] ?? '') : (is_object($hotspot) ? ($hotspot->area ?? $hotspot->name ?? '') : (string)$hotspot);
              $count = is_array($hotspot) ? ($hotspot['count'] ?? 0) : (is_numeric($hotspot) ? $hotspot : 1);
              $districtName = is_array($hotspot) ? ($hotspot['district'] ?? '') : (is_object($hotspot) ? ($hotspot->district ?? '') : '');
              $divisionName = is_array($hotspot) ? ($hotspot['division'] ?? '') : (is_object($hotspot) ? ($hotspot->division ?? '') : '');
              $metaSub = $districtName ? ($districtName . ($divisionName ? ', ' . $divisionName : '')) : ($divisionName ?: 'Active Field Dispatch');
            @endphp
            <a href="{{ route('admin.bookings.index', ['area' => $areaName]) }}" style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center; text-decoration:none; color:inherit; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.borderColor='var(--paces-primary)'; this.style.background='#eff6ff';" onmouseout="this.style.borderColor='var(--paces-border)'; this.style.background='#f8fafc';" title="View bookings in {{ $areaName }}">
              <div>
                <div style="font-size:0.85rem; font-weight:500; color:var(--paces-text-dark);">📍 {{ $areaName }}</div>
                <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $metaSub }}</div>
              </div>
              <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.8rem; font-weight:500;">
                {{ $count }}
              </span>
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <!-- 📈 Earning & Sales Analytics Section (Exact Screenshot Match) -->
  <div class="earnings-analytics-grid">
    
    <!-- Left Card: Earning -->
    <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
      <!-- Header -->
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3 style="font-size:1.1rem; font-weight:500; color:var(--paces-text-dark); margin:0; font-family:'Poppins', sans-serif;">Earning</h3>
        <div style="display:inline-flex; align-items:center; gap:6px;">
          <div style="position:relative;">
            <select id="earningMonthSelector" onchange="switchEarningMonth(this.value)" style="appearance:none; -webkit-appearance:none; background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; padding:5px 28px 5px 12px; font-size:0.82rem; font-weight:500; color:#475569; cursor:pointer; outline:none; font-family:'Poppins', sans-serif;">
              @foreach([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'] as $mNum => $mName)
                <option value="{{ $mNum }}" {{ $currentMonth === $mNum ? 'selected' : '' }}>{{ $mName }}</option>
              @endforeach
            </select>
            <svg style="position:absolute; right:8px; top:50%; transform:translateY(-50%); pointer-events:none;" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </div>
          <button type="button" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; padding:5px 12px; font-size:0.82rem; font-weight:500; color:#475569; cursor:pointer; font-family:'Poppins', sans-serif;">
            Month
          </button>
        </div>
      </div>

      <!-- Card Body -->
      <div class="earning-card-body">
        <!-- Metrics Column -->
        <div>
          <div style="font-size:0.85rem; color:#64748b; font-weight:500; margin-bottom:2px;">This month</div>
          <div id="earningThisMonthVal" style="font-size:1.85rem; font-weight:500; color:#1e293b; letter-spacing:-0.02em; line-height:1.2;">
            {{ $earningMonthlyData[$currentMonth]['thisMonth'] ?? '৳ 0.00' }}
          </div>
          <div style="display:flex; align-items:center; gap:6px; margin:8px 0 16px 0;">
            @php
              $currentGrowth = $earningMonthlyData[$currentMonth]['growth'] ?? '0.0%';
              $isNegativeGrowth = str_starts_with($currentGrowth, '-');
            @endphp
            <span id="earningGrowthBadge" style="background:{{ $isNegativeGrowth ? '#fee2e2' : '#dcfce7' }}; color:{{ $isNegativeGrowth ? '#dc2626' : '#16a34a' }}; font-size:0.75rem; font-weight:500; padding:2px 8px; border-radius:6px;">{{ $currentGrowth }}</span>
            <span style="font-size:0.78rem; color:#64748b; font-weight:500;">From previous period</span>
          </div>

          <a href="{{ route('admin.earnings.index') }}" style="display:inline-flex; align-items:center; gap:8px; background:#4361ee; color:#ffffff; font-size:0.82rem; font-weight:500; padding:7px 16px; border-radius:6px; text-decoration:none; box-shadow:0 4px 10px rgba(67, 97, 238, 0.25); transition:all 0.2s ease;">
            <span>View Details</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </a>

          <div style="margin-top:20px;">
            <div style="font-size:0.85rem; color:#64748b; font-weight:500; margin-bottom:2px;">Last month</div>
            <div id="earningLastMonthVal" style="font-size:1.25rem; font-weight:500; color:#1e293b;">
              {{ $earningMonthlyData[$currentMonth]['lastMonth'] ?? '৳ 0.00' }}
            </div>
          </div>
        </div>

        <!-- Spline Line Chart -->
        <div style="position:relative; width:100%; height:200px;">
          <canvas id="earningSplineChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Right Card: Sales Analytics -->
    <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
      <!-- Header -->
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
        <h3 style="font-size:1.1rem; font-weight:500; color:var(--paces-text-dark); margin:0; font-family:'Poppins', sans-serif;">Sales Analytics</h3>
        <span style="font-size:0.82rem; font-weight:500; color:#64748b; font-family:'Poppins', sans-serif;">Total: {{ $salesAnalytics['total_formatted'] }}</span>
      </div>

      <!-- Donut Chart Canvas with in-slice percentage rendering -->
      <div style="position:relative; width:100%; height:180px; display:flex; align-items:center; justify-content:center;">
        <canvas id="salesAnalyticsDonutChart"></canvas>
      </div>

      <!-- Bottom Legends (dynamic matching category slices) -->
      <div style="display:grid; grid-template-columns:repeat({{ max(1, count($salesAnalytics['categories'])) }}, 1fr); gap:8px; margin-top:1rem; text-align:center; border-top:1px solid #f1f5f9; padding-top:1rem;">
        @forelse($salesAnalytics['categories'] as $cat)
          <div>
            <div style="display:inline-flex; align-items:center; justify-content:center; gap:5px; font-size:0.78rem; font-weight:500; color:#64748b; margin-bottom:3px; max-width:100%;" title="{{ $cat['name'] }}">
              <span style="width:8px; height:8px; border-radius:50%; background:{{ $cat['color'] }}; display:inline-block; flex-shrink:0;"></span>
              <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:110px;">{{ $cat['name'] }}</span>
            </div>
            <div style="font-size:0.95rem; font-weight:500; color:#1e293b;">{{ $cat['amount_formatted'] }}</div>
          </div>
        @empty
          <div style="grid-column:1 / -1; color:#94a3b8; font-size:0.82rem;">No sales data available</div>
        @endforelse
      </div>
    </div>

  </div>

  <!-- 🎯 Top Selling, Tasks & Chat Widget Row (Exact Screenshot Match) -->
  <div class="top-tasks-chat-grid">

    <!-- Card 1: Top Selling Services & AMC Packages -->
    <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <h3 style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin:0; font-family:'Poppins', sans-serif;">Top Selling Services</h3>
          <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem;">
            Dynamic Demand
          </span>
        </div>

        @php
          $top1 = $topSellingDynamic->first();
        @endphp

        <!-- Top Summary Block -->
        <div style="text-align:center; padding-bottom:1.25rem;">
          <div style="font-size:0.85rem; color:#64748b; font-weight:500; margin-bottom:2px;">{{ $top1?->name ?? 'Leading Service' }}</div>
          <div style="font-size:1.85rem; font-weight:500; color:#1e293b; letter-spacing:-0.02em;">{{ $top1?->orders_count ?? 0 }} Orders</div>
          <div style="display:inline-flex; align-items:center; gap:6px; margin-top:6px;">
            <span style="background:#dcfce7; color:#16a34a; font-size:0.75rem; font-weight:500; padding:2px 8px; border-radius:6px; display:inline-flex; align-items:center; gap:3px;">
              {{ $top1?->pct ?? 0 }}% of Total
            </span>
            <span style="font-size:0.78rem; color:#64748b; font-weight:500;">Across client bookings</span>
          </div>
        </div>

        <!-- Product / Service List Rows -->
        <div style="display:flex; flex-direction:column;">
          @forelse($topSellingDynamic as $item)
            <div style="border-top:1px solid #f1f5f9; padding:12px 0; display:flex; justify-content:space-between; align-items:center;">
              <div style="min-width:0; padding-right:10px;">
                <div style="font-size:0.9rem; font-weight:500; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->name }}">
                  {{ $item->name }}
                </div>
                <div style="font-size:0.75rem; color:#94a3b8; margin-top:2px;">{{ $item->subtitle }} ({{ $item->orders_count }} booked)</div>
              </div>
              <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <div style="text-align:right;">
                  <span class="badge-pill-paces" style="background:rgba(67, 97, 238, 0.1); color:{{ $item->color }}; font-weight:500;">
                    {{ $item->pct }} %
                  </span>
                </div>
              </div>
            </div>
          @empty
            <div style="padding:1rem; text-align:center; color:#94a3b8; font-size:0.85rem;">
              No booking records recorded yet.
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Card 2: Outreach Tasks -->
    <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <h3 style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin:0; font-family:'Poppins', sans-serif;">Outreach Tasks</h3>
          <span class="badge-pill-paces {{ $outreachTasks->count() > 0 ? 'bg-warning-subtle' : 'bg-success-subtle' }}">
            {{ $outreachTasks->count() }} Pending Calls
          </span>
        </div>

        <div id="taskListContainer" style="display:flex; flex-direction:column; gap:10px;">
          @forelse($outreachTasks as $task)
            <div class="task-row-item" style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#f8fafc; border:1px solid #edf2f7; border-radius:8px;">
              <div style="min-width:0; padding-right:8px;">
                <div style="font-size:0.88rem; font-weight:500; color:#1e293b;">
                  {{ $task->customer_name }} • <span style="font-family:monospace; color:var(--paces-primary);">#{{ $task->booking_number }}</span>
                </div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                  📞 {{ $task->customer_phone }} — <span style="color:{{ $task->call_status === 'unreachable' ? '#dc2626' : '#d97706' }}; font-weight:500;">
                    {{ $task->call_status === 'unreachable' ? 'Unreachable (Retry)' : 'Call Back Later' }}
                  </span>
                </div>
              </div>
              <a href="{{ route('admin.bookings.show', $task) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="flex-shrink:0; padding:4px 10px; font-size:0.75rem;">
                Call Order →
              </a>
            </div>
          @empty
            <div style="text-align:center; padding:2rem 1rem; color:var(--paces-text-muted);">
              <div style="font-size:2rem; margin-bottom:6px;">🎉</div>
              <div style="font-weight:500; color:var(--paces-text-dark); font-size:0.9rem;">All Outreach Tasks Completed</div>
              <p style="font-size:0.78rem; margin:4px 0 0;">No unreachable or call-back requests pending right now.</p>
            </div>
          @endforelse
        </div>
      </div>
      <div style="text-align:center; padding-top:1rem; border-top:1px solid #f1f5f9; margin-top:1rem;">
        <a href="{{ route('admin.bookings.index', ['status' => 'pending_review']) }}" class="btn-paces btn-paces-outline btn-paces-sm" style="width:100%;">
          View All Pending Bookings →
        </a>
      </div>
    </div>

    <!-- Card 3: Support Helpdesk Widget -->
    <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <!-- Chat Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <div>
            <h4 style="font-size:0.98rem; font-weight:500; color:#1e293b; margin:0;">
              {{ $latestSupportTicket?->customer_name ?? 'Support Helpdesk' }}
            </h4>
            <div style="display:inline-flex; align-items:center; gap:5px; font-size:0.75rem; color:#16a34a; font-weight:500; margin-top:2px;">
              <span style="width:7px; height:7px; border-radius:50%; background:#16a34a; display:inline-block;"></span>
              Ticket #{{ $latestSupportTicket?->ticket_number ?? 'DESK' }}
            </div>
          </div>
          @if($latestSupportTicket)
            <a href="{{ route('admin.tickets.show', $latestSupportTicket) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="font-size:0.75rem; padding:4px 10px;">
              Open Ticket →
            </a>
          @endif
        </div>

        <!-- Chat Stream Area -->
        <div style="height:210px; overflow-y:auto; padding-right:4px; display:flex; flex-direction:column; gap:10px; border-top:1px solid #f1f5f9; padding-top:12px;">
          @if($latestSupportTicket && $latestSupportTicket->messages->isNotEmpty())
            @foreach($latestSupportTicket->messages->take(3) as $msg)
              <div style="position:relative; align-self:{{ $msg->is_admin_reply ? 'flex-end' : 'flex-start' }}; max-width:85%;">
                <div style="background:{{ $msg->is_admin_reply ? '#f1f5f9' : '#eef2ff' }}; border-radius:10px; padding:8px 12px; box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3px; gap:8px;">
                    <span style="font-size:0.75rem; font-weight:500; color:{{ $msg->is_admin_reply ? '#3e60d5' : '#4361ee' }};">
                      {{ $msg->is_admin_reply ? 'Support Staff' : $latestSupportTicket->customer_name }}
                    </span>
                    <span style="font-size:0.65rem; color:#94a3b8;">{{ $msg->created_at->format('h:i A') }}</span>
                  </div>
                  <div style="font-size:0.8rem; color:#1e293b; line-height:1.4;">{{ Str::limit($msg->message, 90) }}</div>
                </div>
              </div>
            @endforeach
          @else
            <div style="padding:1.5rem 1rem; text-align:center; color:#94a3b8; font-size:0.85rem;">
              No active customer support messages.
            </div>
          @endif
        </div>
      </div>

      <!-- Quick Link to Support Tickets -->
      <div style="border-top:1px solid #f1f5f9; padding-top:10px; margin-top:1rem; text-align:center;">
        <a href="{{ route('admin.tickets.index') }}" class="btn-paces btn-paces-primary btn-paces-sm" style="width:100%;">
          View All Support Tickets ({{ \App\Models\SupportTicket::count() }}) →
        </a>
      </div>
    </div>

  </div>

  <!-- Recent Inbound Work Orders (Table Card) -->
  <div class="card-paces">
    <div class="card-header-paces">
      <h3 class="card-title-paces" style="margin:0;">Recent Work Orders</h3>
      <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          View All Work Orders ({{ $stats['all_time_bookings'] }}) →
        </a>
      </div>
    </div>

    @if($recentBookings->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">📋</div>
        <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Service Orders In This Period</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">Try selecting another date range or pick "Month" or "Year" to view recorded work orders.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Service Scope</th>
              <th>Location</th>
              <th>Cost (৳)</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentBookings as $b)
              <tr>
                <td>
                  <strong style="color:var(--paces-primary); font-family:monospace; font-size:0.9rem;">#{{ $b->booking_number }}</strong>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">{{ $b->created_at->format('M d, Y • h:i A') }}</div>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->customer_name }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->customer_phone }}</div>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service') }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize;">{{ $b->property_type }}</div>
                </td>
                <td>
                  <div style="font-weight:500;">{{ $b->area }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->district }}</div>
                </td>
                <td>
                  <strong style="color:var(--paces-text-dark);">৳{{ number_format($b->final_cost ?? $b->estimated_cost ?? 0, 0) }}</strong>
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
                  <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-primary btn-paces-sm">
                    View Order →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- More Orders Button at Bottom (Item 16) -->
      <div style="text-align:center; padding:1.25rem 0 0.5rem; border-top:1px solid var(--paces-border); margin-top:1rem;">
        <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-primary" style="padding:0.65rem 2rem; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
          <span>More Orders →</span>
        </a>
      </div>
    @endif
  </div>

  <!-- Unified Clean BarChart Script (Screenshot Match - 10 Dedicated Metric Bars) -->
  <script>
    const metricLabels = {!! json_encode($metricChart['labels']) !!};
    const metricValues = {!! json_encode($metricChart['values']) !!};
    const metricDetails = {!! json_encode($metricChart['details']) !!};
    const currentRangeLabel = {!! json_encode($stats['range_label']) !!};

    // 10 distinct, cohesive colors matching the screenshot palette
    const barColors = [
      '#4e73df', // Total visitor (Royal Blue)
      '#60a5fa', // New visitor (Sky Blue)
      '#8b5cf6', // Login (Purple)
      '#a855f7', // Registration (Violet)
      '#f6b749', // Service visitor (Warm Amber)
      '#f59e0b', // AMC visitor (Deep Gold)
      '#06b6d4', // Service Request (Cyan)
      '#20c997', // Service Book (Emerald Green)
      '#ec4899', // AMC Request (Rose Pink)
      '#10b981'  // AMC Book (Mint Green)
    ];

    let cleanBarChart = null;

    function initUnifiedBarChart() {
      const canvas = document.getElementById('unifiedCleanBarChart');
      if (!canvas || typeof Chart === 'undefined') return;

      if (cleanBarChart) cleanBarChart.destroy();

      cleanBarChart = new Chart(canvas, {
        type: 'bar',
        data: {
          labels: metricLabels,
          datasets: [{
            label: currentRangeLabel,
            data: metricValues,
            backgroundColor: barColors,
            borderRadius: 4,
            borderSkipped: false,
            barPercentage: 0.55,
            categoryPercentage: 0.85,
            maxBarThickness: 34
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              grid: { display: false },
              ticks: {
                font: { family: 'Poppins', size: 10.5, weight: '500' },
                color: '#475569',
                maxRotation: 30,
                minRotation: 0
              },
              border: { display: false }
            },
            y: {
              beginAtZero: true,
              grid: {
                color: '#f1f5f9',
                drawBorder: false
              },
              ticks: {
                font: { family: 'Poppins', size: 11, weight: '500' },
                color: '#94a3b8',
                precision: 0,
                maxTicksLimit: 7
              },
              border: { display: false }
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: '#1e293b',
              padding: 12,
              cornerRadius: 8,
              titleFont: { family: 'Poppins', size: 12, weight: '500' },
              bodyFont: { family: 'Poppins', size: 12 },
              callbacks: {
                label: function(context) {
                  return ' ' + context.label + ': ' + context.parsed.y.toLocaleString();
                }
              }
            }
          }
        }
      });
    }

    // Modal Handlers for Date to Date
    function openDateModal() {
      const modal = document.getElementById('dateToDateModal');
      if (modal) modal.style.display = 'flex';
    }

    function closeDateModal() {
      const modal = document.getElementById('dateToDateModal');
      if (modal) modal.style.display = 'none';
    }

    function setModalQuickDates(type) {
      const today = new Date();
      const format = d => d.toISOString().split('T')[0];
      const startInput = document.getElementById('modalStartDate');
      const endInput = document.getElementById('modalEndDate');
      if (!startInput || !endInput) return;

      if (type === 'today') {
        startInput.value = format(today);
        endInput.value = format(today);
      } else if (type === 'yesterday') {
        const y = new Date();
        y.setDate(y.getDate() - 1);
        startInput.value = format(y);
        endInput.value = format(y);
      } else if (type === 'last7') {
        const d = new Date();
        d.setDate(d.getDate() - 6);
        startInput.value = format(d);
        endInput.value = format(today);
      } else if (type === 'last30') {
        const d = new Date();
        d.setDate(d.getDate() - 29);
        startInput.value = format(d);
        endInput.value = format(today);
      }
    }

    // Earning Spline Chart & Month Switcher Data (Real Database Revenue)
    const earningMonthlyData = @json($earningMonthlyData);

    let earningSplineChart = null;

    function initEarningSplineChart(monthKey = {{ $currentMonth }}) {
      const canvas = document.getElementById('earningSplineChart');
      if (!canvas || typeof Chart === 'undefined') return;

      const dataObj = earningMonthlyData[monthKey] || earningMonthlyData[{{ $currentMonth }}] || { values: [0,0,0,0,0,0,0,0,0,0,0,0] };
      const ctx = canvas.getContext('2d');

      if (earningSplineChart) earningSplineChart.destroy();

      const gradient = ctx.createLinearGradient(0, 0, 0, 190);
      gradient.addColorStop(0, 'rgba(67, 97, 238, 0.18)');
      gradient.addColorStop(1, 'rgba(67, 97, 238, 0.00)');

      earningSplineChart = new Chart(canvas, {
        type: 'line',
        data: {
          labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
          datasets: [{
            data: dataObj.values,
            borderColor: '#4361ee',
            borderWidth: 3.5,
            fill: true,
            backgroundColor: gradient,
            tension: 0.45,
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: '#4361ee',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 2.5
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e293b',
              titleFont: { family: 'Poppins', size: 12, weight: '500' },
              bodyFont: { family: 'Poppins', size: 12 },
              padding: 10,
              cornerRadius: 8,
              displayColors: false,
              callbacks: {
                title: (items) => 'Period: ' + items[0].label,
                label: (item) => 'Revenue: ৳ ' + Number(item.raw).toLocaleString('en-US')
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              border: { display: false },
              ticks: {
                color: '#94a3b8',
                font: { family: 'Poppins', size: 11, weight: '500' }
              }
            },
            y: {
              beginAtZero: true,
              ticks: {
                callback: (value) => '৳' + (value >= 1000 ? (value / 1000).toFixed(0) + 'k' : value),
                color: '#94a3b8',
                font: { family: 'Poppins', size: 11, weight: '500' }
              },
              grid: {
                color: '#f1f5f9'
              },
              border: { display: false }
            }
          }
        }
      });
    }

    function switchEarningMonth(val) {
      const data = earningMonthlyData[val] || earningMonthlyData[{{ $currentMonth }}] || { thisMonth: "৳ 0.00", lastMonth: "৳ 0.00", growth: "0.0%", values: [0,0,0,0,0,0,0,0,0,0,0,0] };
      const thisEl = document.getElementById('earningThisMonthVal');
      const lastEl = document.getElementById('earningLastMonthVal');
      const badgeEl = document.getElementById('earningGrowthBadge');

      if (thisEl) thisEl.innerText = data.thisMonth;
      if (lastEl) lastEl.innerText = data.lastMonth;
      if (badgeEl) {
        badgeEl.innerText = data.growth;
        if (data.growth.startsWith('-')) {
          badgeEl.style.background = '#fee2e2';
          badgeEl.style.color = '#dc2626';
        } else {
          badgeEl.style.background = '#dcfce7';
          badgeEl.style.color = '#16a34a';
        }
      }

      initEarningSplineChart(Number(val));
    }

    // Sales Analytics Donut Chart
    let salesAnalyticsDonut = null;

    const donutPercentagePlugin = {
      id: 'donutPercentagePlugin',
      afterDatasetsDraw(chart) {
        const { ctx } = chart;
        const meta = chart.getDatasetMeta(0);
        if (!meta || !meta.data) return;

        meta.data.forEach((element, i) => {
          const { startAngle, endAngle, innerRadius, outerRadius } = element;
          const angle = (startAngle + endAngle) / 2;
          const r = (innerRadius + outerRadius) / 2;
          const x = element.x + Math.cos(angle) * r;
          const y = element.y + Math.sin(angle) * r;

          const val = chart.data.datasets[0].data[i];
          if (!val || val <= 0) return;

          ctx.save();
          ctx.font = '500 11.5px Poppins, sans-serif';
          ctx.fillStyle = '#ffffff';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          ctx.fillText(val + '%', x, y);
          ctx.restore();
        });
      }
    };

    function initSalesAnalyticsDonut() {
      const canvas = document.getElementById('salesAnalyticsDonutChart');
      if (!canvas || typeof Chart === 'undefined') return;

      if (salesAnalyticsDonut) salesAnalyticsDonut.destroy();

      const labels = @json($salesAnalytics['labels'] ?? []);
      const dataValues = @json($salesAnalytics['data'] ?? []);
      const bgColors = @json($salesAnalytics['colors'] ?? []);

      salesAnalyticsDonut = new Chart(canvas, {
        type: 'doughnut',
        data: {
          labels: labels.length ? labels : ['No Sales'],
          datasets: [{
            data: dataValues.length ? dataValues : [100],
            backgroundColor: bgColors.length ? bgColors : ['#cbd5e1'],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 4
          }]
        },
        plugins: [donutPercentagePlugin],
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '66%',
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e293b',
              titleFont: { family: 'Poppins', size: 12, weight: '500' },
              bodyFont: { family: 'Poppins', size: 12 },
              padding: 10,
              cornerRadius: 8,
              callbacks: {
                label: (item) => ' ' + item.label + ': ' + item.raw + '%'
              }
            }
          }
        }
      });
    }

    // Tasks Interactive Handlers (In Process / Upcoming)
    const taskDatasets = {
      in_process: [
        { title: "Skote Saas Dashboard", assigned: "Assigned to Mark", done: false },
        { title: "New Landing UI", assigned: "Assigned to Team A", done: false },
        { title: "Brand logo design", assigned: "Assigned to Janis", done: false },
        { title: "Blog Template UI", assigned: "Assigned to Dianna", done: false }
      ],
      upcoming: [
        { title: "HVAC Chiller Maintenance", assigned: "Assigned to Engr. Farhan", done: false },
        { title: "Substation Relay Calibration", assigned: "Assigned to Team Alpha", done: false },
        { title: "Fire Hydrant Pressure Test", assigned: "Assigned to Safety Lead", done: false },
        { title: "Water Tank Disinfection", assigned: "Assigned to Sanitize Unit", done: false }
      ]
    };
    let currentTaskTab = "in_process";

    function renderTasks() {
      const container = document.getElementById("taskListContainer");
      if (!container) return;
      const list = taskDatasets[currentTaskTab] || [];
      container.innerHTML = "";

      list.forEach((t, idx) => {
        const item = document.createElement("div");
        item.className = "task-row-item";
        item.style.cssText = "display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f8fafc;";
        item.innerHTML = `
          <div style="display:flex; align-items:center; gap:12px;">
            <input type="checkbox" ${t.done ? "checked" : ""} onchange="toggleTaskDoneIdx(${idx}, this.checked)" style="width:17px; height:17px; border-radius:4px; border:1.5px solid #cbd5e1; cursor:pointer; accent-color:#4361ee;">
            <div>
              <div class="task-title" style="font-size:0.88rem; font-weight:500; color:${t.done ? "#94a3b8" : "#1e293b"}; text-decoration:${t.done ? "line-through" : "none"}; transition:all 0.2s;">${t.title}</div>
              <div style="font-size:0.75rem; color:#94a3b8;">${t.assigned}</div>
            </div>
          </div>
          <div style="display:inline-flex; align-items:center; gap:10px;">
            <button type="button" onclick="editTaskIdx(${idx})" style="background:none; border:none; color:#10b981; cursor:pointer; padding:4px;" title="Edit Task">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            </button>
            <button type="button" onclick="deleteTaskIdx(${idx})" style="background:none; border:none; color:#ef4444; cursor:pointer; padding:4px;" title="Delete Task">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
          </div>
        `;
        container.appendChild(item);
      });
    }

    function switchTaskTab(tab) {
      currentTaskTab = tab;
      const inBtn = document.getElementById("taskTabInProcess");
      const upBtn = document.getElementById("taskTabUpcoming");
      if (tab === "in_process") {
        if (inBtn) { inBtn.style.background = "#4361ee"; inBtn.style.color = "#ffffff"; }
        if (upBtn) { upBtn.style.background = "transparent"; upBtn.style.color = "#64748b"; }
      } else {
        if (upBtn) { upBtn.style.background = "#4361ee"; upBtn.style.color = "#ffffff"; }
        if (inBtn) { inBtn.style.background = "transparent"; inBtn.style.color = "#64748b"; }
      }
      renderTasks();
    }

    function toggleTaskDoneIdx(idx, isChecked) {
      if (taskDatasets[currentTaskTab] && taskDatasets[currentTaskTab][idx]) {
        taskDatasets[currentTaskTab][idx].done = isChecked;
        renderTasks();
      }
    }

    function editTaskIdx(idx) {
      const current = taskDatasets[currentTaskTab][idx];
      if (!current) return;
      const newTitle = prompt("Edit Task Title:", current.title);
      if (newTitle && newTitle.trim()) {
        current.title = newTitle.trim();
        renderTasks();
      }
    }

    function deleteTaskIdx(idx) {
      if (confirm("Are you sure you want to delete this task?")) {
        taskDatasets[currentTaskTab].splice(idx, 1);
        renderTasks();
      }
    }

    function addNewTaskPrompt() {
      const title = prompt("Enter new task name:");
      if (!title || !title.trim()) return;
      const assigned = prompt("Assigned to (e.g. Mark, Team A):", "Assigned to Ops Lead");
      taskDatasets[currentTaskTab].push({
        title: title.trim(),
        assigned: assigned ? assigned.trim() : "Assigned to Ops Lead",
        done: false
      });
      renderTasks();
    }

    // Chat Interactive Handlers
    function sendChatQuickMessage() {
      const input = document.getElementById("chatInputMessage");
      const stream = document.getElementById("dashboardChatStream");
      if (!input || !stream) return;
      const text = input.value.trim();
      if (!text) return;

      const now = new Date();
      const timeStr = String(now.getHours()).padStart(2, "0") + "." + String(now.getMinutes()).padStart(2, "0");

      const bubble = document.createElement("div");
      bubble.style.cssText = "position:relative; align-self:flex-end; max-width:82%;";
      bubble.innerHTML = `
        <div style="background:#f1f5f9; border-radius:10px; padding:10px 14px; box-shadow:0 1px 3px rgba(0,0,0,0.02); text-align:right;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; flex-direction:row-reverse;">
            <span style="font-size:0.78rem; font-weight:500; color:#3e60d5;">admin</span>
            <span style="font-size:0.8rem; color:#94a3b8; cursor:pointer;">⋮</span>
          </div>
          <div style="font-size:0.82rem; color:#1e293b; line-height:1.4;">${text}</div>
          <div style="display:flex; align-items:center; justify-content:flex-end; gap:4px; font-size:0.68rem; color:#94a3b8; margin-top:6px;">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            ${timeStr}
          </div>
        </div>
      `;
      stream.appendChild(bubble);
      input.value = "";
      stream.scrollTop = stream.scrollHeight;
    }

    document.addEventListener('DOMContentLoaded', () => {
      renderTasks();
      initEarningSplineChart({{ $currentMonth }});
      initSalesAnalyticsDonut();
      initUnifiedBarChart();
      const modal = document.getElementById('dateToDateModal');
      if (modal) {
        modal.addEventListener('click', (e) => {
          if (e.target === modal) closeDateModal();
        });
      }
    });
  </script>
</x-layouts.admin>
