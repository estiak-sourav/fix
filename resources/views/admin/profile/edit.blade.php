<x-layouts.admin title="Administrator Profile & DP | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="margin-bottom:6px;">
        <a href="{{ route('admin.dashboard') }}" style="color:var(--paces-primary); font-size:0.85rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
          ← Back to Dashboard
        </a>
      </div>
      <h1 class="page-header-title">My Profile & Display Picture</h1>
      <p class="page-header-subtitle">Manage your administrator identity, upload your avatar/DP, and update security credentials</p>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:14px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; gap:10px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:14px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-size:0.875rem;">
      <div style="font-weight:500; margin-bottom:6px;">Please correct the following errors:</div>
      <ul style="padding-left:20px; margin:0; font-weight:400;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; max-width:960px;">

      <!-- Display Picture (DP) Card -->
      <div class="card-paces">
        <div class="card-header-paces">
          <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v8"></path><path d="M8 12h8"></path></svg>
            Display Picture (Avatar / DP)
          </h2>
        </div>

        <div class="card-body-paces" style="padding:1.5rem;">
          <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
            <!-- DP Preview Box -->
            <div style="position:relative; flex-shrink:0;">
              <div id="adminAvatarContainer" style="width:92px; height:92px; border-radius:50%; background:linear-gradient(135deg, var(--paces-primary) 0%, #1e293b 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:500; overflow:hidden; border:3px solid #e2e8f0; box-shadow:0 4px 10px rgba(0,0,0,0.08);">
                @if($admin->avatar_url)
                  <img id="adminAvatarImg" src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                @else
                  <span id="adminAvatarInitials">{{ strtoupper(substr($admin->name ?: 'A', 0, 1)) }}</span>
                  <img id="adminAvatarImg" src="" alt="Avatar Preview" style="display:none; width:100%; height:100%; object-fit:cover;">
                @endif
              </div>
            </div>

            <!-- Upload Controls -->
            <div style="flex:1; min-width:240px;">
              <label for="adminAvatarInput" style="display:block; font-weight:500; font-size:0.875rem; margin-bottom:6px; color:var(--paces-text);">
                Upload New Display Picture
              </label>
              <input 
                type="file" 
                id="adminAvatarInput" 
                name="avatar" 
                accept="image/png, image/jpeg, image/jpg, image/webp"
                class="form-control-paces"
                style="padding:8px 12px; font-size:0.85rem;"
                onchange="previewAdminAvatar(this)"
              >
              <div style="color:var(--paces-text-muted); font-size:0.75rem; font-weight:400; margin-top:6px;">
                Supported formats: JPEG, PNG, WebP. Maximum file size: 2MB. Square ratio recommended.
              </div>

              @if($admin->avatar)
                <div style="margin-top:10px;">
                  <label style="display:inline-flex; align-items:center; gap:6px; cursor:pointer; font-size:0.8rem; font-weight:500; color:#dc2626;">
                    <input type="checkbox" name="remove_avatar" value="1" style="accent-color:#dc2626;">
                    <span>Remove current display picture</span>
                  </label>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Account Identity Card -->
      <div class="card-paces">
        <div class="card-header-paces">
          <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Personal Identity & Contact
          </h2>
        </div>

        <div class="card-body-paces" style="padding:1.5rem;">
          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
            <div>
              <label class="form-label-paces" for="name" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Full Name <span style="color:#ef4444;">*</span>
              </label>
              <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control-paces" 
                value="{{ old('name', $admin->name) }}" 
                required
              >
            </div>

            <div>
              <label class="form-label-paces" for="email" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Email Address (Login Username) <span style="color:#ef4444;">*</span>
              </label>
              <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control-paces" 
                value="{{ old('email', $admin->email) }}" 
                required
              >
            </div>

            <div>
              <label class="form-label-paces" for="phone" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Phone Number
              </label>
              <input 
                type="text" 
                name="phone" 
                id="phone" 
                class="form-control-paces" 
                value="{{ old('phone', $admin->phone) }}" 
                placeholder="017xxxxxxxx"
              >
            </div>

            <div>
              <label class="form-label-paces" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Assigned Role
              </label>
              <div style="padding:9px 12px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:var(--paces-radius-sm); font-size:0.875rem; color:#475569; display:flex; align-items:center; gap:8px;">
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span>
                <span style="font-weight:500;">{{ ucfirst(str_replace('_', ' ', $admin->role)) }}</span>
                @if($admin->isSuperAdmin())
                  <span style="background:#e0e7ff; color:#4338ca; font-size:0.75rem; padding:2px 8px; border-radius:12px; font-weight:500; margin-left:auto;">Full System Authority</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Security & Password Card -->
      <div class="card-paces">
        <div class="card-header-paces">
          <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Security & Password Update
          </h2>
        </div>

        <div class="card-body-paces" style="padding:1.5rem;">
          <p style="font-size:0.8rem; color:var(--paces-text-muted); margin:0 0 1.25rem 0; font-weight:400;">
            Leave these fields empty if you do not wish to change your login password.
          </p>

          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
            <div>
              <label class="form-label-paces" for="current_password" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Current Password
              </label>
              <input 
                type="password" 
                name="current_password" 
                id="current_password" 
                class="form-control-paces" 
                placeholder="Enter current password to verify"
                autocomplete="current-password"
              >
            </div>

            <div>
              <label class="form-label-paces" for="password" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                New Password
              </label>
              <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control-paces" 
                placeholder="Minimum 8 characters"
                autocomplete="new-password"
              >
            </div>

            <div>
              <label class="form-label-paces" for="password_confirmation" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Confirm New Password
              </label>
              <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation" 
                class="form-control-paces" 
                placeholder="Re-type new password"
                autocomplete="new-password"
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Feature Permissions Transparency Card (Read Only) -->
      <div class="card-paces">
        <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center;">
          <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            Assigned Modular Access Permissions
          </h2>
          <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:400;">Read-only privilege overview</span>
        </div>

        <div class="card-body-paces" style="padding:1.5rem;">
          @if($admin->isSuperAdmin())
            <div style="background:#eef2ff; border:1px solid #c7d2fe; color:#3730a3; padding:12px 16px; border-radius:var(--paces-radius-sm); font-size:0.85rem; font-weight:500;">
              ✨ As a <strong>Super Admin</strong>, you have unconditional full read and write access across all operational modules, settings, tickets, and user accounts.
            </div>
          @else
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:12px;">
              @foreach($modules as $key => $module)
                @php
                  $hasView = $admin->canView($key);
                  $hasEdit = $admin->canEdit($key);
                @endphp
                <div style="padding:10px 14px; border:1px solid {{ ($hasView || $hasEdit) ? '#cbd5e1' : '#f1f5f9' }}; border-radius:var(--paces-radius-sm); background:{{ ($hasView || $hasEdit) ? '#ffffff' : '#f8fafc' }};">
                  <div style="font-weight:500; font-size:0.85rem; color:var(--paces-text); margin-bottom:4px;">
                    {{ $module['name'] }}
                  </div>
                  <div style="display:flex; gap:6px; font-size:0.72rem; font-weight:500;">
                    <span style="padding:2px 6px; border-radius:4px; {{ $hasView ? 'background:#dcfce7; color:#15803d;' : 'background:#e2e8f0; color:#94a3b8;' }}">
                      {{ $hasView ? '✓ View' : '✕ View' }}
                    </span>
                    <span style="padding:2px 6px; border-radius:4px; {{ $hasEdit ? 'background:#e0e7ff; color:#4338ca;' : 'background:#e2e8f0; color:#94a3b8;' }}">
                      {{ $hasEdit ? '✓ Manage / Edit' : '✕ Edit' }}
                    </span>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      <!-- Action Buttons -->
      <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:0.5rem; margin-bottom:2rem;">
        <a href="{{ route('admin.dashboard') }}" class="btn-paces" style="background:#e2e8f0; color:#334155; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; padding:10px 20px; border-radius:var(--paces-radius-sm);">
          Cancel
        </a>
        <button type="submit" class="btn-paces" style="background:var(--paces-primary); color:#ffffff; font-weight:500; display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border:none; border-radius:var(--paces-radius-sm); cursor:pointer;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
          Save Changes & Update DP
        </button>
      </div>
    </div>
  </form>

  <script>
    function previewAdminAvatar(input) {
      if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
          alert('Selected image exceeds 2MB maximum size limit.');
          input.value = '';
          return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById('adminAvatarImg');
          const initials = document.getElementById('adminAvatarInitials');
          if (img) {
            img.src = e.target.result;
            img.style.display = 'block';
          }
          if (initials) {
            initials.style.display = 'none';
          }
        };
        reader.readAsDataURL(file);
      }
    }
  </script>
</x-layouts.admin>
