@extends('layouts.app')
@section('title', 'Email Accounts')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Email Accounts</h4>
            <small class="text-muted">SMTP accounts used to send notifications. Tried in priority order — falls back to the next if one fails.</small>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMailerModal">
            <i class="ti ti-plus me-1"></i> Add Account
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-none border">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Priority</th>
                        <th>Name</th>
                        <th>Host / Port</th>
                        <th>Username</th>
                        <th>From</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td><span class="badge bg-label-secondary">{{ $account->priority }}</span></td>
                        <td><strong>{{ $account->name }}</strong></td>
                        <td><code>{{ $account->host }}:{{ $account->port }}</code> <span class="badge bg-label-info ms-1">{{ strtoupper($account->encryption) }}</span></td>
                        <td>{{ $account->username }}</td>
                        <td>{{ $account->from_name }} &lt;{{ $account->from_address }}&gt;</td>
                        <td>
                            @if($account->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <form action="{{ route('mailer-accounts.test', $account) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-info" title="Send test email to yourself">
                                    <i class="ti ti-send"></i>
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editMailerModal{{ $account->id }}"
                                title="Edit">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <form action="{{ route('mailer-accounts.destroy', $account) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this mailer account?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No mailer accounts configured yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===================== ADD MODAL ===================== --}}
<div class="modal fade" id="addMailerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('mailer-accounts.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Mailer Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Account Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Gmail Primary" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Priority <small class="text-muted">(lower = tried first)</small></label>
                            <input type="number" name="priority" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                            <input type="text" name="host" class="form-control" placeholder="smtp.gmail.com" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Port <span class="text-danger">*</span></label>
                            <input type="number" name="port" class="form-control" value="587" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Encryption</label>
                            <select name="encryption" class="form-select">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username / Email <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="sender@gmail.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password / App Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Name <span class="text-danger">*</span></label>
                            <input type="text" name="from_name" class="form-control" placeholder="FOSA Task Management" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Email <span class="text-danger">*</span></label>
                            <input type="email" name="from_address" class="form-control" placeholder="noreply@yourorg.com" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Account</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===================== EDIT MODALS ===================== --}}
@foreach($accounts as $account)
<div class="modal fade" id="editMailerModal{{ $account->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('mailer-accounts.update', $account) }}">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit: {{ $account->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Account Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $account->name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Priority</label>
                            <input type="number" name="priority" class="form-control" value="{{ $account->priority }}" min="0">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $account->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" name="host" class="form-control" value="{{ $account->host }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Port</label>
                            <input type="number" name="port" class="form-control" value="{{ $account->port }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Encryption</label>
                            <select name="encryption" class="form-select">
                                <option value="tls"  {{ $account->encryption === 'tls'  ? 'selected' : '' }}>TLS</option>
                                <option value="ssl"  {{ $account->encryption === 'ssl'  ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ $account->encryption === 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username / Email</label>
                            <input type="text" name="username" class="form-control" value="{{ $account->username }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Name</label>
                            <input type="text" name="from_name" class="form-control" value="{{ $account->from_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Email</label>
                            <input type="email" name="from_address" class="form-control" value="{{ $account->from_address }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
