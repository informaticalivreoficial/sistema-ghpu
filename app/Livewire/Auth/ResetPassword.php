<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.guest')]
class ResetPassword extends Component
{
    public $token;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $config;

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
        $this->config = \App\Models\Config::first();
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'password.required' => 'Informe a nova senha.',
            'password.confirmed' => 'As senhas não conferem.',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Senha redefinida com sucesso! Faça login com sua nova senha.',
                'title' => 'Senha alterada'
            ]);

            return redirect()->route('login');
        }

        throw ValidationException::withMessages([
            'email' => ['Este link de recuperação é inválido ou expirou.'],
        ]);
    }

    #[Title('Redefinir senha')]
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
