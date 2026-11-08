<x-partner.layouts.app :title="'Technicians & Crews'">
  <div style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: var(--p-primary); letter-spacing: -0.02em;">Field Technicians</h1>
      </div>

      <div>
        <button type="button" onclick="document.getElementById('addCrewModal').style.display='flex'" class="btn-p btn-p-primary">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          <span>Add Technician</span>
        </button>
      </div>
    </div>
  </div>

  <div class="p-card">
    <div class="p-card-header">
      <h3 class="p-card-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <span>Team Roster ({{ $crews->count() }})</span>
      </h3>
    </div>

    @if($crews->isEmpty())
      <div style="text-align: center; padding: 48px 16px; color: var(--p-muted);">
        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 10px; color: #cbd5e1;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        <div style="font-weight: 600; font-size: 0.95rem; color: #475569;">No Technicians Listed</div>
      </div>
    @else
      <div class="p-table-wrapper">
        <table class="p-table">
          <thead>
            <tr>
              <th>Technician</th>
              <th>Phone</th>
              <th>Specialty</th>
              <th>Status</th>
              <th>Active Jobs</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($crews as $crew)
              @php
                $activeJobsCount = \App\Models\Booking::where('assigned_partner_crew_id', $crew->id)
                  ->whereIn('partner_job_status', ['assigned', 'accepted', 'on_the_way', 'in_progress'])
                  ->count();
              @endphp
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 9px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.82rem;">
                      {{ strtoupper(substr($crew->name, 0, 1)) }}
                    </div>
                    <strong style="color: var(--p-primary); font-size: 0.88rem;">{{ $crew->name }}</strong>
                  </div>
                </td>
                <td>
                  <a href="tel:{{ $crew->phone }}" style="color: var(--p-accent); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; font-size: 0.84rem;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    {{ $crew->phone }}
                  </a>
                </td>
                <td>
                  <span class="p-badge p-badge-neutral">{{ $crew->trade_skill ?: 'Technician' }}</span>
                </td>
                <td>
                  @if($crew->is_active)
                    <span class="p-badge p-badge-success">Active</span>
                  @else
                    <span class="p-badge p-badge-danger">Inactive</span>
                  @endif
                </td>
                <td>
                  @if($activeJobsCount > 0)
                    <span class="p-badge p-badge-warning">{{ $activeJobsCount }} active</span>
                  @else
                    <span style="color: var(--p-muted); font-size: 0.78rem;">Idle</span>
                  @endif
                </td>
                <td style="text-align: right;">
                  <div style="display: inline-flex; align-items: center; gap: 6px;">
                    <button type="button" onclick="editCrew({{ $crew->toJson() }})" class="btn-p btn-p-outline" style="padding: 4px 10px; font-size: 0.76rem;">
                      Edit
                    </button>
                    <form id="deleteCrewForm_{{ $crew->id }}" action="{{ route('partner.crews.destroy', $crew) }}" method="POST" style="margin: 0; display: inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn-p btn-p-outline" style="padding: 4px 8px; font-size: 0.76rem; color: #dc2626;" onclick="confirmDialog({ title: 'Remove Technician?', text: 'Remove {{ $crew->name }} from your active roster?', type: 'danger', confirmText: 'Remove', formId: 'deleteCrewForm_{{ $crew->id }}' })" title="Delete">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  {{-- Add Modal --}}
  <div id="addCrewModal" class="fix-dialog-backdrop" style="display: none;" onclick="if(event.target===this) this.style.display='none'">
    <div class="fix-dialog-box" style="text-align: left; max-width: 420px;">
      <h3 class="fix-dialog-title" style="margin-bottom: 14px;">Add New Technician</h3>

      <form id="storeCrewForm" action="{{ route('partner.crews.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 12px;">
          <label class="p-label">Full Name *</label>
          <input type="text" name="name" class="p-input" required placeholder="e.g. Md. Rahim Ali">
        </div>

        <div style="margin-bottom: 12px;">
          <label class="p-label">Mobile Number *</label>
          <input type="tel" name="phone" class="p-input" required placeholder="017XXXXXXXX">
        </div>

        <div style="margin-bottom: 18px;">
          <label class="p-label">Specialty</label>
          <input type="text" name="trade_skill" class="p-input" placeholder="e.g. HVAC, Electrical, Plumbing">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" onclick="document.getElementById('addCrewModal').style.display='none'" class="btn-p btn-p-outline">
            Cancel
          </button>
          <button type="button" class="btn-p btn-p-primary" onclick="confirmDialog({ title: 'Add Technician?', text: 'Add this technician to your company roster?', type: 'primary', confirmText: 'Save Technician', formId: 'storeCrewForm' })">
            Save
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Edit Modal --}}
  <div id="editCrewModal" class="fix-dialog-backdrop" style="display: none;" onclick="if(event.target===this) this.style.display='none'">
    <div class="fix-dialog-box" style="text-align: left; max-width: 420px;">
      <h3 class="fix-dialog-title" style="margin-bottom: 14px;">Edit Technician</h3>

      <form id="editCrewForm" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom: 12px;">
          <label class="p-label">Full Name *</label>
          <input type="text" name="name" id="editCrewName" class="p-input" required>
        </div>

        <div style="margin-bottom: 12px;">
          <label class="p-label">Mobile Number *</label>
          <input type="tel" name="phone" id="editCrewPhone" class="p-input" required>
        </div>

        <div style="margin-bottom: 12px;">
          <label class="p-label">Specialty</label>
          <input type="text" name="trade_skill" id="editCrewTradeSkill" class="p-input">
        </div>

        <div style="margin-bottom: 18px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.84rem; font-weight: 600; color: #1e293b;">
            <input type="checkbox" name="is_active" id="editCrewIsActive" value="1" style="accent-color: var(--p-accent); width: 16px; height: 16px;">
            <span>Active & Available for Work</span>
          </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" onclick="document.getElementById('editCrewModal').style.display='none'" class="btn-p btn-p-outline">
            Cancel
          </button>
          <button type="button" class="btn-p btn-p-primary" onclick="confirmDialog({ title: 'Update Technician?', text: 'Confirm saving edits for this technician.', type: 'primary', confirmText: 'Save Changes', formId: 'editCrewForm' })">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function editCrew(crew) {
      document.getElementById('editCrewForm').action = '/partner/crews/' + crew.id;
      document.getElementById('editCrewName').value = crew.name || '';
      document.getElementById('editCrewPhone').value = crew.phone || '';
      document.getElementById('editCrewTradeSkill').value = crew.trade_skill || '';
      document.getElementById('editCrewIsActive').checked = crew.is_active ? true : false;
      document.getElementById('editCrewModal').style.display = 'flex';
    }
  </script>
</x-partner.layouts.app>
