<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskDeadlineSet;
use App\Notifications\TaskDone;
use App\Notifications\TaskMoved;
use App\Notifications\TaskUnassigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'event_id'      => ['required', 'exists:events,id'],
            'column'        => ['required', 'in:backlog,todo,doing,done,archive'],
            'description'   => ['nullable', 'string'],
            'deadline_date' => ['nullable', 'date'],
            'priority'      => ['required', 'in:low,medium,high,critical'],
            'card_color'    => ['nullable', 'string', 'max:20'],
            'assignees'     => ['nullable', 'array'],
            'assignees.*'   => ['exists:users,id'],
        ]);

        $maxPosition = Task::where('event_id', $data['event_id'])
            ->where('column', $data['column'])
            ->max('position') ?? -1;

        $task = Task::create([
            'title'         => $data['title'],
            'event_id'      => $data['event_id'],
            'created_by'    => Auth::id(),
            'column'        => $data['column'],
            'description'   => $data['description'] ?? null,
            'deadline_date' => $data['deadline_date'] ?? null,
            'priority'      => $data['priority'],
            'card_color'    => $data['card_color'] ?? null,
            'position'      => $maxPosition + 1,
        ]);

        if (!empty($data['assignees'])) {
            $task->assignees()->sync($data['assignees']);
            $task->load('event');
            $assignees = User::whereIn('id', $data['assignees'])->where('id', '!=', Auth::id())->get();
            foreach ($assignees as $assignee) {
                $assignee->notify(new TaskAssigned($task));
            }
        }

        return response()->json(['success' => true, 'task' => $task->load('assignees')]);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'         => ['sometimes', 'required', 'string', 'max:255'],
            'column'        => ['sometimes', 'required', 'in:backlog,todo,doing,done,archive'],
            'description'   => ['nullable', 'string'],
            'deadline_date' => ['nullable', 'date'],
            'priority'      => ['sometimes', 'required', 'in:low,medium,high,critical'],
            'card_color'    => ['nullable', 'string', 'max:20'],
            'assignees'     => ['nullable', 'array'],
            'assignees.*'   => ['exists:users,id'],
            'position'      => ['nullable', 'integer'],
        ]);

        $oldColumn   = $task->column;
        $oldDeadline = $task->deadline_date;

        $task->update(array_filter($data, fn($v, $k) => $k !== 'assignees', ARRAY_FILTER_USE_BOTH));

        // Notify assignees when a deadline is first set (was null, now has a value)
        if (array_key_exists('deadline_date', $data) && !$oldDeadline && !empty($data['deadline_date'])) {
            $task->loadMissing('assignees', 'event');
            $notifyIds = $task->assignees->pluck('id')->diff([Auth::id()]);
            foreach (User::whereIn('id', $notifyIds)->get() as $user) {
                $user->notify(new TaskDeadlineSet($task));
            }
        }

        if (array_key_exists('assignees', $data)) {
            $newIds = collect($data['assignees'] ?? []);
            $oldIds = $task->assignees()->pluck('users.id');
            $task->assignees()->sync($newIds->all());

            $task->load('event');

            // Notify newly added assignees (skip the updater)
            $addedIds = $newIds->diff($oldIds)->diff([Auth::id()]);
            if ($addedIds->isNotEmpty()) {
                foreach (User::whereIn('id', $addedIds)->get() as $user) {
                    $user->notify(new TaskAssigned($task));
                }
            }

            // Notify removed assignees
            $removedIds = $oldIds->diff($newIds)->diff([Auth::id()]);
            if ($removedIds->isNotEmpty()) {
                foreach (User::whereIn('id', $removedIds)->get() as $user) {
                    $user->notify(new TaskUnassigned($task));
                }
            }
        }

        // Handle column change notifications
        if (isset($data['column']) && $data['column'] !== $oldColumn) {
            $task->loadMissing('assignees', 'event');
            $this->handleColumnChangeNotifications($task, $oldColumn, $data['column']);
        }

        return response()->json(['success' => true, 'task' => $task->load('assignees')]);
    }

    public function moveColumn(Request $request, Task $task)
    {
        $data = $request->validate([
            'column'   => ['required', 'in:backlog,todo,doing,done,archive'],
            'position' => ['nullable', 'integer'],
        ]);

        $oldColumn = $task->column;
        $task->update($data);

        if ($data['column'] !== $oldColumn) {
            $task->load('assignees', 'event');
            $this->handleColumnChangeNotifications($task, $oldColumn, $data['column']);
        }

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'tasks'            => ['required', 'array'],
            'tasks.*.id'       => ['required', 'exists:tasks,id'],
            'tasks.*.position' => ['required', 'integer'],
            'tasks.*.column'   => ['required', 'in:backlog,todo,doing,done,archive'],
        ]);

        // Snapshot current columns before bulk update
        $taskIds        = collect($request->tasks)->pluck('id');
        $oldColumns     = Task::whereIn('id', $taskIds)->pluck('column', 'id');

        foreach ($request->tasks as $item) {
            Task::where('id', $item['id'])->update([
                'position' => $item['position'],
                'column'   => $item['column'],
            ]);
        }

        // Fire notifications only for tasks that actually changed column
        foreach ($request->tasks as $item) {
            $oldColumn = $oldColumns[$item['id']] ?? null;
            if ($oldColumn && $oldColumn !== $item['column']) {
                $task = Task::with('assignees', 'event')->find($item['id']);
                $this->handleColumnChangeNotifications($task, $oldColumn, $item['column']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    public function show(Task $task)
    {
        $task->load('assignees', 'creator', 'event');
        return response()->json($task);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Fire the appropriate notifications when a task's column changes.
     *  - Notify all current assignees (except the actor) about the move.
     *  - If new column is "done": notify task creator, event manager, and
     *    all presidents / vice-presidents (except the actor).
     *  - If old column was "done" and new column is NOT "archive": delete
     *    every existing task_done notification for this task.
     */
    private function handleColumnChangeNotifications(Task $task, string $fromColumn, string $toColumn): void
    {
        $actorId = Auth::id();
        $task->loadMissing('assignees', 'event');

        // Skip notifications for: anything → archive, or archive → done
        $skipMoved = ($toColumn === 'archive') || ($fromColumn === 'archive' && $toColumn === 'done');

        if (!$skipMoved) {
            // Notify assignees about the column move (skip the person who moved it)
            foreach ($task->assignees->where('id', '!=', $actorId) as $assignee) {
                $assignee->notify(new TaskMoved($task, $fromColumn, $toColumn));
            }
        }

        if ($toColumn === 'done' && $fromColumn !== 'archive') {
            $this->notifyTaskDone($task, $actorId);
        }

        if ($fromColumn === 'done' && $toColumn !== 'archive') {
            DB::table('notifications')
                ->whereJsonContains('data->type', 'task_done')
                ->whereJsonContains('data->task_id', $task->id)
                ->delete();
        }
    }

    /**
     * Send a TaskDone notification to the task creator, event manager,
     * and all users with role president or vice_president (skip actor).
     */
    private function notifyTaskDone(Task $task, int $actorId): void
    {
        $task->loadMissing('event');

        $recipientIds = collect([$task->created_by, $task->event->manager_id])
            ->filter()
            ->merge(User::whereIn('role', ['president', 'vice_president'])->pluck('id'))
            ->unique()
            ->reject(fn($id) => $id === $actorId);

        foreach (User::whereIn('id', $recipientIds)->get() as $user) {
            $user->notify(new TaskDone($task));
        }
    }
}
