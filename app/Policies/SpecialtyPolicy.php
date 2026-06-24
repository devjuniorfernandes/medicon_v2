<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;

class SpecialtyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Specialty $specialty): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Specialty $specialty): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, Specialty $specialty): bool
    {
        return $user->isSuperAdmin();
    }
}
