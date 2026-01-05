<?php

namespace App\Services\Course;

use App\Models\CourseModule;
use App\Models\ModuleProgress;
use Illuminate\Support\Facades\DB;

class ModuleProgressService
{
    public function complete(CourseModule $module, int $userId): void
    {
        DB::transaction(function () use ($module, $userId) {

            ModuleProgress::updateOrCreate(
                [
                    'course_module_id' => $module->id,
                    'user_id' => $userId,
                ],
                [
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );

            // 🔥 trigger evaluasi completion
            app(CourseCompletionService::class)
                ->evaluate($module->course_id, $userId);
        });
    }
}
