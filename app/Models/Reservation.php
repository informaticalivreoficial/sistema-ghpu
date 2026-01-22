<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

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

    public function apartment()
    {
        return $this->belongsTo(Apartments::class);
    }
}
