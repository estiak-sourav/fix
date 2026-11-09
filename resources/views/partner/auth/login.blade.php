<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Partner Sign In | {{ \App\Models\SiteSetting::get('site_name', 'FIX') }}</title>
  <link rel="icon" type="image/png" href="{{ asset(\App\Models\SiteSetting::get('favicon', 'assets/images/favicon-white.png')) }}?v=white">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=white">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.25rem;
      background: #0f172a;
      color: #1e293b;
      -webkit-font-smoothing: antialiased;
    }
    .login-card {
      width: 100%;
      max-width: 400px;
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .login-header {
      padding: 30px 24px 22px;
      text-align: center;
      border-bottom: 1px solid #f1f5f9;
    }
    .brand-badge {
      display: inline-block;
      background: #f0fdfa;
      border: 1px solid #ccfbf1;
      color: #0d9488;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 3px 9px;
      border-radius: 6px;
      margin-bottom: 8px;
    }
    .login-header h1 {
      font-size: 1.3rem;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.02em;
    }
    .login-body {
      padding: 24px;
    }
    .form-group {
      margin-bottom: 16px;
    }
    .form-label {
      display: block;
      font-size: 0.77rem;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
    }
    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .input-icon {
      position: absolute;
      left: 12px;
      color: #94a3b8;
      pointer-events: none;
    }
    .form-control {
      width: 100%;
      height: 44px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 8px 12px 8px 40px;
      font-size: 0.88rem;
      color: #1e293b;
      transition: border-color 0.15s, box-shadow 0.15s;
      outline: none;
    }
    .form-control:focus {
      border-color: #0d9488;
      box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    }
    .btn-toggle-pwd {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      padding: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .btn-submit {
      width: 100%;
      height: 44px;
      background: #0d9488;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      transition: background 0.15s;
      margin-top: 20px;
    }
    .btn-submit:hover { background: #0f766e; }
    .alert {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.82rem;
      font-weight: 600;
      margin-bottom: 16px;
    }
    .alert-danger {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b;
    }
    .alert-success {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #166534;
    }
    .login-footer {
      padding: 16px 24px;
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      text-align: center;
      font-size: 0.8rem;
      color: #64748b;
    }
    .login-footer a {
      color: #0d9488;
      font-weight: 700;
      text-decoration: none;
    }
    .login-footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <div class="login-card">
    <div class="login-header">
      <span class="brand-badge">Partner Portal</span>
      <h1>Sign In</h1>
    </div>

    <div class="login-body">
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
          @endforeach
        </div>
      @endif

      <form action="{{ route('partner.login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label" for="phoneInput">Mobile Number</label>
          <div class="input-wrap">
            <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            <input type="tel" name="phone" id="phoneInput" class="form-control" placeholder="017XXXXXXXX" value="{{ old('phone') }}" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="passwordInput">Password</label>
          <div class="input-wrap">
            <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
            <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility()">Show</button>
          </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; margin-top: 10px;">
          <label style="display: flex; align-items: center; gap: 6px; color: #64748b; cursor: pointer;">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} style="accent-color: #0d9488; width: 15px; height: 15px;">
            Remember me
          </label>
        </div>

        <button type="submit" class="btn-submit">
          <span>Sign In</span>
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </button>
      </form>
    </div>

    <div class="login-footer">
      New partner? 
      <a href="{{ url('/become-a-partner') }}">Apply to Join →</a>
    </div>
  </div>

  <script>
    function togglePasswordVisibility() {
      const pwd = document.getElementById('passwordInput');
      const btn = event.target;
      if (pwd.type === 'password') {
        pwd.type = 'text';
        btn.textContent = 'Hide';
      } else {
        pwd.type = 'password';
        btn.textContent = 'Show';
      }
    }

    // Auto-dismiss alert messages after 4 seconds
    document.addEventListener('DOMContentLoaded', function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        setTimeout(function() {
          alert.style.transition = 'opacity 0.35s ease, transform 0.35s ease, max-height 0.35s ease, margin 0.35s ease, padding 0.35s ease';
          alert.style.opacity = '0';
          alert.style.transform = 'translateY(-6px)';
          alert.style.maxHeight = '0';
          alert.style.marginTop = '0';
          alert.style.marginBottom = '0';
          alert.style.paddingTop = '0';
          alert.style.paddingBottom = '0';
          alert.style.overflow = 'hidden';
          setTimeout(function() {
            alert.remove();
          }, 360);
        }, 4000);
      });
    });
  </script>
</body>
</html>
