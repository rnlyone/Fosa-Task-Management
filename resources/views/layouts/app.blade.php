<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('vuexy') }}/" data-template="vertical-menu-template"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Dashboard') | FOSA Task Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('vuexy/img/favicon/favicon.ico') }}" />

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <meta name="theme-color" content="#2563eb" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="FOSA" />
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('vuexy/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/typeahead-js/typeahead.css') }}" />

    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('vuexy/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('vuexy/js/config.js') }}"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Sidebar Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('dashboard') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('fosalogo.png') }}" alt="FOSA" style="height:32px;width:auto;" />
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold">FOSA</span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
                        <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-layout-kanban"></i>
                            <div>Kanban Board</div>
                        </a>
                    </li>

                    <!-- Events -->
                    <li class="menu-header small">
                        <span class="menu-header-text">Event Management</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <a href="{{ route('events.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-calendar-event"></i>
                            <div>Events</div>
                        </a>
                    </li>

                    @if(auth()->user()->isLeadership() || auth()->user()->managedEvents()->exists())
                    <li class="menu-item {{ request()->routeIs('event-management.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons ti ti-chart-bar"></i>
                            <div>Management View</div>
                        </a>
                        <ul class="menu-sub">
                            @php
                                $sidebarUser = auth()->user();
                                $mgmtEvents = \App\Models\Event::where('status', '!=', 'completed')
                                    ->when(!$sidebarUser->isLeadership(), fn($q) => $q->where('manager_id', $sidebarUser->id))
                                    ->latest()->take(5)->get();
                            @endphp
                            @foreach($mgmtEvents as $ev)
                            <li class="menu-item">
                                <a href="{{ route('event-management.show', $ev) }}" class="menu-link">
                                    <div>{{ Str::limit($ev->name, 25) }}</div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif

                    <!-- Members & Departments -->
                    <li class="menu-header small">
                        <span class="menu-header-text">Organizations</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
                        <a href="{{ route('members.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-users"></i>
                            <div>Members</div>
                        </a>
                    </li>
                    @if(auth()->user()->isLeadership())
                    <li class="menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        <a href="{{ route('departments.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-building"></i>
                            <div>Departments</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('mailer-accounts.*') ? 'active' : '' }}">
                        <a href="{{ route('mailer-accounts.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-mail-cog"></i>
                            <div>Email Accounts</div>
                        </a>
                    </li>
                    @endif

                    <!-- Evaluations -->
                    <li class="menu-header small">
                        <span class="menu-header-text">Evaluations</span>
                    </li>
                    @if(auth()->user()->isLeadership())
                    <li class="menu-item {{ request()->routeIs('evaluations.index', 'evaluations.show', 'evaluations.create') ? 'active' : '' }}">
                        <a href="{{ route('evaluations.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-clipboard-list"></i>
                            <div>Evaluation Results</div>
                        </a>
                    </li>
                    @endif
                    @php
                        $now = now();
                        $openEval = \App\Models\Evaluation::where(function($q) use ($now) {
                            $q->where(function($q2) use ($now) {
                                $q2->whereNull('opens_at')->orWhere('opens_at', '<=', $now);
                            })->where(function($q2) use ($now) {
                                $q2->whereNull('closes_at')->orWhere('closes_at', '>=', $now);
                            });
                        })->latest()->first();
                    @endphp
                    @if($openEval)
                    <li class="menu-item">
                        <a href="{{ route('evaluations.form', $openEval) }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-pencil"></i>
                            <div>Fill Evaluation</div>
                            <div class="badge bg-danger rounded-pill ms-auto">Open</div>
                        </a>
                    </li>
                    @endif

                    <!-- Documentation -->
                    <li class="menu-header small mt-auto">
                        <span class="menu-header-text">Help</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('docs') ? 'active' : '' }}">
                        <a href="{{ route('docs') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-book-2"></i>
                            <div>Documentation</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout page -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <span class="d-none d-md-inline-block text-muted fw-normal">FOSA Task Management</span>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Status Dropdown -->
                            @php
                                $statuses = [
                                    'free'             => ['label' => 'Free',              'color' => 'success'],
                                    'available'        => ['label' => 'Available',         'color' => 'info'],
                                    'busy'             => ['label' => 'Busy',              'color' => 'warning'],
                                    'very_busy'        => ['label' => 'Very Busy',         'color' => 'danger'],
                                    'not_available'    => ['label' => 'Not Available',     'color' => 'secondary'],
                                    'cant_be_bothered' => ['label' => "Can't Be Bothered", 'color' => 'dark'],
                                ];
                                $currentStatus = auth()->user()->status;
                                $currentMeta   = $statuses[$currentStatus] ?? ['label' => 'Unknown', 'color' => 'secondary'];
                            @endphp
                            <li class="nav-item dropdown me-1">
                                <a class="nav-link dropdown-toggle hide-arrow btn btn-sm btn-{{ $currentMeta['color'] }} d-flex align-items-center gap-1 px-2 py-1"
                                   href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius:20px;">
                                    <i class="ti ti-circle-dot ti-xs"></i>
                                    <span style="font-size:.75rem;">{{ $currentMeta['label'] }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width:180px;">
                                    <li><h6 class="dropdown-header">My Status</h6></li>
                                    @foreach($statuses as $val => $meta)
                                    <li>
                                        <form method="POST" action="{{ route('profile.status') }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $val }}">
                                            <button type="submit"
                                                class="dropdown-item d-flex align-items-center gap-2 {{ $currentStatus === $val ? 'active' : '' }}">
                                                <span class="badge bg-{{ $meta['color'] }} p-1 rounded-circle" style="width:10px;height:10px;"></span>
                                                {{ $meta['label'] }}
                                                @if($currentStatus === $val)
                                                <i class="ti ti-check ms-auto ti-xs"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <!-- Notifications -->
                            @php
                                $notifications     = auth()->user()->notifications()->latest()->take(15)->get();
                                $unreadCount       = auth()->user()->unreadNotifications()->count();
                                $notifIcons        = ['event_created' => 'ti-calendar-event', 'task_assigned' => 'ti-clipboard-check'];
                                $notifColors       = ['event_created' => 'primary', 'task_assigned' => 'warning'];
                            @endphp
                            <li class="nav-item dropdown me-1">
                                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill position-relative" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-bell ti-md"></i>
                                    @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;padding:2px 5px;">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-0" style="width:360px;max-height:480px;overflow:hidden;">
                                    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                                        <h6 class="mb-0 fw-semibold">Notifications</h6>
                                        @if($unreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.read-all') }}">
                                            @csrf
                                            <button class="btn btn-sm btn-text-primary p-0" style="font-size:.75rem;">Mark all read</button>
                                        </form>
                                        @endif
                                    </div>
                                    <ul class="list-unstyled mb-0 overflow-auto" style="max-height:390px;">
                                        @forelse($notifications as $notif)
                                        @php
                                            $nType  = $notif->data['type'] ?? 'task_assigned';
                                            $nIcon  = $notifIcons[$nType] ?? 'ti-bell';
                                            $nColor = $notifColors[$nType] ?? 'secondary';
                                        @endphp
                                        <li class="notif-item {{ $notif->read_at ? '' : 'bg-primary bg-opacity-5' }}" data-id="{{ $notif->id }}">
                                            <a href="{{ $notif->data['url'] ?? '#' }}"
                                               class="d-flex align-items-start gap-3 px-3 py-2 text-body text-decoration-none notif-link"
                                               data-notif-id="{{ $notif->id }}">
                                                <span class="avatar avatar-sm flex-shrink-0">
                                                    <span class="avatar-initial rounded-circle bg-label-{{ $nColor }}">
                                                        <i class="ti {{ $nIcon }} ti-sm"></i>
                                                    </span>
                                                </span>
                                                <div class="flex-grow-1" style="min-width:0;">
                                                    <p class="mb-0 fw-semibold" style="font-size:.8125rem;line-height:1.3;">{{ $notif->data['title'] ?? '' }}</p>
                                                    <p class="mb-0 text-muted text-truncate" style="font-size:.75rem;">{{ $notif->data['body'] ?? '' }}</p>
                                                    <small class="text-muted" style="font-size:.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if(!$notif->read_at)
                                                <span class="flex-shrink-0 mt-1">
                                                    <span class="badge bg-primary rounded-circle p-1" style="width:8px;height:8px;"></span>
                                                </span>
                                                @endif
                                            </a>
                                        </li>
                                        @empty
                                        <li class="text-center text-muted py-4" style="font-size:.875rem;">
                                            <i class="ti ti-bell-off ti-lg d-block mb-2"></i>No notifications
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </li>
                            <!-- / Notifications -->
                            <!-- Style Switcher -->
                            <li class="nav-item dropdown-style-switcher dropdown me-1">
                                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class='ti ti-md'></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                    <li><a class="dropdown-item" href="javascript:void(0);" data-theme="light"><span><i class='ti ti-sun ti-md me-3'></i>Light</span></a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" data-theme="dark"><span><i class="ti ti-moon-stars ti-md me-3"></i>Dark</span></a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" data-theme="system"><span><i class="ti ti-device-desktop-analytics ti-md me-3"></i>System</span></a></li>
                                </ul>
                            </li>

                            <!-- User Dropdown -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown me-1">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <div class="dropdown-item mt-0">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ auth()->user()->avatar_url }}" alt class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li><div class="dropdown-divider my-1 mx-n2"></div></li>
                                    <li>
                                        <a href="{{ route('profile.show') }}" class="dropdown-item d-flex align-items-center gap-2">
                                            <i class="ti ti-user-circle ti-sm"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider my-1 mx-n2"></div></li>
                                    <li>
                                        <div class="d-grid px-2 pt-2 pb-1">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger d-flex w-100">
                                                    <small class="align-middle">Logout</small>
                                                    <i class="ti ti-logout ms-2 ti-14px"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        @if(session('info'))
                        <div class="alert alert-info alert-dismissible" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @yield('content')
                    </div>

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">© {{ date('Y') }} FOSA Task Management</div>
                            </div>
                        </div>
                    </footer>

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('vuexy/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('vuexy/js/main.js') }}"></script>

    @stack('scripts')
    <script>
    // Mark individual notification as read on click
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.notif-link');
        if (!link) return;
        const id = link.dataset.notifId;
        if (!id) return;
        const li = link.closest('.notif-item');
        // Fire-and-forget — don't block navigation
        fetch('/notifications/' + id + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        // Remove unread indicator immediately
        li.classList.remove('bg-primary', 'bg-opacity-5');
        const dot = li.querySelector('.badge.rounded-circle');
        if (dot) dot.remove();
        // Decrement badge
        const badge = document.querySelector('.nav-item .badge.bg-danger');
        if (badge) {
            const current = parseInt(badge.textContent) || 1;
            if (current <= 1) badge.remove();
            else badge.textContent = current - 1;
        }
    });
    </script>

    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
