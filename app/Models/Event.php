<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'event_date',
        'start_preparing_date',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_preparing_date' => 'date',
    ];

    // --- Relationships ---
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_members');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    // --- Helpers ---
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function tasksByColumn(string $column)
    {
        return $this->tasks()->where('column', $column)->orderBy('position')->get();
    }

    public function taskStats(): array
    {
        $tasks = $this->tasks()->get(['column', 'deadline_date']);

        $totals = $tasks->countBy('column')->toArray();

        $done  = $totals['done'] ?? 0;
        $total = $tasks->count();

        $overdue = $tasks->filter(fn($t) =>
            !in_array($t->column, ['done', 'archive']) &&
            $t->deadline_date &&
            \Carbon\Carbon::parse($t->deadline_date)->isPast()
        )->count();

        return [
            'backlog'         => $totals['backlog'] ?? 0,
            'todo'            => $totals['todo'] ?? 0,
            'doing'           => $totals['doing'] ?? 0,
            'done'            => $done,
            'archive'         => $totals['archive'] ?? 0,
            'total'           => $total,
            'overdue'         => $overdue,
            'completion_rate' => $total > 0 ? round($done / $total * 100) : 0,
        ];
    }

    /**
     * Members whose accumulated task load (this + previous event) exceeds threshold.
     */
    public function overloadedMembers(int $threshold = 8): \Illuminate\Support\Collection
    {
        $previousEvent = static::where('id', '<', $this->id)
            ->where('status', 'completed')
            ->orderByDesc('id')
            ->first();

        $eventIds = [$this->id];
        if ($previousEvent) {
            $eventIds[] = $previousEvent->id;
        }

        return $this->members->filter(function (User $member) use ($eventIds, $threshold) {
            $count = $member->tasks()
                ->whereIn('tasks.event_id', $eventIds)
                ->whereIn('column', ['backlog', 'todo', 'doing'])
                ->count();
            return $count >= $threshold;
        });
    }

    /**
     * Members with low contribution: few done tasks across this + previous event.
     */
    public function underperformingMembers(int $threshold = 2): \Illuminate\Support\Collection
    {
        $previousEvent = static::where('id', '<', $this->id)
            ->where('status', 'completed')
            ->orderByDesc('id')
            ->first();

        $eventIds = [$this->id];
        if ($previousEvent) {
            $eventIds[] = $previousEvent->id;
        }

        return $this->members->filter(function (User $member) use ($eventIds, $threshold) {
            $done = $member->tasks()
                ->whereIn('tasks.event_id', $eventIds)
                ->where('column', 'done')
                ->count();
            return $done <= $threshold;
        });
    }
}
