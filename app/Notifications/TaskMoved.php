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

    /**
     * Compute a human-readable title based on column movement direction.
     *
     * Forward:  → todo      "Task To-do"
     *           → doing     "Task In-progress"
     *           → done      "Task Done"
     * Backward: todo→backlog            "Task Holded (from To-do to Backlog)"
     *           doing/done→todo         "Task back to To-do (from …)"
     *           done→doing              "Task back to In-progress (from Done)"
     * Other: "Task Moved"
     */
    public static function computeTitle(string $fromColumn, string $toColumn): string
    {
        $order   = ['backlog' => 0, 'todo' => 1, 'doing' => 2, 'done' => 3, 'archive' => 4];
        $fromOrd = $order[$fromColumn] ?? -1;
        $toOrd   = $order[$toColumn]   ?? -1;

        if ($toOrd > $fromOrd) {
            return match ($toColumn) {
                'todo'  => 'Task To-do',
                'doing' => 'Task In-progress',
                'done'  => 'Task Done',
                default => 'Task Moved',
            };
        }

        return match (true) {
            $fromColumn === 'todo'  && $toColumn === 'backlog' => 'Task Holded (from To-do to Backlog)',
            $fromColumn === 'doing' && $toColumn === 'todo'    => 'Task back to To-do (from In-progress)',
            $fromColumn === 'done'  && $toColumn === 'todo'    => 'Task back to To-do (from Done)',
            $fromColumn === 'done'  && $toColumn === 'doing'   => 'Task back to In-progress (from Done)',
            default                                            => 'Task Moved',
        };
    }

    public function via(object $notifiable): array
    {
        return ['database', SmartMailChannel::class];
    }

    public function toArray(object $notifiable): array
    {
        $title = self::computeTitle($this->fromColumn, $this->toColumn);
        return [
            'type'        => 'task_moved',
            'title'       => $title . ': ' . $this->task->title,
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
        $title = self::computeTitle($this->fromColumn, $this->toColumn);
        $url   = route('dashboard.switch', $this->task->event_id);
        return [
            'subject' => '[' . $this->task->event->name . '] ' . $title . ': ' . $this->task->title,
            'html'    => view('emails.notifications.task-moved', [
                'task'       => $this->task,
                'user'       => $notifiable,
                'fromColumn' => $this->fromColumn,
                'toColumn'   => $this->toColumn,
                'title'      => $title,
                'url'        => $url,
            ])->render(),
        ];
    }
}
