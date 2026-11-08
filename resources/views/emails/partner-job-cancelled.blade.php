<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Job Assignment Cancelled – FIX Partner Dispatch</title>
  <style>
    body { margin:0; padding:0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; background:#f4f6f8; color:#1e293b; line-height:1.6; }
    .wrapper { max-width:580px; margin:30px auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden; }
    .header { background:#0f2744; padding:24px 32px; text-align:center; border-bottom:3px solid #ef4444; }
    .header h2 { color:#ffffff; margin:0; font-size:20px; font-weight:700; }
    .header p { color:#94a3b8; margin:4px 0 0 0; font-size:12px; text-transform:uppercase; letter-spacing:1px; }
    .content { padding:32px; }
    .greeting { font-size:16px; font-weight:600; color:#0f172a; margin-bottom:10px; }
    .job-card { background:#fff5f5; border:1px solid #fed7d7; border-left:4px solid #ef4444; border-radius:10px; padding:20px 24px; margin:20px 0; }
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
      <p>Job Assignment Cancellation Notice</p>
    </div>

    <div class="content">
      <div class="greeting">Hello {{ $partner->contact_person }},</div>
      <p style="font-size:14px; color:#475569;">
        Please note that Work Order <strong>#{{ $booking->booking_number }}</strong> has been cancelled or recalled by Fix Central Operations. You do not need to attend this job.
      </p>

      <div class="job-card">
        <div style="font-size:11px; text-transform:uppercase; color:#991b1b; font-weight:700;">Cancelled Work Order</div>
        <div style="font-size:18px; font-weight:800; color:#b91c1c; font-family:monospace; margin-top:4px;">#{{ $booking->booking_number }}</div>
        <div style="font-size:13px; color:#475569; margin-top:8px;">
          <strong>Cancellation Reason:</strong> {{ $reason }}
        </div>
      </div>

      <p style="font-size:13px; color:#64748b;">
        Your availability calendar remains open for new dispatches. Thank you for your continued partnership.
      </p>
    </div>

    <div class="footer">
      &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
    </div>
  </div>
</body>
</html>

