<x-layouts.web :title="($title ?? 'Crew & Partner Policies') . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Services')">
  @php
    $partnerTerms = \App\Models\SiteContent::forSection('partner_terms');
    $partnerWorkPolicy = \App\Models\SiteContent::forSection('partner_work_policy');
    $partnerPrivacy = \App\Models\SiteContent::forSection('partner_privacy');
    $partnerPayoutPolicy = \App\Models\SiteContent::forSection('partner_payout_policy');
    $defaultTab = $activeTab ?? 'terms';
  @endphp

  <x-page-hero 
    :title="$title ?? 'Crew & Partner Operating Policies'"
    :subtitle="'Official operational standards, safety compliance (BNBC/OSHA), customer privacy, and earnings settlement guidelines for FIX registered contractors.'"
    maxWidth="960px"
  />

  <section class="section" style="padding: 40px 0 80px; background: #f8fafc;">
    <div class="container" style="max-width: 960px;">
      <!-- Policy Sub-Nav Tabs -->
      <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 10px; margin-bottom: 28px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="#partner_terms" onclick="showPolicySection('terms', this); return false;" class="policy-nav-tab {{ $defaultTab === 'terms' ? 'active' : '' }}" style="flex: 1; min-width: 180px; padding: 10px 14px; text-align: center; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
          📜 Terms of Service
        </a>
        <a href="#partner_work_policy" onclick="showPolicySection('work', this); return false;" class="policy-nav-tab {{ $defaultTab === 'work' ? 'active' : '' }}" style="flex: 1; min-width: 180px; padding: 10px 14px; text-align: center; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
          🦺 Work Policy &amp; Safety
        </a>
        <a href="#partner_privacy" onclick="showPolicySection('privacy', this); return false;" class="policy-nav-tab {{ $defaultTab === 'privacy' ? 'active' : '' }}" style="flex: 1; min-width: 180px; padding: 10px 14px; text-align: center; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
          🔒 Privacy &amp; Data
        </a>
        <a href="#partner_payout_policy" onclick="showPolicySection('payout', this); return false;" class="policy-nav-tab {{ $defaultTab === 'payout' ? 'active' : '' }}" style="flex: 1; min-width: 180px; padding: 10px 14px; text-align: center; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
          💳 Payout &amp; Settlement
        </a>
      </div>

      <style>
        .policy-nav-tab {
          color: #475569;
          background: #f1f5f9;
        }
        .policy-nav-tab.active {
          background: #0284c7;
          color: #ffffff !important;
          box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        }
        .policy-clause-box {
          background: #ffffff;
          border: 1px solid var(--border);
          border-radius: 12px;
          padding: 22px 26px;
          margin-bottom: 16px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.02);
          transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .policy-clause-box:hover {
          transform: translateY(-1px);
          box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }
      </style>

      <!-- Section 1: Terms of Service -->
      <div id="section-terms" class="policy-content-pane" style="{{ $defaultTab === 'terms' ? 'display:block;' : 'display:none;' }}">
        <div id="partner_terms" style="position: relative; top: -80px;"></div>
        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 30px; margin-bottom: 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.02);">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $partnerTerms?->title ?? 'Crew & Partner Terms of Service' }}</h2>
            <span class="badge" style="font-size: 0.75rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 4px 10px; border-radius: 9999px; white-space: nowrap !important; flex-shrink: 0 !important;">{{ $partnerTerms?->badge ?? 'CONTRACT' }}</span>
          </div>
          <p style="font-size: 0.95rem; color: #64748b; margin: 0; line-height: 1.6;">{{ $partnerTerms?->subtitle ?? 'Operational obligations, dispatch acceptance standards, technician obligations, and platform partnership agreements.' }}</p>
        </div>

        @foreach($partnerTerms?->meta_data['sections'] ?? [] as $sec)
          <div class="policy-clause-box">
            <h3 style="font-size: 1.12rem; font-weight: 700; color: #0f172a; margin: 0 0 10px;">{{ $sec['heading'] ?? '' }}</h3>
            <p style="font-size: 0.9rem; color: #334155; margin: 0; line-height: 1.7;">{!! nl2br(e($sec['body'] ?? '')) !!}</p>
          </div>
        @endforeach
      </div>

      <!-- Section 2: Work Policy & Safety SOP -->
      <div id="section-work" class="policy-content-pane" style="{{ $defaultTab === 'work' ? 'display:block;' : 'display:none;' }}">
        <div id="partner_work_policy" style="position: relative; top: -80px;"></div>
        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 30px; margin-bottom: 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.02);">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $partnerWorkPolicy?->title ?? 'Crew Work Policy & Safety SOP' }}</h2>
            <span class="badge" style="font-size: 0.75rem; font-weight: 800; color: #16a34a; background: #dcfce7; padding: 4px 10px; border-radius: 9999px; white-space: nowrap !important; flex-shrink: 0 !important;">{{ $partnerWorkPolicy?->badge ?? 'SAFETY SOP' }}</span>
          </div>
          <p style="font-size: 0.95rem; color: #64748b; margin: 0; line-height: 1.6;">{{ $partnerWorkPolicy?->subtitle ?? 'Standard operating procedures, BNBC electrical and HVAC safety compliance, customer etiquette, and site conduct.' }}</p>
        </div>

        @foreach($partnerWorkPolicy?->meta_data['sections'] ?? [] as $sec)
          <div class="policy-clause-box">
            <h3 style="font-size: 1.12rem; font-weight: 700; color: #0f172a; margin: 0 0 10px;">{{ $sec['heading'] ?? '' }}</h3>
            <p style="font-size: 0.9rem; color: #334155; margin: 0; line-height: 1.7;">{!! nl2br(e($sec['body'] ?? '')) !!}</p>
          </div>
        @endforeach
      </div>

      <!-- Section 3: Privacy & Data Protection -->
      <div id="section-privacy" class="policy-content-pane" style="{{ $defaultTab === 'privacy' ? 'display:block;' : 'display:none;' }}">
        <div id="partner_privacy" style="position: relative; top: -80px;"></div>
        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 30px; margin-bottom: 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.02);">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $partnerPrivacy?->title ?? 'Crew Privacy & Data Protection Policy' }}</h2>
            <span class="badge" style="font-size: 0.75rem; font-weight: 800; color: #7c3aed; background: #f3e8ff; padding: 4px 10px; border-radius: 9999px; white-space: nowrap !important; flex-shrink: 0 !important;">{{ $partnerPrivacy?->badge ?? 'PRIVACY & SECURITY' }}</span>
          </div>
          <p style="font-size: 0.95rem; color: #64748b; margin: 0; line-height: 1.6;">{{ $partnerPrivacy?->subtitle ?? 'Guidelines governing technician personal data, dispatch location telemetry, and strict client confidentiality standards.' }}</p>
        </div>

        @foreach($partnerPrivacy?->meta_data['sections'] ?? [] as $sec)
          <div class="policy-clause-box">
            <h3 style="font-size: 1.12rem; font-weight: 700; color: #0f172a; margin: 0 0 10px;">{{ $sec['heading'] ?? '' }}</h3>
            <p style="font-size: 0.9rem; color: #334155; margin: 0; line-height: 1.7;">{!! nl2br(e($sec['body'] ?? '')) !!}</p>
          </div>
        @endforeach
      </div>

      <!-- Section 4: Payout & Settlement -->
      <div id="section-payout" class="policy-content-pane" style="{{ $defaultTab === 'payout' ? 'display:block;' : 'display:none;' }}">
        <div id="partner_payout_policy" style="position: relative; top: -80px;"></div>
        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 30px; margin-bottom: 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.02);">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $partnerPayoutPolicy?->title ?? 'Crew Payout & Earnings Settlement Policy' }}</h2>
            <span class="badge" style="font-size: 0.75rem; font-weight: 800; color: #d97706; background: #fef3c7; padding: 4px 10px; border-radius: 9999px; white-space: nowrap !important; flex-shrink: 0 !important;">{{ $partnerPayoutPolicy?->badge ?? 'PAYOUT & EARNINGS' }}</span>
          </div>
          <p style="font-size: 0.95rem; color: #64748b; margin: 0; line-height: 1.6;">{{ $partnerPayoutPolicy?->subtitle ?? 'Rules governing job earnings, commission splits, payout withdrawal thresholds, processing turnaround, and warranty reserves.' }}</p>
        </div>

        @foreach($partnerPayoutPolicy?->meta_data['sections'] ?? [] as $sec)
          <div class="policy-clause-box">
            <h3 style="font-size: 1.12rem; font-weight: 700; color: #0f172a; margin: 0 0 10px;">{{ $sec['heading'] ?? '' }}</h3>
            <p style="font-size: 0.9rem; color: #334155; margin: 0; line-height: 1.7;">{!! nl2br(e($sec['body'] ?? '')) !!}</p>
          </div>
        @endforeach
      </div>

      <!-- Compliance Call to Action -->
      <div style="margin-top: 32px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 14px; padding: 32px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
        <div>
          <h4 style="font-size: 1.25rem; font-weight: 800; margin: 0 0 6px; color: #ffffff;">Ready to Partner with FIX?</h4>
          <p style="font-size: 0.88rem; color: #94a3b8; margin: 0; max-width: 540px;">
            Join Bangladesh's premier network of verified facility maintenance technicians, electricians, HVAC engineers, and certified contractors.
          </p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
          <a href="{{ url('/vendor') }}" class="btn" style="background: #0284c7; color: #ffffff; font-weight: 700; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
            Apply as Partner
          </a>
          <a href="{{ route('partner.login') }}" class="btn" style="background: rgba(255,255,255,0.1); color: #ffffff; font-weight: 700; padding: 10px 20px; border-radius: 8px; text-decoration: none; border: 1px solid rgba(255,255,255,0.2);">
            Partner Portal Login
          </a>
        </div>
      </div>
    </div>
  </section>

  <script>
    function showPolicySection(tabKey, element) {
      document.querySelectorAll('.policy-nav-tab').forEach(tab => tab.classList.remove('active'));
      if (element) element.classList.add('active');

      document.querySelectorAll('.policy-content-pane').forEach(pane => {
        pane.style.display = (pane.id === 'section-' + tabKey) ? 'block' : 'none';
      });

      if (history.replaceState) {
        history.replaceState(null, null, '#partner_' + (tabKey === 'work' ? 'work_policy' : (tabKey === 'privacy' ? 'privacy' : (tabKey === 'payout' ? 'payout_policy' : 'terms'))));
      }
    }

    // Auto-detect hash on load
    document.addEventListener('DOMContentLoaded', function() {
      const hash = window.location.hash.toLowerCase();
      if (hash.includes('work')) {
        const tab = document.querySelector('a[href="#partner_work_policy"]');
        showPolicySection('work', tab);
      } else if (hash.includes('priv')) {
        const tab = document.querySelector('a[href="#partner_privacy"]');
        showPolicySection('privacy', tab);
      } else if (hash.includes('pay')) {
        const tab = document.querySelector('a[href="#partner_payout_policy"]');
        showPolicySection('payout', tab);
      } else if (hash.includes('term')) {
        const tab = document.querySelector('a[href="#partner_terms"]');
        showPolicySection('terms', tab);
      }
    });
  </script>
</x-layouts.web>
