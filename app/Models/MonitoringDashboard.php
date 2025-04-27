<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MonitoringDashboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'category',
        'config',
        'is_public',
        'filters',
        'display_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'config' => 'json',
        'filters' => 'json',
        'is_public' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(
            MonitoringIndicator::class,
            'monitoring_dashboard_indicators',
            'monitoring_dashboard_id',
            'monitoring_indicator_id'
        )->withPivot('display_order');
    }
}
