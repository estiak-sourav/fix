<!-- Email OTP Authentication Modal (Login & Registration) -->
<style>
  #authModal.active {
    display: flex !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    visibility: visible !important;
    z-index: 999999 !important;
  }
  #authModal:not(.active) {
    display: none !important;
    opacity: 0 !important;
    pointer-events: none !important;
    visibility: hidden !important;
  }
  #authModal .modal-card {
    background: #ffffff !important;
    max-width: 440px !important;
    width: 100% !important;
    border-radius: 18px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 25px 50px -12px rgba(15,23,42,0.3) !important;
    overflow: hidden !important;
    position: relative !important;
    font-family: 'Poppins', sans-serif !important;
    transform: none !important;
  }
</style>

<div id="authModal" class="modal-overlay" style="position:fixed; inset:0; background:rgba(15,23,42,0.75); backdrop-filter:blur(5px); z-index:999999; align-items:center; justify-content:center; padding:1.25rem;">
  <div class="modal-card">
    
    <!-- Modal Header -->
    <div style="padding:1.25rem 1.5rem 1rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; background:#fafbfc;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:38px; height:38px; border-radius:10px; background:#eff6ff; display:flex; align-items:center; justify-content:center; color:#2563eb;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
        <div>
          <h3 id="authModalTitle" style="margin:0; font-size:1.1rem; font-weight:700; color:#0f172a;">Customer Access</h3>
          <p id="authModalSubtitle" style="margin:2px 0 0; font-size:0.75rem; color:#64748b;">Email-Based Passwordless OTP Authentication</p>
        </div>
      </div>
      <button type="button" onclick="closeAuthModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8; padding:6px; border-radius:6px;" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>

    <!-- Alert / Status Notice -->
    <div id="authStatusNotice" style="display:none; padding:0.75rem 1.5rem; font-size:0.85rem; font-weight:500; border-bottom:1px solid transparent; transition:all 0.2s;"></div>

    <!-- Step 1: Input Form with Sign In / Register Tabs -->
    <div id="authStepEmail" style="padding:1.5rem;">
      <!-- Tab Switcher -->
      <div style="display:flex; background:#f1f5f9; border-radius:10px; padding:3px; margin-bottom:1.25rem;">
        <button type="button" id="tabBtnLogin" onclick="switchAuthTab('login')" 
          style="flex:1; padding:0.6rem; border:none; border-radius:8px; font-size:0.85rem; font-weight:700; cursor:pointer; transition:all 0.15s; background:#ffffff; color:#0f172a; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
          Sign In
        </button>
        <button type="button" id="tabBtnRegister" onclick="switchAuthTab('register')" 
          style="flex:1; padding:0.6rem; border:none; border-radius:8px; font-size:0.85rem; font-weight:600; cursor:pointer; transition:all 0.15s; background:transparent; color:#64748b;">
          Create Account
        </button>
      </div>

      <!-- Helper description -->
      <p id="authTabDesc" style="font-size:0.85rem; color:#64748b; margin-bottom:1.25rem; line-height:1.45;">
        Enter your registered email address to receive a secure 6-digit verification code.
      </p>

      <form id="requestOtpForm" onsubmit="handleSendOtp(event)">
        <!-- Hidden purpose field -->
        <input type="hidden" id="authPurpose" name="purpose" value="login">

        <!-- Name Field (Register only) -->
        <div id="groupAuthName" style="display:none; margin-bottom:1.1rem;">
          <label for="authName" style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:0.35rem; color:#1e293b;">
            Full Name *
          </label>
          <input type="text" id="authName" name="name" placeholder="e.g. Arif Hossain / ABC Holdings" 
            style="width:100%; box-sizing:border-box; padding:0.7rem 0.9rem; border-radius:8px; border:1px solid #cbd5e1; background:#f8fafc; color:#0f172a; font-size:0.9rem; outline:none; transition:border-color 0.15s;">
        </div>

        <!-- Email Field (Both) -->
        <div style="margin-bottom:1.1rem;">
          <label for="authEmail" style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:0.35rem; color:#1e293b;">
            Email Address *
          </label>
          <input type="email" id="authEmail" name="email" required placeholder="name@company.com" 
            style="width:100%; box-sizing:border-box; padding:0.7rem 0.9rem; border-radius:8px; border:1px solid #cbd5e1; background:#f8fafc; color:#0f172a; font-size:0.9rem; outline:none; transition:border-color 0.15s;">
          <span style="font-size:0.72rem; color:#94a3b8; margin-top:3px; display:block;">Your 6-digit security code will be dispatched to this inbox.</span>
        </div>

        <!-- Phone Field (Register Required - Bangladesh Only) -->
        <div id="groupAuthPhone" style="display:none; margin-bottom:1.25rem;">
          <label for="authPhone" style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:0.35rem; color:#1e293b;">
            Phone Number (Bangladesh Only) *
          </label>
          <div style="display:flex; border:1px solid #cbd5e1; border-radius:8px; overflow:hidden; background:#f8fafc; transition:border-color 0.15s;" id="phoneInputContainer">
            <span style="display:inline-flex; align-items:center; gap:4px; padding:0 12px; background:#f1f5f9; color:#475569; font-size:0.85rem; font-weight:700; border-right:1px solid #cbd5e1; user-select:none;">
              🇧🇩 +880
            </span>
            <input type="tel" id="authPhone" name="phone" placeholder="017XXXXXXXX" maxlength="14"
              style="flex:1; border:none; background:transparent; padding:0.7rem 0.9rem; color:#0f172a; font-size:0.9rem; outline:none;">
          </div>
          <span style="font-size:0.72rem; color:#64748b; margin-top:3px; display:block;">Required for facility emergency contacts (e.g. 017XXXXXXXX).</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="sendOtpBtn" class="btn btn-accent btn-block" style="padding:0.75rem; font-size:0.92rem; font-weight:600; width:100%; display:flex; align-items:center; justify-content:center; gap:8px;">
          <span id="sendOtpBtnText">Send Verification Code</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </button>
      </form>

      <!-- Footer switch links -->
      <div id="authSwitchFooter" style="text-align:center; font-size:0.82rem; color:#64748b; margin-top:1.1rem;">
        <span id="authSwitchText">Don't have an account yet?</span>
        <a href="javascript:void(0)" id="authSwitchAction" onclick="switchAuthTab('register')" style="color:var(--accent, #e11d48); font-weight:700; text-decoration:none; margin-left:4px;">
          Create an Account
        </a>
      </div>
    </div>

    <!-- Step 2: Verify OTP Screen -->
    <div id="authStepOtp" style="display:none; padding:1.5rem;">
      <div style="text-align:center; margin-bottom:1.25rem;">
        <div style="width:48px; height:48px; border-radius:50%; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; margin-bottom:10px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>
        <h4 style="margin:0; font-size:1.1rem; font-weight:700; color:#0f172a;">Enter 6-Digit Code</h4>
        <p style="font-size:0.82rem; color:#64748b; margin:4px 0 0;">
          Code sent to <strong id="sentEmailDisplay" style="color:#0f172a;"></strong>
        </p>
      </div>

      <form id="verifyOtpForm" onsubmit="handleVerifyOtp(event)">
        <div style="margin-bottom:1.25rem;">
          <input type="text" id="authOtpCode" name="otp_code" maxlength="6" required placeholder="••••••" autocomplete="one-time-code" inputmode="numeric"
            style="width:100%; box-sizing:border-box; padding:0.75rem 1rem; letter-spacing:8px; font-size:1.5rem; font-weight:800; text-align:center; border-radius:10px; border:2px solid #cbd5e1; background:#f8fafc; color:#0f172a; outline:none; transition:border-color 0.15s;">
          <span style="font-size:0.72rem; color:#64748b; text-align:center; display:block; margin-top:4px;">⏱️ Code expires in 10 minutes</span>
        </div>

        <button type="submit" id="verifyOtpBtn" class="btn btn-accent btn-block" style="padding:0.75rem; font-size:0.92rem; font-weight:600; width:100%; margin-bottom:0.9rem;">
          Verify & Continue
        </button>

        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.82rem; padding:0 4px;">
          <button type="button" onclick="backToEmailStep()" style="background:none; border:none; color:#64748b; cursor:pointer; font-weight:500;">
            ← Change Email
          </button>
          <button type="button" id="resendOtpBtn" onclick="handleResendOtp()" style="background:none; border:none; color:var(--accent, #e11d48); font-weight:600; cursor:pointer;">
            Resend Code
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
