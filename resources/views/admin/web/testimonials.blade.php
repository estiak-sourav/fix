<x-layouts.admin title="Client Testimonials & Reviews | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">⭐ Client Testimonials & Social Proof</h1>
      <p class="page-header-subtitle">Manage corporate recommendations, ratings, and customer endorsements featured on the live website</p>
    </div>
    <div style="display:flex; gap:10px;">
      <a href="{{ route('admin.web.index') }}" class="btn-paces btn-paces-primary" style="background:#475569; border-color:#475569;">
        ← Back to Web Control
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 1fr 2fr; gap:1.5rem; align-items:flex-start;">
    
    <!-- Add New Testimonial Card -->
    <div class="card-paces">
      <h3 class="card-title-paces" style="margin-bottom:1rem;">Add Testimonial</h3>
      <form action="{{ route('admin.web.testimonials.store') }}" method="POST">
        @csrf

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces">Client Name *</label>
          <input type="text" name="client_name" required placeholder="e.g. Mahbubul Alam" class="form-control-paces">
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:1rem;">
          <div>
            <label class="form-label-paces">Job Title / Role</label>
            <input type="text" name="client_title" placeholder="e.g. Head of Facilities" class="form-control-paces">
          </div>
          <div>
            <label class="form-label-paces">Company / Org</label>
            <input type="text" name="company_name" placeholder="e.g. Apex Holdings" class="form-control-paces">
          </div>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces">Star Rating (1 - 5) *</label>
          <select name="rating" class="form-control-paces" required>
            <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars - Excellent)</option>
            <option value="4">⭐⭐⭐⭐ (4 Stars - Good)</option>
            <option value="3">⭐⭐⭐ (3 Stars - Average)</option>
          </select>
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces">Review Quote *</label>
          <textarea name="quote" required rows="4" placeholder="Client review quote..." class="form-control-paces"></textarea>
        </div>

        <div style="background:#f8fafc; border-radius:8px; padding:10px 12px; margin-bottom:1.25rem; border:1px solid var(--paces-border); display:flex; gap:16px;">
          <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:600; cursor:pointer;">
            <input type="checkbox" name="is_featured" value="1" checked>
            Featured on Home
          </label>
          <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:600; cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" checked>
            Active
          </label>
        </div>

        <button type="submit" class="btn-paces btn-paces-primary" style="width:100%;">
          Add Testimonial
        </button>
      </form>
    </div>

    <!-- Existing Testimonials List -->
    <div class="card-paces">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
        <h3 class="card-title-paces">Client Endorsements ({{ $testimonials->total() }})</h3>
      </div>

      <div style="display:flex; flex-direction:column; gap:14px;">
        @forelse($testimonials as $t)
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:10px; padding:1.25rem;">
            <form action="{{ route('admin.web.testimonials.update', $t) }}" method="POST">
              @csrf
              @method('PUT')

              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <div style="font-size:0.95rem; font-weight:700; color:var(--paces-text-dark);">
                  {{ $t->client_name }}
                  <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">
                    — {{ $t->client_title ?? 'Client' }} ({{ $t->company_name ?? 'Corporate Partner' }})
                  </span>
                </div>
                <div>
                  @for($i = 0; $i < $t->rating; $i++)
                    ⭐
                  @endfor
                </div>
              </div>

              <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:8px;">
                <input type="text" name="client_name" value="{{ $t->client_name }}" class="form-control-paces" style="background:#ffffff;" required>
                <input type="text" name="company_name" value="{{ $t->company_name }}" class="form-control-paces" style="background:#ffffff;" placeholder="Company Name">
              </div>

              <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:8px;">
                <input type="text" name="client_title" value="{{ $t->client_title }}" class="form-control-paces" style="background:#ffffff;" placeholder="Title / Role">
                <select name="rating" class="form-control-paces" style="background:#ffffff;">
                  <option value="5" {{ $t->rating == 5 ? 'selected' : '' }}>5 Stars</option>
                  <option value="4" {{ $t->rating == 4 ? 'selected' : '' }}>4 Stars</option>
                  <option value="3" {{ $t->rating == 3 ? 'selected' : '' }}>3 Stars</option>
                </select>
              </div>

              <div style="margin-bottom:8px;">
                <textarea name="quote" rows="2" class="form-control-paces" style="background:#ffffff;" required>{{ $t->quote }}</textarea>
              </div>

              <div style="display:flex; justify-content:space-between; align-items:center; margin-top:8px;">
                <div style="display:flex; gap:14px;">
                  <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; font-weight:600; cursor:pointer;">
                    <input type="checkbox" name="is_featured" value="1" {{ $t->is_featured ? 'checked' : '' }}>
                    Featured
                  </label>
                  <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; font-weight:600; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $t->is_active ? 'checked' : '' }}>
                    Active
                  </label>
                </div>
                <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.35rem 0.85rem; font-size:0.78rem;">
                  Save Changes
                </button>
              </div>
            </form>

            <div style="display:flex; justify-content:flex-end; margin-top:6px;">
              <form action="{{ route('admin.web.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete this testimonial?');" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none; border:none; color:#ef4444; font-size:0.78rem; font-weight:600; cursor:pointer;">
                  🗑️ Delete Review
                </button>
              </form>
            </div>
          </div>
        @empty
          <p style="color:var(--paces-text-muted); font-size:0.85rem;">No testimonials found.</p>
        @endforelse
      </div>

      <div style="margin-top:1.25rem;">
        {{ $testimonials->links() }}
      </div>
    </div>

  </div>
</x-layouts.admin>
