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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
