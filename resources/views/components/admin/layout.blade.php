<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Fix' }}</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}?v=white">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=white">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon-white.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root {
      --admin-bg: #0f172a;
      --admin-sidebar: #1e293b;
      --admin-card: #1e293b;
      --admin-content: #0f172a;
      --admin-border: #334155;
      --admin-text: #f8fafc;
      --admin-text-muted: #94a3b8;
      --admin-primary: #3b82f6;
      --admin-accent: #f59e0b;
      --admin-success: #10b981;
      --admin-danger: #ef4444;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    *, body, button, input, select, textarea { font-family: 'Poppins', sans-serif; font-weight: 400; }
    body { font-family: 'Poppins', sans-serif; font-weight: 400; background: var(--admin-content); color: var(--admin-text); display: flex; min-height: 100vh; }
    
    /* CMS Typography Standardization */
    body, p, span, div, a, input, select, textarea, td, li, small, label { font-weight: 400; }
    h1, h2, h3, h4, h5, h6, b, strong, th, .nav-heading, .topbar-title, .stat-card-title, .stat-card-val, .panel-title, .admin-table th, .status-badge, .btn-admin, .sidebar-badge, .alert-counter { font-weight: 500 !important; }
    [style*="font-weight: 900"], [style*="font-weight:900"],
    [style*="font-weight: 800"], [style*="font-weight:800"],
    [style*="font-weight: 700"], [style*="font-weight:700"],
    [style*="font-weight: 600"], [style*="font-weight:600"],
    [style*="font-weight: bold"], [style*="font-weight:bold"] { font-weight: 500 !important; }
    [style*="font-weight: 400"], [style*="font-weight:400"],
    [style*="font-weight: normal"], [style*="font-weight:normal"] { font-weight: 400 !important; }

    /* Sidebar */
    .admin-sidebar { width: 260px; background: var(--admin-sidebar); border-right: 1px solid var(--admin-border); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-brand { padding: 1.5rem; border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; gap: 10px; }
    .sidebar-brand img { height: 32px; filter: brightness(0) invert(1); }
    .sidebar-badge { font-size: 0.65rem; background: var(--admin-accent); color: #000; font-weight: 500; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; }
    
    .sidebar-nav { padding: 1.25rem 0.75rem; flex: 1; display: flex; flex-direction: column; gap: 4px; }
    .nav-heading { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--admin-text-muted); padding: 0.75rem 0.75rem 0.25rem; font-weight: 500; }
    .nav-btn { display: flex; align-items: center; gap: 10px; padding: 0.65rem 0.85rem; border-radius: 8px; color: var(--admin-text-muted); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease; }
    .nav-btn:hover, .nav-btn.active { background: rgba(59, 130, 246, 0.15); color: #ffffff; }
    .nav-btn svg { width: 18px; height: 18px; stroke-width: 2; }
    
    .sidebar-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: space-between; font-size: 0.85rem; }
    
    /* Main Content Area */
    .admin-main { flex: 1; display: flex; flex-direction: column; min-width: 0; overflow-x: hidden; }
    .admin-topbar { height: 70px; background: var(--admin-sidebar); border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2rem; }
    .topbar-title { font-size: 1.15rem; font-weight: 500; color: #ffffff; }
    .topbar-actions { display: flex; align-items: center; gap: 16px; }
    
    /* Real-time Alert Bell */
    .alert-bell { position: relative; background: rgba(255,255,255,0.05); border: 1px solid var(--admin-border); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--admin-text); }
    .alert-counter { position: absolute; top: -5px; right: -5px; background: var(--admin-danger); color: #fff; font-size: 0.7rem; font-weight: 500; border-radius: 10px; padding: 1px 6px; }
    .sound-badge { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; background: rgba(16, 185, 129, 0.15); color: var(--admin-success); border: 1px solid rgba(16, 185, 129, 0.3); padding: 4px 10px; border-radius: 20px; font-weight: 500; }
    
    .admin-body { padding: 2rem; flex: 1; }
    
    /* Standard Admin UI Elements */
    .stat-cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
    .stat-card { background: var(--admin-card); border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.25rem; }
    .stat-card-title { font-size: 0.8rem; font-weight: 500; color: var(--admin-text-muted); text-transform: uppercase; margin-bottom: 6px; }
    .stat-card-val { font-size: 1.85rem; font-weight: 500; color: #ffffff; }
    
    .admin-panel-card { background: var(--admin-card); border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .panel-title { font-size: 1.1rem; font-weight: 500; color: #ffffff; }
    
    /* Table */
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.85rem 1rem; border-bottom: 1px solid var(--admin-border); color: var(--admin-text-muted); font-weight: 500; font-size: 0.75rem; text-transform: uppercase; }
    .admin-table td { padding: 1rem; border-bottom: 1px solid var(--admin-border); color: #e2e8f0; }
    .admin-table tr:hover { background: rgba(255,255,255,0.02); }
    
    /* Badges */
    .status-badge { display: inline-block; font-size: 0.75rem; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
    
    /* Form controls */
    .form-control-admin { width: 100%; background: #0f172a; border: 1px solid var(--admin-border); border-radius: 8px; padding: 0.65rem 0.85rem; color: #ffffff; font-size: 0.875rem; outline: none; }
    .form-control-admin:focus { border-color: var(--admin-primary); }
    .btn-admin { display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 500; font-size: 0.875rem; text-decoration: none; cursor: pointer; border: none; }
    .btn-admin-primary { background: var(--admin-primary); color: #fff; }
    .btn-admin-success { background: var(--admin-success); color: #fff; }
    .btn-admin-outline { background: transparent; border: 1px solid var(--admin-border); color: #e2e8f0; }
    .btn-admin-outline:hover { background: rgba(255,255,255,0.05); }
    
    /* Notification Toaster */
    #adminNotificationToast { position: fixed; top: 20px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <img src="{{ asset('assets/images/logo-white-crop.png') }}" alt="FIX">
      <div>
        <div style="font-size:0.85rem; font-weight:500; letter-spacing:0.04em;">OPERATIONS</div>
        <span class="sidebar-badge">Control Hub</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-heading">Operations</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-btn {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
        <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.bookings.index') }}" class="nav-btn {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        <span>Work Orders & Bookings</span>
      </a>

      <div class="nav-heading">Content & Media CMS</div>
      <a href="{{ route('admin.cms.services') }}" class="nav-btn {{ request()->routeIs('admin.cms.services*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        <span>Services & Photos</span>
      </a>

      <a href="{{ route('admin.cms.settings') }}" class="nav-btn {{ request()->routeIs('admin.cms.settings*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <span>Site Settings & Logos</span>
      </a>

      <div class="nav-heading">Public Portal</div>
      <a href="/" target="_blank" class="nav-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
        <span>View Live Website ↗</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div>
        <div style="font-weight:500; color:#fff;">{{ auth()->user()->name }}</div>
        <div style="font-size:0.75rem; color:var(--admin-text-muted);">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
      </div>
      <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" style="background:none; border:none; color:var(--admin-danger); cursor:pointer; font-weight:500; font-size:0.8rem;">
          Logout
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content Area -->
  <div class="admin-main">
    <header class="admin-topbar">
      <div class="topbar-title">
        {{ $headerTitle ?? 'Operations Command Center' }}
      </div>

      <div class="topbar-actions">
        <!-- Live Audio Sound Trigger Indicator -->
        <div class="sound-badge" id="audioChimeStatus">
          <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981; animation:pulse 2s infinite;"></span>
          <span>Audio Alert Active</span>
        </div>

        <!-- Alert Bell -->
        <div class="alert-bell" onclick="window.location.href='{{ route('admin.bookings.index') }}'">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          <span class="alert-counter" id="liveAlertCounter" style="display:none;">0</span>
        </div>
      </div>
    </header>

    <main class="admin-body">
      @if(session('success'))
        <div data-alert style="background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.4); color:#34d399; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; justify-content:space-between;">
          <span>{{ session('success') }}</span>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#34d399; cursor:pointer; font-size:1.2rem; line-height:1; padding:0 4px;" aria-label="Close">&times;</button>
        </div>
      @endif

      @if(session('error'))
        <div data-alert style="background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.4); color:#f87171; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; justify-content:space-between;">
          <span>{{ session('error') }}</span>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#f87171; cursor:pointer; font-size:1.2rem; line-height:1; padding:0 4px;" aria-label="Close">&times;</button>
        </div>
      @endif

      {{ $slot }}
    </main>
  </div>

  <div id="adminNotificationToast"></div>

  <!-- Real-Time Notification Poller & Native Web Audio Chime Generator -->
  <script>
    let audioCtx = null;
    function playChime() {
      try {
        if (!audioCtx) {
          audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
          audioCtx.resume();
        }

        const now = audioCtx.currentTime;
        const osc1 = audioCtx.createOscillator();
        const gain1 = audioCtx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(587.33, now);
        gain1.gain.setValueAtTime(0.3, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
        osc1.connect(gain1);
        gain1.connect(audioCtx.destination);
        osc1.start(now);
        osc1.stop(now + 0.35);

        const osc2 = audioCtx.createOscillator();
        const gain2 = audioCtx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(880.00, now + 0.12);
        gain2.gain.setValueAtTime(0.35, now + 0.12);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
        osc2.connect(gain2);
        gain2.connect(audioCtx.destination);
        osc2.start(now + 0.12);
        osc2.stop(now + 0.6);
      } catch (e) {
        console.warn('Audio alert exception:', e);
      }
    }

    document.addEventListener('click', () => {
      if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }, { once: true });

    async function pollAdminAlerts() {
      try {
        const res = await fetch('{{ route("admin.alerts.check") }}');
        if (!res.ok) return;
        const data = await res.json();

        const badge = document.getElementById('liveAlertCounter');
        if (data.unread_total > 0) {
          badge.style.display = 'block';
          badge.innerText = data.unread_total;
        }

        if (data.has_sound) {
          playChime();
          data.alerts.forEach(alert => {
            showAdminToast(alert.title, alert.message, alert.type);
          });
        }
      } catch (e) {}
    }

    function showAdminToast(title, message, type) {
      const toast = document.createElement('div');
      const isEmergency = type === 'emergency_alert';
      toast.style.cssText = `
        background: ${isEmergency ? '#dc2626' : '#1e3a8a'};
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        padding: 16px 20px;
        border-radius: 10px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);
        min-width: 300px;
        max-width: 420px;
        animation: slideIn 0.3s ease;
      `;
      toast.innerHTML = `
        <div style="font-weight:800; font-size:0.95rem; margin-bottom:4px; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:6px;">
            ${isEmergency ? '🚨' : '🔔'} ${title}
          </div>
          <button type="button" onclick="this.closest('div[style*=\\'position\\'], div[style*=\\'box-shadow\\']').remove()" style="background:none; border:none; color:#fff; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 0 0 8px; opacity:0.85;" aria-label="Close">&times;</button>
        </div>
        <div style="font-size:0.85rem; color:#f1f5f9;">${message}</div>
      `;
      document.getElementById('adminNotificationToast').appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 6000);
    }

    setInterval(pollAdminAlerts, 4000);
  </script>
</body>
</html>
