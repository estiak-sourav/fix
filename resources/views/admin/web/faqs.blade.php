<x-layouts.admin title="FAQ's | FIX Admin">
  <div class="page-header-box" style="margin-bottom:1.25rem;">
    <div>
      <h1 class="page-header-title">Web Content</h1>
      <p class="page-header-subtitle">Manage customer frequently asked questions, answers, and category classifications</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <a href="{{ \App\Models\SiteSetting::mainSiteUrl('#faq') }}" target="_blank" class="btn-paces btn-paces-outline btn-paces-sm" style="display:inline-flex; align-items:center; gap:6px;">
        <span>View Live Website</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
      </a>
      @if(auth('admin')->user()?->hasPermission('web_content.edit'))
        <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddFaqModal()" style="display:inline-flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          <span>Add New FAQ</span>
        </button>
      @endif
    </div>
  </div>

  <x-admin.web-content-tabs active="faqs" />

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#16a34a; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  @if($errors->any())
    <div data-alert style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:flex-start; justify-content:space-between;">
      <div>
        <div style="font-weight:600; margin-bottom:4px;">Please correct the following errors:</div>
        <ul style="margin:0; padding-left:1.25rem; font-size:0.88rem;">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Existing FAQs List Table Card -->
  <div class="card-paces">
    <div class="card-header-paces" style="border-bottom:1px solid var(--paces-border); padding-bottom:0.85rem; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
      <div>
        <h3 class="card-title-paces" style="margin:0;">
          FAQs Knowledgebase List ({{ $faqs->total() }})
        </h3>
        <p style="margin:3px 0 0; font-size:0.82rem; color:var(--paces-text-muted);">
          All published questions, answers, and category groupings for the public help center
        </p>
      </div>
    </div>

    <div class="table-responsive-paces">
      <table class="table-paces">
        <thead>
          <tr>
            <th style="width:140px;">Category</th>
            <th style="width:280px;">Question</th>
            <th>Detailed Answer</th>
            <th style="width:80px; text-align:center;">Order</th>
            <th style="width:110px;">Status</th>
            <th style="width:110px; text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($faqs as $faq)
            @php
              $faqData = [
                'id' => $faq->id,
                'category' => $faq->category,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'order' => $faq->order,
                'is_active' => (bool)$faq->is_active,
                'update_url' => route('admin.web.faqs.update', $faq),
                'delete_url' => route('admin.web.faqs.destroy', $faq),
              ];
            @endphp
            <tr>
              <td>
                <span class="badge-pill-paces bg-primary-subtle" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.03em;">
                  {{ $faq->category }}
                </span>
              </td>
              <td>
                <div style="font-weight:600; color:var(--paces-text-dark); font-size:0.9rem; line-height:1.4;">
                  {{ $faq->question }}
                </div>
              </td>
              <td>
                <p style="margin:0; font-size:0.85rem; color:#475569; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                  {{ $faq->answer }}
                </p>
              </td>
              <td style="text-align:center;">
                <span style="font-size:0.82rem; font-weight:600; color:var(--paces-text-muted);">
                  {{ $faq->order }}
                </span>
              </td>
              <td>
                @if($faq->is_active)
                  <span class="badge-pill-paces bg-success-subtle" style="font-size:0.72rem;">● Active</span>
                @else
                  <span class="badge-pill-paces bg-danger-subtle" style="font-size:0.72rem;">○ Inactive</span>
                @endif
              </td>
              <td style="text-align:right;">
                @if(auth('admin')->user()?->hasPermission('web_content.edit'))
                  <button type="button" 
                          class="btn-paces btn-paces-primary btn-paces-sm" 
                          onclick="openEditFaqModal({{ json_encode($faqData) }})"
                          style="display:inline-flex; align-items:center; gap:5px; padding:0.35rem 0.75rem;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Edit</span>
                  </button>
                @else
                  <span style="font-size:0.75rem; color:var(--paces-text-muted);">View Only</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--paces-text-muted);">
                <div style="font-size:1.8rem; margin-bottom:8px;">❓</div>
                <div style="font-weight:500;">No FAQs found.</div>
                <p style="margin:4px 0 12px; font-size:0.82rem;">Add answers to common customer questions.</p>
                @if(auth('admin')->user()?->hasPermission('web_content.edit'))
                  <button type="button" class="btn-paces btn-paces-primary btn-paces-sm" onclick="openAddFaqModal()">
                    Add New FAQ
                  </button>
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($faqs->hasPages())
      <div style="margin-top:1.25rem;">
        {{ $faqs->links() }}
      </div>
    @endif
  </div>

  <!-- ============================================== -->
  <!-- 1. ADD FAQ MODAL                               -->
  <!-- ============================================== -->
  <div id="addFaqModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:620px;">
      <div class="modal-header-paces">
        <h3 class="modal-title-paces">Add New FAQ</h3>
        <button type="button" class="modal-close-btn" onclick="closeAddFaqModal()">✕</button>
      </div>

      <form action="{{ route('admin.web.faqs.store') }}" method="POST">
        @csrf
        <div class="modal-body-paces">
          <div style="display:grid; grid-template-columns: 2fr 1fr; gap:12px; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Category *</label>
              <select name="category" required class="form-control-paces">
                <option value="services" selected>Services & Booking</option>
                <option value="amc">AMC & Corporate</option>
                <option value="safety">Technicians & Safety</option>
                <option value="billing">Billing & Partners</option>
                <option value="general">General Inquiries</option>
              </select>
            </div>
            <div>
              <label class="form-label-paces">Display Order</label>
              <input type="number" name="order" value="{{ $faqs->total() + 1 }}" class="form-control-paces">
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Question *</label>
            <input type="text" name="question" required placeholder="e.g. What is the emergency response SLA?" class="form-control-paces">
          </div>

          <div style="margin-bottom:1.25rem;">
            <label class="form-label-paces">Detailed Answer *</label>
            <textarea name="answer" required rows="5" placeholder="Enter clear, helpful explanation for customers and building owners..." class="form-control-paces"></textarea>
          </div>

          <div style="background:#f8fafc; border-radius:8px; padding:10px 14px; border:1px solid var(--paces-border);">
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--paces-primary);">
              Active / Visible on Help Center
            </label>
          </div>
        </div>

        <div class="modal-footer-paces">
          <button type="button" class="btn-paces btn-paces-outline" onclick="closeAddFaqModal()">
            Cancel
          </button>
          <button type="submit" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
            <span>Publish FAQ</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================== -->
  <!-- 2. EDIT FAQ MODAL (With Edit & Delete)         -->
  <!-- ============================================== -->
  <div id="editFaqModal" class="modal-backdrop-paces" style="display:none;">
    <div class="modal-dialog-paces" style="max-width:620px;">
      <div class="modal-header-paces">
        <h3 class="modal-title-paces" id="editFaqModalHeaderTitle">Edit FAQ</h3>
        <button type="button" class="modal-close-btn" onclick="closeEditFaqModal()">✕</button>
      </div>

      <form id="editFaqForm" method="POST" action="">
        @csrf
        @method('PUT')

        <div class="modal-body-paces">
          <div style="display:grid; grid-template-columns: 2fr 1fr; gap:12px; margin-bottom:1rem;">
            <div>
              <label class="form-label-paces">Category *</label>
              <select name="category" id="editFaqCategory" required class="form-control-paces">
                <option value="services">Services & Booking</option>
                <option value="amc">AMC & Corporate</option>
                <option value="safety">Technicians & Safety</option>
                <option value="billing">Billing & Partners</option>
                <option value="general">General Inquiries</option>
              </select>
            </div>
            <div>
              <label class="form-label-paces">Display Order</label>
              <input type="number" name="order" id="editFaqOrder" class="form-control-paces">
            </div>
          </div>

          <div style="margin-bottom:1rem;">
            <label class="form-label-paces">Question *</label>
            <input type="text" name="question" id="editFaqQuestion" required class="form-control-paces">
          </div>

          <div style="margin-bottom:1.25rem;">
            <label class="form-label-paces">Detailed Answer *</label>
            <textarea name="answer" id="editFaqAnswer" required rows="5" class="form-control-paces"></textarea>
          </div>

          <div style="background:#f8fafc; border-radius:8px; padding:10px 14px; border:1px solid var(--paces-border);">
            <label style="display:inline-flex; align-items:center; gap:6px; font-size:0.88rem; font-weight:500; cursor:pointer;">
              <input type="checkbox" name="is_active" id="editFaqIsActive" value="1" style="accent-color:var(--paces-primary);">
              Active / Visible on Help Center
            </label>
          </div>
        </div>

        <div class="modal-footer-paces" style="justify-content:space-between;">
          <button type="button" class="btn-paces btn-paces-danger" onclick="triggerDeleteFaq()" style="display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            <span>Delete FAQ</span>
          </button>

          <div style="display:flex; gap:10px;">
            <button type="button" class="btn-paces btn-paces-outline" onclick="closeEditFaqModal()">
              Cancel
            </button>
            <button type="submit" class="btn-paces btn-paces-primary">
              Save Changes
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Separate Hidden Delete Form -->
  <form id="deleteFaqForm" method="POST" action="" style="display:none;">
    @csrf
    @method('DELETE')
  </form>

  <script>
    function openAddFaqModal() {
      const modal = document.getElementById('addFaqModal');
      if (modal) modal.style.display = 'flex';
    }

    function closeAddFaqModal() {
      const modal = document.getElementById('addFaqModal');
      if (modal) modal.style.display = 'none';
    }

    let activeFaqDeleteUrl = '';

    function openEditFaqModal(data) {
      const form = document.getElementById('editFaqForm');
      form.action = data.update_url;
      activeFaqDeleteUrl = data.delete_url;

      document.getElementById('editFaqModalHeaderTitle').textContent = 'Edit FAQ: ' + data.question.substring(0, 35) + '...';
      const catSelect = document.getElementById('editFaqCategory');
      if (data.category && !Array.from(catSelect.options).some(o => o.value === data.category)) {
        catSelect.add(new Option(data.category, data.category, true, true));
      }
      catSelect.value = data.category || 'services';
      document.getElementById('editFaqOrder').value = data.order || 1;
      document.getElementById('editFaqQuestion').value = data.question || '';
      document.getElementById('editFaqAnswer').value = data.answer || '';
      document.getElementById('editFaqIsActive').checked = Boolean(data.is_active);

      document.getElementById('editFaqModal').style.display = 'flex';
    }

    function closeEditFaqModal() {
      document.getElementById('editFaqModal').style.display = 'none';
    }

    function triggerDeleteFaq() {
      openAdminConfirmModal({
        title: 'Delete FAQ?',
        message: 'Are you sure you want to permanently delete this FAQ item? This action cannot be undone.',
        confirmText: 'Delete FAQ',
        confirmColor: '#dc2626',
        iconType: 'danger',
        onConfirm: () => {
          const delForm = document.getElementById('deleteFaqForm');
          delForm.action = activeFaqDeleteUrl;
          delForm.submit();
        }
      });
    }

    // Close on backdrop click
    window.addEventListener('click', function(e) {
      const addModal = document.getElementById('addFaqModal');
      const editModal = document.getElementById('editFaqModal');
      if (e.target === addModal) closeAddFaqModal();
      if (e.target === editModal) closeEditFaqModal();
    });
  </script>
</x-layouts.admin>
