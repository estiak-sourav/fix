<!-- Primary Sticky Navigation -->
<header class="navbar">
  <div class="container nav-container">
    <a href="{{ route('home') }}" class="nav-brand">
      <img src="{{ asset(\App\Models\SiteSetting::get('header_logo', 'assets/images/logo-dark-crop.png')) }}" alt="FIX Logo" class="nav-logo-img">
      <span class="nav-brand-tagline">CLEAN • SAFE • SUSTAINABLE</span>
    </a>

    <nav>
      <ul class="nav-links">
        <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('home') }}#services" class="nav-link">Services</a></li>
        <li><a href="{{ route('home') }}#amc" class="nav-link">Packages</a></li>
        <li class="nav-dropdown-item">
          <a href="{{ route('home') }}#howItWorks" class="nav-link nav-dropdown-toggle">
            How FIX Works
            <svg class="dropdown-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>
          <ul class="nav-dropdown-menu">
            <li><a href="{{ route('home') }}#howItWorks" class="dropdown-item">How It Works</a></li>
            <li><a href="{{ route('home') }}#whyFix" class="dropdown-item">Why FIX</a></li>
            <li><a href="{{ route('home') }}#whoWeServe" class="dropdown-item">Who We Serve</a></li>
          </ul>
        </li>
        <li><a href="{{ route('home') }}#about" class="nav-link">About Us</a></li>
        <li><a href="{{ route('home') }}#faq" class="nav-link">FAQ</a></li>
        <li><a href="{{ route('home') }}#contact" class="nav-link">Contact</a></li>
        <li><a href="{{ route('support.create') }}" class="nav-link {{ request()->routeIs('support.*') ? 'active' : '' }}">Support</a></li>
      </ul>
    </nav>

    <div class="nav-actions">
      <a href="{{ route('vendor') }}" class="btn btn-vendor-nav">Become a Partner</a>

      @auth
        <div class="nav-dropdown-item user-menu" style="position: relative;">
          <a href="{{ route('customer.bookings') }}" class="btn btn-outline-primary" style="display:inline-flex; align-items:center; gap:8px; padding: 0.4rem 0.85rem; font-size: 0.85rem; border-radius: 6px; font-weight:600;">
            @if(auth()->user()->avatar_url)
              <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:24px; height:24px; border-radius:50%; object-fit:cover; border:1px solid #c7d2fe;">
            @else
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            @endif
            <span>{{ auth()->user()->name ?: 'My Account' }}</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>
          <ul class="nav-dropdown-menu" style="right: 0; left: auto; min-width: 170px;">
            <li><a href="{{ route('customer.bookings') }}" class="dropdown-item">My Bookings</a></li>
            <li><a href="{{ route('customer.tickets.index') }}" class="dropdown-item">Support Tickets</a></li>
            <li><a href="{{ route('customer.profile') }}" class="dropdown-item">Profile Settings</a></li>
            @if(method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
              <li><a href="/admin" target="_blank" class="dropdown-item" style="color:var(--accent); font-weight:700;">Admin Panel ↗</a></li>
            @endif
            <li>
              <form action="{{ route('auth.logout') }}" method="POST" style="margin:0; padding:0;">
                @csrf
                <button type="submit" class="dropdown-item" style="width:100%; text-align:left; background:none; border:none; color:#ef4444; cursor:pointer;">
                  Sign Out
                </button>
              </form>
            </li>
          </ul>
        </div>
      @else
        <button type="button" class="btn btn-outline-primary btn-open-auth-modal" data-auth-modal-trigger="true" onclick="openAuthModal()" style="display:inline-flex; align-items:center; gap:6px; padding: 0.5rem 0.9rem; font-size: 0.85rem; border-radius: 6px; font-weight:600; cursor:pointer;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
          <span>Sign In</span>
        </button>
      @endauth

      <button class="menu-toggle" aria-label="Open Navigation Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Drawer Menu -->
<div class="drawer-overlay"></div>
<div class="mobile-drawer">
  <div class="drawer-header">
    <div class="nav-brand">
      <img src="{{ asset(\App\Models\SiteSetting::get('header_logo', 'assets/images/logo-dark-crop.png')) }}" alt="FIX Logo" style="height:36px;">
      <span class="nav-brand-tagline">CLEAN • SAFE • SUSTAINABLE</span>
    </div>
    <button class="drawer-close" aria-label="Close Menu">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
  </div>
  <ul class="drawer-links">
    <li><a href="{{ route('home') }}" class="drawer-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
    <li><a href="{{ route('home') }}#services" class="drawer-link">Services Portfolio</a></li>
    <li><a href="{{ route('home') }}#amc" class="drawer-link">Packages</a></li>
    <li class="drawer-dropdown-item">
      <button class="drawer-dropdown-toggle" type="button">
        <span>How FIX Works</span>
        <svg class="drawer-dropdown-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </button>
      <ul class="drawer-submenu">
        <li><a href="{{ route('home') }}#howItWorks" class="drawer-sublink">How It Works</a></li>
        <li><a href="{{ route('home') }}#whyFix" class="drawer-sublink">Why Choose FIX</a></li>
        <li><a href="{{ route('home') }}#whoWeServe" class="drawer-sublink">Who We Serve</a></li>
      </ul>
    </li>
    <li><a href="{{ route('home') }}#about" class="drawer-link">About Company</a></li>
    <li><a href="{{ route('home') }}#faq" class="drawer-link">FAQ</a></li>
    <li><a href="{{ route('home') }}#contact" class="drawer-link">Contact Support</a></li>
    <li><a href="{{ route('support.create') }}" class="drawer-link">Submit Support Ticket</a></li>
    @auth
      <li><a href="{{ route('customer.bookings') }}" class="drawer-link" style="color:var(--accent); font-weight:700;">My Bookings & Status</a></li>
      <li><a href="{{ route('customer.tickets.index') }}" class="drawer-link">My Support Tickets</a></li>
      <li><a href="{{ route('customer.profile') }}" class="drawer-link">My Profile</a></li>
      <li>
        <form action="{{ route('auth.logout') }}" method="POST" style="margin:0; padding:10px 0;">
          @csrf
          <button type="submit" style="background:none; border:none; color:#ef4444; font-size:1rem; font-weight:600; cursor:pointer;">Sign Out</button>
        </form>
      </li>
    @else
      <li><a href="javascript:void(0)" onclick="openAuthModal()" class="drawer-link" style="color:var(--accent); font-weight:700;">Sign In with OTP</a></li>
    @endauth
    <li><a href="{{ route('vendor') }}" class="drawer-link" style="color:var(--primary); font-weight:600;">Become a Partner</a></li>
  </ul>
  <div class="drawer-footer">
    <a href="{{ route('booking.create') }}" class="btn btn-accent btn-block">Book a Service Now</a>
    <div style="text-align:center; font-size:0.85rem; color:var(--text-muted); margin-top:10px;">
      24/7 Hotline: <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+8801700000000') }}" style="font-weight:700; color:var(--primary);">{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}</a>
    </div>
  </div>
</div>
