<x-partner.layouts.app :title="'Notifications'">
  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em; margin: 0;">
          Notifications
        </h1>
        @if($unreadCount > 0)
          <span style="background: #ef4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 2px 8px; border-radius: 9999px; line-height: 1.4;">
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
        Unread ({{ $unreadCount }})
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
          <div style="padding: 16px 20px; border-bottom: 1px solid var(--p-border); display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; background: {{ $isUnread ? '#f8fafc' : '#ffffff' }}; border-left: {{ $isUnread ? '4px solid var(--p-accent)' : '4px solid transparent' }}; transition: background 0.15s ease;">
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
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--p-accent); display: inline-block;"></span>
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

            <!-- Actions -->
            <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
              @if($notif->action_url)
                <form action="{{ route('partner.notifications.read', $notif) }}" method="POST" style="margin: 0;">
                  @csrf
                  <input type="hidden" name="redirect" value="1">
                  <button type="submit" class="btn-p btn-p-primary" style="padding: 6px 12px; font-size: 0.78rem;">
                    <span>View</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                  </button>
                </form>
              @endif

              @if($isUnread)
                <form action="{{ route('partner.notifications.read', $notif) }}" method="POST" style="margin: 0;">
                  @csrf
                  <button type="submit" class="btn-p btn-p-outline" style="padding: 6px 10px; font-size: 0.76rem;" title="Mark as read">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  </button>
                </form>
              @endif
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
</x-partner.layouts.app>

