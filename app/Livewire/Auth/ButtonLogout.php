<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ButtonLogout extends Component
{
    public function logout()
    {
        $user = Auth::user();

        Auth::logout(); // 🔥 Faz logout do usuário
        session()->invalidate(); // Invalida a sessão
        session()->regenerateToken(); // Evita ataques CSRF

        // Redireciona dependendo do perfil
        if ($user && $user->hasRole('employee')) {
            return redirect()->route('web.login'); // rota para colaboradores
        }

        return redirect()->route('login'); // 🔄 Redireciona para a página de login
    }

    public function render()
    {
        return view('livewire.auth.button-logout');
    }
}
