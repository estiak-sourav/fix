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
      <div id="unreadAlertsCount" style="font-size:1.6rem; font-weight:500; color:#dc2626; margin-top:4px; line-height:1.2;">
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
            $isPrice = $n->type === 'price_updated';
            $b = $n->booking;
          @endphp
          <div 
            class="notification-item-card"
            data-id="{{ $n->id }}"
            data-read-url="{{ route('admin.notifications.read', $n) }}"
            data-is-read="{{ $n->is_read ? '1' : '0' }}"
            data-title="{{ $n->title }}"
            data-message="{{ $n->message }}"
            data-type="{{ $n->type }}"
            data-type-label="{{ ucwords(str_replace('_', ' ', $n->type)) }}"
            data-created-human="{{ $n->created_at->diffForHumans() }}"
            data-created-formatted="{{ $n->created_at->format('M d, Y • h:i:s A') }}"
            data-action-url="{{ $n->action_url ?: ($n->booking_id ? route('admin.bookings.show', $n->booking_id) : '') }}"
            data-has-booking="{{ $b ? '1' : '0' }}"
            @if($b)
              data-booking-number="#{{ $b->booking_number }}"
              data-booking-customer="{{ $b->customer_name }}"
              data-booking-phone="{{ $b->customer_phone }}"
              data-booking-location="{{ $b->area }}, {{ $b->district }}"
              data-booking-service="{{ $b->service?->title ?? $b->amcPlan?->name ?? 'Facility Service' }}"
              data-booking-status="{{ $b->status_label }}"
              data-booking-price="{{ $b->final_cost > 0 ? '৳ ' . number_format($b->final_cost, 2) : ($b->estimated_cost > 0 ? '৳ ' . number_format($b->estimated_cost, 2) : 'Pending / Set Later') }}"
              data-booking-url="{{ route('admin.bookings.show', $b) }}"
            @endif
            onclick="handleNotificationClick(this, event)"
            style="
              display:flex;
              align-items:flex-start;
              justify-content:space-between;
              gap:16px;
              padding:14px 18px;
              border-radius:var(--paces-radius-sm);
              border: 1px solid {{ !$n->is_read ? '#fecaca' : 'var(--paces-border)' }};
              background: {{ !$n->is_read ? '#fef2f2' : '#ffffff' }};
              border-left: 4px solid {{ !$n->is_read ? '#ef4444' : ($isEmergency ? 'var(--paces-danger)' : ($isBooking ? 'var(--paces-primary)' : ($isPrice ? '#10b981' : '#94a3b8'))) }};
              cursor: pointer;
              transition: all 0.2s ease;
            "
            onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'; this.style.transform='translateY(-1px)';"
            onmouseout="this.style.boxShadow='none'; this.style.transform='none';"
          >
            <div style="display:flex; gap:14px; align-items:flex-start; flex:1; min-width:0;">
              <!-- Message Details -->
              <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                  <strong style="font-size:0.92rem; color:var(--paces-text-dark); font-weight:500;">{{ $n->title }}</strong>
                  
                  @if(!$n->is_read)
                    <span class="badge-pill-paces notif-unread-pill" style="background:#fee2e2; color:#b91c1c; font-size:0.68rem; padding:2px 8px; font-weight:500;">
                      UNREAD
                    </span>
                  @endif

                  @if($isEmergency)
                    <span class="badge-pill-paces bg-danger-subtle" style="font-size:0.68rem; padding:2px 8px;">URGENT</span>
                  @elseif($isPrice)
                    <span class="badge-pill-paces bg-success-subtle" style="font-size:0.68rem; padding:2px 8px;">PRICE</span>
                  @endif

                  @if($n->booking)
                    <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.72rem; text-decoration:none; font-family:monospace;">
                      #{{ $n->booking->booking_number }}
                    </span>
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
                <a href="{{ route('admin.bookings.show', $n->booking_id) }}" onclick="event.stopPropagation();" class="btn-paces btn-paces-primary btn-paces-sm" style="white-space:nowrap; display:inline-flex; align-items:center;">
                  View Order →
                </a>
              @endif

              <form action="{{ route('admin.notifications.destroy', $n) }}" method="POST" onclick="event.stopPropagation();" data-confirm="Are you sure you want to permanently delete this notification?" data-confirm-title="Delete Notification?" data-confirm-btn="Delete" style="margin:0;">
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

  <!-- Notification Details Modal Popup -->
  <div id="notificationDetailsModal" class="modal-backdrop-paces" style="display:none;" onclick="if(event.target===this) closeNotificationModal();">
    <div class="modal-dialog-paces" style="max-width:540px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);" onclick="event.stopPropagation();">
      <div class="modal-header-paces" style="background:#f8fafc; border-bottom:1px solid var(--paces-border); padding:16px 20px;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div style="width:36px; height:36px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); display:flex; align-items:center; justify-content:center; font-size:1.1rem;" id="modalNotifIcon">
            🔔
          </div>
          <div>
            <h3 class="modal-title-paces" id="modalNotifTitle" style="font-size:1.05rem; font-weight:600; color:var(--paces-text-dark); line-height:1.3;">
              Notification Details
            </h3>
            <div style="display:flex; align-items:center; gap:8px; margin-top:3px;">
              <span id="modalNotifTypeBadge" class="badge-pill-paces bg-primary-subtle" style="font-size:0.7rem; font-weight:600;">Alert</span>
              <span id="modalNotifTime" style="font-size:0.75rem; color:var(--paces-text-muted);">Just now</span>
            </div>
          </div>
        </div>
        <button type="button" class="modal-close-btn" onclick="closeNotificationModal()" aria-label="Close" style="font-size:1.4rem; line-height:1; color:#94a3b8; cursor:pointer; background:none; border:none;">
          &times;
        </button>
      </div>

      <div class="modal-body-paces" style="padding:20px; overflow-y:auto; max-height:calc(85vh - 130px);">
        <!-- Full Message Description -->
        <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:8px; padding:14px 16px; margin-bottom:16px;">
          <label style="font-size:0.75rem; font-weight:600; text-transform:uppercase; color:var(--paces-text-muted); display:block; margin-bottom:6px;">
            Notification Message
          </label>
          <p id="modalNotifMessage" style="font-size:0.92rem; color:#334155; line-height:1.6; margin:0; white-space:pre-wrap;">
            Message body
          </p>
        </div>

        <!-- Linked Work Order Details (if present) -->
        <div id="modalBookingSection" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px 16px; margin-bottom:16px;">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
            <div style="display:flex; align-items:center; gap:6px;">
              <span style="font-size:0.9rem;">📋</span>
              <strong style="font-size:0.85rem; color:var(--paces-text-dark);">Linked Work Order</strong>
              <span id="modalBookingNumber" style="font-family:monospace; font-weight:700; color:var(--paces-primary); font-size:0.85rem;">#DHK-00000</span>
            </div>
            <span id="modalBookingStatus" class="badge-pill-paces bg-info-subtle" style="font-size:0.72rem; font-weight:600;">Status</span>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:0.82rem;">
            <div>
              <span style="color:var(--paces-text-muted); display:block; font-size:0.72rem; text-transform:uppercase;">Service:</span>
              <strong id="modalBookingService" style="color:var(--paces-text-dark); display:block; margin-top:1px;">General Service</strong>
            </div>
            <div>
              <span style="color:var(--paces-text-muted); display:block; font-size:0.72rem; text-transform:uppercase;">Agreed / Est. Price:</span>
              <strong id="modalBookingPrice" style="color:#059669; display:block; margin-top:1px;">৳ 0.00</strong>
            </div>
            <div>
              <span style="color:var(--paces-text-muted); display:block; font-size:0.72rem; text-transform:uppercase;">Customer:</span>
              <span id="modalBookingCustomer" style="color:var(--paces-text-dark); font-weight:500;">Client Name</span>
            </div>
            <div>
              <span style="color:var(--paces-text-muted); display:block; font-size:0.72rem; text-transform:uppercase;">Phone:</span>
              <a id="modalBookingPhone" href="tel:" style="color:var(--paces-primary); font-weight:600; text-decoration:none;">01700000000</a>
            </div>
            <div style="grid-column:1 / -1;">
              <span style="color:var(--paces-text-muted); display:block; font-size:0.72rem; text-transform:uppercase;">Location Address:</span>
              <span id="modalBookingLocation" style="color:var(--paces-text-dark);">Area, District</span>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer-paces" style="background:#f8fafc; border-top:1px solid var(--paces-border); padding:12px 20px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
        <span style="font-size:0.75rem; color:#16a34a; font-weight:500; display:inline-flex; align-items:center; gap:4px;">
          ✓ Marked as Read
        </span>

        <div style="display:flex; gap:8px;">
          <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="closeNotificationModal()">
            Close
          </button>
          <a id="modalActionBtn" href="#" class="btn-paces btn-paces-primary btn-paces-sm" style="display:none; text-decoration:none;">
            View Work Order Details →
          </a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function handleNotificationClick(card, event) {
      if (!card) return;

      const notifId = card.dataset.id;
      const readUrl = card.dataset.readUrl;
      const isRead = card.dataset.isRead === '1';

      // 1. Mark as read in database if unread
      if (!isRead) {
        card.dataset.isRead = '1';
        card.style.background = '#ffffff';
        card.style.borderColor = 'var(--paces-border)';
        
        // Remove unread pill
        const unreadPill = card.querySelector('.notif-unread-pill');
        if (unreadPill) unreadPill.remove();

        // Decrement unread counter on index page
        const unreadMetric = document.getElementById('unreadAlertsCount');
        if (unreadMetric) {
          let cur = parseInt(unreadMetric.innerText.replace(/[^0-9]/g, ''), 10) || 0;
          if (cur > 0) unreadMetric.innerText = cur - 1;
        }

        // Decrement topbar badge
        const topbarBadge = document.getElementById('topbarAlertBadge');
        if (topbarBadge) {
          let cur = parseInt(topbarBadge.innerText.replace(/[^0-9]/g, ''), 10) || 0;
          if (cur > 0) topbarBadge.innerText = cur - 1;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (readUrl && csrfToken) {
          fetch(readUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            }
          }).catch(() => {});
        }
      }

      // 2. Populate modal details
      const title = card.dataset.title || 'Notification Details';
      const msg = card.dataset.message || '';
      const type = card.dataset.type || '';
      const typeLabel = card.dataset.typeLabel || 'Alert';
      const timeHuman = card.dataset.createdHuman || '';
      const timeFormatted = card.dataset.createdFormatted || '';
      const actionUrl = card.dataset.actionUrl || '';
      const hasBooking = card.dataset.hasBooking === '1';

      document.getElementById('modalNotifTitle').innerText = title;
      document.getElementById('modalNotifMessage').innerText = msg;
      document.getElementById('modalNotifTime').innerText = `${timeHuman} • ${timeFormatted}`;

      const typeBadge = document.getElementById('modalNotifTypeBadge');
      typeBadge.innerText = typeLabel;
      if (type === 'emergency_alert') {
        typeBadge.className = 'badge-pill-paces bg-danger-subtle';
        document.getElementById('modalNotifIcon').innerText = '🚨';
        document.getElementById('modalNotifIcon').style.background = '#fee2e2';
        document.getElementById('modalNotifIcon').style.color = '#dc2626';
      } else if (type === 'price_updated') {
        typeBadge.className = 'badge-pill-paces bg-success-subtle';
        document.getElementById('modalNotifIcon').innerText = '💰';
        document.getElementById('modalNotifIcon').style.background = '#dcfce7';
        document.getElementById('modalNotifIcon').style.color = '#15803d';
      } else {
        typeBadge.className = 'badge-pill-paces bg-primary-subtle';
        document.getElementById('modalNotifIcon').innerText = '🔔';
        document.getElementById('modalNotifIcon').style.background = 'var(--paces-primary-subtle)';
        document.getElementById('modalNotifIcon').style.color = 'var(--paces-primary)';
      }

      const bookingSec = document.getElementById('modalBookingSection');
      if (hasBooking) {
        bookingSec.style.display = 'block';
        document.getElementById('modalBookingNumber').innerText = card.dataset.bookingNumber || '';
        document.getElementById('modalBookingService').innerText = card.dataset.bookingService || '';
        document.getElementById('modalBookingPrice').innerText = card.dataset.bookingPrice || '';
        document.getElementById('modalBookingCustomer').innerText = card.dataset.bookingCustomer || '';
        document.getElementById('modalBookingLocation').innerText = card.dataset.bookingLocation || '';
        
        const phoneEl = document.getElementById('modalBookingPhone');
        phoneEl.innerText = card.dataset.bookingPhone || '';
        phoneEl.href = 'tel:' + (card.dataset.bookingPhone || '');

        document.getElementById('modalBookingStatus').innerText = card.dataset.bookingStatus || '';
      } else {
        bookingSec.style.display = 'none';
      }

      const actionBtn = document.getElementById('modalActionBtn');
      if (actionUrl) {
        actionBtn.style.display = 'inline-block';
        actionBtn.href = actionUrl;
        actionBtn.innerText = hasBooking ? 'View Work Order Details →' : 'Open Link →';
      } else {
        actionBtn.style.display = 'none';
      }

      // 3. Show modal
      const modal = document.getElementById('notificationDetailsModal');
      if (modal) modal.style.display = 'flex';
    }

    function closeNotificationModal() {
      const modal = document.getElementById('notificationDetailsModal');
      if (modal) modal.style.display = 'none';
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeNotificationModal();
    });
  </script>
</x-layouts.admin>
