<x-layouts.admin title="Revenue & Earnings | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.dashboard') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Dashboard
        </a>
      </div>
      <h1 class="page-header-title">Revenue & Earning</h1>
    </div>
    <div style="display:flex; gap:8px;">
      <a href="{{ route('admin.bookings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm">
        All Work Orders →
      </a>
    </div>
  </div>

  <!-- Revenue & Vendor Payout KPI Highlights -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:1.5rem;">
    <!-- Gross Revenue -->
    <div class="card-paces" style="margin-bottom:0; padding:16px 18px; border-left:4px solid var(--paces-primary);">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:var(--paces-text-muted); text-transform:uppercase;">Gross Client Billed</span>
        <span style="font-size:1.15rem;">💰</span>
      </div>
      <div style="font-size:1.55rem; font-weight:800; color:var(--paces-primary); margin:6px 0 2px;">
        ৳ {{ number_format($totalRevenue, 2) }}
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">Invoiced: ৳ {{ number_format($invoicedRevenue, 2) }}</div>
    </div>

    <!-- Vendor Direct Cost -->
    <div class="card-paces" style="margin-bottom:0; padding:16px 18px; border-left:4px solid #f59e0b;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:#b45309; text-transform:uppercase;">Vendor Payout Costs</span>
        <span style="font-size:1.15rem;">👷</span>
      </div>
      <div style="font-size:1.55rem; font-weight:800; color:#b45309; margin:6px 0 2px;">
        ৳ {{ number_format($totalVendorCost, 2) }}
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">
        Paid: ৳ {{ number_format($settledVendorPayouts, 2) }} • Due: ৳ {{ number_format($pendingVendorPayouts, 2) }}
      </div>
    </div>

    <!-- Net Platform Margin -->
    <div class="card-paces" style="margin-bottom:0; padding:16px 18px; border-left:4px solid #16a34a;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:#15803d; text-transform:uppercase;">Net Platform Margin</span>
        <span style="font-size:1.15rem;">📈</span>
      </div>
      <div style="font-size:1.55rem; font-weight:800; color:#16a34a; margin:6px 0 2px;">
        ৳ {{ number_format($netPlatformMargin, 2) }}
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">
        Margin: {{ $totalRevenue > 0 ? round(($netPlatformMargin / $totalRevenue) * 100, 1) : 0 }}% of gross
      </div>
    </div>

    <!-- Pipeline In-Flight -->
    <div class="card-paces" style="margin-bottom:0; padding:16px 18px; border-left:4px solid #6366f1;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.75rem; font-weight:700; color:#4338ca; text-transform:uppercase;">In-Flight Pipeline</span>
        <span style="font-size:1.15rem;">⏳</span>
      </div>
      <div style="font-size:1.55rem; font-weight:800; color:#4338ca; margin:6px 0 2px;">
        ৳ {{ number_format($pendingPipeline, 2) }}
      </div>
      <div style="font-size:0.72rem; color:var(--paces-text-muted);">AOV: ৳ {{ number_format($avgOrderValue, 2) }}</div>
    </div>
  </div>

  <!-- Filter & Period Toolbar -->
  <div class="card-paces" style="margin-bottom:1.5rem; padding:18px 20px;">
    <form action="{{ route('admin.earnings.index') }}" method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
      
      <!-- Period Range -->
      <div style="display:flex; align-items:center; gap:6px;">
        <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Period:</label>
        <select name="range" onchange="this.form.submit()" style="padding:6px 12px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500;">
          <option value="all_time" {{ $range === 'all_time' ? 'selected' : '' }}>All Time</option>
          <option value="today" {{ $range === 'today' ? 'selected' : '' }}>Today</option>
          <option value="week" {{ $range === 'week' ? 'selected' : '' }}>Last 7 Days</option>
          <option value="month" {{ $range === 'month' ? 'selected' : '' }}>Last 30 Days</option>
          <option value="year" {{ $range === 'year' ? 'selected' : '' }}>This Year</option>
        </select>
      </div>

      <!-- Division -->
      <div style="display:flex; align-items:center; gap:6px;">
        <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Division:</label>
        <select name="division" onchange="this.form.submit()" style="padding:6px 12px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500;">
          <option value="all" {{ $division === 'all' ? 'selected' : '' }}>All Divisions</option>
          @foreach(['Dhaka', 'Chattogram', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh'] as $dName)
            <option value="{{ $dName }}" {{ $division === $dName ? 'selected' : '' }}>{{ $dName }}</option>
          @endforeach
        </select>
      </div>

      <!-- Service Filter -->
      <div style="display:flex; align-items:center; gap:6px;">
        <label style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Service:</label>
        <select name="service_id" onchange="this.form.submit()" style="padding:6px 12px; font-size:0.82rem; border-radius:6px; border:1px solid var(--paces-border); background:#ffffff; font-weight:500; max-width:200px;">
          <option value="all" {{ $serviceId === 'all' ? 'selected' : '' }}>All Services</option>
          @foreach($servicesList as $s)
            <option value="{{ $s->id }}" {{ (string)$serviceId === (string)$s->id ? 'selected' : '' }}>{{ $s->title }}</option>
          @endforeach
        </select>
      </div>

      <!-- Search Input -->
      <div style="flex:1; min-width:220px; display:flex; gap:6px;">
        <input 
          type="text" 
          name="search" 
          value="{{ $search }}" 
          class="topbar-search-input" 
          placeholder="Search order #, customer, area..." 
          style="width:100%; border-radius:6px; padding:6px 12px; font-size:0.82rem;"
        >
        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Filter</button>
      </div>

      @if($range !== 'all_time' || $division !== 'all' || $serviceId !== 'all' || !empty($search))
        <a href="{{ route('admin.earnings.index') }}" class="btn-paces btn-paces-outline btn-paces-sm" style="color:#ef4444; border-color:#fca5a5;">
          ✕ Reset
        </a>
      @endif
    </form>
  </div>

  <!-- Revenue Breakdown Cards (Services vs Divisions) -->
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.5rem;">
    <!-- Top Services by Revenue -->
    <div class="card-paces" style="margin-bottom:0; padding:18px;">
      <h3 class="card-title-paces" style="font-size:0.95rem; margin-bottom:12px;">Top Services by Revenue</h3>
      @if($revenueByService->isEmpty())
        <div style="color:var(--paces-text-muted); font-size:0.85rem; padding:1rem 0;">No service revenue logged for this period.</div>
      @else
        <div style="display:flex; flex-direction:column; gap:12px;">
          @foreach($revenueByService as $s)
            @php
              $pct = $totalRevenue > 0 ? min(100, round(($s['total'] / $totalRevenue) * 100)) : 0;
            @endphp
            <div>
              <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:4px;">
                <span style="font-weight:500; color:var(--paces-text-dark);">{{ $s['title'] }} ({{ $s['count'] }})</span>
                <span style="font-weight:500; color:var(--paces-primary);">৳ {{ number_format($s['total'], 2) }}</span>
              </div>
              <div style="height:6px; border-radius:3px; background:#f1f5f9; overflow:hidden;">
                <div style="height:100%; width:{{ $pct }}%; background:var(--paces-primary); border-radius:3px;"></div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Revenue by Division -->
    <div class="card-paces" style="margin-bottom:0; padding:18px;">
      <h3 class="card-title-paces" style="font-size:0.95rem; margin-bottom:12px;">Revenue by Regional Division</h3>
      @if($revenueByDivision->isEmpty())
        <div style="color:var(--paces-text-muted); font-size:0.85rem; padding:1rem 0;">No regional revenue records found.</div>
      @else
        <div style="display:flex; flex-direction:column; gap:12px;">
          @foreach($revenueByDivision as $d)
            @php
              $pct = $totalRevenue > 0 ? min(100, round(($d['total'] / $totalRevenue) * 100)) : 0;
            @endphp
            <div>
              <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:4px;">
                <span style="font-weight:500; color:var(--paces-text-dark);">📍 {{ $d['division'] }} ({{ $d['count'] }} orders)</span>
                <span style="font-weight:500; color:#16a34a;">৳ {{ number_format($d['total'], 2) }}</span>
              </div>
              <div style="height:6px; border-radius:3px; background:#f1f5f9; overflow:hidden;">
                <div style="height:100%; width:{{ $pct }}%; background:#16a34a; border-radius:3px;"></div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <!-- Vendor Payables Breakdown -->
  <div class="card-paces" style="margin-bottom:1.5rem; padding:0; overflow:hidden;">
    <div class="card-header-paces" style="padding:16px 20px; border-bottom:1px solid var(--paces-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; background:#fffdf5;">
      <div>
        <div style="display:flex; align-items:center; gap:8px;">
          <h3 class="card-title-paces" style="margin:0; color:#b45309; font-size:1rem;">
            👷 Vendor Payables & Outstanding Disbursements
          </h3>
          <span class="badge-paces" style="background:#fef3c7; color:#b45309; font-weight:700; padding:2px 10px; border-radius:12px; font-size:0.75rem;">
            {{ $vendorPayables->where('pending_due', '>', 0)->count() }} Vendors with Dues
          </span>
        </div>
        <p class="card-subtitle-paces" style="margin:2px 0 0; color:#78350f; font-size:0.8rem;">
          Partner-by-partner payout calculation: completed work orders delivered, amounts disbursed, and current payable balances
        </p>
      </div>

      <div style="text-align:right;">
        <span style="font-size:0.75rem; color:#b45309; text-transform:uppercase; font-weight:700;">Total Pending Payables</span>
        <div style="font-size:1.35rem; font-weight:800; color:#b45309;">
          ৳ {{ number_format($pendingVendorPayouts, 2) }}
        </div>
      </div>
    </div>

    @if($vendorPayables->isEmpty())
      <div style="text-align:center; padding:2.5rem 1rem; color:var(--paces-text-muted);">
        <p style="margin:0; font-size:0.9rem;">No vendor payout records found.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Vendor / Contractor</th>
              <th>Trade Domain</th>
              <th>Payment Destination</th>
              <th style="text-align:center;">Delivered Orders</th>
              <th style="text-align:right;">Total Earned</th>
              <th style="text-align:right;">Total Disbursed</th>
              <th style="text-align:right;">Payable Due</th>
              <th style="text-align:center;">Payout Status</th>
              <th style="text-align:right;">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vendorPayables as $vp)
              @php
                $hasDue = $vp['pending_due'] > 0;
              @endphp
              <tr style="{{ $hasDue ? 'background:rgba(254, 243, 199, 0.15);' : '' }}">
                <td>
                  <div style="font-weight:700; color:var(--paces-text-dark); font-size:0.92rem;">
                    <a href="{{ route('admin.partners.show', $vp['partner']) }}#payouts" style="color:inherit; text-decoration:none;">
                      {{ $vp['company_name'] }}
                    </a>
                  </div>
                  <div style="font-size:0.78rem; color:var(--paces-text-muted); display:flex; align-items:center; gap:6px; margin-top:2px;">
                    <span>👤 {{ $vp['contact_person'] }}</span>
                    <span>•</span>
                    <span>📞 <a href="tel:{{ $vp['phone'] }}" style="color:inherit; text-decoration:none;">{{ $vp['phone'] }}</a></span>
                  </div>
                </td>
                <td>
                  <span class="badge-paces" style="background:#f1f5f9; color:#475569; font-weight:600; font-size:0.75rem; padding:3px 8px; border-radius:6px;">
                    {{ $vp['trade_domain'] ?: 'Facility Service' }}
                  </span>
                </td>
                <td>
                  @if($vp['payment_method'] === 'bank' && !empty($vp['bank_name']))
                    <div style="font-size:0.82rem; font-weight:600; color:var(--paces-text-dark);">🏦 {{ $vp['bank_name'] }}</div>
                    <div style="font-size:0.72rem; color:var(--paces-text-muted); font-family:monospace;">
                      A/C: {{ $vp['account_number'] }} • {{ $vp['account_holder'] }}
                    </div>
                  @elseif(!empty($vp['mfs_number']))
                    <div style="font-size:0.82rem; font-weight:600; color:#d97706;">
                      📱 {{ strtoupper($vp['payment_method']) }}: {{ $vp['mfs_number'] }}
                    </div>
                  @else
                    <span style="font-size:0.78rem; color:var(--paces-text-muted);">Not configured</span>
                  @endif
                </td>
                <td style="text-align:center;">
                  <span style="font-weight:700; font-size:0.85rem;">{{ $vp['completed_count'] }}</span>
                  @if($vp['unsettled_count'] > 0)
                    <div style="font-size:0.7rem; color:#b45309; font-weight:600;">{{ $vp['unsettled_count'] }} unpaid</div>
                  @else
                    <div style="font-size:0.7rem; color:#16a34a; font-weight:500;">All settled</div>
                  @endif
                </td>
                <td style="text-align:right; font-weight:600;">
                  ৳ {{ number_format($vp['total_earned'], 2) }}
                </td>
                <td style="text-align:right; font-weight:600; color:#16a34a;">
                  ৳ {{ number_format($vp['total_paid'], 2) }}
                </td>
                <td style="text-align:right;">
                  <strong style="font-size:1rem; color:{{ $hasDue ? '#dc2626' : '#16a34a' }};">
                    ৳ {{ number_format($vp['pending_due'], 2) }}
                  </strong>
                </td>
                <td style="text-align:center;">
                  @if($hasDue)
                    <span class="badge-paces" style="background:#fee2e2; color:#dc2626; font-weight:700; font-size:0.72rem; padding:3px 10px; border-radius:12px;">
                      Payment Due
                    </span>
                  @else
                    <span class="badge-paces" style="background:#dcfce7; color:#16a34a; font-weight:700; font-size:0.72rem; padding:3px 10px; border-radius:12px;">
                      ✓ Cleared
                    </span>
                  @endif
                </td>
                <td style="text-align:right;">
                  <a href="{{ route('admin.partners.show', $vp['partner']) }}#payouts" class="btn-paces btn-paces-primary btn-paces-sm" style="padding:4px 10px; font-size:0.78rem;">
                    Settle Payout →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- Detailed Transactions & Work Orders Table -->
  <div class="card-paces" style="padding:0; overflow:hidden;">
    <div class="card-header-paces" style="padding:16px 20px; border-bottom:1px solid var(--paces-border);">
      <h3 class="card-title-paces" style="margin:0;">
        Earning Transaction Records ({{ $bookings->total() }})
      </h3>
    </div>

    @if($bookings->isEmpty())
      <div style="text-align:center; padding:3.5rem 1rem; color:var(--paces-text-muted);">
        <div style="font-size:2.5rem; margin-bottom:10px;">💳</div>
        <h4 style="font-size:1rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:4px;">No Earning Records</h4>
        <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">No orders match the selected filters with billed or estimated revenue.</p>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Service</th>
              <th>Location</th>
              <th>Order Date</th>
              <th>Billing Amount</th>
              <th>Vendor Payout</th>
              <th>Order Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bookings as $b)
              @php
                $amount = $b->final_cost > 0 ? $b->final_cost : ($b->estimated_cost ?: 0);
                $isFinal = $b->final_cost > 0;
                $vPayout = (float) ($b->partner_payout_amount ?? ($b->partner ? $b->partner->calculateBookingEarning($b) : 0));
                $vStatus = $b->partner_payout_status ?: 'unpaid';
              @endphp
              <tr>
                <td style="font-weight:500; font-family:monospace; color:var(--paces-primary);">
                  <a href="{{ route('admin.bookings.show', $b) }}" style="color:inherit; text-decoration:none;">
                    #{{ $b->booking_number }}
                  </a>
                </td>
                <td>
                  <div style="font-weight:500; color:var(--paces-text-dark);">{{ $b->customer_name }}</div>
                  @if($b->company_name)
                    <div style="font-size:0.75rem; color:var(--paces-text-muted);">{{ $b->company_name }}</div>
                  @endif
                </td>
                <td>
                  <div style="font-weight:500; font-size:0.85rem; color:var(--paces-text-dark);">
                    {{ $b->service?->title ?? ($b->amcPlan?->name ?? 'Custom Service') }}
                  </div>
                </td>
                <td>
                  <div style="font-size:0.82rem; color:var(--paces-text-dark);">📍 {{ $b->area }}, {{ $b->division }}</div>
                </td>
                <td style="font-size:0.82rem; color:var(--paces-text-muted);">
                  {{ $b->created_at->format('M d, Y') }}
                </td>
                <td>
                  <strong style="font-size:0.95rem; color:{{ $isFinal ? '#16a34a' : 'var(--paces-primary)' }};">
                    ৳ {{ number_format($amount, 2) }}
                  </strong>
                  <div style="font-size:0.7rem; color:var(--paces-text-muted);">
                    {{ $isFinal ? 'Invoiced Final' : 'Agreed Estimate' }}
                  </div>
                </td>
                <td>
                  @if($b->partner_id || $vPayout > 0)
                    <strong style="font-size:0.9rem; color:#b45309;">
                      ৳ {{ number_format($vPayout, 2) }}
                    </strong>
                    <div style="margin-top:2px;">
                      @if($vStatus === 'paid')
                        <span class="badge-paces badge-paces-success" style="font-size:0.68rem; padding:1px 6px;">Paid</span>
                      @elseif($vStatus === 'processing')
                        <span class="badge-paces badge-paces-info" style="font-size:0.68rem; padding:1px 6px;">Processing</span>
                      @else
                        <span class="badge-paces badge-paces-secondary" style="font-size:0.68rem; padding:1px 6px;">Unpaid</span>
                      @endif
                    </div>
                  @else
                    <span style="font-size:0.75rem; color:var(--paces-text-muted);">Unassigned</span>
                  @endif
                </td>
                <td>
                  <span class="badge-pill-paces {{ $b->status === 'completed' ? 'bg-success-subtle' : 'bg-primary-subtle' }}" style="font-size:0.72rem; font-weight:500;">
                    {{ $b->status_label }}
                  </span>
                </td>
                <td style="text-align:right; white-space:nowrap;">
                  <a href="{{ route('admin.bookings.show', $b) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:500; padding:6px 12px; text-decoration:none; white-space:nowrap; display:inline-flex; align-items:center;">
                    View Order →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="padding:16px 20px; border-top:1px solid var(--paces-border);">
        {{ $bookings->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
