<x-layouts.app title="Track Support Ticket | FIX Helpdesk">
  <section class="page-hero">
    <div class="container">
      <div style="max-width:600px; margin:0 auto; text-align:center;">
        <h1 style="font-size:2rem; font-weight:800; color:var(--text-main); margin-bottom:0.75rem;">
          Track Support Ticket
        </h1>
        <p style="font-size:0.95rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Enter your ticket reference and email address to view updates, chat responses, and resolution status.
        </p>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container" style="max-width:580px;">
      <div class="form-card" style="padding:2.25rem 2rem; border:1px solid var(--border); box-shadow:0 4px 20px rgba(0,0,0,0.04); border-radius:12px;">
        
        @if(session('error'))
          <div style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:1rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
          </div>
        @endif

        <form action="{{ route('support.track') }}" method="POST">
          @csrf

          <div style="margin-bottom:18px;">
            <label for="ticket_number" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
              Ticket Reference Number <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" id="ticket_number" name="ticket_number" value="{{ old('ticket_number', session('prefill_ticket')) }}" required
                   placeholder="e.g. FIX-TK-2610-ABCDE"
                   style="width:100%; padding:11px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.95rem; outline:none; font-family:monospace; text-transform:uppercase;">
          </div>

          <div style="margin-bottom:24px;">
            <label for="email" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
              Registered / Submitter Email Address <span style="color:#ef4444;">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                   placeholder="e.g. tanvir@example.com"
                   style="width:100%; padding:11px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.95rem; outline:none;">
          </div>

          <button type="submit" class="btn btn-accent" style="width:100%; padding:13px; font-size:1rem; font-weight:700; border-radius:6px; cursor:pointer; display:flex; justify-content:center; align-items:center; gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <span>Lookup Ticket & Conversation Flow ➔</span>
          </button>
        </form>

        <div style="text-align:center; margin-top:20px; font-size:0.875rem; color:#64748b;">
          Don't have a ticket yet?
          <a href="{{ route('support.create') }}" style="color:var(--primary); font-weight:700; text-decoration:none;">
            Submit a New Support Ticket →
          </a>
        </div>
      </div>
    </div>
  </section>
</x-layouts.app>
