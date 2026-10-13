<!-- Email OTP Authentication Modal -->
<div id="authModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.75); backdrop-filter:blur(4px); z-index:10000; align-items:center; justify-content:center; padding:1.5rem;">
  <div class="modal-card" style="background:var(--bg-card); max-width:440px; width:100%; border-radius:16px; border:1px solid var(--border); box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden; position:relative;">
    
    <!-- Modal Header -->
    <div style="padding:1.5rem 1.75rem 1rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:10px;">
        <div style="width:38px; height:38px; border-radius:10px; background:rgba(30,58,138,0.08); display:flex; align-items:center; justify-content:center; color:var(--primary);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
        <div>
          <h3 style="margin:0; font-size:1.15rem; font-weight:700; color:var(--text-main);">Customer Access</h3>
          <p style="margin:0; font-size:0.8rem; color:var(--text-muted);">Passwordless OTP Login & Registration</p>
        </div>
      </div>
      <button type="button" onclick="closeAuthModal()" style="background:none; border:none; cursor:pointer; color:var(--text-muted); padding:4px;" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>

    <!-- Alert / Status message -->
    <div id="authStatusNotice" style="display:none; padding:0.75rem 1.75rem; font-size:0.85rem; font-weight:500;"></div>

    <!-- Step 1: Request OTP -->
    <div id="authStepEmail" style="padding:1.75rem;">
      <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:1.25rem;">
        To book services or view active requests, please enter your email address. We will send a secure verification code.
      </p>

      <form id="requestOtpForm" onsubmit="handleSendOtp(event)">
        <div style="margin-bottom:1.25rem;">
          <label for="authEmail" style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem; color:var(--text-main);">Email Address *</label>
          <input type="email" id="authEmail" name="email" required placeholder="name@company.com" 
            style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-page); color:var(--text-main); font-size:0.95rem; outline:none;">
        </div>

        <div style="margin-bottom:1.25rem;">
          <label for="authName" style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem; color:var(--text-main);">Full Name / Organization (Optional)</label>
          <input type="text" id="authName" name="name" placeholder="Your name or company" 
            style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-page); color:var(--text-main); font-size:0.95rem; outline:none;">
        </div>

        <button type="submit" id="sendOtpBtn" class="btn btn-accent btn-block" style="padding:0.8rem; font-size:0.95rem; font-weight:600; width:100%;">
          Send Verification OTP
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </button>
      </form>
    </div>

    <!-- Step 2: Verify OTP -->
    <div id="authStepOtp" style="display:none; padding:1.75rem;">
      <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:1.25rem;">
        A 6-digit verification code has been sent to <strong id="sentEmailDisplay" style="color:var(--text-main);"></strong>.
      </p>

      <form id="verifyOtpForm" onsubmit="handleVerifyOtp(event)">
        <div style="margin-bottom:1.25rem;">
          <label for="authOtpCode" style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem; color:var(--text-main);">6-Digit OTP Code *</label>
          <input type="text" id="authOtpCode" name="otp_code" maxlength="6" required placeholder="123456" 
            style="width:100%; padding:0.75rem 1rem; letter-spacing:6px; font-size:1.35rem; font-weight:700; text-align:center; border-radius:8px; border:1px solid var(--border); background:var(--bg-page); color:var(--text-main); outline:none;">
        </div>

        <button type="submit" id="verifyOtpBtn" class="btn btn-accent btn-block" style="padding:0.8rem; font-size:0.95rem; font-weight:600; width:100%; margin-bottom:0.75rem;">
          Verify & Continue
        </button>

        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem;">
          <button type="button" onclick="backToEmailStep()" style="background:none; border:none; color:var(--text-muted); cursor:pointer;">
            ← Change Email
          </button>
          <button type="button" id="resendOtpBtn" onclick="handleResendOtp()" style="background:none; border:none; color:var(--accent); font-weight:600; cursor:pointer;">
            Resend OTP
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
