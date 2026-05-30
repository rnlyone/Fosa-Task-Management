@extends('layouts.app')
@section('title', 'My Profile')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
@endpush

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

{{-- Crop Modal --}}
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-crop me-2"></i>Crop Photo</h5>
                <button type="button" class="btn-close" id="cropCancel"></button>
            </div>
            <div class="modal-body" style="background:#1e1e2d;">
                <div style="max-height:420px;overflow:hidden;">
                    <img id="cropImage" style="max-width:100%;display:block;">
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-2 me-auto text-muted" style="font-size:.8rem;">
                    <span><i class="ti ti-arrows-move ti-xs"></i> Drag to pan</span>
                    <span><i class="ti ti-zoom-in ti-xs"></i> Scroll to zoom</span>
                </div>
                <button type="button" class="btn btn-label-secondary" id="cropCancel2">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropApply">
                    <i class="ti ti-check me-1"></i> Apply & Save
                </button>
            </div>
        </div>
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
                           style="width:30px;height:30px;cursor:pointer;" title="Change photo">
                        <i class="ti ti-camera ti-xs"></i>
                    </label>
                </div>

                <h5 class="mb-0 fw-semibold">{{ $user->name }}</h5>
                <span class="badge bg-label-primary">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>

                <hr class="my-3">

                <input type="file" id="avatarInput" accept="image/*" class="d-none">
                <p class="text-muted mb-0" style="font-size:.75rem;">JPG, PNG or WebP &middot; max 2 MB</p>
                <p class="text-muted mt-1 mb-0" style="font-size:.75rem;">
                    <i class="ti ti-crop ti-xs me-1"></i>You can crop the photo before saving
                </p>

                {{-- Hidden form for AJAX --}}
                <form id="avatarForm" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                </form>

                <div id="avatarSpinner" class="mt-2 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="text-muted ms-1" style="font-size:.8rem;">Uploading…</span>
                </div>
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
                            @if($user->pending_email)
                            <div class="alert alert-warning d-flex align-items-start gap-2 mt-2 mb-0 py-2">
                                <i class="ti ti-mail-forward fs-5 flex-shrink-0 mt-1"></i>
                                <div class="flex-grow-1" style="font-size:.85rem;">
                                    Pending verification: <strong>{{ $user->pending_email }}</strong><br>
                                    <span class="text-muted">Your email will change once you click the link sent to that address.</span>
                                </div>
                                <form method="POST" action="{{ route('profile.cancel-email-change') }}" class="flex-shrink-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-label-danger py-1 px-2" title="Cancel pending change">
                                        <i class="ti ti-x ti-xs me-1"></i>Cancel
                                    </button>
                                </form>
                            </div>
                            @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
(function () {
    let cropper = null;
    const cropModalEl = document.getElementById('cropModal');
    const cropModal   = new bootstrap.Modal(cropModalEl);
    const cropImage   = document.getElementById('cropImage');
    const avatarInput = document.getElementById('avatarInput');

    // Open file picker when camera label is clicked
    avatarInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            cropImage.src = e.target.result;
            cropModal.show();
        };
        reader.readAsDataURL(file);
        // reset so same file can be re-selected after cancel
        this.value = '';
    });

    // Init Cropper.js once modal is fully shown
    cropModalEl.addEventListener('shown.bs.modal', function () {
        if (cropper) { cropper.destroy(); }
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    });

    // Destroy Cropper when modal closes
    cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropper) { cropper.destroy(); cropper = null; }
    });

    // Cancel buttons
    document.getElementById('cropCancel').addEventListener('click',  () => cropModal.hide());
    document.getElementById('cropCancel2').addEventListener('click', () => cropModal.hide());

    // Apply & Save: get cropped canvas → blob → upload
    document.getElementById('cropApply').addEventListener('click', function () {
        if (!cropper) return;

        const applyBtn = this;
        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving…';

        cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob(function (blob) {
            const fd = new FormData(document.getElementById('avatarForm'));
            fd.append('avatar', blob, 'avatar.jpg');

            fetch('{{ route('profile.avatar') }}', { method: 'POST', body: fd })
                .then(r => r.redirected ? r.url : Promise.reject('Upload failed'))
                .then(url => {
                    // Update all avatar images on the page with the cropped preview
                    const previewUrl = URL.createObjectURL(blob);
                    document.querySelectorAll('#avatarPreview, .navbar-user-avatar').forEach(img => img.src = previewUrl);
                    cropModal.hide();
                    // Soft-reload to sync navbar avatar + session flash
                    window.location.reload();
                })
                .catch(err => {
                    applyBtn.disabled = false;
                    applyBtn.innerHTML = '<i class="ti ti-check me-1"></i> Apply & Save';
                    alert('Upload failed. Please try again.');
                });
        }, 'image/jpeg', 0.9);
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
})();
</script>
@endpush
@endsection
