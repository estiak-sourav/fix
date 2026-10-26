<x-layouts.admin title="Edit Admin Staff & Permissions | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="margin-bottom:6px;">
        <a href="{{ route('admin.staff.index') }}" style="color:var(--paces-primary); font-size:0.85rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
          ← Back to Staff Directory
        </a>
      </div>
      <h1 class="page-header-title">Edit Admin Staff: {{ $staff->name }}</h1>
      <p class="page-header-subtitle">Update account identity, toggle account activation, and customize modular service permissions</p>
    </div>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#166534; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:14px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-size:0.875rem; display:flex; align-items:flex-start; justify-content:space-between;">
      <div>
        <div style="font-weight:500; margin-bottom:6px;">Please fix the following validation errors:</div>
        <ul style="padding-left:20px; margin:0;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Account Details Card -->
    <div class="card-paces" style="margin-bottom:1.5rem;">
      <div class="card-header-paces">
        <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Account Identity & Access Settings
        </h2>
      </div>

      <div class="card-body-paces" style="padding:1.5rem;">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
          <div>
            <label class="form-label-paces" for="name" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
              Full Name <span style="color:#ef4444;">*</span>
            </label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control-paces" 
              value="{{ old('name', $staff->name) }}" 
              required
            >
          </div>

          <div>
            <label class="form-label-paces" for="email" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
              Email Address (Login Username) <span style="color:#ef4444;">*</span>
            </label>
            <input 
              type="email" 
              name="email" 
              id="email" 
              class="form-control-paces" 
              value="{{ old('email', $staff->email) }}" 
              required
            >
          </div>

          <div>
            <label class="form-label-paces" for="phone" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
              Phone Number
            </label>
            <input 
              type="text" 
              name="phone" 
              id="phone" 
              class="form-control-paces" 
              value="{{ old('phone', $staff->phone) }}" 
              placeholder="+880 1700-000000"
            >
          </div>

          <div>
            <label class="form-label-paces" for="role" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
              Administrative Role <span style="color:#ef4444;">*</span>
            </label>
            <select name="role" id="role" class="form-control-paces" required onchange="handleRoleChange(this.value)">
              <option value="admin" {{ old('role', $staff->role) === 'admin' ? 'selected' : '' }}>Admin Staff (Permission-governed)</option>
              <option value="operations_manager" {{ old('role', $staff->role) === 'operations_manager' ? 'selected' : '' }}>Operations Manager</option>
              <option value="super_admin" {{ old('role', $staff->role) === 'super_admin' ? 'selected' : '' }}>★ Super Administrator (Full Bypass Access)</option>
            </select>
          </div>
        </div>

        <div style="border-top:1px solid var(--paces-border); margin:1.5rem 0; padding-top:1.5rem;">
          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem; align-items:start;">
            <div>
              <label class="form-label-paces" for="password" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Set New Password (Optional)
              </label>
              <input 
                type="text" 
                name="password" 
                id="password" 
                class="form-control-paces" 
                placeholder="Leave blank to keep existing password"
              >
              <small style="color:var(--paces-text-muted); font-size:0.78rem; display:block; margin-top:4px;">
                Enter a new password only if you wish to overwrite the staff user's current password.
              </small>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px; margin-top:6px;">
              <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input 
                  type="checkbox" 
                  name="must_change_password" 
                  id="must_change_password" 
                  value="1" 
                  {{ old('must_change_password', $staff->must_change_password ? '1' : '0') == '1' ? 'checked' : '' }} 
                  style="width:18px; height:18px; accent-color:#3e60d5;"
                >
                <span style="font-size:0.875rem; font-weight:500; color:var(--paces-text);">
                  Force password update on next login
                </span>
              </label>

              <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input 
                  type="checkbox" 
                  name="is_active" 
                  id="is_active" 
                  value="1" 
                  {{ old('is_active', $staff->is_active ? '1' : '0') == '1' ? 'checked' : '' }} 
                  style="width:18px; height:18px; accent-color:#16a34a;"
                >
                <span style="font-size:0.875rem; font-weight:500; color:var(--paces-text);">
                  Account is Active (Allow login)
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Permissions Matrix Card -->
    <div class="card-paces" id="permissionsContainer">
      <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
          <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Service & Feature Access Permissions
          </h2>
          <p style="margin:4px 0 0; font-size:0.82rem; color:var(--paces-text-muted);">
            Manage specific access checkboxes for each service. Select View Only or Edit / Manage per service.
          </p>
        </div>

        <!-- Quick selection helpers -->
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="grantAllFullAccess()">
            ✓ Grant Full Access (All)
          </button>
          <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="grantAllViewOnly()">
            👁️ Grant View-Only (All)
          </button>
          <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" onclick="clearAllPermissions()" style="color:#dc2626;">
            ✕ Clear All
          </button>
        </div>
      </div>

      <!-- Super Admin Notice Banner -->
      <div id="superAdminBanner" style="display:{{ $staff->isSuperAdmin() ? 'block' : 'none' }}; background:#eff6ff; border-bottom:1px solid #bfdbfe; padding:12px 20px; font-size:0.85rem; color:#1d4ed8; font-weight:500;">
        ★ <strong>Super Administrator Role:</strong> Super Admins bypass individual permission rules and have full unrestricted access to all modules automatically.
      </div>

      <div style="overflow-x:auto;">
        <table class="table-paces">
          <thead>
            <tr>
              <th style="width:38%;">Feature / Service Module</th>
              <th style="width:25%; text-align:center;">View Only</th>
              <th style="width:25%; text-align:center;">Edit / Manage</th>
              <th style="width:12%; text-align:right;">Quick Toggle</th>
            </tr>
          </thead>
          <tbody>
            @php
              $currentPerms = old('permissions', $staffPermissions);
            @endphp
            @foreach($modules as $moduleKey => $module)
              @php
                $viewKey = "{$moduleKey}.view";
                $editKey = "{$moduleKey}.edit";
                $hasEdit = isset($module['permissions'][$editKey]);
                $isViewChecked = in_array($viewKey, $currentPerms, true) || in_array($editKey, $currentPerms, true);
                $isEditChecked = in_array($editKey, $currentPerms, true);
              @endphp
              <tr data-module="{{ $moduleKey }}">
                <td>
                  <div style="font-weight:500; color:var(--paces-text); font-size:0.9rem;">
                    {{ $module['name'] }}
                  </div>
                  <div style="font-size:0.78rem; color:var(--paces-text-muted); line-height:1.4;">
                    {{ $module['description'] }}
                  </div>
                </td>

                <!-- View Only Checkbox -->
                <td style="text-align:center; vertical-align:middle;">
                  <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; font-size:0.85rem; color:var(--paces-text); font-weight:500;">
                    <input 
                      type="checkbox" 
                      name="permissions[]" 
                      value="{{ $viewKey }}" 
                      class="perm-checkbox perm-view" 
                      data-module="{{ $moduleKey }}"
                      {{ $isViewChecked ? 'checked' : '' }}
                      onchange="onViewCheckboxChange('{{ $moduleKey }}')"
                      style="width:18px; height:18px; accent-color:#3e60d5; cursor:pointer;"
                    >
                    <span>View Access</span>
                  </label>
                </td>

                <!-- Edit / Manage Checkbox -->
                <td style="text-align:center; vertical-align:middle;">
                  @if($hasEdit)
                    <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; font-size:0.85rem; color:#1d4ed8; font-weight:500;">
                      <input 
                        type="checkbox" 
                        name="permissions[]" 
                        value="{{ $editKey }}" 
                        class="perm-checkbox perm-edit" 
                        data-module="{{ $moduleKey }}"
                        {{ $isEditChecked ? 'checked' : '' }}
                        onchange="onEditCheckboxChange('{{ $moduleKey }}')"
                        style="width:18px; height:18px; accent-color:#3e60d5; cursor:pointer;"
                      >
                      <span>Edit & Manage</span>
                    </label>
                  @else
                    <span style="font-size:0.75rem; color:var(--paces-text-muted); font-style:italic;">
                      (View-only module)
                    </span>
                  @endif
                </td>

                <!-- Quick row toggle -->
                <td style="text-align:right; vertical-align:middle;">
                  <div style="display:inline-flex; gap:4px;">
                    <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:2px 6px; font-size:0.72rem;" onclick="setRowPermission('{{ $moduleKey }}', 'all')">
                      All
                    </button>
                    <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:2px 6px; font-size:0.72rem;" onclick="setRowPermission('{{ $moduleKey }}', 'view')">
                      View
                    </button>
                    <button type="button" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:2px 6px; font-size:0.72rem; color:#dc2626;" onclick="setRowPermission('{{ $moduleKey }}', 'none')">
                      None
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="padding:1.25rem 1.5rem; background:var(--paces-bg-subtle); border-top:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div style="font-size:0.82rem; color:var(--paces-text-muted);">
          Changes to permissions take effect immediately upon saving.
        </div>
        <div style="display:flex; gap:10px;">
          <a href="{{ route('admin.staff.index') }}" class="btn-paces btn-paces-outline">Cancel</a>
          <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Save Staff Changes</span>
          </button>
        </div>
      </div>
    </div>
  </form>

  <script>
    function handleRoleChange(role) {
      const banner = document.getElementById('superAdminBanner');
      if (role === 'super_admin') {
        banner.style.display = 'block';
      } else {
        banner.style.display = 'none';
      }
    }

    function onEditCheckboxChange(module) {
      const editBox = document.querySelector(`.perm-edit[data-module="${module}"]`);
      const viewBox = document.querySelector(`.perm-view[data-module="${module}"]`);
      if (editBox && editBox.checked && viewBox) {
        viewBox.checked = true;
      }
    }

    function onViewCheckboxChange(module) {
      const editBox = document.querySelector(`.perm-edit[data-module="${module}"]`);
      const viewBox = document.querySelector(`.perm-view[data-module="${module}"]`);
      if (viewBox && !viewBox.checked && editBox) {
        editBox.checked = false;
      }
    }

    function setRowPermission(module, type) {
      const viewBox = document.querySelector(`.perm-view[data-module="${module}"]`);
      const editBox = document.querySelector(`.perm-edit[data-module="${module}"]`);

      if (type === 'all') {
        if (viewBox) viewBox.checked = true;
        if (editBox) editBox.checked = true;
      } else if (type === 'view') {
        if (viewBox) viewBox.checked = true;
        if (editBox) editBox.checked = false;
      } else if (type === 'none') {
        if (viewBox) viewBox.checked = false;
        if (editBox) editBox.checked = false;
      }
    }

    function grantAllFullAccess() {
      document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
    }

    function grantAllViewOnly() {
      document.querySelectorAll('.perm-view').forEach(cb => cb.checked = true);
      document.querySelectorAll('.perm-edit').forEach(cb => cb.checked = false);
    }

    function clearAllPermissions() {
      document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
    }

    document.addEventListener('DOMContentLoaded', () => {
      const roleSelect = document.getElementById('role');
      if (roleSelect) handleRoleChange(roleSelect.value);
    });
  </script>
</x-layouts.admin>
