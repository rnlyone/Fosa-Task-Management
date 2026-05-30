@extends('emails.layout')
@section('content')
<div class="body">
  <h2>🔀 Task Moved</h2>
  <p>Hi {{ $user->name }},</p>
  <p>A task you are assigned to has been moved to a new column.</p>
  <div class="meta">
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Event:</strong> {{ $task->event->name }}</p>
    <p><strong>Moved from:</strong> {{ ucfirst($fromColumn) }} → <strong>{{ ucfirst($toColumn) }}</strong></p>
  </div>
  <a href="{{ $url }}" class="btn">View Board</a>
</div>
@endsection
