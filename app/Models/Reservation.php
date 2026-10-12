<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'company_id',
        'customer_id',
        'apartment_id',
        'apartamento_texto',
        'checkin',
        'checkout',
        'adultos',
        'criancas',
        'codigo',
        'status',
        'information',
    ];

    protected $casts = [
        'checkin' => 'date:Y-m-d',
        'checkout' => 'date:Y-m-d',
        'adultos' => 'integer',
        'criancas' => 'integer',
    ];

    /** Status possíveis de uma reserva */
    public const STATUSES = [
        'pendente' => 'Pendente',
        'confirmada' => 'Confirmada',
        'hospedada' => 'Hospedada',
        'finalizada' => 'Finalizada',
        'cancelada' => 'Cancelada',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartments::class);
    }
}
