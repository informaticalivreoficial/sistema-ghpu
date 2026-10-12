<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_id', 'email', 'cpf', 'rg', 'birthday',
        'whatsapp', 'zipcode', 'street', 'neighborhood', 'number',
        'city', 'estate', 'complement', 'status', 'information',
    ];

    protected $casts = [
        'birthday' => 'date:Y-m-d',
    ];

    /**
     * 🔹 Escopo de tenant (mesmo padrão do Ocorrencia):
     * API (sem login) e admin/super não filtram; demais perfis só veem a própria empresa.
     */
    protected static function booted()
    {
        static::addGlobalScope('company', function ($query) {
            $user = auth()->user();

            if (! $user || $user->isSuperAdmin() || $user->isAdmin()) {
                return;
            }

            $query->where('company_id', $user->company_id);
        });
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /** A API grava 'ativo' e o default da migration é 'active' — normaliza para exibição */
    public function isAtivo(): bool
    {
        return in_array($this->status, ['ativo', 'active'], true);
    }

    /**
     * Link wa.me para o colaborador contatar o cliente.
     * Normaliza: só dígitos + DDI 55 quando for número brasileiro local.
     * Retorna null quando não há número cadastrado.
     */
    public function whatsappLink(): ?string
    {
        if (empty($this->whatsapp)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $this->whatsapp);

        if ($digits === '' || $digits === null) {
            return null;
        }

        // Já é BR internacional (55 + 12 dígitos: DDD + número com 9º dígito
        // ou 55 + DDD + 8 dígitos): mantém. Números locais ganham o DDI 55.
        $isBrazilianIntl = str_starts_with($digits, '55') && strlen($digits) >= 12;

        if (! $isBrazilianIntl && strlen($digits) <= 11) {
            $digits = '55'.$digits;
        }

        return 'https://wa.me/'.$digits;
    }
}
