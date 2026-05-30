<?php

namespace App\Notifications;

use App\Channels\SmartMailChannel;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDeadlineSet extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->wantsMailFor('task_deadline_set')) {
            $channels[] = SmartMailChannel::class;
        }
        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        $deadline = Carbon::parse($this->task->deadline_date)->format('d M Y');
        return [
            'type'       => 'task_deadline_set',
            'title'      => 'Deadline Set: ' . $this->task->title,
            'body'       => 'A deadline of ' . $deadline . ' has been set for the task "' . $this->task->title . '" in event "' . $this->task->event->name . '".',
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
            'subject' => '[' . $this->task->event->name . '] Deadline Set: ' . $this->task->title,
            'html'    => view('emails.notifications.task-deadline-set', [
                'task' => $this->task,
                'user' => $notifiable,
                'url'  => $url,
            ])->render(),
        ];
    }
}
