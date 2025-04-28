<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'year',
        'target',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'target' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the indicator that owns the target.
     */
    public function indicator(): BelongsTo
    {
        return $this->belongsTo(PerformanceIndicator::class, 'indicator_id');
    }

    /**
     * Get the creator of the target.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the target.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculate achievement percentage based on actual values.
     */
    public function calculateAchievement(): float
    {
        $latestValue = $this->indicator->values()
            ->where('year', $this->year)
            ->latest('measurement_date')
            ->first();

        if (!$latestValue || !$this->target) {
            return 0;
        }

        return ($latestValue->value / $this->target) * 100;
    }

    /**
     * Get the achievement status based on percentage.
     */
    public function getAchievementStatus(): string
    {
        $percentage = $this->calculateAchievement();

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
        $percentage = $this->calculateAchievement();

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
}
