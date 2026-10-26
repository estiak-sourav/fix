// FIX Facility Management - Passwordless OTP Email Authentication & Registration Handler
let authRedirectUrl = null;
let resendTimerInterval = null;
let resendSecondsRemaining = 0;

function switchAuthTab(tab) {
  const tabLogin = document.getElementById('tabBtnLogin');
  const tabRegister = document.getElementById('tabBtnRegister');
  const purposeInput = document.getElementById('authPurpose');
  const groupName = document.getElementById('groupAuthName');
  const groupPhone = document.getElementById('groupAuthPhone');
  const nameInput = document.getElementById('authName');
  const phoneInput = document.getElementById('authPhone');
  const tabDesc = document.getElementById('authTabDesc');
  const btnText = document.getElementById('sendOtpBtnText');
  const switchText = document.getElementById('authSwitchText');
  const switchAction = document.getElementById('authSwitchAction');
  const modalTitle = document.getElementById('authModalTitle');

  hideAuthNotice();

  if (tab === 'register') {
    if (purposeInput) purposeInput.value = 'register';
    if (tabLogin) {
      tabLogin.style.background = 'transparent';
      tabLogin.style.color = '#64748b';
      tabLogin.style.boxShadow = 'none';
      tabLogin.style.fontWeight = '500';
    }
    if (tabRegister) {
      tabRegister.style.background = '#ffffff';
      tabRegister.style.color = '#0f172a';
      tabRegister.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
      tabRegister.style.fontWeight = '700';
    }
    if (groupName) groupName.style.display = 'block';
    if (groupPhone) groupPhone.style.display = 'block';
    if (nameInput) nameInput.required = true;
    if (phoneInput) phoneInput.required = true;

    if (modalTitle) modalTitle.innerText = 'Create Account';
    if (tabDesc) tabDesc.innerText = 'Register with your email and Bangladesh phone number. We will send a secure verification code.';
    if (btnText) btnText.innerText = 'Send Registration OTP';
    if (switchText) switchText.innerText = 'Already have an account?';
    if (switchAction) {
      switchAction.innerText = 'Sign In';
      switchAction.setAttribute('onclick', "switchAuthTab('login')");
    }

    setTimeout(() => {
      if (nameInput && !nameInput.value) nameInput.focus();
    }, 100);
  } else {
    if (purposeInput) purposeInput.value = 'login';
    if (tabLogin) {
      tabLogin.style.background = '#ffffff';
      tabLogin.style.color = '#0f172a';
      tabLogin.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
      tabLogin.style.fontWeight = '700';
    }
    if (tabRegister) {
      tabRegister.style.background = 'transparent';
      tabRegister.style.color = '#64748b';
      tabRegister.style.boxShadow = 'none';
      tabRegister.style.fontWeight = '500';
    }
    if (groupName) groupName.style.display = 'none';
    if (groupPhone) groupPhone.style.display = 'none';
    if (nameInput) nameInput.required = false;
    if (phoneInput) phoneInput.required = false;

    if (modalTitle) modalTitle.innerText = 'Customer Access';
    if (tabDesc) tabDesc.innerText = 'Enter your registered email address to receive a secure 6-digit verification code.';
    if (btnText) btnText.innerText = 'Send Login Code';
    if (switchText) switchText.innerText = "Don't have an account yet?";
    if (switchAction) {
      switchAction.innerText = 'Create an Account';
      switchAction.setAttribute('onclick', "switchAuthTab('register')");
    }

    setTimeout(() => {
      const emailInput = document.getElementById('authEmail');
      if (emailInput && !emailInput.value) emailInput.focus();
    }, 100);
  }
}

