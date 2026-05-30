@extends('layouts.app')
@section('title', 'Thank You')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-6 text-center">
        <div class="card shadow-none border-0">
            <div class="card-body py-5">
                <div class="mb-4">
                    <span class="badge bg-label-success p-4 rounded-circle" style="font-size:3rem;line-height:1">
                        <i class="ti ti-circle-check" style="font-size:3rem"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-2">Thank You!</h3>
                <p class="text-muted mb-4">Your evaluation has been submitted anonymously.<br>Your feedback helps the team grow.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="ti ti-home me-1"></i> Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
