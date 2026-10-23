<x-layouts.admin title="Edit Service: {{ $service->title }} | FIX Admin">
  <div class="page-header-box">
    <div>
      <div style="margin-bottom:0.5rem;">
        <a href="{{ route('admin.cms.services') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          ← Back to Services Portfolio
        </a>
      </div>
      <h1 class="page-header-title">Edit Service: {{ $service->title }}</h1>
      <p class="page-header-subtitle">Update service pricing, details, and upload customized photo</p>
    </div>
  </div>

  <div class="card-paces" style="max-width:850px;">
    <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem;">
      <h3 class="card-title-paces">Service Configuration & Media</h3>
    </div>

    <form action="{{ route('admin.cms.services.update', $service) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Image Upload Section -->
      <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
        <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:0.85rem;">📷 Service Card Image Upload</h4>
        
        <div style="display:flex; gap:20px; align-items:center; flex-wrap:wrap;">
          <div>
            <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-bottom:6px; font-weight:500;">Current Thumbnail:</div>
            <img src="{{ $service->thumbnail_url }}" alt="Preview" 
              style="width:130px; height:85px; object-fit:cover; border-radius:10px; border:1px solid var(--paces-border); box-shadow:var(--paces-shadow);">
          </div>
          <div style="flex:1; min-width:240px;">
            <label class="form-label-paces">
              Upload New Photo from Computer
            </label>
            <input type="file" name="thumbnail_file" accept="image/*" class="form-control-paces">
            <span class="form-help-paces">Selecting an image replaces the demo Unsplash URL with your custom uploaded file.</span>
          </div>
        </div>
      </div>

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label class="form-label-paces">Service Title *</label>
          <input type="text" name="title" value="{{ old('title', $service->title) }}" class="form-control-paces" required>
        </div>

        <div>
          <label class="form-label-paces">Category</label>
          <select name="category_id" class="form-control-paces">
            <option value="">Select Category...</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $service->category_id == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Subtitle / Tagline</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $service->subtitle) }}" class="form-control-paces">
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Detailed Description *</label>
        <textarea name="description" class="form-control-paces" rows="4" required>{{ old('description', $service->description) }}</textarea>
      </div>

      <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label class="form-label-paces">Starting Price (৳)</label>
          <input type="number" step="0.01" name="price_starting" value="{{ old('price_starting', $service->price_starting) }}" class="form-control-paces">
        </div>

        <div>
          <label class="form-label-paces">Pricing Unit</label>
          <input type="text" name="pricing_unit" value="{{ old('pricing_unit', $service->pricing_unit) }}" class="form-control-paces" placeholder="e.g. per visit, sqft">
        </div>

        <div>
          <label class="form-label-paces">Emergency SLA (Hours) *</label>
          <input type="number" name="sla_hours" value="{{ old('sla_hours', $service->sla_hours) }}" class="form-control-paces" required>
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">
          Scope / Key Deliverables (One line each)
        </label>
        <textarea name="scope_included" class="form-control-paces" rows="4">{{ is_array($service->scope_included) ? implode("\n", $service->scope_included) : '' }}</textarea>
      </div>

      <div style="display:flex; gap:24px; margin-bottom:1.75rem; background:#f8fafc; padding:1rem 1.25rem; border-radius:8px; border:1px solid var(--paces-border);">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:500; font-size:0.875rem;">
          <input type="checkbox" name="is_emergency" value="1" {{ $service->is_emergency ? 'checked' : '' }} style="accent-color:var(--paces-primary);">
          <span>Emergency 1-Hour SLA Available</span>
        </label>

        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:500; font-size:0.875rem;">
          <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} style="accent-color:var(--paces-primary);">
          <span>Live / Published on Website</span>
        </label>
      </div>

      <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.75rem 2rem;">
        Update Service & Save Photo
      </button>
    </form>
  </div>
</x-layouts.admin>
