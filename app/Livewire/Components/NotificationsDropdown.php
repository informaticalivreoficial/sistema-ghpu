<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsDropdown extends Component
{
    public $unreadNotificationsCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (!Auth::check()) return;
        
        $user = Auth::user();
        
        // Filtra notificações baseado na role
        $query = $user->unreadNotifications();
        
        // Se for gerente, mostra apenas da própria empresa
        if ($user->isManager()) {
            $query->whereJsonContains('data->company_id', $user->company_id);
        }
        // Super-admin e Admin veem tudo (não precisa filtrar)
        
        $this->unreadNotificationsCount = $query->count();
        $this->notifications = $query->take(5)->get();
    }

    public function markAsRead($notificationId)
    {
        if (Auth::check()) {
            $notification = Auth::user()
                ->unreadNotifications()
                ->where('id', $notificationId)
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
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->loadNotifications();
            
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Todas as notificações foram marcadas como lidas'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.components.notifications-dropdown');
    }
}
