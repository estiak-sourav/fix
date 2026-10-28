<x-layouts.admin title="AMC Packages | Web Content | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Content</h1>
      <p class="page-header-subtitle">Annual Maintenance Contracts (AMC) packages and scope specifications</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <a href="/" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
      <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddPlanModal()">
        + Add New Package
      </button>
    </div>
  </div>

  <x-admin.web-content-tabs active="amc_plans" />

  @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between; gap:8px;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:flex-start; justify-content:space-between; gap:8px;">
      <div>
        <div style="font-weight:500; margin-bottom:4px;">Please correct the following errors:</div>
        <ul style="margin:0; padding-left:1.25rem;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('div').remove()" style="background:none; border:none; color:inherit; cursor:pointer; padding:2px 6px; font-size:1.1rem; line-height:1; opacity:0.6; display:inline-flex; align-items:center;" title="Close" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  @endif

  <!-- Packages List Table Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; border-bottom:1px solid var(--paces-border); padding-bottom:1rem; margin-bottom:1rem;">
      <h3 class="card-title-paces" style="margin:0;">
        Available AMC Packages ({{ $plans->count() }})
      </h3>
      <div style="font-size:0.8rem; color:var(--paces-text-muted);">
        Click Details to inspect package specifications, or Edit to update features.
      </div>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces">
        <thead>
          <tr>
            <th>Package Name & Tier</th>
            <th>Contract Period</th>
            <th>Target Audience</th>
            <th>Features</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($plans as $plan)
            @php
              $planFeatures = is_array($plan->features) ? $plan->features : [];
              $tierLabel = match($plan->tier) {
                'corporate_enterprise' => 'Corporate Enterprise',
                'industrial_manufacturing' => 'Industrial / Factory',
                'residential' => 'Residential',
                default => ucfirst(str_replace('_', ' ', $plan->tier ?? 'Standard'))
              };
            @endphp
            <tr>
              <td>
                <div style="font-weight:500; font-size:0.92rem; color:var(--paces-text-dark);">
                  {{ $plan->name }}
                </div>
                <div style="display:flex; gap:6px; align-items:center; margin-top:4px;">
                  <span class="badge-pill-paces bg-info-subtle" style="font-size:0.72rem;">
                    {{ $tierLabel }}
                  </span>
                  @if($plan->is_popular)
                    <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.72rem;">
                      Featured
                    </span>
                  @endif
                </div>
              </td>
              <td>
                <div style="font-size:0.85rem; color:var(--paces-text-dark);">
                  {{ $plan->price_period ?: 'Custom Contract' }}
                </div>
              </td>
              <td>
                <div style="font-size:0.82rem; color:var(--paces-text-muted); max-width:220px; line-height:1.4;">
                  {{ $plan->target_audience ?: 'Commercial & Residential Facilities' }}
                </div>
              </td>
              <td>
                <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.78rem;">
                  {{ count($planFeatures) }} Features
                </span>
              </td>
              <td>
                <span class="badge-pill-paces {{ $plan->is_active ? 'bg-success-subtle' : 'bg-danger-subtle' }}" style="font-size:0.78rem;">
                  {{ $plan->is_active ? 'Active' : 'Deactivated' }}
                </span>
              </td>
              <td style="text-align:right;">
                <div style="display:inline-flex; gap:6px; align-items:center; justify-content:flex-end;">
                  <!-- Details Button -->
                  <button type="button" 
                          class="btn-paces btn-paces-outline btn-paces-sm" 
                          onclick="openDetailsModal({{ $plan->id }})" 
                          title="View Details">
                    Details
                  </button>

                  <!-- Direct Edit Button -->
                  <button type="button" 
                          class="btn-paces btn-paces-primary btn-paces-sm" 
                          onclick="openEditPlanModal({{ $plan->id }})" 
                          title="Edit Package">
                    Edit
                  </button>

                  <!-- Status Toggle Form -->
                  <form action="{{ route('admin.web.amc-plans.toggle-status', $plan) }}" method="POST" style="margin:0;">
                    @csrf
                    @if($plan->is_active)
                      <button type="submit" class="btn-paces btn-paces-sm" style="background:#fef3c7; border:1px solid #fde68a; color:#b45309;" title="Deactivate">
                        Disable
                      </button>
                    @else
                      <button type="submit" class="btn-paces btn-paces-sm" style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a;" title="Activate">
                        Enable
                      </button>
                    @endif
                  </form>

                  <!-- Delete Form -->
                  <form action="{{ route('admin.web.amc-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Permanently delete AMC package \'{{ $plan->name }}\'?');" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-paces btn-paces-sm" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;" title="Delete">
                      ✕
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:2rem; color:var(--paces-text-muted);">
                No AMC maintenance packages configured yet. Click "+ Add New Package" to get started.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ============================================== -->
  <!-- 1. DETAILS MODAL                               -->
  <!-- ============================================== -->
  <div id="planDetailsModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:620px;">
      <div class="modal-header-paces">
        <div>
          <h3 class="modal-title-paces" id="detailPlanName">Package Details</h3>
          <div style="display:flex; gap:6px; align-items:center; margin-top:4px;">
            <span class="badge-pill-paces bg-info-subtle" id="detailPlanTier" style="font-size:0.75rem;"></span>
            <span class="badge-pill-paces" id="detailPlanStatus" style="font-size:0.75rem;"></span>
            <span class="badge-pill-paces bg-primary-subtle" id="detailPlanPopular" style="font-size:0.75rem; display:none;">Featured</span>
          </div>
        </div>
        <button type="button" class="modal-close-btn" onclick="closeDetailsModal()">✕</button>
      </div>

      <div class="modal-body-paces" style="padding:1.5rem;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:1.25rem;">
          <div style="background:#f8fafc; padding:12px 14px; border-radius:8px; border:1px solid var(--paces-border);">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">Contract Period</div>
            <div style="font-size:0.92rem; font-weight:500; color:var(--paces-text-dark); margin-top:4px;" id="detailPlanPeriod"></div>
          </div>
          <div style="background:#f8fafc; padding:12px 14px; border-radius:8px; border:1px solid var(--paces-border);">
            <div style="font-size:0.75rem; color:var(--paces-text-muted); font-weight:500; text-transform:uppercase;">Target Customer Audience</div>
            <div style="font-size:0.92rem; font-weight:500; color:var(--paces-text-dark); margin-top:4px;" id="detailPlanAudience"></div>
          </div>
        </div>

        <div>
          <div style="font-size:0.85rem; font-weight:500; color:var(--paces-text-dark); margin-bottom:8px;">
            Included Features & Scope of Work:
          </div>
          <ul id="detailPlanFeaturesList" style="margin:0; padding-left:1.25rem; font-size:0.88rem; color:#334155; line-height:1.7;">
          </ul>
        </div>
      </div>

      <div class="modal-footer-paces">
        <button type="button" class="btn-paces btn-paces-outline" onclick="closeDetailsModal()">
          Close
        </button>
        <button type="button" class="btn-paces btn-paces-primary" onclick="proceedToEditFromDetails()">
          Edit This Package
        </button>
      </div>
    </div>
  </div>

  <!-- ============================================== -->
  <!-- 2. EDIT PACKAGE MODAL                          -->
  <!-- ============================================== -->
  <div id="planEditModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:680px;">
      <div class="modal-header-paces">
        <h3 class="modal-title-paces" id="editModalHeaderTitle">Edit AMC Package</h3>
        <button type="button" class="modal-close-btn" onclick="closeEditPlanModal()">✕</button>
      </div>

      <form id="editPlanForm" method="POST" action="">
        @csrf
        <div class="modal-body-paces" style="padding:1.5rem;">
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Package Name *</label>
              <input type="text" name="name" id="editPlanName" required class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Target Audience</label>
              <input type="text" name="target_audience" id="editPlanAudience" placeholder="e.g. Commercial Towers, Corporate Offices" class="form-control-paces">
            </div>
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Contract Period</label>
              <input type="text" name="price_period" id="editPlanPeriod" placeholder="e.g. Annual / Billed Monthly" class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Tier Classification</label>
              <select name="tier" id="editPlanTierSelect" class="form-control-paces">
                <option value="residential">Residential / Apartment</option>
                <option value="corporate_enterprise">Corporate Enterprise</option>
                <option value="industrial_manufacturing">Industrial / Factory</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Included Features (One feature per line)</label>
            <textarea name="features" id="editPlanFeatures" rows="5" class="form-control-paces" placeholder="24/7 dedicated facility engineer&#10;Monthly preventive electrical audit&#10;Zero emergency dispatch fees"></textarea>
          </div>

          <div style="background:#f8fafc; border-radius:8px; padding:12px 14px; border:1px solid var(--paces-border); display:flex; gap:20px; flex-wrap:wrap;">
            <label style="display:inline-flex; align-items:center; gap:8px; font-size:0.85rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_popular" id="editPlanPopular" value="1">
              <span>Highlight as Popular / Featured</span>
            </label>
            <label style="display:inline-flex; align-items:center; gap:8px; font-size:0.85rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" id="editPlanActive" value="1">
              <span>Active on Live Site</span>
            </label>
          </div>
        </div>

        <div class="modal-footer-paces">
          <button type="button" class="btn-paces btn-paces-outline" onclick="closeEditPlanModal()">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary">Save Package Changes</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================== -->
  <!-- 3. ADD NEW PACKAGE MODAL                       -->
  <!-- ============================================== -->
  <div id="addPlanModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:680px;">
      <div class="modal-header-paces">
        <h3 class="modal-title-paces">+ Create New AMC Package</h3>
        <button type="button" class="modal-close-btn" onclick="closeAddPlanModal()">✕</button>
      </div>

      <form action="{{ route('admin.web.amc-plans.store') }}" method="POST">
        @csrf
        <div class="modal-body-paces" style="padding:1.5rem;">
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Package Name *</label>
              <input type="text" name="name" required placeholder="e.g. Corporate Premium Facility" class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Target Audience</label>
              <input type="text" name="target_audience" placeholder="e.g. Commercial Towers, Corporate Offices" class="form-control-paces">
            </div>
          </div>

          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Contract Period</label>
              <input type="text" name="price_period" placeholder="e.g. Annual / Billed Monthly" class="form-control-paces">
            </div>
            <div>
              <label class="form-label-paces">Tier Classification</label>
              <select name="tier" class="form-control-paces">
                <option value="residential">Residential / Apartment</option>
                <option value="corporate_enterprise" selected>Corporate Enterprise</option>
                <option value="industrial_manufacturing">Industrial / Factory</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Included Features (One feature per line)</label>
            <textarea name="features" rows="5" class="form-control-paces" placeholder="24/7 dedicated facility engineer&#10;Monthly preventive electrical audit&#10;Zero emergency dispatch fees&#10;HVAC chiller maintenance included"></textarea>
          </div>

          <div style="background:#f8fafc; border-radius:8px; padding:12px 14px; border:1px solid var(--paces-border); display:flex; gap:20px; flex-wrap:wrap;">
            <label style="display:inline-flex; align-items:center; gap:8px; font-size:0.85rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_popular" value="1">
              <span>Highlight as Popular / Featured</span>
            </label>
            <label style="display:inline-flex; align-items:center; gap:8px; font-size:0.85rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" checked>
              <span>Active on Live Site</span>
            </label>
          </div>
        </div>

        <div class="modal-footer-paces">
          <button type="button" class="btn-paces btn-paces-outline" onclick="closeAddPlanModal()">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary">Create Package</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Package Data Store for Dynamic JavaScript Modals -->
  <script>
    const amcPlansData = @json($plans->keyBy('id'));
    let currentlyViewingPlanId = null;

    function openAddPlanModal() {
      document.getElementById('addPlanModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeAddPlanModal() {
      document.getElementById('addPlanModal').style.display = 'none';
      document.body.style.overflow = '';
    }

    function openDetailsModal(planId) {
      const plan = amcPlansData[planId];
      if (!plan) return;

      currentlyViewingPlanId = planId;

      document.getElementById('detailPlanName').innerText = plan.name;
      
      const tierMap = {
        'corporate_enterprise': 'Corporate Enterprise',
        'industrial_manufacturing': 'Industrial / Factory',
        'residential': 'Residential'
      };
      document.getElementById('detailPlanTier').innerText = tierMap[plan.tier] || (plan.tier || 'Standard');

      const statusEl = document.getElementById('detailPlanStatus');
      if (plan.is_active) {
        statusEl.className = 'badge-pill-paces bg-success-subtle';
        statusEl.innerText = 'Active';
      } else {
        statusEl.className = 'badge-pill-paces bg-danger-subtle';
        statusEl.innerText = 'Deactivated';
      }

      document.getElementById('detailPlanPopular').style.display = plan.is_popular ? 'inline-block' : 'none';
      document.getElementById('detailPlanPeriod').innerText = plan.price_period || 'Custom Contract';
      document.getElementById('detailPlanAudience').innerText = plan.target_audience || 'Commercial & Residential Facilities';

      const listEl = document.getElementById('detailPlanFeaturesList');
      listEl.innerHTML = '';
      const features = Array.isArray(plan.features) ? plan.features : [];
      if (features.length > 0) {
        features.forEach(f => {
          const li = document.createElement('li');
          li.innerText = f;
          listEl.appendChild(li);
        });
      } else {
        const li = document.createElement('li');
        li.style.color = '#94a3b8';
        li.innerText = 'No specific features listed for this tier.';
        listEl.appendChild(li);
      }

      document.getElementById('planDetailsModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
      document.getElementById('planDetailsModal').style.display = 'none';
      document.body.style.overflow = '';
    }

    function proceedToEditFromDetails() {
      if (!currentlyViewingPlanId) return;
      const idToEdit = currentlyViewingPlanId;
      closeDetailsModal();
      openEditPlanModal(idToEdit);
    }

    function openEditPlanModal(planId) {
      const plan = amcPlansData[planId];
      if (!plan) return;

      document.getElementById('editModalHeaderTitle').innerText = 'Edit AMC Package: ' + plan.name;
      document.getElementById('editPlanForm').action = '/admin/web-control/amc-plans/' + plan.id;
      document.getElementById('editPlanName').value = plan.name || '';
      document.getElementById('editPlanAudience').value = plan.target_audience || '';
      document.getElementById('editPlanPeriod').value = plan.price_period || '';
      document.getElementById('editPlanTierSelect').value = plan.tier || 'residential';

      const features = Array.isArray(plan.features) ? plan.features.join('\n') : '';
      document.getElementById('editPlanFeatures').value = features;
      document.getElementById('editPlanPopular').checked = Boolean(plan.is_popular);
      document.getElementById('editPlanActive').checked = Boolean(plan.is_active);

      document.getElementById('planEditModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeEditPlanModal() {
      document.getElementById('planEditModal').style.display = 'none';
      document.body.style.overflow = '';
    }

    // Close modal on backdrop click
    window.addEventListener('click', function(e) {
      if (e.target.classList.contains('modal-backdrop-paces')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
      }
    });

    // Close on Escape key
    window.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        ['planDetailsModal', 'planEditModal', 'addPlanModal'].forEach(id => {
          const el = document.getElementById(id);
          if (el) el.style.display = 'none';
        });
        document.body.style.overflow = '';
      }
    });
  </script>

  <!-- Modal Backdrop & Dialog Styles -->
  <style>
    .modal-backdrop-paces {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(15, 23, 42, 0.55);
      backdrop-filter: blur(4px);
      z-index: 1050;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      box-sizing: border-box;
      animation: fadeInPaces 0.18s ease-out;
    }

    .modal-dialog-paces {
      background: #ffffff;
      border-radius: 12px;
      width: 100%;
      box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.25);
      border: 1px solid var(--paces-border);
      display: flex;
      flex-direction: column;
      max-height: 90vh;
      overflow: hidden;
      animation: scaleUpPaces 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-header-paces {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--paces-border);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      background: #ffffff;
    }

    .modal-title-paces {
      margin: 0;
      font-size: 1.15rem;
      font-weight: 500;
      color: var(--paces-text-dark);
    }

    .modal-close-btn {
      background: none;
      border: none;
      font-size: 1.25rem;
      color: var(--paces-text-muted);
      cursor: pointer;
      padding: 2px 8px;
      line-height: 1;
      border-radius: 6px;
      transition: background 0.15s, color 0.15s;
    }

    .modal-close-btn:hover {
      background: #f1f5f9;
      color: var(--paces-text-dark);
    }

    .modal-body-paces {
      overflow-y: auto;
      flex: 1;
    }

    .modal-footer-paces {
      padding: 1rem 1.5rem;
      border-top: 1px solid var(--paces-border);
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      background: #f8fafc;
    }

    @keyframes fadeInPaces {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes scaleUpPaces {
      from { opacity: 0; transform: scale(0.96); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</x-layouts.admin>
