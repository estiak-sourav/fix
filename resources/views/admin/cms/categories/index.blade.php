<x-layouts.admin title="Service Categories | Web Content | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Service Categories</h1>
      <p class="page-header-subtitle">Manage facility service classifications, trade domains, ordering, and publication status</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      @if(auth('admin')->user()?->hasPermission('services.edit'))
        <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openCreateCategoryModal()" style="display:inline-flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          <span>Add Category</span>
        </button>
      @endif
      <a href="{{ \App\Models\SiteSetting::mainSiteUrl('#services') }}" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
    </div>
  </div>

  <x-admin.web-content-tabs active="categories" />

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between; gap:8px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500;">
      <ul style="margin:0; padding-left:1.25rem;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:12px;">
      <div>
        <h3 class="card-title-paces">All Service Categories ({{ $categories->count() }})</h3>
        <p class="card-subtitle-paces" style="margin-top:2px;">Dynamic trade domains used in vendor applications, customer bookings, and catalog groupings</p>
      </div>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces">
        <thead>
          <tr>
            <th style="width:60px;">Order</th>
            <th>Category Name & Slug</th>
            <th>Description</th>
            <th style="text-align:center;">Linked Services</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr>
              <td>
                <span style="font-weight:700; color:var(--paces-text-muted); font-size:0.85rem;">#{{ $category->order }}</span>
              </td>
              <td>
                <div style="font-weight:700; color:var(--paces-text-main); font-size:0.95rem;">
                  {{ $category->name }}
                </div>
                <div style="font-size:0.75rem; color:var(--paces-text-muted); font-family:monospace; margin-top:2px;">
                  /{{ $category->slug }}
                </div>
              </td>
              <td>
                <div style="font-size:0.82rem; color:var(--paces-text-muted); max-width:320px; line-height:1.4;">
                  {{ $category->description ?: 'No description provided' }}
                </div>
              </td>
              <td style="text-align:center;">
                <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-weight:700; font-size:0.8rem; padding:3px 10px; border-radius:20px;">
                  {{ $category->services_count }} services
                </span>
              </td>
              <td>
                @if($category->is_active)
                  <span class="badge-paces badge-paces-success" style="background:#ecfdf5; color:#047857; font-weight:700; padding:3px 10px; border-radius:20px; font-size:0.78rem;">Active</span>
                @else
                  <span class="badge-paces badge-paces-secondary" style="background:#f1f5f9; color:#64748b; font-weight:700; padding:3px 10px; border-radius:20px; font-size:0.78rem;">Inactive</span>
                @endif
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex; align-items:center; gap:6px;">
                  @if(auth('admin')->user()?->hasPermission('services.edit'))
                    <!-- Toggle Status -->
                    <form action="{{ route('admin.web.categories.toggle-status', $category) }}" method="POST" style="margin:0;">
                      @csrf
                      <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px;" title="{{ $category->is_active ? 'Deactivate Category' : 'Activate Category' }}">
                        @if($category->is_active)
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                        @else
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        @endif
                      </button>
                    </form>

                    <!-- Edit Button -->
                    <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px;" title="Edit Category"
                      onclick="openEditCategoryModal({{ json_encode($category) }})">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.web.categories.destroy', $category) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete category \'{{ $category->name }}\'? Any associated services will remain but be unassigned.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-paces btn-paces-danger btn-paces-sm" style="padding:4px 8px;" title="Delete Category">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--paces-text-muted);">
                No service categories created yet. Click "Add Category" above to create one.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Create Category Modal -->
  <div id="createCategoryModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:16px; max-width:480px; width:100%; padding:24px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0;">Add Service Category</h3>
        <button type="button" onclick="closeCreateCategoryModal()" style="background:none; border:none; cursor:pointer; color:#64748b; font-size:1.3rem;">&times;</button>
      </div>

      <form action="{{ route('admin.web.categories.store') }}" method="POST">
        @csrf
        <div style="margin-bottom:14px;">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Category Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="name" required class="form-control-paces" placeholder="e.g. Electrical & Power Systems" style="width:100%;">
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Description</label>
          <textarea name="description" rows="3" class="form-control-paces" placeholder="Brief scope of services under this category" style="width:100%; resize:vertical;"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Display Order</label>
            <input type="number" name="order" value="{{ $categories->count() + 1 }}" min="0" class="form-control-paces" style="width:100%;">
          </div>
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Status</label>
            <select name="is_active" class="form-control-paces" style="width:100%;">
              <option value="1">Active / Published</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" class="btn-paces btn-paces-outline" onclick="closeCreateCategoryModal()">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary">Create Category</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Category Modal -->
  <div id="editCategoryModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:16px; max-width:480px; width:100%; padding:24px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0;">Edit Service Category</h3>
        <button type="button" onclick="closeEditCategoryModal()" style="background:none; border:none; cursor:pointer; color:#64748b; font-size:1.3rem;">&times;</button>
      </div>

      <form id="editCategoryForm" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:14px;">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Category Name <span style="color:#ef4444;">*</span></label>
          <input type="text" id="editCategoryName" name="name" required class="form-control-paces" style="width:100%;">
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Description</label>
          <textarea id="editCategoryDescription" name="description" rows="3" class="form-control-paces" style="width:100%; resize:vertical;"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Display Order</label>
            <input type="number" id="editCategoryOrder" name="order" min="0" class="form-control-paces" style="width:100%;">
          </div>
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:5px;">Status</label>
            <select id="editCategoryActive" name="is_active" class="form-control-paces" style="width:100%;">
              <option value="1">Active / Published</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" class="btn-paces btn-paces-outline" onclick="closeEditCategoryModal()">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openCreateCategoryModal() {
      document.getElementById('createCategoryModal').style.display = 'flex';
    }
    function closeCreateCategoryModal() {
      document.getElementById('createCategoryModal').style.display = 'none';
    }
    function openEditCategoryModal(cat) {
      const form = document.getElementById('editCategoryForm');
      form.action = "{{ url('admin/web-control/categories') }}/" + cat.id;
      document.getElementById('editCategoryName').value = cat.name || '';
      document.getElementById('editCategoryDescription').value = cat.description || '';
      document.getElementById('editCategoryOrder').value = cat.order || 0;
      document.getElementById('editCategoryActive').value = cat.is_active ? '1' : '0';
      document.getElementById('editCategoryModal').style.display = 'flex';
    }
    function closeEditCategoryModal() {
      document.getElementById('editCategoryModal').style.display = 'none';
    }
  </script>
</x-layouts.admin>

