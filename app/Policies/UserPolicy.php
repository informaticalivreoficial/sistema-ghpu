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

    public function view(User $user, User $model)
    {
        // SuperAdmin e Admin veem TODOS
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        // Manager e Employee só veem da própria empresa
        return $user->company_id === $model->company_id;
    }

    public function update(User $user, User $model)
    {
        // SuperAdmin atualiza qualquer um
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin atualiza qualquer um EXCETO SuperAdmin
        if ($user->isAdmin()) {
            return !$model->isSuperAdmin();
        }

        // Manager atualiza apenas colaboradores da mesma empresa
        if ($user->isManager()) {
            return $user->company_id === $model->company_id 
                && $model->isEmployee();
        }

        // Employee só edita ele mesmo
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        // SuperAdmin deleta qualquer um (exceto ele mesmo)
        if ($user->isSuperAdmin()) {
            return $user->id !== $model->id;
        }

        // Admin deleta qualquer um EXCETO SuperAdmin
        if ($user->isAdmin()) {
            return !$model->isSuperAdmin();
        }

        // Manager deleta apenas colaboradores da mesma empresa
        if ($user->isManager()) {
            return $user->company_id === $model->company_id 
                && $model->isEmployee();
        }

        return false;
    }
}
