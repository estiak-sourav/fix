<x-layouts.admin title="Create New Admin Staff | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="margin-bottom:6px;">
        <a href="{{ route('admin.staff.index') }}" style="color:var(--paces-primary); font-size:0.85rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
          ← Back to Staff Directory
        </a>
      </div>
      <h1 class="page-header-title">Create New Admin Staff</h1>
      <p class="page-header-subtitle">Provision account credentials with default password and configure granular feature permission checkboxes</p>
    </div>
  </div>

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:14px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-size:0.875rem;">
      <div style="font-weight:500; margin-bottom:6px;">Please fix the following validation errors:</div>
      <ul style="padding-left:20px; margin:0;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.staff.store') }}" method="POST">
    @csrf

    <!-- Account Information Card -->
    <div class="card-paces" style="margin-bottom:1.5rem;">
      <div class="card-header-paces">
        <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Staff Identity & Login Credentials
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
              value="{{ old('name') }}" 
              placeholder="e.g. Tanvir Ahmed" 
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
              value="{{ old('email') }}" 
              placeholder="e.g. staff@fix.com.bd" 
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
              value="{{ old('phone') }}" 
              placeholder="+880 1700-000000"
            >
          </div>

          <div>
            <label class="form-label-paces" for="role" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
              Administrative Role <span style="color:#ef4444;">*</span>
            </label>
            <select name="role" id="role" class="form-control-paces" required onchange="handleRoleChange(this.value)">
              <option value="admin" {{ old('role', 'admin') === 'admin' ? 'selected' : '' }}>Admin Staff (Permission-governed)</option>
              <option value="operations_manager" {{ old('role') === 'operations_manager' ? 'selected' : '' }}>Operations Manager</option>
              <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>★ Super Administrator (Full Bypass Access)</option>
            </select>
          </div>
        </div>

        <div style="border-top:1px solid var(--paces-border); margin:1.5rem 0; padding-top:1.5rem;">
          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem; align-items:start;">
            <div>
              <label class="form-label-paces" for="password" style="display:block; margin-bottom:6px; font-weight:500; font-size:0.875rem;">
                Default Temporary Password <span style="color:#ef4444;">*</span>
              </label>
              <div style="position:relative;">
                <input 
                  type="text" 
                  name="password" 
                  id="password" 
                  class="form-control-paces" 
                  value="{{ old('password') }}" 
                  placeholder="Min 8 characters" 
                  required
                >
              </div>
              <small style="color:var(--paces-text-muted); font-size:0.78rem; display:block; margin-top:4px;">
                Initial password provided to staff. Staff will be prompted to change it upon first login.
              </small>
            </div>

            <div>
              <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer; padding:10px 14px; background:#fffbeb; border:1px solid #fde68a; border-radius:var(--paces-radius-sm); margin-top:18px;">
                <input 
                  type="checkbox" 
                  name="must_change_password" 
                  id="must_change_password" 
                  value="1" 
                  {{ old('must_change_password', '1') == '1' ? 'checked' : '' }} 
                  style="width:18px; height:18px; accent-color:#3e60d5; margin-top:2px;"
                >
                <div>
                  <div style="font-weight:500; font-size:0.875rem; color:#92400e;">
                    Must update password on first login
                  </div>
                  <div style="font-size:0.78rem; color:#b45309; line-height:1.4;">
                    When enabled, the user cannot access any CMS features until they choose a new secure personal password.
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modular Feature Permissions Matrix Card -->
    <div class="card-paces" id="permissionsContainer">
      <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
          <h2 style="font-size:1.05rem; font-weight:500; margin:0; color:var(--paces-text); display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Service & Feature Access Permissions
          </h2>
          <p style="margin:4px 0 0; font-size:0.82rem; color:var(--paces-text-muted);">
            Check specific access permissions for each operational service. Select View Only or Edit / Manage per service.
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

      <!-- Super Admin Notice Banner (hidden by default unless super_admin selected) -->
      <div id="superAdminBanner" style="display:none; background:#eff6ff; border-bottom:1px solid #bfdbfe; padding:12px 20px; font-size:0.85rem; color:#1d4ed8; font-weight:500;">
        ★ <strong>Super Administrator Selected:</strong> Users with the Super Administrator role bypass individual permission checks and automatically possess unrestricted full access across all present and future modules.
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
            @foreach($modules as $moduleKey => $module)
              @php
                $viewKey = "{$moduleKey}.view";
                $editKey = "{$moduleKey}.edit";
                $hasEdit = isset($module['permissions'][$editKey]);
                $oldPerms = old('permissions', []);
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
                      {{ in_array($viewKey, $oldPerms, true) ? 'checked' : '' }}
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
                        {{ in_array($editKey, $oldPerms, true) ? 'checked' : '' }}
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
          Staff can only view or manage features where explicit permissions have been checked above.
        </div>
        <div style="display:flex; gap:10px;">
          <a href="{{ route('admin.staff.index') }}" class="btn-paces btn-paces-outline">Cancel</a>
          <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Create Admin Staff Account</span>
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

    // When edit is checked, auto-check view
    function onEditCheckboxChange(module) {
      const editBox = document.querySelector(`.perm-edit[data-module="${module}"]`);
      const viewBox = document.querySelector(`.perm-view[data-module="${module}"]`);
      if (editBox && editBox.checked && viewBox) {
        viewBox.checked = true;
      }
    }

    // When view is unchecked, auto-uncheck edit
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

    // Run on init in case of old inputs
    document.addEventListener('DOMContentLoaded', () => {
      const roleSelect = document.getElementById('role');
      if (roleSelect) handleRoleChange(roleSelect.value);
    });
  </script>
</x-layouts.admin>
