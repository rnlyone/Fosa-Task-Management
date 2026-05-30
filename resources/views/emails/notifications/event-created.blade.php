@extends('emails.layout')
@section('content')
<div class="body">
  <h2>🎉 You've Been Added to an Event</h2>
  <p>Hi {{ $user->name }},</p>
  <p>You have been added as a member of the following event.</p>
  <div class="meta">
    <p><strong>Event:</strong> {{ $event->name }}</p>
    @if($event->description)
    <p><strong>Description:</strong> {{ $event->description }}</p>
    @endif
  </div>
  <a href="{{ $url }}" class="btn">View Event</a>
</div>
@endsection
