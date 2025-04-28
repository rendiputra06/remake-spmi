<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dashboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'level',
        'department_id',
        'configuration',
        'is_public',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Konstanta untuk tipe dashboard
     */
    const TYPE_MONITORING = 'monitoring';
    const TYPE_ANALYTICS = 'analytics';
    const TYPE_STRATEGIC = 'strategic';
    const TYPE_OPERATIONAL = 'operational';
    const TYPE_OTHER = 'other';

    /**
     * Konstanta untuk level dashboard
     */
    const LEVEL_INSTITUTION = 'institution';
    const LEVEL_FACULTY = 'faculty';
    const LEVEL_DEPARTMENT = 'department';
    const LEVEL_PROGRAM = 'program';

    /**
     * Get the department that owns the dashboard.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the creator of the dashboard.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the dashboard.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the indicators for the dashboard.
     */
    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceIndicator::class, 'dashboard_indicators', 'dashboard_id', 'indicator_id')
            ->withPivot('chart_type', 'chart_config', 'order')
            ->orderBy('pivot_order')
            ->withTimestamps();
    }

    /**
     * Get the type label.
     */
    public function getTypeLabel(): string
    {
        $labels = [
            self::TYPE_MONITORING => 'Monitoring',
            self::TYPE_ANALYTICS => 'Analitik',
            self::TYPE_STRATEGIC => 'Strategis',
            self::TYPE_OPERATIONAL => 'Operasional',
            self::TYPE_OTHER => 'Lainnya',
        ];

        return $labels[$this->type] ?? $this->type;
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
     * Get dashboard configuration value.
     */
    public function getConfigValue(string $key, $default = null)
    {
        if (!is_array($this->configuration)) {
            return $default;
        }

        return $this->configuration[$key] ?? $default;
    }

    /**
     * Set dashboard configuration value.
     */
    public function setConfigValue(string $key, $value): void
    {
        $config = is_array($this->configuration) ? $this->configuration : [];
        $config[$key] = $value;
        $this->configuration = $config;
    }

    /**
     * Calculate average achievement percentage.
     */
    public function calculateAverageAchievement(): float
    {
        $indicators = $this->indicators;

        if ($indicators->isEmpty()) {
            return 0;
        }

        $total = 0;
        $count = 0;

        foreach ($indicators as $indicator) {
            $achievement = $indicator->calculateAchievement();
            if ($achievement > 0) {
                $total += $achievement;
                $count++;
            }
        }

        return $count > 0 ? $total / $count : 0;
    }
}
