<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? ($headerTitle ?? 'Operations Command Center') }} | FIX Facility Operations (Paces)</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--paces-bg);
      color: var(--paces-text-dark);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ---------------- Sidebar (Paces Dark Sidebar) ---------------- */
    .app-sidebar {
      width: 260px;
      background: var(--paces-sidebar-bg);
      border-right: 1px solid var(--paces-sidebar-border);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      transition: all 0.25s ease;
      z-index: 1050;
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
      font-weight: 800;
      letter-spacing: 0.05em;
      color: #ffffff;
      font-family: 'Poppins', sans-serif;
    }
    .brand-badge {
      font-size: 0.6rem;
      font-weight: 800;
      color: #f59e0b;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .sidebar-menu-wrapper {
      padding: 1.25rem 0.85rem;
      flex: 1;
      overflow-y: auto;
    }
    .nav-header {
      font-size: 0.68rem;
      font-weight: 700;
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
      font-weight: 600;
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
      font-weight: 800;
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
      font-weight: 700;
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
      font-weight: 600;
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
      font-weight: 700;
      font-size: 0.85rem;
      flex-shrink: 0;
    }
    .user-meta {
      flex: 1;
      min-width: 0;
    }
    .user-name {
      font-size: 0.85rem;
      font-weight: 700;
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
      font-weight: 700;
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
      font-weight: 700;
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
      font-weight: 800;
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
      font-weight: 700;
      transition: all 0.2s;
    }
    .btn-topbar-site:hover {
      background: #e2e8f0;
      color: #000;
    }

    .topbar-user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      padding-left: 6px;
    }
    .topbar-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--paces-primary);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .topbar-user-info {
      display: flex;
      flex-direction: column;
    }
    .topbar-user-name {
      font-size: 0.85rem;
      font-weight: 700;
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
      font-weight: 800;
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
      font-weight: 700;
      color: var(--paces-text-dark);
      font-family: 'Poppins', sans-serif;
    }

    .metrics-grid-paces {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.25rem;
      margin-bottom: 1.75rem;
    }
    .metric-card-paces {
      background: var(--paces-card-bg);
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius);
      box-shadow: var(--paces-shadow);
      padding: 1.35rem;
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
      margin-bottom: 12px;
    }
    .metric-label {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--paces-text-muted);
    }
    .metric-icon-badge {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .metric-icon-badge svg {
      width: 22px;
      height: 22px;
      stroke-width: 2;
    }
    .metric-value {
      font-size: 2rem;
      font-weight: 800;
      color: var(--paces-text-dark);
      font-family: 'Poppins', sans-serif;
      line-height: 1.1;
      margin-bottom: 8px;
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
      font-weight: 700;
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
      font-weight: 700;
    }

    /* Buttons */
    .btn-paces {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 0.55rem 1rem;
      border-radius: var(--paces-radius-sm);
      font-size: 0.85rem;
      font-weight: 600;
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
      font-weight: 700;
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
      font-weight: 800;
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
      font-weight: 600;
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
      font-weight: 700;
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
      font-weight: 700;
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

    @media (max-width: 992px) {
      .app-sidebar {
        position: fixed;
        left: -260px;
        top: 0;
        bottom: 0;
      }
      .app-sidebar.show {
        left: 0;
      }
      .topbar-search-box {
        display: none;
      }
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

    // Toggle sidebar on mobile
    function toggleAdminSidebar() {
      const sidebar = document.getElementById('appSidebar');
      if (sidebar) sidebar.classList.toggle('show');
    }

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
          <span style="font-size:0.75rem; color:#64748b; font-weight:700;">JUST NOW</span>
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
