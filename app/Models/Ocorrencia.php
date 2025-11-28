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

            if (!$user || $user->isSuperAdmin()) {
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
}
