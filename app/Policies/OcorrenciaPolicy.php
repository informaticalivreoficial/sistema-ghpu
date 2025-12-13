<?php

namespace App\Policies;

use App\Models\Ocorrencia;
use App\Models\User;

class OcorrenciaPolicy
{
    public function create(User $user)
    {
        // SuperAdmin, Admin, Manager e Employee podem criar
        return $user->isSuperAdmin() 
            || $user->isAdmin() 
            || $user->isManager() 
            || $user->isEmployee();
    }

    public function view(User $user, Ocorrencia $ocorrencia)
    {
        // SuperAdmin e Admin veem TODAS as ocorrências
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        // Manager e Employee só veem ocorrências da própria empresa
        return $user->company_id === $ocorrencia->company_id;
    }

    public function update(User $user, Ocorrencia $ocorrencia)
    {
        // SuperAdmin pode editar TUDO
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin pode editar TUDO
        if ($user->isAdmin()) {
            return true;
        }

        // Manager e Employee só editam ocorrências da própria empresa
        if ($user->isManager() || $user->isEmployee()) {
            return $user->company_id === $ocorrencia->company_id;
        }

        return false;
    }

    public function delete(User $user, Ocorrencia $ocorrencia)
    {
        // SuperAdmin pode deletar TUDO
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin pode deletar TUDO
        if ($user->isAdmin()) {
            return true;
        }

        // Manager pode deletar apenas da própria empresa
        if ($user->isManager()) {
            return $user->company_id === $ocorrencia->company_id;
        }

        // Employee NÃO pode deletar
        return false;
    }
}
