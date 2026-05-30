@extends('layouts.app')
@section('title', 'Evaluation Form')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-identify me-2 text-primary"></i>Anonymous Evaluation</h4>
        <p class="text-muted mb-0">{{ $evaluation->event->name }} &mdash; Your responses are completely anonymous.</p>
    </div>
</div>

<form action="{{ route('evaluations.submit', $evaluation) }}" method="POST">
    @csrf

    <div class="row g-4" id="evalForms">
        @foreach($members as $member)
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle" width="40" height="40">
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $member->name }}</h6>
                            <small class="text-muted">{{ implode(', ', $member->departments->pluck('name')->toArray()) ?: 'No department' }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Star Rating --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Performance Rating</label>
                        <div class="star-rating d-flex gap-2" data-user="{{ $member->id }}">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="star-label" for="rating_{{ $member->id }}_{{ $i }}" style="cursor:pointer;font-size:2rem;color:#d1d5db;" title="{{ $i }} star{{ $i>1?'s':'' }}">&#9733;</label>
                            <input type="radio" name="entries[{{ $member->id }}][rating]" id="rating_{{ $member->id }}_{{ $i }}" value="{{ $i }}" class="d-none star-input" required>
                            @endfor
                        </div>
                        @error("entries.{$member->id}.rating")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    {{-- Comment --}}
                    <div>
                        <label class="form-label fw-semibold">Comment / Menfess <span class="text-muted">(optional)</span></label>
                        <textarea name="entries[{{ $member->id }}][comment]" class="form-control" rows="3" placeholder="Share your honest, anonymous feedback...">{{ old("entries.{$member->id}.comment") }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-label-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary btn-lg"><i class="ti ti-send me-1"></i> Submit Evaluation</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.star-rating').forEach(function(container) {
    const userId = container.dataset.user;
    const labels = container.querySelectorAll('.star-label');
    const inputs = container.querySelectorAll('.star-input');

    labels.forEach(function(label, index) {
        label.addEventListener('mouseover', function() {
            labels.forEach((l, i) => l.style.color = i <= index ? '#f59e0b' : '#d1d5db');
        });
        label.addEventListener('mouseout', function() {
            const checked = container.querySelector('.star-input:checked');
            const checkedIndex = checked ? parseInt(checked.value) - 1 : -1;
            labels.forEach((l, i) => l.style.color = i <= checkedIndex ? '#f59e0b' : '#d1d5db');
        });
        label.addEventListener('click', function() {
            const val = index + 1;
            inputs[index].checked = true;
            labels.forEach((l, i) => l.style.color = i <= index ? '#f59e0b' : '#d1d5db');
        });
    });
});
</script>
@endpush
