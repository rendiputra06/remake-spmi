<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_period_id',
        'monitoring_indicator_id',
        'faculty_id',
        'department_id',
        'actual_value',
        'status',
        'remarks',
        'achievements',
        'obstacles',
        'follow_up',
        'document_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'actual_value' => 'decimal:2',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(MonitoringPeriod::class, 'monitoring_period_id');
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(MonitoringIndicator::class, 'monitoring_indicator_id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusText()
    {
        $statuses = [
            'achieved' => 'Tercapai',
            'not_achieved' => 'Belum Tercapai',
            'in_progress' => 'Dalam Proses',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColor()
    {
        $colors = [
            'achieved' => 'success',
            'not_achieved' => 'danger',
            'in_progress' => 'warning',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    // Menghitung persentase pencapaian terhadap target
    public function getAchievementPercentage()
    {
        if (!$this->actual_value || !$this->indicator->target_value) {
            return 0;
        }

        $percentage = ($this->actual_value / $this->indicator->target_value) * 100;
        return min(100, $percentage); // Maksimal 100%
    }
}
