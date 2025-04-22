<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'question',
        'type',
        'options',
        'min_value',
        'max_value',
        'min_label',
        'max_label',
        'order',
        'is_required',
    ];

    protected $casts = [
        'options' => 'array',
        'min_value' => 'integer',
        'max_value' => 'integer',
        'order' => 'integer',
        'is_required' => 'boolean',
    ];

    /**
     * Get the survey that owns the question.
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Get the answers for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
