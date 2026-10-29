<x-layouts.admin title="New Service | FIX Admin">
  <div class="page-header-box">
    <div>
      <div style="margin-bottom:0.5rem;">
        <a href="{{ route('admin.web.services') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          ← Back to Service List
        </a>
      </div>
      <h1 class="page-header-title">Add New Facility Service</h1>
      <p class="page-header-subtitle">Create a new engineering service offering, upload photos, and set pricing details</p>
    </div>
  </div>

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; max-width:850px; display:flex; align-items:flex-start; justify-content:space-between; gap:8px;">
      <div>
        <div style="font-weight:500; margin-bottom:4px;">Please correct the following errors:</div>
        <ul style="margin:0; padding-left:1.25rem;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  <div class="card-paces" style="max-width:850px;">
    <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1.5rem;">
      <h3 class="card-title-paces">Service Details & Photos</h3>
    </div>

    <form action="{{ route('admin.web.services.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Primary Thumbnail Image Upload -->
      <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
        <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:0.85rem;">Primary Thumbnail Photo</h4>
        
        <div>
          <label class="form-label-paces">
            Upload Main Photo from Computer
          </label>
          <input type="file" name="thumbnail_file" accept="image/*" class="form-control-paces">
          <span class="form-help-paces">Featured primary photo shown in service grids and booking summaries.</span>
        </div>
      </div>

      <!-- Multiple Gallery Photos Upload (Up to 5 Photos) -->
      <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
        <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:0.85rem;">
          Service Photo Gallery (Up to 5 Photos)
        </h4>

        <div>
          <label class="form-label-paces">
            Add Gallery Photos from Computer
          </label>
          <input type="file" name="service_photos[]" accept="image/*" multiple class="form-control-paces">
          <span class="form-help-paces">You can select up to 5 additional on-site photos of real work or tools.</span>
        </div>
      </div>

      <!-- Basic Identity Fields -->
      <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
        <div>
          <label class="form-label-paces">Service Title *</label>
          <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Substation & Transformer Servicing" class="form-control-paces" required>
        </div>
        <div>
          <label class="form-label-paces">Category</label>
          <select name="category_id" class="form-control-paces">
            <option value="">General Facility</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Subtitle / Quick Tagline</label>
        <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Certified HT/LT electrical engineering with thermal imaging" class="form-control-paces">
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Detailed Service Description *</label>
        <textarea name="description" class="form-control-paces" rows="4" placeholder="Comprehensive breakdown of diagnostics, equipment maintenance, and technician qualifications..." required>{{ old('description') }}</textarea>
      </div>

      <!-- Dynamic Pricing & Post-Inspection Assessment Notice -->
      <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; margin-bottom:1.25rem; font-size:0.82rem; color:#475569; display:flex; align-items:center; gap:8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
        <span>Pricing is tailored on-demand: clients submit issues, and operations sets or invoices the exact price after engineering assessment or service delivery.</span>
      </div>

      <!-- Included Scope Breakdown (One per line) -->
      <div style="margin-bottom:1.5rem;">
        <label class="form-label-paces">Scope Included Checklist (One per line)</label>
        <textarea name="scope_included" class="form-control-paces" rows="4" placeholder="Insulation resistance test&#10;Contact resistance checking&#10;Thermal heat inspection&#10;Digital calibrated certification report">{{ old('scope_included') }}</textarea>
        <span class="form-help-paces">Each line will be rendered as a verified green checkmark in the customer view.</span>
      </div>

      <!-- Publication & Emergency Flags -->
      <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem; display:flex; gap:24px; flex-wrap:wrap;">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:500; color:var(--paces-text-dark);">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} style="accent-color:var(--paces-primary); width:18px; height:18px;">
          <span>Published (Active on Live Website)</span>
        </label>

        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.88rem; font-weight:500; color:var(--paces-text-dark);">
          <input type="hidden" name="is_emergency" value="0">
          <input type="checkbox" name="is_emergency" value="1" {{ old('is_emergency') == '1' ? 'checked' : '' }} style="accent-color:var(--paces-primary); width:18px; height:18px;">
          <span>24/7 Emergency Dispatch Eligible</span>
        </label>
      </div>

      <!-- Action Buttons -->
      <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="{{ route('admin.web.services') }}" class="btn-paces btn-paces-outline">Cancel</a>
        <button type="submit" class="btn-paces btn-paces-primary">
          Save & Publish Service
        </button>
      </div>
    </form>
  </div>
</x-layouts.admin>