function openAuthModal(returnUrl = null, defaultTab = 'login') {
  if (returnUrl) {
    authRedirectUrl = returnUrl;
  }
  const modal = document.getElementById('authModal');
  if (modal) {
    modal.classList.add('active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    backToEmailStep();
    switchAuthTab(defaultTab);
  }
}

function closeAuthModal() {
  const modal = document.getElementById('authModal');
  if (modal) {
    modal.classList.remove('active');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    clearInterval(resendTimerInterval);
  }
}

function showAuthNotice(msg, isError = false) {
  const notice = document.getElementById('authStatusNotice');
  if (notice) {
    notice.style.display = 'block';
    notice.style.background = isError ? '#fef2f2' : '#f0fdf4';
    notice.style.borderBottomColor = isError ? '#fecaca' : '#bbf7d0';
    notice.style.color = isError ? '#dc2626' : '#16a34a';
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
  clearInterval(resendTimerInterval);
}

// Bangladesh mobile number validation (013-019, 11 digits or +8801...)
function isValidBangladeshPhone(phone) {
  if (!phone) return false;
  const clean = phone.replace(/[\s\-\(\)\.]+/g, '');
  return /^(?:\+?880|880|0)?1[3-9]\d{8}$/.test(clean);
}

async function handleSendOtp(e) {
  if (e && e.preventDefault) e.preventDefault();
  const purpose = document.getElementById('authPurpose')?.value || 'login';
  const emailInput = document.getElementById('authEmail');
  const nameInput = document.getElementById('authName');
  const phoneInput = document.getElementById('authPhone');
  const btn = document.getElementById('sendOtpBtn');

  const email = emailInput ? emailInput.value.trim().toLowerCase() : '';
  const name = nameInput ? nameInput.value.trim() : '';
  const phone = phoneInput ? phoneInput.value.trim() : '';

  if (!email) {
    showAuthNotice('Please enter a valid email address.', true);
    emailInput?.focus();
    return;
  }

  if (purpose === 'register') {
    if (!name) {
      showAuthNotice('Full Name is required for registration.', true);
      nameInput?.focus();
      return;
    }
    if (!phone) {
      showAuthNotice('A Bangladesh phone number is required for registration.', true);
      phoneInput?.focus();
      return;
    }
    if (!isValidBangladeshPhone(phone)) {
      showAuthNotice('Please enter a valid 11-digit Bangladesh mobile number (e.g. 017XXXXXXXX).', true);
      phoneInput?.focus();
      return;
    }
  }

  if (btn) {
    btn.disabled = true;
    btn.innerHTML = 'Sending Code... <span style="display:inline-block; animation:spin 1s linear infinite;">⏳</span>';
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
      body: JSON.stringify({ email, name, phone, purpose })
    });

    const data = await res.json();

    if (res.ok && data.success) {
      const sentDisplay = document.getElementById('sentEmailDisplay');
      if (sentDisplay) sentDisplay.innerText = email;

      const stepEmail = document.getElementById('authStepEmail');
      const stepOtp = document.getElementById('authStepOtp');
      if (stepEmail) stepEmail.style.display = 'none';
      if (stepOtp) stepOtp.style.display = 'block';

      const msg = data.message || 'Verification code sent successfully!';
      showAuthNotice(msg, false);

      startResendCountdown(60);

      setTimeout(() => document.getElementById('authOtpCode')?.focus(), 250);
    } else {
      let errorMsg = data.message || 'Unable to send OTP. Please check your details.';
      if (data.errors) {
        const firstKey = Object.keys(data.errors)[0];
        if (data.errors[firstKey] && data.errors[firstKey][0]) {
          errorMsg = data.errors[firstKey][0];
        }
      }

      showAuthNotice(errorMsg, true);

      // Auto suggestion if email not registered during login
      if (purpose === 'login' && errorMsg.toLowerCase().includes('no account found')) {
        setTimeout(() => {
          switchAuthTab('register');
          showAuthNotice('No account found with this email. We have prepared the registration form for you below.', false);
        }, 1500);
      }
    }
  } catch (err) {
    showAuthNotice('Network connection error. Please try again.', true);
  } finally {
    if (btn) {
      btn.disabled = false;
      const btnText = document.getElementById('sendOtpBtnText');
      btn.innerHTML = `<span id="sendOtpBtnText">${btnText ? btnText.innerText : 'Send Verification Code'}</span> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>`;
    }
  }
}

async function handleVerifyOtp(e) {
  if (e && e.preventDefault) e.preventDefault();
  const emailInput = document.getElementById('authEmail');
  const otpInput = document.getElementById('authOtpCode');
  const email = emailInput ? emailInput.value.trim().toLowerCase() : '';
  const otpCode = otpInput ? otpInput.value.trim() : '';
  const btn = document.getElementById('verifyOtpBtn');

  if (!otpCode || otpCode.length < 4) {
    showAuthNotice('Please enter the full 6-digit verification code.', true);
    otpInput?.focus();
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
      showAuthNotice('Authentication successful! Establishing secure session...', false);
      fixToast('Welcome to FIX Facility Management!', 'success');

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
      let errorMsg = data.message || 'Invalid or expired OTP code. Please try again.';
      if (data.errors && data.errors.otp_code) {
        errorMsg = data.errors.otp_code[0];
      }
      showAuthNotice(errorMsg, true);
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
  if (resendSecondsRemaining > 0) return;
  const fakeEvent = { preventDefault: () => {} };
  handleSendOtp(fakeEvent);
}

function startResendCountdown(seconds = 60) {
  clearInterval(resendTimerInterval);
  resendSecondsRemaining = seconds;
  const resendBtn = document.getElementById('resendOtpBtn');

  if (!resendBtn) return;
  resendBtn.style.pointerEvents = 'none';
  resendBtn.style.opacity = '0.6';

  const updateText = () => {
    if (resendSecondsRemaining <= 0) {
      clearInterval(resendTimerInterval);
      resendBtn.innerText = 'Resend Code';
      resendBtn.style.pointerEvents = 'auto';
      resendBtn.style.opacity = '1';
    } else {
      resendBtn.innerText = `Resend in ${resendSecondsRemaining}s`;
      resendSecondsRemaining--;
    }
  };

  updateText();
  resendTimerInterval = setInterval(updateText, 1000);
}

// Global Toast Notification Helper
function fixToast(message, type = 'info') {
  let container = document.getElementById('fixToastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'fixToastContainer';
    container.style.cssText = 'position:fixed; bottom:24px; right:24px; z-index:999999; display:flex; flex-direction:column; gap:8px;';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  const bg = type === 'success' ? '#16a34a' : (type === 'error' ? '#dc2626' : '#1e3a8a');

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
    font-family: 'Poppins', sans-serif;
  `;

  toast.innerHTML = `<span>${message}</span>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

// Explicitly bind functions to global window object
window.openAuthModal = openAuthModal;
window.closeAuthModal = closeAuthModal;
window.switchAuthTab = switchAuthTab;
window.handleSendOtp = handleSendOtp;
window.handleVerifyOtp = handleVerifyOtp;
window.handleResendOtp = handleResendOtp;
window.backToEmailStep = backToEmailStep;
window.fixToast = fixToast;

// Document Ready Setup & Event Delegation
document.addEventListener('DOMContentLoaded', () => {
  // Delegate all auth modal triggers
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-auth-modal-trigger], .btn-open-auth-modal');
    if (trigger) {
      e.preventDefault();
      const tab = trigger.getAttribute('data-tab') || 'login';
      openAuthModal(null, tab);
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
