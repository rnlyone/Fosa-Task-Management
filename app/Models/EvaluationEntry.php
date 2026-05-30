<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationEntry extends Model
{
    protected $fillable = ['submission_id', 'evaluated_user_id', 'rating', 'comment'];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(EvaluationSubmission::class, 'submission_id');
    }

    public function evaluatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_user_id');
    }
}
