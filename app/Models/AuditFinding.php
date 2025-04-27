<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditFinding extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'description',
        'standard_id',
        'type',
        'status',
        'followup_action',
        'followup_date',
        'followup_by',
        'verification_notes',
        'verification_date',
        'verified_by',
        'evidence',
        'target_completion_date',
        'created_by',
    ];

    protected $casts = [
        'target_completion_date' => 'date',
        'followup_date' => 'date',
        'verification_date' => 'date',
    ];

    /**
     * Get the audit that owns the finding.
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the standard associated with the finding.
     */
    public function standard(): BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    /**
     * Get the user who created this finding.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who performed the followup action.
     */
    public function followupBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'followup_by');
    }

    /**
     * Get the user who verified the finding.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include findings that need followup.
     */
    public function scopeNeedsFollowup($query)
    {
        return $query->whereIn('status', ['open', 'responded'])
            ->whereNull('followup_action');
    }

    /**
     * Scope a query to only include findings that need verification.
     */
    public function scopeNeedsVerification($query)
    {
        return $query->where('status', 'in_progress')
            ->whereNotNull('followup_action')
            ->whereNull('verification_notes');
    }
}
