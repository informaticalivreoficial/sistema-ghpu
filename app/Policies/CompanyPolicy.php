<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    /**
     * Quem pode criar empresas
     */
    public function create(User $user)
    {
        // Apenas SuperAdmin pode criar empresas
        return $user->isSuperAdmin();
    }

    /**
     * Quem pode ver a lista de empresas
     */
    public function viewAny(User $user)
    {
        // SuperAdmin e Admin podem ver todas as empresas
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Quem pode visualizar uma empresa específica
     */
    public function view(User $user, Company $company)
    {
        // SuperAdmin e Admin veem TODAS as empresas
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        // Manager e Employee só veem a própria empresa
        return $user->company_id === $company->id;
    }

    /**
     * Quem pode editar uma empresa
     */
    public function update(User $user, Company $company)
    {
        // SuperAdmin pode editar TODAS as empresas
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin pode editar TODAS as empresas
        if ($user->isAdmin()) {
            return true;
        }

        // Manager pode editar apenas a própria empresa
        if ($user->isManager()) {
            return $user->company_id === $company->id;
        }

        // Employee NÃO pode editar empresas
        return false;
    }

    /**
     * Quem pode deletar uma empresa
     */
    public function delete(User $user, Company $company)
    {
        // Apenas SuperAdmin pode deletar empresas
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Ninguém mais pode deletar
        return false;
    }
}
