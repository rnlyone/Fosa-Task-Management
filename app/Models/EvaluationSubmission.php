<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationSubmission extends Model
{
    protected $fillable = ['evaluation_id', 'evaluator_id', 'submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(EvaluationEntry::class, 'submission_id');
    }

    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }
}
