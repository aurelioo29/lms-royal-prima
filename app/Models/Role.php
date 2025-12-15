<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'level',
        'can_manage_users',
        'can_create_plans',
        'can_approve_plans',
        'can_create_courses',
        'can_approve_courses',
    ];

    protected $casts = [
        'can_manage_users' => 'boolean',
        'can_create_plans' => 'boolean',
        'can_approve_plans' => 'boolean',
        'can_create_courses' => 'boolean',
        'can_approve_courses' => 'boolean',
    ];
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // helper kecil biar enak
    public function inSlugs(array $slugs): bool
    {
        return in_array($this->slug, $slugs, true);
    }
}
