<?php

namespace App\Livewire\Dashboard\Messages;

use Livewire\Component;
use App\Models\Message;
use App\Models\MessageItem;
use App\Models\MessageRead;

class MessageThread extends Component
{
    public Message $message;
    public string $body = '';

    public function mount(Message $message)
    {
        abort_unless(
            $message->company_id === auth()->user()->company_id,
            403
        );

        $this->message = $message;

        // Marca como lidas criando registros (se não existirem)
        $items = MessageItem::where('message_id', $message->id)
            ->where('sender_id', '!=', auth()->id())
            ->pluck('id');

        foreach ($items as $itemId) {
            MessageRead::firstOrCreate([
                'message_item_id' => $itemId,
                'user_id' => auth()->id(),
            ]);
        }
    }

    public function send()
    {
        $this->validate([
            'body' => 'required|string',
        ]);

        $item = MessageItem::create([
            'message_id' => $this->message->id,
            'sender_id'  => auth()->id(),
            'body'       => $this->body,
        ]);

        // Marca como lida para quem enviou
        MessageRead::create([
            'message_item_id' => $item->id,
            'user_id' => auth()->id(),
        ]);

        $this->body = '';
        $this->message->touch();
    }

    public function refreshMessages()
    {
        $this->dispatch('messages-updated'); // dispara scroll no frontend
    }

    public function render()
    {
        return view('livewire.dashboard.messages.message-thread', [
            'messages' => MessageItem::where('message_id', $this->message->id)
            ->with('sender:id,name,avatar,gender')
            ->orderBy('created_at')
            ->get()
        ]);
    }
}