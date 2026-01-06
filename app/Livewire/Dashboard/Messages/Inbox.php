<?php

namespace App\Livewire\Dashboard\Messages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use App\Models\Message;

#[Layout('components.layouts.app')]
#[Title('Mensagens')]
class Inbox extends Component
{
    public $threads = [];
    public ?int $activeThreadId = null;
    public ?int $deleteThreadId = null;

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
        ->get()
        ->map(fn($thread) => [
            'model' => $thread,
            'id' => $thread->id,
            'lastItem' => $thread->lastItem,
            // ✅ aqui é o que importa
            'hasNewMessages' => $thread->hasNewMessagesFor($user->id),
        ])
        ->toArray();

        // $this->threads = Message::query()
        //     ->forCompany($user->company_id)
        //     ->forUser($user->id)
        //     ->with([
        //         'lastItem.user:id,name,avatar,gender',
        //         'userOne:id,name,avatar,gender',
        //         'userTwo:id,name,avatar,gender',
        //     ])
        //     ->latest('updated_at')
        //     ->get();
    }

    public function openThread(int $threadId)
    {
        $this->activeThreadId = $threadId;

        // Buscar a thread
        $thread = Message::with('items')->find($threadId);

        // Marcar todas as mensagens da thread como lidas para o usuário logado
        foreach ($thread->items as $item) {
            $item->markAsRead(auth()->id());
        }

        // Atualizar threads para remover bolinha verde
        $userId = auth()->id();
        $this->threads = collect($this->threads)->map(function ($t) use ($threadId, $userId) {
            if ($t['id'] === $threadId) {
                $t['hasNewMessages'] = false;
            } else {
                // Atualiza outras threads caso tenham recebido novas mensagens desde a última vez
                $t['hasNewMessages'] = $t['model']->hasNewMessagesFor($userId);
            }
            return $t;
        })->toArray();
    }

    public function refreshThreads()
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
            ->get()
            ->map(fn($thread) => [
                'model' => $thread,
                'id' => $thread->id,
                'lastItem' => $thread->lastItem,
                'hasNewMessages' => $thread->hasNewMessagesFor($user->id),
            ])
            ->toArray();
    }

    public function confirmDelete(int $threadId)
    {
        $this->authorizeDelete();

        $this->deleteThreadId = $threadId;

        $this->dispatch('delete-prompt');
    }

    private function authorizeDelete()
    {
        if (!auth()->user()->canDeleteMessages()) {
            abort(403);
        }
    }

    #[On('goOn-Delete')]
    public function deleteThread()
    {
        $this->authorizeDelete();

        if (!$this->deleteThreadId) {
            return;
        }

        Message::findOrFail($this->deleteThreadId)->delete();

        $this->threads = collect($this->threads)
            ->reject(fn ($thread) => $thread['id'] === $this->deleteThreadId)
            ->values()
            ->toArray();

        $this->deleteThreadId = null;
    }

    public function render()
    {
        return view('livewire.dashboard.messages.inbox');
    }
}