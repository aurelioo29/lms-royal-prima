<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function (CourseType $type) {
            $type->slug = $type->slug ?: static::makeUniqueSlug($type->name);
        });

        static::updating(function (CourseType $type) {
            if ($type->isDirty('name')) {
                $type->slug = static::makeUniqueSlug($type->name, $type->id);
            }

            if ($type->isDirty('slug') && filled($type->slug)) {
                $type->slug = static::makeUniqueSlug($type->slug, $type->id, true);
            }
        });
    }

    /**
     * Generate unique slug: "pelatihan-blended-3f8k"
     */
    public static function makeUniqueSlug(string $source, ?int $ignoreId = null, bool $sourceAlreadySlug = false): string
    {
        $base = $sourceAlreadySlug ? Str::slug($source) : Str::slug($source);
        $base = $base ?: 'course-type';

        do {
            $candidate = $base . '-' . Str::lower(Str::random(4));
            $exists = static::query()
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists();
        } while ($exists);

        return $candidate;
    }
}
