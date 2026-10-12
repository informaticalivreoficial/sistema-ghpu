<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'social_name',
        'alias_name',
        'document_company',
        'document_company_secondary',
        'information',
        'logo',
        'status',
        'zipcode',
        'street',
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
        try {
            // O upload da logo salva no disco 'public' (ver CompanyForm), então a
            // verificação precisa ser no mesmo disco — nunca no disco default.
            if (empty($this->logo) || ! Storage::disk('public')->exists($this->logo)) {
                return asset('theme/images/image.jpg');
            }

            return Storage::disk('public')->url($this->logo);
        } catch (\Throwable $e) {
            // Nunca derruba a página por causa da imagem (ex.: disco default
            // mal configurado no ambiente → chamava Storage::disk() e dava 500).
            return asset('theme/images/image.jpg');
        }
    }

    public function logoPathForPdf(): string
    {
        if ($this->logo && file_exists(storage_path('app/public/'.$this->logo))) {
            return storage_path('app/public/'.$this->logo);
        }

        return public_path('theme/images/image.jpg');
    }
}
