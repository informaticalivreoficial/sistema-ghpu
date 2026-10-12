<?php

namespace Tests\Feature;

use App\Livewire\Navigation\SideNavigation;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\PreReservaCriada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PreReservaPopupTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Reservation $reserva;

    protected function setUp(): void
    {
        parent::setUp();

        // A sidebar usa @role/User::role — roles precisam existir
        foreach (['employee', 'manager', 'admin', 'super-admin'] as $role) {
            Role::create(['name' => $role]);
        }

        Company::factory()->create(['id' => 18]);

        $this->user = User::factory()->create(['company_id' => 18]);

        $customer = Customer::create([
            'company_id' => 18,
            'name' => 'Cliente Popup',
            'email' => 'popup@teste.com',
            'status' => 'ativo',
        ]);

        $this->reserva = Reservation::create([
            'company_id' => 18,
            'customer_id' => $customer->id,
            'checkin' => '2026-10-15',
            'checkout' => '2026-10-18',
            'adultos' => 2,
            'criancas' => 0,
            'codigo' => 'POPUPTST',
            'status' => 'pendente',
        ]);
    }

    private function createNotification(User $user, \DateTimeInterface $createdAt): void
    {
        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => PreReservaCriada::class,
            'data' => [
                'reservation_id' => $this->reserva->id,
                'message' => 'Nova pré-reserva: Cliente Popup (POPUPTST)',
                'url' => route('reservas.index'),
            ],
            'read_at' => null,
            'created_at' => $createdAt,
        ]);
    }

    public function test_popup_fires_for_new_pre_reserva(): void
    {
        $this->actingAs($this->user);

        // monta o componente (loadedAt = agora) e depois chega a notificação
        $component = Livewire::test(SideNavigation::class);

        $this->createNotification($this->user, now());

        $component
            ->call('checkNewPreReservas')
            ->assertDispatched('swal');
    }

    public function test_popup_fires_only_once_per_notification(): void
    {
        $this->actingAs($this->user);

        $component = Livewire::test(SideNavigation::class);

        $this->createNotification($this->user, now());

        $component
            ->call('checkNewPreReservas')
            ->call('checkNewPreReservas')
            ->assertSet('popupNotifiedIds', fn ($ids) => count($ids) === 1);
    }

    public function test_old_notifications_do_not_popup(): void
    {
        $this->actingAs($this->user);

        // notificação anterior ao carregamento da página: só fica no sino
        $component = Livewire::test(SideNavigation::class);

        $this->createNotification($this->user, now()->subHour());

        $component
            ->call('checkNewPreReservas')
            ->assertNotDispatched('swal');
    }

    public function test_database_notification_payload_has_message_and_url(): void
    {
        $this->user->notify(new PreReservaCriada($this->reserva));

        $notification = $this->user->notifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('Nova pré-reserva: Cliente Popup (POPUPTST)', $notification->data['message']);
        $this->assertSame(route('reservas.index'), $notification->data['url']);
    }
}
