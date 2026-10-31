<x-layouts.app :title="'My Support Tickets | ' . \App\Models\SiteSetting::get('site_name', 'FIX') . ' Customer Portal'">
  <x-page-hero 
    title="My Support Tickets & Inquiries"
    subtitle="Track all your engineering maintenance tickets, real-time status updates, and support communications."
  />

  <section class="section" style="padding-top:2.5rem; padding-bottom:4rem;">
    <div class="container" style="max-width:960px;">
      @if(session('success'))
        <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.9rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
          <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
          </div>
          <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
        </div>
      @endif

      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:16px;">
        <div>
          <div style="display:flex; align-items:center; gap:10px;">
            <h2 style="font-size:1.35rem; font-weight:800; margin:0; color:var(--text-main);">
              Support Tickets
            </h2>
            <span style="font-size:0.78rem; font-weight:700; background:rgba(67,97,238,0.1); color:var(--primary); padding:3px 10px; border-radius:12px;">
              {{ $tickets->total() }} Total
            </span>
          </div>
          <p style="margin:4px 0 0; font-size:0.85rem; color:var(--text-muted);">
            Account: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
          </p>
        </div>

        <div style="display:flex; gap:10px; align-items:center;">
          <a href="{{ route('customer.bookings') }}" class="btn btn-outline" style="padding:0.6rem 1.1rem; font-size:0.875rem; font-weight:600; border-radius:8px; display:inline-flex; align-items:center; gap:6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span>My Bookings</span>
          </a>
          <a href="{{ route('support.create') }}" class="btn btn-accent" style="padding:0.6rem 1.25rem; font-size:0.875rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Open New Ticket</span>
          </a>
        </div>
      </div>

      @if($tickets->isEmpty())
        <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; text-align:center; padding:4rem 2rem; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
          <div style="width:68px; height:68px; border-radius:50%; background:rgba(67,97,238,0.08); color:var(--primary); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
          </div>
          <h3 style="font-size:1.25rem; font-weight:700; color:var(--text-main); margin-bottom:0.5rem;">No Support Tickets Found</h3>
          <p style="color:var(--text-muted); max-width:440px; margin:0 auto 1.75rem; font-size:0.925rem; line-height:1.5;">
            You have not submitted any technical support or facility assistance tickets yet. If you have an inquiry or issue, our operations desk is here to assist.
          </p>
          <a href="{{ route('support.create') }}" class="btn btn-accent" style="padding:0.75rem 1.5rem; font-weight:700; border-radius:8px;">
            Open Your First Ticket →
          </a>
        </div>
      @else
        <div style="display:flex; flex-direction:column; gap:16px;">
          @foreach($tickets as $ticket)
            <div class="customer-ticket-card" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:1.5rem 1.75rem; box-shadow:0 2px 6px rgba(0,0,0,0.03); transition:all 0.2s ease;" onmouseover="this.style.boxShadow='0 6px 18px rgba(0,0,0,0.06)'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.boxShadow='0 2px 6px rgba(0,0,0,0.03)'; this.style.borderColor='#e2e8f0';">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                <div style="flex:1; min-width:0;">
                  <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
                    <span style="font-size:0.875rem; font-weight:800; color:var(--primary); font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace; background:rgba(67,97,238,0.08); padding:3px 8px; border-radius:6px; letter-spacing:0.02em;">
                      #{{ $ticket->ticket_number }}
                    </span>

                    @php
                      $statusBg = '#f1f5f9';
                      $statusColor = '#475569';
                      $statusBorder = '#cbd5e1';
                      if ($ticket->status === 'open') {
                        $statusBg = '#ecfdf5';
                        $statusColor = '#065f46';
                        $statusBorder = '#a7f3d0';
                      } elseif ($ticket->status === 'in_progress') {
                        $statusBg = '#fffbeb';
                        $statusColor = '#92400e';
                        $statusBorder = '#fde68a';
                      } elseif ($ticket->status === 'closed') {
                        $statusBg = '#f8fafc';
                        $statusColor = '#64748b';
                        $statusBorder = '#e2e8f0';
                      }
                    @endphp
                    <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:{{ $statusBg }}; color:{{ $statusColor }}; border:1px solid {{ $statusBorder }};">
                      ● {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>

                    @php
                      $priBg = '#f8fafc';
                      $priColor = '#64748b';
                      $priBorder = '#e2e8f0';
                      if ($ticket->priority === 'urgent') {
                        $priBg = '#fef2f2';
                        $priColor = '#991b1b';
                        $priBorder = '#fecaca';
                      } elseif ($ticket->priority === 'high') {
                        $priBg = '#fff7ed';
                        $priColor = '#9a3412';
                        $priBorder = '#fed7aa';
                      }
                    @endphp
                    <span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.02em; padding:2px 8px; border-radius:10px; background:{{ $priBg }}; color:{{ $priColor }}; border:1px solid {{ $priBorder }};">
                      {{ ucfirst($ticket->priority) }} Priority
                    </span>
                  </div>

                  <h3 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0 0 10px; line-height:1.4;">
                    <a href="{{ route('customer.tickets.show', $ticket->ticket_number) }}" style="color:inherit; text-decoration:none; transition:color 0.15s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='#0f172a'">
                      {{ $ticket->subject }}
                    </a>
                  </h3>

                  <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap; font-size:0.825rem; color:#64748b;">
                    <span style="display:inline-flex; align-items:center; gap:5px;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                      {{ $ticket->created_at->format('M d, Y • h:i A') }}
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:5px;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                      <strong>{{ $ticket->messages->count() }}</strong> messages
                    </span>
                  </div>
                </div>

                <div style="align-self:center;">
                  <a href="{{ route('customer.tickets.show', $ticket->ticket_number) }}" class="btn btn-outline" style="padding:8px 16px; font-size:0.85rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; color:var(--primary); border-color:#cbd5e1; background:#ffffff;">
                    <span>View Conversation Flow</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
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
