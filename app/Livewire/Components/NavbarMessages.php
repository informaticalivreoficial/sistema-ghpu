<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Message;
use App\Models\MessageItem;
use App\Models\MessageRead;

class NavbarMessages extends Component
{
    public function render()
    {
        $user = auth()->user();

        $conversations = Message::forCompany($user->company_id)
            ->forUser($user->id)
            ->with([
                'userOne:id,name,avatar',
                'userTwo:id,name,avatar',
                'lastItem.sender',
            ])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $unreadCount = MessageItem::whereHas('message', function ($q) use ($user) {
                $q->forCompany($user->company_id)
                ->forUser($user->id);
            })
            ->where('sender_id', '!=', $user->id) // ✅ quem enviou
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        return view('livewire.components.navbar-messages', compact(
            'conversations',
            'unreadCount'
        ));
    }
}
