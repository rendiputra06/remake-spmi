<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PerformanceIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'unit',
        'target',
        'type',
        'category',
        'level',
        'department_id',
        'parent_id',
        'order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'target' => 'float',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Konstanta untuk tipe indikator
     */
    const TYPE_QUALITY = 'quality';
    const TYPE_PROCESS = 'process';
    const TYPE_OUTPUT = 'output';
    const TYPE_OUTCOME = 'outcome';
    const TYPE_IMPACT = 'impact';

    /**
     * Konstanta untuk kategori indikator
     */
    const CATEGORY_ACADEMIC = 'academic';
    const CATEGORY_RESEARCH = 'research';
    const CATEGORY_COMMUNITY_SERVICE = 'community_service';
    const CATEGORY_GOVERNANCE = 'governance';
    const CATEGORY_INFRASTRUCTURE = 'infrastructure';
    const CATEGORY_FINANCE = 'finance';
    const CATEGORY_HUMAN_RESOURCE = 'human_resource';
    const CATEGORY_OTHER = 'other';

    /**
     * Konstanta untuk level indikator
     */
    const LEVEL_INSTITUTION = 'institution';
    const LEVEL_FACULTY = 'faculty';
    const LEVEL_DEPARTMENT = 'department';
    const LEVEL_PROGRAM = 'program';

    /**
     * Get the department that owns the indicator.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the parent indicator.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(PerformanceIndicator::class, 'parent_id');
    }

    /**
     * Get the child indicators.
     */
    public function children(): HasMany
    {
        return $this->hasMany(PerformanceIndicator::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the values for the indicator.
     */
    public function values(): HasMany
    {
        return $this->hasMany(PerformanceValue::class, 'indicator_id');
    }

    /**
     * Get the targets for the indicator.
     */
    public function targets(): HasMany
    {
        return $this->hasMany(PerformanceTarget::class, 'indicator_id');
    }

    /**
     * Get the dashboards that use this indicator.
     */
    public function dashboards(): BelongsToMany
    {
        return $this->belongsToMany(Dashboard::class, 'dashboard_indicators', 'indicator_id', 'dashboard_id')
            ->withPivot('chart_type', 'chart_config', 'order')
            ->orderBy('pivot_order')
            ->withTimestamps();
    }

    /**
     * Get the documents related to this indicator.
     */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(MonitoringDocument::class, 'monitoring_document_indicators', 'indicator_id', 'document_id')
            ->withTimestamps();
    }

    /**
     * Get the creator of the indicator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the indicator.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the latest value for this indicator.
     */
    public function getLatestValue()
    {
        return $this->values()->latest('measurement_date')->first();
    }

    /**
     * Get the target for a specific year.
     */
    public function getTarget($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $target = $this->targets()->where('year', $year)->first();
        return $target ? $target->target : $this->target;
    }

    /**
     * Get the type label.
     */
    public function getTypeLabel(): string
    {
        $labels = [
            self::TYPE_QUALITY => 'Mutu',
            self::TYPE_PROCESS => 'Proses',
            self::TYPE_OUTPUT => 'Keluaran',
            self::TYPE_OUTCOME => 'Hasil',
            self::TYPE_IMPACT => 'Dampak',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get the category label.
     */
    public function getCategoryLabel(): string
    {
        $labels = [
            self::CATEGORY_ACADEMIC => 'Akademik',
            self::CATEGORY_RESEARCH => 'Penelitian',
            self::CATEGORY_COMMUNITY_SERVICE => 'Pengabdian Masyarakat',
            self::CATEGORY_GOVERNANCE => 'Tata Kelola',
            self::CATEGORY_INFRASTRUCTURE => 'Sarana Prasarana',
            self::CATEGORY_FINANCE => 'Keuangan',
            self::CATEGORY_HUMAN_RESOURCE => 'SDM',
            self::CATEGORY_OTHER => 'Lainnya',
        ];

        return $labels[$this->category] ?? $this->category;
    }

    /**
     * Get the level label.
     */
    public function getLevelLabel(): string
    {
        $labels = [
            self::LEVEL_INSTITUTION => 'Institusi',
            self::LEVEL_FACULTY => 'Fakultas',
            self::LEVEL_DEPARTMENT => 'Departemen',
            self::LEVEL_PROGRAM => 'Program Studi',
        ];

        return $labels[$this->level] ?? $this->level;
    }

    /**
     * Calculate achievement percentage for a specific year.
     */
    public function calculateAchievement($year = null): float
    {
        if (!$year) {
            $year = date('Y');
        }

        $value = $this->values()->where('year', $year)->latest('measurement_date')->first();
        $target = $this->getTarget($year);

        if (!$value || !$target) {
            return 0;
        }

        return ($value->value / $target) * 100;
    }

    /**
     * Get the achievement status based on percentage.
     */
    public function getAchievementStatus($year = null): string
    {
        $percentage = $this->calculateAchievement($year);

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
    public function getAchievementColor($year = null): string
    {
        $percentage = $this->calculateAchievement($year);

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
