<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleStatus;

class AdminPostPolicy
{
    public function adminAccess(User $user): bool
    {
        return $user->role === RoleStatus::ADMIN;
    }
}
