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

  @php
    /** @var \App\Models\Admin|null $adminUser */
    $adminUser = auth('admin')->user();
  @endphp

  <!-- Navigation Menu -->
  <div class="sidebar-menu-wrapper" data-simplebar>
    <nav class="sidebar-nav">
      @if($adminUser?->hasPermission('dashboard.view'))
        <div class="nav-header">DASHBOARDS</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
          </span>
          <span class="nav-text">Dashboard</span>
        </a>
      @endif

      @if($adminUser?->hasPermission('bookings.view') || $adminUser?->hasPermission('analytics.view') || $adminUser?->hasPermission('tickets.view'))
        <div class="nav-header">OPERATIONS & DISPATCH</div>
      @endif

      <!-- Bookings Management -->
      @if($adminUser?->hasPermission('bookings.view'))
        <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          </span>
          <span class="nav-text">Booking</span>
          @php
            $pendingCount = \App\Models\Booking::where('status', 'pending_review')->count();
          @endphp
          @if($pendingCount > 0)
            <span class="nav-badge bg-warning">{{ $pendingCount }}</span>
          @endif
        </a>

        <!-- Outreach Tasks -->
        <a href="{{ route('admin.outreach-tasks.index') }}" class="nav-item {{ request()->routeIs('admin.outreach-tasks.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
          </span>
          <span class="nav-text">Outreach Tasks</span>
          @php
            $pendingTasksCount = \App\Models\OutreachTask::where('status', 'pending')->count();
          @endphp
          @if($pendingTasksCount > 0)
            <span class="nav-badge bg-warning">{{ $pendingTasksCount }}</span>
          @endif
        </a>
      @endif

      <!-- Notifications & Alerts Page (Always available to authenticated staff) -->
      @php
        $unreadAlertsCount = \App\Models\AdminNotification::where('is_read', false)->count();
      @endphp
      <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        </span>
        <span class="nav-text">Notifications</span>
        @if($unreadAlertsCount > 0)
          <span class="nav-badge bg-warning" style="background:#ef4444; color:#fff;">{{ $unreadAlertsCount }}</span>
        @endif
      </a>

      <!-- Support Tickets System -->
      @if($adminUser?->hasPermission('tickets.view'))
        @php
          $openTicketsCount = \App\Models\SupportTicket::where('status', 'open')->count();
        @endphp
        <a href="{{ route('admin.tickets.index') }}" class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
          </span>
          <span class="nav-text">Support Tickets</span>
          @if($openTicketsCount > 0)
            <span class="nav-badge bg-info" style="background:#0ea5e9; color:#fff;">{{ $openTicketsCount }}</span>
          @endif
        </a>
      @endif

      <!-- Partners & Enlisted Field Crew Network -->
      @php
        $pendingPartnersCount = \App\Models\Partner::where('status', 'pending')->count();
      @endphp
      <a href="{{ route('admin.partners.index') }}" class="nav-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </span>
        <span class="nav-text">Partners & Field Crew</span>
        @if($pendingPartnersCount > 0)
          <span class="nav-badge bg-warning">{{ $pendingPartnersCount }}</span>
        @endif
      </a>

      <!-- Service Hub: Collapsible Submenu (Services + AMC Packages) -->
      @if($adminUser?->hasPermission('services.view') || $adminUser?->hasPermission('amc_plans.view'))
        <div class="nav-header">SERVICE & FACILITY</div>
        @php
          $isServiceHubActive = request()->routeIs('admin.web.services*') || request()->routeIs('admin.web.amc-plans*') || request()->routeIs('admin.cms.services*');
        @endphp
        <div class="nav-group {{ $isServiceHubActive ? 'open active' : '' }}">
          <button type="button" class="nav-item nav-toggle {{ $isServiceHubActive ? 'active' : '' }}" onclick="toggleNavGroup(this)">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
            </span>
            <span class="nav-text">Service Hub</span>
            <span class="nav-arrow">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </span>
          </button>
          <div class="nav-submenu">
            @if($adminUser?->hasPermission('services.view'))
              <a href="{{ route('admin.web.services') }}" class="nav-subitem {{ (request()->routeIs('admin.web.services*') || request()->routeIs('admin.cms.services*')) ? 'active' : '' }}">
                <span class="nav-subdot"></span>
                <span>Service List</span>
              </a>
            @endif
            @if($adminUser?->hasPermission('amc_plans.view'))
              <a href="{{ route('admin.web.amc-plans') }}" class="nav-subitem {{ request()->routeIs('admin.web.amc-plans*') ? 'active' : '' }}">
                <span class="nav-subdot"></span>
                <span>AMC Packages</span>
              </a>
            @endif
          </div>
        </div>
      @endif

      <!-- Users: Collapsible Submenu (Client user list and their controls) -->
      @if($adminUser?->hasPermission('users.view'))
        <div class="nav-header">CLIENTS & USERS</div>
        @php
          $isUsersActive = request()->routeIs('admin.users.*');
        @endphp
        <div class="nav-group {{ $isUsersActive ? 'open active' : '' }}">
          <button type="button" class="nav-item nav-toggle {{ $isUsersActive ? 'active' : '' }}" onclick="toggleNavGroup(this)">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <span class="nav-text">Client Users</span>
            <span class="nav-arrow">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </span>
          </button>
          <div class="nav-submenu">
            <a href="{{ route('admin.users.index') }}" class="nav-subitem {{ (request()->routeIs('admin.users.index') && !request('status')) ? 'active' : '' }}">
              <span class="nav-subdot"></span>
              <span>Client Users</span>
            </a>
            <a href="{{ route('admin.users.index', ['status' => '1']) }}" class="nav-subitem {{ request('status') === '1' ? 'active' : '' }}">
              <span class="nav-subdot"></span>
              <span>Active Accounts</span>
            </a>
            <a href="{{ route('admin.users.index', ['status' => '0']) }}" class="nav-subitem {{ request('status') === '0' ? 'active' : '' }}">
              <span class="nav-subdot"></span>
              <span>Deactivated Accounts</span>
            </a>
          </div>
        </div>
      @endif

      <!-- Staff & Activity Logs Access Control -->
      @if($adminUser?->hasPermission('staff_management.view') || $adminUser?->hasPermission('activity_logs.view'))
        <div class="nav-header">ACCESS & AUDIT</div>
        @if($adminUser?->hasPermission('staff_management.view'))
          <a href="{{ route('admin.staff.index') }}" class="nav-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </span>
            <span class="nav-text">Staff & Permissions</span>
          </a>
        @endif

        @if($adminUser?->hasPermission('activity_logs.view'))
          <a href="{{ route('admin.activity-logs.index') }}" class="nav-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
            <span class="nav-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </span>
            <span class="nav-text">Activity Logs</span>
          </a>
        @endif
      @endif

      <!-- Web Settings & Content -->
      @if($adminUser?->hasPermission('settings.view') || $adminUser?->hasPermission('web_content.view'))
        <div class="nav-header">WEBSITE & PORTAL</div>
      @endif

      @if($adminUser?->hasPermission('settings.view'))
        <a href="{{ route('admin.web.settings') }}" class="nav-item {{ (request()->routeIs('admin.web.settings*') || request()->routeIs('admin.web.smtp*') || request()->routeIs('admin.cms.settings*')) ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          </span>
          <span class="nav-text">Web Settings</span>
        </a>
      @endif

      @if($adminUser?->hasPermission('web_content.view'))
        <a href="{{ route('admin.web.contents') }}" class="nav-item {{ (request()->routeIs('admin.web.contents*') || request()->routeIs('admin.web.faqs*') || request()->routeIs('admin.web.testimonials*') || request()->routeIs('admin.web.services*') || request()->routeIs('admin.cms.services*') || request()->routeIs('admin.web.amc-plans*') || request()->routeIs('admin.web.index')) ? 'active' : '' }}">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
          </span>
          <span class="nav-text">Web Content</span>
        </a>
      @endif

      <div class="nav-header">PUBLIC PORTAL</div>
      <a href="{{ \App\Models\SiteSetting::mainSiteUrl('') }}" target="_blank" rel="noopener noreferrer" class="nav-item">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        </span>
        <span class="nav-text">View Live Website ↗</span>
      </a>
    </nav>
  </div>

  <!-- Sidebar User Profile Footer -->
  <div class="sidebar-user-footer">
    <a href="{{ route('admin.profile') }}" style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; text-decoration: none; color: inherit;" title="My Profile & Avatar Settings">
      <div class="user-avatar-sm">
        @if($adminUser?->avatar_url)
          <img src="{{ $adminUser->avatar_url }}" alt="{{ $adminUser->name }}">
        @else
          <span>{{ substr($adminUser?->name ?? 'A', 0, 1) }}</span>
        @endif
      </div>
      <div class="user-meta">
        <div class="user-name">{{ $adminUser?->name ?? 'Operations Lead' }}</div>
        <div class="user-role">{{ ucfirst(str_replace('_', ' ', $adminUser?->role ?? 'super_admin')) }}</div>
      </div>
    </a>
    <form action="{{ route('admin.logout') }}" method="POST" style="margin:0;">
      @csrf
      <button type="submit" class="sidebar-logout-btn" title="Sign Out">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
      </button>
    </form>
  </div>
</aside>
