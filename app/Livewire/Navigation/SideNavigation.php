<?php

namespace App\Livewire\Navigation;

use App\Models\Config;
use App\Models\Ocorrencia;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class SideNavigation extends Component
{
    public function render()
    {
        // Conta quantos têm cada role
        $clientCount = User::role('employee')->count();   // Colaborador
        $timeCount   = User::role(['manager', 'admin'])->count(); // Gerente, admin e super

        $postsCount = Post::count();
        $ocorrenciaCount = Ocorrencia::count();
        // Manifest count
        //$manifestCount = Manifest::where(function($query) {
        //    $query->where('section', 'conferencia')
        //        ->orWhereNull('section');
        //})->count();
        //$manifestComercialCount = Manifest::where('section', 'comercial')->count();
        //$manifestFinanceCount = Manifest::where('section', 'financeiro')->count();
        //$manifestFinishCount = Manifest::where([
        //    ['status', '=', 'entregue'],
        //    ['section', '=', 'finalizado'],
        //])->count();
        $config = Config::first();

        return view('livewire.navigation.side-navigation',[
            'clientCount' => $clientCount,
            'timeCount' => $timeCount,   
            'postsCount' => $postsCount, 
            'ocorrenciaCount' => $ocorrenciaCount,
            'config' => $config,
        ]);
    }
}
