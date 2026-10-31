<x-layouts.app :title="(auth()->check() ? 'My Support Tickets & Tracking' : 'Track Support Ticket') . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX') . ' Helpdesk'">
  <x-page-hero 
    :title="auth()->check() ? 'My Support Tickets & Tracking' : 'Track Support Ticket'"
    :subtitle="auth()->check() ? 'Track real-time responses, engineering updates, and status for your tickets.' : 'Enter your ticket reference and email address to view updates, chat responses, and resolution status.'"
    :maxWidth="auth()->check() ? '820px' : '620px'"
  />

  <section class="section" style="padding-top:2.5rem; padding-bottom:4.5rem;">
    <div class="container" style="max-width:{{ auth()->check() ? '960px' : '580px' }}; margin:0 auto;">
      
      @if(session('error'))
        <div style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <span style="font-size:0.92rem; font-weight:500;">{{ session('error') }}</span>
        </div>
      @endif

      @if(session('success'))
        <div style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span style="font-size:0.92rem; font-weight:500;">{{ session('success') }}</span>
        </div>
      @endif

      @auth
        {{-- ========================================================================= --}}
        {{-- LOGGED IN USER VIEW: Show current user's tickets directly                 --}}
        {{-- ========================================================================= --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem; flex-wrap:wrap; gap:16px;">
          <div>
            <div style="display:flex; align-items:center; gap:10px;">
              <h2 style="font-size:1.35rem; font-weight:800; margin:0; color:var(--text-main);">
                My Support Tickets
              </h2>
              <span style="font-size:0.78rem; font-weight:700; background:rgba(67,97,238,0.1); color:var(--primary); padding:3px 10px; border-radius:12px;">
                {{ $myTickets->count() }} Total
              </span>
            </div>
            <p style="margin:4px 0 0; font-size:0.85rem; color:var(--text-muted);">
              Logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
            </p>
          </div>

          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
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

        @if($myTickets->isEmpty())
          <div style="background:#ffffff; border:1px solid var(--border); border-radius:14px; text-align:center; padding:3.5rem 2rem; box-shadow:0 2px 8px rgba(0,0,0,0.03); margin-bottom:2rem;">
            <div style="width:64px; height:64px; border-radius:50%; background:rgba(67,97,238,0.08); color:var(--primary); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            </div>
            <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-main); margin-bottom:0.4rem;">No Support Tickets Found</h3>
            <p style="color:var(--text-muted); max-width:440px; margin:0 auto 1.5rem; font-size:0.9rem; line-height:1.5;">
              You have not submitted any technical support or facility assistance tickets under this account yet.
            </p>
            <a href="{{ route('support.create') }}" class="btn btn-accent" style="padding:0.75rem 1.5rem; font-weight:700; border-radius:8px;">
              Open a Support Ticket →
            </a>
          </div>
        @else
          <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:2.5rem;">
            @foreach($myTickets as $ticket)
              <div class="customer-ticket-card" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:1.5rem 1.75rem; box-shadow:0 2px 6px rgba(0,0,0,0.03); transition:all 0.2s ease;">
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
                          $priBg = '#ffedd5';
                          $priColor = '#ea580c';
                          $priBorder = '#fed7aa';
                        }
                      @endphp
                      <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em; padding:3px 10px; border-radius:12px; background:{{ $priBg }}; color:{{ $priColor }}; border:1px solid {{ $priBorder }};">
                        Priority: {{ ucfirst($ticket->priority) }}
                      </span>
                    </div>

                    <h3 style="font-size:1.1rem; font-weight:700; color:var(--text-main); margin:0 0 6px 0;">
                      {{ $ticket->subject }}
                    </h3>

                    <div style="font-size:0.825rem; color:var(--text-muted); display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                      <span>Created {{ $ticket->created_at->format('M d, Y • h:i A') }}</span>
                      <span>•</span>
                      <span>Last Activity: <strong style="color:var(--text-main);">{{ $ticket->updated_at->diffForHumans() }}</strong></span>
                      <span>•</span>
                      <span>{{ $ticket->messages->count() }} {{ \Illuminate\Support\Str::plural('message', $ticket->messages->count()) }}</span>
                    </div>
                  </div>

                  <div style="align-self:center;">
                    <a href="{{ route('customer.tickets.show', $ticket->ticket_number) }}" class="btn btn-outline" style="padding:8px 18px; font-size:0.875rem; font-weight:700; border-radius:8px; display:inline-flex; align-items:center; gap:6px; color:var(--primary); border-color:#cbd5e1; background:#ffffff; text-decoration:none;">
                      <span>View Details &amp; Reply</span>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        {{-- Collapsible / Secondary lookup card for logged-in users who have a ticket under another reference --}}
        <div class="form-card" style="padding:1.5rem 1.75rem; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; margin-top:2rem;">
          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:1rem;">
            <div>
              <h4 style="font-size:0.98rem; font-weight:700; color:#1e293b; margin:0;">
                🔍 Need to track a ticket submitted with a different reference number?
              </h4>
              <span style="font-size:0.82rem; color:#64748b;">Enter any ticket reference number (with or without #) to open its timeline.</span>
            </div>
          </div>

          <form action="{{ route('support.track') }}" method="POST" style="display:flex; gap:12px; flex-wrap:wrap;">
            @csrf
            <div style="flex:1; min-width:240px;">
              <input type="text" name="ticket_number" value="{{ old('ticket_number') }}" required
                     placeholder="e.g. FIX-TK-2610-FQSDA or #FIX-TK-2610-FQSDA"
                     style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.92rem; outline:none; font-family:monospace; text-transform:uppercase; background:#ffffff;">
            </div>
            <button type="submit" class="btn btn-accent" style="padding:10px 22px; font-size:0.9rem; font-weight:700; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
              <span>Search Ticket ➔</span>
            </button>
          </form>
        </div>

      @else
        {{-- ========================================================================= --}}
        {{-- GUEST / NOT LOGGED IN USER VIEW: Ticket Reference + Email Lookup Form     --}}
        {{-- ========================================================================= --}}
        <div class="form-card" style="padding:2.25rem 2rem; border:1px solid var(--border); box-shadow:0 4px 20px rgba(0,0,0,0.04); border-radius:12px; background:#ffffff;">
          
          <div style="text-align:center; margin-bottom:1.5rem;">
            <div style="width:48px; height:48px; border-radius:50%; background:rgba(67,97,238,0.1); color:var(--primary); display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <h3 style="font-size:1.25rem; font-weight:700; color:var(--text-main); margin:0 0 4px 0;">Track Your Support Ticket</h3>
            <p style="font-size:0.85rem; color:#64748b; margin:0;">Enter the ticket reference number received via email or SMS.</p>
          </div>

          <form action="{{ route('support.track') }}" method="POST">
            @csrf

            <div style="margin-bottom:18px;">
              <label for="ticket_number" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                Ticket Reference Number <span style="color:#ef4444;">*</span>
              </label>
              <input type="text" id="ticket_number" name="ticket_number" value="{{ old('ticket_number', session('prefill_ticket')) }}" required
                     placeholder="e.g. FIX-TK-2610-FQSDA or #FIX-TK-2610-FQSDA"
                     style="width:100%; padding:11px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none; font-family:monospace; text-transform:uppercase;">
              <span style="font-size:0.75rem; color:#64748b; margin-top:4px; display:block;">You can paste with or without '#' prefix.</span>
            </div>

            <div style="margin-bottom:24px;">
              <label for="email" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                Registered / Submitter Email Address <span style="color:#ef4444;">*</span>
              </label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required
                     placeholder="e.g. easourav@gmail.com"
                     style="width:100%; padding:11px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;">
            </div>

            <button type="submit" class="btn btn-accent" style="width:100%; padding:13px; font-size:1rem; font-weight:700; border-radius:8px; cursor:pointer; display:flex; justify-content:center; align-items:center; gap:8px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <span>Lookup Ticket &amp; Conversation Flow ➔</span>
            </button>
          </form>

          <div style="margin-top:24px; padding-top:16px; border-top:1px solid #f1f5f9; text-align:center;">
            <p style="font-size:0.85rem; color:#64748b; margin-bottom:8px;">
              Already have a registered account?
              <a href="javascript:void(0)" onclick="openAuthModal('login')" style="color:var(--primary); font-weight:700; text-decoration:none;">
                Sign In with OTP
              </a>
              to view all your tickets at once.
            </p>
            <p style="font-size:0.825rem; color:#94a3b8; margin:0;">
              Don't have a ticket yet?
              <a href="{{ route('support.create') }}" style="color:var(--accent); font-weight:600; text-decoration:none;">
                Submit a New Support Ticket →
              </a>
            </p>
          </div>
        </div>
      @endauth

    </div>
  </section>
</x-layouts.app>
