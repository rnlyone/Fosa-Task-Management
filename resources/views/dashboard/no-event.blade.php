@extends('layouts.app')
@section('title', 'No Active Event')

@section('content')
<div class="container-p-y text-center py-12">
    <div class="misc-wrapper">
        <h2 class="mb-2 mx-2">No Active Event 🎉</h2>
        <p class="mb-4 mx-2">There is no active event right now. Create one to get started!</p>
        @if(auth()->user()->isLeadership())
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Create Event
        </a>
        @else
        <p class="text-muted">Please wait for the president to create a new event.</p>
        @endif
    </div>
</div>
@endsection
