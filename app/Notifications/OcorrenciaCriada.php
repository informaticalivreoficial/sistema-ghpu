<?php

namespace App\Notifications;

use App\Models\Ocorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OcorrenciaCriada extends Notification
{
    use Queueable;

    public $ocorrencia;

    public function __construct(Ocorrencia $ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;
    }

    public function via($notifiable)
    {
        return ['database']; // ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ocorrencia_id' => $this->ocorrencia->id,
            'message' => 'Ocorrência: ' . $this->ocorrencia->title,
            'company_id' => $this->ocorrencia->company_id,             
            'type' => $this->ocorrencia->type,
            'user_name' => $this->ocorrencia->user->name ?? 'Desconhecido',
            'url' => route('ocorrencia.pdf', $this->ocorrencia->id),
            'created_at' => $this->ocorrencia->created_at->format('d/m/Y H:i'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Nova ocorrência: ' . $this->ocorrencia->title,
            'ocorrencia_id' => $this->ocorrencia->id,
        ];
    }

    
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject('Nova ocorrência cadastrada')
    //         ->line('Uma nova ocorrência foi criada.')
    //         ->action('Ver ocorrência', url('/admin/ocorrencias/ocorrencia/'.$this->ocorrencia->id));
    // }

   
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
