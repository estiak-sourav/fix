<x-layouts.admin title="Client Testimonials & Reviews | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Content</h1>
      <p class="page-header-subtitle">Manage customer reviews, trust ratings, and homepage testimonials</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <a href="/" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddTestimonialModal()" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>+ Add Testimonial</span>
      </button>
    </div>
  </div>

  <x-admin.web-content-tabs active="testimonials" />

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

  <!-- Testimonials List Table Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <div>
        <h3 class="card-title-paces" style="margin:0;">
          Client Testimonials List ({{ $testimonials->total() }})
        </h3>
        <p style="margin:3px 0 0; font-size:0.82rem; color:var(--paces-text-muted);">
          All published client endorsements with client profile photos and ratings
        </p>
      </div>
      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddTestimonialModal()" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>+ Add Testimonial</span>
      </button>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces">
        <thead>
          <tr>
            <th style="width:280px;">Client Profile</th>
            <th style="width:130px;">Rating</th>
            <th>Review Quote</th>
            <th style="width:160px;">Status</th>
            <th style="width:110px; text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($testimonials as $t)
            @php
              $testimonialData = [
                'id' => $t->id,
                'client_name' => $t->client_name,
                'client_title' => $t->client_title,
                'company_name' => $t->company_name,
                'avatar_url' => $t->avatar_url,
                'has_avatar' => !empty($t->avatar_path),
                'rating' => $t->rating,
                'quote' => $t->quote,
                'is_featured' => (bool)$t->is_featured,
                'is_active' => (bool)$t->is_active,
                'update_url' => route('admin.web.testimonials.update', $t),
                'delete_url' => route('admin.web.testimonials.destroy', $t),
              ];
            @endphp
            <tr>
              <td>
                <div style="display:flex; align-items:center; gap:12px;">
                  @if($t->avatar_url)
                    <img src="{{ $t->avatar_url }}" alt="{{ $t->client_name }}" style="width:42px; height:42px; border-radius:50%; object-fit:cover; border:1px solid #cbd5e1; flex-shrink:0;">
                  @else
                    <div style="width:42px; height:42px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); display:flex; align-items:center; justify-content:center; font-weight:600; font-size:1rem; border:1px solid var(--paces-primary-border); flex-shrink:0;">
                      {{ strtoupper(substr($t->client_name, 0, 1)) }}
                    </div>
                  @endif
                  <div style="min-width:0;">
                    <div style="font-weight:600; color:var(--paces-text-dark); font-size:0.92rem;">
                      {{ $t->client_name }}
                    </div>
                    <div style="font-size:0.78rem; color:var(--paces-text-muted); margin-top:1px;">
                      {{ $t->client_title ?? 'Client' }}
                      @if($t->company_name)
                        • {{ $t->company_name }}
                      @endif
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div style="font-size:0.85rem; line-height:1.2;">
                  <div>
                    @for($i = 0; $i < $t->rating; $i++)
                      ⭐
                    @endfor
                  </div>
                  <span style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500;">
                    {{ $t->rating }} / 5 Stars
                  </span>
                </div>
              </td>
              <td>
                <p style="margin:0; font-size:0.85rem; color:#334155; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                  "{{ $t->quote }}"
                </p>
              </td>
              <td>
                <div style="display:flex; flex-direction:column; gap:4px; align-items:flex-start;">
                  @if($t->is_featured)
                    <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.72rem;">★ Featured Home</span>
                  @endif
                  @if($t->is_active)
                    <span class="badge-pill-paces bg-success-subtle" style="font-size:0.72rem;">● Active</span>
                  @else
                    <span class="badge-pill-paces bg-danger-subtle" style="font-size:0.72rem;">○ Inactive</span>
                  @endif
                </div>
              </td>
              <td style="text-align:right;">
                <button type="button" 
                        class="btn-paces btn-paces-primary btn-paces-sm" 
                        onclick="openEditTestimonialModal({{ json_encode($testimonialData) }})"
                        style="display:inline-flex; align-items:center; gap:5px; padding:0.35rem 0.75rem;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  <span>Edit</span>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center; padding:2.5rem; color:var(--paces-text-muted);">
                <div style="font-size:1.8rem; margin-bottom:8px;">💬</div>
                <div style="font-weight:500;">No testimonials added yet.</div>
                <p style="margin:4px 0 12px; font-size:0.82rem;">Add your first client review to show on the website.</p>
                <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddTestimonialModal()">
                  + Add Testimonial
                </button>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($testimonials->hasPages())
      <div style="margin-top:1.25rem;">
        {{ $testimonials->links() }}
      </div>
    @endif
  </div>

  <!-- ============================================== -->
  <!-- 1. ADD TESTIMONIAL MODAL                       -->
  <!-- ============================================== -->
  <div id="addTestimonialModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:620px;">
      <div class="modal-header-paces">
        <h3 class="modal-title-paces">+ Add New Testimonial</h3>
        <button type="button" class="modal-close-btn" onclick="closeAddTestimonialModal()">✕</button>
      </div>

      <form action="{{ route('admin.web.testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body-paces">
          <!-- Client Photo Upload -->
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:10px; padding:1rem; margin-bottom:1.25rem;">
            <label class="form-label-paces" style="font-weight:600; margin-bottom:4px;">
              Client Photo / Avatar (Upload from device)
            </label>
            <div style="display:flex; align-items:center; gap:16px; margin-top:8px;">
              <div id="addAvatarPreviewContainer" style="width:56px; height:56px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; overflow:hidden; border:2px solid #cbd5e1; flex-shrink:0;">
                <svg id="addAvatarPlaceholder" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <img id="addAvatarImg" src="" alt="Preview" style="display:none; width:100%; height:100%; object-fit:cover;">
              </div>
              <div style="flex:1;">
                <input type="file" name="avatar" accept="image/*" class="form-control-paces" style="background:#ffffff;" onchange="previewAvatar(this, 'addAvatarImg', 'addAvatarPlaceholder')">
                <span class="form-help-paces">Supported: JPG, PNG, WEBP (Max: 5MB). Photo will be displayed beside the client name.</span>
              </div>
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Client Name *</label>
            <input type="text" name="client_name" required placeholder="e.g. Mahbubul Alam" class="form-control-paces">
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Job Title / Designation</label>
              <input type="text" name="client_title" placeholder="e.g. Head of Facilities" class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Company / Organization</label>
              <input type="text" name="company_name" placeholder="e.g. Apex Holdings Ltd." class="form-control-paces">
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Star Rating *</label>
            <select name="rating" class="form-control-paces" required>
              <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars - Excellent)</option>
              <option value="4">⭐⭐⭐⭐ (4 Stars - Good)</option>
              <option value="3">⭐⭐⭐ (3 Stars - Average)</option>
              <option value="2">⭐⭐ (2 Stars)</option>
              <option value="1">⭐ (1 Star)</option>
            </select>
          </div>

          <div style="margin-bottom:1.25rem;">
            <label class="form-label-paces">Review Quote *</label>
            <textarea name="quote" required rows="5" placeholder="Enter what the client said about FIX facility engineering services..." class="form-control-paces"></textarea>
          </div>

          <div style="background:#f8fafc; border-radius:8px; padding:10px 14px; border:1px solid var(--paces-border); display:flex; gap:20px;">
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_featured" value="1" checked style="accent-color:var(--paces-primary);">
              Featured on Homepage
            </label>
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--paces-primary);">
              Active / Visible
            </label>
          </div>
        </div>

        <div class="modal-footer-paces">
          <button type="button" class="btn-paces btn-paces-outline" onclick="closeAddTestimonialModal()">
            Cancel
          </button>
          <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
            <span>Save Testimonial</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================== -->
  <!-- 2. EDIT TESTIMONIAL MODAL (With Edit/Delete)   -->
  <!-- ============================================== -->
  <div id="editTestimonialModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:620px;">
      <div class="modal-header-paces">
        <h3 class="modal-title-paces" id="editModalClientTitle">Edit Testimonial</h3>
        <button type="button" class="modal-close-btn" onclick="closeEditTestimonialModal()">✕</button>
      </div>

      <form id="editTestimonialForm" method="POST" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-body-paces">
          <!-- Client Photo Upload & Management -->
          <div style="background:#f8fafc; border:1px solid var(--paces-border); border-radius:10px; padding:1rem; margin-bottom:1.25rem;">
            <label class="form-label-paces" style="font-weight:600; margin-bottom:4px;">
              Client Photo / Avatar
            </label>
            <div style="display:flex; align-items:center; gap:16px; margin-top:8px;">
              <div id="editAvatarPreviewContainer" style="width:56px; height:56px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; overflow:hidden; border:2px solid #cbd5e1; flex-shrink:0;">
                <svg id="editAvatarPlaceholder" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <img id="editAvatarImg" src="" alt="Preview" style="display:none; width:100%; height:100%; object-fit:cover;">
              </div>
              <div style="flex:1;">
                <input type="file" name="avatar" accept="image/*" class="form-control-paces" style="background:#ffffff;" onchange="previewAvatar(this, 'editAvatarImg', 'editAvatarPlaceholder')">
                <div id="editRemoveAvatarBox" style="margin-top:6px; display:none;">
                  <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.8rem; color:#dc2626; cursor:pointer; font-weight:500;">
                    <input type="checkbox" name="remove_avatar" value="1" style="accent-color:#dc2626;">
                    <span>Remove current photo</span>
                  </label>
                </div>
                <span class="form-help-paces">Upload a new file to replace the existing photo.</span>
              </div>
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Client Name *</label>
            <input type="text" name="client_name" id="editClientName" required class="form-control-paces">
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Job Title / Designation</label>
              <input type="text" name="client_title" id="editClientTitle" class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Company / Organization</label>
              <input type="text" name="company_name" id="editCompanyName" class="form-control-paces">
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Star Rating *</label>
            <select name="rating" id="editRating" class="form-control-paces" required>
              <option value="5">⭐⭐⭐⭐⭐ (5 Stars - Excellent)</option>
              <option value="4">⭐⭐⭐⭐ (4 Stars - Good)</option>
              <option value="3">⭐⭐⭐ (3 Stars - Average)</option>
              <option value="2">⭐⭐ (2 Stars)</option>
              <option value="1">⭐ (1 Star)</option>
            </select>
          </div>

          <div style="margin-bottom:1.25rem;">
            <label class="form-label-paces">Review Quote *</label>
            <textarea name="quote" id="editQuote" required rows="5" class="form-control-paces"></textarea>
          </div>

          <div style="background:#f8fafc; border-radius:8px; padding:10px 14px; border:1px solid var(--paces-border); display:flex; gap:20px;">
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_featured" id="editIsFeatured" value="1" style="accent-color:var(--paces-primary);">
              Featured on Homepage
            </label>
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" id="editIsActive" value="1" style="accent-color:var(--paces-primary);">
              Active / Visible
            </label>
          </div>
        </div>

        <div class="modal-footer-paces" style="justify-content:space-between;">
          <button type="button" class="btn-paces btn-paces-danger" onclick="triggerDeleteTestimonial()" style="display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            <span>Delete Review</span>
          </button>

          <div style="display:flex; gap:10px;">
            <button type="button" class="btn-paces btn-paces-outline" onclick="closeEditTestimonialModal()">
              Cancel
            </button>
            <button type="submit" class="btn-paces btn-paces-primary">
              Save Changes
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Separate Hidden Delete Form -->
  <form id="deleteTestimonialForm" method="POST" action="" style="display:none;">
    @csrf
    @method('DELETE')
  </form>

  <script>
    function openAddTestimonialModal() {
      const modal = document.getElementById('addTestimonialModal');
      if (modal) modal.style.display = 'flex';
    }

    function closeAddTestimonialModal() {
      const modal = document.getElementById('addTestimonialModal');
      if (modal) modal.style.display = 'none';
    }

    let activeDeleteUrl = '';

    function openEditTestimonialModal(data) {
      const form = document.getElementById('editTestimonialForm');
      form.action = data.update_url;
      activeDeleteUrl = data.delete_url;

      document.getElementById('editModalClientTitle').textContent = 'Edit: ' + data.client_name;
      document.getElementById('editClientName').value = data.client_name || '';
      document.getElementById('editClientTitle').value = data.client_title || '';
      document.getElementById('editCompanyName').value = data.company_name || '';
      document.getElementById('editRating').value = data.rating;
      document.getElementById('editQuote').value = data.quote || '';
      document.getElementById('editIsFeatured').checked = Boolean(data.is_featured);
      document.getElementById('editIsActive').checked = Boolean(data.is_active);

      const previewImg = document.getElementById('editAvatarImg');
      const placeholder = document.getElementById('editAvatarPlaceholder');
      const removeBox = document.getElementById('editRemoveAvatarBox');

      if (data.avatar_url) {
        previewImg.src = data.avatar_url;
        previewImg.style.display = 'block';
        placeholder.style.display = 'none';
        removeBox.style.display = 'block';
      } else {
        previewImg.src = '';
        previewImg.style.display = 'none';
        placeholder.style.display = 'block';
        removeBox.style.display = 'none';
      }

      document.getElementById('editTestimonialModal').style.display = 'flex';
    }

    function closeEditTestimonialModal() {
      document.getElementById('editTestimonialModal').style.display = 'none';
    }

    function triggerDeleteTestimonial() {
      if (!confirm('Are you sure you want to permanently delete this testimonial?')) {
        return;
      }
      const delForm = document.getElementById('deleteTestimonialForm');
      delForm.action = activeDeleteUrl;
      delForm.submit();
    }

    function previewAvatar(input, imgId, placeholderId) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById(imgId);
          const ph = document.getElementById(placeholderId);
          if (img) {
            img.src = e.target.result;
            img.style.display = 'block';
          }
          if (ph) {
            ph.style.display = 'none';
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    // Close on backdrop click
    window.addEventListener('click', function(e) {
      const addModal = document.getElementById('addTestimonialModal');
      const editModal = document.getElementById('editTestimonialModal');
      if (e.target === addModal) closeAddTestimonialModal();
      if (e.target === editModal) closeEditTestimonialModal();
    });
  </script>
</x-layouts.admin>
