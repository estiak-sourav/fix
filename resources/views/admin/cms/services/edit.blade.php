<x-layouts.admin title="Edit Service: {{ $service->title }} | FIX Admin">
  <div class="page-header-box">
    <div>
      <div style="margin-bottom:0.5rem;">
        <a href="{{ route('admin.web.services') }}" class="btn-paces btn-paces-outline btn-paces-sm">
          ← Back to Service List
        </a>
      </div>
      <h1 class="page-header-title">Edit Service: {{ $service->title }}</h1>
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
      <h3 class="card-title-paces">Service Configuration & Photos</h3>
    </div>

    <form action="{{ route('admin.cms.services.update', $service) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Primary Thumbnail Image Upload -->
      <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
        <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:0.85rem;">Primary Thumbnail Photo</h4>
        
        <div style="display:flex; gap:20px; align-items:center; flex-wrap:wrap;">
          <div>
            <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-bottom:6px; font-weight:500;">Current Thumbnail:</div>
            <img src="{{ $service->thumbnail_url }}" alt="Preview" 
              style="width:130px; height:85px; object-fit:cover; border-radius:10px; border:1px solid var(--paces-border); box-shadow:var(--paces-shadow);">
          </div>
          <div style="flex:1; min-width:240px;">
            <label class="form-label-paces">
              Upload New Main Photo from Computer
            </label>
            <input type="file" name="thumbnail_file" accept="image/*" class="form-control-paces">
            <span class="form-help-paces">Selecting an image replaces the current primary thumbnail with your uploaded local photo.</span>
            <div style="display:flex; align-items:flex-start; gap:8px; margin-top:10px; padding:8px 12px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; font-size:0.8rem; color:#1e40af;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
              <div>
                <strong>সুপারিশকৃত সাইজ (Recommended):</strong> ৮০০ × ৫০০ পিক্সেল (১৬:১০ বা ১৬:৯ রেশিও, JPG/PNG/WebP)।<br>
                <span style="color:#2563eb; font-size:0.75rem;">💡 যেকোনো সাইজ বা রেশিওর ছবি আপলোড করা যাবে (কোনো সাইজ লিমিট বা বাধ্যবাধকতা নেই)। ওয়েবসাইটে ইমেজগুলো স্বয়ংক্রিয়ভাবে ফিক্সড হাইট ও উইথে প্রফেশনালভাবে ক্রপ হয়ে প্রদর্শিত হবে।</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Multiple Gallery Photos Upload (Up to 5 Photos) -->
      <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.5rem;">
        <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:0.85rem;">
          Service Photo Gallery (Up to 5 Photos)
        </h4>

        @php
          $gallery = $service->gallery_images ?? [];
        @endphp

        @if(!empty($gallery))
          <div style="margin-bottom:1rem;">
            <div style="font-size:0.78rem; font-weight:500; color:var(--paces-text-muted); margin-bottom:6px;">Current Gallery Photos (Check to remove):</div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
              @foreach($gallery as $gIdx => $gPath)
                <div style="background:#ffffff; padding:6px; border-radius:8px; border:1px solid var(--paces-border); text-align:center; width:110px;">
                  <img src="{{ str_starts_with($gPath, 'http') ? $gPath : asset($gPath) }}" alt="Photo {{ $gIdx + 1 }}" style="width:98px; height:65px; object-fit:cover; border-radius:6px; margin-bottom:4px;">
                  <label style="font-size:0.75rem; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                    <input type="checkbox" name="remove_photos[]" value="{{ $gPath }}" style="accent-color:#ef4444;">
                    <span>Remove</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <div>
          <label class="form-label-paces">Upload Additional Gallery Photos</label>
          <input type="file" name="service_photos[]" multiple accept="image/*" class="form-control-paces">
          <span class="form-help-paces">You can upload up to 5 photos total for this service to showcase actual completed work.</span>
          <div style="display:flex; align-items:flex-start; gap:8px; margin-top:10px; padding:8px 12px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; font-size:0.8rem; color:#1e40af;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <div>
              <strong>সুপারিশকৃত গ্যালারি সাইজ (Recommended):</strong> ৮০০ × ৬০০ পিক্সেল (৪:৩ বা ১৬:৯ রেশিও)।<br>
              <span style="color:#2563eb; font-size:0.75rem;">💡 যেকোনো রেজোলিউশনের ইমেজ আপলোড করতে পারেন (কোনো সাইজ চেক নেই)। সার্ভিস ডিটেইলস পেজের ফটো গ্যালারিতে এটি স্বয়ংক্রিয়ভাবে ফিক্সড সাইজ থাম্বনেইল ও প্রিভিউ হিসেবে সুন্দরভাবে দেখাবে।</span>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Service Title *</label>
        <input type="text" name="title" value="{{ old('title', $service->title) }}" class="form-control-paces" required>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Subtitle / Tagline</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $service->subtitle) }}" class="form-control-paces" placeholder="e.g. Certified Mechanical & Engineering Maintenance">
      </div>

      <div style="margin-bottom:1.25rem;">
        <label class="form-label-paces">Detailed Description *</label>
        <textarea name="description" class="form-control-paces" rows="5" required>{{ old('description', $service->description) }}</textarea>
      </div>

      <div style="margin-bottom:1.5rem;">
        <label class="form-label-paces">
          Scope / Key Deliverables (One item per line)
        </label>
        <textarea name="scope_included" class="form-control-paces" rows="5" placeholder="Full diagnostic inspection&#10;Emergency team dispatch&#10;Written service report">{{ is_array($service->scope_included) ? implode("\n", $service->scope_included) : '' }}</textarea>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px;">
        <a href="{{ route('admin.web.services') }}" class="btn-paces btn-paces-outline">
          Cancel
        </a>
        <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.75rem 2rem;">
          Save Service Changes
        </button>
      </div>
    </form>
  </div>
</x-layouts.admin>
