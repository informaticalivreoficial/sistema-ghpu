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

        // Últimas conversas (dropdown)
        $conversations = Message::forCompany($user->company_id)
            ->forUser($user->id)
            ->with([
                'userOne:id,name,avatar,gender',
                'userTwo:id,name,avatar,gender',
                'lastItem.sender',
            ])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        // ✅ Contagem correta (threads com mensagem não lida)
        $unreadCount = Message::query()
            ->forCompany($user->company_id)
            ->forUser($user->id)
            ->whereHas('lastItem', function ($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)
                  ->whereDoesntHave('reads', fn ($q) =>
                        $q->where('user_id', $user->id)
                  );
            })
            ->count();

        return view('livewire.components.navbar-messages', compact(
            'conversations',
            'unreadCount'
        ));
    }
}