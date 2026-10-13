<!-- Paces-style Modern Dark Sidebar -->
<aside class="app-sidebar" id="appSidebar">
  <!-- Brand Logo Header -->
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{ asset('assets/images/logo-white-crop.png') }}" alt="FIX Logo" class="brand-logo">
      <div class="brand-text">
        <span class="brand-title">FIX OPS</span>
        <span class="brand-badge">PACES COMMAND</span>
      </div>
    </a>
  </div>

  <!-- Navigation Menu -->
  <div class="sidebar-menu-wrapper" data-simplebar>
    <nav class="sidebar-nav">
      <div class="nav-header">DASHBOARDS</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
        </span>
        <span class="nav-text">Operations Center</span>
      </a>

      <div class="nav-header">OPERATIONS & DISPATCH</div>
      <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </span>
        <span class="nav-text">Work Orders & Bookings</span>
        @php
          $pendingCount = \App\Models\Booking::where('status', 'pending_review')->count();
        @endphp
        @if($pendingCount > 0)
          <span class="nav-badge bg-warning">{{ $pendingCount }}</span>
        @endif
      </a>

      <div class="nav-header">CONTENT MANAGEMENT (CMS)</div>
      <a href="{{ route('admin.cms.services') }}" class="nav-item {{ request()->routeIs('admin.cms.services*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </span>
        <span class="nav-text">Services & Media</span>
      </a>

      <a href="{{ route('admin.cms.settings') }}" class="nav-item {{ request()->routeIs('admin.cms.settings*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        </span>
        <span class="nav-text">Global CMS Settings</span>
      </a>

      <div class="nav-header">PUBLIC PORTAL</div>
      <a href="/" target="_blank" class="nav-item">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        </span>
        <span class="nav-text">View Live Website ↗</span>
      </a>
    </nav>
  </div>

  <!-- Sidebar User Profile Footer -->
  <div class="sidebar-user-footer">
    <div class="user-avatar-sm">
      <span>{{ substr((auth('admin')->user() ?? auth()->user())?->name ?? 'A', 0, 1) }}</span>
    </div>
    <div class="user-meta">
      <div class="user-name">{{ (auth('admin')->user() ?? auth()->user())?->name ?? 'Operations Lead' }}</div>
      <div class="user-role">{{ ucfirst(str_replace('_', ' ', (auth('admin')->user() ?? auth()->user())?->role ?? 'super_admin')) }}</div>
    </div>
    <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
      @csrf
      <button type="submit" class="sidebar-logout-btn" title="Sign Out">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
      </button>
    </form>
  </div>
</aside>
