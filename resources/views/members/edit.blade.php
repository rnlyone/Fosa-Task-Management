@extends('layouts.app')
@section('title', 'Edit Member')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-user-edit me-2 text-primary"></i>Edit Member</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol></nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('members.update', $member) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $member->username) }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-muted">(leave blank to keep)</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                @foreach(['member', 'vice_president', 'president', 'administrator'] as $r)
                                <option value="{{ $r }}" {{ old('role', $member->role) === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $r)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['free','available','busy','very_busy','not_available','cant_be_bothered'] as $s)
                                <option value="{{ $s }}" {{ old('status', $member->status) === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Departments</label>
                            <select name="departments[]" class="select2 form-select" multiple>
                                @php $memberDepts = $member->departments->pluck('id')->toArray(); @endphp
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ in_array($dept->id, old('departments', $memberDepts)) ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('members.index') }}" class="btn btn-label-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Save</button>
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
