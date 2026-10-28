<x-layouts.admin title="Partners & Field Crews | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
      <h1 class="page-header-title">Enlisted Partners & Field Crew Network</h1>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between; gap:8px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  <!-- Metrics Grid -->
  <div class="metrics-grid-paces" style="margin-bottom:1.5rem;">
    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Total Applications</span>
        <div class="metric-icon-badge bg-primary-subtle">👷</div>
      </div>
      <div class="metric-value">{{ $stats['total'] }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Pending</span>
        <div class="metric-icon-badge bg-warning-subtle">⏳</div>
      </div>
      <div class="metric-value" style="color:#d97706;">{{ $stats['pending'] }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Approved & Active</span>
        <div class="metric-icon-badge bg-success-subtle">✓</div>
      </div>
      <div class="metric-value" style="color:var(--paces-success);">{{ $stats['approved'] }}</div>
    </div>

    <div class="metric-card-paces">
      <div class="metric-top">
        <span class="metric-label">Rejected / Suspended</span>
        <div class="metric-icon-badge bg-danger-subtle">✕</div>
      </div>
      <div class="metric-value" style="color:var(--paces-danger);">{{ $stats['rejected'] }}</div>
    </div>
  </div>

  <!-- Partner Directory Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:14px; border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1rem;">
      <!-- Status Tabs -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.partners.index') }}" class="btn-paces {{ $status === 'all' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.partners.index', ['status' => 'pending']) }}" class="btn-paces {{ $status === 'pending' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Pending Approvals ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('admin.partners.index', ['status' => 'approved']) }}" class="btn-paces {{ $status === 'approved' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Approved Crews ({{ $stats['approved'] }})
        </a>
        <a href="{{ route('admin.partners.index', ['status' => 'rejected']) }}" class="btn-paces {{ $status === 'rejected' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Rejected ({{ $stats['rejected'] }})
        </a>
      </div>

      <!-- Search Input -->
      <form action="{{ route('admin.partners.index') }}" method="GET" style="display:flex; gap:8px;">
        <input type="hidden" name="status" value="{{ $status }}">
        <input 
          type="text" 
          name="search" 
          value="{{ $search }}" 
          placeholder="Search partner, lead, trade, phone..." 
          class="form-control-paces" 
          style="padding:6px 12px; font-size:0.82rem; width:260px;"
        >
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">
          Search
        </button>
      </form>
    </div>

    @if($partners->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">👷</div>
        <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Partner Applications</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">No contractors found under current selection.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Company & Contact Lead</th>
              <th>Trade Domain</th>
              <th>Workforce</th>
              <th>Coverage Region</th>
              <th>Status</th>
              <th>Payout Profile</th>
              <th style="text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($partners as $p)
              <tr>
                <td>
                  <div style="display:flex; align-items:center; gap:10px;">
                    @if($p->user?->avatar_url)
                      <img src="{{ $p->user->avatar_url }}" alt="{{ $p->company_name }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                    @else
                      <div style="width:36px; height:36px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:600; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;">
                        {{ strtoupper(substr($p->company_name, 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <div style="font-weight:500; font-size:0.92rem; color:var(--paces-text-dark);">
                        {{ $p->company_name }}
                      </div>
                      <div style="font-size:0.8rem; color:var(--paces-primary); font-weight:500; margin-top:2px;">
                        👤 {{ $p->contact_person }}
                      </div>
                      <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">
                        📞 {{ $p->phone }} {{ $p->email ? '• ' . $p->email : '' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <span style="font-weight:500; font-size:0.84rem; color:var(--paces-text-dark);">
                    {{ $p->trade_domain }}
                  </span>
                </td>
                <td>
                  <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem;">
                    {{ $p->technicians_count }} Techs
                  </span>
                </td>
                <td>
                  <div style="font-size:0.82rem; color:var(--paces-text-dark); max-width:240px; line-height:1.4;">
                    📍 {{ Str::limit($p->coverage_areas, 60) }}
                  </div>
                </td>
                <td>
                  @php $badge = $p->status_badge; @endphp
                  <span class="badge-pill-paces {{ $badge['bg'] }}" style="color:{{ $badge['color'] }};">
                    {{ $badge['label'] }}
                  </span>
                </td>
                <td>
                  @if($p->status === 'approved')
                    <div style="font-size:0.8rem; font-weight:500; color:var(--paces-text-dark); text-transform:capitalize;">
                      {{ $p->payment_method ? strtoupper($p->payment_method) : 'Bank' }} • {{ $p->payout_terms }}
                    </div>
                    <div style="font-size:0.72rem; color:var(--paces-text-muted);">
                      Rate: {{ $p->commission_rate }}%
                    </div>
                  @else
                    <span style="font-size:0.75rem; color:#d97706; font-weight:500;">
                      Pending Setup
                    </span>
                  @endif
                </td>
                <td style="text-align:right; white-space:nowrap;">
                  <a href="{{ route('admin.partners.show', $p) }}" class="btn-paces {{ $p->status === 'pending' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
                    {{ $p->status === 'pending' ? 'Review & Approve →' : 'View Details →' }}
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="padding:16px 20px; border-top:1px solid var(--paces-border);">
        {{ $partners->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
