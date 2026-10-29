<x-layouts.admin title="Client Users | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Client Users Directory</h1>
    </div>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#166534; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Metrics Grid -->
  <div class="metrics-grid-paces">
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Registered Clients</span>
        <div class="metric-icon-badge bg-primary-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
      </div>
      <div class="metric-value">{{ $stats['total_clients'] }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Active Accounts</span>
        <div class="metric-icon-badge bg-success-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-success);">{{ $stats['active_clients'] }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Corporate Clients</span>
        <div class="metric-icon-badge bg-info-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-info);">{{ $stats['corporate_clients'] }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Client Bookings</span>
        <div class="metric-icon-badge bg-warning-subtle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        </div>
      </div>
      <div class="metric-value" style="color:var(--paces-warning);">{{ $stats['total_bookings'] }}</div>
    </div>
  </div>

  <!-- User Directory Table Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:14px;">
      <!-- Filter status tabs -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.users.index') }}" class="btn-paces {{ (!request()->has('status') && !request('property_type')) ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All Clients ({{ $stats['total_clients'] }})
        </a>
        <a href="{{ route('admin.users.index', ['status' => '1']) }}" class="btn-paces {{ request('status') === '1' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Active ({{ $stats['active_clients'] }})
        </a>
        <a href="{{ route('admin.users.index', ['status' => '0']) }}" class="btn-paces {{ request('status') === '0' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Deactivated ({{ $stats['deactivated_clients'] }})
        </a>
      </div>

      <!-- Search Input Form -->
      <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex; gap:8px;">
        @if(request('status') !== null)
          <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        @if(request('property_type'))
          <input type="hidden" name="property_type" value="{{ request('property_type') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" class="topbar-search-input" placeholder="Search client name, email, phone..." style="width:260px;">
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Search</button>
      </form>
    </div>

    @if($users->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">👥</div>
        <h4 style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Clients Found</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">No client user accounts match your search or filter criteria.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Client</th>
              <th>Contact</th>
              <th>Property / Area</th>
              <th>Orders</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
              <tr>
                <td>
                  <div style="display:flex; align-items:center; gap:10px;">
                    @if($u->avatar_url)
                      <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" style="width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                    @else
                      <div style="width:34px; height:34px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:600; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <div style="font-weight:500; color:var(--paces-text-dark);">
                        <a href="{{ route('admin.users.show', $u) }}" style="color:inherit; text-decoration:none;">{{ $u->name }}</a>
                      </div>
                      <div style="font-size:0.75rem; color:var(--paces-text-muted);">Joined {{ $u->created_at->format('M d, Y') }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <div>
                    <a href="mailto:{{ $u->email }}" style="color:var(--paces-primary); text-decoration:none; font-weight:500; font-size:0.88rem;">{{ $u->email }}</a>
                  </div>
                  @if($u->phone)
                    <div style="font-size:0.78rem; color:var(--paces-text-muted); margin-top:2px;">
                      {{ $u->phone }}
                    </div>
                  @endif
                </td>
                <td>
                  <div style="font-weight:500; font-size:0.88rem; color:var(--paces-text-dark);">
                    {{ $u->company_name ?? ($u->property_type ?? 'Individual Property') }}
                  </div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">
                    {{ $u->area ?? 'Dhaka' }}{{ $u->district ? ', '.$u->district : '' }}
                  </div>
                </td>
                <td>
                  <a href="{{ route('admin.bookings.index', ['search' => $u->email]) }}" class="badge-pill-paces bg-primary-subtle" title="View bookings by this client" style="text-decoration:none;">
                    {{ $u->bookings_count }} Orders
                  </a>
                </td>
                <td>
                  @if($u->is_active)
                    <span class="badge-pill-paces bg-success-subtle">
                      <span style="width:6px; height:6px; border-radius:50%; background:#16a34a;"></span>
                      Active
                    </span>
                  @else
                    <span class="badge-pill-paces bg-danger-subtle">
                      <span style="width:6px; height:6px; border-radius:50%; background:#dc2626;"></span>
                      Disabled
                    </span>
                  @endif
                </td>
                <td style="text-align:right;">
                  <div style="display:inline-flex; gap:6px; align-items:center;">
                    <!-- View Profile -->
                    <a href="{{ route('admin.users.show', $u) }}" class="btn-paces btn-paces-outline btn-paces-sm">
                      View
                    </a>

                    <!-- Toggle Status Form -->
                    @if(auth('admin')->user()?->hasPermission('users.edit'))
                      <form action="{{ route('admin.users.toggle-status', $u) }}" method="POST" data-confirm="Are you sure you want to {{ $u->is_active ? 'deactivate' : 'activate' }} client {{ $u->name }}?" data-confirm-title="{{ $u->is_active ? 'Deactivate' : 'Activate' }} Client Account?" data-confirm-btn="{{ $u->is_active ? 'Deactivate' : 'Activate' }}" style="margin:0;">
                        @csrf
                        @if($u->is_active)
                          <button type="submit" class="btn-paces btn-paces-sm" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;" title="Deactivate Account">
                            Disable
                          </button>
                        @else
                          <button type="submit" class="btn-paces btn-paces-sm" style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;" title="Activate Account">
                            Enable
                          </button>
                        @endif
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top:1.5rem;">
        {{ $users->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
