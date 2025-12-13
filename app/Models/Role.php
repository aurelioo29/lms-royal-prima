<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'level'];

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
