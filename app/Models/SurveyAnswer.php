<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_response_id',
        'survey_question_id',
        'answer',
        'answer_type',
    ];

    protected $casts = [
        'answer' => 'string',
    ];

    /**
     * Get the response that owns the answer.
     */
    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    /**
     * Get the question that this answer is for.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    /**
     * Get the answer value with the correct type casting
     */
    public function getTypedAnswerAttribute()
    {
        if (!$this->answer_type || !$this->answer) {
            return $this->answer;
        }

        return match ($this->answer_type) {
            'integer', 'number' => (int) $this->answer,
            'float', 'decimal' => (float) $this->answer,
            'boolean' => (bool) $this->answer,
            'array', 'json' => json_decode($this->answer, true),
            default => $this->answer,
        };
    }
}
