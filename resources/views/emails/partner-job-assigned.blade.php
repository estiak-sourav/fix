<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Job Assigned – FIX Partner Dispatch</title>
  <style>
    body { margin:0; padding:0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; background:#f4f6f8; color:#1e293b; line-height:1.6; }
    .wrapper { max-width:580px; margin:30px auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden; }
    .header { background:#0f2744; padding:24px 32px; text-align:center; border-bottom:3px solid #00a896; }
    .header h2 { color:#ffffff; margin:0; font-size:20px; font-weight:700; }
    .header p { color:#94a3b8; margin:4px 0 0 0; font-size:12px; text-transform:uppercase; letter-spacing:1px; }
    .content { padding:32px; }
    .greeting { font-size:16px; font-weight:600; color:#0f172a; margin-bottom:10px; }
    .job-card { background:#f8fafc; border:1px solid #e2e8f0; border-left:4px solid #00a896; border-radius:10px; padding:20px 24px; margin:20px 0; }
    .job-card .num { font-size:18px; font-weight:800; color:#0f2744; font-family:monospace; }
    .row { display:flex; justify-content:space-between; font-size:13px; color:#475569; border-top:1px solid #e2e8f0; padding-top:10px; margin-top:8px; }
    .cta-btn { display:inline-block; text-align:center; background:#00a896; color:#ffffff !important; text-decoration:none; padding:12px 24px; border-radius:8px; font-size:14px; font-weight:600; margin:16px 0; }
    .footer { background:#f8fafc; padding:20px 32px; text-align:center; font-size:12px; color:#94a3b8; border-top:1px solid #e2e8f0; }
  </style>
</head>
<body>
  @php
    $siteName = \App\Models\SiteSetting::get('site_name', 'FIX Facility Management');
  @endphp
  <div class="wrapper">
    <div class="header">
      <h2>{{ $siteName }}</h2>
      <p>Partner Dispatch &amp; Work Order Network</p>
    </div>

    <div class="content">
      <div class="greeting">Hello {{ $partner->contact_person }} ({{ $partner->company_name }}),</div>
      <p style="font-size:14px; color:#475569;">
        A new service order has been dispatched to your team by Fix Central Operations. Please review details below and confirm acceptance via your Partner Portal.
      </p>

      <div class="job-card">
        <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:700;">Work Order Number</div>
        <div class="num">#{{ $booking->booking_number }}</div>

        <div style="font-size:14px; color:#1e293b; margin-top:10px;">
          <strong>Service:</strong> {{ $booking->service?->name ?? $booking->amcPlan?->name ?? 'Facility Maintenance' }}
        </div>
        <div style="font-size:13px; color:#475569; margin-top:4px;">
          <strong>Location:</strong> {{ $booking->street_address ?: $booking->address }} ({{ $booking->area ?: 'Dhaka' }})
        </div>

        @if($booking->arrival_window || $booking->preferred_time)
          <div style="font-size:13px; color:#00a896; font-weight:600; margin-top:6px;">
            Target Arrival Slot: {{ $booking->arrival_window ?: $booking->preferred_time }}
          </div>
        @endif

        <div class="row">
          <span>Client: <strong>{{ $booking->customer_name ?: $booking->name }}</strong></span>
          <span>Contact: <strong>{{ $booking->customer_phone ?: $booking->phone }}</strong></span>
        </div>
      </div>

      <div style="text-align:center;">
        <a href="{{ $portalUrl }}" class="cta-btn">Open Partner Portal &amp; Accept Job</a>
      </div>

      <p style="font-size:12px; color:#64748b; margin-top:16px;">
        <em>Note: You can update your email dispatch preferences anytime under Partner Settings &gt; Profile in your portal.</em>
      </p>
    </div>

    <div class="footer">
      &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
    </div>
  </div>
</body>
</html>
