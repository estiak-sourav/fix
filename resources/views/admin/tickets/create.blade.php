<x-layouts.admin title="Create Support Ticket | FIX Admin">
  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
        <a href="{{ route('admin.tickets.index') }}" style="color:var(--paces-primary); text-decoration:none; font-size:0.85rem; font-weight:500;">
          ← Back to Support Tickets
        </a>
      </div>
      <h1 class="page-header-title">Generate Support Ticket from CMS</h1>
      <p class="page-header-subtitle">Initiate a direct support ticket for a registered client or any non-registered email address</p>
    </div>
  </div>

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:12px 18px; border-radius:8px; margin-bottom:1.5rem;">
      <strong style="display:block; margin-bottom:4px;">Please correct the highlighted fields:</strong>
      <ul style="margin:0; padding-left:1.25rem; font-size:0.85rem;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card-paces" style="max-width:820px; padding:2rem;">
    <form action="{{ route('admin.tickets.store') }}" method="POST" id="adminCreateTicketForm">
      @csrf

      <!-- Recipient Type Toggle -->
      <div style="margin-bottom:22px;">
        <label style="display:block; font-size:0.875rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:8px;">
          Ticket Recipient Target <span style="color:#ef4444;">*</span>
        </label>
        <div style="display:flex; gap:14px; flex-wrap:wrap;">
          <label style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid #cbd5e1; border-radius:8px; cursor:pointer; background:#fff; font-size:0.9rem; font-weight:500;">
            <input type="radio" name="recipient_type" value="registered" id="typeRegistered"
                   {{ old('recipient_type', $selectedUser ? 'registered' : 'registered') === 'registered' ? 'checked' : '' }}
                   onchange="toggleRecipientType()">
            <span>👤 Existing Registered User (From Client List)</span>
          </label>

          <label style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid #cbd5e1; border-radius:8px; cursor:pointer; background:#fff; font-size:0.9rem; font-weight:500;">
            <input type="radio" name="recipient_type" value="non_registered" id="typeNonRegistered"
                   {{ old('recipient_type') === 'non_registered' ? 'checked' : '' }}
                   onchange="toggleRecipientType()">
            <span>✉️ Non-Registered User Email Address</span>
          </label>
        </div>
      </div>

      <!-- Option 1: Registered User Dropdown Box -->
      <div id="registeredUserBox" style="margin-bottom:20px; background:#f8fafc; padding:16px 18px; border-radius:8px; border:1px solid #e2e8f0;">
        <label for="user_id" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
          Select Registered Client <span style="color:#ef4444;">*</span>
        </label>
        <select name="user_id" id="user_id" style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; background:#ffffff;">
          <option value="">-- Choose client from directory ({{ $users->count() }} clients) --</option>
          @foreach($users as $u)
            <option value="{{ $u->id }}" {{ (old('user_id') == $u->id || ($selectedUser && $selectedUser->id == $u->id)) ? 'selected' : '' }}>
              {{ $u->name }} — {{ $u->email }} {{ $u->company_name ? "({$u->company_name})" : '' }} {{ $u->phone ? "• {$u->phone}" : '' }}
            </option>
          @endforeach
        </select>
        <span style="font-size:0.75rem; color:#64748b; margin-top:4px; display:block;">
          The ticket will automatically appear in this client's user web dashboard.
        </span>
      </div>

      <!-- Option 2: Non-Registered User Manual Input Box -->
      <div id="nonRegisteredUserBox" style="display:none; margin-bottom:20px; background:#f8fafc; padding:16px 18px; border-radius:8px; border:1px solid #e2e8f0;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:12px;">
          <div>
            <label for="customer_name" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
              Contact Name <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                   placeholder="e.g. Asif Mahmud"
                   style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; background:#fff;">
          </div>
          <div>
            <label for="customer_email" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
              Email Address (Recipient) <span style="color:#ef4444;">*</span>
            </label>
            <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}"
                   placeholder="e.g. asif@propertymgmt.com"
                   style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; background:#fff;">
          </div>
        </div>
        <div>
          <label for="customer_phone" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
            Phone Number (Optional)
          </label>
          <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                 placeholder="e.g. 018XXXXXXXX"
                 style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; background:#fff;">
        </div>
        <span style="font-size:0.75rem; color:#64748b; margin-top:6px; display:block;">
          A unique tracking reference token will be generated for this email. If they later register an account with this email, the ticket history will seamlessly link.
        </span>
      </div>

      <!-- Priority & Subject Row -->
      <div style="display:grid; grid-template-columns:200px 1fr; gap:16px; margin-bottom:18px;">
        <div>
          <label for="priority" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
            Priority Urgency <span style="color:#ef4444;">*</span>
          </label>
          <select id="priority" name="priority" required
                  style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; background:#ffffff;">
            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
            <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
          </select>
        </div>

        <div>
          <label for="subject" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
            Subject / Ticket Title <span style="color:#ef4444;">*</span>
          </label>
          <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                 placeholder="e.g. Annual HVAC Servicing Schedule & Filter Replacement Inspection"
                 style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem;">
        </div>
      </div>

      <!-- Initial Message from Admin (Strictly text only) -->
      <div style="margin-bottom:24px;">
        <label for="message" style="display:block; font-size:0.85rem; font-weight:500; color:#1e293b; margin-bottom:6px;">
          Initial Message / Engineering Assessment <span style="color:#ef4444;">*</span>
        </label>
        <textarea id="message" name="message" rows="6" required
                  placeholder="Type the initial support message or instructions from the operations center..."
                  style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.92rem; outline:none; font-family:inherit; resize:vertical;">{{ old('message') }}</textarea>
        <div style="font-size:0.75rem; color:#94a3b8; margin-top:4px;">
          🔒 Strictly text content only. Image and file uploads are disabled by design.
        </div>
      </div>

      <!-- Buttons -->
      <div style="display:flex; justify-content:flex-end; gap:12px; align-items:center;">
        <a href="{{ route('admin.tickets.index') }}" class="btn-paces btn-paces-outline">Cancel</a>
        <button type="submit" class="btn-paces btn-paces-primary" style="padding:10px 24px; font-weight:500;">
          Create & Dispatch Ticket ➔
        </button>
      </div>
    </form>
  </div>

  <script>
    function toggleRecipientType() {
      const isRegistered = document.getElementById('typeRegistered').checked;
      const regBox = document.getElementById('registeredUserBox');
      const nonRegBox = document.getElementById('nonRegisteredUserBox');

      if (isRegistered) {
        regBox.style.display = 'block';
        nonRegBox.style.display = 'none';
      } else {
        regBox.style.display = 'none';
        nonRegBox.style.display = 'block';
      }
    }

    document.addEventListener('DOMContentLoaded', toggleRecipientType);
  </script>
</x-layouts.admin>
