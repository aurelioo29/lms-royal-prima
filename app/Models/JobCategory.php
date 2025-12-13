<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function jobTitles(): HasMany
    {
        return $this->hasMany(JobTitle::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
