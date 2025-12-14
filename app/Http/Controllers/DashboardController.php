<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        [$count, $users] = $this->onlineUsers();

        // template cards placeholders (so your grid has structure)
        $stats = [
            ['label' => 'Kalender Tahunan', 'value' => '—'],
            ['label' => 'Jam Diklat', 'value' => '—'],
            ['label' => 'Status Akun', 'value' => auth()->user()->is_active ? 'Aktif' : 'Nonaktif'],
        ];

        return view('dashboard', [
            'onlineCount' => $count,
            'onlineUsers' => $users,
            'stats'       => $stats,
        ]);
    }

    public function online(): JsonResponse
    {
        [$count, $users] = $this->onlineUsers();

        return response()->json([
            'count' => $count,
            'users' => $users->map(fn($u) => [
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
            ->select(['id', 'name', 'last_seen_at'])
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', $cutoff)
            ->orderByDesc('last_seen_at')
            ->limit(30)
            ->get();

        return [$users->count(), $users];
    }
}
