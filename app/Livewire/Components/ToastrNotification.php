<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class ToastrNotification extends Component
{
    public $message = '';
    public $type = 'info';
    public $title = '';

    #[On('toastr')]
    public function show($type = 'info', $message = '', $title = '')
    {
        $this->type = $type;
        $this->message = $message;
        $this->title = $title;

        $this->dispatch('toastr-show', [
            'type' => $type,
            'message' => $message,
            'title' => $title
        ]);
    }

    public function render()
    {
        return view('livewire.components.toastr-notification');
    }
}
