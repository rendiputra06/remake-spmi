<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'department_id',
        'coordinator_id',
        'title',
        'description',
        'type',
        'institution_name',
        'grade',
        'period_start',
        'period_end',
        'status',
        'submission_date',
        'visit_date',
        'result_date',
        'expiry_date',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'submission_date' => 'date',
        'visit_date' => 'date',
        'result_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the faculty that owns the accreditation.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the department that owns the accreditation.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the coordinator of the accreditation.
     */
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    /**
     * Get the standards for the accreditation.
     */
    public function standards(): HasMany
    {
        return $this->hasMany(AccreditationStandard::class);
    }

    /**
     * Get the documents for the accreditation.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(AccreditationDocument::class);
    }

    /**
     * Get the evaluations for the accreditation.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(AccreditationEvaluation::class);
    }

    /**
     * Get the creator of the accreditation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the accreditation.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if the accreditation is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the formatted status.
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'draft' => 'Draft',
            'in_progress' => 'Sedang Berjalan',
            'submitted' => 'Diajukan',
            'completed' => 'Selesai',
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
            'in_progress' => 'info',
            'submitted' => 'warning',
            'completed' => 'success',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get the formatted accreditation type.
     */
    public function getTypeLabel(): string
    {
        $labels = [
            'institution' => 'Institusi',
            'faculty' => 'Fakultas',
            'department' => 'Program Studi',
            'program' => 'Program Lainnya',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Calculate the overall achievement percentage.
     */
    public function calculateOverallAchievement(): float
    {
        $standards = $this->standards()
            ->whereNull('parent_id')
            ->get();

        if ($standards->isEmpty()) {
            return 0;
        }

        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($standards as $standard) {
            $evaluation = $standard->getLatestEvaluation();
            if ($evaluation && $standard->weight > 0) {
                $achievementPercent = ($evaluation->score / $standard->target_score) * 100;
                $totalWeightedScore += $achievementPercent * $standard->weight;
                $totalWeight += $standard->weight;
            }
        }

        if ($totalWeight <= 0) {
            return 0;
        }

        return $totalWeightedScore / $totalWeight;
    }

    /**
     * Get the achievement status based on percentage.
     */
    public function getAchievementStatus(): string
    {
        $percentage = $this->calculateOverallAchievement();

        if ($percentage >= 85) {
            return 'Sangat Baik';
        } elseif ($percentage >= 70) {
            return 'Baik';
        } elseif ($percentage >= 55) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }

    /**
     * Get the achievement color based on percentage.
     */
    public function getAchievementColor(): string
    {
        $percentage = $this->calculateOverallAchievement();

        if ($percentage >= 85) {
            return 'success';
        } elseif ($percentage >= 70) {
            return 'primary';
        } elseif ($percentage >= 55) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}
