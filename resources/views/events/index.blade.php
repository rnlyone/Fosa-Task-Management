@extends('layouts.app')
@section('title', 'Events')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
@endpush

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-calendar-event me-2 text-primary"></i>Events</h4>
    </div>
    @if(auth()->user()->isLeadership())
    <div class="col-auto">
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> New Event
        </a>
    </div>
    @endif
</div>

<div class="row g-4">
    @forelse($events as $event)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-0">{{ $event->name }}</h5>
                    <span class="badge bg-label-{{ $event->status === 'active' ? 'success' : ($event->status === 'preparation' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="ti ti-user me-2 text-muted"></i>
                        <small class="text-muted">Manager: <strong>{{ $event->manager->name }}</strong></small>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="ti ti-calendar me-2 text-muted"></i>
                        <small class="text-muted">Event Date: <strong>{{ $event->event_date->format('d M Y') }}</strong></small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-calendar-plus me-2 text-muted"></i>
                        <small class="text-muted">Preparation: <strong>{{ $event->start_preparing_date->format('d M Y') }}</strong></small>
                    </div>
                </div>

                @php $stats = $event->taskStats(); @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Progress</small>
                        <small class="text-muted">{{ $stats['done'] }}/{{ $stats['total'] }} done</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['total'] > 0 ? round(($stats['done'] / $stats['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('dashboard.switch', $event->id) }}" class="btn btn-sm btn-label-primary">
                        <i class="ti ti-layout-kanban me-1"></i> Board
                    </a>
                    @if(auth()->user()->isLeadership() || auth()->user()->id === $event->manager_id)
                    <a href="{{ route('event-management.show', $event) }}" class="btn btn-sm btn-label-info">
                        <i class="ti ti-chart-bar me-1"></i> Manage
                    </a>
                    @endif
                    @if(auth()->user()->isLeadership())
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-label-warning">
                        <i class="ti ti-edit me-1"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-8">
                <i class="ti ti-calendar-off ti-3x text-muted mb-3 d-block"></i>
                <h5 class="text-muted">No events yet</h5>
                @if(auth()->user()->isLeadership())
                <a href="{{ route('events.create') }}" class="btn btn-primary mt-2">Create First Event</a>
                @endif
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $events->links() }}
</div>
@endsection
