<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

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

    public function getStatusText()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusColor()
    {
        return match ($this->status) {
            'draft' => 'gray',
            'active' => 'green',
            'completed' => 'blue',
            default => 'gray',
        };
    }
}
