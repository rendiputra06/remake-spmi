<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'unit',
        'target_value',
        'minimum_value',
        'standard_id',
        'formula',
        'data_source',
        'frequency',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'minimum_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function standard(): BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(MonitoringMeasurement::class);
    }

    public function dashboards()
    {
        return $this->belongsToMany(
            MonitoringDashboard::class,
            'monitoring_dashboard_indicators',
            'monitoring_indicator_id',
            'monitoring_dashboard_id'
        )->withPivot('display_order');
    }
}
