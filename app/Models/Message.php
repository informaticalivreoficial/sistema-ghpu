<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'company_id',
        'user_one_id',
        'user_two_id',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function items()
    {
        return $this->hasMany(MessageItem::class)->latest();
    }

    public function lastItem()
    {
        return $this->hasOne(MessageItem::class)->latest();
    }

    /* SCOPES */

    public function scopeForCompany($q, $companyId)
    {
        return $q->where('company_id', $companyId);
    }

    public function scopeForUser($q, $userId)
    {
        return $q->where(function ($q) use ($userId) {
            $q->where('user_one_id', $userId)
              ->orWhere('user_two_id', $userId);
        });
    }

    /* HELPERS */

    public function otherUser(int $userId): User
    {
        return $this->user_one_id === $userId
            ? $this->userTwo
            : $this->userOne;
    }

    public function hasNewMessagesFor(int $userId): bool
    {
        $last = $this->lastItem;
        return $last
            && $last->sender_id !== $userId  // veio de outro usuário
            && !$last->isReadFor($userId);   // ainda não foi lida
    }
}
