<x-layouts.admin title="Admin Staff & Permissions Management | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <h1 class="page-header-title">Admin Staff & Role Permissions</h1>
      <p class="page-header-subtitle">Manage administrative team members, assign modular feature permissions, and enforce secure first-login credential rotation</p>
    </div>
    @if(auth('admin')->user()?->hasPermission('staff_management.edit'))
      <a href="{{ route('admin.staff.create') }}" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>Add New Admin Staff</span>
      </a>
    @endif
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; gap:8px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
      {{ session('error') }}
    </div>
  @endif

  <!-- Metrics Grid -->
  <div class="metrics-grid-paces">
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Total Staff</span>
        <div class="metric-icon-badge bg-primary-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
      </div>
      <div class="metric-value">{{ $stats['total_staff'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-text-muted);">
        Registered operations accounts
      </div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Active Staff</span>
        <div class="metric-icon-badge bg-success-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-success);">{{ $stats['active_staff'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-success); font-weight:500;">
        Authorized login sessions
      </div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Super Admins</span>
        <div class="metric-icon-badge bg-info-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-info);">{{ $stats['super_admins'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-text-muted);">
        Full system bypass privileges
      </div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Pending First-Login</span>
        <div class="metric-icon-badge bg-warning-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-warning);">{{ $stats['pending_password_change'] }}</div>
      <div class="metric-subtext" style="color:var(--paces-text-muted);">
        Default password rotation required
      </div>
    </div>
  </div>

  <!-- Staff Directory Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:14px;">
      <!-- Role Filters -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.staff.index') }}" class="btn-paces {{ (!request()->has('role') && !request()->has('status')) ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All Staff ({{ $stats['total_staff'] }})
        </a>
        <a href="{{ route('admin.staff.index', ['role' => 'super_admin']) }}" class="btn-paces {{ request('role') === 'super_admin' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Super Admins
        </a>
        <a href="{{ route('admin.staff.index', ['role' => 'admin']) }}" class="btn-paces {{ request('role') === 'admin' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Standard Admins
        </a>
        <a href="{{ route('admin.staff.index', ['role' => 'operations_manager']) }}" class="btn-paces {{ request('role') === 'operations_manager' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Operations Managers
        </a>
        <a href="{{ route('admin.staff.index', ['status' => '1']) }}" class="btn-paces {{ request('status') === '1' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Active
        </a>
        <a href="{{ route('admin.staff.index', ['status' => '0']) }}" class="btn-paces {{ request('status') === '0' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Deactivated
        </a>
      </div>

      <!-- Search Form -->
      <form action="{{ route('admin.staff.index') }}" method="GET" style="display:flex; gap:8px;">
        @if(request('role'))
          <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
        @if(request('status') !== null)
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input 
          type="text" 
          name="search" 
          placeholder="Search staff by name or email..." 
          value="{{ request('search') }}" 
          class="form-control-paces" 
          style="padding:6px 12px; font-size:0.875rem; width:260px;"
        >
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Search</button>
        @if(request()->hasAny(['search', 'role', 'status']))
          <a href="{{ route('admin.staff.index') }}" class="btn-paces btn-paces-outline btn-paces-sm" title="Clear Filters">Clear</a>
        @endif
      </form>
    </div>

    <div style="overflow-x:auto;">
      <table class="table-paces">
        <thead>
          <tr>
            <th>Staff Member</th>
            <th>Role</th>
            <th>Feature Permissions</th>
            <th>Account & Security</th>
            <th>Created</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($staffMembers as $member)
            <tr>
              <td>
                <div style="display:flex; align-items:center; gap:12px;">
                  <div style="width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg, #3e60d5 0%, #1e293b 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-weight:500; font-size:0.95rem; flex-shrink:0;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                  </div>
                  <div>
                    <div style="font-weight:500; color:var(--paces-text); font-size:0.9rem;">
                      {{ $member->name }}
                      @if($member->id === auth('admin')->id())
                        <span style="background:#e0e7ff; color:#3730a3; font-size:0.68rem; padding:2px 6px; border-radius:4px; font-weight:500; margin-left:4px;">You</span>
                      @endif
                    </div>
                    <div style="font-size:0.8rem; color:var(--paces-text-muted);">
                      {{ $member->email }}
                    </div>
                    @if($member->phone)
                      <div style="font-size:0.75rem; color:#94a3b8;">
                        📞 {{ $member->phone }}
                      </div>
                    @endif
                  </div>
                </div>
              </td>

              <td>
                @if($member->role === 'super_admin')
                  <span class="badge-paces" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                    ★ Super Administrator
                  </span>
                @elseif($member->role === 'operations_manager')
                  <span class="badge-paces" style="background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe;">
                    Operations Manager
                  </span>
                @else
                  <span class="badge-paces" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                    Admin Staff
                  </span>
                @endif
              </td>

              <td>
                @if($member->isSuperAdmin())
                  <div style="display:inline-flex; align-items:center; gap:6px; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; padding:4px 10px; border-radius:6px; font-size:0.78rem; font-weight:500;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Full Access (All Modules)
                  </div>
                @else
                  @php
                    $perms = $member->permissions ?? [];
                    $editCount = count(array_filter($perms, fn($p) => str_ends_with($p, '.edit')));
                    $viewOnlyCount = count(array_filter($perms, fn($p) => str_ends_with($p, '.view')));
                  @endphp
                  @if(count($perms) > 0)
                    <div style="display:flex; flex-direction:column; gap:4px;">
                      <div style="font-size:0.8rem; color:var(--paces-text); font-weight:500;">
                        {{ count($perms) }} Total Privileges Assigned
                      </div>
                      <div style="display:flex; gap:6px; flex-wrap:wrap;">
                        @if($editCount > 0)
                          <span style="font-size:0.7rem; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:2px 6px; border-radius:4px; font-weight:500;">
                            {{ $editCount }} Edit / Manage
                          </span>
                        @endif
                        @if($viewOnlyCount > 0)
                          <span style="font-size:0.7rem; background:#f8fafc; color:#475569; border:1px solid #cbd5e1; padding:2px 6px; border-radius:4px; font-weight:500;">
                            {{ $viewOnlyCount }} View Only
                          </span>
                        @endif
                      </div>
                    </div>
                  @else
                    <span style="font-size:0.78rem; color:#ef4444; background:#fef2f2; border:1px solid #fecaca; padding:2px 8px; border-radius:4px; font-weight:500;">
                      No Permissions Assigned
                    </span>
                  @endif
                @endif
              </td>

              <td>
                <div style="display:flex; flex-direction:column; gap:4px;">
                  <div>
                    @if($member->is_active)
                      <span class="badge-paces badge-paces-success">Active</span>
                    @else
                      <span class="badge-paces badge-paces-danger">Deactivated</span>
                    @endif
                  </div>
                  <div>
                    @if($member->must_change_password)
                      <span style="display:inline-flex; align-items:center; gap:4px; font-size:0.7rem; background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:2px 6px; border-radius:4px; font-weight:500;" title="User must update password on next login">
                        ⚠️ Must Change Password
                      </span>
                    @else
                      <span style="font-size:0.7rem; color:#10b981; font-weight:500;">
                        ✓ Password Set
                      </span>
                    @endif
                  </div>
                </div>
              </td>

              <td style="font-size:0.8rem; color:var(--paces-text-muted);">
                {{ $member->created_at?->format('M d, Y') ?? 'N/A' }}
              </td>

              <td style="text-align:right;">
                <div style="display:inline-flex; align-items:center; gap:6px;">
                  @if(auth('admin')->user()?->hasPermission('staff_management.edit'))
                    <a href="{{ route('admin.staff.edit', $member) }}" class="btn-paces btn-paces-outline btn-paces-sm" title="Edit Permissions & Profile">
                      Edit
                    </a>

                    <!-- Quick Password Reset to default -->
                    <form action="{{ route('admin.staff.reset-password', $member) }}" method="POST" onsubmit="return confirm('Reset password for {{ $member->name }} to default (password123)? They will be forced to change it on next login.')" style="display:inline;">
                      @csrf
                      <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" title="Reset to default password" style="color:#d97706; border-color:#fde68a;">
                        Reset Pwd
                      </button>
                    </form>

                    <!-- Toggle Status -->
                    @if($member->id !== auth('admin')->id())
                      <form action="{{ route('admin.staff.toggle-status', $member) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="color:{{ $member->is_active ? '#dc2626' : '#16a34a' }};">
                          {{ $member->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                      </form>

                      <!-- Delete -->
                      <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('Permanently delete staff account {{ $member->name }}?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#dc2626; border-color:#fecaca;">
                          Delete
                        </button>
                      </form>
                    @endif
                  @else
                    <span style="font-size:0.75rem; color:var(--paces-text-muted);">View Only</span>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--paces-text-muted);">
                No admin staff found matching current criteria.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($staffMembers->hasPages())
      <div style="padding:1rem 1.5rem; border-top:1px solid var(--paces-border);">
        {{ $staffMembers->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
