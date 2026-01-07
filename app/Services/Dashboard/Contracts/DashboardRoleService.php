<?php

namespace App\Services\Dashboard\Contracts;

use App\Models\User;

interface DashboardRoleService
{
    public function getStats(User $user): array;

    public function getSummary(User $user): array;

    public function getActivities(User $user): array;
}
