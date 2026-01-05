<?php

namespace App\Livewire\Components;

use App\Models\MessageItem;
use Livewire\Component;

class GlobalMessageNotifier extends Component
{
    public int $unreadCount = 0;

    public function mount()
    {
        $this->unreadCount = $this->calculateUnreadCount();
    }

    public function poll()
    {
        $newCount = $this->calculateUnreadCount();

        if ($newCount > $this->unreadCount) {
            $this->dispatch('global-new-message');
        }

        $this->unreadCount = $newCount;
    }

    protected function calculateUnreadCount(): int
    {
        $user = auth()->user();

        return MessageItem::whereHas('message', function ($q) use ($user) {
                $q->forCompany($user->company_id)
                  ->forUser($user->id);
            })
            ->where('sender_id', '!=', $user->id)
            ->whereDoesntHave('reads', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.components.global-message-notifier');
    }
}
