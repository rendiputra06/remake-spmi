<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MonitoringDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'type',
        'year',
        'semester',
        'level',
        'department_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'semester' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Konstanta untuk tipe dokumen
     */
    const TYPE_REPORT = 'report';
    const TYPE_EVIDENCE = 'evidence';
    const TYPE_PLAN = 'plan';
    const TYPE_EVALUATION = 'evaluation';
    const TYPE_OTHER = 'other';

    /**
     * Konstanta untuk level dokumen
     */
    const LEVEL_INSTITUTION = 'institution';
    const LEVEL_FACULTY = 'faculty';
    const LEVEL_DEPARTMENT = 'department';
    const LEVEL_PROGRAM = 'program';

    /**
     * Get the department that owns the document.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
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
     * Get the indicators related to this document.
     */
    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceIndicator::class, 'monitoring_document_indicators', 'document_id', 'indicator_id')
            ->withTimestamps();
    }

    /**
     * Get the type label.
     */
    public function getTypeLabel(): string
    {
        $labels = [
            self::TYPE_REPORT => 'Laporan',
            self::TYPE_EVIDENCE => 'Bukti',
            self::TYPE_PLAN => 'Rencana',
            self::TYPE_EVALUATION => 'Evaluasi',
            self::TYPE_OTHER => 'Lainnya',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get the level label.
     */
    public function getLevelLabel(): string
    {
        $labels = [
            self::LEVEL_INSTITUTION => 'Institusi',
            self::LEVEL_FACULTY => 'Fakultas',
            self::LEVEL_DEPARTMENT => 'Departemen',
            self::LEVEL_PROGRAM => 'Program Studi',
        ];

        return $labels[$this->level] ?? $this->level;
    }

    /**
     * Get the file name from the file path.
     */
    public function getFileName(): string
    {
        return basename($this->file_path);
    }

    /**
     * Get formatted semester text.
     */
    public function getSemesterText(): string
    {
        if (!$this->semester) {
            return '';
        }

        return $this->semester == 1 ? 'Ganjil' : 'Genap';
    }

    /**
     * Get academic year text.
     */
    public function getAcademicYearText(): string
    {
        if (!$this->semester) {
            return (string) $this->year;
        }

        $startYear = $this->semester == 1 ? $this->year : $this->year - 1;
        $endYear = $this->semester == 1 ? $this->year + 1 : $this->year;

        return $startYear . '/' . $endYear;
    }

    /**
     * Get period text.
     */
    public function getPeriodText(): string
    {
        if ($this->semester) {
            return 'Semester ' . $this->getSemesterText() . ' ' . $this->getAcademicYearText();
        }

        return 'Tahun ' . $this->year;
    }

    /**
     * Get download URL.
     */
    public function getDownloadUrl(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return route('monitoring.document.download', $this->id);
    }
}
