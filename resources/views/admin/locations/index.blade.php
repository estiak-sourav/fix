<x-layouts.admin title="Service Coverage & Locations | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Service Coverage & Locations</h1>
      <p class="page-header-subtitle">Control which districts FIX operates in, manage thanas/areas, and define live client booking & vendor coverage zones</p>
    </div>
  </div>

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

  <!-- Metrics Overview Cards -->
  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:1.5rem;">
    <div class="card-paces" style="padding:16px 20px; margin-bottom:0;">
      <span style="font-size:0.75rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Active Operating Districts</span>
      <div style="font-size:1.75rem; font-weight:800; color:#047857; margin-top:4px;">{{ $activeDistrictsCount }} <span style="font-size:0.9rem; font-weight:500; color:var(--paces-text-muted);">/ {{ $totalDistrictsCount }}</span></div>
    </div>
    <div class="card-paces" style="padding:16px 20px; margin-bottom:0;">
      <span style="font-size:0.75rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Nationwide Thanas & Areas</span>
      <div style="font-size:1.75rem; font-weight:800; color:#1e293b; margin-top:4px;">{{ $totalThanasCount }}</div>
    </div>
    <div class="card-paces" style="padding:16px 20px; margin-bottom:0;">
      <span style="font-size:0.75rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase; letter-spacing:0.04em;">Divisions Represented</span>
      <div style="font-size:1.75rem; font-weight:800; color:#2563eb; margin-top:4px;">{{ $divisions->count() }}</div>
    </div>
  </div>

  <!-- Search & Division Filter -->
  <div class="card-paces" style="margin-bottom:1.5rem; padding:16px 20px;">
    <form method="GET" action="{{ route('admin.locations.index') }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; justify-content:space-between;">
      <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center; flex:1; min-width:280px;">
        <select name="division_id" class="form-control-paces" style="max-width:200px;" onchange="this.form.submit()">
          <option value="">All Divisions ({{ $divisions->count() }})</option>
          @foreach($divisions as $div)
            <option value="{{ $div->id }}" {{ (string)$selectedDivisionId === (string)$div->id ? 'selected' : '' }}>
              {{ $div->name }} ({{ $div->active_districts_count }}/{{ $div->districts_count }})
            </option>
          @endforeach
        </select>

        <div style="position:relative; flex:1; min-width:200px; max-width:340px;">
          <input type="text" name="q" value="{{ $search }}" placeholder="Search district or thana..." class="form-control-paces" style="width:100%; padding-left:34px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>

        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Search</button>
        @if($selectedDivisionId || $search)
          <a href="{{ route('admin.locations.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <!-- District Cards Grid -->
  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(360px, 1fr)); gap:16px;">
    @forelse($districts as $district)
      <div class="card-paces" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between; border-left:4px solid {{ $district->is_active ? '#10b981' : '#cbd5e1' }};">
        <div>
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
            <div>
              <div style="display:flex; align-items:center; gap:8px;">
                <h3 style="font-size:1.05rem; font-weight:800; color:var(--paces-text-main); margin:0;">
                  {{ $district->name }}
                </h3>
                @if($district->bn_name)
                  <span style="font-size:0.8rem; color:#64748b; font-weight:600;">({{ $district->bn_name }})</span>
                @endif
              </div>
              <span style="font-size:0.75rem; font-weight:700; color:#3b82f6; text-transform:uppercase; letter-spacing:0.04em;">
                {{ $district->division?->name }} Division
              </span>
            </div>

            <!-- District Active Switch -->
            @if(auth('admin')->user()?->hasPermission('locations.edit'))
              <form action="{{ route('admin.locations.districts.toggle', $district) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-paces btn-paces-sm {{ $district->is_active ? 'btn-paces-primary' : 'btn-paces-outline' }}" style="padding:4px 10px; font-size:0.76rem; border-radius:20px; font-weight:700; display:inline-flex; align-items:center; gap:5px;" title="{{ $district->is_active ? 'Click to deactivate this district from service coverage' : 'Click to activate this district for service coverage' }}">
                  <span style="width:7px; height:7px; border-radius:50%; background:{{ $district->is_active ? '#10b981' : '#94a3b8' }};"></span>
                  <span>{{ $district->is_active ? 'Operating' : 'Disabled' }}</span>
                </button>
              </form>
            @else
              <span class="badge-paces {{ $district->is_active ? 'badge-paces-success' : 'badge-paces-secondary' }}">
                {{ $district->is_active ? 'Operating' : 'Disabled' }}
              </span>
            @endif
          </div>

          <div style="font-size:0.8rem; color:var(--paces-text-muted); margin-bottom:12px;">
            <strong>{{ $district->thanas_count }}</strong> Thanas / Areas registered.
            @if(!$district->is_active)
              <span style="color:#ef4444; font-weight:600; margin-left:4px;">(Not available for client bookings or vendor enlistment)</span>
            @endif
          </div>

          <!-- Quick preview of first 5 thanas -->
          <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; max-height:86px; overflow:hidden;">
            @foreach($district->thanas->take(6) as $th)
              <span style="background:#f8fafc; border:1px solid #e2e8f0; font-size:0.74rem; font-weight:600; padding:2px 8px; border-radius:6px; color:#475569; {{ !$th->is_active ? 'text-decoration:line-through; opacity:0.6;' : '' }}">
                {{ $th->name }}
              </span>
            @endforeach
            @if($district->thanas_count > 6)
              <span style="font-size:0.74rem; color:#64748b; font-weight:700; align-self:center;">+{{ $district->thanas_count - 6 }} more</span>
            @endif
          </div>
        </div>

        <div style="border-top:1px solid var(--paces-border); padding-top:12px; display:flex; justify-content:space-between; align-items:center;">
          <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="openThanasManager({{ $district->id }}, '{{ addslashes($district->name) }}', {{ json_encode($district->thanas) }})" style="font-size:0.78rem; font-weight:700;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            <span>Manage Thanas / Areas ({{ $district->thanas_count }})</span>
          </button>
        </div>
      </div>
    @empty
      <div style="grid-column:1/-1;" class="card-paces">
        <div style="text-align:center; padding:3rem 1rem; color:var(--paces-text-muted);">
          No districts found matching your filters.
        </div>
      </div>
    @endforelse
  </div>

  <!-- Thanas & Areas Modal Manager -->
  <div id="thanasModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:18px; max-width:640px; width:100%; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
      <!-- Modal Header -->
      <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
        <div>
          <h3 id="modalDistrictTitle" style="font-size:1.15rem; font-weight:800; color:#0f172a; margin:0;">District Thanas & Areas</h3>
          <p style="font-size:0.78rem; color:#64748b; margin-top:2px;">Add new thanas, edit names, or toggle active coverage</p>
        </div>
        <button type="button" onclick="closeThanasManager()" style="background:none; border:none; cursor:pointer; color:#64748b; font-size:1.4rem; line-height:1;">&times;</button>
      </div>

      <!-- Add New Thana Section (If permitted) -->
      @if(auth('admin')->user()?->hasPermission('locations.edit'))
        <div style="padding:16px 24px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
          <form id="addThanaForm" action="{{ route('admin.locations.thanas.store') }}" method="POST" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            @csrf
            <input type="hidden" id="addThanaDistrictId" name="district_id" value="">
            <input type="text" name="name" required placeholder="New Thana / Area Name (e.g. Uttara Sector 18)" class="form-control-paces" style="flex:1; min-width:200px; height:38px;">
            <input type="text" name="bn_name" placeholder="Bengali Name (Optional)" class="form-control-paces" style="flex:1; min-width:140px; height:38px;">
            <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="height:38px; padding:0 16px; white-space:nowrap;">
              + Add Thana
            </button>
          </form>
        </div>
      @endif

      <!-- Thanas List -->
      <div style="padding:20px 24px; overflow-y:auto; flex:1;">
        <div id="thanasTableContainer">
          <!-- Populated by JS -->
        </div>
      </div>

      <!-- Modal Footer -->
      <div style="padding:14px 24px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
        <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="closeThanasManager()">Close</button>
      </div>
    </div>
  </div>

  <!-- Rename Thana Modal -->
  <div id="editThanaModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); backdrop-filter:blur(4px); z-index:10000; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:16px; max-width:420px; width:100%; padding:22px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.3);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
        <h4 style="font-size:1.05rem; font-weight:700; color:#0f172a; margin:0;">Edit Thana / Area Name</h4>
        <button type="button" onclick="closeEditThanaModal()" style="background:none; border:none; cursor:pointer; color:#64748b; font-size:1.3rem;">&times;</button>
      </div>
      <form id="editThanaForm" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:12px;">
          <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Thana Name <span style="color:#ef4444;">*</span></label>
          <input type="text" id="editThanaName" name="name" required class="form-control-paces" style="width:100%;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:4px;">Bengali Name</label>
          <input type="text" id="editThanaBnName" name="bn_name" class="form-control-paces" style="width:100%;">
        </div>
        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="closeEditThanaModal()">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Update Thana</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    let currentDistrictThanas = [];
    let currentDistrictId = null;

    function openThanasManager(districtId, districtName, thanas) {
      currentDistrictId = districtId;
      currentDistrictThanas = thanas || [];
      document.getElementById('modalDistrictTitle').textContent = districtName + ' - Thanas & Areas (' + currentDistrictThanas.length + ')';
      document.getElementById('addThanaDistrictId').value = districtId;

      renderThanasList();
      document.getElementById('thanasModal').style.display = 'flex';
    }

    function closeThanasManager() {
      document.getElementById('thanasModal').style.display = 'none';
    }

    function renderThanasList() {
      const container = document.getElementById('thanasTableContainer');
      if (!currentDistrictThanas || currentDistrictThanas.length === 0) {
        container.innerHTML = '<div style="text-align:center; padding:30px; color:#94a3b8;">No thanas registered under this district yet.</div>';
        return;
      }

      let html = '<div style="display:flex; flex-direction:column; gap:8px;">';
      currentDistrictThanas.forEach(th => {
        html += `
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
            <div>
              <strong style="color:#0f172a; font-size:0.9rem;">${escapeHtml(th.name)}</strong>
              ${th.bn_name ? `<span style="font-size:0.78rem; color:#64748b; margin-left:6px;">(${escapeHtml(th.bn_name)})</span>` : ''}
              ${!th.is_active ? `<span class="badge-paces badge-paces-secondary" style="font-size:0.7rem; margin-left:6px;">Inactive</span>` : ''}
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
              @if(auth('admin')->user()?->hasPermission('locations.edit'))
                <!-- Toggle Active -->
                <form action="{{ url('admin/locations/thanas') }}/${th.id}/toggle" method="POST" style="margin:0;">
                  @csrf
                  <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px; font-size:0.74rem;" title="${th.is_active ? 'Deactivate' : 'Activate'}">
                    ${th.is_active ? '<span style="color:#10b981;">Active</span>' : '<span style="color:#94a3b8;">Off</span>'}
                  </button>
                </form>

                <!-- Edit Button -->
                <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px;" title="Edit Thana Name" onclick="openEditThanaModal(${th.id}, '${escapeHtml(th.name)}', '${escapeHtml(th.bn_name || '')}')">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>

                <!-- Delete Button -->
                <form action="{{ url('admin/locations/thanas') }}/${th.id}" method="POST" style="margin:0;" onsubmit="return confirm('Delete thana \\'${escapeHtml(th.name)}\\'?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-paces btn-paces-danger btn-paces-sm" style="padding:4px 8px;" title="Delete Thana">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </form>
              @endif
            </div>
          </div>
        `;
      });
      html += '</div>';
      container.innerHTML = html;
    }

    function openEditThanaModal(id, name, bnName) {
      const form = document.getElementById('editThanaForm');
      form.action = "{{ url('admin/locations/thanas') }}/" + id;
      document.getElementById('editThanaName').value = name;
      document.getElementById('editThanaBnName').value = bnName || '';
      document.getElementById('editThanaModal').style.display = 'flex';
    }

    function closeEditThanaModal() {
      document.getElementById('editThanaModal').style.display = 'none';
    }

    function escapeHtml(text) {
      if (!text) return '';
      return String(text).replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
      });
    }
  </script>
</x-layouts.admin>

