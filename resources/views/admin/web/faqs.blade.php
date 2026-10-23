<x-layouts.admin title="FAQs Knowledgebase | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">❓ FAQs Knowledgebase Management</h1>
      <p class="page-header-subtitle">Manage customer questions, answers, and categorical knowledge displayed on the website</p>
    </div>
    <div style="display:flex; gap:10px;">
      <a href="{{ route('admin.web.index') }}" class="btn-paces btn-paces-primary" style="background:#475569; border-color:#475569;">
        ← Back to Web Control
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 1fr 2fr; gap:1.5rem; align-items:flex-start;">
    
    <!-- Add New FAQ Card -->
    <div class="card-paces">
      <h3 class="card-title-paces" style="margin-bottom:1rem;">Add New FAQ</h3>
      <form action="{{ route('admin.web.faqs.store') }}" method="POST">
        @csrf

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces">Category *</label>
          <input type="text" name="category" required placeholder="e.g. General, Booking, AMC" class="form-control-paces" value="General">
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces">Question *</label>
          <input type="text" name="question" required placeholder="e.g. What is the emergency response SLA?" class="form-control-paces">
        </div>

        <div style="margin-bottom:1rem;">
          <label class="form-label-paces">Detailed Answer *</label>
          <textarea name="answer" required rows="4" placeholder="Enter clear explanation for clients..." class="form-control-paces"></textarea>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:1.25rem;">
          <div>
            <label class="form-label-paces">Display Order</label>
            <input type="number" name="order" value="1" class="form-control-paces">
          </div>
          <div style="display:flex; align-items:flex-end; padding-bottom:6px;">
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" checked>
              Active / Visible
            </label>
          </div>
        </div>

        <button type="submit" class="btn-paces btn-paces-primary" style="width:100%;">
          Publish New FAQ
        </button>
      </form>
    </div>

    <!-- Existing FAQs Table -->
    <div class="card-paces">
      <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1rem;">
        <h3 class="card-title-paces">Existing FAQs ({{ $faqs->total() }})</h3>
      </div>

      <div style="display:flex; flex-direction:column; gap:12px;">
        @forelse($faqs as $faq)
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:10px; padding:1.2rem;">
            <form action="{{ route('admin.web.faqs.update', $faq) }}" method="POST">
              @csrf
              @method('PUT')

              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem;">
                  {{ $faq->category }}
                </span>
                <div style="display:flex; align-items:center; gap:12px;">
                  <label style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; font-weight:500; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }}>
                    Active
                  </label>
                  <button type="submit" class="btn-paces btn-paces-primary" style="padding:0.35rem 0.75rem; font-size:0.75rem;">
                    Update
                  </button>
                </div>
              </div>

              <div style="margin-bottom:8px;">
                <input type="hidden" name="category" value="{{ $faq->category }}">
                <input type="hidden" name="order" value="{{ $faq->order }}">
                <input type="text" name="question" value="{{ $faq->question }}" class="form-control-paces" style="font-weight:500; background:#ffffff; font-size:0.92rem; margin-bottom:6px;" required>
                <textarea name="answer" rows="2" class="form-control-paces" style="background:#ffffff; font-size:0.85rem;" required>{{ $faq->answer }}</textarea>
              </div>
            </form>

            <div style="display:flex; justify-content:flex-end; margin-top:6px;">
              <form action="{{ route('admin.web.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none; border:none; color:#ef4444; font-size:0.78rem; font-weight:500; cursor:pointer;">
                  🗑️ Delete FAQ
                </button>
              </form>
            </div>
          </div>
        @empty
          <p style="color:var(--paces-text-muted); font-size:0.85rem;">No FAQs added yet.</p>
        @endforelse
      </div>

      <div style="margin-top:1.25rem;">
        {{ $faqs->links() }}
      </div>
    </div>

  </div>
</x-layouts.admin>
