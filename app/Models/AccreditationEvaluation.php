<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccreditationEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_id',
        'name',
        'description',
        'overall_score',
        'strengths',
        'weaknesses',
        'recommendations',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
    ];

    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(Accreditation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
