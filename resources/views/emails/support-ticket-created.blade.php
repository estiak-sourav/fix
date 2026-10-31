<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support Ticket Received – FIX Helpdesk</title>
  <style>
    body { margin:0; padding:0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; background:#f4f6f8; color:#1e293b; line-height:1.6; }
    .wrapper { max-width:580px; margin:30px auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden; }
    .header { background:#0a1b30; padding:24px 32px; text-align:center; border-bottom:3px solid #3e60d5; }
    .header h2 { color:#ffffff; margin:0; font-size:20px; letter-spacing:0.5px; font-weight:700; }
    .header p { color:#94a3b8; margin:4px 0 0 0; font-size:12px; text-transform:uppercase; letter-spacing:1px; }
    .content { padding:32px; }
    .greeting { font-size:16px; font-weight:600; color:#0f172a; margin-bottom:10px; }
    .desc { font-size:14px; color:#475569; margin-bottom:24px; }
    .ticket-card { background:#f8fafc; border:1px solid #e2e8f0; border-left:4px solid #3e60d5; border-radius:10px; padding:20px 24px; margin-bottom:24px; }
    .ticket-card .label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#64748b; margin-bottom:4px; }
    .ticket-card .value { font-size:16px; font-weight:800; color:#0f172a; margin-bottom:14px; font-family:'Courier New',monospace; letter-spacing:1px; }
    .ticket-card .row { display:flex; justify-content:space-between; font-size:13px; color:#475569; border-top:1px solid #e2e8f0; padding-top:12px; margin-top:4px; flex-wrap:wrap; gap:8px; }
    .ticket-card .row span strong { color:#1e293b; }
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; }
    .badge-open { background:#e0f2fe; color:#0369a1; }
    .badge-normal { background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; }
    .badge-high { background:#ffedd5; color:#ea580c; }
    .badge-urgent { background:#fee2e2; color:#dc2626; }
    .cta-btn { display:block; text-align:center; background:#3e60d5; color:#ffffff !important; text-decoration:none; padding:14px 28px; border-radius:8px; font-size:15px; font-weight:700; margin:0 auto 24px; }
    .info-box { background:#fefce8; border:1px solid #fde68a; border-radius:8px; padding:14px 18px; font-size:13px; color:#92400e; margin-bottom:24px; }
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
      <p>Engineering Helpdesk Confirmation</p>
    </div>

    <div class="content">
      <div class="greeting">Hello {{ $ticket->customer_name }},</div>
      <div class="desc">
        Your support request has been successfully logged in our system. Our engineering team has been notified and will respond shortly.
      </div>

      <div class="ticket-card">
        <div class="label">Your Ticket Reference</div>
        <div class="value">#{{ $ticket->ticket_number }}</div>
        <div style="font-size:14px; color:#334155; margin-bottom:12px;"><strong>Subject:</strong> {{ $ticket->subject }}</div>
        <div class="row">
          <span><strong>Status:</strong> &nbsp;<span class="badge badge-open">Open</span></span>
          <span><strong>Priority:</strong> &nbsp;<span class="badge badge-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span></span>
          <span><strong>Opened:</strong> {{ $ticket->created_at->format('M d, Y • h:i A') }}</span>
        </div>
      </div>

      <div class="info-box">
        📌 <strong>Keep this email</strong> — your ticket number <strong>#{{ $ticket->ticket_number }}</strong> is required to track updates. You can view real-time responses by clicking the button below.
      </div>

      <a href="{{ $trackUrl }}" class="cta-btn">View Your Ticket & Live Updates →</a>

      <div style="font-size:13px; color:#64748b; line-height:1.6;">
        You will receive an email notification each time our engineering team posts a response. If you have additional details to add, you can reply directly on your ticket page.
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
