@extends('layouts.app')
@section('title', 'Create Evaluation')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-star me-2 text-primary"></i>Create Evaluation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('evaluations.index') }}">Evaluations</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol></nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Evaluation Setup</h5></div>
            <div class="card-body">
                <form action="{{ route('evaluations.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Event</label>
                            <select name="event_id" class="select2 form-select @error('event_id') is-invalid @enderror" required>
                                <option value="">— Select Event —</option>
                                @foreach($events as $evt)
                                <option value="{{ $evt->id }}" {{ old('event_id') == $evt->id ? 'selected' : '' }}>
                                    {{ $evt->name }} ({{ $evt->status }})
                                </option>
                                @endforeach
                            </select>
                            @error('event_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opens At <span class="text-muted">(optional)</span></label>
                            <input type="text" name="opens_at" class="form-control flatpickr" placeholder="Select date" value="{{ old('opens_at') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Closes At <span class="text-muted">(optional)</span></label>
                            <input type="text" name="closes_at" class="form-control flatpickr" placeholder="Select date" value="{{ old('closes_at') }}">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('evaluations.index') }}" class="btn btn-label-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('vuexy/vendor/libs/select2/select2.js') }}"></script>
<script>
    flatpickr('.flatpickr', { dateFormat: 'Y-m-d', allowInput: true });
    $('.select2').each(function(){ $(this).wrap('<div class="position-relative"></div>'); $(this).select2({ dropdownParent: $(this).parent() }); });
</script>
@endpush
