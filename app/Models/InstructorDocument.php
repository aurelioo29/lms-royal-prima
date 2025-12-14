<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorDocument extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'status',
        'issued_at',
        'expires_at',
        'verified_by',
        'verified_at',
        'rejected_reason',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeMot($q)
    {
        return $q->where('type', 'mot');
    }
}
