<x-layouts.app :title="'Ticket #' . $ticket->ticket_number . ' | ' . \App\Models\SiteSetting::get('site_name', 'FIX') . ' Customer Portal'">
  <section class="page-hero" style="padding-bottom:1.5rem;">
    <div class="container">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
        <div>
          <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <a href="{{ route('customer.tickets.index') }}" style="color:#38bdf8; font-size:0.875rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
              ← Back to My Support Tickets
            </a>
          </div>
          <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h1 style="font-size:1.85rem; font-weight:800; color:#ffffff; margin:0;">
              Ticket #{{ $ticket->ticket_number }}
            </h1>
            <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; padding:4px 10px; border-radius:20px;
              background:{{ $ticket->status === 'closed' ? '#f1f5f9' : ($ticket->status === 'in_progress' ? '#fef3c7' : '#e0f2fe') }};
              color:{{ $ticket->status === 'closed' ? '#64748b' : ($ticket->status === 'in_progress' ? '#b45309' : '#0369a1') }};">
              ● {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
            <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; padding:4px 10px; border-radius:20px;
              background:{{ $ticket->priority === 'urgent' ? '#fee2e2' : ($ticket->priority === 'high' ? '#ffedd5' : '#f8fafc') }};
              color:{{ $ticket->priority === 'urgent' ? '#dc2626' : ($ticket->priority === 'high' ? '#ea580c' : '#475569') }}; border:1px solid #e2e8f0;">
              Priority: {{ ucfirst($ticket->priority) }}
            </span>
          </div>
          <p style="font-size:1.05rem; color:#f1f5f9; font-weight:500; margin-top:6px; margin-bottom:0;">
            {{ $ticket->subject }}
          </p>
        </div>

        <div style="text-align:right; font-size:0.875rem; color:#cbd5e1;">
          <div>Registered on <strong style="color:#ffffff; font-weight:600;">{{ $ticket->created_at->format('M d, Y • h:i A') }}</strong></div>
          <div style="margin-top:4px;">Last Activity: <strong style="color:#ffffff; font-weight:600;">{{ $ticket->updated_at->diffForHumans() }}</strong></div>
        </div>
      </div>
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

      <!-- Chatting Thread (Normal Clean Chat Interface without Step counts or Step Pipeline) -->
      <div style="margin-bottom:2rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
          <h2 style="font-size:1.25rem; font-weight:700; color:var(--text-main); margin:0;">
            Support Conversation
          </h2>
          <span style="font-size:0.85rem; color:#64748b;">
            {{ $ticket->messages->count() }} {{ \Illuminate\Support\Str::plural('message', $ticket->messages->count()) }}
          </span>
        </div>

        <div class="chat-thread-container" style="display:flex; flex-direction:column; gap:16px;">
          @foreach($ticket->messages as $msg)
            <!-- Chat Bubble -->
            <div class="message-card {{ $msg->sender_type === 'admin' ? 'admin-response' : 'customer-inquiry' }}" style="border-radius:12px; padding:1.25rem; border:1px solid var(--border); background:{{ $msg->sender_type === 'admin' ? '#eff6ff' : '#ffffff' }};">
              
              <!-- Message Header -->
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; border-bottom:1px solid rgba(0,0,0,0.05); padding-bottom:6px;">
                <div style="display:flex; align-items:center; gap:8px;">
                  @if($msg->sender_type === 'admin')
                    <span style="font-size:0.875rem; font-weight:700; color:#1e40af;">
                    FIX Support
                    </span>
                  @else
                    <span style="font-size:0.875rem; font-weight:700; color:#1e293b;">
                      👤 {{ $msg->sender_name }} (You)
                    </span>
                  @endif
                </div>
                <span style="font-size:0.75rem; color:#94a3b8;">
                  {{ $msg->created_at->format('M d, Y • h:i A') }}
                </span>
              </div>

              <!-- Message Body -->
              <div class="message-body" style="font-size:0.92rem; line-height:1.6; color:#334155; white-space:pre-line;">
                {{ $msg->message }}
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Reply Box (Strictly text only, no image upload) -->
      <div class="form-card" style="padding:2rem; border:1px solid var(--border); border-radius:12px; background:#ffffff; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
        <h3 style="font-size:1.15rem; font-weight:700; color:var(--text-main); margin:0 0 8px 0;">
          Post a Reply to Engineering Desk
        </h3>
        <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:18px;">
          Send your reply to the engineering team. Note: Image and file uploads are disabled for security.
        </p>

        @if($ticket->status === 'closed')
          <div style="background:#fef3c7; border:1px solid #fcd34d; color:#92400e; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:0.85rem;">
            ℹ️ This ticket is currently closed. Replying will automatically re-open it.
          </div>
        @endif

        <form action="{{ route('support.reply', $ticket->ticket_number) }}" method="POST">
          @csrf

          <div style="margin-bottom:16px;">
            <textarea name="message" rows="4" required
                      placeholder="Write your response or additional technical details..."
                      style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.92rem; outline:none; font-family:inherit; transition:border 0.2s;"
                      onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">{{ old('message') }}</textarea>
            @error('message')
              <div style="color:#ef4444; font-size:0.8rem; margin-top:4px;">{{ $message }}</div>
            @enderror
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="font-size:0.75rem; color:#94a3b8; display:flex; align-items:center; gap:6px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
              <span>Text transmission only. Attachments disabled.</span>
            </div>

            <button type="submit" class="btn btn-accent" style="padding:10px 22px; font-size:0.92rem; font-weight:700; border-radius:6px; display:inline-flex; align-items:center; gap:8px;">
              <span>Send Step {{ $ticket->messages->count() + 1 }} Reply ➔</span>
            </button>
          </div>
        </form>
      </div>

    </div>
  </section>

  <!-- Modern Step-by-Step Chatting Flow Styles -->
  <style>
    .arrow-steps-container {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .arrow-step {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.825rem;
      font-weight: 600;
    }
    .arrow-step.completed {
      background: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }
    .arrow-step.pending {
      background: #f1f5f9;
      color: #94a3b8;
      border: 1px solid #e2e8f0;
    }
    .arrow-step .step-num {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: currentColor;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: 800;
    }
    .arrow-step .step-arrow {
      margin-left: 6px;
      font-weight: 800;
      opacity: 0.6;
    }

    /* Chat Thread */
    .chat-thread-container {
      display: flex;
      flex-direction: column;
      gap: 0;
    }
    .message-card {
      border-radius: 10px;
      padding: 1.25rem 1.5rem;
      border: 1px solid var(--border);
      background: #ffffff;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .message-card.customer-inquiry {
      border-left: 5px solid #4361ee;
      background: #fcfdfe;
    }
    .message-card.admin-response {
      border-left: 5px solid #10b981;
      background: #f0fdf4;
    }
    .message-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      padding-bottom: 8px;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      flex-wrap: wrap;
      gap: 8px;
    }
    .badge-step-customer {
      background: #e0e7ff;
      color: #3730a3;
      font-size: 0.72rem;
      font-weight: 800;
      padding: 2px 8px;
      border-radius: 12px;
      letter-spacing: 0.02em;
    }
    .badge-step-admin {
      background: #dcfce7;
      color: #166534;
      font-size: 0.72rem;
      font-weight: 800;
      padding: 2px 8px;
      border-radius: 12px;
      letter-spacing: 0.02em;
    }
    .message-body {
      font-size: 0.92rem;
      line-height: 1.6;
      color: #334155;
    }

    /* Step-by-Step Connector Arrows */
    .flow-step-connector {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 10px 0;
      gap: 12px;
    }
    .flow-step-connector .connector-line {
      flex: 1;
      height: 1px;
      background: #e2e8f0;
    }
    .flow-step-connector .connector-badge {
      background: #f1f5f9;
      color: #64748b;
      font-size: 0.72rem;
      font-weight: 700;
      padding: 3px 12px;
      border-radius: 14px;
      border: 1px dashed #cbd5e1;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
  </style>
</x-layouts.app>
