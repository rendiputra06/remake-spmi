<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'year',
        'semester',
        'measurement_date',
        'value',
        'achievement_percentage',
        'description',
        'findings',
        'root_causes',
        'recommendations',
        'corrective_actions',
        'created_by',
        'updated_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'year' => 'integer',
        'semester' => 'integer',
        'measurement_date' => 'date',
        'value' => 'float',
        'achievement_percentage' => 'float',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the indicator that owns the value.
     */
    public function indicator(): BelongsTo
    {
        return $this->belongsTo(PerformanceIndicator::class, 'indicator_id');
    }

    /**
     * Get the creator of the value.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the value.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the verifier of the value.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if the value is verified.
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Calculate achievement percentage based on target.
     */
    public function calculateAchievement(): float
    {
        $indicator = $this->indicator;
        $target = $indicator->getTarget($this->year);

        if (!$target) {
            return 0;
        }

        return ($this->value / $target) * 100;
    }

    /**
     * Get the achievement status based on percentage.
     */
    public function getAchievementStatus(): string
    {
        $percentage = $this->achievement_percentage ?? $this->calculateAchievement();

        if ($percentage >= 100) {
            return 'Tercapai';
        } elseif ($percentage >= 85) {
            return 'Hampir Tercapai';
        } elseif ($percentage >= 70) {
            return 'Cukup Tercapai';
        } else {
            return 'Belum Tercapai';
        }
    }

    /**
     * Get the achievement color based on percentage.
     */
    public function getAchievementColor(): string
    {
        $percentage = $this->achievement_percentage ?? $this->calculateAchievement();

        if ($percentage >= 100) {
            return 'success';
        } elseif ($percentage >= 85) {
            return 'info';
        } elseif ($percentage >= 70) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    /**
     * Get formatted semester text.
     */
    public function getSemesterText(): string
    {
        if (!$this->semester) {
            return '';
        }

        return $this->semester == 1 ? 'Ganjil' : 'Genap';
    }

    /**
     * Get academic year text.
     */
    public function getAcademicYearText(): string
    {
        if (!$this->semester) {
            return $this->year;
        }

        $startYear = $this->semester == 1 ? $this->year : $this->year - 1;
        $endYear = $this->semester == 1 ? $this->year + 1 : $this->year;

        return $startYear . '/' . $endYear;
    }
}
