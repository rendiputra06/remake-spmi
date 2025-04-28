<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccreditationEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_id',
        'standard_id',
        'title',
        'description',
        'evaluation_date',
        'status',
        'score',
        'findings',
        'recommendations',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'evaluation_date' => 'date',
        'score' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REVIEWED = 'reviewed';

    /**
     * Get the accreditation that owns the evaluation.
     */
    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(Accreditation::class);
    }

    /**
     * Get the standard being evaluated.
     */
    public function standard(): BelongsTo
    {
        return $this->belongsTo(AccreditationStandard::class, 'standard_id');
    }

    /**
     * Get the creator of the evaluation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the evaluation.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the formatted status.
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'draft' => 'Draft',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            'reviewed' => 'Sudah Ditinjau',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get the status color.
     */
    public function getStatusColor(): string
    {
        $colors = [
            'draft' => 'gray',
            'in_progress' => 'warning',
            'completed' => 'success',
            'reviewed' => 'info',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get the achievement level based on percentage.
     */
    public function getAchievementLevel(): string
    {
        if ($this->achievement_percentage >= 85) {
            return 'Sangat Baik';
        } elseif ($this->achievement_percentage >= 70) {
            return 'Baik';
        } elseif ($this->achievement_percentage >= 55) {
            return 'Cukup';
        } elseif ($this->achievement_percentage >= 40) {
            return 'Kurang';
        } else {
            return 'Sangat Kurang';
        }
    }

    /**
     * Get the achievement color based on percentage.
     */
    public function getAchievementColor(): string
    {
        if ($this->achievement_percentage >= 85) {
            return 'success';
        } elseif ($this->achievement_percentage >= 70) {
            return 'info';
        } elseif ($this->achievement_percentage >= 55) {
            return 'warning';
        } elseif ($this->achievement_percentage >= 40) {
            return 'danger';
        } else {
            return 'gray';
        }
    }
}
