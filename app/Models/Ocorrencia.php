<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias';

    protected $fillable = [
        'user_id',
        'destinatario',
        'type',
        'company_id',
        'title',
        'content',
        'status',
        'views',
        'update_user_id',
        'form',
    ];

    protected $casts = [
        'form' => 'array',
    ];

    protected static function booted()
    {
        static::addGlobalScope('company', function ($query) {
            $user = auth()->user();

            if (!$user || $user->isSuperAdmin() || $user->isAdmin()) {
                return;
            }

            $query->where('company_id', $user->company_id);
        });
    }

    /**
     * Relacionamentos
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function updateUser()
    {
        return $this->belongsTo(User::class, 'update_user_id');
    }

    public function canBeEditedBy(User $user): bool
    {
        // Super-admins e Admins sempre podem
        if ($user->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        // Managers só podem editar ocorrências da própria empresa
        if ($user->hasRole('manager')) {
            return $this->company_id === $user->company_id;
        }

        // Usuários comuns só podem editar as próprias ocorrências
        if ($user->id !== $this->user_id) {
            return false;
        }

        // Colaboradores só podem editar até 6 horas após a criação
        return $this->created_at->diffInMinutes(now()) < 360;
    }

    public function canBeDeletedBy(User $user): bool
    {
        // Super-admin e admin sempre podem excluir
        if ($user->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        // Manager só exclui da própria empresa
        if ($user->hasRole('manager')) {
            return $this->company_id === $user->company_id;
        }

        // 🔒 Employee NUNCA pode excluir (regra de negócio — difere da edição)
        return false;
    }
}
