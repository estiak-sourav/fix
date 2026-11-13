<x-layouts.app :title="'My Profile Settings | ' . \App\Models\SiteSetting::get('site_name', 'FIX Facility Management')">
  <x-page-hero 
    title="Customer Profile & Settings" 
    subtitle="Keep your contact details and default facility address up to date." 
  />

  <section class="section" style="padding-top:2.5rem; padding-bottom:4rem;">
    <div class="container" style="max-width:820px;">

      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
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

      {{-- ===================== 1. PROFILE DETAILS VIEW (DEFAULT) ===================== --}}
      <div id="customerProfileDetailsView" style="margin-bottom:2rem;">
        {{-- Profile Header Card --}}
        <div class="customer-profile-card" style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:1.75rem 2rem; margin-bottom:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px; border-bottom:1px solid var(--border); padding-bottom:1.25rem; margin-bottom:1.5rem;">
            <div>
              <h2 style="font-size:1.25rem; font-weight:700; margin:0 0 4px; color:var(--text-main);">My Customer Profile</h2>
              <p style="margin:0; font-size:0.85rem; color:var(--text-muted);">View and manage your registered client details.</p>
            </div>
            <button type="button" class="btn btn-accent" onclick="showCustomerEditMode()" style="display:inline-flex; align-items:center; gap:8px; padding:9px 20px; font-size:0.875rem; font-weight:700; border-radius:8px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              <span>Edit Profile</span>
            </button>
          </div>

          {{-- Avatar and User Identity --}}
          <div style="display:flex; align-items:center; gap:22px; flex-wrap:wrap; padding:1.25rem 1.5rem; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px;">
            <div style="width:84px; height:84px; border-radius:50%; background:linear-gradient(135deg,var(--primary) 0%,#1e293b 100%); color:#fff; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; overflow:hidden; border:3px solid #e2e8f0; box-shadow:0 4px 10px rgba(0,0,0,0.08); flex-shrink:0;">
              @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover; object-position:center;">
              @else
                <span>{{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}</span>
              @endif
            </div>

            <div style="flex:1; min-width:220px;">
              <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:4px;">
                <h3 style="font-size:1.2rem; font-weight:700; margin:0; color:var(--text-main);">{{ $user->name }}</h3>
                <span style="font-size:0.75rem; font-weight:700; color:#0d9488; background:#f0fdfa; border:1px solid #ccfbf1; padding:3px 10px; border-radius:9999px;">
                  Verified Client
                </span>
              </div>
              <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap; font-size:0.85rem; color:var(--text-muted); margin-top:6px;">
                <span style="display:inline-flex; align-items:center; gap:5px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                  {{ $user->phone }}
                </span>
                <span style="display:inline-flex; align-items:center; gap:5px;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                  {{ $user->email }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Contact & Account Information Card --}}
        <div class="customer-profile-card" style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:1.75rem 2rem; margin-bottom:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0; color:var(--text-main);">Primary Contact Information</h3>
          </div>

          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:16px;">
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Full Name</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->name }}</div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Mobile Phone</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->phone }}</div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Email Address</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->email }}</div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Company / Organization</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->company_name ?: 'Individual Customer' }}</div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; grid-column:1 / -1;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Primary Facility Type</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">
                @php
                  $facilityLabels = [
                    'residential' => 'Residential Apartment / House',
                    'commercial' => 'Commercial Office / Building',
                    'industrial' => 'Industrial / Factory / Warehouse',
                    'retail' => 'Retail Store / Restaurant',
                  ];
                @endphp
                {{ $facilityLabels[$user->property_type] ?? ($user->property_type ?: 'Residential Apartment / House') }}
              </div>
            </div>
          </div>
        </div>

        {{-- Address Details Card --}}
        <div class="customer-profile-card" style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:1.75rem 2rem; margin-bottom:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0; color:var(--text-main);">Address</h3>
          </div>

          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:14px;">
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Division</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->division ?: 'Not set' }}</div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">District</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->district ?: 'Not set' }}</div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px;">
              <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Thana / Area</div>
              <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">{{ $user->area ?: 'Not set' }}</div>
            </div>
          </div>

          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Full Street Address</div>
            <div style="font-size:0.95rem; font-weight:500; color:var(--text-main); line-height:1.5;">
              @if($user->default_address)
                📍 {{ $user->default_address }}
              @else
                <span style="color:var(--text-muted); font-style:italic;">No street address provided yet.</span>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- ===================== 2. PROFILE EDIT VIEW (HIDDEN BY DEFAULT) ===================== --}}
      <div id="customerProfileEditView" style="display:none; margin-bottom:2rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:1.5rem; border-bottom:1px solid var(--border); padding-bottom:1rem;">
          <div>
            <h2 style="font-size:1.25rem; font-weight:700; margin:0 0 4px; color:var(--text-main);">Edit Profile &amp; Settings</h2>
            <p style="margin:0; font-size:0.85rem; color:var(--text-muted);">Update your contact details, profile photo, and default service address.</p>
          </div>
          <button type="button" class="btn btn-outline-secondary" onclick="showCustomerDetailsMode()" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; font-size:0.85rem; font-weight:600; border-radius:8px; border:1.5px solid #cbd5e1; background:#fff; cursor:pointer;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>&larr; Back to Profile</span>
          </button>
        </div>

        {{-- ===================== AVATAR CARD ===================== --}}
        <div class="customer-profile-card" style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:1.75rem 2rem; margin-bottom:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0; color:var(--text-main);">Profile Picture</h3>
          </div>

        <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
          {{-- Avatar Preview --}}
          <div id="avatarPreviewContainer" style="width:96px; height:96px; border-radius:50%; background:linear-gradient(135deg,var(--primary) 0%,#1e293b 100%); color:#fff; display:flex; align-items:center; justify-content:center; font-size:2.2rem; font-weight:700; overflow:hidden; border:3px solid #e2e8f0; box-shadow:0 4px 12px rgba(0,0,0,0.1); flex-shrink:0;">
            @if($user->avatar_url)
              <img id="avatarPreviewImg" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover; object-position:center;">
            @else
              <span id="avatarInitials">{{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}</span>
              <img id="avatarPreviewImg" src="" alt="Preview" style="display:none; width:100%; height:100%; object-fit:cover; object-position:center;">
            @endif
          </div>

          {{-- Standalone avatar upload form --}}
          <div style="flex:1; min-width:0;">
            <form id="avatarUploadForm" action="{{ route('customer.profile.avatar') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <label style="display:block; font-size:0.875rem; font-weight:600; color:var(--text-main); margin-bottom:8px;">
                Choose New Profile Photo
              </label>

              <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <label for="profAvatar" style="display:inline-flex; align-items:center; gap:6px; cursor:pointer; background:var(--primary); color:#fff; padding:8px 16px; border-radius:7px; font-size:0.85rem; font-weight:600; transition:opacity 0.2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                  Select Photo
                </label>
                <input type="file" id="profAvatar" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" style="display:none;" onchange="handleAvatarChange(this)">
                <span id="avatarFileName" style="font-size:0.825rem; color:var(--text-muted);">No file selected</span>
              </div>

              <div id="avatarSubmitArea" style="display:none; margin-top:12px;">
                <button type="submit" class="btn btn-accent" style="padding:8px 20px; font-size:0.85rem; font-weight:700; border-radius:7px; display:inline-flex; align-items:center; gap:6px;">
                  <span>Upload & Save Photo</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </button>
              </div>
            </form>

            @if($user->avatar)
              <form action="{{ route('customer.profile.avatar.remove') }}" method="POST" style="margin-top:10px;" onsubmit="return confirm('Are you sure you want to remove your profile photo?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none; border:none; color:#ef4444; font-size:0.8rem; font-weight:600; cursor:pointer; padding:0; display:inline-flex; align-items:center; gap:4px; text-decoration:underline;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  Remove current photo
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>

      {{-- ===================== MAIN PROFILE FORM ===================== --}}
      <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Contact Info --}}
        <div class="customer-profile-card" style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:1.75rem 2rem; margin-bottom:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0; color:var(--text-main);">Primary Contact Information</h3>
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
              <label>Email Address</label>
              <input type="email" class="form-control" value="{{ $user->email }}" readonly style="background:var(--bg-page); cursor:not-allowed; color:var(--text-muted);">
              <small style="color:var(--text-muted); display:block; margin-top:4px;">Linked to your OTP authentication — cannot be altered.</small>
            </div>
            <div class="form-group">
              <label for="profCompany">Company / Organization (Optional)</label>
              <input type="text" id="profCompany" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}" placeholder="e.g. Apex Corporation">
            </div>
          </div>

          <div class="form-group">
            <label for="profPropertyType">Primary Facility Type (Optional)</label>
            <select id="profPropertyType" name="property_type" class="form-control">
              <option value="residential" {{ old('property_type', $user->property_type) === 'residential' ? 'selected' : '' }}>Residential Apartment / House</option>
              <option value="commercial"  {{ old('property_type', $user->property_type) === 'commercial'  ? 'selected' : '' }}>Commercial Office / Building</option>
              <option value="industrial"  {{ old('property_type', $user->property_type) === 'industrial'  ? 'selected' : '' }}>Industrial / Factory / Warehouse</option>
              <option value="retail"      {{ old('property_type', $user->property_type) === 'retail'      ? 'selected' : '' }}>Retail Store / Restaurant</option>
            </select>
          </div>
        </div>

        {{-- Address Section (with dynamic Division, District, Thana/Area Dropdowns) --}}
        <div class="customer-profile-card" style="background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:1.75rem 2rem; margin-bottom:1.75rem; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <div style="margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0; color:var(--text-main);">Address</h3>
          </div>

          <div class="form-row form-row-3col" style="grid-template-columns:1fr 1fr 1fr;">
            {{-- 1. Division Dropdown --}}
            <div class="form-group">
              <label for="profDivision">Division</label>
              <select id="profDivision" name="division" class="form-control">
                <option value="">— Select Division —</option>
                @foreach($divisionsData ?? [] as $div)
                  @php
                    $divName = is_array($div) ? ($div['name'] ?? null) : (is_object($div) ? ($div->name ?? null) : (is_string($div) ? $div : null));
                  @endphp
                  @if(!empty($divName))
                    <option value="{{ $divName }}" {{ old('division', $user->division) == $divName ? 'selected' : '' }}>
                      {{ $divName }}
                    </option>
                  @endif
                @endforeach
              </select>
            </div>

            {{-- 2. District Dropdown --}}
            <div class="form-group">
              <label for="profDistrict">District</label>
              <select id="profDistrict" name="district" class="form-control">
                <option value="">— Select District —</option>
              </select>
            </div>

            {{-- 3. Thana / Area Dropdown --}}
            <div class="form-group">
              <label for="profArea">Thana / Area</label>
              <select id="profArea" name="area" class="form-control">
                <option value="">— Select Thana / Area —</option>
              </select>
            </div>
          </div>

          <div class="form-group" style="margin-top:14px;">
            <label for="profAddress">Full Street Address</label>
            <textarea id="profAddress" name="default_address" class="form-control" rows="3" placeholder="House #, Road #, Block, Floor / Suite">{{ old('default_address', $user->default_address) }}</textarea>
          </div>
        </div>

          <div style="display:flex; gap:12px; margin-top:1.5rem;">
            <button type="button" class="btn btn-outline-secondary" onclick="showCustomerDetailsMode()" style="flex:1; padding:0.95rem; font-size:1rem; font-weight:600; border-radius:10px; border:1px solid #cbd5e1; background:#fff; cursor:pointer;">
              Cancel
            </button>
            <button type="submit" class="btn btn-accent" style="flex:2; padding:0.95rem; font-size:1rem; font-weight:700; border-radius:10px; letter-spacing:0.01em;">
              Save Profile Changes →
            </button>
          </div>
        </form>
      </div>

    </div>
  </section>

  <script>
    function showCustomerEditMode() {
      const details = document.getElementById('customerProfileDetailsView');
      const edit = document.getElementById('customerProfileEditView');
      if (details && edit) {
        details.style.display = 'none';
        edit.style.display = 'block';
        window.location.hash = 'edit';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    }

    function showCustomerDetailsMode() {
      const details = document.getElementById('customerProfileDetailsView');
      const edit = document.getElementById('customerProfileEditView');
      if (details && edit) {
        edit.style.display = 'none';
        details.style.display = 'block';
        if (window.location.hash === '#edit') {
          history.replaceState(null, null, window.location.pathname);
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const hash = window.location.hash.replace('#', '');
      const hasErrors = @json($errors->any());
      if (urlParams.get('edit') === '1' || hash === 'edit' || hasErrors) {
        showCustomerEditMode();
      } else {
        showCustomerDetailsMode();
      }
    });
    // Avatar upload client preview
    function handleAvatarChange(input) {
      if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('avatarFileName').textContent = file.name;
        document.getElementById('avatarSubmitArea').style.display = 'block';

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
        reader.readAsDataURL(file);
      }
    }

    // Cascading Bangladesh Locations Dropdowns (Division -> District -> Thana/Area)
    const locationsHierarchy = @json($divisionsData ?? []);
    const initialDivision = @json(old('division', $user->division ?? ''));
    const initialDistrict = @json(old('district', $user->district ?? ''));
    const initialArea = @json(old('area', $user->area ?? ''));

    const divisionSelect = document.getElementById('profDivision');
    const districtSelect = document.getElementById('profDistrict');
    const areaSelect = document.getElementById('profArea');

    function populateDistricts(divName, preselectedDistrict = '') {
      districtSelect.innerHTML = '<option value="">— Select District —</option>';
      areaSelect.innerHTML = '<option value="">— Select Thana / Area —</option>';

      if (!divName) return;

      const foundDiv = Array.isArray(locationsHierarchy) 
        ? locationsHierarchy.find(d => d && (d.name === divName || d === divName))
        : null;
      if (!foundDiv || !Array.isArray(foundDiv.districts)) return;

      foundDiv.districts.forEach(dist => {
        const distName = (dist && dist.name) ? dist.name : dist;
        const opt = document.createElement('option');
        opt.value = distName;
        opt.textContent = distName;
        if (distName === preselectedDistrict) {
          opt.selected = true;
        }
        districtSelect.appendChild(opt);
      });

      if (preselectedDistrict) {
        populateThanas(divName, preselectedDistrict, initialArea);
      }
    }

    function populateThanas(divName, distName, preselectedArea = '') {
      areaSelect.innerHTML = '<option value="">— Select Thana / Area —</option>';

      if (!divName || !distName) return;

      const foundDiv = Array.isArray(locationsHierarchy)
        ? locationsHierarchy.find(d => d && (d.name === divName || d === divName))
        : null;
      if (!foundDiv || !Array.isArray(foundDiv.districts)) return;

      const foundDist = foundDiv.districts.find(d => d && (d.name === distName || d === distName));
      if (!foundDist || !Array.isArray(foundDist.thanas)) return;

      foundDist.thanas.forEach(thanaItem => {
        const thanaName = (thanaItem && thanaItem.name) ? thanaItem.name : thanaItem;
        const opt = document.createElement('option');
        opt.value = thanaName;
        opt.textContent = thanaName;
        if (thanaName === preselectedArea) {
          opt.selected = true;
        }
        areaSelect.appendChild(opt);
      });
    }

    divisionSelect.addEventListener('change', function() {
      populateDistricts(this.value);
    });

    districtSelect.addEventListener('change', function() {
      populateThanas(divisionSelect.value, this.value);
    });

    // Initialize with existing user data on load
    if (initialDivision) {
      divisionSelect.value = initialDivision;
      populateDistricts(initialDivision, initialDistrict);
    }
  </script>
</x-layouts.app>
