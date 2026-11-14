<x-layouts.admin title="Official Receiving Payment Methods | FIX Admin">
  <style>
    /* Strict single line rule for all badges and buttons */
    .badge, .btn, span[style*="border-radius"], [class*="badge"], [class*="btn"], button, a.btn-paces {
      white-space: nowrap !important;
      flex-shrink: 0 !important;
      word-break: keep-all !important;
    }
  </style>

  <div class="page-header-box" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
    <div>
      <h1 class="page-header-title">Official Receiving Payment Methods</h1>
      <p style="margin:4px 0 0; font-size:0.85rem; color:var(--paces-text-muted);">
        Configure accounts (bKash, Nagad, Bank, Cash at Office) where crews settle client payments and dues.
      </p>
    </div>
    <div style="display:flex; gap:10px;">
      <a href="{{ route('admin.partners.index') }}" class="btn-paces btn-paces-outline" style="display:inline-flex; align-items:center; gap:6px;">
        ← Back to Field Crews
      </a>
      <button type="button" onclick="openAddModal()" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>Add Receiving Method</span>
      </button>
    </div>
  </div>

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 18px; border-radius:var(--paces-radius-sm); margin-bottom:1.5rem; font-weight:500; font-size:0.875rem; display:flex; align-items:center; justify-content:space-between; gap:8px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  <div class="card-paces" style="margin-bottom:1.5rem;">
    <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center;">
      <h3 class="card-title-paces" style="margin:0; font-size:1.05rem;">
        Active FIX Official Payment Methods ({{ $methods->count() }})
      </h3>
    </div>

    @if($methods->isEmpty())
      <div style="text-align:center; padding:3rem 1rem; color:var(--paces-text-muted);">
        <p style="font-size:1rem; margin-bottom:1rem;">No payment receiving methods configured yet.</p>
        <button type="button" onclick="openAddModal()" class="btn-paces btn-paces-primary">
          Add Official Method
        </button>
      </div>
    @else
      <div class="table-responsive-paces">
        <table class="table-paces">
          <thead>
            <tr>
              <th>Method</th>
              <th>Account Details</th>
              <th>Account Name / Office</th>
              <th>Instructions for Crew</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($methods as $m)
              <tr>
                <td>
                  <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight:700; font-size:0.9rem; color:#1e293b; text-transform:uppercase;">
                      {{ $m->method_type === 'offline' ? '💵 Cash / Offline' : $m->method_type }}
                    </span>
                    @if($m->is_default)
                      <span style="font-size:0.7rem; font-weight:700; background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:2px 8px; border-radius:10px;">
                        ★ Default
                      </span>
                    @endif
                  </div>
                  @if($m->number_type)
                    <span style="font-size:0.75rem; color:#64748b; text-transform:capitalize;">
                      {{ $m->number_type }} Account
                    </span>
                  @endif
                </td>
                <td>
                  @if($m->method_type === 'bank')
                    <div style="font-weight:600; color:#1e293b;">{{ $m->bank_name }}</div>
                    <div style="font-size:0.8rem; font-family:monospace; color:#3b82f6;">A/C: {{ $m->account_number }}</div>
                    @if($m->branch_name)
                      <div style="font-size:0.75rem; color:#64748b;">Branch: {{ $m->branch_name }}</div>
                    @endif
                  @elseif($m->method_type === 'offline')
                    <div style="font-weight:600; color:#047857;">In-Person Cash Deposit</div>
                    <div style="font-size:0.78rem; color:#64748b;">Ref Code: {{ $m->account_number ?: 'OFFLINE-HQ' }}</div>
                  @else
                    <div style="font-weight:700; font-family:monospace; font-size:0.92rem; color:#1e293b;">
                      {{ $m->account_number }}
                    </div>
                  @endif
                </td>
                <td>
                  <div style="font-weight:600; color:#334155;">{{ $m->account_name ?: 'FIX Accounts Desk' }}</div>
                </td>
                <td style="max-width:260px; font-size:0.8rem; color:#64748b;">
                  {{ $m->instructions ?: '—' }}
                </td>
                <td>
                  @if($m->is_active)
                    <span style="font-size:0.75rem; font-weight:700; background:#ecfdf5; color:#059669; padding:3px 9px; border-radius:8px;">
                      Active
                    </span>
                  @else
                    <span style="font-size:0.75rem; font-weight:700; background:#f1f5f9; color:#64748b; padding:3px 9px; border-radius:8px;">
                      Inactive
                    </span>
                  @endif
                </td>
                <td style="text-align:right;">
                  <div style="display:inline-flex; align-items:center; gap:6px;">
                    @if(!$m->is_default)
                      <form action="{{ route('admin.payment-methods.default', $m) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px; font-size:0.75rem;" title="Make Default">
                          Set Default
                        </button>
                      </form>
                    @endif

                    <button type="button" onclick="openEditModal({{ json_encode($m) }})" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px; font-size:0.75rem;">
                      Edit
                    </button>

                    <form action="{{ route('admin.payment-methods.destroy', $m) }}" method="POST" style="margin:0;" onsubmit="return confirm('Delete this payment method?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-paces btn-paces-outline btn-paces-sm" style="padding:4px 8px; font-size:0.75rem; color:#dc2626; border-color:#fca5a5;">
                        ✕
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

  {{-- Modal: Add Payment Method --}}
  <div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; border-radius:12px; max-width:540px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.2); overflow:hidden;">
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#1e293b;">Add Official Receiving Method</h3>
        <button type="button" onclick="closeAddModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b; line-height:1;">&times;</button>
      </div>
      <form action="{{ route('admin.payment-methods.store') }}" method="POST" style="padding:20px; margin:0;">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
          <div>
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Method Type *</label>
            <select name="method_type" id="add_method_type" class="form-control-paces" onchange="toggleMethodFields('add')" required>
              <option value="bkash">bKash</option>
              <option value="nagad">Nagad</option>
              <option value="rocket">Rocket</option>
              <option value="upay">Upay</option>
              <option value="bank">Bank Transfer</option>
              <option value="offline">Offline / Cash at Office</option>
            </select>
          </div>
          <div id="add_number_type_box">
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Number / Account Type</label>
            <select name="number_type" class="form-control-paces">
              <option value="merchant">Merchant</option>
              <option value="personal">Personal</option>
              <option value="agent">Agent</option>
            </select>
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
          <div id="add_account_number_box">
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;" id="add_acc_label">Account / Phone Number *</label>
            <input type="text" name="account_number" id="add_account_number" class="form-control-paces" placeholder="e.g. 017XXXXXXXX">
          </div>
          <div>
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Account Name / Label *</label>
            <input type="text" name="account_name" class="form-control-paces" value="FIX Operations" required>
          </div>
        </div>

        {{-- Bank Specific Fields --}}
        <div id="add_bank_fields" style="display:none; margin-bottom:12px;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
            <div>
              <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Bank Name</label>
              <input type="text" name="bank_name" class="form-control-paces" placeholder="e.g. The City Bank Ltd.">
            </div>
            <div>
              <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Branch Name</label>
              <input type="text" name="branch_name" class="form-control-paces" placeholder="e.g. Gulshan Branch">
            </div>
          </div>
          <div>
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Routing Number (Optional)</label>
            <input type="text" name="routing_number" class="form-control-paces" placeholder="e.g. 225261145">
          </div>
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Instructions for Crew</label>
          <textarea name="instructions" rows="2" class="form-control-paces" placeholder="e.g. Reference format, counter number, or office address for cash deposit..."></textarea>
        </div>

        <div style="margin-bottom:18px;">
          <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; cursor:pointer;">
            <input type="checkbox" name="is_default" value="1">
            <span>Set as default receiving payment method</span>
          </label>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeAddModal()" class="btn-paces btn-paces-outline">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary">Save Method</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal: Edit Payment Method --}}
  <div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#ffffff; border-radius:12px; max-width:540px; width:100%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.2); overflow:hidden;">
      <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:#1e293b;">Edit Receiving Method</h3>
        <button type="button" onclick="closeEditModal()" style="background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b; line-height:1;">&times;</button>
      </div>
      <form id="editForm" method="POST" style="padding:20px; margin:0;">
        @csrf
        @method('PUT')
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
          <div>
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Method Type *</label>
            <select name="method_type" id="edit_method_type" class="form-control-paces" onchange="toggleMethodFields('edit')" required>
              <option value="bkash">bKash</option>
              <option value="nagad">Nagad</option>
              <option value="rocket">Rocket</option>
              <option value="upay">Upay</option>
              <option value="bank">Bank Transfer</option>
              <option value="offline">Offline / Cash at Office</option>
            </select>
          </div>
          <div id="edit_number_type_box">
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Number / Account Type</label>
            <select name="number_type" id="edit_number_type" class="form-control-paces">
              <option value="merchant">Merchant</option>
              <option value="personal">Personal</option>
              <option value="agent">Agent</option>
            </select>
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
          <div id="edit_account_number_box">
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;" id="edit_acc_label">Account / Phone Number</label>
            <input type="text" name="account_number" id="edit_account_number" class="form-control-paces">
          </div>
          <div>
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Account Name / Label *</label>
            <input type="text" name="account_name" id="edit_account_name" class="form-control-paces" required>
          </div>
        </div>

        {{-- Bank Specific Fields --}}
        <div id="edit_bank_fields" style="display:none; margin-bottom:12px;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
            <div>
              <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Bank Name</label>
              <input type="text" name="bank_name" id="edit_bank_name" class="form-control-paces">
            </div>
            <div>
              <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Branch Name</label>
              <input type="text" name="branch_name" id="edit_branch_name" class="form-control-paces">
            </div>
          </div>
          <div>
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Routing Number (Optional)</label>
            <input type="text" name="routing_number" id="edit_routing_number" class="form-control-paces">
          </div>
        </div>

        <div style="margin-bottom:14px;">
          <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:4px;">Instructions for Crew</label>
          <textarea name="instructions" id="edit_instructions" rows="2" class="form-control-paces"></textarea>
        </div>

        <div style="display:flex; gap:18px; margin-bottom:18px;">
          <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; cursor:pointer;">
            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
            <span>Is Active</span>
          </label>
          <label style="display:flex; align-items:center; gap:8px; font-size:0.85rem; cursor:pointer;">
            <input type="checkbox" name="is_default" id="edit_is_default" value="1">
            <span>Is Default</span>
          </label>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeEditModal()" class="btn-paces btn-paces-outline">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary">Update Method</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function toggleMethodFields(prefix) {
      var type = document.getElementById(prefix + '_method_type').value;
      var bankBox = document.getElementById(prefix + '_bank_fields');
      var numTypeBox = document.getElementById(prefix + '_number_type_box');
      var accLabel = document.getElementById(prefix + '_acc_label');

      if (type === 'bank') {
        bankBox.style.display = 'block';
        numTypeBox.style.display = 'none';
        accLabel.innerText = 'Bank Account Number *';
      } else if (type === 'offline') {
        bankBox.style.display = 'none';
        numTypeBox.style.display = 'none';
        accLabel.innerText = 'Counter Code / Desk Ref';
      } else {
        bankBox.style.display = 'none';
        numTypeBox.style.display = 'block';
        accLabel.innerText = 'Mobile Wallet Number *';
      }
    }

    function openAddModal() {
      document.getElementById('addModal').style.display = 'flex';
      toggleMethodFields('add');
    }

    function closeAddModal() {
      document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(method) {
      document.getElementById('editForm').action = '/admin/payment-methods/' + method.id;
      document.getElementById('edit_method_type').value = method.method_type;
      document.getElementById('edit_number_type').value = method.number_type || 'merchant';
      document.getElementById('edit_account_number').value = method.account_number || '';
      document.getElementById('edit_account_name').value = method.account_name || '';
      document.getElementById('edit_bank_name').value = method.bank_name || '';
      document.getElementById('edit_branch_name').value = method.branch_name || '';
      document.getElementById('edit_routing_number').value = method.routing_number || '';
      document.getElementById('edit_instructions').value = method.instructions || '';
      document.getElementById('edit_is_active').checked = !!method.is_active;
      document.getElementById('edit_is_default').checked = !!method.is_default;
      document.getElementById('editModal').style.display = 'flex';
      toggleMethodFields('edit');
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }
  </script>
</x-layouts.admin>
