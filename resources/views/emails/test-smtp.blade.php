<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SMTP Connection Test - FIX Facility Operations</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: #f4f6f8;
      color: #1e293b;
      line-height: 1.6;
    }
    .wrapper {
      max-width: 580px;
      margin: 30px auto;
      background: #ffffff;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    .header {
      background: #1e2229;
      padding: 24px 32px;
      text-align: center;
      border-bottom: 3px solid #16a34a;
    }
    .header h2 {
      color: #ffffff;
      margin: 0;
      font-size: 20px;
      letter-spacing: 0.5px;
      font-weight: 700;
    }
    .content {
      padding: 32px 32px 24px;
    }
    .success-badge {
      display: inline-block;
      background: #dcfce7;
      color: #15803d;
      font-weight: 700;
      font-size: 14px;
      padding: 6px 14px;
      border-radius: 20px;
      margin-bottom: 16px;
    }
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
    }
    .info-table td {
      padding: 10px 12px;
      border-bottom: 1px solid #e2e8f0;
    }
    .info-table td:first-child {
      font-weight: 600;
      color: #64748b;
      width: 40%;
    }
    .footer {
      background: #f8fafc;
      padding: 20px 32px;
      text-align: center;
      font-size: 12px;
      color: #94a3b8;
      border-top: 1px solid #e2e8f0;
    }
  </style>
</head>
<body>
  @php
    $siteName = \App\Models\SiteSetting::get('site_name', 'FIX Facility Management');
    $logoRelative = \App\Models\SiteSetting::get('header_logo_dark', \App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png'));
    $logoPath = $logoRelative ? public_path($logoRelative) : null;
    if (isset($message) && is_object($message) && method_exists($message, 'embed') && $logoPath && file_exists($logoPath)) {
        $logoSrc = $message->embed($logoPath);
    } elseif ($logoRelative) {
        $logoSrc = url(asset($logoRelative));
    } else {
        $logoSrc = null;
    }
  @endphp
  <div class="wrapper">
    <div class="header">
      @if($logoSrc)
        <div style="margin-bottom: 6px;">
          <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: inline-block;">
            <img src="{{ $logoSrc }}" alt="{{ $siteName }}" style="height: 44px; max-width: 220px; object-fit: contain; vertical-align: middle; border: 0;">
          </a>
        </div>
      @else
        <h2>{{ $siteName }}</h2>
      @endif
      @php
        $testSmtpSubtitle = \App\Models\SiteSetting::get('email_header_test_smtp', 'Admin Command Center');
      @endphp
      @if(!empty($testSmtpSubtitle))
        <p style="color:#94a3b8; margin:4px 0 0; font-size:12px;">{{ $testSmtpSubtitle }}</p>
      @endif
    </div>

    <div class="content">
      <div class="success-badge">✅ SMTP Gateway Connected Successfully</div>
      <h3 style="margin-top:0; color:#0f172a;">SMTP Test Transmission Verified</h3>
      <p style="font-size:14px; color:#475569;">
        This test message confirms that your SMTP gateway credentials configured in the <strong>FIX Operations Admin Center</strong> are valid and actively routing outgoing emails.
      </p>

      <table class="info-table">
        <tr>
          <td>Recipient</td>
          <td><strong>{{ $recipientEmail }}</strong></td>
        </tr>
        <tr>
          <td>SMTP Host</td>
          <td><code>{{ $configuredHost }}</code></td>
        </tr>
        <tr>
          <td>SMTP Port</td>
          <td><code>{{ $configuredPort }}</code></td>
        </tr>
        <tr>
          <td>Test Timestamp</td>
          <td>{{ $timestamp }}</td>
        </tr>
      </table>

      <div style="font-size:13px; color:#64748b;">
        Your system is ready to dispatch passwordless OTP authentication codes to customers and administrators.
      </div>
    </div>

    <div class="footer">
      <div>FIX Facility Management Operations • Automated Notification</div>
    </div>
  </div>
</body>
</html>
