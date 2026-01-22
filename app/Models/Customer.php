<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_id', 'email','cpf','rg','birthday',
        'whatsapp','zipcode','street','neighborhood','number',
        'city','estate', 'complement', 'status', 'information',
    ];
}
