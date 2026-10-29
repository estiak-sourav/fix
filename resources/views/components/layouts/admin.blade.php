<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? ($headerTitle ?? 'Fix') }}</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}?v=white">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=white">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon-white.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Chart.js for High-Performance Analytics & Bar Charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    /* ==========================================================================
       Paces Bootstrap/Tailwind Modern SaaS Admin Theme Tokens
       ========================================================================== */
    :root {
      --paces-bg: #f3f4f7;
      --paces-card-bg: #ffffff;
      --paces-border: #eaedf1;
      --paces-sidebar-bg: #1e2229;
      --paces-sidebar-border: #282d37;
      --paces-sidebar-text: #94a3b8;
      --paces-sidebar-active: #3e60d5;
      
      --paces-text-dark: #1e293b;
      --paces-text-muted: #64748b;
      --paces-text-light: #94a3b8;
      
      --paces-primary: #3e60d5;
      --paces-primary-subtle: #eef2ff;
      --paces-primary-border: #c7d2fe;
      
      --paces-success: #16a34a;
      --paces-success-subtle: #f0fdf4;
      --paces-success-border: #bbf7d0;
      
      --paces-warning: #d97706;
      --paces-warning-subtle: #fffbeb;
      --paces-warning-border: #fef3c7;
      
      --paces-danger: #dc2626;
      --paces-danger-subtle: #fef2f2;
      --paces-danger-border: #fecaca;
      
      --paces-info: #7c3aed;
      --paces-info-subtle: #f5f3ff;
      --paces-info-border: #ddd6fe;

      --paces-radius: 12px;
      --paces-radius-sm: 8px;
      --paces-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
      --paces-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    *, body, button, input, select, textarea {
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
    }
    body {
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
      background: var(--paces-bg);
      color: var(--paces-text-dark);
      display: flex;
      min-height: 100vh;
      width: 100%;
      max-width: 100vw;
      overflow-x: hidden;
      position: relative;
    }

    /* ==========================================================================
       CMS Typography Standardization (Normal: 400, Bold/Headings/Accents: 500)
       ========================================================================== */
    body, p, span, div, a, input, select, textarea, td, li, small, label, dd, dt {
      font-weight: 400;
    }

    h1, h2, h3, h4, h5, h6,
    b, strong, th,
    .page-header-title,
    .card-title-paces,
    .panel-title,
    .brand-title,
    .brand-badge,
    .nav-header,
    .nav-item,
    .nav-badge,
    .topbar-user-name,
    .topbar-badge,
    .topbar-title,
    .metric-value,
    .metric-label,
    .table-paces th,
    .admin-table th,
    .badge-pill-paces,
    .status-badge,
    .btn-paces,
    .btn-admin,
    .btn-topbar-site,
    .btn-topbar-chime,
    .form-label-paces,
    .user-name,
    .user-avatar-sm,
    .topbar-avatar,
    .top-kpi-label,
    .top-kpi-value,
    .fw-bold,
    .font-bold,
    .font-semibold,
    .font-medium {
      font-weight: 500 !important;
    }

    /* Enforce font-weight 500 for any overly bold inline weights in CMS views */
    [style*="font-weight: 900"],
    [style*="font-weight:900"],
    [style*="font-weight: 800"],
    [style*="font-weight:800"],
    [style*="font-weight: 700"],
    [style*="font-weight:700"],
    [style*="font-weight: 600"],
    [style*="font-weight:600"],
    [style*="font-weight: bold"],
    [style*="font-weight:bold"] {
      font-weight: 500 !important;
    }

    [style*="font-weight: 400"],
    [style*="font-weight:400"],
    [style*="font-weight: normal"],
    [style*="font-weight:normal"] {
      font-weight: 400 !important;
    }

    /* ---------------- Sidebar (Paces Dark Sidebar) ---------------- */
    .app-sidebar {
      width: 260px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      background: var(--paces-sidebar-bg);
      border-right: 1px solid var(--paces-sidebar-border);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 1050;
      overflow: visible;
    }

    .sidebar-brand {
      height: 70px;
      padding: 0 1.5rem;
      border-bottom: 1px solid var(--paces-sidebar-border);
      display: flex;
      align-items: center;
    }
    .brand-link {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }
    .brand-logo {
      height: 32px;
      filter: brightness(0) invert(1);
    }
    .brand-text {
      display: flex;
      flex-direction: column;
    }
    .brand-title {
      font-size: 0.95rem;
      font-weight: 500;
      letter-spacing: 0.05em;
      color: #ffffff;
      font-family: 'Poppins', sans-serif;
    }
    .brand-badge {
      font-size: 0.6rem;
      font-weight: 500;
      color: #f59e0b;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .sidebar-menu-wrapper {
      padding: 1.25rem 0.85rem;
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    .sidebar-menu-wrapper::-webkit-scrollbar {
      display: none;
      width: 0;
      height: 0;
    }
    .nav-header {
      font-size: 0.68rem;
      font-weight: 500;
      color: #64748b;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding: 0.85rem 0.75rem 0.35rem;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0.65rem 0.85rem;
      border-radius: var(--paces-radius-sm);
      color: var(--paces-sidebar-text);
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 500;
      transition: all 0.2s ease;
      margin-bottom: 3px;
    }
    .nav-item:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.05);
    }
    .nav-item.active {
      background: var(--paces-primary);
      color: #ffffff;
      box-shadow: 0 4px 10px rgba(62, 96, 213, 0.35);
    }
    .nav-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 20px;
    }
    .nav-icon svg {
      width: 18px;
      height: 18px;
      stroke-width: 2;
    }
    .nav-text {
      flex: 1;
    }
    .nav-badge {
      font-size: 0.7rem;
      font-weight: 500;
      padding: 2px 7px;
      border-radius: 20px;
    }
    .nav-badge.bg-warning {
      background: #f59e0b;
      color: #000;
    }

    /* Collapsible Sub-menu Navigation (Paces Pattern) */
    .nav-group {
      margin-bottom: 3px;
    }
    .nav-group .nav-toggle {
      width: 100%;
      border: none;
      background: transparent;
      cursor: pointer;
      text-align: left;
      font-family: inherit;
    }
    .nav-arrow {
      margin-left: auto;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.25s ease;
      color: #64748b;
    }
    .nav-arrow svg {
      width: 14px;
      height: 14px;
      stroke-width: 2.5;
    }
    .nav-group.open > .nav-toggle .nav-arrow {
      transform: rotate(90deg);
      color: #ffffff;
    }
    .nav-group.open > .nav-toggle {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.04);
    }
    .nav-group.active > .nav-toggle {
      color: #ffffff;
      font-weight: 500;
    }
    .nav-submenu {
      display: none;
      flex-direction: column;
      padding: 4px 0 6px 14px;
      margin-left: 12px;
      border-left: 1px solid rgba(255, 255, 255, 0.1);
      gap: 2px;
    }
    .nav-group.open .nav-submenu {
      display: flex;
    }
    .nav-subitem {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0.45rem 0.75rem;
      border-radius: var(--paces-radius-sm);
      color: var(--paces-sidebar-text);
      text-decoration: none;
      font-size: 0.82rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    .nav-subitem:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.06);
    }
    .nav-subitem.active {
      color: #ffffff;
      background: rgba(62, 96, 213, 0.22);
      font-weight: 500;
    }
    .nav-subitem.active .nav-subdot {
      background: var(--paces-primary);
      box-shadow: 0 0 8px var(--paces-primary);
    }
    .nav-subdot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #475569;
      transition: all 0.2s;
    }

    .sidebar-user-footer {
      padding: 1rem 1.25rem;
      border-top: 1px solid var(--paces-sidebar-border);
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(0,0,0,0.15);
    }
    .user-avatar-sm {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--paces-primary);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 500;
      font-size: 0.85rem;
      flex-shrink: 0;
      overflow: hidden;
    }
    .user-avatar-sm img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .user-meta {
      flex: 1;
      min-width: 0;
    }
    .user-name {
      font-size: 0.85rem;
      font-weight: 500;
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .user-role {
      font-size: 0.7rem;
      color: var(--paces-sidebar-text);
      text-transform: capitalize;
    }
    .sidebar-logout-btn {
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      padding: 6px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    .sidebar-logout-btn:hover {
      color: #ef4444;
      background: rgba(239, 68, 68, 0.1);
    }
    .sidebar-logout-btn svg {
      width: 18px;
      height: 18px;
    }

    /* ---------------- Main Content Layout ---------------- */
    .app-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      margin-left: 260px;
      width: calc(100% - 260px);
      max-width: calc(100% - 260px);
      transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1), width 0.25s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      box-sizing: border-box;
      overflow-x: hidden;
    }

    /* ---------------- Topbar (Paces Style) ---------------- */
    .app-topbar {
      height: 70px;
      background: var(--paces-card-bg);
      border-bottom: 1px solid var(--paces-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.75rem;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .btn-topbar-toggle {
      background: transparent;
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius-sm);
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--paces-text-dark);
      cursor: pointer;
      transition: all 0.2s;
    }
    .btn-topbar-toggle:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }

    .topbar-search-box {
      position: relative;
      display: flex;
      align-items: center;
    }
    .search-icon {
      position: absolute;
      left: 14px;
      color: var(--paces-text-muted);
      pointer-events: none;
    }
    .topbar-search-input {
      padding: 0.55rem 1rem 0.55rem 38px;
      border-radius: 30px;
      border: 1px solid var(--paces-border);
      background: #f8fafc;
      font-size: 0.85rem;
      width: 320px;
      color: var(--paces-text-dark);
      outline: none;
      transition: all 0.2s;
    }
    .topbar-search-input:focus {
      background: #ffffff;
      border-color: var(--paces-primary);
      box-shadow: 0 0 0 3px rgba(62, 96, 213, 0.12);
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .topbar-audio-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 4px 10px;
      border-radius: 30px;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
    }
    .audio-pulse-indicator {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.75rem;
      font-weight: 500;
      color: #16a34a;
    }
    .pulse-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #16a34a;
      box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
      animation: pulse 1.6s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }
    .btn-topbar-chime {
      background: #ffffff;
      border: 1px solid #bbf7d0;
      border-radius: 20px;
      padding: 3px 8px;
      font-size: 0.7rem;
      font-weight: 500;
      color: #15803d;
      cursor: pointer;
    }
    .btn-topbar-chime:hover {
      background: #dcfce7;
    }

    .btn-topbar-icon {
      width: 38px;
      height: 38px;
      border-radius: var(--paces-radius-sm);
      border: 1px solid var(--paces-border);
      background: var(--paces-card-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--paces-text-dark);
      position: relative;
      cursor: pointer;
    }
    .topbar-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: var(--paces-danger);
      color: #fff;
      font-size: 0.65rem;
      font-weight: 500;
      border-radius: 10px;
      padding: 1px 5px;
    }

    .btn-topbar-site {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 0.5rem 0.9rem;
      border-radius: 20px;
      background: #f1f5f9;
      color: var(--paces-text-dark);
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 500;
      transition: all 0.2s;
    }
    .btn-topbar-site:hover {
      background: #e2e8f0;
      color: #000;
    }

    .topbar-user-wrapper {
      position: relative;
    }
    .topbar-user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 4px 8px;
      border-radius: 8px;
      cursor: pointer;
      background: transparent;
      border: 1px solid transparent;
      transition: all 0.2s;
      text-decoration: none;
    }
    .topbar-user-profile:hover,
    .topbar-user-wrapper.open .topbar-user-profile {
      background: #ffffff;
      border-color: var(--paces-border);
      box-shadow: var(--paces-shadow);
    }
    .topbar-user-menu {
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      width: 250px;
      background: #ffffff;
      border: 1px solid var(--paces-border);
      border-radius: 10px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
      z-index: 1060;
      display: none;
      animation: fadeInPaces 0.15s ease-out;
      overflow: hidden;
    }
    .topbar-user-wrapper.open .topbar-user-menu {
      display: block;
    }
    .topbar-user-header {
      padding: 12px 16px;
      border-bottom: 1px solid var(--paces-border);
      background: #f8fafc;
    }
    .topbar-user-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      color: var(--paces-text-dark);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
      transition: background 0.15s, color 0.15s;
    }
    .topbar-user-link:hover {
      background: #f1f5f9;
      color: var(--paces-primary);
    }
    .topbar-user-link svg {
      width: 16px;
      height: 16px;
      color: var(--paces-text-muted);
    }
    .topbar-user-link:hover svg {
      color: var(--paces-primary);
    }
    .topbar-user-divider {
      height: 1px;
      background: var(--paces-border);
      margin: 4px 0;
    }
    .topbar-logout-btn {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      border: none;
      background: transparent;
      color: #dc2626;
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      text-align: left;
      transition: background 0.15s;
    }
    .topbar-logout-btn:hover {
      background: #fef2f2;
    }
    .topbar-logout-btn svg {
      width: 16px;
      height: 16px;
    }
    .topbar-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--paces-primary);
      color: #ffffff;
      font-weight: 500;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }
    .topbar-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .topbar-user-info {
      display: flex;
      flex-direction: column;
    }
    .topbar-user-name {
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--paces-text-dark);
      line-height: 1.2;
    }
    .topbar-user-role {
      font-size: 0.7rem;
      color: var(--paces-text-muted);
    }

    /* ---------------- Content Canvas ---------------- */
    .app-content {
      padding: 1.75rem 2rem;
      flex: 1;
      min-width: 0;
      width: 100%;
      box-sizing: border-box;
    }
    .page-header-box {
      margin-bottom: 1.75rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }
    .page-header-title {
      font-size: 1.35rem;
      font-weight: 500;
      color: var(--paces-text-dark);
      font-family: 'Poppins', sans-serif;
    }
    .page-header-subtitle {
      font-size: 0.85rem;
      color: var(--paces-text-muted);
      margin-top: 2px;
    }

    /* ---------------- Paces Card & Stat Metrics ---------------- */
    .card-paces {
      background: var(--paces-card-bg);
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius);
      box-shadow: var(--paces-shadow);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      position: relative;
    }
    .card-header-paces {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.25rem;
    }
    .card-title-paces {
      font-size: 1.05rem;
      font-weight: 500;
      color: var(--paces-text-dark);
      font-family: 'Poppins', sans-serif;
    }

    .metrics-grid-paces {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 12px;
      margin-bottom: 1.25rem;
    }
    .metric-card-paces {
      background: var(--paces-card-bg);
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius);
      box-shadow: var(--paces-shadow);
      padding: 10px 16px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card-paces:hover {
      transform: translateY(-2px);
      box-shadow: var(--paces-shadow-md);
    }
    .metric-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 4px;
    }
    .metric-label {
      font-size: 0.72rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--paces-text-muted);
    }
    .metric-icon-badge {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
    }
    .metric-icon-badge svg {
      width: 16px;
      height: 16px;
      stroke-width: 2;
    }
    .metric-value {
      font-size: 1.55rem;
      font-weight: 500;
      color: var(--paces-text-dark);
      font-family: 'Poppins', sans-serif;
      line-height: 1.15;
      margin-bottom: 0;
    }
    .metric-subtext {
      font-size: 0.78rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* Paces Subtle Colors */
    .bg-primary-subtle { background: var(--paces-primary-subtle); color: var(--paces-primary); border: 1px solid var(--paces-primary-border); }
    .bg-warning-subtle { background: var(--paces-warning-subtle); color: var(--paces-warning); border: 1px solid var(--paces-warning-border); }
    .bg-info-subtle { background: var(--paces-info-subtle); color: var(--paces-info); border: 1px solid var(--paces-info-border); }
    .bg-success-subtle { background: var(--paces-success-subtle); color: var(--paces-success); border: 1px solid var(--paces-success-border); }
    .bg-danger-subtle { background: var(--paces-danger-subtle); color: var(--paces-danger); border: 1px solid var(--paces-danger-border); }

    /* ---------------- Tables (Paces Style) ---------------- */
    .table-responsive-paces {
      overflow-x: auto;
    }
    .table-paces {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 0.875rem;
    }
    .table-paces th {
      background: #f8fafc;
      color: var(--paces-text-muted);
      font-weight: 500;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0.9rem 1.1rem;
      border-bottom: 1px solid var(--paces-border);
      border-top: 1px solid var(--paces-border);
      text-align: left;
    }
    .table-paces td {
      padding: 1rem 1.1rem;
      border-bottom: 1px solid var(--paces-border);
      color: var(--paces-text-dark);
      vertical-align: middle;
      background: #ffffff;
    }
    .table-paces tr:hover td {
      background: #f8fafc;
    }

    .badge-pill-paces {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    /* Buttons */
    .btn-paces {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 0.55rem 1rem;
      border-radius: var(--paces-radius-sm);
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      border: 1px solid transparent;
      transition: all 0.2s;
    }
    .btn-paces-primary {
      background: var(--paces-primary);
      color: #ffffff;
    }
    .btn-paces-primary:hover {
      background: #314eb8;
    }
    .btn-paces-outline {
      background: #ffffff;
      border-color: var(--paces-border);
      color: var(--paces-text-dark);
    }
    .btn-paces-outline:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }
    .btn-paces-sm {
      padding: 0.35rem 0.75rem;
      font-size: 0.78rem;
    }
    .btn-paces-danger {
      background: var(--paces-danger);
      color: #ffffff;
    }
    .btn-paces-danger:hover {
      background: #b91c1c;
    }
    .btn-paces-success {
      background: var(--paces-success);
      color: #ffffff;
    }
    .btn-paces-success:hover {
      background: #15803d;
    }

    /* Form Inputs & Controls */
    .form-label-paces {
      display: block;
      font-size: 0.82rem;
      font-weight: 500;
      color: var(--paces-text-dark);
      margin-bottom: 6px;
    }
    .form-control-paces {
      width: 100%;
      padding: 0.65rem 0.95rem;
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius-sm);
      background: #ffffff;
      font-family: inherit;
      font-size: 0.875rem;
      color: var(--paces-text-dark);
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
      outline: none;
    }
    .form-control-paces:focus {
      border-color: var(--paces-primary);
      box-shadow: 0 0 0 3px rgba(62, 96, 213, 0.12);
      background: #ffffff;
    }
    textarea.form-control-paces {
      min-height: 120px;
      line-height: 1.5;
      resize: vertical;
    }
    select, .form-select-paces, select.form-control-paces {
      appearance: none !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      background-color: #ffffff;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.75' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
      background-position: right 14px center !important;
      background-repeat: no-repeat !important;
      background-size: 16px 16px !important;
      padding-right: 2.85rem !important;
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius-sm);
      font-family: inherit;
      color: var(--paces-text-dark);
      cursor: pointer;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    select:focus, .form-select-paces:focus, select.form-control-paces:focus {
      border-color: var(--paces-primary) !important;
      box-shadow: 0 0 0 3px rgba(62, 96, 213, 0.12) !important;
      outline: none !important;
    }
    .form-help-paces {
      font-size: 0.78rem;
      color: var(--paces-text-muted);
      margin-top: 4px;
      display: block;
    }

    /* Aliases for legacy admin classes */
    .admin-panel-card {
      background: var(--paces-card-bg);
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius);
      box-shadow: var(--paces-shadow);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .panel-title {
      font-size: 1.1rem;
      font-weight: 500;
      color: var(--paces-text-dark);
      font-family: 'Poppins', sans-serif;
    }
    .panel-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.25rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--paces-border);
    }
    .form-control-admin {
      width: 100%;
      padding: 0.65rem 0.95rem;
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius-sm);
      background: #ffffff;
      font-family: inherit;
      font-size: 0.875rem;
      color: var(--paces-text-dark);
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
      outline: none;
    }
    .form-control-admin:focus {
      border-color: var(--paces-primary);
      box-shadow: 0 0 0 3px rgba(62, 96, 213, 0.12);
    }
    .btn-admin {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 0.55rem 1rem;
      border-radius: var(--paces-radius-sm);
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      border: 1px solid transparent;
      transition: all 0.2s;
    }
    .btn-admin-primary {
      background: var(--paces-primary);
      color: #ffffff;
    }
    .btn-admin-primary:hover {
      background: #314eb8;
    }
    .admin-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 0.875rem;
    }
    .admin-table th {
      background: #f8fafc;
      color: var(--paces-text-muted);
      font-weight: 500;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0.9rem 1.1rem;
      border-bottom: 1px solid var(--paces-border);
      border-top: 1px solid var(--paces-border);
      text-align: left;
    }
    .admin-table td {
      padding: 1rem 1.1rem;
      border-bottom: 1px solid var(--paces-border);
      color: var(--paces-text-dark);
      vertical-align: middle;
      background: #ffffff;
    }
    .admin-table tr:hover td {
      background: #f8fafc;
    }
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    /* Toast Notification Banner */
    #adminNotificationToast {
      position: fixed;
      top: 24px;
      right: 24px;
      z-index: 99999;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    /* Desktop Sidebar Collapsed State (Mini Sidebar: Icons Only) */
    @media (min-width: 993px) {
      body.sidebar-collapsed .app-sidebar {
        width: 72px !important;
        transform: none !important;
        overflow: visible !important;
      }
      body.sidebar-collapsed .app-main {
        margin-left: 72px !important;
        width: calc(100% - 72px) !important;
        max-width: calc(100% - 72px) !important;
      }
      body.sidebar-collapsed .brand-text {
        display: none !important;
      }
      body.sidebar-collapsed .sidebar-brand {
        padding: 0 !important;
        justify-content: center !important;
      }
      body.sidebar-collapsed .brand-link {
        justify-content: center !important;
      }
      body.sidebar-collapsed .brand-logo {
        height: 28px !important;
        margin: 0 auto !important;
      }
      body.sidebar-collapsed .nav-header {
        height: 1px !important;
        padding: 0 !important;
        margin: 10px 14px !important;
        background: var(--paces-sidebar-border) !important;
        font-size: 0 !important;
        overflow: hidden !important;
      }
      body.sidebar-collapsed .nav-item {
        padding: 0.7rem 0 !important;
        justify-content: center !important;
        position: relative !important;
        margin: 4px 8px !important;
        border-radius: var(--paces-radius-sm) !important;
      }
      body.sidebar-collapsed .nav-item .nav-icon {
        margin: 0 auto !important;
        width: 22px !important;
        height: 22px !important;
      }
      body.sidebar-collapsed .nav-item .nav-text,
      body.sidebar-collapsed .nav-item .nav-arrow {
        display: none !important;
      }
      body.sidebar-collapsed .nav-badge {
        position: absolute !important;
        top: 2px !important;
        right: 4px !important;
        font-size: 0.6rem !important;
        padding: 1px 4px !important;
        border-radius: 10px !important;
        line-height: 1.2 !important;
      }
      body.sidebar-collapsed .nav-submenu {
        display: none !important;
      }
      /* Clean floating tooltip on hover for collapsed mini-sidebar */
      body.sidebar-collapsed .nav-item[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(100% + 12px);
        top: 50%;
        transform: translateY(-50%);
        background: #0f172a;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        white-space: nowrap;
        z-index: 2000;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
        pointer-events: none;
        border: 1px solid rgba(255, 255, 255, 0.12);
        animation: fadeInPaces 0.15s ease-out;
      }
      body.sidebar-collapsed .sidebar-user-footer {
        padding: 0.75rem 0 !important;
        justify-content: center !important;
      }
      body.sidebar-collapsed .sidebar-user-footer .user-meta,
      body.sidebar-collapsed .sidebar-user-footer .sidebar-logout-btn {
        display: none !important;
      }
      body.sidebar-collapsed .sidebar-user-footer .user-avatar-sm {
        margin: 0 auto !important;
      }
    }

    /* Mobile Sidebar & Drawer Backdrop */
    .sidebar-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(2px);
      z-index: 1040;
      display: none;
      opacity: 0;
      transition: opacity 0.25s ease;
    }
    .sidebar-backdrop.show {
      display: block;
      opacity: 1;
    }

    .btn-sidebar-collapse {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 6px;
      color: #94a3b8;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
      margin-left: auto;
    }
    .btn-sidebar-collapse:hover {
      background: rgba(255, 255, 255, 0.15);
      color: #ffffff;
    }

    /* ---------------- Comprehensive Mobile & Tablet Responsive Rules ---------------- */
    @media (max-width: 992px) {
      .app-sidebar {
        position: fixed;
        left: -260px;
        top: 0;
        bottom: 0;
        width: 260px !important;
        z-index: 1050;
        transition: left 0.25s ease;
      }
      .app-sidebar.show {
        left: 0;
      }
      .app-main {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100vw !important;
      }
      .topbar-search-box {
        display: none;
      }
      .app-content {
        padding: 1rem 0.85rem;
      }
      .earnings-analytics-grid,
      .analytics-barchart-grid,
      .workorder-grid,
      .operational-insights-grid {
        grid-template-columns: 1fr !important;
      }
    }

    @media (max-width: 768px) {
      .app-topbar {
        height: 60px;
        padding: 0 0.85rem;
      }
      .app-content {
        padding: 0.85rem 0.65rem;
      }
      .page-header-box {
        margin-bottom: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
      .page-header-title {
        font-size: 1.15rem;
      }
      .card-paces {
        padding: 1rem 0.85rem;
        margin-bottom: 1rem;
        border-radius: 10px;
      }
      .card-header-paces {
        flex-wrap: wrap;
        gap: 8px;
      }
      .metrics-grid-paces {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
      }
      /* Collapse multi-column form and layout grids to single column on mobile */
      div[style*="grid-template-columns: 1fr 1fr"],
      div[style*="grid-template-columns:1fr 1fr"],
      div[style*="grid-template-columns: 1fr 1fr 1fr"],
      div[style*="grid-template-columns:1fr 1fr 1fr"],
      div[style*="grid-template-columns: 2fr 1fr 1fr"],
      div[style*="grid-template-columns:2fr 1fr 1fr"],
      div[style*="grid-template-columns: 1fr 2fr"],
      div[style*="grid-template-columns:1fr 2fr"],
      div[style*="grid-template-columns: 360px 1fr"],
      div[style*="grid-template-columns:360px 1fr"],
      div[style*="grid-template-columns: 340px 1fr"],
      div[style*="grid-template-columns:340px 1fr"],
      div[style*="grid-template-columns: 2fr 1fr"],
      div[style*="grid-template-columns:2fr 1fr"],
      .grid-2-col,
      .form-grid-paces {
        grid-template-columns: 1fr !important;
      }
      /* Modals responsive fit */
      .modal-backdrop-paces {
        padding: 0.5rem;
      }
      .modal-dialog-paces {
        width: 100% !important;
        max-width: 100% !important;
        max-height: 94vh !important;
        border-radius: 12px;
        margin: 0 auto;
      }
      .modal-header-paces {
        padding: 0.85rem 1rem;
      }
      .modal-body-paces {
        padding: 0.85rem 1rem !important;
        max-height: calc(88vh - 110px) !important;
      }
      .modal-footer-paces {
        padding: 0.75rem 1rem !important;
        flex-wrap: wrap;
        gap: 8px;
      }
      /* Tables mobile touch horizontal scroll */
      .card-paces:has(table),
      .table-responsive,
      .table-responsive-paces {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        width: 100% !important;
        display: block !important;
      }
      table.table-paces,
      table.admin-table {
        min-width: 540px;
      }
    }

    @media (max-width: 576px) {
      .topbar-user-info {
        display: none !important;
      }
      .app-topbar {
        padding: 0 0.65rem;
        height: 56px;
      }
      .topbar-left {
        gap: 8px;
      }
      .topbar-right {
        gap: 6px;
      }
      .metrics-grid-paces {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 6px !important;
      }
      .top-kpi-card {
        padding: 0.75rem 0.85rem !important;
        gap: 8px !important;
      }
      .top-kpi-card .metric-icon-badge {
        width: 36px !important;
        height: 36px !important;
      }
      .top-kpi-card .metric-icon-badge svg {
        width: 18px !important;
        height: 18px !important;
      }
      .top-kpi-value {
        font-size: 1.35rem !important;
      }
      .btn-paces {
        font-size: 0.78rem;
        padding: 0.4rem 0.75rem;
      }
      .topbar-user-menu {
        width: calc(100vw - 20px);
        right: -6px;
      }
    }
    /* ---------------- Paces Modern Modals ---------------- */
    .modal-backdrop-paces {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(15, 23, 42, 0.55);
      backdrop-filter: blur(4px);
      z-index: 1050;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      box-sizing: border-box;
      animation: fadeInPaces 0.18s ease-out;
    }

    .modal-dialog-paces {
      background: #ffffff;
      border-radius: 12px;
      width: 100%;
      box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.25);
      border: 1px solid var(--paces-border);
      display: flex;
      flex-direction: column;
      max-height: 90vh;
      overflow: hidden;
      animation: scaleUpPaces 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-header-paces {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--paces-border);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      background: #ffffff;
    }

    .modal-title-paces {
      margin: 0;
      font-size: 1.15rem;
      font-weight: 500;
      color: var(--paces-text-dark);
    }

    .modal-close-btn {
      background: none;
      border: none;
      font-size: 1.25rem;
      color: var(--paces-text-muted);
      cursor: pointer;
      padding: 2px 8px;
      line-height: 1;
      border-radius: 6px;
      transition: background 0.15s, color 0.15s;
    }

    .modal-close-btn:hover {
      background: #f1f5f9;
      color: var(--paces-text-dark);
    }

    .modal-body-paces {
      padding: 1.5rem;
      overflow-y: auto;
      flex: 1;
    }

    .modal-footer-paces {
      padding: 1.25rem 1.5rem;
      border-top: 1px solid var(--paces-border);
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      background: #f8fafc;
      flex-shrink: 0;
    }

    @keyframes fadeInPaces {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes scaleUpPaces {
      from { opacity: 0; transform: scale(0.96); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

  <!-- Paces Modern Sidebar -->
  <x-admin.sidebar />

  <!-- Main View Area -->
  <div class="app-main">
    <!-- Paces Topbar Header -->
    <x-admin.header />

    <!-- Dynamic Content Slot -->
    <main class="app-content">
      {{ $slot }}
    </main>
  </div>

  <!-- Sidebar Drawer Backdrop for Mobile View -->
  <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="closeMobileSidebar()"></div>

  <!-- Global Reusable Confirmation Dialog for Logout & Sensitive Admin Actions -->
  <div id="adminConfirmModal" class="modal-backdrop-paces" style="display:none; z-index:100000;">
    <div class="modal-dialog-paces" style="max-width:440px; border-radius:14px; overflow:hidden; border:none; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
      <div style="padding:1.75rem 1.5rem 1.25rem; text-align:center;">
        <div id="adminConfirmIcon" style="width:58px; height:58px; border-radius:50%; background:#fee2e2; color:#ef4444; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1rem; box-shadow:0 4px 12px rgba(239,68,68,0.15);">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <h3 id="adminConfirmTitle" style="font-size:1.15rem; font-weight:600; color:#1e293b; margin:0 0 8px;">Confirm Action</h3>
        <p id="adminConfirmMessage" style="font-size:0.875rem; color:#64748b; margin:0; line-height:1.5;">Are you sure you want to perform this sensitive action?</p>
      </div>
      <div style="display:flex; gap:10px; padding:1rem 1.5rem 1.5rem; background:#f8fafc; border-top:1px solid var(--paces-border); justify-content:flex-end;">
        <button type="button" id="adminConfirmCancelBtn" onclick="closeAdminConfirmModal()" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:0.5rem 1.25rem; font-weight:500;">Cancel</button>
        <button type="button" id="adminConfirmProceedBtn" class="btn-paces btn-paces-primary btn-paces-sm" style="padding:0.5rem 1.25rem; background:#dc2626; border-color:#dc2626; font-weight:500;">Proceed</button>
      </div>
    </div>
  </div>

  <!-- Real-time Admin Alert Notification Container -->
  <div id="adminNotificationToast"></div>

  <!-- Real-Time Notification Polling & Audio Chime Script -->
  <script>
    let audioCtx = null;

    function initAudioContext() {
      if (!audioCtx) {
        audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      }
      if (audioCtx.state === 'suspended') {
        audioCtx.resume();
      }
    }

    // Play synthesized attention-getting chime for operations center
    function playChime() {
      try {
        initAudioContext();
        const now = audioCtx.currentTime;
        
        // Tone 1 (High)
        const osc1 = audioCtx.createOscillator();
        const gain1 = audioCtx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(880, now);
        osc1.frequency.exponentialRampToValueAtTime(1320, now + 0.15);
        gain1.gain.setValueAtTime(0.3, now);
        gain1.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
        osc1.connect(gain1);
        gain1.connect(audioCtx.destination);
        osc1.start(now);
        osc1.stop(now + 0.35);

        // Tone 2 (Harmonic alert chime)
        const osc2 = audioCtx.createOscillator();
        const gain2 = audioCtx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(1760, now + 0.15);
        gain2.gain.setValueAtTime(0.35, now + 0.15);
        gain2.gain.exponentialRampToValueAtTime(0.01, now + 0.6);
        osc2.connect(gain2);
        gain2.connect(audioCtx.destination);
        osc2.start(now + 0.15);
        osc2.stop(now + 0.6);
      } catch (e) {
        console.warn('Audio chime triggered:', e);
      }
    }

    // Toggle sidebar on mobile and desktop
    function toggleAdminSidebar() {
      const isMobile = window.innerWidth <= 992;
      const sidebar = document.getElementById('appSidebar');
      const backdrop = document.getElementById('sidebarBackdrop');

      if (isMobile) {
        if (sidebar) {
          const isOpen = sidebar.classList.toggle('show');
          if (backdrop) {
            backdrop.classList.toggle('show', isOpen);
          }
        }
      } else {
        const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
        try {
          localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? '1' : '0');
        } catch (e) {}
        setTimeout(() => {
          window.dispatchEvent(new Event('resize'));
        }, 260);
      }
    }

    function closeMobileSidebar() {
      const sidebar = document.getElementById('appSidebar');
      const backdrop = document.getElementById('sidebarBackdrop');
      if (sidebar) sidebar.classList.remove('show');
      if (backdrop) backdrop.classList.remove('show');
    }

    // Initialize sidebar collapse state preference on desktop
    document.addEventListener('DOMContentLoaded', () => {
      try {
        if (window.innerWidth > 992 && localStorage.getItem('admin_sidebar_collapsed') === '1') {
          document.body.classList.add('sidebar-collapsed');
        }
      } catch (e) {}
    });

    // Reusable Confirmation Modal Helper
    let confirmProceedCallback = null;

    function openAdminConfirmModal({
      title = 'Confirm Action',
      message = 'Are you sure you want to proceed?',
      confirmText = 'Proceed',
      confirmColor = '#dc2626',
      iconType = 'danger',
      onConfirm = null
    }) {
      const modal = document.getElementById('adminConfirmModal');
      const titleEl = document.getElementById('adminConfirmTitle');
      const msgEl = document.getElementById('adminConfirmMessage');
      const btnEl = document.getElementById('adminConfirmProceedBtn');
      const iconEl = document.getElementById('adminConfirmIcon');

      if (!modal) {
        if (window.confirm(`${title}\n\n${message}`)) {
          if (typeof onConfirm === 'function') onConfirm();
        }
        return;
      }

      if (titleEl) titleEl.innerText = title;
      if (msgEl) msgEl.innerText = message;
      if (btnEl) {
        btnEl.innerText = confirmText;
        btnEl.style.background = confirmColor;
        btnEl.style.borderColor = confirmColor;
      }

      if (iconEl) {
        if (iconType === 'warning') {
          iconEl.style.background = '#fef3c7';
          iconEl.style.color = '#d97706';
          iconEl.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
        } else {
          iconEl.style.background = '#fee2e2';
          iconEl.style.color = '#ef4444';
          iconEl.innerHTML = `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`;
        }
      }

      confirmProceedCallback = onConfirm;
      modal.style.display = 'flex';
    }

    function closeAdminConfirmModal() {
      const modal = document.getElementById('adminConfirmModal');
      if (modal) modal.style.display = 'none';
      confirmProceedCallback = null;
    }

    document.getElementById('adminConfirmProceedBtn')?.addEventListener('click', function() {
      if (typeof confirmProceedCallback === 'function') {
        const cb = confirmProceedCallback;
        closeAdminConfirmModal();
        cb();
      } else {
        closeAdminConfirmModal();
      }
    });

    // Close on backdrop click
    document.getElementById('adminConfirmModal')?.addEventListener('click', function(e) {
      if (e.target === this) {
        closeAdminConfirmModal();
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeAdminConfirmModal();
        closeMobileSidebar();
      }
    });

    // Confirmation for Administrator Logout
    function confirmAdminLogout(formOrEvent) {
      if (formOrEvent && formOrEvent.preventDefault) {
        formOrEvent.preventDefault();
      }
      const form = (formOrEvent && formOrEvent.target && formOrEvent.target.tagName === 'FORM') ? formOrEvent.target : (formOrEvent?.tagName === 'FORM' ? formOrEvent : document.getElementById('adminLogoutForm') || document.querySelector('form[action*="logout"]'));

      openAdminConfirmModal({
        title: 'Sign Out of Admin Console?',
        message: 'Are you sure you want to sign out? You will need your administrator credentials to access the command center again.',
        confirmText: 'Sign Out',
        confirmColor: '#dc2626',
        iconType: 'warning',
        onConfirm: () => {
          if (form) {
            form.submit();
          }
        }
      });
      return false;
    }

    // Intercept forms with data-confirm
    document.addEventListener('submit', function(e) {
      const form = e.target;
      if (form && form.hasAttribute('data-confirm')) {
        e.preventDefault();
        const msg = form.getAttribute('data-confirm');
        const title = form.getAttribute('data-confirm-title') || 'Confirm Action';
        const btnText = form.getAttribute('data-confirm-btn') || 'Proceed';
        openAdminConfirmModal({
          title: title,
          message: msg,
          confirmText: btnText,
          confirmColor: '#dc2626',
          iconType: 'danger',
          onConfirm: () => {
            form.removeAttribute('data-confirm');
            form.submit();
          }
        });
      }
    });

    // Toggle collapsible submenu group
    function toggleNavGroup(btn) {
      const group = btn.closest('.nav-group');
      if (group) {
        group.classList.toggle('open');
      }
    }

    // Poll for real-time inbound booking alerts every 15s
    async function pollInboundAlerts() {
      try {
        const res = await fetch('{{ route('admin.alerts.check') }}', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (res.ok) {
          const data = await res.json();
          const badge = document.getElementById('topbarAlertBadge');
          if (badge) badge.innerText = data.unread_total || 0;

          if (data.has_sound) {
            playChime();
            if (data.alerts && data.alerts.length > 0) {
              data.alerts.forEach(alert => {
                showAdminToast(alert.title, alert.message, alert.type === 'emergency_alert');
              });
            }
          }
        }
      } catch (err) {
        // silent polling error
      }
    }

    function showAdminToast(title, msg, isEmergency = false) {
      const container = document.getElementById('adminNotificationToast');
      if (!container) return;

      const toast = document.createElement('div');
      toast.style.cssText = `
        background: ${isEmergency ? '#fef2f2' : '#ffffff'};
        border: 1px solid ${isEmergency ? '#f87171' : '#e2e8f0'};
        border-left: 5px solid ${isEmergency ? '#dc2626' : '#3e60d5'};
        padding: 14px 18px;
        border-radius: 10px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        max-width: 380px;
        color: #1e293b;
        animation: slideIn 0.3s ease;
      `;

      toast.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:4px;">
          <strong style="font-size:0.9rem; color:${isEmergency ? '#dc2626' : '#1e293b'};">${title}</strong>
          <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:0.75rem; color:#64748b; font-weight:500;">JUST NOW</span>
            <button type="button" onclick="this.closest('div[style*=\\'position\\'], div[style*=\\'box-shadow\\']').remove()" style="background:none; border:none; color:#64748b; cursor:pointer; font-size:1.15rem; line-height:1; padding:0;" aria-label="Close">&times;</button>
          </div>
        </div>
        <p style="margin:0; font-size:0.825rem; color:#475569;">${msg}</p>
      `;

      container.appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 7000);
    }

    document.addEventListener('click', () => initAudioContext(), { once: true });
    setInterval(pollInboundAlerts, 15000);
  </script>
</body>
</html>
