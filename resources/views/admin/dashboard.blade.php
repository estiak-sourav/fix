<x-layouts.admin title="Operations Dashboard | FIX Admin">
  <!-- Paces Page Header Box -->
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Dashboard</h1>
      <p class="page-header-subtitle">Real-time building facility dispatch, SLA compliance & work orders monitoring</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
      <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>All Work Orders</span>
      </a>
      <a href="{{ route('admin.cms.services') }}" class="btn-paces btn-paces-outline">
        <span>CMS Portfolio</span>
      </a>
    </div>
  </div>

  <!-- Paces 4/5 Metric Stat Cards Strip -->
  <div class="metrics-grid-paces">
    <!-- Card 1: Total Orders -->
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Total Work Orders</span>
        <div class="metric-icon-badge bg-primary-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        </div>
      </div>
      <div class="metric-value">{{ $stats['total_bookings'] }}</div>
      <div class="metric-subtext text-muted">
        <span style="color:#16a34a; font-weight:700;">↑ +18.4%</span>
        <span>vs last month</span>
      </div>
    </div>

    <!-- Card 2: Pending Dispatch -->
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Pending Review</span>
        <div class="metric-icon-badge bg-warning-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
      </div>
      <div class="metric-value" style="color:#d97706;">{{ $stats['pending_review'] }}</div>
      <div class="metric-subtext">
        <span class="badge-pill-paces bg-warning-subtle" style="padding:2px 8px; font-size:0.7rem;">Needs Action</span>
        <span style="color:#64748b;">Awaiting engineer assignment</span>
      </div>
    </div>

    <!-- Card 3: Active On-Site -->
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Active / On-Site</span>
        <div class="metric-icon-badge bg-info-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </div>
      </div>
      <div class="metric-value" style="color:#7c3aed;">{{ $stats['active_in_progress'] }}</div>
      <div class="metric-subtext text-muted">
        <span style="color:#7c3aed; font-weight:700;">● Active</span>
        <span>Field teams deployed</span>
      </div>
    </div>

    <!-- Card 4: Completed Orders -->
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Completed Jobs</span>
        <div class="metric-icon-badge bg-success-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
      </div>
      <div class="metric-value" style="color:#16a34a;">{{ $stats['completed'] }}</div>
      <div class="metric-subtext text-muted">
        <span style="color:#16a34a; font-weight:700;">99.4%</span>
        <span>Client sign-off rating</span>
      </div>
    </div>

    <!-- Card 5: Registered Clients -->
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Corporate Clients</span>
        <div class="metric-icon-badge bg-primary-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
      </div>
      <div class="metric-value">{{ $stats['total_customers'] }}</div>
      <div class="metric-subtext text-muted">
        <span>Verified OTP customer accounts</span>
      </div>
    </div>
  </div>

  <!-- Audio Alert Diagnostic Status Banner -->
  <div style="background:#ffffff; border:1px solid #eaedf1; border-left:4px solid #3e60d5; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.75rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
    <div style="display:flex; align-items:center; gap:12px;">
      <div style="width:36px; height:36px; border-radius:10px; background:#eef2ff; color:#3e60d5; display:flex; align-items:center; justify-content:center; font-size:1.1rem;">
        🔔
      </div>
      <div>
        <div style="font-weight:700; font-size:0.9rem; color:#1e293b;">Real-Time Audio Dispatch Alert System</div>
        <div style="font-size:0.8rem; color:#64748b;">Your browser will play an attention harmonic chime whenever an emergency service request or booking arrives.</div>
      </div>
    </div>
    <button type="button" onclick="playChime()" class="btn-paces btn-paces-outline btn-paces-sm">
      <span>Test Emergency Chime 🔊</span>
    </button>
  </div>

  <!-- Paces Table Card: Recent Inbound Work Orders -->
  <div class="card-paces">
    <div class="card-header-paces">
      <div>
        <h3 class="card-title-paces">Recent Inbound Work Orders</h3>
        <p style="font-size:0.8rem; color:var(--paces-text-muted); margin-top:2px;">Live stream of facility service bookings and maintenance calls</p>
      </div>
      <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          <span>View All ({{ $stats['total_bookings'] }}) →</span>
        </a>
      </div>
    </div>

    @if($recentBookings->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">📋</div>
        <h4 style="font-size:1rem; font-weight:700; color:var(--paces-text-dark); margin-bottom:4px;">No Service Orders Yet</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">When users place emergency or scheduled requests from the main portal, they will appear here in real-time with automatic audio alerts.</p>
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
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentBookings as $b)
              <tr>
                <td>
                  <div style="display:flex; align-items:center; gap:6px;">
                    <strong style="color:var(--paces-primary); font-family:monospace; font-size:0.9rem;">#{{ $b->booking_number }}</strong>
                  </div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">{{ $b->created_at->diffForHumans() }}</div>
                </td>
                <td>
                  <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:#f1f5f9; color:#475569; font-weight:700; font-size:0.8rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                      {{ substr($b->customer_name, 0, 1) }}
                    </div>
                    <div>
                      <div style="font-weight:700; color:var(--paces-text-dark);">{{ $b->customer_name }}</div>
                      <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->customer_phone }}</div>
                    </div>
                  </div>
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
                      <span>Standard Scheduled</span>
                    </span>
                  @endif
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
                      Manage & Dispatch
                    </a>
                    @if($b->status === 'pending_review')
                      <form action="{{ route('admin.bookings.update-status', $b) }}" method="POST" style="margin:0; display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <input type="hidden" name="comment" value="Request accepted by Operations Lead. Field technician dispatching.">
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
</x-layouts.admin>
