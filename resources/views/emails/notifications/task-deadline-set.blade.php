@extends('emails.layout')
@section('content')
<div class="body">
  <h2>📅 Deadline Set</h2>
  <p>Hi {{ $user->name }},</p>
  <p>A deadline has been set for a task you are assigned to.</p>
  <div class="meta">
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Event:</strong> {{ $task->event->name }}</p>
    <p><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($task->deadline_date)->format('d M Y') }}</p>
  </div>
  <a href="{{ $url }}" class="btn">View Board</a>
</div>
@endsection
