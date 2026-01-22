<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartments extends Model
{
    use HasFactory;

    protected $table = 'apartments';

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'capacidade_adultos',
        'capacidade_criancas',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'apartment_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'ativo' : 'inativo';
    }

    public function scopeVisibleFor($query, User $user)
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return $query;
        }

        return $query->where('company_id', $user->company_id);
    }
}
