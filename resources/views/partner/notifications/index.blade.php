<x-partner.layouts.app :title="'Notifications'">
  <style>
    .partner-notif-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 14px;
      padding: 16px 20px;
      border-bottom: 1px solid var(--p-border);
      color: inherit;
      cursor: pointer;
      transition: background 0.15s ease;
      user-select: none;
    }
    .partner-notif-row:hover {
      background: #f1f5f9 !important;
    }
    .partner-notif-row:active {
      background: #e2e8f0 !important;
    }
    @keyframes modalPopIn {
      from { opacity: 0; transform: scale(0.96); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>

  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em; margin: 0;">
          Notifications
        </h1>
        @if($unreadCount > 0)
          <span id="topUnreadBadge" style="background: #ef4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 2px 8px; border-radius: 9999px; line-height: 1.4;">
            {{ $unreadCount }} New
          </span>
        @endif
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        @if($unreadCount > 0)
          <form id="markAllReadForm" action="{{ route('partner.notifications.mark-all-read') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="button" class="btn-p btn-p-outline" style="padding: 7px 14px; font-size: 0.82rem;" onclick="confirmDialog({ title: 'Mark All as Read?', text: 'All unread notifications will be marked as read.', type: 'primary', confirmText: 'Mark All Read', formId: 'markAllReadForm' })">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
              <span>Mark all read</span>
            </button>
          </form>
        @endif
      </div>
    </div>

    <!-- Filter Tabs -->
    <div style="display: flex; gap: 8px; margin-top: 14px;">
      <a href="{{ route('partner.notifications.index') }}" class="btn-p {{ $filter === 'all' ? 'btn-p-primary' : 'btn-p-outline' }}" style="padding: 5px 14px; font-size: 0.8rem; border-radius: 20px; text-decoration: none;">
        All ({{ $totalCount }})
      </a>
      <a href="{{ route('partner.notifications.index', ['filter' => 'unread']) }}" class="btn-p {{ $filter === 'unread' ? 'btn-p-primary' : 'btn-p-outline' }}" style="padding: 5px 14px; font-size: 0.8rem; border-radius: 20px; text-decoration: none;">
        Unread (<span id="unreadTabCount">{{ $unreadCount }}</span>)
      </a>
    </div>
  </div>

  <!-- Notification Feed -->
  <div class="p-card" style="padding: 0; overflow: hidden;">
    @if($notifications->isEmpty())
      <div style="text-align: center; padding: 56px 20px; color: var(--p-muted);">
        <div style="width: 54px; height: 54px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: #94a3b8;">
          <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        </div>
        <div style="font-weight: 700; font-size: 0.98rem; color: #334155;">No Notifications</div>
        <p style="font-size: 0.82rem; color: #94a3b8; margin-top: 4px;">You're completely up to date. Service orders and dispatch updates will appear here.</p>
      </div>
    @else
      <div style="display: flex; flex-direction: column;">
        @foreach($notifications as $notif)
          @php
            $badge = $notif->type_badge;
            $isUnread = ! $notif->is_read;
          @endphp
          <div 
            class="partner-notif-row" 
            id="notif-row-{{ $notif->id }}" 
            onclick="openNotifModal({{ $notif->id }})" 
            style="background: {{ $isUnread ? '#f8fafc' : '#ffffff' }}; border-left: {{ $isUnread ? '4px solid var(--p-accent)' : '4px solid transparent' }};"
          >
            <div style="display: flex; align-items: flex-start; gap: 12px; min-width: 0; flex: 1;">
              <!-- Icon -->
              <div style="width: 38px; height: 38px; border-radius: 10px; background: {{ $badge['bg'] }}; border: 1px solid {{ $badge['border'] }}; color: {{ $badge['color'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                @if($notif->type === 'job_assigned')
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @elseif($notif->type === 'job_updated')
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                @elseif($notif->type === 'job_cancelled')
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                @elseif($notif->type === 'account_approved')
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                @else
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                @endif
              </div>

              <!-- Text Details -->
              <div style="min-width: 0; flex: 1;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                  <strong style="font-size: 0.92rem; color: var(--p-primary); font-weight: 700;">{{ $notif->title }}</strong>
                  <span style="font-size: 0.72rem; font-weight: 700; color: {{ $badge['color'] }}; background: {{ $badge['bg'] }}; border: 1px solid {{ $badge['border'] }}; border-radius: 6px; padding: 2px 7px;">
                    {{ $badge['label'] }}
                  </span>
                  @if($isUnread)
                    <span id="notif-dot-{{ $notif->id }}" style="width: 8px; height: 8px; border-radius: 50%; background: var(--p-accent); display: inline-block;"></span>
                  @endif
                </div>

                <p style="font-size: 0.84rem; color: #475569; margin-top: 4px; line-height: 1.45;">
                  {{ $notif->message }}
                </p>

                <div style="font-size: 0.74rem; color: var(--p-muted); margin-top: 6px;">
                  {{ $notif->created_at->diffForHumans() }} • {{ $notif->created_at->format('M d, h:i A') }}
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      @if($notifications->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--p-border);">
          {{ $notifications->links() }}
        </div>
      @endif
    @endif
  </div>

  <!-- ========================================================================= -->
  <!-- NOTIFICATION DETAILS INTERACTIVE POPUP MODAL                              -->
  <!-- ========================================================================= -->
  <div id="notifDetailModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="closeNotifModalOnBackdrop(event)">
    <div style="background:#ffffff; border-radius:14px; max-width:520px; width:100%; max-height:90vh; overflow-y:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border:1px solid #e2e8f0; animation:modalPopIn 0.2s ease;">
      <!-- Modal Header -->
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border-radius:14px 14px 0 0;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div id="mNotifIconBox" style="width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
          </div>
          <div>
            <h3 id="mNotifTitle" style="margin:0; font-size:1.02rem; font-weight:800; color:var(--p-primary);"></h3>
            <span id="mNotifTime" style="font-size:0.75rem; color:var(--p-muted); display:block; margin-top:2px;"></span>
          </div>
        </div>
        <button type="button" onclick="closeNotifModal()" style="background:none; border:none; font-size:1.3rem; color:#94a3b8; cursor:pointer; padding:0 4px; line-height:1;">✕</button>
      </div>

      <!-- Modal Body -->
      <div style="padding:20px;">
        <!-- Type Badge -->
        <div style="margin-bottom:14px;">
          <span id="mNotifBadge" style="font-size:0.75rem; font-weight:700; padding:3px 10px; border-radius:6px; display:inline-block;"></span>
        </div>

        <!-- Notification Message Box -->
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; margin-bottom:16px;">
          <span style="font-size:0.72rem; text-transform:uppercase; color:var(--p-muted); font-weight:700; letter-spacing:0.04em; display:block; margin-bottom:4px;">Notice Details</span>
          <div id="mNotifMessage" style="font-size:0.92rem; color:#1e293b; line-height:1.5; white-space:pre-wrap;"></div>
        </div>

        <!-- Booking Details Section (if attached to an order) -->
        <div id="mNotifBookingSection" style="display:none; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; margin-bottom:16px;">
          <div style="font-size:0.72rem; text-transform:uppercase; color:var(--p-muted); font-weight:700; letter-spacing:0.04em; margin-bottom:8px;">Related Service Order</div>
          <div>
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
              <span id="mBookingNumber" style="font-family:monospace; font-weight:800; color:var(--p-accent); font-size:0.95rem; background:#eff6ff; padding:2px 8px; border-radius:4px;"></span>
              <span id="mBookingStatus" style="font-size:0.72rem; font-weight:700; padding:2px 8px; border-radius:10px;"></span>
            </div>
            <div id="mBookingService" style="font-weight:700; font-size:0.9rem; color:#0f172a; margin-top:6px;"></div>
            <div id="mBookingLocation" style="font-size:0.78rem; color:#64748b; margin-top:2px;"></div>
          </div>

          <!-- Cancellation Notice Alert Box -->
          <div id="mBookingCancelledAlert" style="display:none; margin-top:12px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 12px; font-size:0.8rem; color:#991b1b; line-height:1.45;">
            ✕ <strong>Work Order Cancelled / Recalled:</strong> This work order was cancelled or recalled by dispatch. No further crew action is required.
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding:14px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; border-radius:0 0 14px 14px; gap:10px;">
        <div id="mNotifJobLinkWrap" style="display:none;">
          <a id="mNotifJobLink" href="#" class="btn-p btn-p-primary" style="padding:7px 14px; font-size:0.82rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
            <span>Open Job Details →</span>
          </a>
        </div>
        <button type="button" onclick="closeNotifModal()" class="btn-p btn-p-outline" style="padding:7px 16px; font-size:0.82rem; margin-left:auto;">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- JAVASCRIPT: DATA MAP, MODAL LOGIC & AUTO MARK-READ                       -->
  <!-- ========================================================================= -->
  <script>
    const notificationsData = {
      @foreach($notifications as $notif)
        @php
          $badge = $notif->type_badge;
          $booking = $notif->booking;
          $canView = $booking && (int) $booking->partner_id === (int) $partner->id && $booking->status !== 'cancelled';
          $isCancelled = ($notif->type === 'job_cancelled' || ($booking && $booking->status === 'cancelled'));
        @endphp
        "{{ $notif->id }}": {
          id: {{ $notif->id }},
          title: "{{ addslashes($notif->title) }}",
          type: "{{ $notif->type }}",
          badge_label: "{{ addslashes($badge['label']) }}",
          badge_bg: "{{ $badge['bg'] }}",
          badge_color: "{{ $badge['color'] }}",
          badge_border: "{{ $badge['border'] }}",
          message: "{{ addslashes($notif->message) }}",
          time: "{{ $notif->created_at->diffForHumans() }} • {{ $notif->created_at->format('M d, Y • h:i A') }}",
          is_read: {{ $notif->is_read ? 'true' : 'false' }},
          read_url: "{{ route('partner.notifications.read', $notif) }}",
          has_booking: {{ $booking ? 'true' : 'false' }},
          booking_number: "{{ $booking?->booking_number ?? '' }}",
          booking_service: "{{ addslashes($booking?->service?->title ?? ($booking?->amcPlan?->name ?? 'Facility Service')) }}",
          booking_location: "{{ addslashes(($booking?->area ? $booking->area . ', ' : '') . ($booking?->district ?? '')) }}",
          booking_status: "{{ $booking?->status_label ?? '' }}",
          booking_status_color: "{{ $booking?->status_badge_color ?? '#64748b' }}",
          can_view_job: {{ $canView ? 'true' : 'false' }},
          job_url: "{{ $booking ? route('partner.jobs.show', $booking) : '' }}",
          is_cancelled: {{ $isCancelled ? 'true' : 'false' }}
        },
      @endforeach
    };

    function getIconSvg(type) {
      if (type === 'job_assigned') {
        return '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
      } else if (type === 'job_updated') {
        return '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
      } else if (type === 'job_cancelled') {
        return '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
      } else if (type === 'account_approved') {
        return '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
      } else {
        return '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>';
      }
    }

    function openNotifModal(id) {
      const data = notificationsData[id];
      if (!data) return;

      document.getElementById('mNotifTitle').textContent = data.title;
      document.getElementById('mNotifTime').textContent = data.time;
      document.getElementById('mNotifMessage').textContent = data.message;

      const badgeEl = document.getElementById('mNotifBadge');
      badgeEl.textContent = data.badge_label;
      badgeEl.style.color = data.badge_color;
      badgeEl.style.background = data.badge_bg;
      badgeEl.style.border = '1px solid ' + data.badge_border;

      const iconBox = document.getElementById('mNotifIconBox');
      iconBox.style.background = data.badge_bg;
      iconBox.style.border = '1px solid ' + data.badge_border;
      iconBox.style.color = data.badge_color;
      iconBox.innerHTML = getIconSvg(data.type);

      const bookingSec = document.getElementById('mNotifBookingSection');
      const jobLinkWrap = document.getElementById('mNotifJobLinkWrap');
      const cancelledAlert = document.getElementById('mBookingCancelledAlert');

      if (data.has_booking) {
        bookingSec.style.display = 'block';
        document.getElementById('mBookingNumber').textContent = '#' + data.booking_number;
        document.getElementById('mBookingService').textContent = data.booking_service;
        document.getElementById('mBookingLocation').textContent = data.booking_location ? '📍 ' + data.booking_location : '';

        const bStatus = document.getElementById('mBookingStatus');
        bStatus.textContent = data.booking_status;
        bStatus.style.color = data.booking_status_color;
        bStatus.style.background = data.booking_status_color + '22';
        bStatus.style.border = '1px solid ' + data.booking_status_color + '44';

        if (data.is_cancelled) {
          cancelledAlert.style.display = 'block';
        } else {
          cancelledAlert.style.display = 'none';
        }

        if (data.can_view_job) {
          jobLinkWrap.style.display = 'block';
          document.getElementById('mNotifJobLink').href = data.job_url;
        } else {
          jobLinkWrap.style.display = 'none';
        }
      } else {
        bookingSec.style.display = 'none';
        jobLinkWrap.style.display = 'none';
      }

      const modal = document.getElementById('notifDetailModal');
      if (modal) modal.style.display = 'flex';

      // Auto mark-as-read when opened
      if (!data.is_read) {
        data.is_read = true;

        const row = document.getElementById('notif-row-' + id);
        if (row) {
          row.style.background = '#ffffff';
          row.style.borderLeft = '4px solid transparent';
        }

        const dot = document.getElementById('notif-dot-' + id);
        if (dot) dot.remove();

        decrementCounters();

        fetch(data.read_url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        }).then(res => res.json()).then(resData => {
          if (resData && typeof resData.unread_count !== 'undefined') {
            syncCounters(resData.unread_count);
          }
        }).catch(err => {
          console.error('Failed to mark notification as read:', err);
        });
      }
    }

    function decrementCounters() {
      const topBadge = document.getElementById('topUnreadBadge');
      if (topBadge) {
        let count = parseInt(topBadge.textContent) || 0;
        count = Math.max(0, count - 1);
        if (count > 0) {
          topBadge.textContent = count + ' New';
        } else {
          topBadge.remove();
        }
      }

      const tabCount = document.getElementById('unreadTabCount');
      if (tabCount) {
        let count = parseInt(tabCount.textContent) || 0;
        tabCount.textContent = Math.max(0, count - 1);
      }

      const navBadges = document.querySelectorAll('.p-notif-badge');
      navBadges.forEach(badge => {
        let count = parseInt(badge.textContent) || 0;
        count = Math.max(0, count - 1);
        if (count > 0) {
          badge.textContent = count > 99 ? '99+' : count;
        } else {
          badge.remove();
        }
      });
    }

    function syncCounters(count) {
      const topBadge = document.getElementById('topUnreadBadge');
      if (count <= 0) {
        if (topBadge) topBadge.remove();
      } else if (topBadge) {
        topBadge.textContent = count + ' New';
      }

      const tabCount = document.getElementById('unreadTabCount');
      if (tabCount) {
        tabCount.textContent = count;
      }

      const navBadges = document.querySelectorAll('.p-notif-badge');
      navBadges.forEach(badge => {
        if (count <= 0) {
          badge.remove();
        } else {
          badge.textContent = count > 99 ? '99+' : count;
        }
      });
    }

    function closeNotifModal() {
      const modal = document.getElementById('notifDetailModal');
      if (modal) modal.style.display = 'none';
    }

    function closeNotifModalOnBackdrop(e) {
      if (e.target.id === 'notifDetailModal') {
        closeNotifModal();
      }
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeNotifModal();
      }
    });
  </script>
</x-partner.layouts.app>
