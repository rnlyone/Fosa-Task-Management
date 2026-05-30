@extends('layouts.app')
@section('title', 'Departments')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-building me-2 text-primary"></i>Departments</h4>
    </div>
    @if(auth()->user()->isLeadership())
    <div class="col-auto">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus me-1"></i> Add Department
        </button>
    </div>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible mb-4"><i class="ti ti-check me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    @forelse($departments as $dept)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1">{{ $dept->name }}</h5>
                        @if($dept->description)
                        <p class="text-muted mb-0 small">{{ $dept->description }}</p>
                        @endif
                    </div>
                    @if(auth()->user()->isLeadership())
                    <div class="dropdown">
                        <button class="btn btn-icon btn-sm btn-label-secondary" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" onclick="openEdit({{ $dept->id }}, '{{ addslashes($dept->name) }}', '{{ addslashes($dept->description ?? '') }}')"><i class="ti ti-edit me-2"></i>Edit</button></li>
                            <li>
                                <form action="{{ route('departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Delete this department?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    <div class="avatar-group me-2">
                        @foreach($dept->users->take(4) as $u)
                        <div class="avatar avatar-sm">
                            <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="rounded-circle" title="{{ $u->name }}" width="28" height="28">
                        </div>
                        @endforeach
                    </div>
                    <span class="text-muted small">{{ $dept->users_count }} member{{ $dept->users_count != 1 ? 's' : '' }}</span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="ti ti-building-off ti-xl mb-3 d-block"></i>
        <p>No departments yet.</p>
    </div>
    @endforelse
</div>

@if(auth()->user()->isLeadership())
{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Department</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-muted">(optional)</span></label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Edit Department</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="editName" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="editDesc" name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function openEdit(id, name, desc) {
    document.getElementById('editForm').action = '/departments/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
