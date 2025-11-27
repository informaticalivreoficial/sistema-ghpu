<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    public function view(User $user, Ocorrencia $occ)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $occ->company_id;
    }

    public function update(User $user, Ocorrencia $occ)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $occ->company_id;
    }

    public function delete(User $user, Ocorrencia $occ)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->company_id === $occ->company_id;
    }
}
