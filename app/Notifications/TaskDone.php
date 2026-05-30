<?php

namespace App\Notifications;

use App\Channels\SmartMailChannel;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDone extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->wantsMailFor('task_done')) {
            $channels[] = SmartMailChannel::class;
        }
        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'task_done',
            'title'      => 'Task Completed: ' . $this->task->title,
            'body'       => 'The task "' . $this->task->title . '" in event "' . $this->task->event->name . '" has been moved to Done.',
            'url'        => route('dashboard.switch', $this->task->event_id),
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
            'event_id'   => $this->task->event_id,
            'event_name' => $this->task->event->name,
        ];
    }

    public function toSmartMail(object $notifiable): array
    {
        $url = route('dashboard.switch', $this->task->event_id);
        return [
            'subject' => '[FOSA] Task Completed: ' . $this->task->title,
            'html'    => view('emails.notifications.task-done', [
                'task' => $this->task,
                'user' => $notifiable,
                'url'  => $url,
            ])->render(),
        ];
    }
}
