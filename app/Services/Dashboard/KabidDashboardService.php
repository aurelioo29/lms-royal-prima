<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\AnnualPlan;
use App\Models\CourseCompletion;
use Illuminate\Support\Facades\DB;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class KabidDashboardService implements DashboardRoleService
{
    protected int $employeeRoleId;

    public function __construct()
    {
        $this->employeeRoleId = Role::where('slug', 'karyawan')->value('id');
    }

    /**
     * KPI UTAMA
     */
    public function getStats(User $user): array
    {
        $year = now()->year;

        $employeeIds = User::where('role_id', $this->employeeRoleId)
            ->where('is_active', true)
            ->pluck('id');

        $totalEmployees = $employeeIds->count();

        $totalJpl = CourseCompletion::whereYear('completed_at', $year)
            ->whereIn('user_id', $employeeIds)
            ->sum('earned_hours');

        return [
            [
                'label' => 'Total Karyawan Aktif',
                'value' => $totalEmployees,
            ],
            [
                'label' => 'Course Aktif',
                'value' => Course::where('status', 'published')->count(),
            ],
            [
                'label' => 'Total JPL Tahun Ini',
                'value' => $totalJpl,
            ],
            [
                'label' => 'Rata-rata JPL / Karyawan',
                'value' => $totalEmployees > 0
                    ? round($totalJpl / $totalEmployees, 1)
                    : 0,
            ],
        ];
    }

    /**
     * SUMMARY & PROGRESS TAHUNAN
     */
    public function getSummary(User $user): array
    {
        $year = now()->year;
        $targetPerEmployee = 20;

        /** 1. Karyawan aktif */
        $employeeIds = User::where('role_id', $this->employeeRoleId)
            ->where('is_active', true)
            ->pluck('id');

        $employeeCount = $employeeIds->count();

        /** 2. JPL per karyawan */
        $jplPerEmployee = User::whereIn('users.id', $employeeIds)
            ->leftJoin('course_completions', function ($join) use ($year) {
                $join->on('users.id', '=', 'course_completions.user_id')
                    ->whereYear('course_completions.completed_at', $year);
            })
            ->select(
                'users.id',
                'users.name',
                DB::raw('COALESCE(SUM(course_completions.earned_hours), 0) as total_jpl')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_jpl')
            ->get();

        // dd($jplPerEmployee);


        /** 3. Total JPL */
        $totalJpl = $jplPerEmployee->sum('total_jpl');

        /** 4. Target institusi */
        $targetInstitusi = $employeeCount * 20;

        /** 5. Progress */
        $progress = $targetInstitusi > 0
            ? round(($totalJpl / $targetInstitusi) * 100)
            : 0;

        return [
            'total_jpl'        => $totalJpl,
            'avg_jpl'          => $employeeCount > 0
                ? round($totalJpl / $employeeCount, 1)
                : 0,
            'target_jpl'       => $targetInstitusi,
            'progress_percent' => min($progress, 100),
            'jpl_per_employee' => $jplPerEmployee,
            'target_per_employee' => $targetPerEmployee,
        ];
    }


    /**
     * AKTIVITAS (DINONAKTIFKAN)
     */
    public function getActivities(User $user): array
    {
        return [];
    }
}
