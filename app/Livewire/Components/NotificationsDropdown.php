<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsDropdown extends Component
{
    public $unreadNotificationsCount = 0;
    public $notifications = [];

    // Atualiza o dropdown a cada 30 segundos
    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (!Auth::check()) return;

        $user = Auth::user();

        // Pega todas as notificações não lidas
        $notifications = $user->unreadNotifications;

        // Se for gerente, filtra apenas notificações da própria empresa
        if ($user->isManager()) {
            $notifications = $notifications->filter(function ($n) use ($user) {
                return isset($n->data['company_id']) && $n->data['company_id'] == $user->company_id;
            });
        }

        // Atualiza propriedades do componente
        $this->unreadNotificationsCount = $notifications->count();
        $this->notifications = $notifications->take(5);
    }

    public function markAsRead($notificationId)
    {
        if (!Auth::check()) return;

        $user = Auth::user();

        $notification = $user->unreadNotifications
            ->when($user->isManager(), fn($coll) => $coll->filter(fn($n) => $n->id === $notificationId && $n->data['company_id'] == $user->company_id))
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Notificação marcada como lida'
            ]);
        }
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) return;

        $user = Auth::user();

        $notifications = $user->unreadNotifications;

        if ($user->isManager()) {
            $notifications = $notifications->filter(fn($n) => isset($n->data['company_id']) && $n->data['company_id'] == $user->company_id);
        }

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $this->loadNotifications();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Todas as notificações foram marcadas como lidas'
        ]);
    }

    public function render()
    {
        return view('livewire.components.notifications-dropdown');
    }
}
