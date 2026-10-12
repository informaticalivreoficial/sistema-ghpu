<?php

namespace App\Livewire\Navigation;

use App\Models\Config;
use App\Models\Message;
use App\Models\Ocorrencia;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\PreReservaCriada;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SideNavigation extends Component
{
    /** Momento em que a página carregou (popups só de pré-reservas novas daqui pra frente) */
    public string $loadedAt = '';

    /** IDs de notificações que já dispararam popup nesta sessão da página */
    public array $popupNotifiedIds = [];

    public function mount(): void
    {
        $this->loadedAt = now()->toDateTimeString();
    }

    /**
     * 🔔 Chamado pelo wire:poll: dispara popup (swal) para cada nova pré-reserva
     * recebida via API desde que a página abriu. Notificações antigas ficam só
     * no sino/notificações — sem encher a tela de popup.
     */
    public function checkNewPreReservas(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $notifications = $user->notifications()
            ->where('type', PreReservaCriada::class)
            ->where('created_at', '>=', $this->loadedAt)
            ->whereNotIn('id', $this->popupNotifiedIds)
            ->orderBy('created_at')
            ->get();

        foreach ($notifications as $notification) {
            $this->popupNotifiedIds[] = $notification->id;

            $this->dispatch('swal', [
                'title' => 'Nova Pré-reserva!',
                'text' => $notification->data['message'] ?? 'Uma nova pré-reserva foi recebida.',
                'icon' => 'info',
            ]);
        }
    }

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

        $timeCount = User::role(['manager', 'admin'])->count(); // Gerente, admin e super

        $postsCount = Post::count();
        $ocorrenciaCount = Ocorrencia::count();
        // Global Scope do tenant: employee/manager contam só a própria empresa
        $reservasCount = Reservation::count();

        // Contagem de mensagens não lidas
        $unreadMessagesCount = Message::query()
            ->forCompany($user->company_id)
            ->forUser($user->id)
            ->whereHas('lastItem', function ($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)
                    ->whereDoesntHave('reads', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->count();

        // Fallback para modelo vazio: se a tabela 'configs' estiver vazia,
        // $config era null e a view morria com "getlogoadmin() on null" → 500
        // em todas as páginas do painel.
        $config = Config::first() ?? new Config;

        return view('livewire.navigation.side-navigation', [
            'colaboradoresCount' => $colaboradoresCount,
            'unreadMessagesCount' => $unreadMessagesCount,
            'timeCount' => $timeCount,
            'postsCount' => $postsCount,
            'ocorrenciaCount' => $ocorrenciaCount,
            'reservasCount' => $reservasCount,
            'config' => $config,
        ]);
    }
}
