<x-layouts.admin title="AMC Packages & Maintenance Contracts | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">📋 Annual Maintenance Contracts (AMC)</h1>
      <p class="page-header-subtitle">Manage customer maintenance packages, tiers, included features, and live statuses</p>
    </div>
    <div style="display:flex; gap:10px;">
      <a href="{{ route('admin.web.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
        ← Web Control
      </a>
      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="toggleAddPackageModal()">
        + Add New Package
      </button>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500;">
      <div style="font-weight:500; margin-bottom:4px;">Please correct the following errors:</div>
      <ul style="margin:0; padding-left:1.25rem;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Add New Package Modal / Drawer -->
  <div id="addPackageModal" style="display:none; background:#ffffff; border:1px solid var(--paces-primary-border); border-radius:12px; padding:1.5rem; margin-bottom:2rem; box-shadow:0 4px 15px rgba(0,0,0,0.06);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem;">
      <h3 style="font-size:1.1rem; font-weight:500; color:var(--paces-primary); margin:0;">
        + Create New AMC Package
      </h3>
      <button type="button" onclick="toggleAddPackageModal()" style="background:none; border:none; font-size:1.1rem; color:var(--paces-text-muted); cursor:pointer;">✕</button>
    </div>

    <form action="{{ route('admin.web.amc-plans.store') }}" method="POST">
      @csrf

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
        <div>
          <label class="form-label-paces">Package Name *</label>
          <input type="text" name="name" required placeholder="e.g. Corporate Premium Facility" class="form-control-paces">
        </div>
        <div>
          <label class="form-label-paces">Target Audience</label>
          <input type="text" name="target_audience" placeholder="e.g. Commercial Towers, Corporate Offices" class="form-control-paces">
        </div>
      </div>

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
        <div>
          <label class="form-label-paces">Contract Period</label>
          <input type="text" name="price_period" placeholder="e.g. Annual / Billed Monthly" class="form-control-paces">
        </div>
        <div>
          <label class="form-label-paces">Tier Classification</label>
          <select name="tier" class="form-control-paces">
            <option value="residential">Residential / Apartment</option>
            <option value="corporate_enterprise">Corporate Enterprise</option>
            <option value="industrial_manufacturing">Industrial / Factory</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:1rem;">
        <label class="form-label-paces">Included Features (One feature per line)</label>
        <textarea name="features" rows="4" class="form-control-paces" placeholder="24/7 dedicated facility engineer&#10;Monthly preventive electrical audit&#10;Zero emergency dispatch fees"></textarea>
      </div>

      <div style="display:flex; gap:16px; margin-bottom:1.25rem;">
        <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:500; cursor:pointer;">
          <input type="checkbox" name="is_popular" value="1">
          <span>Highlight as Popular / Featured</span>
        </label>
        <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:500; cursor:pointer;">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>Active on Live Site</span>
        </label>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px;">
        <button type="button" class="btn-paces btn-paces-outline" onclick="toggleAddPackageModal()">Cancel</button>
        <button type="submit" class="btn-paces btn-paces-primary">Create Package</button>
      </div>
    </form>
  </div>

  <!-- Packages Grid -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap:1.5rem;">
    @foreach($plans as $plan)
      <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between; position:relative; border-top: 4px solid {{ $plan->is_popular ? 'var(--paces-primary)' : 'var(--paces-border)' }};">
        <div>
          <!-- Card Top Bar -->
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div>
              <span class="badge-pill-paces {{ $plan->is_active ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                {{ $plan->is_active ? 'Active' : 'Deactivated' }}
              </span>
              @if($plan->is_popular)
                <span class="badge-pill-paces bg-primary-subtle" style="margin-left:6px;">⭐ Featured</span>
              @endif
            </div>
            <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">
              Tier: {{ ucfirst(str_replace('_', ' ', $plan->tier ?? 'Standard')) }}
            </span>
          </div>

          <!-- Edit Form -->
          <form action="{{ route('admin.web.amc-plans.update', $plan) }}" method="POST">
            @csrf

            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">Package Name *</label>
              <input type="text" name="name" value="{{ $plan->name }}" class="form-control-paces" required>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:1rem;">
              <div>
                <label class="form-label-paces">Contract Period</label>
                <input type="text" name="price_period" value="{{ $plan->price_period }}" class="form-control-paces" placeholder="e.g. Annual Contract">
              </div>
              <div>
                <label class="form-label-paces">Tier</label>
                <select name="tier" class="form-control-paces">
                  <option value="residential" {{ $plan->tier === 'residential' ? 'selected' : '' }}>Residential</option>
                  <option value="corporate_enterprise" {{ $plan->tier === 'corporate_enterprise' ? 'selected' : '' }}>Corporate Enterprise</option>
                  <option value="industrial_manufacturing" {{ $plan->tier === 'industrial_manufacturing' ? 'selected' : '' }}>Industrial / Factory</option>
                </select>
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">Target Customer Audience</label>
              <input type="text" name="target_audience" value="{{ $plan->target_audience }}" class="form-control-paces" placeholder="e.g. Commercial Towers & Offices">
            </div>

            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">Included Features (One per line)</label>
              <textarea name="features" rows="4" class="form-control-paces">{{ is_array($plan->features) ? implode("\n", $plan->features) : '' }}</textarea>
            </div>

            <div style="background:#f8fafc; border-radius:8px; padding:10px 12px; margin-bottom:1.25rem; border:1px solid var(--paces-border); display:flex; gap:16px;">
              <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:500; cursor:pointer;">
                <input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}>
                Popular Highlight
              </label>

              <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:500; cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                Active on Live Site
              </label>
            </div>

            <button type="submit" class="btn-paces btn-paces-primary" style="width:100%; margin-bottom:10px;">
              Save Package Changes
            </button>
          </form>
        </div>

        <!-- Action Buttons: Activate/Deactivate & Delete -->
        <div style="border-top:1px solid var(--paces-border); padding-top:0.75rem; margin-top:0.5rem; display:flex; justify-content:space-between; align-items:center;">
          <!-- Toggle Status -->
          <form action="{{ route('admin.web.amc-plans.toggle-status', $plan) }}" method="POST" style="margin:0;">
            @csrf
            @if($plan->is_active)
              <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#d97706; border-color:#fcd34d;">
                Deactivate
              </button>
            @else
              <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#16a34a; border-color:#86efac;">
                Activate
              </button>
            @endif
          </form>

          <!-- Delete Form -->
          <form action="{{ route('admin.web.amc-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete AMC package \'{{ $plan->name }}\'?');" style="margin:0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-paces btn-paces-sm" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;">
              Delete Package
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  <script>
    function toggleAddPackageModal() {
      const modal = document.getElementById('addPackageModal');
      modal.style.display = (modal.style.display === 'none' || modal.style.display === '') ? 'block' : 'none';
      if (modal.style.display === 'block') {
        modal.scrollIntoView({ behavior: 'smooth' });
      }
    }
  </script>
</x-layouts.admin>
