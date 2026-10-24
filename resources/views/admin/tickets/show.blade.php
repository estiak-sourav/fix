<x-layouts.admin title="Ticket #{{ $ticket->ticket_number }} | FIX Admin">
  <style>
    .chat-app-wrapper {
      display: grid;
      grid-template-columns: 1fr 320px;
      gap: 20px;
      align-items: start;
    }
    .chat-box-card {
      background: #ffffff;
      border: 1px solid var(--paces-border);
      border-radius: var(--paces-radius-sm);
      display: flex;
      flex-direction: column;
      height: calc(100vh - 200px);
      min-height: 560px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
      overflow: hidden;
    }
    .chat-sticky-top {
      position: sticky;
      top: 0;
      z-index: 10;
      background: #ffffff;
      border-bottom: 1px solid var(--paces-border);
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }
    .chat-messages-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 20px;
      background: #f8fafc;
      display: flex;
      flex-direction: column;
      gap: 16px;
      scroll-behavior: smooth;
    }
    .chat-sticky-bottom {
      position: sticky;
      bottom: 0;
      z-index: 10;
      background: #ffffff;
      border-top: 1px solid var(--paces-border);
      padding: 14px 20px;
    }
    .bubble-customer {
      align-self: flex-start;
      max-width: 78%;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px 14px 14px 2px;
      padding: 12px 16px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .bubble-admin {
      align-self: flex-end;
      max-width: 78%;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 14px 14px 2px 14px;
      padding: 12px 16px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    @media (max-width: 992px) {
      .chat-app-wrapper {
        grid-template-columns: 1fr !important;
      }
      .chat-box-card {
        height: auto;
        min-height: 600px;
      }
    }
  </style>

  <!-- Sticky Top Breadcrumb & Status Bar -->
  <div style="margin-bottom:1rem;">
    <a href="{{ route('admin.tickets.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
      ← Back to All Tickets
    </a>
  </div>

  @if(session('success'))
    <div style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:10px 16px; border-radius:6px; margin-bottom:1rem; display:flex; align-items:center; gap:8px; font-size:0.85rem;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Main Chat Application Grid -->
  <div class="chat-app-wrapper">
    
    <!-- Left: Dedicated Chat Panel (Sticky Top Status, Scrollable Messages, Stacked Bottom Reply) -->
    <div class="chat-box-card">
      
      <!-- Top Sticky Header inside Chat Box (Ticket Status top e show korbe, top e close ticket button bad, scroll hobe na) -->
      <div class="chat-sticky-top">
        <div>
          <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <h2 style="font-size:1.15rem; font-weight:500; color:var(--paces-text-dark); margin:0;">
              Ticket #{{ $ticket->ticket_number }}
            </h2>

            <!-- Ticket Status Badge -->
            <span style="font-size:0.75rem; font-weight:500; text-transform:uppercase; padding:3px 10px; border-radius:14px;
              background:{{ $ticket->status === 'closed' ? '#f1f5f9' : ($ticket->status === 'in_progress' ? '#fef3c7' : '#e0f2fe') }};
              color:{{ $ticket->status === 'closed' ? '#64748b' : ($ticket->status === 'in_progress' ? '#b45309' : '#0369a1') }};">
              ● {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>

            <!-- Priority Badge -->
            <span style="font-size:0.72rem; font-weight:500; text-transform:uppercase; padding:2px 8px; border-radius:12px;
              background:{{ $ticket->priority === 'urgent' ? '#fee2e2' : ($ticket->priority === 'high' ? '#ffedd5' : '#f8fafc') }};
              color:{{ $ticket->priority === 'urgent' ? '#dc2626' : ($ticket->priority === 'high' ? '#ea580c' : '#64748b') }}; border:1px solid #e2e8f0;">
              {{ ucfirst($ticket->priority) }} Priority
            </span>
          </div>

          <div style="font-size:0.875rem; color:#475569; margin-top:3px; font-weight:500;">
            {{ $ticket->subject }}
          </div>
        </div>

        <div style="font-size:0.78rem; color:#94a3b8; text-align:right;">
          Started {{ $ticket->created_at->format('M d, Y') }}
        </div>
      </div>

      <!-- Scrollable Chat Feed (Normal Chatting App er moto Admin & Client chat korbe, No Step count / names) -->
      <div class="chat-messages-scroll" id="chatMessagesFeed">
        @foreach($ticket->messages as $msg)
          @if($msg->sender_type === 'admin')
            <!-- Admin Chat Bubble (Right aligned) -->
            <div class="bubble-admin">
              <div style="font-size:0.88rem; line-height:1.55; color:#1e3a8a; white-space:pre-line;">{{ $msg->message }}</div>
              
              <!-- CMS Admin user name shown under chat -->
              <div style="font-size:0.72rem; color:#64748b; margin-top:6px; text-align:right;">
                Replied by: <strong style="color:var(--paces-primary); font-weight:500;">{{ $msg->sender_name }}</strong> • {{ $msg->created_at->format('h:i A') }}
              </div>
            </div>
          @else
            <!-- Client Chat Bubble (Left aligned) -->
            <div class="bubble-customer">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; gap:8px;">
                <span style="font-size:0.78rem; font-weight:500; color:var(--paces-text-dark);">
                  👤 {{ $msg->sender_name }}
                </span>
                <span style="font-size:0.7rem; color:#94a3b8;">
                  {{ $msg->created_at->format('M d, Y • h:i A') }}
                </span>
              </div>
              <div style="font-size:0.88rem; line-height:1.55; color:#1e293b; white-space:pre-line;">{{ $msg->message }}</div>
            </div>
          @endif
        @endforeach
      </div>

      <!-- Sticky Bottom Reply Input Section (Stacked at the bottom, does not scroll) -->
      @if(auth('admin')->user()?->hasPermission('tickets.edit'))
        <div class="chat-sticky-bottom">
          <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
            @csrf

            <div style="margin-bottom:8px;">
              <textarea 
                name="message" 
                rows="2" 
                required
                placeholder="Type your reply to {{ $ticket->customer_name }}..."
                style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.88rem; outline:none; font-family:inherit; resize:vertical; min-height:56px;"
              ></textarea>
              @error('message')
                <div style="color:#ef4444; font-size:0.78rem; margin-top:2px;">{{ $message }}</div>
              @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
              <div style="display:flex; align-items:center; gap:8px;">
                <label for="statusSelect" style="font-size:0.8rem; font-weight:500; color:var(--paces-text-muted);">Set Status:</label>
                <select name="status" id="statusSelect" style="padding:5px 10px; font-size:0.8rem; border:1px solid #cbd5e1; border-radius:6px; background:#fff; font-weight:500;">
                  <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                  <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                  <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed / Resolved</option>
                </select>
              </div>

              <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="font-weight:500; padding:6px 18px;">
                Send Reply ➔
              </button>
            </div>
          </form>
        </div>
      @else
        <div class="chat-sticky-bottom" style="background:#f8fafc; text-align:center; color:#64748b; font-size:0.82rem; padding:10px;">
          👁️ View Only Mode. Replies require staff editing permissions.
        </div>
      @endif

    </div>

    <!-- Right: Client Summary Card -->
    <div>
      <div class="card-paces" style="padding:1.25rem; margin-bottom:1rem;">
        <h4 style="font-size:0.9rem; font-weight:500; color:var(--paces-text-dark); margin:0 0 12px; text-transform:uppercase; letter-spacing:0.04em;">
          Client Information
        </h4>

        <div style="margin-bottom:12px;">
          <div style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; font-weight:500;">Full Name</div>
          <div style="font-size:0.95rem; font-weight:500; color:#1e293b; margin-top:2px;">{{ $ticket->customer_name }}</div>
        </div>

        <div style="margin-bottom:12px;">
          <div style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; font-weight:500;">Email Address</div>
          <a href="mailto:{{ $ticket->customer_email }}" style="font-size:0.85rem; font-weight:500; color:var(--paces-primary); text-decoration:none;">
            {{ $ticket->customer_email }}
          </a>
        </div>

        @if($ticket->customer_phone)
          <div style="margin-bottom:12px;">
            <div style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; font-weight:500;">Phone Number</div>
            <a href="tel:{{ $ticket->customer_phone }}" style="font-size:0.88rem; font-weight:500; color:#1e293b; text-decoration:none;">
              {{ $ticket->customer_phone }}
            </a>
          </div>
        @endif

        <div style="border-top:1px dashed #e2e8f0; padding-top:12px; margin-bottom:12px;">
          <div style="font-size:0.72rem; color:#94a3b8; text-transform:uppercase; font-weight:500; margin-bottom:4px;">Account Status</div>
          @if($ticket->user)
            <span style="display:inline-block; font-size:0.72rem; font-weight:500; background:#e0e7ff; color:#3730a3; padding:2px 8px; border-radius:12px;">
              ✓ Registered User
            </span>
          @else
            <span style="display:inline-block; font-size:0.72rem; font-weight:500; background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:12px;">
              Guest Contact
            </span>
          @endif
        </div>

        <div style="font-size:0.78rem; color:#64748b; border-top:1px dashed #e2e8f0; padding-top:12px;">
          <div>Created: <strong style="font-weight:500;">{{ $ticket->created_at->format('M d, Y') }}</strong></div>
          @if($ticket->closed_at)
            <div style="color:#10b981; font-weight:500; margin-top:2px;">Closed: {{ $ticket->closed_at->format('M d, Y • h:i A') }}</div>
          @endif
        </div>
      </div>
    </div>

  </div>

  <script>
    // Auto-scroll chat to latest message on load
    document.addEventListener('DOMContentLoaded', function () {
      const feed = document.getElementById('chatMessagesFeed');
      if (feed) {
        feed.scrollTop = feed.scrollHeight;
      }
    });
  </script>
</x-layouts.admin>
