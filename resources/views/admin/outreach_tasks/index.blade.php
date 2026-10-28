<x-layouts.admin title="Outreach Tasks | FIX Admin">
  <style>
    .task-interactive-row {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      transition: all 0.15s ease;
      cursor: pointer;
    }
    .task-interactive-row:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      transform: translateY(-1px);
    }
    .task-action-btn {
      width: 32px;
      height: 32px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #e2e8f0;
      background: #ffffff;
      color: #64748b;
      cursor: pointer;
      transition: all 0.15s ease;
      padding: 0;
    }
    .task-action-btn:hover {
      background: #f1f5f9;
      color: #0f172a;
      border-color: #cbd5e1;
    }
    .task-action-btn.btn-done:hover {
      background: #ecfdf5;
      color: #059669;
      border-color: #a7f3d0;
    }
    .task-action-btn.btn-delete:hover {
      background: #fef2f2;
      color: #dc2626;
      border-color: #fecaca;
    }
    .task-filter-tab {
      padding: 6px 14px;
      font-size: 0.825rem;
      font-weight: 500;
      color: #64748b;
      border-radius: 8px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 1px solid transparent;
      transition: all 0.15s ease;
    }
    .task-filter-tab:hover {
      background: #f1f5f9;
      color: #1e293b;
    }
    .task-filter-tab.active {
      background: var(--paces-primary, #3e60d5);
      color: #ffffff;
      font-weight: 600;
      box-shadow: 0 2px 6px rgba(62, 96, 213, 0.25);
    }
  </style>

  <!-- Page Header -->
  <div class="page-header-box" style="margin-bottom:1.5rem;">
    <div>
      <h1 class="page-header-title">Outreach Tasks</h1>
      <p class="page-header-subtitle">Manage customer outreaches, follow-up calls, booking confirmations, and action items</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
      <button type="button" onclick="openCreateTaskModal()" class="btn-paces btn-paces-primary" style="display:inline-flex; align-items:center; gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>New Task</span>
      </button>
    </div>
  </div>

  @if(session('success'))
    <div data-alert style="background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; padding:0.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" onclick="this.closest('[data-alert]').remove()" style="background:none; border:none; color:#16a34a; cursor:pointer; font-size:1.15rem; line-height:1; padding:0 4px;" aria-label="Close message">&times;</button>
    </div>
  @endif

  <!-- Metrics Grid -->
  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:1.5rem;">
    <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:10px; padding:14px 18px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
      <div style="font-size:0.78rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">All Tasks</div>
      <div style="font-size:1.6rem; font-weight:700; color:#0f172a; margin-top:4px;">{{ number_format($stats['total']) }}</div>
    </div>
    <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:10px; padding:14px 18px; box-shadow:0 1px 3px rgba(0,0,0,0.03); border-left:4px solid #f59e0b;">
      <div style="font-size:0.78rem; font-weight:600; color:#b45309; text-transform:uppercase; letter-spacing:0.5px;">Pending Tasks</div>
      <div style="font-size:1.6rem; font-weight:700; color:#b45309; margin-top:4px;">{{ number_format($stats['pending']) }}</div>
    </div>
    <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:10px; padding:14px 18px; box-shadow:0 1px 3px rgba(0,0,0,0.03); border-left:4px solid #10b981;">
      <div style="font-size:0.78rem; font-weight:600; color:#047857; text-transform:uppercase; letter-spacing:0.5px;">Completed</div>
      <div style="font-size:1.6rem; font-weight:700; color:#047857; margin-top:4px;">{{ number_format($stats['completed']) }}</div>
    </div>
    <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:10px; padding:14px 18px; box-shadow:0 1px 3px rgba(0,0,0,0.03); border-left:4px solid #ef4444;">
      <div style="font-size:0.78rem; font-weight:600; color:#b91c1c; text-transform:uppercase; letter-spacing:0.5px;">Urgent & High</div>
      <div style="font-size:1.6rem; font-weight:700; color:#b91c1c; margin-top:4px;">{{ number_format($stats['urgent_high']) }}</div>
    </div>
  </div>

  <!-- Filter & Search Toolbar -->
  <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:12px; padding:12px 16px; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <!-- Filter Tabs -->
    <div style="display:flex; gap:6px; flex-wrap:wrap;">
      <a href="{{ route('admin.outreach-tasks.index', ['status' => 'all', 'priority' => $priority, 'search' => $search]) }}" class="task-filter-tab {{ $status === 'all' && $priority !== 'urgent_high' ? 'active' : '' }}">
        <span>All Tasks</span>
        <span style="font-size:0.72rem; padding:1px 6px; border-radius:10px; background:rgba(0,0,0,0.08); color:inherit;">{{ $stats['total'] }}</span>
      </a>
      <a href="{{ route('admin.outreach-tasks.index', ['status' => 'pending', 'priority' => $priority, 'search' => $search]) }}" class="task-filter-tab {{ $status === 'pending' ? 'active' : '' }}">
        <span>Pending</span>
        <span style="font-size:0.72rem; padding:1px 6px; border-radius:10px; background:rgba(0,0,0,0.08); color:inherit;">{{ $stats['pending'] }}</span>
      </a>
      <a href="{{ route('admin.outreach-tasks.index', ['status' => 'completed', 'priority' => $priority, 'search' => $search]) }}" class="task-filter-tab {{ $status === 'completed' ? 'active' : '' }}">
        <span>Completed</span>
        <span style="font-size:0.72rem; padding:1px 6px; border-radius:10px; background:rgba(0,0,0,0.08); color:inherit;">{{ $stats['completed'] }}</span>
      </a>
      <a href="{{ route('admin.outreach-tasks.index', ['status' => 'all', 'priority' => 'urgent_high', 'search' => $search]) }}" class="task-filter-tab {{ $priority === 'urgent_high' ? 'active' : '' }}">
        <span>Urgent & High</span>
        <span style="font-size:0.72rem; padding:1px 6px; border-radius:10px; background:rgba(0,0,0,0.08); color:inherit;">{{ $stats['urgent_high'] }}</span>
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.outreach-tasks.index') }}" method="GET" style="margin:0; position:relative; width:280px;">
      <input type="hidden" name="status" value="{{ $status }}">
      <input type="hidden" name="priority" value="{{ $priority }}">
      <input type="text" name="search" value="{{ $search }}" placeholder="Search title, customer, order #..." style="width:100%; font-size:0.83rem; padding:7px 12px 7px 32px; border-radius:8px; border:1px solid #cbd5e1; outline:none;" onfocus="this.style.borderColor='var(--paces-primary, #3e60d5)'" onblur="this.style.borderColor='#cbd5e1'">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); pointer-events:none;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      @if($search)
        <a href="{{ route('admin.outreach-tasks.index', ['status' => $status, 'priority' => $priority]) }}" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); color:#94a3b8; text-decoration:none; font-size:1.1rem; line-height:1;" title="Clear search">&times;</a>
      @endif
    </form>
  </div>

  <!-- Task List Container -->
  <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:1.5rem;">
    @forelse($tasks as $task)
      @php
        $taskData = [
          'id' => $task->id,
          'title' => $task->title,
          'task_type' => ucfirst(str_replace('_', ' ', $task->task_type ?? 'General')),
          'priority' => $task->priority,
          'status' => $task->status,
          'due_at' => $task->due_at ? $task->due_at->format('M d, Y, h:i A') : null,
          'due_at_input' => $task->due_at ? $task->due_at->format('Y-m-d\TH:i') : '',
          'is_overdue' => $task->due_at ? $task->due_at->isPast() : false,
          'overdue_text' => $task->due_at ? ($task->due_at->isPast() ? $task->due_at->diffForHumans() : '') : '',
          'notes' => $task->notes ?? '',
          'customer_name' => $task->customer_name ?? ($task->booking?->customer_name ?? $task->user?->name),
          'customer_phone' => $task->customer_phone ?? ($task->booking?->customer_phone ?? $task->user?->phone),
          'booking_id' => $task->booking_id,
          'booking_number' => $task->booking?->booking_number,
          'booking_url' => $task->booking ? route('admin.bookings.show', $task->booking) : null,
          'booking_service' => $task->booking?->service?->title ?? ($task->booking?->service_type ? ucfirst(str_replace('_', ' ', $task->booking->service_type)) : null),
          'booking_status' => $task->booking?->status ? ucfirst(str_replace('_', ' ', $task->booking->status)) : null,
          'user_id' => $task->user_id,
          'user_name' => $task->user?->name,
          'user_email' => $task->user?->email,
          'user_url' => $task->user ? route('admin.users.show', $task->user) : null,
          'created_at' => $task->created_at ? $task->created_at->format('M d, Y h:i A') : '',
          'created_by' => $task->createdByAdmin?->name ?? 'System',
          'completed_at' => $task->completed_at ? $task->completed_at->format('M d, Y h:i A') : null,
          'completed_by' => $task->completedByAdmin?->name,
          'complete_url' => route('admin.outreach-tasks.complete', $task),
          'update_url' => route('admin.outreach-tasks.update', $task),
          'delete_url' => route('admin.outreach-tasks.destroy', $task),
        ];
      @endphp

      <div class="task-interactive-row" onclick="openTaskDetailsModal({{ json_encode($taskData) }})">
        <!-- Left: Checkbox circle + Details -->
        <div style="display:flex; align-items:center; gap:12px; min-width:0; flex:1;">
          @if($task->status === 'completed')
            <div style="width:22px; height:22px; border-radius:50%; background:#10b981; color:#ffffff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
          @else
            <form action="{{ route('admin.outreach-tasks.complete', $task) }}" method="POST" style="margin:0; flex-shrink:0;" onclick="event.stopPropagation()">
              @csrf
              <button type="submit" title="Mark as Done" style="width:22px; height:22px; border-radius:50%; border:2px solid #cbd5e1; background:transparent; display:flex; align-items:center; justify-content:center; cursor:pointer; padding:0; color:transparent; transition:all 0.15s ease;" onmouseover="this.style.borderColor='#10b981'; this.style.color='#10b981';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='transparent';">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
              </button>
            </form>
          @endif

          <div style="min-width:0; flex:1;">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
              <span style="font-size:0.92rem; font-weight:600; color:{{ $task->status === 'completed' ? '#94a3b8; text-decoration:line-through;' : '#1e293b' }};">
                {{ $task->title }}
              </span>

              @if($task->priority === 'urgent')
                <span style="font-size:0.68rem; font-weight:700; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; padding:1px 6px; border-radius:8px; text-transform:uppercase;">Urgent</span>
              @elseif($task->priority === 'high')
                <span style="font-size:0.68rem; font-weight:700; background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; padding:1px 6px; border-radius:8px; text-transform:uppercase;">High</span>
              @endif

              @if($task->status === 'completed')
                <span style="font-size:0.68rem; font-weight:600; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; padding:1px 6px; border-radius:8px;">Completed</span>
              @endif
            </div>

            <!-- Meta subline with standard icons only -->
            <div style="font-size:0.76rem; color:#64748b; margin-top:3px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
              @if($task->due_at)
                <span style="color:{{ ($task->due_at->isPast() && $task->status !== 'completed') ? '#dc2626' : '#64748b' }}; display:inline-flex; align-items:center; gap:4px;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  <span>{{ ($task->due_at->isPast() && $task->status !== 'completed') ? 'Overdue: '.$task->due_at->diffForHumans() : $task->due_at->format('M d, h:i A') }}</span>
                </span>
              @endif

              @if($task->booking)
                <span style="display:inline-flex; align-items:center; gap:4px;" onclick="event.stopPropagation()">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                  <a href="{{ route('admin.bookings.show', $task->booking) }}" style="color:var(--paces-primary, #3e60d5); font-weight:600; text-decoration:none; font-family:monospace;">
                    #{{ $task->booking->booking_number }}
                  </a>
                  @if($task->booking->customer_name)
                    <span style="color:#94a3b8;">({{ $task->booking->customer_name }})</span>
                  @endif
                </span>
              @elseif($task->user)
                <span style="display:inline-flex; align-items:center; gap:4px;" onclick="event.stopPropagation()">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                  <a href="{{ route('admin.users.show', $task->user) }}" style="color:#475569; font-weight:500; text-decoration:none;">
                    {{ $task->user->name }}
                  </a>
                </span>
              @endif

              @if($task->notes)
                <span style="color:#94a3b8; max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $task->notes }}">
                  • {{ $task->notes }}
                </span>
              @endif
            </div>
          </div>
        </div>

        <!-- Right: Action Buttons (Icons ONLY - No text) -->
        <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;" onclick="event.stopPropagation()">
          @if($task->status !== 'completed')
            <form action="{{ route('admin.outreach-tasks.complete', $task) }}" method="POST" style="margin:0;">
              @csrf
              <button type="submit" class="task-action-btn btn-done" title="Mark Done">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
              </button>
            </form>
          @endif

          <button type="button" class="task-action-btn" title="Edit Task" onclick="openEditTaskModal({{ json_encode($taskData) }})">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          </button>

          <form action="{{ route('admin.outreach-tasks.destroy', $task) }}" method="POST" style="margin:0;" onsubmit="return confirm('Delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="task-action-btn btn-delete" title="Delete Task">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
          </form>
        </div>
      </div>
    @empty
      <div style="background:#ffffff; border:1px solid var(--paces-border); border-radius:12px; padding:3rem 1.5rem; text-align:center; color:#64748b;">
        <div style="width:48px; height:48px; border-radius:50%; background:#f1f5f9; display:inline-flex; align-items:center; justify-content:center; color:#94a3b8; margin-bottom:12px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        </div>
        <h4 style="font-size:1.05rem; font-weight:600; color:#1e293b; margin:0 0 6px;">No Outreach Tasks Found</h4>
        <p style="font-size:0.825rem; color:#64748b; margin:0 0 16px;">There are no tasks matching your current filter or search criteria.</p>
        <button type="button" onclick="openCreateTaskModal()" class="btn-paces btn-paces-primary btn-paces-sm">
          <span>Create First Task</span>
        </button>
      </div>
    @endforelse
  </div>

  @if($tasks->hasPages())
    <div style="display:flex; justify-content:center; margin-top:1.5rem;">
      {{ $tasks->links() }}
    </div>
  @endif

  <!-- ============================================================== -->
  <!-- TASK DETAILS POPUP MODAL (Clean & Minimal)                     -->
  <!-- ============================================================== -->
  <div id="taskDetailsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;" onclick="closeTaskDetailsModal()">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:540px; padding:22px 24px; position:relative; max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#64748b;" id="modalTaskType">Task Details</span>
          <span id="modalTaskPriorityBadge"></span>
          <span id="modalTaskStatusBadge"></span>
        </div>
        <button type="button" onclick="closeTaskDetailsModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <!-- Task Title -->
      <h2 id="modalTaskTitle" style="font-size:1.2rem; font-weight:700; color:#0f172a; margin:0 0 12px; line-height:1.35;"></h2>

      <!-- Due Date -->
      <div id="modalTaskDueDateRow" style="display:flex; align-items:center; gap:6px; font-size:0.83rem; color:#475569; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #f1f5f9;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span id="modalTaskDueText"></span>
      </div>

      <!-- Notes Box -->
      <div style="margin-bottom:16px;">
        <label style="font-size:0.75rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:6px;">Notes & Instructions</label>
        <div id="modalTaskNotes" style="font-size:0.875rem; color:#334155; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; line-height:1.5; white-space:pre-wrap;">
        </div>
      </div>

      <!-- Linked Booking Info (if any) -->
      <div id="modalLinkedBookingCard" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 14px; margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
          <span style="font-size:0.75rem; font-weight:700; color:#047857; text-transform:uppercase;">Linked Booking</span>
          <a href="#" id="modalBookingLink" target="_blank" style="font-size:0.78rem; font-weight:600; color:#059669; text-decoration:none;">Open Booking →</a>
        </div>
        <div id="modalBookingDetails" style="font-size:0.84rem; color:#1e293b;"></div>
      </div>

      <!-- Linked Customer Info (if any) -->
      <div id="modalLinkedUserCard" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
          <span style="font-size:0.75rem; font-weight:700; color:#475569; text-transform:uppercase;">Linked Customer</span>
          <a href="#" id="modalUserLink" target="_blank" style="font-size:0.78rem; font-weight:600; color:var(--paces-primary, #3e60d5); text-decoration:none;">View Customer →</a>
        </div>
        <div id="modalUserDetails" style="font-size:0.84rem; color:#1e293b;"></div>
      </div>

      <!-- Audit Meta -->
      <div style="font-size:0.76rem; color:#94a3b8; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f1f5f9; padding-top:12px; margin-bottom:16px;">
        <span id="modalTaskCreatedMeta"></span>
        <span id="modalTaskCompletedMeta"></span>
      </div>

      <!-- Modal Actions -->
      <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
        <form id="modalDeleteForm" method="POST" style="margin:0;" onsubmit="return confirm('Delete this task?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-paces btn-paces-sm" style="color:#ef4444; border:1px solid #fecaca; background:#fff; height:36px; padding:0 12px;">Delete</button>
        </form>

        <div style="display:flex; gap:8px;">
          <button type="button" id="modalEditBtn" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Edit Task</button>
          
          <form id="modalCompleteForm" method="POST" style="margin:0;">
            @csrf
            <button type="submit" id="modalCompleteBtn" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 16px;">Mark Completed</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Task Modal -->
  <div id="createTaskModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;" onclick="closeCreateTaskModal()">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:520px; padding:22px 24px; position:relative; max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <div>
          <h3 style="margin:0; font-size:1.1rem; font-weight:600; color:#1e293b;">Create Task</h3>
          <p style="margin:2px 0 0; font-size:0.78rem; color:#64748b;">Add a new simple task, or link to a booking/user for detailed follow-up.</p>
        </div>
        <button type="button" onclick="closeCreateTaskModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <form action="{{ route('admin.outreach-tasks.store') }}" method="POST" style="margin:0;">
        @csrf

        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Task Title *</label>
            <input type="text" name="title" class="form-control-paces" placeholder="e.g. Call customer for site visit schedule" required style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Priority</label>
              <select name="priority" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="normal" selected>Normal</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
                <option value="low">Low</option>
              </select>
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Due Date & Time (Optional)</label>
              <input type="datetime-local" name="due_at" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
            </div>
          </div>

          <!-- Searchable Booking Dropdown -->
          <div style="position:relative;">
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Link to Booking (Optional)</label>
            <input type="hidden" name="booking_id" id="taskBookingId" value="">
            
            <div id="bookingDropdownTrigger" onclick="toggleTaskDropdown('bookingDropdownMenu')" style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer; font-size:0.85rem; min-height:38px;">
              <span id="bookingDropdownSelectedText" style="color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Select a booking (Optional)...</span>
              <div style="display:flex; align-items:center; gap:6px;">
                <span id="bookingClearBtn" onclick="clearSelectedBooking(event)" style="display:none; color:#94a3b8; font-size:1.1rem; line-height:1; padding:0 3px; cursor:pointer;" title="Clear booking">&times;</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
              </div>
            </div>

            <div id="bookingDropdownMenu" style="display:none; position:absolute; top:calc(100% + 4px); left:0; width:100%; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 12px 28px rgba(0,0,0,0.14); z-index:10010; flex-direction:column; overflow:hidden;">
              <div style="padding:8px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <input type="text" id="bookingSearchInput" oninput="filterBookings(this.value)" placeholder="Search booking #, customer, phone..." style="width:100%; padding:6px 10px; font-size:0.82rem; border:1px solid #cbd5e1; border-radius:6px; outline:none;" onclick="event.stopPropagation()">
              </div>
              <div id="bookingOptionsList" style="overflow-y:auto; max-height:180px; padding:4px 0;"></div>
            </div>
          </div>

          <!-- Searchable User Dropdown -->
          <div style="position:relative;">
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Link to Customer / User (Optional)</label>
            <input type="hidden" name="user_id" id="taskUserId" value="">
            
            <div id="userDropdownTrigger" onclick="toggleTaskDropdown('userDropdownMenu')" style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; border:1px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer; font-size:0.85rem; min-height:38px;">
              <span id="userDropdownSelectedText" style="color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Select a customer / user (Optional)...</span>
              <div style="display:flex; align-items:center; gap:6px;">
                <span id="userClearBtn" onclick="clearSelectedUser(event)" style="display:none; color:#94a3b8; font-size:1.1rem; line-height:1; padding:0 3px; cursor:pointer;" title="Clear customer">&times;</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
              </div>
            </div>

            <div id="userDropdownMenu" style="display:none; position:absolute; top:calc(100% + 4px); left:0; width:100%; background:#ffffff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 12px 28px rgba(0,0,0,0.14); z-index:10010; flex-direction:column; overflow:hidden;">
              <div style="padding:8px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <input type="text" id="userSearchInput" oninput="filterUsers(this.value)" placeholder="Search customer by name, phone, email..." style="width:100%; padding:6px 10px; font-size:0.82rem; border:1px solid #cbd5e1; border-radius:6px; outline:none;" onclick="event.stopPropagation()">
              </div>
              <div id="userOptionsList" style="overflow-y:auto; max-height:180px; padding:4px 0;"></div>
            </div>
          </div>

          <!-- Dynamic Details Preview Card -->
          <div id="linkedEntityDetailsCard" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px; font-size:0.8rem;"></div>

          <!-- Notes Textarea -->
          <div id="taskNewInfoContainer" style="display:none;">
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Task Instructions / Notes</label>
            <textarea name="notes" id="taskNotesInput" class="form-control-paces" rows="3" placeholder="Enter instructions, discussion points, new customer details or action items..." style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;"></textarea>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeCreateTaskModal()" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 18px;">Save Task</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Task Modal -->
  <div id="editTaskModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;" onclick="closeEditTaskModal()">
    <div style="background:#ffffff; border-radius:14px; box-shadow:0 20px 45px rgba(0,0,0,0.18); width:92%; max-width:480px; padding:22px 24px; position:relative; max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <div>
          <h3 style="margin:0; font-size:1.1rem; font-weight:600; color:#1e293b;">Edit Task</h3>
          <p style="margin:2px 0 0; font-size:0.78rem; color:#64748b;">Update task details, notes or status.</p>
        </div>
        <button type="button" onclick="closeEditTaskModal()" style="background:none; border:none; color:#94a3b8; font-size:1.5rem; cursor:pointer; line-height:1; padding:0 4px;">&times;</button>
      </div>

      <form id="editTaskForm" method="POST" style="margin:0;">
        @csrf
        @method('PUT')

        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:18px;">
          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Task Title *</label>
            <input type="text" name="title" id="editTaskTitle" class="form-control-paces" required style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Priority</label>
              <select name="priority" id="editTaskPriority" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="normal">Normal</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
                <option value="low">Low</option>
              </select>
            </div>
            <div>
              <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Status</label>
              <select name="status" id="editTaskStatus" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>

          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Due Date & Time (Optional)</label>
            <input type="datetime-local" name="due_at" id="editTaskDueAt" class="form-control-paces" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;">
          </div>

          <div>
            <label style="display:block; font-size:0.8rem; font-weight:500; color:#475569; margin-bottom:4px;">Notes / Instructions</label>
            <textarea name="notes" id="editTaskNotes" class="form-control-paces" rows="3" style="width:100%; font-size:0.85rem; padding:8px 12px; border-radius:8px; border:1px solid #cbd5e1;"></textarea>
          </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
          <button type="button" onclick="closeEditTaskModal()" class="btn-paces btn-paces-outline btn-paces-sm" style="height:36px; padding:0 14px;">Cancel</button>
          <button type="submit" class="btn-paces btn-paces-primary btn-paces-sm" style="height:36px; padding:0 18px;">Update Task</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const taskBookingsData = @json($bookingOptionsData ?? []);
    const taskUsersData = @json($userOptionsData ?? []);
    let selectedTaskBooking = null;
    let selectedTaskUser = null;
    let currentTaskDataForModal = null;

    // Open Task Details Modal
    function openTaskDetailsModal(data) {
      currentTaskDataForModal = data;
      document.getElementById('modalTaskTitle').innerText = data.title;
      document.getElementById('modalTaskType').innerText = data.task_type || 'General Task';

      // Priority badge
      const priorityEl = document.getElementById('modalTaskPriorityBadge');
      if (data.priority === 'urgent') {
        priorityEl.innerHTML = '<span style="font-size:0.7rem; font-weight:700; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; padding:1px 8px; border-radius:10px; text-transform:uppercase;">Urgent</span>';
      } else if (data.priority === 'high') {
        priorityEl.innerHTML = '<span style="font-size:0.7rem; font-weight:700; background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; padding:1px 8px; border-radius:10px; text-transform:uppercase;">High</span>';
      } else {
        priorityEl.innerHTML = '<span style="font-size:0.7rem; font-weight:600; background:#f1f5f9; color:#475569; padding:1px 8px; border-radius:10px; text-transform:capitalize;">' + (data.priority || 'Normal') + '</span>';
      }

      // Status badge
      const statusEl = document.getElementById('modalTaskStatusBadge');
      if (data.status === 'completed') {
        statusEl.innerHTML = '<span style="font-size:0.7rem; font-weight:600; background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; padding:1px 8px; border-radius:10px;">Completed</span>';
      } else {
        statusEl.innerHTML = '<span style="font-size:0.7rem; font-weight:600; background:#fffbeb; color:#b45309; border:1px solid #fde68a; padding:1px 8px; border-radius:10px;">Pending</span>';
      }

      // Due date
      const dueRow = document.getElementById('modalTaskDueDateRow');
      if (data.due_at) {
        dueRow.style.display = 'flex';
        let dueHtml = '<span>Due: ' + data.due_at + '</span>';
        if (data.is_overdue && data.status !== 'completed') {
          dueHtml += ' <span style="color:#dc2626; font-weight:600; margin-left:6px;">(Overdue: ' + data.overdue_text + ')</span>';
        }
        document.getElementById('modalTaskDueText').innerHTML = dueHtml;
      } else {
        dueRow.style.display = 'none';
      }

      // Notes
      const notesEl = document.getElementById('modalTaskNotes');
      if (data.notes && data.notes.trim()) {
        notesEl.innerText = data.notes;
        notesEl.style.color = '#334155';
        notesEl.style.fontStyle = 'normal';
      } else {
        notesEl.innerText = 'No instructions or notes added to this task.';
        notesEl.style.color = '#94a3b8';
        notesEl.style.fontStyle = 'italic';
      }

      // Linked Booking
      const bookingCard = document.getElementById('modalLinkedBookingCard');
      if (data.booking_id && data.booking_number) {
        bookingCard.style.display = 'block';
        document.getElementById('modalBookingLink').href = data.booking_url || '#';
        document.getElementById('modalBookingDetails').innerHTML = `
          <div style="font-weight:600; color:var(--paces-primary, #3e60d5); font-family:monospace; margin-bottom:2px;">#${data.booking_number}</div>
          <div style="color:#475569;">${data.customer_name || 'Customer'} ${data.customer_phone ? '• ' + data.customer_phone : ''}</div>
          <div style="font-size:0.76rem; color:#64748b; margin-top:2px;">${data.booking_service || 'Service Order'} ${data.booking_status ? '• Status: ' + data.booking_status : ''}</div>
        `;
      } else {
        bookingCard.style.display = 'none';
      }

      // Linked User
      const userCard = document.getElementById('modalLinkedUserCard');
      if (data.user_id && data.user_name && !data.booking_id) {
        userCard.style.display = 'block';
        document.getElementById('modalUserLink').href = data.user_url || '#';
        document.getElementById('modalUserDetails').innerHTML = `
          <div style="font-weight:600; color:#1e293b;">${data.user_name}</div>
          <div style="color:#64748b; font-size:0.78rem;">${data.customer_phone || ''} ${data.user_email ? '• ' + data.user_email : ''}</div>
        `;
      } else {
        userCard.style.display = 'none';
      }

      // Audit meta
      document.getElementById('modalTaskCreatedMeta').innerText = 'Created ' + (data.created_at || 'Recently') + ' by ' + (data.created_by || 'Admin');
      if (data.status === 'completed' && data.completed_at) {
        document.getElementById('modalTaskCompletedMeta').innerText = 'Completed: ' + data.completed_at;
      } else {
        document.getElementById('modalTaskCompletedMeta').innerText = '';
      }

      // Form actions
      document.getElementById('modalCompleteForm').action = data.complete_url;
      const compBtn = document.getElementById('modalCompleteBtn');
      if (data.status === 'completed') {
        compBtn.style.display = 'none';
      } else {
        compBtn.style.display = 'inline-block';
      }

      document.getElementById('modalDeleteForm').action = data.delete_url;
      document.getElementById('modalEditBtn').onclick = function() {
        closeTaskDetailsModal();
        openEditTaskModal(data);
      };

      const modal = document.getElementById('taskDetailsModal');
      modal.style.display = 'flex';
    }

    function closeTaskDetailsModal() {
      const modal = document.getElementById('taskDetailsModal');
      if (modal) modal.style.display = 'none';
    }

    // Create Modal & Dropdown Logic
    function openCreateTaskModal() {
      renderBookingOptions();
      renderUserOptions();
      clearSelectedBooking();
      clearSelectedUser();
      const modal = document.getElementById('createTaskModal');
      if (modal) modal.style.display = 'flex';
    }

    function closeCreateTaskModal() {
      const modal = document.getElementById('createTaskModal');
      if (modal) modal.style.display = 'none';
      closeAllDropdowns();
    }

    function openEditTaskModal(task) {
      const modal = document.getElementById('editTaskModal');
      const form = document.getElementById('editTaskForm');
      if (!modal || !form) return;

      form.action = task.update_url;
      document.getElementById('editTaskTitle').value = task.title || '';
      document.getElementById('editTaskPriority').value = task.priority || 'normal';
      document.getElementById('editTaskStatus').value = task.status || 'pending';
      document.getElementById('editTaskDueAt').value = task.due_at_input || '';
      document.getElementById('editTaskNotes').value = task.notes || '';

      modal.style.display = 'flex';
    }

    function closeEditTaskModal() {
      const modal = document.getElementById('editTaskModal');
      if (modal) modal.style.display = 'none';
    }

    function renderBookingOptions(filter = '') {
      const list = document.getElementById('bookingOptionsList');
      if (!list) return;
      const q = filter.trim().toLowerCase();
      const filtered = taskBookingsData.filter(b => {
        if (!q) return true;
        return (b.number && b.number.toLowerCase().includes(q)) ||
               (b.name && b.name.toLowerCase().includes(q)) ||
               (b.phone && b.phone.toLowerCase().includes(q)) ||
               (b.service && b.service.toLowerCase().includes(q));
      });

      if (filtered.length === 0) {
        list.innerHTML = '<div style="padding:10px 12px; font-size:0.8rem; color:#94a3b8; text-align:center;">No matching bookings found</div>';
        return;
      }

      list.innerHTML = filtered.map(b => `
        <div onclick="selectBooking(${b.id})" style="padding:8px 12px; cursor:pointer; font-size:0.82rem; border-bottom:1px solid #f1f5f9; display:flex; flex-direction:column; gap:2px;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-weight:600; color:var(--paces-primary, #3e60d5); font-family:monospace;">#${b.number}</span>
            <span style="font-size:0.7rem; color:#64748b; background:#f1f5f9; padding:1px 6px; border-radius:4px;">${b.status}</span>
          </div>
          <div style="color:#1e293b; font-weight:500;">${b.name || 'Unknown'} ${b.phone ? '• <span style="color:#64748b;">'+b.phone+'</span>' : ''}</div>
          <div style="font-size:0.75rem; color:#64748b;">${b.service || ''} ${b.cost ? '• '+b.cost : ''}</div>
        </div>
      `).join('');
    }

    function renderUserOptions(filter = '') {
      const list = document.getElementById('userOptionsList');
      if (!list) return;
      const q = filter.trim().toLowerCase();
      const filtered = taskUsersData.filter(u => {
        if (!q) return true;
        return (u.name && u.name.toLowerCase().includes(q)) ||
               (u.phone && u.phone.toLowerCase().includes(q)) ||
               (u.email && u.email.toLowerCase().includes(q));
      });

      if (filtered.length === 0) {
        list.innerHTML = '<div style="padding:10px 12px; font-size:0.8rem; color:#94a3b8; text-align:center;">No matching customers found</div>';
        return;
      }

      list.innerHTML = filtered.map(u => `
        <div onclick="selectUser(${u.id})" style="padding:8px 12px; cursor:pointer; font-size:0.82rem; border-bottom:1px solid #f1f5f9; display:flex; flex-direction:column; gap:2px;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
          <div style="font-weight:600; color:#1e293b;">${u.name}</div>
          <div style="font-size:0.75rem; color:#64748b;">${u.phone || 'No phone'} ${u.email ? '• '+u.email : ''}</div>
        </div>
      `).join('');
    }

    function toggleTaskDropdown(id) {
      const menu = document.getElementById(id);
      if (!menu) return;
      const isOpen = menu.style.display === 'flex';
      closeAllDropdowns();
      if (!isOpen) {
        menu.style.display = 'flex';
        if (id === 'bookingDropdownMenu') {
          setTimeout(() => document.getElementById('bookingSearchInput')?.focus(), 50);
        } else if (id === 'userDropdownMenu') {
          setTimeout(() => document.getElementById('userSearchInput')?.focus(), 50);
        }
      }
    }

    function closeAllDropdowns() {
      const bMenu = document.getElementById('bookingDropdownMenu');
      const uMenu = document.getElementById('userDropdownMenu');
      if (bMenu) bMenu.style.display = 'none';
      if (uMenu) uMenu.style.display = 'none';
    }

    function filterBookings(val) { renderBookingOptions(val); }
    function filterUsers(val) { renderUserOptions(val); }

    function selectBooking(id) {
      const booking = taskBookingsData.find(b => b.id == id);
      if (!booking) return;
      selectedTaskBooking = booking;
      document.getElementById('taskBookingId').value = booking.id;
      document.getElementById('bookingDropdownSelectedText').innerHTML = `<strong style="color:var(--paces-primary, #3e60d5); font-family:monospace;">#${booking.number}</strong> • ${booking.name || 'Customer'}`;
      document.getElementById('bookingClearBtn').style.display = 'block';
      closeAllDropdowns();
      updateLinkedEntityDetails();
    }

    function clearSelectedBooking(e) {
      if (e) e.stopPropagation();
      selectedTaskBooking = null;
      const input = document.getElementById('taskBookingId');
      if (input) input.value = '';
      const text = document.getElementById('bookingDropdownSelectedText');
      if (text) text.innerText = 'Select a booking (Optional)...';
      const clearBtn = document.getElementById('bookingClearBtn');
      if (clearBtn) clearBtn.style.display = 'none';
      updateLinkedEntityDetails();
    }

    function selectUser(id) {
      const user = taskUsersData.find(u => u.id == id);
      if (!user) return;
      selectedTaskUser = user;
      document.getElementById('taskUserId').value = user.id;
      document.getElementById('userDropdownSelectedText').innerHTML = `<strong>${user.name}</strong> • ${user.phone || user.email}`;
      document.getElementById('userClearBtn').style.display = 'block';
      closeAllDropdowns();
      updateLinkedEntityDetails();
    }

    function clearSelectedUser(e) {
      if (e) e.stopPropagation();
      selectedTaskUser = null;
      const input = document.getElementById('taskUserId');
      if (input) input.value = '';
      const text = document.getElementById('userDropdownSelectedText');
      if (text) text.innerText = 'Select a customer / user (Optional)...';
      const clearBtn = document.getElementById('userClearBtn');
      if (clearBtn) clearBtn.style.display = 'none';
      updateLinkedEntityDetails();
    }

    function updateLinkedEntityDetails() {
      const card = document.getElementById('linkedEntityDetailsCard');
      const newInfoCont = document.getElementById('taskNewInfoContainer');
      if (!card || !newInfoCont) return;

      if (!selectedTaskBooking && !selectedTaskUser) {
        card.style.display = 'none';
        newInfoCont.style.display = 'none';
        return;
      }

      card.style.display = 'block';
      newInfoCont.style.display = 'block';

      let html = '';
      if (selectedTaskBooking) {
        html += `
          <div style="font-weight:600; color:#065f46; margin-bottom:4px; display:flex; justify-content:space-between;">
            <span>Linked Booking: #${selectedTaskBooking.number}</span>
            <span>Status: ${selectedTaskBooking.status}</span>
          </div>
          <div style="color:#1e293b; line-height:1.4;">
            <strong>Customer:</strong> ${selectedTaskBooking.name || 'N/A'} • <strong>Phone:</strong> ${selectedTaskBooking.phone || 'N/A'}<br>
            <strong>Service:</strong> ${selectedTaskBooking.service} • <strong>Area:</strong> ${selectedTaskBooking.area || 'Dhaka'}
          </div>
        `;
      } else if (selectedTaskUser) {
        html += `
          <div style="font-weight:600; color:#065f46; margin-bottom:4px;">Linked Customer: ${selectedTaskUser.name}</div>
          <div style="color:#1e293b; line-height:1.4;">
            <strong>Phone:</strong> ${selectedTaskUser.phone || 'N/A'} • <strong>Email:</strong> ${selectedTaskUser.email || 'N/A'}<br>
            <strong>Address:</strong> ${selectedTaskUser.address || 'Dhaka'}
          </div>
        `;
      }
      card.innerHTML = html;
    }

    window.addEventListener('click', function(e) {
      if (!e.target.closest('#bookingDropdownTrigger') && !e.target.closest('#bookingDropdownMenu') &&
          !e.target.closest('#userDropdownTrigger') && !e.target.closest('#userDropdownMenu')) {
        closeAllDropdowns();
      }
    });
  </script>
</x-layouts.admin>
