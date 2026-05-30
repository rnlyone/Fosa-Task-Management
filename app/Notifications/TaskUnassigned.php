<?php

namespace App\Notifications;

use App\Channels\SmartMailChannel;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskUnassigned extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->wantsMailFor('task_unassigned')) {
            $channels[] = SmartMailChannel::class;
        }
        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'task_unassigned',
            'title'      => 'Task Unassigned: ' . $this->task->title,
            'body'       => 'You have been removed from the task "' . $this->task->title . '" in event "' . $this->task->event->name . '".',
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
            'subject' => '[FOSA] Task Unassigned: ' . $this->task->title,
            'html'    => view('emails.notifications.task-unassigned', [
                'task' => $this->task,
                'user' => $notifiable,
                'url'  => $url,
            ])->render(),
        ];
    }
}
