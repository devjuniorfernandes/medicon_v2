<?php

namespace App\Policies;

use App\Models\Hospital;
use App\Models\User;

class HospitalPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Hospital $hospital): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin() || ($user->isHospital() && $user->id === $hospital->user_id);
    }

    public function delete(User $user, Hospital $hospital): bool
    {
        return $user->isSuperAdmin();
    }

    public function manageGallery(User $user, Hospital $hospital): bool
    {
        return $this->update($user, $hospital);
    }
}
