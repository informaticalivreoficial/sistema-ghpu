<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcorrenciaTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'title',
        'content',
        'created_by',
    ];
    
}
