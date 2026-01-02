<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.guest')]
class ForgotPassword extends Component
{
    public $email = '';
    public $config;
    public $emailSent = false;

    public function mount()
    {
        $this->config = \App\Models\Config::first();
    }

    public function sendResetLink()
    {
        $this->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'email.exists' => 'E-mail não encontrado em nossa base de dados.',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSent = true;
            
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Link de recuperação enviado para seu e-mail!',
                'title' => 'E-mail enviado'
            ]);

            return;
        }

        throw ValidationException::withMessages([
            'email' => ['Não foi possível enviar o link de recuperação. Tente novamente.'],
        ]);
    }

    #[Title('Esqueci minha senha')]
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
