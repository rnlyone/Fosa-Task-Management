<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = ['event_id', 'opens_at', 'closes_at'];

    protected $casts = [
        'opens_at'  => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function getStatusAttribute(): string
    {
        $now = now();
        if ($this->closes_at && $this->closes_at->lt($now)) return 'closed';
        if ($this->opens_at && $this->opens_at->lte($now)) return 'open';
        return 'upcoming';
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(EvaluationSubmission::class);
    }

    public function submissionByUser(int $userId): ?EvaluationSubmission
    {
        return $this->submissions()->where('evaluator_id', $userId)->first();
    }

    public function isOpen(): bool
    {
        $now = now();
        return (!$this->opens_at || $this->opens_at->lte($now))
            && (!$this->closes_at || $this->closes_at->gte($now));
    }

    /**
     * Aggregate average ratings per evaluated user.
     */
    public function averageRatings(): \Illuminate\Support\Collection
    {
        return EvaluationEntry::query()
            ->selectRaw('evaluated_user_id, AVG(rating) as avg_rating, COUNT(*) as total_reviews')
            ->whereIn('submission_id', $this->submissions()->pluck('id'))
            ->groupBy('evaluated_user_id')
            ->with('evaluatedUser')
            ->get();
    }
}
