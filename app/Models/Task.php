<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'event_id',
        'created_by',
        'column',
        'description',
        'deadline_date',
        'priority',
        'card_color',
        'position',
    ];

    protected $casts = [
        'deadline_date' => 'date',
    ];

    // --- Relationships ---
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_assignees');
    }

    // --- Helpers ---
    public function priorityColor(): string
    {
        return match ($this->priority) {
            'low'      => 'success',
            'medium'   => 'info',
            'high'     => 'warning',
            'critical' => 'danger',
            default    => 'secondary',
        };
    }

    public function isOverdue(): bool
    {
        return $this->deadline_date
            && $this->deadline_date->isPast()
            && !in_array($this->column, ['done', 'archive']);
    }
}
