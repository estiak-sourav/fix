<x-layouts.admin title="Ticket #{{ $ticket->ticket_number }} | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.tickets.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to All Tickets
        </a>
      </div>
      <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <h1 class="page-header-title" style="margin:0;">
          Ticket #{{ $ticket->ticket_number }}
        </h1>
        <span style="font-size:0.8rem; font-weight:500; text-transform:uppercase; padding:4px 10px; border-radius:20px;
          background:{{ $ticket->status === 'closed' ? '#f1f5f9' : ($ticket->status === 'in_progress' ? '#fef3c7' : '#e0f2fe') }};
          color:{{ $ticket->status === 'closed' ? '#64748b' : ($ticket->status === 'in_progress' ? '#b45309' : '#0369a1') }};">
          ● {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
        </span>
        <span style="font-size:0.8rem; font-weight:500; text-transform:uppercase; padding:4px 10px; border-radius:20px;
          background:{{ $ticket->priority === 'urgent' ? '#fee2e2' : ($ticket->priority === 'high' ? '#ffedd5' : '#f8fafc') }};
          color:{{ $ticket->priority === 'urgent' ? '#dc2626' : ($ticket->priority === 'high' ? '#ea580c' : '#64748b') }}; border:1px solid #e2e8f0;">
          {{ ucfirst($ticket->priority) }} Priority
        </span>
      </div>
      <p style="font-size:1.1rem; color:#1e293b; font-weight:500; margin:6px 0 0;">
        {{ $ticket->subject }}
      </p>
    </div>

    <!-- Quick Status Toggles (Close, Open, Re-open) -->
    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      @if(auth('admin')->user()?->hasPermission('tickets.edit'))
        @if($ticket->status !== 'closed')
          @if($ticket->status !== 'in_progress')
            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" style="margin:0;">
              @csrf
              <input type="hidden" name="status" value="in_progress">
              <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="background:#fffbeb; color:#b45309; border-color:#fde68a;">
                Mark In Progress
              </button>
            </form>
          @endif
          <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" style="margin:0;">
            @csrf
            <input type="hidden" name="status" value="closed">
            <button type="submit" class="btn-paces btn-paces-sm" style="background:#10b981; color:#fff; border:none;">
              ✓ Close Ticket
            </button>
          </form>
        @else
          <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" style="margin:0;">
            @csrf
            <input type="hidden" name="status" value="open">
            <button type="submit" class="btn-paces btn-paces-sm" style="background:#f59e0b; color:#fff; border:none;">
              ↺ Re-open Ticket
            </button>
          </form>
        @endif

        <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to permanently delete this support ticket and conversation history?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-paces btn-paces-sm" style="background:#fee2e2; color:#dc2626; border:none;" title="Delete Ticket">
            🗑 Delete
          </button>
        </form>
      @else
        <span style="font-size:0.75rem; background:#f1f5f9; color:#64748b; padding:4px 10px; border-radius:12px; font-weight:500;">
          👁️ View Only Mode
        </span>
      @endif
    </div>
  </div>

  @if(session('success'))
    <div style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:12px 18px; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Step-by-Step Lifecycle Arrow Progress Bar -->
  <div class="card-paces" style="padding:16px 20px; margin-bottom:1.5rem;">
    <div style="font-size:0.75rem; font-weight:500; text-transform:uppercase; color:#64748b; margin-bottom:10px; letter-spacing:0.04em;">
      Operational Resolution Pipeline (Step-by-Step Flow):
    </div>

    @php
      $hasAdminReply = $ticket->messages->where('sender_type', 'admin')->isNotEmpty();
      $stepActive = $ticket->status === 'closed' ? 4 : ($hasAdminReply ? 3 : 2);
    @endphp

    <div class="admin-arrow-steps">
      <div class="admin-step completed">
        <span class="step-num">1</span>
        <span>Ticket Inbound</span>
        <span class="step-arrow">➔</span>
      </div>

      <div class="admin-step {{ $stepActive >= 2 ? 'completed' : 'pending' }}">
        <span class="step-num">2</span>
        <span>Ops Assigned</span>
        <span class="step-arrow">➔</span>
      </div>

      <div class="admin-step {{ $stepActive >= 3 ? 'completed' : 'pending' }}">
        <span class="step-num">3</span>
        <span>Troubleshooting Chat Flow</span>
        <span class="step-arrow">➔</span>
      </div>

      <div class="admin-step {{ $stepActive >= 4 ? 'completed' : 'pending' }}">
        <span class="step-num">4</span>
        <span>Resolution Sign-Off</span>
      </div>
    </div>
  </div>

  <!-- Main Grid: Conversation Thread & Sidebar Metadata -->
  <div style="display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start;" class="admin-ticket-grid">
    
    <!-- Left Column: Step-by-Step Message Chat Thread -->
    <div>
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3 style="font-size:1.15rem; font-weight:500; color:var(--paces-text-dark); margin:0;">
          Step-by-Step Conversation Flow
        </h3>
        <span style="font-size:0.8rem; color:#64748b;">
          {{ $ticket->messages->count() }} exchange {{ \Illuminate\Support\Str::plural('step', $ticket->messages->count()) }}
        </span>
      </div>

      <div class="chat-thread-container" style="display:flex; flex-direction:column; gap:0;">
        @foreach($ticket->messages as $msg)
          <!-- Single Step Message Card -->
          <div class="admin-message-card {{ $msg->sender_type === 'admin' ? 'admin-is-sender' : 'customer-is-sender' }}">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; padding-bottom:6px; border-bottom:1px solid rgba(0,0,0,0.06);">
              <div style="display:flex; align-items:center; gap:8px;">
                @if($msg->sender_type === 'admin')
                  <span style="background:#dcfce7; color:#166534; font-size:0.72rem; font-weight:500; padding:2px 8px; border-radius:12px;">
                    Step {{ $loop->iteration }} ➔ Admin Response
                  </span>
                @else
                  <span style="background:#e0e7ff; color:#3730a3; font-size:0.72rem; font-weight:500; padding:2px 8px; border-radius:12px;">
                    Step {{ $loop->iteration }} ➔ Client Message
                  </span>
                @endif
                <span style="font-weight:500; font-size:0.875rem; color:#1e293b;">
                  {{ $msg->sender_name }}
                </span>
              </div>
              <span style="font-size:0.75rem; color:#94a3b8;">
                {{ $msg->created_at->format('M d, Y • h:i A') }} ({{ $msg->created_at->diffForHumans() }})
              </span>
            </div>

            <div style="font-size:0.9rem; line-height:1.6; color:#334155;">
              {!! nl2br(e($msg->message)) !!}
            </div>
          </div>

          <!-- Directional Arrow Connector between Consecutive Steps -->
          @if(! $loop->last)
            <div style="display:flex; align-items:center; justify-content:center; margin:8px 0; gap:12px;">
              <div style="flex:1; height:1px; background:#e2e8f0;"></div>
              <div style="background:#f1f5f9; color:#64748b; font-size:0.7rem; font-weight:500; padding:2px 10px; border-radius:12px; border:1px dashed #cbd5e1;">
                ↓ Step {{ $loop->iteration }} ➔ Step {{ $loop->iteration + 1 }}
              </div>
              <div style="flex:1; height:1px; background:#e2e8f0;"></div>
            </div>
          @endif
        @endforeach
      </div>

      <!-- Admin Reply Box (Strictly text only, no image upload) -->
      @if(auth('admin')->user()?->hasPermission('tickets.edit'))
        <div class="card-paces" style="margin-top:1.5rem; padding:1.5rem;">
          <h4 style="font-size:1.05rem; font-weight:500; color:var(--paces-text-dark); margin:0 0 8px;">
            Send Admin Reply (Step {{ $ticket->messages->count() + 1 }} ➔)
          </h4>
          <p style="font-size:0.825rem; color:var(--paces-text-muted); margin-bottom:14px;">
            Dispatch operational instructions or updates to client. (Strictly text only — file/image uploads disabled).
          </p>

          <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
            @csrf

            <div style="margin-bottom:14px;">
              <textarea name="message" rows="4" required
                        placeholder="Write response message from operations center..."
                        style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.88rem; outline:none; font-family:inherit;"></textarea>
              @error('message')
                <div style="color:#ef4444; font-size:0.8rem; margin-top:4px;">{{ $message }}</div>
              @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
              <div style="display:flex; align-items:center; gap:8px;">
                <label for="status" style="font-size:0.8rem; font-weight:500; color:#475569;">Update Status to:</label>
                <select name="status" id="status" style="padding:6px 10px; font-size:0.8rem; border:1px solid #cbd5e1; border-radius:6px; background:#fff;">
                  <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                  <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                  <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed / Resolved</option>
                </select>
              </div>

              <button type="submit" class="btn-paces btn-paces-primary" style="padding:8px 18px; font-weight:500;">
                Post Step {{ $ticket->messages->count() + 1 }} Response ➔
              </button>
            </div>
          </form>
        </div>
      @else
        <div class="card-paces" style="margin-top:1.5rem; padding:1.25rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
          <div style="font-weight:500; color:#1e293b; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
            <span>👁️ Read-Only Mode</span>
          </div>
          <div style="font-size:0.825rem; color:#64748b; line-height:1.5;">
            You have view-only access to this support ticket. Posting replies or modifying ticket status requires Edit permissions.
          </div>
        </div>
      @endif

    </div>

    <!-- Right Column: Requester Metadata & Info -->
    <div>
      <div class="card-paces" style="padding:1.5rem; margin-bottom:16px;">
        <h4 style="font-size:0.95rem; font-weight:500; color:var(--paces-text-dark); margin:0 0 14px; text-transform:uppercase; letter-spacing:0.04em;">
          Client Information
        </h4>

        <div style="margin-bottom:12px;">
          <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase; font-weight:500;">Full Name</div>
          <div style="font-size:0.95rem; font-weight:500; color:#1e293b;">{{ $ticket->customer_name }}</div>
        </div>

        <div style="margin-bottom:12px;">
          <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase; font-weight:500;">Email Address</div>
          <div style="font-size:0.9rem; font-weight:500; color:var(--paces-primary);">{{ $ticket->customer_email }}</div>
        </div>

        @if($ticket->customer_phone)
          <div style="margin-bottom:12px;">
            <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase; font-weight:500;">Phone Number</div>
            <div style="font-size:0.9rem; font-weight:500; color:#1e293b;">
              <a href="tel:{{ $ticket->customer_phone }}" style="color:inherit; text-decoration:none;">{{ $ticket->customer_phone }}</a>
            </div>
          </div>
        @endif

        <div style="margin-bottom:14px;">
          <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase; font-weight:500; margin-bottom:4px;">Account Linkage</div>
          @if($ticket->user)
            <span style="display:inline-block; font-size:0.75rem; font-weight:500; background:#e0e7ff; color:#3730a3; padding:2px 8px; border-radius:12px;">
              ✓ Registered User
            </span>
            <div style="margin-top:6px;">
              <a href="{{ route('admin.users.show', $ticket->user) }}" class="btn-paces btn-paces-outline btn-paces-sm" style="font-size:0.75rem; padding:4px 8px;">
                View Client Profile ↗
              </a>
            </div>
          @else
            <span style="display:inline-block; font-size:0.75rem; font-weight:500; background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:12px;">
              Guest Email (Non-registered)
            </span>
          @endif
        </div>

        <div style="border-top:1px solid #f1f5f9; padding-top:12px; font-size:0.78rem; color:#64748b;">
          <div>Created via: <strong style="font-weight:500;">{{ ucfirst($ticket->created_by) }}</strong></div>
          <div>Initial Opened: <strong style="font-weight:500;">{{ $ticket->created_at->format('M d, Y') }}</strong></div>
          @if($ticket->closed_at)
            <div style="color:#10b981; font-weight:500; margin-top:2px;">Closed at: {{ $ticket->closed_at->format('M d, Y • h:i A') }}</div>
          @endif
        </div>
      </div>

      <!-- Quick Action: Create Another Ticket for this User -->
      @if($ticket->user)
        <a href="{{ route('admin.tickets.create', ['user_id' => $ticket->user->id]) }}" class="btn-paces btn-paces-outline" style="width:100%; display:block; text-align:center; padding:10px; font-size:0.85rem; font-weight:500;">
          + New Ticket for this Client
        </a>
      @endif
    </div>

  </div>

  <style>
    .admin-arrow-steps {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .admin-step {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 12px;
      border-radius: 16px;
      font-size: 0.78rem;
      font-weight: 500;
    }
    .admin-step.completed {
      background: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }
    .admin-step.pending {
      background: #f1f5f9;
      color: #94a3b8;
      border: 1px solid #e2e8f0;
    }
    .admin-step .step-num {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: currentColor;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.68rem;
      font-weight: 500;
    }
    .admin-step .step-arrow {
      margin-left: 4px;
      font-weight: 500;
      opacity: 0.6;
    }

    .admin-message-card {
      border-radius: 8px;
      padding: 1rem 1.25rem;
      border: 1px solid #e2e8f0;
      background: #ffffff;
    }
    .admin-message-card.customer-is-sender {
      border-left: 4px solid #4361ee;
      background: #f8fafc;
    }
    .admin-message-card.admin-is-sender {
      border-left: 4px solid #10b981;
      background: #f0fdf4;
    }

    @media (max-width: 900px) {
      .admin-ticket-grid {
        grid-template-columns: 1fr !important;
      }
    }
  </style>
</x-layouts.admin>
