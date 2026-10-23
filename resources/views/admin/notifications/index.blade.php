<x-layouts.admin title="Notifications & Dispatch Alerts | FIX Operations">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Operations Notifications & Dispatch Alerts</h1>
      <p class="page-header-subtitle">Real-time alerts, critical dispatch updates, and customer request stream</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      @if($stats['unread_alerts'] > 0)
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Mark All as Read ({{ $stats['unread_alerts'] }})
          </button>
        </form>
      @endif

      <form action="{{ route('admin.notifications.clear-read') }}" method="POST" onsubmit="return confirm('Clear all resolved/read notifications?');" style="margin:0;">
        @csrf
        <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
          Clear Read History
        </button>
      </form>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <!-- Metrics Grid -->
  <div class="metrics-grid-paces">
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Total Alert Feed</span>
        <div class="metric-icon-badge bg-primary-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        </div>
      </div>
      <div class="metric-value">{{ $stats['total_alerts'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-text-muted);">
        All recorded inbound events
      </div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Unread Alerts</span>
        <div class="metric-icon-badge bg-warning-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-warning);">{{ $stats['unread_alerts'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-warning); font-weight:500;">
        Requires operator review
      </div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Emergency Requests</span>
        <div class="metric-icon-badge bg-danger-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-danger);">{{ $stats['emergency_alerts'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-danger); font-weight:500;">
        High-priority SLA dispatch
      </div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Work Order Inbound</span>
        <div class="metric-icon-badge bg-info-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-info);">{{ $stats['booking_alerts'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-text-muted);">
        Standard client bookings
      </div>
    </div>
  </div>

  <!-- Filter and Search Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:14px;">
      <!-- Filter status tabs -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.notifications.index') }}" class="btn-paces {{ (!request('status') && !request('type')) ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All Alerts ({{ $stats['total_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['status' => 'unread']) }}" class="btn-paces {{ request('status') === 'unread' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Unread ({{ $stats['unread_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'emergency_alert']) }}" class="btn-paces {{ request('type') === 'emergency_alert' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          🚨 Emergency ({{ $stats['emergency_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'new_booking']) }}" class="btn-paces {{ request('type') === 'new_booking' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          📦 New Bookings ({{ $stats['booking_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['status' => 'read']) }}" class="btn-paces {{ request('status') === 'read' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Resolved History
        </a>
      </div>

      <!-- Search Input -->
      <form action="{{ route('admin.notifications.index') }}" method="GET" style="display:flex; gap:8px;">
        @if(request('status'))
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        @if(request('type'))
          <input type="hidden" name="type" value="{{ request('type') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" class="topbar-search-input" placeholder="Search notification title or body..." style="width:260px;">
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Search</button>
      </form>
    </div>

    <!-- Notification Feed List -->
    @if($notifications->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">🔔</div>
        <h4 style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Notifications Found</h4>
        <p style="font-size:0.85rem; max-width:420px; margin:0 auto;">All caught up! There are no operational alerts matching your current filter.</p>
      </div>
    @else
      <div style="display:flex; flex-direction:column; gap:12px;">
        @foreach($notifications as $n)
          @php
            $isEmergency = $n->type === 'emergency_alert';
            $isBooking = $n->type === 'new_booking';
          @endphp
          <div style="
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:16px;
            padding:16px 20px;
            border-radius:var(--paces-radius-sm);
            border: 1px solid {{ !$n->is_read ? ($isEmergency ? '#fecaca' : '#c7d2fe') : 'var(--paces-border)' }};
            background: {{ !$n->is_read ? ($isEmergency ? '#fff5f5' : '#f8faff') : '#ffffff' }};
            border-left: 5px solid {{ $isEmergency ? 'var(--paces-danger)' : ($isBooking ? 'var(--paces-primary)' : '#94a3b8') }};
            transition: all 0.2s ease;
          ">
            <div style="display:flex; gap:14px; align-items:flex-start; flex:1; min-width:0;">
              <!-- Type Icon -->
              <div style="
                width:40px;
                height:40px;
                border-radius:10px;
                display:flex;
                align-items:center;
                justify-content:center;
                flex-shrink:0;
                background:{{ $isEmergency ? 'var(--paces-danger-subtle)' : ($isBooking ? 'var(--paces-primary-subtle)' : '#f1f5f9') }};
                color:{{ $isEmergency ? 'var(--paces-danger)' : ($isBooking ? 'var(--paces-primary)' : '#64748b') }};
              ">
                @if($isEmergency)
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                @elseif($isBooking)
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                @else
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                @endif
              </div>

              <!-- Message Details -->
              <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px; flex-wrap:wrap;">
                  <strong style="font-size:0.95rem; color:var(--paces-text-dark);">{{ $n->title }}</strong>
                  
                  @if(!$n->is_read)
                    <span class="badge-pill-paces bg-warning-subtle" style="font-size:0.68rem; padding:2px 8px;">UNREAD</span>
                  @endif

                  @if($isEmergency)
                    <span class="badge-pill-paces bg-danger-subtle" style="font-size:0.68rem; padding:2px 8px;">URGENT</span>
                  @endif

                  @if($n->booking)
                    <a href="{{ route('admin.bookings.show', $n->booking_id) }}" class="badge-pill-paces bg-primary-subtle" style="font-size:0.72rem; text-decoration:none; font-family:monospace;">
                      #{{ $n->booking->booking_number }}
                    </a>
                  @endif
                </div>

                <p style="margin:0 0 6px; font-size:0.875rem; color:#475569; line-height:1.5;">
                  {{ $n->message }}
                </p>

                <div style="display:flex; align-items:center; gap:12px; font-size:0.75rem; color:var(--paces-text-muted);">
                  <span>⏱ {{ $n->created_at->diffForHumans() }}</span>
                  <span>•</span>
                  <span>{{ $n->created_at->format('M d, Y • h:i:s A') }}</span>
                  @if($n->booking)
                    <span>•</span>
                    <span>Client: <strong>{{ $n->booking->customer_name }}</strong> ({{ $n->booking->customer_phone }})</span>
                  @endif
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div style="display:flex; gap:8px; align-items:center; flex-shrink:0;">
              @if($n->booking_id)
                <a href="{{ route('admin.bookings.show', $n->booking_id) }}" class="btn-paces btn-paces-primary btn-paces-sm">
                  View Order
                </a>
              @endif

              @if(!$n->is_read)
                <form action="{{ route('admin.notifications.read', $n) }}" method="POST" style="margin:0;">
                  @csrf
                  <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" title="Mark as Read">
                    ✓ Read
                  </button>
                </form>
              @endif

              <form action="{{ route('admin.notifications.destroy', $n) }}" method="POST" onsubmit="return confirm('Delete this notification?');" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" title="Delete notification" style="color:#ef4444;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

      <div style="margin-top:1.5rem;">
        {{ $notifications->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
