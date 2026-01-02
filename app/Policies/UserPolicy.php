<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isManager();
    }

    public function view(User $user, User $model): bool
    {
        // SuperAdmin e Admin veem todos
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        // Manager e Employee só veem da própria empresa
        return
            $user->company_id !== null &&
            $user->company_id === $model->company_id;
    }

    public function update(User $user, User $model): bool
    {
        // 🚀 Super Admin pode tudo
        if ($user->isSuperAdmin()) {
            return true;
        }

        // 🛡 Admin pode todos, menos Super Admin e ele mesmo
        if ($user->isAdmin()) {
            return
                ! $model->isSuperAdmin();
        }

        // 🧑‍💼 Manager → apenas employees da mesma empresa
        if ($user->isManager()) {
            return
                $model->isEmployee()
                && $user->company_id === $model->company_id;
        }

        // 👷 Employee → somente o próprio perfil
        if ($user->isEmployee()) {
            return $user->id === $model->id;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        // 🚀 SuperAdmin pode deletar qualquer um (exceto ele mesmo)
        if ($user->isSuperAdmin()) {
            return $user->id !== $model->id;
        }

        // 🛡 Admin deleta qualquer um EXCETO SuperAdmin e ele mesmo
        if ($user->isAdmin()) {
            return
                ! $model->isSuperAdmin()
                && $user->id !== $model->id;
        }

        // 🧑‍💼 Manager deleta apenas colaboradores da mesma empresa
        if ($user->isManager()) {
            return
                $model->isEmployee()
                && $user->company_id === $model->company_id;
        }

        return false;
    }
}
