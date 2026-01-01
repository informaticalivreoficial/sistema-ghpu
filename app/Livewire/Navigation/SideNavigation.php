<?php

namespace App\Livewire\Navigation;

use App\Models\Config;
use App\Models\Ocorrencia;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class SideNavigation extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Se o usuário for gerente, filtra apenas colaboradores da mesma empresa
        if ($user->isManager()) {
            $colaboradoresCount = User::role('employee')->where('company_id', $user->company_id)->count();
        } else {
            // Se for admin ou super, conta todos
            $colaboradoresCount = User::role('employee')->count();
        }

        $timeCount   = User::role(['manager', 'admin'])->count(); // Gerente, admin e super

        $postsCount = Post::count();
        $ocorrenciaCount = Ocorrencia::count();
        
        $config = Config::first();

        return view('livewire.navigation.side-navigation',[
            'colaboradoresCount' => $colaboradoresCount,
            'timeCount' => $timeCount,   
            'postsCount' => $postsCount, 
            'ocorrenciaCount' => $ocorrenciaCount,
            'config' => $config,
        ]);
    }
}
