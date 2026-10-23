<!-- Paces Topbar Header -->
<header class="app-topbar">
  <div class="topbar-left">
    <!-- Mobile / Desktop Sidebar Toggle -->
    <button type="button" class="btn-topbar-toggle" onclick="toggleAdminSidebar()" aria-label="Toggle Sidebar">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>

    <!-- Quick Search Pill Input (Paces Pattern) -->
    <div class="topbar-search-box">
      <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" class="topbar-search-input" placeholder="Quick Search orders, clients, facilities..." onkeyup="if(event.key==='Enter') window.location.href='{{ route('admin.bookings.index') }}?search='+this.value">
    </div>
  </div>

  <div class="topbar-right">
    <!-- Notification Bell -->
    @php
      $topbarUnreadCount = \App\Models\AdminNotification::where('is_read', false)->count();
    @endphp
    <div class="topbar-dropdown-wrapper">
      <a href="{{ route('admin.notifications.index') }}" class="btn-topbar-icon" id="adminBellBtn" title="Alerts & Work Orders" style="text-decoration:none;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        <span class="topbar-badge" id="topbarAlertBadge">{{ $topbarUnreadCount }}</span>
      </a>
    </div>

    <!-- User Profile Link to Self Profile -->
    @php
      $currentAdmin = auth('admin')->user() ?? auth()->user();
    @endphp
    <a href="{{ route('admin.profile') }}" class="topbar-user-profile" style="text-decoration:none;" title="My Profile & Avatar Settings">
      <div class="topbar-avatar">
        @if($currentAdmin?->avatar_url)
          <img src="{{ $currentAdmin->avatar_url }}" alt="{{ $currentAdmin->name }}">
        @else
          <span>{{ substr($currentAdmin?->name ?? 'A', 0, 1) }}</span>
        @endif
      </div>
      <div class="topbar-user-info">
        <span class="topbar-user-name">{{ $currentAdmin?->name ?? 'Admin User' }}</span>
        <span class="topbar-user-role">{{ ucfirst(str_replace('_', ' ', $currentAdmin?->role ?? 'Operations HQ')) }}</span>
      </div>
    </a>
  </div>
</header>
