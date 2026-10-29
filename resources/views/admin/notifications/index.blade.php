<x-layouts.admin title="Notifications | FIX Operations">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Operations Notifications & Dispatch Alerts</h1>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      @if($stats['unread_alerts'] > 0)
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">
            Mark All as Read ({{ $stats['unread_alerts'] }})
          </button>
        </form>
      @endif

      <form action="{{ route('admin.notifications.clear-read') }}" method="POST" data-confirm="Are you sure you want to clear all read notifications from your history?" data-confirm-title="Clear Read History?" data-confirm-btn="Clear Notifications" style="margin:0;">
        @csrf
        <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm">
          Clear Read History
        </button>
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

  <!-- Compact Metrics Grid (Title on top, Counter below, Subtext removed, Reduced height) -->
  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:1.5rem;">
    <!-- Total Alert Feed -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Total Alert Feed</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:var(--paces-text-dark); margin-top:4px; line-height:1.2;">
        {{ $stats['total_alerts'] }}
      </div>
    </div>

    <!-- Unread Alerts -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px; border-left:3px solid #ef4444;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:#b91c1c; text-transform:uppercase;">Unread Alerts</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:#dc2626; margin-top:4px; line-height:1.2;">
        {{ $stats['unread_alerts'] }}
      </div>
    </div>

    <!-- Emergency Requests -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px; border-left:3px solid #dc2626;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:#dc2626; text-transform:uppercase;">Emergency Requests</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:#dc2626; margin-top:4px; line-height:1.2;">
        {{ $stats['emergency_alerts'] }}
      </div>
    </div>

    <!-- Work Order Inbound -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px; border-left:3px solid var(--paces-primary);">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Work Order Inbound</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:var(--paces-primary); margin-top:4px; line-height:1.2;">
        {{ $stats['booking_alerts'] }}
      </div>
    </div>
  </div>

  <!-- Filter and Search Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:14px;">
      <!-- Filter status tabs (Icons removed) -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.notifications.index') }}" class="btn-paces {{ (!request('status') && !request('type')) ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All Alerts ({{ $stats['total_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['status' => 'unread']) }}" class="btn-paces {{ request('status') === 'unread' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Unread ({{ $stats['unread_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'emergency_alert']) }}" class="btn-paces {{ request('type') === 'emergency_alert' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Emergency ({{ $stats['emergency_alerts'] }})
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'new_booking']) }}" class="btn-paces {{ request('type') === 'new_booking' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          New Bookings ({{ $stats['booking_alerts'] }})
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

    <!-- Notification Feed List (Unread marked with light red) -->
    @if($notifications->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">🔔</div>
        <h4 style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Notifications Found</h4>
        <p style="font-size:0.85rem; max-width:420px; margin:0 auto;">All caught up! There are no operational alerts matching your current filter.</p>
      </div>
    @else
      <div style="display:flex; flex-direction:column; gap:10px; padding:1rem 1.5rem;">
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
            padding:14px 18px;
            border-radius:var(--paces-radius-sm);
            border: 1px solid {{ !$n->is_read ? '#fecaca' : 'var(--paces-border)' }};
            background: {{ !$n->is_read ? '#fef2f2' : '#ffffff' }};
            border-left: 4px solid {{ !$n->is_read ? '#ef4444' : ($isEmergency ? 'var(--paces-danger)' : ($isBooking ? 'var(--paces-primary)' : '#94a3b8')) }};
            transition: all 0.2s ease;
          ">
            <div style="display:flex; gap:14px; align-items:flex-start; flex:1; min-width:0;">
              <!-- Message Details -->
              <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                  <strong style="font-size:0.92rem; color:var(--paces-text-dark); font-weight:500;">{{ $n->title }}</strong>
                  
                  @if(!$n->is_read)
                    <span class="badge-pill-paces" style="background:#fee2e2; color:#b91c1c; font-size:0.68rem; padding:2px 8px; font-weight:500;">
                      UNREAD
                    </span>
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

              <form action="{{ route('admin.notifications.destroy', $n) }}" method="POST" data-confirm="Are you sure you want to permanently delete this notification?" data-confirm-title="Delete Notification?" data-confirm-btn="Delete" style="margin:0;">
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
