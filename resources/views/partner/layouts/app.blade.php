<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>{{ $title ?? 'Partner Portal' }} | {{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Management') }}</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}?v=white">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=white">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --p-primary: #0f172a;
      --p-accent: #0d9488;
      --p-accent-dark: #0f766e;
      --p-accent-light: #f0fdfa;
      --p-bg: #f8fafc;
      --p-card: #ffffff;
      --p-border: #e2e8f0;
      --p-border-light: #f1f5f9;
      --p-text: #0f172a;
      --p-muted: #64748b;
      --p-success: #10b981;
      --p-warning: #f59e0b;
      --p-danger: #ef4444;
      --p-radius: 14px;
      --p-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
      --p-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
      --p-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
    body {
      background: var(--p-bg);
      color: var(--p-text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 74px;
      -webkit-font-smoothing: antialiased;
    }
    @media (min-width: 992px) {
      body { padding-bottom: 0; }
    }

    /* Top Navbar */
    .p-navbar {
      background: #ffffff;
      border-bottom: 1px solid var(--p-border);
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }
    .p-nav-inner {
      max-width: 1240px;
      margin: 0 auto;
      padding: 0 20px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .p-brand-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .p-brand {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      color: var(--p-primary);
    }
    .p-brand img {
      height: 28px;
      max-width: 120px;
      object-fit: contain;
      vertical-align: middle;
      display: block;
    }
    .p-brand-badge {
      background: #f0fdfa;
      color: var(--p-accent);
      border: 1px solid #ccfbf1;
      font-size: 0.6rem;
      font-weight: 700;
      padding: 2px 6px;
      border-radius: 4px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      line-height: 1.15;
    }

    /* Desktop Navigation Links */
    .p-nav-links {
      display: none;
      align-items: center;
      gap: 4px;
    }
    @media (min-width: 992px) {
      .p-nav-links { display: flex; }
    }
    .p-nav-link {
      color: #64748b;
      text-decoration: none;
      font-size: 0.84rem;
      font-weight: 600;
      padding: 7px 12px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 7px;
      transition: all 0.15s ease;
    }
    .p-nav-link:hover {
      background: #f1f5f9;
      color: var(--p-primary);
    }
    .p-nav-link.active {
      background: #f0fdfa;
      color: var(--p-accent);
      font-weight: 700;
    }
    .p-nav-link svg {
      width: 17px;
      height: 17px;
    }

    .p-nav-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .p-nav-notif-btn {
      position: relative;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: #f8fafc;
      border: 1px solid var(--p-border);
      color: #475569;
      text-decoration: none;
      transition: all 0.15s ease;
      cursor: pointer;
    }
    .p-nav-notif-btn:hover {
      background: #f1f5f9;
      color: var(--p-primary);
      border-color: #cbd5e1;
    }
    .p-nav-notif-btn svg {
      width: 19px;
      height: 19px;
    }
    .p-notif-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: #ef4444;
      color: #ffffff;
      font-size: 0.65rem;
      font-weight: 800;
      min-width: 18px;
      height: 18px;
      line-height: 14px;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 4px;
      border: 2px solid #ffffff;
      box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Status Pill Toggle */
    .status-badge-btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 6px 13px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
      cursor: pointer;
      border: 1px solid transparent;
      transition: all 0.15s ease;
      background: none;
      line-height: 1;
    }
    .status-badge-btn:hover {
      transform: translateY(-1px);
    }
    .status-badge-btn.online {
      background: #ecfdf5;
      color: #047857;
      border-color: #a7f3d0;
    }
    .status-badge-btn.offline {
      background: #fef2f2;
      color: #b91c1c;
      border-color: #fecaca;
    }

    @keyframes pulseDot {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
      70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
    }
    .status-dot.online {
      background: #10b981;
      animation: pulseDot 2s infinite;
    }
    .status-dot.offline {
      background: #ef4444;
    }

    /* Main Container */
    .p-main {
      flex: 1;
      max-width: 1240px;
      width: 100%;
      margin: 0 auto;
      padding: 24px 20px;
    }

    /* Modern Cards */
    .p-card {
      background: var(--p-card);
      border: 1px solid var(--p-border);
      border-radius: var(--p-radius);
      box-shadow: var(--p-shadow);
      padding: 20px;
      margin-bottom: 20px;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .p-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }
    .p-card-title {
      font-size: 0.98rem;
      font-weight: 700;
      color: var(--p-primary);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .p-card-title svg {
      width: 18px;
      height: 18px;
      color: var(--p-accent);
    }

    /* Metric Stat Counters Grid (2x2 on mobile, 4 in a row on desktop) */
    .p-stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }
    @media (min-width: 768px) {
      .p-stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 22px;
      }
    }
    .p-stat-card {
      margin-bottom: 0 !important;
      padding: 14px 14px;
    }
    @media (min-width: 768px) {
      .p-stat-card {
        padding: 18px 20px;
      }
    }
    .p-stat-label {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--p-muted);
      text-transform: uppercase;
      letter-spacing: 0.04em;
      display: block;
      line-height: 1.25;
    }
    .p-stat-value {
      font-size: 1.55rem;
      font-weight: 800;
      margin-top: 4px;
      line-height: 1.1;
      white-space: nowrap;
    }
    @media (min-width: 768px) {
      .p-stat-value {
        font-size: 1.75rem;
      }
    }
    .p-stat-icon {
      width: 36px;
      height: 36px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    @media (min-width: 768px) {
      .p-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
      }
    }

    /* Standard Buttons */
    .btn-p {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 0.82rem;
      font-weight: 600;
      padding: 8px 14px;
      border-radius: 8px;
      border: 1px solid transparent;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s ease;
      line-height: 1.25;
    }
    .btn-p svg {
      width: 15px;
      height: 15px;
    }
    .btn-p-primary {
      background: var(--p-accent);
      color: #ffffff;
      border-color: var(--p-accent);
    }
    .btn-p-primary:hover {
      background: var(--p-accent-dark);
      border-color: var(--p-accent-dark);
    }
    .btn-p-outline {
      background: #ffffff;
      border-color: var(--p-border);
      color: #334155;
    }
    .btn-p-outline:hover {
      background: #f8fafc;
      color: var(--p-primary);
      border-color: #cbd5e1;
    }
    .btn-p-danger {
      background: #ef4444;
      color: #ffffff;
      border-color: #ef4444;
    }
    .btn-p-danger:hover {
      background: #dc2626;
      border-color: #dc2626;
    }
    .btn-p-success {
      background: #10b981;
      color: #ffffff;
      border-color: #10b981;
    }
    .btn-p-success:hover {
      background: #059669;
      border-color: #059669;
    }

    /* Standard Badges */
    .p-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.72rem;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 6px;
      line-height: 1.2;
    }
    .p-badge-warning { background: #fef3c7; color: #92400e; }
    .p-badge-info { background: #e0f2fe; color: #0369a1; }
    .p-badge-purple { background: #f3e8ff; color: #6b21a8; }
    .p-badge-success { background: #dcfce7; color: #15803d; }
    .p-badge-danger { background: #fee2e2; color: #b91c1c; }
    .p-badge-neutral { background: #f1f5f9; color: #475569; }

    /* Standard Tables */
    .p-table-wrapper {
      width: 100%;
      overflow-x: auto;
      border: 1px solid var(--p-border);
      border-radius: 10px;
      background: #ffffff;
    }
    .p-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.85rem;
      text-align: left;
    }
    .p-table th {
      background: #f8fafc;
      padding: 11px 14px;
      font-size: 0.74rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #64748b;
      border-bottom: 1px solid var(--p-border);
    }
    .p-table td {
      padding: 13px 14px;
      border-bottom: 1px solid #f1f5f9;
      color: #334155;
      vertical-align: middle;
    }
    .p-table tbody tr:last-child td { border-bottom: none; }
    .p-table tbody tr:hover { background: #fafafa; }

    /* Flash Alerts & Toasts */
    .p-alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.84rem;
      font-weight: 600;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: opacity 0.35s ease, transform 0.35s ease, max-height 0.35s ease, margin 0.35s ease, padding 0.35s ease;
      overflow: hidden;
      max-height: 120px;
    }
    .p-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .p-alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .p-alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
    .p-alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    .p-alert.p-alert-hiding {
      opacity: 0;
      transform: translateY(-8px);
      max-height: 0;
      margin-top: 0;
      margin-bottom: 0;
      padding-top: 0;
      padding-bottom: 0;
      border-width: 0;
    }

    /* Forms */
    .p-input {
      width: 100%;
      height: 40px;
      padding: 8px 12px;
      border: 1px solid var(--p-border);
      border-radius: 8px;
      font-size: 0.86rem;
      color: #1e293b;
      background: #ffffff;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .p-input:focus {
      border-color: var(--p-accent);
      box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
    }
    .p-label {
      display: block;
      font-size: 0.77rem;
      font-weight: 700;
      color: #475569;
      margin-bottom: 5px;
    }

    /* Modal Backdrop & Alert Dialog System */
    .fix-dialog-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      animation: fixFadeIn 0.18s ease-out;
    }
    .fix-dialog-box {
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      max-width: 390px;
      width: 100%;
      padding: 26px;
      text-align: center;
      animation: fixScaleUp 0.18s ease-out;
      border: 1px solid rgba(255, 255, 255, 0.8);
    }
    @keyframes fixFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fixScaleUp { from { transform: scale(0.92); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    .fix-dialog-icon {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      margin: 0 auto 14px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .fix-dialog-title {
      font-size: 1.08rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 6px;
      letter-spacing: -0.01em;
    }
    .fix-dialog-text {
      font-size: 0.84rem;
      color: #64748b;
      line-height: 1.45;
      margin-bottom: 22px;
    }
    .fix-dialog-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
    }
    .fix-dialog-actions button {
      flex: 1;
      padding: 10px 16px;
      font-size: 0.85rem;
      font-weight: 700;
    }

    /* Mobile Bottom Navigation */
    .p-bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 62px;
      background: #ffffff;
      border-top: 1px solid var(--p-border);
      display: flex;
      align-items: center;
      justify-content: space-around;
      z-index: 99;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.03);
    }
    @media (min-width: 992px) {
      .p-bottom-nav { display: none; }
    }
    .p-bottom-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 4px;
      text-decoration: none;
      color: #64748b;
      font-size: 0.68rem;
      font-weight: 600;
      flex: 1;
      height: 100%;
      transition: color 0.15s ease;
    }
    .p-bottom-item.active { color: var(--p-accent); font-weight: 700; }
    .p-bottom-item svg { width: 20px; height: 20px; }
  </style>
  {{ $styles ?? '' }}
</head>
<body>

  <!-- Top Navbar -->
  <header class="p-navbar">
    <div class="p-nav-inner">
      <div class="p-brand-group">
        <a href="{{ route('partner.dashboard') }}" class="p-brand">
          <img src="{{ asset(\App\Models\SiteSetting::get('header_logo', 'assets/images/logo-dark-crop.png')) }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'FIX') }}">
        </a>
        <span class="p-brand-badge">{{ (auth('partner')->user()?->partner_type === 'company' || auth('partner')->user()?->isVendor()) ? 'Vendor' : 'Crew' }}</span>
      </div>

      <!-- Desktop Nav -->
      <nav class="p-nav-links">
        <a href="{{ route('partner.dashboard') }}" class="p-nav-link {{ request()->routeIs('partner.dashboard') ? 'active' : '' }}">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
          Dashboard
        </a>
        <a href="{{ route('partner.jobs.index') }}" class="p-nav-link {{ request()->routeIs('partner.jobs.*') ? 'active' : '' }}">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Work Orders
        </a>
        <a href="{{ route('partner.availability.index') }}" class="p-nav-link {{ request()->routeIs('partner.availability.*') ? 'active' : '' }}">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          Leave
        </a>
        @if(auth('partner')->user()?->isVendor())
          <a href="{{ route('partner.crews.index') }}" class="p-nav-link {{ request()->routeIs('partner.crews.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            My Crews
          </a>
        @endif
        <a href="{{ route('partner.payouts.index') }}" class="p-nav-link {{ request()->routeIs('partner.payouts.*') ? 'active' : '' }}">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          Earnings
        </a>
        <a href="{{ route('partner.profile.index') }}" class="p-nav-link {{ request()->routeIs('partner.profile.*') ? 'active' : '' }}">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          Settings
        </a>
      </nav>

      <!-- Nav Actions -->
      <div class="p-nav-actions">
        <!-- In-App Notification Bell -->
        @php
          $partnerUnreadCount = auth('partner')->user()?->unreadNotificationsCount() ?? 0;
        @endphp
        <a href="{{ route('partner.notifications.index') }}" class="p-nav-notif-btn" title="Notifications">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          @if($partnerUnreadCount > 0)
            <span class="p-notif-badge">{{ $partnerUnreadCount > 99 ? '99+' : $partnerUnreadCount }}</span>
          @endif
        </a>

        <!-- Quick Availability Toggle with Alert Dialog -->
        <form id="navAvailabilityForm" action="{{ route('partner.availability.toggle') }}" method="POST" style="margin:0;">
          @csrf
          @if(auth('partner')->user()?->is_currently_offline)
            <button type="button" class="status-badge-btn offline" onclick="confirmDialog({ title:'Set Available?', text:'You will become available for new jobs immediately.', type:'success', confirmText:'Set Available', formId:'navAvailabilityForm' })" title="Switch to Available">
              <span class="status-dot offline"></span>
              <span>Unavailable</span>
            </button>
          @else
            <button type="button" class="status-badge-btn online" onclick="confirmDialog({ title:'Set Unavailable?', text:'You will not receive new job dispatches while unavailable.', type:'warning', confirmText:'Set Unavailable', formId:'navAvailabilityForm' })" title="Switch to Unavailable">
              <span class="status-dot online"></span>
              <span>Available</span>
            </button>
          @endif
        </form>

        <!-- Partner Profile Avatar -->
        @php
          $partnerAuth = auth('partner')->user();
        @endphp
        <a href="{{ route('partner.profile.index') }}" title="{{ $partnerAuth?->contact_person ?? 'Profile Settings' }}" style="display:flex; align-items:center; text-decoration:none; margin-left:4px;">
          <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, var(--p-accent) 0%, var(--p-primary) 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; overflow:hidden; border:2px solid #e2e8f0; flex-shrink:0; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            @if($partnerAuth?->avatar_url)
              <img src="{{ $partnerAuth->avatar_url }}" alt="{{ $partnerAuth->contact_person }}" style="width:100%; height:100%; object-fit:cover; display:block;">
            @else
              <span>{{ $partnerAuth?->initials ?? 'CR' }}</span>
            @endif
          </div>
        </a>
      </div>
    </div>
  </header>

  <!-- Flash Alerts / Toasts -->
  <div id="pFlashAlertsContainer" style="max-width:1240px; margin:16px auto 0; padding:0 20px; width:100%;">
    @if(session('success'))
      <div class="p-alert p-alert-success" data-auto-dismiss="4000">
        <div style="display:flex; align-items:center; gap:8px;">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="dismissPartnerAlert(this.closest('.p-alert'))" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.2rem;line-height:1;padding:0 4px;" aria-label="Close">&times;</button>
      </div>
    @endif
    @if(session('error'))
      <div class="p-alert p-alert-danger" data-auto-dismiss="4500">
        <div style="display:flex; align-items:center; gap:8px;">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="dismissPartnerAlert(this.closest('.p-alert'))" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.2rem;line-height:1;padding:0 4px;" aria-label="Close">&times;</button>
      </div>
    @endif
    @if(session('info'))
      <div class="p-alert p-alert-info" data-auto-dismiss="4000">
        <div style="display:flex; align-items:center; gap:8px;">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
          <span>{{ session('info') }}</span>
        </div>
        <button type="button" onclick="dismissPartnerAlert(this.closest('.p-alert'))" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.2rem;line-height:1;padding:0 4px;" aria-label="Close">&times;</button>
      </div>
    @endif
  </div>

  <!-- Main View Body -->
  <main class="p-main">
    {{ $slot ?? '' }}
    @yield('content')
  </main>

  <!-- Mobile Bottom Nav -->
  <nav class="p-bottom-nav">
    <a href="{{ route('partner.dashboard') }}" class="p-bottom-item {{ request()->routeIs('partner.dashboard') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
      <span>Home</span>
    </a>
    <a href="{{ route('partner.jobs.index') }}" class="p-bottom-item {{ request()->routeIs('partner.jobs.*') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      <span>Jobs</span>
    </a>
    <a href="{{ route('partner.availability.index') }}" class="p-bottom-item {{ request()->routeIs('partner.availability.*') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
      <span>Leave</span>
    </a>
    @if(auth('partner')->user()?->isVendor())
      <a href="{{ route('partner.crews.index') }}" class="p-bottom-item {{ request()->routeIs('partner.crews.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Crews</span>
      </a>
    @endif
    <a href="{{ route('partner.payouts.index') }}" class="p-bottom-item {{ request()->routeIs('partner.payouts.*') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
      <span>Earnings</span>
    </a>
    <a href="{{ route('partner.profile.index') }}" class="p-bottom-item {{ request()->routeIs('partner.profile.*') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
      <span>Settings</span>
    </a>
  </nav>

  <!-- Global Action Alert Confirmation Dialog -->
  <div id="fixActionModal" class="fix-dialog-backdrop" style="display:none;" onclick="if(event.target===this) closeConfirmDialog()">
    <div class="fix-dialog-box">
      <div id="fixModalIcon" class="fix-dialog-icon"></div>
      <h3 id="fixModalTitle" class="fix-dialog-title">Confirm Action</h3>
      <p id="fixModalText" class="fix-dialog-text">Are you sure you want to proceed?</p>
      <div class="fix-dialog-actions">
        <button type="button" class="btn-p btn-p-outline" onclick="closeConfirmDialog()">Cancel</button>
        <button type="button" id="fixModalConfirmBtn" class="btn-p btn-p-primary">Confirm</button>
      </div>
    </div>
  </div>

  <script>
    let currentDialogCallback = null;

    function confirmDialog(opts) {
      const modal = document.getElementById('fixActionModal');
      const titleEl = document.getElementById('fixModalTitle');
      const textEl = document.getElementById('fixModalText');
      const iconEl = document.getElementById('fixModalIcon');
      const confirmBtn = document.getElementById('fixModalConfirmBtn');

      titleEl.textContent = opts.title || 'Confirm Action';
      textEl.textContent = opts.text || 'Are you sure you want to proceed?';
      confirmBtn.textContent = opts.confirmText || 'Confirm';

      const type = opts.type || 'primary';
      if (type === 'danger') {
        iconEl.style.background = '#fef2f2';
        iconEl.style.color = '#ef4444';
        confirmBtn.className = 'btn-p btn-p-danger';
        confirmBtn.style.background = '';
        confirmBtn.style.borderColor = '';
        iconEl.innerHTML = '<svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
      } else if (type === 'success') {
        iconEl.style.background = '#ecfdf5';
        iconEl.style.color = '#10b981';
        confirmBtn.className = 'btn-p btn-p-success';
        confirmBtn.style.background = '';
        confirmBtn.style.borderColor = '';
        iconEl.innerHTML = '<svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
      } else if (type === 'warning') {
        iconEl.style.background = '#fffbeb';
        iconEl.style.color = '#f59e0b';
        confirmBtn.className = 'btn-p btn-p-primary';
        confirmBtn.style.background = '#f59e0b';
        confirmBtn.style.borderColor = '#f59e0b';
        iconEl.innerHTML = '<svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
      } else {
        iconEl.style.background = '#f0fdfa';
        iconEl.style.color = '#0d9488';
        confirmBtn.className = 'btn-p btn-p-primary';
        confirmBtn.style.background = '';
        confirmBtn.style.borderColor = '';
        iconEl.innerHTML = '<svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>';
      }

      currentDialogCallback = function() {
        closeConfirmDialog();
        if (opts.formId) {
          document.getElementById(opts.formId)?.submit();
        } else if (opts.formElement) {
          opts.formElement.submit();
        } else if (typeof opts.onConfirm === 'function') {
          opts.onConfirm();
        }
      };

      confirmBtn.onclick = currentDialogCallback;
      modal.style.display = 'flex';
    }

    function closeConfirmDialog() {
      const modal = document.getElementById('fixActionModal');
      if (modal) modal.style.display = 'none';
      currentDialogCallback = null;
    }

    // Auto-intercept forms with data-confirm
    document.addEventListener('submit', function(e) {
      const form = e.target;
      if (form.hasAttribute('data-confirm') && !form.dataset.confirmed) {
        e.preventDefault();
        confirmDialog({
          title: form.dataset.confirmTitle || 'Confirm Action',
          text: form.dataset.confirm || 'Are you sure you want to proceed?',
          type: form.dataset.confirmType || 'primary',
          confirmText: form.dataset.confirmBtn || 'Confirm',
          onConfirm: function() {
            form.dataset.confirmed = 'true';
            form.submit();
          }
        });
      }
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeConfirmDialog();
    });

    // Dismiss partner alert / toast with smooth fade & collapse animation
    function dismissPartnerAlert(el) {
      if (!el || el.classList.contains('p-alert-hiding')) return;
      el.classList.add('p-alert-hiding');
      setTimeout(function() {
        if (el && el.parentElement) {
          el.remove();
        }
      }, 360);
    }
    window.dismissPartnerAlert = dismissPartnerAlert;

    // Auto-dismiss all flash alerts after a short delay (default: 4s)
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.p-alert[data-auto-dismiss], .p-alert');
      alerts.forEach(function(alert) {
        const timeout = parseInt(alert.getAttribute('data-auto-dismiss') || '4000', 10);
        setTimeout(function() {
          dismissPartnerAlert(alert);
        }, timeout);
      });
    });

    // Programmatic toast utility for dynamic partner actions
    window.showPartnerToast = function(message, type = 'success', duration = 4000) {
      let container = document.getElementById('pFlashAlertsContainer');
      if (!container) {
        container = document.createElement('div');
        container.id = 'pFlashAlertsContainer';
        container.style.cssText = 'max-width:1240px; margin:16px auto 0; padding:0 20px; width:100%;';
        const main = document.querySelector('.p-main');
        if (main && main.parentNode) {
          main.parentNode.insertBefore(container, main);
        } else {
          document.body.prepend(container);
        }
      }
      const toast = document.createElement('div');
      const isSuccess = type === 'success';
      toast.className = `p-alert p-alert-${isSuccess ? 'success' : (type === 'danger' || type === 'error' ? 'danger' : 'info')}`;
      toast.setAttribute('data-auto-dismiss', duration);
      toast.innerHTML = `
        <div style="display:flex; align-items:center; gap:8px;">
          ${isSuccess 
            ? '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
            : '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>'}
          <span>${message}</span>
        </div>
        <button type="button" onclick="dismissPartnerAlert(this.closest(\'.p-alert\'))" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.2rem;line-height:1;padding:0 4px;" aria-label="Close">&times;</button>
      `;
      container.appendChild(toast);
      setTimeout(function() {
        dismissPartnerAlert(toast);
      }, duration);
    };
  </script>

  {{ $scripts ?? '' }}
  @stack('scripts')
  @yield('scripts')
</body>
</html>
