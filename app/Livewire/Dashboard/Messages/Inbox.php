<?php

namespace App\Livewire\Dashboard\Messages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Message;
use App\Models\MessageRead;

#[Layout('components.layouts.app')]
#[Title('Mensagens')]
class Inbox extends Component
{
    public $threads = [];
    public ?int $activeThreadId = null;

    public function mount()
    {
        $user = auth()->user();

        $this->threads = Message::query()
            ->forCompany($user->company_id)
            ->forUser($user->id)
            ->with([
                'lastItem.user:id,name,avatar,gender',
                'userOne:id,name,avatar,gender',
                'userTwo:id,name,avatar,gender',
            ])
            ->latest('updated_at')
            ->get();
    }

    public function openThread(int $threadId)
    {
        $this->activeThreadId = $threadId;
    }

    public function render()
    {
        return view('livewire.dashboard.messages.inbox');
    }
}