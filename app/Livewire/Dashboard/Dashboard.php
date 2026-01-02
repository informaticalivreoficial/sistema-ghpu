<?php

namespace App\Livewire\Dashboard;

use App\Models\Ocorrencia;
use App\Models\Post;
use Livewire\Component;

class Dashboard extends Component
{
    public array $topposts = [];
    public int $postsCount = 0;
    public int $postsYearCount = 0;
    public array $lastOcorrencias = [];

    public function mount()
    {
        $user = auth()->user();

        // 🧑‍💼 Manager / Admin / Super
        if (! $user->isEmployee()) {
            $this->postsCount = Post::count();
            $this->postsYearCount = Post::whereYear('created_at', now()->year)->count();

            $this->topposts = Post::orderByDesc('views')->limit(5)->get()->toArray();
        }        

        // Colaborador → só vê as dele
        if ($user->isEmployee()) {
            $this->lastOcorrencias = Ocorrencia::where('company_id', $user->company_id)
                ->latest()
                ->limit(5)
                ->with('user:id,name,avatar')
                ->get()
                ->toArray();
            return;
        }

        // Manager / Admin / Super
        $query = Ocorrencia::latest()->limit(5)->with('user:id,name,avatar');

        // Manager → apenas da empresa
        if ($user->isManager()) {
            $query->where('company_id', $user->company_id);
        }

        $this->lastOcorrencias = $query->get()->toArray();
    }

    public function refreshOcorrencias()
    {
        $user = auth()->user();

        // Employees não usam polling
        if ($user->isEmployee()) {
            return;
        }

        $query = Ocorrencia::latest()->limit(5)->with('user:id,name,avatar');

        // Manager → apenas da empresa
        if ($user->isManager()) {
            $query->where('company_id', $user->company_id);
        }

        $this->lastOcorrencias = $query->get()->toArray();
    }

    public function render()
    {
        $user = auth()->user();

        if ($user->isEmployee()) {
            return view('livewire.dashboard.dashboard-employee', [
                'title' => 'Meu Painel - ' . config('app.name'),
            ]);
        }

        return view('livewire.dashboard.dashboard', [
            'title' => 'Painel de Controle - ' . config('app.name'),
        ]);
    }
}
