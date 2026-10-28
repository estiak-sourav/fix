<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | FIX</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}?v=white">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=white">
  <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon-white.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    *, body, button, input, select, textarea {
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
    }
    
    h1, h2, h3, h4, h5, h6, b, strong, .brand-header h2, .status-pill, .form-label, .btn-submit, .credentials-chip code {
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
        linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 41, 59, 0.82) 50%, rgba(15, 23, 42, 0.94) 100%),
        url("{{ asset('assets/images/admin-login-bg.jpg') }}");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      overflow-x: hidden;
    }

    /* Ambient lighting vignette */
    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 50% 30%, rgba(62, 96, 213, 0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 450px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.8);
      border-radius: 20px;
      padding: 2.75rem 2.25rem 2.25rem;
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
      margin-bottom: 2rem;
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
      font-size: 1.45rem;
      font-weight: 500;
      color: #0f172a;
      font-family: 'Poppins', sans-serif;
      letter-spacing: -0.02em;
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 6px;
      padding: 3px 10px;
      border-radius: 20px;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      font-size: 0.75rem;
      font-weight: 500;
      color: #15803d;
    }

    .pulse-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #16a34a;
      box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
      animation: pulse 1.6s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
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
      font-size: 0.95rem;
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

    .toggle-password {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      display: flex;
      align-items: center;
      padding: 4px;
      transition: color 0.2s;
    }

    .toggle-password:hover {
      color: #3e60d5;
    }

    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, #3e60d5 0%, #2e4ebd 100%);
      color: #ffffff;
      font-weight: 500;
      font-size: 0.98rem;
      padding: 0.88rem 1.25rem;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-top: 0.75rem;
      font-family: inherit;
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

    .btn-submit:active {
      transform: translateY(0);
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

    .alert-error svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
    }

    .credentials-chip {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 0.78rem;
      color: #475569;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 1.25rem;
    }

    .credentials-chip code {
      font-family: monospace;
      font-weight: 500;
      color: #3e60d5;
      background: #eef2ff;
      padding: 1px 6px;
      border-radius: 4px;
    }

    .card-footer-info {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.75);
    }

    .card-footer-info a {
      color: #ffffff;
      text-decoration: underline;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <div class="brand-header">
        <div class="brand-logo-wrap">
          <img src="{{ asset('assets/images/logo-dark-crop.png') }}" alt="FIX Logo">
        </div>
        <h2>Operations Command Center</h2>
        <div>
          <span class="status-pill">
            <span class="pulse-dot"></span>
            Authorized Staff Access Only
          </span>
        </div>
      </div>

      @if($errors->any())
        <div data-alert class="alert-error" style="display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px; height:18px; flex-shrink:0;">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ $errors->first() }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:inherit; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      @if(session('success'))
        <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; padding:0.85rem 1rem; border-radius:10px; margin-bottom:1.25rem; font-size:0.85rem; display:flex; align-items:center; justify-content:space-between;">
          <span>{{ session('success') }}</span>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#15803d; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="adminEmail" class="form-label">Staff Email</label>
          <div class="input-wrapper">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
            </span>
            <input 
              type="email" 
              id="adminEmail" 
              name="email" 
              class="form-control" 
              value="{{ old('email') }}" 
              placeholder="admin@fix.com.bd"
              required 
              autofocus
            >
          </div>
        </div>

        <div class="form-group">
          <label for="adminPassword" class="form-label">Security Password</label>
          <div class="input-wrapper">
            <span class="input-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </span>
            <input 
              type="password" 
              id="adminPassword" 
              name="password" 
              class="form-control" 
              placeholder="••••••••••••"
              required
            >
            <button type="button" class="toggle-password" id="togglePasswordBtn" title="Toggle password visibility">
              <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px; height:18px;">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; font-size:0.85rem; color:#475569;">
          <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:500;">
            <input 
              type="checkbox" 
              name="remember" 
              id="remember" 
              value="1" 
              style="width:16px; height:16px; accent-color:#3e60d5; border-radius:4px; cursor:pointer;"
            >
            <span>Remember Device</span>
          </label>
          <span style="font-size:0.75rem; color:#64748b;">Isolated Multi-Guard Session</span>
        </div>

        <button type="submit" class="btn-submit">
          <span>Authenticate & Enter Hub</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px; height:18px;">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
      </form>
    </div>

    <div class="card-footer-info">
      <span>FIX Enterprise Facility Operations • </span>
      <a href="{{ route('home') }}">← Return to Public Portal</a>
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('adminPassword');
    if (toggleBtn && passwordInput) {
      toggleBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
      });
    }
  </script>
</body>
</html>
