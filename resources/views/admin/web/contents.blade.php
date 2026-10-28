<x-layouts.admin title="Content Management | FIX Admin">
  <style>
    :root {
      --cms-brand: var(--paces-primary, #3e60d5);
      --cms-brand-dark: #2c4cb8;
      --cms-brand-subtle: rgba(62, 96, 213, 0.08);
      --cms-brand-border: rgba(62, 96, 213, 0.25);
    }
    .cms-brand-btn {
      background: var(--paces-primary, #3e60d5) !important;
      border-color: var(--paces-primary, #3e60d5) !important;
      color: #ffffff !important;
      transition: all 0.2s ease;
    }
    .cms-brand-btn:hover {
      background: #2c4cb8 !important;
      border-color: #2c4cb8 !important;
      box-shadow: 0 4px 12px rgba(62, 96, 213, 0.25);
    }
    .page-filter-btn {
      border: 1px solid var(--paces-border);
      background: #ffffff;
      color: #64748b;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 0.825rem;
      font-weight: 500;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease;
    }
    .page-filter-btn:hover {
      background: #f8fafc;
      color: #1e293b;
      border-color: #cbd5e1;
    }
    .page-filter-btn.active {
      background: var(--paces-primary, #3e60d5);
      color: #ffffff;
      border-color: var(--paces-primary, #3e60d5);
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(62, 96, 213, 0.25);
    }
    .live-preview-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--paces-primary, #3e60d5);
      background: rgba(62, 96, 213, 0.08);
      border: 1px solid rgba(62, 96, 213, 0.25);
      padding: 4px 11px;
      border-radius: 6px;
      text-decoration: none;
      transition: all 0.15s ease;
    }
    .live-preview-pill:hover {
      background: rgba(62, 96, 213, 0.18);
      color: #2c4cb8;
      border-color: rgba(62, 96, 213, 0.4);
      transform: translateY(-1px);
    }
    .page-group-divider {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-left: 4px solid var(--paces-primary, #3e60d5);
      padding: 10px 16px;
      border-radius: 8px;
      margin-top: 0.5rem;
      margin-bottom: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .switch-slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #cbd5e1;
      transition: .2s;
      border-radius: 24px;
    }
    .switch-slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .2s;
      border-radius: 50%;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    input:checked + .switch-slider {
      background-color: #10b981;
    }
    input:checked + .switch-slider:before {
      transform: translateX(20px);
    }
  </style>

  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Content Management</h1>
      <p class="page-header-subtitle">Manage homepage sections, partner recruitment, legal compliance, and customer helpdesk</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <a href="{{ \App\Models\SiteSetting::mainSiteUrl('') }}" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
    </div>
  </div>

  <x-admin.web-content-tabs active="contents" />

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#16a34a; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:flex-start; justify-content:space-between;">
      <div>
        <div style="font-weight:600; margin-bottom:4px;">Please correct the following errors:</div>
        <ul style="margin:0; padding-left:1.25rem; font-size:0.88rem;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Page Filter Toolbar & Real-Time Search -->
  <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; max-width:960px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <!-- Page Filter Buttons -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;" id="pageFilterContainer">
        <button type="button" class="page-filter-btn active" onclick="setPageFilter('all', this)">
          <span>All Pages</span>
          <span style="font-size:0.7rem; padding:1px 6px; border-radius:10px; background:rgba(255,255,255,0.25); color:inherit;">{{ $contents->count() }}</span>
        </button>
        <button type="button" class="page-filter-btn" onclick="setPageFilter('homepage', this)">
          <span>🏠 Homepage</span>
          <span style="font-size:0.7rem; padding:1px 6px; border-radius:10px; background:#f1f5f9; color:#64748b;">11</span>
        </button>
        <button type="button" class="page-filter-btn" onclick="setPageFilter('partner', this)">
          <span>🤝 Become a Partner</span>
          <span style="font-size:0.7rem; padding:1px 6px; border-radius:10px; background:#f1f5f9; color:#64748b;">2</span>
        </button>
        <button type="button" class="page-filter-btn" onclick="setPageFilter('legal', this)">
          <span>⚖️ Legal & Policies</span>
          <span style="font-size:0.7rem; padding:1px 6px; border-radius:10px; background:#f1f5f9; color:#64748b;">3</span>
        </button>
        <button type="button" class="page-filter-btn" onclick="setPageFilter('support', this)">
          <span>🎫 Support Helpdesk</span>
          <span style="font-size:0.7rem; padding:1px 6px; border-radius:10px; background:#f1f5f9; color:#64748b;">1</span>
        </button>
        <button type="button" class="page-filter-btn" onclick="setPageFilter('about', this)">
          <span>🏢 About Us</span>
          <span style="font-size:0.7rem; padding:1px 6px; border-radius:10px; background:#f1f5f9; color:#64748b;">2</span>
        </button>
      </div>

      <!-- Quick Search Box -->
      <div style="position:relative; width:260px;">
        <input type="text" id="sectionSearchInput" placeholder="Quick search sections..." oninput="filterSections()" 
          style="width:100%; font-size:0.825rem; padding:7px 12px 7px 32px; border-radius:8px; border:1px solid #cbd5e1; outline:none; transition:border-color 0.15s ease;"
          onfocus="this.style.borderColor='var(--paces-primary, #3e60d5)'" onblur="this.style.borderColor='#cbd5e1'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); pointer-events:none;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </div>
    </div>
  </div>

  @php
    $sectionNames = [
      'hero' => '1. Hero & Service Booking Banner',
      'clients' => '2. Corporate Partners (Clients Marquee)',
      'services' => '3. Facility Services Portfolio',
      'amc_plans' => '4. Annual Maintenance Contracts (AMC)',
      'how_it_works' => '5. How It Works (4-Step Process)',
      'video_portfolio' => '6. Operations Video Portfolio & Playlist',
      'why_fix' => '7. Why Choose FIX (6 Core Value Pillars)',
      'who_we_serve' => '8. Who We Serve (Industry Sectors)',
      'testimonials' => '9. Client Testimonials & Reviews',
      'faq' => '10. Frequently Asked Questions (FAQ)',
      'contact' => '11. Operations Command Center (Contact)',
      'vendor_hero' => '1. Partner Enlistment Hero & Metrics',
      'vendor_benefits' => '2. Contractor Benefits & Advantage Perks',
      'legal_privacy' => '1. Privacy Policy & Data Protection',
      'legal_terms' => '2. Terms of Service & General Conditions',
      'legal_sla' => '3. Service Level Agreement (SLA) & Guarantee',
      'support_desk' => '1. Support Helpdesk & Ticket Portal',
      'about_hero' => '1. About Us Hero & Enterprise Metrics',
      'about_overview' => '2. Mission, Vision, Values & Company Milestones',
    ];

    $liveUrls = [
      'hero' => url('/#hero'),
      'clients' => url('/#clients'),
      'services' => url('/#services'),
      'amc_plans' => url('/#amc-plans'),
      'how_it_works' => url('/#how-it-works'),
      'video_portfolio' => url('/#video-portfolio'),
      'why_fix' => url('/#why-fix'),
      'who_we_serve' => url('/#who-we-serve'),
      'testimonials' => url('/#testimonials'),
      'faq' => url('/#faq'),
      'contact' => url('/#contact'),
      'vendor_hero' => url('/vendor#vendor-hero'),
      'vendor_benefits' => url('/vendor#vendor-benefits'),
      'legal_privacy' => url('/privacy#legal-content'),
      'legal_terms' => url('/terms#legal-content'),
      'legal_sla' => url('/sla#legal-content'),
      'support_desk' => url('/support#support-portal'),
      'about_hero' => url('/about#overview'),
      'about_overview' => url('/about#overview'),
    ];

    $currentGroup = null;
  @endphp

  <!-- Section Cards Container -->
  <div id="sectionCardsContainer" style="display:flex; flex-direction:column; gap:1.25rem; max-width:960px;">
    @foreach($contents as $content)
      @php
        $displayTitle = $sectionNames[$content->section_key] ?? (ucwords(str_replace('_', ' ', $content->section_key)).' Section');
        $slides = $content->meta_data['slides'] ?? ($content->media_path ? [$content->media_path] : []);
        $meta = $content->meta_data ?? [];
        $pageGroup = $content->page_group ?? 'homepage';
        $pageBadge = $content->page_badge ?? '🏠 Homepage';
        $liveUrl = $liveUrls[$content->section_key] ?? url('/');
      @endphp

      @if($currentGroup !== $pageGroup)
        @php $currentGroup = $pageGroup; @endphp
        <div class="page-group-divider" data-group-divider="{{ $pageGroup }}">
          <div style="display:flex; align-items:center; gap:8px;">
            <strong style="font-size:0.95rem; color:#1e293b;">{{ $content->page_name ?? 'Page Sections' }}</strong>
            <span style="font-size:0.75rem; color:#64748b;">({{ $pageBadge }})</span>
          </div>
          <span style="font-size:0.75rem; color:var(--paces-primary, #3e60d5); font-weight:600;">Managed Content</span>
        </div>
      @endif

      <div class="card-paces section-card-item" id="section-card-{{ $content->id }}" data-page="{{ $pageGroup }}" data-search="{{ strtolower($displayTitle . ' ' . $content->title . ' ' . $pageBadge) }}" style="border-left: 4px solid {{ ($content->is_active ?? true) ? 'var(--paces-primary, #3e60d5)' : '#94a3b8' }}; {{ !($content->is_active ?? true) ? 'opacity: 0.9;' : '' }}">
        <!-- Card Top Bar: Title & Edit Button -->
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:1rem;">
          <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px; flex-wrap:wrap;">
              <h3 class="card-title-paces" style="margin:0; font-size:1.08rem; color:#1e293b; font-weight:600;">
                {{ $displayTitle }}
              </h3>
              <span style="font-size:0.75rem; font-weight:600; padding:2px 8px; border-radius:12px; background:rgba(62,96,213,0.1); color:var(--paces-primary, #3e60d5); border:1px solid rgba(62,96,213,0.2);">
                {{ $pageBadge }}
              </span>

              @if($content->is_active ?? true)
                <span class="badge-pill-paces" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:0.75rem; font-weight:600; padding:2px 8px; border-radius:12px; display:inline-flex; align-items:center; gap:4px;">
                  <span style="width:6px; height:6px; border-radius:50%; background:#10b981; display:inline-block;"></span>
                  Active on Website
                </span>
              @else
                <span class="badge-pill-paces" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-size:0.75rem; font-weight:600; padding:2px 8px; border-radius:12px; display:inline-flex; align-items:center; gap:4px;">
                  <span style="width:6px; height:6px; border-radius:50%; background:#ef4444; display:inline-block;"></span>
                  Hidden / Inactive
                </span>
              @endif
            </div>
            
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:2px;">
              <!-- Direct Live Section Link -->
              <a href="{{ $liveUrl }}" target="_blank" class="live-preview-pill" title="Preview live section on website (direct jump)">
                <span>View on Website</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
              </a>

              <span style="font-size:0.76rem; color:var(--paces-text-muted);">
                • Last Updated: {{ $content->updated_at ? $content->updated_at->diffForHumans() : 'Recently' }}
              </span>
            </div>
          </div>

          <!-- Quick Actions: Toggle Status & Edit -->
          <div style="display:flex; align-items:center; gap:8px;">
            <form action="{{ route('admin.web.contents.toggle', $content) }}" method="POST" style="margin:0;">
              @csrf
              @if($content->is_active ?? true)
                <button type="submit" 
                        class="btn-paces btn-paces-sm" 
                        title="Click to hide this section from the live website"
                        style="background:#ffffff; border:1px solid #cbd5e1; color:#64748b; font-size:0.8rem; font-weight:500; display:inline-flex; align-items:center; gap:5px; padding:6px 11px; border-radius:6px;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                  <span>Hide Section</span>
                </button>
              @else
                <button type="submit" 
                        class="btn-paces btn-paces-sm" 
                        title="Click to show this section on the live website"
                        style="background:#ecfdf5; border:1px solid #a7f3d0; color:#059669; font-size:0.8rem; font-weight:600; display:inline-flex; align-items:center; gap:5px; padding:6px 11px; border-radius:6px;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  <span>Show Section</span>
                </button>
              @endif
            </form>

            <button type="button" 
                    class="btn-paces cms-brand-btn btn-paces-sm" 
                    onclick="openSectionModal({{ $content->id }})"
                    style="display:inline-flex; align-items:center; gap:6px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              <span>Edit Section</span>
            </button>
          </div>
        </div>

        <!-- Section Content Readout / Summary Preview -->
        <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border);">
          <div style="display:grid; grid-template-columns: 1fr; gap:12px;">
            <!-- Title -->
            <div>
              <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Current Title / Headline:</span>
              <div style="font-size:1.02rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
                {!! $content->title !!}
              </div>
            </div>

            <!-- Subtitle (if applicable) -->
            @if($content->subtitle)
              <div>
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Subtitle / Subtext:</span>
                <div style="font-size:0.875rem; color:#475569; margin-top:2px; line-height:1.5;">
                  {{ $content->subtitle }}
                </div>
              </div>
            @endif

            <!-- 1. Hero Preview Details -->
            @if($content->section_key === 'hero')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px; margin-top:4px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Hero Buttons:</span>
                <div style="font-size:0.84rem; margin-top:3px; display:flex; gap:16px; flex-wrap:wrap;">
                  <span>Primary: <strong>{{ $content->button_text ?? 'Book a Service' }}</strong> (<code>{{ $content->button_url ?? '/booking' }}</code>)</span>
                  <span>Secondary: <strong>{{ $content->secondary_button_text ?? 'Explore Services' }}</strong> (<code>{{ $content->secondary_button_url ?? '#services' }}</code>)</span>
                </div>
              </div>

              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Trust Badges:</span>
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:6px;">
                  @foreach($meta['trust_badges'] ?? ['Verified & Certified Providers', 'Quick Service Request & Dispatch', 'Multiple Building Solutions', '24/7 Dedicated Operations Support'] as $tb)
                    <span class="badge-pill-paces" style="background:rgba(0,168,150,0.08); color:rgb(0,168,150); border:1px solid rgba(0,168,150,0.2); font-size:0.75rem;">✓ {{ $tb }}</span>
                  @endforeach
                </div>
              </div>

              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Hero Auto-Slider Images ({{ count($slides) }} Slides):</span>
                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:8px;">
                  @forelse($slides as $sIdx => $slide)
                    <div style="position:relative; width:110px; height:68px; border-radius:6px; overflow:hidden; border:1px solid var(--paces-border);">
                      <img src="{{ asset($slide) }}" alt="Slide {{ $sIdx + 1 }}" style="width:100%; height:100%; object-fit:cover;">
                      <span style="position:absolute; bottom:2px; left:3px; background:rgba(0,0,0,0.7); color:#fff; font-size:0.62rem; padding:1px 4px; border-radius:3px;">#{{ $sIdx + 1 }}</span>
                    </div>
                  @empty
                    <span style="font-size:0.8rem; color:#94a3b8;">Default slides in use.</span>
                  @endforelse
                </div>
              </div>
            @endif

            <!-- 2. Clients Preview Details -->
            @if($content->section_key === 'clients')
              @php
                $previewClients = $meta['clients'] ?? array_map(fn($n) => ['name' => $n, 'logo' => null], $meta['partner_names'] ?? [
                  'Standard Chartered Tower',
                  'Grameenphone GPHouse',
                  'Shanta Holdings Towers',
                  'Apex Footwear Complex',
                  'United Hospital Complex',
                  'Square Pharmaceuticals',
                  'Beximco Industrial Park',
                  'BRAC Centre & Campus'
                ]);
              @endphp
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                  <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">
                    Client Companies & Logos ({{ count($previewClients) }} Active):
                  </span>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                  @foreach($previewClients as $c)
                    <div style="display:flex; align-items:center; gap:8px; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; padding:4px 10px;">
                      @if(!empty($c['logo']))
                        <img src="{{ asset($c['logo']) }}" alt="{{ $c['name'] }}" style="height:20px; max-width:55px; object-fit:contain;">
                      @else
                        <span style="width:20px; height:20px; border-radius:4px; background:#f1f5f9; color:#64748b; font-size:0.65rem; font-weight:700; display:inline-flex; align-items:center; justify-content:center;">
                          {{ strtoupper(substr($c['name'] ?? 'C', 0, 1)) }}
                        </span>
                      @endif
                      <span style="font-size:0.78rem; font-weight:600; color:#1e293b;">{{ $c['name'] }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- 3. AMC Highlight Stats Preview -->
            @if($content->section_key === 'amc_plans')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Featured Highlight Counters:</span>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:10px; margin-top:6px;">
                  @foreach($meta['highlight_stats'] ?? [['val'=>'99.8%','lbl'=>'Equipment Uptime'],['val'=>'60 Min','lbl'=>'Emergency Response'],['val'=>'15%','lbl'=>'Energy Savings'],['val'=>'24/7','lbl'=>'Technical Support']] as $st)
                    <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px; border-radius:6px; text-align:center;">
                      <div style="font-weight:700; color:var(--paces-primary, #3e60d5); font-size:0.92rem;">{{ $st['val'] ?? '' }}</div>
                      <div style="font-size:0.72rem; color:var(--paces-text-muted);">{{ $st['lbl'] ?? '' }}</div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- 4. How It Works Steps Preview -->
            @if($content->section_key === 'how_it_works')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">4-Step Maintenance Workflow:</span>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px; margin-top:8px;">
                  @foreach($meta['steps'] ?? [
                    '1' => ['title' => 'Submit Service Request', 'desc' => 'Select building trade, specify urgency, and submit online dispatch.'],
                    '2' => ['title' => 'Diagnostic Assessment', 'desc' => 'Certified engineer inspects site and confirms pricing.'],
                    '3' => ['title' => 'Execution & Quality Check', 'desc' => 'Equipped technicians carry out repairs with safety compliance.'],
                    '4' => ['title' => 'Job Signoff & Warranty', 'desc' => 'Digital report delivered with 30-day workmanship guarantee.']
                  ] as $sNum => $sInfo)
                    <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px 10px; border-radius:6px; font-size:0.8rem;">
                      <strong style="display:block; margin-bottom:2px;">
                        <span style="color:var(--paces-primary, #3e60d5);">Step {{ $sNum }}:</span> {{ $sInfo['title'] ?? '' }}
                      </strong>
                      <span style="color:var(--paces-text-muted); font-size:0.75rem;">{{ $sInfo['desc'] ?? '' }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- 5. Video Portfolio Preview -->
            @if($content->section_key === 'video_portfolio')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px; font-size:0.84rem;">
                <div>YouTube Embed: <code style="color:var(--paces-primary, #3e60d5);">{{ $content->media_path ?? 'https://www.youtube.com/embed/LXb3EKWsInQ?rel=0' }}</code></div>
              </div>
            @endif

            <!-- 6. Why Choose FIX Preview -->
            @if($content->section_key === 'why_fix')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">6 Core Value Pillars:</span>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:8px; margin-top:8px;">
                  @foreach($meta['cards'] ?? [
                    ['title' => 'Certified Technical Teams', 'desc' => 'Every technician undergoes vetting, background checks, and standard safety training.'],
                    ['title' => 'Rapid Emergency Dispatch', 'desc' => 'Guaranteed lead technician response within 60 minutes across Dhaka & EPZ zones.'],
                    ['title' => '30-Day Workmanship Warranty', 'desc' => 'All non-cosmetic repairs are backed by our 30-day guarantee.'],
                    ['title' => 'Institutional Accountability', 'desc' => 'Standardized checklists, digital job cards, and transparent audit trails.'],
                    ['title' => 'Enterprise-Grade Equipment', 'desc' => 'Industrial thermal cameras, precision electrical meters, and certified safety gear.'],
                    ['title' => 'Cost-Predictable AMC Plans', 'desc' => 'Prevent costly failures with scheduled preventive maintenance and transparent pricing.']
                  ] as $cIdx => $c)
                    <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px 10px; border-radius:6px; font-size:0.78rem;">
                      <strong style="color:#1e293b; display:block;">✓ {{ $c['title'] ?? '' }}</strong>
                      <span style="color:#64748b;">{{ $c['desc'] ?? '' }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- 7. Become a Partner - Hero Preview -->
            @if($content->section_key === 'vendor_hero')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Enlistment Metric Badges:</span>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:10px; margin-top:6px;">
                  <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px; border-radius:6px; text-align:center;">
                    <div style="font-weight:700; color:var(--paces-primary, #3e60d5); font-size:0.95rem;">{{ $meta['stat_partners'] ?? '500+' }}</div>
                    <div style="font-size:0.72rem; color:var(--paces-text-muted);">{{ $meta['stat_partners_label'] ?? 'Enlisted Partners' }}</div>
                  </div>
                  <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px; border-radius:6px; text-align:center;">
                    <div style="font-weight:700; color:var(--paces-primary, #3e60d5); font-size:0.95rem;">{{ $meta['stat_payouts'] ?? '৳ 2.5 Cr+' }}</div>
                    <div style="font-size:0.72rem; color:var(--paces-text-muted);">{{ $meta['stat_payouts_label'] ?? 'Disbursed Payouts' }}</div>
                  </div>
                  <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px; border-radius:6px; text-align:center;">
                    <div style="font-weight:700; color:var(--paces-primary, #3e60d5); font-size:0.95rem;">{{ $meta['stat_verification'] ?? '24-48h' }}</div>
                    <div style="font-size:0.72rem; color:var(--paces-text-muted);">{{ $meta['stat_verification_label'] ?? 'Fast Verification' }}</div>
                  </div>
                </div>
              </div>
            @endif

            <!-- 8. Become a Partner - Benefits Preview -->
            @if($content->section_key === 'vendor_benefits')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Contractor Advantage Perks:</span>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:8px; margin-top:8px;">
                  @foreach($meta['perks'] ?? [
                    ['title' => 'Consistent Commercial Orders', 'description' => 'Direct institutional orders without marketing spending.'],
                    ['title' => 'Guaranteed Timely Payments', 'description' => 'Bi-weekly disbursements to company bank or bKash account.'],
                    ['title' => 'Free Safety Gear & ID Kits', 'description' => 'Standardized uniform vests, safety harnesses, and photo ID kits.']
                  ] as $p)
                    <div style="background:#ffffff; border:1px solid var(--paces-border); padding:8px 10px; border-radius:6px; font-size:0.78rem;">
                      <strong style="color:var(--paces-primary, #3e60d5); display:block;">★ {{ $p['title'] ?? '' }}</strong>
                      <span style="color:#64748b;">{{ $p['description'] ?? '' }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- 9. Legal Policy Clauses Preview -->
            @if(in_array($content->section_key, ['legal_privacy', 'legal_terms', 'legal_sla']))
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Policy Clauses & Clauses Overview:</span>
                <div style="display:flex; flex-direction:column; gap:6px; margin-top:6px;">
                  @foreach($meta['sections'] ?? [
                    ['heading' => '1. Scope / Information We Collect', 'body' => 'Overview of terms and data handling.'],
                    ['heading' => '2. SLA Standards & Commitments', 'body' => 'Execution protocols and emergency dispatch.'],
                  ] as $sec)
                    <div style="background:#ffffff; border:1px solid var(--paces-border); padding:6px 10px; border-radius:6px; font-size:0.78rem;">
                      <strong style="color:#1e293b;">{{ $sec['heading'] ?? '' }}</strong>
                      <div style="color:#64748b; font-size:0.75rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:600px;">{{ Str::limit($sec['body'] ?? '', 100) }}</div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- 10. Support Desk Preview -->
            @if($content->section_key === 'support_desk')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px; font-size:0.84rem;">
                <span>Helpdesk Button: <strong>{{ $content->button_text ?? 'Track Existing Ticket' }}</strong> (<code>{{ $content->button_url ?? '/support/track' }}</code>)</span>
              </div>
            @endif

            <!-- 11. About Hero Preview -->
            @if($content->section_key === 'about_hero')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px; font-size:0.84rem;">
                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:6px;">
                  <span>Experience: <strong>{{ $meta['stat_experience'] ?? '12+' }}</strong></span>
                  <span>•</span>
                  <span>Corporate Clients: <strong>{{ $meta['stat_clients'] ?? '150+' }}</strong></span>
                  <span>•</span>
                  <span>Engineers: <strong>{{ $meta['stat_technicians'] ?? '250+' }}</strong></span>
                  <span>•</span>
                  <span>SLA Reliability: <strong>{{ $meta['stat_satisfaction'] ?? '99.4%' }}</strong></span>
                </div>
                @if(!empty($meta['story_title']))
                  <div style="color:var(--paces-text-muted); font-size:0.8rem;">Story: <em>{{ $meta['story_title'] }}</em></div>
                @endif
              </div>
            @endif

            <!-- 12. About Overview Preview -->
            @if($content->section_key === 'about_overview')
              <div style="border-top:1px dashed var(--paces-border); padding-top:10px; font-size:0.84rem;">
                <div style="margin-bottom:6px;">
                  <strong>Mission:</strong> <span style="color:var(--paces-text-muted);">{{ Str::limit($meta['mission_desc'] ?? 'Elevate facility management standards...', 80) }}</span>
                </div>
                <div style="display:flex; gap:12px; flex-wrap:wrap; font-size:0.8rem; color:var(--paces-text-muted);">
                  <span>Core Values: <strong>{{ count($meta['values'] ?? [1,2,3,4]) }} Pillars</strong></span>
                  <span>•</span>
                  <span>Milestones: <strong>{{ count($meta['milestones'] ?? [1,2,3,4]) }} Key Years</strong></span>
                </div>
              </div>
            @endif

          </div>
        </div>
      </div>

      <!-- ============================================== -->
      <!-- EDIT SECTION MODAL FOR SECTION #{{ $content->id }} -->
      <!-- ============================================== -->
      <div id="editSectionModal_{{ $content->id }}" class="modal-backdrop-paces" style="display:none;">
        <div class="modal-dialog-paces" style="max-width:{{ in_array($content->section_key, ['hero', 'video_portfolio', 'how_it_works', 'why_fix', 'who_we_serve', 'amc_plans', 'vendor_hero', 'vendor_benefits', 'legal_privacy', 'legal_terms', 'legal_sla']) ? '780px' : '680px' }};">
          <div class="modal-header-paces" style="border-bottom:1px solid var(--paces-border);">
            <div>
              <h3 class="modal-title-paces" style="margin:0; font-size:1.15rem; color:#1e293b;">Edit: {{ $displayTitle }}</h3>
              <p style="margin:4px 0 0; font-size:0.78rem; color:var(--paces-text-muted); display:flex; align-items:center; gap:8px;">
                <span>Page: <strong>{{ $pageBadge }}</strong></span>
                <span>•</span>
                <a href="{{ $liveUrl }}" target="_blank" style="color:var(--paces-primary, #3e60d5); text-decoration:none; font-weight:600;">
                  Live Preview: {{ $liveUrl }} ↗
                </a>
              </p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeSectionModal({{ $content->id }})">✕</button>
          </div>

          <form action="{{ route('admin.web.contents.update', $content) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body-paces" style="max-height: calc(85vh - 140px); overflow-y: auto; padding:1.25rem;">
              
              <!-- Section Display Status (Active / Hidden Toggle) -->
              <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:0.9rem 1.15rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div>
                  <div style="font-weight:600; font-size:0.92rem; color:#1e293b; display:flex; align-items:center; gap:6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <span>Section Display Status</span>
                  </div>
                  <div style="font-size:0.78rem; color:#64748b; margin-top:2px;">
                    Turn off to hide this section from visitors on the live website. All section data remains saved.
                  </div>
                </div>
                <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                  <input type="hidden" name="is_active" value="0">
                  <label style="position:relative; display:inline-block; width:44px; height:24px; margin:0; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ ($content->is_active ?? true) ? 'checked' : '' }} style="opacity:0; width:0; height:0;" onchange="const lbl = document.getElementById('status-label-{{ $content->id }}'); if(lbl){ lbl.innerText = this.checked ? 'Active (Visible)' : 'Hidden (Inactive)'; lbl.style.color = this.checked ? '#059669' : '#dc2626'; }">
                    <span class="switch-slider"></span>
                  </label>
                  <span id="status-label-{{ $content->id }}" style="font-size:0.82rem; font-weight:600; color:{{ ($content->is_active ?? true) ? '#059669' : '#dc2626' }}; min-width:115px;">
                    {{ ($content->is_active ?? true) ? 'Active (Visible)' : 'Hidden (Inactive)' }}
                  </span>
                </div>
              </div>

              <!-- Section Title / Headline -->
              <div style="margin-bottom:1rem;">
                <label class="form-label-paces">Section Headline / Title *</label>
                <input type="text" name="title" value="{{ $content->title }}" class="form-control-paces" required>
                <span class="form-help-paces">You can wrap key words in &lt;span&gt;accent text&lt;/span&gt; for highlighted brand color.</span>
              </div>

              <!-- Section Subtitle -->
              <div style="margin-bottom:1rem;">
                <label class="form-label-paces">Section Subtitle / Supporting Paragraph</label>
                <textarea name="subtitle" class="form-control-paces" rows="3">{{ $content->subtitle }}</textarea>
              </div>

              <!-- Badge / Pill (Optional) -->
              @if(!in_array($content->section_key, ['legal_privacy', 'legal_terms', 'legal_sla']))
                <div style="margin-bottom:1rem;">
                  <label class="form-label-paces">Header Badge / Tag Pill (Optional)</label>
                  <input type="text" name="badge" value="{{ $content->badge }}" class="form-control-paces" placeholder="e.g. 24/7 NATIONWIDE SERVICE DISPATCH">
                </div>
              @endif

              <!-- Call to Action Buttons (For Hero, Contact, Vendor, Support) -->
              @if(in_array($content->section_key, ['hero', 'services', 'amc_plans', 'how_it_works', 'video_portfolio', 'contact', 'vendor_hero', 'support_desk']))
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Primary Call-to-Action Button</strong>
                  <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div>
                      <label class="form-label-paces">Button Label</label>
                      <input type="text" name="button_text" value="{{ $content->button_text }}" class="form-control-paces" placeholder="e.g. Book a Service">
                    </div>
                    <div>
                      <label class="form-label-paces">Button Destination Link</label>
                      <input type="text" name="button_url" value="{{ $content->button_url }}" class="form-control-paces" placeholder="e.g. /booking or #services">
                    </div>
                  </div>
                </div>

                @if(in_array($content->section_key, ['hero', 'vendor_hero']))
                  <div style="margin-bottom:1rem;">
                    <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Secondary Button</strong>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                      <div>
                        <label class="form-label-paces">Secondary Label</label>
                        <input type="text" name="secondary_button_text" value="{{ $content->secondary_button_text }}" class="form-control-paces" placeholder="e.g. Hotline Support">
                      </div>
                      <div>
                        <label class="form-label-paces">Secondary Destination</label>
                        <input type="text" name="secondary_button_url" value="{{ $content->secondary_button_url }}" class="form-control-paces" placeholder="e.g. tel:+8801700000000">
                      </div>
                    </div>
                  </div>
                @endif
              @endif

              <!-- 1. Hero Specialized Controls -->
              @if($content->section_key === 'hero')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Trust Badges (Under Hero Title)</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    @foreach($meta['trust_badges'] ?? ['Verified & Certified Providers', 'Quick Service Request & Dispatch', 'Multiple Building Solutions', '24/7 Dedicated Operations Support'] as $tIdx => $badgeText)
                      <div>
                        <input type="text" name="trust_badges[]" value="{{ $badgeText }}" class="form-control-paces" placeholder="Badge item #{{ $tIdx + 1 }}">
                      </div>
                    @endforeach
                  </div>
                </div>

                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Floating Hero Badge Card</strong>
                  <div style="display:grid; grid-template-columns:1fr 2fr; gap:12px;">
                    <div>
                      <label class="form-label-paces">Badge Title</label>
                      <input type="text" name="floating_badge_title" value="{{ $meta['floating_badge_title'] ?? 'Quality Guaranteed' }}" class="form-control-paces">
                    </div>
                    <div>
                      <label class="form-label-paces">Badge Description Text</label>
                      <input type="text" name="floating_badge_text" value="{{ $meta['floating_badge_text'] ?? '100% Verified Technical Teams & Rigorous Safety' }}" class="form-control-paces">
                    </div>
                  </div>
                </div>

                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Hero Auto-Slider Image Gallery</strong>
                  @if(!empty($slides))
                    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:12px;">
                      @foreach($slides as $sIdx => $slide)
                        <div style="position:relative; width:130px; height:80px; border-radius:6px; overflow:hidden; border:1px solid #cbd5e1;">
                          <img src="{{ asset($slide) }}" alt="Slide {{ $sIdx + 1 }}" style="width:100%; height:100%; object-fit:cover;">
                          <label style="position:absolute; top:4px; right:4px; background:rgba(220,38,38,0.9); color:#fff; font-size:0.65rem; padding:2px 5px; border-radius:4px; cursor:pointer;">
                            <input type="checkbox" name="remove_slides[]" value="{{ $slide }}" style="margin-right:2px;"> Delete
                          </label>
                        </div>
                      @endforeach
                    </div>
                  @endif
                  <label class="form-label-paces">Upload New Slide Images</label>
                  <input type="file" name="hero_images[]" multiple class="form-control-paces" accept="image/jpeg,image/png,image/webp">
                </div>
              @endif

              <!-- 2. Clients Marquee Controls -->
              @if($content->section_key === 'clients')
                @php
                  $editClients = $meta['clients'] ?? array_map(fn($n) => ['name' => $n, 'logo' => null], $meta['partner_names'] ?? [
                    'Standard Chartered Tower',
                    'Grameenphone GPHouse',
                    'Shanta Holdings Towers',
                    'Apex Footwear Complex',
                    'United Hospital Complex',
                    'Square Pharmaceuticals',
                    'Beximco Industrial Park',
                    'BRAC Centre & Campus'
                  ]);
                @endphp
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem; flex-wrap:wrap; gap:8px;">
                    <div>
                      <strong style="display:block; font-size:0.875rem; color:#1e293b;">Enterprise Clients & Logos</strong>
                      <span style="font-size:0.75rem; color:var(--paces-text-muted);">
                        Logos can be any aspect ratio (wide, square, or tall). They will scale smoothly without being cropped or cut off.
                      </span>
                    </div>
                    <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="addClientRow('clientRowsContainer_{{ $content->id }}')" style="padding:0.35rem 0.75rem; font-size:0.78rem;">
                      + Add New Client
                    </button>
                  </div>

                  <div id="clientRowsContainer_{{ $content->id }}" style="display:flex; flex-direction:column; gap:10px; max-height:360px; overflow-y:auto; padding-right:4px;">
                    @foreach($editClients as $cIdx => $c)
                      <div class="client-edit-row" style="display:grid; grid-template-columns:56px 1fr 140px 36px; gap:10px; align-items:center; background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:8px 10px;">
                        <!-- Logo Thumbnail -->
                        <div style="width:56px; height:40px; background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; display:flex; align-items:center; justify-content:center; overflow:hidden; padding:2px;">
                          @if(!empty($c['logo']))
                            <img src="{{ asset($c['logo']) }}" alt="Logo" style="max-width:100%; max-height:100%; object-fit:contain;">
                          @else
                            <span style="font-size:0.7rem; color:#94a3b8; font-weight:600;">No logo</span>
                          @endif
                        </div>
                        <input type="hidden" name="clients[{{ $cIdx }}][existing_logo]" value="{{ $c['logo'] ?? '' }}">

                        <!-- Company Title -->
                        <div>
                          <input type="text" name="clients[{{ $cIdx }}][name]" value="{{ $c['name'] ?? '' }}" class="form-control-paces" placeholder="Company / Enterprise Name" required style="font-size:0.85rem; font-weight:600;">
                        </div>

                        <!-- Change Logo File -->
                        <div>
                          <label style="font-size:0.72rem; color:var(--paces-text-muted); display:block; margin-bottom:2px; cursor:pointer;">
                            Upload Logo:
                          </label>
                          <input type="file" name="client_logos[{{ $cIdx }}]" accept="image/*" class="form-control-paces" style="font-size:0.72rem; padding:3px 6px;">
                        </div>

                        <!-- Delete Button -->
                        <div>
                          <button type="button" class="btn-paces btn-paces-danger btn-paces-sm" onclick="this.closest('.client-edit-row').remove()" style="padding:4px 8px; font-size:0.8rem; line-height:1;" title="Remove Client">
                            &times;
                          </button>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 3. AMC Highlight Stats -->
              @if($content->section_key === 'amc_plans')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">4 Highlight Statistic Counters</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    @foreach($meta['highlight_stats'] ?? [['val'=>'99.8%','lbl'=>'Equipment Uptime'],['val'=>'60 Min','lbl'=>'Emergency Response'],['val'=>'15%','lbl'=>'Energy Savings'],['val'=>'24/7','lbl'=>'Technical Support']] as $i => $st)
                      <div style="display:flex; gap:6px;">
                        <input type="text" name="highlight_stats[{{ $i }}][val]" value="{{ $st['val'] ?? '' }}" class="form-control-paces" style="width:35%;" placeholder="99.8%">
                        <input type="text" name="highlight_stats[{{ $i }}][lbl]" value="{{ $st['lbl'] ?? '' }}" class="form-control-paces" style="width:65%;" placeholder="Metric Label">
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 4. How It Works Steps -->
              @if($content->section_key === 'how_it_works')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">4-Step Maintenance Workflow</strong>
                  <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($meta['steps'] ?? [
                      '1' => ['title' => 'Submit Service Request', 'desc' => 'Select building trade, specify urgency, and submit online dispatch.'],
                      '2' => ['title' => 'Diagnostic Assessment', 'desc' => 'Certified engineer inspects site and confirms pricing.'],
                      '3' => ['title' => 'Execution & Quality Check', 'desc' => 'Equipped technicians carry out repairs with safety compliance.'],
                      '4' => ['title' => 'Job Signoff & Warranty', 'desc' => 'Digital report delivered with 30-day workmanship guarantee.']
                    ] as $s => $st)
                      <div style="background:#f8fafc; border:1px solid var(--paces-border); padding:8px 12px; border-radius:6px;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                          <span style="background:var(--paces-primary, #3e60d5); color:#fff; width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;">{{ $s }}</span>
                          <input type="text" name="steps[{{ $s }}][title]" value="{{ $st['title'] ?? '' }}" class="form-control-paces" style="font-weight:600;" placeholder="Step {{ $s }} Title">
                        </div>
                        <input type="text" name="steps[{{ $s }}][desc]" value="{{ $st['desc'] ?? '' }}" class="form-control-paces" style="font-size:0.825rem;" placeholder="Step description">
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 5. Why Choose FIX 6 Pillars -->
              @if($content->section_key === 'why_fix')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">6 Core Value Pillars</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    @foreach($meta['cards'] ?? [
                      ['title' => 'Certified Technical Teams', 'desc' => 'Every technician undergoes vetting, background checks, and standard safety training.'],
                      ['title' => 'Rapid Emergency Dispatch', 'desc' => 'Guaranteed lead technician response within 60 minutes across Dhaka & EPZ zones.'],
                      ['title' => '30-Day Workmanship Warranty', 'desc' => 'All non-cosmetic repairs are backed by our 30-day guarantee.'],
                      ['title' => 'Institutional Accountability', 'desc' => 'Standardized checklists, digital job cards, and transparent audit trails.'],
                      ['title' => 'Enterprise-Grade Equipment', 'desc' => 'Industrial thermal cameras, precision electrical meters, and certified safety gear.'],
                      ['title' => 'Cost-Predictable AMC Plans', 'desc' => 'Prevent costly failures with scheduled preventive maintenance and transparent pricing.']
                    ] as $cIdx => $c)
                      <div style="background:#f8fafc; border:1px solid var(--paces-border); padding:8px 10px; border-radius:6px;">
                        <input type="text" name="cards[{{ $cIdx }}][title]" value="{{ $c['title'] ?? '' }}" class="form-control-paces" style="font-weight:600; margin-bottom:4px;" placeholder="Pillar Title">
                        <textarea name="cards[{{ $cIdx }}][desc]" class="form-control-paces" rows="2" style="font-size:0.8rem;" placeholder="Pillar explanation">{{ $c['desc'] ?? '' }}</textarea>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 6. Video Portfolio Controls -->
              @if($content->section_key === 'video_portfolio')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">YouTube Video Player Link</strong>
                  <label class="form-label-paces">YouTube Embed URL *</label>
                  <input type="text" name="media_path" value="{{ $content->media_path ?? 'https://www.youtube.com/embed/LXb3EKWsInQ?rel=0' }}" class="form-control-paces" placeholder="https://www.youtube.com/embed/VIDEO_ID">
                </div>
              @endif

              <!-- 7. Become a Partner - Hero Controls -->
              @if($content->section_key === 'vendor_hero')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">3 Core Enlistment Metrics</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                    <div>
                      <label class="form-label-paces">Partners Metric</label>
                      <input type="text" name="stat_partners" value="{{ $meta['stat_partners'] ?? '500+' }}" class="form-control-paces">
                      <input type="text" name="stat_partners_label" value="{{ $meta['stat_partners_label'] ?? 'Enlisted Vendor Partners' }}" class="form-control-paces" style="margin-top:4px; font-size:0.8rem;">
                    </div>
                    <div>
                      <label class="form-label-paces">Payouts Metric</label>
                      <input type="text" name="stat_payouts" value="{{ $meta['stat_payouts'] ?? '৳ 2.5 Cr+' }}" class="form-control-paces">
                      <input type="text" name="stat_payouts_label" value="{{ $meta['stat_payouts_label'] ?? 'Partner Payouts Disbursed' }}" class="form-control-paces" style="margin-top:4px; font-size:0.8rem;">
                    </div>
                    <div>
                      <label class="form-label-paces">Verification Metric</label>
                      <input type="text" name="stat_verification" value="{{ $meta['stat_verification'] ?? '24-48h' }}" class="form-control-paces">
                      <input type="text" name="stat_verification_label" value="{{ $meta['stat_verification_label'] ?? 'Fast-Track Verification' }}" class="form-control-paces" style="margin-top:4px; font-size:0.8rem;">
                    </div>
                  </div>
                </div>

                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Floating Image Badges</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                      <label class="form-label-paces">Top Badge Title & Text</label>
                      <input type="text" name="floating_badge_top_title" value="{{ $meta['floating_badge_top_title'] ?? 'Guaranteed Corporate Payouts' }}" class="form-control-paces">
                      <input type="text" name="floating_badge_top_text" value="{{ $meta['floating_badge_top_text'] ?? 'Direct Bank & bKash Bi-Weekly Settlement' }}" class="form-control-paces" style="margin-top:4px; font-size:0.8rem;">
                    </div>
                    <div>
                      <label class="form-label-paces">Bottom Badge Title & Text</label>
                      <input type="text" name="floating_badge_bottom_title" value="{{ $meta['floating_badge_bottom_title'] ?? '4.9 Partner Rating' }}" class="form-control-paces">
                      <input type="text" name="floating_badge_bottom_text" value="{{ $meta['floating_badge_bottom_text'] ?? '98% Contractor Retention Across Dhaka & EPZ' }}" class="form-control-paces" style="margin-top:4px; font-size:0.8rem;">
                    </div>
                  </div>
                </div>

                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <label class="form-label-paces">Replace Hero Visual Image</label>
                  <input type="file" name="section_image" class="form-control-paces" accept="image/*">
                </div>
              @endif

              <!-- 8. Become a Partner - Benefits Controls -->
              @if($content->section_key === 'vendor_benefits')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">3 Contractor Advantage Perks</strong>
                  <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($meta['perks'] ?? [
                      ['title' => 'Consistent Commercial Orders', 'description' => 'Gain direct access to corporate headquarters, hospitals, EPZ industrial facilities, and luxury residences without spending on marketing.'],
                      ['title' => 'Guaranteed Timely Payments', 'description' => 'Transparent job completion verification with automatic bi-weekly or monthly disbursements directly to your company bank or bKash account.'],
                      ['title' => 'Free Safety Gear & ID Kits', 'description' => 'Enlisted partner technicians receive standardized FIX professional uniform vests, high-altitude harnesses, safety kits, and verified photo ID badges.']
                    ] as $pIdx => $p)
                      <div style="background:#f8fafc; border:1px solid var(--paces-border); padding:10px; border-radius:6px;">
                        <label class="form-label-paces">Perk #{{ $pIdx + 1 }} Title</label>
                        <input type="text" name="perks[{{ $pIdx }}][title]" value="{{ $p['title'] ?? '' }}" class="form-control-paces" style="font-weight:600; margin-bottom:6px;">
                        <label class="form-label-paces">Perk #{{ $pIdx + 1 }} Description</label>
                        <textarea name="perks[{{ $pIdx }}][description]" class="form-control-paces" rows="2">{{ $p['description'] ?? '' }}</textarea>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 9. Legal Policy Clauses Controls -->
              @if(in_array($content->section_key, ['legal_privacy', 'legal_terms', 'legal_sla']))
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Policy Sections & Clauses</strong>
                  <div style="display:flex; flex-direction:column; gap:12px;" id="legalSectionsContainer_{{ $content->id }}">
                    @foreach($meta['sections'] ?? [
                      ['heading' => '1. Scope of Services', 'body' => 'Details of services provided.'],
                      ['heading' => '2. Safety & Operational Standards', 'body' => 'Compliance protocols and guarantees.']
                    ] as $sIdx => $sec)
                      <div style="background:#f8fafc; border:1px solid var(--paces-border); padding:10px 12px; border-radius:8px;">
                        <label class="form-label-paces">Clause #{{ $sIdx + 1 }} Heading</label>
                        <input type="text" name="legal_sections[{{ $sIdx }}][heading]" value="{{ $sec['heading'] ?? '' }}" class="form-control-paces" style="font-weight:600; margin-bottom:6px;">
                        <label class="form-label-paces">Clause Content / Policy Text</label>
                        <textarea name="legal_sections[{{ $sIdx }}][body]" class="form-control-paces" rows="4">{{ $sec['body'] ?? '' }}</textarea>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <!-- 10. About Us Hero & Stats Controls -->
              @if($content->section_key === 'about_hero')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Key Statistics & Metrics Badges</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:1rem;">
                    <div>
                      <label class="form-label-paces">Years of Experience</label>
                      <input type="text" name="stat_experience" value="{{ $meta['stat_experience'] ?? '12+' }}" class="form-control-paces">
                      <label class="form-label-paces" style="margin-top:4px;">Experience Label</label>
                      <input type="text" name="stat_experience_label" value="{{ $meta['stat_experience_label'] ?? 'Years of Engineering Excellence' }}" class="form-control-paces">
                    </div>
                    <div>
                      <label class="form-label-paces">Corporate Clients</label>
                      <input type="text" name="stat_clients" value="{{ $meta['stat_clients'] ?? '150+' }}" class="form-control-paces">
                      <label class="form-label-paces" style="margin-top:4px;">Clients Label</label>
                      <input type="text" name="stat_clients_label" value="{{ $meta['stat_clients_label'] ?? 'Enterprise Facilities Managed' }}" class="form-control-paces">
                    </div>
                    <div>
                      <label class="form-label-paces">Certified Engineers / Techs</label>
                      <input type="text" name="stat_technicians" value="{{ $meta['stat_technicians'] ?? '250+' }}" class="form-control-paces">
                      <label class="form-label-paces" style="margin-top:4px;">Engineers Label</label>
                      <input type="text" name="stat_technicians_label" value="{{ $meta['stat_technicians_label'] ?? 'Certified Engineers & Techs' }}" class="form-control-paces">
                    </div>
                    <div>
                      <label class="form-label-paces">SLA Satisfaction Rate</label>
                      <input type="text" name="stat_satisfaction" value="{{ $meta['stat_satisfaction'] ?? '99.4%' }}" class="form-control-paces">
                      <label class="form-label-paces" style="margin-top:4px;">Satisfaction Label</label>
                      <input type="text" name="stat_satisfaction_label" value="{{ $meta['stat_satisfaction_label'] ?? 'SLA Reliability Rate' }}" class="form-control-paces">
                    </div>
                  </div>

                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Company Background & Executive Story</strong>
                  <div style="margin-bottom:0.75rem;">
                    <label class="form-label-paces">Story Headline / Title</label>
                    <input type="text" name="story_title" value="{{ $meta['story_title'] ?? '' }}" class="form-control-paces">
                  </div>
                  <div style="margin-bottom:0.75rem;">
                    <label class="form-label-paces">Story Paragraph 1</label>
                    <textarea name="story_p1" class="form-control-paces" rows="3">{{ $meta['story_p1'] ?? '' }}</textarea>
                  </div>
                  <div style="margin-bottom:0.75rem;">
                    <label class="form-label-paces">Story Paragraph 2 (Headquarters & Coverage)</label>
                    <textarea name="story_p2" class="form-control-paces" rows="3">{{ $meta['story_p2'] ?? '' }}</textarea>
                  </div>
                </div>
              @endif

              <!-- 11. About Us Mission, Vision, Values & Milestones -->
              @if($content->section_key === 'about_overview')
                <div style="border-top:1px dashed var(--paces-border); padding-top:1rem; margin-bottom:1rem;">
                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Mission & Vision Statements</strong>
                  <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:1rem;">
                    <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px;">
                      <label class="form-label-paces">Mission Card Title</label>
                      <input type="text" name="mission_title" value="{{ $meta['mission_title'] ?? 'Our Mission' }}" class="form-control-paces" style="margin-bottom:6px;">
                      <label class="form-label-paces">Mission Statement</label>
                      <textarea name="mission_desc" class="form-control-paces" rows="3">{{ $meta['mission_desc'] ?? '' }}</textarea>
                    </div>
                    <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px;">
                      <label class="form-label-paces">Vision Card Title</label>
                      <input type="text" name="vision_title" value="{{ $meta['vision_title'] ?? 'Our Vision' }}" class="form-control-paces" style="margin-bottom:6px;">
                      <label class="form-label-paces">Vision Statement</label>
                      <textarea name="vision_desc" class="form-control-paces" rows="3">{{ $meta['vision_desc'] ?? '' }}</textarea>
                    </div>
                  </div>

                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Core Values & Principles (4 Pillars)</strong>
                  <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:1rem;">
                    @foreach($meta['values'] ?? [
                      ['title' => 'BNBC & NFPA Certified Safety', 'desc' => 'Strict adherence to national and international building codes.'],
                      ['title' => '2-Hour Emergency SLA', 'desc' => 'Rapid technical squads stationed at regional hubs for immediate crisis mitigation.'],
                      ['title' => 'Transparent Digital Tracking', 'desc' => 'Every maintenance request is digitally documented with verified before-after logs.'],
                      ['title' => 'Eco-Friendly & Sustainable', 'desc' => 'Energy-efficient HVAC optimization and preventive asset lifecycle planning.']
                    ] as $vIdx => $val)
                      <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px;">
                        <label class="form-label-paces">Pillar #{{ $vIdx + 1 }} Title</label>
                        <input type="text" name="values[{{ $vIdx }}][title]" value="{{ $val['title'] ?? '' }}" class="form-control-paces" style="margin-bottom:6px; font-weight:600;">
                        <label class="form-label-paces">Pillar Description</label>
                        <textarea name="values[{{ $vIdx }}][desc]" class="form-control-paces" rows="2">{{ $val['desc'] ?? '' }}</textarea>
                      </div>
                    @endforeach
                  </div>

                  <strong style="display:block; font-size:0.875rem; margin-bottom:0.75rem; color:#1e293b;">Company Milestones & History Timeline</strong>
                  <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($meta['milestones'] ?? [
                      ['year' => '2014', 'title' => 'MEP Operations Founded', 'desc' => 'Launched specialized electrical and mechanical facility engineering services.'],
                      ['year' => '2018', 'title' => 'Enterprise Expansion', 'desc' => 'Expanded to 50+ corporate headquarters and EPZ manufacturing facilities.'],
                      ['year' => '2022', 'title' => '24/7 Command Center', 'desc' => 'Built our real-time centralized emergency dispatch control room.'],
                      ['year' => '2026', 'title' => 'Unified Digital Platform', 'desc' => 'Integrated self-service customer booking and transparent AMC tracking.']
                    ] as $mIdx => $m)
                      <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:8px; padding:10px;">
                        <div style="display:grid; grid-template-columns:100px 1fr; gap:10px; margin-bottom:6px;">
                          <div>
                            <label class="form-label-paces">Year</label>
                            <input type="text" name="milestones[{{ $mIdx }}][year]" value="{{ $m['year'] ?? '' }}" class="form-control-paces" style="font-weight:700;">
                          </div>
                          <div>
                            <label class="form-label-paces">Milestone Title</label>
                            <input type="text" name="milestones[{{ $mIdx }}][title]" value="{{ $m['title'] ?? '' }}" class="form-control-paces" style="font-weight:600;">
                          </div>
                        </div>
                        <label class="form-label-paces">Milestone Description</label>
                        <textarea name="milestones[{{ $mIdx }}][desc]" class="form-control-paces" rows="2">{{ $m['desc'] ?? '' }}</textarea>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

            </div>

            <div class="modal-footer-paces" style="border-top:1px solid var(--paces-border); padding:12px 18px; display:flex; justify-content:flex-end; gap:8px;">
              <button type="button" class="btn-paces btn-paces-outline" onclick="closeSectionModal({{ $content->id }})">
                Cancel
              </button>
              <button type="submit" class="btn-paces cms-brand-btn" style="display:inline-flex; align-items:center; gap:6px;">
                <span>Save Section & Log Changes</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
              </button>
            </div>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  <script>
    let activePage = 'all';

    function setPageFilter(page, btn) {
      activePage = page;
      document.querySelectorAll('#pageFilterContainer .page-filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      filterSections();
    }

    function filterSections() {
      const query = (document.getElementById('sectionSearchInput')?.value || '').toLowerCase().trim();
      const cards = document.querySelectorAll('.section-card-item');
      const dividers = document.querySelectorAll('.page-group-divider');

      cards.forEach(card => {
        const page = card.getAttribute('data-page');
        const searchContent = card.getAttribute('data-search') || '';
        const matchesPage = (activePage === 'all' || page === activePage);
        const matchesSearch = (!query || searchContent.includes(query));

        if (matchesPage && matchesSearch) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });

      // Show/hide page group dividers based on active page and search
      dividers.forEach(div => {
        const group = div.getAttribute('data-group-divider');
        if (activePage !== 'all' || query.length > 0) {
          div.style.display = 'none';
        } else {
          div.style.display = 'flex';
        }
      });
    }

    function openSectionModal(id) {
      const modal = document.getElementById('editSectionModal_' + id);
      if (modal) modal.style.display = 'flex';
    }

    function closeSectionModal(id) {
      const modal = document.getElementById('editSectionModal_' + id);
      if (modal) modal.style.display = 'none';
    }

    function addClientRow(containerId) {
      const container = document.getElementById(containerId);
      if (!container) return;
      const newIdx = 'new_' + Date.now();
      const div = document.createElement('div');
      div.className = 'client-edit-row';
      div.style.cssText = 'display:grid; grid-template-columns:56px 1fr 140px 36px; gap:10px; align-items:center; background:#f0fdf4; border:1px solid #86efac; border-radius:8px; padding:8px 10px;';
      div.innerHTML = `
        <div style="width:56px; height:40px; background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; display:flex; align-items:center; justify-content:center; overflow:hidden; padding:2px;">
          <span style="font-size:0.7rem; color:#16a34a; font-weight:700;">NEW</span>
        </div>
        <div>
          <input type="text" name="new_clients[${newIdx}][name]" placeholder="New Company / Enterprise Name" class="form-control-paces" required style="font-size:0.85rem; font-weight:600;">
        </div>
        <div>
          <label style="font-size:0.72rem; color:var(--paces-text-muted); display:block; margin-bottom:2px;">Upload Logo:</label>
          <input type="file" name="new_client_logos[${newIdx}]" accept="image/*" class="form-control-paces" style="font-size:0.72rem; padding:3px 6px;">
        </div>
        <div>
          <button type="button" class="btn-paces btn-paces-danger btn-paces-sm" onclick="this.closest('.client-edit-row').remove()" style="padding:4px 8px; font-size:0.8rem; line-height:1;" title="Remove Client">&times;</button>
        </div>
      `;
      container.appendChild(div);
      container.scrollTop = container.scrollHeight;
    }

    window.addEventListener('click', function(e) {
      if (e.target.classList.contains('modal-backdrop-paces')) {
        e.target.style.display = 'none';
      }
    });
  </script>
</x-layouts.admin>
