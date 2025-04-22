<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'audit_date_start',
        'audit_date_end',
        'faculty_id',
        'department_id',
        'unit_id',
        'status',
        'lead_auditor_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'audit_date_start' => 'date',
        'audit_date_end' => 'date',
    ];

    /**
     * Get the user who created this audit.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this audit.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the lead auditor for this audit.
     */
    public function leadAuditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_auditor_id');
    }

    /**
     * Get the faculty being audited.
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the department being audited.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the unit being audited.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the auditors for this audit.
     */
    public function auditors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'audit_auditors')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the findings for this audit.
     */
    public function findings(): HasMany
    {
        return $this->hasMany(AuditFinding::class);
    }

    /**
     * Scope a query to only include audits that are in progress.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope a query to only include audits that are completed.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include audits that are planned.
     */
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }
}
