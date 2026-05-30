@extends('emails.layout')
@section('content')
<div class="body">
  <h2>✅ Task Completed</h2>
  <p>Hi {{ $user->name }},</p>
  <p>A task in <strong>{{ $task->event->name }}</strong> has been moved to <strong>Done</strong>.</p>
  <div class="meta">
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Event:</strong> {{ $task->event->name }}</p>
    @if($task->deadline_date)
    <p><strong>Deadline was:</strong> {{ \Carbon\Carbon::parse($task->deadline_date)->format('d M Y') }}</p>
    @endif
  </div>
  <a href="{{ $url }}" class="btn">View Board</a>
</div>
@endsection
