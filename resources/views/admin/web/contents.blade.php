<x-layouts.admin title="Section Content | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Content</h1>
      <p class="page-header-subtitle">Manage landing page section content, hero slider, and copy</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <a href="/" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
    </div>
  </div>

  <!-- Content Navigation Tabs -->
  <div style="display:flex; gap:8px; border-bottom:1px solid var(--paces-border); margin-bottom:1.5rem; max-width:960px;">
    <a href="{{ route('admin.web.contents') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:600; color:var(--paces-primary); text-decoration:none; border-bottom:2px solid var(--paces-primary); display:flex; align-items:center; gap:6px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
      Section Content
    </a>
    <a href="{{ route('admin.web.faqs') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:500; color:var(--paces-text-muted); text-decoration:none; border-bottom:2px solid transparent; display:flex; align-items:center; gap:6px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
      FAQs Knowledgebase
    </a>
    <a href="{{ route('admin.web.testimonials') }}" style="padding:0.6rem 1.25rem; font-size:0.88rem; font-weight:500; color:var(--paces-text-muted); text-decoration:none; border-bottom:2px solid transparent; display:flex; align-items:center; gap:6px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
      Client Testimonials
    </a>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#16a34a; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:flex-start; justify-content:space-between;">
      <div>
        <div style="font-weight:600; margin-bottom:4px;">Please correct the following errors:</div>
        <ul style="margin:0; padding-left:1.25rem; font-size:0.88rem;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Section Cards Container -->
  <div style="display:flex; flex-direction:column; gap:1.25rem; max-width:960px;">
    @foreach($contents as $content)
      @php
        $sectionTitle = ucwords(str_replace('_', ' ', $content->section_key));
        $slides = $content->meta_data['slides'] ?? ($content->media_path ? [$content->media_path] : []);
      @endphp

      <div class="card-paces" id="section-card-{{ $content->id }}" style="border-left: 4px solid var(--paces-primary);">
        <!-- Card Top Bar: Title & Edit Button -->
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:1rem;">
          <div>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
              <h3 class="card-title-paces" style="margin:0; font-size:1.1rem;">
                {{ $sectionTitle }} Section
              </h3>
              <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem; text-transform:uppercase;">
                {{ $content->section_key }}
              </span>
              @if($content->section_key === 'hero')
                <span class="badge-pill-paces bg-success-subtle" style="font-size:0.75rem;">
                  ✨ Multi-Image Animated Slider
                </span>
              @endif
            </div>
            <span style="font-size:0.78rem; color:var(--paces-text-muted);">
              Key: <code style="color:var(--paces-primary);">site_content:{{ $content->section_key }}</code> • Last Updated: {{ $content->updated_at->diffForHumans() }}
            </span>
          </div>

          <!-- Edit Button (Opens Modal) -->
          <button type="button" 
                  class="btn-paces btn-paces-primary btn-paces-sm" 
                  onclick="openSectionModal({{ $content->id }})"
                  style="display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            <span>Edit Section</span>
          </button>
        </div>

        <!-- Section Content Readout / Summary Preview -->
        <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border);">
          <div style="display:grid; grid-template-columns: 1fr; gap:12px;">
            <div>
              <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Current Title / Headline:</span>
              <div style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
                {!! $content->title !!}
              </div>
            </div>

            @if($content->subtitle)
              <div>
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Subtitle / Description:</span>
                <div style="font-size:0.875rem; color:#475569; margin-top:2px; line-height:1.5;">
                  {{ $content->subtitle }}
                </div>
              </div>
            @endif

            @if($content->badge)
              <div>
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Section Badge Tag:</span>
                <div style="margin-top:3px;">
                  <span class="badge-pill-paces bg-info-subtle">{{ $content->badge }}</span>
                </div>
              </div>
            @endif

            <!-- Hero Slides Thumbnails Preview -->
            @if($content->section_key === 'hero')
              <div style="margin-top:6px; border-top:1px dashed var(--paces-border); padding-top:10px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">
                  Hero Auto-Slider Images ({{ count($slides) }} Slides Uploaded):
                </span>
                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:8px;">
                  @forelse($slides as $idx => $slide)
                    <div style="position:relative; width:120px; height:75px; border-radius:8px; overflow:hidden; border:1px solid var(--paces-border); box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                      <img src="{{ asset($slide) }}" alt="Slide {{ $idx + 1 }}" style="width:100%; height:100%; object-fit:cover;">
                      <span style="position:absolute; bottom:3px; left:4px; background:rgba(0,0,0,0.65); color:#fff; font-size:0.65rem; padding:1px 5px; border-radius:4px; font-weight:500;">Slide {{ $idx + 1 }}</span>
                    </div>
                  @empty
                    <span style="font-size:0.8rem; color:#94a3b8;">No slides uploaded yet.</span>
                  @endforelse
                </div>
              </div>
            @elseif($content->media_path)
              <div style="margin-top:4px;">
                <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Active Media:</span>
                <div style="font-size:0.8rem; color:var(--paces-primary); word-break:break-all; margin-top:2px;">
                  {{ $content->media_path }}
                </div>
              </div>
            @endif

            @if($content->button_text)
              <div style="display:flex; gap:16px; margin-top:4px; flex-wrap:wrap;">
                <div>
                  <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Primary Button:</span>
                  <div style="font-size:0.82rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
                    "{{ $content->button_text }}" → <span style="color:var(--paces-text-muted); font-family:monospace;">{{ $content->button_url }}</span>
                  </div>
                </div>
                @if($content->secondary_button_text)
                  <div>
                    <span style="font-size:0.75rem; font-weight:600; color:var(--paces-text-muted); text-transform:uppercase;">Secondary Button:</span>
                    <div style="font-size:0.82rem; font-weight:500; color:var(--paces-text-dark); margin-top:2px;">
                      "{{ $content->secondary_button_text }}" → <span style="color:var(--paces-text-muted); font-family:monospace;">{{ $content->secondary_button_url }}</span>
                    </div>
                  </div>
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- ============================================== -->
      <!-- EDIT SECTION MODAL FOR SECTION #{{ $content->id }} -->
      <!-- ============================================== -->
      <div id="editSectionModal_{{ $content->id }}" class="modal-backdrop-paces" style="display:none;">
        <div class="modal-dialog-paces" style="max-width:720px;">
          <div class="modal-header-paces">
            <div>
              <h3 class="modal-title-paces">Edit: {{ $sectionTitle }} Section</h3>
              <p style="margin:2px 0 0; font-size:0.78rem; color:var(--paces-text-muted);">
                Identifier: <code style="color:var(--paces-primary);">site_content:{{ $content->section_key }}</code>
              </p>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeSectionModal({{ $content->id }})">✕</button>
          </div>

          <form action="{{ route('admin.web.contents.update', $content) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body-paces">
              <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.25rem; margin-bottom:1rem;">
                <div>
                  <label class="form-label-paces">Section Title / Headline *</label>
                  <input type="text" name="title" value="{{ $content->title }}" class="form-control-paces" required>
                  <span class="form-help-paces">You can wrap keywords in &lt;span&gt;word&lt;/span&gt; for highlighted accent coloring.</span>
                </div>
                <div>
                  <label class="form-label-paces">Badge / Category Pill</label>
                  <input type="text" name="badge" value="{{ $content->badge }}" class="form-control-paces" placeholder="e.g. OPERATIONS COMMAND">
                </div>
              </div>

              <div style="margin-bottom:1rem;">
                <label class="form-label-paces">Subtitle / Detailed Description</label>
                <textarea name="subtitle" class="form-control-paces" rows="3">{{ $content->subtitle }}</textarea>
              </div>

              <!-- Hero Section Dedicated Media Uploader -->
              @if($content->section_key === 'hero')
                <div style="background:#eff6ff; border-radius:10px; padding:1.25rem; border:1px solid #bfdbfe; margin-bottom:1.25rem;">
                  <div style="margin-bottom:0.75rem;">
                    <h5 style="font-size:0.92rem; font-weight:600; color:#1e40af; margin:0;">
                      📷 Multiple Hero Slider Images (Auto-slide & Animated on Website)
                    </h5>
                    <p style="font-size:0.78rem; color:#3b82f6; margin:2px 0 0;">
                      Upload local photos from your device. The website smoothly animates and crossfades between these slides automatically.
                    </p>
                  </div>

                  <!-- Existing Slides List with Remove option -->
                  @if(!empty($slides))
                    <div style="margin-bottom:1rem;">
                      <label style="font-size:0.78rem; font-weight:600; color:#1e40af; margin-bottom:6px; display:block;">
                        Current Active Slides (Check to remove):
                      </label>
                      <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        @foreach($slides as $idx => $slide)
                          <div style="background:#ffffff; padding:6px; border-radius:8px; border:1px solid #cbd5e1; display:flex; flex-direction:column; align-items:center; width:120px;">
                            <img src="{{ asset($slide) }}" alt="Slide {{ $idx + 1 }}" style="width:108px; height:68px; object-fit:cover; border-radius:6px; margin-bottom:6px;">
                            <label style="font-size:0.75rem; color:#ef4444; cursor:pointer; display:flex; align-items:center; gap:4px; font-weight:500;">
                              <input type="checkbox" name="remove_slides[]" value="{{ $slide }}" style="accent-color:#ef4444;">
                              <span>Remove</span>
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endif

                  <!-- Upload New Slides Input -->
                  <div>
                    <label class="form-label-paces" style="color:#1e40af;">
                      Upload New Hero Slide Photos (Hold Ctrl/Cmd to select multiple)
                    </label>
                    <input type="file" name="hero_images[]" multiple accept="image/*" class="form-control-paces" style="background:#ffffff;">
                    <span class="form-help-paces" style="color:#3b82f6;">All images are stored locally and served directly without external URLs.</span>
                  </div>

                  <!-- Hero Floating Card & Trust Badges Controls -->
                  <div style="margin-top:1rem; padding-top:1rem; border-top:1px dashed #bfdbfe; display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                    <div>
                      <label class="form-label-paces" style="color:#1e40af;">Floating Card Title</label>
                      <input type="text" name="floating_badge_title" value="{{ $content->meta_data['floating_badge_title'] ?? 'Quality Guaranteed' }}" class="form-control-paces" style="background:#ffffff;">
                    </div>
                    <div>
                      <label class="form-label-paces" style="color:#1e40af;">Floating Card Text</label>
                      <input type="text" name="floating_badge_text" value="{{ $content->meta_data['floating_badge_text'] ?? '100% Verified Technical Teams & Rigorous Safety' }}" class="form-control-paces" style="background:#ffffff;">
                    </div>
                  </div>

                  <div style="margin-top:1rem;">
                    <label class="form-label-paces" style="color:#1e40af;">Trust Badges (4 Key Value Highlights)</label>
                    @php
                      $badges = $content->meta_data['trust_badges'] ?? [
                        'Verified & Certified Providers',
                        'Quick Service Request & Dispatch',
                        'Multiple Building Solutions',
                        '24/7 Dedicated Operations Support'
                      ];
                    @endphp
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                      @foreach($badges as $bIdx => $bText)
                        <input type="text" name="trust_badges[]" value="{{ $bText }}" class="form-control-paces" style="background:#ffffff;">
                      @endforeach
                    </div>
                  </div>
                </div>
              @elseif($content->section_key === 'video_portfolio')
                <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.25rem;">
                  <label class="form-label-paces">YouTube Video Embed URL</label>
                  <input type="text" name="media_path" value="{{ $content->media_path }}" class="form-control-paces" placeholder="https://www.youtube.com/embed/...">
                  <span class="form-help-paces">Enter your YouTube embed URL (e.g. https://www.youtube.com/embed/LXb3EKWsInQ).</span>
                </div>
              @else
                <!-- Optional Section Image Upload -->
                <div style="background:#f8fafc; border-radius:10px; padding:1.25rem; border:1px solid var(--paces-border); margin-bottom:1.25rem;">
                  <label class="form-label-paces">Upload Section Image / Banner (Optional)</label>
                  <input type="file" name="section_image" accept="image/*" class="form-control-paces">
                  <span class="form-help-paces">Upload a local image from your device if this section displays an illustration or photo.</span>
                </div>
              @endif

              <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
                <div>
                  <label class="form-label-paces">Primary Button Label</label>
                  <input type="text" name="button_text" value="{{ $content->button_text }}" class="form-control-paces" placeholder="e.g. Book a Service">
                </div>
                <div>
                  <label class="form-label-paces">Primary Button Destination (URL/Hash)</label>
                  <input type="text" name="button_url" value="{{ $content->button_url }}" class="form-control-paces" placeholder="e.g. /booking or #services">
                </div>
              </div>

              <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:0.5rem;">
                <div>
                  <label class="form-label-paces">Secondary Button Label</label>
                  <input type="text" name="secondary_button_text" value="{{ $content->secondary_button_text }}" class="form-control-paces" placeholder="e.g. Contact Us">
                </div>
                <div>
                  <label class="form-label-paces">Secondary Button Destination (URL/Hash)</label>
                  <input type="text" name="secondary_button_url" value="{{ $content->secondary_button_url }}" class="form-control-paces" placeholder="e.g. /contact">
                </div>
              </div>
            </div>

            <div class="modal-footer-paces">
              <button type="button" class="btn-paces btn-paces-outline" onclick="closeSectionModal({{ $content->id }})">
                Cancel
              </button>
              <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
                <span>Save Section & Log Changes</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
              </button>
            </div>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  <script>
    function openSectionModal(id) {
      const modal = document.getElementById('editSectionModal_' + id);
      if (modal) modal.style.display = 'flex';
    }

    function closeSectionModal(id) {
      const modal = document.getElementById('editSectionModal_' + id);
      if (modal) modal.style.display = 'none';
    }

    // Close on backdrop click
    window.addEventListener('click', function(e) {
      if (e.target.classList.contains('modal-backdrop-paces')) {
        e.target.style.display = 'none';
      }
    });
  </script>
</x-layouts.admin>
