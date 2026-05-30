@extends('layouts.app')
@section('title', 'Members')

@push('styles')
<link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold mb-0"><i class="ti ti-users me-2 text-primary"></i>Members</h4>
    </div>
    @if(auth()->user()->isLeadership())
    <div class="col-auto">
        <a href="{{ route('members.create') }}" class="btn btn-primary">
            <i class="ti ti-user-plus me-1"></i> Add Member
        </a>
    </div>
    @endif
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, username, email...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="president" {{ request('role') === 'president' ? 'selected' : '' }}>President</option>
                    <option value="vice_president" {{ request('role') === 'vice_president' ? 'selected' : '' }}>Vice President</option>
                    <option value="administrator" {{ request('role') === 'administrator' ? 'selected' : '' }}>Administrator</option>
                    <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Member</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['free','available','busy','very_busy','not_available','cant_be_bothered'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Departments</th>
                    @if(auth()->user()->isLeadership())<th>Actions</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                @php
                $roleColors = ['president' => 'primary', 'vice_president' => 'info', 'administrator' => 'danger', 'member' => 'secondary'];
                $statusColors = ['free' => 'success', 'available' => 'info', 'busy' => 'warning', 'very_busy' => 'danger', 'not_available' => 'secondary', 'cant_be_bothered' => 'dark'];
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="rounded-circle">
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $member->name }}</div>
                                <small class="text-muted">{{ $member->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $member->username }}</code></td>
                    <td><span class="badge bg-label-{{ $roleColors[$member->role] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</span></td>
                    <td><span class="badge bg-label-{{ $statusColors[$member->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $member->status)) }}</span></td>
                    <td>
                        @foreach($member->departments->take(2) as $dept)
                        <span class="badge bg-label-info me-1">{{ $dept->name }}</span>
                        @endforeach
                        @if($member->departments->count() > 2)
                        <span class="badge bg-label-secondary">+{{ $member->departments->count() - 2 }}</span>
                        @endif
                    </td>
                    @if(auth()->user()->isLeadership())
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-icon btn-sm btn-label-warning" title="Edit">
                                <i class="ti ti-edit ti-sm"></i>
                            </a>
                            <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove this member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-label-danger" title="Delete">
                                    <i class="ti ti-trash ti-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isLeadership() ? 6 : 5 }}" class="text-center py-6 text-muted">No members found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $members->links() }}
    </div>
</div>
@endsection
