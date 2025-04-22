<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'visibility',
        'start_date',
        'end_date',
        'target_audience',
        'category',
        'is_anonymous',
        'created_by',
        'updated_by',
        'faculty_id',
        'department_id',
        'unit_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_anonymous' => 'boolean',
    ];

    /**
     * Get the user who created this survey.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this survey.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the faculty associated with this survey.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the department associated with this survey.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the unit associated with this survey.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the questions for this survey.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order');
    }

    /**
     * Get the responses for this survey.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Scope a query to only include active surveys.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->where('end_date', '>=', now())
                    ->orWhereNull('end_date');
            });
    }

    /**
     * Scope a query to only include closed surveys.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed')
            ->orWhere('end_date', '<', now());
    }

    /**
     * Scope a query to only include draft surveys.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
