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

      <form id="profileUpdateForm" action="{{ route('partner.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tab 1: Account Profile -->
        <div id="tab-profile" class="settings-tab-panel active">
          <div class="p-card">
            <h3 class="p-card-title" style="margin-bottom: 16px; border-bottom: 1px solid var(--p-border); padding-bottom: 10px;">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              <span>Account Profile</span>
            </h3>

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

            <div style="margin-bottom: 20px;">
              <label class="p-label">Coverage Areas</label>
              <textarea name="coverage_areas" rows="2" class="p-input" style="height: auto; resize: vertical;" placeholder="e.g. Gulshan, Banani, Uttara">{{ old('coverage_areas', $partner->coverage_areas) }}</textarea>
            </div>

            <div style="border-top: 1px solid var(--p-border); padding-top: 16px; display: flex; justify-content: flex-end;">
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

    // Check URL hash or errors on load
    document.addEventListener('DOMContentLoaded', function() {
      const hash = window.location.hash.replace('#', '');
      const hasErrors = @json($errors->any());
      const hasSuccess = @json(session('success') ? true : false);

      @if($errors->has('current_password') || $errors->has('new_password'))
        switchSettingsTab('tab-security');
      @else
        if (hash === 'banking' || hash === 'payout') {
          switchSettingsTab('tab-payout');
        } else if (hash === 'notifications') {
          switchSettingsTab('tab-notifications');
        } else if (hash === 'security') {
          switchSettingsTab('tab-security');
        } else if (hash === 'profile' || hasSuccess || hasErrors) {
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
