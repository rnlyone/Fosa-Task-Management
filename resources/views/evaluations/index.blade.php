@extends('layouts.app')
@section('title', 'Evaluations')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-star me-2 text-primary"></i>Blind Evaluations</h4>
    </div>
    <div class="col-auto">
        <a href="{{ route('evaluations.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> New Evaluation</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible mb-4"><i class="ti ti-check me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Event</th>
                    <th>Status</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Submissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                @php
                    $statusColor = ['upcoming'=>'secondary','open'=>'success','closed'=>'info'][$eval->status] ?? 'secondary';
                    $memberCount = $eval->event->members->count();
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $eval->event->name }}</div>
                        <small class="text-muted">{{ $eval->event->event_date?->format('d M Y') ?? 'TBD' }}</small>
                    </td>
                    <td><span class="badge bg-{{ $statusColor }}">{{ ucfirst($eval->status) }}</span></td>
                    <td>{{ $eval->opens_at ? $eval->opens_at->format('d M Y') : '—' }}</td>
                    <td>{{ $eval->closes_at ? $eval->closes_at->format('d M Y') : '—' }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;min-width:80px">
                                @php $pct = $memberCount > 0 ? round($eval->submissions->count() / $memberCount * 100) : 0; @endphp
                                <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                            </div>
                            <small class="text-muted text-nowrap">{{ $eval->submissions->count() }}/{{ $memberCount }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('evaluations.show', $eval) }}" class="btn btn-sm btn-label-info" title="View Results"><i class="ti ti-chart-bar"></i></a>
                            @if($eval->status === 'upcoming')
                            <form action="{{ route('evaluations.open', $eval) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-label-success" title="Open Evaluation Now"><i class="ti ti-lock-open"></i></button>
                            </form>
                            @elseif($eval->status === 'open')
                            <form action="{{ route('evaluations.close', $eval) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-label-warning" title="Close Evaluation Now"><i class="ti ti-lock"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="ti ti-star-off ti-xl mb-3 d-block"></i>No evaluations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
