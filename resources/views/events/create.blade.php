@extends('layouts.app')
@section('title', 'Create Event')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.css') }}" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-calendar-plus me-2 text-primary"></i>Create Event</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Event Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Annual General Meeting 2025" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Event Manager <span class="text-danger">*</span></label>
                            <select name="manager_id" class="select2 form-select @error('manager_id') is-invalid @enderror" required>
                                <option value="">Select manager...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                                </option>
                                @endforeach
                            </select>
                            @error('manager_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="preparation" {{ old('status') === 'preparation' ? 'selected' : '' }}>Preparation</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Preparing Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_preparing_date" class="form-control flatpickr-date @error('start_preparing_date') is-invalid @enderror"
                                   value="{{ old('start_preparing_date') }}" required>
                            @error('start_preparing_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Event Date <span class="text-danger">*</span></label>
                            <input type="date" name="event_date" class="form-control flatpickr-date @error('event_date') is-invalid @enderror"
                                   value="{{ old('event_date') }}" required>
                            @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Committee Members</label>
                            <select name="members[]" class="select2 form-select @error('members') is-invalid @enderror" multiple>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('members', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} — {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">Select committee members. The manager is added automatically.</div>
                            @error('members')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('events.index') }}" class="btn btn-label-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Create Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vuexy/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
$('.select2').each(function(){ $(this).wrap('<div class="position-relative"></div>'); $(this).select2({ dropdownParent: $(this).parent() }); });
$('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });
</script>
@endpush
