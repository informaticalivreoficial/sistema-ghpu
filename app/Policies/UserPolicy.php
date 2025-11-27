<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->isManager();
    }

    public function view(User $user, User $model)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // gerente ou colaborador só veem usuários da própria empresa
        return $user->company_id === $model->company_id;
    }

    public function update(User $user, User $model)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // gerente só atualiza colaboradores da mesma empresa
        if ($user->isManager()) {
            return $user->company_id === $model->company_id;
        }

        // colaborador só edita ele mesmo
        return $user->id === $model->id;
    }
}
