@php
$priorityColors = ['low' => 'success', 'medium' => 'info', 'high' => 'warning', 'critical' => 'danger'];
$color = $priorityColors[$task->priority] ?? 'secondary';
@endphp
<div class="card mb-2 task-card shadow-none border"
     data-id="{{ $task->id }}"
     data-assignee-ids="{{ $task->assignees->pluck('id')->join(',') }}"
     @if($task->card_color) style="border-left: 3px solid {{ $task->card_color }} !important;" @endif>
    <div class="card-body p-3">
        <div class="d-flex align-items-start justify-content-between mb-2">
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-label-{{ $color }} badge-sm">{{ ucfirst($task->priority) }}</span>
                @if($task->isOverdue())
                <span class="badge bg-danger badge-sm">Overdue</span>
                @endif
            </div>
        </div>

        <h6 class="card-title mb-2 fw-normal">{{ $task->title }}</h6>
        <div class="d-flex align-items-center justify-content-between mt-2">
            <!-- Assignees avatars -->
            <div class="avatar-group d-flex">
                @foreach($task->assignees->take(3) as $assignee)
                <div class="avatar avatar-xs" title="{{ $assignee->name }}">
                    <img src="{{ $assignee->avatar_url }}" alt="{{ $assignee->name }}" class="rounded-circle">
                </div>
                @endforeach
                @if($task->assignees->count() > 3)
                <div class="avatar avatar-xs">
                    <span class="avatar-initial rounded-circle bg-label-secondary" style="font-size:.6rem;">
                        +{{ $task->assignees->count() - 3 }}
                    </span>
                </div>
                @endif
            </div>

            @if($task->deadline_date)
            <small class="text-{{ $task->isOverdue() ? 'danger' : 'muted' }}">
                <i class="ti ti-calendar ti-xs me-1"></i>
                {{ $task->deadline_date->format('d M') }}
            </small>
            @endif
        </div>
    </div>
</div>
