<?php

namespace App\Notifications;

use App\Channels\SmartMailChannel;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskMoved extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task,
        public string $fromColumn,
        public string $toColumn,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', SmartMailChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'task_moved',
            'title'       => 'Task Moved: ' . $this->task->title,
            'body'        => 'The task "' . $this->task->title . '" in event "' . $this->task->event->name . '" was moved from ' . ucfirst($this->fromColumn) . ' to ' . ucfirst($this->toColumn) . '.',
            'url'         => route('dashboard.switch', $this->task->event_id),
            'task_id'     => $this->task->id,
            'task_title'  => $this->task->title,
            'event_id'    => $this->task->event_id,
            'event_name'  => $this->task->event->name,
            'from_column' => $this->fromColumn,
            'to_column'   => $this->toColumn,
        ];
    }

    public function toSmartMail(object $notifiable): array
    {
        $url = route('dashboard.switch', $this->task->event_id);
        return [
            'subject' => '[FOSA] Task Moved: ' . $this->task->title,
            'html'    => view('emails.notifications.task-moved', [
                'task'       => $this->task,
                'user'       => $notifiable,
                'fromColumn' => $this->fromColumn,
                'toColumn'   => $this->toColumn,
                'url'        => $url,
            ])->render(),
        ];
    }
}
