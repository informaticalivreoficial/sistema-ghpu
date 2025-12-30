<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsBell extends Component
{
    public function render()
    {
        return view('livewire.components.notifications-bell');
    }

    public function getUnreadCountProperty()
    {
        return Auth::user()->unreadNotifications()->count();
    }

    public function getNotificationsProperty()
    {
        return Auth::user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get();
    }

    public function markAsRead($id)
    {
        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);
    }
}
