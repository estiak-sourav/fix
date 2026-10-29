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

    <!-- User Profile Dropdown -->
    @php
      $currentAdmin = auth('admin')->user() ?? auth()->user();
    @endphp
    <div class="topbar-user-wrapper" id="topbarUserWrapper">
      <button type="button" class="topbar-user-profile" onclick="toggleUserDropdown(event)" aria-expanded="false" aria-label="User Account Menu">
        <div class="topbar-avatar">
          @if($currentAdmin?->avatar_url)
            <img src="{{ $currentAdmin->avatar_url }}" alt="{{ $currentAdmin->name }}">
          @else
            <span>{{ substr($currentAdmin?->name ?? 'A', 0, 1) }}</span>
          @endif
        </div>
        <div class="topbar-user-info" style="text-align:left;">
          <span class="topbar-user-name">{{ $currentAdmin?->name ?? 'Admin User' }}</span>
          <span class="topbar-user-role">{{ ucfirst(str_replace('_', ' ', $currentAdmin?->role ?? 'Operations HQ')) }}</span>
        </div>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--paces-text-muted); margin-left:2px;"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </button>

      <!-- Dropdown Popup Menu -->
      <div class="topbar-user-menu" id="topbarUserMenu">
        <div class="topbar-user-header">
          <div style="font-size:0.875rem; font-weight:600; color:var(--paces-text-dark); margin-bottom:2px;">
            {{ $currentAdmin?->name ?? 'Administrator' }}
          </div>
          <div style="font-size:0.75rem; color:var(--paces-text-muted); text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">
            {{ $currentAdmin?->email ?? '' }}
          </div>
          <div style="margin-top:6px;">
            <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.7rem; font-weight:500;">
              {{ ucfirst(str_replace('_', ' ', $currentAdmin?->role ?? 'Admin')) }}
            </span>
          </div>
        </div>

        <a href="{{ route('admin.profile') }}" class="topbar-user-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span>My Profile & Avatar</span>
        </a>

        <a href="{{ route('admin.password.change') }}" class="topbar-user-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          <span>Change Password</span>
        </a>

        <div class="topbar-user-divider"></div>

        <form action="{{ route('admin.logout') }}" method="POST" id="topbarLogoutForm" style="margin:0;" onsubmit="return confirmAdminLogout(event)">
          @csrf
          <button type="submit" class="topbar-logout-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            <span>Sign Out</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</header>

<script>
  function toggleUserDropdown(e) {
    if (e) e.stopPropagation();
    const wrapper = document.getElementById('topbarUserWrapper');
    if (wrapper) wrapper.classList.toggle('open');
  }

  document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('topbarUserWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
      wrapper.classList.remove('open');
    }
  });
</script>
