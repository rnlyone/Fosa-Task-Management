@extends('layouts.app')
@section('title', 'Evaluation Results')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/apex-charts/apex-charts.css') }}" />
@endpush

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-chart-bar me-2 text-primary"></i>Evaluation Results</h4>
        <p class="text-muted mb-0">{{ $evaluation->event->name }} &mdash; <span class="badge bg-{{ ['upcoming'=>'secondary','open'=>'success','closed'=>'info'][$evaluation->status] ?? 'secondary' }}">{{ ucfirst($evaluation->status) }}</span></p>
    </div>
    <div class="col-auto">
        <a href="{{ route('evaluations.index') }}" class="btn btn-label-secondary"><i class="ti ti-arrow-left me-1"></i> Back</a>
    </div>
</div>

@php
    $totalMembers = $evaluation->event->members->count();
    $submitted = $evaluation->submissions->count();
    $pct = $totalMembers > 0 ? round($submitted / $totalMembers * 100) : 0;
    $avgRatings = $evaluation->averageRatings();
@endphp

{{-- Submission Progress --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <h2 class="fw-bold text-primary">{{ $submitted }}<span class="text-muted fs-6">/{{ $totalMembers }}</span></h2>
                <p class="mb-2 text-muted">Submissions</p>
                <div class="progress" style="height:8px">
                    <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                </div>
                <small class="text-muted mt-1 d-block">{{ $pct }}% responded</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                @php $topMember = $avgRatings->sortByDesc('avg_rating')->first(); @endphp
                @if($topMember)
                <div class="avatar mb-2">
                    <img src="{{ $topMember['user']->avatar_url }}" class="rounded-circle" width="50" height="50">
                </div>
                <h6 class="fw-semibold mb-0">{{ $topMember['user']->name }}</h6>
                <div class="text-warning">@for($i=0;$i<round($topMember['avg_rating']);$i++)&#9733;@endfor</div>
                <small class="text-muted">Top Performer</small>
                @else
                <p class="text-muted my-4">No data yet</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                @php $avg = $avgRatings->avg('avg_rating'); @endphp
                <h2 class="fw-bold text-success">{{ $avg ? number_format($avg, 1) : '—' }}</h2>
                <div class="text-warning fs-5">@for($i=0;$i<round($avg??0);$i++)&#9733;@endfor</div>
                <p class="mb-0 text-muted">Team Average Rating</p>
            </div>
        </div>
    </div>
</div>

{{-- Bar Chart --}}
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Average Ratings per Member</h5></div>
    <div class="card-body">
        <div id="ratingsChart"></div>
    </div>
</div>

{{-- Per-member breakdown --}}
<div class="row g-4">
    @foreach($avgRatings->sortByDesc('avg_rating') as $item)
    @php $u = $item['user']; @endphp
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar me-3">
                        <img src="{{ $u->avatar_url }}" class="rounded-circle" width="40" height="40">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $u->name }}</h6>
                        <div class="text-warning">@for($i=0;$i<round($item['avg_rating']);$i++)&#9733;@endfor <small class="text-muted">{{ number_format($item['avg_rating'], 2) }}/5</small></div>
                    </div>
                    <span class="badge bg-label-secondary">{{ $item['count'] }} review{{ $item['count']!=1?'s':'' }}</span>
                </div>
                @if($item['comments']->isNotEmpty())
                <hr class="my-2">
                <p class="small text-muted mb-2"><i class="ti ti-message-circle me-1"></i>Comments (anonymous)</p>
                <ul class="list-unstyled mb-0">
                    @foreach($item['comments']->take(3) as $comment)
                    <li class="small mb-2 p-2 bg-light rounded">"{{ $comment }}"</li>
                    @endforeach
                    @if($item['comments']->count() > 3)
                    <li class="small text-muted">+{{ $item['comments']->count() - 3 }} more comments</li>
                    @endif
                </ul>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="{{ asset('vuexy/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
const members = @json($avgRatings->sortByDesc('avg_rating')->pluck('user')->pluck('name'));
const ratings = @json($avgRatings->sortByDesc('avg_rating')->pluck('avg_rating')->map(fn($v)=>round($v,2)));

new ApexCharts(document.getElementById('ratingsChart'), {
    series: [{ name: 'Avg Rating', data: ratings }],
    chart: { type: 'bar', height: 300, toolbar: { show: false } },
    plotOptions: { bar: { borderRadius: 6, horizontal: false, distributed: true } },
    dataLabels: { enabled: true, formatter: v => v.toFixed(1) },
    xaxis: { categories: members },
    yaxis: { min: 0, max: 5 },
    colors: ['#696cff','#03c3ec','#71dd37','#ff3e1d','#ffab00','#20c997','#8592a3','#607d8b'],
    legend: { show: false },
    grid: { borderColor: '#dbdade' }
}).render();
</script>
@endpush
