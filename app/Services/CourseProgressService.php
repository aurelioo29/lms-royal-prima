<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use Illuminate\Support\Carbon;

class CourseProgressService
{
    // Ringkasan progress course untuk 1 user
    public static function summary(Course $course, int $userId): array
    {
        $modules = $course->modules()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $total = $modules->count();

        if ($total === 0) {
            return [
                'total' => 0,
                'completed' => 0,
                'percent' => 0,
                'next_module' => null,
            ];
        }

        $completedModuleIds = ModuleProgress::where('user_id', $userId)
            ->whereIn('course_module_id', $modules->pluck('id'))
            ->where('status', 'completed')
            ->pluck('course_module_id');

        $completed = $completedModuleIds->count();

        // 🎯 NEXT MODULE = modul pertama yang BELUM completed
        $nextModule = $modules->firstWhere(
            fn($module) => ! $completedModuleIds->contains($module->id)
        );

        return [
            'total'     => $total,
            'completed' => $completed,
            'percent'   => round(($completed / $total) * 100),
            'next_module' => $nextModule,
        ];
    }


    // cek apakah modul terkunci
    public static function isLocked(
        Course $course,
        CourseModule $module,
        int $userId
    ): bool {
        // ✅ JIKA MODUL SUDAH PERNAH DIAKSES → JANGAN DIKUNCI
        $alreadyAccessed = ModuleProgress::where('user_id', $userId)
            ->where('course_module_id', $module->id)
            ->exists();

        if ($alreadyAccessed) {
            return false;
        }

        $requiredIds = $course->modules()
            ->where('is_required', true)
            ->where('sort_order', '<', $module->sort_order)
            ->pluck('id');

        if ($requiredIds->isEmpty()) {
            return false;
        }

        return ModuleProgress::where('user_id', $userId)
            ->whereIn('course_module_id', $requiredIds)
            ->where('status', '!=', 'completed')
            ->exists();
    }


    // Set modul menjadi IN PROGRESS (ketika dibuka)
    public static function markInProgress(
        CourseModule $module,
        int $userId
    ): ModuleProgress {
        return ModuleProgress::firstOrCreate(
            [
                'course_module_id' => $module->id,
                'user_id' => $userId,
            ],
            [
                'status' => 'in_progress',
            ]
        );
    }

    // Set modul menjadi COMPLETED
    public static function markCompleted(
        CourseModule $module,
        int $userId
    ): ModuleProgress {
        return ModuleProgress::updateOrCreate(
            [
                'course_module_id' => $module->id,
                'user_id' => $userId,
            ],
            [
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]
        );
    }
}
