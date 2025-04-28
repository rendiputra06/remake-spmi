<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccreditationStandard extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_id',
        'code',
        'name',
        'description',
        'weight',
        'parent_id',
        'category',
        'order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weight' => 'float',
        'order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the accreditation that owns the standard.
     */
    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(Accreditation::class);
    }

    /**
     * Get the parent standard.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccreditationStandard::class, 'parent_id');
    }

    /**
     * Get the child standards.
     */
    public function children(): HasMany
    {
        return $this->hasMany(AccreditationStandard::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get all documents related to this standard.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(AccreditationDocument::class, 'standard_id');
    }

    /**
     * Get the evaluations for this standard.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(AccreditationEvaluation::class, 'standard_id');
    }

    /**
     * Get the creator of the standard.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the standard.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if this standard has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the latest evaluation.
     */
    public function getLatestEvaluation()
    {
        return $this->evaluations()->latest()->first();
    }

    /**
     * Calculate the achievement percentage for this standard.
     */
    public function calculateAchievement(): float
    {
        if ($this->hasChildren()) {
            // If this is a parent standard, calculate based on children's weighted achievements
            $childrenAchievements = $this->children()
                ->get()
                ->map(function ($child) {
                    return [
                        'achievement' => $child->calculateAchievement(),
                        'weight' => $child->weight,
                    ];
                });

            $totalWeight = $childrenAchievements->sum('weight');

            if ($totalWeight == 0) {
                return 0;
            }

            return $childrenAchievements->sum(function ($item) use ($totalWeight) {
                return ($item['achievement'] * $item['weight']) / $totalWeight;
            });
        } else {
            // If this is a leaf standard, calculate based on evaluations
            $latestEvaluation = $this->getLatestEvaluation();

            return $latestEvaluation ? ($latestEvaluation->score ?: 0) : 0;
        }
    }

    /**
     * Get the achievement status based on percentage.
     */
    public function getAchievementStatus(): string
    {
        $percentage = $this->calculateAchievement();

        if ($percentage >= 85) {
            return 'Sangat Baik';
        } elseif ($percentage >= 70) {
            return 'Baik';
        } elseif ($percentage >= 55) {
            return 'Cukup';
        } elseif ($percentage >= 40) {
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
        $percentage = $this->calculateAchievement();

        if ($percentage >= 85) {
            return 'success';
        } elseif ($percentage >= 70) {
            return 'info';
        } elseif ($percentage >= 55) {
            return 'warning';
        } elseif ($percentage >= 40) {
            return 'danger';
        } else {
            return 'gray';
        }
    }
}
