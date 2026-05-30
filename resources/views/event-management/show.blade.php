@extends('layouts.app')
@section('title', 'Management — ' . $event->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
.member-workload-bar { height: 8px; border-radius: 4px; }
.status-indicator { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.overload-card { border-left: 3px solid #ea5455; }
.underperform-card { border-left: 3px solid #ff9f43; }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-1">
            <i class="ti ti-chart-bar me-2 text-primary"></i>Event Management
        </h4>
        <p class="text-muted mb-0">{{ $event->name }} — {{ ucfirst($event->status) }}</p>
    </div>
    <div class="col-auto d-flex gap-2">
        <a href="{{ route('dashboard.switch', $event->id) }}" class="btn btn-label-primary btn-sm">
            <i class="ti ti-layout-kanban me-1"></i> Kanban Board
        </a>
        <a href="{{ route('events.edit', $event) }}" class="btn btn-label-warning btn-sm">
            <i class="ti ti-edit me-1"></i> Edit Event
        </a>
    </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-4 mb-4">
    @php
    $statCards = [
        ['label' => 'Backlog',  'value' => $stats['backlog'],  'color' => 'secondary', 'icon' => 'ti-stack-2'],
        ['label' => 'To Do',    'value' => $stats['todo'],     'color' => 'info',      'icon' => 'ti-circle'],
        ['label' => 'In Progress','value'=> $stats['doing'],   'color' => 'warning',   'icon' => 'ti-progress'],
        ['label' => 'Done',     'value' => $stats['done'],     'color' => 'success',   'icon' => 'ti-circle-check'],
        ['label' => 'Total',    'value' => $stats['total'],    'color' => 'primary',   'icon' => 'ti-clipboard-list'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="col-6 col-md-4 col-xl">
        <div class="card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="avatar">
                    <span class="avatar-initial rounded bg-label-{{ $card['color'] }}">
                        <i class="ti {{ $card['icon'] }}"></i>
                    </span>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $card['value'] }}</h5>
                    <small class="text-muted">{{ $card['label'] }}</small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <!-- Task Progress Chart -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Task Distribution</h5>
            </div>
            <div class="card-body">
                <div id="taskDistributionChart"></div>
                @php $remaining = $stats['backlog'] + $stats['todo'] + $stats['doing']; @endphp
                <div class="text-center mt-3">
                    <h3 class="fw-bold text-primary">{{ $stats['total'] > 0 ? round(($stats['done'] / $stats['total']) * 100) : 0 }}%</h3>
                    <p class="text-muted mb-0">Overall Completion</p>
                    <small class="text-muted">{{ $remaining }} tasks remaining</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Status -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Member Status</h5>
            </div>
            <div class="card-body">
                <div id="memberStatusChart"></div>
                <div class="mt-3">
                    @php
                    $statusColors = ['free' => 'success', 'available' => 'info', 'busy' => 'warning', 'very_busy' => 'danger', 'not_available' => 'secondary', 'cant_be_bothered' => 'dark'];
                    @endphp
                    @foreach($statusCounts as $status => $count)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-indicator bg-{{ $statusColors[$status] ?? 'secondary' }}"></span>
                            <small>{{ ucfirst(str_replace('_', ' ', $status)) }}</small>
                        </div>
                        <span class="badge bg-label-{{ $statusColors[$status] ?? 'secondary' }}">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Event Info -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Event Info</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Manager</small>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $event->manager->avatar_url }}" class="rounded-circle" width="32" height="32" alt="">
                            <div>
                                <div class="fw-semibold">{{ $event->manager->name }}</div>
                                <small class="text-muted">{{ $event->manager->username }}</small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Event Date</small>
                        <p class="mb-0 fw-semibold">{{ $event->event_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Preparation Since</small>
                        <p class="mb-0 fw-semibold">{{ $event->start_preparing_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Committee Members</small>
                        <p class="mb-0 fw-semibold">{{ $event->members->count() }} members</p>
                    </div>
                    <div>
                        @php $daysLeft = now()->diffInDays($event->event_date, false); @endphp
                        <small class="text-muted text-uppercase fw-semibold">Days Until Event</small>
                        <p class="mb-0 fw-bold {{ $daysLeft < 7 ? 'text-danger' : ($daysLeft < 30 ? 'text-warning' : 'text-success') }}">
                            {{ $daysLeft > 0 ? $daysLeft . ' days' : ($daysLeft === 0 ? 'Today!' : abs($daysLeft) . ' days ago') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Member Workload Chart -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Member Workload (Current + Previous Event)</h5>
                <small class="text-muted">Overload threshold: 8 tasks</small>
            </div>
            <div class="card-body">
                <div id="workloadChart" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Overloaded Members -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-danger rounded-pill">{{ $overloaded->count() }}</span>
                <h5 class="card-title mb-0 text-danger">Overloaded Members</h5>
            </div>
            <div class="card-body p-0">
                @if($overloaded->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="ti ti-mood-happy ti-2x mb-2 d-block text-success"></i>
                    No overloaded members!
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Status</th>
                                <th>Tasks (Acc.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($memberWorkload->whereIn('id', $overloaded->pluck('id')) as $mw)
                            <tr class="overload-card">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $mw['avatar'] }}" class="rounded-circle" width="32" height="32" alt="">
                                        <div>
                                            <div class="fw-semibold">{{ $mw['name'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-label-{{ $statusColors[$mw['status']] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $mw['status'])) }}</span></td>
                                <td>
                                    <span class="badge bg-danger">{{ $mw['total'] }}</span>
                                    <small class="text-muted ms-1">({{ $mw['current'] }}+{{ $mw['previous'] }})</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Underperforming Members -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-warning rounded-pill">{{ $underperform->count() }}</span>
                <h5 class="card-title mb-0 text-warning">Underperforming Members</h5>
            </div>
            <div class="card-body p-0">
                @if($underperform->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="ti ti-trophy ti-2x mb-2 d-block text-success"></i>
                    Everyone is contributing!
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Done (Acc.)</th>
                                <th>Total Assigned (Acc.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($memberWorkload->whereIn('id', $underperform->pluck('id')) as $mw)
                            <tr class="underperform-card">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $mw['avatar'] }}" class="rounded-circle" width="32" height="32" alt="">
                                        <span class="fw-semibold">{{ $mw['name'] }}</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">{{ $mw['done'] }}</span></td>
                                <td><span class="badge bg-secondary">{{ $mw['total'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All Members Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">All Committee Members</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Departments</th>
                        <th>Status</th>
                        <th>Tasks (Cur.)</th>
                        <th>Tasks (Prev.)</th>
                        <th>Done (Acc.)</th>
                        <th>Total Load</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($memberWorkload as $mw)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $mw['avatar'] }}" class="rounded-circle" width="36" height="36" alt="">
                                <div>
                                    <div class="fw-semibold">{{ $mw['name'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php $memberModel = $event->members->firstWhere('id', $mw['id']); @endphp
                            @if($memberModel && $memberModel->departments->isNotEmpty())
                                @foreach($memberModel->departments as $dept)
                                <span class="badge bg-label-info me-1">{{ $dept->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-label-{{ $statusColors[$mw['status']] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $mw['status'])) }}</span></td>
                        <td>{{ $mw['current'] }}</td>
                        <td>{{ $mw['previous'] }}</td>
                        <td><span class="badge bg-success">{{ $mw['done'] }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $mw['total'] >= 8 ? 'danger' : ($mw['total'] >= 5 ? 'warning' : 'success') }}"
                                         style="width: {{ min(($mw['total'] / 12) * 100, 100) }}%"></div>
                                </div>
                                <small class="fw-semibold {{ $mw['total'] >= 8 ? 'text-danger' : '' }}">{{ $mw['total'] }}</small>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vuexy/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
(function () {
    // Task Distribution Donut
    const taskData = {
        backlog: {{ $stats['backlog'] }},
        todo:    {{ $stats['todo'] }},
        doing:   {{ $stats['doing'] }},
        done:    {{ $stats['done'] }},
        archive: {{ $stats['archive'] }},
    };

    new ApexCharts(document.getElementById('taskDistributionChart'), {
        chart: { type: 'donut', height: 220 },
        labels: ['Backlog', 'To Do', 'Doing', 'Done', 'Archive'],
        series: Object.values(taskData),
        colors: ['#8c8c8c', '#00cfe8', '#ff9f43', '#28c76f', '#4b4b4b'],
        legend: { show: false },
        dataLabels: { enabled: false },
    }).render();

    // Member Status Donut
    const statusData = @json($statusCounts);
    const statusLabels = Object.keys(statusData).map(s => s.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()));
    new ApexCharts(document.getElementById('memberStatusChart'), {
        chart: { type: 'donut', height: 200 },
        labels: statusLabels,
        series: Object.values(statusData),
        legend: { show: false },
        dataLabels: { enabled: false },
    }).render();

    // Member Workload Bar Chart
    const workloadData = @json($memberWorkload);
    new ApexCharts(document.getElementById('workloadChart'), {
        chart: { type: 'bar', height: 300, stacked: true, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        xaxis: { categories: workloadData.map(m => m.name) },
        series: [
            { name: 'Current Event', data: workloadData.map(m => m.current), color: '#2563eb' },
            { name: 'Previous Event', data: workloadData.map(m => m.previous), color: '#a8a2e8' },
        ],
        legend: { position: 'top' },
        dataLabels: { enabled: false },
        annotations: {
            xaxis: [{ x: 8, borderColor: '#ea5455', label: { text: 'Overload Threshold (8)', style: { color: '#ea5455' } } }]
        },
    }).render();
})();
</script>
@endpush
