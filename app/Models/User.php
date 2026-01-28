<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\InstructorDocument;

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
        'jabatan',
        'unit',
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
        'last_seen_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_logout_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function canManageUsers(): bool
    {
        return (bool) ($this->role?->can_manage_users);
    }

    public function canCreatePlans(): bool
    {
        return (bool) ($this->role?->can_create_plans);
    }

    public function canApprovePlans(): bool
    {
        return (bool) ($this->role?->can_approve_plans);
    }

    public function canCreateCourses(): bool
    {
        return (bool) ($this->role?->can_create_courses);
    }

    public function canApproveCourses(): bool
    {
        return (bool) ($this->role?->can_approve_courses);
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

    // Manage MOT Narasumber
    public function instructorDocuments(): HasMany
    {
        return $this->hasMany(InstructorDocument::class);
    }

    public function motDocument(): HasOne
    {
        return $this->hasOne(InstructorDocument::class)
            ->where('type', 'mot')
            ->latestOfMany();
    }

    public function isNarasumber(): bool
    {
        return $this->role?->slug === 'instructor';
    }

    public function canApproveMot(): bool
    {
        return in_array($this->role?->slug, ['developer', 'admin'], true);
    }

    public function canCreateTOR(): bool
    {
        return $this->canCreatePlans();
    }

    public function canApproveTOR(): bool
    {
        return $this->canApprovePlans();
    }

    public function canCreateEvents(): bool
    {
        if ($this->canApprovePlans()) {
            return false;
        }

        return $this->canCreatePlans();
    }

    // Course enrollments
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
            ->withPivot(['status', 'enrolled_at', 'completed_at'])
            ->withTimestamps();
    }

    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }


    public function instructedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_instructors')
            ->withPivot(['role', 'status', 'can_manage_modules'])
            ->withTimestamps();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function latestMot()
    {
        return $this->hasOne(\App\Models\InstructorDocument::class)
            ->where('type', 'mot')
            ->latestOfMany(); // ambil MOT terbaru
    }
}
