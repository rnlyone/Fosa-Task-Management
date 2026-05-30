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
            <span class="badge bg-label-primary rounded-pill">Version 1.0</span>
        </div>
    </div>

    <div class="row">
        <!-- Sticky sidebar nav -->
        <div class="col-lg-3 d-none d-lg-block doc-sidebar-col" id="docSidebarCol">
            <div class="doc-sidebar card card-body p-2" id="docSidebarEl">
                <p class="text-uppercase text-muted fw-semibold" style="font-size:0.7rem;letter-spacing:.08em;padding:.3rem .75rem;margin-bottom:0;">Contents</p>
                <nav class="nav flex-column" id="docNav">
                    <a href="#overview"       class="nav-link">Overview</a>
                    <a href="#roles"          class="nav-link">User Roles</a>
                    <a href="#kanban"         class="nav-link">Kanban Board</a>
                    <a href="#tasks"          class="nav-link sub">Tasks</a>
                    <a href="#events"         class="nav-link">Events</a>
                    <a href="#event-mgmt"     class="nav-link sub">Management View</a>
                    <a href="#members"        class="nav-link">Members</a>
                    <a href="#departments"    class="nav-link sub">Departments</a>
                    <a href="#evaluations"    class="nav-link">Evaluations</a>
                    <a href="#notifications"  class="nav-link">Notifications</a>
                    <a href="#email-accounts" class="nav-link">Email Accounts</a>
                    <a href="#pwa"            class="nav-link">PWA / Install App</a>
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
                        <strong>FOSA Task Management</strong> is an internal operations platform for the FOSA organisation.
                        It centralises task delegation, event management, member coordination, and periodic evaluations
                        in one place — with real-time in-app <em>and</em> email notifications keeping everyone in sync.
                    </p>
                    <div class="row g-3">
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
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Label</th>
                                    <th>Capabilities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="doc-badge president">President</span></td>
                                    <td><code class="inline">president</code></td>
                                    <td>Full access to all features, including member management, departments, evaluations, email accounts, and all event data.</td>
                                </tr>
                                <tr>
                                    <td><span class="doc-badge vp">Vice President</span></td>
                                    <td><code class="inline">vice_president</code></td>
                                    <td>Same as President. Both are referred to as <em>Leadership</em> in the system.</td>
                                </tr>
                                <tr>
                                    <td><span class="doc-badge all">Member</span></td>
                                    <td><code class="inline">member</code></td>
                                    <td>View assigned tasks, view events they belong to, fill evaluation forms. Cannot manage members, departments, or email accounts.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-primary mb-0 d-flex gap-2">
                        <i class="ti ti-info-circle fs-5 flex-shrink-0 mt-1"></i>
                        <div>Roles are assigned by Leadership on the <strong>Members</strong> page. A member's role can be changed at any time.</div>
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
                    <p>The Kanban Board is your home screen. It shows tasks for the <strong>active event</strong> organised into four columns:</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="card border mb-0 h-100">
                                <div class="card-body text-center py-3">
                                    <span class="badge bg-secondary mb-2">To Do</span>
                                    <p class="small mb-0">Newly created tasks awaiting action.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border mb-0 h-100">
                                <div class="card-body text-center py-3">
                                    <span class="badge bg-primary mb-2">In Progress</span>
                                    <p class="small mb-0">Tasks currently being worked on.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border mb-0 h-100">
                                <div class="card-body text-center py-3">
                                    <span class="badge bg-warning mb-2">In Review</span>
                                    <p class="small mb-0">Completed work waiting for approval.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border mb-0 h-100">
                                <div class="card-body text-center py-3">
                                    <span class="badge bg-success mb-2">Done</span>
                                    <p class="small mb-0">Finished and approved tasks.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>If you are a member of multiple events, a <strong>Switch Event</strong> button appears in the header letting you change which event's board is shown.</p>
                    <p class="mb-0">Each column header shows a badge with <strong>your</strong> task count in that column.</p>
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
                    <ol class="mb-3">
                        <li class="mb-1">Click <strong>+ Add Task</strong> on any Kanban column.</li>
                        <li class="mb-1">Fill in the title, description, deadline, and priority.</li>
                        <li class="mb-1">Assign one or more members from the event's participant list.</li>
                        <li>Click <strong>Save</strong>. Assigned members receive an in-app and email notification.</li>
                    </ol>
                    <h6>Moving tasks</h6>
                    <p class="mb-3">Drag-and-drop a task card to move it between columns, or use the arrow buttons on the card. When a task is moved to <strong>Done</strong>, all assignees are notified.</p>
                    <h6>Editing &amp; deleting <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <p>Click the pencil icon on a card to edit title, description, deadline, assignees, or priority. Use the trash icon to delete. Unassigned members receive a notification when removed.</p>
                    <div class="alert alert-warning mb-0 d-flex gap-2">
                        <i class="ti ti-alert-triangle fs-5 flex-shrink-0 mt-1"></i>
                        <div>Deleting a task is permanent and cannot be undone.</div>
                    </div>
                </div>
            </div>

            <!-- ── Events ── -->
            <div id="events" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-calendar-event fs-5 text-primary"></i>
                    <h5 class="mb-0">Events</h5>
                </div>
                <div class="card-body">
                    <p>Events represent real-world programmes or projects (e.g. "FOSA Annual Gala 2026"). Each event has its own task board, participant list, and manager.</p>
                    <h6>Creating an event <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <ol class="mb-3">
                        <li class="mb-1">Go to <strong>Events</strong> in the sidebar.</li>
                        <li class="mb-1">Click <strong>Create Event</strong>.</li>
                        <li class="mb-1">Enter the event name, date, and description.</li>
                        <li class="mb-1">Assign a <strong>Manager</strong> — this member can access the Event Management View for this event.</li>
                        <li>Add participants using the multi-select member field.</li>
                    </ol>
                    <p>When an event is created, all added participants receive an <strong>Event Created</strong> notification via in-app and email.</p>

                    <h6 class="mt-3">Event Status</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light"><tr><th>Status</th><th>Meaning</th></tr></thead>
                            <tbody>
                                <tr><td><span class="badge bg-label-primary">Upcoming</span></td><td>Event date is in the future.</td></tr>
                                <tr><td><span class="badge bg-label-warning">In Progress</span></td><td>Today falls within the event's start–end range.</td></tr>
                                <tr><td><span class="badge bg-label-success">Completed</span></td><td>Event date has passed or marked done by leadership.</td></tr>
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
                    <p>The <strong>Management View</strong> gives a per-event overview: task completion statistics, member workloads, and a summary table.</p>
                    <p>Accessible to:</p>
                    <ul class="mb-0">
                        <li><span class="doc-badge leader">Leadership</span> — all active events.</li>
                        <li><span class="doc-badge all">Event Manager</span> — only the event(s) they manage.</li>
                    </ul>
                </div>
            </div>

            <!-- ── Members ── -->
            <div id="members" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-users fs-5 text-primary"></i>
                    <h5 class="mb-0">Members</h5>
                </div>
                <div class="card-body">
                    <p>The Members page lists everyone in the organisation with their role, department(s), status, and workload score.</p>

                    <h6>Adding a member <span class="doc-badge leader ms-1">Leadership</span></h6>
                    <ol class="mb-3">
                        <li class="mb-1">Click <strong>Add Member</strong>.</li>
                        <li class="mb-1">Fill in name, username, email, initial password, role, and status.</li>
                        <li>Optionally assign departments.</li>
                    </ol>

                    <h6>Workload Score</h6>
                    <p class="mb-0">A number shows how many active tasks a member has across the current and previous event. It helps leadership distribute work fairly when assigning tasks.</p>
                </div>
            </div>

            <!-- ── Departments ── -->
            <div id="departments" class="doc-section card mb-4 doc-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-building fs-5 text-primary"></i>
                    <h5 class="mb-0">Departments <span class="doc-badge leader ms-2">Leadership only</span></h5>
                </div>
                <div class="card-body">
                    <p>Departments are organisational units (e.g. "Media", "Finance"). Members can belong to multiple departments.</p>
                    <ul class="mb-0">
                        <li>Create, rename, and delete departments from the <strong>Departments</strong> page.</li>
                        <li>Assign members to departments via the Members page or the Department edit form.</li>
                    </ul>
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
                    <ol class="mb-3">
                        <li class="mb-1">Go to <strong>Evaluation Results</strong> and click <strong>Create Evaluation</strong>.</li>
                        <li class="mb-1">Give it a title and description, then save.</li>
                        <li class="mb-1">Click <strong>Open</strong> to make it live — members will see the <em>Fill Evaluation</em> link in their sidebar.</li>
                        <li>Once all submissions are in, click <strong>Close</strong> to lock it and view aggregated results.</li>
                    </ol>
                    <h6>For Members</h6>
                    <p class="mb-0">When an evaluation is open, a <span class="badge bg-danger">Open</span> badge appears next to <strong>Fill Evaluation</strong> in the sidebar. Click it, rate each member, optionally leave comments, and submit. You can only submit once per evaluation.</p>
                </div>
            </div>

            <!-- ── Notifications ── -->
            <div id="notifications" class="doc-section card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-bell fs-5 text-primary"></i>
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    <p>FOSA sends notifications both in-app (bell icon in the top navbar) and by email whenever any of the following occur:</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-3">
                            <thead class="table-light">
                                <tr><th>Event</th><th>Who is notified</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><span class="badge bg-label-primary">Event Created</span></td><td>All participants added to the event.</td></tr>
                                <tr><td><span class="badge bg-label-success">Task Assigned</span></td><td>Members newly assigned to a task.</td></tr>
                                <tr><td><span class="badge bg-label-secondary">Task Unassigned</span></td><td>Members removed from a task.</td></tr>
                                <tr><td><span class="badge bg-label-warning">Task Moved</span></td><td>All assignees of a task when it changes column.</td></tr>
                                <tr><td><span class="badge bg-label-info">Task Done</span></td><td>All assignees when a task reaches the <em>Done</em> column.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mb-0">Unread notifications show a red badge on the bell. Click a notification to mark it read, or use <strong>Mark all as read</strong> to clear them all.</p>
                </div>
            </div>

            <!-- ── Email Accounts ── -->
            <div id="email-accounts" class="doc-section card mb-4 doc-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-mail-cog fs-5 text-primary"></i>
                    <h5 class="mb-0">Email Accounts <span class="doc-badge leader ms-2">Leadership only</span></h5>
                </div>
                <div class="card-body">
                    <p>FOSA supports multiple outbound SMTP accounts so notifications are never silently dropped when a sender hits its daily sending limit. Accounts are tried in priority order; if one fails, the next is used automatically.</p>

                    <h6>Adding an account</h6>
                    <ol class="mb-3">
                        <li class="mb-1">Go to <strong>Email Accounts</strong> in the sidebar.</li>
                        <li class="mb-1">Click <strong>Add Account</strong>.</li>
                        <li class="mb-1">Fill in the SMTP details: host, port, encryption, username, and password.</li>
                        <li class="mb-1">Set a <strong>Priority</strong> (lower number = tried first).</li>
                        <li>Toggle <strong>Active</strong> on and click Save.</li>
                    </ol>

                    <h6>Testing an account</h6>
                    <p class="mb-3">Click the <span class="badge bg-label-info"><i class="ti ti-send me-1"></i>Test</span> button on any account row to send a test email to your own address. A green or red flash message will confirm success or show the error.</p>

                    <div class="alert alert-primary mb-0 d-flex gap-2">
                        <i class="ti ti-lock fs-5 flex-shrink-0 mt-1"></i>
                        <div>Passwords are <strong>encrypted at rest</strong> in the database and are never exposed in plain text through the interface.</div>
                    </div>
                </div>
            </div>

            <!-- ── PWA ── -->
            <div id="pwa" class="doc-section card mb-4 doc-card success">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="ti ti-device-mobile fs-5 text-primary"></i>
                    <h5 class="mb-0">PWA / Install App</h5>
                </div>
                <div class="card-body">
                    <p>FOSA is a <strong>Progressive Web App</strong> — you can install it on your phone or desktop for a native-like experience without going through an app store.</p>
                    <h6>Install on iOS (Safari)</h6>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Open the FOSA URL in Safari.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>Tap the <strong>Share</strong> button (box with arrow).</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">3</span><span>Scroll and tap <strong>Add to Home Screen</strong>.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">4</span><span>Tap <strong>Add</strong> — FOSA now appears as an app icon.</span></div>
                    </div>
                    <h6>Install on Android (Chrome)</h6>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Open the FOSA URL in Chrome.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>Tap the three-dot menu and select <strong>Add to Home Screen</strong>, or look for the install prompt in the address bar.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">3</span><span>Tap <strong>Install</strong>.</span></div>
                    </div>
                    <h6>Install on Desktop (Chrome / Edge)</h6>
                    <div class="d-flex flex-column gap-2 mb-0">
                        <div class="d-flex align-items-start gap-2"><span class="step-num">1</span><span>Look for the install icon (⊕) in the browser address bar.</span></div>
                        <div class="d-flex align-items-start gap-2"><span class="step-num">2</span><span>Click it and select <strong>Install</strong>.</span></div>
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
