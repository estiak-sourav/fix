<x-partner.layouts.app :title="'Job #' . $booking->booking_number">
  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
          <a href="{{ route('partner.jobs.index') }}" class="btn-p btn-p-outline" style="padding: 5px 10px; font-size: 0.78rem;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>Jobs</span>
          </a>
          <span style="font-size: 0.8rem; color: var(--p-muted);">Assigned {{ $booking->partner_assigned_at ? $booking->partner_assigned_at->format('M d, Y') : $booking->created_at->format('M d, Y') }}</span>
        </div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em;">Job #{{ $booking->booking_number }}</h1>
      </div>

      <div>
        @php
          $badge = match($booking->partner_job_status) {
            'assigned' => ['class' => 'p-badge-warning', 'label' => 'Awaiting Acceptance'],
            'accepted' => ['class' => 'p-badge-info', 'label' => 'Accepted'],
            'on_the_way' => ['class' => 'p-badge-purple', 'label' => 'En Route'],
            'in_progress' => ['class' => 'p-badge-success', 'label' => 'In Progress'],
            'completed' => ['class' => 'p-badge-success', 'label' => 'Completed'],
            'declined' => ['class' => 'p-badge-danger', 'label' => 'Declined'],
            default => ['class' => 'p-badge-neutral', 'label' => ucfirst($booking->partner_job_status ?? 'Assigned')],
          };
        @endphp
        <span class="p-badge {{ $badge['class'] }}" style="font-size: 0.85rem; padding: 6px 14px;">
          {{ $badge['label'] }}
        </span>
      </div>
    </div>
  </div>

  {{-- Top Action Banner for pending acceptance --}}
  @if($booking->partner_job_status === 'assigned')
    <div class="p-card" style="border-left: 4px solid var(--p-warning); background: #fffdf5; padding: 16px 20px; margin-bottom: 20px;">
      <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
        <div>
          <h3 style="font-size: 0.98rem; font-weight: 700; color: #92400e; margin: 0;">New Job Assignment</h3>
          <p style="font-size: 0.82rem; color: #78350f; margin-top: 2px;">Review details and confirm acceptance.</p>
        </div>
        <div style="display: flex; gap: 8px;">
          <form id="acceptJobForm" action="{{ route('partner.jobs.accept', $booking) }}" method="POST" style="margin: 0;">
            @csrf
            <button type="button" class="btn-p btn-p-primary" onclick="confirmDialog({ title: 'Accept Job #{{ $booking->booking_number }}?', text: 'Confirm accepting this service assignment.', type: 'success', confirmText: 'Accept Job', formId: 'acceptJobForm' })">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
              <span>Accept Job</span>
            </button>
          </form>
          <button type="button" class="btn-p btn-p-outline" style="color: #dc2626; border-color: #fca5a5;" onclick="document.getElementById('declineModal').style.display='flex'">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <span>Decline</span>
          </button>
        </div>
      </div>
    </div>
  @endif

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
    {{-- Left Column: Execution & Service Scope --}}
    <div style="display: flex; flex-direction: column; gap: 18px;">

      {{-- Field Status Step Updater --}}
      @if(in_array($booking->partner_job_status, ['accepted', 'on_the_way', 'in_progress']))
        <div class="p-card" style="border: 2px solid var(--p-accent);">
          <h3 class="p-card-title" style="margin-bottom: 14px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            <span>Update Status</span>
          </h3>

          @if($booking->partner_job_status === 'accepted')
            <form id="startJourneyForm" action="{{ route('partner.jobs.status', $booking) }}" method="POST" style="margin: 0;">
              @csrf
              <input type="hidden" name="partner_job_status" value="on_the_way">
              <button type="button" class="btn-p btn-p-primary" style="width: 100%; padding: 11px; font-size: 0.9rem;" onclick="confirmDialog({ title: 'Start Journey?', text: 'Mark crew as en route to client location.', type: 'primary', confirmText: 'Confirm On The Way', formId: 'startJourneyForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                <span>Start Journey (En Route)</span>
              </button>
            </form>
          @elseif($booking->partner_job_status === 'on_the_way')
            <form id="startWorkForm" action="{{ route('partner.jobs.status', $booking) }}" method="POST" style="margin: 0;">
              @csrf
              <input type="hidden" name="partner_job_status" value="in_progress">
              <button type="button" class="btn-p btn-p-success" style="width: 100%; padding: 11px; font-size: 0.9rem;" onclick="confirmDialog({ title: 'Start Work On Site?', text: 'Confirm team has arrived and started work.', type: 'success', confirmText: 'Confirm In Progress', formId: 'startWorkForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                <span>Arrived & Start Work (In Progress)</span>
              </button>
            </form>
          @elseif($booking->partner_job_status === 'in_progress')
            <form id="completeJobForm" action="{{ route('partner.jobs.status', $booking) }}" method="POST" enctype="multipart/form-data" style="margin: 0;">
              @csrf
              <input type="hidden" name="partner_job_status" value="completed">

              @if($partner->earning_model === 'hourly')
                <div style="margin-bottom: 12px;">
                  <label class="p-label">Total Worked Hours on Site *</label>
                  <input type="number" step="0.25" min="0.25" name="partner_worked_hours" class="p-input" value="{{ old('partner_worked_hours', $booking->partner_worked_hours ?: 1.0) }}" placeholder="e.g. 2.5" required>
                  <span style="font-size: 0.72rem; color: var(--p-muted);">Your rate: {{ $partner->formatted_rate }}</span>
                </div>
              @endif

              <div style="margin-bottom: 12px;">
                <label class="p-label">After-Service Photos (Optional)</label>
                <input type="file" name="completion_photos[]" multiple accept="image/*" class="p-input" style="height: auto; padding: 7px;">
              </div>

              <div style="margin-bottom: 14px;">
                <label class="p-label">Completion Remarks</label>
                <textarea name="partner_notes" rows="2" class="p-input" style="height: auto; resize: vertical;" placeholder="Work summary or replaced parts...">{{ old('partner_notes', $booking->partner_notes) }}</textarea>
              </div>

              <button type="button" class="btn-p btn-p-success" style="width: 100%; padding: 11px; font-size: 0.9rem;" onclick="confirmDialog({ title: 'Complete Service Job?', text: 'Confirm all work on site is finished.', type: 'success', confirmText: 'Complete Job', formId: 'completeJobForm' })">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>Complete Job</span>
              </button>
            </form>
          @endif
        </div>
      @endif

      {{-- Service Requirements Card --}}
      <div class="p-card">
        <h3 class="p-card-title" style="margin-bottom: 14px; border-bottom: 1px solid var(--p-border); padding-bottom: 8px;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          <span>Service Details</span>
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 14px;">
          <div>
            <span style="font-size: 0.74rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Service</span>
            <div style="font-size: 0.95rem; font-weight: 700; color: var(--p-primary); margin-top: 2px;">
              {{ $booking->service?->name ?? $booking->amcPlan?->name ?? 'Facility Maintenance' }}
            </div>
            @if($booking->service?->category)
              <span style="font-size: 0.78rem; color: var(--p-accent); font-weight: 600;">{{ $booking->service->category->name }}</span>
            @endif
          </div>

          <div>
            <span style="font-size: 0.74rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Schedule</span>
            <div style="font-size: 0.95rem; font-weight: 700; color: var(--p-primary); margin-top: 2px;">
              {{ $booking->preferred_date ? \Carbon\Carbon::parse($booking->preferred_date)->format('M d, Y') : 'ASAP' }}
            </div>
            <span style="font-size: 0.8rem; color: var(--p-muted);">
              {{ $booking->arrival_window ?: ($booking->preferred_time ?: 'Standard Hours') }}
            </span>
          </div>
        </div>

        <div style="margin-bottom: 14px;">
          <span style="font-size: 0.74rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Problem Description</span>
          <div style="margin-top: 5px; padding: 12px 14px; background: #f8fafc; border-radius: 8px; border: 1px solid var(--p-border); font-size: 0.88rem; color: #334155; line-height: 1.45;">
            {{ $booking->problem_description ?: ($booking->notes ?: 'No description provided.') }}
          </div>
        </div>

        {{-- Problem Images --}}
        @if(!empty($booking->images) && is_array($booking->images) && count($booking->images) > 0)
          <div style="margin-bottom: 14px;">
            <span style="font-size: 0.74rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Client Photos</span>
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px;">
              @foreach($booking->images as $img)
                <a href="{{ asset('storage/' . $img) }}" target="_blank">
                  <img src="{{ asset('storage/' . $img) }}" alt="Issue" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid var(--p-border);">
                </a>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Completion Proof Photos --}}
        @if(!empty($booking->completion_photos) && is_array($booking->completion_photos) && count($booking->completion_photos) > 0)
          <div style="border-top: 1px solid var(--p-border); padding-top: 14px; margin-top: 14px;">
            <span style="font-size: 0.74rem; font-weight: 700; color: #059669; text-transform: uppercase;">Completion Proof</span>
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px;">
              @foreach($booking->completion_photos as $cImg)
                <a href="{{ asset(str_starts_with($cImg, 'storage/') ? $cImg : 'storage/' . $cImg) }}" target="_blank">
                  <img src="{{ asset(str_starts_with($cImg, 'storage/') ? $cImg : 'storage/' . $cImg) }}" alt="Proof" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #10b981;">
                </a>
              @endforeach
            </div>
            @if($booking->partner_notes)
              <div style="margin-top: 8px; font-size: 0.82rem; color: #475569;">
                <strong>Remarks:</strong> {{ $booking->partner_notes }}
              </div>
            @endif
          </div>
        @endif
      </div>

      {{-- Client Rating & Review Card --}}
      @if($booking->review_rating)
        <div class="p-card" style="border: 2px solid #fde68a; background: #fffdf5;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 32px; height: 32px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
              </div>
              <div>
                <h3 style="font-size: 0.96rem; font-weight: 800; color: #78350f; margin: 0;">Client Review</h3>
                <span style="font-size: 0.74rem; color: #b45309;">Verified customer feedback</span>
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 5px; background: #ffffff; padding: 4px 10px; border-radius: 20px; border: 1px solid #fde68a;">
              <span style="color: #f59e0b; font-size: 1.05rem; line-height: 1;">
                @for($i = 1; $i <= 5; $i++)
                  {{ $i <= $booking->review_rating ? '★' : '☆' }}
                @endfor
              </span>
              <strong style="color: #92400e; font-size: 0.86rem; margin-left: 2px;">{{ $booking->review_rating }}.0</strong>
            </div>
          </div>

          <div style="background: #ffffff; border-radius: 8px; border: 1px solid #fef3c7; padding: 12px 14px; margin-bottom: 10px;">
            <p style="margin: 0; font-size: 0.88rem; color: #334155; line-height: 1.5; font-style: italic;">
              "{{ $booking->review_comment }}"
            </p>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #92400e;">
            <span>Client: <strong>{{ $booking->customer_name ?: $booking->name }}</strong></span>
            <span>{{ $booking->reviewed_at ? $booking->reviewed_at->format('M d, Y • h:i A') : 'Recently' }}</span>
          </div>
        </div>
      @elseif($booking->status === 'completed' || $booking->partner_job_status === 'completed')
        <div class="p-card" style="background: #f8fafc; border: 1px dashed var(--p-border); padding: 14px 18px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <div>
              <div style="font-weight: 700; font-size: 0.88rem; color: #475569;">Awaiting Client Review</div>
              <div style="font-size: 0.78rem; color: var(--p-muted); margin-top: 2px;">The client has not yet submitted a rating or feedback for this service.</div>
            </div>
          </div>
        </div>
      @endif
    </div>

    {{-- Right Column: Client & Crew Assignment --}}
    <div style="display: flex; flex-direction: column; gap: 18px;">
      {{-- Client Details Card --}}
      <div class="p-card">
        <h3 class="p-card-title" style="margin-bottom: 14px;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span>Site & Contact</span>
        </h3>

        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.85rem;">
          <div>
            <span style="font-size: 0.72rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Customer</span>
            <div style="font-weight: 700; color: var(--p-primary); font-size: 0.92rem;">{{ $booking->customer_name ?: $booking->name }}</div>
            @if($booking->company_name)
              <div style="font-size: 0.78rem; color: var(--p-muted);">{{ $booking->company_name }}</div>
            @endif
          </div>

          <div>
            <span style="font-size: 0.72rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Phone</span>
            <div>
              <a href="tel:{{ $booking->customer_phone ?: $booking->phone }}" style="color: var(--p-accent); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                {{ $booking->customer_phone ?: $booking->phone }}
              </a>
            </div>
          </div>

          <div style="border-top: 1px solid var(--p-border); padding-top: 10px;">
            <span style="font-size: 0.72rem; font-weight: 700; color: var(--p-muted); text-transform: uppercase;">Address</span>
            <div style="font-weight: 600; color: var(--p-primary); margin-top: 2px;">{{ $booking->street_address ?: $booking->address }}</div>
            <div style="color: var(--p-muted); font-size: 0.8rem; margin-top: 1px;">{{ $booking->area ?: 'Dhaka Area' }}</div>
          </div>

          @if($booking->arrival_window)
            <div style="background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 8px; padding: 10px 12px;">
              <span style="font-size: 0.7rem; font-weight: 700; color: var(--p-accent); text-transform: uppercase;">Arrival Slot</span>
              <div style="font-weight: 700; color: var(--p-primary); font-size: 0.88rem; margin-top: 1px;">{{ $booking->arrival_window }}</div>
            </div>
          @endif
        </div>
      </div>

      {{-- Internal Crew Assignment (Vendors) --}}
      @if($partner->isVendor())
        <div class="p-card">
          <h3 class="p-card-title" style="margin-bottom: 12px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span>Assigned Technician</span>
          </h3>

          @if($booking->assignedCrew)
            <div style="background: #f8fafc; border: 1px solid var(--p-border); padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
              <div>
                <strong style="color: var(--p-primary); font-size: 0.86rem;">{{ $booking->assignedCrew->name }}</strong>
                <div style="font-size: 0.74rem; color: var(--p-muted);">{{ $booking->assignedCrew->trade_skill ?: 'Tech' }} • {{ $booking->assignedCrew->phone }}</div>
              </div>
              <span class="p-badge p-badge-success">Assigned</span>
            </div>
          @endif

          <form id="assignCrewForm" action="{{ route('partner.jobs.crew', $booking) }}" method="POST">
            @csrf
            <div style="margin-bottom: 10px;">
              <select name="assigned_partner_crew_id" class="p-input" style="font-size: 0.84rem;">
                <option value="">-- Select Technician --</option>
                @foreach($internalCrews as $crew)
                  <option value="{{ $crew->id }}" {{ $booking->assigned_partner_crew_id == $crew->id ? 'selected' : '' }}>
                    {{ $crew->name }} ({{ $crew->trade_skill ?: 'Tech' }})
                  </option>
                @endforeach
              </select>
            </div>
            <button type="button" class="btn-p btn-p-outline" style="width: 100%; font-size: 0.82rem; padding: 8px;" onclick="confirmDialog({ title: 'Assign Technician?', text: 'Confirm assigning selected crew member to this job.', type: 'primary', confirmText: 'Assign', formId: 'assignCrewForm' })">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
              <span>Save Assignment</span>
            </button>
          </form>
        </div>
      @endif

      {{-- Financial Summary --}}
      <div class="p-card">
        <h3 class="p-card-title" style="margin-bottom: 12px;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          <span>Earnings Summary</span>
        </h3>
        <div style="display: flex; justify-content: space-between; font-size: 0.84rem; margin-bottom: 8px;">
          <span style="color: var(--p-muted);">Earning Model:</span>
          <span style="font-weight: 700; color: var(--p-primary);">{{ $partner->earning_model_label }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.84rem; margin-bottom: 8px;">
          <span style="color: var(--p-muted);">Rate:</span>
          <span style="font-weight: 700; color: var(--p-primary);">{{ $partner->formatted_rate }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.84rem; margin-bottom: 10px;">
          <span style="color: var(--p-muted);">Job Total:</span>
          <span style="font-weight: 700; color: var(--p-primary);">৳ {{ number_format($booking->final_cost ?? $booking->estimated_cost ?? 0, 2) }}</span>
        </div>
        <div style="border-top: 1px solid var(--p-border); padding-top: 10px; display: flex; justify-content: space-between; font-size: 0.95rem;">
          <span style="font-weight: 700; color: var(--p-primary);">Est. Net Payout:</span>
          <span style="font-weight: 800; color: var(--p-accent);">৳ {{ number_format($partner->calculateBookingEarning($booking), 2) }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Decline Dialog Modal with Alert Confirmation --}}
  <div id="declineModal" class="fix-dialog-backdrop" style="display: none;" onclick="if(event.target===this) this.style.display='none'">
    <div class="fix-dialog-box" style="text-align: left;">
      <div class="fix-dialog-icon" style="background: #fef2f2; color: #ef4444; margin: 0 auto 12px;">
        <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
      </div>
      <h3 class="fix-dialog-title" style="text-align: center; color: #dc2626;">Decline Job</h3>
      <p class="fix-dialog-text" style="text-align: center; margin-bottom: 14px;">Select reason to decline Job #{{ $booking->booking_number }}:</p>

      <form id="declineJobForm" action="{{ route('partner.jobs.decline', $booking) }}" method="POST">
        @csrf
        <div style="margin-bottom: 16px;">
          <select name="decline_reason" class="p-input" required>
            <option value="Already engaged in another active service">Already engaged in another service</option>
            <option value="Technician on emergency leave / unavailable">Technician unavailable</option>
            <option value="Outside my active service coverage area">Outside service area</option>
            <option value="Required specialized tools/parts not on hand">Required tools/parts not on hand</option>
            <option value="Other scheduling conflict">Scheduling conflict</option>
          </select>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" onclick="document.getElementById('declineModal').style.display='none'" class="btn-p btn-p-outline">
            Cancel
          </button>
          <button type="button" class="btn-p btn-p-danger" onclick="confirmDialog({ title: 'Confirm Decline?', text: 'Are you sure you want to decline this job assignment?', type: 'danger', confirmText: 'Confirm Decline', formId: 'declineJobForm' })">
            Decline Job
          </button>
        </div>
      </form>
    </div>
  </div>
</x-partner.layouts.app>
