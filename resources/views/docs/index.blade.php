@extends('layouts.app')
@section('title', 'Documentation')

@push('styles')
<style>
    .doc-section { scroll-margin-top: 80px; }
    .doc-sidebar-col { align-self: flex-start; }
    .doc-sidebar { max-height: calc(100vh - 100px); overflow-y: auto; }
    .doc-sidebar.is-sticky { position: fixed; z-index: 100; }
    .doc-sidebar .nav-link { color: #697a8d; font-size: 0.875rem; padding: 0.3rem 0.75rem; border-left: 2px solid transparent; }
    .doc-sidebar .nav-link:hover, .doc-sidebar .nav-link.active { color: #2563eb; border-left-color: #2563eb; background: transparent; }
    .doc-sidebar .nav-link.sub { padding-left: 1.5rem; font-size: 0.8125rem; }
    .doc-badge { display: inline-block; padding: 0.2em 0.55em; font-size: 0.75rem; font-weight: 600; border-radius: 0.3rem; }
    .doc-badge.president { background:#dbeafe; color:#1d4ed8; }
    .doc-badge.vp { background:#ede9fe; color:#5b21b6; }
    .doc-badge.all { background:#dcfce7; color:#15803d; }
    .doc-badge.leader { background:#fef3c7; color:#92400e; }
    .step-num { display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background:#2563eb; color:#fff; font-size:0.75rem; font-weight:700; flex-shrink:0; }
    .doc-card { border-left: 4px solid #2563eb; }
    .doc-card.warning { border-left-color: #f59e0b; }
    .doc-card.success { border-left-color: #10b981; }
    .doc-card.danger  { border-left-color: #ef4444; }
    code.inline { background:#f1f5f9; color:#0f172a; padding:0.1em 0.4em; border-radius:0.25rem; font-size:0.85em; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="fw-bold mb-1"><i class="ti ti-book-2 me-2 text-primary"></i>FOSA Task Management — Documentation</h4>
            <p class="text-muted mb-0">Everything you need to know about using the platform.</p>
        </div>
        <div class="col-auto">
            <span class="badge bg-label-primary rounded-pill">Version 1.1</span>
        </div>
    </div>

    <div class="row">
        <!-- Sticky sidebar nav -->
        <div class="col-lg-3 d-none d-lg-block doc-sidebar-col" id="docSidebarCol">
            <div class="doc-sidebar card card-body p-2" id="docSidebarEl">
                <p class="text-uppercase text-muted fw-semibold" style="font-size:0.7rem;letter-spacing:.08em;padding:.3rem .75rem;margin-bottom:0;">Contents</p>
                <nav class="nav flex-column" id="docNav">
                    <a href="#overview"        class="nav-link">Overview</a>
                    <a href="#roles"           class="nav-link">User Roles</a>
                    <a href="#kanban"          class="nav-link">Kanban Board</a>
                    <a href="#columns"         class="nav-link sub">Columns</a>
                    <a href="#tasks"           class="nav-link sub">Tasks</a>
                    <a href="#priorities"      class="nav-link sub">Priority &amp; Colors</a>
                    <a href="#events"          class="nav-link">Events</a>
                    <a href="#event-mgmt"      class="nav-link sub">Management View</a>
                    <a href="#members"         class="nav-link">Members</a>
                    <a href="#departments"     class="nav-link sub">Departments</a>
                    <a href="#workload"        class="nav-link sub">Workload Indicators</a>
                    <a href="#profile"         class="nav-link">My Profile</a>
                    <a href="#evaluations"     class="nav-link">Evaluations</a>
                    <a href="#notifications"   class="nav-link">Notifications</a>
                    <a href="#email-accounts"  class="nav-link">Email Accounts</a>
                    <a href="#pwa"             class="nav-link">PWA / Install App</a>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-lg-9" id="docMainCol">

            <!-- ── Overview ── -->
            <div id="overview" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-home fs-5 text-primary"></i>
                    <h5 class="mb-0">Overview</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>FOSA Task Management</strong> is the internal operations platform for the FOSA organisation.
                        It centralises task delegation, event management, member coordination, and periodic evaluations
                        — with real-time in-app <em>and</em> email notifications keeping everyone in sync.
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card bg-label-primary text-center h-100 mb-0">
                                <div class="card-body py-3">
                                    <i class="ti ti-layout-kanban fs-2"></i>
                                    <p class="mb-0 mt-1 fw-semibold small">Kanban Board</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card bg-label-success text-center h-100 mb-0">
                                <div class="card-body py-3">
                                    <i class="ti ti-calendar-event fs-2"></i>
                                    <p class="mb-0 mt-1 fw-semibold small">Events</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card bg-label-warning text-center h-100 mb-0">
                                <div class="card-body py-3">
                                    <i class="ti ti-clipboard-list fs-2"></i>
                                    <p class="mb-0 mt-1 fw-semibold small">Evaluations</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card bg-label-info text-center h-100 mb-0">
                                <div class="card-body py-3">
                                    <i class="ti ti-bell fs-2"></i>
                                    <p class="mb-0 mt-1 fw-semibold small">Notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>Quick Start</h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Log in with your username and password provided by leadership.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>You land on the <strong>Kanban Board</strong> for the active event. Tasks assigned to you appear in the columns.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">3</span><span>Update your <strong>Status</strong> (top-right dropdown) so colleagues know your availability.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">4</span><span>Check the <strong>bell icon</strong> for notifications — task assignments, moves, and deadlines appear here and via email.</span></div>
                    </div>
                </div>
            </div>

            <!-- ── Roles ── -->
            <div id="roles" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-shield-half fs-5 text-primary"></i>
                    <h5 class="mb-0">User Roles</h5>
                </div>
                <div class="card-body">
                    <p>Every account is assigned one of three roles. Permissions cascade downward — leadership can do everything a member can, plus more.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-3">
                            <thead class="table-light">
                                <tr><th>Role</th><th>Code</th><th>Capabilities</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="doc-badge president">President</span></td>
                                    <td><code class="inline">president</code></td>
                                    <td>Full access: member management, departments, evaluations, email accounts, all events &amp; tasks.</td>
                                </tr>
                                <tr>
                                    <td><span class="doc-badge vp">Vice President</span></td>
                                    <td><code class="inline">vice_president</code></td>
                                    <td>Identical to President. Both roles are called <em>Leadership</em> throughout the system.</td>
                                </tr>
                                <tr>
                                    <td><span class="doc-badge all">Member</span></td>
                                    <td><code class="inline">member</code></td>
                                    <td>View &amp; move tasks in assigned events; fill evaluation forms; manage own profile. Cannot manage members, departments, or email accounts.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-primary mb-0 d-flex gap-2">
                        <i class="ti ti-info-circle fs-5 flex-shrink-0 mt-1"></i>
                        <div>Roles are assigned by Leadership on the <strong>Members</strong> page and can be changed at any time.</div>
                    </div>
                </div>
            </div>

            <!-- ── Kanban ── -->
            <div id="kanban" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-layout-kanban fs-5 text-primary"></i>
                    <h5 class="mb-0">Kanban Board</h5>
                </div>
                <div class="card-body">
                    <p>The Kanban Board is the home screen. It shows all tasks for the <strong>active event</strong> organised into columns. If you are a member of multiple events, use the <strong>Switch Event</strong> button at the top to change the view.</p>
                    <p>Each column header shows a badge with <strong>your</strong> task count in that column.</p>

                    <h6 class="mt-3" id="columns">Columns</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-3">
                            <thead class="table-light"><tr><th>Column</th><th>Internal key</th><th>Purpose</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge bg-secondary">Backlog</span></td><td><code class="inline">backlog</code></td><td>Ideas or tasks not yet ready to be started. Holding area before work begins.</td></tr>
                                <tr><td><span class="badge bg-primary">To-do</span></td><td><code class="inline">todo</code></td><td>Ready to start — assigned and waiting for someone to pick them up.</td></tr>
                                <tr><td><span class="badge bg-warning text-dark">In Progress</span></td><td><code class="inline">doing</code></td><td>Actively being worked on right now.</td></tr>
                                <tr><td><span class="badge bg-success">Done</span></td><td><code class="inline">done</code></td><td>Completed tasks. Moving here triggers a <em>Task Done</em> notification to leadership.</td></tr>
                                <tr><td><span class="badge bg-dark">Archive</span></td><td><code class="inline">archive</code></td><td>Archived tasks — hidden from the main view. No notifications are sent when moving to or from Archive.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h6>Drag &amp; Drop</h6>
                    <p class="mb-0">Drag any task card to a different column or to a new position within the same column. On mobile, <strong>tap and hold</strong> briefly before dragging to avoid accidental moves.</p>
                </div>
            </div>

            <!-- ── Tasks ── -->
            <div id="tasks" class="doc-section card mb-4 doc-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-checklist fs-5 text-primary"></i>
                    <h5 class="mb-0">Tasks</h5>
                </div>
                <div class="card-body">
                    <h6>Creating a task <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <ol class="mb-4">
                        <li class="mb-1">Click <strong>+ Add Task</strong> on any column.</li>
                        <li class="mb-1">Fill in the title, description (rich text), deadline, and priority.</li>
                        <li class="mb-1">Select assignees from the event's participant list. Each option shows the member's availability status and an <span class="badge bg-danger" style="font-size:.7rem;">OLM</span> or <span class="badge bg-warning text-dark" style="font-size:.7rem;">UPM</span> badge if applicable (see <a href="#workload">Workload Indicators</a>).</li>
                        <li>Click <strong>Save</strong>. Assigned members receive an in-app and email notification immediately.</li>
                    </ol>

                    <h6>Editing a task <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <p class="mb-3">Click the <strong>pencil icon</strong> on a card to edit the title, description, deadline, assignees, priority, and card colour. Changes are saved instantly. Assignees who are added receive a <em>Task Assigned</em> notification; those removed receive a <em>Task Unassigned</em> notification. Setting a deadline for the first time sends a <em>Deadline Set</em> notification to all assignees.</p>

                    <h6>Moving tasks</h6>
                    <p class="mb-3">Drag-and-drop a card to move it between columns, or use the inline arrow buttons. See the <a href="#notifications">Notifications</a> section for which column moves trigger which notification titles.</p>

                    <h6>Deep-link from email</h6>
                    <p class="mb-3">Task notification emails include an <strong>Open Task</strong> button. Clicking it opens the board and automatically scrolls to and highlights the relevant task, even when accessing the app as a PWA.</p>

                    <h6>Deleting a task <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <div class="alert alert-warning mb-0 d-flex gap-2">
                        <i class="ti ti-alert-triangle fs-5 flex-shrink-0 mt-1"></i>
                        <div>Click the <strong>trash icon</strong> on a card to delete it. This is <strong>permanent</strong> and cannot be undone.</div>
                    </div>
                </div>
            </div>

            <!-- ── Priority & Colors ── -->
            <div id="priorities" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-flag fs-5 text-primary"></i>
                    <h5 class="mb-0">Priority &amp; Card Colors</h5>
                </div>
                <div class="card-body">
                    <h6>Priority levels</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light"><tr><th>Priority</th><th>When to use</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge bg-label-secondary">Low</span></td><td>Nice-to-have; no hard deadline pressure.</td></tr>
                                <tr><td><span class="badge bg-label-info">Medium</span></td><td>Standard tasks with a clear due date.</td></tr>
                                <tr><td><span class="badge bg-label-warning">High</span></td><td>Time-sensitive; should be completed soon.</td></tr>
                                <tr><td><span class="badge bg-label-danger">Critical</span></td><td>Urgent — blockers or imminent deadlines.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <h6>Card accent color</h6>
                    <p class="mb-0">When creating or editing a task, you can choose an optional <strong>card colour</strong>. This adds a coloured left border to the card, useful for visually grouping related tasks (e.g. all Finance tasks in blue, all Media tasks in orange) without changing priority.</p>
                </div>
            </div>

            <!-- ── Events ── -->
            <div id="events" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-calendar-event fs-5 text-primary"></i>
                    <h5 class="mb-0">Events</h5>
                </div>
                <div class="card-body">
                    <p>Events represent real-world programmes or projects (e.g. "FOSA Annual Gala 2026"). Each event has its own Kanban board, participant list, and dedicated manager.</p>

                    <h6>Creating an event <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <ol class="mb-3">
                        <li class="mb-1">Go to <strong>Events</strong> in the sidebar → <strong>Create Event</strong>.</li>
                        <li class="mb-1">Enter the event name, start date, end date, and description.</li>
                        <li class="mb-1">Assign a <strong>Manager</strong> — this member gains access to the Management View for this event.</li>
                        <li>Add participants via the multi-select member field, then save.</li>
                    </ol>
                    <p class="mb-3">All added participants receive an <strong>Event Created</strong> notification by in-app and email.</p>

                    <h6>Event Status</h6>
                    <div class="table-responsive mb-0">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light"><tr><th>Status</th><th>Meaning</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge bg-label-primary">Upcoming</span></td><td>Event start date is in the future.</td></tr>
                                <tr><td><span class="badge bg-label-warning">In Progress</span></td><td>Today falls within the event's start–end range.</td></tr>
                                <tr><td><span class="badge bg-label-success">Completed</span></td><td>Event end date has passed or manually marked done by leadership.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ── Event Management ── -->
            <div id="event-mgmt" class="doc-section card mb-4 doc-card success">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-chart-bar fs-5 text-primary"></i>
                    <h5 class="mb-0">Management View</h5>
                </div>
                <div class="card-body">
                    <p>The Management View gives a per-event analytics dashboard: task completion statistics, per-column task distribution, and a member workload table with OLM/UPM flags.</p>
                    <p><strong>Who can access it:</strong></p>
                    <ul class="mb-3">
                        <li><span class="doc-badge leader">Leadership</span> — all active events, via the collapsible <em>Management View</em> sidebar menu.</li>
                        <li><span class="doc-badge all">Event Manager</span> — only the event(s) they are assigned to manage.</li>
                    </ul>
                    <p class="mb-0">The dashboard also highlights <span class="badge bg-danger" style="font-size:.75rem;">Overloaded Members (OLM)</span> and <span class="badge bg-warning text-dark" style="font-size:.75rem;">Underperforming Members (UPM)</span> to help leadership redistribute tasks fairly.</p>
                </div>
            </div>

            <!-- ── Members ── -->
            <div id="members" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-users fs-5 text-primary"></i>
                    <h5 class="mb-0">Members</h5>
                </div>
                <div class="card-body">
                    <p>The Members page lists everyone in the organisation with their role, department(s), current status, and workload score.</p>

                    <h6>Adding a member <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <ol class="mb-3">
                        <li class="mb-1">Click <strong>Add Member</strong>.</li>
                        <li class="mb-1">Fill in full name, username, email, initial password, role, and availability status.</li>
                        <li>Optionally assign departments and save.</li>
                    </ol>
                    <p class="mb-3">The new member can log in immediately using the credentials leadership sets, and can change their password from their <a href="#profile">Profile</a> page.</p>

                    <h6>Availability Status</h6>
                    <p class="mb-3">Each member can set their own availability status from the <strong>Status</strong> dropdown in the top-right of the navbar. Leadership can also override a member's status from the Edit Member page.</p>
                    <div class="table-responsive mb-0">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light"><tr><th>Status</th><th>Meaning</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge bg-success">Free</span></td><td>Available and looking for tasks.</td></tr>
                                <tr><td><span class="badge bg-info">Available</span></td><td>Available but already has some tasks.</td></tr>
                                <tr><td><span class="badge bg-warning text-dark">Busy</span></td><td>Has tasks, can still accept minor ones.</td></tr>
                                <tr><td><span class="badge bg-danger">Very Busy</span></td><td>Overloaded — avoid assigning new tasks.</td></tr>
                                <tr><td><span class="badge bg-secondary">Not Available</span></td><td>Temporarily unavailable (e.g. on leave).</td></tr>
                                <tr><td><span class="badge bg-dark">Can't Be Bothered</span></td><td>🙃</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ── Departments ── -->
            <div id="departments" class="doc-section card mb-4 doc-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-building fs-5 text-primary"></i>
                    <h5 class="mb-0">Departments <span class="doc-badge leader ms-2">Leadership only</span></h5>
                </div>
                <div class="card-body">
                    <p>Departments are organisational units (e.g. "Media", "Finance", "Public Relations"). Members can belong to multiple departments.</p>
                    <ul class="mb-0">
                        <li>Create, rename, and delete departments from the <strong>Departments</strong> page in the sidebar.</li>
                        <li>Assign members to departments via the Members edit page or the Department edit form.</li>
                        <li>Department membership is shown on the Members list and used for filtering.</li>
                    </ul>
                </div>
            </div>

            <!-- ── Workload Indicators ── -->
            <div id="workload" class="doc-section card mb-4 doc-card warning">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-activity fs-5 text-primary"></i>
                    <h5 class="mb-0">Workload Indicators (OLM / UPM)</h5>
                </div>
                <div class="card-body">
                    <p>The system automatically flags members with unusual workloads. These badges appear in the <strong>assignee dropdown</strong> when creating or editing a task, and in the <strong>Management View</strong>.</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="card border-danger mb-0 h-100">
                                <div class="card-body">
                                    <span class="badge bg-danger mb-2">OLM — Overloaded Member</span>
                                    <p class="small mb-0">Member has a <strong>workload score ≥ 8</strong>. The score counts all active tasks (Backlog + To-do + In Progress) across the current event and the previous completed event. Avoid assigning more tasks unless necessary.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning mb-0 h-100">
                                <div class="card-body">
                                    <span class="badge bg-warning text-dark mb-2">UPM — Underperforming Member</span>
                                    <p class="small mb-0">Member has <strong>≤ 1 task</strong> assigned in the current event. This may indicate they need more responsibilities or are being overlooked when tasks are distributed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-primary mb-0 d-flex gap-2">
                        <i class="ti ti-info-circle fs-5 flex-shrink-0 mt-1"></i>
                        <div>A member can be <em>neither</em> OLM nor UPM. The badges only appear when the threshold is crossed. The workload score is updated in real time as tasks are assigned or completed.</div>
                    </div>
                </div>
            </div>

            <!-- ── My Profile ── -->
            <div id="profile" class="doc-section card mb-4 doc-card success">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-user-circle fs-5 text-primary"></i>
                    <h5 class="mb-0">My Profile <span class="doc-badge all ms-2">All users</span></h5>
                </div>
                <div class="card-body">
                    <p>Every user can manage their own account details from the <strong>Profile</strong> page. Access it by clicking your avatar in the top-right navbar and selecting <strong>My Profile</strong>.</p>

                    <h6>Account Information</h6>
                    <p class="mb-3">Update your <strong>full name</strong>, <strong>username</strong>, and <strong>email address</strong> at any time. Username and email must be unique across all accounts. Click <strong>Save Changes</strong> to apply.</p>

                    <h6>Profile Photo</h6>
                    <ol class="mb-3">
                        <li class="mb-1">Click the <strong>camera button</strong> on your avatar.</li>
                        <li class="mb-1">Select any JPG, PNG, or WebP image (max 2 MB).</li>
                        <li class="mb-1">A <strong>crop tool</strong> opens — drag to pan, scroll to zoom, and resize the crop box.</li>
                        <li>Click <strong>Apply &amp; Save</strong>. The photo is cropped to a square (400 × 400 px) and uploaded instantly.</li>
                    </ol>
                    <p class="mb-3">If no photo is uploaded, the system generates an avatar from your initials automatically.</p>

                    <h6>Change Password</h6>
                    <p class="mb-0">Enter your <strong>current password</strong> to verify your identity, then set a new password (minimum 8 characters). Your password is stored encrypted and is never visible in the interface.</p>
                </div>
            </div>

            <!-- ── Evaluations ── -->
            <div id="evaluations" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-clipboard-list fs-5 text-primary"></i>
                    <h5 class="mb-0">Evaluations</h5>
                </div>
                <div class="card-body">
                    <h6>For Leadership <span class="doc-badge leader ms-1">President / VP</span></h6>
                    <ol class="mb-4">
                        <li class="mb-1">Go to <strong>Evaluation Results</strong> → <strong>Create Evaluation</strong>.</li>
                        <li class="mb-1">Give it a title and description, then save (it starts as <em>Draft</em>).</li>
                        <li class="mb-1">Click <strong>Open</strong> to make it live — members see the <em>Fill Evaluation</em> link appear in the sidebar with a red <span class="badge bg-danger" style="font-size:.7rem;">Open</span> badge.</li>
                        <li class="mb-1">Once all submissions are collected, click <strong>Close</strong> to lock submissions and view aggregated results.</li>
                        <li>The results page shows a ranked leaderboard, per-member average scores, and individual comments.</li>
                    </ol>

                    <h6>For Members</h6>
                    <p class="mb-0">When an evaluation is open, click <strong>Fill Evaluation</strong> in the sidebar. Rate each fellow member on a scale, optionally leave comments, and submit. You can <strong>only submit once</strong> per evaluation — submissions cannot be edited after saving.</p>
                </div>
            </div>

            <!-- ── Notifications ── -->
            <div id="notifications" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-bell fs-5 text-primary"></i>
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    <p>FOSA sends notifications <strong>in-app</strong> (bell icon, top navbar) and by <strong>email</strong> whenever any of the following occur. The notification title is dynamic and reflects exactly what happened.</p>

                    <h6 class="mt-3">Task Movement</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light"><tr><th>Move</th><th>Notification title</th><th>Who receives it</th></tr></thead>
                            <tbody>
                                <tr><td>Any → <span class="badge bg-primary" style="font-size:.7rem;">To-do</span></td><td><code class="inline">Task To-do</code></td><td>All assignees (except the person who moved it)</td></tr>
                                <tr><td>Any → <span class="badge bg-warning text-dark" style="font-size:.7rem;">In Progress</span></td><td><code class="inline">Task In-progress</code></td><td>All assignees (except mover)</td></tr>
                                <tr><td>Any → <span class="badge bg-success" style="font-size:.7rem;">Done</span></td><td><code class="inline">Task Done</code></td><td>All assignees (except mover)</td></tr>
                                <tr><td><span class="badge bg-primary" style="font-size:.7rem;">To-do</span> → <span class="badge bg-secondary" style="font-size:.7rem;">Backlog</span></td><td><code class="inline">Task Holded (from To-do to Backlog)</code></td><td>All assignees (except mover)</td></tr>
                                <tr><td><span class="badge bg-warning text-dark" style="font-size:.7rem;">In Progress</span> or <span class="badge bg-success" style="font-size:.7rem;">Done</span> → <span class="badge bg-primary" style="font-size:.7rem;">To-do</span></td><td><code class="inline">Task back to To-do (from …)</code></td><td>All assignees (except mover)</td></tr>
                                <tr><td><span class="badge bg-success" style="font-size:.7rem;">Done</span> → <span class="badge bg-warning text-dark" style="font-size:.7rem;">In Progress</span></td><td><code class="inline">Task back to In-progress (from Done)</code></td><td>All assignees (except mover)</td></tr>
                                <tr><td>Any other move</td><td><code class="inline">Task Moved</code></td><td>All assignees (except mover)</td></tr>
                                <tr class="table-secondary"><td>Any → <span class="badge bg-dark" style="font-size:.7rem;">Archive</span></td><td colspan="2"><em>No notification sent</em></td></tr>
                                <tr class="table-secondary"><td><span class="badge bg-dark" style="font-size:.7rem;">Archive</span> → <span class="badge bg-success" style="font-size:.7rem;">Done</span></td><td colspan="2"><em>No notification sent</em></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h6>Task Done — Leadership Alert</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light"><tr><th>Event</th><th>Who receives it</th></tr></thead>
                            <tbody>
                                <tr><td>Task moved to <span class="badge bg-success" style="font-size:.7rem;">Done</span> (not from Archive)</td><td>Task creator, Event manager, all Presidents &amp; Vice Presidents (except the person who moved it)</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h6>Other Notifications</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light"><tr><th>Event</th><th>Who receives it</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge bg-label-success">Event Created</span></td><td>All participants added to the event.</td></tr>
                                <tr><td><span class="badge bg-label-primary">Task Assigned</span></td><td>Members newly assigned to a task (not the person assigning).</td></tr>
                                <tr><td><span class="badge bg-label-secondary">Task Unassigned</span></td><td>Members who were removed from a task.</td></tr>
                                <tr><td><span class="badge bg-label-info">Deadline Set</span></td><td>All assignees when a deadline is set for the first time (was blank, now has a date).</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h6>Managing notifications</h6>
                    <ul class="mb-0">
                        <li>Unread notifications show a <span class="badge bg-danger" style="font-size:.7rem;">red count badge</span> on the bell.</li>
                        <li>Click any notification to follow its link and mark it as read.</li>
                        <li>Use <strong>Mark all as read</strong> to clear the unread badge in one click.</li>
                        <li>The bell dropdown shows the 15 most recent notifications.</li>
                    </ul>
                </div>
            </div>

            <!-- ── Email Accounts ── -->
            <div id="email-accounts" class="doc-section card mb-4 doc-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-mail-cog fs-5 text-primary"></i>
                    <h5 class="mb-0">Email Accounts <span class="doc-badge leader ms-2">Leadership only</span></h5>
                </div>
                <div class="card-body">
                    <p>FOSA supports multiple outbound SMTP accounts so notifications are never silently dropped when a sender hits its daily sending limit. Accounts are tried in <strong>priority order</strong>; if one fails, the next is used automatically.</p>

                    <h6>Adding an account</h6>
                    <ol class="mb-3">
                        <li class="mb-1">Go to <strong>Email Accounts</strong> in the sidebar.</li>
                        <li class="mb-1">Click <strong>Add Account</strong>.</li>
                        <li class="mb-1">Fill in SMTP host, port, encryption (TLS / SSL / none), sender name, username, and password.</li>
                        <li class="mb-1">Set a <strong>Priority</strong> number — lower = tried first (e.g. priority 1 is the primary sender).</li>
                        <li>Toggle <strong>Active</strong> on and click Save.</li>
                    </ol>

                    <h6>Testing an account</h6>
                    <p class="mb-3">Click the <span class="badge bg-label-info"><i class="ti ti-send me-1"></i>Test</span> button on any row to send a test email to your own address. A green or red flash message will confirm success or display the SMTP error.</p>

                    <div class="alert alert-primary mb-0 d-flex gap-2">
                        <i class="ti ti-lock fs-5 flex-shrink-0 mt-1"></i>
                        <div>SMTP passwords are <strong>encrypted at rest</strong> in the database and are never shown in plain text through the UI.</div>
                    </div>
                </div>
            </div>

            <!-- ── PWA ── -->
            <div id="pwa" class="doc-section card mb-4 doc-card success">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-device-mobile fs-5 text-primary"></i>
                    <h5 class="mb-0">PWA / Install App <span class="doc-badge all ms-2">All users</span></h5>
                </div>
                <div class="card-body">
                    <p>FOSA is a <strong>Progressive Web App (PWA)</strong>. You can install it on your phone or desktop for a native-like full-screen experience — no app store required. Notification deep-links work correctly in the installed app.</p>

                    <h6>Install on iOS (Safari)</h6>
                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Open the FOSA URL in <strong>Safari</strong> (other browsers on iOS cannot install PWAs).</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>Tap the <strong>Share</strong> button (box with upward arrow) at the bottom of the screen.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">3</span><span>Scroll down and tap <strong>Add to Home Screen</strong>.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">4</span><span>Tap <strong>Add</strong>. FOSA appears as an app icon on your home screen.</span></div>
                    </div>

                    <h6>Install on Android (Chrome)</h6>
                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Open the FOSA URL in <strong>Chrome</strong>.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>Tap the <strong>three-dot menu</strong> and select <strong>Add to Home Screen</strong>, or look for an install banner / address-bar prompt.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">3</span><span>Tap <strong>Install</strong>. The app opens in a standalone window without browser chrome.</span></div>
                    </div>

                    <h6>Install on Desktop (Chrome / Edge)</h6>
                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Look for the install icon <strong>(⊕)</strong> at the right end of the browser address bar.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>Click it and select <strong>Install</strong>. FOSA opens as a dedicated app window.</span></div>
                    </div>

                    <div class="alert alert-primary mb-0 d-flex gap-2">
                        <i class="ti ti-info-circle fs-5 flex-shrink-0 mt-1"></i>
                        <div>If you don't see an install prompt, make sure you're logged in and accessing the site over <strong>HTTPS</strong>. The prompt may take a few seconds to appear after the page loads.</div>
                    </div>
                </div>
            </div>

        </div><!-- /col -->
    </div><!-- /row -->
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Active section highlight ──────────────────────────────────
    const links    = document.querySelectorAll('#docNav .nav-link');
    const sections = Array.from(links).map(l => document.querySelector(l.getAttribute('href')));

    const sectionObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                links.forEach(l => l.classList.remove('active'));
                const link = document.querySelector(`#docNav a[href="#${entry.target.id}"]`);
                if (link) link.classList.add('active');
            }
        });
    }, { rootMargin: '-20% 0px -70% 0px' });
    sections.forEach(s => { if (s) sectionObserver.observe(s); });

    // ── Sticky sidebar ────────────────────────────────────────────
    const colEl     = document.getElementById('docSidebarCol');
    const cardEl    = document.getElementById('docSidebarEl');
    const mainColEl = document.getElementById('docMainCol');
    if (!colEl || !cardEl || !mainColEl) return;

    // Detect the actual element that scrolls (Vuexy can scroll an inner container)
    function findScrollHost(el) {
        let node = el.parentElement;
        while (node && node !== document.documentElement) {
            const s = window.getComputedStyle(node);
            if (/auto|scroll/.test(s.overflow + s.overflowY)) return node;
            node = node.parentElement;
        }
        return window;
    }
    const scrollHost = findScrollHost(colEl);

    // Cached measurements — taken once before any stickiness is applied.
    // When card goes position:fixed the column collapses, so we can never
    // reliably re-measure from colEl after the first stick.
    let colDocTop = 0, mainDocBottom = 0, colNatWidth = 0, cardNatH = 0, navGap = 0;
    let ready = false;

    function getScrollTop() {
        return scrollHost === window
            ? (window.scrollY || window.pageYOffset)
            : scrollHost.scrollTop;
    }

    function measure() {
        // Unstick temporarily so measurements reflect natural layout
        const wasStuck = cardEl.classList.contains('is-sticky');
        if (wasStuck) {
            cardEl.classList.remove('is-sticky');
            cardEl.style.top = cardEl.style.width = '';
            colEl.style.minHeight = '';
        }

        const st = getScrollTop();
        const nav = document.querySelector('.layout-navbar');
        navGap       = (nav ? nav.offsetHeight : 64) + 24;
        colDocTop    = st + colEl.getBoundingClientRect().top;
        mainDocBottom= st + mainColEl.getBoundingClientRect().top + mainColEl.offsetHeight;
        colNatWidth  = cardEl.getBoundingClientRect().width;   // card's own width, not the column's
        cardNatH     = cardEl.offsetHeight;
        // Prevent the column from collapsing to 0 when card leaves the flow
        colEl.style.minHeight = cardNatH + 'px';
        ready = true;
    }

    function updateSticky() {
        if (!ready) return;
        const st       = getScrollTop();
        const stickAt  = colDocTop - navGap;
        const releaseAt= mainDocBottom - cardNatH - navGap;

        if (st >= stickAt && st <= releaseAt) {
            cardEl.classList.add('is-sticky');
            cardEl.style.top   = navGap + 'px';
            cardEl.style.width = colNatWidth + 'px';
        } else {
            cardEl.classList.remove('is-sticky');
            cardEl.style.top = cardEl.style.width = '';
        }
    }

    // Give the layout time to finish rendering before measuring
    setTimeout(function () { measure(); updateSticky(); }, 200);

    const evtEl = scrollHost === window ? window : scrollHost;
    evtEl.addEventListener('scroll', updateSticky, { passive: true });
    window.addEventListener('resize', function () { measure(); updateSticky(); }, { passive: true });
});
</script>
@endpush
@endsection
