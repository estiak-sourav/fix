<x-layouts.admin title="Activity Logs | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <h1 class="page-header-title">Administrative Activity Logs</h1>
    </div>
  </div>

  <!-- Activity Metrics Grid -->
  <div class="metrics-grid-paces">
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Total Logged Activities</span>
        <div class="metric-icon-badge bg-primary-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
      </div>
      <div class="metric-value">{{ number_format($stats['total_activities']) }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Today's Activities</span>
        <div class="metric-icon-badge bg-info-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-info);">{{ number_format($stats['today_activities']) }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Staff & Security Events</span>
        <div class="metric-icon-badge bg-warning-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-warning);">{{ number_format($stats['staff_actions']) }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Work Orders & Tickets</span>
        <div class="metric-icon-badge bg-success-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-success);">{{ number_format($stats['operational_actions']) }}</div>
    </div>
  </div>

  <!-- Activity Logs Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="flex-direction:column; align-items:stretch; gap:16px;">
      <!-- Category Filter Tabs -->
      <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); margin-right:4px;">Category:</span>
        <a href="{{ route('admin.activity-logs.index', request()->except(['category', 'page'])) }}" class="btn-paces {{ !request('category') ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All Events
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'auth'])) }}" class="btn-paces {{ request('category') === 'auth' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          🔑 Authentication
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'staff'])) }}" class="btn-paces {{ request('category') === 'staff' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          🛡️ Staff & Roles
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'bookings'])) }}" class="btn-paces {{ request('category') === 'bookings' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          📦 Work Orders
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'tickets'])) }}" class="btn-paces {{ request('category') === 'tickets' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          💬 Support Desk
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'users'])) }}" class="btn-paces {{ request('category') === 'users' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          👥 Clients
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'services'])) }}" class="btn-paces {{ request('category') === 'services' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          🛠️ Services & AMC
        </a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page'), ['category' => 'settings'])) }}" class="btn-paces {{ request('category') === 'settings' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          ⚙️ Settings
        </a>
      </div>

      <!-- Filters & Search Form -->
      <form action="{{ route('admin.activity-logs.index') }}" method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
        @if(request('category'))
          <input type="hidden" name="category" value="{{ request('category') }}">
        @endif

        <div class="activity-filter-form" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center; width:100%;">
          <!-- Staff Actor Filter -->
          <select name="admin_id" class="form-control-paces" style="padding:6px 12px; font-size:0.85rem; width:200px; max-width:100%;" onchange="this.form.submit()">
            <option value="">All Staff Members</option>
            @foreach($adminUsers as $u)
              <option value="{{ $u->id }}" {{ request('admin_id') == $u->id ? 'selected' : '' }}>
                {{ $u->name }}
              </option>
            @endforeach
          </select>

          <!-- Timeframe Filter -->
          <select name="timeframe" class="form-control-paces" style="padding:6px 12px; font-size:0.85rem; width:160px; max-width:100%;" onchange="this.form.submit()">
            <option value="">Any Time</option>
            <option value="today" {{ request('timeframe') === 'today' ? 'selected' : '' }}>Today</option>
            <option value="yesterday" {{ request('timeframe') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
            <option value="7days" {{ request('timeframe') === '7days' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="30days" {{ request('timeframe') === '30days' ? 'selected' : '' }}>Last 30 Days</option>
          </select>

          <!-- Search Query -->
          <input 
            type="text" 
            name="search" 
            placeholder="Search action, description, IP..." 
            value="{{ request('search') }}" 
            class="form-control-paces" 
            style="padding:6px 12px; font-size:0.85rem; width:240px; max-width:100%;"
          >

          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Filter</button>

          @if(request()->hasAny(['search', 'category', 'admin_id', 'timeframe']))
            <a href="{{ route('admin.activity-logs.index') }}" class="btn-paces btn-paces-outline btn-paces-sm" title="Clear all filters">Clear</a>
          @endif
        </div>

        <div style="font-size:0.8rem; color:var(--paces-text-muted);">
          Showing {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} records
        </div>
      </form>
    </div>

    <!-- Table of Activity Logs -->
    <div style="overflow-x:auto;">
      <table class="table-paces">
        <thead>
          <tr>
            <th style="width:20%;">Actor (Staff Member)</th>
            <th style="width:16%;">Event Category</th>
            <th style="width:36%;">Action & Details</th>
            <th style="width:14%;">Network / Device</th>
            <th style="width:14%; text-align:right;">Timestamp</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
            @php
              $badge = $log->category_badge;
            @endphp
            <tr>
              <!-- Actor -->
              <td style="vertical-align:top;">
                <div style="display:flex; align-items:center; gap:10px;">
                  @if($log->admin?->avatar_url)
                    <img src="{{ $log->admin->avatar_url }}" alt="{{ $log->admin_name }}" style="width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                  @else
                    <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, #3e60d5 0%, #1e293b 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:0.85rem; flex-shrink:0;">
                      {{ strtoupper(substr($log->admin_name ?: 'S', 0, 1)) }}
                    </div>
                  @endif
                  <div>
                    <div style="font-weight:500; color:var(--paces-text); font-size:0.875rem;">
                      {{ $log->admin_name ?: 'System Automation' }}
                    </div>
                    @if($log->admin_email)
                      <div style="font-size:0.75rem; color:var(--paces-text-muted);">
                        {{ $log->admin_email }}
                      </div>
                    @endif
                    @if($log->admin_role)
                      <span style="font-size:0.68rem; color:#64748b; background:#f1f5f9; padding:1px 6px; border-radius:4px; font-weight:500; display:inline-block; margin-top:2px;">
                        {{ ucfirst(str_replace('_', ' ', $log->admin_role)) }}
                      </span>
                    @endif
                  </div>
                </div>
              </td>

              <!-- Category & Action Tag -->
              <td style="vertical-align:top;">
                <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-start;">
                  <span class="badge-paces" style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }}; border:1px solid {{ $badge['border'] }}; font-size:0.75rem;">
                    {{ $badge['label'] }}
                  </span>
                  <code style="font-size:0.72rem; color:#475569; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 5px; border-radius:4px; font-family:monospace;">
                    {{ $log->action }}
                  </code>
                </div>
              </td>

              <!-- Description & Properties Dropdown -->
              <td style="vertical-align:top;">
                <div style="font-size:0.875rem; color:var(--paces-text); line-height:1.5;">
                  {{ $log->description }}
                </div>

                @if(!empty($log->properties))
                  <details style="margin-top:6px; font-size:0.78rem;">
                    <summary style="cursor:pointer; color:var(--paces-primary); font-weight:500; outline:none; user-select:none;">
                      View Event Metadata / Changes
                    </summary>
                    <div style="margin-top:6px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:8px 12px; font-family:monospace; font-size:0.75rem; color:#334155; max-height:200px; overflow-y:auto; white-space:pre-wrap;">
{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                    </div>
                  </details>
                @endif
              </td>

              <!-- IP & User Agent -->
              <td style="vertical-align:top;">
                <div style="font-size:0.8rem; color:var(--paces-text); font-weight:500; font-family:monospace;">
                  {{ $log->ip_address ?: '127.0.0.1' }}
                </div>
                @if($log->user_agent)
                  <div style="font-size:0.72rem; color:var(--paces-text-muted); margin-top:3px; word-break:break-word; line-height:1.35;" title="{{ $log->user_agent }}">
                    {{ $log->user_agent }}
                  </div>
                @endif
              </td>

              <!-- Timestamp -->
              <td style="vertical-align:top; text-align:right;">
                <div style="font-weight:500; font-size:0.825rem; color:var(--paces-text);">
                  {{ $log->created_at->diffForHumans() }}
                </div>
                <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">
                  {{ $log->created_at->format('M d, Y • h:i A') }}
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center; padding:3rem; color:var(--paces-text-muted);">
                <div style="font-size:2rem; margin-bottom:8px;">📜</div>
                <div style="font-weight:500; color:var(--paces-text-dark); font-size:1rem; margin-bottom:4px;">No Activity Logs Found</div>
                <p style="font-size:0.85rem; margin:0;">No actions recorded matching the current filter criteria.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($logs->hasPages())
      <div style="padding:1rem 1.5rem; border-top:1px solid var(--paces-border);">
        {{ $logs->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
