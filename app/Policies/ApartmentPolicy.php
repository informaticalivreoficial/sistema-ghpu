<?php

namespace App\Policies;

use App\Models\Apartment;
use App\Models\Apartments;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApartmentPolicy
{
    public function before(User $user): bool|null
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager()) {
            return true;
        }

        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin()
            || $user->isAdmin()
            || $user->isManager()
            || $user->isEmployee();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Apartments $apartment): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true; // todas empresas
        }

        return $user->company_id === $apartment->company_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin()
            || $user->isAdmin()
            || $user->isManager();
    }

    public function update(User $user, Apartments $apartment): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        return $user->isManager()
            && $user->company_id === $apartment->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Apartments $apartment): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }    
    
}
