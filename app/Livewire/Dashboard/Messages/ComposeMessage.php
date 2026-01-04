<?php

namespace App\Livewire\Dashboard\Messages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageItem;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class ComposeMessage extends Component
{
    public string $subject = '';
    public string $body = '';
    public array $recipients = [];
    public $availableUsers;

    public function mount()
    {
        $this->loadUsers();
    }

    protected function loadUsers()
    {
        $user = auth()->user();

        $this->availableUsers = User::where('company_id', $user->company_id)
            ->where('id', '!=', $user->id)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['employee', 'manager', 'admin']); // filtra roles permitidas
            })
            ->available() // seu escopo existente
            ->orderBy('name')
            ->get();
    }

    public function send()
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
        ]);

        DB::transaction(function () {
            $auth = auth()->user();

            foreach ($this->recipients as $recipientId) {

                $recipient = User::find($recipientId);

                if (! $auth->canMessage($recipient)) {
                    continue;
                }

                // garante ordem fixa
                [$one, $two] = collect([$auth->id, $recipientId])->sort()->values();

                $message = Message::firstOrCreate(
                    [
                        'company_id' => $auth->company_id,
                        'user_one_id' => $one,
                        'user_two_id' => $two,
                    ],
                    ['subject' => $this->subject]
                );

                MessageItem::create([
                    'message_id' => $message->id,
                    'sender_id'  => auth()->id(), // ✅ OBRIGATÓRIO
                    'body'       => $this->body,
                ]);
            }
        });

        $this->dispatch('toastr',
            type: 'success',
            message: 'Mensagem enviada com sucesso!',
            title: 'Sucesso'
        );

        return redirect()->route('messages.inbox');
    }

    #[Title('Nova Mensagem')]
    public function render()
    {
        return view('livewire.dashboard.messages.compose-message');
    }
}
