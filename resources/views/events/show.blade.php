@extends('layouts.app')
@section('title', $event->name)

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-calendar-event me-2 text-primary"></i>{{ $event->name }}</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
            <li class="breadcrumb-item active">{{ $event->name }}</li>
        </ol></nav>
    </div>
    <div class="col-auto d-flex gap-2">
        @if(auth()->user()->isLeadership() || $event->manager_id === auth()->id())
        <a href="{{ route('event-management.show', $event) }}" class="btn btn-label-info"><i class="ti ti-chart-bar me-1"></i> Management</a>
        @endif
        <a href="{{ route('dashboard.switch', $event) }}" class="btn btn-label-primary"><i class="ti ti-layout-kanban me-1"></i> Kanban</a>
        @if(auth()->user()->isLeadership())
        <a href="{{ route('events.edit', $event) }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit</a>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- Event Info Card --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Event Details</h5></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center mb-3">
                        <span class="badge bg-label-primary p-2 me-3"><i class="ti ti-tag ti-sm"></i></span>
                        <div>
                            <small class="text-muted d-block">Status</small>
                            @php
                                $statusColors = ['planning'=>'secondary','active'=>'success','completed'=>'info','cancelled'=>'danger'];
                                $color = $statusColors[$event->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($event->status) }}</span>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span class="badge bg-label-info p-2 me-3"><i class="ti ti-calendar ti-sm"></i></span>
                        <div>
                            <small class="text-muted d-block">Event Date</small>
                            <strong>{{ $event->event_date ? $event->event_date->format('d M Y') : 'TBD' }}</strong>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span class="badge bg-label-warning p-2 me-3"><i class="ti ti-clock ti-sm"></i></span>
                        <div>
                            <small class="text-muted d-block">Preparation Start</small>
                            <strong>{{ $event->start_preparing_date ? $event->start_preparing_date->format('d M Y') : 'TBD' }}</strong>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span class="badge bg-label-success p-2 me-3"><i class="ti ti-user-check ti-sm"></i></span>
                        <div>
                            <small class="text-muted d-block">Manager</small>
                            <strong>{{ $event->manager->name ?? 'Unassigned' }}</strong>
                        </div>
                    </li>
                    @if($event->description)
                    <li class="mt-4">
                        <small class="text-muted d-block mb-1">Description</small>
                        <p class="mb-0">{{ $event->description }}</p>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- Task Stats --}}
    <div class="col-xl-8">
        @php $stats = $event->taskStats(); @endphp
        <div class="row g-3 mb-4">
            @php
                $cols = [
                    ['label'=>'Total', 'val'=>$stats['total'], 'icon'=>'ti-list', 'color'=>'primary'],
                    ['label'=>'Done', 'val'=>$stats['done'], 'icon'=>'ti-circle-check', 'color'=>'success'],
                    ['label'=>'In Progress', 'val'=>$stats['doing'], 'icon'=>'ti-progress', 'color'=>'warning'],
                    ['label'=>'Overdue', 'val'=>$stats['overdue'], 'icon'=>'ti-alarm', 'color'=>'danger'],
                ];
            @endphp
            @foreach($cols as $c)
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-none bg-label-{{ $c['color'] }} h-100">
                    <div class="card-body text-center py-3">
                        <i class="ti {{ $c['icon'] }} ti-lg text-{{ $c['color'] }} mb-2"></i>
                        <h3 class="mb-0 text-{{ $c['color'] }}">{{ $c['val'] }}</h3>
                        <small class="text-muted">{{ $c['label'] }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($stats['total'] > 0)
        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between mb-1">
                    <small>Progress</small>
                    <small class="fw-semibold">{{ $stats['completion_rate'] }}%</small>
                </div>
                <div class="progress" style="height:10px">
                    <div class="progress-bar bg-success" style="width:{{ $stats['completion_rate'] }}%"></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Members --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Members ({{ $event->members->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($event->members as $member)
                    @php
                        $statusColors = ['free'=>'success','available'=>'info','busy'=>'warning','very_busy'=>'danger','not_available'=>'secondary','cant_be_bothered'=>'dark'];
                        $sc = $statusColors[$member->status] ?? 'secondary';
                        $taskCount = $member->tasks()->whereHas('event', fn($q)=>$q->where('id',$event->id))->count();
                    @endphp
                    <div class="col-md-4 col-lg-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="avatar me-3">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle" width="40" height="40">
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="fw-semibold mb-0 text-truncate">{{ $member->name }}</p>
                                <span class="badge bg-label-{{ $sc }} badge-sm">{{ ucfirst(str_replace('_',' ',$member->status)) }}</span>
                            </div>
                            <span class="badge bg-primary rounded-pill ms-2">{{ $taskCount }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
