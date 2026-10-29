<x-layouts.app title="My Profile Settings | FIX Facility Management">
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
                  <option value="{{ $div['name'] }}" {{ old('division', $user->division) == $div['name'] ? 'selected' : '' }}>
                    {{ $div['name'] }}
                  </option>
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

        <button type="submit" class="btn btn-accent" style="width:100%; padding:0.95rem; font-size:1rem; font-weight:700; border-radius:10px; letter-spacing:0.01em;">
          Save Profile Changes →
        </button>
      </form>

    </div>
  </section>

  <script>
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

      const foundDiv = locationsHierarchy.find(d => d.name === divName);
      if (!foundDiv || !foundDiv.districts) return;

      foundDiv.districts.forEach(dist => {
        const opt = document.createElement('option');
        opt.value = dist.name;
        opt.textContent = dist.name;
        if (dist.name === preselectedDistrict) {
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

      const foundDiv = locationsHierarchy.find(d => d.name === divName);
      if (!foundDiv || !foundDiv.districts) return;

      const foundDist = foundDiv.districts.find(d => d.name === distName);
      if (!foundDist || !foundDist.thanas) return;

      foundDist.thanas.forEach(thanaName => {
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
