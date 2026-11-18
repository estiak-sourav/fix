<x-layouts.admin title="Services | Web Content | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Content</h1>
      <p class="page-header-subtitle">Manage standalone facility services, descriptions, photo galleries, and publication statuses</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      @if(auth('admin')->user()?->hasPermission('services.edit'))
        <a href="{{ route('admin.web.services.create') }}" class="btn-paces btn-paces-primary btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          <span>Add New Service</span>
        </a>
      @endif
      <a href="{{ \App\Models\SiteSetting::mainSiteUrl('#services') }}" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
    </div>
  </div>

  <x-admin.web-content-tabs active="services" />

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

  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:12px;">
      <div>
        <h3 class="card-title-paces">All Services ({{ $services->total() }})</h3>
        <span style="font-size:0.78rem; color:var(--paces-text-muted);">
          Drag rows or use ▲ ▼ arrows to change display order on the storefront.
        </span>
      </div>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces" id="servicesSortableTable">
        <thead>
          <tr>
            <th style="width:90px; text-align:center;">Order ⇅</th>
            <th>Photo</th>
            <th>Service Title</th>
            <th>Description</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="servicesSortableBody">
          @foreach($services as $service)
            <tr class="sortable-service-row" data-id="{{ $service->id }}" draggable="true" style="transition:background 0.15s ease;">
              <td style="text-align:center; vertical-align:middle; width:90px;">
                <div style="display:inline-flex; align-items:center; gap:5px; justify-content:center;">
                  <span class="drag-handle" style="cursor:grab; color:#94a3b8; padding:4px 2px; user-select:none; display:inline-flex;" title="Drag to reorder position on website">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="5" r="1.5"></circle><circle cx="9" cy="12" r="1.5"></circle><circle cx="9" cy="19" r="1.5"></circle><circle cx="15" cy="5" r="1.5"></circle><circle cx="15" cy="12" r="1.5"></circle><circle cx="15" cy="19" r="1.5"></circle></svg>
                  </span>
                  <span class="service-order-badge badge-pill-paces" style="background:#f1f5f9; color:#334155; font-weight:700; font-size:0.75rem; padding:2px 7px; border:1px solid #cbd5e1;">
                    #{{ $service->order ?? $loop->iteration }}
                  </span>
                  <div style="display:inline-flex; flex-direction:column; gap:1px;">
                    <button type="button" class="btn-move-row" onclick="moveServiceRow(this, -1)" title="Move up" style="background:none; border:none; padding:0; line-height:1; color:#94a3b8; cursor:pointer; font-size:0.65rem;">▲</button>
                    <button type="button" class="btn-move-row" onclick="moveServiceRow(this, 1)" title="Move down" style="background:none; border:none; padding:0; line-height:1; color:#94a3b8; cursor:pointer; font-size:0.65rem;">▼</button>
                  </div>
                </div>
              </td>
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
                @if(auth('admin')->user()?->hasPermission('services.edit'))
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
                    <form action="{{ route('admin.web.services.destroy', $service) }}" method="POST" data-confirm="Are you sure you want to permanently delete service '{{ $service->title }}'?" data-confirm-title="Delete Service?" data-confirm-btn="Delete Service" style="margin:0; display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-paces btn-paces-sm" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;" title="Delete Service">
                        Delete
                      </button>
                    </form>
                  </div>
                @else
                  <span style="font-size:0.75rem; color:var(--paces-text-muted);">View Only</span>
                @endif
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

  <style>
    .sortable-service-row.dragging {
      opacity: 0.45 !important;
      background: #eef2ff !important;
      outline: 2px dashed var(--paces-primary, #3e60d5);
    }
    .sortable-service-row.drag-over-top {
      border-top: 3px solid var(--paces-primary, #3e60d5) !important;
    }
    .sortable-service-row.drag-over-bottom {
      border-bottom: 3px solid var(--paces-primary, #3e60d5) !important;
    }
    .drag-handle:active {
      cursor: grabbing !important;
    }
    .btn-move-row:hover {
      color: var(--paces-primary, #3e60d5) !important;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tbody = document.getElementById('servicesSortableBody');
      if (!tbody) return;

      let draggedRow = null;

      function updateBadges() {
        const rows = tbody.querySelectorAll('tr[data-id]');
        rows.forEach((row, idx) => {
          const badge = row.querySelector('.service-order-badge');
          if (badge) badge.textContent = `#${idx + 1}`;
        });
      }

      function saveOrder() {
        const rows = tbody.querySelectorAll('tr[data-id]');
        const order = Array.from(rows).map(r => parseInt(r.dataset.id));

        if (typeof showAdminToast === 'function') {
          showAdminToast('Updating Order...', 'Saving new service display sequence on website...');
        }

        fetch("{{ route('admin.web.services.reorder') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json'
          },
          body: JSON.stringify({ order: order })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (typeof showAdminToast === 'function') {
              showAdminToast('Sequence Saved ✓', 'Service packages order updated. Live storefront reflects this sequence.');
            }
          } else {
            alert(data.message || 'Failed to update order');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Network error while saving order.');
        });
      }

      // Drag & Drop Handlers
      tbody.addEventListener('dragstart', function(e) {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        draggedRow = row;
        row.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', row.dataset.id);
      });

      tbody.addEventListener('dragend', function(e) {
        if (draggedRow) {
          draggedRow.classList.remove('dragging');
        }
        tbody.querySelectorAll('tr[data-id]').forEach(r => {
          r.classList.remove('drag-over-top', 'drag-over-bottom');
        });
        draggedRow = null;
        updateBadges();
        saveOrder();
      });

      tbody.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        const targetRow = e.target.closest('tr[data-id]');
        if (!targetRow || targetRow === draggedRow) return;

        const rect = targetRow.getBoundingClientRect();
        const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;

        tbody.querySelectorAll('tr[data-id]').forEach(r => {
          r.classList.remove('drag-over-top', 'drag-over-bottom');
        });

        if (next) {
          targetRow.classList.add('drag-over-bottom');
          targetRow.after(draggedRow);
        } else {
          targetRow.classList.add('drag-over-top');
          targetRow.before(draggedRow);
        }
        updateBadges();
      });

      // Move Up / Down Button Handlers
      window.moveServiceRow = function(btn, direction) {
        const row = btn.closest('tr[data-id]');
        if (!row) return;

        if (direction === -1) {
          const prev = row.previousElementSibling;
          if (prev && prev.matches('tr[data-id]')) {
            prev.before(row);
            updateBadges();
            saveOrder();
          }
        } else if (direction === 1) {
          const next = row.nextElementSibling;
          if (next && next.matches('tr[data-id]')) {
            next.after(row);
            updateBadges();
            saveOrder();
          }
        }
      };
    });
  </script>
</x-layouts.admin>
