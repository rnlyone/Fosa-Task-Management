@extends('layouts.app')
@section('title', 'Kanban Board — ' . $event->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/jkanban/jkanban.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/quill/typography.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/quill/katex.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/quill/editor.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/css/pages/app-kanban.css') }}" />
<style>
.kanban-column-title { font-weight: 600; font-size: .875rem; }
.task-card { cursor: pointer; }
.priority-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
.avatar-group .avatar { margin-left: -8px; }
.event-badge { font-size: .7rem; }
</style>
@endpush

@section('content')
<!-- Event Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="ti ti-layout-kanban me-2 text-primary"></i>
                    {{ $event->name }}
                </h4>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <span class="badge bg-label-{{ $event->status === 'active' ? 'success' : ($event->status === 'preparation' ? 'warning' : 'secondary') }} event-badge">
                        {{ ucfirst($event->status) }}
                    </span>
                    <small class="text-muted">Event Date: {{ $event->event_date->format('d M Y') }}</small>
                    <small class="text-muted">Manager: {{ $event->manager->name }}</small>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <!-- Event switcher -->
                <div class="dropdown">
                    <button class="btn btn-label-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-switch-horizontal me-1"></i> Switch Event
                    </button>
                    <ul class="dropdown-menu">
                        @foreach($allEvents as $ev)
                        <li>
                            <a class="dropdown-item {{ $ev->id === $event->id ? 'active' : '' }}"
                               href="{{ route('dashboard.switch', $ev->id) }}">
                                {{ $ev->name }}
                                <span class="badge bg-label-{{ $ev->status === 'active' ? 'success' : ($ev->status === 'preparation' ? 'warning' : 'secondary') }} ms-1 event-badge">
                                    {{ ucfirst($ev->status) }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                @if(auth()->user()->isLeadership() || auth()->user()->id === $event->manager_id)
                <a href="{{ route('event-management.show', $event) }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-chart-bar me-1"></i> Management View
                </a>
                @endif

                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="ti ti-plus me-1"></i> Add Task
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Kanban Board -->
<div class="app-kanban">
    <div class="kanban-wrapper d-flex gap-4" style="overflow-x: auto; min-height: 70vh; padding-bottom: 1rem;">

        @php
        $columnLabels = [
            'backlog' => ['label' => 'Backlog', 'color' => 'secondary', 'icon' => 'ti-stack-2'],
            'todo'    => ['label' => 'To Do',   'color' => 'info',      'icon' => 'ti-circle'],
            'doing'   => ['label' => 'Doing',   'color' => 'warning',   'icon' => 'ti-progress'],
            'done'    => ['label' => 'Done',     'color' => 'success',   'icon' => 'ti-circle-check'],
            'archive' => ['label' => 'Archive',  'color' => 'dark',      'icon' => 'ti-archive'],
        ];
        @endphp

        @foreach($columnLabels as $colKey => $colInfo)
        <div class="kanban-column flex-shrink-0" style="width: 290px;" data-column="{{ $colKey }}">
            <div class="card shadow-none border">
                <div class="card-header d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-label-{{ $colInfo['color'] }} p-1_5">
                            <i class="ti {{ $colInfo['icon'] }} ti-sm"></i>
                        </span>
                        <h6 class="kanban-column-title mb-0">{{ $colInfo['label'] }}</h6>
                        <span class="badge bg-{{ $colInfo['color'] }} rounded-pill">{{ count($columns[$colKey]) }}</span>
                        @php
                            $myTaskCount = collect($columns[$colKey])->filter(fn($t) => $t->assignees->contains('id', auth()->id()))->count();
                        @endphp
                        <span class="badge bg-label-secondary rounded-pill d-inline-flex align-items-center gap-1" data-member-badge title="Your tasks in this column">
                            <i class="ti ti-user" style="font-size:.7rem;"></i><span>{{ $myTaskCount }}</span>
                        </span>
                    </div>
                    <button class="btn btn-icon btn-sm btn-text-secondary add-task-btn"
                            data-column="{{ $colKey }}"
                            data-bs-toggle="modal" data-bs-target="#addTaskModal"
                            title="Add task to {{ $colInfo['label'] }}">
                        <i class="ti ti-plus ti-sm"></i>
                    </button>
                </div>
                <div class="card-body p-2 kanban-tasks" data-column="{{ $colKey }}" id="col-{{ $colKey }}">
                    @foreach($columns[$colKey] as $task)
                    @include('dashboard.partials.task-card', ['task' => $task])
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addTaskForm">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="column" id="newTaskColumn" value="backlog">
                <div class="modal-body row g-4">
                    <div class="col-12">
                        <label class="form-label">Task Name <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Enter task name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Deadline</label>
                        <input type="date" name="deadline_date" class="form-control flatpickr-date">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Assign Members</label>
                        <select name="assignees[]" class="select2 form-select" multiple>
                            @foreach($eventMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} ({{ ucfirst(str_replace('_', ' ', $member->role)) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Card Color</label>
                        <select name="card_color" class="form-select">
                            <option value="">Default</option>
                            <option value="#2563eb">Blue</option>
                            <option value="#28c76f">Green</option>
                            <option value="#ff9f43">Orange</option>
                            <option value="#ea5455">Red</option>
                            <option value="#00cfe8">Cyan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <div id="taskDescriptionEditor" style="min-height:120px;"></div>
                        <input type="hidden" name="description" id="taskDescriptionInput">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="addTaskSubmitBtn" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editTaskOffcanvas" style="width: 420px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body pt-3">
        <form id="editTaskForm">
            @csrf
            <input type="hidden" id="editTaskId">
            <div class="mb-3 p-2 bg-light rounded" id="editTaskCreator" style="display:none">
                <small class="text-muted"><i class="ti ti-user ti-xs me-1"></i>Created by <span id="editTaskCreatorName" class="fw-semibold"></span></small>
            </div>
            <div class="mb-4">
                <label class="form-label">Task Name</label>
                <input type="text" id="editTaskName" class="form-control" required>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label">Priority</label>
                    <select id="editTaskPriority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Column</label>
                    <select id="editTaskColumn" class="form-select">
                        <option value="backlog">Backlog</option>
                        <option value="todo">To Do</option>
                        <option value="doing">Doing</option>
                        <option value="done">Done</option>
                        <option value="archive">Archive</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Deadline</label>
                <input type="date" id="editTaskDeadline" class="form-control">
            </div>
            <div class="mb-4">
                <label class="form-label">Assign Members</label>
                <select id="editTaskAssignees" class="select2 form-select" multiple>
                    @foreach($eventMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <div id="editDescriptionEditor" style="min-height:120px;"></div>
                <input type="hidden" id="editDescriptionInput">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Save Changes</button>
                <button type="button" class="btn btn-danger" id="deleteTaskBtn">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vuexy/vendor/libs/sortablejs/sortable.js') }}"></script>
<script src="{{ asset('vuexy/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('vuexy/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('vuexy/vendor/libs/quill/quill.js') }}"></script>
<script>
(function () {
    'use strict';

    const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
    const EVENT   = {{ $event->id }};
    const AUTH_ID = '{{ auth()->id() }}';

    // Init Quill editors
    const addQuill  = new Quill('#taskDescriptionEditor',  { theme: 'snow', placeholder: 'Task description...' });
    const editQuill = new Quill('#editDescriptionEditor', { theme: 'snow', placeholder: 'Task description...' });

    // Init Select2
    $('.select2').each(function () {
        $(this).wrap('<div class="position-relative"></div>');
        $(this).select2({ dropdownParent: $(this).parent() });
    });

    // Init Flatpickr
    $('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });

    // --- Add Task Column shortcut ---
    document.querySelectorAll('.add-task-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('newTaskColumn').value = btn.dataset.column;
            document.querySelector('#addTaskModal select[name="column"]') && (document.querySelector('#addTaskModal select[name="column"]').value = btn.dataset.column);
        });
    });

    // Reset Add Task button when modal closes
    document.getElementById('addTaskModal').addEventListener('hidden.bs.modal', function () {
        const btn = document.getElementById('addTaskSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="ti ti-plus me-1"></i> Add Task';
    });

    // --- Add Task Form ---
    document.getElementById('addTaskForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        document.getElementById('taskDescriptionInput').value = addQuill.root.innerHTML;

        const btn = document.getElementById('addTaskSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Adding...';

        const fd = new FormData(this);
        try {
            const res = await fetch('{{ route("tasks.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: fd,
            });
            const json = await res.json();
            if (json.success) {
                bootstrap.Modal.getInstance(document.getElementById('addTaskModal')).hide();
                location.reload();
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-plus me-1"></i> Add Task';
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-plus me-1"></i> Add Task';
        }
    });

    // --- Sortable columns ---
    document.querySelectorAll('.kanban-tasks').forEach(container => {
        Sortable.create(container, {
            group: 'kanban',
            animation: 150,
            onEnd: async function (evt) {
                const tasks = [];
                document.querySelectorAll('.kanban-tasks').forEach(col => {
                    col.querySelectorAll('.task-card').forEach((card, idx) => {
                        tasks.push({ id: card.dataset.id, position: idx, column: col.dataset.column });
                    });
                });
                await fetch('{{ route("tasks.reorder") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ tasks }),
                });
                // Update badge counts
                document.querySelectorAll('.kanban-column').forEach(col => {
                    const cards = col.querySelectorAll('.task-card');
                    const count = cards.length;
                    col.querySelector('.rounded-pill').textContent = count;
                    // Recount unique assigned members
                    let myCount = 0;
                    cards.forEach(card => {
                        if ((card.dataset.assigneeIds || '').split(',').includes(AUTH_ID)) myCount++;
                    });
                    const memberBadge = col.querySelector('[data-member-badge]');
                    if (memberBadge) memberBadge.querySelector('span').textContent = myCount;
                });
            },
        });
    });

    // --- Open Edit Offcanvas ---
    document.addEventListener('click', async function (e) {
        const card = e.target.closest('.task-card');
        if (!card || e.target.closest('.task-card-actions')) return;
        const taskId = card.dataset.id;

        const res  = await fetch(`/tasks/${taskId}`, { headers: { 'Accept': 'application/json' } });
        const task = await res.json();

        document.getElementById('editTaskId').value            = task.id;
        document.getElementById('editTaskName').value          = task.title;
        if (task.creator) {
            document.getElementById('editTaskCreatorName').textContent = task.creator.name;
            document.getElementById('editTaskCreator').style.display = '';
        } else {
            document.getElementById('editTaskCreator').style.display = 'none';
        }
        document.getElementById('editTaskPriority').value      = task.priority;
        document.getElementById('editTaskColumn').value        = task.column;
        document.getElementById('editTaskDeadline').value      = task.deadline_date ?? '';
        editQuill.root.innerHTML                               = task.description ?? '';
        document.getElementById('editDescriptionInput').value  = task.description ?? '';

        // Set select2 assignees
        const assigneeIds = task.assignees.map(a => a.id.toString());
        $('#editTaskAssignees').val(assigneeIds).trigger('change');

        new bootstrap.Offcanvas(document.getElementById('editTaskOffcanvas')).show();
    });

    // --- Save Edit ---
    document.getElementById('editTaskForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        document.getElementById('editDescriptionInput').value = editQuill.root.innerHTML;

        const id   = document.getElementById('editTaskId').value;
        const data = {
            title:         document.getElementById('editTaskName').value,
            priority:      document.getElementById('editTaskPriority').value,
            column:        document.getElementById('editTaskColumn').value,
            deadline_date: document.getElementById('editTaskDeadline').value || null,
            description:   document.getElementById('editDescriptionInput').value,
            assignees:     $('#editTaskAssignees').val(),
        };

        const saveRes  = await fetch(`/tasks/${id}`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data),
        });
        const saveJson = await saveRes.json();

        bootstrap.Offcanvas.getInstance(document.getElementById('editTaskOffcanvas')).hide();
        if (saveJson.task) refreshCard(saveJson.task);
    });

    // --- Delete Task ---
    document.getElementById('deleteTaskBtn').addEventListener('click', async function () {
        if (!confirm('Delete this task?')) return;
        const id = document.getElementById('editTaskId').value;
        await fetch(`/tasks/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        bootstrap.Offcanvas.getInstance(document.getElementById('editTaskOffcanvas')).hide();
        removeCard(id);
    });

    // ── DOM helpers (no page reload) ────────────────────────────────────────

    function avatarUrl(a) {
        return a.avatar
            ? '/storage/' + a.avatar
            : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(a.name) + '&background=7367f0&color=fff';
    }

    function refreshCard(task) {
        const card = document.querySelector(`.task-card[data-id="${task.id}"]`);
        const targetCol = document.getElementById(`col-${task.column}`);
        if (!card || !targetCol) return;

        // Move to correct column if column changed
        if (card.closest('.kanban-tasks').dataset.column !== task.column) {
            targetCol.appendChild(card);
        }

        // Update data attribute
        card.dataset.assigneeIds = task.assignees.map(a => a.id).join(',');

        // Border colour
        card.style.borderLeft = task.card_color ? `3px solid ${task.card_color}` : '';

        // Priority
        const pColors = { low: 'success', medium: 'info', high: 'warning', critical: 'danger' };
        const pColor  = pColors[task.priority] || 'secondary';

        // Overdue (compare date-only to avoid timezone shifts)
        const today   = new Date(); today.setHours(0,0,0,0);
        const dl      = task.deadline_date ? new Date(task.deadline_date + 'T00:00:00') : null;
        const overdue = dl && dl < today;

        // Assignee avatars
        let avatarsHtml = task.assignees.slice(0, 3).map(a =>
            `<div class="avatar avatar-xs" title="${a.name}">
                <img src="${avatarUrl(a)}" alt="${a.name}" class="rounded-circle">
             </div>`
        ).join('');
        if (task.assignees.length > 3) {
            avatarsHtml += `<div class="avatar avatar-xs">
                <span class="avatar-initial rounded-circle bg-label-secondary" style="font-size:.6rem;">
                    +${task.assignees.length - 3}
                </span></div>`;
        }

        // Deadline
        const dlHtml = dl
            ? `<small class="text-${overdue ? 'danger' : 'muted'}">
                   <i class="ti ti-calendar ti-xs me-1"></i>
                   ${dl.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' })}
               </small>`
            : '';

        card.querySelector('.card-body').innerHTML = `
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-label-${pColor} badge-sm">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span>
                    ${overdue ? '<span class="badge bg-danger badge-sm">Overdue</span>' : ''}
                </div>
            </div>
            <h6 class="card-title mb-2 fw-normal">${task.title}</h6>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="avatar-group d-flex">${avatarsHtml}</div>
                ${dlHtml}
            </div>`;

        updateColumnCounts();
    }

    function removeCard(taskId) {
        const card = document.querySelector(`.task-card[data-id="${taskId}"]`);
        if (card) card.remove();
        updateColumnCounts();
    }

    function updateColumnCounts() {
        const me = String(AUTH_ID);
        document.querySelectorAll('.kanban-column').forEach(col => {
            const cards = col.querySelectorAll('.task-card');
            // Total count badge (first .rounded-pill in the header)
            const totalBadge = col.querySelector('.card-header .rounded-pill');
            if (totalBadge) totalBadge.textContent = cards.length;
            // My-tasks badge
            let myCount = 0;
            cards.forEach(c => { if ((c.dataset.assigneeIds || '').split(',').includes(me)) myCount++; });
            const memberBadge = col.querySelector('[data-member-badge] span');
            if (memberBadge) memberBadge.textContent = myCount;
        });
    }

})();
</script>
@endpush
