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

  @php
    $bookingOptionsData = ($taskBookings ?? collect())->map(function($b) {
        return [
            'id' => $b->id,
            'number' => $b->booking_number,
            'name' => $b->customer_name,
            'phone' => $b->customer_phone,
            'email' => $b->customer_email,
            'service' => $b->service?->title ?? 'General Maintenance',
            'status' => ucfirst(str_replace('_', ' ', $b->status)),
            'call_status' => $b->call_status ? ucfirst(str_replace('_', ' ', $b->call_status)) : null,
            'call_notes' => $b->call_notes,
            'address' => trim(($b->street_address ?? '').', '.($b->area ?? '').', '.($b->district ?? '')),
            'date' => $b->preferred_date ? \Carbon\Carbon::parse($b->preferred_date)->format('M d, Y') : null,
            'time' => $b->preferred_time_slot,
            'cost' => ($b->final_cost ?? $b->estimated_cost) ? '৳ '.number_format($b->final_cost ?? $b->estimated_cost, 0) : null,
            'user_id' => $b->user_id,
        ];
    })->values();

    $userOptionsData = ($taskUsers ?? collect())->map(function($u) {
        return [
            'id' => $u->id,
            'name' => $u->name,
            'phone' => $u->phone,
            'email' => $u->email,
            'address' => $u->default_address ?? trim(($u->area ?? '').', '.($u->district ?? '')),
            'created_at' => $u->created_at ? $u->created_at->format('M d, Y') : null,
            'company' => $u->company_name,
        ];
    })->values();
  @endphp

  <!-- Create Outreach Task Popup Modal -->
  <div id="createTaskModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:520px; padding:22px 24px; position:relative; max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <div>
          <h3 style="margin:0; font-size:1.1rem; font-weight:600; color:#1e293b; font-family:'Poppins', sans-serif;">Create Task</h3>
          <p style="margin:2px 0 0; font-size:0.78rem; color:#64748b;">Add a new simple task, or link to a booking/user for detailed follow-up.</p>
        </div>
        <button type="button" onclick="closeCreateTaskModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <form action="{{ route('admin.outreach-tasks.store') }}" method="POST" style="margin:0;">
        @csrf

        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">
          <!-- 1. Basic Info: Title -->
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Task Title *</label>
            <input type="text" name="title" id="taskTitleInput" class="form-control-paces" placeholder="e.g. Call customer for site visit schedule" required style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
          </div>

          <!-- Priority & Due Time -->
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Priority</label>
              <select name="priority" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="normal" selected>Normal</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
                <option value="low">Low</option>
              </select>
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Due Date & Time (Optional)</label>
              <input type="datetime-local" name="due_at" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
            </div>
          </div>

          <!-- 2. Searchable Booking Dropdown -->
          <div style="position:relative;">
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Link to Booking (Optional)</label>
            <input type="hidden" name="booking_id" id="taskBookingId" value="">
            
            <div id="bookingDropdownTrigger" onclick="toggleTaskDropdown('bookingDropdownMenu')" style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer; font-size:0.85rem; min-height:38px;">
              <span id="bookingDropdownSelectedText" style="color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Select a booking (Optional)...</span>
              <div style="display:flex; align-items:center; gap:6px;">
                <span id="bookingClearBtn" onclick="clearSelectedBooking(event)" style="display:none; color:#94a3b8; font-size:1.1rem; line-height:1; padding:0 3px; cursor:pointer;" title="Clear booking">&times;</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
              </div>
            </div>

            <div id="bookingDropdownMenu" style="display:none; position:absolute; top:calc(100% + 4px); left:0; width:100%; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 12px 28px rgba(0,0,0,0.14); z-index:10010; flex-direction:column; overflow:hidden;">
              <div style="padding:8px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <input type="text" id="bookingSearchInput" oninput="filterBookings(this.value)" placeholder="Search booking #, customer, phone..." style="width:100%; padding:6px 10px; font-size:0.82rem; border:1px solid #cbd5e1; border-radius:6px; outline:none;" onclick="event.stopPropagation()">
              </div>
              <div id="bookingOptionsList" style="overflow-y:auto; max-height:180px; padding:4px 0;">
                <!-- Populated dynamically via JS -->
              </div>
            </div>
          </div>

          <!-- 3. Searchable User Dropdown -->
          <div style="position:relative;">
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Link to Customer / User (Optional)</label>
            <input type="hidden" name="user_id" id="taskUserId" value="">
            
            <div id="userDropdownTrigger" onclick="toggleTaskDropdown('userDropdownMenu')" style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer; font-size:0.85rem; min-height:38px;">
              <span id="userDropdownSelectedText" style="color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Select a customer / user (Optional)...</span>
              <div style="display:flex; align-items:center; gap:6px;">
                <span id="userClearBtn" onclick="clearSelectedUser(event)" style="display:none; color:#94a3b8; font-size:1.1rem; line-height:1; padding:0 3px; cursor:pointer;" title="Clear customer">&times;</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
              </div>
            </div>

            <div id="userDropdownMenu" style="display:none; position:absolute; top:calc(100% + 4px); left:0; width:100%; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 12px 28px rgba(0,0,0,0.14); z-index:10010; flex-direction:column; overflow:hidden;">
              <div style="padding:8px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <input type="text" id="userSearchInput" oninput="filterUsers(this.value)" placeholder="Search customer by name, phone, email..." style="width:100%; padding:6px 10px; font-size:0.82rem; border:1px solid #cbd5e1; border-radius:6px; outline:none;" onclick="event.stopPropagation()">
              </div>
              <div id="userOptionsList" style="overflow-y:auto; max-height:180px; padding:4px 0;">
                <!-- Populated dynamically via JS -->
              </div>
            </div>
          </div>

          <!-- 4. Dynamic Previous Details Info Card (Visible when Booking or User is chosen) -->
          <div id="linkedEntityDetailsCard" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px; font-size:0.8rem;">
            <!-- Dynamically populated -->
          </div>

          <!-- 5. New Info / Notes Textarea (Visible when Booking or User is chosen) -->
          <div id="taskNewInfoContainer" style="display:none;">
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">
              <span>New Info / Notes for this Task</span>
              <span style="font-size:0.75rem; color:#10b981; font-weight:500; margin-left:4px;">(Visible because entity is selected)</span>
            </label>
            <textarea name="notes" id="taskNotesInput" class="form-control-paces" rows="3" placeholder="Enter instructions, discussion points, new customer details or action items..." style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;"></textarea>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeCreateTaskModal()" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 18px;">Save Task</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Outreach Task Modal -->
  <div id="editTaskModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:480px; padding:22px 24px; position:relative; max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <div>
          <h3 style="margin:0; font-size:1.1rem; font-weight:600; color:#1e293b; font-family:'Poppins', sans-serif;">Edit Task</h3>
          <p style="margin:2px 0 0; font-size:0.78rem; color:#64748b;">Update task details, notes or mark as completed.</p>
        </div>
        <button type="button" onclick="closeEditTaskModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <form id="editTaskForm" method="POST" style="margin:0;">
        @csrf
        @method('PUT')

        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Task Title *</label>
            <input type="text" name="title" id="editTaskTitle" class="form-control-paces" required style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Priority</label>
              <select name="priority" id="editTaskPriority" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="normal">Normal</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
                <option value="low">Low</option>
              </select>
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Status</label>
              <select name="status" id="editTaskStatus" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>

          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Due Date & Time (Optional)</label>
            <input type="datetime-local" name="due_at" id="editTaskDueAt" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
          </div>

          <div id="editTaskLinkedInfo" style="display:none; font-size:0.78rem; padding:8px 12px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; color:#475569;">
            <!-- dynamically populated -->
          </div>

          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Task Notes / Details</label>
            <textarea name="notes" id="editTaskNotes" class="form-control-paces" rows="3" placeholder="Enter task notes or instructions..." style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;"></textarea>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeEditTaskModal()" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 18px;">Update Task</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Task Details Popup Modal (Clean & Minimal) -->
  <div id="taskDetailsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;" onclick="closeTaskDetailsModal()">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:540px; padding:22px 24px; position:relative; max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#64748b;" id="modalTaskType">Task Details</span>
          <span id="modalTaskPriorityBadge"></span>
          <span id="modalTaskStatusBadge"></span>
        </div>
        <button type="button" onclick="closeTaskDetailsModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <!-- Task Title -->
      <h2 id="modalTaskTitle" style="font-size:1.2rem; font-weight:700; color:#0f172a; margin:0 0 12px; line-height:1.35;"></h2>

      <!-- Due Date -->
      <div id="modalTaskDueDateRow" style="display:flex; align-items:center; gap:6px; font-size:0.83rem; color:#475569; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #f1f5f9;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span id="modalTaskDueText"></span>
      </div>

      <!-- Notes Box -->
      <div style="margin-bottom:16px;">
        <label style="font-size:0.75rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:6px;">Notes & Instructions</label>
        <div id="modalTaskNotes" style="font-size:0.875rem; color:#334155; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; line-height:1.5; white-space:pre-wrap;"></div>
      </div>

      <!-- Linked Booking Info (if any) -->
      <div id="modalLinkedBookingCard" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 14px; margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
          <span style="font-size:0.75rem; font-weight:700; color:#047857; text-transform:uppercase;">Linked Booking</span>
          <a href="#" id="modalBookingLink" target="_blank" style="font-size:0.78rem; font-weight:600; color:#059669; text-decoration:none;">Open Booking →</a>
        </div>
        <div id="modalBookingDetails" style="font-size:0.84rem; color:#1e293b;"></div>
      </div>

      <!-- Linked Customer Info (if any) -->
      <div id="modalLinkedUserCard" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
          <span style="font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase;">Linked Customer</span>
          <a href="#" id="modalUserLink" target="_blank" style="font-size:0.78rem; font-weight:600; color:var(--paces-primary, #3e60d5); text-decoration:none;">View Customer →</a>
        </div>
        <div id="modalUserDetails" style="font-size:0.84rem; color:#1e293b;"></div>
      </div>

      <!-- Audit Meta -->
      <div style="font-size:0.76rem; color:#94a3b8; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:12px; margin-bottom:16px;">
        <span id="modalTaskCreatedMeta"></span>
        <span id="modalTaskCompletedMeta"></span>
      </div>

      <!-- Modal Actions -->
      <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
        <form id="modalDeleteForm" method="POST" style="margin:0;" onsubmit="return confirm('Delete this task?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-paces btn-paces-sm" style="color:#ef4444; border:1px solid #fecaca; background:#fff; height:36px; padding:0 12px;">Delete</button>
        </form>

        <div style="display:flex; gap:8px;">
          <button type="button" id="modalEditBtn" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Edit Task</button>
          
          <form id="modalCompleteForm" method="POST" style="margin:0;">
            @csrf
            <button type="submit" id="modalCompleteBtn" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 16px;">Mark Completed</button>
          </form>
        </div>
      </div>
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

    <!-- Card 2: Outreach Tasks (Clean & Minimal) -->
    <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
      <div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
          <div style="display:flex; align-items:center; gap:8px;">
            <h3 style="font-size:1.05rem; font-weight:600; color:#0f172a; margin:0; font-family:'Poppins', sans-serif;">Outreach Tasks</h3>
            <span style="font-size:0.75rem; font-weight:600; padding:2px 8px; border-radius:12px; background:{{ $outreachTasks->count() > 0 ? '#fffbeb' : '#ecfdf5' }}; color:{{ $outreachTasks->count() > 0 ? '#b45309' : '#047857' }}; border:1px solid {{ $outreachTasks->count() > 0 ? '#fde68a' : '#a7f3d0' }};">
              {{ $outreachTasks->count() }} Pending
            </span>
          </div>

          <button type="button" onclick="openCreateTaskModal()" class="btn-paces btn-paces-primary btn-paces-sm" style="padding:4px 10px; font-size:0.75rem; display:inline-flex; align-items:center; gap:5px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Create Task</span>
          </button>
        </div>

        <div id="taskListContainer" style="display:flex; flex-direction:column; gap:8px;">
          @forelse($outreachTasks as $task)
            @php
              $taskData = [
                'id' => $task->id,
                'title' => $task->title,
                'task_type' => ucfirst(str_replace('_', ' ', $task->task_type ?? 'General')),
                'priority' => $task->priority,
                'status' => $task->status,
                'due_at' => $task->due_at ? $task->due_at->format('M d, Y, h:i A') : null,
                'due_at_input' => $task->due_at ? $task->due_at->format('Y-m-d\TH:i') : '',
                'is_overdue' => $task->due_at ? $task->due_at->isPast() : false,
                'overdue_text' => $task->due_at ? ($task->due_at->isPast() ? $task->due_at->diffForHumans() : '') : '',
                'notes' => $task->notes ?? '',
                'customer_name' => $task->customer_name ?? ($task->booking?->customer_name ?? $task->user?->name),
                'customer_phone' => $task->customer_phone ?? ($task->booking?->customer_phone ?? $task->user?->phone),
                'booking_id' => $task->booking_id,
                'booking_number' => $task->booking?->booking_number,
                'booking_url' => $task->booking ? route('admin.bookings.show', $task->booking) : null,
                'booking_service' => $task->booking?->service?->title ?? ($task->booking?->service_type ? ucfirst(str_replace('_', ' ', $task->booking->service_type)) : null),
                'booking_status' => $task->booking?->status ? ucfirst(str_replace('_', ' ', $task->booking->status)) : null,
                'user_id' => $task->user_id,
                'user_name' => $task->user?->name,
                'user_email' => $task->user?->email,
                'user_url' => $task->user ? route('admin.users.show', $task->user) : null,
                'created_at' => $task->created_at ? $task->created_at->format('M d, Y h:i A') : '',
                'created_by' => $task->createdByAdmin?->name ?? 'System',
                'completed_at' => $task->completed_at ? $task->completed_at->format('M d, Y h:i A') : null,
                'completed_by' => $task->completedByAdmin?->name,
                'complete_url' => route('admin.outreach-tasks.complete', $task),
                'update_url' => route('admin.outreach-tasks.update', $task),
                'delete_url' => route('admin.outreach-tasks.destroy', $task),
              ];
            @endphp
            <div class="task-interactive-row" onclick="openTaskDetailsModal({{ json_encode($taskData) }})" style="display:flex; align-items:center; justify-content:space-between; padding:9px 12px; background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; gap:10px; cursor:pointer; transition:all 0.15s ease;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#e2e8f0';">
              <!-- Left: Checkbox + Title & Meta -->
              <div style="display:flex; align-items:center; gap:10px; min-width:0; flex:1;">
                <form action="{{ route('admin.outreach-tasks.complete', $task) }}" method="POST" style="margin:0; flex-shrink:0;" onclick="event.stopPropagation()">
                  @csrf
                  <button type="submit" title="Mark as Done" style="width:20px; height:20px; border-radius:50%; border:2px solid #cbd5e1; background:transparent; display:flex; align-items:center; justify-content:center; cursor:pointer; padding:0; color:transparent; transition:all 0.15s ease;" onmouseover="this.style.borderColor='#10b981'; this.style.color='#10b981';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='transparent';">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  </button>
                </form>

                <div style="min-width:0; flex:1;">
                  <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                    <span style="font-size:0.86rem; font-weight:600; color:#1e293b; line-height:1.3; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:240px;" title="{{ $task->title }}">
                      {{ $task->title }}
                    </span>
                    @if($task->priority === 'urgent')
                      <span style="font-size:0.65rem; font-weight:700; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; padding:1px 5px; border-radius:6px; text-transform:uppercase;">Urgent</span>
                    @elseif($task->priority === 'high')
                      <span style="font-size:0.65rem; font-weight:700; background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; padding:1px 5px; border-radius:6px; text-transform:uppercase;">High</span>
                    @endif
                  </div>

                  @if($task->due_at || $task->booking || $task->user)
                    <div style="font-size:0.73rem; color:#64748b; margin-top:2px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                      @if($task->due_at)
                        <span style="color:{{ $task->due_at->isPast() ? '#dc2626' : '#64748b' }}; font-weight:{{ $task->due_at->isPast() ? '600' : '400' }}; display:inline-flex; align-items:center; gap:3px;">
                          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                          <span>{{ $task->due_at->isPast() ? 'Overdue: '.$task->due_at->diffForHumans() : $task->due_at->format('M d, h:i A') }}</span>
                        </span>
                      @endif

                      @if($task->booking)
                        <span style="display:inline-flex; align-items:center; gap:3px;" onclick="event.stopPropagation()">
                          <a href="{{ route('admin.bookings.show', $task->booking) }}" style="color:var(--paces-primary, #3e60d5); text-decoration:none; font-size:0.72rem; font-weight:600; font-family:monospace;" title="Linked Booking">
                            #{{ $task->booking->booking_number }}
                          </a>
                        </span>
                      @elseif($task->user)
                        <span style="font-size:0.72rem; color:#475569; display:inline-flex; align-items:center; gap:3px;" onclick="event.stopPropagation()">
                          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                          <span>{{ $task->user->name }}</span>
                        </span>
                      @endif
                    </div>
                  @endif
                </div>
              </div>

              <!-- Right: Clean Action Buttons (NO TEXT) -->
              <div style="display:flex; align-items:center; gap:4px; flex-shrink:0;" onclick="event.stopPropagation()">
                <!-- Edit Icon Button -->
                <button type="button" onclick="openEditTaskModal({{ json_encode($taskData) }})" style="width:28px; height:28px; border-radius:6px; border:1px solid #e2e8f0; background:#ffffff; color:#64748b; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; padding:0; transition:all 0.15s ease;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#0f172a';" onmouseout="this.style.background='#ffffff'; this.style.color='#64748b';" title="Edit Task">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>

                <!-- Done Icon Button -->
                <form action="{{ route('admin.outreach-tasks.complete', $task) }}" method="POST" style="margin:0;">
                  @csrf
                  <button type="submit" style="width:28px; height:28px; border-radius:6px; border:1px solid #a7f3d0; background:#ecfdf5; color:#059669; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; padding:0; transition:all 0.15s ease;" onmouseover="this.style.background='#d1fae5'; this.style.color='#047857';" onmouseout="this.style.background='#ecfdf5'; this.style.color='#059669';" title="Mark as Done">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  </button>
                </form>

                <!-- Delete Icon Button -->
                <form action="{{ route('admin.outreach-tasks.destroy', $task) }}" method="POST" style="margin:0;" onsubmit="return confirm('Delete this task?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" style="width:28px; height:28px; border-radius:6px; border:1px solid #fee2e2; background:#ffffff; color:#ef4444; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; padding:0; transition:all 0.15s ease;" onmouseover="this.style.background='#fef2f2';" onmouseout="this.style.background='#ffffff';" title="Delete Task">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </form>
              </div>
            </div>
          @empty
            <div style="text-align:center; padding:1.75rem 1rem; color:var(--paces-text-muted);">
              <div style="width:40px; height:40px; border-radius:50%; background:#ecfdf5; color:#059669; display:inline-flex; align-items:center; justify-content:center; margin-bottom:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
              </div>
              <div style="font-weight:600; color:#1e293b; font-size:0.88rem;">All Tasks Completed</div>
              <p style="font-size:0.76rem; color:#64748b; margin:2px 0 10px;">No pending follow-ups or outreaches right now.</p>
              <button type="button" onclick="openCreateTaskModal()" class="btn-paces btn-paces-primary btn-paces-sm" style="padding:4px 10px; font-size:0.75rem; display:inline-flex; align-items:center; gap:4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Create Task</span>
              </button>
            </div>
          @endforelse
        </div>
      </div>
      <div style="text-align:center; padding-top:0.85rem; border-top:1px solid #f1f5f9; margin-top:0.85rem;">
        <a href="{{ route('admin.outreach-tasks.index') }}" class="btn-paces btn-paces-outline btn-paces-sm" style="width:100%; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
          <span>View All Tasks</span>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
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
                  <div style="display:flex; align-items:center; gap:10px;">
                    @if($b->user?->avatar_url)
                      <img src="{{ $b->user->avatar_url }}" alt="{{ $b->customer_name }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                    @else
                      <div style="width:32px; height:32px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:600; display:flex; align-items:center; justify-content:center; font-size:0.8rem; flex-shrink:0;">
                        {{ strtoupper(substr($b->customer_name ?: 'C', 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->customer_name }}</div>
                      <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->customer_phone }}</div>
                    </div>
                  </div>
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

    // Outreach Tasks Management & Searchable Dropdowns
    const taskBookingsData = @json($bookingOptionsData ?? []);
    const taskUsersData = @json($userOptionsData ?? []);
    let selectedTaskBooking = null;
    let selectedTaskUser = null;

    function renderBookingOptions(filter = '') {
      const list = document.getElementById('bookingOptionsList');
      if (!list) return;
      const q = filter.trim().toLowerCase();
      const filtered = taskBookingsData.filter(b => {
        if (!q) return true;
        return (b.number && b.number.toLowerCase().includes(q)) ||
               (b.name && b.name.toLowerCase().includes(q)) ||
               (b.phone && b.phone.toLowerCase().includes(q)) ||
               (b.service && b.service.toLowerCase().includes(q));
      });

      if (filtered.length === 0) {
        list.innerHTML = '<div style="padding:10px 12px; font-size:0.8rem; color:#94a3b8; text-align:center;">No matching bookings found</div>';
        return;
      }

      list.innerHTML = filtered.map(b => `
        <div onclick="selectBooking(${b.id})" style="padding:8px 12px; cursor:pointer; font-size:0.82rem; border-bottom:1px solid #f1f5f9; display:flex; flex-direction:column; gap:2px;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-weight:600; color:var(--paces-primary); font-family:monospace;">#${b.number}</span>
            <span style="font-size:0.7rem; color:#64748b; background:#f1f5f9; padding:1px 6px; border-radius:4px;">${b.status}</span>
          </div>
          <div style="color:#1e293b; font-weight:500;">${b.name || 'Unknown'} ${b.phone ? '• <span style="color:#64748b;">'+b.phone+'</span>' : ''}</div>
          <div style="font-size:0.75rem; color:#64748b; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${b.service || ''} ${b.cost ? '• '+b.cost : ''}</div>
        </div>
      `).join('');
    }

    function renderUserOptions(filter = '') {
      const list = document.getElementById('userOptionsList');
      if (!list) return;
      const q = filter.trim().toLowerCase();
      const filtered = taskUsersData.filter(u => {
        if (!q) return true;
        return (u.name && u.name.toLowerCase().includes(q)) ||
               (u.phone && u.phone.toLowerCase().includes(q)) ||
               (u.email && u.email.toLowerCase().includes(q));
      });

      if (filtered.length === 0) {
        list.innerHTML = '<div style="padding:10px 12px; font-size:0.8rem; color:#94a3b8; text-align:center;">No matching customers found</div>';
        return;
      }

      list.innerHTML = filtered.map(u => `
        <div onclick="selectUser(${u.id})" style="padding:8px 12px; cursor:pointer; font-size:0.82rem; border-bottom:1px solid #f1f5f9; display:flex; flex-direction:column; gap:2px;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
          <div style="font-weight:600; color:#1e293b;">${u.name}</div>
          <div style="font-size:0.75rem; color:#64748b;">${u.phone || ''} ${u.email ? '• '+u.email : ''}</div>
        </div>
      `).join('');
    }

    function filterBookings(val) {
      renderBookingOptions(val);
    }

    function filterUsers(val) {
      renderUserOptions(val);
    }

    function toggleTaskDropdown(menuId) {
      const menu = document.getElementById(menuId);
      if (!menu) return;
      const isOpen = menu.style.display === 'flex';
      const bMenu = document.getElementById('bookingDropdownMenu');
      const uMenu = document.getElementById('userDropdownMenu');
      if (bMenu) bMenu.style.display = 'none';
      if (uMenu) uMenu.style.display = 'none';

      if (!isOpen) {
        menu.style.display = 'flex';
        if (menuId === 'bookingDropdownMenu') {
          renderBookingOptions('');
          const inp = document.getElementById('bookingSearchInput');
          if (inp) { inp.value = ''; inp.focus(); }
        } else if (menuId === 'userDropdownMenu') {
          renderUserOptions('');
          const inp = document.getElementById('userSearchInput');
          if (inp) { inp.value = ''; inp.focus(); }
        }
      }
    }

    function selectBooking(id) {
      selectedTaskBooking = taskBookingsData.find(b => b.id == id);
      const hiddenInput = document.getElementById('taskBookingId');
      const textSpan = document.getElementById('bookingDropdownSelectedText');
      const clearBtn = document.getElementById('bookingClearBtn');
      const menu = document.getElementById('bookingDropdownMenu');

      if (hiddenInput) hiddenInput.value = id;
      if (textSpan && selectedTaskBooking) {
        textSpan.textContent = '#' + selectedTaskBooking.number + ' • ' + (selectedTaskBooking.name || 'Customer');
        textSpan.style.color = '#1e293b';
        textSpan.style.fontWeight = '600';
      }
      if (clearBtn) clearBtn.style.display = 'inline';
      if (menu) menu.style.display = 'none';

      updateEntityPreviewAndNotes();
    }

    function clearSelectedBooking(e) {
      if (e) e.stopPropagation();
      selectedTaskBooking = null;
      const hiddenInput = document.getElementById('taskBookingId');
      const textSpan = document.getElementById('bookingDropdownSelectedText');
      const clearBtn = document.getElementById('bookingClearBtn');

      if (hiddenInput) hiddenInput.value = '';
      if (textSpan) {
        textSpan.textContent = 'Select a booking (Optional)...';
        textSpan.style.color = '#64748b';
        textSpan.style.fontWeight = 'normal';
      }
      if (clearBtn) clearBtn.style.display = 'none';

      updateEntityPreviewAndNotes();
    }

    function selectUser(id) {
      selectedTaskUser = taskUsersData.find(u => u.id == id);
      const hiddenInput = document.getElementById('taskUserId');
      const textSpan = document.getElementById('userDropdownSelectedText');
      const clearBtn = document.getElementById('userClearBtn');
      const menu = document.getElementById('userDropdownMenu');

      if (hiddenInput) hiddenInput.value = id;
      if (textSpan && selectedTaskUser) {
        textSpan.textContent = selectedTaskUser.name + (selectedTaskUser.phone ? ' (' + selectedTaskUser.phone + ')' : '');
        textSpan.style.color = '#1e293b';
        textSpan.style.fontWeight = '600';
      }
      if (clearBtn) clearBtn.style.display = 'inline';
      if (menu) menu.style.display = 'none';

      updateEntityPreviewAndNotes();
    }

    function clearSelectedUser(e) {
      if (e) e.stopPropagation();
      selectedTaskUser = null;
      const hiddenInput = document.getElementById('taskUserId');
      const textSpan = document.getElementById('userDropdownSelectedText');
      const clearBtn = document.getElementById('userClearBtn');

      if (hiddenInput) hiddenInput.value = '';
      if (textSpan) {
        textSpan.textContent = 'Select a customer / user (Optional)...';
        textSpan.style.color = '#64748b';
        textSpan.style.fontWeight = 'normal';
      }
      if (clearBtn) clearBtn.style.display = 'none';

      updateEntityPreviewAndNotes();
    }

    function updateEntityPreviewAndNotes() {
      const previewCard = document.getElementById('linkedEntityDetailsCard');
      const notesContainer = document.getElementById('taskNewInfoContainer');
      if (!previewCard || !notesContainer) return;

      if (selectedTaskBooking) {
        const b = selectedTaskBooking;
        previewCard.style.display = 'block';
        previewCard.innerHTML = `
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; border-bottom:1px solid #bbf7d0; padding-bottom:6px;">
            <span style="font-weight:700; color:#166534; font-size:0.82rem;">Previous Booking Details (#${b.number})</span>
            <span style="font-size:0.7rem; font-weight:600; padding:2px 8px; border-radius:12px; background:#dcfce7; color:#15803d;">${b.status}</span>
          </div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; color:#1e293b; font-size:0.78rem;">
            <div><span style="color:#64748b;">Customer:</span> <strong>${b.name || 'N/A'}</strong></div>
            <div><span style="color:#64748b;">Phone:</span> <strong style="color:#0284c7;">${b.phone || 'N/A'}</strong></div>
            <div><span style="color:#64748b;">Service:</span> ${b.service || 'N/A'}</div>
            <div><span style="color:#64748b;">Amount:</span> <strong>${b.cost || 'N/A'}</strong></div>
            ${b.date ? `<div><span style="color:#64748b;">Schedule:</span> ${b.date} ${b.time ? '('+b.time+')' : ''}</div>` : ''}
            ${b.address ? `<div style="grid-column: span 2;"><span style="color:#64748b;">Address:</span> ${b.address}</div>` : ''}
          </div>
          ${b.call_status ? `<div style="margin-top:6px; padding-top:6px; border-top:1px dashed #86efac; font-size:0.75rem; color:#166534;"><span style="font-weight:600;">Previous Call Status:</span> ${b.call_status} ${b.call_notes ? '— <em>'+b.call_notes+'</em>' : ''}</div>` : ''}
        `;
        notesContainer.style.display = 'block';
      } else if (selectedTaskUser) {
        const u = selectedTaskUser;
        previewCard.style.display = 'block';
        previewCard.innerHTML = `
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; border-bottom:1px solid #bbf7d0; padding-bottom:6px;">
            <span style="font-weight:700; color:#166534; font-size:0.82rem;">Customer Profile Details</span>
            <span style="font-size:0.7rem; color:#15803d; font-weight:600; background:#dcfce7; padding:2px 8px; border-radius:12px;">Registered</span>
          </div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; color:#1e293b; font-size:0.78rem;">
            <div><span style="color:#64748b;">Name:</span> <strong>${u.name}</strong></div>
            <div><span style="color:#64748b;">Phone:</span> <strong style="color:#0284c7;">${u.phone || 'N/A'}</strong></div>
            <div><span style="color:#64748b;">Email:</span> ${u.email || 'N/A'}</div>
            <div><span style="color:#64748b;">Joined:</span> ${u.created_at || 'N/A'}</div>
            ${u.address ? `<div style="grid-column: span 2;"><span style="color:#64748b;">Address:</span> ${u.address}</div>` : ''}
          </div>
        `;
        notesContainer.style.display = 'block';
      } else {
        previewCard.style.display = 'none';
        previewCard.innerHTML = '';
        notesContainer.style.display = 'none';
      }
    }

    // Open Task Details Modal
    function openTaskDetailsModal(data) {
      document.getElementById('modalTaskTitle').innerText = data.title;
      document.getElementById('modalTaskType').innerText = data.task_type || 'General Task';

      // Priority badge
      const priorityEl = document.getElementById('modalTaskPriorityBadge');
      if (data.priority === 'urgent') {
        priorityEl.innerHTML = '<span style="font-size:0.7rem; font-weight:700; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; padding:1px 8px; border-radius:10px; text-transform:uppercase;">Urgent</span>';
      } else if (data.priority === 'high') {
        priorityEl.innerHTML = '<span style="font-size:0.7rem; font-weight:700; background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; padding:1px 8px; border-radius:10px; text-transform:uppercase;">High</span>';
      } else {
        priorityEl.innerHTML = '<span style="font-size:0.7rem; font-weight:600; background:#f1f5f9; color:#475569; padding:1px 8px; border-radius:10px; text-transform:capitalize;">' + (data.priority || 'Normal') + '</span>';
      }

      // Status badge
      const statusEl = document.getElementById('modalTaskStatusBadge');
      if (data.status === 'completed') {
        statusEl.innerHTML = '<span style="font-size:0.7rem; font-weight:600; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; padding:1px 8px; border-radius:10px;">Completed</span>';
      } else {
        statusEl.innerHTML = '<span style="font-size:0.7rem; font-weight:600; background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:1px 8px; border-radius:10px;">Pending</span>';
      }

      // Due date
      const dueRow = document.getElementById('modalTaskDueDateRow');
      if (data.due_at) {
        dueRow.style.display = 'flex';
        let dueHtml = '<span>Due: ' + data.due_at + '</span>';
        if (data.is_overdue && data.status !== 'completed') {
          dueHtml += ' <span style="color:#dc2626; font-weight:600; margin-left:6px;">(Overdue: ' + data.overdue_text + ')</span>';
        }
        document.getElementById('modalTaskDueText').innerHTML = dueHtml;
      } else {
        dueRow.style.display = 'none';
      }

      // Notes
      const notesEl = document.getElementById('modalTaskNotes');
      if (data.notes && data.notes.trim()) {
        notesEl.innerText = data.notes;
        notesEl.style.color = '#334155';
        notesEl.style.fontStyle = 'normal';
      } else {
        notesEl.innerText = 'No instructions or notes added to this task.';
        notesEl.style.color = '#94a3b8';
        notesEl.style.fontStyle = 'italic';
      }

      // Linked Booking
      const bookingCard = document.getElementById('modalLinkedBookingCard');
      if (data.booking_id && data.booking_number) {
        bookingCard.style.display = 'block';
        document.getElementById('modalBookingLink').href = data.booking_url || '#';
        document.getElementById('modalBookingDetails').innerHTML = `
          <div style="font-weight:600; color:var(--paces-primary, #3e60d5); font-family:monospace; margin-bottom:2px;">#${data.booking_number}</div>
          <div style="color:#475569;">${data.customer_name || 'Customer'} ${data.customer_phone ? '• ' + data.customer_phone : ''}</div>
          <div style="font-size:0.76rem; color:#64748b; margin-top:2px;">${data.booking_service || 'Service Order'} ${data.booking_status ? '• Status: ' + data.booking_status : ''}</div>
        `;
      } else {
        bookingCard.style.display = 'none';
      }

      // Linked User
      const userCard = document.getElementById('modalLinkedUserCard');
      if (data.user_id && data.user_name && !data.booking_id) {
        userCard.style.display = 'block';
        document.getElementById('modalUserLink').href = data.user_url || '#';
        document.getElementById('modalUserDetails').innerHTML = `
          <div style="font-weight:600; color:#1e293b;">${data.user_name}</div>
          <div style="color:#64748b; font-size:0.78rem;">${data.customer_phone || ''} ${data.user_email ? '• ' + data.user_email : ''}</div>
        `;
      } else {
        userCard.style.display = 'none';
      }

      // Audit meta
      document.getElementById('modalTaskCreatedMeta').innerText = 'Created ' + (data.created_at || 'Recently') + ' by ' + (data.created_by || 'Admin');
      if (data.status === 'completed' && data.completed_at) {
        document.getElementById('modalTaskCompletedMeta').innerText = 'Completed: ' + data.completed_at;
      } else {
        document.getElementById('modalTaskCompletedMeta').innerText = '';
      }

      // Form actions
      document.getElementById('modalCompleteForm').action = data.complete_url;
      const compBtn = document.getElementById('modalCompleteBtn');
      if (data.status === 'completed') {
        compBtn.style.display = 'none';
      } else {
        compBtn.style.display = 'inline-block';
      }

      document.getElementById('modalDeleteForm').action = data.delete_url;
      document.getElementById('modalEditBtn').onclick = function() {
        closeTaskDetailsModal();
        openEditTaskModal(data);
      };

      const modal = document.getElementById('taskDetailsModal');
      modal.style.display = 'flex';
    }

    function closeTaskDetailsModal() {
      const modal = document.getElementById('taskDetailsModal');
      if (modal) modal.style.display = 'none';
    }

    function openCreateTaskModal() {
      const modal = document.getElementById('createTaskModal');
      if (modal) {
        clearSelectedBooking();
        clearSelectedUser();
        const titleInp = document.getElementById('taskTitleInput');
        if (titleInp) titleInp.value = '';
        const notesInp = document.getElementById('taskNotesInput');
        if (notesInp) notesInp.value = '';
        modal.style.display = 'flex';
      }
    }

    function closeCreateTaskModal() {
      const modal = document.getElementById('createTaskModal');
      if (modal) modal.style.display = 'none';
      const bMenu = document.getElementById('bookingDropdownMenu');
      const uMenu = document.getElementById('userDropdownMenu');
      if (bMenu) bMenu.style.display = 'none';
      if (uMenu) uMenu.style.display = 'none';
    }

    function openEditTaskModal(task) {
      const modal = document.getElementById('editTaskModal');
      const form = document.getElementById('editTaskForm');
      if (!modal || !form) return;

      form.action = task.update_url;
      document.getElementById('editTaskTitle').value = task.title || '';
      document.getElementById('editTaskPriority').value = task.priority || 'normal';
      document.getElementById('editTaskStatus').value = task.status || 'pending';
      document.getElementById('editTaskDueAt').value = task.due_at_input || task.due_at || '';
      document.getElementById('editTaskNotes').value = task.notes || '';

      const linkedInfo = document.getElementById('editTaskLinkedInfo');
      if (task.booking_label || task.user_label) {
        linkedInfo.style.display = 'block';
        linkedInfo.innerHTML = `<strong>Linked to:</strong> ${task.booking_label || task.user_label}`;
      } else {
        linkedInfo.style.display = 'none';
        linkedInfo.innerHTML = '';
      }

      modal.style.display = 'flex';
    }

    function closeEditTaskModal() {
      const modal = document.getElementById('editTaskModal');
      if (modal) modal.style.display = 'none';
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
      initEarningSplineChart({{ $currentMonth }});
      initSalesAnalyticsDonut();
      initUnifiedBarChart();

      const modal = document.getElementById('dateToDateModal');
      if (modal) {
        modal.addEventListener('click', (e) => {
          if (e.target === modal) closeDateModal();
        });
      }

      const taskModal = document.getElementById('createTaskModal');
      if (taskModal) {
        taskModal.addEventListener('click', (e) => {
          if (e.target === taskModal) closeCreateTaskModal();
        });
      }

      const editModal = document.getElementById('editTaskModal');
      if (editModal) {
        editModal.addEventListener('click', (e) => {
          if (e.target === editModal) closeEditTaskModal();
        });
      }

      // Close dropdowns on outside click
      document.addEventListener('click', (e) => {
        const bTrigger = document.getElementById('bookingDropdownTrigger');
        const bMenu = document.getElementById('bookingDropdownMenu');
        if (bMenu && bMenu.style.display === 'flex' && !bMenu.contains(e.target) && !bTrigger.contains(e.target)) {
          bMenu.style.display = 'none';
        }

        const uTrigger = document.getElementById('userDropdownTrigger');
        const uMenu = document.getElementById('userDropdownMenu');
        if (uMenu && uMenu.style.display === 'flex' && !uMenu.contains(e.target) && !uTrigger.contains(e.target)) {
          uMenu.style.display = 'none';
        }
      });
    });
  </script>
</x-layouts.admin>
