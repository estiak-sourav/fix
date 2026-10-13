<x-layouts.admin title="Services Management & Image Uploads | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Services Portfolio & Image Uploads</h1>
      <p class="page-header-subtitle">
        Manage service offerings, pricing, emergency SLAs, and upload real photos from your computer
      </p>
    </div>
  </div>

  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:12px;">
      <div>
        <h3 class="card-title-paces">Active Services Catalog ({{ $services->total() }})</h3>
        <p style="font-size:0.8rem; color:var(--paces-text-muted);">
          Click 'Edit & Upload Photo' to replace any demo image with actual high-resolution local photos.
        </p>
      </div>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Service Title</th>
            <th>Category</th>
            <th>Starting Price</th>
            <th>Emergency SLA</th>
            <th>Status</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($services as $service)
            <tr>
              <td>
                <img src="{{ $service->thumbnail_url }}" alt="{{ $service->title }}" 
                  style="width:56px; height:56px; border-radius:10px; object-fit:cover; border:1px solid var(--paces-border); box-shadow:var(--paces-shadow);">
              </td>
              <td>
                <strong style="color:var(--paces-text-dark); font-size:0.92rem;">{{ $service->title }}</strong>
                <div style="font-size:0.78rem; color:var(--paces-text-muted); margin-top:2px;">{{ Str::limit($service->description, 60) }}</div>
              </td>
              <td>
                <span style="font-weight:600; color:var(--paces-text-dark);">{{ $service->category?->name ?? 'Uncategorized' }}</span>
              </td>
              <td>
                <strong style="color:var(--paces-success); font-family:'Outfit', sans-serif;">৳ {{ number_format($service->price_starting ?? 0, 2) }}</strong>
                @if($service->pricing_unit)
                  <span style="font-size:0.75rem; color:var(--paces-text-muted);">/ {{ $service->pricing_unit }}</span>
                @endif
              </td>
              <td>
                <span class="badge-pill-paces bg-warning-subtle">
                  ⚡ {{ $service->sla_hours }} Hours
                </span>
              </td>
              <td>
                <span class="badge-pill-paces {{ $service->is_active ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                  {{ $service->is_active ? 'Active' : 'Disabled' }}
                </span>
              </td>
              <td style="text-align:right;">
                <a href="{{ route('admin.cms.services.edit', $service) }}" class="btn-paces btn-paces-primary btn-paces-sm">
                  Edit & Upload Photo 📷
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div style="margin-top:1.5rem;">
      {{ $services->links() }}
    </div>
  </div>
</x-layouts.admin>
