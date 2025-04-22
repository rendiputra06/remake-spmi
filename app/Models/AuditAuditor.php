<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditAuditor extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'user_id',
        'role',
    ];

    /**
     * Get the audit that this auditor is assigned to.
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the user who is assigned as an auditor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
