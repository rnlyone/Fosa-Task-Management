@extends('emails.layout')
@section('content')
<div class="body">
  <h2>📋 Task Assigned to You</h2>
  <p>Hi {{ $user->name }},</p>
  <p>You have been assigned to a new task in <strong>{{ $task->event->name }}</strong>.</p>
  <div class="meta">
    <p><strong>Task:</strong> {{ $task->title }}</p>
    <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
    @if($task->deadline_date)
    <p><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($task->deadline_date)->format('d M Y') }}</p>
    @endif
    @if($task->description)
    <p><strong>Description:</strong></p>
    <div>{!! $task->description !!}</div>
    @endif
  </div>
  <a href="{{ $url }}" class="btn">Open Task</a>
  <a href="{{ $boardUrl }}" class="btn-outline">View Board</a>
</div>
@endsection
