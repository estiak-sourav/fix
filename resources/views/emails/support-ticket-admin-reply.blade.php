<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Response on Your Support Ticket – FIX Helpdesk</title>
  <style>
    body { margin:0; padding:0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; background:#f4f6f8; color:#1e293b; line-height:1.6; }
    .wrapper { max-width:580px; margin:30px auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden; }
    .header { background:#0a1b30; padding:24px 32px; text-align:center; border-bottom:3px solid #10b981; }
    .header h2 { color:#ffffff; margin:0; font-size:20px; letter-spacing:0.5px; font-weight:700; }
    .header p { color:#94a3b8; margin:4px 0 0 0; font-size:12px; text-transform:uppercase; letter-spacing:1px; }
    .content { padding:32px; }
    .greeting { font-size:16px; font-weight:600; color:#0f172a; margin-bottom:10px; }
    .desc { font-size:14px; color:#475569; margin-bottom:24px; }
    .reply-card { background:#f0fdf4; border:1px solid #a7f3d0; border-left:4px solid #10b981; border-radius:10px; padding:20px 24px; margin-bottom:24px; }
    .reply-card .from-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#065f46; margin-bottom:6px; }
    .reply-card .from-name { font-size:14px; font-weight:700; color:#0f172a; margin-bottom:12px; }
    .reply-card .reply-text { font-size:14px; color:#1e293b; line-height:1.7; white-space:pre-line; }
    .ticket-ref { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 18px; font-size:13px; color:#475569; margin-bottom:24px; }
    .ticket-ref strong { color:#0f172a; }
    .cta-btn { display:block; text-align:center; background:#10b981; color:#ffffff !important; text-decoration:none; padding:14px 28px; border-radius:8px; font-size:15px; font-weight:700; margin:0 auto 24px; }
    .footer { background:#f8fafc; padding:20px 32px; text-align:center; font-size:12px; color:#94a3b8; border-top:1px solid #e2e8f0; }
    .footer a { color:#3e60d5; text-decoration:none; }
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
      <p>Engineering Helpdesk — New Response</p>
    </div>

    <div class="content">
      <div class="greeting">Hello {{ $ticket->customer_name }},</div>
      <div class="desc">
        Our engineering team has posted a new response to your support ticket. Please review the message below and reply on your ticket page if you have additional questions.
      </div>

      <div class="reply-card">
        <div class="from-label">Response from Engineering Desk</div>
        <div class="from-name">{{ $replyMessage->sender_name }}</div>
        <div class="reply-text">{{ $replyMessage->message }}</div>
      </div>

      <div class="ticket-ref">
        <strong>Ticket:</strong> #{{ $ticket->ticket_number }} &nbsp;|&nbsp;
        <strong>Subject:</strong> {{ $ticket->subject }} &nbsp;|&nbsp;
        <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
      </div>

      <a href="{{ $trackUrl }}" class="cta-btn">View Full Conversation & Reply →</a>

      <div style="font-size:13px; color:#64748b; line-height:1.6;">
        You can reply or add more details directly on your ticket page. If this issue has been resolved to your satisfaction, no further action is needed.
      </div>
    </div>

    <div class="footer">
      <div>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
      <div style="margin-top:6px;">
        Emergency Hotline: <strong>{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}</strong> |
        Support: <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}">{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}</a>
      </div>
    </div>
  </div>
</body>
</html>
