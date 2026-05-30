@extends('layouts.app')
@section('title', 'Edit Event')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/flatpickr/flatpickr.css') }}" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-edit me-2 text-primary"></i>Edit Event</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('events.update', $event) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Event Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $event->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Event Manager <span class="text-danger">*</span></label>
                            <select name="manager_id" class="select2 form-select" required>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('manager_id', $event->manager_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['preparation', 'active', 'completed'] as $s)
                                <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Preparing Date</label>
                            <input type="date" name="start_preparing_date" class="form-control flatpickr-date"
                                   value="{{ old('start_preparing_date', $event->start_preparing_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Event Date</label>
                            <input type="date" name="event_date" class="form-control flatpickr-date"
                                   value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Committee Members</label>
                            <select name="members[]" class="select2 form-select" multiple>
                                @php $currentMembers = $event->members->pluck('id')->toArray(); @endphp
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('members', $currentMembers)) ? 'selected' : '' }}>
                                    {{ $user->name }} — {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-between">
                            <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteEventForm').submit()">
                                <i class="ti ti-trash me-1"></i> Delete
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('events.index') }}" class="btn btn-label-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Delete form outside the edit form to prevent nesting --}}
                <form id="deleteEventForm" action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Delete this event?')">
                    @csrf
                    @method('DELETE')
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
