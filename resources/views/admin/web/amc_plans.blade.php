<x-layouts.admin title="AMC Packages & Maintenance Contracts | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">📋 Annual Maintenance Contracts (AMC)</h1>
      <p class="page-header-subtitle">Manage customer maintenance packages, pricing, emergency SLA response times, and featured tiers</p>
    </div>
    <a href="{{ route('admin.web.index') }}" class="btn-paces btn-paces-primary" style="background:#475569; border-color:#475569;">
      ← Back to Web Control
    </a>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap:1.5rem;">
    @foreach($plans as $plan)
      <div class="card-paces" style="display:flex; flex-direction:column; justify-content:space-between; position:relative; border-top: 4px solid {{ $plan->is_popular ? 'var(--paces-primary)' : 'var(--paces-border)' }};">
        <div>
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div>
              <span class="badge-pill-paces {{ $plan->is_active ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                {{ $plan->is_active ? 'Active on Web' : 'Draft / Inactive' }}
              </span>
              @if($plan->is_popular)
                <span class="badge-pill-paces bg-primary-subtle" style="margin-left:6px;">⭐ Featured</span>
              @endif
            </div>
            <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">
              Tier: {{ $plan->tier ?? 'Standard' }}
            </span>
          </div>

          <form action="{{ route('admin.web.amc-plans.update', $plan) }}" method="POST">
            @csrf

            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">Package Name *</label>
              <input type="text" name="name" value="{{ $plan->name }}" class="form-control-paces" required>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:1rem;">
              <div>
                <label class="form-label-paces">Price (৳) *</label>
                <input type="number" step="0.01" name="price" value="{{ $plan->price }}" class="form-control-paces" required>
              </div>
              <div>
                <label class="form-label-paces">Billing Period *</label>
                <input type="text" name="price_period" value="{{ $plan->price_period }}" class="form-control-paces" placeholder="e.g. / month" required>
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">Emergency SLA Response Time *</label>
              <input type="text" name="sla_response_time" value="{{ $plan->sla_response_time }}" class="form-control-paces" placeholder="e.g. Within 2 Hours" required>
            </div>

            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">Target Customer Audience</label>
              <input type="text" name="target_audience" value="{{ $plan->target_audience }}" class="form-control-paces" placeholder="e.g. Apartments & Villas">
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

            <button type="submit" class="btn-paces btn-paces-primary" style="width:100%;">
              Save Package Changes
            </button>
          </form>
        </div>

        @if(!empty($plan->features))
          <div style="margin-top:1.25rem; padding-top:1rem; border-top:1px dashed var(--paces-border);">
            <div style="font-size:0.75rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase; margin-bottom:6px;">Included Features</div>
            <ul style="font-size:0.8rem; color:var(--paces-text-muted); margin:0; padding-left:1.2rem; line-height:1.6;">
              @foreach($plan->features as $f)
                <li>{{ $f }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</x-layouts.admin>
