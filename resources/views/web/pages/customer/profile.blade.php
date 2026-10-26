<x-layouts.app title="My Profile Settings | FIX Facility Management">
  <section class="page-hero">
    <div class="container">
      <h1>Customer Profile & Settings</h1>
      <p>Keep your profile picture, contact details, facility locations, and security settings up to date.</p>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container" style="max-width:800px;">
      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      @if($errors->any())
        <div data-alert style="background:#fee2e2; border:1px solid #f87171; color:#991b1b; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:flex-start; justify-content:space-between;">
          <ul style="margin:0; padding-left:1.25rem;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#991b1b; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <div class="form-card">
        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <!-- Profile Picture (DP) Section -->
          <div class="form-step-section" style="border-bottom:1px solid var(--border-color); padding-bottom:1.5rem; margin-bottom:1.5rem;">
            <div class="step-header-title">
              <span class="step-num-pill">📷</span>
              <h3>Profile Picture (DP)</h3>
            </div>

            <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap; margin-top:12px;">
              <div style="position:relative;">
                <div id="avatarPreviewContainer" style="width:84px; height:84px; border-radius:50%; background:linear-gradient(135deg, var(--primary) 0%, #1e293b 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:600; overflow:hidden; border:3px solid #e2e8f0; box-shadow:0 4px 10px rgba(0,0,0,0.08);">
                  @if($user->avatar_url)
                    <img id="avatarPreviewImg" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover;">
                  @else
                    <span id="avatarInitials">{{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}</span>
                    <img id="avatarPreviewImg" src="" alt="Preview" style="display:none; width:100%; height:100%; object-fit:cover;">
                  @endif
                </div>
              </div>

              <div style="flex:1; min-width:240px;">
                <label for="profAvatar" style="display:block; font-weight:600; font-size:0.875rem; margin-bottom:6px;">
                  Upload New Display Picture
                </label>
                <input 
                  type="file" 
                  id="profAvatar" 
                  name="avatar" 
                  accept="image/png, image/jpeg, image/jpg, image/webp"
                  class="form-control"
                  style="padding:6px 10px; font-size:0.85rem;"
                  onchange="previewUserAvatar(this)"
                >
                <small style="color:var(--text-muted); font-size:0.75rem; display:block; margin-top:4px;">
                  Supports JPG, PNG or WebP. Max 2MB. Recommended: 400x400 square photo.
                </small>

                @if($user->avatar)
                  <label style="display:inline-flex; align-items:center; gap:6px; margin-top:8px; cursor:pointer; font-size:0.8rem; color:#dc2626;">
                    <input type="checkbox" name="remove_avatar" value="1" style="accent-color:#dc2626;">
                    <span>Remove current photo</span>
                  </label>
                @endif
              </div>
            </div>
          </div>

          <!-- Primary Contact Section -->
          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">👤</span>
              <h3>Primary Contact Information</h3>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="profName">Full Name <span class="required">*</span></label>
                <input type="text" id="profName" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
              </div>

              <div class="form-group">
                <label for="profPhone">Mobile Phone <span class="required">*</span></label>
                <input type="tel" id="profPhone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="017xxxxxxxx" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="profEmail">Email Address (Registered via OTP)</label>
                <input type="email" id="profEmail" class="form-control" value="{{ $user->email }}" readonly style="background:var(--bg-page); cursor:not-allowed;">
                <small style="color:var(--text-muted); display:block; margin-top:4px;">Email is linked to your secure passwordless OTP login.</small>
              </div>

              <div class="form-group">
                <label for="profCompany">Company / Organization</label>
                <input type="text" id="profCompany" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}" placeholder="e.g. Apex Corporation">
              </div>
            </div>

            <div class="form-group">
              <label for="profPropertyType">Primary Facility Type</label>
              <select id="profPropertyType" name="property_type" class="form-control">
                <option value="residential" {{ old('property_type', $user->property_type) === 'residential' ? 'selected' : '' }}>Residential Apartment / House</option>
                <option value="commercial" {{ old('property_type', $user->property_type) === 'commercial' ? 'selected' : '' }}>Commercial Office / Building</option>
                <option value="industrial" {{ old('property_type', $user->property_type) === 'industrial' ? 'selected' : '' }}>Industrial / Factory / Warehouse</option>
                <option value="retail" {{ old('property_type', $user->property_type) === 'retail' ? 'selected' : '' }}>Retail Store / Restaurant</option>
              </select>
            </div>
          </div>

          <!-- Address Section -->
          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">📍</span>
              <h3>Default Facility Address</h3>
            </div>

            <div class="form-row" style="grid-template-columns: 1fr 1fr 1fr;">
              <div class="form-group">
                <label for="profDivision">Division <span class="required">*</span></label>
                <select id="profDivision" name="division" class="form-control" required>
                  @foreach(['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'] as $div)
                    <option value="{{ $div }}" {{ old('division', $user->division) == $div ? 'selected' : '' }}>{{ $div }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label for="profDistrict">District <span class="required">*</span></label>
                <input type="text" id="profDistrict" name="district" class="form-control" value="{{ old('district', $user->district ?? 'Dhaka City') }}" placeholder="e.g. Dhaka City, Gazipur, Chattogram City" required>
              </div>

              <div class="form-group">
                <label for="profArea">Thana / Area <span class="required">*</span></label>
                <input type="text" id="profArea" name="area" class="form-control" value="{{ old('area', $user->area ?? 'Gulshan / Banani') }}" required>
              </div>
            </div>

            <div class="form-group">
              <label for="profAddress">Full Street Address <span class="required">*</span></label>
              <textarea id="profAddress" name="default_address" class="form-control" rows="3" required placeholder="House, Road, Block, Floor">{{ old('default_address', $user->default_address) }}</textarea>
            </div>
          </div>

          <!-- Optional Password Security Section -->
          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">🔒</span>
              <h3>Security & Password (Optional)</h3>
            </div>
            <p style="font-size:0.83rem; color:var(--text-muted); margin-bottom:12px;">
              Leave these fields blank if you prefer logging in via fast email OTP verification.
            </p>

            <div class="form-row">
              <div class="form-group">
                <label for="profPass">Set / Change Password</label>
                <input type="password" id="profPass" name="password" class="form-control" placeholder="Min 8 characters">
              </div>

              <div class="form-group">
                <label for="profPassConf">Confirm New Password</label>
                <input type="password" id="profPassConf" name="password_confirmation" class="form-control" placeholder="Re-type new password">
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-accent btn-lg btn-block" style="padding:0.9rem; width:100%; font-weight:600;">
            Save Profile & DP Changes
          </button>
        </form>
      </div>
    </div>
  </section>

  <script>
    function previewUserAvatar(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById('avatarPreviewImg');
          const initials = document.getElementById('avatarInitials');
          if (img) {
            img.src = e.target.result;
            img.style.display = 'block';
          }
          if (initials) {
            initials.style.display = 'none';
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</x-layouts.app>
