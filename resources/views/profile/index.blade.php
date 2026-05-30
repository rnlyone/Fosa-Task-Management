@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-user-circle me-2 text-primary"></i>My Profile</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol></nav>
    </div>
</div>

<div class="row g-4">

    {{-- Left: Avatar card --}}
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="mb-3 position-relative d-inline-block">
                    <img id="avatarPreview"
                         src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="rounded-circle border border-3"
                         style="width:120px;height:120px;object-fit:cover;">
                    <label for="avatarInput"
                           class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                           style="width:30px;height:30px;cursor:pointer;" title="Change avatar">
                        <i class="ti ti-camera ti-xs"></i>
                    </label>
                </div>

                <h5 class="mb-0 fw-semibold">{{ $user->name }}</h5>
                <p class="text-muted mb-1" style="font-size:.85rem;">@{{ $user->username }}</p>
                <span class="badge bg-label-primary">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>

                <hr class="my-3">

                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                    <p class="text-muted mb-2" style="font-size:.75rem;">JPG, PNG or WebP &middot; max 2 MB</p>
                    <button type="submit" id="avatarSaveBtn" class="btn btn-primary btn-sm d-none">
                        <i class="ti ti-upload me-1"></i> Save Photo
                    </button>
                </form>
            </div>
        </div>

        {{-- Status info card --}}
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="ti ti-circle-dot me-2 text-primary"></i>Current Status</h6>
                @php
                    $statusMap = [
                        'free'             => ['label' => 'Free',              'color' => 'success'],
                        'available'        => ['label' => 'Available',         'color' => 'info'],
                        'busy'             => ['label' => 'Busy',              'color' => 'warning'],
                        'very_busy'        => ['label' => 'Very Busy',         'color' => 'danger'],
                        'not_available'    => ['label' => 'Not Available',     'color' => 'secondary'],
                        'cant_be_bothered' => ['label' => "Can't Be Bothered", 'color' => 'dark'],
                    ];
                    $meta = $statusMap[$user->status] ?? ['label' => 'Unknown', 'color' => 'secondary'];
                @endphp
                <span class="badge bg-{{ $meta['color'] }} fs-6 px-3 py-2">{{ $meta['label'] }}</span>
                <p class="text-muted mt-2 mb-0" style="font-size:.75rem;">Change your status from the top navbar.</p>
            </div>
        </div>
    </div>

    {{-- Right: Info + Password --}}
    <div class="col-xl-8 col-lg-7">

        {{-- Account Information --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-id me-2 text-primary"></i>Account Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" name="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username', $user->username) }}" required>
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-lock me-2 text-primary"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Enter current password">
                                <span class="input-group-text cursor-pointer toggle-pw"><i class="ti ti-eye-off"></i></span>
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Min. 8 characters">
                                <span class="input-group-text cursor-pointer toggle-pw"><i class="ti ti-eye-off"></i></span>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Repeat new password">
                                <span class="input-group-text cursor-pointer toggle-pw"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <ul class="text-muted ps-3 mb-0" style="font-size:.8rem;">
                                <li>Minimum 8 characters</li>
                                <li>Use a combination of letters, numbers, and symbols for stronger security</li>
                            </ul>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-lock-open me-1"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Avatar file preview + auto-show save button
document.getElementById('avatarInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('avatarPreview').src = e.target.result;
        document.getElementById('avatarSaveBtn').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

// Toggle password visibility
document.querySelectorAll('.toggle-pw').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const input = this.closest('.input-group').querySelector('input');
        const icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('ti-eye-off', 'ti-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('ti-eye', 'ti-eye-off');
        }
    });
});
</script>
@endpush
@endsection
