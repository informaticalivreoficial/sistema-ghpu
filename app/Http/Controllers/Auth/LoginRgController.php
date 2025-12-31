<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginRgController extends Controller
{
    public function show()
    {
        return view('auth.login-rg');
    }

    public function login(Request $request)
    {
        $request->validate([
            'rg' => ['required', 'string'],
        ], [
            'rg.required' => 'Informe o RG.',
        ]);

        $user = User::where('rg', $request->rg)->first();

        if (! $user) {
            return back()
                ->withErrors(['rg' => 'RG não encontrado.'])
                ->withInput();
        }

        Auth::login($user);

        return redirect()->intended('/admin');
    }
}
