<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Management') }} - Under Scheduled Maintenance</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #0b0f19;
      color: #f1f5f9;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
      position: relative;
      overflow-x: hidden;
    }
    .ambient-glow {
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, rgba(30, 58, 138, 0.05) 50%, transparent 70%);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
      z-index: 0;
    }
    .maintenance-card {
      position: relative;
      z-index: 1;
      max-width: 620px;
      width: 100%;
      background: rgba(17, 24, 39, 0.85);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      padding: 40px 36px;
      text-align: center;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(16px);
    }
    .brand-logo {
      height: 48px;
      max-width: 220px;
      object-fit: contain;
      margin-bottom: 28px;
    }
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(245, 158, 11, 0.12);
      border: 1px solid rgba(245, 158, 11, 0.3);
      color: #fbbf24;
      padding: 6px 14px;
      border-radius: 9999px;
      font-size: 0.82rem;
      font-weight: 600;
      letter-spacing: 0.03em;
      margin-bottom: 20px;
    }
    .pulse-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #fbbf24;
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(0.95); opacity: 0.8; }
      50% { transform: scale(1.3); opacity: 1; }
      100% { transform: scale(0.95); opacity: 0.8; }
    }
    h1 {
      font-size: 1.85rem;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 12px;
      letter-spacing: -0.02em;
    }
    p.message {
      font-size: 0.98rem;
      color: #94a3b8;
      line-height: 1.6;
      margin-bottom: 28px;
    }
    .contact-box {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      padding: 16px 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      gap: 16px;
      margin-bottom: 24px;
      font-size: 0.875rem;
    }
    .contact-item strong {
      display: block;
      color: #cbd5e1;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 4px;
    }
    .contact-item a {
      color: #38bdf8;
      text-decoration: none;
      font-weight: 600;
    }
    .contact-item a:hover {
      text-decoration: underline;
    }
    .footer-note {
      font-size: 0.8rem;
      color: #64748b;
      margin-top: 20px;
    }
    .admin-link {
      color: #64748b;
      font-size: 0.78rem;
      text-decoration: none;
      margin-top: 14px;
      display: inline-block;
      transition: color 0.2s;
    }
    .admin-link:hover {
      color: #94a3b8;
    }
  </style>
</head>
<body>
  <div class="ambient-glow"></div>

  <div class="maintenance-card">
    <img src="{{ asset(\App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png')) }}" alt="{{ \App\Models\SiteSetting::get('site_name', 'FIX Logo') }}" class="brand-logo">
    
    <div>
      <span class="status-badge">
        <span class="pulse-dot"></span>
        Under Scheduled System Maintenance
      </span>
    </div>

    <h1>We Will Be Right Back</h1>

    <p class="message">
      {{ $maintenanceMessage }}
    </p>

    <div class="contact-box">
      <div class="contact-item">
        <strong>24/7 Emergency Dispatch</strong>
        <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+8801700000000') }}">
          {{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}
        </a>
      </div>
      <div class="contact-item">
        <strong>Direct Operations Support</strong>
        <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}">
          {{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}
        </a>
      </div>
    </div>

    <div class="footer-note">
      {{ \App\Models\SiteSetting::get('copyright_text', '© 2026 FIX Facility Management Services. All Rights Reserved.') }}
    </div>

    <div>
      <a href="/admin" class="admin-link">Staff & Operations Center Login ↗</a>
    </div>
  </div>
</body>
</html>
