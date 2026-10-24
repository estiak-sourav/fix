<x-layouts.admin title="Service List | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Service List</h1>
      <p class="page-header-subtitle">
        Manage standalone facility services, descriptions, photo galleries, and publication statuses
      </p>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:12px;">
      <div>
        <h3 class="card-title-paces">All Services ({{ $services->total() }})</h3>
      </div>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Service Title</th>
            <th>Description</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($services as $service)
            <tr>
              <td>
                <img src="{{ $service->thumbnail_url }}" alt="{{ $service->title }}" 
                  style="width:54px; height:54px; border-radius:10px; object-fit:cover; border:1px solid var(--paces-border); box-shadow:var(--paces-shadow);">
              </td>
              <td>
                <strong style="color:var(--paces-text-dark); font-size:0.92rem;">{{ $service->title }}</strong>
                @if(!empty($service->gallery_images))
                  <div style="font-size:0.72rem; color:var(--paces-primary); margin-top:2px;">
                    📷 {{ count($service->gallery_images) }} gallery photos
                  </div>
                @endif
              </td>
              <td>
                <div style="font-size:0.82rem; color:var(--paces-text-muted); max-width:380px; line-height:1.4;">
                  {{ Str::limit($service->description, 100) }}
                </div>
              </td>
              <td>
                @if($service->is_active)
                  <span class="badge-pill-paces bg-success-subtle">
                    <span style="width:6px; height:6px; border-radius:50%; background:#16a34a;"></span>
                    Published
                  </span>
                @else
                  <span class="badge-pill-paces bg-danger-subtle">
                    <span style="width:6px; height:6px; border-radius:50%; background:#dc2626;"></span>
                    Unpublished
                  </span>
                @endif
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex; gap:6px; align-items:center;">
                  <!-- Edit Button -->
                  <a href="{{ route('admin.cms.services.edit', $service) }}" class="btn-paces btn-paces-primary btn-paces-sm">
                    Edit
                  </a>

                  <!-- Publish / Unpublish Toggle Form -->
                  <form action="{{ route('admin.web.services.toggle-status', $service) }}" method="POST" style="margin:0; display:inline;">
                    @csrf
                    @if($service->is_active)
                      <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#d97706; border-color:#fcd34d;" title="Unpublish this service">
                        Unpublish
                      </button>
                    @else
                      <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#16a34a; border-color:#86efac;" title="Publish this service">
                        Publish
                      </button>
                    @endif
                  </form>

                  <!-- Delete Form -->
                  <form action="{{ route('admin.web.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete service \'{{ $service->title }}\'?');" style="margin:0; display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-paces btn-paces-sm" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;" title="Delete Service">
                      Delete
                    </button>
                  </form>
                </div>
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
