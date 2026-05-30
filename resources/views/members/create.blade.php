@extends('layouts.app')
@section('title', 'Add Member')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-user-plus me-2 text-primary"></i>Add Member</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item active">Add</li>
        </ol></nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member</option>
                                <option value="vice_president" {{ old('role') === 'vice_president' ? 'selected' : '' }}>Vice President</option>
                                <option value="president" {{ old('role') === 'president' ? 'selected' : '' }}>President</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['free','available','busy','very_busy','not_available','cant_be_bothered'] as $s)
                                <option value="{{ $s }}" {{ old('status', 'available') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Departments</label>
                            <select name="departments[]" class="select2 form-select" multiple>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ in_array($dept->id, old('departments', [])) ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('members.index') }}" class="btn btn-label-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Add Member</button>
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
<script>$('.select2').each(function(){ $(this).wrap('<div class="position-relative"></div>'); $(this).select2({ dropdownParent: $(this).parent() }); });</script>
@endpush
