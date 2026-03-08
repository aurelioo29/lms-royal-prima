<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Dashboard\DashboardService;
use App\Support\RoleMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        [$count, $users] = $this->onlineUsers();
        $user = auth()->user();

        $roleKey = RoleMapper::map($user->role->slug);

        $service = DashboardService::resolve($user);

        $dashboardData = [
            'stats' => $service->getStats($user),
            'summary' => $service->getSummary($user),
            'activities' => $service->getActivities($user),
            'view' => 'dashboard.'.$roleKey,
        ];

        return view($dashboardData['view'], array_merge($dashboardData, [
            'onlineCount' => $count,
            'onlineUsers' => $users,
        ]));
    }

    // ONLINE USERS
    public function online(): JsonResponse
    {
        [$count, $users] = $this->onlineUsers();

        return response()->json([
            'count' => $count,
            'users' => $users->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'last_seen_at' => optional($u->last_seen_at)->toIso8601String(),
            ])->values(),
        ]);
    }

    private function onlineUsers()
    {
        $cutoff = now()->subMinutes(5);

        $users = User::query()
            ->select(['id', 'name', 'last_seen_at', 'role_id'])
            ->with('role:id,slug')
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', $cutoff)
            ->whereHas('role', function ($q) {
                $q->where('slug', '!=', 'developer');
            })
            ->orderByDesc('last_seen_at')
            ->limit(30)
            ->get();

        return [$users->count(), $users];
    }
}
