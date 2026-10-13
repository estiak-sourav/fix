// FIX Facility Management - Passwordless OTP Authentication Handler
let authRedirectUrl = null;

function openAuthModal(returnUrl = null) {
  if (returnUrl) {
    authRedirectUrl = returnUrl;
  }
  const modal = document.getElementById('authModal');
  if (modal) {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    const emailInput = document.getElementById('authEmail');
    if (emailInput && !emailInput.value) {
      setTimeout(() => emailInput.focus(), 150);
    }
  }
}

function closeAuthModal() {
  const modal = document.getElementById('authModal');
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
}

function showAuthNotice(msg, isError = false) {
  const notice = document.getElementById('authStatusNotice');
  if (notice) {
    notice.style.display = 'block';
    notice.style.background = isError ? '#fee2e2' : '#ecfdf5';
    notice.style.color = isError ? '#991b1b' : '#065f46';
    notice.innerHTML = msg;
  }
}

function hideAuthNotice() {
  const notice = document.getElementById('authStatusNotice');
  if (notice) notice.style.display = 'none';
}

function backToEmailStep() {
  hideAuthNotice();
  const stepEmail = document.getElementById('authStepEmail');
  const stepOtp = document.getElementById('authStepOtp');
  if (stepEmail) stepEmail.style.display = 'block';
  if (stepOtp) stepOtp.style.display = 'none';
}

async function handleSendOtp(e) {
  if (e && e.preventDefault) e.preventDefault();
  const emailInput = document.getElementById('authEmail');
  const nameInput = document.getElementById('authName');
  const btn = document.getElementById('sendOtpBtn');
  const email = emailInput ? emailInput.value.trim() : '';
  const name = nameInput ? nameInput.value.trim() : '';

  if (!email) {
    showAuthNotice('Please enter a valid email address.', true);
    return;
  }

  if (btn) {
    btn.disabled = true;
    btn.innerText = 'Sending OTP Code...';
  }
  hideAuthNotice();

  try {
    const res = await fetch('/auth/send-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email, name })
    });

    const data = await res.json();

    if (res.ok && data.success) {
      const sentDisplay = document.getElementById('sentEmailDisplay');
      if (sentDisplay) sentDisplay.innerText = email;

      const stepEmail = document.getElementById('authStepEmail');
      const stepOtp = document.getElementById('authStepOtp');
      if (stepEmail) stepEmail.style.display = 'none';
      if (stepOtp) stepOtp.style.display = 'block';

      let msg = data.message || 'Verification code sent successfully!';
      if (data.debug_code) {
        msg += `<div style="margin-top:8px; padding:8px 12px; background:#fef3c7; border:1px solid #fde68a; color:#92400e; border-radius:6px; font-weight:700; text-align:center;">Local Dev OTP: <span style="font-size:1.25rem; letter-spacing:4px; font-family:monospace; color:#b45309;">${data.debug_code}</span></div>`;
        const otpInput = document.getElementById('authOtpCode');
        if (otpInput) otpInput.value = data.debug_code;
      }
      showAuthNotice(msg, false);

      setTimeout(() => document.getElementById('authOtpCode')?.focus(), 200);
    } else {
      showAuthNotice(data.message || 'Unable to send OTP. Please try again.', true);
    }
  } catch (err) {
    showAuthNotice('Network connection error. Please try again.', true);
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = 'Send Verification OTP <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
    }
  }
}

async function handleVerifyOtp(e) {
  if (e && e.preventDefault) e.preventDefault();
  const emailInput = document.getElementById('authEmail');
  const otpInput = document.getElementById('authOtpCode');
  const email = emailInput ? emailInput.value.trim() : '';
  const otpCode = otpInput ? otpInput.value.trim() : '';
  const btn = document.getElementById('verifyOtpBtn');

  if (!otpCode || otpCode.length < 4) {
    showAuthNotice('Please enter the full 6-digit verification code.', true);
    return;
  }

  if (btn) {
    btn.disabled = true;
    btn.innerText = 'Verifying Code...';
  }
  hideAuthNotice();

  try {
    const res = await fetch('/auth/verify-otp', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email, otp_code: otpCode })
    });

    const data = await res.json();

    if (res.ok && data.success) {
      showAuthNotice('Verification successful! Logging in...', false);
      fixToast('Logged in successfully! Welcome to FIX.', 'success');

      setTimeout(() => {
        if (authRedirectUrl) {
          window.location.href = authRedirectUrl;
        } else if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          window.location.reload();
        }
      }, 600);
    } else {
      showAuthNotice(data.message || 'Invalid or expired OTP code. Please check and retry.', true);
      if (btn) {
        btn.disabled = false;
        btn.innerText = 'Verify & Continue';
      }
    }
  } catch (err) {
    showAuthNotice('Verification error. Please retry.', true);
    if (btn) {
      btn.disabled = false;
      btn.innerText = 'Verify & Continue';
    }
  }
}

function handleResendOtp() {
  const fakeEvent = { preventDefault: () => {} };
  handleSendOtp(fakeEvent);
}

// Global Toast Notification Helper
function fixToast(message, type = 'info') {
  const container = document.getElementById('fixToastContainer');
  if (!container) return;

  const toast = document.createElement('div');
  const bg = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#1e3a8a');

  toast.style.cssText = `
    background: ${bg};
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2);
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.3s ease-out;
  `;

  toast.innerHTML = `<span>${message}</span>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

// Document Ready Setup & Event Delegation
document.addEventListener('DOMContentLoaded', () => {
  // Delegate all auth modal triggers
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-auth-modal-trigger], .btn-open-auth-modal');
    if (trigger) {
      e.preventDefault();
      openAuthModal();
      return;
    }

    // Close on backdrop click
    const modal = document.getElementById('authModal');
    if (modal && e.target === modal) {
      closeAuthModal();
    }
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAuthModal();
    }
  });
});
