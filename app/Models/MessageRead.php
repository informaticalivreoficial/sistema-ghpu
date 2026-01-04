<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_item_id',
        'user_id',
        'read_at',
    ];

    public function item()
    {
        return $this->belongsTo(MessageItem::class, 'message_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
