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
    public $lastOcorrencias = null;
    public array $lastTurno = [];
    public ?string $lastTurnoDate = null;

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
                ->get();
            return;
        }

        // Manager / Admin / Super
        $query = Ocorrencia::latest()->limit(5)->with('user:id,name,avatar');

        // Manager → apenas da empresa
        if ($user->isManager()) {
            $query->where('company_id', $user->company_id);
        }

        $this->lastOcorrencias = $query->get();


        if ($user->isManager() && $user->company_id === 18) {
            $turno = Ocorrencia::query()
            ->where('company_id', $user->company_id)
            ->where('type', 'passagem-de-turno')
            ->latest('created_at')
            ->first();
        }else{
            $turno = Ocorrencia::query()
            ->where('company_id', $user->company_id)
            ->where('type', 'passagem-de-turno-cavalo')
            ->latest('created_at')
            ->first();
        }
        

        if (! $turno || ! is_array($turno->form)) {
            return;
        }

        $this->lastTurno = [
            'hospedes' => (int) data_get($turno->form, 'turno.hospedes', 0),
            'aptos' => (int) data_get($turno->form, 'turno.apto_ocupados', 0),
            'reservas' => (int) data_get($turno->form, 'turno.reservas', 0),
            'checkouts' => (int) data_get($turno->form, 'turno.checkouts', 0),
            'cartoes' => (int) data_get($turno->form, 'turno.cartoes_emprestados', 0),

            'caixa_dinheiro' => (float) data_get($turno->form, 'turno.caixa_dinheiro', 0),
            'caixa_cartao' => (float) data_get($turno->form, 'turno.caixa_cartoes', 0),
            'caixa_total' => (float) (data_get($turno->form, 'turno.caixa_dinheiro', 0) + data_get($turno->form, 'turno.caixa_cartoes', 0)),
        ];

        $this->lastTurnoDate = $turno->created_at->format('d/m/Y H:i');

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

        $this->lastOcorrencias = $query->get();
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
