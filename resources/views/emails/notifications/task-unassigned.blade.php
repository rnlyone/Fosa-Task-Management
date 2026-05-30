@extends('emails.layout')
@section('content')
<div class="body">
  <h2>🔕 Task Unassigned</h2>
  <p>Hi {{ $user->name }},</p>
  <p>You have been removed from the following task in <strong>{{ $task->event->name }}</strong>.</p>
  <div class="meta">
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Event:</strong> {{ $task->event->name }}</p>
  </div>
  <a href="{{ $url }}" class="btn">View Board</a>
</div>
@endsection
