<x-layouts.web :title="'HTML Sitemap | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Services')">
  <x-page-hero 
    title="Website Sitemap"
    subtitle="Complete directory of all pages, technical services, AMC maintenance tiers, and customer assistance channels."
    maxWidth="1040px"
  />

  <section class="section" style="padding:50px 0 80px; background:#f8fafc;">
    <div class="container" style="max-width:1040px;">
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:24px;">
        
        <!-- 1. Main Navigation Pages -->
        <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:28px 24px; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <h2 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0;">Main Site Pages</h2>
          </div>
          <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:0.925rem;">
            <li><a href="{{ route('home') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Home (Main Landing)</a></li>
            <li><a href="{{ route('home') }}#services" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Services Overview</a></li>
            <li><a href="{{ route('home') }}#howItWorks" style="color:#334155; text-decoration:none; font-weight:500;">&bull; How FIX Works</a></li>
            <li><a href="{{ route('home') }}#whyFix" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Why Choose FIX Engineering</a></li>
            <li><a href="{{ route('home') }}#amc" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Annual Maintenance Contracts (AMC)</a></li>
            <li><a href="{{ route('home') }}#whoWeServe" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Industries & Sectors We Serve</a></li>
            <li><a href="{{ route('home') }}#testimonials" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Client Testimonials</a></li>
            <li><a href="{{ route('home') }}#faq" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Frequently Asked Questions</a></li>
            <li><a href="{{ route('home') }}#contact" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Contact & Emergency Operations</a></li>
          </ul>
        </div>

        <!-- 2. Services Directory -->
        <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:28px 24px; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            <h2 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0;">Facility Engineering Services</h2>
          </div>
          <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:0.925rem;">
            @foreach($services as $service)
              <li>
                <a href="{{ route('services.show', $service->slug ?? $service->id) }}" style="color:#334155; text-decoration:none; font-weight:500; display:flex; justify-content:space-between; align-items:center;">
                  <span>&bull; {{ $service->title }}</span>
                  <span style="font-size:0.75rem; background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:4px;">{{ $service->category?->name ?? 'Service' }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </div>

        <!-- 3. Portals, Booking & Assistance -->
        <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:28px 24px; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <h2 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0;">Booking & Client Portals</h2>
          </div>
          <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:0.925rem;">
            <li><a href="{{ route('booking.create') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Book a Facility Service</a></li>
            <li><a href="{{ route('vendor') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Enlist as Partner / Field Crew</a></li>
            <li><a href="{{ route('support.create') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Operations Helpdesk & Support Desk</a></li>
            <li><a href="{{ route('support.track') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Track Existing Support Ticket</a></li>
            <li><a href="{{ route('customer.bookings') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Customer Portal - My Bookings</a></li>
            <li><a href="{{ route('customer.tickets.index') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Customer Portal - My Tickets</a></li>
            <li><a href="{{ route('customer.profile') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Customer Account Profile</a></li>
          </ul>
        </div>

        <!-- 4. Legal, SLA & Policies -->
        <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:28px 24px; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            <h2 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0;">Legal & Service Guarantees</h2>
          </div>
          <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:0.925rem;">
            <li><a href="{{ route('privacy') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Privacy & Data Protection Policy</a></li>
            <li><a href="{{ route('terms') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; Terms of Service Agreement</a></li>
            <li><a href="{{ route('sla') }}" style="color:#334155; text-decoration:none; font-weight:500;">&bull; SLA & 30-Day Workmanship Guarantee</a></li>
            <li><a href="{{ route('sitemap') }}" style="color:#0284c7; text-decoration:none; font-weight:600;">&bull; Website Sitemap (Current Page)</a></li>
          </ul>
        </div>

      </div>
    </div>
  </section>
</x-layouts.web>
