<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobTitle extends Model
{
    protected $fillable = ['job_category_id', 'name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }
}
