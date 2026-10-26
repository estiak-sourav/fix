<x-layouts.app title="Submit Support Ticket | FIX Helpdesk">
  <section class="page-hero">
    <div class="container">
      <div style="max-width:720px; margin:0 auto; text-align:center;">
        <span style="display:inline-block; font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; background:rgba(67, 97, 238, 0.12); color:var(--primary); padding:4px 12px; border-radius:20px; margin-bottom:12px;">
          24/7 ENGINEERING HELPDESK
        </span>
        <h1 style="font-size:2.2rem; font-weight:800; color:var(--text-main); margin-bottom:0.75rem;">
          Submit a Support Ticket
        </h1>
        <p style="font-size:1rem; color:var(--text-muted); line-height:1.6; margin:0;">
          Need assistance with ongoing facility maintenance, emergency engineering, or an existing service? Log a ticket below. Guests and registered clients can both generate tickets.
        </p>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0;">
    <div class="container">
      <div style="display:grid; grid-template-columns:1fr 340px; gap:32px; align-items:start;" class="support-grid-responsive">
        
        <!-- Main Ticket Form Card -->
        <div class="form-card" style="padding:2.25rem 2rem; border:1px solid var(--border); box-shadow:0 4px 20px rgba(0,0,0,0.04); border-radius:12px;">
          
          @if(session('success'))
            <div data-alert style="background:#ecfdf5; border:1px solid #34d399; color:#065f46; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
              <div style="display:flex; align-items:center; gap:10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>{{ session('success') }}</span>
              </div>
              <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
            </div>
          @endif

          @if($errors->any())
            <div data-alert style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; align-items:flex-start; justify-content:space-between;">
              <div>
                <strong style="display:block; margin-bottom:6px;">Please correct the highlighted fields:</strong>
                <ul style="margin:0; padding-left:1.25rem; font-size:0.875rem;">
                  @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
              </div>
              <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#991b1b; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
            </div>
          @endif

          <!-- Guest / Logged-in Notice Banner -->
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-left:4px solid var(--primary); padding:14px 18px; border-radius:8px; margin-bottom:1.75rem; font-size:0.875rem; color:#475569; line-height:1.5;">
            @auth
              <strong>Logged in as:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }}). This ticket will automatically be linked to your customer dashboard.
            @else
              <strong>No account needed:</strong> You can submit a ticket using your email address without logging in. You will receive real-time updates and can track your ticket at any time using your email and ticket reference.
            @endauth
          </div>

          <form action="{{ route('support.store') }}" method="POST" id="supportTicketForm">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px;" class="grid-2col-responsive">
              <!-- Name -->
              <div>
                <label for="name" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                  Your Full Name <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()?->name) }}" required
                       placeholder="e.g. Tanvir Ahmed"
                       style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; outline:none; transition:border 0.2s;"
                       onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">
              </div>

              <!-- Email -->
              <div>
                <label for="email" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                  Email Address <span style="color:#ef4444;">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                       placeholder="e.g. tanvir@example.com"
                       style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; outline:none; transition:border 0.2s;"
                       onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">
              </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px;" class="grid-2col-responsive">
              <!-- Phone -->
              <div>
                <label for="phone" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                  Phone Number (Optional)
                </label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()?->phone) }}"
                       placeholder="e.g. 017XXXXXXXX"
                       style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; outline:none; transition:border 0.2s;"
                       onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">
              </div>

              <!-- Priority -->
              <div>
                <label for="priority" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                  Priority Urgency
                </label>
                <select id="priority" name="priority"
                        style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; outline:none; background:#ffffff;">
                  <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low (General inquiry / Consultation)</option>
                  <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal (Standard maintenance inquiry)</option>
                  <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (Active operational interruption)</option>
                  <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent (Critical facility / Safety hazard)</option>
                </select>
              </div>
            </div>

            <!-- Subject -->
            <div style="margin-bottom:18px;">
              <label for="subject" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                Subject / Issue Title <span style="color:#ef4444;">*</span>
              </label>
              <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                     placeholder="Briefly state your inquiry, e.g. HVAC chiller leaking at Banani branch"
                     style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; outline:none; transition:border 0.2s;"
                     onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Problem Message Description (Strictly text only) -->
            <div style="margin-bottom:20px;">
              <label for="message" style="display:block; font-size:0.875rem; font-weight:600; color:#1e293b; margin-bottom:6px;">
                Detailed Description <span style="color:#ef4444;">*</span>
              </label>
              <textarea id="message" name="message" rows="6" required
                        placeholder="Please describe your facility problem, location details, error symptoms or questions in detail..."
                        style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem; outline:none; resize:vertical; font-family:inherit; transition:border 0.2s;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#cbd5e1'">{{ old('message') }}</textarea>
              <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem; color:#64748b; margin-top:4px;">
                <span>🔒 Secure text inquiry transmission</span>
                <span style="color:#94a3b8;">Attachments/Images disabled for ticket security</span>
              </div>
            </div>

            <button type="submit" class="btn btn-accent" style="width:100%; padding:13px; font-size:1rem; font-weight:700; border-radius:6px; cursor:pointer; display:flex; justify-content:center; align-items:center; gap:8px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
              <span>Submit Support Ticket ➔</span>
            </button>
          </form>
        </div>

        <!-- Right Side: Info & Track Ticket Box -->
        <div>
          <!-- Track Existing Ticket Card -->
          <div class="form-card" style="padding:1.5rem; border:1px solid var(--border); border-radius:10px; margin-bottom:20px; background:#ffffff;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <h3 style="font-size:1.05rem; font-weight:700; margin:0; color:var(--text-main);">Track Existing Ticket</h3>
            </div>
            <p style="font-size:0.85rem; color:var(--text-muted); line-height:1.5; margin-bottom:14px;">
              Already have a support ticket number? Look up real-time responses and engineering notes.
            </p>
            <a href="{{ route('support.track') }}" class="btn btn-outline-primary" style="display:block; text-align:center; padding:9px 14px; font-size:0.85rem; font-weight:600; border-radius:6px;">
              Track Ticket Status →
            </a>
          </div>

          <!-- Step-by-Step Flow Explanation Card -->
          <div class="form-card" style="padding:1.5rem; border:1px solid var(--border); border-radius:10px; background:#f8fafc;">
            <h3 style="font-size:0.95rem; font-weight:700; color:#1e293b; margin:0 0 12px 0; text-transform:uppercase; letter-spacing:0.04em;">
              How Ticket Resolution Works
            </h3>
            
            <div style="display:flex; flex-direction:column; gap:12px; font-size:0.825rem; color:#475569;">
              <div style="display:flex; align-items:flex-start; gap:8px;">
                <span style="background:#e0e7ff; color:#3730a3; font-weight:800; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">1</span>
                <div>
                  <strong>Ticket Logged ➔</strong> Immediate ticket number issued and notified to operations dispatch.
                </div>
              </div>

              <div style="display:flex; align-items:flex-start; gap:8px;">
                <span style="background:#e0e7ff; color:#3730a3; font-weight:800; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">2</span>
                <div>
                  <strong>Engineer Review ➔</strong> Assigned specialist assesses problem urgency and safety criteria.
                </div>
              </div>

              <div style="display:flex; align-items:flex-start; gap:8px;">
                <span style="background:#e0e7ff; color:#3730a3; font-weight:800; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">3</span>
                <div>
                  <strong>Interactive Flow ➔</strong> Step-by-step chat messages with instant email notifications.
                </div>
              </div>

              <div style="display:flex; align-items:flex-start; gap:8px;">
                <span style="background:#dcfce7; color:#166534; font-weight:800; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">✓</span>
                <div>
                  <strong>Resolution & Close ➔</strong> Issue resolved with complete audit trail.
                </div>
              </div>
            </div>
          </div>

          <!-- Hotline Card -->
          <div style="background:#1e293b; color:#ffffff; padding:1.25rem; border-radius:10px; margin-top:20px; font-size:0.85rem;">
            <div style="font-weight:700; margin-bottom:4px; font-size:0.95rem; color:#f8fafc;">🚨 Emergency Facility Support?</div>
            <p style="color:#94a3b8; margin-bottom:10px; font-size:0.8rem; line-height:1.4;">
              For immediate danger or flood / substation fire hazards, call our central dispatch hotline directly:
            </p>
            <a href="tel:{{ \App\Models\SiteSetting::get('emergency_phone', '+8801700000000') }}" style="color:#38bdf8; font-weight:700; font-size:1.05rem; text-decoration:none;">
              {{ \App\Models\SiteSetting::get('emergency_phone', '+880 1700-000000') }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <style>
    @media (max-width: 860px) {
      .support-grid-responsive {
        grid-template-columns: 1fr !important;
      }
      .grid-2col-responsive {
        grid-template-columns: 1fr !important;
      }
    }
  </style>
</x-layouts.app>
