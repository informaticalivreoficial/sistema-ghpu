<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'social_name',
        'alias_name',
        'document_company',
        'document_company_secondary',
        'information',
        'logo',
        'status',
        'zipcode',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'phone',
        'cell_phone',
        'email',
        'whatsapp',
        'telegram',
        'additional_email',
    ];

    /**
     * Relacionamentos
    */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class);
    }

    /**
     * Accerssors and Mutators
    */ 
    public function getlogo()
    {
        if(empty($this->logo) || !Storage::disk()->exists($this->logo)) {
            return asset('theme/images/image.jpg');
        } 
        return Storage::url($this->logo);
    }
}
