<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccreditationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_id',
        'accreditation_standard_id',
        'document_id',
        'status',
        'notes',
        'reviewer_id',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(Accreditation::class);
    }

    public function standard(): BelongsTo
    {
        return $this->belongsTo(AccreditationStandard::class, 'accreditation_standard_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getStatusText()
    {
        $statuses = [
            'submitted' => 'Submitted',
            'reviewed' => 'Reviewed',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColor()
    {
        $colors = [
            'submitted' => 'blue',
            'reviewed' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red'
        ];

        return $colors[$this->status] ?? 'gray';
    }
}
