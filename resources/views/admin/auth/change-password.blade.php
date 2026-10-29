<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Password | FIX Admin</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}?v=white">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=white">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    *, body, button, input, select, textarea {
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
    }
    
    h1, h2, h3, h4, h5, h6, b, strong, .brand-header h2, .status-pill, .form-label, .btn-submit, .badge-req {
      font-weight: 500;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      background: 
        linear-gradient(135deg, rgba(15, 23, 42, 0.90) 0%, rgba(30, 41, 59, 0.85) 50%, rgba(15, 23, 42, 0.96) 100%),
        url("{{ asset('assets/images/admin-login-bg.jpg') }}");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      overflow-x: hidden;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 50% 30%, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
      pointer-events: none;
    }

    .container-box {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 480px;
    }

    .card-box {
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.8);
      border-radius: 20px;
      padding: 2.5rem 2.25rem 2rem;
      box-shadow: 
        0 25px 60px -15px rgba(0, 0, 0, 0.45),
        0 0 0 1px rgba(255, 255, 255, 0.3),
        0 8px 20px -6px rgba(0, 0, 0, 0.15);
      animation: fadeInScale 0.4s ease-out;
    }

    @keyframes fadeInScale {
      from { opacity: 0; transform: translateY(12px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .brand-header {
      text-align: center;
      margin-bottom: 1.75rem;
    }

    .brand-logo-wrap {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #ffffff;
      padding: 10px 20px;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
      border: 1px solid #eaedf1;
      margin-bottom: 1rem;
    }

    .brand-logo-wrap img {
      height: 38px;
      width: auto;
      object-fit: contain;
    }

    .brand-header h2 {
      font-size: 1.4rem;
      font-weight: 500;
      color: #0f172a;
      letter-spacing: -0.02em;
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 6px;
      padding: 4px 12px;
      border-radius: 20px;
      background: #fef3c7;
      border: 1px solid #fde68a;
      font-size: 0.75rem;
      font-weight: 500;
      color: #b45309;
    }

    .notice-banner {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      font-size: 0.83rem;
      color: #1e40af;
      line-height: 1.5;
      display: flex;
      gap: 10px;
    }

    .notice-banner svg {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
      color: #2563eb;
      margin-top: 2px;
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-label {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.82rem;
      font-weight: 500;
      margin-bottom: 6px;
      color: #1e293b;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      color: #94a3b8;
      pointer-events: none;
      display: flex;
      align-items: center;
    }

    .input-icon svg {
      width: 18px;
      height: 18px;
    }

    .form-control {
      width: 100%;
      background: #ffffff;
      border: 1px solid #eaedf1;
      border-radius: 10px;
      padding: 0.75rem 1rem 0.75rem 42px;
      color: #0f172a;
      font-size: 0.92rem;
      font-family: inherit;
      outline: none;
      transition: all 0.2s ease;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .form-control:focus {
      border-color: #3e60d5;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(62, 96, 213, 0.12);
    }

    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, #3e60d5 0%, #2e4ebd 100%);
      color: #ffffff;
      font-weight: 500;
      font-size: 0.95rem;
      padding: 0.85rem 1.25rem;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-top: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 4px 14px rgba(62, 96, 213, 0.35);
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, #3554c2 0%, #2542a6 100%);
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(62, 96, 213, 0.45);
    }

    .alert-error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
      padding: 0.85rem 1rem;
      border-radius: 10px;
      margin-bottom: 1.25rem;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .badge-req {
      font-size: 0.72rem;
      color: #64748b;
      font-weight: 500;
    }

    .logout-bar {
      margin-top: 1.25rem;
      text-align: center;
      border-top: 1px solid #f1f5f9;
      padding-top: 1rem;
    }

    .logout-btn {
      background: none;
      border: none;
      color: #64748b;
      font-size: 0.82rem;
      cursor: pointer;
      text-decoration: underline;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: color 0.2s;
    }

    .logout-btn:hover {
      color: #ef4444;
    }
  </style>
</head>
<body>

  @php
    $admin = auth('admin')->user();
    $isFirstLogin = (bool) ($admin?->must_change_password);
  @endphp

  <div class="container-box">
    <div class="card-box">
      <div class="brand-header">
        <div class="brand-logo-wrap">
          <img src="{{ asset('assets/images/logo-dark-crop.png') }}" alt="FIX Logo">
        </div>
        <h2>{{ $isFirstLogin ? 'Security Setup: New Password' : 'Change Password' }}</h2>
        <div>
          @if($isFirstLogin)
            <span class="status-pill">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px;">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
              First-Login Password Reset Required
            </span>
          @else
            <span class="status-pill" style="background:#e0f2fe; border-color:#bae6fd; color:#0369a1;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px;">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              Account Security &amp; Credentials
            </span>
          @endif
        </div>
      </div>

      @if($isFirstLogin)
        <div class="notice-banner">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
          </svg>
          <div>
            <strong>Hello, {{ $admin?->name ?? 'Staff Member' }}!</strong><br>
            Your administrator account was created with a temporary default password. To protect client data and operations, you must choose a secure permanent password before entering the system.
          </div>
        </div>
      @else
        <div class="notice-banner" style="background:#f8fafc; border-color:#e2e8f0; color:#334155;">
          <svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
          <div>
            <strong>Update Your Password</strong><br>
            Choose a strong, unique password of at least 8 characters to keep your administrator account secure.
          </div>
        </div>
      @endif

      @if($errors->any())
        <div data-alert class="alert-error" style="display:flex; align-items:flex-start; justify-content:space-between;">
          <div style="display:flex; align-items:flex-start; gap:8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px; height:18px; flex-shrink:0; margin-top:2px;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="display:flex; flex-direction:column; gap:3px;">
              @foreach($errors->all() as $error)
                <span>{{ $error }}</span>
              @endforeach
            </div>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:inherit; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <form action="{{ route('admin.password.update') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="current_password" class="form-label">
            <span>{{ $isFirstLogin ? 'Current Default Password' : 'Current Password' }}</span>
            <span class="badge-req">Required</span>
          </label>
          <div class="input-wrapper">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </span>
            <input 
              type="password" 
              id="current_password" 
              name="current_password" 
              class="form-control" 
              placeholder="{{ $isFirstLogin ? 'Enter temporary / default password' : 'Enter your current password' }}" 
              required
              autofocus
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">
            <span>{{ $isFirstLogin ? 'New Permanent Password' : 'New Password' }}</span>
            <span class="badge-req">Min 8 characters</span>
          </label>
          <div class="input-wrapper">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 2l-2 2m-1.5 1.5L10 13l-4 4-2-2 4-4 7.5-7.5M21 2l-3 3"></path>
                <circle cx="7.5" cy="16.5" r="2.5"></circle>
              </svg>
            </span>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-control" 
              placeholder="Choose a strong password" 
              required
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password_confirmation" class="form-label">
            <span>Confirm New Password</span>
            <span class="badge-req">Must match</span>
          </label>
          <div class="input-wrapper">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
            </span>
            <input 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              class="form-control" 
              placeholder="Re-type new password" 
              required
            >
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <span>{{ $isFirstLogin ? 'Set Password & Access Dashboard' : 'Update Password' }}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px; height:18px;">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
      </form>

      <div class="logout-bar" style="display:flex; justify-content:{{ $isFirstLogin ? 'center' : 'space-between' }}; align-items:center; flex-wrap:wrap; gap:10px;">
        @if(! $isFirstLogin)
          <a href="{{ route('admin.dashboard') }}" style="color:#64748b; font-size:0.82rem; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-weight:500;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <span>Cancel &amp; Back to Dashboard</span>
          </a>
        @endif

        <form action="{{ route('admin.logout') }}" method="POST" style="display:inline; margin:0;">
          @csrf
          <button type="submit" class="logout-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px; height:14px;">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Log Out and return to login screen
          </button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
