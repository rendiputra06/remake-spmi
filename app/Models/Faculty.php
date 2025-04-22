<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'dean_id',
    ];

    /**
     * Get the dean of the faculty.
     */
    public function dean(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    /**
     * Get the departments in this faculty.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get the user profiles associated with this faculty.
     */
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}
