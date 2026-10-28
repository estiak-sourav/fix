<x-layouts.admin title="Support Tickets | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.dashboard') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Dashboard
        </a>
      </div>
      <h1 class="page-header-title">Support Tickets Command Center</h1>
    </div>
    
    <div style="display:flex; gap:10px;">
      <a href="{{ route('admin.tickets.create') }}" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>New Support Ticket</span>
      </a>
    </div>
  </div>

  @if(session('success'))
    <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:12px 18px; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Compact KPI Metrics Cards (Title on top, Counter number, Subtext removed, Reduced height) -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:1.5rem;">
    <!-- Total Tickets -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted); text-transform:uppercase;">Total Inquiries</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:var(--paces-text-dark); margin-top:4px; line-height:1.2;">{{ $metrics['total'] }}</div>
    </div>

    <!-- Open Tickets -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px; border-left:3px solid #0284c7;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:#0284c7; text-transform:uppercase;">Open Awaiting</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:#0284c7; margin-top:4px; line-height:1.2;">{{ $metrics['open'] }}</div>
    </div>

    <!-- In Progress -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px; border-left:3px solid #f59e0b;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:#b45309; text-transform:uppercase;">In Discussion</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:#b45309; margin-top:4px; line-height:1.2;">{{ $metrics['in_progress'] }}</div>
    </div>

    <!-- Closed -->
    <div class="card-paces" style="margin-bottom:0; padding:12px 16px; border-left:3px solid #10b981;">
      <div>
        <span style="font-size:0.8rem; font-weight:500; color:#10b981; text-transform:uppercase;">Resolved / Closed</span>
      </div>
      <div style="font-size:1.6rem; font-weight:500; color:#10b981; margin-top:4px; line-height:1.2;">{{ $metrics['closed'] }}</div>
    </div>
  </div>

  <!-- Filter Toolbar Card -->
  <div class="card-paces" style="padding:14px 18px; margin-bottom:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
      
      <!-- Status Tabs -->
      <div style="display:flex; gap:6px; flex-wrap:wrap;">
        <a href="{{ route('admin.tickets.index', ['status' => 'all', 'priority' => $priority, 'search' => $search]) }}"
           class="btn-paces {{ $status === 'all' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          All Tickets ({{ $metrics['total'] }})
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'open', 'priority' => $priority, 'search' => $search]) }}"
           class="btn-paces {{ $status === 'open' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Open ({{ $metrics['open'] }})
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'in_progress', 'priority' => $priority, 'search' => $search]) }}"
           class="btn-paces {{ $status === 'in_progress' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          In Progress ({{ $metrics['in_progress'] }})
        </a>
        <a href="{{ route('admin.tickets.index', ['status' => 'closed', 'priority' => $priority, 'search' => $search]) }}"
           class="btn-paces {{ $status === 'closed' ? 'btn-paces-primary' : 'btn-paces-outline' }} btn-paces-sm" style="font-weight:500;">
          Closed ({{ $metrics['closed'] }})
        </a>
      </div>

      <!-- Search & Priority Form -->
      <form action="{{ route('admin.tickets.index') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin:0;">
        <input type="hidden" name="status" value="{{ $status }}">

        <select name="priority" onchange="this.form.submit()" style="padding:6px 12px; font-size:0.82rem; border:1px solid #cbd5e1; border-radius:6px; background:#fff; outline:none; font-weight:500;">
          <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>All Priorities</option>
          <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
          <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
          <option value="normal" {{ $priority === 'normal' ? 'selected' : '' }}>Normal</option>
          <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
        </select>

        <div style="position:relative; width:240px;">
          <input type="text" name="search" value="{{ $search }}" placeholder="Search ticket #, email, subject..."
                 style="width:100%; padding:6px 28px 6px 10px; font-size:0.82rem; border:1px solid #cbd5e1; border-radius:6px; outline:none;">
          @if($search)
            <a href="{{ route('admin.tickets.index', ['status' => $status, 'priority' => $priority]) }}"
               style="position:absolute; right:8px; top:50%; transform:translateY(-50%); color:#94a3b8; text-decoration:none; font-weight:500;">✕</a>
          @endif
        </div>

        <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm">Search</button>
      </form>

    </div>
  </div>

  <!-- Tickets Data Table -->
  <div class="card-paces" style="padding:0; overflow:hidden;">
    @if($tickets->isEmpty())
      <div style="text-align:center; padding:3.5rem 1.5rem; color:#64748b;">
        <div style="font-size:2.5rem; margin-bottom:10px;">🎫</div>
        <h3 style="font-size:1.15rem; font-weight:500; color:#1e293b; margin-bottom:4px;">No Support Tickets Found</h3>
        <p style="font-size:0.875rem; margin:0 auto 1.25rem; max-width:380px;">No tickets matched your selected status or filter parameters.</p>
        <a href="{{ route('admin.tickets.create') }}" class="btn-paces btn-paces-primary btn-paces-sm">
          + Create New Support Ticket
        </a>
      </div>
    @else
      <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:0.85rem;">
          <thead>
            <tr style="background:#f8fafc; border-bottom:1px solid #e2e8f0; color:#64748b; font-weight:500; text-transform:uppercase; font-size:0.75rem; letter-spacing:0.03em;">
              <th style="padding:12px 18px;">Ticket #</th>
              <th style="padding:12px 16px;">Client / Submitter</th>
              <th style="padding:12px 16px;">Subject</th>
              <th style="padding:12px 16px;">Priority</th>
              <th style="padding:12px 16px;">Status</th>
              <th style="padding:12px 16px;">Created / Active</th>
              <th style="padding:12px 18px; text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tickets as $ticket)
              <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                
                <!-- Ticket # -->
                <td style="padding:14px 18px; font-weight:500; font-family:monospace; color:var(--paces-primary);">
                  <a href="{{ route('admin.tickets.show', $ticket) }}" style="color:inherit; text-decoration:none;">
                    #{{ $ticket->ticket_number }}
                  </a>
                </td>

                <!-- Client / Submitter -->
                <td style="padding:14px 16px;">
                  <div style="display:flex; align-items:center; gap:10px;">
                    @if($ticket->user?->avatar_url)
                      <img src="{{ $ticket->user->avatar_url }}" alt="{{ $ticket->customer_name }}" style="width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                    @else
                      <div style="width:34px; height:34px; border-radius:50%; background:var(--paces-primary-subtle); color:var(--paces-primary); font-weight:600; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;">
                        {{ strtoupper(substr($ticket->customer_name ?: 'C', 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <div style="font-weight:500; color:#1e293b;">{{ $ticket->customer_name }}</div>
                      <div style="font-size:0.78rem; color:#64748b;">{{ $ticket->customer_email }}</div>
                      @if($ticket->user)
                        <span style="display:inline-block; font-size:0.68rem; font-weight:500; background:#e0e7ff; color:#3730a3; padding:1px 6px; border-radius:10px; margin-top:2px;">
                          Registered Client
                        </span>
                      @else
                        <span style="display:inline-block; font-size:0.68rem; font-weight:500; background:#f1f5f9; color:#64748b; padding:1px 6px; border-radius:10px; margin-top:2px;">
                          Guest Email
                        </span>
                      @endif
                    </div>
                  </div>
                </td>

                <!-- Subject -->
                <td style="padding:14px 16px; max-width:260px;">
                  <a href="{{ route('admin.tickets.show', $ticket) }}" style="font-weight:500; color:#1e293b; text-decoration:none; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    {{ $ticket->subject }}
                  </a>
                  <div style="font-size:0.75rem; color:#94a3b8; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    {{ \Illuminate\Support\Str::limit($ticket->messages->first()?->message ?? '', 60) }}
                  </div>
                </td>

                <!-- Priority -->
                <td style="padding:14px 16px;">
                  <span style="font-size:0.72rem; font-weight:500; text-transform:uppercase; padding:2px 8px; border-radius:12px;
                    background:{{ $ticket->priority === 'urgent' ? '#fee2e2' : ($ticket->priority === 'high' ? '#ffedd5' : '#f8fafc') }};
                    color:{{ $ticket->priority === 'urgent' ? '#dc2626' : ($ticket->priority === 'high' ? '#ea580c' : '#64748b') }}; border:1px solid #e2e8f0;">
                    {{ ucfirst($ticket->priority) }}
                  </span>
                </td>

                <!-- Status -->
                <td style="padding:14px 16px;">
                  <span style="font-size:0.75rem; font-weight:500; text-transform:uppercase; padding:3px 9px; border-radius:14px;
                    background:{{ $ticket->status === 'closed' ? '#f1f5f9' : ($ticket->status === 'in_progress' ? '#fef3c7' : '#e0f2fe') }};
                    color:{{ $ticket->status === 'closed' ? '#64748b' : ($ticket->status === 'in_progress' ? '#b45309' : '#0369a1') }};">
                    ● {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                  </span>
                </td>

                <!-- Created / Active -->
                <td style="padding:14px 16px; font-size:0.78rem; color:#64748b;">
                  <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                  <div style="color:#94a3b8;">{{ $ticket->updated_at->diffForHumans() }}</div>
                </td>

                <!-- Actions (Button Flow na View Hobe, fill color) -->
                <td style="padding:14px 18px; text-align:right;">
                  <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:500; padding:6px 14px; text-decoration:none;">
                    View →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="padding:16px 20px; border-top:1px solid #f1f5f9;">
        {{ $tickets->links() }}
      </div>
    @endif
  </div>
</x-layouts.admin>
