<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',

        // tambahan
        'role_id',
        'nik',
        'phone',
        'birth_date',
        'gender',
        'job_category_id',
        'job_title_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }

    /* ---------- helpers biar enak dipakai ---------- */

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function isDeveloper(): bool
    {
        return $this->hasRole('developer');
    }

    public function isDirector(): bool
    {
        return $this->hasRole('director');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    // "Developer di atas Direktur, akses sama kayak Direktur"
    public function canActAsDirector(): bool
    {
        return $this->isDeveloper() || $this->isDirector();
    }
}
