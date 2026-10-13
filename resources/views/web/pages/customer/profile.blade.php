<x-layouts.app title="My Profile Settings | FIX Facility Management">
  <section class="page-hero">
    <div class="container">
      <h1>Customer Profile & Settings</h1>
      <p>Keep your contact details, facility locations, and billing info updated for faster dispatches.</p>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container" style="max-width:800px;">
      @if(session('success'))
        <div style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem;">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div style="background:#fee2e2; border:1px solid #f87171; color:#991b1b; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem;">
          <ul style="margin:0; padding-left:1.25rem;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="form-card">
        <form action="{{ route('customer.profile.update') }}" method="POST">
          @csrf
          @method('PUT')

          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">👤</span>
              <h3>Primary Contact</h3>
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
          </div>

          <div class="form-step-section">
            <div class="step-header-title">
              <span class="step-num-pill">📍</span>
              <h3>Default Facility Address</h3>
            </div>

            <div class="form-row" style="grid-template-columns: 1fr 1fr 1fr;">
              <div class="form-group">
                <label for="profDivision">Division <span class="required">*</span></label>
                <select id="profDivision" name="division" class="form-control" required>
                  <option value="Dhaka" {{ old('division', $user->division) == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                  <option value="Chattogram" {{ old('division', $user->division) == 'Chattogram' ? 'selected' : '' }}>Chattogram</option>
                  <option value="Sylhet" {{ old('division', $user->division) == 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                  <option value="Rajshahi" {{ old('division', $user->division) == 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                  <option value="Khulna" {{ old('division', $user->division) == 'Khulna' ? 'selected' : '' }}>Khulna</option>
                </select>
              </div>

              <div class="form-group">
                <label for="profDistrict">District <span class="required">*</span></label>
                <select id="profDistrict" name="district" class="form-control" required>
                  <option value="Dhaka" {{ old('district', $user->district) == 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                  <option value="Gazipur" {{ old('district', $user->district) == 'Gazipur' ? 'selected' : '' }}>Gazipur</option>
                  <option value="Narayanganj" {{ old('district', $user->district) == 'Narayanganj' ? 'selected' : '' }}>Narayanganj</option>
                  <option value="Chattogram" {{ old('district', $user->district) == 'Chattogram' ? 'selected' : '' }}>Chattogram</option>
                </select>
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

          <button type="submit" class="btn btn-accent btn-lg btn-block" style="padding:0.9rem; width:100%;">
            Update Profile Information
          </button>
        </form>
      </div>
    </div>
  </section>
</x-layouts.app>
