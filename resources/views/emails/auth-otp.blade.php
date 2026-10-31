<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Verification Code - {{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Operations') }}</title>
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
      border-bottom: 3px solid #3e60d5;
    }
    .header h2 {
      color: #ffffff;
      margin: 0;
      font-size: 20px;
      letter-spacing: 0.5px;
      font-weight: 700;
    }
    .header p {
      color: #94a3b8;
      margin: 6px 0 0 0;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .content {
      padding: 32px 32px 24px;
    }
    .greeting {
      font-size: 16px;
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 12px;
    }
    .description {
      font-size: 14px;
      color: #475569;
      margin-bottom: 24px;
    }
    .otp-card {
      background: #eef2ff;
      border: 2px dashed #818cf8;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      margin: 24px 0;
    }
    .otp-label {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #4338ca;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .otp-code {
      font-family: 'Courier New', Courier, monospace;
      font-size: 34px;
      font-weight: 800;
      letter-spacing: 8px;
      color: #3e60d5;
      margin: 0;
    }
    .meta-box {
      background: #f8fafc;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 13px;
      color: #64748b;
      margin-bottom: 24px;
      border-left: 3px solid #f59e0b;
    }
    .footer {
      background: #f8fafc;
      padding: 20px 32px;
      text-align: center;
      font-size: 12px;
      color: #94a3b8;
      border-top: 1px solid #e2e8f0;
    }
    .footer a {
      color: #3e60d5;
      text-decoration: none;
    }
  </style>
</head>
<body>
  @php
    $siteName = \App\Models\SiteSetting::get('site_name', 'FIX Facility Management');
    $siteTagline = \App\Models\SiteSetting::get('site_tagline', 'Clean • Safe • Sustainable Engineering');
    $logoRelative = \App\Models\SiteSetting::get('header_logo_dark', \App\Models\SiteSetting::get('footer_logo', 'assets/images/logo-white-crop.png'));
    $logoPath = $logoRelative ? public_path($logoRelative) : null;
    if (isset($message) && is_object($message) && method_exists($message, 'embed') && $logoPath && file_exists($logoPath)) {
        $logoSrc = $message->embed($logoPath);
    } elseif ($logoRelative) {
        $logoSrc = url(asset($logoRelative));
    } else {
        $logoSrc = null;
    }
    $securityNotice = \App\Models\SiteSetting::get('otp_security_notice', 'This code is valid for 10 minutes. Never share your OTP with anyone, including FIX representatives.');
    $disclaimerText = \App\Models\SiteSetting::get('otp_disclaimer_text', 'If you did not request this verification code, please ignore this email or contact our 24/7 emergency dispatch immediately.');
    $emergencyPhone = \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000');
    $supportEmail = \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd');
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

      @if(!empty($siteTagline))
        <p>{{ $siteTagline }}</p>
      @endif
    </div>

    <div class="content">
      <div class="greeting">Hello {{ $name }},</div>
      <div class="description">
        You requested a verification code to <strong>{{ $purpose === 'register' ? 'register a new customer account' : 'sign in to your customer portal' }}</strong> on {{ $siteName }}.
      </div>

      <div class="otp-card">
        <div class="otp-label">Your One-Time Passcode (OTP)</div>
        <div class="otp-code">{{ $otpCode }}</div>
      </div>

      <div class="meta-box">
        <strong>Security Notice:</strong> {{ $securityNotice }}
      </div>

      <div style="font-size: 13px; color: #64748b; line-height: 1.5;">
        {{ $disclaimerText }}
      </div>
    </div>

    <div class="footer">
      <div>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
      <div style="margin-top: 6px;">
        Emergency Hotline: <strong>{{ $emergencyPhone }}</strong> | Support: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
      </div>
    </div>
  </div>
</body>
</html>
