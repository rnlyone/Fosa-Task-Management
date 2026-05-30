<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
        'role',
        'avatar',
        'pending_email',
        'email_change_token',
        'mail_preferences',
    ];

    protected $hidden = ['password', 'remember_token', 'email_change_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'mail_preferences'  => 'array',
        ];
    }

    // --- Role helpers ---
    public function isPresident(): bool
    {
        return $this->role === 'president';
    }

    public function isVicePresident(): bool
    {
        return $this->role === 'vice_president';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'administrator';
    }

    /** True for president, vice_president, and administrator. */
    public function isLeadership(): bool
    {
        return in_array($this->role, ['president', 'vice_president', 'administrator']);
    }

    // --- Relationships ---
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_user');
    }

    public function managedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'manager_id');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_members');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_assignees');
    }

    public function evaluationSubmissions(): HasMany
    {
        return $this->hasMany(EvaluationSubmission::class, 'evaluator_id');
    }

    /**
     * Workload score: count active tasks in current + 1 previous event.
     */
    public function workloadScore(?int $currentEventId = null): int
    {
        $query = $this->tasks()
            ->whereIn('column', ['todo', 'doing', 'backlog']);

        if ($currentEventId) {
            $previousEvent = Event::where('id', '<', $currentEventId)
                ->where('status', 'completed')
                ->orderByDesc('id')
                ->first();

            $eventIds = collect([$currentEventId]);
            if ($previousEvent) {
                $eventIds->push($previousEvent->id);
            }

            $query->whereIn('tasks.event_id', $eventIds);
        }

        return $query->count();
    }

    /**
     * Check whether the user wants an email for a given notification type.
     * Defaults to true (enabled) if no preference has been saved.
     */
    public function wantsMailFor(string $type): bool
    {
        return $this->mail_preferences[$type] ?? true;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // Files are stored directly in public/avatars/ — no symlink needed
            return asset($this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=7367f0&color=fff';
    }
}
