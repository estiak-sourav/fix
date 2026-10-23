<x-layouts.admin title="Work Orders & Service Requests | FIX Admin">
  <div class="page-header-box">
    <div>
      <h1 class="page-header-title">Work Orders & Service Requests</h1>
      <p class="page-header-subtitle">Manage customer bookings, field technician dispatching, and SLA compliance</p>
    </div>
  </div>

  <div class="card-paces">
    <div class="card-header-paces" style="flex-wrap:wrap; gap:14px;">
      <!-- Filter status tabs -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.bookings.index') }}" class="btn-paces {{ !request('status') ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          All Orders ({{ \App\Models\Booking::count() }})
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'pending_review']) }}" class="btn-paces {{ request('status') === 'pending_review' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">
          Pending ({{ \App\Models\Booking::where('status', 'pending_review')->count() }})
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'accepted']) }}" class="btn-paces {{ request('status') === 'accepted' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">Accepted</a>
        <a href="{{ route('admin.bookings.index', ['status' => 'in_progress']) }}" class="btn-paces {{ request('status') === 'in_progress' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">In Progress</a>
        <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}" class="btn-paces {{ request('status') === 'completed' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm">Completed</a>
      </div>

      <!-- Quick Search Bar -->
      <form action="{{ route('admin.bookings.index') }}" method="GET" style="display:flex; gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" class="topbar-search-input" placeholder="Search order #, customer, phone..." style="width:260px;">
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Search</button>
      </form>
    </div>

    @if($bookings->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">📋</div>
        <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Orders Found</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">No facility service bookings match your active filters.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Service Scope</th>
              <th>Schedule</th>
              <th>Location</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $b)
              <tr>
                <td>
                  <strong style="color:var(--paces-primary); font-family:monospace; font-size:0.9rem; font-weight:500;">#{{ $b->booking_number }}</strong>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); margin-top:2px;">{{ $b->created_at->format('M d, Y • h:i A') }}</div>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->customer_name }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">📞 {{ $b->customer_phone }}</div>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service') }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize;">{{ $b->property_type }}</div>
                </td>
                <td>
                  <div>{{ \Carbon\Carbon::parse($b->preferred_date)->format('M d, Y') }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted); text-transform:capitalize;">Slot: {{ $b->preferred_time_slot }}</div>
                </td>
                <td>
                  <div style="font-weight:500;">{{ $b->area }}</div>
                  <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->district }}</div>
                </td>
                <td>
                  @php
                    $statusClass = match($b->status) {
                      'pending_review' => 'bg-warning-subtle',
                      'accepted', 'confirmed' => 'bg-primary-subtle',
                      'in_progress', 'assigned' => 'bg-info-subtle',
                      'completed' => 'bg-success-subtle',
                      'cancelled' => 'bg-danger-subtle',
                      default => 'bg-primary-subtle'
                    };
                  @endphp
                  <span class="badge-pill-paces {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $b->status)) }}
                  </span>
                </td>
                <td style="text-align:right;">
                  <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-outline btn-paces-sm">
                    Manage & Details →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top:1.5rem;">
        {{ $bookings->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
