<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.app')]
class ChangePassword extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $config;

    public function mount()
    {
        $this->config = \App\Models\Config::first();
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Informe a senha atual.',
            'password.required' => 'Informe a nova senha.',
            'password.confirmed' => 'As senhas não conferem.',
        ]);

        $user = auth()->user();

        // Verifica se a senha atual está correta
        if (!Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['A senha atual está incorreta.'],
            ]);
        }

        // Atualiza a senha
        $user->update([
            'password' => Hash::make($this->password)
        ]);

        // Limpa os campos
        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('toastr', [
            'type' => 'success',
            'message' => 'Sua senha foi alterada com sucesso!',
            'title' => 'Senha alterada'
        ]);
    }

    #[Title('Alterar Senha')]
    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
