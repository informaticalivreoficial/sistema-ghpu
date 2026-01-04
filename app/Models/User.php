<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'cargo', 'company_id', 'password', 'remember_token', 
        'gender',
        'cpf',
        'rg',
        'rg_expedition',
        'birthday',
        'naturalness',
        'civil_status',
        'avatar',  
        //Address      
        'zipcode', 'street', 'number', 'complement', 'neighborhood', 'state', 'city',
        //Contact
        'phone', 'cell_phone', 'whatsapp', 'telegram', 'email', 'additional_email',
        //Social
        'facebook', 'twitter', 'instagram', 'linkedin',
        'status',
        'information'
    ];
    
    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        });
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relacionamentos
    */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function conversations()
    {
        return Message::forCompany($this->company_id)
            ->forUser($this->id)
            ->latest('updated_at');
    }

    public function sentMessageItems()
    {
        return $this->hasMany(MessageItem::class, 'sender_id');
    }

    /* ==========================
    | PERMISSÃO
    ========================== */

    public function canMessage(User $user): bool
    {
        return $this->company_id === $user->company_id;
    }

    /* ==========================
    | NÃO LIDAS
    ========================== */

    public function unreadMessagesCount(): int
    {
        return Message::forCompany($this->company_id)
            ->forUser($this->id)
            ->whereHas('items', function ($q) {
                $q->where('sender_id', '!=', $this->id);
            })
            ->count();
    }

    

    /**
     * Scopes
    */
    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Accerssors and Mutators
    */
    public function avatarUrl(): string
    {
        // 1️⃣ Se tem foto do usuário
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::url($this->avatar);
        }

        // 2️⃣ Fallback por gênero
        return match ($this->gender) {
            'feminino', 'F' => asset('theme/images/avatar3.png'),
            'masculino', 'M'   => asset('theme/images/avatar5.png'),
            default       => asset('theme/images/image.jpg'),
        };
    }

    public function setCellPhoneAttribute($value)
    {
        $this->attributes['cell_phone'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    public function getCellPhoneAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return  
            substr($value, 0, 0) . '(' .
            substr($value, 0, 2) . ') ' .
            substr($value, 2, 5) . '-' .
            substr($value, 7, 4) ;
    }

    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    public function getWhatsappAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return  
            substr($value, 0, 0) . '(' .
            substr($value, 0, 2) . ') ' .
            substr($value, 2, 5) . '-' .
            substr($value, 7, 4) ;
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }

    public function getBirthdayAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }

    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = (!empty($value) ? $this->clearField($value) : null);
    }

    public function getZipcodeAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }

    public function setRgAttribute($value)
    {
        $this->attributes['rg'] = (!empty($value) ? $this->clearField($value) : null);
    }
    
    public function getRgAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return
            substr($value, 0, 2) . '.' .
            substr($value, 2, 3) . '.' .
            substr($value, 5, 3) . '-' .
            substr($value, 8, 1);
    }

    private function convertStringToDouble(?string $param)
    {
        if (empty($param)) {
            return null;
        }

        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
    
    private function clearField(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
