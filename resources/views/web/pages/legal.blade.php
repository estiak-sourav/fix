<x-layouts.web :title="$title . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Services')">
  <section class="page-hero" style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding:60px 0 40px; color:#ffffff;">
    <div class="container" style="max-width:900px;">
      <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:#38bdf8; letter-spacing:0.05em; display:block; margin-bottom:8px;">Compliance & Policies</span>
      <h1 style="font-size:2.2rem; font-weight:800; margin:0 0 10px;">{{ $title }}</h1>
      <p style="color:#94a3b8; margin:0; font-size:0.95rem;">Last Updated: October 2026 • {{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Management Services') }}</p>
    </div>
  </section>

  <section class="section" style="padding:40px 0 80px;">
    <div class="container" style="max-width:900px; background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:36px 40px; box-shadow:0 4px 20px rgba(0,0,0,0.03); line-height:1.7; color:#334155;">
      @if($type === 'privacy')
        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">1. Information We Collect</h2>
        <p style="margin-bottom:20px;">
          When you use {{ \App\Models\SiteSetting::get('site_name', 'FIX') }}, request building facility dispatches, subscribe to Annual Maintenance Contracts (AMC), or communicate with our operations center, we may collect personal details such as your name, corporate organization, email address, phone number, physical site address, and equipment logs.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">2. How We Use Your Data</h2>
        <p style="margin-bottom:20px;">
          We utilize your data to dispatch certified engineering technicians, provide booking status notifications, generate invoices, perform scheduled preventive maintenance, and safeguard security across all operational interactions.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">3. Cookies & Analytical Tracking</h2>
        <p style="margin-bottom:20px;">
          Our platform utilizes cookies to remember session authentication, optimize navigation performance, and analyze operational trends. You may control cookie preferences through browser settings or our on-page cookie consent banner.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">4. Contacting Data Protection</h2>
        <p style="margin-bottom:0;">
          For inquiries regarding privacy, data access requests, or policy clarification, please contact our administrative operations center at 
          <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}" style="color:#0284c7; font-weight:600;">{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}</a> or call 
          <strong>{{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}</strong>.
        </p>
      @else
        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">1. Scope of Services</h2>
        <p style="margin-bottom:20px;">
          {{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Management Services') }} provides professional facility engineering, electrical maintenance, commercial HVAC services, fire safety inspections, plumbing dispatches, and scheduled Annual Maintenance Contracts (AMC) across Bangladesh.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">2. Service Booking & Dispatch Agreements</h2>
        <p style="margin-bottom:20px;">
          Booking estimates provided via the online portal represent initial scope assessments. Final service costs are confirmed upon physical site diagnostic inspection by certified lead technicians. All work orders follow approved safety guidelines and industry SLA standards.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">3. Annual Maintenance Contracts (AMC)</h2>
        <p style="margin-bottom:20px;">
          AMC plan subscriptions are billed per tier terms (Monthly or Annual billing cycle). Subscribers enjoy scheduled preventive inspections, emergency priority dispatches, and dedicated customer support desk access.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">4. Support & Resolution</h2>
        <p style="margin-bottom:0;">
          For support requests, service guarantees, or contract terms inquiries, reach out to our team at 
          <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}" style="color:#0284c7; font-weight:600;">{{ \App\Models\SiteSetting::get('support_email', 'support@fix.com.bd') }}</a>.
        </p>
      @endif
    </div>
  </section>
</x-layouts.web>
