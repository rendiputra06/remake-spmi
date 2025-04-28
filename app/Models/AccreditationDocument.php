<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccreditationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_id',
        'standard_id',
        'title',
        'description',
        'document_number',
        'document_type',
        'file_path',
        'status',
        'issue_date',
        'valid_until',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the accreditation that owns the document.
     */
    public function accreditation(): BelongsTo
    {
        return $this->belongsTo(Accreditation::class);
    }

    /**
     * Get the standard associated with this document.
     */
    public function standard(): BelongsTo
    {
        return $this->belongsTo(AccreditationStandard::class, 'standard_id');
    }

    /**
     * Get multiple standards associated with this document (for legacy support).
     */
    public function standards(): BelongsToMany
    {
        return $this->belongsToMany(
            AccreditationStandard::class,
            'accreditation_document_standard',
            'document_id',
            'standard_id'
        )->withTimestamps();
    }

    /**
     * Get the creator of the document.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the document.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the file name from the file path.
     */
    public function getFileName(): string
    {
        return basename($this->file_path);
    }

    /**
     * Get the formatted document type.
     */
    public function getTypeLabel(): string
    {
        $labels = [
            'manual' => 'Manual Mutu',
            'policy' => 'Kebijakan',
            'procedure' => 'Prosedur',
            'regulation' => 'Peraturan',
            'certificate' => 'Sertifikat',
            'report' => 'Laporan',
            'evidence' => 'Bukti',
            'other' => 'Lainnya',
        ];

        return $labels[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get the type color.
     */
    public function getTypeColor(): string
    {
        $colors = [
            'manual' => 'primary',
            'policy' => 'info',
            'procedure' => 'success',
            'regulation' => 'warning',
            'certificate' => 'danger',
            'report' => 'gray',
            'evidence' => 'secondary',
            'other' => 'gray',
        ];

        return $colors[$this->document_type] ?? 'gray';
    }

    /**
     * Get the formatted document status.
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'draft' => 'Draft',
            'review' => 'Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'expired' => 'Kadaluarsa',
            'archived' => 'Diarsipkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get the status color.
     */
    public function getStatusColor(): string
    {
        $colors = [
            'draft' => 'gray',
            'review' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'expired' => 'info',
            'archived' => 'secondary',
        ];

        return $colors[$this->status] ?? 'gray';
    }
}
