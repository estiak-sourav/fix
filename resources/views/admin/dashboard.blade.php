<x-layouts.admin title="Dashboard | FIX Command Center">
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
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--paces-text-muted, #64748b);
      margin-bottom: 2px;
    }
    .top-kpi-value {
      font-size: 1.65rem;
      font-weight: 800;
      color: var(--paces-text-dark, #1e293b);
      font-family: 'Poppins', sans-serif;
      line-height: 1.1;
      margin: 0;
    }
  </style>

  <!-- Sticky Dashboard Header: Dashboard Title on Left, Global Date Range Filter on Right (Remains pinned on scroll) -->
  <div class="dashboard-sticky-bar">
    <div style="display:flex; align-items:center; gap:12px;">
      <h1 class="page-header-title" style="margin:0; font-size:1.35rem; font-weight:800; color:#1e293b;">Dashboard</h1>
      <span style="background:#3e60d5; color:#ffffff; font-size:0.78rem; font-weight:600; padding:5px 14px; border-radius:20px; white-space:nowrap; box-shadow:0 2px 6px rgba(62,96,213,0.25);">
        {{ $stats['range_label'] }}
      </span>
    </div>

    <!-- Date Range Action Pills (Global Dashboard Filter: Yesterday, Today, Week, Month, Year & Date to Date) -->
    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
      <div style="display:flex; align-items:center; background:#ffffff; border:1px solid #eaedf1; border-radius:8px; padding:3px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
        <!-- Yesterday -->
        <a href="{{ route('admin.dashboard', ['range' => 'yesterday']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:600; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'yesterday' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Yesterday
        </a>

        <!-- Today -->
        <a href="{{ route('admin.dashboard', ['range' => 'today']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:600; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'today' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Today
        </a>

        <!-- Week -->
        <a href="{{ route('admin.dashboard', ['range' => 'week']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:600; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'week' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Week
        </a>

        <!-- Month -->
        <a href="{{ route('admin.dashboard', ['range' => 'month']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:600; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'month' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Month
        </a>

        <!-- Year -->
        <a href="{{ route('admin.dashboard', ['range' => 'year']) }}" 
          style="text-decoration:none; font-size:0.825rem; font-weight:600; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ $stats['range'] === 'year' ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'color:#64748b;' }}">
          Year
        </a>

        <!-- Date to Date (Clean popup trigger, no icon) -->
        <button type="button" onclick="openDateModal()" 
          style="border:none; cursor:pointer; font-size:0.825rem; font-weight:600; padding:6px 14px; border-radius:6px; transition:all 0.15s ease; {{ in_array($stats['range'], ['custom', 'date']) ? 'background:#3e60d5; color:#ffffff; box-shadow:0 2px 6px rgba(62,96,213,0.3);' : 'background:transparent; color:#64748b;' }}">
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
      <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0; font-family: 'Poppins', sans-serif;">
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
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Total visitor</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['total_visitors']) }}</div>
        </div>

        <!-- 2. New visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #60a5fa;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">New visitor</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['new_visitors']) }}</div>
        </div>

        <!-- 3. Login -->
        <div class="analytics-counter-item" style="border-top:3px solid #8b5cf6;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Login</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['logins']) }}</div>
        </div>

        <!-- 4. Registration -->
        <div class="analytics-counter-item" style="border-top:3px solid #a855f7;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Registration</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['registrations']) }}</div>
        </div>

        <!-- 5. Service visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #f6b749;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Service visitor</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['service_visitors']) }}</div>
        </div>

        <!-- 6. AMC visitor -->
        <div class="analytics-counter-item" style="border-top:3px solid #f59e0b;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">AMC visitor</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['amc_visitors']) }}</div>
        </div>

        <!-- 7. Service Request (Under Service visitor) -->
        <div class="analytics-counter-item" style="border-top:3px solid #06b6d4;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Service Request</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['service_requests']) }}</div>
        </div>

        <!-- 8. AMC Request (Under AMC visitor) -->
        <div class="analytics-counter-item" style="border-top:3px solid #ec4899;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">AMC Request</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['amc_requests']) }}</div>
        </div>

        <!-- 9. Service Book (Under Service Request) -->
        <div class="analytics-counter-item" style="border-top:3px solid #20c997;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Service Book</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['service_book']) }}</div>
        </div>

        <!-- 10. AMC Book (Under AMC Request) -->
        <div class="analytics-counter-item" style="border-top:3px solid #10b981;">
          <div style="font-size:0.68rem; font-weight:600; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">AMC Book</div>
          <div style="font-size:1.1rem; font-weight:700; color:#1e293b; margin-top:2px;">{{ number_format($metricChart['details']['amc_book']) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Simple Calendar Date Selection Popup Modal -->
  <div id="dateToDateModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:400px; padding:22px 24px; position:relative;" onclick="event.stopPropagation()">
      <!-- Modal Header -->
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="margin:0; font-size:1.05rem; font-weight:700; color:#1e293b; font-family:'Poppins', sans-serif;">Select Date Range</h3>
        <button type="button" onclick="closeDateModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <!-- Modal Form -->
      <form action="{{ route('admin.dashboard') }}" method="GET" style="margin:0;">
        <input type="hidden" name="range" value="custom">

        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:600; color:#475569; margin-bottom:4px;">From</label>
            <input type="date" name="start_date" id="modalStartDate" value="{{ $stats['start_date'] }}" class="form-control-paces" style="width:100%; height:38px; font-size:0.875rem; padding:6px 10px; border-radius:8px;" required>
          </div>
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:600; color:#475569; margin-bottom:4px;">To</label>
            <input type="date" name="end_date" id="modalEndDate" value="{{ $stats['end_date'] }}" class="form-control-paces" style="width:100%; height:38px; font-size:0.875rem; padding:6px 10px; border-radius:8px;" required>
          </div>
        </div>

        <!-- Quick preset shortcuts -->
        <div style="display:flex; gap:6px; margin-bottom:18px; flex-wrap:wrap;">
          <button type="button" onclick="setModalQuickDates('today')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:600; color:#475569; cursor:pointer;">Today</button>
          <button type="button" onclick="setModalQuickDates('yesterday')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:600; color:#475569; cursor:pointer;">Yesterday</button>
          <button type="button" onclick="setModalQuickDates('last7')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:600; color:#475569; cursor:pointer;">Last 7 Days</button>
          <button type="button" onclick="setModalQuickDates('last30')" style="background:#f1f5f9; border:none; padding:4px 10px; border-radius:6px; font-size:0.75rem; font-weight:600; color:#475569; cursor:pointer;">Last 30 Days</button>
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
              <div style="display:flex; justify-content:space-between; font-size:0.825rem; font-weight:600; margin-bottom:4px;">
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

    <!-- Dhaka Area Regional Hotspots -->
    <div class="card-paces" style="margin-bottom:0;">
      <div class="card-header-paces">
        <h3 class="card-title-paces" style="margin:0;">Dhaka Operations Hotspots</h3>
        <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          Dispatch Map →
        </a>
      </div>

      @if($areaHotspots->isEmpty())
        <div style="text-align:center; padding:2rem; color:var(--paces-text-muted);">
          No area records in this date period.
        </div>
      @else
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
          @foreach($areaHotspots as $areaName => $count)
            <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
              <div>
                <div style="font-size:0.85rem; font-weight:700; color:var(--paces-text-dark);">📍 {{ $areaName }}</div>
                <div style="font-size:0.75rem; color:var(--paces-text-muted);">Field dispatch ready</div>
              </div>
              <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.8rem; font-weight:800;">
                {{ $count }}
              </span>
            </div>
          @endforeach
        </div>
      @endif
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
        <h4 style="font-size:1rem; font-weight:700; color:var(--paces-text-dark); margin-bottom:4px;">No Service Orders In This Period</h4>
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
              <th>Urgency</th>
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
                  <div style="font-weight:700; color:var(--paces-text-dark);">{{ $b->customer_name }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->customer_phone }}</div>
                </td>
                <td>
                  <div style="font-weight:600; color:var(--paces-text-dark);">{{ $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service') }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize;">{{ $b->property_type }}</div>
                </td>
                <td>
                  <div style="font-weight:500;">{{ $b->area }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->district }}</div>
                </td>
                <td>
                  @if($b->urgency === 'emergency_1h')
                    <span class="badge-pill-paces bg-danger-subtle">
                      <span class="pulse-dot" style="background:#dc2626;"></span>
                      <span>1h Emergency</span>
                    </span>
                  @elseif($b->urgency === 'urgent_4h' || $b->urgency === 'same_day_4h')
                    <span class="badge-pill-paces bg-warning-subtle">
                      <span>⚡ 4h Urgent</span>
                    </span>
                  @else
                    <span class="badge-pill-paces bg-primary-subtle">
                      <span>Standard</span>
                    </span>
                  @endif
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
                  <div style="display:inline-flex; gap:6px;">
                    <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-outline btn-paces-sm">
                      View
                    </a>
                    @if($b->status === 'pending_review')
                      <form action="{{ route('admin.bookings.update-status', $b) }}" method="POST" style="margin:0; display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <input type="hidden" name="comment" value="Request accepted by Operations Lead.">
                        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">
                          Accept ✓
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
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
                font: { family: 'Poppins', size: 10.5, weight: '600' },
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
              titleFont: { family: 'Poppins', size: 12, weight: '700' },
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

    document.addEventListener('DOMContentLoaded', () => {
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
