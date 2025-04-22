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
        'standard_id',
        'type',
        'finding',
        'evidence',
        'recommendation',
        'response',
        'action_plan',
        'response_date',
        'target_completion_date',
        'status',
        'created_by',
        'responded_by',
        'verified_by',
    ];

    protected $casts = [
        'response_date' => 'date',
        'target_completion_date' => 'date',
    ];

    /**
     * Get the audit that this finding belongs to.
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the standard related to this finding.
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
     * Get the user who responded to this finding.
     */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Get the user who verified this finding.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include open findings.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include verified findings.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope a query to only include closed findings.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
