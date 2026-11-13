<x-partner.layouts.app :title="'Settings'">
  <style>
    .settings-layout {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      align-items: start;
    }
    @media (min-width: 860px) {
      .settings-layout {
        grid-template-columns: 260px 1fr;
      }
    }

    /* Menu Container */
    .settings-menu {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    @media (min-width: 860px) {
      .settings-menu {
        background: #ffffff;
        border: 1px solid var(--p-border);
        border-radius: var(--p-radius);
        box-shadow: var(--p-shadow);
        padding: 8px;
        gap: 4px;
      }
    }

    /* Menu Item Button */
    .settings-menu-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 14px 16px;
      border-radius: 12px;
      border: 1px solid var(--p-border);
      background: #ffffff;
      color: #1e293b;
      font-size: 0.92rem;
      font-weight: 700;
      cursor: pointer;
      text-align: left;
      width: 100%;
      transition: all 0.15s ease;
      box-shadow: var(--p-shadow-sm);
    }
    .settings-menu-btn:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }
    .settings-menu-btn-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .menu-icon-wrap {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: #f1f5f9;
      color: #475569;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: all 0.15s ease;
    }
    .menu-icon-wrap svg {
      width: 20px;
      height: 20px;
    }
    .settings-chevron {
      color: #94a3b8;
      flex-shrink: 0;
      transition: transform 0.15s ease;
    }

    /* Desktop Adjustments for Menu */
    @media (min-width: 860px) {
      .settings-menu-btn {
        border: none;
        box-shadow: none;
        padding: 10px 12px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #475569;
        border-radius: 8px;
        background: transparent;
      }
      .settings-menu-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
      }
      .settings-menu-btn.active {
        background: #f0fdfa;
        color: var(--p-accent);
        font-weight: 700;
      }
      .settings-menu-btn.active .menu-icon-wrap {
        background: #ccfbf1;
        color: var(--p-accent);
      }
      .menu-icon-wrap {
        width: 26px;
        height: 26px;
        background: transparent;
      }
      .menu-icon-wrap svg {
        width: 18px;
        height: 18px;
      }
      .settings-chevron {
        display: none;
      }
    }

    /* Mobile Visibility Logic */
    @media (max-width: 859px) {
      .settings-menu.mobile-hidden {
        display: none !important;
      }
      .settings-content-wrap.mobile-hidden {
        display: none !important;
      }
    }
    @media (min-width: 860px) {
      .settings-menu {
        display: flex !important;
      }
      .settings-content-wrap {
        display: block !important;
      }
      .mobile-back-bar {
        display: none !important;
      }
    }

    .mobile-back-bar {
      margin-bottom: 14px;
    }

    /* Tab Panels */
    .settings-tab-panel {
      display: none;
      animation: tabFadeIn 0.15s ease-out;
    }
    .settings-tab-panel.active {
      display: block;
    }
    @keyframes tabFadeIn {
      from { opacity: 0; transform: translateY(4px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <div style="margin-bottom: 20px;">
    <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em;">Settings</h1>
  </div>

  <div class="settings-layout">
    <!-- Menu List (Sidebar on desktop, Full-width vertical list on mobile) -->
    <aside id="settingsMenu" class="settings-menu">
      <button type="button" class="settings-menu-btn active" data-tab="tab-profile" onclick="switchSettingsTab('tab-profile')">
        <span class="settings-menu-btn-left">
          <span class="menu-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          </span>
          <span>Account Profile</span>
        </span>
        <svg class="settings-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>

      <button type="button" class="settings-menu-btn" data-tab="tab-security" onclick="switchSettingsTab('tab-security')">
        <span class="settings-menu-btn-left">
          <span class="menu-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          </span>
          <span>Security & Password</span>
        </span>
        <svg class="settings-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>

      <button type="button" class="settings-menu-btn" data-tab="tab-notifications" onclick="switchSettingsTab('tab-notifications')">
        <span class="settings-menu-btn-left">
          <span class="menu-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          </span>
          <span>Email Notifications</span>
        </span>
        <svg class="settings-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>

      <button type="button" class="settings-menu-btn" data-tab="tab-payout" onclick="switchSettingsTab('tab-payout')">
        <span class="settings-menu-btn-left">
          <span class="menu-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          </span>
          <span>Payout Method</span>
        </span>
        <svg class="settings-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>

      <a href="{{ route('partner.notifications.index') }}" class="settings-menu-btn" style="text-decoration: none; color: inherit;">
        <span class="settings-menu-btn-left">
          <span class="menu-icon-wrap" style="position: relative;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          </span>
          <span>Notification Center</span>
        </span>
        <div style="display: flex; align-items: center; gap: 8px;">
          @php $unrCount = auth('partner')->user()?->unreadNotificationsCount() ?? 0; @endphp
          @if($unrCount > 0)
            <span style="background: #ef4444; color: #fff; font-size: 0.7rem; font-weight: 800; padding: 2px 7px; border-radius: 9999px;">{{ $unrCount }} New</span>
          @endif
          <svg class="settings-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </div>
      </a>

      <!-- Sign Out Action -->
      <div style="border-top: 1px solid var(--p-border); margin-top: 4px; padding-top: 6px;">
        <button type="button" class="settings-menu-btn" style="color: #ef4444;" onclick="confirmDialog({ title:'Sign Out?', text:'Are you sure you want to end your current session?', type:'danger', confirmText:'Sign Out', formId:'settingsLogoutForm' })">
          <span class="settings-menu-btn-left">
            <span class="menu-icon-wrap" style="background: #fef2f2; color: #ef4444;">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </span>
            <span style="font-weight: 700;">Sign Out</span>
          </span>
          <svg class="settings-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
    </aside>

    <form id="settingsLogoutForm" action="{{ route('partner.logout') }}" method="POST" style="display: none;">
      @csrf
    </form>

    <!-- Right Content Panels wrapped in single form -->
    <div id="settingsContentWrap" class="settings-content-wrap">
      <!-- Mobile Back to Menu Bar -->
      <div class="mobile-back-bar">
        <button type="button" class="btn-p btn-p-outline" onclick="backToMobileMenu()" style="padding: 8px 14px; font-size: 0.82rem; font-weight: 700; gap: 7px;">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          <span>Back to Menu</span>
        </button>
      </div>

      <form id="profileUpdateForm" action="{{ route('partner.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Tab 1: Account Profile -->
        <div id="tab-profile" class="settings-tab-panel active">
          <!-- 1. Profile Details Overview Card (Default View) -->
          <div id="crewProfileDetailsCard" class="p-card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px; margin-bottom: 20px; border-bottom: 1px solid var(--p-border); padding-bottom: 16px;">
              <div>
                <h3 class="p-card-title" style="margin-bottom: 4px;">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                  <span>Crew Profile Details</span>
                </h3>
                <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Your registered contractor &amp; field crew operational profile.</p>
              </div>

              <button type="button" class="btn-p btn-p-primary" onclick="showCrewProfileEdit()" style="padding: 9px 18px; font-size: 0.84rem; font-weight: 700; gap: 7px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <span>Edit Profile</span>
              </button>
            </div>

            <!-- Profile Header with Avatar & Identity -->
            <div style="display: flex; align-items: center; gap: 20px; padding: 18px 20px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; margin-bottom: 24px; flex-wrap: wrap;">
              <div style="width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, var(--p-accent) 0%, var(--p-primary) 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 700; overflow: hidden; border: 3px solid #e2e8f0; box-shadow: 0 4px 10px rgba(0,0,0,0.08); flex-shrink: 0;">
                @if($partner->avatar_url)
                  <img src="{{ $partner->avatar_url }}" alt="{{ $partner->contact_person }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                  <span>{{ $partner->initials }}</span>
                @endif
              </div>

              <div style="flex: 1; min-width: 220px;">
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 4px;">
                  <h2 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">{{ $partner->contact_person }}</h2>
                  <span style="font-size: 0.72rem; font-weight: 700; color: #0d9488; background: #f0fdfa; border: 1px solid #ccfbf1; padding: 3px 10px; border-radius: 9999px; text-transform: uppercase;">
                    Verified {{ ucfirst($partner->partner_type ?? 'Crew') }}
                  </span>
                </div>
                <div style="font-size: 0.92rem; font-weight: 600; color: #475569; margin-bottom: 8px;">{{ $partner->company_name }}</div>
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; font-size: 0.82rem; color: #64748b;">
                  <span style="display: inline-flex; align-items: center; gap: 5px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    {{ $partner->phone }}
                  </span>
                  @if($partner->email)
                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                      {{ $partner->email }}
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <!-- Details Field Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 24px;">
              <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;">
                <div style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Contact Person</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">{{ $partner->contact_person }}</div>
              </div>

              <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;">
                <div style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Registered Mobile (Login)</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">{{ $partner->phone }}</div>
              </div>

              <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;">
                <div style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Official Email</div>
                <div style="font-size: 0.95rem; font-weight: 600; color: {{ $partner->email ? '#1e293b' : '#94a3b8' }};">
                  {{ $partner->email ?: 'Not provided' }}
                </div>
              </div>

              <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;">
                <div style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Active Field Technicians</div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">{{ $partner->technicians_count ?: '1-5' }} Technicians</div>
              </div>
            </div>

            <!-- Service Types / Trade Domains -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 18px;">
              <div style="font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                <span>Service Types &amp; Core Competencies</span>
              </div>
              <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @php
                  $partnerDomains = array_filter(array_map('trim', explode(',', (string) ($partner->trade_domain ?? ''))));
                @endphp
                @forelse($partnerDomains as $pDomain)
                  <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 9999px; font-size: 0.84rem; font-weight: 600; color: #0f172a;">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: var(--p-accent);"></span>
                    {{ $pDomain }}
                  </span>
                @empty
                  <span style="font-size: 0.82rem; color: #94a3b8; font-style: italic;">No service domains configured yet.</span>
                @endforelse
              </div>
            </div>

            <!-- Operating Coverage Areas -->
            <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
              <div style="font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>Assigned Operating Coverage Areas</span>
              </div>
              <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @php
                  $partnerAreas = array_filter(array_map('trim', explode(',', (string) ($partner->coverage_areas ?? ''))));
                @endphp
                @forelse($partnerAreas as $pArea)
                  <span style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; background: #f0fdfa; border: 1.5px solid #ccfbf1; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; color: var(--p-accent);">
                    <span>📍</span>
                    <span>{{ $pArea }}</span>
                  </span>
                @empty
                  <span style="font-size: 0.82rem; color: #94a3b8; font-style: italic;">No operating coverage areas assigned yet.</span>
                @endforelse
              </div>
            </div>
          </div>

          <!-- 2. Profile Edit Card (Hidden by default, shown when tapping Edit Profile) -->
          <div id="crewProfileEditCard" class="p-card" style="display: none; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; border-bottom: 1px solid var(--p-border); padding-bottom: 12px;">
              <h3 class="p-card-title" style="margin: 0;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <span>Edit Account Profile</span>
              </h3>

              <button type="button" class="btn-p btn-p-outline" onclick="showCrewProfileDetails()" style="padding: 6px 14px; font-size: 0.8rem; font-weight: 700; gap: 6px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>&larr; Back to Profile</span>
              </button>
            </div>

            <!-- Profile Picture (Avatar) -->
            <div style="display: flex; align-items: center; gap: 20px; padding: 14px 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px;">
              <div id="partnerAvatarPreviewBox" style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, var(--p-accent) 0%, var(--p-primary) 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; overflow: hidden; border: 2.5px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.06); flex-shrink: 0;">
                @if($partner->avatar_url)
                  <img id="partnerAvatarImg" src="{{ $partner->avatar_url }}" alt="{{ $partner->contact_person }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                @else
                  <span id="partnerAvatarInitials">{{ $partner->initials }}</span>
                  <img id="partnerAvatarImg" src="" alt="Avatar Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                @endif
              </div>

              <div style="flex: 1;">
                <label class="p-label" style="font-size: 0.88rem; font-weight: 700; margin-bottom: 3px; display: block;">Profile Picture</label>
                <div style="font-size: 0.76rem; color: #64748b; margin-bottom: 10px;">Upload your personal or business photo (PNG, JPG, WebP up to 3MB).</div>

                <input type="file" name="avatar" id="partnerAvatarInput" accept="image/png,image/jpeg,image/webp" style="display: none;" onchange="previewPartnerAvatar(this)">
                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                  <button type="button" class="btn-p btn-p-primary" style="padding: 6px 14px; font-size: 0.78rem;" onclick="document.getElementById('partnerAvatarInput').click()">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <span>Upload Photo</span>
                  </button>

                  @if($partner->avatar)
                    <button type="button" id="removeAvatarBtn" class="btn-p btn-p-outline" style="padding: 6px 12px; font-size: 0.78rem; color: #ef4444; border-color: #fca5a5;" onclick="triggerRemoveAvatar()">
                      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                      <span>Remove</span>
                    </button>
                  @endif
                </div>
              </div>
            </div>

            <div style="margin-bottom: 14px;">
              <label class="p-label">Business / Entity Name</label>
              <input type="text" value="{{ $partner->company_name }}" class="p-input" disabled style="background: #f1f5f9; color: #64748b; cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 14px;">
              <label class="p-label">Contact Person *</label>
              <input type="text" name="contact_person" class="p-input" value="{{ old('contact_person', $partner->contact_person) }}" required>
            </div>

            <div style="margin-bottom: 14px;">
              <label class="p-label">Phone (Login)</label>
              <input type="text" value="{{ $partner->phone }}" class="p-input" disabled style="background: #f1f5f9; color: #64748b; cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 14px;">
              <label class="p-label">Email Address</label>
              <input type="email" name="email" class="p-input" placeholder="partner@example.com" value="{{ old('email', $partner->email) }}">
            </div>

            @php
              $tradeDomainOptions = isset($activeCategories) && $activeCategories->isNotEmpty()
                ? $activeCategories->pluck('name')->all()
                : [
                    'Electrical & Substation Power',
                    'HVAC & Central Air Conditioning',
                    'Plumbing & Water Boosting Systems',
                    'Fire Safety & NFPA Suppression',
                    'Commercial Janitorial & Sanitization',
                    'Interior Fit-Out & Renovation',
                    'General Facility Maintenance',
                  ];

              $coverageAreaOptions = isset($activeDistricts) && $activeDistricts->isNotEmpty()
                ? $activeDistricts->map(fn($d) => $d->name.($d->division ? " ({$d->division->name})" : ''))->all()
                : [
                    'Dhaka (Dhaka)',
                    'Gazipur (Dhaka)',
                  ];

              $rawOldDomains = old('trade_domain');
              if ($rawOldDomains !== null) {
                $selectedDomains = is_array($rawOldDomains) ? $rawOldDomains : array_map('trim', explode(',', (string) $rawOldDomains));
              } else {
                $selectedDomains = array_map('trim', explode(',', (string) ($partner->trade_domain ?? '')));
              }

              $rawOldAreas = old('coverage_areas');
              if ($rawOldAreas !== null) {
                $selectedAreas = is_array($rawOldAreas) ? $rawOldAreas : array_map('trim', explode(',', (string) $rawOldAreas));
              } else {
                $selectedAreas = array_map('trim', explode(',', (string) ($partner->coverage_areas ?? '')));
              }
            @endphp

            <input type="hidden" name="trade_domain_submitted" value="1">
            <input type="hidden" name="coverage_areas_submitted" value="1">

            <!-- Service Types / Trade Domains -->
            <div style="margin-bottom: 20px;">
              <label class="p-label" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span>Service Types / Primary Domains</span>
                <span style="font-size: 0.76rem; font-weight: 500; color: #64748b;">(Multi-select all that apply)</span>
              </label>
              <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 10px;">
                @foreach($tradeDomainOptions as $domain)
                  @php
                    $isDomainChecked = in_array($domain, $selectedDomains, true)
                      || \Illuminate\Support\Str::contains($partner->trade_domain ?? '', $domain)
                      || \Illuminate\Support\Str::contains($partner->trade_domain ?? '', str_replace([' Power', ' Systems'], '', $domain));
                  @endphp
                  <label style="display: flex; align-items: center; gap: 10px; padding: 11px 14px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;">
                    <input type="checkbox" name="trade_domain[]" value="{{ $domain }}" {{ $isDomainChecked ? 'checked' : '' }} style="width: 17px; height: 17px; accent-color: var(--p-accent); cursor: pointer;">
                    <span style="font-size: 0.86rem; font-weight: 600; color: #1e293b;">{{ $domain }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            @php
              $locationsList = isset($activeDivisions) && $activeDivisions->isNotEmpty()
                ? $activeDivisions
                : \App\Models\Division::whereHas('districts', fn($q) => $q->where('is_active', true))->with(['districts' => fn($q) => $q->where('is_active', true)->with(['thanas' => fn($t) => $t->where('is_active', true)->orderBy('name')])->orderBy('name')])->orderBy('name')->get();
            @endphp

            <!-- Operating Coverage Areas -->
            <div style="margin-bottom: 22px;">
              <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                <label class="p-label" style="margin-bottom: 0; font-size: 0.95rem; font-weight: 700;">
                  Operating Coverage Areas
                </label>
                <span id="selectedAreasCountBadge" style="font-size: 0.78rem; font-weight: 700; color: var(--p-accent); background: #f0fdfa; border: 1px solid #ccfbf1; padding: 2px 8px; border-radius: 9999px;">
                  0 Selected
                </span>
              </div>

              <!-- Selected Areas Tag Container (Above filter) -->
              <div style="margin-bottom: 14px;">
                <div style="font-size: 0.76rem; font-weight: 600; color: #64748b; margin-bottom: 6px;">All Active Selected Operating Areas:</div>
                <div id="selectedAreasTagContainer" style="display: flex; flex-wrap: wrap; gap: 6px; min-height: 28px; padding: 8px 10px; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 8px;">
                  <!-- Dynamically rendered badge pills with × button -->
                </div>
              </div>

              <!-- Division & District Filter Dropdowns -->
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 12px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 14px;">
                <div>
                  <label class="p-label" style="font-size: 0.78rem; color: #64748b; margin-bottom: 4px;">Division Filter</label>
                  <select id="coverageDivisionSelect" class="p-input" style="background: #ffffff; font-weight: 600;" onchange="onCoverageDivisionChange(this.value)">
                    <option value="">-- Select Division --</option>
                    @forelse($locationsList as $div)
                      <option value="{{ $div->id }}">{{ $div->name }}</option>
                    @empty
                      <option value="">No Active Divisions</option>
                    @endforelse
                  </select>
                </div>

                <div>
                  <label class="p-label" style="font-size: 0.78rem; color: #64748b; margin-bottom: 4px;">District Filter</label>
                  <select id="coverageDistrictSelect" class="p-input" style="background: #ffffff; font-weight: 600;" onchange="onCoverageDistrictChange(this.value)" disabled>
                    <option value="">-- Select Division First --</option>
                  </select>
                </div>
              </div>

              <!-- Quick Selection Bar (Collapsed by default until district is selected) -->
              <div id="quickSelectionBar" style="display: none; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; font-size: 0.8rem;">
                <span style="color: #64748b; font-weight: 500;">Multi-select areas for the chosen district:</span>
                <div style="display: flex; gap: 8px;">
                  <button type="button" class="btn-p btn-p-outline" style="padding: 4px 10px; font-size: 0.74rem;" onclick="selectAllDistrictAreas()">Select All in District</button>
                  <button type="button" class="btn-p btn-p-outline" style="padding: 4px 10px; font-size: 0.74rem; color: #ef4444; border-color: #fca5a5;" onclick="deselectAllDistrictAreas()">Clear District</button>
                </div>
              </div>

              <!-- Collapsed Notice when not selected -->
              <div id="districtAreasCollapsedNotice" style="padding: 14px 16px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 10px; font-size: 0.84rem; color: #64748b; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Select a <strong>Division</strong> and <strong>District</strong> above to filter and pick coverage areas.</span>
              </div>

              <!-- Thanas / Areas Checkbox Groups (Rendered per District) -->
              <div id="districtAreasWrapper">
                @forelse($locationsList as $div)
                  @foreach($div->districts as $district)
                    <div class="district-areas-panel" id="districtAreasPanel_{{ $district->id }}" data-division-id="{{ $div->id }}" data-district-id="{{ $district->id }}" style="display: none;">
                      @if($district->thanas->isEmpty())
                        <div style="padding: 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; font-size: 0.85rem; color: #64748b; text-align: center;">
                          No active areas/thanas configured under {{ $district->name }} by admin.
                        </div>
                      @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px;">
                          @foreach($district->thanas as $thana)
                            @php
                              $isThanaChecked = in_array($thana->name, $selectedAreas, true)
                                || in_array($thana->name . ' (' . $district->name . ')', $selectedAreas, true)
                                || \Illuminate\Support\Str::contains($partner->coverage_areas ?? '', $thana->name);
                            @endphp
                            <label class="area-checkbox-card" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.15s ease;">
                              <input type="checkbox" name="coverage_areas[]" value="{{ $thana->name }}" {{ $isThanaChecked ? 'checked' : '' }} class="area-checkbox-item" data-district-id="{{ $district->id }}" data-area-name="{{ $thana->name }}" onchange="updateSelectedAreasSummary()" style="width: 16px; height: 16px; accent-color: var(--p-accent); cursor: pointer;">
                              <span style="font-size: 0.84rem; font-weight: 600; color: #1e293b;">{{ $thana->name }}</span>
                              @if($thana->bn_name)
                                <span style="font-size: 0.72rem; color: #64748b; font-weight: 500;">({{ $thana->bn_name }})</span>
                              @endif
                            </label>
                          @endforeach
                        </div>
                      @endif
                    </div>
                  @endforeach
                @empty
                  <div style="padding: 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; font-size: 0.85rem; color: #64748b; text-align: center;">
                    No active divisions or districts configured in the system.
                  </div>
                @endforelse
              </div>
            </div>

            <div style="border-top: 1px solid var(--p-border); padding-top: 16px; display: flex; justify-content: flex-end; gap: 10px;">
              <button type="button" class="btn-p btn-p-outline" onclick="showCrewProfileDetails()" style="padding: 10px 18px; font-size: 0.88rem; font-weight: 600;">
                Cancel
              </button>
              <button type="button" class="btn-p btn-p-primary" style="padding: 10px 22px; font-size: 0.88rem;" onclick="confirmDialog({ title: 'Save Profile?', text: 'Confirm updating your profile information.', type: 'primary', confirmText: 'Save Changes', formId: 'profileUpdateForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Save Changes</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Tab 2: Security & Password -->
        <div id="tab-security" class="settings-tab-panel">
          <div class="p-card">
            <h3 class="p-card-title" style="margin-bottom: 16px; border-bottom: 1px solid var(--p-border); padding-bottom: 10px;">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
              <span>Security & Password</span>
            </h3>

            <div style="margin-bottom: 14px;">
              <label class="p-label">Current Password</label>
              <input type="password" name="current_password" class="p-input" placeholder="Leave blank to keep unchanged">
            </div>

            <div style="margin-bottom: 14px;">
              <label class="p-label">New Password</label>
              <input type="password" name="new_password" class="p-input" placeholder="Min 6 characters">
            </div>

            <div style="margin-bottom: 20px;">
              <label class="p-label">Confirm New Password</label>
              <input type="password" name="new_password_confirmation" class="p-input" placeholder="Re-type new password">
            </div>

            <div style="border-top: 1px solid var(--p-border); padding-top: 16px; display: flex; justify-content: flex-end;">
              <button type="button" class="btn-p btn-p-primary" style="padding: 10px 22px; font-size: 0.88rem;" onclick="confirmDialog({ title: 'Update Password?', text: 'Confirm updating your account password.', type: 'primary', confirmText: 'Update Password', formId: 'profileUpdateForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Save Changes</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Tab 3: Email Notifications -->
        <div id="tab-notifications" class="settings-tab-panel">
          <div class="p-card">
            <h3 class="p-card-title" style="margin-bottom: 16px; border-bottom: 1px solid var(--p-border); padding-bottom: 10px;">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
              <span>Email Notifications</span>
            </h3>

            @php
              $prefs = $partner->notification_preferences ?? [];
            @endphp

            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
              <label style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; padding: 12px 14px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--p-border);">
                <span style="font-size: 0.86rem; font-weight: 600; color: var(--p-primary);">Job Assignment</span>
                <input type="checkbox" name="notify_job_assigned" value="1" {{ ($prefs['job_assigned'] ?? true) ? 'checked' : '' }} style="accent-color: var(--p-accent); width: 16px; height: 16px;">
              </label>

              <label style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; padding: 12px 14px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--p-border);">
                <span style="font-size: 0.86rem; font-weight: 600; color: var(--p-primary);">Job Schedule Updates</span>
                <input type="checkbox" name="notify_job_updated" value="1" {{ ($prefs['job_updated'] ?? true) ? 'checked' : '' }} style="accent-color: var(--p-accent); width: 16px; height: 16px;">
              </label>

              <label style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; padding: 12px 14px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--p-border);">
                <span style="font-size: 0.86rem; font-weight: 600; color: var(--p-primary);">Payout Settlements</span>
                <input type="checkbox" name="notify_payout_sent" value="1" {{ ($prefs['payout_sent'] ?? true) ? 'checked' : '' }} style="accent-color: var(--p-accent); width: 16px; height: 16px;">
              </label>

              <label style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; padding: 12px 14px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--p-border);">
                <span style="font-size: 0.86rem; font-weight: 600; color: var(--p-primary);">Job Cancellations</span>
                <input type="checkbox" name="notify_job_cancelled" value="1" {{ ($prefs['job_cancelled'] ?? true) ? 'checked' : '' }} style="accent-color: var(--p-accent); width: 16px; height: 16px;">
              </label>
            </div>

            <div style="border-top: 1px solid var(--p-border); padding-top: 16px; display: flex; justify-content: flex-end;">
              <button type="button" class="btn-p btn-p-primary" style="padding: 10px 22px; font-size: 0.88rem;" onclick="confirmDialog({ title: 'Save Notification Preferences?', text: 'Confirm updating your email preferences.', type: 'primary', confirmText: 'Save Changes', formId: 'profileUpdateForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Save Changes</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Tab 4: Payout Method -->
        <div id="tab-payout" class="settings-tab-panel">
          <div class="p-card">
            <h3 class="p-card-title" style="margin-bottom: 16px; border-bottom: 1px solid var(--p-border); padding-bottom: 10px;">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
              <span>Payout Method</span>
            </h3>

            <div style="margin-bottom: 14px;">
              <label class="p-label">Channel</label>
              <select name="payment_method" class="p-input" id="payment_method_select">
                <option value="bank" {{ old('payment_method', $partner->payment_method) === 'bank' ? 'selected' : '' }}>Bank Account</option>
                <option value="bkash" {{ old('payment_method', $partner->payment_method) === 'bkash' ? 'selected' : '' }}>bKash</option>
                <option value="nagad" {{ old('payment_method', $partner->payment_method) === 'nagad' ? 'selected' : '' }}>Nagad</option>
                <option value="rocket" {{ old('payment_method', $partner->payment_method) === 'rocket' ? 'selected' : '' }}>Rocket</option>
              </select>
            </div>

            {{-- Bank Account Fields --}}
            <div id="bank_fields">
              <div style="margin-bottom: 12px;">
                <label class="p-label">Bank Name</label>
                <input type="text" name="bank_name" class="p-input" placeholder="e.g. BRAC Bank PLC" value="{{ old('bank_name', $partner->bank_name) }}">
              </div>
              <div style="margin-bottom: 12px;">
                <label class="p-label">Account Holder Name</label>
                <input type="text" name="account_holder" class="p-input" value="{{ old('account_holder', $partner->account_holder) }}">
              </div>
              <div style="margin-bottom: 12px;">
                <label class="p-label">Account Number</label>
                <input type="text" name="account_number" class="p-input" value="{{ old('account_number', $partner->account_number) }}">
              </div>
              <div style="margin-bottom: 14px;">
                <label class="p-label">Branch Name</label>
                <input type="text" name="branch_name" class="p-input" placeholder="e.g. Gulshan Branch" value="{{ old('branch_name', $partner->branch_name) }}">
              </div>
            </div>

            {{-- MFS Fields --}}
            <div id="mfs_fields" style="display: none;">
              <div style="margin-bottom: 14px;">
                <label class="p-label">Mobile Wallet Number</label>
                <input type="text" name="mfs_number" class="p-input" placeholder="017XXXXXXXX" value="{{ old('mfs_number', $partner->mfs_number) }}">
              </div>
            </div>

            <div style="border-top: 1px solid var(--p-border); padding-top: 16px; display: flex; justify-content: flex-end;">
              <button type="button" class="btn-p btn-p-primary" style="padding: 10px 22px; font-size: 0.88rem;" onclick="confirmDialog({ title: 'Save Payout Method?', text: 'Confirm updating your payout banking/MFS details.', type: 'primary', confirmText: 'Save Changes', formId: 'profileUpdateForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Save Changes</span>
              </button>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>

  <script>
    function isMobileView() {
      return window.innerWidth < 860;
    }

    function previewPartnerAvatar(input) {
      if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById('partnerAvatarImg');
          const initials = document.getElementById('partnerAvatarInitials');
          if (img) {
            img.src = e.target.result;
            img.style.display = 'block';
          }
          if (initials) {
            initials.style.display = 'none';
          }
          const removeFlag = document.getElementById('removeAvatarFlag');
          if (removeFlag) removeFlag.value = '0';
        };
        reader.readAsDataURL(file);
      }
    }

    function triggerRemoveAvatar() {
      const img = document.getElementById('partnerAvatarImg');
      const initials = document.getElementById('partnerAvatarInitials');
      const fileInput = document.getElementById('partnerAvatarInput');
      const removeFlag = document.getElementById('removeAvatarFlag');
      const removeBtn = document.getElementById('removeAvatarBtn');

      if (fileInput) fileInput.value = '';
      if (img) {
        img.src = '';
        img.style.display = 'none';
      }
      if (initials) initials.style.display = 'block';
      if (removeFlag) removeFlag.value = '1';
      if (removeBtn) removeBtn.style.display = 'none';
    }

    function switchSettingsTab(tabId, updateHistory = true) {
      // Toggle nav buttons active state
      document.querySelectorAll('.settings-menu-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.tab === tabId);
      });

      // Toggle tab panels
      document.querySelectorAll('.settings-tab-panel').forEach(panel => {
        panel.classList.toggle('active', panel.id === tabId);
      });

      // On mobile view: hide menu list, show content panel
      if (isMobileView()) {
        const menuEl = document.getElementById('settingsMenu');
        const contentEl = document.getElementById('settingsContentWrap');
        if (menuEl) menuEl.classList.add('mobile-hidden');
        if (contentEl) contentEl.classList.remove('mobile-hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      // Update URL hash
      const hash = tabId.replace('tab-', '');
      if (updateHistory && history.replaceState) {
        history.replaceState(null, null, '#' + hash);
      }
    }

    function backToMobileMenu() {
      if (isMobileView()) {
        const menuEl = document.getElementById('settingsMenu');
        const contentEl = document.getElementById('settingsContentWrap');
        if (menuEl) menuEl.classList.remove('mobile-hidden');
        if (contentEl) contentEl.classList.add('mobile-hidden');
        if (history.replaceState) {
          history.replaceState(null, null, window.location.pathname);
        }
      }
    }

    function togglePaymentFields() {
      const val = document.getElementById('payment_method_select').value;
      const bank = document.getElementById('bank_fields');
      const mfs = document.getElementById('mfs_fields');
      if (val === 'bank') {
        bank.style.display = 'block';
        mfs.style.display = 'none';
      } else {
        bank.style.display = 'none';
        mfs.style.display = 'block';
      }
    }

    document.getElementById('payment_method_select').addEventListener('change', togglePaymentFields);
    togglePaymentFields();

    // Coverage Areas Filtering & Summary Logic
    const activeLocationsData = @json($locationsList);

    function onCoverageDivisionChange(selectedDivId) {
      const distSelect = document.getElementById('coverageDistrictSelect');
      if (!distSelect) return;
      distSelect.innerHTML = '';

      if (!selectedDivId) {
        distSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = '-- Select Division First --';
        distSelect.appendChild(opt);
        onCoverageDistrictChange('');
        return;
      }

      const division = activeLocationsData.find(d => String(d.id) === String(selectedDivId));
      if (!division || !division.districts || division.districts.length === 0) {
        distSelect.disabled = true;
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'No active districts';
        distSelect.appendChild(opt);
        onCoverageDistrictChange('');
        return;
      }

      distSelect.disabled = false;
      const defaultOpt = document.createElement('option');
      defaultOpt.value = '';
      defaultOpt.textContent = '-- Select District --';
      distSelect.appendChild(defaultOpt);

      division.districts.forEach(dist => {
        const opt = document.createElement('option');
        opt.value = dist.id;
        opt.textContent = dist.name;
        distSelect.appendChild(opt);
      });

      onCoverageDistrictChange('');
    }

    function onCoverageDistrictChange(selectedDistId) {
      let hasActivePanel = false;
      document.querySelectorAll('.district-areas-panel').forEach(panel => {
        if (selectedDistId && String(panel.dataset.districtId) === String(selectedDistId)) {
          panel.style.display = 'block';
          hasActivePanel = true;
        } else {
          panel.style.display = 'none';
        }
      });

      const quickBar = document.getElementById('quickSelectionBar');
      if (quickBar) {
        quickBar.style.display = hasActivePanel ? 'flex' : 'none';
      }

      const notice = document.getElementById('districtAreasCollapsedNotice');
      if (notice) {
        notice.style.display = hasActivePanel ? 'none' : 'flex';
      }
    }

    function selectAllDistrictAreas() {
      const currentDistId = document.getElementById('coverageDistrictSelect')?.value;
      const panel = document.getElementById('districtAreasPanel_' + currentDistId);
      if (panel) {
        panel.querySelectorAll('.area-checkbox-item').forEach(cb => cb.checked = true);
        updateSelectedAreasSummary();
      }
    }

    function deselectAllDistrictAreas() {
      const currentDistId = document.getElementById('coverageDistrictSelect')?.value;
      const panel = document.getElementById('districtAreasPanel_' + currentDistId);
      if (panel) {
        panel.querySelectorAll('.area-checkbox-item').forEach(cb => cb.checked = false);
        updateSelectedAreasSummary();
      }
    }

    function updateSelectedAreasSummary() {
      const checkedBoxes = Array.from(document.querySelectorAll('.area-checkbox-item:checked'));
      const countBadge = document.getElementById('selectedAreasCountBadge');
      if (countBadge) {
        countBadge.textContent = checkedBoxes.length + ' Selected';
      }

      const container = document.getElementById('selectedAreasTagContainer');
      if (!container) return;

      if (checkedBoxes.length === 0) {
        container.innerHTML = '<span style="font-size:0.78rem; color:#94a3b8; font-style:italic;">No operating areas selected yet. Please select from the districts above.</span>';
        return;
      }

      container.innerHTML = '';
      checkedBoxes.forEach(cb => {
        const areaName = cb.dataset.areaName || cb.value;
        const pill = document.createElement('span');
        pill.style.cssText = 'display:inline-flex; align-items:center; gap:5px; padding:3px 9px; background:#f0fdfa; border:1px solid #ccfbf1; border-radius:9999px; font-size:0.75rem; font-weight:600; color:var(--p-accent);';
        pill.innerHTML = `<span>${areaName}</span><button type="button" style="background:none; border:none; color:inherit; cursor:pointer; padding:0; line-height:1; font-size:0.85rem;" title="Remove area">&times;</button>`;
        pill.querySelector('button').onclick = function() {
          cb.checked = false;
          updateSelectedAreasSummary();
        };
        container.appendChild(pill);
      });
    }

    function showCrewProfileEdit() {
      const detailsCard = document.getElementById('crewProfileDetailsCard');
      const editCard = document.getElementById('crewProfileEditCard');
      if (detailsCard && editCard) {
        detailsCard.style.display = 'none';
        editCard.style.display = 'block';
        window.location.hash = 'profile-edit';
      }
    }

    function showCrewProfileDetails() {
      const detailsCard = document.getElementById('crewProfileDetailsCard');
      const editCard = document.getElementById('crewProfileEditCard');
      if (detailsCard && editCard) {
        editCard.style.display = 'none';
        detailsCard.style.display = 'block';
        if (window.location.hash === '#profile-edit') {
          history.replaceState(null, null, window.location.pathname);
        }
      }
    }

    // Check URL hash or errors on load
    document.addEventListener('DOMContentLoaded', function() {
      // Keep coverage filter collapsed until user selects division & district
      onCoverageDivisionChange('');
      updateSelectedAreasSummary();
      const hash = window.location.hash.replace('#', '');
      const urlParams = new URLSearchParams(window.location.search);
      const hasErrors = @json($errors->any());
      const hasSuccess = @json(session('success') ? true : false);

      const isProfileEdit = urlParams.get('edit') === '1' || hash === 'profile-edit' || (hasErrors && !@json($errors->has('current_password') || $errors->has('new_password')));
      if (isProfileEdit) {
        showCrewProfileEdit();
      } else {
        showCrewProfileDetails();
      }

      @if($errors->has('current_password') || $errors->has('new_password'))
        switchSettingsTab('tab-security');
      @else
        if (hash === 'banking' || hash === 'payout') {
          switchSettingsTab('tab-payout');
        } else if (hash === 'notifications') {
          switchSettingsTab('tab-notifications');
        } else if (hash === 'security') {
          switchSettingsTab('tab-security');
        } else if (hash === 'profile' || hash === 'profile-edit' || hasSuccess || hasErrors || urlParams.get('edit') === '1') {
          switchSettingsTab('tab-profile');
        } else {
          // On mobile without a target hash, show menu list by default
          if (isMobileView()) {
            document.getElementById('settingsMenu').classList.remove('mobile-hidden');
            document.getElementById('settingsContentWrap').classList.add('mobile-hidden');
          }
        }
      @endif
    });

    window.addEventListener('resize', function() {
      if (!isMobileView()) {
        document.getElementById('settingsMenu')?.classList.remove('mobile-hidden');
        document.getElementById('settingsContentWrap')?.classList.remove('mobile-hidden');
      }
    });
  </script>
</x-partner.layouts.app>
