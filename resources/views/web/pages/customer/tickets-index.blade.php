<x-layouts.app title="My Support Tickets | FIX Customer Portal">
  <section class="page-hero">
    <div class="container">
      <h1 style="font-size:2.2rem; font-weight:800; color:var(--text-main); margin-bottom:0.5rem;">
        My Support Tickets & Inquiries
      </h1>
      <p style="font-size:0.95rem; color:var(--text-muted); margin:0;">
        Track all your engineering maintenance tickets, status updates, and discussion threads.
      </p>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container">
      @if(session('success'))
        <div style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:12px;">
        <div>
          <h2 style="font-size:1.3rem; font-weight:700; margin:0; color:var(--text-main);">
            Ticket History
          </h2>
          <p style="margin:2px 0 0; font-size:0.85rem; color:var(--text-muted);">
            Linked Account: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
          </p>
        </div>

        <div style="display:flex; gap:10px;">
          <a href="{{ route('customer.bookings') }}" class="btn btn-outline-primary" style="padding:0.6rem 1rem; font-size:0.875rem;">
            My Bookings
          </a>
          <a href="{{ route('support.create') }}" class="btn btn-accent" style="padding:0.6rem 1.2rem; font-size:0.875rem; display:inline-flex; align-items:center; gap:6px;">
            <span>+ Open New Support Ticket</span>
          </a>
        </div>
      </div>

      @if($tickets->isEmpty())
        <div class="form-card" style="text-align:center; padding:3.5rem 1.5rem; border:1px solid var(--border); border-radius:12px;">
          <div style="width:64px; height:64px; border-radius:50%; background:rgba(67,97,238,0.08); color:var(--primary); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
          </div>
          <h3 style="font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">No Support Tickets Found</h3>
          <p style="color:var(--text-muted); max-width:440px; margin:0 auto 1.5rem; font-size:0.9rem;">
            You have not registered any support or facility tickets with your email yet. If you have an inquiry or technical issue, our desk is ready.
          </p>
          <a href="{{ route('support.create') }}" class="btn btn-accent">
            Create Your First Ticket →
          </a>
        </div>
      @else
        <div style="display:flex; flex-direction:column; gap:16px;">
          @foreach($tickets as $ticket)
            <div class="form-card" style="padding:1.4rem 1.75rem; border:1px solid var(--border); border-radius:10px; transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'" onmouseout="this.style.boxShadow='none'">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
                <div>
                  <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                    <span style="font-size:1.05rem; font-weight:800; color:var(--primary); font-family:monospace;">
                      #{{ $ticket->ticket_number }}
                    </span>
                    <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; padding:3px 9px; border-radius:14px;
                      background:{{ $ticket->status === 'closed' ? '#f1f5f9' : ($ticket->status === 'in_progress' ? '#fef3c7' : '#e0f2fe') }};
                      color:{{ $ticket->status === 'closed' ? '#64748b' : ($ticket->status === 'in_progress' ? '#b45309' : '#0369a1') }};">
                      ● {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; padding:2px 8px; border-radius:12px;
                      background:{{ $ticket->priority === 'urgent' ? '#fee2e2' : ($ticket->priority === 'high' ? '#ffedd5' : '#f8fafc') }};
                      color:{{ $ticket->priority === 'urgent' ? '#dc2626' : ($ticket->priority === 'high' ? '#ea580c' : '#64748b') }}; border:1px solid #e2e8f0;">
                      {{ ucfirst($ticket->priority) }}
                    </span>
                  </div>

                  <h3 style="font-size:1.1rem; font-weight:700; color:#1e293b; margin:4px 0 6px;">
                    {{ $ticket->subject }}
                  </h3>

                  <div style="font-size:0.8rem; color:#64748b;">
                    Submitted on {{ $ticket->created_at->format('M d, Y • h:i A') }} • 
                    <strong>{{ $ticket->messages->count() }}</strong> exchange messages
                  </div>
                </div>

                <div style="text-align:right;">
                  <a href="{{ route('customer.tickets.show', $ticket->ticket_number) }}" class="btn btn-outline-primary" style="padding:8px 16px; font-size:0.85rem; font-weight:700; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                    <span>View Conversation Flow ➔</span>
                  </a>
                </div>
              </div>
            </div>
          @endforeach

          <div style="margin-top:1.5rem;">
            {{ $tickets->links() }}
          </div>
        </div>
      @endif

    </div>
  </section>
</x-layouts.app>
