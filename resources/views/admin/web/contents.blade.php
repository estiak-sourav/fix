<x-layouts.admin title="Homepage Sections & Content Copy | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">📝 Homepage Sections & Dynamic Content</h1>
      <p class="page-header-subtitle">Control hero marketing banners, video portfolio embeds, and why choose FIX value propositions</p>
    </div>
    <a href="{{ route('admin.web.index') }}" class="btn-paces btn-paces-primary" style="background:#475569; border-color:#475569;">
      ← Back to Web Control
    </a>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  <div style="display:grid; grid-template-columns: 1fr; gap:1.5rem; max-width:860px;">
    @foreach($contents as $content)
      <div class="card-paces">
        <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.75rem; margin-bottom:1.25rem;">
          <div>
            <h3 class="card-title-paces" style="text-transform:capitalize;">
              {{ str_replace('_', ' ', $content->section_key) }} Section
            </h3>
            <span style="font-size:0.75rem; color:var(--paces-text-muted); font-family:monospace;">
              Key: site_content:{{ $content->section_key }}
            </span>
          </div>
        </div>

        <form action="{{ route('admin.web.contents.update', $content) }}" method="POST">
          @csrf

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Section Heading / Title *</label>
              <input type="text" name="title" value="{{ $content->title }}" class="form-control-paces" required>
            </div>
            <div>
              <label class="form-label-paces">Badge / Pill Text</label>
              <input type="text" name="badge" value="{{ $content->badge }}" class="form-control-paces" placeholder="e.g. OPERATIONS COMMAND">
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Subtitle / Description</label>
            <textarea name="subtitle" class="form-control-paces" rows="3">{{ $content->subtitle }}</textarea>
          </div>

          @if($content->section_key === 'video_portfolio')
            <div style="margin-bottom:1rem;">
              <label class="form-label-paces">YouTube / Video Embed URL</label>
              <input type="text" name="media_path" value="{{ $content->media_path }}" class="form-control-paces" placeholder="https://www.youtube.com/embed/...">
              <span class="form-help-paces">Embed URL for the live operations showcase video.</span>
            </div>
          @endif

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
            <div>
              <label class="form-label-paces">Primary Button Label</label>
              <input type="text" name="button_text" value="{{ $content->button_text }}" class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Primary Button Destination (URL/Hash)</label>
              <input type="text" name="button_url" value="{{ $content->button_url }}" class="form-control-paces">
            </div>
          </div>

          <button type="submit" class="btn-paces btn-paces-primary">
            Save Section Content
          </button>
        </form>
      </div>
    @endforeach
  </div>
</x-layouts.admin>
