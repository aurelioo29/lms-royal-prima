<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditEvent extends Model
{
    use HasFactory;

    protected $table = 'audit_events';

    protected $fillable = [
        'occurred_at',

        'actor_id',
        'actor_name',
        'actor_role_slug',

        'action',

        'entity_type',
        'entity_id',

        'summary',
        'meta',

        'ip',
        'user_agent',
        'request_id',
    ];

    // Cast kolom
    protected $casts = [
        'occurred_at' => 'datetime',
        'meta'        => 'array',
    ];

    // Relasi ke User (actor)
    // Nullable karena user bisa terhapus
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    // Scope: filter berdasarkan role actor
    public function scopeByRole($query, string $roleSlug)
    {
        return $query->where('actor_role_slug', $roleSlug);
    }

    // Scope: filter berdasarkan entity
    public function scopeForEntity($query, string $entityType, $entityId = null)
    {
        $query->where('entity_type', $entityType);

        if ($entityId !== null) {
            $query->where('entity_id', $entityId);
        }

        return $query;
    }

    // Scope: filter berdasarkan action
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
