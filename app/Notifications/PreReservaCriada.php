<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PreReservaCriada extends Notification
{
    use Queueable;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $cliente = $this->reservation->customer;

        return [
            'reservation_id' => $this->reservation->id,
            'message' => 'Nova pré-reserva: '.($cliente->name ?? '—').' ('.$this->reservation->codigo.')',

            'company_id' => $this->reservation->company_id,
            'codigo' => $this->reservation->codigo,
            'cliente' => $cliente->name ?? '',

            'url' => route('reservas.index'),
            'created_at' => now()->format('d/m/Y H:i'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'message' => 'Nova pré-reserva recebida via API',
        ];
    }
}
