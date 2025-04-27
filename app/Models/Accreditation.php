<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'institution_name',
        'status',
        'grade',
        'submission_date',
        'visit_date',
        'result_date',
        'expiry_date',
        'faculty_id',
        'department_id',
        'coordinator_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'visit_date' => 'date',
        'result_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function standards(): HasMany
    {
        return $this->hasMany(AccreditationStandard::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AccreditationDocument::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(AccreditationEvaluation::class);
    }

    public function getStatusText()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'in_progress' => 'Sedang Berjalan',
            'submitted' => 'Diajukan',
            'completed' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusColor()
    {
        return match ($this->status) {
            'draft' => 'gray',
            'in_progress' => 'blue',
            'submitted' => 'yellow',
            'completed' => 'green',
            default => 'gray',
        };
    }
}
