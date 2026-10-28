<x-layouts.app title="About Us | FIX Facility Management">
  <!-- Page Hero Banner -->
  <x-page-hero 
    :title="$heroContent->title ?? 'Pioneering Modern Facility Engineering in Bangladesh'"
    :subtitle="$heroContent->subtitle ?? 'FIX is Bangladesh\'s premier technology-driven facility management company, delivering MEP engineering, HVAC solutions, and compliant operations.'"
    :badge="$heroContent->badge ?? 'ABOUT FIX FACILITY MANAGEMENT'"
    maxWidth="900px"
  />

  @php
    $heroMeta = $heroContent->meta_data ?? [];
    $overviewMeta = $overviewContent->meta_data ?? [];
    $values = $overviewMeta['values'] ?? [
      ['title' => 'BNBC & NFPA Certified Safety', 'desc' => 'Strict adherence to national and international building codes, fire safety, and electrical hazard prevention.'],
      ['title' => '2-Hour Emergency SLA', 'desc' => 'Rapid technical squads stationed at regional hubs for immediate crisis mitigation and critical dispatch.'],
      ['title' => 'Transparent Digital Tracking', 'desc' => 'Every maintenance request is digitally documented with verified before-after logs and satisfaction tracking.'],
      ['title' => 'Eco-Friendly & Sustainable', 'desc' => 'Energy-efficient HVAC optimization, non-toxic commercial chemicals, and preventive asset lifecycle planning.'],
    ];
    $milestones = $overviewMeta['milestones'] ?? [
      ['year' => '2014', 'title' => 'MEP Operations Founded', 'desc' => 'Launched specialized electrical and mechanical facility engineering services in Dhaka.'],
      ['year' => '2018', 'title' => 'Enterprise Expansion', 'desc' => 'Expanded to 50+ corporate headquarters and EPZ manufacturing facilities.'],
      ['year' => '2022', 'title' => '24/7 Command Center', 'desc' => 'Built our real-time centralized emergency dispatch control room.'],
      ['year' => '2026', 'title' => 'Unified Digital Platform', 'desc' => 'Integrated self-service customer booking, transparent AMC tracking, and digital vendor ecosystem.'],
    ];
  @endphp

  <!-- Section 1: Executive Overview & High-Impact Metrics -->
  @if($heroContent?->is_active ?? true)
  <section class="section" id="overview" style="padding-top:4rem; padding-bottom:4rem; background:#ffffff;">
    <div class="container">
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:3.5rem; align-items:center;">
        <div>
          <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(62,96,213,0.08); border:1px solid rgba(62,96,213,0.25); color:var(--paces-primary, #3e60d5); font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; padding:4px 12px; border-radius:20px; margin-bottom:1rem;">
            <span>🏢 Who We Are</span>
          </div>
          <h2 style="font-size:2.2rem; font-weight:800; color:var(--text-main, #0f172a); line-height:1.25; margin-bottom:1.25rem;">
            {{ $heroMeta['story_title'] ?? 'Building Bangladesh\'s Most Trusted Facility Infrastructure' }}
          </h2>
          <p style="font-size:1.05rem; color:var(--text-muted, #475569); line-height:1.75; margin-bottom:1rem;">
            {{ $heroMeta['story_p1'] ?? 'Founded with a clear vision to revolutionize property care, FIX provides end-to-end building operations. From certified electrical compliance and chiller overhauls to green building sustainability and round-the-clock emergency dispatch, we keep critical infrastructure running flawlessly.' }}
          </p>
          <p style="font-size:0.98rem; color:var(--text-muted, #64748b); line-height:1.7; margin-bottom:2rem;">
            {{ $heroMeta['story_p2'] ?? 'Headquartered in Banani, Dhaka, our rapid dispatch technical squads cover Dhaka Metropolitan, Gazipur, Savar, Narayanganj, and major Export Processing Zones (EPZ).' }}
          </p>

          <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ $heroContent->button_url ?? '/#services' }}" class="btn btn-accent" style="padding:0.75rem 1.6rem; font-weight:700;">
              {{ $heroContent->button_text ?? 'Explore Our Services' }} →
            </a>
            <a href="{{ $heroContent->secondary_button_url ?? '/support' }}" class="btn btn-outline" style="padding:0.75rem 1.5rem; font-weight:600;">
              {{ $heroContent->secondary_button_text ?? 'Contact Command Center' }}
            </a>
          </div>
        </div>

        <!-- 4 Key Enterprise Metrics Cards -->
        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:1.25rem;">
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem 1.25rem; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.02); transition:transform 0.25s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:2.5rem; font-weight:800; color:var(--paces-primary, #3e60d5); line-height:1; margin-bottom:8px;">
              {{ $heroMeta['stat_experience'] ?? '12+' }}
            </div>
            <div style="font-size:0.85rem; font-weight:600; color:#475569; line-height:1.3;">
              {{ $heroMeta['stat_experience_label'] ?? 'Years of Engineering Excellence' }}
            </div>
          </div>

          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem 1.25rem; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.02); transition:transform 0.25s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:2.5rem; font-weight:800; color:#0d9488; line-height:1; margin-bottom:8px;">
              {{ $heroMeta['stat_clients'] ?? '150+' }}
            </div>
            <div style="font-size:0.85rem; font-weight:600; color:#475569; line-height:1.3;">
              {{ $heroMeta['stat_clients_label'] ?? 'Enterprise Facilities Managed' }}
            </div>
          </div>

          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem 1.25rem; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.02); transition:transform 0.25s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:2.5rem; font-weight:800; color:#e11d48; line-height:1; margin-bottom:8px;">
              {{ $heroMeta['stat_technicians'] ?? '250+' }}
            </div>
            <div style="font-size:0.85rem; font-weight:600; color:#475569; line-height:1.3;">
              {{ $heroMeta['stat_technicians_label'] ?? 'Certified Engineers & Techs' }}
            </div>
          </div>

          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem 1.25rem; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.02); transition:transform 0.25s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:2.5rem; font-weight:800; color:#16a34a; line-height:1; margin-bottom:8px;">
              {{ $heroMeta['stat_satisfaction'] ?? '99.4%' }}
            </div>
            <div style="font-size:0.85rem; font-weight:600; color:#475569; line-height:1.3;">
              {{ $heroMeta['stat_satisfaction_label'] ?? 'SLA Reliability Rate' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- Section 2: Mission & Vision Cards -->
  @if($overviewContent?->is_active ?? true)
  <section class="section section-alt" style="padding:4.5rem 0; background:#f1f5f9;">
    <div class="container">
      <div class="section-header" style="text-align:center; max-width:720px; margin:0 auto 3rem;">
        <span style="display:inline-block; font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--paces-primary, #3e60d5); letter-spacing:0.08em; margin-bottom:8px;">
          {{ $overviewContent->badge ?? 'PURPOSE & CORE VALUES' }}
        </span>
        <h2 class="section-title" style="font-size:2.1rem; font-weight:800; color:var(--text-main, #0f172a); margin-bottom:0.75rem;">
          {{ $overviewContent->title ?? 'Our Mission, Vision & Core Engineering Principles' }}
        </h2>
        @if(!empty($overviewContent?->subtitle))
          <p class="section-subtitle" style="font-size:1rem; color:var(--text-muted, #64748b);">
            {{ $overviewContent->subtitle }}
          </p>
        @endif
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:2rem; margin-bottom:3rem;">
        <!-- Mission Card -->
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-top:4px solid var(--paces-primary, #3e60d5); border-radius:14px; padding:2.25rem; box-shadow:var(--shadow-md);">
          <div style="width:48px; height:48px; border-radius:12px; background:rgba(62,96,213,0.1); color:var(--paces-primary, #3e60d5); display:inline-flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
          </div>
          <h3 style="font-size:1.4rem; font-weight:800; color:var(--text-main, #0f172a); margin-bottom:0.85rem;">
            {{ $overviewMeta['mission_title'] ?? 'Our Mission' }}
          </h3>
          <p style="font-size:1rem; color:var(--text-muted, #475569); line-height:1.75; margin:0;">
            {{ $overviewMeta['mission_desc'] ?? 'To elevate facility management standards across Bangladesh through certified technical expertise, 24/7 rapid response, and zero-compromise environmental and occupational safety.' }}
          </p>
        </div>

        <!-- Vision Card -->
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-top:4px solid #0d9488; border-radius:14px; padding:2.25rem; box-shadow:var(--shadow-md);">
          <div style="width:48px; height:48px; border-radius:12px; background:rgba(13,148,136,0.1); color:#0d9488; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          </div>
          <h3 style="font-size:1.4rem; font-weight:800; color:var(--text-main, #0f172a); margin-bottom:0.85rem;">
            {{ $overviewMeta['vision_title'] ?? 'Our Vision' }}
          </h3>
          <p style="font-size:1rem; color:var(--text-muted, #475569); line-height:1.75; margin:0;">
            {{ $overviewMeta['vision_desc'] ?? 'To be the most reliable, tech-enabled, and sustainable integrated facility management partner for world-class enterprises, export zones, and commercial properties.' }}
          </p>
        </div>
      </div>

      <!-- Core Values 4-Pillars Grid -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem;">
        @foreach($values as $vIdx => $val)
          <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.75rem 1.5rem; box-shadow:0 1px 4px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.75rem;">
              <span style="width:28px; height:28px; border-radius:50%; background:var(--accent-light, #fee2e2); color:var(--accent, #e11d48); font-weight:800; font-size:0.85rem; display:inline-flex; align-items:center; justify-content:center;">
                {{ $vIdx + 1 }}
              </span>
              <h4 style="font-size:1.05rem; font-weight:700; color:var(--text-main, #0f172a); margin:0;">
                {{ $val['title'] ?? '' }}
              </h4>
            </div>
            <p style="font-size:0.9rem; color:var(--text-muted, #64748b); line-height:1.6; margin:0;">
              {{ $val['desc'] ?? '' }}
            </p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Section 3: Company Milestones & Journey -->
  <section class="section" style="padding:4.5rem 0; background:#ffffff;">
    <div class="container">
      <div class="section-header" style="text-align:center; max-width:680px; margin:0 auto 3rem;">
        <span style="display:inline-block; font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#0d9488; letter-spacing:0.08em; margin-bottom:8px;">
          OUR TRACK RECORD
        </span>
        <h2 class="section-title" style="font-size:2rem; font-weight:800; color:var(--text-main, #0f172a); margin-bottom:0.5rem;">
          Milestones in Facility Engineering
        </h2>
        <p class="section-subtitle" style="font-size:0.95rem; color:var(--text-muted, #64748b);">
          A decade of continuous innovation, safety certification, and enterprise-scale operations in Bangladesh.
        </p>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
        @foreach($milestones as $m)
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.75rem 1.25rem; position:relative; overflow:hidden;">
            <div style="font-size:1.85rem; font-weight:800; color:var(--paces-primary, #3e60d5); margin-bottom:0.5rem; letter-spacing:-0.03em;">
              {{ $m['year'] ?? '' }}
            </div>
            <h4 style="font-size:1.05rem; font-weight:700; color:var(--text-main, #0f172a); margin-bottom:0.5rem;">
              {{ $m['title'] ?? '' }}
            </h4>
            <p style="font-size:0.875rem; color:var(--text-muted, #64748b); line-height:1.6; margin:0;">
              {{ $m['desc'] ?? '' }}
            </p>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- Section 4: Certified Engineering Compliance Badges -->
  <section style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding:3.5rem 0; color:#ffffff;">
    <div class="container" style="text-align:center;">
      <h3 style="font-size:1.6rem; font-weight:800; color:#ffffff; margin-bottom:0.75rem;">
        Operational Rigor & Compliance Standards
      </h3>
      <p style="font-size:0.95rem; color:#94a3b8; max-width:680px; margin:0 auto 2rem; line-height:1.6;">
        Every technician dispatched by FIX is background-verified, health-screened, and works under certified engineering protocols to protect client properties.
      </p>

      <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap;">
        <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:1rem 1.5rem; min-width:200px;">
          <div style="font-size:1.1rem; font-weight:700; color:#38bdf8;">✓ BNBC Standard</div>
          <div style="font-size:0.78rem; color:#cbd5e1; margin-top:4px;">Building Code Electrical Compliance</div>
        </div>
        <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:1rem 1.5rem; min-width:200px;">
          <div style="font-size:1.1rem; font-weight:700; color:#4ade80;">✓ NFPA Protocols</div>
          <div style="font-size:0.78rem; color:#cbd5e1; margin-top:4px;">Fire Safety & Suppression Systems</div>
        </div>
        <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:1rem 1.5rem; min-width:200px;">
          <div style="font-size:1.1rem; font-weight:700; color:#fbbf24;">✓ 24/7 Command Dispatch</div>
          <div style="font-size:0.78rem; color:#cbd5e1; margin-top:4px;">Real-Time Technical Squads</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 5: High-Converting CTA Banner -->
  <section class="section" style="padding:4.5rem 0; background:#ffffff; text-align:center;">
    <div class="container" style="max-width:800px; margin:0 auto;">
      <h2 style="font-size:2.2rem; font-weight:800; color:var(--text-main, #0f172a); margin-bottom:1rem;">
        Ready to Partner with Bangladesh's Top Facility Partner?
      </h2>
      <p style="font-size:1.05rem; color:var(--text-muted, #64748b); line-height:1.7; margin-bottom:2rem;">
        Whether you manage a multinational corporate headquarters, an industrial factory in EPZ, or a premium commercial plaza, FIX has the engineering horsepower to keep your assets secure.
      </p>

      <div style="display:flex; justify-content:center; gap:14px; flex-wrap:wrap;">
        <a href="{{ route('booking.create') }}" class="btn btn-accent btn-lg" style="padding:0.85rem 2rem; font-weight:700;">
          Book Service Now →
        </a>
        <a href="{{ route('vendor') }}" class="btn btn-outline btn-lg" style="padding:0.85rem 1.8rem; font-weight:600;">
          Become a Service Partner
        </a>
        <a href="{{ route('support.create') }}" class="btn btn-outline btn-lg" style="padding:0.85rem 1.8rem; font-weight:600;">
          Contact Support Helpdesk
        </a>
      </div>
    </div>
  </section>
</x-layouts.app>
