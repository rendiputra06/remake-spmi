<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the faculty where the user is a dean.
     */
    public function managedFaculty(): HasOne
    {
        return $this->hasOne(Faculty::class, 'dean_id');
    }

    /**
     * Get the department where the user is a head.
     */
    public function managedDepartment(): HasOne
    {
        return $this->hasOne(Department::class, 'head_id');
    }

    /**
     * Get the unit where the user is a head.
     */
    public function managedUnit(): HasOne
    {
        return $this->hasOne(Unit::class, 'head_id');
    }

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Default allow access to admin panel for users with any role
        return $this->hasRole(['super-admin', 'admin', 'kepala-lpm', 'auditor', 'pimpinan', 'dekan', 'kaprodi', 'dosen', 'staff']);
    }
}
