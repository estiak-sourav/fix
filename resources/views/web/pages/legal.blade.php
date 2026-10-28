<x-layouts.web :title="$title . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Services')">
  @php
    $contentKey = match($type) {
        'privacy' => 'legal_privacy',
        'sla' => 'legal_sla',
        default => 'legal_terms',
    };
    $legalContent = \App\Models\SiteContent::forSection($contentKey);
    $dynamicSections = $legalContent?->meta_data['sections'] ?? null;
    $pageTitle = $legalContent?->title ?: $title;
  @endphp
  <x-page-hero 
    :title="$pageTitle"
    :subtitle="'Last Updated: October 2026 • ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management Services')"
    maxWidth="900px"
  />

  <section id="legal-content" class="section" style="padding:40px 0 80px;">
    <div id="{{ $type }}-content"></div>
    <div id="legal_{{ $type }}"></div>
    <div class="container" style="max-width:900px; background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:36px 40px; box-shadow:0 4px 20px rgba(0,0,0,0.03); line-height:1.7; color:#334155;">
      @if(!empty($dynamicSections) && is_array($dynamicSections))
        @foreach($dynamicSections as $sec)
          <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">{{ $sec['heading'] ?? '' }}</h2>
          <p style="margin-bottom:20px;">{!! nl2br(e($sec['body'] ?? '')) !!}</p>
        @endforeach
      @elseif($type === 'privacy')
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
      @elseif($type === 'sla')
        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">1. Emergency Response Dispatch SLA</h2>
        <p style="margin-bottom:20px;">
          For critical facility emergencies—including primary electrical tripping, severe plumbing burst flooding, central HVAC chiller shutoffs, and fire safety alarms—{{ \App\Models\SiteSetting::get('site_name', 'FIX Facility Services') }} guarantees lead technician dispatch within 60 minutes across metropolitan Dhaka and regional economic zones.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">2. Scheduled Preventive Maintenance SLA</h2>
        <p style="margin-bottom:20px;">
          All Annual Maintenance Contract (AMC) visits are scheduled 48 hours in advance. Preventative routine inspections follow standardized BNBC engineering checklists, and digital inspection logs are delivered to property managers within 24 hours of task completion.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">3. 30-Day Certified Workmanship Guarantee</h2>
        <p style="margin-bottom:20px;">
          All non-cosmetic electrical repairs, plumbing fittings, HVAC servicing, and mechanical installations performed by certified FIX field technicians include a 30-day quality and workmanship warranty. If a recurring defect arises within the guarantee window under standard operational parameters, our crew returns for rectification without additional diagnostic dispatch charges.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">4. Verified Engineering & Safety Standards</h2>
        <p style="margin-bottom:20px;">
          All dispatched field staff undergo police background verification, technical vetting, and OSHA safety protocol training. We maintain rigorous standards to ensure the highest safety and reliability for commercial facilities and residential properties.
        </p>

        <h2 style="font-size:1.35rem; font-weight:700; color:#0f172a; margin-bottom:12px;">5. Escalations & Support SLA</h2>
        <p style="margin-bottom:0;">
          For SLA inquiries, guarantee claims, or operations escalation, contact our 24/7 Operations Command Desk at 
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
