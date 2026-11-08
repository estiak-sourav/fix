<x-partner.layouts.app :title="'Earnings & Payouts'">
  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em;">Earnings & Payouts</h1>
      </div>

      <div>
        <a href="{{ route('partner.profile.index') }}#banking" class="btn-p btn-p-outline">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <span>Payout Method</span>
        </a>
      </div>
    </div>
  </div>

  <!-- 4 Top Cards Grid (2x2 on mobile, 4 in a row on desktop) -->
  <div class="p-stats-grid">
    <div class="p-card p-stat-card">
      <span class="p-stat-label">Total Net Earnings</span>
      <div class="p-stat-value" style="color: var(--p-accent);">৳ {{ number_format($totalEarned, 2) }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">{{ $partner->earning_model_label }}</span>
      <div class="p-stat-value" style="color: var(--p-primary); font-size: 1.2rem;">{{ $partner->formatted_rate }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">Completed Jobs</span>
      <div class="p-stat-value" style="color: #15803d;">{{ $completedJobs->total() }}</div>
    </div>

    <div class="p-card p-stat-card">
      <span class="p-stat-label">Payout Account</span>
      <div class="p-stat-value" style="color: var(--p-primary); font-size: 1.15rem; margin-top: 8px;">
        @if($partner->payment_method === 'bank')
          Bank ({{ $partner->bank_name ?: 'Configured' }})
        @elseif(in_array($partner->payment_method, ['bkash', 'nagad', 'rocket']))
          {{ strtoupper($partner->payment_method) }}
        @else
          <span style="color: var(--p-warning); font-size: 0.9rem;">Not Set</span>
        @endif
      </div>
    </div>
  </div>

  {{-- Completed Work Orders Table --}}
  <div class="p-card">
    <div class="p-card-header">
      <h3 class="p-card-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        <span>Earnings Breakdown</span>
      </h3>
    </div>

    @if($completedJobs->isEmpty())
      <div style="text-align: center; padding: 48px 16px; color: var(--p-muted);">
        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; color: #cbd5e1;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div style="font-weight: 600; font-size: 0.95rem; color: #475569;">No Delivered Jobs Yet</div>
      </div>
    @else
      <div class="p-table-wrapper">
        <table class="p-table">
          <thead>
            <tr>
              <th>Order</th>
              <th>Service</th>
              <th>Completed</th>
              <th>Area</th>
              <th style="text-align: right;">Total</th>
              <th style="text-align: right;">Rate</th>
              <th style="text-align: right;">Net Payout</th>
            </tr>
          </thead>
          <tbody>
            @foreach($completedJobs as $job)
              @php
                $jobValue = (float) ($job->final_cost ?? $job->estimated_cost ?? 0);
                $partnerShare = $partner->calculateBookingEarning($job);
              @endphp
              <tr>
                <td>
                  <a href="{{ route('partner.jobs.show', $job) }}" style="font-weight: 700; color: var(--p-accent); text-decoration: none;">
                    #{{ $job->booking_number }}
                  </a>
                </td>
                <td>
                  <div style="font-weight: 600; color: var(--p-primary); font-size: 0.84rem;">{{ $job->service?->name ?? $job->amcPlan?->name ?? 'Facility Service' }}</div>
                </td>
                <td style="font-size: 0.8rem; color: var(--p-muted);">{{ $job->updated_at->format('M d, Y') }}</td>
                <td style="font-size: 0.8rem;">{{ $job->area }}</td>
                <td style="text-align: right; font-weight: 600;">৳ {{ number_format($jobValue, 2) }}</td>
                <td style="text-align: right; font-size: 0.8rem; color: var(--p-muted);">
                  @if($partner->earning_model === 'commission')
                    {{ (float) ($partner->payout_rate ?? $partner->commission_rate ?? 15) }}%
                  @elseif($partner->earning_model === 'hourly')
                    ৳ {{ number_format($partner->payout_rate ?? 0) }}/hr
                  @elseif($partner->earning_model === 'daily')
                    ৳ {{ number_format($partner->payout_rate ?? 0) }}/day
                  @elseif($partner->earning_model === 'monthly')
                    Salary
                  @elseif($partner->earning_model === 'per_job')
                    ৳ {{ number_format($partner->payout_rate ?? 0) }}/job
                  @else
                    {{ $partner->formatted_rate }}
                  @endif
                </td>
                <td style="text-align: right; font-weight: 800; color: var(--p-accent);">৳ {{ number_format($partnerShare, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top: 20px;">
        {{ $completedJobs->links() }}
      </div>
    @endif
  </div>
</x-partner.layouts.app>
