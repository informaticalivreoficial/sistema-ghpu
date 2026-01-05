<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageItem extends Model
{
    protected $fillable = [
        'message_id',
        'sender_id',
        'body',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function isReadFor(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    public function markAsRead(int $userId)
    {
        if (!$this->isReadFor($userId)) {
            $this->reads()->create([
                'user_id' => $userId,
                'read_at' => now(),
            ]);
        }
    }
}
